<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Home</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        .toast {
            background: var(--card-bg);
            color: var(--text-main);
            padding: 16px 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow);
            border-left: 4px solid var(--primary-color);
            display: flex;
            align-items: center;
            gap: 12px;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            min-width: 250px;
        }
        
        .toast.show { transform: translateX(0); }
        .toast.success { border-left-color: #28a745; }
        .toast.error { border-left-color: #dc3545; }
        .toast.normal { border-left-color: var(--primary-color); }
        
        .search-result-item {
            cursor: pointer;
        }
        
        .search-result-item:hover {
            background: rgba(67, 97, 238, 0.1);
        }
        
        .post-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .like-btn {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .like-btn:hover {
            transform: scale(1.1);
        }
        
        .share-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .share-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .share-modal-content {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 25px;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        
        .share-options {
            display: flex;
            gap: 15px;
            margin-top: 20px;
            flex-wrap: wrap;
        }
        
        .share-option {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 10px;
            border-radius: 8px;
            transition: background 0.3s;
        }
        
        .share-option:hover {
            background: rgba(67, 97, 238, 0.1);
        }
        
        .share-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .facebook-bg { background: #3b5998; }
        .twitter-bg { background: #1da1f2; }
        .whatsapp-bg { background: #25d366; }
        .copy-bg { background: var(--primary-color); }
    </style>
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
                <i class="fas fa-moon" id="theme-icon"></i>
                <span id="theme-text">Dark Mode</span>
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
                            <div class="notification-dot"></div>
                        </button>
                        <button class="icon-btn" onclick="showToast('Messages inbox empty', 'normal')">
                            <i class="far fa-envelope"></i>
                        </button>
                        <button class="icon-btn" id="userMenuBtn">
                            <img src="{{ Auth::check() ? Auth::user()->small_avatar_url : 'https://ui-avatars.com/api/?name=User&background=random&color=fff&size=60' }}" alt="User" id="userAvatarImg" style="border-radius: 50%; width: 32px; height: 32px;">
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
                            @if(isset($profileUser) && $profileUser)
                                @include('layouts.homeTabs.profile-view', ['profileUser' => $profileUser])
                            @else
                                @include('layouts.homeTabs.my-profile')
                            @endif
                        </div>
                    </div>
                </section>

                <!-- 4. WALLET SECTION -->
                {{-- @include('layouts.homeTabs.wallet') --}}

                <!-- 5. SETTINGS SECTION -->
                @include('layouts.homeTabs.settings')

            </div> <!-- End  Content Container -->
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Share Modal -->
    <div class="share-modal" id="shareModal">
        <div class="share-modal-content">
            <h3>Share User Profile</h3>
            <p id="shareProfileName"></p>
            <div class="share-options">
                <div class="share-option" onclick="shareOnFacebook()">
                    <div class="share-icon facebook-bg">
                        <i class="fab fa-facebook-f"></i>
                    </div>
                    <span>Facebook</span>
                </div>
                <div class="share-option" onclick="shareOnTwitter()">
                    <div class="share-icon twitter-bg">
                        <i class="fab fa-twitter"></i>
                    </div>
                    <span>Twitter</span>
                </div>
                <div class="share-option" onclick="shareOnWhatsApp()">
                    <div class="share-icon whatsapp-bg">
                        <i class="fab fa-whatsapp"></i>
                    </div>
                    <span>WhatsApp</span>
                </div>
                <div class="share-option" onclick="copyProfileLink()">
                    <div class="share-icon copy-bg">
                        <i class="fas fa-copy"></i>
                    </div>
                    <span>Copy Link</span>
                </div>
            </div>
            <button class="btn btn-outline" style="width: 100%; margin-top: 20px;" onclick="closeShareModal()">Close</button>
        </div>
    </div>
    
    <script src="{{ asset('js/home.js') }}"></script>
    
    <script>
        // Global variables
        let currentProfileUserId = null;
        let currentShareUrl = null;
        let currentShareTitle = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Home page initialized');
            
            // ===========================================
            // 1. TAB STATE MANAGEMENT
            // ===========================================
            function initializeTabState() {
                // Save active tab on change
                document.querySelectorAll('.nav-item[data-target]').forEach(item => {
                    item.addEventListener('click', function() {
                        const target = this.getAttribute('data-target');
                        localStorage.setItem('activeTab', target);
                        
                        // If switching to profile tab, clear any stored profile user ID
                        if (target !== 'profile') {
                            localStorage.removeItem('profileUserId');
                        }
                    });
                });
                
                // Restore active tab on page load
                const activeTab = localStorage.getItem('activeTab') || 'home';
                const profileUserId = localStorage.getItem('profileUserId');
                
                // Always default to home tab on page load unless explicitly coming from a profile
                if (activeTab === 'profile' && !profileUserId) {
                    localStorage.setItem('activeTab', 'home');
                    switchToTab('home');
                } else {
                    switchToTab(activeTab);
                }
                
                // If there's a profile user ID in storage, load that profile
                if (profileUserId) {
                    setTimeout(() => {
                        loadUserProfile(profileUserId);
                    }, 500);
                }
            }
            
            // ===========================================
            // 2. TAB SWITCHING FUNCTION
            // ===========================================
            function switchToTab(tabId) {
                console.log('Switching to tab:', tabId);
                
                // Remove active class from all nav items
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Remove active-section from all sections
                document.querySelectorAll('.page-section').forEach(section => {
                    section.classList.remove('active-section');
                });
                
                // Add active class to selected nav item
                const tabBtn = document.getElementById(tabId + 'Btn');
                if (tabBtn) {
                    tabBtn.classList.add('active');
                }
                
                // Show selected section
                const tabSection = document.getElementById(tabId);
                if (tabSection) {
                    tabSection.classList.add('active-section');
                }
                
                // Update URL hash
                window.history.pushState(null, null, '#' + tabId);
                
                console.log('Tab switched to:', tabId);
            }
            
            // ===========================================
            // 3. NAVIGATION CLICK HANDLERS
            // ===========================================
            document.querySelectorAll('.nav-item[data-target]').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    
                    // If clicking profile tab without a specific user, load own profile
                    if (targetId === 'profile') {
                        const profileUserId = localStorage.getItem('profileUserId');
                        if (profileUserId) {
                            loadUserProfile(profileUserId);
                        } else {
                            loadMyProfile();
                        }
                    }
                    
                    // Switch tab
                    switchToTab(targetId);
                    
                    // Close sidebar on mobile
                    if (window.innerWidth <= 768) {
                        document.getElementById('sidebar').classList.remove('active');
                    }
                });
            });
            
            // ===========================================
            // 4. SEARCH RESULT CLICK HANDLER
            // ===========================================
            document.addEventListener('click', function(e) {
                // Handle search result clicks
                const searchResultItem = e.target.closest('.search-result-item');
                
                if (searchResultItem) {
                    const url = searchResultItem.getAttribute('data-url');
                    const type = searchResultItem.classList.contains('user') ? 'user' : 'post';
                    
                    if (url) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        // Hide search results
                        document.getElementById('searchResults').style.display = 'none';
                        document.getElementById('searchInput').value = '';
                        
                        if (type === 'user') {
                            // Extract user ID from URL
                            const userId = url.split('/profile/')[1];
                            
                            // Store profile user ID in localStorage
                            localStorage.setItem('profileUserId', userId);
                            localStorage.setItem('activeTab', 'profile');
                            
                            // Switch to profile tab
                            switchToTab('profile');
                            
                            // Load the profile
                            loadUserProfile(userId);
                        } else if (type === 'post') {
                            // For posts, redirect directly
                            window.location.href = url;
                        }
                        
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
                    
                    // Clear stored profile user ID
                    localStorage.removeItem('profileUserId');
                    
                    // Load my profile
                    loadMyProfile();
                    return;
                }
                
                // Handle share button clicks
                const shareBtn = e.target.closest('.share-profile-btn');
                if (shareBtn && shareBtn.hasAttribute('data-user-id')) {
                    e.preventDefault();
                    const userId = shareBtn.getAttribute('data-user-id');
                    const userName = shareBtn.getAttribute('data-user-name');
                    openShareModal(userId, userName);
                    return;
                }
                
                // Handle post clicks in profile view
                const postCard = e.target.closest('.post-card');
                if (postCard && postCard.hasAttribute('data-post-id')) {
                    e.preventDefault();
                    const postId = postCard.getAttribute('data-post-id');
                    window.location.href = '/posts/' + postId;
                    return;
                }
            });
            
            // ===========================================
            // 5. USER MENU BUTTON CLICK
            // ===========================================
            document.getElementById('userMenuBtn').addEventListener('click', function(e) {
                e.preventDefault();
                
                // Clear any stored profile user ID
                localStorage.removeItem('profileUserId');
                localStorage.setItem('activeTab', 'profile');
                
                // Switch to profile tab
                switchToTab('profile');
                
                // Load my profile
                loadMyProfile();
            });
            
            // ===========================================
            // 6. PROFILE LOADING FUNCTIONS
            // ===========================================
            function loadUserProfile(userId) {
                console.log('AJAX loading profile for user:', userId);
                
                showToast('Loading profile...', 'normal');
                
                currentProfileUserId = userId;
                
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
                        
                        // Initialize post interactions
                        initializePostInteractions();
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
                
                currentProfileUserId = null;
                
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
            // 7. FOLLOW BUTTON HANDLING
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
            // 8. POST INTERACTIONS IN PROFILE
            // ===========================================
            function initializePostInteractions() {
                // Like buttons in profile posts
                document.querySelectorAll('.profile-post-like-btn').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        
                        const postId = this.getAttribute('data-post-id');
                        const isLiked = this.classList.contains('liked');
                        
                        togglePostLike(postId, this, isLiked);
                    });
                });
                
                // Post cards click
                document.querySelectorAll('.post-card[data-post-id]').forEach(card => {
                    card.style.cursor = 'pointer';
                });
            }
            
            function togglePostLike(postId, buttonElement, isLiked) {
                const likeIcon = buttonElement.querySelector('i');
                const likeCount = buttonElement.querySelector('.like-count');
                const currentCount = parseInt(likeCount.textContent) || 0;
                
                // Immediate UI update
                if (isLiked) {
                    buttonElement.classList.remove('liked');
                    likeIcon.className = 'far fa-heart';
                    likeCount.textContent = Math.max(0, currentCount - 1);
                } else {
                    buttonElement.classList.add('liked');
                    likeIcon.className = 'fas fa-heart';
                    likeCount.textContent = currentCount + 1;
                }
                
                // Send AJAX request
                const url = isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`;
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.status === 401) {
                        window.location.href = '/login';
                        return null;
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data) return;
                    
                    if (data.success) {
                        // Update with server count
                        if (data.likes_count !== undefined) {
                            likeCount.textContent = data.likes_count;
                        }
                        showToast(data.message || (isLiked ? 'Unliked!' : 'Liked!'), 'success');
                    } else {
                        // Revert UI
                        if (isLiked) {
                            buttonElement.classList.add('liked');
                            likeIcon.className = 'fas fa-heart';
                            likeCount.textContent = currentCount;
                        } else {
                            buttonElement.classList.remove('liked');
                            likeIcon.className = 'far fa-heart';
                            likeCount.textContent = currentCount;
                        }
                        showToast(data.message || 'Failed', 'error');
                    }
                })
                .catch(error => {
                    console.error('Like error:', error);
                    // Revert UI on error
                    if (isLiked) {
                        buttonElement.classList.add('liked');
                        likeIcon.className = 'fas fa-heart';
                        likeCount.textContent = currentCount;
                    } else {
                        buttonElement.classList.remove('liked');
                        likeIcon.className = 'far fa-heart';
                        likeCount.textContent = currentCount;
                    }
                    showToast('Network error', 'error');
                });
            }
            
            // ===========================================
            // 9. SHARE FUNCTIONALITY
            // ===========================================
            function openShareModal(userId, userName) {
                currentShareUrl = window.location.origin + '/profile/' + userId;
                currentShareTitle = `Check out ${userName}'s profile on {{ config('app.name') }}`;
                
                document.getElementById('shareProfileName').textContent = `Share ${userName}'s profile`;
                document.getElementById('shareModal').classList.add('show');
            }
            
            function closeShareModal() {
                document.getElementById('shareModal').classList.remove('show');
            }
            
            function shareOnFacebook() {
                const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentShareUrl)}`;
                window.open(shareUrl, '_blank', 'width=600,height=400');
                showToast('Opening Facebook share...', 'normal');
                closeShareModal();
            }
            
            function shareOnTwitter() {
                const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(currentShareTitle)}&url=${encodeURIComponent(currentShareUrl)}`;
                window.open(shareUrl, '_blank', 'width=600,height=400');
                showToast('Opening Twitter share...', 'normal');
                closeShareModal();
            }
            
            function shareOnWhatsApp() {
                const shareUrl = `https://wa.me/?text=${encodeURIComponent(currentShareTitle + ' ' + currentShareUrl)}`;
                window.open(shareUrl, '_blank', 'width=600,height=400');
                showToast('Opening WhatsApp share...', 'normal');
                closeShareModal();
            }
            
            function copyProfileLink() {
                navigator.clipboard.writeText(currentShareUrl).then(() => {
                    showToast('Profile link copied to clipboard!', 'success');
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    showToast('Failed to copy link', 'error');
                });
                closeShareModal();
            }
            
            // ===========================================
            // 10. TOAST FUNCTION
            // ===========================================
            window.showToast = function(message, type = 'normal') {
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
            };
            
            // ===========================================
            // 11. INITIALIZE ON PAGE LOAD
            // ===========================================
            initializeTabState();
            
            // Check for direct profile access on page load
            const urlParams = new URLSearchParams(window.location.search);
            const profileId = urlParams.get('profile');
            
            if (profileId) {
                localStorage.setItem('profileUserId', profileId);
                localStorage.setItem('activeTab', 'profile');
                switchToTab('profile');
                loadUserProfile(profileId);
            }
            
            // Initialize follow buttons for any existing content
            initializeFollowButtons();
            
            // Initialize post interactions
            initializePostInteractions();
            
            // Close share modal when clicking outside
            document.getElementById('shareModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeShareModal();
                }
            });
            
            console.log('JavaScript setup complete');
            
            // ===========================================
            // 12. MAKE FUNCTIONS AVAILABLE GLOBALLY
            // ===========================================
            window.loadUserProfile = loadUserProfile;
            window.loadMyProfile = loadMyProfile;
            window.switchToTab = switchToTab;
            window.initializeFollowButtons = initializeFollowButtons;
            window.openShareModal = openShareModal;
            window.closeShareModal = closeShareModal;
            window.shareOnFacebook = shareOnFacebook;
            window.shareOnTwitter = shareOnTwitter;
            window.shareOnWhatsApp = shareOnWhatsApp;
            window.copyProfileLink = copyProfileLink;
        });
    </script>
</body>
</html>