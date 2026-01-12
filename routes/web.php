<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\ProfileController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

Route::get('/test', function () {
    return view('test.test');
})->name('test');

// Profile viewing route (public)
Route::get('/profile/{user}', function (User $user) {
    return redirect()->route('home')->with([
        'view_profile' => true,
        'profile_user_id' => $user->id
    ]);
})->name('profile.show');

// Profile AJAX routes
Route::get('/users/{user}/profile-data', [ProfileController::class, 'getProfileData'])->name('profile.data');
Route::get('/my-profile-data', [ProfileController::class, 'getMyProfileData'])->name('my.profile.data');

// Search routes (public)
Route::prefix('search')->group(function () {
    Route::get('/live', [SearchController::class, 'liveSearch'])->name('search.live');
    Route::get('/', [SearchController::class, 'index'])->name('search.index');
    Route::get('/users', [SearchController::class, 'users'])->name('search.users');
    Route::get('/posts', [SearchController::class, 'posts'])->name('search.posts');
    Route::get('/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
});

// Public Post Routes
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::get('/posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts/popular', [PostController::class, 'popular'])->name('posts.popular');
Route::get('/posts/trending', [PostController::class, 'getTrendingPosts'])->name('posts.trending');
Route::get('/user/{user}/posts', [PostController::class, 'userPosts'])->name('posts.user.show');

// API endpoints for home page (public)
Route::get('/api/posts', [PostController::class, 'apiIndex']);
Route::get('/api/posts/filtered', [PostController::class, 'getFilteredPosts'])->name('posts.filtered');
Route::get('/api/posts/trending', [PostController::class, 'getTrendingPosts'])->name('posts.trending');

// Authentication routes
Auth::routes();

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {
    // Post CRUD operations
    Route::resource('posts', PostController::class)->except(['show']);
    
    // Post interactions
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
    Route::post('/posts/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    
    // User specific post routes
    Route::get('/my-posts', [PostController::class, 'userPosts'])->name('posts.user');
    Route::get('/my-drafts', [PostController::class, 'drafts'])->name('posts.drafts');
    
    // Follower routes
    Route::prefix('users')->group(function () {
        Route::post('/{user}/follow', [FollowerController::class, 'follow'])->name('users.follow');
        Route::post('/{user}/unfollow', [FollowerController::class, 'unfollow'])->name('users.unfollow');
        Route::post('/{user}/toggle-follow', [FollowerController::class, 'toggleFollow'])->name('users.toggle-follow');
        Route::get('/{user}/followers', [FollowerController::class, 'followers'])->name('users.followers');
        Route::get('/{user}/following', [FollowerController::class, 'following'])->name('users.following');
        Route::get('/{user}/check-follow', [FollowerController::class, 'check'])->name('users.check-follow');
    });
    
    // Avatar routes
    Route::post('/avatar/upload', [AvatarController::class, 'upload'])->name('avatar.upload');
    Route::post('/avatar/remove', [AvatarController::class, 'remove'])->name('avatar.remove');
    
    // Profile update
    Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();
        $user->update($request->validate([
            'fName' => 'required|string|max:255',
            'lName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobNumber' => 'required|string|max:20'
        ]));
        
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    })->name('profile.update');
});

// Fallback route (keep at the end)
Route::fallback(function () {
    return redirect()->route('home');
});