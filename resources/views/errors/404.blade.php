@extends('errors.minimal')

@section('title', 'Page Not Found')

@section('content')
    <!-- 404 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 20%; left: 30%; width: 40px; height: 40px; background-color: var(--primary); border-radius: 50%; opacity: 0.7; animation: float 4s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 60%; left: 20%; width: 30px; height: 30px; background-color: var(--secondary); border-radius: 50%; opacity: 0.5; animation: float 5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 40%; left: 70%; width: 25px; height: 25px; background-color: var(--warning); border-radius: 50%; opacity: 0.6; animation: float 3.5s ease-in-out infinite;"></div>
        
        <!-- Main 404 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation float" style="animation-duration: 8s;">
                <!-- Book shape -->
                <rect x="30" y="50" width="140" height="100" rx="8" fill="#F3F4F6" stroke="#D1D5DB" stroke-width="2"/>
                <rect x="40" y="60" width="120" height="80" rx="4" fill="white" stroke="#E5E7EB" stroke-width="1"/>
                <line x1="40" y1="90" x2="160" y2="90" stroke="#E5E7EB" stroke-width="1"/>
                <line x1="40" y1="110" x2="160" y2="110" stroke="#E5E7EB" stroke-width="1"/>
                
                <!-- Magnifying glass -->
                <circle cx="120" cy="100" r="30" fill="none" stroke="#4F46E5" stroke-width="3" stroke-dasharray="5,5"/>
                <line x1="140" y1="120" x2="160" y2="140" stroke="#4F46E5" stroke-width="3"/>
                
                <!-- 404 text -->
                <text x="100" y="180" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#EF4444">404</text>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Page Not Found</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        Oops! The page you're looking for seems to have gone on an educational field trip and hasn't returned. 
        It might have been moved, deleted, or never existed in the first place.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: var(--danger); font-weight: bold;">ERROR_CODE: 404_NOT_FOUND</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ url('/') }}" class="btn btn-primary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Back to Homepage
        </a>
        
        <a href="{{ url('/courses') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 20h9"></path>
                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
            </svg>
            Browse Courses
        </a>
        
        <button onclick="window.history.back()" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5"></path>
                <path d="M12 19l-7-7 7-7"></path>
            </svg>
            Go Back
        </button>
    </div>

    <!-- Search suggestion -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 20px; background: white; border-radius: var(--radius); max-width: 500px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 18px; margin-bottom: 10px;">Can't find what you're looking for?</h3>
        <p style="color: var(--gray); margin-bottom: 15px;">Try searching our educational resources:</p>
        <form action="{{ url('/search') }}" method="GET" style="display: flex;">
            <input type="text" name="q" placeholder="Search courses, articles, or resources..." style="flex-grow: 1; padding: 12px 15px; border: 1px solid var(--gray-light); border-radius: var(--radius) 0 0 var(--radius); font-family: 'Inter'; font-size: 16px; outline: none;">
            <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0 20px; border-radius: 0 var(--radius) var(--radius) 0; cursor: pointer;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </button>
        </form>
    </div>
@endsection