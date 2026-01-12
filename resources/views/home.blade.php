<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Home</title>

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

    <div class="app-container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div>
                <div class="logo">
                    <div class="logo-shape"></div>
                    <span>{{ config('app.name') }}</span>
                </div>
                <nav class="nav-links">
                    <a href="#home" id="homeBtn" class="nav-item active" data-target="home">
                        <i class="fa-solid fa-house"></i>
                        <span>Home</span>
                    </a>
                    <a href="#profile" id="profileBtn" class="nav-item" data-target="profile">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                    <a href="#dashboard" id="dashboardBtn" class="nav-item" data-target="dashboard">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="#wallet" id="walletBtn" class="nav-item" data-target="wallet">
                        <i class="fas fa-wallet"></i>
                        <span>Wallet</span>
                    </a>
                    <a href="#settings" id="settingsBtn" class="nav-item" data-target="settings">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </nav>
            </div>
            <div class="nav-item nav-bottom" id="themeToggle">
                <i class="fas fa-moon" id="theme-icon themeIcon"></i>
                <span id="theme-text themeText">Dark Mode</span>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- Centering wrapper for very wide screens -->
            <div class="content-container">
                
                <!-- HEADER -->
                <header class="top-bar">
                    <div style="display: flex; align-items: center; flex: 1;">
                        <button class="menu-toggle" onclick="toggleSidebar()">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="search-wrapper" id="searchWrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" id="searchInput" placeholder="Search users, posts..." autocomplete="off">
                            
                            <!-- Live Search Results Dropdown -->
                            <div class="search-results-dropdown" id="searchResults" style="display: none;">
                                <div class="search-results-container">
                                    <div class="search-results-header">
                                        <span>Search Results</span>
                                        <small id="resultsCount">0 results</small>
                                    </div>
                                    <div class="search-results-body" id="searchResultsBody">
                                        <!-- Results will be loaded here -->
                                    </div>
                                    <div class="search-results-footer">
                                        <a href="#" id="viewAllResults" style="display: none;">View all results</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="header-actions">
                        <button class="icon-btn" onclick="showToast('Notifications checked', 'success')">
                            <i class="far fa-bell"></i>
                            <div class="notification-dot" style="position: absolute; top: 0; right: 0; width: 8px; height: 8px; background: var(--accent-color); border-radius: 50%; border: 2px solid var(--card-bg);"></div>
                        </button>
                        <button class="icon-btn" onclick="showToast('Messages inbox empty', 'normal')">
                            <i class="far fa-envelope"></i>
                        </button>
                        <button class="icon-btn" id="userMenuBtn">
                            <img src="{{ Auth::check() ? Auth::user()->small_avatar_url : 'https://picsum.photos/seed/user123/40/40.jpg' }}" alt="User" style="border-radius: 50%; width: 32px; height: 32px;">
                        </button>
                    </div>
                </header>

                <!-- 1. HOME SECTION -->
                @include('layouts.homeTabs.home')

                <!-- 2. DASHBOARD SECTION -->
                @include('layouts.homeTabs.dashboard')

                <!-- 3. PROFILE SECTION -->
                <section id="profile" class="page-section">
                    <div class="content-container">
                        <!-- Dynamic Profile Content will be loaded here -->
                        <div id="profileContent">
                            @php
                                // Check for direct profile access first
                                if(session('direct_profile_access') && session('profile_user_id')) {
                                    $profileUser = App\Models\User::find(session('profile_user_id'));
                                    session()->forget('direct_profile_access');
                                    
                                    // Make sure profile tab is active
                                    echo '<script>document.addEventListener("DOMContentLoaded", function() { switchToProfileTab(); });</script>';
                                }
                            @endphp
                            
                            @if(isset($profileUser) && $profileUser)
                                @include('layouts.homeTabs.profile-view', ['profileUser' => $profileUser])
                            @else
                                @include('layouts.homeTabs.my-profile')
                            @endif
                        </div>
                    </div>
                </section>

                <!-- 4. WALLET SECTION -->
                <section id="wallet" class="page-section">
                    <div class="dashboard-grid">
                        <div class="widget-card" style="background: linear-gradient(135deg, #3a0ca3, #4361ee); color: white;">
                            <div style="display: flex; justify-content: space-between; align-items: center;"><span>Total Balance</span><i class="fab fa-cc-visa" style="font-size: 2rem; opacity: 0.8;"></i></div>
                            <h2 style="font-size: 2.5rem; margin: 20px 0;">$12,450.00</h2>
                            <div style="display: flex; gap: 10px;"><button class="btn" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(5px);">Deposit</button><button class="btn" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(5px);">Withdraw</button></div>
                        </div>
                        <div class="widget-card">
                            <h3>Quick Transfer</h3>
                            <div style="margin-top: 20px; display: flex; gap: 10px; align-items: center;"><input type="text" placeholder="Wallet Address" style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-color); color: var(--text-main);"><button class="btn btn-primary" onclick="showToast('Transfer Initiated', 'success')">Send</button></div>
                        </div>
                    </div>
                </section>

                <!-- 5. SETTINGS SECTION -->
                @include('layouts.homeTabs.settings')

            </div> <!-- End  Content Container -->
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <script src="{{ asset('js/home.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Home page initialized');
        
        // ===========================================
        // 1. SEARCH RESULT CLICK HANDLER - FIXED
        // ===========================================
        document.addEventListener('click', function(e) {
            // Handle search result clicks
            const searchResultItem = e.target.closest('.search-result-item');
            
            if (searchResultItem) {
                const url = searchResultItem.getAttribute('data-url');
                console.log('Search result clicked, URL:', url);
                
                if (url && url.includes('/profile/')) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Extract user ID from URL
                    const userId = url.split('/profile/')[1];
                    console.log('Loading profile for user ID:', userId);
                    
                    // Hide search results
                    document.getElementById('searchResults').style.display = 'none';
                    document.getElementById('searchInput').value = '';
                    
                    // CRITICAL FIX: Switch to profile tab FIRST
                    switchToProfileTab();
                    
                    // Then load the profile
                    loadUserProfile(userId);
                    
                    return;
                }
            }
            
            // Handle follow button clicks
            const followBtn = e.target.closest('.follow-btn');
            if (followBtn && followBtn.hasAttribute('data-user-id')) {
                e.preventDefault();
                const userId = followBtn.getAttribute('data-user-id');
                toggleFollow(userId, followBtn);
                return;
            }
            
            // Handle back button clicks
            if (e.target.closest('[onclick*="loadMyProfile"]') || 
                e.target.closest('[data-action="back-to-profile"]')) {
                e.preventDefault();
                loadMyProfile();
                return;
            }
        });
        
        // ===========================================
        // 2. USER MENU BUTTON CLICK
        // ===========================================
        document.getElementById('userMenuBtn').addEventListener('click', function(e) {
            e.preventDefault();
            switchToProfileTab();
            loadMyProfile();
        });
        
        // ===========================================
        // 3. PROFILE LOADING FUNCTIONS
        // ===========================================
        function loadUserProfile(userId) {
            console.log('AJAX loading profile for user:', userId);
            
            showToast('Loading profile...', 'normal');
            
            fetch(`/users/${userId}/profile-data`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('profileContent').innerHTML = data.html;
                    console.log('Profile loaded successfully');
                    showToast('Profile loaded', 'success');
                    
                    // Re-initialize follow buttons in the loaded profile
                    initializeFollowButtons();
                } else {
                    console.error('Failed to load profile:', data);
                    showToast('Failed to load profile', 'error');
                }
            })
            .catch(error => {
                console.error('Error loading profile:', error);
                showToast('Error loading profile', 'error');
            });
        }
        
        function loadMyProfile() {
            console.log('Loading my profile');
            
            showToast('Loading your profile...', 'normal');
            
            fetch('/my-profile-data', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('profileContent').innerHTML = data.html;
                    console.log('My profile loaded successfully');
                    showToast('Profile loaded', 'success');
                    
                    // Re-initialize follow buttons
                    initializeFollowButtons();
                } else {
                    console.error('Failed to load my profile:', data);
                    showToast('Failed to load profile', 'error');
                }
            })
            .catch(error => {
                console.error('Error loading my profile:', error);
                showToast('Error loading profile', 'error');
            });
        }
        
        // ===========================================
        // 4. TAB SWITCHING FUNCTION - FIXED
        // ===========================================
        function switchToProfileTab() {
            console.log('Switching to profile tab');
            
            // 1. Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // 2. Remove active-section from all sections
            document.querySelectorAll('.page-section').forEach(section => {
                section.classList.remove('active-section');
            });
            
            // 3. Add active class to profile nav item
            document.getElementById('profileBtn').classList.add('active');
            
            // 4. Show profile section
            document.getElementById('profile').classList.add('active-section');
            
            // 5. Update URL hash (optional, for browser history)
            window.history.pushState(null, null, '#profile');
            
            console.log('Profile tab activated');
        }
        
        // ===========================================
        // 5. FOLLOW BUTTON HANDLING
        // ===========================================
        function initializeFollowButtons() {
            console.log('Initializing follow buttons...');
            
            // Remove existing event listeners
            document.querySelectorAll('.follow-btn').forEach(button => {
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
            });
            
            // Add fresh event listeners
            document.querySelectorAll('.follow-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const userId = this.getAttribute('data-user-id');
                    if (!userId) return;
                    
                    console.log('Toggling follow for user:', userId);
                    
                    // Store original state
                    const originalHTML = this.innerHTML;
                    const originalClasses = this.className;
                    
                    // Show loading state
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                    this.disabled = true;
                    
                    // Send request
                    fetch(`/users/${userId}/toggle-follow`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        this.disabled = false;
                        
                        if (data.success) {
                            // Update button appearance
                            if (data.action === 'followed') {
                                this.innerHTML = '<i class="fas fa-user-check"></i> Following';
                                this.classList.add('following');
                                this.classList.remove('btn-primary');
                                this.classList.add('btn-outline');
                            } else {
                                this.innerHTML = '<i class="fas fa-user-plus"></i> Follow';
                                this.classList.remove('following');
                                this.classList.add('btn-primary');
                                this.classList.remove('btn-outline');
                            }
                            
                            // Update followers count
                            const followersCountEl = document.getElementById('followersCount');
                            if (followersCountEl && data.followers_count !== undefined) {
                                followersCountEl.textContent = data.followers_count;
                            }
                            
                            showToast(data.message || 'Success', 'success');
                        } else {
                            // Revert to original state
                            this.innerHTML = originalHTML;
                            this.className = originalClasses;
                            showToast(data.message || 'Failed', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Follow error:', error);
                        this.disabled = false;
                        this.innerHTML = originalHTML;
                        this.className = originalClasses;
                        showToast('Network error', 'error');
                    });
                });
            });
            
            console.log('Follow buttons initialized');
        }
        
        // ===========================================
        // 6. TOGGLE FOLLOW FUNCTION
        // ===========================================
        function toggleFollow(userId, buttonElement) {
            console.log('Toggle follow called for user:', userId);
            
            // This is handled by the event listener above
            // We trigger a click on the button
            buttonElement.click();
        }
        
        // ===========================================
        // 7. TOAST FUNCTION
        // ===========================================
        function showToast(message, type = 'normal') {
            const container = document.getElementById('toastContainer');
            if (!container) {
                const newContainer = document.createElement('div');
                newContainer.id = 'toastContainer';
                newContainer.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 1000;';
                document.body.appendChild(newContainer);
            }
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let icon = 'fa-info-circle';
            if (type === 'success') icon = 'fa-check-circle';
            if (type === 'error') icon = 'fa-exclamation-circle';
            
            toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
            document.getElementById('toastContainer').appendChild(toast);
            
            setTimeout(() => toast.classList.add('show'), 10);
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
        
        // ===========================================
        // 8. INITIALIZE ON PAGE LOAD
        // ===========================================
        
        // Check if we should show a specific profile on page load
        @if(session('view_profile') && session('profile_user_id'))
            console.log('Showing profile from session');
            // The PHP code in the blade template already handles this
            // But we need to make sure the profile tab is active
            setTimeout(() => {
                switchToProfileTab();
            }, 100);
        @endif
        
        // Initialize follow buttons for any existing content
        initializeFollowButtons();
        
        // ===========================================
        // 9. FIX NAVIGATION SYSTEM
        // ===========================================
        
        // Store the original navigation click handler
        const originalNavClickHandler = function(e) {
            e.preventDefault();
            
            // Remove active class from all nav items
            document.querySelectorAll('.nav-item').forEach(nav => {
                nav.classList.remove('active');
            });
            
            // Add active class to clicked nav item
            this.classList.add('active');
            
            // Hide all sections
            document.querySelectorAll('.page-section').forEach(sec => {
                sec.classList.remove('active-section');
            });
            
            // Show the target section
            const targetId = this.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active-section');
            
            // Close sidebar on mobile
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('active');
            }
        };
        
        // Re-attach navigation click handlers
        document.querySelectorAll('.nav-item[data-target]').forEach(item => {
            item.removeEventListener('click', originalNavClickHandler);
            item.addEventListener('click', originalNavClickHandler);
        });
        
        console.log('JavaScript setup complete');
        
        // ===========================================
        // 10. MAKE FUNCTIONS AVAILABLE GLOBALLY
        // ===========================================
        window.loadUserProfile = loadUserProfile;
        window.loadMyProfile = loadMyProfile;
        window.switchToProfileTab = switchToProfileTab;
        window.initializeFollowButtons = initializeFollowButtons;
    });
</script>
    
</body>
</html>