@extends('test.posts.app')

@section('title', 'Edit: ' . $post->title . ' - PostShare')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">
                    <i class="bi bi-pencil-square"></i> Edit Post
                </h4>
            </div>
            
            <div class="card-body">
                <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">
                            <i class="bi bi-card-heading"></i> Post Title *
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $post->title) }}"
                               placeholder="Enter post title" 
                               required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            <i class="bi bi-text-paragraph"></i> Content *
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="12" 
                                  placeholder="Write your post content here..." 
                                  required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            You can use basic HTML tags for formatting.
                        </div>
                    </div>
                    
                    <!-- Current Image -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-image"></i> Current Image
                        </label>
                        
                        @if($post->image)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $post->image) }}" 
                                     class="img-thumbnail" 
                                     alt="Current post image"
                                     style="max-height: 300px;">
                                <p class="text-muted mt-2">
                                    <i class="bi bi-info-circle"></i>
                                    Current image. Upload a new one to replace.
                                </p>
                            </div>
                            
                            <!-- Remove Image Option -->
                            <div class="form-check mb-3">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="remove_image" 
                                       name="remove_image" 
                                       value="1">
                                <label class="form-check-label text-danger" for="remove_image">
                                    <i class="bi bi-trash"></i> Remove current image
                                </label>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle"></i>
                                No image currently attached to this post.
                            </div>
                        @endif
                    </div>
                    
                    <!-- New Image Upload -->
                    <div class="mb-4">
                        <label for="image" class="form-label">
                            <i class="bi bi-cloud-arrow-up"></i> Upload New Image (Optional)
                        </label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Max file size: 2MB. Supported formats: JPG, PNG, GIF.
                            Leave empty to keep current image.
                        </div>
                        
                        <!-- New Image Preview -->
                        <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                            <p class="text-muted">New Image Preview:</p>
                            <img id="preview" class="img-thumbnail" style="max-height: 300px;">
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <div>
                            <a href="{{ route('posts.show', $post) }}" 
                               class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <a href="{{ route('posts.index') }}" 
                               class="btn btn-outline-dark ms-2">
                                <i class="bi bi-card-list"></i> All Posts
                            </a>
                        </div>
                        
                        <div>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle"></i> Update Post
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Current Post Preview -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">
                    <i class="bi bi-eye"></i> Current Post Preview
                </h5>
            </div>
            <div class="card-body">
                <h5>{{ $post->title }}</h5>
                <div class="text-muted mb-3">
                    <small>
                        <i class="bi bi-calendar"></i> 
                        Created: {{ $post->created_at->format('M d, Y') }} | 
                        <i class="bi bi-arrow-clockwise"></i>
                        Last updated: {{ $post->updated_at->diffForHumans() }}
                    </small>
                </div>
                <p>{{ Str::limit($post->content, 200) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Script -->
@section('scripts')
<script>
    // Image preview for new upload
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const previewDiv = document.getElementById('imagePreview');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                previewDiv.style.display = 'block';
            }
            
            reader.readAsDataURL(this.files[0]);
        } else {
            previewDiv.style.display = 'none';
        }
    });
    
    // Warn if both remove image and new image are selected
    document.querySelector('form').addEventListener('submit', function(e) {
        const removeImage = document.getElementById('remove_image');
        const newImage = document.getElementById('image');
        
        if (removeImage && removeImage.checked && newImage.files.length > 0) {
            if (!confirm('You selected to remove the current image AND upload a new one. The old image will be removed and replaced with the new one. Continue?')) {
                e.preventDefault();
            }
        }
    });
</script>
@endsection
@endsection