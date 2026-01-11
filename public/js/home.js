// 1. NAVIGATION
const navItems = document.querySelectorAll('.nav-item[data-target]');
const sections = document.querySelectorAll('.page-section');
const sidebar = document.getElementById('sidebar');

navItems.forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        navItems.forEach(nav => nav.classList.remove('active'));
        item.classList.add('active');
        sections.forEach(sec => sec.classList.remove('active-section'));
        const targetId = item.getAttribute('data-target');
        document.getElementById(targetId).classList.add('active-section');
        if (window.innerWidth <= 768) sidebar.classList.remove('active');
        // Trigger chart redraw
        setTimeout(() => {
            if(targetId === 'dashboard') drawChart('smartChart', [20, 45, 30, 60, 55, 80, 70, 90, 85, 100]);
            if(targetId === 'analytics') drawChart('secondaryChart', [40, 30, 60, 50, 70, 40, 80]);
        }, 50);
    });
});

function toggleSidebar() { sidebar.classList.toggle('active'); }

// 2. THEME TOGGLE
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    
    if (themeToggle) {
        const themeIcon = themeToggle.querySelector('i');
        const themeText = themeToggle.querySelector('span');
        
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);
        updateThemeButton(savedTheme);
        
        themeToggle.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeButton(newTheme);
            
            this.classList.add('theme-changing');
            setTimeout(() => {
                this.classList.remove('theme-changing');
            }, 300);
        });
        
        function updateThemeButton(theme) {
            if (theme === 'dark') {
                themeIcon.className = 'fas fa-sun';
                themeText.textContent = 'Light Mode';
            } else {
                themeIcon.className = 'fas fa-moon';
                themeText.textContent = 'Dark Mode';
            }
        }
    }
    
    // Initialize live search
    initializeLiveSearch();
});

// 3. COUNTERS
function animateCounters() {
    const counters = document.querySelectorAll('[data-target]');
    counters.forEach(counter => {
        const target = parseInt(counter.getAttribute('data-target'));
        if (isNaN(target)) {
            console.warn('Invalid data-target value:', counter.getAttribute('data-target'));
            return;
        }
        
        const duration = 2000; 
        const increment = target / (duration / 16); 
        let current = 0;
        const updateCounter = () => {
            current += increment;
            if (current < target) { 
                counter.innerText = Math.ceil(current).toLocaleString(); 
                requestAnimationFrame(updateCounter); 
            }
            else { 
                counter.innerText = target.toLocaleString(); 
            }
        };
        updateCounter();
    });
}

// 4. TOAST
function showToast(message, type = 'normal') {
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 10px;';
        document.body.appendChild(container);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    let icon = 'fa-info-circle'; 
    if (type === 'success') icon = 'fa-check-circle'; 
    if (type === 'error') icon = 'fa-exclamation-circle';
    toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
    container.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => { 
        toast.classList.remove('show'); 
        setTimeout(() => toast.remove(), 400); 
    }, 3000);
}

// 5. CHARTS
function drawChart(canvasId, dataPoints) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const parent = canvas.parentElement;
    
    canvas.width = parent.offsetWidth;
    canvas.height = parent.offsetHeight;

    const w = canvas.width;
    const h = canvas.height;
    const padding = 20;
    
    ctx.clearRect(0, 0, w, h);
    
    const gradient = ctx.createLinearGradient(0, 0, 0, h);
    const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--primary-color').trim();
    gradient.addColorStop(0, primaryColor + '80'); 
    gradient.addColorStop(1, primaryColor + '05'); 

    ctx.beginPath();
    ctx.moveTo(padding, h - padding);

    const stepX = (w - padding * 2) / (dataPoints.length - 1);
    let points = [];
    
    dataPoints.forEach((val, i) => {
        points.push({ x: padding + i * stepX, y: h - padding - (val / 100) * (h - padding * 2) });
    });

    if (points.length > 0) {
        ctx.lineTo(points[0].x, points[0].y);
        for (let i = 0; i < points.length - 1; i++) {
            const xc = (points[i].x + points[i + 1].x) / 2;
            const yc = (points[i].y + points[i + 1].y) / 2;
            ctx.quadraticCurveTo(points[i].x, points[i].y, xc, yc);
        }
        ctx.lineTo(points[points.length - 1].x, points[points.length - 1].y);
    }

    ctx.strokeStyle = primaryColor;
    ctx.lineWidth = 4;
    ctx.lineCap = 'round';
    ctx.lineJoin = 'round';
    ctx.stroke();
    
    ctx.lineTo(points[points.length - 1].x, h - padding);
    ctx.lineTo(padding, h - padding);
    ctx.closePath();
    ctx.fillStyle = gradient;
    ctx.fill();

    points.forEach(p => {
        ctx.beginPath(); 
        ctx.arc(p.x, p.y, 4, 0, Math.PI * 2); 
        ctx.fillStyle = '#fff'; 
        ctx.fill(); 
        ctx.strokeStyle = primaryColor; 
        ctx.lineWidth = 2; 
        ctx.stroke();
    });
}

