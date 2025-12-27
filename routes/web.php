<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});

Route::get('/search', function () {
    return view('app');
});

Route::get('/403', function () {
    return view('errors.403');
});
Route::get('/401', function () {
    return view('errors.401');
});
Route::get('/402', function () {
    return view('errors.402');
});
Route::get('/419', function () {
    return view('errors.419');
});
Route::get('/429', function () {
    return view('errors.429');
});
Route::get('/500', function () {
    return view('errors.500');
});
Route::get('/503', function () {
    return view('errors.503');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
