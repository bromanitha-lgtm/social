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
            <form action="{{ route('posts.search') }}" method="GET">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search posts..." 
                           value="{{ request('q') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <div class="btn-group float-end">
                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-clock"></i> Latest
                </a>
                <a href="{{ route('posts.popular') }}" class="btn btn-outline-primary">
                    <i class="fas fa-fire"></i> Popular
                </a>
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="row">
        @forelse($posts as $post)
        <div class="col-md-6 col-lg-4 mb-4">
            @include('test.posts.partials._post-card', ['post' => $post])
        </div>
        @empty
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
        @endforelse
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            {{ $posts->links() }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // Like/Unlike functionality
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.like-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const isLiked = this.dataset.liked === 'true';
                
                fetch(isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const likeCount = this.querySelector('.like-count');
                        const likeIcon = this.querySelector('i');
                        
                        if (isLiked) {
                            this.dataset.liked = 'false';
                            likeIcon.className = 'far fa-heart';
                            this.classList.remove('text-danger');
                        } else {
                            this.dataset.liked = 'true';
                            likeIcon.className = 'fas fa-heart';
                            this.classList.add('text-danger');
                        }
                        
                        if (likeCount) {
                            likeCount.textContent = data.likes_count;
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });
    });
</script>
@endpush