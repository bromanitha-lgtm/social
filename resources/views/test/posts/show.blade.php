@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Post Header -->
            <div class="card mb-4">
                @if($post->image_url)
                <img src="{{ $post->image_url }}" class="card-img-top" alt="{{ $post->title }}" 
                     style="max-height: 400px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <!-- Post Actions -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary">{{ $post->user->name }}</span>
                            <span class="text-muted ms-2">{{ $post->formatted_date }}</span>
                        </div>
                        <div class="btn-group">
                            @auth
                                @if(Auth::id() === $post->user_id || Auth::user()->isAdmin())
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" 
                                      class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <!-- Post Title -->
                    <h1 class="card-title h2 mb-3">{{ $post->title }}</h1>

                    <!-- Post Stats -->
                    <div class="d-flex gap-4 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-eye text-muted me-2"></i>
                            <span>{{ $post->views }} views</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-muted me-2"></i>
                            <span>{{ $post->reading_time }} min read</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-heart text-muted me-2"></i>
                            <span>{{ $post->likes_count }} likes</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-comment text-muted me-2"></i>
                            <span>{{ $post->comments_count }} comments</span>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="post-content mb-4">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Post Tags/Categories -->
                    <div class="mb-4">
                        <span class="badge bg-light text-dark me-2">Article</span>
                        <span class="badge bg-light text-dark">Tutorial</span>
                    </div>

                    <!-- Post Actions -->
                    <div class="d-flex gap-3 mb-4">
                        <!-- Like Button -->
                        @auth
                        <button class="btn btn-outline-danger like-btn" 
                                data-post-id="{{ $post->id }}"
                                data-liked="{{ $isLiked ? 'true' : 'false' }}">
                            <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart me-2"></i>
                            <span class="like-count">{{ $post->likes_count }}</span>
                        </button>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-outline-danger">
                            <i class="far fa-heart me-2"></i>
                            <span>{{ $post->likes_count }}</span>
                        </a>
                        @endauth

                        <!-- Share Button -->
                        <button class="btn btn-outline-primary" onclick="sharePost()">
                            <i class="fas fa-share-alt me-2"></i> Share
                        </button>

                        <!-- Save Button -->
                        @auth
                        <button class="btn btn-outline-secondary">
                            <i class="far fa-bookmark me-2"></i> Save
                        </button>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-comments me-2"></i>
                        Comments ({{ $post->comments_count }})
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Comment Form -->
                    @auth
                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea name="content" class="form-control" rows="3" 
                                      placeholder="Write a comment..." required></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Post Comment
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-info mb-4">
                        <a href="{{ route('login') }}" class="alert-link">Login</a> to post a comment.
                    </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="comments-list">
                        @forelse($post->comments as $comment)
                        <div class="comment-item mb-3 pb-3 border-bottom">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="avatar-circle bg-primary text-white">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">{{ $comment->user->name }}</h6>
                                        <small class="text-muted">{{ $comment->formatted_date }}</small>
                                    </div>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4">
                            <i class="fas fa-comment-slash fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Info -->
            <div class="card mb-4">
                <div class="card-body text-center">
                    <div class="author-avatar mb-3">
                        <div class="avatar-circle-lg bg-primary text-white mx-auto">
                            {{ strtoupper(substr($post->user->name, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="card-title">{{ $post->user->name }}</h5>
                    <p class="text-muted">Author</p>
                    <div class="d-flex justify-content-center gap-3 mb-3">
                        <div>
                            <h6 class="mb-0">{{ $post->user->posts()->count() }}</h6>
                            <small class="text-muted">Posts</small>
                        </div>
                        <div>
                            <h6 class="mb-0">0</h6>
                            <small class="text-muted">Followers</small>
                        </div>
                    </div>
                    @auth
                        @if(Auth::id() !== $post->user_id)
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user-plus me-2"></i>Follow
                        </button>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Related Posts -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Related Posts</h5>
                </div>
                <div class="card-body">
                    @php
                        $relatedPosts = \App\Models\Post::where('user_id', $post->user_id)
                            ->where('id', '!=', $post->id)
                            ->latest()
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @forelse($relatedPosts as $related)
                    <div class="related-post mb-3">
                        <h6 class="mb-1">
                            <a href="{{ route('posts.show', $related) }}" class="text-decoration-none">
                                {{ Str::limit($related->title, 50) }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            {{ $related->formatted_date }} · {{ $related->views }} views
                        </small>
                    </div>
                    @empty
                    <p class="text-muted mb-0">No related posts found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
    }
    
    .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
    }
    
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .avatar-circle-lg {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 2rem;
    }
    
    .like-btn:hover {
        transform: scale(1.05);
        transition: transform 0.2s;
    }
</style>
@endpush

@push('scripts')
<script>
    // Like functionality
    const likeBtn = document.querySelector('.like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            const postId = this.dataset.postId;
            const isLiked = this.dataset.liked === 'true';
            
            fetch(isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const likeCount = this.querySelector('.like-count');
                    const likeIcon = this.querySelector('i');
                    
                    if (isLiked) {
                        this.dataset.liked = 'false';
                        likeIcon.className = 'far fa-heart me-2';
                        this.classList.remove('text-danger');
                    } else {
                        this.dataset.liked = 'true';
                        likeIcon.className = 'fas fa-heart me-2';
                        this.classList.add('text-danger');
                    }
                    
                    if (likeCount) {
                        likeCount.textContent = data.likes_count;
                    }
                    
                    // Update comments count if needed
                    const commentsCount = document.querySelector('.comments-count');
                    if (commentsCount) {
                        commentsCount.textContent = data.comments_count || '{{ $post->comments_count }}';
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    }
    
    // Share functionality
    function sharePost() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $post->title }}',
                text: 'Check out this post!',
                url: window.location.href
            })
            .then(() => console.log('Successful share'))
            .catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback: Copy to clipboard
            navigator.clipboard.writeText(window.location.href)
                .then(() => alert('Link copied to clipboard!'))
                .catch(err => console.error('Failed to copy:', err));
        }
    }
    
    // Reading progress indicator
    document.addEventListener('DOMContentLoaded', function() {
        const progressBar = document.createElement('div');
        progressBar.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(to right, #4361ee, #3a0ca3);
            width: 0%;
            z-index: 9999;
            transition: width 0.1s;
        `;
        document.body.appendChild(progressBar);
        
        window.addEventListener('scroll', function() {
            const winHeight = window.innerHeight;
            const docHeight = document.documentElement.scrollHeight;
            const scrolled = window.scrollY;
            const progress = (scrolled / (docHeight - winHeight)) * 100;
            progressBar.style.width = progress + '%';
        });
    });
</script>
@endpush