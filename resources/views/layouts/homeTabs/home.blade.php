{{-- views/layouts/homeTabs/home.blade.php --}}
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

    <!-- Search Bar -->
    <div class="home-search-bar">
        <div class="search-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="search-input" id="home-search-input" placeholder="Search posts...">
            <button class="search-btn" id="home-search-btn">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
        <div class="search-filters">
            <button class="filter-btn active" data-filter="latest">Latest</button>
            <button class="filter-btn" data-filter="popular">Popular</button>
            <button class="filter-btn" data-filter="trending">Trending</button>
        </div>
    </div>

    <!-- Posts Grid Container -->
    <div class="posts-grid" id="posts-container">
        <!-- Posts will be loaded here via AJAX -->
    </div>

    <!-- Loading Spinner -->
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

    <!-- Debug Info (Remove in production) -->
    <div class="debug-info" style="display: none; padding: 20px; background: #f5f5f5; border-radius: 8px; margin-top: 20px;">
        <h4>Debug Information</h4>
        <div id="debug-content"></div>
    </div>
</section>

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
        background: var(--accent-color);
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

    /* Search Bar */
    .home-search-bar {
        margin-bottom: 30px;
        padding: 0 20px;
    }

    .search-wrapper {
        position: relative;
        margin-bottom: 15px;
    }

    .search-input {
        width: 100%;
        padding: 12px 45px 12px 45px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-main);
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--accent-color);
        color: white;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .search-btn:hover {
        background: var(--accent-color-dark);
    }

    .search-filters {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-btn {
        padding: 8px 16px;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        background: var(--card-bg);
        color: var(--text-main);
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .filter-btn:hover {
        border-color: var(--accent-color);
        color: var(--accent-color);
    }

    .filter-btn.active {
        background: var(--accent-color);
        color: white;
        border-color: var(--accent-color);
    }

    /* Posts Grid */
    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 24px;
        padding: 0 20px;
        margin-bottom: 40px;
        min-height: 300px;
    }

    /* Post Card */
    .post-card {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        border: 1px solid var(--border-color);
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s ease forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .post-image-container {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .post-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .post-card:hover .post-image {
        transform: scale(1.05);
    }

    .post-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, #4361ee20, #3a0ca320);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .post-placeholder i {
        font-size: 3rem;
        color: var(--accent-color);
        opacity: 0.5;
    }

    .post-content {
        padding: 20px;
    }

    .post-title {
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: var(--text-main);
        line-height: 1.4;
    }

    .post-title a {
        color: inherit;
        text-decoration: none;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .post-title a:hover {
        color: var(--accent-color);
    }

    .post-excerpt {
        color: var(--text-secondary);
        margin-bottom: 15px;
        line-height: 1.6;
        font-size: 0.95rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .post-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .author-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .author-details {
        display: flex;
        flex-direction: column;
    }

    .author-name {
        font-size: 0.9rem;
        color: var(--text-main);
        font-weight: 500;
        line-height: 1.2;
    }

    .post-date {
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.2;
    }

    .post-stats {
        display: flex;
        gap: 20px;
        padding-top: 15px;
        border-top: 1px solid var(--border-color);
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    .stat-item i {
        font-size: 0.9rem;
    }

    .like-btn {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 5px 8px;
        border-radius: 5px;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
    }

    .like-btn:hover {
        color: var(--danger-color);
        background: rgba(220, 53, 69, 0.1);
    }

    .like-btn.liked {
        color: var(--danger-color);
    }

    .like-btn.liked:hover {
        color: var(--danger-color-dark);
    }

    .read-more-btn {
        display: block;
        text-align: center;
        padding: 10px;
        background: var(--accent-color);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        margin-top: 15px;
        transition: background-color 0.2s;
        font-weight: 500;
    }

    .read-more-btn:hover {
        background: var(--accent-color-dark);
        color: white;
    }

    /* Loading Spinner */
    .loading-spinner {
        text-align: center;
        padding: 40px;
        grid-column: 1 / -1;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid var(--border-color);
        border-top-color: var(--accent-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .loading-spinner p {
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    /* No More Posts */
    .no-more-posts {
        text-align: center;
        padding: 30px;
        grid-column: 1 / -1;
        color: var(--text-muted);
    }

    .no-more-posts i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: var(--success-color);
    }

    .no-more-posts p {
        font-size: 0.95rem;
    }

    /* No Posts Found */
    .no-posts-found {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
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
    @media (max-width: 768px) {
        .posts-grid {
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 0 15px;
        }
        
        .section-header {
            padding: 0 15px;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .home-search-bar {
            padding: 0 15px;
        }
        
        .post-card {
            margin: 0;
        }
    }

    @media (max-width: 480px) {
        .posts-grid {
            grid-template-columns: 1fr;
        }
        
        .post-stats {
            gap: 15px;
            justify-content: space-between;
        }
    }
</style>

<script>
// CSRF Token for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

document.addEventListener('DOMContentLoaded', function() {
    console.log('Home page loaded');
    
    // Initialize posts system
    initializePostsSystem();
    
    // Initialize search and filter functionality
    initializeSearchAndFilter();
    
    // Show debug info if in development
    if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
        document.querySelector('.debug-info').style.display = 'block';
    }
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
    let currentFilter = 'latest';
    let currentSearch = '';
    
    // Load initial posts
    loadPosts();
    
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
    
    // Load posts function
    function loadPosts() {
        if (isLoading || !hasMorePages) {
            console.log('Cannot load posts: isLoading=', isLoading, 'hasMorePages=', hasMorePages);
            return;
        }
        
        isLoading = true;
        showLoading(true);
        
        // Build URL with parameters
        let url = '/api/posts?page=' + currentPage;
        if (currentFilter !== 'latest') {
            url += '&filter=' + currentFilter;
        }
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
                    hasMorePages = data.has_more_pages;
                    currentPage++;
                    
                    // Initialize like buttons
                    initializeLikeButtons();
                    
                    // Hide no posts message if posts exist
                    document.getElementById('no-posts-found').style.display = 'none';
                    
                    // Update debug info
                    updateDebugInfo(data);
                } else {
                    hasMorePages = false;
                    
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
                            <button onclick="testAPI()" class="btn-create-post" style="margin-top: 10px; background: var(--secondary-color);">
                                <i class="fas fa-vial"></i> Test API
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
        
        // Extract first letter of author name for avatar
        const authorInitial = post.user && post.user.name ? post.user.name.charAt(0).toUpperCase() : 'U';
        const authorName = post.user ? post.user.name : 'Unknown';
        
        // Create excerpt from content (remove HTML tags)
        let excerpt = 'No content available';
        if (post.content) {
            const plainText = post.content.replace(/<[^>]*>/g, '');
            excerpt = plainText.length > 120 ? plainText.substring(0, 120) + '...' : plainText;
        }
        
        // Check if user is logged in for like button
        const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
        
        // Create image URL
        const imageUrl = post.image ? '/storage/' + post.image : null;
        
        // Create card element
        const card = document.createElement('div');
        card.className = 'post-card';
        card.innerHTML = `
            <div class="post-image-container">
                ${imageUrl ? 
                    `<img src="${imageUrl}" class="post-image" alt="${post.title}" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\"post-placeholder\"><i class=\"fas fa-newspaper\"></i></div>';">` :
                    `<div class="post-placeholder">
                        <i class="fas fa-newspaper"></i>
                    </div>`
                }
            </div>
            <div class="post-content">
                <h3 class="post-title">
                    <a href="/posts/${post.id}">${post.title || 'Untitled Post'}</a>
                </h3>
                <p class="post-excerpt">${excerpt}</p>
                
                <div class="post-meta">
                    <div class="author-info">
                        <div class="author-avatar">${authorInitial}</div>
                        <div class="author-details">
                            <span class="author-name">${authorName}</span>
                            <span class="post-date">${formattedDate}</span>
                        </div>
                    </div>
                </div>
                
                <div class="post-stats">
                    ${isLoggedIn ? 
                        `<button class="like-btn" data-post-id="${post.id}" data-liked="false">
                            <i class="far fa-heart"></i>
                            <span class="like-count">${post.likes_count || 0}</span>
                        </button>` :
                        `<a href="/login" class="like-btn">
                            <i class="far fa-heart"></i>
                            <span class="like-count">${post.likes_count || 0}</span>
                        </a>`
                    }
                    <div class="stat-item">
                        <i class="fas fa-eye"></i>
                        <span>${post.views || 0}</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-comment"></i>
                        <span>${post.comments_count || 0}</span>
                    </div>
                </div>
                
                <a href="/posts/${post.id}" class="read-more-btn">
                    Read More <i class="fas fa-arrow-right" style="margin-left: 5px;"></i>
                </a>
            </div>
        `;
        
        return card;
    }
    
    // Initialize like buttons
    function initializeLikeButtons() {
        document.querySelectorAll('.like-btn[data-post-id]').forEach(button => {
            // Remove existing event listeners
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);
            
            // Add click event
            newButton.addEventListener('click', function(e) {
                e.preventDefault();
                handleLikeClick(this);
            });
        });
    }
    
    // Handle like button click
    function handleLikeClick(button) {
        const postId = button.getAttribute('data-post-id');
        const isLiked = button.getAttribute('data-liked') === 'true';
        const likeIcon = button.querySelector('i');
        const likeCountSpan = button.querySelector('.like-count');
        
        // Store original values
        const originalLikeCount = parseInt(likeCountSpan.textContent) || 0;
        
        // Immediate UI feedback
        if (isLiked) {
            button.setAttribute('data-liked', 'false');
            likeIcon.className = 'far fa-heart';
            button.classList.remove('liked');
            likeCountSpan.textContent = Math.max(0, originalLikeCount - 1);
        } else {
            button.setAttribute('data-liked', 'true');
            likeIcon.className = 'fas fa-heart';
            button.classList.add('liked');
            likeCountSpan.textContent = originalLikeCount + 1;
        }
        
        // Send AJAX request
        const url = isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                // Revert if failed
                revertLikeUI(button, isLiked, originalLikeCount);
                console.error('Like action failed:', data.message);
            } else if (data.likes_count !== undefined) {
                // Update with server count
                likeCountSpan.textContent = data.likes_count;
            }
        })
        .catch(error => {
            console.error('Like error:', error);
            revertLikeUI(button, isLiked, originalLikeCount);
        });
    }
    
    // Revert like UI if request fails
    function revertLikeUI(button, originalIsLiked, originalLikeCount) {
        const likeIcon = button.querySelector('i');
        const likeCountSpan = button.querySelector('.like-count');
        
        if (originalIsLiked) {
            button.setAttribute('data-liked', 'true');
            likeIcon.className = 'fas fa-heart';
            button.classList.add('liked');
        } else {
            button.setAttribute('data-liked', 'false');
            likeIcon.className = 'far fa-heart';
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
    
    // Update debug information
    function updateDebugInfo(data) {
        const debugDiv = document.getElementById('debug-content');
        if (debugDiv) {
            debugDiv.innerHTML = `
                <p><strong>Current Page:</strong> ${currentPage}</p>
                <p><strong>Has More Pages:</strong> ${hasMorePages}</p>
                <p><strong>Posts Count:</strong> ${data.posts.length}</p>
                <p><strong>Total Posts:</strong> ${data.total || 'N/A'}</p>
                <p><strong>Next Page URL:</strong> ${data.next_page_url || 'None'}</p>
            `;
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
        document.getElementById('posts-container').innerHTML = '';
        document.getElementById('no-more-posts').style.display = 'none';
        document.getElementById('no-posts-found').style.display = 'none';
        showLoading(false);
        
        // Load new posts
        loadPosts();
    }
    
    // Initialize search and filter functionality
    function initializeSearchAndFilter() {
        // Search input
        const searchInput = document.getElementById('home-search-input');
        const searchBtn = document.getElementById('home-search-btn');
        
        // Filter buttons
        const filterButtons = document.querySelectorAll('.filter-btn');
        
        // Search button click
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                currentSearch = searchInput.value.trim();
                console.log('Searching for:', currentSearch);
                resetAndLoadPosts();
            });
        }
        
        // Search on Enter key
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    currentSearch = this.value.trim();
                    console.log('Searching for (Enter):', currentSearch);
                    resetAndLoadPosts();
                }
            });
        }
        
        // Filter button clicks
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active state
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Update filter
                currentFilter = this.getAttribute('data-filter');
                console.log('Filter changed to:', currentFilter);
                resetAndLoadPosts();
            });
        });
    }
    
    // Make functions available globally
    window.resetAndLoadPosts = resetAndLoadPosts;
    window.testAPI = testAPI;
}

// Test API function
function testAPI() {
    console.log('Testing API endpoints...');
    
    // Test basic API endpoint
    fetch('/api/posts?page=1&_=' + Date.now(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('API Test Response Status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('API Test Data:', data);
        alert('API Test: ' + (data.success ? 'SUCCESS' : 'FAILED') + '\n' + 
              'Message: ' + (data.message || 'No message') + '\n' +
              'Posts Count: ' + (data.posts ? data.posts.length : 0));
    })
    .catch(error => {
        console.error('API Test Error:', error);
        alert('API Test Failed: ' + error.message);
    });
}
</script>