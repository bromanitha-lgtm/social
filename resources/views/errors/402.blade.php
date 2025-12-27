@extends('errors.minimal')

@section('title', 'Payment Required')

@section('content')
    <!-- 402 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 20%; left: 20%; width: 40px; height: 40px; background-color: var(--secondary); border-radius: 50%; opacity: 0.7; animation: float 4s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 65%; left: 25%; width: 35px; height: 35px; background-color: #F59E0B; border-radius: 50%; opacity: 0.5; animation: float 5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 35%; left: 70%; width: 30px; height: 30px; background-color: var(--primary); border-radius: 50%; opacity: 0.6; animation: float 3.5s ease-in-out infinite;"></div>
        
        <!-- Main 402 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation float" style="animation-duration: 8s;">
                <!-- Wallet with coins -->
                <rect x="60" y="80" width="80" height="60" rx="8" fill="#FEF3C7" stroke="#F59E0B" stroke-width="3"/>
                <rect x="65" y="85" width="70" height="50" rx="5" fill="#FEF3C7" stroke="#F59E0B" stroke-width="1"/>
                
                <!-- Coins -->
                <circle cx="85" cy="110" r="12" fill="#10B981" stroke="#059669" stroke-width="2">
                    <animate attributeName="cy" values="110;105;110" dur="2s" repeatCount="indefinite"/>
                </circle>
                <circle cx="115" cy="110" r="10" fill="#F59E0B" stroke="#D97706" stroke-width="2">
                    <animate attributeName="cy" values="110;107;110" dur="2.5s" repeatCount="indefinite"/>
                </circle>
                
                <!-- Lock on wallet -->
                <rect x="120" y="60" width="15" height="25" rx="3" fill="#6B7280"/>
                <rect x="123" y="50" width="9" height="10" rx="2" fill="#6B7280"/>
                
                <!-- Dollar sign -->
                <text x="100" y="125" text-anchor="middle" font-family="Poppins" font-size="20" font-weight="bold" fill="#92400E">$</text>
                
                <!-- 402 text -->
                <text x="100" y="190" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#F59E0B">402</text>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Payment Required</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        This premium educational content requires payment to access. Unlock full access to continue your learning journey with our advanced courses and features.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: #F59E0B; font-weight: bold;">ERROR_CODE: 402_PAYMENT_REQUIRED</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px;">
        <a href="{{ url('/pricing') }}" class="btn btn-primary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 1v22"></path>
                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
            </svg>
            View Pricing Plans
        </a>
        
        <a href="{{ url('/courses') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            Browse Free Courses
        </a>
        
        <a href="{{ url('/contact') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                <polyline points="22,6 12,13 2,6"></polyline>
            </svg>
            Contact Sales
        </a>
    </div>

    <!-- Pricing comparison -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 30px; background: white; border-radius: var(--radius); max-width: 800px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 22px; margin-bottom: 25px; color: var(--dark); text-align: center;">Choose Your Learning Plan</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 25px;">
            <!-- Free Plan -->
            <div style="border: 2px solid var(--gray-light); border-radius: var(--radius); padding: 25px; text-align: center; transition: var(--transition);" onmouseover="this.style.borderColor='var(--secondary)'" onmouseout="this.style.borderColor='var(--gray-light)'">
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #E5E7EB, #9CA3AF); border-radius: 12px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
                    </svg>
                </div>
                <h4 style="font-size: 18px; margin-bottom: 10px; color: var(--dark);">Free Learner</h4>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 15px; color: var(--dark);">$0<span style="font-size: 14px; color: var(--gray); font-weight: normal;">/month</span></div>
                <ul style="list-style: none; text-align: left; margin-bottom: 25px;">
                    <li style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span style="font-size: 14px;">Access to free courses</span>
                    </li>
                    <li style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span style="font-size: 14px;">Community forum access</span>
                    </li>
                    <li style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="3">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        <span style="font-size: 14px; color: var(--gray);">Premium courses</span>
                    </li>
                    <li style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="3">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                        <span style="font-size: 14px; color: var(--gray);">Certificates</span>
                    </li>
                </ul>
                <a href="{{ url('/register') }}" class="btn btn-secondary" style="width: 100%;">Get Started</a>
            </div>
            
            <!-- Premium Plan -->
            <div style="border: 2px solid var(--primary); border-radius: var(--radius); padding: 25px; text-align: center; background: linear-gradient(180deg, rgba(79, 70, 229, 0.03) 0%, rgba(79, 70, 229, 0) 100%); position: relative; transition: var(--transition);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--primary); color: white; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600;">POPULAR</div>
                <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary), #8B5CF6); border-radius: 12px; margin: 10px auto 20px; display: flex; align-items: center; justify-content: center;">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h4 style="font-size: 18px; margin-bottom: 10px; color: var(--dark);">Premium Learner</h4>
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 15px; color: var(--dark);">$19<span style="font-size: 14px; color: var(--gray); font-weight: normal;">/month</span></div>
                <ul style="list-style: none; text-align: left; margin-bottom: 25px;">
                    <li style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span style="font-size: 14px;">Everything in Free, plus:</span>
                    </li>
                    <li style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span style="font-size: 14px;">All premium courses</span>
                    </li>
                    <li style="margin-bottom: 10px; display: flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span style="font-size: 14px;">Completion certificates</span>
                    </li>
                    <li style="margin-bottom: 10px; display="flex; align-items: flex-start; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        <span style="font-size: 14px;">Priority support</span>
                    </li>
                </ul>
                <a href="{{ url('/pricing/premium') }}" class="btn btn-primary" style="width: 100%;">Upgrade Now</a>
            </div>
        </div>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid var(--gray-light); text-align: center;">
            <p style="color: var(--gray); font-size: 14px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 5px;">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                All plans include a 14-day money-back guarantee
            </p>
        </div>
    </div>
@endsection