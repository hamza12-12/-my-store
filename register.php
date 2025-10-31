<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // التحقق من البيانات
    if (empty($name)) {
        $errors[] = 'الاسم مطلوب';
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'البريد الإلكتروني غير صحيح';
    }
    
    if (strlen($password) < 6) {
        $errors[] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    }
    
    if ($password !== $confirm_password) {
        $errors[] = 'كلمات المرور غير متطابقة';
    }
    
    // التحقق من عدم وجود email مكرر
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'البريد الإلكتروني مسجل مسبقاً';
    }
    
    if (empty($errors)) {
        // تسجيل المستخدم - تفعيل تلقائي
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
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إنشاء حساب - DevStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2><i class="fas fa-user-plus"></i> إنشاء حساب جديد</h2>
                <p>انضم إلى مجتمع DevStore وابدأ رحلتك البرمجية</p>
            </div>
            
            <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                    <li><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> الاسم الكامل</label>
                    <input type="text" id="name" name="name" class="form-control" 
                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> كلمة المرور</label>
                    <input type="password" id="password" name="password" class="form-control" required minlength="6">
                </div>
                
                <div class="form-group">
                    <label for="confirm_password"><i class="fas fa-lock"></i> تأكيد كلمة المرور</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-user-plus"></i> إنشاء الحساب
                </button>
            </form>
            
            <div class="auth-footer">
                <p>لديك حساب already? <a href="login.php">سجل الدخول هنا</a></p>
            </div>
        </div>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>