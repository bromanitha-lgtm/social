{{-- views/test/posts/index.blade.php --}}

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
        @php
            $isLikedByUser = Auth::check() && $post->isLikedBy(Auth::user());
        @endphp
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
                                        data-liked="{{ $isLikedByUser ? 'true' : 'false' }}">
                                    <i class="{{ $isLikedByUser ? 'fas' : 'far' }} fa-heart"></i>
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
    
    .like-btn.text-danger {
        border-color: #dc3545;
    }
    
    .like-btn.text-danger:hover {
        background-color: #dc3545;
        color: white;
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
        
        // Initialize like buttons for initial posts
        initializeLikeButtons();
        
        // Function to check if user is near bottom of page
        function isNearBottom() {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight;
            const clientHeight = document.documentElement.clientHeight;
            
            // Check if user is within 500px of the bottom
            return (scrollTop + clientHeight) >= (scrollHeight - 500);
        }
        
        // Scroll event listener
        let scrollTimeout;
        window.addEventListener('scroll', function() {
            // Debounce the scroll event
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                if (!isLoading && hasMorePages && isNearBottom()) {
                    loadMorePosts();
                }
            }, 100);
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
            const loadUrl = nextPageUrl + (nextPageUrl.includes('?') ? '&' : '?') + 'ajax=1&_=' + Date.now();
            
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
                    
                    // Re-initialize like buttons for new posts
                    setTimeout(() => {
                        initializeLikeButtons();
                    }, 100);
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
        
        // Function to render posts from AJAX
        function renderPosts(posts) {
            const postsContainer = document.getElementById('posts-container');
            
            posts.forEach(post => {
                // For AJAX loaded posts, we don't know if user liked them, so default to false
                const isLikedByUser = false;
                
                // Format date
                const postDate = new Date(post.created_at);
                const formattedDate = postDate.toLocaleDateString('en-US', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                
                // Create post HTML - EXACTLY matching the initial posts structure
                const postHtml = `
                    <div class="col-md-6 col-lg-4 mb-4 post-item fade-in">
                        <div class="card h-100 post-card">
                            ${post.image ? 
                                `<img src="/storage/${post.image}" class="card-img-top" alt="${post.title}" style="height: 200px; object-fit: cover;">` 
                                : ''}
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${post.title.length > 50 ? post.title.substring(0, 50) + '...' : post.title}</h5>
                                <p class="card-text flex-grow-1">${post.content.replace(/<[^>]*>/g, '').substring(0, 100)}${post.content.length > 100 ? '...' : ''}</p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-user me-1"></i>
                                            ${post.user.name}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            ${formattedDate}
                                        </small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-sm btn-outline-danger like-btn" 
                                                    data-post-id="${post.id}"
                                                    data-liked="${isLikedByUser}">
                                                <i class="${isLikedByUser ? 'fas' : 'far'} fa-heart"></i>
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
        }
        
        // Initialize like buttons - SIMPLE AND RELIABLE VERSION
        function initializeLikeButtons() {
            // Remove existing event listeners by replacing buttons
            document.querySelectorAll('.like-btn').forEach(button => {
                const newButton = button.cloneNode(true);
                button.parentNode.replaceChild(newButton, button);
                
                // Add click event to new button
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
            const likeIcon = button.querySelector('i');
            const likeCountSpan = button.querySelector('.like-count');
            
            // Store original values
            const originalLikeCount = parseInt(likeCountSpan.textContent);
            
            // Immediate UI feedback
            if (isLiked) {
                // Unlike - update UI immediately
                button.setAttribute('data-liked', 'false');
                likeIcon.className = 'far fa-heart';
                button.classList.remove('text-danger');
                likeCountSpan.textContent = originalLikeCount - 1;
            } else {
                // Like - update UI immediately
                button.setAttribute('data-liked', 'true');
                likeIcon.className = 'fas fa-heart';
                button.classList.add('text-danger');
                likeCountSpan.textContent = originalLikeCount + 1;
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
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update like count with server response
                    likeCountSpan.textContent = data.likes_count;
                } else {
                    // If failed, revert UI
                    revertLikeUI(button, isLiked, originalLikeCount);
                    console.log('Like action failed:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert UI on error
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
                button.classList.add('text-danger');
            } else {
                button.setAttribute('data-liked', 'false');
                likeIcon.className = 'far fa-heart';
                button.classList.remove('text-danger');
            }
            likeCountSpan.textContent = originalLikeCount;
        }
        
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