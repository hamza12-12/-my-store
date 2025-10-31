<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// التحقق من تسجيل الدخول
require_login();

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

// جلب بيانات المستخدم
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // التحقق من البيانات الأساسية
    if (empty($name)) {
        $errors[] = 'الاسم مطلوب';
    }
    
    if (empty($email) || !is_valid_email($email)) {
        $errors[] = 'البريد الإلكتروني غير صحيح';
    }
    
    // التحقق من عدم تكرار البريد الإلكتروني
    if ($email !== $user['email']) {
        $check_stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check_stmt->execute([$email, $user_id]);
        if ($check_stmt->fetch()) {
            $errors[] = 'البريد الإلكتروني مسجل مسبقاً';
        }
    }
    
    // التحقق من كلمة المرور إذا تم تقديمها
    if (!empty($current_password)) {
        if (!password_verify($current_password, $user['password'])) {
            $errors[] = 'كلمة المرور الحالية غير صحيحة';
        } elseif (empty($new_password) || strlen($new_password) < 6) {
            $errors[] = 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل';
        } elseif ($new_password !== $confirm_password) {
            $errors[] = 'كلمات المرور غير متطابقة';
        }
    }
    
    if (empty($errors)) {
        try {
            // بناء استعلام التحديث
            $update_data = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone
            ];
            
            $update_sql = "UPDATE users SET name = :name, email = :email, phone = :phone";
            
            // إذا كانت هناك كلمة مرور جديدة
            if (!empty($new_password)) {
                $update_data['password'] = password_hash($new_password, PASSWORD_DEFAULT);
                $update_sql .= ", password = :password";
            }
            
            $update_sql .= " WHERE id = :id";
            $update_data['id'] = $user_id;
            
            $stmt = $pdo->prepare($update_sql);
            $stmt->execute($update_data);
            
            // تحديث بيانات الجلسة
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            
            $success = 'تم تحديث الملف الشخصي بنجاح';
            
        } catch (PDOException $e) {
            $errors[] = 'حدث خطأ أثناء تحديث البيانات: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الملف الشخصي - DevStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Dashboard Header -->
    <header class="dashboard-header">
        <nav>
            <div class="logo">Dev<span>Store</span></div>
            <div class="user-menu">
                <span class="user-welcome">مرحباً, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <div class="dropdown">
                    <button class="dropdown-toggle">
                        <i class="fas fa-user-circle"></i>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="profile.php" class="active"><i class="fas fa-user"></i> الملف الشخصي</a>
                        <a href="settings.php"><i class="fas fa-cog"></i> الإعدادات</a>
                        <div class="dropdown-divider"></div>
                        <a href="logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> تسجيل الخروج</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <a href="dashboard.php" class="nav-item">
                    <i class="fas fa-home"></i>
                    <span>الرئيسية</span>
                </a>
                <a href="orders.php" class="nav-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>طلباتي</span>
                </a>
                <a href="services.php" class="nav-item">
                    <i class="fas fa-laptop-code"></i>
                    <span>الخدمات</span>
                </a>
                <a href="profile.php" class="nav-item active">
                    <i class="fas fa-user"></i>
                    <span>الملف الشخصي</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="section-header">
                <h1><i class="fas fa-user"></i> الملف الشخصي</h1>
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

            <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
            </div>
            <?php endif; ?>

            <div class="profile-section">
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="profile-info">
                            <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                            <p><?php echo htmlspecialchars($user['email']); ?></p>
                            <span class="member-since">عضو منذ <?php echo date('Y-m-d', strtotime($user['created_at'])); ?></span>
                        </div>
                    </div>

                    <form method="POST" class="profile-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name"><i class="fas fa-user"></i> الاسم الكامل</label>
                                <input type="text" id="name" name="name" class="form-control" 
                                       value="<?php echo htmlspecialchars($user['name']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone"><i class="fas fa-phone"></i> رقم الهاتف</label>
                            <input type="tel" id="phone" name="phone" class="form-control"
                                   value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>

                        <div class="form-section">
                            <h3><i class="fas fa-lock"></i> تغيير كلمة المرور</h3>
                            <p class="section-description">اترك الحقول فارغة إذا لم ترد تغيير كلمة المرور</p>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="current_password">كلمة المرور الحالية</label>
                                    <input type="password" id="current_password" name="current_password" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="new_password">كلمة المرور الجديدة</label>
                                    <input type="password" id="new_password" name="new_password" class="form-control" minlength="6">
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirm_password">تأكيد كلمة المرور</label>
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ التغييرات
                            </button>
                            <a href="dashboard.php" class="btn btn-outline">إلغاء</a>
                        </div>
                    </form>
                </div>

                <div class="profile-stats">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?")->fetchColumn([$user_id]); ?></h3>
                            <p>إجمالي الطلبات</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon completed">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?php echo $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'completed'")->fetchColumn([$user_id]); ?></h3>
                            <p>طلبات مكتملة</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>