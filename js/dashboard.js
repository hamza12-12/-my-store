// dashboard.js - لوحة التحكم

document.addEventListener('DOMContentLoaded', function() {
    initDashboard();
    initCharts();
    initOrderFilters();
    initStatsCounter();
});

// تهيئة لوحة التحكم
function initDashboard() {
    initSidebar();
    initUserMenu();
    initQuickActions();
}

// تهيئة الشريط الجانبي
function initSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const navItems = document.querySelectorAll('.nav-item');
    
    // تحديد العنصر النشط بناءً على الصفحة الحالية
    const currentPage = window.location.pathname.split('/').pop();
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && href.includes(currentPage)) {
            item.classList.add('active');
        }
    });
    
    // إضافة زر تصغير الشريط الجانبي للشاشات الصغيرة
    if (window.innerWidth < 768) {
        const toggleBtn = document.createElement('button');
        toggleBtn.className = 'sidebar-toggle';
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
        toggleBtn.addEventListener('click', toggleSidebar);
        
        document.querySelector('.dashboard-header nav').appendChild(toggleBtn);
    }
}

// تبديل الشريط الجانبي
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const main = document.querySelector('.dashboard-main');
    
    sidebar.classList.toggle('collapsed');
    main.classList.toggle('expanded');
}

// تهيئة قائمة المستخدم
function initUserMenu() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const menu = this.parentNode.querySelector('.dropdown-menu');
            menu.classList.toggle('active');
        });
    });
    
    // إغلاق القوائم المنسدلة عند النقر خارجها
    document.addEventListener('click', function() {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            menu.classList.remove('active');
        });
    });
}

// تهيئة الإجراءات السريعة
function initQuickActions() {
    const quickActions = document.querySelectorAll('.quick-action-card');
    
    quickActions.forEach(action => {
        action.addEventListener('click', function(e) {
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
                utils.showNotification('هذه الميزة قيد التطوير', 'info');
            }
        });
    });
}

// تهيئة المخططات الإحصائية
function initCharts() {
    // مخطط الإحصائيات البسيط
    const statsCtx = document.getElementById('statsChart');
    if (statsCtx) {
        const statsChart = new Chart(statsCtx, {
            type: 'bar',
            data: {
                labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
                datasets: [{
                    label: 'الطلبات',
                    data: [12, 19, 8, 15, 12, 18],
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
}

// تهيئة عوامل تصفية الطلبات
function initOrderFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const orderRows = document.querySelectorAll('.orders-table tbody tr');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const status = this.getAttribute('data-status');
            
            // تحديث الأزرار النشطة
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // تصفية الصفوف
            orderRows.forEach(row => {
                if (status === 'all' || row.querySelector('.status-badge').classList.contains(`status-${status}`)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
}

// عداد الإحصائيات
function initStatsCounter() {
    const statNumbers = document.querySelectorAll('.stat-info h3');
    
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent);
        const duration = 2000; // مدة العد بالمللي ثانية
        const step = target / (duration / 16); // 60 FPS
        
        let current = 0;
        const timer = setInterval(() => {
            current += step;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            stat.textContent = Math.floor(current);
        }, 16);
    });
}

// تحديث الإحصائيات في الوقت الفعلي
function updateStats() {
    // يمكن جلب البيانات من API هنا
    console.log('Updating dashboard stats...');
}

// تحديث كل 30 ثانية
setInterval(updateStats, 30000);

// إدارة حالة التحميل
const dashboardLoader = {
    show: function() {
        const loader = document.createElement('div');
        loader.className = 'dashboard-loader';
        loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        document.querySelector('.dashboard-main').appendChild(loader);
    },
    
    hide: function() {
        const loader = document.querySelector('.dashboard-loader');
        if (loader) {
            loader.remove();
        }
    }
};

// تصدير للاستخدام العالمي
window.dashboard = {
    toggleSidebar,
    updateStats,
    loader: dashboardLoader
};