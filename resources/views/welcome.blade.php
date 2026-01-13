<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <img src="{{ asset('build/assets/svg/mainLogo.svg') }}" alt="Logo" class="logo">
        
        <div class="lang-toggle-container">
            <button class="lang-toggle-btn" id="langToggle">
                <div class="lang-flag">
                    <img src="https://flagcdn.com/w20/gb.png" alt="English" id="langFlag">
                </div>
                <span id="langText">English</span>
            </button>
        </div>
        
        <a href="{{ route('login') }}" class="btn-animate"><i class="fas fa-user login-btn"></i></a>
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
                            use Illuminate\Support\Facades\DB;
                            
                            // Get published posts count
                            $publishedPostsCount = Post::when(
                                \Schema::hasColumn('posts', 'status'), 
                                function($query) {
                                    $query->where('status', 'published');
                                }
                            )->count();
                            
                            // Get active writers count (users with at least one published post in last 30 days)
                            $activeWritersCount = User::whereHas('posts', function($query) {
                                $query->when(\Schema::hasColumn('posts', 'status'), function($q) {
                                    $q->where('status', 'published');
                                })
                                ->where('created_at', '>=', now()->subDays(30));
                            })->count();
                            
                            // Get monthly readers (approximate from post views in last 30 days)
                            $monthlyReaders = Post::when(
                                \Schema::hasColumn('posts', 'status'), 
                                function($query) {
                                    $query->where('status', 'published');
                                }
                            )->where('created_at', '>=', now()->subDays(30))
                              ->sum('views');
                        @endphp
                        
                        <span class="stat-number" id="statArticles">{{ number_format($publishedPostsCount) }}+</span>
                        <span class="stat-label" id="statArticlesLabel">Articles Published</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="statWriters">{{ number_format($activeWritersCount) }}+</span>
                        <span class="stat-label" id="statWritersLabel">Active Writers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" id="statReaders">{{ number_format($monthlyReaders) }}+</span>
                        <span class="stat-label" id="statReadersLabel">Monthly Readers</span>
                    </div>
                </div>
                
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-animate">
                        <i class="fas fa-pen"></i> <span id="startWritingBtn">Start Writing Now</span>
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline btn-animate">
                        <i class="fas fa-sign-in-alt"></i> <span id="signInBtn">Sign In to Read</span>
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <div class="floating-element">
                    <i class="fas fa-newspaper"></i>
                </div>
            </div>
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
                    <p class="article-feature-desc" id="feature1Desc">Write and format your articles with our powerful WYSIWYG editor. Add images, videos, and embed content easily.</p>
                </div>

                <!-- Feature 2 -->
                <div class="article-feature-card animate-slide-up" style="animation-delay: 0.2s">
                    <div class="article-feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="article-feature-title" id="feature2Title">Analytics Dashboard</h3>
                    <p class="article-feature-desc" id="feature2Desc">Track your article performance with detailed analytics. See views, engagement, and reader demographics.</p>
                </div>

                <!-- Feature 3 -->
                <div class="article-feature-card animate-slide-up" style="animation-delay: 0.3s">
                    <div class="article-feature-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="article-feature-title" id="feature3Title">Community Engagement</h3>
                    <p class="article-feature-desc" id="feature3Desc">Connect with readers through comments, likes, and shares. Build your audience and receive feedback.</p>
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
                    <p class="step-desc" id="step2Desc">Use our editor to create beautiful articles</p>
                </div>
                
                <div class="step">
                    <div class="step-number">3</div>
                    <h3 class="step-title" id="step3Title">Publish & Share</h3>
                    <p class="step-desc" id="step3Desc">Publish instantly and share with the community</p>
                </div>
            </div>
        </section>

        <!-- Platform Benefits -->
        <section class="benefits-section">
            <h2 style="text-align: center; margin-bottom: 2rem; color: var(--text-main);" id="benefitsTitle">Benefits for Writers</h2>
            
            <div class="benefits-grid">
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit1Title">Global Reach</h4>
                        <p id="benefit1Desc">Share your articles with readers worldwide and expand your influence</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit2Title">Content Ownership</h4>
                        <p id="benefit2Desc">You retain full rights to all content you publish on our platform</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit3Title">Reader Notifications</h4>
                        <p id="benefit3Desc">Get notified when readers engage with your content</p>
                    </div>
                </div>
                
                <div class="benefit-item">
                    <div class="benefit-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="benefit-content">
                        <h4 id="benefit4Title">Category System</h4>
                        <p id="benefit4Desc">Organize your articles with categories and tags for better discoverability</p>
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
                    <a href="{{ route('login') }}" class="btn btn-outline" style="color: white; border-color: white;">
                        <i class="fas fa-sign-in-alt"></i> <span id="signInBtn2">Sign In to Continue</span>
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
                    heroSubtitle: "A powerful platform where writers, thinkers, and creators come together to share knowledge and inspire change",
                    statArticlesLabel: "Articles Published",
                    statWritersLabel: "Active Writers",
                    statReadersLabel: "Monthly Readers",
                    startWritingBtn: "Start Writing Now",
                    signInBtn: "Sign In to Read",
                    featuresTitle: "Why Choose Our Platform?",
                    featuresSubtitle: "Everything you need to share your knowledge and connect with readers",
                    feature1Title: "Rich Text Editor",
                    feature1Desc: "Write and format your articles with our powerful WYSIWYG editor. Add images, videos, and embed content easily.",
                    feature2Title: "Analytics Dashboard",
                    feature2Desc: "Track your article performance with detailed analytics. See views, engagement, and reader demographics.",
                    feature3Title: "Community Engagement",
                    feature3Desc: "Connect with readers through comments, likes, and shares. Build your audience and receive feedback.",
                    howItWorksTitle: "How It Works",
                    howItWorksSubtitle: "Start sharing your knowledge in just 3 simple steps",
                    step1Title: "Sign Up",
                    step1Desc: "Create your free account in under a minute",
                    step2Title: "Write & Format",
                    step2Desc: "Use our editor to create beautiful articles",
                    step3Title: "Publish & Share",
                    step3Desc: "Publish instantly and share with the community",
                    benefitsTitle: "Benefits for Writers",
                    benefit1Title: "Global Reach",
                    benefit1Desc: "Share your articles with readers worldwide and expand your influence",
                    benefit2Title: "Content Ownership",
                    benefit2Desc: "You retain full rights to all content you publish on our platform",
                    benefit3Title: "Reader Notifications",
                    benefit3Desc: "Get notified when readers engage with your content",
                    benefit4Title: "Category System",
                    benefit4Desc: "Organize your articles with categories and tags for better discoverability",
                    ctaTitle: "Ready to Start Your Writing Journey?",
                    ctaText: "Join our community of writers today. It's completely free to get started.",
                    joinNowBtn: "Create Free Account",
                    signInBtn2: "Sign In to Continue",
                    langText: "English",
                    langFlag: "https://flagcdn.com/w20/gb.png"
                },
                si: {
                    heroTitle: "ඔබේ හඬ වැදගත්. එය ලෝකය සමඟ බෙදාගන්න.",
                    heroSubtitle: "ලේඛකයන්, කල්පනාකරුවන් සහ නිර්මාණකරුවන් දැනුම බෙදා ගැනීමට සහ වෙනසක් ඇති කිරීමට එකතු වන ප්‍රබල වේදිකාවක්",
                    statArticlesLabel: "ප්‍රකාශිත ලිපි",
                    statWritersLabel: "ක්‍රියාකාරී ලේඛකයන්",
                    statReadersLabel: "මාසික කියවන්නන්",
                    startWritingBtn: "දැන් ලිවීම ආරම්භ කරන්න",
                    signInBtn: "කියවීමට පුරන්න",
                    featuresTitle: "අපගේ වේදිකාව තෝරාගන්නේ ඇයි?",
                    featuresSubtitle: "ඔබගේ දැනුම බෙදා ගැනීමට සහ කියවන්නන් සමඟ සම්බන්ධ වීමට අවශ්‍ය සියල්ල",
                    feature1Title: "සමෘද්ධ පෙළ සංස්කාරකය",
                    feature1Desc: "අපගේ ප්‍රබල WYSIWYG සංස්කාරකය සමඟ ඔබේ ලිපි ලියන්න සහ ආකෘතිගත කරන්න. පින්තූර, වීඩියෝ සහ කාවැද්දූ අන්තර්ගතය පහසුවෙන් එක් කරන්න.",
                    feature2Title: "විශ්ලේෂණ උපකරණ පුවරුව",
                    feature2Desc: "විස්තරාත්මක විශ්ලේෂණ සමඟ ඔබේ ලිපි කාර්ය සාධනය ගමන් මග කරන්න. දසුන්, සම්බන්ධතාවය සහ කියවන්නන්ගේ ජනවිකාස දැක්වීම් බලන්න.",
                    feature3Title: "ප්‍රජා සම්බන්ධතාවය",
                    feature3Desc: "ප්‍රතිචාර, කැමැත්ත සහ බෙදාගැනීම් හරහා කියවන්නන් සමඟ සම්බන්ධ වන්න. ඔබේ ප්‍රේක්ෂකයන් ගොඩනඟා ගන්න සහ ප්‍රතිපෝෂණ ලබා ගන්න.",
                    howItWorksTitle: "එය ක්‍රියා කරන්නේ කෙසේද",
                    howItWorksSubtitle: "සරල පියවර 3කින් ඔබේ දැනුම බෙදා ගැනීම ආරම්භ කරන්න",
                    step1Title: "ලියාපදිංචි වන්න",
                    step1Desc: "තත්පර 60 ක් තුළ ඔබේ නොමිලේ ගිණුම සාදන්න",
                    step2Title: "ලියන්න සහ ආකෘතිගත කරන්න",
                    step2Desc: "ලස්සන ලිපි සෑදීමට අපගේ සංස්කාරකය භාවිතා කරන්න",
                    step3Title: "ප්‍රකාශනය කරන්න සහ බෙදාගන්න",
                    step3Desc: "ක්ෂණිකව ප්‍රකාශනය කර ප්‍රජාව සමඟ බෙදාගන්න",
                    benefitsTitle: "ලේඛකයන් සඳහා ප්‍රතිලාභ",
                    benefit1Title: "ගෝලීය ප්‍රවේශය",
                    benefit1Desc: "ලෝකයේ සිටින කියවන්නන් සමඟ ඔබේ ලිපි බෙදාගෙන ඔබේ බලපෑම පුළුල් කරන්න",
                    benefit2Title: "අන්තර්ගත හිමිකම",
                    benefit2Desc: "අපගේ වේදිකාවේ ඔබ ප්‍රකාශනය කරන සියලුම අන්තර්ගතයන් සඳහා සම්පූර්ණ අයිතිවාසිකම් ඔබට ඇත",
                    benefit3Title: "කියවන්නන්ගේ දැනුම්දීම්",
                    benefit3Desc: "කියවන්නන් ඔබේ අන්තර්ගතය සමඟ ඇති කරගන්නා විට දැනුම් දෙන්න",
                    benefit4Title: "ප්‍රවර්ග ක්‍රමය",
                    benefit4Desc: "හොඳින් සොයා ගැනීම සඳහා ප්‍රවර්ග සහ ටැග් සමඟ ඔබේ ලිපි සංවිධානය කරන්න",
                    ctaTitle: "ඔබේ ලිවීමේ ගමන ආරම්භ කිරීමට සූදානම්ද?",
                    ctaText: "අදම අපගේ ලේඛක ප්‍රජාවට සම්බන්ධ වන්න. ආරම්භ කිරීම සම්පූර්ණයෙන්ම නොමිලේ.",
                    joinNowBtn: "නොමිලේ ගිණුමක් සාදන්න",
                    signInBtn2: "කරගෙන යාමට පුරන්න",
                    langText: "සිංහල",
                    langFlag: "https://flagcdn.com/w20/lk.png"
                }
            };

            // Check saved language
            const savedLang = localStorage.getItem('language') || 'en';
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