@extends('errors.minimal')

@section('title', 'Access Denied')

@section('content')
    <!-- 403 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 10%; left: 25%; width: 35px; height: 35px; background-color: var(--warning); border-radius: 50%; opacity: 0.7; animation: float 4.5s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 70%; left: 15%; width: 40px; height: 40px; background-color: var(--danger); border-radius: 50%; opacity: 0.5; animation: float 5.5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 30%; left: 75%; width: 30px; height: 30px; background-color: var(--primary); border-radius: 50%; opacity: 0.6; animation: float 4s ease-in-out infinite;"></div>
        
        <!-- Main 403 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation float" style="animation-duration: 7s;">
                <!-- Shield with lock -->
                <path d="M100,30 C150,30 180,60 180,100 C180,140 150,170 100,170 C50,170 20,140 20,100 C20,60 50,30 100,30" fill="#FEF3C7" stroke="#F59E0B" stroke-width="3"/>
                <path d="M100,40 C140,40 165,65 165,100 C165,135 140,160 100,160 C60,160 35,135 35,100 C35,65 60,40 100,40" fill="white" stroke="#F59E0B" stroke-width="1"/>
                
                <!-- Lock -->
                <rect x="85" y="90" width="30" height="40" rx="4" fill="#4F46E5"/>
                <rect x="90" y="70" width="20" height="20" rx="3" fill="#4F46E5"/>
                <circle cx="100" cy="80" r="5" fill="white"/>
                
                <!-- 403 text -->
                <text x="100" y="180" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#F59E0B">403</text>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Access Denied</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        You don't have permission to access this educational resource. This area might be restricted to certain 
        user roles or require special authorization.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: var(--warning); font-weight: bold;">ERROR_CODE: 403_FORBIDDEN</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <a href="{{ url('/') }}" class="btn btn-primary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Go to Homepage
        </a>
        
        <a href="{{ url('/login') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
            Sign In
        </a>
        
        <a href="{{ url('/contact') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            Request Access
        </a>
    </div>

    <!-- Access levels information -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 25px; background: white; border-radius: var(--radius); max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 18px; margin-bottom: 15px; color: var(--dark);">Understanding Access Levels</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; text-align: left;">
            <div>
                <div style="width: 12px; height: 12px; background-color: var(--secondary); border-radius: 50%; display: inline-block; margin-right: 8px;"></div>
                <strong>Student</strong>
                <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">Access to enrolled courses and basic resources</p>
            </div>
            <div>
                <div style="width: 12px; height: 12px; background-color: var(--primary); border-radius: 50%; display: inline-block; margin-right: 8px;"></div>
                <strong>Instructor</strong>
                <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">Can create and manage courses, access analytics</p>
            </div>
            <div>
                <div style="width: 12px; height: 12px; background-color: var(--warning); border-radius: 50%; display: inline-block; margin-right: 8px;"></div>
                <strong>Administrator</strong>
                <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">Full system access and user management</p>
            </div>
        </div>
    </div>
@endsection