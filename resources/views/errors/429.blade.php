@extends('errors.minimal')

@section('title', 'Too Many Requests')

@section('content')
    <!-- 429 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 20%; left: 20%; width: 35px; height: 35px; background-color: var(--danger); border-radius: 50%; opacity: 0.7; animation: float 4s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 65%; left: 25%; width: 40px; height: 40px; background-color: var(--warning); border-radius: 50%; opacity: 0.5; animation: float 5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 35%; left: 70%; width: 30px; height: 30px; background-color: var(--primary); border-radius: 50%; opacity: 0.6; animation: float 3.5s ease-in-out infinite;"></div>
        
        <!-- Main 429 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation pulse" style="animation-duration: 2s;">
                <!-- Traffic light -->
                <rect x="80" y="40" width="40" height="120" rx="5" fill="#374151" stroke="#1F2937" stroke-width="2"/>
                <rect x="85" y="45" width="30" height="110" rx="3" fill="#1F2937"/>
                
                <!-- Red light (active) -->
                <circle cx="100" cy="70" r="12" fill="#EF4444" filter="url(#glow)">
                    <animate attributeName="opacity" values="1;0.5;1" dur="1s" repeatCount="indefinite"/>
                </circle>
                
                <!-- Yellow light -->
                <circle cx="100" cy="110" r="12" fill="#F59E0B" opacity="0.3"/>
                
                <!-- Green light -->
                <circle cx="100" cy="150" r="12" fill="#10B981" opacity="0.3"/>
                
                <!-- Stop sign -->
                <path d="M100,180 L120,170 L120,190 L80,190 L80,170 Z" fill="#EF4444"/>
                <text x="100" y="185" text-anchor="middle" font-family="Poppins" font-size="14" font-weight="bold" fill="white">STOP</text>
                
                <!-- 429 text -->
                <text x="100" y="190" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#EF4444">429</text>
                
                <!-- Glow filter for red light -->
                <defs>
                    <filter id="glow">
                        <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                        <feMerge>
                            <feMergeNode in="coloredBlur"/>
                            <feMergeNode in="SourceGraphic"/>
                        </feMerge>
                    </filter>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Too Many Requests</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        You've made too many requests to our educational servers in a short time. This helps us ensure fair access for all learners. Please wait a moment before trying again.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: var(--danger); font-weight: bold;">ERROR_CODE: 429_TOO_MANY_REQUESTS</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px;">
        <button onclick="window.location.reload()" class="btn btn-primary" id="retryButton" disabled>
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M23 4v6h-6"></path>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            Try Again (<span id="countdown">30</span>s)
        </button>
        
        <a href="{{ url('/') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                <polyline points="9 22 9 12 15 12 15 22"></polyline>
            </svg>
            Go to Homepage
        </a>
        
        <a href="{{ url('/help/rate-limiting') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 16v-4"></path>
                <path d="M12 8h.01"></path>
            </svg>
            Learn More
        </a>
    </div>

    <!-- Rate limiting info -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 25px; background: white; border-radius: var(--radius); max-width: 600px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 18px; margin-bottom: 15px; color: var(--dark);">Rate Limiting Information</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 25px;">
            <div style="padding: 15px; background-color: #FEF2F2; border-radius: 8px; border-left: 4px solid #EF4444;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <strong style="color: #DC2626;">Request Limit Reached</strong>
                </div>
                <p style="font-size: 14px; color: #92400E;">You've exceeded 60 requests per minute to this resource.</p>
            </div>
            
            <div style="padding: 15px; background-color: #F0F9FF; border-radius: 8px; border-left: 4px solid #0EA5E9;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0369A1" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <strong style="color: #0369A1;">Temporary Block</strong>
                </div>
                <p style="font-size: 14px; color: #0369A1;">This restriction will be automatically lifted shortly.</p>
            </div>
        </div>
        
        <!-- Countdown visualization -->
        <div style="margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 14px; color: var(--gray);">Time until you can try again:</span>
                <span style="font-size: 14px; color: var(--dark); font-weight: 600;" id="timeRemaining">00:30</span>
            </div>
            <div style="height: 8px; background-color: #E5E7EB; border-radius: 4px; overflow: hidden;">
                <div id="progressBar" style="height: 100%; width: 100%; background: linear-gradient(90deg, #EF4444, #F59E0B); transition: width 1s linear;"></div>
            </div>
        </div>
        
        <!-- Prevention tips -->
        <div style="background-color: #F8FAFC; padding: 20px; border-radius: 8px;">
            <h4 style="font-size: 16px; margin-bottom: 15px; color: var(--dark);">How to avoid rate limiting:</h4>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">1</div>
                    <div>
                        <strong>Space Out Requests</strong>
                        <p style="font-size: 14px; color: var(--gray); margin-top: 2px;">Avoid rapid clicking or automated requests</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">2</div>
                    <div>
                        <strong>Use APIs Responsibly</strong>
                        <p style="font-size: 14px; color: var(--gray); margin-top: 2px;">Implement proper delays between API calls</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 10px;">
                    <div style="min-width: 24px; width: 24px; height: 24px; background-color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 12px;">3</div>
                    <div>
                        <strong>Contact for Higher Limits</strong>
                        <p style="font-size: 14px; color: var(--gray); margin-top: 2px;">Educational institutions can request higher limits</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Countdown timer
            let seconds = 30;
            const countdownElement = document.getElementById('countdown');
            const timeRemainingElement = document.getElementById('timeRemaining');
            const progressBar = document.getElementById('progressBar');
            const retryButton = document.getElementById('retryButton');
            const totalSeconds = 30;
            
            const countdownInterval = setInterval(function() {
                seconds--;
                
                // Update countdown display
                countdownElement.textContent = seconds;
                
                // Update time remaining display
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                timeRemainingElement.textContent = 
                    `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
                
                // Update progress bar
                const progressPercentage = (seconds / totalSeconds) * 100;
                progressBar.style.width = `${progressPercentage}%`;
                
                // Change progress bar color as time decreases
                if (seconds <= 10) {
                    progressBar.style.background = 'linear-gradient(90deg, #EF4444, #DC2626)';
                } else if (seconds <= 20) {
                    progressBar.style.background = 'linear-gradient(90deg, #F59E0B, #EF4444)';
                }
                
                // Enable button when countdown reaches 0
                if (seconds <= 0) {
                    clearInterval(countdownInterval);
                    retryButton.disabled = false;
                    retryButton.innerHTML = `
                        <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M23 4v6h-6"></path>
                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                        </svg>
                        Try Again Now
                    `;
                }
            }, 1000);
        });
    </script>
@endsection