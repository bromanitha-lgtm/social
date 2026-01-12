<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class PostController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Apply auth middleware to all methods except show, search, popular, and apiIndex
        $this->middleware('auth')->except([
            'show', 'search', 'popular', 'apiIndex', 
            'getFilteredPosts', 'getTrendingPosts'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Post::with(['user'])
                    ->withCount(['likes', 'comments']);
        
        // Check if status column exists before filtering
        if (Schema::hasColumn('posts', 'status')) {
            $query->where('status', 'published');
        }
        
        // Apply filters if any
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'popular':
                    $query->orderBy('likes_count', 'desc')
                          ->orderBy('views', 'desc');
                    break;
                case 'trending':
                    $query->where('created_at', '>=', now()->subDays(7))
                          ->orderBy('likes_count', 'desc')
                          ->orderBy('views', 'desc');
                    break;
                case 'drafts':
                    if (auth()->check()) {
                        $query->where('user_id', auth()->id())
                              ->where('status', 'draft');
                    }
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }
        
        $posts = $query->paginate(12);
        
        // AJAX request එකක් නම් JSON ලෙස return කරන්න
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
                'has_more_pages' => $posts->hasMorePages(),
                'total' => $posts->total()
            ]);
        }
        
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'content' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|in:draft,published',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status ?? 'published',
        ];

        // Add published_at only if column exists
        if (Schema::hasColumn('posts', 'published_at')) {
            $data['published_at'] = $request->published_at ?? now();
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post = Post::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'redirect' => route('posts.show', $post)
            ]);
        }

        return redirect()->route('posts.show', $post)
                        ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        // Increment views
        $post->increment('views');
        
        // Load relationships
        $post->load(['user', 'comments.user', 'likes.user']);
        
        // Check if user liked the post
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $post->likes()->where('user_id', Auth::id())->exists();
        }

        // Get related posts
        $relatedPosts = Post::where('user_id', $post->user_id)
                          ->where('id', '!=', $post->id)
                          ->withCount(['likes', 'comments'])
                          ->latest()
                          ->limit(4)
                          ->get();

        return view('posts.show', compact('post', 'isLiked', 'relatedPosts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Check if user is authorized to edit
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check authorization
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|min:3',
            'content' => 'required|string|min:10',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'nullable|in:draft,published',
            'remove_image' => 'nullable|boolean',
            'published_at' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'title' => $request->title,
            'content' => $request->content,
        ];

        // Add status only if column exists
        if (Schema::hasColumn('posts', 'status')) {
            $data['status'] = $request->status ?? $post->status;
        }

        // Add published_at only if column exists
        if (Schema::hasColumn('posts', 'published_at') && $request->has('published_at')) {
            $data['published_at'] = $request->published_at ?? $post->published_at;
        }

        // Handle image removal
        if ($request->has('remove_image') && $request->remove_image) {
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
                $data['image'] = null;
            }
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Post updated successfully!',
                'post' => $post->fresh()
            ]);
        }

        return redirect()->route('posts.show', $post)
                        ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check authorization
        if (Auth::id() !== $post->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete image if exists
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Post deleted successfully!'
            ]);
        }

        return redirect()->route('posts.index')
                        ->with('success', 'Post deleted successfully!');
    }

    /**
     * Like a post
     */
    public function like(Post $post)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to like posts.'
            ], 401);
        }
        
        // Check if already liked
        $existingLike = $post->likes()->where('user_id', $user->id)->first();
        
        if ($existingLike) {
            // Unlike the post
            $existingLike->delete();
            $post->decrement('likes_count');
            
            return response()->json([
                'success' => true,
                'action' => 'unliked',
                'likes_count' => $post->fresh()->likes_count,
                'message' => 'Post unliked successfully!'
            ]);
        }
        
        // Like the post
        $post->likes()->create(['user_id' => $user->id]);
        $post->increment('likes_count');
        
        return response()->json([
            'success' => true,
            'action' => 'liked',
            'likes_count' => $post->fresh()->likes_count,
            'message' => 'Post liked successfully!'
        ]);
    }

    /**
     * Unlike a post
     */
    public function unlike(Post $post)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to unlike posts.'
            ], 401);
        }
        
        $like = $post->likes()->where('user_id', $user->id)->first();
        
        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            
            return response()->json([
                'success' => true,
                'likes_count' => $post->fresh()->likes_count,
                'message' => 'Post unliked successfully!'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'You have not liked this post!'
        ], 400);
    }

    /**
     * Add comment to post
     */
    public function comment(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000|min:1',
            'parent_id' => 'nullable|exists:comments,id'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to comment.'
            ], 401);
        }
        
        $comment = $post->comments()->create([
            'user_id' => $user->id,
            'content' => $request->content,
            'parent_id' => $request->parent_id
        ]);
        
        $post->increment('comments_count');
        
        // Load user relationship for response
        $comment->load('user');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully!',
                'comment' => $comment,
                'comments_count' => $post->fresh()->comments_count
            ]);
        }
        
        return redirect()->route('posts.show', $post)
                        ->with('success', 'Comment added successfully!');
    }

    /**
     * Search posts
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $postsQuery = Post::with(['user'])
                    ->withCount(['likes', 'comments']);
        
        // Check if status column exists before filtering
        if (Schema::hasColumn('posts', 'status')) {
            $postsQuery->where('status', 'published');
        }
        
        $posts = $postsQuery->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%");
            })
            ->latest()
            ->paginate(12);
        
        // AJAX request එකක් නම් JSON ලෙස return කරන්න
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
                'has_more_pages' => $posts->hasMorePages(),
                'total' => $posts->total()
            ]);
        }
        
        return view('posts.search', compact('posts', 'query'));
    }

    /**
     * Get popular posts
     */
    public function popular(Request $request)
    {
        $postsQuery = Post::with(['user'])
                    ->withCount(['likes', 'comments']);
        
        // Check if status column exists before filtering
        if (Schema::hasColumn('posts', 'status')) {
            $postsQuery->where('status', 'published');
        }
        
        $posts = $postsQuery->orderBy('likes_count', 'desc')
                    ->orderBy('views', 'desc')
                    ->paginate(12);
        
        // AJAX request එකක් නම් JSON ලෙස return කරන්න
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
                'has_more_pages' => $posts->hasMorePages(),
                'total' => $posts->total()
            ]);
        }
        
        return view('posts.popular', compact('posts'));
    }

    /**
     * Get trending posts
     */
    public function getTrendingPosts(Request $request)
    {
        $postsQuery = Post::with(['user'])
                    ->withCount(['likes', 'comments']);
        
        // Check if status column exists before filtering
        if (Schema::hasColumn('posts', 'status')) {
            $postsQuery->where('status', 'published');
        }
        
        $posts = $postsQuery->where('created_at', '>=', now()->subDays(7))
                    ->orderBy('likes_count', 'desc')
                    ->orderBy('views', 'desc')
                    ->paginate(12);
        
        // AJAX request එකක් නම් JSON ලෙස return කරන්න
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
                'has_more_pages' => $posts->hasMorePages(),
                'total' => $posts->total()
            ]);
        }
        
        return view('posts.trending', compact('posts'));
    }

    /**
     * API endpoint for home page posts
     */
    public function apiIndex(Request $request)
    {
        try {
            \Log::info('API Index called', ['request' => $request->all()]);
            
            $query = Post::with(['user'])
                        ->withCount(['likes', 'comments']);
            
            // Check if status column exists before filtering
            if (Schema::hasColumn('posts', 'status')) {
                $query->where('status', 'published');
            }
            
            \Log::info('Query built', ['query_count' => $query->count()]);
            
            // Apply filters
            if ($request->has('filter')) {
                switch ($request->filter) {
                    case 'popular':
                        $query->orderBy('likes_count', 'desc')
                              ->orderBy('views', 'desc');
                        break;
                    case 'trending':
                        $query->where('created_at', '>=', now()->subDays(7))
                              ->orderBy('likes_count', 'desc')
                              ->orderBy('views', 'desc');
                        break;
                    default:
                        $query->latest();
                        break;
                }
            } else {
                $query->latest();
            }
            
            // Apply search if provided
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }
            
            $posts = $query->paginate(9);
            
            \Log::info('Posts fetched', ['count' => $posts->count(), 'total' => $posts->total()]);
            
            return response()->json([
                'success' => true,
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
                'has_more_pages' => $posts->hasMorePages(),
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'total' => $posts->total()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('API Index Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error loading posts: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Get filtered posts (for AJAX requests)
     */
    public function getFilteredPosts(Request $request)
    {
        return $this->apiIndex($request);
    }

    /**
     * Get user's posts
     */
    public function userPosts(Request $request, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $postsQuery = Post::with(['user'])
                    ->withCount(['likes', 'comments'])
                    ->where('user_id', $userId);
        
        // Check if status column exists before filtering
        if (Schema::hasColumn('posts', 'status')) {
            $postsQuery->where('status', 'published');
        }
        
        $posts = $postsQuery->latest()
                    ->paginate(12);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
                'has_more_pages' => $posts->hasMorePages(),
                'total' => $posts->total()
            ]);
        }
        
        return view('posts.user', compact('posts'));
    }

    /**
     * Get user's draft posts
     */
    public function drafts(Request $request)
    {
        // Check if status column exists
        if (!Schema::hasColumn('posts', 'status')) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'posts' => [],
                    'next_page_url' => null,
                    'has_more_pages' => false,
                    'total' => 0
                ]);
            }
            return view('posts.drafts', ['posts' => collect()]);
        }
        
        $posts = Post::with(['user'])
                    ->withCount(['likes', 'comments'])
                    ->where('user_id', Auth::id())
                    ->where('status', 'draft')
                    ->latest()
                    ->paginate(12);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'posts' => $posts->items(),
                'next_page_url' => $posts->nextPageUrl(),
                'has_more_pages' => $posts->hasMorePages(),
                'total' => $posts->total()
            ]);
        }
        
        return view('posts.drafts', compact('posts'));
    }
}