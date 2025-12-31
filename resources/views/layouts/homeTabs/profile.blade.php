<section id="profile" class="page-section">
                    <div class="profile-card">
                        <div class="avatar-container"><img src="https://picsum.photos/seed/alex/200/200.jpg" alt="Profile" class="avatar-img"></div>
                        <div class="profile-info"><h1>{{ Auth::user()->fName }} {{ Auth::user()->lName }}</h1><p>{{ Auth::user()->email }} <br> {{ Auth::user()->mobNumber }}</p></div>
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