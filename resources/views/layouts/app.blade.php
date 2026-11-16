<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Article') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- Animate CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- Toastr CSS for notifications -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Page Specific Styles -->
    @stack('styles')
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3a0ca3;
            --accent-color: #4cc9f0;
            --success-color: #4ade80;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --light-color: #f8fafc;
            --dark-color: #1e293b;
            --sidebar-width: 260px;
            --header-height: 70px;
            --transition-speed: 0.3s;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', 'Figtree', sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            color: var(--dark-color);
            overflow-x: hidden;
            padding-top: var(--header-height);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Navbar Styling */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)) !important;
            height: var(--header-height);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 0 1.5rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            transition: all var(--transition-speed) ease;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.8rem;
            color: white !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            animation: pulse 2s infinite;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin: 0 3px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
            color: white !important;
        }

        /* Dropdown Styling */
        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: fadeInDown 0.3s ease;
            padding: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dropdown-item:hover {
            background: var(--primary-color);
            color: white;
            transform: translateX(5px);
        }

        /* User Profile in Navbar */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.3);
            object-fit: cover;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .user-profile:hover .user-avatar {
            transform: scale(1.1);
            border-color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
        }

        .user-role {
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.8rem;
        }

        /* Main Content Area */
        .main-content {
            min-height: calc(100vh - var(--header-height));
            animation: fadeIn 0.5s ease;
            padding: 2rem 0;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: white;
        }

        /* Cards Styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            background: white;
            margin-bottom: 1.5rem;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Buttons Styling */
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.4);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            background: transparent;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Form Controls */
        .form-control {
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
            transform: translateY(-1px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 20px rgba(67, 97, 238, 0.4);
            cursor: pointer;
            z-index: 1000;
            transition: all 0.3s ease;
            animation: float 3s ease-in-out infinite;
        }

        .fab:hover {
            transform: scale(1.1) rotate(90deg);
            animation: none;
        }

        /* Alerts & Notifications */
        .alert {
            border-radius: 12px;
            border: none;
            padding: 1rem 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, var(--success-color), #22c55e);
            color: white;
        }

        .alert-warning {
            background: linear-gradient(135deg, var(--warning-color), #eab308);
            color: white;
        }

        .alert-danger {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
        }

        .alert-info {
            background: linear-gradient(135deg, var(--accent-color), #0ea5e9);
            color: white;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 0.75rem 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .breadcrumb-item.active {
            color: var(--primary-color);
            font-weight: 600;
        }

        /* Pagination */
        .pagination {
            justify-content: center;
        }

        .page-link {
            border: none;
            border-radius: 8px !important;
            margin: 0 3px;
            color: var(--primary-color);
            font-weight: 500;
        }

        .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-color: var(--primary-color);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                padding: 1rem;
            }
            
            .navbar-nav {
                padding: 1rem 0;
            }
            
            .navbar-collapse {
                background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                padding: 1rem;
                border-radius: 0 0 15px 15px;
                margin-top: 10px;
            }
            
            .user-profile .user-info {
                display: none;
            }
            
            .dropdown-menu {
                position: static !important;
                transform: none !important;
                width: 100%;
                margin-top: 0.5rem;
                box-shadow: none;
                background: rgba(255, 255, 255, 0.1);
            }
            
            .dropdown-item {
                color: white;
            }
            
            .dropdown-item:hover {
                background: rgba(255, 255, 255, 0.2);
            }
        }

        @media (max-width: 576px) {
            .navbar-brand span {
                font-size: 1.2rem;
            }
            
            .main-content {
                padding: 1rem 0.5rem;
            }
            
            .container {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
        }

        /* Theme Toggle */
        .theme-toggle {
            position: relative;
            width: 60px;
            height: 30px;
            background: #e2e8f0;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .theme-toggle::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 26px;
            height: 26px;
            background: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .theme-toggle.active {
            background: var(--primary-color);
        }

        .theme-toggle.active::after {
            left: calc(100% - 28px);
        }

        /* Dark Theme */
        [data-bs-theme="dark"] body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .card {
            background: #1e293b;
            color: #e2e8f0;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        [data-bs-theme="dark"] .form-control {
            background: #2d3748;
            border-color: #4a5568;
            color: #e2e8f0;
        }

        [data-bs-theme="dark"] .form-control:focus {
            background: #2d3748;
            color: #e2e8f0;
            border-color: var(--primary-color);
        }

        [data-bs-theme="dark"] ::-webkit-scrollbar-track {
            background: #2d3748;
        }

        [data-bs-theme="dark"] ::-webkit-scrollbar-thumb {
            background: var(--primary-color);
        }

        [data-bs-theme="dark"] .breadcrumb {
            background: #2d3748;
            color: #e2e8f0;
        }

        /* Loading Screen */
        #loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease;
        }

        /* Ripple effect styles */
        .btn {
            position: relative;
            overflow: hidden;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
            cursor: pointer;
            z-index: 999;
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
        }

        .back-to-top.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div id="loading">
        <div class="spinner-border text-light" style="width: 3rem; height: 3rem;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <h3 class="mt-4 text-light animate__animated animate__pulse">
            <i class="fas fa-rocket me-2"></i>Loading {{ config('app.name', 'Article') }}...
        </h3>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <!-- Brand Logo -->
            <a class="navbar-brand animate__animated animate__fadeInLeft" href="{{ url('/') }}">
                <i class="fas fa-rocket"></i>
                <span>{{ config('app.name', 'Article') }}</span>
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Content -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Navigation -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                            <i class="fas fa-home"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('posts*') && !request()->is('posts/create') ? 'active' : '' }}" href="{{ route('posts.index') }}">
                            <i class="fas fa-newspaper"></i>
                            <span>Posts</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                            <i class="fas fa-chart-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-user-edit"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    @if(Auth::check() && method_exists(Auth::user(), 'isAdmin') && Auth::user()->isAdmin())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-cogs"></i>
                            <span>Admin</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-users"></i> Users</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-chart-bar"></i> Analytics</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>

                <!-- Right Side Navigation -->
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <!-- Theme Toggle -->
                    <li class="nav-item me-3 d-flex align-items-center">
                        <div class="theme-toggle" id="themeToggle" role="button" tabindex="0" aria-label="Toggle theme">
                            <i class="fas fa-sun position-absolute" style="left: 8px; top: 7px; color: #f59e0b; font-size: 12px;"></i>
                            <i class="fas fa-moon position-absolute" style="right: 8px; top: 7px; color: #cbd5e0; font-size: 12px;"></i>
                        </div>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            @php
                                $unreadNotifications = 0; // You can implement actual notification count
                            @endphp
                            @if($unreadNotifications > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" 
                                  style="font-size: 0.6rem; padding: 0.25em 0.6em;">
                                {{ $unreadNotifications }}
                            </span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Notifications</h6></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user-plus text-success me-2"></i> New user registered</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-exclamation-triangle text-warning me-2"></i> System update required</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-check-circle text-primary me-2"></i> Profile updated</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">View all notifications</a></li>
                        </ul>
                    </li>

                    <!-- User Profile -->
                    @auth
                    <li class="nav-item dropdown">
                        <div class="user-profile dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                            @if(Auth::user()->avatar_url && file_exists(public_path(Auth::user()->avatar_url)))
                                <img src="{{ Auth::user()->avatar_url }}" alt="{{ Auth::user()->name }}" class="user-avatar">
                            @else
                                <div class="user-avatar">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="user-info d-none d-lg-block">
                                <span class="user-name">{{ Auth::user()->name }}</span>
                                <span class="user-role">
                                    @if(method_exists(Auth::user(), 'isAdmin') && Auth::user()->isAdmin())
                                        Administrator
                                    @else
                                        Member
                                    @endif
                                </span>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="dropdown-header">
                                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                                <small class="text-muted">{{ Auth::user()->email }}</small>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    <i class="fas fa-user-circle me-2"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('posts.create') }}">
                                    <i class="fas fa-plus me-2"></i> New Post
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                    
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">
                            <i class="fas fa-user-plus"></i>
                            <span>Register</span>
                        </a>
                    </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content animate__animated animate__fadeIn">
        <div class="container-fluid px-4">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show animate__animated animate__slideInRight" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__slideInRight" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show animate__animated animate__slideInRight" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show animate__animated animate__slideInRight" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Breadcrumb -->
            @section('breadcrumb')
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>
                    @yield('breadcrumb-items')
                </ol>
            </nav>
            @show

            <!-- Page Content -->
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer mt-5">
        <div class="container-fluid px-4">
            <div class="row">
                <div class="col-md-4">
                    <h5>{{ config('app.name', 'Article') }}</h5>
                    <p class="mb-0">A modern platform for sharing articles and ideas.</p>
                </div>
                <div class="col-md-2">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('posts.index') }}">Posts</a></li>
                        <li><a href="{{ route('posts.popular') }}">Popular</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h6>Legal</h6>
                    <ul class="list-unstyled">
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h6>Connect With Us</h6>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white fs-5"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white fs-5"><i class="fab fa-github"></i></a>
                    </div>
                    <p class="mt-3 mb-0">
                        &copy; {{ date('Y') }} {{ config('app.name', 'Article') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <div class="back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </div>

    <!-- Floating Action Button -->
    @auth
    <div class="fab" data-bs-toggle="tooltip" data-bs-placement="left" title="Quick Actions" role="button" tabindex="0">
        <i class="fas fa-plus"></i>
    </div>
    @endauth

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Toastr JS for notifications -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Page Specific Scripts -->
    @stack('scripts')
    
    <script>
        // Hide loading screen
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loading = document.getElementById('loading');
                loading.style.opacity = '0';
                setTimeout(function() {
                    loading.style.display = 'none';
                }, 500);
            }, 800);
        });

        // Theme toggle functionality
        document.getElementById('themeToggle').addEventListener('click', function() {
            toggleTheme();
        });

        document.getElementById('themeToggle').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleTheme();
            }
        });

        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-bs-theme', newTheme);
            this.classList.toggle('active');
            
            localStorage.setItem('theme', newTheme);
            
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 150);
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
        if (savedTheme === 'dark') {
            document.getElementById('themeToggle').classList.add('active');
        }

        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Initialize dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl);
            });
        });

        // Back to Top Button
        const backToTop = document.getElementById('backToTop');
        
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });

        backToTop.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Toastr notifications configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": true,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // FAB click functionality
        let fabMenu = null;
        const fab = document.querySelector('.fab');
        
        if (fab) {
            fab.addEventListener('click', function(e) {
                e.stopPropagation();
                
                if (fabMenu) {
                    fabMenu.remove();
                    fabMenu = null;
                    return;
                }
                
                // Create floating action menu
                const menuItems = [
                    {icon: 'fas fa-plus', label: 'New Post', action: '{{ route("posts.create") }}'},
                    {icon: 'fas fa-search', label: 'Search', action: '#'},
                    {icon: 'fas fa-share', label: 'Share', action: '#'},
                    {icon: 'fas fa-cog', label: 'Settings', action: '#'}
                ];
                
                fabMenu = document.createElement('div');
                fabMenu.className = 'fab-menu';
                fabMenu.style.cssText = `
                    position: fixed;
                    bottom: 80px;
                    right: 20px;
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                    padding: 10px;
                    z-index: 999;
                    animation: slideInUp 0.3s ease;
                `;
                
                const slideInUp = document.createElement('style');
                slideInUp.textContent = `
                    @keyframes slideInUp {
                        from { transform: translateY(20px); opacity: 0; }
                        to { transform: translateY(0); opacity: 1; }
                    }
                `;
                document.head.appendChild(slideInUp);
                
                menuItems.forEach(item => {
                    const menuItem = document.createElement('a');
                    menuItem.href = item.action;
                    menuItem.className = 'fab-menu-item';
                    menuItem.style.cssText = `
                        display: flex;
                        align-items: center;
                        padding: 10px 15px;
                        color: #333;
                        text-decoration: none;
                        border-radius: 10px;
                        transition: all 0.3s ease;
                        margin: 5px 0;
                    `;
                    menuItem.innerHTML = `
                        <i class="${item.icon} me-3"></i>
                        <span>${item.label}</span>
                    `;
                    menuItem.addEventListener('mouseenter', () => {
                        menuItem.style.background = '#f1f5f9';
                    });
                    menuItem.addEventListener('mouseleave', () => {
                        menuItem.style.background = 'transparent';
                    });
                    fabMenu.appendChild(menuItem);
                });
                
                document.body.appendChild(fabMenu);
                
                const closeFabMenu = (e) => {
                    if (fabMenu && !fabMenu.contains(e.target) && !fab.contains(e.target)) {
                        fabMenu.remove();
                        fabMenu = null;
                        document.removeEventListener('click', closeFabMenu);
                    }
                };
                
                setTimeout(() => {
                    document.addEventListener('click', closeFabMenu);
                }, 100);
            });
        }

        // Ripple effect for buttons
        document.querySelectorAll('.btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const existingRipples = this.querySelectorAll('.ripple-effect');
                existingRipples.forEach(ripple => ripple.remove());
                
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.className = 'ripple-effect';
                ripple.style.cssText = `
                    position: absolute;
                    border-radius: 50%;
                    background: rgba(255, 255, 255, 0.7);
                    transform: scale(0);
                    animation: ripple-animation 0.6s linear;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                setTimeout(() => {
                    if (ripple.parentNode === this) {
                        ripple.remove();
                    }
                }, 600);
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const target = document.querySelector(targetId);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                openDropdowns.forEach(dropdown => {
                    const dropdownInstance = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                    if (dropdownInstance) {
                        dropdownInstance.hide();
                    }
                });
            }
        });

        // AJAX setup for CSRF token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Display flash messages as toastr notifications
        @if(session('success'))
        toastr.success('{{ session('success') }}');
        @endif

        @if(session('error'))
        toastr.error('{{ session('error') }}');
        @endif

        @if(session('warning'))
        toastr.warning('{{ session('warning') }}');
        @endif

        @if(session('info'))
        toastr.info('{{ session('info') }}');
        @endif
    </script>
</body>
</html>