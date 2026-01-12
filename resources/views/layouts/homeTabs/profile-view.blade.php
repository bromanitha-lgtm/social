<div class="profile-view-container">
    <!-- Back Button -->
    <div class="mb-4">
        <button class="btn btn-outline" onclick="loadMyProfile()">
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
                
                <button class="btn btn-outline">
                    <i class="fas fa-envelope"></i> Message
                </button>
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
                    <div class="post-card">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="post-image">
                        @endif
                        <div class="post-content">
                            <h4>{{ Str::limit($post->title, 50) }}</h4>
                            <p class="post-excerpt">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                            <div class="post-meta">
                                <span><i class="fas fa-heart"></i> {{ $post->likes_count }}</span>
                                <span><i class="fas fa-comment"></i> {{ $post->comments_count }}</span>
                                <span><i class="fas fa-eye"></i> {{ $post->views }}</span>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-primary mt-3">Read More</a>
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
    }
</style>

<script>
    function loadMyProfile() {
        fetch('/my-profile-data')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('profileContent').innerHTML = data.html;
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>