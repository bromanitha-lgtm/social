<div class="my-profile-container">
    <!-- Your existing profile content with follow buttons -->
    
    <!-- Followers/Following Section -->
    <div class="profile-stats mt-5">
        <div class="stat-item">
            <span class="stat-number" id="myFollowersCount">{{ Auth::user()->followers_count }}</span>
            <span class="stat-label">Followers</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ Auth::user()->following_count }}</span>
            <span class="stat-label">Following</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ Auth::user()->posts()->count() }}</span>
            <span class="stat-label">Posts</span>
        </div>
    </div>
    
    <!-- Followers List -->
    <div class="mt-5">
        <h4>Followers</h4>
        <div class="followers-list" id="followersList">
            @php
                $followers = Auth::user()->followers()->withCount(['posts', 'followers'])->take(5)->get();
            @endphp
            
            @foreach($followers as $follower)
                <div class="follower-item">
                    <img src="{{ $follower->small_avatar_url }}" alt="{{ $follower->fName }}" class="follower-avatar">
                    <div class="follower-info">
                        <h6>{{ $follower->fName }} {{ $follower->lName }}</h6>
                        <small>{{ $follower->posts_count }} posts</small>
                    </div>
                    <button class="btn btn-sm {{ Auth::user()->isFollowing($follower) ? 'btn-outline following' : 'btn-primary' }} follow-btn" 
                            data-user-id="{{ $follower->id }}">
                        {{ Auth::user()->isFollowing($follower) ? 'Following' : 'Follow Back' }}
                    </button>
                </div>
            @endforeach
        </div>
    </div>
</div>