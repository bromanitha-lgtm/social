@extends('errors.minimal')

@section('title', 'Service Unavailable')

@section('content')
    <!-- 503 Animation Container -->
    <div class="error-animation-container">
        <!-- Animated elements -->
        <div class="error-animation" style="position: absolute; top: 15%; left: 25%; width: 45px; height: 45px; background-color: var(--warning); border-radius: 50%; opacity: 0.7; animation: float 5s ease-in-out infinite;"></div>
        <div class="error-animation" style="position: absolute; top: 70%; left: 20%; width: 35px; height: 35px; background-color: var(--primary); border-radius: 50%; opacity: 0.5; animation: float 4.5s ease-in-out infinite reverse;"></div>
        <div class="error-animation" style="position: absolute; top: 40%; left: 75%; width: 40px; height: 40px; background-color: var(--secondary); border-radius: 50%; opacity: 0.6; animation: float 6s ease-in-out infinite;"></div>
        
        <!-- Main 503 illustration -->
        <div style="position: relative; z-index: 10;">
            <svg width="200" height="200" viewBox="0 0 200 200" class="error-animation pulse" style="animation-duration: 3s;">
                <!-- Cloud with tools -->
                <path d="M50,100 Q70,70 100,70 Q130,70 150,100 Q180,100 180,130 Q180,160 150,160 L50,160 Q20,160 20,130 Q20,100 50,100" fill="#E5E7EB" stroke="#9CA3AF" stroke-width="2"/>
                
                <!-- Gear 1 -->
                <circle cx="80" cy="110" r="20" fill="#6B7280" stroke="#4B5563" stroke-width="2"/>
                <path d="M80,90 L80,130 M70,110 L90,110 M65,95 L75,85 M95,125 L85,135 M65,125 L75,135 M95,95 L85,85" stroke="#D1D5DB" stroke-width="3" stroke-linecap="round">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" from="0 80 110" to="360 80 110" dur="10s" repeatCount="indefinite"/>
                </path>
                
                <!-- Gear 2 -->
                <circle cx="120" cy="130" r="15" fill="#9CA3AF" stroke="#6B7280" stroke-width="2"/>
                <path d="M120,115 L120,145 M110,130 L130,130 M105,120 L115,110 M135,140 L125,150 M105,140 L115,150 M135,120 L125,110" stroke="#E5E7EB" stroke-width="2" stroke-linecap="round">
                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" from="360 120 130" to="0 120 130" dur="8s" repeatCount="indefinite"/>
                </path>
                
                <!-- Wrench -->
                <path d="M160,90 L175,75 L185,85 L170,100 L165,95 L175,85 L170,80 L165,85" fill="#F59E0B" stroke="#D97706" stroke-width="2"/>
                
                <!-- 503 text -->
                <text x="100" y="190" text-anchor="middle" font-family="Poppins" font-size="32" font-weight="bold" fill="#6B7280">503</text>
            </svg>
        </div>
    </div>

    <!-- Error Message -->
    <h1 class="error-animation slide-up" style="font-size: 48px; margin-bottom: 20px; color: var(--dark);">Service Unavailable</h1>
    <p class="error-animation slide-up" style="font-size: 18px; color: var(--gray); max-width: 600px; margin: 0 auto 40px;">
        Our educational platform is temporarily undergoing maintenance to improve your learning experience. We're working hard to bring it back online as soon as possible.
    </p>

    <!-- Error Code -->
    <div class="error-animation slide-up error-code" style="background: white; padding: 15px 30px; border-radius: var(--radius); display: inline-block; margin-bottom: 40px; box-shadow: var(--shadow);">
        <code style="font-family: 'Courier New', monospace; color: #6B7280; font-weight: bold;">ERROR_CODE: 503_SERVICE_UNAVAILABLE</code>
    </div>

    <!-- Action Buttons -->
    <div class="error-animation slide-up" style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap; margin-bottom: 40px;">
        <button onclick="window.location.reload()" class="btn btn-primary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M23 4v6h-6"></path>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            Check Again
        </button>
        
        <a href="{{ url('/status') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
            </svg>
            Check System Status
        </a>
        
        <a href="{{ url('/offline-resources') }}" class="btn btn-secondary">
            <svg style="margin-right: 8px;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
            </svg>
            Access Offline Resources
        </a>
    </div>

    <!-- Maintenance information -->
    <div class="error-animation slide-up" style="margin-top: 40px; padding: 25px; background: white; border-radius: var(--radius); max-width: 700px; margin-left: auto; margin-right: auto; box-shadow: var(--shadow);">
        <h3 style="font-size: 18px; margin-bottom: 20px; color: var(--dark);">Scheduled Maintenance</h3>
        
        <!-- Maintenance progress -->
        <div style="margin-bottom: 30px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 14px; color: var(--gray);">Maintenance Progress</span>
                <span style="font-size: 14px; color: var(--dark); font-weight: 600;" id="maintenanceProgress">65%</span>
            </div>
            <div style="height: 10px; background-color: #E5E7EB; border-radius: 5px; overflow: hidden;">
                <div id="maintenanceBar" style="height: 100%; width: 65%; background: linear-gradient(90deg, var(--primary), var(--secondary)); transition: width 2s ease-in-out;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; margin-top: 8px;">
                <span style="font-size: 12px; color: var(--gray);">Started: 02:00 AM</span>
                <span style="font-size: 12px; color: var(--gray);">Estimated completion: 04:30 AM</span>
            </div>
        </div>
        
        <!-- Status indicators -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="text-align: center;">
                <div style="width: 16px; height: 16px; background-color: #10B981; border-radius: 50%; display: inline-block; margin-right: 8px;"></div>
                <span style="font-size: 14px; color: var(--dark);">Authentication</span>
                <div style="font-size: 12px; color: var(--gray);">Operational</div>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 16px; height: 16px; background-color: #F59E0B; border-radius: 50%; display: inline-block; margin-right: 8px;"></div>
                <span style="font-size: 14px; color: var(--dark);">Course Content</span>
                <div style="font-size: 12px; color: var(--gray);">Under Maintenance</div>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 16px; height: 16px; background-color: #EF4444; border-radius: 50%; display: inline-block; margin-right: 8px;"></div>
                <span style="font-size: 14px; color: var(--dark);">Video Streaming</span>
                <div style="font-size: 12px; color: var(--gray);">Offline</div>
            </div>
            
            <div style="text-align: center;">
                <div style="width: 16px; height: 16px; background-color: #10B981; border-radius: 50%; display: inline-block; margin-right: 8px;"></div>
                <span style="font-size: 14px; color: var(--dark);">User Dashboard</span>
                <div style="font-size: 12px; color: var(--gray);">Operational</div>
            </div>
        </div>
        
        <!-- What we're improving -->
        <div style="background-color: #F8FAFC; padding: 20px; border-radius