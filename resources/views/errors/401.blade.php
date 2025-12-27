@extends('errors.minimal')

@section('title', 'Authentication Required')

@section('content')
    <!-- 401 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 25%; left: 25%; width: 35px; height: 35px; background-color: var(--primary); border-radius: 50%; opacity: 0.7; animation: float 4s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 65%; left: 20%; width: 30px; height: 30px; background-color: var(--secondary); border-radius: 50%; opacity: 0.5; animation: float 5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 40%; left: 70%; width: 40px; height: 40px; background-color: var(--warning); border-radius: 50%; opacity: 0.6; animation: float 3.5s ease-in-out infinite;"></div>
        
        <!-- Main 401 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation float" style="animation-duration: 7s;">
                <!-- User silhouette with question mark -->
                <circle cx="100" cy="70" r="30" fill="#E5E7EB" stroke="#9CA3AF" stroke-width="2"/>
                <path d="M100,100 L100,140 Q100,160 120,160 L80,160 Q100,160 100,140" fill="#E5E7EB" stroke="#9CA3AF" stroke-width="2"/>
                
                <!-- Keyhole -->
                <circle cx="100" cy="100" r="15" fill="#4F46E5" opacity="0.8"/>
                <rect x="95" y="100" width="10" height="20" rx="2" fill="#4F46E5" opacity="0.8"/>
                
                <!-- Question mark -->
                <circle cx="160" cy="120" r="20" fill="#FEF3C7" stroke="#F59E0B" stroke-width="2"/>
                <text x="160" y="125" text-anchor="middle" font-family="Poppins" font-size="24" font-weight="bold" fill="#92400E">?</text>
                
                <!-- 401 text -->
                <text x="100" y="190" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#4F46E5">401</text>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Authentication Required</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        You need to be signed in to access this educational resource. Please log in to continue your learning journey.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: var(--primary); font-weight: bold;">ERROR_CODE: 401_UNAUTHORIZED</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px;">
        <a href="{{ url('/login') }}" class="btn btn-primary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
            Sign In
        </a>
        
        <a href="{{ url('/register') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            Create Account
        </a>
        
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Browse Public Content
        </a>
    </div>

    <!-- Login reminder card -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 25px; background: white; border-radius: var(--radius); max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 18px; margin-bottom: 15px; color: var(--dark);">Why sign in?</h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; text-align: left;">
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <div style="min-width: 36px; width: 36px; height: 36px; background: linear-gradient(135deg, var(--primary), #8B5CF6); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <div>
                    <strong>Continue Learning</strong>
                    <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">Pick up where you left off in your courses</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <div style="min-width: 36px; width: 36px; height: 36px; background: linear-gradient(135deg, var(--secondary), #0EA5E9); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>
                <div>
                    <strong>Join Community</strong>
                    <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">Connect with fellow learners and instructors</p>
                </div>
            </div>
        </div>
        
        <!-- Login form snippet -->
        <div style="margin-top: 25px; padding-top: 20px; border-top: 1px solid var(--gray-light);">
            <p style="color: var(--gray); margin-bottom: 15px;">Quick sign in options:</p>
            <div style="display: flex; gap: 10px; flex-wrap: wrap; justify-content: center;">
                <button style="padding: 10px 20px; border: 1px solid var(--gray-light); border-radius: var(--radius); background: white; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: var(--transition);" onmouseover="this.style.backgroundColor='#F9FAFB'" onmouseout="this.style.backgroundColor='white'">
                    <svg width="18" height="18" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    <span>Sign in with Google</span>
                </button>
                
                <button style="padding: 10px 20px; border: 1px solid var(--gray-light); border-radius: var(--radius); background: white; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: var(--transition);" onmouseover="this.style.backgroundColor='#F9FAFB'" onmouseout="this.style.backgroundColor='white'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="#1877F2">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>Sign in with Facebook</span>
                </button>
                
                <button style="padding: 10px 20px; border: 1px solid var(--gray-light); border-radius: var(--radius); background: white; display: flex; align-items: center; gap: 8px; cursor: pointer; transition: var(--transition);" onmouseover="this.style.backgroundColor='#F9FAFB'" onmouseout="this.style.backgroundColor='white'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="black">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    <span>Sign in with GitHub</span>
                </button>
            </div>
        </div>
    </div>
@endsection