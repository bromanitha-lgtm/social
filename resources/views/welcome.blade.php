<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Sinhala:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="{{ route('home') }}" class="site-brand" aria-label="Article home">
            <span class="site-brand-icon"><i class="fas fa-book-open" aria-hidden="true"></i></span>
            <span>Article</span>
        </a>
        
        <div class="lang-toggle-container">
            <button class="lang-toggle-btn" id="langToggle">
                <div class="lang-flag">
                    <img src="https://flagcdn.com/w20/gb.png" alt="English" id="langFlag">
                </div>
                <span id="langText">English</span>
            </button>
        </div>
        
        <a href="{{ route('login') }}" class="login-btn btn-animate" aria-label="Sign in"><i class="fas fa-user"></i></a>
    </header>
    
    <main class="container">
        <!-- Hero Section -->
        <section class="hero animate-fade-in">
            <div class="hero-content">
                <h1 class="hero-title" id="heroTitle">Your Voice Matters. Share It With The World.</h1>
                <p class="hero-subtitle" id="heroSubtitle">A powerful platform where writers, thinkers, and creators come together to share knowledge and inspire change</p>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        @php
                            use App\Models\Post;
                            use App\Models\User;
                            // Get published posts count
                            $publishedPostsCount = Post::when(
                                \Schema::hasColumn('posts', 'status'), 
                                function($query) {
                                    $query->where('status', 'published');
                                }
                            )->where(function($query) {
                                $query->whereNull('published_at')
                                      ->orWhere('published_at', '<=', now());
                            })->count();
                            
                            // Get active writers count (users with at least one published post in last 30 days)
                            $activeWritersCount = User::whereHas('posts', function($query) {
                                $query->when(\Schema::hasColumn('posts', 'status'), function($q) {
                                    $q->where('status', 'published');
                                })
                                ->where(function($q) {
                                    $q->whereNull('published_at')
                                      ->orWhere('published_at', '<=', now());
                                })
                                ->where('created_at', '>=', now()->subDays(30));
                            })->count();
                            
                            // Count total views across published articles
                            $totalArticleViews = Post::when(
                                \Schema::hasColumn('posts', 'status'), 
                                function($query) {
                                    $query->where('status', 'published');
                                }
                            )->where(function($query) {
                                $query->whereNull('published_at')
                                      ->orWhere('published_at', '<=', now());
                            })->sum('views');
                        @endphp
                        
                        <span class="stat-number" id="statArticles">{{ number_format($publishedPostsCount) }}</span>
                        <span class="stat-label" id="statArticlesLabel">Articles Published</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="statWriters">{{ number_format($activeWritersCount) }}</span>
                        <span class="stat-label" id="statWritersLabel">Active Writers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="statViews">{{ number_format($totalArticleViews) }}</span>
                        <span class="stat-label" id="statViewsLabel">Article Views</span>
                    </div>
                </div>
                
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-animate">
                        <i class="fas fa-pen"></i> <span id="startWritingBtn">Start Writing Now</span>
                    </a>
                    <a href="#latest-articles" class="btn btn-outline btn-animate">
                        <i class="fas fa-book-open"></i> <span id="exploreArticlesBtn">Explore Articles</span>
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <div class="floating-element">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
        </section>

        <section id="latest-articles" class="latest-articles-section">
            <div class="latest-articles-heading">
                <div>
                    <h2 class="section-title" id="latestArticlesTitle">Latest Articles</h2>
                    <p class="section-subtitle" id="latestArticlesSubtitle">Discover ideas shared by our community.</p>
                </div>
            </div>

            @if($posts->isNotEmpty())
                <div class="latest-articles-grid">
                    @foreach($posts as $post)
                        <article class="latest-article-card">
                            @if($post->image_url)
                                <img src="{{ $post->image_url }}" alt="" class="latest-article-image">
                            @endif
                            <div class="latest-article-body">
                                <p class="latest-article-meta">
                                    <span>{{ $post->user->fName }} {{ $post->user->lName }}</span>
                                    <span aria-hidden="true">&middot;</span>
                                    <time datetime="{{ ($post->published_at ?? $post->created_at)->toIso8601String() }}">{{ ($post->published_at ?? $post->created_at)->diffForHumans() }}</time>
                                </p>
                                <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
                                <p class="latest-article-excerpt">{{ $post->excerpt(140) }}</p>
                                <div class="latest-article-footer">
                                    <span><i class="fas fa-eye" aria-hidden="true"></i> {{ number_format($post->views) }}</span>
                                    <span><i class="fas fa-heart" aria-hidden="true"></i> {{ number_format($post->likes_count) }}</span>
                                    <span><i class="fas fa-comment" aria-hidden="true"></i> {{ number_format($post->comments_count) }}</span>
                                                    <a href="{{ route('posts.show', $post) }}" class="latest-article-read-link"><span class="read-article-label">Read article</span> <i class="fas fa-arrow-right" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            @else
                <div class="latest-articles-empty">
                    <i class="fas fa-feather-alt" aria-hidden="true"></i>
                    <p id="latestArticlesEmpty">No articles have been published yet. Be the first to share an idea.</p>
                </div>
            @endif
        </section>

        <!-- Article Platform Features -->
        <section id="features" class="features-section">
            <h2 class="section-title" id="featuresTitle">Why Choose Our Platform?</h2>
            <p class="section-subtitle" style="text-align: center; color: var(--text-muted); margin-bottom: 3rem;" id="featuresSubtitle">
                Everything you need to share your knowledge and connect with readers
            </p>
            
            <div class="article-features-grid">
                <!-- Feature 1 -->
                <div class="article-feature-card animate-slide-up" style="animation-delay: 0.1s">
                    <div class="article-feature-icon">
                        <i class="fas fa-edit"></i>
                    </div>
                    <h3 class="article-feature-title" id="feature1Title">Rich Text Editor</h3>
                    <p class="article-feature-desc" id="feature1Desc">Format your writing with the built-in editor and add images to your posts.</p>
                </div>

                <!-- Feature 2 -->
                <div class="article-feature-card animate-slide-up" style="animation-delay: 0.2s">
                    <div class="article-feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="article-feature-title" id="feature2Title">Your Dashboard</h3>
                    <p class="article-feature-desc" id="feature2Desc">Keep track of your posts, views, likes, and followers from your dashboard.</p>
                </div>

                <!-- Feature 3 -->
                <div class="article-feature-card animate-slide-up" style="animation-delay: 0.3s">
                    <div class="article-feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="article-feature-title" id="feature3Title">Community Engagement</h3>
                    <p class="article-feature-desc" id="feature3Desc">Follow writers and connect with the community through likes and comments.</p>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section class="how-it-works">
            <h2 class="section-title" id="howItWorksTitle">How It Works</h2>
            <p class="section-subtitle" style="text-align: center; color: var(--text-muted); margin-bottom: 3rem;" id="howItWorksSubtitle">
                Start sharing your knowledge in just 3 simple steps
            </p>
            
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3 class="step-title" id="step1Title">Sign Up</h3>
                    <p class="step-desc" id="step1Desc">Create your free account in under a minute</p>
                </div>
                
                <div class="step">
                    <div class="step-number">2</div>
                    <h3 class="step-title" id="step2Title">Write & Format</h3>
                    <p class="step-desc" id="step2Desc">Write a post, format it, and add an image if you like</p>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <h3 class="step-title" id="step3Title">Publish & Share</h3>
                    <p class="step-desc" id="step3Desc">Publish your post and connect with readers</p>
                </div>
            </div>
        </section>

        <!-- Platform Benefits -->
        <section class="benefits-section">
            <h2 style="text-align: center; margin-bottom: 2rem; color: var(--text-main);" id="benefitsTitle">What You Can Do</h2>
            
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-compass"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit1Title">Discover Articles</h4>
                        <p id="benefit1Desc">Find the latest, popular, and trending posts from the community</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit2Title">Follow Writers</h4>
                        <p id="benefit2Desc">Follow writers whose posts you enjoy and keep up with their work</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit3Title">Share Feedback</h4>
                        <p id="benefit3Desc">Join conversations by liking and commenting on posts</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit4Title">Track Your Posts</h4>
                        <p id="benefit4Desc">See your post and engagement counts from your personal dashboard</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <div class="cta-card">
                <h2 class="cta-title" id="ctaTitle">Ready to Start Your Writing Journey?</h2>
                <p class="cta-text" id="ctaText">Join our community of writers today. It's completely free to get started.</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-secondary btn-animate" style="background: white; color: var(--primary-color);">
                        <i class="fas fa-user-plus"></i> <span id="joinNowBtn">Create Free Account</span>
                    </a>
                    <a href="#latest-articles" class="btn btn-outline" style="color: white; border-color: white;">
                        <i class="fas fa-book-open"></i> <span id="exploreArticlesBtn2">Explore Articles</span>
                    </a>
                </div>
            </div>
        </section>
    </main>
    
    <script>
        // Language Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const langToggle = document.getElementById('langToggle');
            const langFlag = document.getElementById('langFlag');
            const langText = document.getElementById('langText');
            
            // Language texts
            const translations = {
                en: {
                    heroTitle: "Your Voice Matters. Share It With The World.",
                    heroSubtitle: "Write and share articles, discover new ideas, and connect with writers through likes, comments, and follows.",
                    statArticlesLabel: "Articles Published",
                    statWritersLabel: "Active Writers",
                    statViewsLabel: "Article Views",
                    startWritingBtn: "Start Writing Now",
                    exploreArticlesBtn: "Explore Articles",
                    exploreArticlesBtn2: "Explore Articles",
                    latestArticlesTitle: "Latest Articles",
                    latestArticlesSubtitle: "Discover ideas shared by our community.",
                    latestArticlesEmpty: "No articles have been published yet. Be the first to share an idea.",
                    readArticleLabel: "Read article",
                    featuresTitle: "Why Choose Our Platform?",
                    featuresSubtitle: "Write, discover, and connect with a community of readers and writers.",
                    feature1Title: "Rich Text Editor",
                    feature1Desc: "Format your writing with the built-in editor and add images to your posts.",
                    feature2Title: "Your Dashboard",
                    feature2Desc: "Keep track of your posts, views, likes, and followers from your dashboard.",
                    feature3Title: "Community Engagement",
                    feature3Desc: "Follow writers and connect with the community through likes and comments.",
                    howItWorksTitle: "How It Works",
                    howItWorksSubtitle: "Start sharing your knowledge in just 3 simple steps",
                    step1Title: "Sign Up",
                    step1Desc: "Create your free account in under a minute",
                    step2Title: "Write & Format",
                    step2Desc: "Write a post, format it, and add an image if you like",
                    step3Title: "Publish & Share",
                    step3Desc: "Publish your post and connect with readers",
                    benefitsTitle: "What You Can Do",
                    benefit1Title: "Discover Articles",
                    benefit1Desc: "Find the latest, popular, and trending posts from the community",
                    benefit2Title: "Follow Writers",
                    benefit2Desc: "Follow writers whose posts you enjoy and keep up with their work",
                    benefit3Title: "Share Feedback",
                    benefit3Desc: "Join conversations by liking and commenting on posts",
                    benefit4Title: "Track Your Posts",
                    benefit4Desc: "See your post and engagement counts from your personal dashboard",
                    ctaTitle: "Ready to Start Your Writing Journey?",
                    ctaText: "Join our community of writers today. It's completely free to get started.",
                    joinNowBtn: "Create Free Account",
                    langText: "English",
                    langFlag: "https://flagcdn.com/w20/gb.png"
                },
                si: {
                    heroTitle: "ඔබේ හඬ වැදගත්. එය ලෝකය සමඟ බෙදාගන්න.",
                    heroSubtitle: "ලිපි ලියන්න සහ බෙදාගන්න, නව අදහස් සොයාගන්න, කැමැත්ත, අදහස් සහ අනුගමනය කිරීම් හරහා ලේඛකයන් සමඟ සම්බන්ධ වන්න.",
                    statArticlesLabel: "ප්‍රකාශිත ලිපි",
                    statWritersLabel: "ක්‍රියාකාරී ලේඛකයන්",
                    statViewsLabel: "ලිපි නැරඹුම්",
                    startWritingBtn: "දැන් ලිවීම ආරම්භ කරන්න",
                    exploreArticlesBtn: "ලිපි බලන්න",
                    exploreArticlesBtn2: "ලිපි බලන්න",
                    latestArticlesTitle: "නවතම ලිපි",
                    latestArticlesSubtitle: "අපගේ ප්‍රජාව බෙදාගත් අදහස් සොයාගන්න.",
                    latestArticlesEmpty: "තවම ලිපි පළ කර නැත. අදහසක් බෙදාගන්නා පළමු කෙනා වන්න.",
                    readArticleLabel: "ලිපිය කියවන්න",
                    featuresTitle: "අපගේ වේදිකාව තෝරාගන්නේ ඇයි?",
                    featuresSubtitle: "ලිපි ලියන්න, අදහස් සොයාගන්න, සහ පාඨකයන් හා ලේඛකයන් සමඟ සම්බන්ධ වන්න.",
                    feature1Title: "ලිපි සංස්කාරකය",
                    feature1Desc: "සංස්කාරකය භාවිතයෙන් ලිපි සකස් කර ඔබේ පළ කිරීම්වලට පින්තූර එක් කරන්න.",
                    feature2Title: "ඔබේ උපකරණ පුවරුව",
                    feature2Desc: "ඔබේ ලිපි, නැරඹුම්, කැමැත්ත සහ අනුගාමිකයන් පිළිබඳ තොරතුරු බලන්න.",
                    feature3Title: "ප්‍රජා සම්බන්ධතාවය",
                    feature3Desc: "ලේඛකයන් අනුගමනය කර කැමැත්ත සහ අදහස් හරහා ප්‍රජාව සමඟ සම්බන්ධ වන්න.",
                    howItWorksTitle: "එය ක්‍රියා කරන්නේ කෙසේද",
                    howItWorksSubtitle: "සරල පියවර 3කින් ඔබේ දැනුම බෙදාගැනීම ආරම්භ කරන්න",
                    step1Title: "ලියාපදිංචි වන්න",
                    step1Desc: "තත්පර 60 ක් තුළ ඔබේ නොමිලේ ගිණුම සාදන්න",
                    step2Title: "ලියන්න සහ ආකෘතිගත කරන්න",
                    step2Desc: "ලිපියක් ලියා, සකස් කර, කැමති නම් පින්තූරයක් එක් කරන්න",
                    step3Title: "ප්‍රකාශනය කරන්න සහ බෙදාගන්න",
                    step3Desc: "ඔබේ ලිපිය පළ කර පාඨකයන් සමඟ සම්බන්ධ වන්න",
                    benefitsTitle: "ඔබට කළ හැකි දේ",
                    benefit1Title: "ලිපි සොයාගන්න",
                    benefit1Desc: "ප්‍රජාවේ නවතම, ජනප්‍රිය සහ ප්‍රවණතා ලිපි බලන්න",
                    benefit2Title: "ලේඛකයන් අනුගමනය කරන්න",
                    benefit2Desc: "ඔබ කැමති ලිපි ලියන අය අනුගමනය කර ඔවුන්ගේ නව ලිපි බලන්න",
                    benefit3Title: "අදහස් බෙදාගන්න",
                    benefit3Desc: "ලිපිවලට කැමැත්ත පළ කර අදහස් දැක්වීමෙන් සංවාදයට එක්වන්න",
                    benefit4Title: "ඔබේ ලිපි නිරීක්ෂණය කරන්න",
                    benefit4Desc: "ඔබේ ලිපි සහ සම්බන්ධතා ගණන් පුද්ගලික උපකරණ පුවරුවෙන් බලන්න",
                    ctaTitle: "ඔබේ ලිවීමේ ගමන ආරම්භ කිරීමට සූදානම්ද?",
                    ctaText: "අදම අපගේ ලේඛක ප්‍රජාවට සම්බන්ධ වන්න. ආරම්භ කිරීම සම්පූර්ණයෙන්ම නොමිලේ.",
                    joinNowBtn: "නොමිලේ ගිණුමක් සාදන්න",
                    langText: "සිංහල",
                    langFlag: "https://flagcdn.com/w20/lk.png"
                }
            };

            // Check saved language
            const savedLang = localStorage.getItem('language') === 'si' ? 'si' : 'en';
            updateLanguage(savedLang);
            
            langToggle.addEventListener('click', function() {
                const currentLang = localStorage.getItem('language') || 'en';
                const newLang = currentLang === 'en' ? 'si' : 'en';
                
                localStorage.setItem('language', newLang);
                updateLanguage(newLang);
                
                // Add animation
                this.classList.add('lang-changing');
                setTimeout(() => {
                    this.classList.remove('lang-changing');
                }, 300);
            });
            
            function updateLanguage(lang) {
                const texts = translations[lang];
                
                // Update all text elements
                for (const [key, value] of Object.entries(texts)) {
                    const element = document.getElementById(key);
                    if (element) {
                        if (key === 'langFlag') {
                            element.src = value;
                        } else {
                            element.textContent = value;
                        }
                    }
                }
                document.querySelectorAll('.read-article-label').forEach(function(element) {
                    element.textContent = texts.readArticleLabel;
                });
                document.documentElement.lang = lang === 'si' ? 'si-LK' : 'en';
                
                // Add Sinhala font class if needed
                if (lang === 'si') {
                    document.body.classList.add('sinhala-text');
                } else {
                    document.body.classList.remove('sinhala-text');
                }
            }
        });
    </script>
</body>
</html>