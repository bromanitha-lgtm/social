<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $post->title }} - {{ config('app.name') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Prism.js for code highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --accent-color: #f093fb;
            --text-color: #333;
            --light-bg: #f8f9fa;
            --border-color: #e0e0e0;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        
        .post-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .post-header {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .post-image-container {
            position: relative;
            height: 400px;
            overflow: hidden;
        }
        
        .post-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .image-placeholder {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #667eea20, #764ba220);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .image-placeholder i {
            font-size: 5rem;
            color: #667eea;
            opacity: 0.3;
        }
        
        .post-content-container {
            padding: 2rem;
        }
        
        .post-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #222;
            line-height: 1.2;
        }
        
        .post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
        }
        
        .meta-item i {
            color: var(--primary-color);
        }
        
        .author-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .author-name {
            font-weight: 600;
            color: #333;
        }
        
        .post-stats {
            display: flex;
            gap: 30px;
            margin-bottom: 2rem;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 1rem;
        }
        
        .post-content {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 2rem;
        }
        
        .post-content h1, .post-content h2, .post-content h3, .post-content h4, .post-content h5, .post-content h6 {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #222;
        }
        
        .post-content h1 { font-size: 2rem; }
        .post-content h2 { font-size: 1.75rem; }
        .post-content h3 { font-size: 1.5rem; }
        .post-content h4 { font-size: 1.25rem; }
        .post-content h5 { font-size: 1.1rem; }
        .post-content h6 { font-size: 1rem; }
        
        .post-content p {
            margin-bottom: 1.5rem;
        }
        
        .post-content strong, .post-content b {
            font-weight: 700;
        }
        
        .post-content em, .post-content i {
            font-style: italic;
        }
        
        .post-content u {
            text-decoration: underline;
        }
        
        .post-content a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .post-content a:hover {
            text-decoration: underline;
        }
        
        .post-content ul, .post-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }
        
        .post-content li {
            margin-bottom: 0.5rem;
        }
        
        .post-content blockquote {
            border-left: 4px solid var(--primary-color);
            padding-left: 1.5rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #666;
        }
        
        .post-content pre {
            background: #f5f5f5;
            padding: 1rem;
            border-radius: 8px;
            overflow-x: auto;
            margin: 1.5rem 0;
            border-left: 4px solid var(--primary-color);
        }
        
        .post-content code {
            background: #f5f5f5;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        
        .post-content pre code {
            background: transparent;
            padding: 0;
            border-radius: 0;
        }
        
        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }
        
        .post-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }
        
        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .like-btn {
            background-color: white;
            border: 1px solid var(--border-color);
            color: #666;
        }
        
        .like-btn:hover {
            background-color: #fff5f5;
            border-color: #ff6b6b;
            color: #ff6b6b;
        }
        
        .like-btn.liked {
            background-color: #ff6b6b;
            border-color: #ff6b6b;
            color: white;
        }
        
        .share-btn {
            background-color: white;
            border: 1px solid var(--border-color);
            color: #666;
        }
        
        .share-btn:hover {
            background-color: #f8f9fa;
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        
        .save-btn {
            background-color: white;
            border: 1px solid var(--border-color);
            color: #666;
        }
        
        .save-btn:hover {
            background-color: #f8f9fa;
            border-color: #20c997;
            color: #20c997;
        }
        
        .edit-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .edit-btn:hover {
            background-color: #5a67d8;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .delete-btn {
            background-color: #ff6b6b;
            color: white;
            border: none;
        }
        
        .delete-btn:hover {
            background-color: #ff5252;
            color: white;
        }
        
        .follow-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }
        
        .follow-btn:hover {
            background-color: #5a67d8;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .follow-btn.following {
            background-color: #6c757d;
            color: white;
            border: 1px solid #6c757d;
        }
        
        .follow-btn.following:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        
        .comments-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            margin-bottom: 30px;
        }
        
        .comments-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .comments-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        
        .comment-form {
            margin-bottom: 2rem;
        }
        
        .comment-textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            resize: vertical;
            font-family: inherit;
            margin-bottom: 1rem;
        }
        
        .comment-textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .comment-submit-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .comment-submit-btn:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .comments-list {
            margin-top: 2rem;
        }
        
        .comment-item {
            padding: 1.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .comment-item:last-child {
            border-bottom: none;
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }
        
        .comment-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .comment-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .comment-author-name {
            font-weight: 600;
        }
        
        .comment-date {
            color: #999;
            font-size: 0.9rem;
        }
        
        .comment-content {
            color: #444;
            line-height: 1.6;
        }
        
        .sidebar {
            margin-top: 30px;
        }
        
        .sidebar-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            margin-bottom: 20px;
        }
        
        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .author-card {
            text-align: center;
        }
        
        .author-avatar-lg {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }
        
        .author-stats {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 1.5rem 0;
        }
        
        .author-stat {
            text-align: center;
        }
        
        .author-stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .author-stat-label {
            font-size: 0.9rem;
            color: #666;
        }
        
        .related-post {
            padding: 1rem 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .related-post:last-child {
            border-bottom: none;
        }
        
        .related-post-title {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .related-post-title a {
            color: #333;
            text-decoration: none;
        }
        
        .related-post-title a:hover {
            color: var(--primary-color);
        }
        
        .related-post-meta {
            font-size: 0.85rem;
            color: #999;
        }
        
        .empty-comments {
            text-align: center;
            padding: 3rem 1rem;
            color: #999;
        }
        
        .empty-comments i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .login-prompt {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .login-link {
            color: var(--primary-color);
            font-weight: 500;
            text-decoration: none;
        }
        
        .login-link:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .post-container {
                padding: 15px;
            }
            
            .post-title {
                font-size: 1.8rem;
            }
            
            .post-image-container,
            .image-placeholder {
                height: 250px;
            }
            
            .post-content-container {
                padding: 1.5rem;
            }
            
            .post-meta {
                flex-direction: column;
                gap: 10px;
            }
            
            .post-stats {
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .post-actions {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .comments-section {
                padding: 1.5rem;
            }
        }
        
        .reading-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            width: 0%;
            z-index: 9999;
            transition: width 0.1s;
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: white;
            color: #333;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 9999;
            transform: translateX(120%);
            transition: transform 0.3s ease;
        }
        
        .toast-notification.show {
            transform: translateX(0);
        }
        
        .toast-content i {
            color: var(--primary-color);
        }
        
        .toast-content i.fa-check-circle {
            color: #28a745;
        }
        
        .toast-content i.fa-exclamation-circle {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Reading Progress Bar -->
    <div class="reading-progress" id="readingProgress"></div>
    
    <div class="post-container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Post Header -->
                <div class="post-header">
                    @if($post->image)
                    <div class="post-image-container">
                        <img src="{{ asset('storage/' . $post->image) }}" class="post-image" alt="{{ $post->title }}">
                    </div>
                    @else
                    <div class="image-placeholder">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    @endif
                    
                    <div class="post-content-container">
                        <!-- Post Meta -->
                        <div class="post-meta">
                            <div class="author-info">
                                <div class="author-avatar">
                                    {{ strtoupper(substr($post->user->fName, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="author-name">
                                        <a href="{{ route('profile.show', $post->user) }}" style="color: #333; text-decoration: none;">
                                            {{ $post->user->fName }} {{ $post->user->lName }}
                                        </a>
                                    </div>
                                    <div class="text-muted">{{ $post->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            
                            <div class="meta-item">
                                <i class="fas fa-clock"></i>
                                <span>{{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                            </div>
                        </div>
                        
                        <!-- Post Title -->
                        <h1 class="post-title">{{ $post->title }}</h1>
                        
                        <!-- Post Stats -->
                        <div class="post-stats">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>{{ $post->views }} views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-heart"></i>
                                <span id="likesCount">{{ $post->likes_count }} likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-comment"></i>
                                <span id="commentsCount">{{ $post->comments_count }} comments</span>
                            </div>
                        </div>
                        
                        <!-- Post Content with HTML formatting -->
                        <div class="post-content">
                            @php
                                use Illuminate\Support\Str;
                                
                                // Check if HTML Purifier is installed
                                if (function_exists('clean')) {
                                    echo clean($post->content);
                                } else {
                                    // Safe HTML tags for formatting
                                    $allowedTags = '<h1><h2><h3><h4><h5><h6><p><br><strong><b><em><i><u><a><ul><ol><li><blockquote><code><pre><span><div>';
                                    
                                    // First, strip all tags except allowed ones
                                    $content = strip_tags($post->content, $allowedTags);
                                    
                                    // Remove dangerous attributes from allowed tags
                                    $content = preg_replace_callback('/<(\w+)([^>]*)>/', function($matches) {
                                        $tag = $matches[1];
                                        $attributes = $matches[2];
                                        
                                        // List of safe attributes for each tag
                                        $safeAttributes = [
                                            'a' => ['href', 'title', 'target'],
                                            'img' => ['src', 'alt', 'width', 'height'],
                                            'span' => ['style', 'class'],
                                            'div' => ['style', 'class'],
                                        ];
                                        
                                        // Clean attributes
                                        if (isset($safeAttributes[$tag])) {
                                            $allowedAttrs = $safeAttributes[$tag];
                                            $cleanAttrs = '';
                                            
                                            // Extract attributes
                                            preg_match_all('/(\w+)\s*=\s*"([^"]*)"|\'([^\']*)\'/', $attributes, $attrMatches);
                                            
                                            for ($i = 0; $i < count($attrMatches[0]); $i++) {
                                                $attrName = $attrMatches[1][$i];
                                                $attrValue = !empty($attrMatches[2][$i]) ? $attrMatches[2][$i] : $attrMatches[3][$i];
                                                
                                                if (in_array($attrName, $allowedAttrs)) {
                                                    // Remove dangerous content from attributes
                                                    $attrValue = preg_replace('/javascript:/i', '', $attrValue);
                                                    $cleanAttrs .= ' ' . $attrName . '="' . htmlspecialchars($attrValue) . '"';
                                                }
                                            }
                                            
                                            return '<' . $tag . $cleanAttrs . '>';
                                        }
                                        
                                        return '<' . $tag . '>';
                                    }, $content);
                                    
                                    echo $content;
                                }
                            @endphp
                        </div>
                        
                        <!-- Post Actions -->
                        <div class="post-actions">
                            @auth
                            <button class="action-btn like-btn {{ $isLiked ? 'liked' : '' }}" 
                                    id="likeButton"
                                    data-post-id="{{ $post->id }}">
                                <i class="{{ $isLiked ? 'fas' : 'far' }} fa-heart"></i>
                                <span>Like</span>
                            </button>
                            @else
                            <a href="{{ route('login') }}" class="action-btn like-btn">
                                <i class="far fa-heart"></i>
                                <span>Like</span>
                            </a>
                            @endauth
                            
                            <button class="action-btn share-btn" onclick="sharePost()">
                                <i class="fas fa-share-alt"></i>
                                <span>Share</span>
                            </button>
                            
                            @auth
                            <button class="action-btn save-btn" id="saveButton">
                                <i class="far fa-bookmark"></i>
                                <span>Save</span>
                            </button>
                            @endauth
                            
                            @auth
                                @if(Auth::id() == $post->user_id)
                                <a href="{{ route('posts.edit', $post) }}" class="action-btn edit-btn">
                                    <i class="fas fa-edit"></i>
                                    <span>Edit</span>
                                </a>
                                
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure you want to delete this post?')">
                                        <i class="fas fa-trash"></i>
                                        <span>Delete</span>
                                    </button>
                                </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
                
                <!-- Comments Section -->
                <div class="comments-section">
                    <div class="comments-header">
                        <h3 class="comments-title">
                            <i class="fas fa-comments me-2"></i>
                            Comments (<span id="commentsCountDisplay">{{ $post->comments_count }}</span>)
                        </h3>
                    </div>
                    
                    <!-- Comment Form -->
                    @auth
                    <form action="{{ route('posts.comment', $post) }}" method="POST" class="comment-form" id="commentForm">
                        @csrf
                        <textarea name="content" class="comment-textarea" rows="3" placeholder="Write a comment..." required></textarea>
                        <button type="submit" class="comment-submit-btn">
                            <i class="fas fa-paper-plane me-2"></i>
                            Post Comment
                        </button>
                    </form>
                    @else
                    <div class="login-prompt">
                        <p class="mb-0">
                            <a href="{{ route('login') }}" class="login-link">Login</a> to post a comment.
                        </p>
                    </div>
                    @endauth
                    
                    <!-- Comments List -->
                    <div class="comments-list" id="commentsList">
                        @forelse($post->comments as $comment)
                        <div class="comment-item">
                            <div class="comment-header">
                                <div class="comment-author">
                                    <div class="comment-avatar">
                                        {{ strtoupper(substr($comment->user->fName, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="comment-author-name">
                                            <a href="{{ route('profile.show', $comment->user) }}" style="color: #333; text-decoration: none;">
                                                {{ $comment->user->fName }} {{ $comment->user->lName }}
                                            </a>
                                        </div>
                                        <div class="comment-date">{{ $comment->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="comment-content">
                                {{ $comment->content }}
                            </div>
                        </div>
                        @empty
                        <div class="empty-comments">
                            <i class="fas fa-comment-slash"></i>
                            <p>No comments yet. Be the first to comment!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Author Info -->
                    <div class="sidebar-card author-card">
                        <div class="author-avatar-lg">
                            {{ strtoupper(substr($post->user->fName, 0, 1)) }}
                        </div>
                        <h4>{{ $post->user->fName }} {{ $post->user->lName }}</h4>
                        <p class="text-muted">Author</p>
                        
                        <div class="author-stats">
                            <div class="author-stat">
                                <div class="author-stat-number">{{ $post->user->posts()->count() }}</div>
                                <div class="author-stat-label">Posts</div>
                            </div>
                            <div class="author-stat">
                                <div class="author-stat-number">{{ $post->user->followers_count ?? 0 }}</div>
                                <div class="author-stat-label">Followers</div>
                            </div>
                        </div>
                        
                        @auth
                            @if(Auth::id() != $post->user_id)
                                <button class="action-btn follow-btn {{ Auth::user()->isFollowing($post->user) ? 'following' : '' }}" 
                                        id="followAuthorButton"
                                        data-user-id="{{ $post->user->id }}"
                                        style="width: 100%; margin-top: 15px;">
                                    <i class="fas {{ Auth::user()->isFollowing($post->user) ? 'fa-user-check' : 'fa-user-plus' }} me-2"></i>
                                    {{ Auth::user()->isFollowing($post->user) ? 'Following' : 'Follow' }}
                                </button>
                            @else
                                <a href="{{ route('home') }}" class="action-btn edit-btn" style="width: 100%; margin-top: 15px;">
                                    <i class="fas fa-user me-2"></i>
                                    My Profile
                                </a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="action-btn follow-btn" style="width: 100%; margin-top: 15px;">
                                <i class="fas fa-user-plus me-2"></i>
                                Follow
                            </a>
                        @endauth
                    </div>
                    
                    <!-- Related Posts -->
                    @if($relatedPosts->count() > 0)
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">
                            <i class="fas fa-newspaper me-2"></i>
                            More from {{ $post->user->fName }}
                        </h4>
                        @foreach($relatedPosts as $related)
                        <div class="related-post">
                            <h5 class="related-post-title">
                                <a href="{{ route('posts.show', $related) }}">{{ Str::limit($related->title, 60) }}</a>
                            </h5>
                            <div class="related-post-meta">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $related->created_at->format('M d, Y') }} · 
                                <i class="fas fa-eye me-1 ms-2"></i> {{ $related->views }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    <!-- Post Stats Card -->
                    <div class="sidebar-card">
                        <h4 class="sidebar-title">
                            <i class="fas fa-chart-bar me-2"></i>
                            Post Stats
                        </h4>
                        <div class="post-stats" style="flex-direction: column; gap: 15px;">
                            <div class="stat-item">
                                <i class="fas fa-eye"></i>
                                <span>{{ $post->views }} total views</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-heart"></i>
                                <span>{{ $post->likes_count }} likes</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-comment"></i>
                                <span>{{ $post->comments_count }} comments</span>
                            </div>
                            <div class="stat-item">
                                <i class="fas fa-calendar"></i>
                                <span>Published {{ $post->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Prism.js for code highlighting -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/prism.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reading Progress Bar
            const readingProgress = document.getElementById('readingProgress');
            
            window.addEventListener('scroll', function() {
                const winHeight = window.innerHeight;
                const docHeight = document.documentElement.scrollHeight;
                const scrolled = window.scrollY;
                const progress = (scrolled / (docHeight - winHeight)) * 100;
                readingProgress.style.width = Math.min(progress, 100) + '%';
            });
            
            // Initialize Prism for code highlighting
            if (typeof Prism !== 'undefined') {
                Prism.highlightAll();
            }
            
            // Like Functionality
            const likeButton = document.getElementById('likeButton');
            if (likeButton) {
                likeButton.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    const isLiked = this.classList.contains('liked');
                    const likesCount = document.getElementById('likesCount');
                    
                    fetch(isLiked ? `/posts/${postId}/unlike` : `/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI
                            if (isLiked) {
                                this.classList.remove('liked');
                                this.querySelector('i').className = 'far fa-heart';
                            } else {
                                this.classList.add('liked');
                                this.querySelector('i').className = 'fas fa-heart';
                            }
                            
                            // Update likes count
                            if (likesCount) {
                                likesCount.textContent = data.likes_count + ' likes';
                            }
                            
                            showToast(data.message || (isLiked ? 'Post unliked!' : 'Post liked!'), 'success');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred. Please try again.', 'error');
                    });
                });
            }
            
            // Follow Author Functionality
            const followAuthorButton = document.getElementById('followAuthorButton');
            if (followAuthorButton) {
                followAuthorButton.addEventListener('click', function() {
                    const userId = this.getAttribute('data-user-id');
                    const isFollowing = this.classList.contains('following');
                    
                    fetch(`/users/${userId}/toggle-follow`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update UI
                            if (data.action === 'followed') {
                                this.classList.add('following');
                                this.innerHTML = '<i class="fas fa-user-check me-2"></i> Following';
                            } else {
                                this.classList.remove('following');
                                this.innerHTML = '<i class="fas fa-user-plus me-2"></i> Follow';
                            }
                            
                            // Show toast notification
                            showToast(data.message, 'success');
                            
                            // Update author's followers count in the sidebar
                            const authorFollowersElement = document.querySelector('.author-stat-number:nth-child(2)');
                            if (authorFollowersElement && data.followers_count !== undefined) {
                                authorFollowersElement.textContent = data.followers_count;
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('An error occurred. Please try again.', 'error');
                    });
                });
            }
            
            // Comment Form Submission (AJAX)
            const commentForm = document.getElementById('commentForm');
            if (commentForm) {
                commentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const postId = '{{ $post->id }}';
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    
                    // Show loading state
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Posting...';
                    submitBtn.disabled = true;
                    
                    fetch(`/posts/${postId}/comment`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Reset button
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.disabled = false;
                        
                        if (data.success) {
                            // Clear textarea
                            this.querySelector('textarea').value = '';
                            
                            // Add new comment to list
                            const commentsList = document.getElementById('commentsList');
                            const emptyComments = commentsList.querySelector('.empty-comments');
                            
                            if (emptyComments) {
                                emptyComments.remove();
                            }
                            
                            const now = new Date();
                            const formattedDate = now.toLocaleDateString('en-US', { 
                                month: 'short', 
                                day: 'numeric',
                                year: 'numeric'
                            }) + ' (' + 'Just now' + ')';
                            
                            const newComment = `
                                <div class="comment-item">
                                    <div class="comment-header">
                                        <div class="comment-author">
                                            <div class="comment-avatar">
                                                {{ strtoupper(substr(auth()->user()->fName, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="comment-author-name">
                                                    <a href="{{ route('profile.show', auth()->user()) }}" style="color: #333; text-decoration: none;">
                                                        {{ auth()->user()->fName }} {{ auth()->user()->lName }}
                                                    </a>
                                                </div>
                                                <div class="comment-date">Just now</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        ${data.comment.content}
                                    </div>
                                </div>
                            `;
                            
                            commentsList.insertAdjacentHTML('afterbegin', newComment);
                            
                            // Update comments count
                            const commentsCount = document.getElementById('commentsCount');
                            const commentsCountDisplay = document.getElementById('commentsCountDisplay');
                            if (commentsCount) {
                                commentsCount.textContent = data.comments_count + ' comments';
                            }
                            if (commentsCountDisplay) {
                                commentsCountDisplay.textContent = data.comments_count;
                            }
                            
                            // Show success message
                            showToast('Comment added successfully!', 'success');
                        } else {
                            showToast(data.message || 'Failed to add comment', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.disabled = false;
                        showToast('An error occurred. Please try again.', 'error');
                    });
                });
            }
            
            // Save for later functionality
            const saveButton = document.getElementById('saveButton');
            if (saveButton) {
                saveButton.addEventListener('click', function() {
                    const postId = '{{ $post->id }}';
                    let savedPosts = JSON.parse(localStorage.getItem('savedPosts') || '[]');
                    
                    if (savedPosts.includes(postId)) {
                        savedPosts = savedPosts.filter(id => id !== postId);
                        this.innerHTML = '<i class="far fa-bookmark"></i><span>Save</span>';
                        showToast('Post removed from saved list', 'success');
                    } else {
                        savedPosts.push(postId);
                        this.innerHTML = '<i class="fas fa-bookmark"></i><span>Saved</span>';
                        showToast('Post saved for later!', 'success');
                    }
                    
                    localStorage.setItem('savedPosts', JSON.stringify(savedPosts));
                });
                
                // Check if already saved
                let savedPosts = JSON.parse(localStorage.getItem('savedPosts') || '[]');
                if (savedPosts.includes('{{ $post->id }}')) {
                    saveButton.innerHTML = '<i class="fas fa-bookmark"></i><span>Saved</span>';
                }
            }
        });
        
        // Share Functionality
        window.sharePost = function() {
            const title = '{{ $post->title }}';
            const url = window.location.href;
            const text = 'Check out this post: ' + title;
            
            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: 'Check out this post!',
                    url: url
                })
                .then(() => {
                    showToast('Shared successfully!', 'success');
                })
                .catch(error => {
                    console.log('Error sharing:', error);
                    copyLink();
                });
            } else {
                copyLink();
            }
        };
        
        // Copy link function
        function copyLink() {
            const url = window.location.href;
            navigator.clipboard.writeText(url)
                .then(() => {
                    showToast('Link copied to clipboard!', 'success');
                })
                .catch(err => {
                    console.error('Failed to copy:', err);
                    
                    // Fallback method
                    const textArea = document.createElement('textarea');
                    textArea.value = url;
                    document.body.appendChild(textArea);
                    textArea.select();
                    document.execCommand('copy');
                    document.body.removeChild(textArea);
                    
                    showToast('Link copied to clipboard!', 'success');
                });
        }
        
        // Toast notification function
        window.showToast = function(message, type = 'normal') {
            const toast = document.createElement('div');
            toast.className = 'toast-notification';
            
            let icon = 'fa-info-circle';
            if (type === 'success') icon = 'fa-check-circle';
            if (type === 'error') icon = 'fa-exclamation-circle';
            
            toast.innerHTML = `
                <div class="toast-content">
                    <i class="fas ${icon}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        };
    </script>
</body>
</html>