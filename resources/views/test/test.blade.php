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
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search...">
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
                        <button class="icon-btn">
                            <img src="https://picsum.photos/seed/user123/40/40.jpg" alt="User" style="border-radius: 50%; width: 32px; height: 32px;">
                        </button>
                    </div>
                </header>

                <!-- 1. HOME SECTION -->
<section id="home" class="page-section active-section">

    <!-- Main Content Container -->
    <div class="home-main-container">
        <!-- Left: Posts Grid Container -->
        <div class="posts-main-content">
            <!-- Search Bar -->
            <div class="home-search-bar">
                <div class="search-filters">
                    <button class="filter-btn active" data-filter="latest">
                        <i class="fas fa-clock"></i> Latest
                    </button>
                    <button class="filter-btn" data-filter="oldest">
                        <i class="fas fa-history"></i> Oldest
                    </button>
                    <button class="filter-btn" data-filter="popular">
                        <i class="fas fa-fire"></i> Popular
                    </button>
                    <button class="filter-btn" data-filter="trending">
                        <i class="fas fa-chart-line"></i> Trending
                    </button>
                </div>
            </div>

            <!-- Posts Grid -->
            <div class="posts-grid" id="posts-container">
                <!-- Posts will be loaded here via AJAX -->
                <div class="loading-spinner" id="initial-loader">
                    <div class="spinner"></div>
                    <p>Loading posts...</p>
                </div>
            </div>

            <!-- Loading Spinner (for infinite scroll) -->
            <div class="loading-spinner" id="loading-spinner" style="display: none;">
                <div class="spinner"></div>
                <p>Loading more posts...</p>
            </div>

            <!-- No More Posts Message -->
            <div class="no-more-posts" id="no-more-posts" style="display: none;">
                <i class="fas fa-check-circle"></i>
                <p>You've reached the end</p>
            </div>

            <!-- No Posts Found Message -->
            <div class="no-posts-found" id="no-posts-found" style="display: none;">
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <h3>No posts found</h3>
                    <p>Be the first to create a post!</p>
                    @auth
                    <a href="{{ route('posts.create') }}" class="btn-create-post">
                        <i class="fas fa-plus"></i> Create Your First Post
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn-create-post">
                        <i class="fas fa-sign-in-alt"></i> Login to Create Posts
                    </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Right: Vertical Stacked Sidebar Cards -->
        <div class="home-sidebar">
            <!-- Profile Card -->
            <div class="sidebar-card profile-card">
                <div class="profile-header">
                    <div class="avatar-container">
                        <img src="https://picsum.photos/seed/alex/200/200.jpg" alt="Profile" class="avatar-img">
                        <div class="online-status"></div>
                    </div>
                    <div class="profile-info">
                        <h3 class="profile-name">{{ Auth::user()->fName ?? 'User' }} {{ Auth::user()->lName ?? '' }}</h3>
                        <p class="profile-role">
                            <i class="fas fa-award" style="color: #FFD700; margin-right: 5px;"></i>
                            Community Member
                        </p>
                        <div class="profile-badges">
                            <span class="badge"><i class="fas fa-star"></i> Member</span>
                            <span class="badge"><i class="fas fa-user-check"></i> Verified</span>
                        </div>
                    </div>
                </div>

                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-pencil-alt"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value">24</span>
                            <span class="stat-label">Posts</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value">186</span>
                            <span class="stat-label">Likes</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <div class="stat-details">
                            <span class="stat-value">1.2K</span>
                            <span class="stat-label">Views</span>
                        </div>
                    </div>
                </div>

                <div class="profile-actions">
                    @auth
                    <a href="{{ route('posts.create') }}" class="btn-create-post-full">
                        <i class="fas fa-plus-circle"></i>
                        <span>Create New Post</span>
                    </a>
                    @endauth
                    @if(Route::has('profile'))
                    <a href="{{ route('profile') }}" class="btn-view-profile">
                        <i class="fas fa-user-circle"></i>
                        <span>View Profile</span>
                    </a>
                    @else
                    <button class="btn-view-profile" onclick="showToast('Profile page', 'normal')">
                        <i class="fas fa-user-circle"></i>
                        <span>View Profile</span>
                    </button>
                    @endif
                </div>
            </div>

            <!-- Trending Topics Card -->
            <div class="sidebar-card trending-card">
                <div class="trending-header">
                    <h3 class="trending-title">
                        <i class="fas fa-hashtag"></i> Trending Topics
                    </h3>
                    <button class="trending-refresh" onclick="refreshTrending()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                
                <div class="trending-list">
                    <div class="trending-item">
                        <div class="trending-rank">1</div>
                        <div class="trending-content">
                            <span class="trending-tag">#Technology</span>
                            <span class="trending-count">1.2K posts</span>
                        </div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-rank">2</div>
                        <div class="trending-content">
                            <span class="trending-tag">#WebDev</span>
                            <span class="trending-count">856 posts</span>
                        </div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-rank">3</div>
                        <div class="trending-content">
                            <span class="trending-tag">#Design</span>
                            <span class="trending-count">743 posts</span>
                        </div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-rank">4</div>
                        <div class="trending-content">
                            <span class="trending-tag">#Tips</span>
                            <span class="trending-count">621 posts</span>
                        </div>
                    </div>
                    <div class="trending-item">
                        <div class="trending-rank">5</div>
                        <div class="trending-content">
                            <span class="trending-tag">#Community</span>
                            <span class="trending-count">512 posts</span>
                        </div>
                    </div>
                </div>
                
                <div class="trending-footer">
                    <a href="#" class="view-all-trends" onclick="showToast('All trends', 'normal')">
                        <span>View all topics</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="sidebar-card quick-actions-card">
                <h3 class="actions-title">
                    <i class="fas fa-bolt"></i> Quick Actions
                </h3>
                <div class="actions-grid">
                    <button class="action-item" onclick="showToast('Saved Posts', 'normal')">
                        <div class="action-icon">
                            <i class="fas fa-bookmark"></i>
                        </div>
                        <span>Saved Posts</span>
                    </button>
                    <button class="action-item" onclick="showToast('Notifications', 'normal')">
                        <div class="action-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <span>Notifications</span>
                    </button>
                    <button class="action-item" onclick="showToast('Communities', 'normal')">
                        <div class="action-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <span>Communities</span>
                    </button>
                    <button class="action-item" onclick="showToast('Settings', 'normal')">
                        <div class="action-icon">
                            <i class="fas fa-cog"></i>
                        </div>
                        <span>Settings</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Simple CSS for layout only */
    .home-main-container {
        display: flex;
        gap: 30px;
        padding: 0 20px;
        max-width: 1400px;
        margin: 0 auto;
    }

    .posts-main-content {
        flex: 1;
        min-width: 0;
    }

    .home-sidebar {
        width: 350px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 24px;
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    .sidebar-card {
        background: var(--card-bg);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }

    .home-search-bar {
        margin-bottom: 30px;
    }

    .search-filters {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        padding: 10px 0;
    }

    .filter-btn {
        padding: 14px 20px;
        border-radius: 12px;
        border: 2px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-main);
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 500;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
    }

    .filter-btn.active {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-color: transparent;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .avatar-container {
        position: relative;
        width: 80px;
        height: 80px;
    }

    .avatar-img {
        width: 100%;
        height: 100%;
        border-radius: 20px;
        object-fit: cover;
    }

    .online-status {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 12px;
        height: 12px;
        background: #4CAF50;
        border: 2px solid var(--card-bg);
        border-radius: 50%;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin: 24px 0;
        padding: 20px 0;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }

    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-create-post-full, .btn-view-profile {
        padding: 14px 20px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        text-align: center;
        cursor: pointer;
        border: none;
        width: 100%;
    }

    .btn-create-post-full {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        text-decoration: none;
    }

    .btn-view-profile {
        background: transparent;
        border: 2px solid var(--border-color);
        color: var(--text-main);
    }

    .trending-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .trending-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .trending-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        background: rgba(67, 97, 238, 0.05);
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 16px 8px;
        border-radius: 12px;
        background: rgba(67, 97, 238, 0.05);
        border: none;
        cursor: pointer;
    }

    @media (max-width: 1200px) {
        .home-main-container {
            flex-direction: column;
        }
        
        .home-sidebar {
            width: 100%;
            position: static;
            flex-direction: row;
            flex-wrap: wrap;
        }
    }

    @media (max-width: 768px) {
        .home-sidebar {
            flex-direction: column;
        }
        
        .search-filters {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Home page loaded');
        
        // Initialize posts system
        initializePostsSystem();
        
        // Initialize filter buttons
        const filterButtons = document.querySelectorAll('.filter-btn');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                const filter = this.getAttribute('data-filter');
                resetAndLoadPosts(filter);
            });
        });
    });

    function initializePostsSystem() {
        console.log('Initializing posts system...');
        loadPosts();
    }

    function loadPosts() {
        console.log('Loading posts...');
        
        // Show loading
        document.getElementById('initial-loader').style.display = 'block';
        
        // Simple test - create some dummy posts for testing
        setTimeout(() => {
            const postsContainer = document.getElementById('posts-container');
            document.getElementById('initial-loader').style.display = 'none';
            
            // Create 3 dummy posts for testing
            for (let i = 1; i <= 3; i++) {
                const postCard = document.createElement('div');
                postCard.className = 'post-card';
                postCard.innerHTML = `
                    <div class="post-content">
                        <h3 class="post-title">Test Post ${i}</h3>
                        <p class="post-excerpt">This is a test post to check if the display is working properly. Post number ${i} for testing purposes.</p>
                        
                        <div class="post-meta">
                            <div class="author-info">
                                <div class="author-avatar">U</div>
                                <div class="author-details">
                                    <span class="author-name">Test User</span>
                                    <span class="post-date">
                                        <i class="far fa-calendar"></i>
                                        Just now
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="post-stats">
                            <button class="like-btn">
                                <i class="like-icon far fa-heart"></i>
                                <span class="like-count">${i * 5}</span>
                            </button>
                            <div class="stat-item">
                                <i class="far fa-eye"></i>
                                <span>${i * 10}</span>
                            </div>
                            <div class="stat-item">
                                <i class="far fa-comment"></i>
                                <span>${i * 2}</span>
                            </div>
                        </div>
                        
                        <a href="#" class="read-more-btn" onclick="showToast('Post clicked', 'normal')">
                            Read Full Article <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                        </a>
                    </div>
                `;
                postsContainer.appendChild(postCard);
            }
        }, 1000);
    }

    function resetAndLoadPosts(filter) {
        console.log('Loading posts with filter:', filter);
        const postsContainer = document.getElementById('posts-container');
        postsContainer.innerHTML = '<div class="loading-spinner" id="initial-loader"><div class="spinner"></div><p>Loading posts...</p></div>';
        loadPosts();
    }

    function showToast(message, type = 'normal') {
        alert(message); // Simple alert for testing
    }

    function refreshTrending() {
        const refreshBtn = document.querySelector('.trending-refresh');
        refreshBtn.style.transform = 'rotate(180deg)';
        setTimeout(() => {
            refreshBtn.style.transform = 'rotate(0deg)';
            showToast('Trending topics refreshed!', 'success');
        }, 300);
    }
</script>

                <!-- 2. DASHBOARD SECTION -->
                @include('layouts.homeTabs.dashboard')

                <!-- 3. PROFILE SECTION -->
                @include('layouts.homeTabs.profile')


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
    
</body>
</html>