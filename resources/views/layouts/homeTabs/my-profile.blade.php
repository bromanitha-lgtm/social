<div class="my-profile-container">
    <!-- Profile Header -->
    <div class="profile-header-section">
        <div class="profile-cover">
            <div class="cover-image" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
                <div class="cover-overlay">
                    <button class="btn-cover-edit" onclick="showToast('Cover photo upload coming soon', 'normal')">
                        <i class="fas fa-camera"></i> Edit Cover
                    </button>
                </div>
            </div>
        </div>
        
        <div class="profile-info-section">
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->fName }}" class="profile-avatar">
                    <button class="btn-avatar-edit" onclick="document.getElementById('avatarInput').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="avatarInput" accept="image/*" style="display: none;" onchange="uploadAvatar(this)">
                </div>
            </div>
            
            <div class="profile-details">
                <div class="profile-name-section">
                    <h1>{{ Auth::user()->fName }} {{ Auth::user()->lName }}</h1>
                    <p class="profile-email">{{ Auth::user()->email }}</p>
                    @if(Auth::user()->mobNumber)
                        <p class="profile-phone">
                            <i class="fas fa-phone"></i> {{ Auth::user()->mobNumber }}
                        </p>
                    @endif
                </div>
                
                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-value">{{ Auth::user()->posts()->count() }}</div>
                        <div class="stat-label">Posts</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value" id="myFollowersCount">{{ Auth::user()->followers_count }}</div>
                        <div class="stat-label">Followers</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ Auth::user()->following_count }}</div>
                        <div class="stat-label">Following</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ Auth::user()->comments()->count() }}</div>
                        <div class="stat-label">Comments</div>
                    </div>
                </div>
                
                <div class="profile-actions">
                    <button class="btn btn-primary" onclick="editMyProfile()">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <button class="btn btn-outline" onclick="showToast('Share profile link copied', 'success')" id="shareProfileBtn">
                        <i class="fas fa-share-alt"></i> Share Profile
                    </button>
                    <button class="btn btn-outline" onclick="window.location.href='/settings'">
                        <i class="fas fa-cog"></i> Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Content Tabs -->
    <div class="profile-tabs">
        <div class="tabs-header">
            <button class="tab-btn active" data-tab="posts">My Posts</button>
            <button class="tab-btn" data-tab="followers">Followers</button>
            <button class="tab-btn" data-tab="following">Following</button>
            <button class="tab-btn" data-tab="activity">Activity</button>
        </div>
        
        <div class="tabs-content">
            <!-- Posts Tab -->
            <div class="tab-pane active" id="posts-tab">
                <div class="tab-header">
                    <h3>My Posts</h3>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> New Post
                    </a>
                </div>
                
                <div class="posts-grid" id="myPostsGrid">
                    @php
                        $myPosts = Auth::user()->posts()->withCount(['likes', 'comments'])->latest()->take(9)->get();
                    @endphp
                    
                    @if($myPosts->count() > 0)
                        @foreach($myPosts as $post)
                            <div class="post-card-mini">
                                @if($post->image)
                                    <div class="post-image-mini">
                                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}">
                                    </div>
                                @endif
                                <div class="post-content-mini">
                                    <h4>{{ Str::limit($post->title, 40) }}</h4>
                                    <p class="post-excerpt-mini">{{ Str::limit(strip_tags($post->content), 80) }}</p>
                                    <div class="post-meta-mini">
                                        <span class="post-date">{{ $post->created_at->format('M d, Y') }}</span>
                                        <div class="post-stats-mini">
                                            <span><i class="fas fa-heart"></i> {{ $post->likes_count }}</span>
                                            <span><i class="fas fa-comment"></i> {{ $post->comments_count }}</span>
                                            <span><i class="fas fa-eye"></i> {{ $post->views }}</span>
                                        </div>
                                    </div>
                                    <div class="post-actions-mini">
                                        <a href="{{ route('posts.show', $post) }}" class="btn-link">View</a>
                                        <a href="{{ route('posts.edit', $post) }}" class="btn-link">Edit</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-newspaper fa-3x"></i>
                            <h4>No posts yet</h4>
                            <p>Share your thoughts with the community</p>
                            <a href="{{ route('create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Your First Post
                            </a>
                        </div>
                    @endif
                </div>
                
                @if($myPosts->count() > 0)
                    <div class="tab-footer">
                        <a href="{{ route('posts.user', Auth::id()) }}" class="btn btn-outline">View All Posts</a>
                    </div>
                @endif
            </div>
            
            <!-- Followers Tab -->
            <div class="tab-pane" id="followers-tab">
                <div class="tab-header">
                    <h3>Followers</h3>
                    <span class="badge">{{ Auth::user()->followers_count }} people</span>
                </div>
                
                <div class="followers-list" id="myFollowersList">
                    @php
                        $followers = Auth::user()->followers()->withCount(['posts', 'followers'])->latest()->take(10)->get();
                    @endphp
                    
                    @if($followers->count() > 0)
                        @foreach($followers as $follower)
                            <div class="follower-item">
                                <div class="follower-info">
                                    <img src="{{ $follower->small_avatar_url }}" alt="{{ $follower->fName }}" class="follower-avatar">
                                    <div class="follower-details">
                                        <h5>{{ $follower->fName }} {{ $follower->lName }}</h5>
                                        <p>{{ $follower->posts_count }} posts • {{ $follower->followers_count }} followers</p>
                                    </div>
                                </div>
                                <div class="follower-actions">
                                    @if(Auth::user()->isFollowing($follower))
                                        <button class="btn btn-outline btn-sm following" data-user-id="{{ $follower->id }}">
                                            <i class="fas fa-user-check"></i> Following
                                        </button>
                                    @else
                                        <button class="btn btn-primary btn-sm follow-btn" data-user-id="{{ $follower->id }}">
                                            <i class="fas fa-user-plus"></i> Follow
                                        </button>
                                    @endif
                                    <button class="btn btn-outline btn-sm" onclick="showToast('Message feature coming soon', 'normal')">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-users fa-3x"></i>
                            <h4>No followers yet</h4>
                            <p>Start posting to attract followers</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Following Tab -->
            <div class="tab-pane" id="following-tab">
                <div class="tab-header">
                    <h3>Following</h3>
                    <span class="badge">{{ Auth::user()->following_count }} people</span>
                </div>
                
                <div class="following-list" id="myFollowingList">
                    @php
                        $following = Auth::user()->followings()->withCount(['posts', 'followers'])->latest()->take(10)->get();
                    @endphp
                    
                    @if($following->count() > 0)
                        @foreach($following as $user)
                            <div class="following-item">
                                <div class="following-info">
                                    <img src="{{ $user->small_avatar_url }}" alt="{{ $user->fName }}" class="following-avatar">
                                    <div class="following-details">
                                        <h5>{{ $user->fName }} {{ $user->lName }}</h5>
                                        <p>{{ $user->posts_count }} posts • {{ $user->followers_count }} followers</p>
                                    </div>
                                </div>
                                <div class="following-actions">
                                    <button class="btn btn-outline btn-sm unfollow-btn" data-user-id="{{ $user->id }}">
                                        <i class="fas fa-user-times"></i> Unfollow
                                    </button>
                                    <a href="/profile/{{ $user->id }}" class="btn btn-outline btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-friends fa-3x"></i>
                            <h4>Not following anyone yet</h4>
                            <p>Discover and follow interesting people</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Activity Tab -->
            <div class="tab-pane" id="activity-tab">
                <div class="tab-header">
                    <h3>Recent Activity</h3>
                    <button class="btn btn-outline btn-sm" onclick="refreshMyActivity()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                
                <div class="activity-timeline" id="myActivityTimeline">
                    <div class="loading-spinner">
                        <div class="spinner"></div>
                        <p>Loading your activity...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* My Profile Modern Styles */
    .my-profile-container {
        padding: 0;
    }
    
    /* Profile Header */
    .profile-header-section {
        margin-bottom: 40px;
    }
    
    .profile-cover {
        position: relative;
        height: 200px;
        border-radius: 16px 16px 0 0;
        overflow: hidden;
        margin-bottom: 80px;
    }
    
    .cover-image {
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        position: relative;
    }
    
    .cover-overlay {
        position: absolute;
        top: 20px;
        right: 20px;
        opacity: 0;
        transition: opacity 0.3s;
    }
    
    .profile-cover:hover .cover-overlay {
        opacity: 1;
    }
    
    .btn-cover-edit {
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .profile-info-section {
        padding: 0 30px;
    }
    
    .avatar-section {
        position: relative;
        margin-top: -100px;
        z-index: 2;
    }
    
    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 5px solid var(--card-bg);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        object-fit: cover;
    }
    
    .btn-avatar-edit {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        border: 3px solid var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .btn-avatar-edit:hover {
        background: var(--secondary-color);
        transform: scale(1.1);
    }
    
    .profile-details {
        margin-top: 20px;
    }
    
    .profile-name-section h1 {
        font-size: 2.2rem;
        margin-bottom: 5px;
        color: var(--text-main);
    }
    
    .profile-email {
        color: var(--text-muted);
        margin-bottom: 5px;
        font-size: 1rem;
    }
    
    .profile-phone {
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
    }
    
    .profile-stats {
        display: flex;
        gap: 30px;
        margin: 25px 0;
        flex-wrap: wrap;
    }
    
    .stat-card {
        text-align: center;
        padding: 15px 25px;
        background: var(--card-bg);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        min-width: 120px;
        transition: all 0.3s;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .stat-card .stat-value {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
    
    .stat-card .stat-label {
        font-size: 0.9rem;
        color: var(--text-muted);
    }
    
    .profile-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    /* Profile Tabs */
    .profile-tabs {
        background: var(--card-bg);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        margin-top: 30px;
    }
    
    .tabs-header {
        display: flex;
        border-bottom: 1px solid var(--border-color);
        background: rgba(67, 97, 238, 0.03);
        overflow-x: auto;
    }
    
    .tab-btn {
        padding: 18px 30px;
        background: none;
        border: none;
        font-size: 1rem;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        position: relative;
        white-space: nowrap;
        transition: all 0.3s;
    }
    
    .tab-btn:hover {
        color: var(--primary-color);
        background: rgba(67, 97, 238, 0.05);
    }
    
    .tab-btn.active {
        color: var(--primary-color);
        font-weight: 600;
    }
    
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-color);
        border-radius: 3px 3px 0 0;
    }
    
    .tabs-content {
        padding: 30px;
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    .tab-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .badge {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    /* Posts Grid */
    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    
    .post-card-mini {
        background: var(--bg-color);
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: all 0.3s;
    }
    
    .post-card-mini:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .post-image-mini {
        height: 160px;
        overflow: hidden;
    }
    
    .post-image-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    
    .post-card-mini:hover .post-image-mini img {
        transform: scale(1.05);
    }
    
    .post-content-mini {
        padding: 20px;
    }
    
    .post-content-mini h4 {
        font-size: 1.1rem;
        margin-bottom: 10px;
        color: var(--text-main);
        line-height: 1.4;
    }
    
    .post-excerpt-mini {
        color: var(--text-muted);
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .post-meta-mini {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .post-date {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    
    .post-stats-mini {
        display: flex;
        gap: 15px;
    }
    
    .post-stats-mini span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    
    .post-stats-mini i {
        color: var(--primary-color);
    }
    
    .post-actions-mini {
        display: flex;
        gap: 15px;
    }
    
    .btn-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .btn-link:hover {
        color: var(--secondary-color);
        text-decoration: underline;
    }
    
    /* Followers/Following Lists */
    .followers-list,
    .following-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    
    .follower-item,
    .following-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px;
        background: var(--bg-color);
        border-radius: 12px;
        border: 1px solid var(--border-color);
        transition: all 0.3s;
    }
    
    .follower-item:hover,
    .following-item:hover {
        transform: translateX(5px);
        border-color: var(--primary-color);
    }
    
    .follower-info,
    .following-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .follower-avatar,
    .following-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .follower-details h5,
    .following-details h5 {
        margin-bottom: 5px;
        font-size: 1rem;
    }
    
    .follower-details p,
    .following-details p {
        color: var(--text-muted);
        font-size: 0.85rem;
    }
    
    .follower-actions,
    .following-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.85rem;
    }
    
    /* Activity Timeline */
    .activity-timeline {
        position: relative;
        padding-left: 30px;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: var(--border-color);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 25px;
        padding-left: 30px;
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -16px;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid var(--card-bg);
    }
    
    .timeline-content {
        background: var(--bg-color);
        padding: 15px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    
    .timeline-text {
        margin-bottom: 5px;
        font-size: 0.95rem;
    }
    
    .timeline-time {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: var(--text-muted);
    }
    
    .empty-state i {
        font-size: 3.5rem;
        margin-bottom: 15px;
        opacity: 0.3;
    }
    
    .empty-state h4 {
        margin-bottom: 10px;
        color: var(--text-main);
    }
    
    .empty-state p {
        margin-bottom: 20px;
    }
    
    .tab-footer {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
    
    /* Loading Spinner */
    .loading-spinner {
        text-align: center;
        padding: 40px;
    }
    
    .loading-spinner .spinner {
        width: 40px;
        height: 40px;
        border: 4px solid var(--border-color);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .profile-cover {
            height: 150px;
            margin-bottom: 60px;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
        }
        
        .profile-stats {
            gap: 15px;
        }
        
        .stat-card {
            flex: 1;
            min-width: 100px;
            padding: 12px 15px;
        }
        
        .profile-actions {
            flex-direction: column;
        }
        
        .profile-actions .btn {
            width: 100%;
        }
        
        .posts-grid {
            grid-template-columns: 1fr;
        }
        
        .follower-item,
        .following-item {
            flex-direction: column;
            align-items: stretch;
            gap: 15px;
        }
        
        .follower-actions,
        .following-actions {
            justify-content: flex-start;
        }
        
        .tabs-header {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .tab-btn {
            padding: 15px 20px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 480px) {
        .profile-info-section {
            padding: 0 15px;
        }
        
        .tabs-content {
            padding: 20px 15px;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeProfileTabs();
        initializeFollowButtons();
        loadMyActivity();
    });
    
    function initializeProfileTabs() {
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabPanes = document.querySelectorAll('.tab-pane');
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons and panes
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanes.forEach(p => p.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Show corresponding tab pane
                const tabId = this.getAttribute('data-tab');
                document.getElementById(`${tabId}-tab`).classList.add('active');
            });
        });
    }
    
    function initializeFollowButtons() {
        // Follow buttons in followers list
        document.querySelectorAll('.follow-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                toggleFollow(userId, this);
            });
        });
        
        // Unfollow buttons in following list
        document.querySelectorAll('.unfollow-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                unfollowUser(userId, this);
            });
        });
        
        // Following buttons in followers list
        document.querySelectorAll('.following').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.getAttribute('data-user-id');
                unfollowUser(userId, this);
            });
        });
    }
    
    function toggleFollow(userId, buttonElement) {
        // Store original state
        const originalHTML = buttonElement.innerHTML;
        const originalClasses = buttonElement.className;
        
        // Show loading
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        buttonElement.disabled = true;
        
        fetch(`/users/${userId}/toggle-follow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            buttonElement.disabled = false;
            
            if (data.success) {
                if (data.action === 'followed') {
                    buttonElement.innerHTML = '<i class="fas fa-user-check"></i> Following';
                    buttonElement.classList.remove('btn-primary');
                    buttonElement.classList.add('btn-outline', 'following');
                } else {
                    buttonElement.innerHTML = '<i class="fas fa-user-plus"></i> Follow';
                    buttonElement.classList.remove('btn-outline', 'following');
                    buttonElement.classList.add('btn-primary');
                }
                
                // Update followers count
                const followersCount = document.getElementById('myFollowersCount');
                if (followersCount && data.followers_count !== undefined) {
                    followersCount.textContent = data.followers_count;
                }
                
                showToast(data.message, 'success');
            } else {
                buttonElement.innerHTML = originalHTML;
                buttonElement.className = originalClasses;
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalHTML;
            buttonElement.className = originalClasses;
            showToast('Network error', 'error');
        });
    }
    
    function unfollowUser(userId, buttonElement) {
        if (!confirm('Are you sure you want to unfollow this user?')) {
            return;
        }
        
        const originalHTML = buttonElement.innerHTML;
        
        buttonElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        buttonElement.disabled = true;
        
        fetch(`/users/${userId}/unfollow`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            buttonElement.disabled = false;
            
            if (data.success) {
                // Remove the item from the list
                const item = buttonElement.closest('.follower-item, .following-item');
                if (item) {
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.remove();
                        // Check if list is empty
                        const list = document.querySelector('.followers-list, .following-list');
                        if (list && list.children.length === 0) {
                            list.innerHTML = `
                                <div class="empty-state">
                                    <i class="fas fa-users fa-3x"></i>
                                    <h4>No users found</h4>
                                    <p>Start following people to see them here</p>
                                </div>
                            `;
                        }
                    }, 300);
                }
                
                // Update followers count
                const followersCount = document.getElementById('myFollowersCount');
                if (followersCount && data.followers_count !== undefined) {
                    followersCount.textContent = data.followers_count;
                }
                
                showToast(data.message, 'success');
            } else {
                buttonElement.innerHTML = originalHTML;
                showToast(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            buttonElement.disabled = false;
            buttonElement.innerHTML = originalHTML;
            showToast('Network error', 'error');
        });
    }
    
    function uploadAvatar(input) {
        if (input.files.length > 0) {
            const file = input.files[0];
            const formData = new FormData();
            formData.append('avatar', file);
            
            showToast('Uploading avatar...', 'normal');
            
            fetch('/avatar/upload', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update avatar image
                    const avatarImg = document.querySelector('.profile-avatar');
                    if (avatarImg) {
                        avatarImg.src = data.avatar_url + '?t=' + Date.now();
                    }
                    showToast('Avatar updated successfully!', 'success');
                } else {
                    showToast(data.message || 'Upload failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Upload failed', 'error');
            });
        }
    }
    
    function editMyProfile() {
        // Switch to edit mode
        window.location.href = '/profile/edit'; // You need to create this route
    }
    
    function loadMyActivity() {
        const timeline = document.getElementById('myActivityTimeline');
        if (!timeline) return;
        
        fetch('/api/my-activity', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.activities.length > 0) {
                timeline.innerHTML = data.activities.map(activity => `
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-text">${activity.text}</div>
                            <div class="timeline-time">${activity.time}</div>
                        </div>
                    </div>
                `).join('');
            } else {
                timeline.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-history fa-3x"></i>
                        <h4>No recent activity</h4>
                        <p>Your activity will appear here</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            timeline.innerHTML = `
                <div class="error-state">
                    <i class="fas fa-exclamation-triangle"></i>
                    <p>Failed to load activity</p>
                    <button class="btn btn-sm btn-outline" onclick="loadMyActivity()">Retry</button>
                </div>
            `;
        });
    }
    
    function refreshMyActivity() {
        loadMyActivity();
        showToast('Activity refreshed', 'success');
    }
    
    // Share profile functionality
    document.getElementById('shareProfileBtn').addEventListener('click', function() {
        const profileUrl = window.location.origin + '/profile/' + {{ Auth::id() }};
        navigator.clipboard.writeText(profileUrl).then(() => {
            showToast('Profile link copied to clipboard!', 'success');
        }).catch(err => {
            console.error('Failed to copy:', err);
            showToast('Failed to copy link', 'error');
        });
    });
</script>