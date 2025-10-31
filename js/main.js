// main.js - الملف الرئيسي للجافاسكريبت

document.addEventListener('DOMContentLoaded', function() {
    // تهيئة جميع المكونات
    initNavigation();
    initAnimations();
    initForms();
    initServiceCards();
});

// تهيئة التنقل
function initNavigation() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('active');
        });
    }
    
    // إغلاق القوائم المنسدلة عند النقر خارجها
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
            });
        }
    });
}

// تهيئة الحركات والتحريك
function initAnimations() {
    // تحريك العناصر عند التمرير
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);
    
    // مراقبة العناصر المراد تحريكها
    document.querySelectorAll('.feature-card, .service-card, .stat-card').forEach(el => {
        observer.observe(el);
    });
}

// تهيئة النماذج
function initForms() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> جاري المعالجة...';
            }
        });
    });
    
    // التحقق من كلمات المرور
    const passwordFields = document.querySelectorAll('input[type="password"]');
    passwordFields.forEach(field => {
        field.addEventListener('input', validatePassword);
    });
}

// التحقق من قوة كلمة المرور
function validatePassword(e) {
    const password = e.target.value;
    const strength = calculatePasswordStrength(password);
    const strengthBar = e.target.parentNode.querySelector('.password-strength');
    
    if (!strengthBar) return;
    
    strengthBar.className = 'password-strength ' + strength.class;
    strengthBar.innerHTML = strength.text;
}

// حساب قوة كلمة المرور
function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/)) strength++;
    if (password.match(/[A-Z]/)) strength++;
    if (password.match(/[0-9]/)) strength++;
    if (password.match(/[^a-zA-Z0-9]/)) strength++;
    
    switch(strength) {
        case 0:
        case 1:
            return { class: 'weak', text: 'ضعيفة' };
        case 2:
        case 3:
            return { class: 'medium', text: 'متوسطة' };
        case 4:
            return { class: 'strong', text: 'قوية' };
        case 5:
            return { class: 'very-strong', text: 'قوية جداً' };
        default:
            return { class: 'weak', text: 'ضعيفة' };
    }
}

// تهيئة بطاقات الخدمات
function initServiceCards() {
    const serviceCards = document.querySelectorAll('.service-card');
    
    serviceCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// وظائف مساعدة
const utils = {
    // تنسيق الأرقام
    formatNumber: function(num) {
        return new Intl.NumberFormat('ar-SA').format(num);
    },
    
    // تنسيق التاريخ
    formatDate: function(dateString) {
        const options = { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            calendar: 'islamic-umalqura'
        };
        return new Date(dateString).toLocaleDateString('ar-SA', options);
    },
    
    // إظهار الإشعارات
    showNotification: function(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle"></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);
        
        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    },
    
    // تحميل المحتوى بشكل غير متزامن
    loadContent: async function(url, container) {
        try {
            const response = await fetch(url);
            const html = await response.text();
            container.innerHTML = html;
        } catch (error) {
            console.error('Error loading content:', error);
        }
    }
};

// تصدير الوظائف للاستخدام العالمي
window.utils = utils;