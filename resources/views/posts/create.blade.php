<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create New Post - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Trix Editor -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #f72585;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            padding-top: 0;
        }
        
        .create-post-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .create-post-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .create-post-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-color);
            margin: 0;
        }
        
        .back-button {
            display: flex;
            align-items: center;
            gap: 8px;
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .back-button:hover {
            background-color: rgba(0,0,0,0.05);
            color: var(--primary-color);
        }
        
        .create-post-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .post-form {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-color);
            font-size: 1rem;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s;
            background-color: white;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        .form-control::placeholder {
            color: #999;
        }
        
        .title-input {
            font-size: 1.3rem;
            font-weight: 500;
            padding: 15px;
        }
        
        /* Trix Editor Customization */
        .trix-container {
            margin-top: 1rem;
        }
        
        trix-editor {
            min-height: 300px;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: white;
            font-size: 1rem;
            line-height: 1.6;
        }
        
        trix-toolbar {
            border: 1px solid var(--border-color);
            border-radius: 8px 8px 0 0;
            background: #f8f9fa;
            padding: 8px;
        }
        
        trix-editor:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        }
        
        /* Image Upload */
        .image-upload-container {
            border: 2px dashed var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        
        .image-upload-container:hover {
            border-color: var(--primary-color);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .image-upload-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .image-upload-text {
            color: var(--text-color);
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .image-upload-subtext {
            color: #666;
            font-size: 0.9rem;
        }
        
        .image-preview-container {
            display: none;
            margin-top: 1rem;
            text-align: center;
        }
        
        .image-preview {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .remove-image-btn {
            margin-top: 1rem;
            padding: 8px 16px;
            background: #ff6b6b;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s;
        }
        
        .remove-image-btn:hover {
            background: #ff5252;
        }
        
        /* Status Options */
        .status-options {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .status-option {
            flex: 1;
            min-width: 150px;
        }
        
        .status-radio {
            display: none;
        }
        
        .status-label {
            display: block;
            padding: 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: white;
        }
        
        .status-label:hover {
            border-color: var(--primary-color);
            background: rgba(67, 97, 238, 0.05);
        }
        
        .status-radio:checked + .status-label {
            border-color: var(--primary-color);
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .status-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }
        
        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }
        
        .btn-outline {
            background: white;
            color: var(--text-color);
            border: 1px solid var(--border-color);
        }
        
        .btn-outline:hover {
            background: #f8f9fa;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn-danger {
            background: #ff6b6b;
            color: white;
        }
        
        .btn-danger:hover {
            background: #ff5252;
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            color: var(--text-color);
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 9999;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .toast.show {
            transform: translateX(0);
        }
        
        .toast.success {
            border-left: 4px solid #28a745;
        }
        
        .toast.error {
            border-left: 4px solid #dc3545;
        }
        
        /* Character Counter */
        .char-counter {
            text-align: right;
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.5rem;
        }
        
        .char-counter.warning {
            color: #ff6b6b;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .create-post-container {
                margin: 1rem auto;
                padding: 0 0.5rem;
            }
            
            .create-post-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .create-post-header h1 {
                font-size: 1.5rem;
            }
            
            .post-form {
                padding: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 1rem;
            }
            
            .form-actions .btn {
                width: 100%;
                justify-content: center;
            }
            
            .status-options {
                flex-direction: column;
            }
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
        }
        
        .loading-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        
        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid var(--border-color);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="create-post-container">
        <div class="create-post-header">
            <h1><i class="fas fa-plus-circle me-2" style="color: var(--primary-color);"></i> Create New Post</h1>
            <a href="{{ url()->previous() }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
        
        <div class="create-post-card">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="post-form" id="postForm">
                @csrf
                
                <div class="form-group">
                    <label for="title" class="form-label">Post Title</label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           class="form-control title-input" 
                           placeholder="Enter your post title..." 
                           required
                           maxlength="255">
                    <div class="char-counter">
                        <span id="titleCharCount">0</span>/255 characters
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="content" class="form-label">Content</label>
                    <input type="hidden" name="content" id="content">
                    <div class="trix-container">
                        <trix-editor input="content" placeholder="Write your post content here..."></trix-editor>
                    </div>
                    <div class="char-counter">
                        <span id="contentCharCount">0</span> characters
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Featured Image (Optional)</label>
                    <div class="image-upload-container" onclick="document.getElementById('image').click()">
                        <div class="image-upload-icon">
                            <i class="fas fa-image"></i>
                        </div>
                        <div class="image-upload-text">Click to upload an image</div>
                        <div class="image-upload-subtext">Recommended size: 1200x630px • Max: 5MB</div>
                        <input type="file" 
                               name="image" 
                               id="image" 
                               accept="image/*" 
                               style="display: none;" 
                               onchange="previewImage(event)">
                    </div>
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <img src="" alt="Preview" class="image-preview" id="imagePreview">
                        <button type="button" class="remove-image-btn" onclick="removeImage()">
                            <i class="fas fa-trash"></i> Remove Image
                        </button>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Post Status</label>
                    <div class="status-options">
                        <div class="status-option">
                            <input type="radio" 
                                   name="status" 
                                   id="status_published" 
                                   value="published" 
                                   class="status-radio" 
                                   checked>
                            <label for="status_published" class="status-label">
                                <span class="status-icon"><i class="fas fa-globe"></i></span>
                                <span>Publish</span>
                            </label>
                        </div>
                        <div class="status-option">
                            <input type="radio" 
                                   name="status" 
                                   id="status_draft" 
                                   value="draft" 
                                   class="status-radio">
                            <label for="status_draft" class="status-label">
                                <span class="status-icon"><i class="fas fa-save"></i></span>
                                <span>Save as Draft</span>
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="window.location.href='{{ route('posts.index') }}'">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <div>
                        <button type="button" class="btn btn-outline" onclick="saveAsDraft()">
                            <i class="fas fa-save"></i> Save Draft
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-paper-plane"></i> Publish Post
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Post Tips -->
        <div class="create-post-card">
            <div class="post-form">
                <h3><i class="fas fa-lightbulb me-2" style="color: #ffc107;"></i> Writing Tips</h3>
                <ul style="margin-top: 1rem; padding-left: 1.5rem; color: #666;">
                    <li style="margin-bottom: 0.5rem;">Keep your title clear and attention-grabbing</li>
                    <li style="margin-bottom: 0.5rem;">Use headings and formatting to organize your content</li>
                    <li style="margin-bottom: 0.5rem;">Add relevant images to make your post more engaging</li>
                    <li style="margin-bottom: 0.5rem;">Proofread before publishing</li>
                    <li>Use tags to help others discover your post</li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <p id="loadingText">Publishing post...</p>
    </div>
    
    <!-- Trix Editor JS -->
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Post creation page loaded');
            
            // Initialize character counters
            initializeCharCounters();
            
            // Form submission handling
            initializeFormSubmission();
            
            // Set up Trix editor
            initializeTrixEditor();
        });
        
        function initializeCharCounters() {
            const titleInput = document.getElementById('title');
            const titleCounter = document.getElementById('titleCharCount');
            const editor = document.querySelector('trix-editor');
            const contentCounter = document.getElementById('contentCharCount');
            
            if (titleInput && titleCounter) {
                titleInput.addEventListener('input', function() {
                    const count = this.value.length;
                    titleCounter.textContent = count;
                    
                    if (count > 200) {
                        titleCounter.classList.add('warning');
                    } else {
                        titleCounter.classList.remove('warning');
                    }
                });
                
                // Initial count
                titleCounter.textContent = titleInput.value.length;
            }
            
            if (editor && contentCounter) {
                editor.addEventListener('trix-change', function() {
                    const text = editor.editor.getDocument().toString();
                    contentCounter.textContent = text.length;
                    
                    if (text.length > 5000) {
                        contentCounter.classList.add('warning');
                    } else {
                        contentCounter.classList.remove('warning');
                    }
                });
            }
        }
        
        function initializeFormSubmission() {
            const form = document.getElementById('postForm');
            if (!form) return;
            
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                if (!validateForm()) {
                    showToast('Please fill in all required fields', 'error');
                    return;
                }
                
                // Show loading
                showLoading('Publishing post...');
                
                // Submit form via AJAX
                const formData = new FormData(form);
                
                fetch('{{ route("posts.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    hideLoading();
                    
                    if (data.success) {
                        showToast(data.message || 'Post published successfully!', 'success');
                        
                        // Redirect to the new post
                        setTimeout(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                window.location.href = '/posts';
                            }
                        }, 1500);
                    } else {
                        showToast(data.message || 'Failed to publish post', 'error');
                        
                        // Show validation errors if any
                        if (data.errors) {
                            displayFormErrors(data.errors);
                        }
                    }
                })
                .catch(error => {
                    hideLoading();
                    console.error('Error:', error);
                    showToast('An error occurred. Please try again.', 'error');
                });
            });
        }
        
        function initializeTrixEditor() {
            // Configure Trix editor
            document.addEventListener('trix-file-accept', function(e) {
                e.preventDefault(); // Prevent file uploads in editor
            });
            
            // Add custom styles to Trix editor
            const trixEditor = document.querySelector('trix-editor');
            if (trixEditor) {
                trixEditor.style.minHeight = '300px';
                trixEditor.style.fontSize = '1rem';
                trixEditor.style.lineHeight = '1.6';
            }
        }
        
        function validateForm() {
            const title = document.getElementById('title').value.trim();
            const content = document.querySelector('trix-editor').editor.getDocument().toString().trim();
            
            if (!title || title.length < 3) {
                showToast('Title must be at least 3 characters', 'error');
                document.getElementById('title').focus();
                return false;
            }
            
            if (!content || content.length < 10) {
                showToast('Content must be at least 10 characters', 'error');
                document.querySelector('trix-editor').focus();
                return false;
            }
            
            return true;
        }
        
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const previewContainer = document.getElementById('imagePreviewContainer');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('Image size must be less than 5MB', 'error');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    showToast('Please select an image file', 'error');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
                
                showToast('Image selected successfully', 'success');
            }
        }
        
        function removeImage() {
            const input = document.getElementById('image');
            const previewContainer = document.getElementById('imagePreviewContainer');
            
            input.value = '';
            previewContainer.style.display = 'none';
            
            showToast('Image removed', 'normal');
        }
        
        function saveAsDraft() {
            // Set status to draft
            document.getElementById('status_draft').checked = true;
            
            // Update submit button text
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Draft';
            
            // Submit form
            document.getElementById('postForm').submit();
            
            // Restore button text (in case submission fails)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                document.getElementById('status_published').checked = true;
            }, 2000);
        }
        
        function showLoading(text = 'Processing...') {
            const overlay = document.getElementById('loadingOverlay');
            const loadingText = document.getElementById('loadingText');
            
            if (overlay && loadingText) {
                loadingText.textContent = text;
                overlay.classList.add('show');
            }
        }
        
        function hideLoading() {
            const overlay = document.getElementById('loadingOverlay');
            if (overlay) {
                overlay.classList.remove('show');
            }
        }
        
        function showToast(message, type = 'normal') {
            // Remove existing toasts
            document.querySelectorAll('.toast').forEach(toast => toast.remove());
            
            // Create new toast
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let icon = 'fa-info-circle';
            if (type === 'success') icon = 'fa-check-circle';
            if (type === 'error') icon = 'fa-exclamation-circle';
            
            toast.innerHTML = `
                <i class="fas ${icon}"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(toast);
            
            // Show toast
            setTimeout(() => toast.classList.add('show'), 10);
            
            // Auto-remove after 4 seconds
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
        
        function displayFormErrors(errors) {
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            
            // Display new errors
            for (const [field, messages] of Object.entries(errors)) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = messages[0];
                    
                    input.parentNode.appendChild(errorDiv);
                }
            }
        }
        
        // Auto-save draft functionality
        let autoSaveTimeout;
        const AUTO_SAVE_INTERVAL = 30000; // 30 seconds
        
        function startAutoSave() {
            const titleInput = document.getElementById('title');
            const editor = document.querySelector('trix-editor');
            
            if (titleInput && editor) {
                titleInput.addEventListener('input', scheduleAutoSave);
                editor.addEventListener('trix-change', scheduleAutoSave);
            }
        }
        
        function scheduleAutoSave() {
            clearTimeout(autoSaveTimeout);
            autoSaveTimeout = setTimeout(autoSaveDraft, AUTO_SAVE_INTERVAL);
        }
        
        function autoSaveDraft() {
            const title = document.getElementById('title').value.trim();
            const content = document.querySelector('trix-editor').editor.getDocument().toString().trim();
            
            if (title || content) {
                // Save to localStorage as draft
                const draft = {
                    title: title,
                    content: content,
                    timestamp: new Date().toISOString()
                };
                
                localStorage.setItem('post_draft', JSON.stringify(draft));
                console.log('Draft auto-saved');
            }
        }
        
        function loadDraft() {
            const draft = localStorage.getItem('post_draft');
            if (draft) {
                try {
                    const data = JSON.parse(draft);
                    
                    if (confirm('You have a saved draft. Would you like to load it?')) {
                        document.getElementById('title').value = data.title || '';
                        document.querySelector('trix-editor').editor.loadHTML(data.content || '');
                        
                        // Update character counters
                        document.getElementById('titleCharCount').textContent = data.title?.length || 0;
                        const contentLength = data.content?.replace(/<[^>]*>/g, '').length || 0;
                        document.getElementById('contentCharCount').textContent = contentLength;
                        
                        showToast('Draft loaded successfully', 'success');
                    }
                } catch (e) {
                    console.error('Error loading draft:', e);
                }
            }
        }
        
        // Clear draft when form is successfully submitted
        document.getElementById('postForm').addEventListener('submit', function() {
            localStorage.removeItem('post_draft');
        });
        
        // Load draft on page load
        window.addEventListener('load', loadDraft);
        
        // Start auto-save
        startAutoSave();
        
        // Clear draft when leaving page
        window.addEventListener('beforeunload', function(e) {
            const title = document.getElementById('title').value.trim();
            const content = document.querySelector('trix-editor').editor.getDocument().toString().trim();
            
            if (title || content) {
                // Don't clear draft if there's content
                return;
            }
            
            // Clear draft if form is empty
            localStorage.removeItem('post_draft');
        });
    </script>
</body>
</html>