function updateChartData() { 
    showToast('Updating chart data...', 'normal'); 
    setTimeout(() => { 
        const newData = Array.from({length: 10}, () => Math.floor(Math.random() * 80) + 20); 
        drawChart('smartChart', newData); 
    }, 500); 
}

// 6. ACTIVITY
const activities = [ 
    { user: 'Sarah J.', action: 'liked your project', time: '2m ago' }, 
    { user: 'Mike T.', action: 'sent a message', time: '15m ago' }, 
    { user: 'Design Daily', action: 'featured your work', time: '1h ago' } 
];

function renderActivity() {
    const list = document.getElementById('activityList');
    if(!list) return;
    list.innerHTML = activities.map((act, index) => `
        <div class="activity-item">
            <div class="activity-dot"></div>
            <div class="activity-content"><h4 style="font-size: 0.95rem;">${act.user}</h4><span style="font-size: 0.8rem; color: var(--text-muted);">${act.action}</span></div>
            <span style="font-size: 0.75rem; color: var(--gray-color); margin-left: auto;">${act.time}</span>
        </div>
    `).join('');
}

// 7. LIVE SEARCH FUNCTIONALITY
function initializeLiveSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const searchResultsBody = document.getElementById('searchResultsBody');
    const resultsCount = document.getElementById('resultsCount');
    const viewAllResults = document.getElementById('viewAllResults');
    const searchWrapper = document.getElementById('searchWrapper');
    
    if (!searchInput || !searchWrapper) {
        console.log('Search elements not found');
        return;
    }
    
    let searchTimeout;
    let currentSearchQuery = '';
    let isSearching = false;
    
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        currentSearchQuery = query;
        
        clearTimeout(searchTimeout);
        
        if (query.length === 0) {
            searchResults.style.display = 'none';
            return;
        }
        
        if (query.length >= 2 && !isSearching) {
            searchResultsBody.innerHTML = `
                <div class="search-loading">
                    <div class="spinner"></div>
                    <p>Searching for "${query}"...</p>
                </div>
            `;
            searchResults.style.display = 'block';
            
            searchTimeout = setTimeout(() => {
                performLiveSearch(query);
            }, 500);
        }
    });
    
    document.addEventListener('click', function(e) {
        if (searchWrapper && !searchWrapper.contains(e.target)) {
            searchResults.style.display = 'none';
        }
    });
    
    function performLiveSearch(query) {
        if (query.length < 2 || isSearching) {
            return;
        }
        
        isSearching = true;
        console.log('Searching for:', query);
        
        const searchUrl = `/search/live?q=${encodeURIComponent(query)}&_=${Date.now()}`;
        
        fetch(searchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Cache-Control': 'no-cache'
            },
            cache: 'no-store'
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Search response:', data);
            
            if (query !== currentSearchQuery) {
                return;
            }
            
            if (data.success) {
                renderSearchResults(data);
            } else {
                showSearchError(data.message || 'Search failed');
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            
            if (query !== currentSearchQuery) {
                return;
            }
            
            showSearchError('Search failed. Please try again.');
        })
        .finally(() => {
            isSearching = false;
        });
    }
    
    function renderSearchResults(data) {
        const { users, posts, total_results, query } = data;
        
        resultsCount.textContent = `${total_results} results for "${query}"`;
        
        if (total_results === 0) {
            searchResultsBody.innerHTML = `
                <div class="no-results">
                    <i class="far fa-search"></i>
                    <p>No results found for "${query}"</p>
                    <small>Try different keywords</small>
                </div>
            `;
            viewAllResults.style.display = 'none';
            return;
        }
        
        let html = '';
        
        if (users.length > 0) {
            users.forEach(user => {
                html += `
                    <div class="search-result-item user" data-url="${user.url}">
                        <img src="${user.avatar_url}" alt="${user.name}" class="search-result-avatar" 
                             onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=random&color=fff&size=60'">
                        <div class="search-result-content">
                            <span class="search-result-title">${user.name}</span>
                            <span class="search-result-subtitle">${user.email}</span>
                            <div class="search-result-meta">
                                <span><i class="fas fa-pen"></i> ${user.posts_count || 0} posts</span>
                                <span><i class="fas fa-users"></i> ${user.followers_count || 0} followers</span>
                            </div>
                        </div>
                        <span class="search-result-type user">User</span>
                    </div>
                `;
            });
        }
        
        if (posts.length > 0) {
            posts.forEach(post => {
                html += `
                    <div class="search-result-item post" data-url="${post.url}">
                        <img src="${post.user_avatar}" alt="${post.user_name}" class="search-result-avatar"
                             onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(post.user_name)}&background=random&color=fff&size=60'">
                        <div class="search-result-content">
                            <span class="search-result-title">${post.title}</span>
                            <span class="search-result-subtitle">${post.excerpt}</span>
                            <div class="search-result-meta">
                                <span><i class="fas fa-user"></i> ${post.user_name}</span>
                                <span><i class="fas fa-heart"></i> ${post.likes_count || 0}</span>
                                <span><i class="fas fa-comment"></i> ${post.comments_count || 0}</span>
                            </div>
                        </div>
                        <span class="search-result-type post">Post</span>
                    </div>
                `;
            });
        }
        
        searchResultsBody.innerHTML = html;
        
        if (total_results > 0) {
            viewAllResults.style.display = 'block';
            viewAllResults.href = `/search?q=${encodeURIComponent(query)}`;
        } else {
            viewAllResults.style.display = 'none';
        }
        
        document.querySelectorAll('.search-result-item').forEach(item => {
            item.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                if (url && url !== '#') {
                    window.location.href = url;
                }
            });
        });
    }
    
    function showSearchError(message) {
        searchResultsBody.innerHTML = `
            <div class="no-results">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
            </div>
        `;
        viewAllResults.style.display = 'none';
    }
    
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && currentSearchQuery.length >= 2) {
            e.preventDefault();
            window.location.href = `/search?q=${encodeURIComponent(currentSearchQuery)}`;
        }
        
        if (e.key === 'Escape') {
            searchResults.style.display = 'none';
            searchInput.blur();
        }
    });
    
    searchInput.addEventListener('focus', function() {
        if (currentSearchQuery.length >= 2 && searchResultsBody.children.length > 0) {
            searchResults.style.display = 'block';
        }
    });
}

// 8. INITIALIZE EVERYTHING
document.addEventListener('DOMContentLoaded', () => {
    console.log('Home page initialized');
    
    animateCounters();
    renderActivity();
    drawChart('smartChart', [20, 45, 30, 60, 55, 80, 70, 90, 85, 100]);
    
    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            if(document.getElementById('dashboard') && document.getElementById('dashboard').classList.contains('active-section')) {
                drawChart('smartChart', [20, 45, 30, 60, 55, 80, 70, 90, 85, 100]);
            }
            if(document.getElementById('analytics') && document.getElementById('analytics').classList.contains('active-section')) {
                drawChart('secondaryChart', [40, 30, 60, 50, 70, 40, 80]);
            }
        }, 200);
    });
});