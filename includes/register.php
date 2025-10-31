if (empty($errors)) {
    // تسجيل المستخدم - تفعيل تلقائي بدون تحقق بالبريد
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, is_verified, created_at) VALUES (?, ?, ?, TRUE, NOW())");
    
    if ($stmt->execute([$name, $email, $hashed_password])) {
        $_SESSION['success'] = 'تم إنشاء الحساب بنجاح. يمكنك تسجيل الدخول الآن.';
        header('Location: login.php');
        exit;
    } else {
        $errors[] = 'حدث خطأ أثناء إنشاء الحساب';
    }
}