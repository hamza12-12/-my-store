<?php
require_once 'includes/config.php';

try {
    // تفعيل جميع المستخدمين الحاليين
    $pdo->exec("UPDATE users SET is_verified = TRUE WHERE is_verified = FALSE");
    
    echo "✅ تم تفعيل جميع المستخدمين بنجاح!";
    echo "<br><a href='login.php'>اذهب لتسجيل الدخول</a>";
    
} catch (PDOException $e) {
    echo "❌ خطأ: " . $e->getMessage();
}
?>