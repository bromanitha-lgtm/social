<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Home</title>

    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>

    <div class="app-container">
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
            <div>
                <div class="logo">
                    <div class="logo-shape"></div>
                    <span>{{ config('app.name') }}</span>
                </div>
                <nav class="nav-links">
                    <a href="" class="nav-item active" data-target="home">
                        <i class="fa-solid fa-house"></i>
                        <span>Home</span>
                    </a>
                    <a href="" class="nav-item" data-target="profile">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="" class="nav-item" data-target="dashboard">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="" class="nav-item" data-target="wallet">
                        <i class="fas fa-wallet"></i>
                        <span>Wallet</span>
                    </a>
                    <a href="" class="nav-item" data-target="settings">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </nav>
            </div>
            <div class="nav-item nav-bottom" id="themeToggle">
                <i class="fas fa-moon" id="theme-icon themeIcon"></i>
                <span id="theme-text themeText">Dark Mode</span>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="main-content">
            <!-- Centering wrapper for very wide screens -->
            <div class="content-container">
                
                <!-- HEADER -->
                <header class="top-bar">
                    <div style="display: flex; align-items: center; flex: 1;">
                        <button class="menu-toggle" onclick="toggleSidebar()">
                            <i class="fas fa-bars"></i>
                        </button>
                        <div class="search-wrapper">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search...">
                        </div>
                    </div>

                    <div class="header-actions">
                        <button class="icon-btn" onclick="showToast('Notifications checked', 'success')">
                            <i class="far fa-bell"></i>
                            <div class="notification-dot" style="position: absolute; top: 0; right: 0; width: 8px; height: 8px; background: var(--accent-color); border-radius: 50%; border: 2px solid var(--card-bg);"></div>
                        </button>
                        <button class="icon-btn" onclick="showToast('Messages inbox empty', 'normal')">
                            <i class="far fa-envelope"></i>
                        </button>
                        <button class="icon-btn">
                            <img src="https://picsum.photos/seed/user123/40/40.jpg" alt="User" style="border-radius: 50%; width: 32px; height: 32px;">
                        </button>
                    </div>
                </header>

                <!-- 1. HOME SECTION -->
                @include('layouts.homeTabs.home') <!-- Posts should be displayed here. -->

                <!-- 2. DASHBOARD SECTION -->
                @include('layouts.homeTabs.dashboard')

                <!-- 3. PROFILE SECTION -->
                @include('layouts.homeTabs.profile')


                <!-- 4. WALLET SECTION -->
                <section id="wallet" class="page-section">
                    <div class="dashboard-grid">
                        <div class="widget-card" style="background: linear-gradient(135deg, #3a0ca3, #4361ee); color: white;">
                            <div style="display: flex; justify-content: space-between; align-items: center;"><span>Total Balance</span><i class="fab fa-cc-visa" style="font-size: 2rem; opacity: 0.8;"></i></div>
                            <h2 style="font-size: 2.5rem; margin: 20px 0;">$12,450.00</h2>
                            <div style="display: flex; gap: 10px;"><button class="btn" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(5px);">Deposit</button><button class="btn" style="background: rgba(255,255,255,0.2); color: white; backdrop-filter: blur(5px);">Withdraw</button></div>
                        </div>
                        <div class="widget-card">
                            <h3>Quick Transfer</h3>
                            <div style="margin-top: 20px; display: flex; gap: 10px; align-items: center;"><input type="text" placeholder="Wallet Address" style="flex: 1; padding: 10px; border: 1px solid var(--border-color); border-radius: 8px; background: var(--bg-color); color: var(--text-main);"><button class="btn btn-primary" onclick="showToast('Transfer Initiated', 'success')">Send</button></div>
                        </div>
                    </div>
                </section>

                <!-- 5. SETTINGS SECTION -->
                @include('layouts.homeTabs.settings')

            </div> <!-- End  Content Container -->
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- <script src="theme.js"></script> -->
    <script src="{{ asset('js/home.js') }}"></script>
    
</body>
</html>