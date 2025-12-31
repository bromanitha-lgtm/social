<section id="dashboard" class="page-section active-section">
                    
                    <div class="dashboard-grid">
                        <div class="profile-card">
                            <div class="avatar-container">
                                <img src="https://picsum.photos/seed/alex/200/200.jpg" alt="Profile" class="avatar-img">
                            </div>
                            <div class="profile-info">
                                <h1>{{ Auth::user()->fName }} {{ Auth::user()->lName }}</h1>
                                <p>{{ Auth::user()->email }}</p>
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