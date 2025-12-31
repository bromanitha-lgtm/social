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

Route::resource('posts', PostController::class);
// routes/web.php
Route::get('/debug-image', function() {
    return view('test.posts.debug-image');
});

// ========================================================================================

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');