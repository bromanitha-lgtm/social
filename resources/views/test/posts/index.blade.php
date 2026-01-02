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
                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary" id="latest-btn">
                    <i class="fas fa-clock"></i> Latest
                </a>
                <a href="{{ route('posts.popular') }}" class="btn btn-outline-primary" id="popular-btn">
                    <i class="fas fa-fire"></i> Popular
                </a>
            </div>
        </div>
    </div>

    <!-- Posts Container -->
    <div class="row" id="posts-container">
        @include('test.posts.partials._post-cards', ['posts' => $posts])
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
            <p class="text-muted">No more posts to load</p>
        </div>
    </div>

    <!-- Pagination (Fallback for non-JS) -->
    @if($posts->hasPages() && !request()->ajax())
    <div class="row mt-4">
        <div class="col-12">
            {{ $posts->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    #loading-spinner {
        min-height: 100px;
    }
    
    .post-card {
        transition: transform 0.3s ease;
    }
    
    .post-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        let isLoading = false;
        let nextPageUrl = '{{ $posts->nextPageUrl() }}';
        let currentUrl = window.location.href;
        
        // Scroll event listener for infinite scroll
        $(window).on('scroll', function() {
            // Check if user has scrolled to bottom
            if ($(window).scrollTop() + $(window).height() >= $(document).height() - 300) {
                loadMorePosts();
            }
        });
        
        // Load more posts function
        function loadMorePosts() {
            // Don't load if already loading or no more pages
            if (isLoading || !nextPageUrl) {
                return;
            }
            
            isLoading = true;
            $('#loading-spinner').removeClass('d-none');
            
            // Add timestamp to prevent caching
            const url = nextPageUrl + '&ajax=1&_=' + new Date().getTime();
            
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.html) {
                        // Append new posts
                        $('#posts-container').append(response.html);
                        
                        // Update next page URL
                        nextPageUrl = response.next_page;
                        
                        if (!nextPageUrl) {
                            // Show "no more posts" message
                            $('#no-more-posts').removeClass('d-none');
                        }
                    }
                    
                    // Re-initialize like buttons for new posts
                    initializeLikeButtons();
                },
                error: function(xhr, status, error) {
                    console.error('Error loading more posts:', error);
                },
                complete: function() {
                    isLoading = false;
                    $('#loading-spinner').addClass('d-none');
                }
            });
        }
        
        // Initialize like buttons functionality
        function initializeLikeButtons() {
            $('.like-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const button = $(this);
                const postId = button.data('post-id');
                const isLiked = button.data('liked') === 'true';
                
                $.ajax({
                    url: isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.success) {
                            const likeCount = button.find('.like-count');
                            const likeIcon = button.find('i');
                            
                            if (isLiked) {
                                button.data('liked', 'false');
                                likeIcon.removeClass('fas').addClass('far');
                                button.removeClass('text-danger');
                            } else {
                                button.data('liked', 'true');
                                likeIcon.removeClass('far').addClass('fas');
                                button.addClass('text-danger');
                            }
                            
                            if (likeCount.length) {
                                likeCount.text(data.likes_count);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            });
        }
        
        // Initialize like buttons on page load
        initializeLikeButtons();
        
        // Handle search form submission with AJAX
        $('#search-form').on('submit', function(e) {
            e.preventDefault();
            const query = $('#search-input').val();
            const url = '{{ route("posts.search") }}?q=' + encodeURIComponent(query) + '&ajax=1';
            
            loadPage(url);
        });
        
        // Handle latest/popular links with AJAX
        $('#latest-btn, #popular-btn').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href') + '?ajax=1';
            
            loadPage(url);
        });
        
        // Function to load a page with AJAX
        function loadPage(url) {
            isLoading = true;
            $('#loading-spinner').removeClass('d-none');
            $('#no-more-posts').addClass('d-none');
            
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.html) {
                        // Replace posts container content
                        $('#posts-container').html(response.html);
                        
                        // Update next page URL
                        nextPageUrl = response.next_page;
                        currentUrl = url;
                        
                        if (!nextPageUrl) {
                            $('#no-more-posts').removeClass('d-none');
                        }
                    }
                    
                    // Re-initialize like buttons
                    initializeLikeButtons();
                    
                    // Scroll to top
                    $('html, body').animate({ scrollTop: 0 }, 300);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading page:', error);
                    // Fallback to normal page load
                    window.location.href = url.replace('&ajax=1', '');
                },
                complete: function() {
                    isLoading = false;
                    $('#loading-spinner').addClass('d-none');
                }
            });
        }
        
        // Initial check for no more posts
        if (!nextPageUrl && $('#posts-container .col-md-6').length > 0) {
            $('#no-more-posts').removeClass('d-none');
        }
    });
</script>
@endpush