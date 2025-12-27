@extends('errors.minimal')

@section('title', 'Server Error')

@section('content')
    <!-- 500 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 20%; left: 20%; width: 45px; height: 45px; background-color: var(--danger); border-radius: 50%; opacity: 0.7; animation: float 5s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 65%; left: 25%; width: 35px; height: 35px; background-color: var(--primary); border-radius: 50%; opacity: 0.5; animation: float 4.5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 35%; left: 65%; width: 40px; height: 40px; background-color: var(--warning); border-radius: 50%; opacity: 0.6; animation: float 6s ease-in-out infinite;"></div>
        
        <!-- Main 500 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation pulse" style="animation-duration: 3s;">
                <!-- Server rack -->
                <rect x="60" y="40" width="80" height="120" rx="5" fill="#F3F4F6" stroke="#D1D5DB" stroke-width="2"/>
                
                <!-- Server slots -->
                <rect x="70" y="50" width="60" height="15" rx="3" fill="#DC2626"/>
                <rect x="70" y="70" width="60" height="15" rx="3" fill="#F59E0B"/>
                <rect x="70" y="90" width="60" height="15" rx="3" fill="#10B981"/>
                <rect x="70" y="110" width="60" height="15" rx="3" fill="#4F46E5"/>
                <rect x="70" y="130" width="60" height="15" rx="3" fill="#8B5CF6"/>
                
                <!-- Warning symbol -->
                <path d="M100,160 L110,145 L90,145 Z" fill="#EF4444"/>
                <circle cx="100" cy="155" r="2" fill="white"/>
                
                <!-- 500 text -->
                <text x="100" y="180" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#EF4444">500</text>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Server Error</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        Our educational servers are experiencing some difficulties. Our technical team has been notified and is working 
        to resolve the issue. Please try again in a few moments.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: var(--danger); font-weight: bold;">ERROR_CODE: 500_INTERNAL_SERVER_ERROR</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
        <button onclick="window.location.reload()" class="btn btn-primary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M23 4v6h-6"></path>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            Try Again
        </button>
        
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Go to Homepage
        </a>
        
        <a href="{{ url('/status') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
            </svg>
            System Status
        </a>
    </div>

    <!-- Status and recovery information -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 25px; background: white; border-radius: var(--radius); max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 18px; margin-bottom: 15px; color: var(--dark);">What's happening?</h3>
        <p style="color: var(--gray); margin-bottom: 20px;">Our system is automatically attempting to recover. Here's what you can do:</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; text-align: left;">
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">1</div>
                <div>
                    <strong>Refresh the page</strong>
                    <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">The issue might be temporary. Wait a moment and try reloading.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">2</div>
                <div>
                    <strong>Check our status page</strong>
                    <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">See if we're experiencing wider issues or scheduled maintenance.</p>
                </div>
            </div>
            
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">3</div>
                <div>
                    <strong>Contact support</strong>
                    <p style="font-size: 14px; color: var(--gray); margin-top: 5px;">If the problem persists, our team is ready to help you.</p>
                </div>
            </div>
        </div>
        
        <div style="margin-top: 20px; padding: 15px; background-color: #FEF2F2; border-radius: 8px; border-left: 4px solid var(--danger);">
            <div style="display: flex; align-items: center; gap: 10px;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="#DC2626" stroke="none">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"></path>
                </svg>
                <strong style="color: var(--danger);">Technical team notified</strong>
            </div>
            <p style="font-size: 14px; color: #92400E; margin-top: 5px; margin-left: 30px;">Our engineers have been alerted and are investigating the issue.</p>
        </div>
    </div>
@endsection