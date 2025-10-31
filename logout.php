<?php
session_start();
session_destroy();

// إعادة التوجيه للصفحة الرئيسية
header('Location: login.php');
exit;
?>