@extends('layouts.app')

@section('title', 'Create New Post')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Create New Post
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Post Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" 
                                   placeholder="Enter post title" required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Image -->
                        <div class="mb-3">
                            <label for="image" class="form-label">Featured Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            <div class="form-text">
                                Recommended size: 1200x600px. Max file size: 2MB
                            </div>
                            @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                <img id="previewImage" src="#" alt="Preview" 
                                     class="img-fluid rounded" style="max-height: 200px;">
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="12" 
                                      placeholder="Write your post content here..." required>{{ old('content') }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                You can use Markdown formatting. Minimum 100 characters.
                            </div>
                        </div>
                        
                        <!-- Word Count -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-info" id="wordCount">0 words</span>
                                    <span class="badge bg-secondary ms-2" id="charCount">0 characters</span>
                                </div>
                                <div>
                                    <span class="text-muted" id="readingTime">~0 min read</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <div class="btn-group">
                                <button type="submit" name="action" value="draft" class="btn btn-outline-primary">
                                    <i class="fas fa-save me-2"></i>Save Draft
                                </button>
                                <button type="submit" name="action" value="publish" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Publish Post
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    #imagePreview img {
        border: 2px dashed #dee2e6;
        padding: 10px;
    }
    
    .editor-toolbar {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-bottom: none;
        padding: 10px;
        border-radius: 5px 5px 0 0;
    }
    
    .editor-toolbar button {
        background: none;
        border: 1px solid #dee2e6;
        border-radius: 3px;
        padding: 5px 10px;
        margin-right: 5px;
        cursor: pointer;
    }
    
    .editor-toolbar button:hover {
        background: #e9ecef;
    }
</style>
@endpush

@push('scripts')
<script>
    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    // Word count
    const contentTextarea = document.getElementById('content');
    const wordCount = document.getElementById('wordCount');
    const charCount = document.getElementById('charCount');
    const readingTime = document.getElementById('readingTime');
    
    function updateCounts() {
        const text = contentTextarea.value;
        const words = text.trim().split(/\s+/).filter(word => word.length > 0);
        const characters = text.length;
        
        wordCount.textContent = `${words.length} words`;
        charCount.textContent = `${characters} characters`;
        
        // Calculate reading time (200 words per minute)
        const minutes = Math.ceil(words.length / 200);
        readingTime.textContent = `~${minutes} min read`;
    }
    
    contentTextarea.addEventListener('input', updateCounts);
    
    // Initialize counts
    updateCounts();
    
    // Simple Markdown toolbar
    const toolbar = document.createElement('div');
    toolbar.className = 'editor-toolbar mb-2';
    toolbar.innerHTML = `
        <button type="button" onclick="formatText('**', '**')"><strong>B</strong></button>
        <button type="button" onclick="formatText('*', '*')"><em>I</em></button>
        <button type="button" onclick="formatText('# ', '')">H1</button>
        <button type="button" onclick="formatText('## ', '')">H2</button>
        <button type="button" onclick="formatText('```\\n', '\\n```')">Code</button>
        <button type="button" onclick="formatText('[', '](url)')">Link</button>
        <button type="button" onclick="formatText('![', '](image-url)')">Image</button>
    `;
    
    contentTextarea.parentNode.insertBefore(toolbar, contentTextarea);
    
    function formatText(before, after) {
        const textarea = contentTextarea;
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const selectedText = textarea.value.substring(start, end);
        const newText = before + selectedText + after;
        
        textarea.value = textarea.value.substring(0, start) + newText + textarea.value.substring(end);
        textarea.selectionStart = textarea.selectionEnd = start + newText.length;
        textarea.focus();
        updateCounts();
    }
    
    // Auto-save draft (optional)
    let autoSaveTimer;
    contentTextarea.addEventListener('input', function() {
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(function() {
            const draft = {
                title: document.getElementById('title').value,
                content: contentTextarea.value,
                timestamp: new Date().toISOString()
            };
            localStorage.setItem('postDraft', JSON.stringify(draft));
            console.log('Draft auto-saved');
        }, 3000);
    });
    
    // Load draft on page load
    window.addEventListener('load', function() {
        const draft = localStorage.getItem('postDraft');
        if (draft) {
            const draftData = JSON.parse(draft);
            if (confirm('You have a saved draft. Load it?')) {
                document.getElementById('title').value = draftData.title || '';
                contentTextarea.value = draftData.content || '';
                updateCounts();
            }
        }
    });
</script>
@endpush