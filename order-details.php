<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// التحقق من تسجيل الدخول
require_login();

$user_id = $_SESSION['user_id'];
$order_id = $_GET['id'] ?? 0;

// جلب بيانات الطلب
$stmt = $pdo->prepare("SELECT o.*, u.name as user_name, u.email as user_email 
                      FROM orders o 
                      LEFT JOIN users u ON o.user_id = u.id 
                      WHERE o.id = ? AND (o.user_id = ? OR ? = (SELECT id FROM users WHERE role = 'admin' LIMIT 1))");
$stmt->execute([$order_id, $user_id, $user_id]);
$order = $stmt->fetch();

if (!$order) {
    header('Location: orders.php');
    exit;
}

// جلب المحادثات
$conversations_stmt = $pdo->prepare("SELECT * FROM conversations WHERE order_id = ? ORDER BY created_at ASC");
$conversations_stmt->execute([$order_id]);
$conversations = $conversations_stmt->fetchAll();

// إضافة رسالة جديدة
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = clean_input($_POST['message']);
    $is_admin = $_SESSION['user_role'] === 'admin';
    
    if (!empty($message)) {
        $stmt = $pdo->prepare("INSERT INTO conversations (order_id, user_id, message, is_admin, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$order_id, $user_id, $message, $is_admin]);
        
        // تحديث حالة الطلب إذا كان المدير
        if ($is_admin && isset($_POST['status'])) {
            $new_status = clean_input($_POST['status']);
            $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?")->execute([$new_status, $order_id]);
            $order['status'] = $new_status;
        }
        
        header("Location: order-details.php?id=$order_id");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الطلب #<?php echo $order_id; ?> - DevStore</title>
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
                        <a href="profile.php"><i class="fas fa-user"></i> الملف الشخصي</a>
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
                <a href="profile.php" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>الملف الشخصي</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="section-header">
                <div>
                    <h1><i class="fas fa-file-alt"></i> تفاصيل الطلب #<?php echo $order_id; ?></h1>
                    <p class="order-status">
                        الحالة: 
                        <span class="status-badge status-<?php echo $order['status']; ?>">
                            <?php echo get_status_text($order['status']); ?>
                        </span>
                    </p>
                </div>
                <a href="orders.php" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> العودة للطلبات
                </a>
            </div>

            <div class="order-details-grid">
                <!-- معلومات الطلب -->
                <div class="order-info-card">
                    <h3><i class="fas fa-info-circle"></i> معلومات الطلب</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>نوع الخدمة:</label>
                            <span><?php echo get_service_name($order['service_type']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>عنوان المشروع:</label>
                            <span><?php echo htmlspecialchars($order['project_title']); ?></span>
                        </div>
                        <div class="info-item">
                            <label>الميزانية:</label>
                            <span><?php echo $order['budget'] ? $order['budget'] . ' ₪' : 'غير محدد'; ?></span>
                        </div>
                        <div class="info-item">
                            <label>الموعد النهائي:</label>
                            <span><?php echo $order['deadline'] ?: 'غير محدد'; ?></span>
                        </div>
                        <div class="info-item">
                            <label>تاريخ الطلب:</label>
                            <span><?php echo date('Y-m-d H:i', strtotime($order['created_at'])); ?></span>
                        </div>
                    </div>
                </div>

                <!-- وصف المشروع -->
                <div class="order-description-card">
                    <h3><i class="fas fa-file-alt"></i> وصف المشروع</h3>
                    <div class="description-content">
                        <?php echo nl2br(htmlspecialchars($order['project_description'])); ?>
                    </div>
                    
                    <?php if (!empty($order['special_requirements'])): ?>
                    <div class="special-requirements">
                        <h4><i class="fas fa-star"></i> المتطلبات الخاصة</h4>
                        <p><?php echo nl2br(htmlspecialchars($order['special_requirements'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- المحادثات -->
                <div class="conversations-card">
                    <h3><i class="fas fa-comments"></i> المحادثات</h3>
                    
                    <div class="conversations-list">
                        <?php if (empty($conversations)): ?>
                        <div class="empty-conversation">
                            <i class="fas fa-comment-slash"></i>
                            <p>لا توجد محادثات بعد</p>
                        </div>
                        <?php else: ?>
                            <?php foreach ($conversations as $conversation): ?>
                            <div class="message <?php echo $conversation['is_admin'] ? 'admin-message' : 'user-message'; ?>">
                                <div class="message-header">
                                    <span class="sender">
                                        <?php echo $conversation['is_admin'] ? 'الدعم الفني' : htmlspecialchars($order['user_name']); ?>
                                    </span>
                                    <span class="time">
                                        <?php echo date('H:i - Y/m/d', strtotime($conversation['created_at'])); ?>
                                    </span>
                                </div>
                                <div class="message-content">
                                    <?php echo nl2br(htmlspecialchars($conversation['message'])); ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- نموذج إرسال رسالة -->
                    <form method="POST" class="message-form">
                        <div class="form-group">
                            <textarea name="message" class="form-control" placeholder="اكتب رسالتك هنا..." rows="3" required></textarea>
                        </div>
                        
                        <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <div class="admin-controls">
                            <div class="form-group">
                                <label>تغيير حالة الطلب:</label>
                                <select name="status" class="form-control">
                                    <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>قيد المراجعة</option>
                                    <option value="approved" <?php echo $order['status'] === 'approved' ? 'selected' : ''; ?>>مقبول</option>
                                    <option value="in_progress" <?php echo $order['status'] === 'in_progress' ? 'selected' : ''; ?>>قيد التنفيذ</option>
                                    <option value="completed" <?php echo $order['status'] === 'completed' ? 'selected' : ''; ?>>مكتمل</option>
                                    <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>ملغي</option>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> إرسال الرسالة
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>