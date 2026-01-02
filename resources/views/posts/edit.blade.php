<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit: {{ $post->title }} - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .edit-form-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 15px;
        }
        
        .form-card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }
        
        .form-card-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
        }
        
        .form-title {
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-section {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            font-weight: 500;
            color: #495057;
            margin-bottom: 0.5rem;
        }
        
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #f5576c;
            box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25);
        }
        
        .current-image-container {
            margin-top: 1rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
        
        .current-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 6px;
            display: block;
            margin: 0 auto;
        }
        
        .image-preview-container {
            margin-top: 1rem;
            border: 2px dashed #dee2e0;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .new-image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 6px;
        }
        
        .image-placeholder {
            color: #adb5bd;
            font-size: 4rem;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
            color: white;
        }
        
        .btn-cancel {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            color: #495057;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            background-color: #e9ecef;
            color: #495057;
        }
        
        .post-preview-card {
            margin-top: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: none;
            overflow: hidden;
        }
        
        .preview-header {
            background-color: #e9ecef;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }
        
        .preview-title {
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .post-meta {
            display: flex;
            gap: 20px;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .status-published {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-draft {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
        }
        
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #f5576c;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .edit-form-container {
                margin: 1rem auto;
            }
            
            .form-card-header {
                padding: 1rem;
            }
            
            .post-meta {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>
    
    <div class="edit-form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-title">
                    <i class="fas fa-edit"></i>
                    Edit Post
                </h2>
            </div>
            
            <div class="card-body p-4">
                <form id="editPostForm" action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Title -->
                    <div class="form-section">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading me-1"></i>
                            Post Title *
                        </label>
                        <input type="text" 
                               class="form-control @error('title') is-invalid @enderror" 
                               id="title" 
                               name="title" 
                               value="{{ old('title', $post->title) }}"
                               placeholder="Enter a catchy title for your post..."
                               required
                               maxlength="200">
                        <div class="char-count mt-1" id="titleCharCount">{{ strlen($post->title) }}/200 characters</div>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Current Image -->
                    @if($post->image)
                    <div class="form-section">
                        <label class="form-label">
                            <i class="fas fa-image me-1"></i>
                            Current Featured Image
                        </label>
                        <div class="current-image-container">
                            <img src="{{ asset('storage/' . $post->image) }}" 
                                 class="current-image" 
                                 alt="Current post image">
                            <div class="mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="remove_image" 
                                           name="remove_image" 
                                           value="1">
                                    <label class="form-check-label text-danger" for="remove_image">
                                        <i class="fas fa-trash me-1"></i>
                                        Remove current image
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="form-section">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No image currently attached to this post.
                        </div>
                    </div>
                    @endif
                    
                    <!-- New Image Upload -->
                    <div class="form-section">
                        <label for="image" class="form-label">
                            <i class="fas fa-cloud-upload-alt me-1"></i>
                            Upload New Image (Optional)
                        </label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <div class="form-text">
                            Max file size: 5MB. Supported formats: JPG, PNG, GIF, WebP
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- New Image Preview -->
                        <div class="image-preview-container" id="imagePreview" style="display: none;">
                            <div class="text-center">
                                <p class="text-muted mb-2">New Image Preview:</p>
                                <img id="newImagePreview" class="new-image-preview" alt="New image preview">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="form-section">
                        <label for="content" class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Content *
                        </label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="12" 
                                  placeholder="Write your amazing content here... (Minimum 100 characters)"
                                  required>{{ old('content', $post->content) }}</textarea>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                @php
                                    $wordCount = str_word_count(strip_tags($post->content));
                                    $charCount = strlen($post->content);
                                @endphp
                                <span class="word-count" id="wordCount">{{ $wordCount }} words</span>
                                <span class="word-count ms-2" id="charCount">{{ $charCount }} characters</span>
                            </div>
                            <div class="char-count" id="readingTime">~{{ ceil($wordCount / 200) }} min read</div>
                        </div>
                        
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Status -->
                    <div class="form-section">
                        <label for="status" class="form-label">
                            <i class="fas fa-globe me-1"></i>
                            Post Status
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" 
                                name="status">
                            <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>
                                Publish Now
                            </option>
                            <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                Save as Draft
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Published At -->
                    <div class="form-section">
                        <label for="published_at" class="form-label">
                            <i class="fas fa-calendar-alt me-1"></i>
                            Publish Date & Time
                        </label>
                        <input type="datetime-local" 
                               class="form-control @error('published_at') is-invalid @enderror" 
                               id="published_at" 
                               name="published_at" 
                               value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}">
                        @error('published_at')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="form-section">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('posts.show', $post) }}" class="btn btn-cancel">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>
                                <a href="{{ route('posts.index') }}" class="btn btn-cancel ms-2">
                                    <i class="fas fa-list me-2"></i>
                                    All Posts
                                </a>
                            </div>
                            
                            <div>
                                <button type="submit" class="btn btn-submit">
                                    <i class="fas fa-save me-2"></i>
                                    Update Post
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Post Preview -->
        <div class="post-preview-card">
            <div class="preview-header">
                <h3 class="preview-title">
                    <i class="fas fa-eye"></i>
                    Current Post Preview
                </h3>
            </div>
            <div class="card-body p-4">
                <h4>{{ $post->title }}</h4>
                
                <div class="post-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>{{ $post->user->fName }} {{ $post->user->lName }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>Created: {{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-arrow-clockwise"></i>
                        <span>Updated: {{ $post->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="status-badge status-{{ $post->status }}">
                            {{ ucfirst($post->status) }}
                        </span>
                    </div>
                </div>
                
                <div class="mt-3">
                    @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" 
                         class="img-fluid rounded mb-3" 
                         alt="{{ $post->title }}"
                         style="max-height: 200px;">
                    @endif
                    
                    <p class="mb-0">{{ Str::limit(strip_tags($post->content), 300) }}</p>
                </div>
                
                <div class="mt-3 d-flex gap-3">
                    <div class="meta-item">
                        <i class="fas fa-eye"></i>
                        <span>{{ $post->views }} views</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-heart"></i>
                        <span>{{ $post->likes_count }} likes</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-comment"></i>
                        <span>{{ $post->comments_count }} comments</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Title Character Counter
            const titleInput = document.getElementById('title');
            const titleCharCount = document.getElementById('titleCharCount');
            
            titleInput.addEventListener('input', function() {
                const length = this.value.length;
                titleCharCount.textContent = `${length}/200 characters`;
                if (length > 180) {
                    titleCharCount.classList.add('warning');
                } else {
                    titleCharCount.classList.remove('warning');
                }
            });
            
            // New Image Preview
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const newImagePreview = document.getElementById('newImagePreview');
            
            imageInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        newImagePreview.src = e.target.result;
                        imagePreview.style.display = 'flex';
                    }
                    
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
            
            // Content Character Counter
            const contentInput = document.getElementById('content');
            const wordCount = document.getElementById('wordCount');
            const charCount = document.getElementById('charCount');
            const readingTime = document.getElementById('readingTime');
            
            function updateContentStats() {
                const text = contentInput.value;
                const words = text.trim().split(/\s+/).filter(word => word.length > 0);
                const characters = text.length;
                
                wordCount.textContent = `${words.length} words`;
                charCount.textContent = `${characters} characters`;
                
                // Calculate reading time (200 words per minute)
                const minutes = Math.ceil(words.length / 200);
                readingTime.textContent = `~${minutes} min read`;
            }
            
            contentInput.addEventListener('input', updateContentStats);
            
            // Form Submission
            const form = document.getElementById('editPostForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            form.addEventListener('submit', function(e) {
                const content = contentInput.value.trim();
                if (content.length < 100) {
                    e.preventDefault();
                    alert('Content must be at least 100 characters long.');
                    contentInput.focus();
                    return;
                }
                
                const removeImage = document.getElementById('remove_image');
                const newImage = document.getElementById('image');
                
                if (removeImage && removeImage.checked && newImage.files.length > 0) {
                    if (!confirm('You selected to remove the current image AND upload a new one. The old image will be removed and replaced with the new one. Continue?')) {
                        e.preventDefault();
                        return;
                    }
                }
                
                loadingOverlay.style.display = 'flex';
            });
            
            // Auto-save (Optional)
            let autoSaveTimer;
            contentInput.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    console.log('Changes saved (not persisted)');
                }, 3000);
            });
        });
    </script>
</body>
</html>