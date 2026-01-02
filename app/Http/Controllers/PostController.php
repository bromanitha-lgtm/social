<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $posts = Post::with('user')
                    ->latest()
                    ->paginate(6); // 12 ක් ලෙස වෙනස් කරන්න
        
        // AJAX request එකක් නම් JSON ලෙස return කරන්න
        if ($request->ajax()) {
            $view = view('test.posts.partials._post-cards', compact('posts'))->render();
            
            return response()->json([
                'html' => $view,
                'next_page' => $posts->hasMorePages() ? $posts->nextPageUrl() : null
            ]);
        }
        
        return view('test.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('test.posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'views' => 0,
            'likes_count' => 0,
            'comments_count' => 0
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post = Post::create($data);

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
        $post->load('user', 'comments.user');
        
        // Check if user liked the post
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $post->isLikedBy(Auth::user());
        }

        return view('test.posts.show', compact('post', 'isLiked'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // Check authorization
        $this->authorize('update', $post);
        
        return view('test.posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        // Check authorization
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'title' => $request->title,
            'content' => $request->content
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }
            
            $imagePath = $request->file('image')->store('posts', 'public');
            $data['image'] = $imagePath;
        }

        $post->update($data);

        return redirect()->route('posts.show', $post)
                        ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // Check authorization
        $this->authorize('delete', $post);

        // Delete image if exists
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('posts.index')
                        ->with('success', 'Post deleted successfully!');
    }

    /**
     * Like a post
     */
    public function like(Post $post)
    {
        $user = Auth::user();

        if (!$post->isLikedBy($user)) {
            $post->likes()->create(['user_id' => $user->id]);
            $post->increment('likes_count');
            
            return response()->json([
                'success' => true,
                'likes_count' => $post->likes_count,
                'message' => 'Post liked successfully!'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'You already liked this post!'
        ], 400);
    }

    /**
     * Unlike a post
     */
    public function unlike(Post $post)
    {
        $user = Auth::user();

        $like = $post->likes()->where('user_id', $user->id)->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            
            return response()->json([
                'success' => true,
                'likes_count' => $post->likes_count,
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
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        $post->increment('comments_count');

        return redirect()->route('posts.show', $post)
                        ->with('success', 'Comment added successfully!');
    }

    /**
     * Search posts
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $posts = Post::with('user')
                    ->where('title', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%")
                    ->latest()
                    ->paginate(6);
        
        // AJAX request එකක් නම් JSON ලෙස return කරන්න
        if ($request->ajax()) {
            $view = view('test.posts.partials._post-cards', compact('posts'))->render();
            
            return response()->json([
                'html' => $view,
                'next_page' => $posts->hasMorePages() ? $posts->nextPageUrl() : null
            ]);
        }
        
        return view('test.posts.index', compact('posts', 'query'));
    }

    /**
     * Get popular posts
     */
    public function popular(Request $request)
    {
        $posts = Post::with('user')
                    ->popular()
                    ->paginate(6);
        
        // AJAX request එකක් නම් JSON ලෙස return කරන්න
        if ($request->ajax()) {
            $view = view('test.posts.partials._post-cards', compact('posts'))->render();
            
            return response()->json([
                'html' => $view,
                'next_page' => $posts->hasMorePages() ? $posts->nextPageUrl() : null
            ]);
        }
        
        return view('test.posts.index', compact('posts'));
    }
}