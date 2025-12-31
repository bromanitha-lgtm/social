<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('app');
// });

Route::get('/search', function () {
    return view('layouts.search');
})->name('search');

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/lar', function () {
    return view('test.login');
})->name('lar');

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');