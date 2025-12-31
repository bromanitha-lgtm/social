@extends('test.posts.app')

@section('title', $post->title . ' - PostShare')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Post Card -->
        <div class="card shadow-sm mb-4">
            <!-- Post Image -->
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" 
                     class="card-img-top" 
                     alt="{{ $post->title }}"
                     style="max-height: 500px; object-fit: cover;">
            @endif
            
            <div class="card-body">
                <!-- Post Title -->
                <h1 class="card-title mb-3">{{ $post->title }}</h1>
                
                <!-- 🔥 AUTHOR INFO WITH NAME (ENHANCED) 🔥 -->
                <div class="d-flex align-items-center mb-4 p-3 bg-light rounded">
                    @if($post->user->profile_pic)
                        <img src="{{ asset('storage/' . $post->user->profile_pic) }}" 
                             class="rounded-circle me-3" 
                             style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-person text-white" style="font-size: 1.8rem;"></i>
                        </div>
                    @endif
                    
                    <div>
                        <h5 class="mb-1 fw-bold">{{ $post->user->fName }}</h5>
                        <div class="text-muted">
                            <small>
                                <i class="bi bi-calendar"></i> 
                                Posted on {{ $post->created_at->format('F j, Y') }}
                            </small>
                            <br>
                            <small>
                                <i class="bi bi-clock"></i> 
                                {{ $post->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Post Content -->
                <div class="post-content mb-4">
                    {!! nl2br(e($post->content)) !!}
                </div>
                
                <hr>
                
                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Posts
                        </a>
                    </div>
                    
                    @auth
                        @if(Auth::id() == $post->user_id)
                            <div class="btn-group">
                                <a href="{{ route('posts.edit', $post) }}" 
                                   class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button" 
                                        class="btn btn-outline-danger" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#deleteModal">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        
        <!-- Comments Section (Optional - Future Feature) -->
        <div class="card shadow-sm">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-chat-left-text"></i> Comments
                    <span class="badge bg-secondary ms-2">Coming Soon</span>
                </h5>
            </div>
            <div class="card-body text-center py-5">
                <i class="bi bi-chat-square-text" style="font-size: 3rem; color: #6c757d;"></i>
                <h5 class="mt-3">Comments Feature Coming Soon</h5>
                <p class="text-muted">Users will be able to comment on posts in the next update.</p>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
@auth
    @if(Auth::id() == $post->user_id)
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this post?</p>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            This action cannot be undone. All post data will be permanently deleted.
                        </div>
                        <p class="mb-0">
                            <strong>Post Title:</strong> {{ $post->title }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Delete Post
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endauth

<style>
    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }
    
    .post-content p {
        margin-bottom: 1.5rem;
    }
</style>
@endsection