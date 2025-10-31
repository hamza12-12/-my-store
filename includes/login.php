if ($user && password_verify($password, $user['password'])) {
    // تسجيل الدخول الناجح - بدون تحقق من التفعيل
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_role'] = $user['role'];
    
    // تحديث آخر login
    $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
    
    header('Location: dashboard.php');
    exit;
}