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
$status_filter = $_GET['status'] ?? 'all';

// بناء الاستعلام بناءً على عامل التصفية
$query = "SELECT * FROM orders WHERE user_id = ?";
$params = [$user_id];

if ($status_filter !== 'all') {
    $query .= " AND status = ?";
    $params[] = $status_filter;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll();

// إحصائيات الطلبات
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ?");
$stmt->execute([$user_id]);
$stats_all = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);
$stats_pending = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'in_progress'");
$stmt->execute([$user_id]);
$stats_in_progress = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = ? AND status = 'completed'");
$stmt->execute([$user_id]);
$stats_completed = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي - DevStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .filters-section {
            background: var(--white);
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        
        .filter-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e1e5e9;
            background: var(--white);
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .filter-btn:hover {
            border-color: var(--secondary-color);
        }
        
        .filter-btn.active {
            background: var(--secondary-color);
            color: var(--white);
            border-color: var(--secondary-color);
        }
        
        .filter-btn .count {
            background: rgba(255,255,255,0.2);
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.8rem;
        }
        
        .text-muted {
            color: #666;
        }
    </style>
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
                <a href="orders.php" class="nav-item active">
                    <i class="fas fa-shopping-cart"></i>
                    <span>طلباتي</span>
                    <span class="badge"><?php echo $stats_all; ?></span>
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
                <h1><i class="fas fa-shopping-cart"></i> طلباتي</h1>
                <a href="order.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> طلب جديد
                </a>
            </div>

            <!-- Filters -->
            <div class="filters-section">
                <div class="filter-buttons">
                    <button class="filter-btn <?php echo $status_filter === 'all' ? 'active' : ''; ?>" onclick="filterOrders('all')">
                        الكل <span class="count">(<?php echo $stats_all; ?>)</span>
                    </button>
                    <button class="filter-btn <?php echo $status_filter === 'pending' ? 'active' : ''; ?>" onclick="filterOrders('pending')">
                        قيد المراجعة <span class="count">(<?php echo $stats_pending; ?>)</span>
                    </button>
                    <button class="filter-btn <?php echo $status_filter === 'in_progress' ? 'active' : ''; ?>" onclick="filterOrders('in_progress')">
                        قيد التنفيذ <span class="count">(<?php echo $stats_in_progress; ?>)</span>
                    </button>
                    <button class="filter-btn <?php echo $status_filter === 'completed' ? 'active' : ''; ?>" onclick="filterOrders('completed')">
                        مكتملة <span class="count">(<?php echo $stats_completed; ?>)</span>
                    </button>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="dashboard-section">
                <?php if (empty($orders)): ?>
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>لا توجد طلبات</h3>
                    <p>ابدأ بطلب خدمتك الأولى من متجرنا</p>
                    <a href="services.php" class="btn btn-primary">استعرض الخدمات</a>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>رقم الطلب</th>
                                <th>الخدمة</th>
                                <th>المشروع</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['id']; ?></td>
                                <td><?php echo get_service_name($order['service_type']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($order['project_title']); ?></strong>
                                    <br>
                                    <small class="text-muted">
                                        <?php 
                                        $description = $order['project_description'];
                                        echo strlen($description) > 50 ? substr($description, 0, 50) . '...' : $description;
                                        ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $order['status']; ?>">
                                        <?php echo get_status_text($order['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($order['status'] === 'pending'): ?>
                                        <a href="edit-order.php?id=<?php echo $order['id']; ?>" class="btn btn-sm btn-outline" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function filterOrders(status) {
            window.location.href = 'orders.php?status=' + status;
        }
    </script>

    <script src="js/dashboard.js"></script>
</body>
</html>