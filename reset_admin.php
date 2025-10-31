<?php
require_once 'includes/config.php';

try {
    // إعادة تعيين كلمة مرور المدير
    $new_password = '123456';
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = 'admin@devstore.com'");
    $stmt->execute([$hashed_password]);
    
    echo "✅ تم إعادة تعيين كلمة مرور المدير بنجاح!";
    echo "<br>البريد: admin@devstore.com";
    echo "<br>كلمة المرور الجديدة: 123456";
    echo "<br><a href='login.php'>اذهب لتسجيل الدخول</a>";
    
} catch (PDOException $e) {
    echo "❌ خطأ: " . $e->getMessage();
}
?>