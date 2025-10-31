<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// الحصول على إحصائيات المستخدم
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$orders_count = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$pending_orders = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'completed'");
$stmt->execute([$user_id]);
$completed_orders = $stmt->fetchColumn();

// الحصول على آخر الطلبات
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$user_id]);
$recent_orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - DevStore</title>
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
                <a href="dashboard.php" class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>الرئيسية</span>
                </a>
                <a href="orders.php" class="nav-item">
                    <i class="fas fa-shopping-cart"></i>
                    <span>طلباتي</span>
                    <span class="badge"><?php echo $orders_count; ?></span>
                </a>
                <a href="services.php" class="nav-item">
                    <i class="fas fa-laptop-code"></i>
                    <span>الخدمات</span>
                </a>
                <a href="profile.php" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>الملف الشخصي</span>
                </a>
                <a href="support.php" class="nav-item">
                    <i class="fas fa-headset"></i>
                    <span>الدعم الفني</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $orders_count; ?></h3>
                        <p>إجمالي الطلبات</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon pending">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $pending_orders; ?></h3>
                        <p>طلبات قيد التنفيذ</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon completed">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?php echo $completed_orders; ?></h3>
                        <p>طلبات مكتملة</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon revenue">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>0 ₪</h3>
                        <p>إجمالي المشتريات</p>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="dashboard-section">
                <div class="section-header">
                    <h2>آخر الطلبات</h2>
                    <a href="orders.php" class="view-all">عرض الكل <i class="fas fa-arrow-left"></i></a>
                </div>
                
                <div class="orders-table">
                    <?php if (empty($recent_orders)): ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>لا توجد طلبات بعد</h3>
                        <p>يمكنك طلب خدمة جديدة من خلال تصفح خدماتنا</p>
                        <a href="services.php" class="btn btn-primary">استعرض الخدمات</a>
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>رقم الطلب</th>
                                    <th>الخدمة</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><?php echo get_service_name($order['service_type']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo $order['status']; ?>">
                                            <?php echo get_status_text($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline">
                                            <i class="fas fa-eye"></i> عرض
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-section">
                <h2>إجراءات سريعة</h2>
                <div class="quick-actions">
                    <a href="order.php" class="quick-action-card">
                        <i class="fas fa-plus-circle"></i>
                        <span>طلب خدمة جديدة</span>
                    </a>
                    <a href="services.php" class="quick-action-card">
                        <i class="fas fa-laptop-code"></i>
                        <span>استعراض الخدمات</span>
                    </a>
                    <a href="support.php" class="quick-action-card">
                        <i class="fas fa-headset"></i>
                        <span>اتصل بالدعم</span>
                    </a>
                    <a href="profile.php" class="quick-action-card">
                        <i class="fas fa-user-edit"></i>
                        <span>تعديل الملف الشخصي</span>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script src="js/dashboard.js"></script>
</body>
</html>