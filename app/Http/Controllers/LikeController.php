<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * Like a post.
     */
    public function like(Post $post)
    {
        $user = Auth::user();
        
        // Check if already liked
        $existingLike = Like::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();
        
        if ($existingLike) {
            // Unlike the post
            $existingLike->delete();
            $post->decrementLikesCount();
            
            return response()->json([
                'success' => true,
                'action' => 'unliked',
                'likes_count' => $post->fresh()->likes_count,
                'message' => 'Post unliked successfully'
            ]);
        }
        
        // Like the post
        $like = new Like();
        $like->post_id = $post->id;
        $like->user_id = $user->id;
        $like->save();
        
        $post->incrementLikesCount();
        
        return response()->json([
            'success' => true,
            'action' => 'liked',
            'likes_count' => $post->fresh()->likes_count,
            'message' => 'Post liked successfully'
        ]);
    }
    
    /**
     * Get users who liked a post.
     */
    public function getLikers(Post $post)
    {
        $likers = $post->likes()
            ->with('user')
            ->paginate(20);
            
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('test.posts.partials.likers', compact('likers'))->render(),
                'has_more' => $likers->hasMorePages()
            ]);
        }
        
        return view('test.posts.likers', compact('post', 'likers'));
    }
}