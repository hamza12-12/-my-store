<?php
require_once 'config.php';
require_once 'init_database.php';

// تنظيف بيانات الإدخال
function clean_input($data) {
    if (empty($data)) return '';
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// إنشاء كود التحقق
function generate_verification_code($length = 6) {
    return strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length));
}

// الحصول على اسم الخدمة
function get_service_name($service_type) {
    $services = [
        'web-design' => 'تصميم مواقع الويب',
        'ecommerce' => 'متاجر إلكترونية', 
        'mobile-app' => 'تطبيقات الجوال',
        'custom' => 'برمجة مخصصة',
        'seo' => 'تحسين محركات البحث',
        'maintenance' => 'صيانة المواقع'
    ];
    
    return $services[$service_type] ?? 'خدمة غير معروفة';
}

// الحصول على نص الحالة
function get_status_text($status) {
    $statuses = [
        'pending' => 'قيد المراجعة',
        'approved' => 'مقبول',
        'in_progress' => 'قيد التنفيذ', 
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي'
    ];
    
    return $statuses[$status] ?? 'غير معروف';
}

// التحقق من صحة البريد الإلكتروني
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// تسجيل خطأ
function log_error($message) {
    error_log("[" . date('Y-m-d H:i:s') . "] " . $message . "\n", 3, "logs/error.log");
}

// إعادة توجيه
function redirect($url) {
    header("Location: $url");
    exit;
}

// التحقق من تسجيل الدخول
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
        redirect('login.php');
    }
}

// تسجيل الخروج
function logout() {
    session_destroy();
    redirect('login.php');
}
?>