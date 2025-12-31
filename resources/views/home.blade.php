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
                    <a href="" class="nav-item active" data-target="dashboard">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="" class="nav-item" data-target="profile">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>
                    <a href="" class="nav-item" data-target="analytics">
                        <i class="fas fa-chart-line"></i>
                        <span>Analytics</span>
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
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

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

                <!-- 1. DASHBOARD SECTION -->
                <section id="dashboard" class="page-section active-section">
                    
                    <div class="dashboard-grid">
                        <div class="profile-card">
                            <div class="avatar-container">
                                <img src="https://picsum.photos/seed/alex/200/200.jpg" alt="Profile" class="avatar-img">
                            </div>
                            <div class="profile-info">
                                <h1>{{ Auth::user()->name }}</h1>
                                <p>Senior Product Designer @ CreativeStudio</p>
                                <div class="profile-stats">
                                    <div class="stat-item"><span class="stat-number" data-target="128">0</span><span class="stat-label">Projects</span></div>
                                    <div class="stat-item"><span class="stat-number" data-target="3420">0</span><span class="stat-label">Followers</span></div>
                                    <div class="stat-item"><span class="stat-number" data-target="98">0</span><span class="stat-label">Reviews</span></div>
                                </div>
                            </div>
                            <div class="action-buttons">
                                <button class="btn btn-primary" onclick="showToast('Edit Profile Mode Activated')"><i class="fas fa-pen"></i> Edit</button>
                                <button class="btn btn-outline" onclick="showToast('Profile Link Copied')"><i class="fas fa-share-alt"></i> Share</button>
                            </div>
                        </div>

                        <div class="widget-card" style="justify-content: center; align-items: center; text-align: center;">
                            <div class="icon-box purple" style="margin-bottom: 10px; width: 60px; height: 60px; font-size: 2rem;"><i class="fas fa-bolt"></i></div>
                            <h3>Account Status</h3>
                            <p style="color: var(--text-muted); margin: 5px 0 15px 0;">Pro Plan Active</p>
                            <div style="width: 100%; background: var(--border-color); height: 8px; border-radius: 10px; overflow: hidden;"><div style="width: 75%; background: var(--secondary-color); height: 100%; border-radius: 10px;"></div></div>
                            <span style="font-size: 0.8rem; margin-top: 5px; display: block;">75% Storage Used</span>
                        </div>
                    </div>

                    <section class="widgets-row">
                        <div class="widget-card">
                            <div class="icon-box pink"><i class="fas fa-heart"></i></div>
                            <div><div class="widget-value" data-target="8540">0</div><div style="color: var(--success-color); font-size: 0.8rem; font-weight: bold;"><i class="fas fa-arrow-up"></i> +12.5%</div></div>
                            <span style="font-size: 0.9rem; color: var(--text-muted);">Total Likes</span>
                        </div>
                        <div class="widget-card">
                            <div class="icon-box blue"><i class="fas fa-eye"></i></div>
                            <div><div class="widget-value" data-target="12300">0</div><div style="color: var(--success-color); font-size: 0.8rem; font-weight: bold;"><i class="fas fa-arrow-up"></i> +8.2%</div></div>
                            <span style="font-size: 0.9rem; color: var(--text-muted);">Profile Views</span>
                        </div>
                        <div class="widget-card">
                            <div class="icon-box purple"><i class="fas fa-comment-dots"></i></div>
                            <div><div class="widget-value" data-target="452">0</div><div style="color: var(--accent-color); font-size: 0.8rem; font-weight: bold;"><i class="fas fa-arrow-down"></i> -2.1%</div></div>
                            <span style="font-size: 0.9rem; color: var(--text-muted);">New Inquiries</span>
                        </div>
                    </section>

                    <section class="content-split">
                        <div class="chart-container">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <h3>Performance Analytics</h3>
                                <select id="chartFilter" onchange="updateChartData()" style="padding: 5px 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);">
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                </select>
                            </div>
                            <div class="canvas-wrapper"><canvas id="smartChart"></canvas></div>
                        </div>

                        <div class="activity-feed">
                            <h3>Recent Activity</h3>
                            <div class="activity-list" id="activityList"></div>
                            <button class="btn btn-outline" style="margin-top: auto; width: 100%; justify-content: center;" onclick="showToast('Viewing all history...')">View All</button>
                        </div>
                    </section>
                </section>

                <!-- 2. PROFILE SECTION -->
                <section id="profile" class="page-section">
                    <div class="profile-card">
                        <div class="avatar-container"><img src="https://picsum.photos/seed/alex/200/200.jpg" alt="Profile" class="avatar-img"></div>
                        <div class="profile-info"><h1>My Profile</h1><p>Manage your personal information and preferences.</p></div>
                    </div>
                    <div class="dashboard-grid" style="grid-template-columns: 1fr 1fr; margin-top: 24px;">
                        <div class="widget-card">
                            <h3>Basic Info</h3>
                            <div style="margin-top: 15px; display: flex; flex-direction: column; gap: 15px;">
                                <div><label style="font-size: 0.8rem; color: var(--text-muted);">Full Name</label><input type="text" value="Alex Morgan" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);"></div>
                                <div><label style="font-size: 0.8rem; color: var(--text-muted);">Email Address</label><input type="email" value="alex@example.com" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border-color); background: var(--bg-color); color: var(--text-main);"></div>
                                <button class="btn btn-primary" onclick="showToast('Profile Updated', 'success')">Save Changes</button>
                            </div>
                        </div>
                        <div class="widget-card">
                            <h3>Skills & Tags</h3>
                            <div style="margin-top: 15px; display: flex; flex-wrap: wrap; gap: 10px;">
                                <span style="background: rgba(67, 97, 238, 0.1); color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">UI Design</span>
                                <span style="background: rgba(67, 97, 238, 0.1); color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">Figma</span>
                                <span style="background: rgba(67, 97, 238, 0.1); color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">HTML/CSS</span>
                                <span style="background: rgba(67, 97, 238, 0.1); color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.85rem;">Prototyping</span>
                                <button class="btn btn-outline" style="font-size: 0.8rem;">+ Add Skill</button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. ANALYTICS SECTION -->
                <section id="analytics" class="page-section">
                    <h2 style="margin-bottom: 20px;">Detailed Analytics</h2>
                    <div class="content-split" style="height: 500px;">
                        <div class="chart-container">
                            <h3>Audience Growth</h3>
                            <div class="canvas-wrapper"><canvas id="secondaryChart"></canvas></div>
                        </div>
                        <div class="activity-feed">
                            <h3>Top Sources</h3>
                            <div class="activity-list">
                                <div class="activity-item">
                                    <div class="activity-dot" style="background: var(--success-color);"></div>
                                    <div style="flex:1"><div style="font-weight: 600;">Direct Traffic</div><div style="font-size: 0.8rem; color: var(--text-muted);">65% of total</div></div><span>12,400</span>
                                </div>
                                <div class="activity-item">
                                    <div class="activity-dot" style="background: var(--accent-color);"></div>
                                    <div style="flex:1"><div style="font-weight: 600;">Social Media</div><div style="font-size: 0.8rem; color: var(--text-muted);">25% of total</div></div><span>5,200</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

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
                <section id="settings" class="page-section">
                    <h2 style="margin-bottom: 20px;">Settings</h2>
                    <div class="settings-grid">
                        <div class="widget-card">
                            <h3>Appearance</h3>
                            <div style="margin-top: 15px;">
                                <div class="setting-item"><div><div style="font-weight: 600;">Dark Mode</div><div style="font-size: 0.8rem; color: var(--text-muted);">Adjust interface color</div></div><i class="fas fa-toggle-off" id="settings-toggle-icon" style="font-size: 1.5rem; color: var(--gray-color); cursor: pointer;"></i></div>
                            </div>
                        </div>
                    </div>
                </section>

            </div> <!-- End Content Container -->
        </main>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- <script src="theme.js"></script> -->
    <script src="{{ asset('js/home.js') }}"></script>
    
</body>
</html>