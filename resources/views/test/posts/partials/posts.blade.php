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
                
                <!-- Author Info -->
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
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endforeach