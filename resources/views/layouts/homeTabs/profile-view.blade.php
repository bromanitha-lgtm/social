<div class="profile-view-container">
    <!-- Back Button -->
    <div class="mb-4">
        <button class="btn btn-outline" onclick="loadMyProfile()" data-action="back-to-profile">
            <i class="fas fa-arrow-left"></i> Back to My Profile
        </button>
    </div>
    
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="avatar-container">
            <img src="{{ $profileUser->medium_avatar_url }}" alt="{{ $profileUser->fName }}" class="avatar-img">
        </div>
        <div class="profile-info">
            <h1>{{ $profileUser->fName }} {{ $profileUser->lName }}</h1>
            <p class="text-muted">{{ $profileUser->email }}</p>
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-number" id="followersCount">{{ $profileUser->followers_count }}</span>
                    <span class="stat-label">Followers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $profileUser->following_count }}</span>
                    <span class="stat-label">Following</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $profileUser->posts_count }}</span>
                    <span class="stat-label">Posts</span>
                </div>
            </div>
            <div class="action-buttons mt-4">
                @auth
                    @if(Auth::id() != $profileUser->id)
                        <button class="btn {{ Auth::user()->isFollowing($profileUser) ? 'btn-outline following' : 'btn-primary' }} follow-btn" 
                                data-user-id="{{ $profileUser->id }}">
                            <i class="fas {{ Auth::user()->isFollowing($profileUser) ? 'fa-user-check' : 'fa-user-plus' }}"></i>
                            {{ Auth::user()->isFollowing($profileUser) ? 'Following' : 'Follow' }}
                        </button>
                        
                        <!-- Share Button -->
                        <button class="btn btn-outline share-profile-btn" 
                                data-user-id="{{ $profileUser->id }}"
                                data-user-name="{{ $profileUser->fName }} {{ $profileUser->lName }}">
                            <i class="fas fa-share-alt"></i> Share
                        </button>
                        
                        <!-- Message Button -->
                        <button class="btn btn-outline" onclick="showToast('Message feature coming soon!', 'normal')">
                            <i class="fas fa-envelope"></i> Message
                        </button>
                    @else
                        <button class="btn btn-outline" onclick="editProfile()">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Follow
                    </a>
                @endauth
            </div>
        </div>
    </div>
    
    <!-- User Posts Section -->
    <div class="mt-5">
        <h3 class="mb-4">Posts by {{ $profileUser->fName }}</h3>
        <div class="posts-grid" id="userPostsGrid">
            @php
                $userPosts = $profileUser->posts()->withCount(['likes', 'comments'])->latest()->take(6)->get();
            @endphp
            
            @if($userPosts->count() > 0)
                @foreach($userPosts as $post)
                    <div class="post-card" data-post-id="{{ $post->id }}">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="post-image" style="width: 100%; height: 200px; object-fit: cover; border-radius: 8px 8px 0 0;">
                        @endif
                        <div class="post-content" style="padding: 15px;">
                            <h4>{{ Str::limit($post->title, 50) }}</h4>
                            <p class="post-excerpt" style="color: var(--text-muted); margin: 10px 0;">
                                {{ Str::limit(strip_tags($post->content), 100) }}
                            </p>
                            <div class="post-meta" style="display: flex; gap: 15px; font-size: 0.9rem; color: var(--text-muted);">
                                <span>
                                    <button class="profile-post-like-btn {{ $post->isLikedBy(Auth::user()) ? 'liked' : '' }}" 
                                            data-post-id="{{ $post->id }}"
                                            style="background: none; border: none; color: inherit; cursor: pointer;">
                                        <i class="{{ $post->isLikedBy(Auth::user()) ? 'fas' : 'far' }} fa-heart"></i>
                                        <span class="like-count">{{ $post->likes_count }}</span>
                                    </button>
                                </span>
                                <span><i class="fas fa-comment"></i> {{ $post->comments_count }}</span>
                                <span><i class="fas fa-eye"></i> {{ $post->views }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-primary mt-3" style="display: block; text-align: center;">Read More</a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-newspaper fa-3x text-muted"></i>
                    <p class="mt-3">No posts yet</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .profile-view-container {
        padding: 20px;
    }
    
    .follow-btn.following {
        background: var(--text-muted);
        color: white;
    }
    
    .follow-btn.following:hover {
        background: var(--accent-color);
    }
    
    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
        grid-column: 1 / -1;
    }
    
    .profile-post-like-btn.liked {
        color: #dc3545;
    }
    
    .profile-post-like-btn:hover {
        color: #dc3545;
    }
    
    .post-card {
        background: var(--card-bg);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    
    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
        }
    }
</style>

<script>
    function editProfile() {
        showToast('Edit profile feature coming soon!', 'normal');
    }
</script>