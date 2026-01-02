<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Post - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .post-form-container {
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .textarea-counter {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
        
        .image-preview-container {
            margin-top: 1rem;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 300px;
            border-radius: 6px;
        }
        
        .image-placeholder {
            color: #adb5bd;
            font-size: 4rem;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
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
        
        .char-count {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .char-count.warning {
            color: #ff6b6b;
        }
        
        .word-count {
            font-size: 0.8rem;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 2px 8px;
            border-radius: 12px;
            display: inline-block;
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
        
        .editor-toolbar {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-bottom: none;
            border-radius: 8px 8px 0 0;
            padding: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        
        .editor-toolbar button {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 6px 12px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }
        
        .editor-toolbar button:hover {
            background-color: #f1f3f4;
            border-color: #667eea;
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
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        @media (max-width: 768px) {
            .post-form-container {
                margin: 1rem auto;
            }
            
            .form-card-header {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>
    
    <div class="post-form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h2 class="form-title">
                    <i class="fas fa-edit"></i>
                    Create New Post
                </h2>
            </div>
            
            <div class="card-body p-4">
                <form id="createPostForm" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
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
                               value="{{ old('title') }}"
                               placeholder="Enter a catchy title for your post..."
                               required
                               maxlength="200">
                        <div class="char-count mt-1" id="titleCharCount">0/200 characters</div>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Image Upload -->
                    <div class="form-section">
                        <label for="image" class="form-label">
                            <i class="fas fa-image me-1"></i>
                            Featured Image (Optional)
                        </label>
                        <input type="file" 
                               class="form-control @error('image') is-invalid @enderror" 
                               id="image" 
                               name="image" 
                               accept="image/*">
                        <div class="form-text">
                            Recommended size: 1200x600px. Max file size: 5MB. Supported formats: JPG, PNG, GIF, WebP
                        </div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div class="image-preview-container" id="imagePreview">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt image-placeholder"></i>
                                <p class="mt-2 mb-0 text-muted">Image preview will appear here</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="form-section">
                        <label for="content" class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Content *
                        </label>
                        
                        <!-- Editor Toolbar -->
                        <div class="editor-toolbar">
                            <button type="button" onclick="formatText('**', '**')" title="Bold">
                                <i class="fas fa-bold"></i>
                            </button>
                            <button type="button" onclick="formatText('*', '*')" title="Italic">
                                <i class="fas fa-italic"></i>
                            </button>
                            <button type="button" onclick="formatText('# ', '')" title="Heading 1">
                                H1
                            </button>
                            <button type="button" onclick="formatText('## ', '')" title="Heading 2">
                                H2
                            </button>
                            <button type="button" onclick="formatText('> ', '')" title="Blockquote">
                                <i class="fas fa-quote-right"></i>
                            </button>
                            <button type="button" onclick="formatText('- ', '')" title="Bullet List">
                                <i class="fas fa-list-ul"></i>
                            </button>
                            <button type="button" onclick="formatText('1. ', '')" title="Numbered List">
                                <i class="fas fa-list-ol"></i>
                            </button>
                            <button type="button" onclick="formatText('`', '`')" title="Inline Code">
                                <i class="fas fa-code"></i>
                            </button>
                            <button type="button" onclick="formatText('[', '](url)')" title="Link">
                                <i class="fas fa-link"></i>
                            </button>
                        </div>
                        
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" 
                                  name="content" 
                                  rows="12" 
                                  placeholder="Write your amazing content here... (Minimum 100 characters)"
                                  required>{{ old('content') }}</textarea>
                        
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div>
                                <span class="word-count" id="wordCount">0 words</span>
                                <span class="word-count ms-2" id="charCount">0 characters</span>
                            </div>
                            <div class="char-count" id="readingTime">~0 min read</div>
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
                            <option value="published" {{ old('status') == 'published' ? 'selected' : 'selected' }}>
                                Publish Now
                            </option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>
                                Save as Draft
                            </option>
                        </select>
                        <div class="form-text">
                            <span id="statusDescription">
                                Published posts will be visible to everyone immediately.
                            </span>
                        </div>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="form-section">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ url()->previous() }}" class="btn btn-cancel">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>
                                <a href="{{ route('posts.index') }}" class="btn btn-cancel ms-2">
                                    <i class="fas fa-list me-2"></i>
                                    View All Posts
                                </a>
                            </div>
                            
                            <div class="btn-group">
                                <button type="submit" name="action" value="draft" class="btn btn-cancel">
                                    <i class="fas fa-save me-2"></i>
                                    Save Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-submit ms-2">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Publish Post
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Image Preview
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            
            imageInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.innerHTML = `
                            <img src="${e.target.result}" class="image-preview" alt="Preview">
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeImage()">
                                    <i class="fas fa-trash me-1"></i> Remove Image
                                </button>
                            </div>
                        `;
                    }
                    
                    reader.readAsDataURL(file);
                }
            });
            
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
            updateContentStats(); // Initial call
            
            // Status Description
            const statusSelect = document.getElementById('status');
            const statusDescription = document.getElementById('statusDescription');
            
            statusSelect.addEventListener('change', function() {
                if (this.value === 'draft') {
                    statusDescription.textContent = 'Drafts are saved privately and can be published later.';
                } else {
                    statusDescription.textContent = 'Published posts will be visible to everyone immediately.';
                }
            });
            
            // Form Submission
            const form = document.getElementById('createPostForm');
            const loadingOverlay = document.getElementById('loadingOverlay');
            
            form.addEventListener('submit', function(e) {
                const content = contentInput.value.trim();
                if (content.length < 100) {
                    e.preventDefault();
                    alert('Content must be at least 100 characters long.');
                    contentInput.focus();
                    return;
                }
                
                loadingOverlay.style.display = 'flex';
            });
            
            // Auto-save Draft (Optional)
            let autoSaveTimer;
            let lastAutoSave = null;
            
            contentInput.addEventListener('input', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    const title = titleInput.value;
                    const content = contentInput.value;
                    
                    if (title || content) {
                        const draft = {
                            title: title,
                            content: content,
                            timestamp: new Date().toISOString()
                        };
                        localStorage.setItem('postDraft', JSON.stringify(draft));
                        lastAutoSave = new Date();
                        console.log('Draft auto-saved');
                    }
                }, 3000);
            });
            
            // Load Draft on Page Load
            const savedDraft = localStorage.getItem('postDraft');
            if (savedDraft) {
                const draft = JSON.parse(savedDraft);
                const loadDraft = confirm('You have a saved draft. Would you like to load it?');
                if (loadDraft) {
                    titleInput.value = draft.title || '';
                    contentInput.value = draft.content || '';
                    updateContentStats();
                }
            }
            
            // Clear draft on successful submission
            form.addEventListener('submit', function() {
                localStorage.removeItem('postDraft');
            });
        });
        
        // Format Text Function
        function formatText(before, after) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);
            const newText = before + selectedText + after;
            
            textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(end);
            textarea.selectionStart = textarea.selectionEnd = start + newText.length;
            textarea.focus();
            
            // Trigger input event to update counts
            textarea.dispatchEvent(new Event('input'));
        }
        
        // Remove Image Function
        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('imagePreview').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-cloud-upload-alt image-placeholder"></i>
                    <p class="mt-2 mb-0 text-muted">Image preview will appear here</p>
                </div>
            `;
        }
        
        // Handle page unload
        window.addEventListener('beforeunload', function(e) {
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            
            if ((title || content) && !document.getElementById('loadingOverlay').style.display === 'flex') {
                e.preventDefault();
                e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            }
        });
    </script>
</body>
</html>