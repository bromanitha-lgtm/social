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
            // If not logged in, show welcome page
            $posts = Post::with('user')
                        ->withCount(['likes', 'comments'])
                        ->latest()
                        ->take(3)
                        ->get();
            
            return view('welcome', compact('posts'));
        }
    }
}