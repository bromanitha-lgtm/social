<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;

// Route::get('/', function () {
//     return view('app');
// });

Route::get('/search', function () {
    return view('layouts.search');
})->name('search');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Route::get('/lar', function () {
//     return view('test.login');
// })->name('lar');

// ======================================== test file ====================================

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
});

// Public Post Routes
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/popular', [PostController::class, 'popular'])->name('posts.popular');
// ========================================================================================

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');