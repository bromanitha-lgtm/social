@extends('test.posts.app')

@section('title', 'All Posts - PostShare')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>
        <i class="bi bi-card-list"></i> All Posts
    </h1>
    
    @auth
        <a href="{{ route('posts.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> New Post
        </a>
    @endauth
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('posts.index') }}" method="GET" class="row g-3">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search posts..." 
                       value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <select name="order" class="form-select">
                    <option value="newest" {{ request('order') == 'newest' ? 'selected' : '' }}>
                        Newest First
                    </option>
                    <option value="oldest" {{ request('order') == 'oldest' ? 'selected' : '' }}>
                        Oldest First
                    </option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Posts Grid -->
@if($posts->count() > 0)
    <div class="row">
        @foreach($posts as $post)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card post-card h-100">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" 
                             class="card-img-top" 
                             alt="{{ $post->title }}"
                             style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                             style="height: 200px;">
                            <i class="bi bi-card-image text-white" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ Str::limit($post->title, 60) }}</h5>
                        <p class="card-text text-muted">
                            {{ Str::limit(strip_tags($post->content), 150) }}
                        </p>
                        
                        <!-- 🔥 AUTHOR INFO WITH NAME 🔥 -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center">
                                @if($post->user->profile_pic)
                                    <img src="{{ asset('storage/' . $post->user->profile_pic) }}" 
                                         class="rounded-circle me-2" 
                                         style="width: 30px; height: 30px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                         style="width: 30px; height: 30px;">
                                        <i class="bi bi-person text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <small class="d-block fw-bold">{{ $post->user->name }}</small>
                                    <small class="text-muted">
                                        <i class="bi bi-calendar"></i> {{ $post->created_at->format('M d') }}
                                    </small>
                                </div>
                            </div>
                            <small class="text-muted">
                                {{ $post->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> View
                        </a>
                        
                        @auth
                            @if(Auth::id() == $post->user_id)
                                <div class="btn-group">
                                    <a href="{{ route('posts.edit', $post) }}" 
                                       class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-danger btn-sm" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $post->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                
                                <!-- Delete Modal -->
                                <div class="modal fade" id="deleteModal{{ $post->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Post</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete "{{ $post->title }}"?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
@else
    <div class="alert alert-info text-center py-5">
        <i class="bi bi-info-circle" style="font-size: 3rem;"></i>
        <h4 class="mt-3">No Posts Found</h4>
        <p>Be the first to create a post!</p>
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-success mt-2">
                <i class="bi bi-plus-circle"></i> Create First Post
            </a>
        @else
            <a href="{{ route('register') }}" class="btn btn-primary mt-2">
                <i class="bi bi-person-plus"></i> Join to Post
            </a>
        @endauth
    </div>
@endif
@endsection