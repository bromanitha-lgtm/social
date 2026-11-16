<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (auth()->check()) {
            // If user is logged in, show home page with posts
            $posts = Post::with('user')
                        ->withCount(['likes', 'comments'])
                        ->latest()
                        ->take(6)
                        ->get();
            
            return view('home', compact('posts'));
        } else {
            // Show only published articles on the public landing page.
            $posts = Post::with('user')
                        ->withCount(['likes', 'comments'])
                        ->where('status', 'published')
                        ->where(function ($query) {
                            $query->whereNull('published_at')
                                  ->orWhere('published_at', '<=', now());
                        })
                        ->latest()
                        ->take(3)
                        ->get();
            
            return view('welcome', compact('posts'));
        }
    }
}