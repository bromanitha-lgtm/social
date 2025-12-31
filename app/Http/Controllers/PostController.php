<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Constructor - Apply middleware
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display all posts
     */
    public function index(Request $request)
    {
        // Start query with user relationship
        $query = Post::with('user');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        
        // Order by
        $order = $request->get('order', 'newest');
        if ($order == 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }
        
        // Paginate results (12 per page)
        $posts = $query->paginate(12);
        
        // Return view from test folder
        return view('test.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new post
     */
    public function create()
    {
        return view('test.posts.create');
    }

    /**
     * Store a newly created post
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // 2MB max
        ]);
        
        // Handle image upload if exists
        $imagePath = null;
        if ($request->hasFile('image')) {
            // Generate unique filename
            $image = $request->file('image');
            
            // Method 1: Using uniqid() + timestamp (recommended)
            $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            
            // Method 2: Using UUID (alternative)
            // $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            
            // Store image with unique filename
            $imagePath = $image->storeAs('posts', $filename, 'public');
            
            // Log for debugging (optional)
            \Log::info('Image uploaded:', [
                'original_name' => $image->getClientOriginalName(),
                'stored_as' => $filename,
                'path' => $imagePath,
                'user_id' => Auth::id()
            ]);
        }
        
        // Create the post
        Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
        ]);
        
        // Redirect with success message
        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display a single post
     */
    public function show(Post $post)
    {
        // Load post with user relationship
        $post->load('user');
        
        // Increment views if needed (future feature)
        // $post->increment('views');
        
        // Return single post view
        return view('test.posts.show', compact('post'));
    }

    /**
     * Show the form for editing a post
     */
    public function edit(Post $post)
    {
        // Check authorization - only post owner can edit
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('test.posts.edit', compact('post'));
    }

    /**
     * Update the specified post
     */
    public function update(Request $request, Post $post)
    {
        // Check authorization
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        
        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            
            // Generate unique filename for new image
            $image = $request->file('image');
            $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
            $post->image = $image->storeAs('posts', $filename, 'public');
        }
        
        // Handle image removal if checkbox is checked
        if ($request->has('remove_image') && $request->remove_image == '1') {
            // Delete old image if exists
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
                $post->image = null;
            }
        }
        
        // Update post details
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();
        
        // Redirect with success message
        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified post
     */
    public function destroy(Post $post)
    {
        // Check authorization
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete image if exists
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        
        // Delete the post
        $post->delete();
        
        // Redirect with success message
        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}