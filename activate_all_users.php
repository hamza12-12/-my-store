<?php
require_once 'includes/config.php';

// تفعيل جميع المستخدمين
$pdo->exec("UPDATE users SET is_verified = TRUE");
echo "All users activated successfully!";
echo "<br><a href='login.php'>Go to Login</a>";
?>