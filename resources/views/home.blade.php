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
                    <a href="" id="homeBtn" class="nav-item active" data-target="home">
                        <i class="fa-solid fa-house"></i>
                        <span>Home</span>
                    </a>
                    <a href="" id="profileBtn" class="nav-item" data-target="profile">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="" id="dashboardBtn" class="nav-item" data-target="dashboard">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="" id="walletBtn" class="nav-item" data-target="wallet">
                        <i class="fas fa-wallet"></i>
                        <span>Wallet</span>
                    </a>
                    <a href="" id="settingsBtn" class="nav-item" data-target="settings">
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
                            @if(session('view_profile') && session('profile_user_id'))
                                @php
                                    $profileUser = App\Models\User::find(session('profile_user_id'));
                                @endphp
                                @if($profileUser)
                                    @include('layouts.homeTabs.profile-view', ['profileUser' => $profileUser])
                                @else
                                    @include('layouts.homeTabs.my-profile')
                                @endif
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
        console.log('=== HOME PAGE INITIALIZED ===');
        
        // Debug: Log all elements
        console.log('Search elements check:');
        console.log('- Search input:', document.getElementById('searchInput'));
        console.log('- Search results:', document.getElementById('searchResults'));
        console.log('- Profile section:', document.getElementById('profile'));
        console.log('- Profile content:', document.getElementById('profileContent'));
        
        // CRITICAL FIX: Event delegation for dynamically loaded search results
        // We need to attach the event to document because search results are loaded dynamically
        document.addEventListener('click', function(e) {
            console.log('=== CLICK EVENT FIRED ===');
            console.log('Clicked element:', e.target);
            console.log('Clicked element class:', e.target.className);
            
            // Method 1: Check if clicked element or its parent is a search result
            let searchResultItem = e.target;
            
            // Traverse up to find .search-result-item
            while (searchResultItem && !searchResultItem.classList?.contains('search-result-item')) {
                searchResultItem = searchResultItem.parentElement;
                if (!searchResultItem) break;
            }
            
            if (searchResultItem && searchResultItem.classList.contains('search-result-item')) {
                console.log('FOUND SEARCH RESULT ITEM:', searchResultItem);
                const url = searchResultItem.getAttribute('data-url');
                console.log('URL from data attribute:', url);
                
                if (url && url.includes('/profile/')) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Extract user ID from URL
                    const userId = url.split('/profile/')[1];
                    console.log('Extracted User ID:', userId);
                    
                    // HIDE search results
                    const searchResults = document.getElementById('searchResults');
                    if (searchResults) {
                        searchResults.style.display = 'none';
                    }
                    
                    // Clear search input
                    const searchInput = document.getElementById('searchInput');
                    if (searchInput) {
                        searchInput.value = '';
                    }
                    
                    // SIMPLE SOLUTION: Redirect to profile URL
                    // This will trigger the route which redirects to home with session data
                    console.log('Redirecting to:', url);
                    window.location.href = url;
                    
                    return;
                }
            }
            
            // Method 2: Check for specific elements that might be inside search results
            if (e.target.classList.contains('search-result-title') || 
                e.target.classList.contains('search-result-avatar') ||
                e.target.classList.contains('search-result-content') ||
                e.target.closest('.search-result-title') ||
                e.target.closest('.search-result-avatar') ||
                e.target.closest('.search-result-content')) {
                
                console.log('Clicked inside search result');
                const resultItem = e.target.closest('.search-result-item');
                if (resultItem) {
                    const url = resultItem.getAttribute('data-url');
                    if (url && url.includes('/profile/')) {
                        e.preventDefault();
                        e.stopPropagation();
                        console.log('Navigating to profile URL:', url);
                        window.location.href = url;
                    }
                }
            }
        });
        
        // User menu button click - SIMPLE REDIRECT
        const userMenuBtn = document.getElementById('userMenuBtn');
        if (userMenuBtn) {
            userMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('User menu button clicked');
                @auth
                    window.location.href = '/profile/{{ Auth::id() }}';
                @else
                    window.location.href = '/login';
                @endauth
            });
        }
        
        // Initialize follow buttons on page load
        function initializeFollowButtons() {
            console.log('Initializing follow buttons...');
            const followButtons = document.querySelectorAll('.follow-btn');
            console.log('Found follow buttons:', followButtons.length);
            
            followButtons.forEach(button => {
                // Remove existing listeners
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                // Add click event
                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const userId = this.getAttribute('data-user-id');
                    if (!userId) return;
                    
                    console.log('Follow button clicked for user:', userId);
                    
                    // Simple form submission
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/users/${userId}/toggle-follow`;
                    form.style.display = 'none';
                    
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    document.body.appendChild(form);
                    form.submit();
                });
            });
        }
        
        // Initialize on page load
        initializeFollowButtons();
        
        // DEBUG: Test function to manually load a profile
        window.testProfileLoad = function(userId) {
            console.log('TEST: Manually loading profile for user:', userId);
            window.location.href = `/profile/${userId}`;
        };
        
        // DEBUG: Test AJAX profile load
        window.testAjaxProfileLoad = function(userId) {
            console.log('TEST AJAX: Loading profile for user:', userId);
            fetch(`/users/${userId}/profile-data`)
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Profile data:', data);
                    if (data.success && data.html) {
                        document.getElementById('profileContent').innerHTML = data.html;
                        initializeFollowButtons();
                        
                        // Switch to profile tab
                        document.querySelectorAll('.nav-item').forEach(item => {
                            item.classList.remove('active');
                        });
                        document.getElementById('profileBtn').classList.add('active');
                        
                        document.querySelectorAll('.page-section').forEach(section => {
                            section.classList.remove('active-section');
                        });
                        document.getElementById('profile').classList.add('active-section');
                    }
                })
                .catch(error => {
                    console.error('AJAX Error:', error);
                });
        };
        
        console.log('=== SETUP COMPLETE ===');
    });
</script>
    
</body>
</html>