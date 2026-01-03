<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

// ... existing routes ...

Route::get('/search', function () {
    return view('layouts.search');
})->name('search');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Posts Routes
Route::middleware(['auth'])->group(function () {
    Route::resource('posts', PostController::class)->except(['show']);
    
    // Post Likes
    Route::post('/posts/{post}/like', [PostController::class, 'like'])
        ->name('posts.like');
    Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])
        ->name('posts.unlike');
    
    // Post Comments
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])
        ->name('posts.comment');
    
    // User specific routes
    Route::get('/my-posts', [PostController::class, 'userPosts'])->name('posts.user');
    Route::get('/my-drafts', [PostController::class, 'drafts'])->name('posts.drafts');
});

// API endpoints for home page
Route::get('/api/posts', [PostController::class, 'apiIndex']);
Route::get('/api/posts/filtered', [PostController::class, 'getFilteredPosts'])->name('posts.filtered');
Route::get('/api/posts/trending', [PostController::class, 'getTrendingPosts'])->name('posts.trending');

// Public Post Routes
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/popular', [PostController::class, 'popular'])->name('posts.popular');
Route::get('/posts/trending', [PostController::class, 'getTrendingPosts'])->name('posts.trending');
Route::get('/user/{user}/posts', [PostController::class, 'userPosts'])->name('posts.user.show');

Route::resource('posts', PostController::class);
Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');