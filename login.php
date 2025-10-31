<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// إذا كان المستخدم مسجل الدخول already، توجيه للوحة التحكم
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    
    $errors = [];
    
    if (empty($email) || empty($password)) {
        $errors[] = 'جميع الحقول مطلوبة';
    }
    
    if (empty($errors)) {
        // البحث عن المستخدم
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
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
        } else {
            $errors[] = 'البريد الإلكتروني أو كلمة المرور غير صحيحة';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - DevStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</h2>
                <p>مرحباً بعودتك! يرجى تسجيل الدخول إلى حسابك</p>
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
            
            <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
            <?php endif; ?>
            
            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                    <input type="email" id="email" name="email" class="form-control"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                           required>
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> كلمة المرور</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                
                <div class="form-options">
                    <label class="checkbox">
                        <input type="checkbox" name="remember"> تذكرني
                    </label>
                    <a href="forgot-password.php" class="forgot-password">نسيت كلمة المرور؟</a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-sign-in-alt"></i> تسجيل الدخول
                </button>
            </form>
            
            <div class="auth-footer">
                <p>ليس لديك حساب? <a href="register.php">أنشئ حساب جديد</a></p>
            </div>
        </div>
    </div>

    <script src="js/auth.js"></script>
</body>
</html>