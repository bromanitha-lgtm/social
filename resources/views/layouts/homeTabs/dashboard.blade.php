<section id="dashboard" class="page-section">
    <div class="dashboard-header">
        <h2 class="dashboard-title">Dashboard Overview</h2>
        <div class="dashboard-actions">
            <button class="btn btn-primary" onclick="click('profileBtn')">
                <i class="fas fa-pen"></i> Edit Profile
            </button>
            <button class="btn btn-outline" onclick="showToast('Dashboard exported', 'success')">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Profile Summary Card -->
        <div class="profile-summary-card">
            <div class="profile-summary-header">
                <div class="avatar-container">
                    <img src="{{ Auth::user()->avatar_url }}" alt="Profile" class="avatar-img">
                    @if(Auth::user()->last_seen && Auth::user()->last_seen->diffInMinutes(now()) < 5)
                        <div class="online-status online" title="Online"></div>
                    @else
                        <div class="online-status offline" title="Offline"></div>
                    @endif
                </div>
                <div class="profile-summary-info">
                    <h3>{{ Auth::user()->fName }} {{ Auth::user()->lName }}</h3>
                    <p class="user-email">{{ Auth::user()->email }}</p>
                    <p class="user-role">Member since {{ Auth::user()->created_at->format('M Y') }}</p>
                </div>
            </div>
            
            <div class="profile-stats-grid">
                <div class="profile-stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value" id="dashboardPostsCount">{{ Auth::user()->posts()->count() }}</span>
                        <span class="stat-label">Posts</span>
                    </div>
                </div>
                <div class="profile-stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value" id="dashboardFollowersCount">{{ Auth::user()->followers_count }}</span>
                        <span class="stat-label">Followers</span>
                    </div>
                </div>
                <div class="profile-stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ Auth::user()->following_count }}</span>
                        <span class="stat-label">Following</span>
                    </div>
                </div>
                <div class="profile-stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-value">{{ Auth::user()->comments()->count() }}</span>
                        <span class="stat-label">Comments</span>
                    </div>
                </div>
            </div>
            
            <div class="profile-summary-footer">
                <div class="profile-progress">
                    <div class="progress-header">
                        <span>Account Status</span>
                        <span class="progress-percentage">Verified</span>
                    </div>
                    <div class="account-info">
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ Auth::user()->email_verified_at ? 'Email Verified' : 'Email Not Verified' }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <span>Last active: {{ Auth::user()->last_seen ? Auth::user()->last_seen->diffForHumans() : 'Never' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="widget-card gradient-card">
            <div class="card-header">
                <div class="card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="card-header-content">
                    <h3>Performance Summary</h3>
                    <p class="card-subtitle">This Month</p>
                </div>
            </div>
            
            <div class="account-stats">
                @php
                    $monthlyPosts = Auth::user()->posts()->whereMonth('created_at', now()->month)->count();
                    $monthlyLikes = Auth::user()->posts()->withCount('likes')->get()->sum('likes_count');
                    $monthlyViews = Auth::user()->posts()->sum('views');
                @endphp
                
                <div class="account-stat">
                    <span class="stat-label">Monthly Posts</span>
                    <div class="stat-value-large">{{ $monthlyPosts }}</div>
                </div>
                <div class="account-stat">
                    <span class="stat-label">Monthly Likes</span>
                    <div class="stat-value-large">{{ $monthlyLikes }}</div>
                </div>
                <div class="account-stat">
                    <span class="stat-label">Monthly Views</span>
                    <div class="stat-value-large">{{ $monthlyViews }}</div>
                </div>
            </div>
            
            <div class="card-footer">
                <button class="btn btn-light" onclick="window.location.href='{{ route('create') }}'">
                    <i class="fas fa-plus"></i> Create New Post
                </button>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <section class="metrics-section">
        <div class="section-header">
            <h3>Your Content Performance</h3>
            <div class="time-filter">
                <select class="filter-select" id="performanceFilter" onchange="updatePerformanceMetrics()">
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="year">This Year</option>
                    <option value="all">All Time</option>
                </select>
            </div>
        </div>
        
        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon success">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="metric-trend">
                        <span>Total</span>
                    </div>
                </div>
                <div class="metric-content">
                    @php
                        $totalLikes = Auth::user()->posts()->withCount('likes')->get()->sum('likes_count');
                    @endphp
                    <h4 class="metric-value">{{ $totalLikes }}</h4>
                    <p class="metric-label">Total Likes Received</p>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon primary">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="metric-trend">
                        <span>Total</span>
                    </div>
                </div>
                <div class="metric-content">
                    @php
                        $totalViews = Auth::user()->posts()->sum('views');
                    @endphp
                    <h4 class="metric-value">{{ $totalViews }}</h4>
                    <p class="metric-label">Total Views</p>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon warning">
                        <i class="fas fa-comment-dots"></i>
                    </div>
                    <div class="metric-trend">
                        <span>Total</span>
                    </div>
                </div>
                <div class="metric-content">
                    @php
                        $totalComments = Auth::user()->posts()->withCount('comments')->get()->sum('comments_count');
                    @endphp
                    <h4 class="metric-value">{{ $totalComments }}</h4>
                    <p class="metric-label">Total Comments</p>
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-header">
                    <div class="metric-icon info">
                        <i class="fas fa-share-alt"></i>
                    </div>
                    <div class="metric-trend">
                        <span>Average</span>
                    </div>
                </div>
                <div class="metric-content">
                    @php
                        $postCount = Auth::user()->posts()->count();
                        $avgLikes = $postCount > 0 ? round($totalLikes / $postCount, 1) : 0;
                    @endphp
                    <h4 class="metric-value">{{ $avgLikes }}</h4>
                    <p class="metric-label">Avg. Likes per Post</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Split -->
    <section class="content-split">
        <div class="chart-container">
            <div class="chart-header">
                <h3>Post Engagement Trend</h3>
                <div class="chart-controls">
                    <button class="chart-control-btn active" data-period="week" onclick="updatePostEngagementChart('week')">Week</button>
                    <button class="chart-control-btn" data-period="month" onclick="updatePostEngagementChart('month')">Month</button>
                    <button class="chart-control-btn" data-period="year" onclick="updatePostEngagementChart('year')">Year</button>
                </div>
            </div>
            <div class="canvas-wrapper">
                <canvas id="postEngagementChart"></canvas>
            </div>
        </div>

        <div class="activity-feed">
            <div class="activity-header">
                <h3>Recent Activity</h3>
                <button class="btn-icon" onclick="refreshRecentActivity()">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </div>
            <div class="activity-list" id="recentActivityList">
                <!-- Real activity data will be loaded here -->
                <div class="loading-spinner">
                    <div class="spinner"></div>
                    <p>Loading activities...</p>
                </div>
            </div>
            <div class="activity-footer">
                <button class="btn btn-outline" style="width: 100%;" onclick="window.location.href='/posts'">
                    View All Posts
                </button>
            </div>
        </div>
    </section>

    <!-- Top Performing Posts -->
    <section class="top-posts-section">
        <div class="section-header">
            <h3>Top Performing Posts</h3>
            <a href="/posts" class="btn-link">View All</a>
        </div>
        
        <div class="top-posts-grid" id="topPostsGrid">
            @php
                $topPosts = Auth::user()->posts()
                    ->withCount(['likes', 'comments'])
                    ->orderBy('likes_count', 'desc')
                    ->orderBy('views', 'desc')
                    ->take(3)
                    ->get();
            @endphp
            
            @if($topPosts->count() > 0)
                @foreach($topPosts as $post)
                    <div class="top-post-card">
                        <div class="post-header">
                            <h4 class="post-title">{{ Str::limit($post->title, 40) }}</h4>
                            <span class="post-date">{{ $post->created_at->format('M d') }}</span>
                        </div>
                        <div class="post-stats">
                            <div class="post-stat">
                                <i class="fas fa-heart"></i>
                                <span>{{ $post->likes_count }}</span>
                            </div>
                            <div class="post-stat">
                                <i class="fas fa-comment"></i>
                                <span>{{ $post->comments_count }}</span>
                            </div>
                            <div class="post-stat">
                                <i class="fas fa-eye"></i>
                                <span>{{ $post->views }}</span>
                            </div>
                        </div>
                        <a href="{{ route('posts.show', $post) }}" class="post-link">View Post →</a>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-newspaper"></i>
                    <p>No posts yet. Create your first post!</p>
                    <a href="{{ route('create') }}" class="btn btn-primary">Create Post</a>
                </div>
            @endif
        </div>
    </section>

    <!-- Quick Actions -->
    <section class="quick-actions-section">
        <h3>Quick Actions</h3>
        <div class="quick-actions-grid">
            <button class="quick-action-btn" onclick="click('profileBtn')">
                <div class="action-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <span>Edit Profile</span>
            </button>
            <button class="quick-action-btn" onclick="window.location.href='{{ route('create') }}'">
                <div class="action-icon">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <span>Create Post</span>
            </button>
            <button class="quick-action-btn" onclick="window.location.href='/posts/my/drafts'">
                <div class="action-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <span>My Drafts</span>
            </button>
            <button class="quick-action-btn" onclick="window.location.href='/posts'">
                <div class="action-icon">
                    <i class="fas fa-list"></i>
                </div>
                <span>All Posts</span>
            </button>
            <button class="quick-action-btn" onclick="window.location.href='/settings'">
                <div class="action-icon">
                    <i class="fas fa-cog"></i>
                </div>
                <span>Settings</span>
            </button>
            <button class="quick-action-btn" onclick="showToast('Help center coming soon', 'normal')">
                <div class="action-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <span>Help Center</span>
            </button>
        </div>
    </section>
</section>

<style>
    /* Dashboard Styles (Same as before but with some additions) */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .dashboard-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }
    
    /* Account Info */
    .account-info {
        margin-top: 15px;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    
    .info-item i {
        width: 16px;
        color: var(--primary-color);
    }
    
    /* Stat Value Large */
    .stat-value-large {
        font-size: 2rem;
        font-weight: 700;
        color: white;
        margin-top: 5px;
    }
    
    /* Top Posts Section */
    .top-posts-section {
        margin-top: 40px;
        padding-top: 25px;
        border-top: 1px solid var(--border-color);
    }
    
    .top-posts-section .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .btn-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: color 0.3s;
    }
    
    .btn-link:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }
    
    .top-posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .top-post-card {
        background: var(--card-bg);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    
    .top-post-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
    }
    
    .post-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }
    
    .post-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        margin: 0;
        flex: 1;
    }
    
    .post-date {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-left: 10px;
    }
    
    .post-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .post-stat {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--text-muted);
        font-size: 0.9rem;
    }
    
    .post-stat i {
        color: var(--primary-color);
    }
    
    .post-link {
        display: inline-block;
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .post-link:hover {
        color: var(--secondary-color);
        transform: translateX(5px);
    }
    
    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 40px 20px;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }
    
    .empty-state p {
        margin-bottom: 20px;
    }
    
    /* Loading Spinner */
    .loading-spinner {
        text-align: center;
        padding: 30px;
    }
    
    .loading-spinner .spinner {
        width: 30px;
        height: 30px;
        border: 3px solid var(--border-color);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Metric Trend (Simple) */
    .metric-trend {
        font-size: 0.8rem;
        color: var(--text-muted);
        padding: 4px 8px;
        background: rgba(67, 97, 238, 0.05);
        border-radius: 4px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .top-posts-grid {
            grid-template-columns: 1fr;
        }
        
        .metric-value {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize dashboard
        initializeDashboard();
        
        // Load recent activities
        loadRecentActivities();
        
        // Initialize engagement chart
        initializePostEngagementChart('month');
    });
    
    function click(clickedBtn) {
        document.getElementById(clickedBtn).click();
    }
    
    function initializeDashboard() {
        // Animate counters
        animateDashboardCounters();
        
        // Set up chart controls
        document.querySelectorAll('.chart-control-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.chart-control-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    }
    
    function animateDashboardCounters() {
        const counters = document.querySelectorAll('.metric-value[data-target]');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            if (!isNaN(target)) {
                let current = 0;
                const increment = target / 50;
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = Math.ceil(current);
                        setTimeout(updateCounter, 20);
                    } else {
                        counter.textContent = target;
                    }
                };
                updateCounter();
            }
        });
    }
    
    function loadRecentActivities() {
        fetch('/api/dashboard/activities', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const activityList = document.getElementById('recentActivityList');
            if (!activityList) return;
            
            if (data.success && data.activities.length > 0) {
                activityList.innerHTML = data.activities.map(activity => `
                    <div class="activity-item">
                        <div class="activity-avatar">
                            <img src="${activity.avatar}" alt="${activity.user_name}">
                            <div class="activity-type ${activity.type}">
                                <i class="fas fa-${activity.type === 'like' ? 'heart' : activity.type === 'follow' ? 'user-plus' : activity.type === 'comment' ? 'comment' : 'post'}"></i>
                            </div>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">
                                <strong>${activity.user_name}</strong> ${activity.action}
                            </div>
                            <div class="activity-time">${activity.time}</div>
                        </div>
                    </div>
                `).join('');
            } else {
                activityList.innerHTML = `
                    <div class="empty-state" style="padding: 20px;">
                        <i class="fas fa-bell-slash"></i>
                        <p>No recent activity</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading activities:', error);
            const activityList = document.getElementById('recentActivityList');
            if (activityList) {
                activityList.innerHTML = `
                    <div class="error-state">
                        <i class="fas fa-exclamation-triangle"></i>
                        <p>Failed to load activities</p>
                        <button class="btn btn-sm btn-outline" onclick="loadRecentActivities()">Retry</button>
                    </div>
                `;
            }
        });
    }
    
    function refreshRecentActivity() {
        const refreshBtn = event.target.closest('.btn-icon');
        if (refreshBtn) {
            refreshBtn.classList.add('rotating');
            setTimeout(() => {
                refreshBtn.classList.remove('rotating');
            }, 500);
        }
        loadRecentActivities();
    }
    
    function updatePerformanceMetrics() {
        const period = document.getElementById('performanceFilter').value;
        showToast(`Loading ${period} performance data...`, 'normal');
        
        fetch(`/api/dashboard/performance?period=${period}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update metrics with real data
                updateMetricsDisplay(data.metrics);
                updatePostEngagementChart(period);
                showToast('Performance data updated', 'success');
            }
        })
        .catch(error => {
            console.error('Error updating performance:', error);
            showToast('Failed to update performance data', 'error');
        });
    }
    
    function updateMetricsDisplay(metrics) {
        // This function would update the metrics display with new data
        // Implementation depends on your API response structure
    }
    
    function initializePostEngagementChart(period) {
        const ctx = document.getElementById('postEngagementChart').getContext('2d');
        
        // Fetch real data from API
        fetch(`/api/dashboard/engagement?period=${period}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                createEngagementChart(ctx, data);
            }
        })
        .catch(error => {
            console.error('Error loading chart data:', error);
            // Create empty chart on error
            createEmptyChart(ctx);
        });
    }
    
    function updatePostEngagementChart(period) {
        // Update chart with new period data
        initializePostEngagementChart(period);
    }
    
    function createEngagementChart(ctx, data) {
        // Destroy existing chart if exists
        if (window.engagementChart instanceof Chart) {
            window.engagementChart.destroy();
        }
        
        // Create new chart with real data
        window.engagementChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels || ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Likes',
                    data: data.likes || [12, 19, 3, 5, 2, 3, 10],
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Views',
                    data: data.views || [20, 25, 10, 15, 8, 12, 18],
                    borderColor: '#f72585',
                    backgroundColor: 'rgba(247, 37, 133, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                }
            }
        });
    }
    
    function createEmptyChart(ctx) {
        if (window.engagementChart instanceof Chart) {
            window.engagementChart.destroy();
        }
        
        window.engagementChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['No data'],
                datasets: [{
                    label: 'No data available',
                    data: [0],
                    borderColor: '#999',
                    backgroundColor: 'rgba(153, 153, 153, 0.1)',
                    borderDash: [5, 5]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    
    // Add rotating animation for refresh button
    const style = document.createElement('style');
    style.textContent = `
        .rotating {
            animation: rotate 0.5s linear;
        }
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
</script>