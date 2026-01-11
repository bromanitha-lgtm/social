<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Live search for users and posts
     */
    public function liveSearch(Request $request)
    {
        Log::info('Live search called', ['query' => $request->get('q')]);
        
        $query = $request->get('q', '');
        
        // If query is less than 2 characters, return empty results
        if (empty($query) || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'query' => $query,
                'users' => [],
                'posts' => [],
                'users_count' => 0,
                'posts_count' => 0,
                'total_results' => 0,
                'message' => 'Type at least 2 characters'
            ]);
        }
        
        try {
            $users = [];
            $posts = [];
            
            // Search Users
            try {
                $userResults = User::where('fName', 'like', "%{$query}%")
                    ->orWhere('lName', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->take(5)
                    ->get();
                
                foreach ($userResults as $user) {
                    $users[] = [
                        'id' => $user->id,
                        'name' => $user->fName . ' ' . $user->lName,
                        'email' => $user->email,
                        'avatar_url' => $this->getAvatarUrl($user),
                        'posts_count' => $user->posts()->count(),
                        'followers_count' => $user->followers()->count(),
                        'following_count' => $user->followings()->count(),
                        'type' => 'user',
                        'url' => url('/profile/' . $user->id)
                    ];
                }
            } catch (\Exception $e) {
                Log::error('User search error: ' . $e->getMessage());
            }
            
            // Search Posts
            try {
                $postResults = Post::with('user')
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->where('status', 'published')
                    ->latest()
                    ->take(5)
                    ->get();
                
                foreach ($postResults as $post) {
                    $posts[] = [
                        'id' => $post->id,
                        'title' => $post->title,
                        'excerpt' => $this->getExcerpt($post->content),
                        'user_name' => $post->user ? ($post->user->fName . ' ' . $post->user->lName) : 'Unknown',
                        'user_avatar' => $post->user ? $this->getAvatarUrl($post->user) : $this->getDefaultAvatar(),
                        'likes_count' => $post->likes_count ?? 0,
                        'comments_count' => $post->comments_count ?? 0,
                        'views' => $post->views ?? 0,
                        'type' => 'post',
                        'url' => url('/posts/' . $post->id)
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Post search error: ' . $e->getMessage());
            }
            
            return response()->json([
                'success' => true,
                'query' => $query,
                'users' => $users,
                'posts' => $posts,
                'users_count' => count($users),
                'posts_count' => count($posts),
                'total_results' => count($users) + count($posts),
                'message' => 'Search completed successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Live search fatal error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Search failed. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
    
    /**
     * Global search for users and posts
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query || strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'users' => [],
                'posts' => [],
                'message' => 'Please enter at least 2 characters'
            ]);
        }
        
        // Search users
        $users = User::where(function($q) use ($query) {
                $q->where('fName', 'like', "%{$query}%")
                  ->orWhere('lName', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->withCount(['posts', 'followers', 'followings'])
            ->limit(10)
            ->get();
        
        // Search posts
        $posts = Post::with(['user'])
                    ->withCount(['likes', 'comments'])
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->where('status', 'published')
                    ->latest()
                    ->limit(10)
                    ->get();
        
        return response()->json([
            'success' => true,
            'query' => $query,
            'users' => $users,
            'posts' => $posts,
            'users_count' => $users->count(),
            'posts_count' => $posts->count()
        ]);
    }
    
    /**
     * Search only users
     */
    public function users(Request $request)
    {
        $query = $request->get('q');
        
        $users = User::where(function($q) use ($query) {
                $q->where('fName', 'like', "%{$query}%")
                  ->orWhere('lName', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->withCount(['posts', 'followers', 'followings'])
            ->paginate(20);
        
        return response()->json([
            'success' => true,
            'users' => $users,
            'total' => $users->total()
        ]);
    }
    
    /**
     * Search only posts
     */
    public function posts(Request $request)
    {
        $query = $request->get('q');
        
        $posts = Post::with(['user'])
                    ->withCount(['likes', 'comments'])
                    ->where(function($q) use ($query) {
                        $q->where('title', 'like', "%{$query}%")
                          ->orWhere('content', 'like', "%{$query}%");
                    })
                    ->where('status', 'published')
                    ->latest()
                    ->paginate(20);
        
        return response()->json([
            'success' => true,
            'posts' => $posts,
            'total' => $posts->total()
        ]);
    }
    
    /**
     * Get avatar URL
     */
    private function getAvatarUrl($user)
    {
        if (!$user) {
            return $this->getDefaultAvatar();
        }
        
        // Check if user has avatar property
        if (property_exists($user, 'avatar') && $user->avatar) {
            return asset('storage/' . $user->avatar);
        }
        
        // Check if user has profile_pic property
        if (property_exists($user, 'profile_pic') && $user->profile_pic) {
            return asset('storage/' . $user->profile_pic);
        }
        
        // Use email for gravatar
        $email = $user->email ?? 'user@example.com';
        $hash = md5(strtolower(trim($email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=60";
    }
    
    /**
     * Get default avatar
     */
    private function getDefaultAvatar()
    {
        return 'https://ui-avatars.com/api/?name=User&background=random&color=fff&size=60';
    }
    
    /**
     * Get excerpt from content
     */
    private function getExcerpt($content, $length = 100)
    {
        if (empty($content)) {
            return 'No content available';
        }
        
        $text = strip_tags($content);
        $text = str_replace(["\r", "\n"], ' ', $text);
        $text = trim($text);
        
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . '...';
    }
    
    /**
     * Advanced search with filters
     */
    public function advanced(Request $request)
    {
        $query = $request->get('q');
        $type = $request->get('type', 'all');
        $sort = $request->get('sort', 'relevance');
        $time = $request->get('time', 'all');
        
        $results = [];
        
        if ($type === 'all' || $type === 'users') {
            $userQuery = User::where(function($q) use ($query) {
                    $q->where('fName', 'like', "%{$query}%")
                      ->orWhere('lName', 'like', "%{$query}%")
                      ->orWhere('email', 'like', "%{$query}%");
                })
                ->withCount(['posts', 'followers', 'followings']);
            
            if ($time !== 'all') {
                $date = $this->getDateFromTimeFilter($time);
                $userQuery->where('created_at', '>=', $date);
            }
            
            switch ($sort) {
                case 'newest':
                    $userQuery->latest();
                    break;
                case 'popular':
                    $userQuery->orderBy('followers_count', 'desc');
                    break;
                default:
                    $userQuery->latest();
                    break;
            }
            
            $results['users'] = $userQuery->paginate(15, ['*'], 'users_page');
        }
        
        if ($type === 'all' || $type === 'posts') {
            $postQuery = Post::with(['user'])
                        ->withCount(['likes', 'comments'])
                        ->where(function($q) use ($query) {
                            $q->where('title', 'like', "%{$query}%")
                              ->orWhere('content', 'like', "%{$query}%");
                        })
                        ->where('status', 'published');
            
            if ($time !== 'all') {
                $date = $this->getDateFromTimeFilter($time);
                $postQuery->where('created_at', '>=', $date);
            }
            
            switch ($sort) {
                case 'newest':
                    $postQuery->latest();
                    break;
                case 'popular':
                    $postQuery->orderBy('likes_count', 'desc')
                              ->orderBy('views', 'desc');
                    break;
                default:
                    $postQuery->latest();
                    break;
            }
            
            $results['posts'] = $postQuery->paginate(15, ['*'], 'posts_page');
        }
        
        return response()->json([
            'success' => true,
            'query' => $query,
            'filters' => [
                'type' => $type,
                'sort' => $sort,
                'time' => $time
            ],
            'results' => $results
        ]);
    }
    
    /**
     * Get date from time filter string
     */
    private function getDateFromTimeFilter($time)
    {
        switch ($time) {
            case 'day':
                return now()->subDay();
            case 'week':
                return now()->subWeek();
            case 'month':
                return now()->subMonth();
            case 'year':
                return now()->subYear();
            default:
                return now()->subYears(100);
        }
    }
}