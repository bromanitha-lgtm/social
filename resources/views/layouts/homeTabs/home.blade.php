<section id="home" class="page-section active-section">
    <!-- Header with Create Post Button -->
    <div class="section-header">
        <h2 class="section-title">Latest Posts</h2>
        @auth
        <a href="{{ route('posts.create') }}" class="btn-create-post">
            <i class="fas fa-plus"></i> Create Post
        </a>
        @endauth
    </div>

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

        <!-- Right: Fixed Profile Card -->
        <div class="home-sidebar">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h3 class="profile-name">John Doe</h3>
                        <p class="profile-role">Community Member</p>
                    </div>
                </div>
                <div class="profile-stats">
                    <div class="stat-item">
                        <i class="fas fa-pen"></i>
                        <div class="stat-details">
                            <span class="stat-value">12</span>
                            <span class="stat-label">Posts</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-heart"></i>
                        <div class="stat-details">
                            <span class="stat-value">47</span>
                            <span class="stat-label">Likes</span>
                        </div>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-comment"></i>
                        <div class="stat-details">
                            <span class="stat-value">23</span>
                            <span class="stat-label">Comments</span>
                        </div>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="btn-profile-action">
                        <i class="fas fa-cog"></i> Settings
                    </button>
                    <button class="btn-profile-action">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </div>
            </div>

            <!-- Trending Topics -->
            <div class="trending-card">
                <h3 class="trending-title">
                    <i class="fas fa-hashtag"></i> Trending Topics
                </h3>
                <div class="trending-tags">
                    <span class="trending-tag">#Technology</span>
                    <span class="trending-tag">#Design</span>
                    <span class="trending-tag">#Development</span>
                    <span class="trending-tag">#Tips</span>
                    <span class="trending-tag">#Community</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
    /* Posts Section Styles */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding: 0 20px;
    }

    .section-title {
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .btn-create-post {
        background: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .btn-create-post:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        color: white;
    }

    /* Main Container */
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

    /* Right Sidebar */
    .home-sidebar {
        width: 320px;
        flex-shrink: 0;
        position: sticky;
        top: 100px;
        height: fit-content;
    }

    /* Search Bar - FIXED */
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .filter-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }

    .filter-btn:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
    }

    .filter-btn:hover::before {
        left: 100%;
    }

    .filter-btn.active {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-color: transparent;
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.25);
        transform: translateY(-2px);
    }

    .filter-btn.active:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 25px rgba(67, 97, 238, 0.3);
    }

    .filter-btn i {
        font-size: 1rem;
        transition: transform 0.3s;
    }

    .filter-btn.active i {
        transform: scale(1.1);
    }

    /* Posts Grid */
    .posts-grid {
        display: grid;
        grid-template-columns: repeat(1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    /* Post Card */
    .post-card {
        background: var(--card-bg);
        border-radius: 16px;
        overflow: hidden;
        max-width: 580px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--border-color);
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease forwards;
        display: flex;
        flex-direction: column;
        cursor: pointer;
        position: relative;
        margin-left: auto;
        margin-right: auto;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .post-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    /* Post Image */
    .post-image-container {
        position: relative;
        height: fit-content;
        max-height: 580px;
        overflow: hidden;
        display: none;
    }

    .post-card.has-image .post-image-container {
        display: block;
    }

    .post-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .post-card:hover .post-image {
        transform: scale(1.05);
    }

    /* Post Content */
    .post-content {
        padding: 24px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .post-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 12px;
        color: var(--text-main);
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .post-excerpt {
        color: var(--text-secondary);
        margin-bottom: 20px;
        line-height: 1.6;
        font-size: 0.95rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    /* Author Section */
    .post-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 16px 0;
        border-top: 1px solid var(--border-color);
        border-bottom: 1px solid var(--border-color);
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .author-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .author-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .author-name {
        font-size: 0.95rem;
        color: var(--text-main);
        font-weight: 600;
        line-height: 1.2;
    }

    .post-date {
        font-size: 0.85rem;
        color: var(--text-muted);
        line-height: 1.2;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .post-date i {
        font-size: 0.8rem;
    }

    /* Post Stats */
    .post-stats {
        display: flex;
        gap: 20px;
        margin-top: auto;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-muted);
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .stat-item:hover {
        color: var(--primary-color);
    }

    .stat-item i {
        font-size: 1rem;
    }

    .like-btn {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 6px 10px;
        border-radius: 8px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
    }

    .like-btn:hover {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }

    .like-btn.liked {
        color: #dc3545;
    }

    .like-btn.liked:hover {
        color: #c82333;
    }

    /* Read More Button */
    .read-more-btn {
        display: block;
        text-align: center;
        padding: 12px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        text-decoration: none;
        border-radius: 8px;
        margin-top: 20px;
        transition: all 0.3s;
        font-weight: 600;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    .read-more-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .read-more-btn:hover::before {
        left: 100%;
    }

    .read-more-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.3);
    }

    /* Profile Card */
    .profile-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
        margin-bottom: 24px;
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
    }

    .profile-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .profile-info {
        flex: 1;
    }

    .profile-name {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 4px;
        color: var(--text-main);
    }

    .profile-role {
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .profile-stats .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        padding: 12px;
        border-radius: 12px;
        background: rgba(67, 97, 238, 0.05);
        transition: all 0.2s;
    }

    .profile-stats .stat-item:hover {
        background: rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .profile-stats .stat-item i {
        font-size: 1.2rem;
        color: var(--primary-color);
    }

    .stat-details {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .profile-actions {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-profile-action {
        padding: 10px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-main);
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.9rem;
    }

    .btn-profile-action:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-1px);
    }

    /* Trending Card */
    .trending-card {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-color);
    }

    .trending-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .trending-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .trending-tag {
        padding: 8px 16px;
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
    }

    .trending-tag:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-1px);
    }

    /* Loading Spinner */
    .loading-spinner {
        text-align: center;
        padding: 60px;
        grid-column: 1 / -1;
    }

    .spinner {
        width: 50px;
        height: 50px;
        border: 3px solid var(--border-color);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-spinner p {
        color: var(--text-muted);
        font-size: 1rem;
    }

    /* No More Posts */
    .no-more-posts {
        text-align: center;
        padding: 40px;
        grid-column: 1 / -1;
        color: var(--text-muted);
    }

    .no-more-posts i {
        font-size: 2.5rem;
        margin-bottom: 15px;
        color: var(--success-color);
    }

    .no-more-posts p {
        font-size: 1rem;
    }

    /* No Posts Found */
    .no-posts-found {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state {
        max-width: 400px;
        margin: 0 auto;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-muted);
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 10px;
        color: var(--text-main);
    }

    .empty-state p {
        color: var(--text-secondary);
        margin-bottom: 25px;
        font-size: 1rem;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .home-main-container {
            flex-direction: column;
        }
        
        .home-sidebar {
            width: 100%;
            position: static;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
        }
        
        .posts-grid {
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        }
    }

    @media (max-width: 992px) {
        .search-filters {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .posts-grid {
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .home-main-container {
            padding: 0 15px;
        }
        
        .section-header {
            padding: 0 15px;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .posts-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .home-sidebar {
            grid-template-columns: 1fr;
        }
        
        .search-filters {
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }
        
        .filter-btn {
            padding: 12px 16px;
            font-size: 0.9rem;
        }
        
        .post-meta {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .post-stats {
            width: 100%;
            justify-content: space-between;
        }
    }

    @media (max-width: 480px) {
        .search-filters {
            grid-template-columns: 1fr;
        }
        
        .filter-btn {
            padding: 14px 20px;
        }
        
        .profile-stats {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

<script>
    // CSRF Token for AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Store liked posts in localStorage to persist like state
    const LIKED_POSTS_KEY = 'liked_posts';
    
    function getLikedPosts() {
        const liked = localStorage.getItem(LIKED_POSTS_KEY);
        return liked ? JSON.parse(liked) : {};
    }
    
    function setLikedPost(postId, isLiked) {
        const likedPosts = getLikedPosts();
        if (isLiked) {
            likedPosts[postId] = true;
        } else {
            delete likedPosts[postId];
        }
        localStorage.setItem(LIKED_POSTS_KEY, JSON.stringify(likedPosts));
    }
    
    function isPostLiked(postId) {
        const likedPosts = getLikedPosts();
        return likedPosts[postId] || false;
    }

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Home page loaded');
        
        // Initialize posts system
        initializePostsSystem();
    });

    function initializePostsSystem() {
        // Check if we're in home section
        const homeSection = document.getElementById('home');
        if (!homeSection) {
            console.log('Home section not found');
            return;
        }
        
        console.log('Initializing posts system...');
        
        // Variables for infinite scroll
        let isLoading = false;
        let hasMorePages = true;
        let currentPage = 1;
        let currentFilter = 'latest'; // Default filter
        let currentSearch = '';
        
        // Load initial posts
        loadPosts();
        
        // Initialize filter buttons
        initializeFilterButtons();
        
        // Scroll event listener for infinite scroll
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                if (isLoading || !hasMorePages) return;
                
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const scrollHeight = document.documentElement.scrollHeight;
                const clientHeight = document.documentElement.clientHeight;
                
                // Load more when 300px from bottom
                if (scrollTop + clientHeight >= scrollHeight - 300) {
                    console.log('Near bottom, loading more posts...');
                    loadPosts();
                }
            }, 100);
        });
        
        // Initialize filter buttons
        function initializeFilterButtons() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Update active state
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Update filter
                    currentFilter = this.getAttribute('data-filter');
                    console.log('Filter changed to:', currentFilter);
                    
                    // Reset and load posts with new filter
                    resetAndLoadPosts();
                });
            });
        }
        
        // Load posts function
        function loadPosts() {
            if (isLoading || !hasMorePages) {
                console.log('Cannot load posts: isLoading=', isLoading, 'hasMorePages=', hasMorePages);
                return;
            }
            
            isLoading = true;
            showLoading(true);
            
            // Hide initial loader on subsequent loads
            if (currentPage > 1) {
                document.getElementById('initial-loader').style.display = 'none';
            }
            
            // Build URL with parameters
            let url = '/api/posts?page=' + currentPage;
            
            // Add filter parameter
            if (currentFilter && currentFilter !== 'latest') {
                url += '&filter=' + currentFilter;
            }
            
            // Add search parameter if exists
            if (currentSearch) {
                url += '&search=' + encodeURIComponent(currentSearch);
            }
            
            // Add timestamp to prevent caching
            url += '&_=' + Date.now();
            
            console.log('Fetching posts from:', url);
            
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                cache: 'no-cache'
            })
            .then(response => {
                console.log('Response status:', response.status, response.statusText);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new TypeError("Server returned non-JSON response");
                }
                
                return response.json();
            })
            .then(data => {
                console.log('API Response:', data);
                
                if (data.success && data.posts && Array.isArray(data.posts)) {
                    if (data.posts.length > 0) {
                        renderPosts(data.posts);
                        
                        // Update pagination info
                        hasMorePages = data.has_more_pages || data.has_more || false;
                        currentPage++;
                        
                        // Initialize like buttons
                        initializeLikeButtons();
                        
                        // Hide initial loader
                        document.getElementById('initial-loader').style.display = 'none';
                        
                        // Hide no posts message if posts exist
                        document.getElementById('no-posts-found').style.display = 'none';
                    } else {
                        hasMorePages = false;
                        
                        // Hide initial loader
                        document.getElementById('initial-loader').style.display = 'none';
                        
                        // Show no posts message if it's the first page and no posts
                        if (currentPage === 1) {
                            document.getElementById('posts-container').innerHTML = '';
                            document.getElementById('no-posts-found').style.display = 'block';
                        }
                    }
                } else if (!data.success) {
                    // API returned success: false
                    throw new Error(data.message || 'Failed to load posts');
                } else {
                    // Unexpected response format
                    throw new Error('Invalid response format from server');
                }
                
                // Show no more posts message
                if (!hasMorePages && currentPage > 1) {
                    showNoMorePosts();
                }
            })
            .catch(error => {
                console.error('Error loading posts:', error);
                hasMorePages = false;
                
                // Hide initial loader
                document.getElementById('initial-loader').style.display = 'none';
                
                // Show error message
                if (currentPage === 1) {
                    document.getElementById('posts-container').innerHTML = `
                        <div class="no-posts-found" style="display: block;">
                            <div class="empty-state">
                                <i class="fas fa-exclamation-triangle"></i>
                                <h3>Error Loading Posts</h3>
                                <p>${error.message || 'Unable to load posts. Please try again.'}</p>
                                <p><small>Check browser console for details</small></p>
                                <button onclick="resetAndLoadPosts()" class="btn-create-post">
                                    <i class="fas fa-redo"></i> Retry
                                </button>
                            </div>
                        </div>
                    `;
                }
            })
            .finally(() => {
                isLoading = false;
                showLoading(false);
            });
        }
        
        // Render posts to the grid
        function renderPosts(posts) {
            const postsContainer = document.getElementById('posts-container');
            
            posts.forEach((post, index) => {
                const postCard = createPostCard(post);
                postsContainer.appendChild(postCard);
                
                // Add animation delay
                setTimeout(() => {
                    postCard.style.animationDelay = (index * 0.1) + 's';
                }, 10);
            });
        }
        
        // Create post card HTML element
        function createPostCard(post) {
            const postDate = new Date(post.created_at);
            const formattedDate = postDate.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            });
            
            // Extract author information
            let authorName = 'Unknown';
            let authorInitial = 'U';
            
            if (post.user) {
                if (post.user.fName || post.user.lName) {
                    authorName = `${post.user.fName || ''} ${post.user.lName || ''}`.trim();
                } else if (post.user.name) {
                    authorName = post.user.name;
                }
                
                if (post.user.fName) {
                    authorInitial = post.user.fName.charAt(0).toUpperCase();
                } else if (post.user.name) {
                    authorInitial = post.user.name.charAt(0).toUpperCase();
                }
            }
            
            if (authorName === 'Unknown' && post.author) {
                if (post.author.fName || post.author.lName) {
                    authorName = `${post.author.fName || ''} ${post.author.lName || ''}`.trim();
                    authorInitial = post.author.fName ? post.author.fName.charAt(0).toUpperCase() : 'U';
                }
            }
            
            // Create excerpt from content
            let excerpt = 'No content available';
            if (post.content) {
                const plainText = post.content.replace(/<[^>]*>/g, '');
                excerpt = plainText.length > 120 ? plainText.substring(0, 120) + '...' : plainText;
            }
            
            // Check if user is logged in
            const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
            
            // Check if post has image
            const hasImage = post.image ? true : false;
            const imageUrl = post.image ? '/storage/' + post.image : null;
            
            // Check if post is liked
            let isLiked = false;
            if (post.is_liked !== undefined) {
                isLiked = post.is_liked;
            } else {
                isLiked = isPostLiked(post.id);
            }
            
            // Create card element
            const card = document.createElement('div');
            card.className = 'post-card' + (hasImage ? ' has-image' : '');
            card.innerHTML = `
                ${hasImage ? `
                    <div class="post-image-container">
                        <img src="${imageUrl}" class="post-image" alt="${post.title || 'Post Image'}" 
                             onerror="this.onerror=null; this.parentElement.style.display='none'; this.closest('.post-card').classList.remove('has-image');">
                    </div>
                ` : ''}
                
                <div class="post-content">
                    <h3 class="post-title">${post.title || 'Untitled Post'}</h3>
                    <p class="post-excerpt">${excerpt}</p>
                    
                    <div class="post-meta">
                        <div class="author-info">
                            <div class="author-avatar">${authorInitial}</div>
                            <div class="author-details">
                                <span class="author-name">${authorName}</span>
                                <span class="post-date">
                                    <i class="far fa-calendar"></i>
                                    ${formattedDate}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="post-stats">
                        ${isLoggedIn ? 
                            `<button class="like-btn ${isLiked ? 'liked' : ''}" data-post-id="${post.id}" data-liked="${isLiked}">
                                <i class="like-icon ${isLiked ? 'fas' : 'far'} fa-heart"></i>
                                <span class="like-count">${post.likes_count || 0}</span>
                            </button>` :
                            `<a href="/login" class="like-btn">
                                <i class="like-icon far fa-heart"></i>
                                <span class="like-count">${post.likes_count || 0}</span>
                            </a>`
                        }
                        <div class="stat-item">
                            <i class="far fa-eye"></i>
                            <span>${post.views || 0}</span>
                        </div>
                        <div class="stat-item">
                            <i class="far fa-comment"></i>
                            <span>${post.comments_count || 0}</span>
                        </div>
                    </div>
                    
                    <a href="/posts/${post.id}" class="read-more-btn">
                        Read Full Article <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                    </a>
                </div>
            `;
            
            // Make entire card clickable for read more
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on like button or its children
                if (!e.target.closest('.like-btn') && !e.target.closest('.read-more-btn')) {
                    window.location.href = `/posts/${post.id}`;
                }
            });
            
            return card;
        }
        
        // Initialize like buttons
        function initializeLikeButtons() {
            document.querySelectorAll('.like-btn[data-post-id]').forEach(button => {
                // Remove existing event listeners by cloning
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                // Add click event
                newButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handleLikeClick(this);
                });
            });
        }
        
        // Handle like button click
        function handleLikeClick(button) {
            const postId = button.getAttribute('data-post-id');
            const isLiked = button.getAttribute('data-liked') === 'true';
            const likeIcon = button.querySelector('.like-icon');
            const likeCountSpan = button.querySelector('.like-count');
            
            // Store original values
            const originalLikeCount = parseInt(likeCountSpan.textContent) || 0;
            
            // Update localStorage immediately for persistence
            setLikedPost(postId, !isLiked);
            
            // Immediate UI feedback
            if (isLiked) {
                // Unlike
                button.setAttribute('data-liked', 'false');
                likeIcon.className = 'like-icon far fa-heart';
                button.classList.remove('liked');
                likeCountSpan.textContent = Math.max(0, originalLikeCount - 1);
            } else {
                // Like
                button.setAttribute('data-liked', 'true');
                likeIcon.className = 'like-icon fas fa-heart';
                button.classList.add('liked');
                likeCountSpan.textContent = originalLikeCount + 1;
            }
            
            // Send AJAX request
            const url = isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => {
                console.log('Like response status:', response.status);
                
                if (response.status === 401) {
                    // Not logged in, redirect to login
                    window.location.href = '/login';
                    return null;
                }
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            })
            .then(data => {
                if (!data) return; // For 401 redirect case
                
                console.log('Like response data:', data);
                
                if (data.success) {
                    // Update with server count
                    if (data.likes_count !== undefined) {
                        likeCountSpan.textContent = data.likes_count;
                    }
                    
                    // Update button state based on server response
                    if (data.action === 'liked') {
                        button.setAttribute('data-liked', 'true');
                        likeIcon.className = 'like-icon fas fa-heart';
                        button.classList.add('liked');
                        setLikedPost(postId, true);
                    } else if (data.action === 'unliked') {
                        button.setAttribute('data-liked', 'false');
                        likeIcon.className = 'like-icon far fa-heart';
                        button.classList.remove('liked');
                        setLikedPost(postId, false);
                    }
                    
                    // Show success message
                    showToast(data.message || (isLiked ? 'Post unliked!' : 'Post liked!'), 'success');
                } else {
                    // Revert if failed
                    revertLikeUI(button, isLiked, originalLikeCount);
                    showToast(data.message || 'Action failed', 'error');
                }
            })
            .catch(error => {
                console.error('Like error:', error);
                revertLikeUI(button, isLiked, originalLikeCount);
                showToast('Network error. Please try again.', 'error');
            });
        }
        
        // Revert like UI if request fails
        function revertLikeUI(button, originalIsLiked, originalLikeCount) {
            const likeIcon = button.querySelector('.like-icon');
            const likeCountSpan = button.querySelector('.like-count');
            
            if (originalIsLiked) {
                button.setAttribute('data-liked', 'true');
                likeIcon.className = 'like-icon fas fa-heart';
                button.classList.add('liked');
            } else {
                button.setAttribute('data-liked', 'false');
                likeIcon.className = 'like-icon far fa-heart';
                button.classList.remove('liked');
            }
            likeCountSpan.textContent = originalLikeCount;
        }
        
        // Show/hide loading spinner
        function showLoading(show) {
            const spinner = document.getElementById('loading-spinner');
            if (spinner) {
                spinner.style.display = show ? 'block' : 'none';
            }
        }
        
        // Show no more posts message
        function showNoMorePosts() {
            const noMore = document.getElementById('no-more-posts');
            if (noMore) {
                noMore.style.display = 'block';
            }
        }
        
        // Reset and load posts with new parameters
        function resetAndLoadPosts() {
            console.log('Resetting and loading posts...');
            
            // Reset state
            currentPage = 1;
            hasMorePages = true;
            isLoading = false;
            
            // Clear current posts
            document.getElementById('posts-container').innerHTML = `
                <div class="loading-spinner" id="initial-loader">
                    <div class="spinner"></div>
                    <p>Loading posts...</p>
                </div>
            `;
            document.getElementById('no-more-posts').style.display = 'none';
            document.getElementById('no-posts-found').style.display = 'none';
            showLoading(false);
            
            // Load new posts
            loadPosts();
        }
        
        // Make reset function available globally
        window.resetAndLoadPosts = resetAndLoadPosts;
    }

    // Toast function
    function showToast(message, type = 'normal') {
        const container = document.getElementById('toastContainer');
        if (!container) {
            // Create toast container if doesn't exist
            const toastContainer = document.createElement('div');
            toastContainer.id = 'toastContainer';
            toastContainer.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
            document.body.appendChild(toastContainer);
        }
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        let icon = 'fa-info-circle'; 
        if (type === 'success') icon = 'fa-check-circle'; 
        if (type === 'error') icon = 'fa-exclamation-circle';
        toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
        document.getElementById('toastContainer').appendChild(toast);
        
        // Add show class with animation
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Remove toast after delay
        setTimeout(() => { 
            toast.classList.remove('show'); 
            setTimeout(() => toast.remove(), 400); 
        }, 3000);
    }
</script>