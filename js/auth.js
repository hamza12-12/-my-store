// auth.js - معالجات صفحات المصادقة

document.addEventListener('DOMContentLoaded', function() {
    initAuthForms();
    initPasswordToggle();
    initFormValidation();
});

// تهيئة نماذج المصادقة
function initAuthForms() {
    const loginForm = document.querySelector('.auth-form');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateAuthForm(this)) {
                e.preventDefault();
            }
        });
    }
    
    // التحقق من تطابق كلمات المرور
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword) {
        confirmPassword.addEventListener('input', validatePasswordMatch);
    }
}

// تبديل إظهار/إخفاء كلمة المرور
function initPasswordToggle() {
    const passwordFields = document.querySelectorAll('.password-field');
    
    passwordFields.forEach(field => {
        const input = field.querySelector('input');
        const toggleBtn = field.querySelector('.password-toggle');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.innerHTML = type === 'password' ? 
                    '<i class="fas fa-eye"></i>' : 
                    '<i class="fas fa-eye-slash"></i>';
            });
        }
    });
}

// التحقق من صحة نموذج المصادقة
function validateAuthForm(form) {
    const email = form.querySelector('input[type="email"]');
    const password = form.querySelector('input[type="password"]');
    let isValid = true;
    
    // التحقق من البريد الإلكتروني
    if (email && !isValidEmail(email.value)) {
        showFieldError(email, 'البريد الإلكتروني غير صحيح');
        isValid = false;
    } else {
        clearFieldError(email);
    }
    
    // التحقق من كلمة المرور
    if (password && password.value.length < 6) {
        showFieldError(password, 'كلمة المرور يجب أن تكون 6 أحرف على الأقل');
        isValid = false;
    } else {
        clearFieldError(password);
    }
    
    return isValid;
}

// التحقق من تطابق كلمات المرور
function validatePasswordMatch() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    
    if (password && confirmPassword) {
        if (password.value !== confirmPassword.value && confirmPassword.value !== '') {
            showFieldError(confirmPassword, 'كلمات المرور غير متطابقة');
        } else {
            clearFieldError(confirmPassword);
        }
    }
}

// التحقق من صحة البريد الإلكتروني
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// إظهار خطأ في الحقل
function showFieldError(field, message) {
    clearFieldError(field);
    
    field.style.borderColor = '#e74c3c';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    
    field.parentNode.appendChild(errorDiv);
}

// مسح خطأ الحقل
function clearFieldError(field) {
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    field.style.borderColor = '';
}

// إضافة CSS للرسائل
const authStyles = `
.field-error {
    color: #e74c3c;
    font-size: 0.85rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.password-field {
    position: relative;
}

.password-toggle {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
}

.notification {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%) translateY(-100px);
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 10000;
    transition: transform 0.3s ease;
}

.notification.show {
    transform: translateX(-50%) translateY(0);
}

.notification-success {
    border-right: 4px solid #27ae60;
}

.notification-error {
    border-right: 4px solid #e74c3c;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
`;

// إضافة الأنماط للصفحة
const styleSheet = document.createElement('style');
styleSheet.textContent = authStyles;
document.head.appendChild(styleSheet);