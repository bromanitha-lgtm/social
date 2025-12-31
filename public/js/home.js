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
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('themeToggle');
            
            // Get elements using querySelector since IDs might have issues
            const themeIcon = themeToggle.querySelector('i');
            const themeText = themeToggle.querySelector('span');
            
            // Check saved theme
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
            updateThemeButton(savedTheme);
            
            themeToggle.addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                document.documentElement.setAttribute('data-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeButton(newTheme);
                
                // Add animation
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
        });

        // 3. COUNTERS
        function animateCounters() {
            const counters = document.querySelectorAll('[data-target]');
            counters.forEach(counter => {
                const target = parseInt(counter.getAttribute('data-target'));
                // Validate target is a number
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
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            let icon = 'fa-info-circle'; if (type === 'success') icon = 'fa-check-circle'; if (type === 'error') icon = 'fa-exclamation-circle';
            toast.innerHTML = `<i class="fas ${icon}"></i> <span>${message}</span>`;
            container.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(() => { toast.classList.remove('show'); setTimeout(() => toast.remove(), 400); }, 3000);
        }

        // 5. CHARTS
        function drawChart(canvasId, dataPoints) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            const parent = canvas.parentElement;
            
            // Fix canvas resolution
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
                ctx.beginPath(); ctx.arc(p.x, p.y, 4, 0, Math.PI * 2); ctx.fillStyle = '#fff'; ctx.fill(); ctx.strokeStyle = primaryColor; ctx.lineWidth = 2; ctx.stroke();
            });
        }

        function updateChartData() { showToast('Updating chart data...', 'normal'); setTimeout(() => { const newData = Array.from({length: 10}, () => Math.floor(Math.random() * 80) + 20); drawChart('smartChart', newData); }, 500); }

        // 6. ACTIVITY
        const activities = [ { user: 'Sarah J.', action: 'liked your project', time: '2m ago' }, { user: 'Mike T.', action: 'sent a message', time: '15m ago' }, { user: 'Design Daily', action: 'featured your work', time: '1h ago' } ];
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

        document.addEventListener('DOMContentLoaded', () => {
            animateCounters();
            renderActivity();
            drawChart('smartChart', [20, 45, 30, 60, 55, 80, 70, 90, 85, 100]);
            
            // Handle Resize
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if(document.getElementById('dashboard').classList.contains('active-section')) drawChart('smartChart', [20, 45, 30, 60, 55, 80, 70, 90, 85, 100]);
                    if(document.getElementById('analytics').classList.contains('active-section')) drawChart('secondaryChart', [40, 30, 60, 50, 70, 40, 80]);
                }, 200);
            });
        });