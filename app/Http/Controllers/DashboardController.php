<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Comment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Get dashboard data
     */
    public function getDashboardData(Request $request)
    {
        $user = Auth::user();
        
        // Real data calculations
        $monthlyPosts = $user->posts()->whereMonth('created_at', now()->month)->count();
        $monthlyLikes = $user->posts()->withCount('likes')->get()->sum('likes_count');
        $monthlyViews = $user->posts()->sum('views');
        
        $totalLikes = $user->posts()->withCount('likes')->get()->sum('likes_count');
        $totalViews = $user->posts()->sum('views');
        $totalComments = $user->posts()->withCount('comments')->get()->sum('comments_count');
        $postCount = $user->posts()->count();
        $avgLikes = $postCount > 0 ? round($totalLikes / $postCount, 1) : 0;
        
        return response()->json([
            'success' => true,
            'data' => [
                'monthly_posts' => $monthlyPosts,
                'monthly_likes' => $monthlyLikes,
                'monthly_views' => $monthlyViews,
                'total_likes' => $totalLikes,
                'total_views' => $totalViews,
                'total_comments' => $totalComments,
                'avg_likes' => $avgLikes,
                'posts_count' => $postCount,
                'followers_count' => $user->followers_count,
                'following_count' => $user->following_count,
                'comments_count' => $user->comments()->count()
            ]
        ]);
    }
    
    /**
     * Get recent activities
     */
    public function getRecentActivities(Request $request)
    {
        $user = Auth::user();
        $activities = [];
        
        // Recent likes on user's posts
        $recentLikes = Like::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['user', 'post'])
          ->latest()
          ->take(5)
          ->get();
        
        foreach ($recentLikes as $like) {
            $activities[] = [
                'type' => 'like',
                'user_name' => $like->user->fName . ' ' . $like->user->lName,
                'avatar' => $like->user->small_avatar_url,
                'action' => 'liked your post "' . Str::limit($like->post->title, 30) . '"',
                'time' => $like->created_at->diffForHumans()
            ];
        }
        
        // Recent comments on user's posts
        $recentComments = Comment::whereHas('post', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with(['user', 'post'])
          ->latest()
          ->take(5)
          ->get();
        
        foreach ($recentComments as $comment) {
            $activities[] = [
                'type' => 'comment',
                'user_name' => $comment->user->fName . ' ' . $comment->user->lName,
                'avatar' => $comment->user->small_avatar_url,
                'action' => 'commented on your post "' . Str::limit($comment->post->title, 30) . '"',
                'time' => $comment->created_at->diffForHumans()
            ];
        }
        
        // Recent followers
        $recentFollowers = $user->followers()->latest()->take(5)->get();
        foreach ($recentFollowers as $follower) {
            $activities[] = [
                'type' => 'follow',
                'user_name' => $follower->fName . ' ' . $follower->lName,
                'avatar' => $follower->small_avatar_url,
                'action' => 'started following you',
                'time' => $follower->pivot->created_at->diffForHumans()
            ];
        }
        
        // Sort by time and limit to 10
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return response()->json([
            'success' => true,
            'activities' => array_slice($activities, 0, 10)
        ]);
    }
    
    /**
     * Get engagement data for chart
     */
    public function getEngagementData(Request $request)
    {
        $period = $request->get('period', 'month');
        $user = Auth::user();
        
        $data = [
            'labels' => [],
            'likes' => [],
            'views' => []
        ];
        
        if ($period === 'week') {
            // Last 7 days
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateLabel = $date->format('D');
                
                $likes = $user->posts()->whereDate('created_at', $date)->withCount('likes')->get()->sum('likes_count');
                $views = $user->posts()->whereDate('created_at', $date)->sum('views');
                
                $data['labels'][] = $dateLabel;
                $data['likes'][] = $likes;
                $data['views'][] = $views;
            }
        } elseif ($period === 'month') {
            // Last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateLabel = $date->format('M d');
                
                $likes = $user->posts()->whereDate('created_at', $date)->withCount('likes')->get()->sum('likes_count');
                $views = $user->posts()->whereDate('created_at', $date)->sum('views');
                
                $data['labels'][] = $dateLabel;
                $data['likes'][] = $likes;
                $data['views'][] = $views;
            }
        }
        
        return response()->json([
            'success' => true,
            'period' => $period,
            'data' => $data
        ]);
    }
    
    /**
     * Get my activity
     */
    public function getMyActivity(Request $request)
    {
        $user = Auth::user();
        $activities = [];
        
        // User's recent posts
        $recentPosts = $user->posts()->latest()->take(5)->get();
        foreach ($recentPosts as $post) {
            $activities[] = [
                'type' => 'post',
                'text' => 'You created a new post: "' . Str::limit($post->title, 40) . '"',
                'time' => $post->created_at->diffForHumans()
            ];
        }
        
        // User's recent comments
        $recentComments = $user->comments()->with('post')->latest()->take(5)->get();
        foreach ($recentComments as $comment) {
            $activities[] = [
                'type' => 'comment',
                'text' => 'You commented on "' . Str::limit($comment->post->title, 40) . '"',
                'time' => $comment->created_at->diffForHumans()
            ];
        }
        
        // User's recent likes
        $recentLikes = $user->likes()->with('post')->latest()->take(5)->get();
        foreach ($recentLikes as $like) {
            $activities[] = [
                'type' => 'like',
                'text' => 'You liked "' . Str::limit($like->post->title, 40) . '"',
                'time' => $like->created_at->diffForHumans()
            ];
        }
        
        // Sort by time
        usort($activities, function($a, $b) {
            return strtotime($b['time']) - strtotime($a['time']);
        });
        
        return response()->json([
            'success' => true,
            'activities' => array_slice($activities, 0, 10)
        ]);
    }
}