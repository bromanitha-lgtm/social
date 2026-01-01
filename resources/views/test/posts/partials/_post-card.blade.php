<div class="card h-100 post-card">
    @if($post->image_url)
    <img src="{{ $post->image_url }}" class="card-img-top" alt="{{ $post->title }}"
         style="height: 200px; object-fit: cover;">
    @else
    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
         style="height: 200px;">
        <i class="fas fa-newspaper fa-3x text-muted"></i>
    </div>
    @endif
    
    <div class="card-body d-flex flex-column">
        <!-- Post Category/Tags -->
        <div class="mb-2">
            <span class="badge bg-primary">Article</span>
        </div>
        
        <!-- Post Title -->
        <h5 class="card-title">
            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
                {{ Str::limit($post->title, 70) }}
            </a>
        </h5>
        
        <!-- Post Excerpt -->
        <p class="card-text text-muted flex-grow-1">
            {{ $post->excerpt(100) }}
        </p>
        
        <!-- Post Meta -->
        <div class="d-flex justify-content-between align-items-center mt-auto">
            <div class="d-flex align-items-center">
                <div class="avatar-circle-sm bg-primary text-white me-2">
                    {{ strtoupper(substr($post->user->name, 0, 1)) }}
                </div>
                <small>{{ $post->user->name }}</small>
            </div>
            <div class="text-muted">
                <small>{{ $post->formatted_date }}</small>
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-transparent">
        <div class="d-flex justify-content-between">
            <div class="d-flex gap-3">
                <span class="text-muted">
                    <i class="fas fa-eye me-1"></i> {{ $post->views }}
                </span>
                <span class="text-muted">
                    <i class="fas fa-heart me-1"></i> {{ $post->likes_count }}
                </span>
                <span class="text-muted">
                    <i class="fas fa-comment me-1"></i> {{ $post->comments_count }}
                </span>
            </div>
            <div>
                <span class="badge bg-light text-dark">
                    {{ $post->reading_time }} min
                </span>
            </div>
        </div>
    </div>
</div>

<style>
    .post-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .post-card .card-title a:hover {
        color: #4361ee !important;
    }
    
    .avatar-circle-sm {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
</style>