<div class="my-profile-container">
    <!-- Profile Header -->
    <div class="profile-header-section">
        <!-- Avatar and Basic Info -->
        <div class="profile-info-section">
            <div class="avatar-section">
                <div class="avatar-wrapper">
                    <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->fName }}" class="profile-avatar">
                    <button class="btn-avatar-edit" onclick="document.getElementById('avatarInput').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <button class="btn-avatar-remove" onclick="removeAvatar()" title="Remove Avatar">
                        <i class="fas fa-times"></i>
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
                    @if(Auth::user()->bio)
                        <p class="profile-bio">{{ Auth::user()->bio }}</p>
                    @endif
                </div>
                
                <!-- Edit Profile Form (Hidden by default) -->
                <div class="edit-profile-form" id="editProfileForm" style="display: none;">
                    <h3><i class="fas fa-user-edit"></i> Edit Your Profile</h3>
                    
                    <form id="profileEditForm" onsubmit="updateProfile(event)">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="editFirstName"><i class="fas fa-user"></i> First Name</label>
                                <input type="text" id="editFirstName" name="fName" value="{{ Auth::user()->fName }}" 
                                       class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editLastName"><i class="fas fa-user"></i> Last Name</label>
                                <input type="text" id="editLastName" name="lName" value="{{ Auth::user()->lName }}" 
                                       class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editEmail"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" id="editEmail" name="email" value="{{ Auth::user()->email }}" 
                                       class="form-input" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="editPhone"><i class="fas fa-phone"></i> Phone Number</label>
                                <input type="text" id="editPhone" name="mobNumber" value="{{ Auth::user()->mobNumber }}" 
                                       class="form-input">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="editBio"><i class="fas fa-info-circle"></i> Bio (Optional)</label>
                            <textarea id="editBio" name="bio" class="form-textarea" rows="3" 
                                      placeholder="Tell us about yourself...">{{ Auth::user()->bio ?? '' }}</textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-outline" onclick="toggleEditMode()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="updateProfileBtn">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                        </div>
                    </form>
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
                    <button class="btn btn-primary" onclick="toggleEditMode()" id="editProfileBtn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </button>
                    <button class="btn btn-outline" onclick="copyProfileLink()" id="shareProfileBtn">
                        <i class="fas fa-share-alt"></i> Share Profile
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
                                        <button class="btn-link text-danger" onclick="deletePost({{ $post->id }})">Delete</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="empty-state">
                            <i class="fas fa-newspaper fa-3x"></i>
                            <h4>No posts yet</h4>
                            <p>Share your thoughts with the community</p>
                            <a href="{{ route('posts.create') }}" class="btn btn-primary">
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
                                    <button class="btn btn-outline btn-sm" onclick="messageUser({{ $follower->id }})">
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
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    /* Profile Header */
    .profile-header-section {
        margin-bottom: 40px;
    }
    
    .profile-info-section {
        display: flex;
        align-items: center;
        gap: 40px;
        padding: 30px;
        background: var(--card-bg);
        border-radius: 20px;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    
    .avatar-section {
        position: relative;
        flex-shrink: 0;
    }
    
    .avatar-wrapper {
        position: relative;
        display: inline-block;
    }
    
    .profile-avatar {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 6px solid rgba(67, 97, 238, 0.1);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        object-fit: cover;
        animation: morph 6s ease-in-out infinite alternate;
    }
    
    .avatar-wrapper:hover .profile-avatar {
        transform: scale(1.02);
        border-color: rgba(67, 97, 238, 0.3);
    }
    
    .btn-avatar-edit {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        border: 4px solid var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.2rem;
    }
    
    .btn-avatar-edit:hover {
        background: var(--secondary-color);
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
    }
    
    .btn-avatar-remove {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(220, 53, 69, 0.9);
        color: white;
        border: 3px solid var(--card-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        opacity: 0;
        transform: scale(0.8);
    }
    
    .avatar-wrapper:hover .btn-avatar-remove {
        opacity: 1;
        transform: scale(1);
    }
    
    .btn-avatar-remove:hover {
        background: #c82333;
        transform: scale(1.1) !important;
    }
    
    .profile-details {
        flex: 1;
        min-width: 0;
    }
    
    .profile-name-section h1 {
        font-size: 2.5rem;
        margin-bottom: 8px;
        color: var(--text-main);
        font-weight: 700;
    }
    
    .profile-email {
        color: var(--text-muted);
        margin-bottom: 8px;
        font-size: 1.1rem;
    }
    
    .profile-phone {
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        margin-bottom: 8px;
    }
    
    .profile-bio {
        color: var(--text-main);
        font-size: 1rem;
        line-height: 1.5;
        margin-top: 15px;
        padding: 15px;
        background: rgba(67, 97, 238, 0.05);
        border-radius: 10px;
        border-left: 4px solid var(--primary-color);
    }
    
    /* Edit Profile Form Styles */
    .edit-profile-form {
        margin-top: 30px;
        padding: 25px;
        background: rgba(67, 97, 238, 0.05);
        border-radius: 16px;
        border: 1px solid rgba(67, 97, 238, 0.1);
        animation: slideDown 0.4s ease;
    }
    
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .edit-profile-form h3 {
        color: var(--primary-color);
        margin-bottom: 20px;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-main);
        font-weight: 600;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .form-input, .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        background: var(--card-bg);
        color: var(--text-main);
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }
    
    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }
    
    .profile-stats {
        display: flex;
        gap: 25px;
        margin: 30px 0;
        flex-wrap: wrap;
    }
    
    .stat-card {
        text-align: center;
        padding: 20px 25px;
        background: rgba(67, 97, 238, 0.05);
        border-radius: 16px;
        border: 1px solid rgba(67, 97, 238, 0.1);
        min-width: 120px;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.1);
        border-color: rgba(67, 97, 238, 0.3);
        background: rgba(67, 97, 238, 0.08);
    }
    
    .stat-card .stat-value {
        font-size: 2.2rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 5px;
        line-height: 1;
    }
    
    .stat-card .stat-label {
        font-size: 0.95rem;
        color: var(--text-muted);
        font-weight: 500;
        letter-spacing: 0.5px;
    }
    
    .profile-actions {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 30px;
    }
    
    .profile-actions .btn {
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .profile-actions .btn-primary {
        background: var(--primary-color);
        border: 2px solid var(--primary-color);
    }
    
    .profile-actions .btn-primary:hover {
        background: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
    }
    
    .profile-actions .btn-outline {
        background: transparent;
        border: 2px solid var(--border-color);
        color: var(--text-main);
    }
    
    .profile-actions .btn-outline:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    /* Profile Tabs */
    .profile-tabs {
        background: var(--card-bg);
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    
    .tabs-header {
        display: flex;
        border-bottom: 1px solid var(--border-color);
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.03), rgba(67, 97, 238, 0.01));
        overflow-x: auto;
        scrollbar-width: none;
    }
    
    .tabs-header::-webkit-scrollbar {
        display: none;
    }
    
    .tab-btn {
        padding: 20px 35px;
        background: none;
        border: none;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--text-muted);
        cursor: pointer;
        position: relative;
        white-space: nowrap;
        transition: all 0.3s ease;
        flex-shrink: 0;
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
        left: 20%;
        right: 20%;
        height: 4px;
        background: var(--primary-color);
        border-radius: 4px 4px 0 0;
        animation: slideIn 0.3s ease-out;
    }
    
    @keyframes slideIn {
        from { left: 50%; right: 50%; }
        to { left: 20%; right: 20%; }
    }
    
    .tabs-content {
        padding: 35px;
    }
    
    .tab-pane {
        display: none;
        animation: fadeIn 0.4s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .tab-pane.active {
        display: block;
    }
    
    .tab-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .tab-header h3 {
        font-size: 1.8rem;
        color: var(--text-main);
        margin: 0;
    }
    
    .badge {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Posts Grid */
    .posts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }
    
    .post-card-mini {
        background: var(--bg-color);
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .post-card-mini:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.12);
        border-color: rgba(67, 97, 238, 0.3);
    }
    
    .post-image-mini {
        height: 180px;
        overflow: hidden;
        position: relative;
    }
    
    .post-image-mini::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.3));
        z-index: 1;
    }
    
    .post-image-mini img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }
    
    .post-card-mini:hover .post-image-mini img {
        transform: scale(1.1);
    }
    
    .post-content-mini {
        padding: 25px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    
    .post-content-mini h4 {
        font-size: 1.2rem;
        margin-bottom: 12px;
        color: var(--text-main);
        line-height: 1.4;
        font-weight: 600;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .post-excerpt-mini {
        color: var(--text-muted);
        font-size: 0.95rem;
        line-height: 1.5;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }
    
    .post-meta-mini {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .post-date {
        font-size: 0.85rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .post-stats-mini {
        display: flex;
        gap: 20px;
    }
    
    .post-stats-mini span {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.9rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    
    .post-stats-mini i {
        color: var(--primary-color);
        font-size: 0.9rem;
    }
    
    .post-actions-mini {
        display: flex;
        gap: 20px;
        margin-top: auto;
    }
    
    .btn-link {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-link:hover {
        color: var(--secondary-color);
        text-decoration: none;
        transform: translateX(3px);
    }
    
    .btn-link.text-danger {
        color: #dc3545;
    }
    
    .btn-link.text-danger:hover {
        color: #c82333;
    }
    
    /* Followers/Following Lists */
    .followers-list,
    .following-list {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }
    
    .follower-item,
    .following-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: var(--bg-color);
        border-radius: 16px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    
    .follower-item:hover,
    .following-item:hover {
        transform: translateX(8px);
        border-color: var(--primary-color);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.1);
    }
    
    .follower-info,
    .following-info {
        display: flex;
        align-items: center;
        gap: 20px;
        flex: 1;
        min-width: 0;
    }
    
    .follower-avatar,
    .following-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid rgba(67, 97, 238, 0.1);
        transition: all 0.3s ease;
    }
    
    .follower-item:hover .follower-avatar,
    .following-item:hover .following-avatar {
        border-color: var(--primary-color);
        transform: scale(1.05);
    }
    
    .follower-details,
    .following-details {
        min-width: 0;
    }
    
    .follower-details h5,
    .following-details h5 {
        margin-bottom: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .follower-details p,
    .following-details p {
        color: var(--text-muted);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .follower-actions,
    .following-actions {
        display: flex;
        gap: 12px;
        flex-shrink: 0;
    }
    
    .btn-sm {
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .btn-sm.following:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    
    /* Activity Timeline */
    .activity-timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .activity-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), transparent);
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 30px;
        animation: slideInRight 0.5s ease;
    }
    
    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -23px;
        top: 8px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 4px solid var(--card-bg);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        z-index: 1;
    }
    
    .timeline-content {
        background: var(--bg-color);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
    }
    
    .timeline-content:hover {
        border-color: var(--primary-color);
        transform: translateX(5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .timeline-text {
        margin-bottom: 10px;
        font-size: 1rem;
        color: var(--text-main);
        line-height: 1.5;
    }
    
    .timeline-time {
        font-size: 0.85rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .timeline-time i {
        font-size: 0.8rem;
    }
    
    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 80px 30px;
        color: var(--text-muted);
        background: var(--bg-color);
        border-radius: 16px;
        border: 2px dashed var(--border-color);
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 25px;
        opacity: 0.2;
        color: var(--primary-color);
    }
    
    .empty-state h4 {
        margin-bottom: 15px;
        color: var(--text-main);
        font-size: 1.5rem;
        font-weight: 600;
    }
    
    .empty-state p {
        margin-bottom: 30px;
        font-size: 1.1rem;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }
    
    .tab-footer {
        text-align: center;
        padding-top: 30px;
        border-top: 1px solid var(--border-color);
    }
    
    /* Loading Spinner */
    .loading-spinner {
        text-align: center;
        padding: 60px 20px;
    }
    
    .loading-spinner .spinner {
        width: 50px;
        height: 50px;
        border: 4px solid var(--border-color);
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
        font-size: 1.1rem;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
        .profile-info-section {
            flex-direction: column;
            text-align: center;
            gap: 30px;
            padding: 30px 20px;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .profile-actions {
            justify-content: center;
        }
        
        .posts-grid {
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        }
    }
    
    @media (max-width: 768px) {
        .my-profile-container {
            padding: 15px;
        }
        
        .profile-avatar {
            width: 140px;
            height: 140px;
        }
        
        .btn-avatar-edit {
            width: 42px;
            height: 42px;
            bottom: 10px;
            right: 10px;
        }
        
        .btn-avatar-remove {
            width: 32px;
            height: 32px;
            top: 8px;
            right: 8px;
        }
        
        .profile-name-section h1 {
            font-size: 2rem;
        }
        
        .profile-stats {
            gap: 15px;
        }
        
        .stat-card {
            flex: 1;
            min-width: calc(50% - 15px);
            padding: 15px 20px;
        }
        
        .profile-actions {
            flex-direction: column;
        }
        
        .profile-actions .btn {
            width: 100%;
            justify-content: center;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions .btn {
            width: 100%;
            justify-content: center;
        }
        
        .tabs-content {
            padding: 25px 20px;
        }
        
        .tab-btn {
            padding: 16px 25px;
            font-size: 1rem;
        }
        
        .posts-grid {
            grid-template-columns: 1fr;
        }
        
        .follower-item,
        .following-item {
            flex-direction: column;
            align-items: stretch;
            gap: 20px;
            text-align: center;
        }
        
        .follower-info,
        .following-info {
            flex-direction: column;
            gap: 15px;
        }
        
        .follower-actions,
        .following-actions {
            justify-content: center;
            flex-wrap: wrap;
        }
    }
    
    @media (max-width: 480px) {
        .profile-info-section {
            padding: 25px 15px;
        }
        
        .tabs-content {
            padding: 20px 15px;
        }
        
        .tab-btn {
            padding: 14px 20px;
            font-size: 0.95rem;
        }
        
        .stat-card .stat-value {
            font-size: 1.8rem;
        }
        
        .post-content-mini {
            padding: 20px;
        }
        
        .activity-timeline {
            padding-left: 30px;
        }
        
        .activity-timeline::before {
            left: 15px;
        }
        
        .timeline-item {
            padding-left: 20px;
        }
        
        .timeline-item::before {
            left: -18px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        initializeProfileTabs();
        initializeFollowButtons();
        loadMyActivity();
        
        // Make sure share button works
        document.getElementById('shareProfileBtn')?.addEventListener('click', copyProfileLink);
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
                const tabPane = document.getElementById(`${tabId}-tab`);
                if (tabPane) {
                    tabPane.classList.add('active');
                    
                    // Lazy load content for followers/following tabs
                    if (tabId === 'followers' || tabId === 'following') {
                        setTimeout(() => initializeFollowButtons(), 100);
                    }
                    
                    // Refresh activity tab when opened
                    if (tabId === 'activity') {
                        setTimeout(loadMyActivity, 300);
                    }
                }
            });
        });
    }
    
    function initializeFollowButtons() {
        // Follow buttons
        document.querySelectorAll('.follow-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const userId = this.getAttribute('data-user-id');
                if (userId) toggleFollow(userId, this);
            });
        });
        
        // Unfollow buttons
        document.querySelectorAll('.unfollow-btn, .following').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const userId = this.getAttribute('data-user-id');
                if (userId) unfollowUser(userId, this);
            });
        });
    }
    
    // Edit Mode Functions
    function toggleEditMode() {
        const editForm = document.getElementById('editProfileForm');
        const editBtn = document.getElementById('editProfileBtn');
        const profileDetails = document.querySelector('.profile-name-section');
        
        if (editForm.style.display === 'none') {
            // Show edit form
            editForm.style.display = 'block';
            editBtn.innerHTML = '<i class="fas fa-times"></i> Cancel Editing';
            editBtn.classList.remove('btn-primary');
            editBtn.classList.add('btn-outline');
            
            // Hide normal profile details
            profileDetails.style.opacity = '0.3';
            
            // Scroll to edit form
            editForm.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            // Hide edit form
            editForm.style.display = 'none';
            editBtn.innerHTML = '<i class="fas fa-edit"></i> Edit Profile';
            editBtn.classList.remove('btn-outline');
            editBtn.classList.add('btn-primary');
            
            // Show normal profile details
            profileDetails.style.opacity = '1';
        }
    }
    
    function updateProfile(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        const updateBtn = document.getElementById('updateProfileBtn');
        const originalBtnText = updateBtn.innerHTML;
        
        // Show loading
        updateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
        updateBtn.disabled = true;
        
        fetch('/profile/update', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            updateBtn.disabled = false;
            updateBtn.innerHTML = originalBtnText;
            
            if (data.success) {
                // Update displayed profile info
                document.querySelector('.profile-name-section h1').textContent = 
                    data.user.fName + ' ' + data.user.lName;
                document.querySelector('.profile-email').textContent = data.user.email;
                
                // Update phone
                if (data.user.mobNumber) {
                    let phoneElement = document.querySelector('.profile-phone');
                    if (phoneElement) {
                        phoneElement.innerHTML = `<i class="fas fa-phone"></i> ${data.user.mobNumber}`;
                    } else {
                        // Create phone element if it doesn't exist
                        const emailElement = document.querySelector('.profile-email');
                        emailElement.insertAdjacentHTML('afterend', 
                            `<p class="profile-phone"><i class="fas fa-phone"></i> ${data.user.mobNumber}</p>`);
                    }
                } else {
                    // Remove phone if it was deleted
                    const phoneElement = document.querySelector('.profile-phone');
                    if (phoneElement) phoneElement.remove();
                }
                
                // Update bio
                if (data.user.bio) {
                    let bioElement = document.querySelector('.profile-bio');
                    if (bioElement) {
                        bioElement.textContent = data.user.bio;
                    } else {
                        // Create bio element if it doesn't exist
                        const phoneElement = document.querySelector('.profile-phone');
                        if (phoneElement) {
                            phoneElement.insertAdjacentHTML('afterend', 
                                `<p class="profile-bio">${data.user.bio}</p>`);
                        } else {
                            const emailElement = document.querySelector('.profile-email');
                            emailElement.insertAdjacentHTML('afterend', 
                                `<p class="profile-bio">${data.user.bio}</p>`);
                        }
                    }
                } else {
                    // Remove bio if it was deleted
                    const bioElement = document.querySelector('.profile-bio');
                    if (bioElement) bioElement.remove();
                }
                
                // Exit edit mode
                toggleEditMode();
                
                showToast('Profile updated successfully!', 'success');
                
                // Update user avatar if changed
                if (data.avatar_url) {
                    const avatarImg = document.querySelector('.profile-avatar');
                    avatarImg.src = data.avatar_url + '?t=' + Date.now();
                }
            } else {
                showToast(data.message || 'Failed to update profile', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            updateBtn.disabled = false;
            updateBtn.innerHTML = originalBtnText;
            showToast('Network error. Please try again.', 'error');
        });
    }
    
    function toggleFollow(userId, buttonElement) {
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
                    // Animate the count change
                    followersCount.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        followersCount.style.transform = 'scale(1)';
                    }, 300);
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
                    item.style.transform = 'translateX(20px)';
                    setTimeout(() => {
                        item.remove();
                        // Check if list is empty
                        const list = item.parentElement;
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
                    followersCount.style.transform = 'scale(1.2)';
                    setTimeout(() => {
                        followersCount.style.transform = 'scale(1)';
                    }, 300);
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
            
            // Show loading on avatar
            const avatarImg = document.querySelector('.profile-avatar');
            const originalSrc = avatarImg.src;
            avatarImg.style.opacity = '0.5';
            
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
                    if (avatarImg) {
                        avatarImg.src = data.avatar_url + '?t=' + Date.now();
                        avatarImg.style.opacity = '1';
                        // Add a quick animation
                        avatarImg.style.transform = 'scale(0.9)';
                        setTimeout(() => {
                            avatarImg.style.transform = 'scale(1)';
                        }, 300);
                    }
                    showToast('Avatar updated successfully!', 'success');
                } else {
                    avatarImg.src = originalSrc;
                    avatarImg.style.opacity = '1';
                    showToast(data.message || 'Upload failed', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                avatarImg.src = originalSrc;
                avatarImg.style.opacity = '1';
                showToast('Upload failed', 'error');
            });
        }
    }
    
    function removeAvatar() {
        if (!confirm('Are you sure you want to remove your avatar?')) return;
        
        fetch('/avatar/remove', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const avatarImg = document.querySelector('.profile-avatar');
                avatarImg.src = data.avatar_url + '?t=' + Date.now();
                showToast('Avatar removed successfully!', 'success');
            } else {
                showToast(data.message || 'Failed to remove avatar', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Network error', 'error');
        });
    }
    
    function loadMyActivity() {
        const timeline = document.getElementById('myActivityTimeline');
        if (!timeline) return;
        
        // Show loading animation
        timeline.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p>Loading your activity...</p>
            </div>
        `;
        
        fetch('/api/my-activity', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.activities && data.activities.length > 0) {
                timeline.innerHTML = data.activities.map(activity => `
                    <div class="timeline-item">
                        <div class="timeline-content">
                            <div class="timeline-text">${activity.text}</div>
                            <div class="timeline-time">
                                <i class="far fa-clock"></i> ${activity.time}
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                timeline.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-history fa-3x"></i>
                        <h4>No recent activity</h4>
                        <p>Your activity will appear here</p>
                        <button class="btn btn-primary mt-3" onclick="refreshMyActivity()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            timeline.innerHTML = `
                <div class="empty-state">
                    <i class="fas fa-exclamation-triangle fa-3x"></i>
                    <h4>Failed to load activity</h4>
                    <p>Please try again</p>
                    <button class="btn btn-outline mt-3" onclick="loadMyActivity()">
                        <i class="fas fa-redo"></i> Retry
                    </button>
                </div>
            `;
        });
    }
    
    function refreshMyActivity() {
        loadMyActivity();
        showToast('Activity refreshed', 'success');
    }
    
    function deletePost(postId) {
        if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
            return;
        }
        
        showToast('Deleting post...', 'normal');
        
        fetch(`/posts/${postId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove post from grid
                const postCard = document.querySelector(`.post-card-mini [onclick="deletePost(${postId})"]`)?.closest('.post-card-mini');
                if (postCard) {
                    postCard.style.opacity = '0';
                    postCard.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        postCard.remove();
                        // Check if no posts left
                        const postsGrid = document.getElementById('myPostsGrid');
                        if (postsGrid && postsGrid.children.length === 0) {
                            postsGrid.innerHTML = `
                                <div class="empty-state">
                                    <i class="fas fa-newspaper fa-3x"></i>
                                    <h4>No posts yet</h4>
                                    <p>Share your thoughts with the community</p>
                                    <a href="{{ route('posts.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus"></i> Create Your First Post
                                    </a>
                                </div>
                            `;
                        }
                    }, 300);
                }
                showToast('Post deleted successfully', 'success');
            } else {
                showToast(data.message || 'Failed to delete post', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Network error', 'error');
        });
    }
    
    function messageUser(userId) {
        showToast('Opening chat...', 'normal');
        // This would normally open a chat modal
        setTimeout(() => {
            // For now, just show a toast
            showToast('Chat feature coming soon!', 'normal');
        }, 500);
    }
    
function copyProfileLink() {
    const profileUrl = window.location.origin + '/profile/' + {{ Auth::id() }};
    navigator.clipboard.writeText(profileUrl).then(() => {
        showToast('Profile link copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy:', err);
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = profileUrl;
        document.body.appendChild(textArea);
        textArea.select();
        try {
            document.execCommand('copy');
            showToast('Profile link copied to clipboard!', 'success');
        } catch (e) {
            showToast('Failed to copy link', 'error');
        }
        document.body.removeChild(textArea);
    });
}
    
    // Make showToast available globally
    window.showToast = function(message, type = 'normal') {
        const container = document.getElementById('toastContainer') || (() => {
            const newContainer = document.createElement('div');
            newContainer.id = 'toastContainer';
            newContainer.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 10000; display: flex; flex-direction: column; gap: 10px;';
            document.body.appendChild(newContainer);
            return newContainer;
        })();
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        let icon = 'fa-info-circle';
        if (type === 'success') icon = 'fa-check-circle';
        if (type === 'error') icon = 'fa-exclamation-circle';
        
        toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
        container.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 10);
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    };
</script>