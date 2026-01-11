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
        <!-- Add your logo or header content here -->
        <img src="{{ asset('build/assets/svg/mainLogo.svg') }}" alt="Logo" class="logo">
        <a href="{{ route('login') }}" class="btn-animate"><i class="fas fa-user login-btn"></i></a>
    </header>
    
    <main class="container">
        <!-- Hero Section -->
        <section class="hero animate-fade-in">
            <div class="hero-content">
                <h1 class="hero-title">Past Paper Discussion Board</h1>
                <p class="hero-subtitle" id="hero-subtitle">සියලුම විෂයයන් සඳහා ප්‍රශ්න පත්‍ර, විසඳුම් සහ සාකච්ඡා</p>
                <div class="hero-buttons">
                    {{-- <a href="{{ route('search') }}" class="btn btn-primary btn-animate"> --}}
                        <i class="fas fa-book-open"></i> ප්‍රශ්න පත්‍ර බලන්න
                    </a>
                    <a href="#features" class="btn btn-secondary btn-animate">
                        <i class="fas fa-star"></i> විෂේෂාංග
                    </a>
                </div>
            </div>
            <div class="hero-image">
                <div class="floating-element">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </section>
        

        <!-- Features Section -->
        <section id="features" class="features-section">
            <h2 class="section-title">විෂේෂාංග</h2>
            <div class="features-grid">
                <div class="feature-card animate-slide-up" style="animation-delay: 0.1s">
                    <div class="feature-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <h3>PDF Download</h3>
                    <p>ප්‍රශ්න පත්‍ර PDF ලෙස බාගත කරගන්න</p>
                </div>
                
                <div class="feature-card animate-slide-up disable" style="animation-delay: 0.2s">
                    <div class="feature-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>Online Paper MCQs</h3>
                    <p>ප්‍රශ්න සඳහා උත්තර සහ සාකච්ඡා</p>
                </div>
                
                <div class="feature-card animate-slide-up disable" style="animation-delay: 0.3s">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Advanced Search</h3>
                    <p>විෂය, ශ්‍රේණිය, වර්ෂය අනුව සොයන්න</p>
                </div>
                
                <div class="feature-card animate-slide-up disable" style="animation-delay: 0.4s">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Mobile Friendly</h3>
                    <p>ඕනෑම උපාංගයකින් පහසුවෙන් ප්‍රවේශ වන්න</p>
                </div>
            </div>
        </section>
    </main>
    
    <script src="{{ asset('js/theme.js') }}"></script>
</body>
</html>