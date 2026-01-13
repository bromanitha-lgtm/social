<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AvatarController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FollowerController;
use App\Http\Controllers\ProfileViewController;
use Illuminate\Support\Facades\Route;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Welcome/Home Routes
Route::get('/welcome', [HomeController::class, 'index'])->name('welcome');
Route::get('/', [HomeController::class, 'index'])->name('home');

// Authentication Routes
Auth::routes();

// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    
    // Avatar Routes
    Route::post('/avatar/upload', [AvatarController::class, 'upload'])->name('avatar.upload');
    Route::post('/avatar/remove', [AvatarController::class, 'remove'])->name('avatar.remove');
    
    // My Profile Data (AJAX)
    Route::get('/my-profile-data', [ProfileController::class, 'getMyProfileData'])->name('profile.my-data');
});

// User Profile Routes (Public)
Route::get('/profile/{user}', function(User $user) {
    // Store in session for direct access
    session(['direct_profile_access' => true, 'profile_user_id' => $user->id]);
    
    // Redirect to home page with profile tab active
    return redirect()->route('home')->with([
        'profile_user_id' => $user->id,
        'direct_profile_access' => true
    ]);
})->name('profile.show');

// User Profile Data (AJAX)
Route::get('/users/{user}/profile-data', [ProfileController::class, 'getProfileData'])->name('profile.data');

// Post Routes
Route::prefix('posts')->group(function () {
    // Public routes
    Route::get('/', [PostController::class, 'index'])->name('posts.index');
    Route::get('/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/search', [PostController::class, 'search'])->name('posts.search');
    Route::get('/popular', [PostController::class, 'popular'])->name('posts.popular');
    Route::get('/trending', [PostController::class, 'getTrendingPosts'])->name('posts.trending');
    
    // API Routes for AJAX
    Route::get('/api/posts', [PostController::class, 'apiIndex'])->name('posts.api.index');
    Route::get('/api/filtered', [PostController::class, 'getFilteredPosts'])->name('posts.api.filtered');
    
    // Auth required routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
        Route::get('/my/drafts', [PostController::class, 'drafts'])->name('posts.drafts');
        Route::get('/user/{user?}', [PostController::class, 'userPosts'])->name('posts.user');
        
        // Like routes
        Route::post('/{post}/like', [PostController::class, 'like'])->name('posts.like');
        Route::post('/{post}/unlike', [PostController::class, 'unlike'])->name('posts.unlike');
        
        // Comment routes
        Route::post('/{post}/comment', [PostController::class, 'comment'])->name('posts.comment');
    });
});

// Like Routes
Route::prefix('likes')->middleware(['auth'])->group(function () {
    Route::post('/{post}', [LikeController::class, 'like'])->name('likes.toggle');
    Route::get('/{post}/likers', [LikeController::class, 'getLikers'])->name('likes.likers');
});

// Comment Routes
Route::prefix('comments')->middleware(['auth'])->group(function () {
    Route::post('/', [CommentController::class, 'store'])->name('comments.store');
    Route::put('/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::get('/{post}/comments', [CommentController::class, 'getComments'])->name('comments.get');
    Route::get('/{comment}/replies', [CommentController::class, 'getReplies'])->name('comments.replies');
});

// Search Routes
Route::prefix('search')->group(function () {
    Route::get('/live', [SearchController::class, 'liveSearch'])->name('search.live');
    Route::get('/', [SearchController::class, 'index'])->name('search.index');
    Route::get('/users', [SearchController::class, 'users'])->name('search.users');
    Route::get('/posts', [SearchController::class, 'posts'])->name('search.posts');
    Route::get('/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
});

// Follower Routes
Route::middleware(['auth'])->prefix('users')->group(function () {
    Route::post('/{user}/follow', [FollowerController::class, 'follow'])->name('follow');
    Route::post('/{user}/unfollow', [FollowerController::class, 'unfollow'])->name('unfollow');
    Route::post('/{user}/toggle-follow', [FollowerController::class, 'toggleFollow'])->name('follow.toggle');
    Route::get('/{user}/followers', [FollowerController::class, 'followers'])->name('followers');
    Route::get('/{user}/following', [FollowerController::class, 'following'])->name('following');
    Route::get('/{user}/check-follow', [FollowerController::class, 'check'])->name('follow.check');
});

// Share Routes (Social Media)
Route::get('/share/profile/{user}', function(User $user) {
    return view('share.profile', compact('user'));
})->name('share.profile');

Route::get('/share/post/{post}', function(\App\Models\Post $post) {
    return view('share.post', compact('post'));
})->name('share.post');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/wallet', function () {
        return view('wallet');
    })->name('wallet');
    
    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');
    
    Route::get('/notifications', function () {
        return view('notifications');
    })->name('notifications');
});

// API Routes for AJAX calls
Route::prefix('api')->group(function () {
    // Posts API
    Route::get('/posts', [PostController::class, 'apiIndex'])->name('api.posts.index');
    Route::get('/posts/filtered', [PostController::class, 'getFilteredPosts'])->name('api.posts.filtered');
    
    // Search API
    Route::get('/search/live', [SearchController::class, 'liveSearch'])->name('api.search.live');
    
    // Profile API
    Route::get('/profile/{user}/data', [ProfileController::class, 'getProfileData'])->name('api.profile.data');
    Route::get('/my-profile/data', [ProfileController::class, 'getMyProfileData'])->name('api.profile.my-data');
    
    // Follow API
    Route::post('/follow/{user}/toggle', [FollowerController::class, 'toggleFollow'])->name('api.follow.toggle');
    
    // Like API
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('api.posts.like');
    Route::post('/posts/{post}/unlike', [PostController::class, 'unlike'])->name('api.posts.unlike');
});
// Dashboard API routes
Route::prefix('api/dashboard')->middleware('auth')->group(function () {
    Route::get('/data', [DashboardController::class, 'getDashboardData']);
    Route::get('/activities', [DashboardController::class, 'getRecentActivities']);
    Route::get('/engagement', [DashboardController::class, 'getEngagementData']);
    Route::get('/performance', [DashboardController::class, 'getPerformanceData']);
});

// Post routes
Route::get('/create', function () {
    return view('posts.create');
})->name('create');

// My activity route
Route::get('/api/my-activity', [DashboardController::class, 'getMyActivity'])->middleware('auth');

// Static Pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::get('/privacy', function () {
    return view('pages.privacy');
})->name('privacy');

Route::get('/terms', function () {
    return view('pages.terms');
})->name('terms');

// Error Pages
Route::fallback(function () {
    return view('errors.404');
});