@extends('errors.minimal')

@section('title', 'Session Expired')

@section('content')
    <!-- 419 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 25%; left: 20%; width: 35px; height: 35px; background-color: #F59E0B; border-radius: 50%; opacity: 0.7; animation: float 4.5s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 60%; left: 25%; width: 40px; height: 40px; background-color: var(--primary); border-radius: 50%; opacity: 0.5; animation: float 5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 35%; left: 70%; width: 30px; height: 30px; background-color: var(--secondary); border-radius: 50%; opacity: 0.6; animation: float 3.5s ease-in-out infinite;"></div>
        
        <!-- Main 419 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation pulse" style="animation-duration: 4s;">
                <!-- Hourglass -->
                <path d="M80,60 L120,60 L140,90 L120,120 L80,120 L60,90 Z" fill="#FEF3C7" stroke="#F59E0B" stroke-width="3"/>
                <path d="M80,120 L120,120 L140,150 L120,180 L80,180 L60,150 Z" fill="#FEF3C7" stroke="#F59E0B" stroke-width="3"/>
                
                <!-- Sand flowing -->
                <path d="M90,80 L110,80 L105,115 L95,115 Z" fill="#D97706" opacity="0.8">
                    <animate attributeName="d" dur="3s" repeatCount="indefinite" values="M90,80 L110,80 L105,115 L95,115 Z; M90,80 L110,80 L107,150 L93,150 Z; M90,80 L110,80 L105,115 L95,115 Z"/>
                </path>
                
                <!-- Sand in bottom -->
                <path d="M90,160 L110,160 L105,175 L95,175 Z" fill="#D97706" opacity="0.6"/>
                
                <!-- Clock hands -->
                <line x1="100" y1="90" x2="100" y2="80" stroke="#1F2937" stroke-width="3" stroke-linecap="round">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 100 100" to="360 100 100" dur="8s" repeatCount="indefinite"/>
                </line>
                <line x1="100" y1="90" x2="115" y2="90" stroke="#1F2937" stroke-width="2" stroke-linecap="round">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 100 100" to="360 100 100" dur="60s" repeatCount="indefinite"/>
                </line>
                
                <!-- 419 text -->
                <text x="100" y="190" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#F59E0B">419</text>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Session Expired</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        Your learning session has timed out due to inactivity. This is a security measure to protect your account. Please sign in again to continue.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: #F59E0B; font-weight: bold;">ERROR_CODE: 419_PAGE_EXPIRED</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px;">
        <a href="{{ url('/login') }}" class="btn btn-primary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                <polyline points="10 17 15 12 10 7"></polyline>
                <line x1="15" y1="12" x2="3" y2="12"></line>
            </svg>
            Sign In Again
        </a>
        
        <button onclick="window.location.reload()" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M23 4v6h-6"></path>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            Refresh Page
        </button>
        
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Return to Homepage
        </a>
    </div>

    <!-- Session info card -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 25px; background: white; border-radius: var(--radius); max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 18px; margin-bottom: 15px; color: var(--dark);">Session Security Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 25px;">
            <div style="padding: 15px; background-color: #FEF3C7; border-radius: 8px; border-left: 4px solid #F59E0B;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#92400E" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <strong style="color: #92400E;">Session Timeout</strong>
                </div>
                <p style="font-size: 14px; color: #92400E;">Sessions automatically expire after 2 hours of inactivity for security.</p>
            </div>
            
            <div style="padding: 15px; background-color: #ECFDF5; border-radius: 8px; border-left: 4px solid #10B981;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#065F46" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <strong style="color: #065F46;">Security First</strong>
                </div>
                <p style="font-size: 14px; color: #065F46;">Automatic logout protects your account and learning progress.</p>
            </div>
        </div>
        
        <!-- Prevention tips -->
        <div style="background-color: #F8FAFC; padding: 20px; border-radius: 8px;">
            <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark);">Tips to avoid session expiration:</h4>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">1</div>
                    <div>
                        <strong>Stay Active</strong>
                        <p style="font-size: 14px; color: var(--gray); margin-top: 2px;">Interact with the page regularly to keep your session alive</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">2</div>
                    <div>
                        <strong>Use "Remember Me"</strong>
                        <p style="font-size: 14px; color: var(--gray); margin-top: 2px;">Check "Remember Me" when signing in for longer sessions</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">3</div>
                    <div>
                        <strong>Save Your Work</strong>
                        <p style="font-size: 14px; color: var(--gray); margin-top: 2px;">Regularly save course progress to avoid losing your work</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Auto-redirect counter -->
        <div style="margin-top: 25px; padding: 15px; background-color: #EFF6FF; border-radius: 8px; text-align: center;">
            <p style="color: var(--primary); margin-bottom: 10px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;">
                    <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                </svg>
                <strong>Auto-redirecting to login in <span id="countdown">10</span> seconds...</strong>
            </p>
            <button onclick="clearTimeout(redirectTimer)" style="background: none; border: none; color: var(--primary); text-decoration: underline; cursor: pointer; font-size: 14px;">Cancel auto-redirect</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-redirect countdown
            let seconds = 10;
            const countdownElement = document.getElementById('countdown');
            const countdownInterval = setInterval(function() {
                seconds--;
                countdownElement.textContent = seconds;
                
                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    window.location.href = "{{ url('/login') }}";
                }
            }, 1000);
            
            // Store the timer ID to allow cancellation
            window.redirectTimer = setTimeout(function() {
                window.location.href = "{{ url('/login') }}";
            }, 10000);
        });
    </script>
@endsection