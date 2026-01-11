<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot follow yourself.'
            ], 400);
        }

        Auth::user()->follow($user);

        return response()->json([
            'success' => true,
            'action' => 'followed',
            'followers_count' => $user->followers_count,
            'message' => 'You are now following ' . $user->fName
        ]);
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        Auth::user()->unfollow($user);

        return response()->json([
            'success' => true,
            'action' => 'unfollowed',
            'followers_count' => $user->followers_count,
            'message' => 'You have unfollowed ' . $user->fName
        ]);
    }

    /**
     * Toggle follow status
     */
    public function toggleFollow(User $user)
    {
        $isFollowing = Auth::user()->toggleFollow($user);

        return response()->json([
            'success' => true,
            'action' => $isFollowing ? 'followed' : 'unfollowed',
            'followers_count' => $user->followers_count,
            'is_following' => $isFollowing,
            'message' => $isFollowing 
                ? 'You are now following ' . $user->fName 
                : 'You have unfollowed ' . $user->fName
        ]);
    }

    /**
     * Get user's followers
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
                         ->withCount(['posts', 'followers', 'followings'])
                         ->paginate(20);

        return response()->json([
            'success' => true,
            'followers' => $followers,
            'total' => $user->followers_count
        ]);
    }

    /**
     * Get users that user is following
     */
    public function following(User $user)
    {
        $following = $user->followings()
                         ->withCount(['posts', 'followers', 'followings'])
                         ->paginate(20);

        return response()->json([
            'success' => true,
            'following' => $following,
            'total' => $user->following_count
        ]);
    }

    /**
     * Check if current user follows a user
     */
    public function check(User $user)
    {
        $isFollowing = Auth::check() ? Auth::user()->isFollowing($user) : false;

        return response()->json([
            'success' => true,
            'is_following' => $isFollowing,
            'followers_count' => $user->followers_count,
            'following_count' => $user->following_count
        ]);
    }
}