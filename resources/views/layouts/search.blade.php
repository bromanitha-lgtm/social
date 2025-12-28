<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ config('app.name', 'Past Papers') }} - Download past papers, model papers, and answers for various subjects and grades.">
    <title>{{ config('app.name', 'Past Papers') }} - Search Papers</title>
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search.css') }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Responsive Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <!-- Mobile Menu Button (Hidden on Desktop) -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open menu">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Header -->
    <header>
        <nav class="nav-container">
            <a href="{{ route('home') }}" class="logo">
                <i class="fas fa-book"></i> 
                <span class="logo-text">{{ config('app.name', 'Past Paper System') }}</span>
            </a>
            
            <!-- Mobile Close Button -->
            <button class="mobile-close-btn" id="mobileCloseBtn" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
            
            <ul class="nav-links" id="navLinks">
                <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> <span class="nav-text">Home</span></a></li>
                <li><a href="{{ route('search') }}" class="active"><i class="fas fa-search"></i> <span class="nav-text">Search</span></a></li>
                <li><a href="{{ route('search') }}"><i class="fas fa-file-alt"></i> <span class="nav-text">Papers</span></a></li>
                <li><a href="#"><i class="fas fa-question-circle"></i> <span class="nav-text">Answers</span></a></li>
                <li><a href="#"><i class="fas fa-comments"></i> <span class="nav-text">Discussion</span></a></li>
            </ul>
            
            <div class="nav-right">
                <!-- Theme Toggle -->
                <button id="themeToggle" class="theme-toggle-btn" aria-label="Toggle theme">
                    <i class="fas fa-moon" id="themeIcon"></i>
                    <span id="themeText" class="theme-text">Dark Mode</span>
                </button>
                
                <!-- Auth Buttons -->
                @auth
                    <a href="{{ route('search') }}" class="btn btn-primary btn-sm login-btn">
                        <i class="fas fa-user"></i>
                        <span class="login-text">Dashboard</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-sm login-btn">
                        <i class="fas fa-user"></i>
                        <span class="login-text">Login</span>
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <main class="container">
        <!-- Hero Search Section -->
        <section class="search-hero-section animate__animated animate__fadeIn">
            <div class="hero-content">
                <div class="floating-book">
                    <i class="fas fa-book-open"></i>
                </div>
                <h1 class="hero-title animate__animated animate__fadeInUp">
                    <span class="gradient-text">Find Study Materials</span>
                </h1>
                <p class="hero-subtitle animate__animated animate__fadeInUp animate__delay-1s">
                    Search thousands of past papers, model papers, and answers
                </p>
                
                <!-- Main Search Box -->
                <div class="search-box-wrapper animate__animated animate__fadeInUp animate__delay-2s">
                    <div class="search-box">
                        <div class="search-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <form action="{{ route('search') }}" method="GET" class="search-form" id="searchForm">
                            <input 
                                type="text" 
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="Search by subject, grade, year, or keywords..." 
                                class="search-input"
                                id="mainSearchInput"
                                autocomplete="off"
                                aria-label="Search past papers"
                            >
                            <button type="submit" class="search-button" aria-label="Search">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Quick Search Tags -->
                    <div class="quick-tags">
                        <span class="quick-tags-label">Quick Search:</span>
                        <div class="tags-container">
                            <a href="{{ route('search') }}?q=Mathematics" class="tag">Mathematics</a>
                            <a href="{{ route('search') }}?q=Science" class="tag">Science</a>
                            <a href="{{ route('search') }}?q=English" class="tag">English</a>
                            <a href="{{ route('search') }}?q=2023" class="tag">2023 Papers</a>
                            <a href="{{ route('search') }}?q=Grade 10" class="tag">Grade 10</a>
                            <a href="{{ route('search') }}?q=A/L" class="tag">A/L</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Stats -->
            <div class="hero-stats animate__animated animate__fadeIn animate__delay-3s">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalPapers }}</h3>
                        <p>Past Papers</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalMCQs }}</h3>
                        <p>MCQs</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="stat-info">
                        <h3>{{ $totalDownloads }}</h3>
                        <p>Downloads Papers</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filters Section -->
        <section class="filters-section animate__animated animate__fadeIn">
            <div class="filter-card">
                <div class="filter-header">
                    <h3><i class="fas fa-filter"></i> Advanced Filters</h3>
                    <button class="filter-toggle-btn" id="filterToggleBtn" aria-label="Toggle filters">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                
                <form action="{{ route('search') }}" method="GET" class="filter-form" id="filterForm">
                    <input type="hidden" name="q" value="{{ request('q') }}">
                    
                    <div class="filter-grid">
                        <div class="filter-group">
                            <label for="subjectFilter"><i class="fas fa-book"></i> Subject</label>
                            <select name="subject" id="subjectFilter" class="modern-select">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject }}" {{ request('subject') == $subject ? 'selected' : '' }}>
                                        {{ $subject }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="gradeFilter"><i class="fas fa-graduation-cap"></i> Grade</label>
                            <select name="grade" id="gradeFilter" class="modern-select">
                                <option value="">All Grades</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>
                                        Grade {{ $grade }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="yearFilter"><i class="far fa-calendar"></i> Year</label>
                            <select name="year" id="yearFilter" class="modern-select">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="sortFilter"><i class="fas fa-sort"></i> Sort By</label>
                            <select name="sort" id="sortFilter" class="modern-select">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Most Downloads</option>
                                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title (A-Z)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary btn-filter">
                            <i class="fas fa-search"></i> Apply Filters
                        </button>
                        <a href="{{ route('search') }}" class="btn btn-outline btn-reset">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </section>

        <!-- Results Section -->
        <section class="results-section" id="resultsSection">
            <!-- Results Header -->
            <div class="results-header animate__animated animate__fadeInDown">
                <div class="header-content">
                    <h2>
                        <i class="fas fa-search"></i>
                        Found <span class="highlight" id="resultCount">{{ $papers->total() }}</span> Results
                    </h2>
                    @if(request()->has('q') && request('q'))
                        <p id="searchQuery">for "{{ request('q') }}"</p>
                    @endif
                </div>
                
                <div class="sort-options">
                    <span class="sort-label">Sort by:</span>
                    <div class="sort-buttons">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" 
                           class="sort-btn {{ request('sort', 'newest') == 'newest' ? 'active' : '' }}">
                            <i class="fas fa-clock"></i> <span class="sort-text">Newest</span>
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['sort' => 'downloads']) }}" 
                           class="sort-btn {{ request('sort') == 'downloads' ? 'active' : '' }}">
                            <i class="fas fa-download"></i> <span class="sort-text">Downloads</span>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Filter Button -->
            <button class="mobile-filter-btn" id="mobileFilterBtn" aria-label="Show filters">
                <i class="fas fa-filter"></i>
                <span>Filters</span>
            </button>
            
            <!-- Results Grid -->
            <div class="results-grid" id="resultsGrid">
                @forelse($papers as $paper)
                    <div class="paper-card animate__animated animate__fadeInUp">
                        <div class="paper-card-header">
                            <span class="paper-grade">Grade {{ $paper->grade }}</span>
                            <span class="paper-year">{{ $paper->year }}</span>
                        </div>
                        <div class="paper-card-body">
                            <h3>{{ $paper->title }}</h3>
                            <p class="paper-subject">{{ $paper->subject }}</p>
                            <div class="paper-stats">
                                <span><i class="fas fa-eye"></i> {{ $paper->views }}</span>
                                <span><i class="fas fa-download"></i> {{ $paper->downloads }}</span>
                            </div>
                        </div>
                        <div class="paper-card-footer">
                            <a href="{{ route('home', $paper->id) }}" class="btn btn-outline">View Paper</a>
                            <a href="{{ route('home', $paper->id) }}" class="btn btn-primary">Download PDF</a>
                        </div>
                    </div>
                @empty
                    <div class="no-results" id="noResults">
                        <i class="fas fa-search"></i>
                        <h3>No results found</h3>
                        <p>Try adjusting your search or filters</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Loading State (for AJAX) -->
            <div class="loading-state" id="loadingState" style="display: none;">
                <div class="loading-spinner"></div>
                <p>Loading papers...</p>
            </div>
            
            <!-- Pagination -->
            @if($papers->hasPages())
                <div class="pagination animate__animated animate__fadeIn" id="pagination">
                    {{ $papers->links('vendor.pagination.custom') }}
                </div>
            @endif
        </section>

        <!-- Categories Section -->
        <section class="categories-section animate__animated animate__fadeIn">
            <div class="section-header">
                <h2><i class="fas fa-th-large"></i> Browse by Category</h2>
                <p>Find papers by subject category</p>
            </div>
            
            <div class="categories-grid">
                @foreach($categories as $category)
                    <a href="{{ route('search') }}?subject={{ $category['name'] }}" class="category-card {{ $category['class'] }}">
                        <div class="category-icon">
                            <i class="{{ $category['icon'] }}"></i>
                        </div>
                        <h3>{{ $category['name'] }}</h3>
                        <p>{{ $category['count'] }} papers</p>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Back to Top Button -->
        <button class="back-to-top" id="backToTop" aria-label="Back to top">
            <i class="fas fa-arrow-up"></i>
        </button>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <i class="fas fa-book"></i>
                    <span>{{ config('app.name', 'Past Paper System') }}</span>
                </div>
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Past Paper System') }}. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="{{ route('home') }}">Contact Us</a>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script src="{{ asset('assets/js/search.js') }}"></script>
</body>
</html>