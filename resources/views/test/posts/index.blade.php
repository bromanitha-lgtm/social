@extends('layouts.app')

@section('title', 'All Posts')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h2">All Posts</h1>
            <p class="text-muted">Browse through all articles and stories</p>
        </div>
        <div class="col-md-4 text-end">
            @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Create New Post
            </a>
            @endauth
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="row mb-4">
        <div class="col-md-8">
            <form action="{{ route('posts.search') }}" method="GET" id="search-form">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search posts..." 
                           value="{{ request('q') }}" id="search-input">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="btn-group float-end">
                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary {{ !request()->routeIs('posts.popular') ? 'active' : '' }}">
                    <i class="fas fa-clock"></i> Latest
                </a>
                <a href="{{ route('posts.popular') }}" class="btn btn-outline-primary {{ request()->routeIs('posts.popular') ? 'active' : '' }}">
                    <i class="fas fa-fire"></i> Popular
                </a>
            </div>
        </div>
    </div>

    <!-- Posts Container -->
    <div class="row" id="posts-container">
        @foreach($posts as $post)
        <div class="col-md-6 col-lg-4 mb-4 post-item">
            <div class="card h-100 post-card">
                @if($post->image)
                <img src="{{ Storage::url($post->image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ Str::limit($post->title, 50) }}</h5>
                    <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($post->content), 100) }}</p>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <small class="text-muted">
                                <i class="fas fa-user me-1"></i>
                                {{ $post->user->name }}
                            </small>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $post->created_at->format('M d, Y') }}
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <div>
                                <button class="btn btn-sm btn-outline-danger like-btn" 
                                        data-post-id="{{ $post->id }}"
                                        data-liked="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'true' : 'false' }}">
                                    <i class="{{ Auth::check() && $post->isLikedBy(Auth::user()) ? 'fas' : 'far' }} fa-heart"></i>
                                    <span class="like-count ms-1">{{ $post->likes_count }}</span>
                                </button>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <small class="text-muted">
                                    <i class="fas fa-eye me-1"></i> {{ $post->views }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-comment me-1"></i> {{ $post->comments_count }}
                                </small>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary w-100">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        @if($posts->isEmpty())
        <div class="col-12">
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="fas fa-newspaper fa-3x text-muted mb-3"></i>
                    <h4>No posts found</h4>
                    <p class="text-muted">Be the first to create a post!</p>
                    @auth
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        Create Your First Post
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Loading Spinner -->
    <div class="row mt-4 d-none" id="loading-spinner">
        <div class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Loading more posts...</p>
        </div>
    </div>

    <!-- No More Posts -->
    <div class="row mt-4 d-none" id="no-more-posts">
        <div class="col-12 text-center">
            <p class="text-muted">
                <i class="fas fa-check-circle text-success me-2"></i>
                You've reached the end
            </p>
        </div>
    </div>

    <!-- Traditional Pagination (hidden when JS is enabled) -->
    @if($posts->hasPages())
    <div class="row mt-4" id="traditional-pagination">
        <div class="col-12">
            {{ $posts->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .btn-outline-primary.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    
    .post-card {
        transition: transform 0.3s ease;
    }
    
    .post-card:hover {
        transform: translateY(-5px);
    }
    
    #loading-spinner {
        min-height: 100px;
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables for infinite scroll
        let isLoading = false;
        let hasMorePages = {{ $posts->hasMorePages() ? 'true' : 'false' }};
        let nextPageUrl = '{{ $posts->nextPageUrl() }}';
        let currentPage = {{ $posts->currentPage() }};
        
        // Hide traditional pagination if JS is enabled
        document.getElementById('traditional-pagination')?.classList.add('d-none');
        
        // Function to check if user is near bottom of page
        function isNearBottom() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight;
            const clientHeight = document.documentElement.clientHeight;
            
            // Check if user is within 500px of the bottom
            return (scrollTop + clientHeight) >= (scrollHeight - 500);
        }
        
        // Scroll event listener
        window.addEventListener('scroll', function() {
            if (!isLoading && hasMorePages && isNearBottom()) {
                loadMorePosts();
            }
        });
        
        // Load more posts function
        function loadMorePosts() {
            if (isLoading || !hasMorePages || !nextPageUrl) {
                return;
            }
            
            isLoading = true;
            
            // Show loading spinner
            const loadingSpinner = document.getElementById('loading-spinner');
            loadingSpinner.classList.remove('d-none');
            
            // Add AJAX parameter to URL
            const loadUrl = nextPageUrl + (nextPageUrl.includes('?') ? '&' : '?') + 'ajax=1';
            
            fetch(loadUrl, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.posts && data.posts.length > 0) {
                    // Render new posts
                    renderPosts(data.posts);
                    
                    // Update pagination info
                    hasMorePages = data.has_more_pages;
                    nextPageUrl = data.next_page_url;
                    currentPage++;
                } else {
                    // No more posts
                    hasMorePages = false;
                }
                
                // If no more pages, show "no more posts" message
                if (!hasMorePages) {
                    setTimeout(() => {
                        document.getElementById('no-more-posts').classList.remove('d-none');
                    }, 500);
                }
            })
            .catch(error => {
                console.error('Error loading more posts:', error);
                hasMorePages = false; // Stop trying on error
            })
            .finally(() => {
                isLoading = false;
                loadingSpinner.classList.add('d-none');
            });
        }
        
        // Function to render posts
        function renderPosts(posts) {
            const postsContainer = document.getElementById('posts-container');
            
            posts.forEach(post => {
                // Create post HTML
                const postHtml = `
                    <div class="col-md-6 col-lg-4 mb-4 post-item fade-in">
                        <div class="card h-100 post-card">
                            ${post.image ? 
                                `<img src="/storage/${post.image}" class="card-img-top" alt="${post.title}" style="height: 200px; object-fit: cover;">` 
                                : ''}
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${post.title.substring(0, 50)}${post.title.length > 50 ? '...' : ''}</h5>
                                <p class="card-text flex-grow-1">${post.content.substring(0, 100).replace(/<[^>]*>/g, '')}${post.content.length > 100 ? '...' : ''}</p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            ${post.user.name}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            ${new Date(post.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                        </small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-sm btn-outline-danger like-btn" 
                                                    data-post-id="${post.id}"
                                                    data-liked="false">
                                                <i class="far fa-heart"></i>
                                                <span class="like-count ms-1">${post.likes_count}</span>
                                            </button>
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <small class="text-muted">
                                                <i class="fas fa-eye me-1"></i> ${post.views}
                                            </small>
                                            <small class="text-muted">
                                                <i class="fas fa-comment me-1"></i> ${post.comments_count}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <a href="/posts/${post.id}" class="btn btn-outline-primary w-100">
                                            Read More
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                // Append post to container
                postsContainer.insertAdjacentHTML('beforeend', postHtml);
            });
            
            // Re-initialize like buttons for new posts
            initializeLikeButtons();
        }
        
        // Event delegation for like buttons (works for dynamically added buttons)
        function initializeLikeButtons() {
            // Use event delegation for like buttons
            document.getElementById('posts-container').addEventListener('click', function(e) {
                const likeBtn = e.target.closest('.like-btn');
                
                if (likeBtn) {
                    e.preventDefault();
                    handleLikeClick(likeBtn);
                }
            });
        }
        
        // Handle like button click
        function handleLikeClick(button) {
            const postId = button.dataset.postId;
            const isLiked = button.dataset.liked === 'true';
            const likeIcon = button.querySelector('i');
            const likeCount = button.querySelector('.like-count');
            
            // Update UI immediately for better UX
            if (isLiked) {
                button.dataset.liked = 'false';
                likeIcon.classList.remove('fas', 'text-danger');
                likeIcon.classList.add('far');
            } else {
                button.dataset.liked = 'true';
                likeIcon.classList.remove('far');
                likeIcon.classList.add('fas', 'text-danger');
            }
            
            // Send AJAX request
            fetch(isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update like count
                    if (likeCount) {
                        likeCount.textContent = data.likes_count;
                    }
                } else {
                    // Revert UI if failed
                    if (isLiked) {
                        button.dataset.liked = 'true';
                        likeIcon.classList.remove('far');
                        likeIcon.classList.add('fas', 'text-danger');
                    } else {
                        button.dataset.liked = 'false';
                        likeIcon.classList.remove('fas', 'text-danger');
                        likeIcon.classList.add('far');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert UI on error
                if (isLiked) {
                    button.dataset.liked = 'true';
                    likeIcon.classList.remove('far');
                    likeIcon.classList.add('fas', 'text-danger');
                } else {
                    button.dataset.liked = 'false';
                    likeIcon.classList.remove('fas', 'text-danger');
                    likeIcon.classList.add('far');
                }
            });
        }
        
        // Initialize like buttons for initial posts
        initializeLikeButtons();
        
        // Initial check for "no more posts"
        if (!hasMorePages && document.querySelectorAll('.post-item').length > 0) {
            document.getElementById('no-more-posts').classList.remove('d-none');
        }
        
        // Optional: Load more immediately if page is not filled
        setTimeout(() => {
            const postsContainer = document.getElementById('posts-container');
            if (postsContainer && postsContainer.offsetHeight < window.innerHeight && hasMorePages) {
                loadMorePosts();
            }
        }, 1000);
    });
</script>
@endpush