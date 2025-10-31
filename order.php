 <?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

$service_type = $_GET['service'] ?? 'custom';
$user_id = $_SESSION['user_id'];

// معلومات الخدمات
$services = [
    'web-design' => [
        'name' => 'تصميم مواقع الويب',
        'base_price' => 500,
        'description' => 'تصميم موقع ويب احترافي متجاوب مع جميع الأجهزة'
    ],
    'ecommerce' => [
        'name' => 'متاجر إلكترونية', 
        'base_price' => 1500,
        'description' => 'متجر إلكتروني متكامل بأنظمة الدفع والمخزون'
    ],
    'mobile-app' => [
        'name' => 'تطبيقات الجوال',
        'base_price' => 2000,
        'description' => 'تطبيقات أندرويد و iOS بأعلى المعايير'
    ],
    'custom' => [
        'name' => 'برمجة مخصصة',
        'base_price' => 0,
        'description' => 'حل برمجي مخصص حسب متطلباتك'
    ]
];

$selected_service = $services[$service_type] ?? $services['custom'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_type = clean_input($_POST['service_type']);
    $project_title = clean_input($_POST['project_title']);
    $project_description = clean_input($_POST['project_description']);
    $budget = clean_input($_POST['budget']);
    $deadline = clean_input($_POST['deadline']);
    $special_requirements = clean_input($_POST['special_requirements']);
    
    $errors = [];
    
    if (empty($project_title)) {
        $errors[] = 'عنوان المشروع مطلوب';
    }
    
    if (empty($project_description)) {
        $errors[] = 'وصف المشروع مطلوب';
    }
    
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, service_type, project_title, project_description, budget, deadline, special_requirements, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())");
            
            $stmt->execute([
                $user_id,
                $service_type,
                $project_title,
                $project_description,
                $budget,
                $deadline,
                $special_requirements
            ]);
            
            $order_id = $pdo->lastInsertId();
            
            $_SESSION['success'] = 'تم تقديم طلبك بنجاح! رقم الطلب: #' . $order_id;
            header('Location: orders.php');
            exit;
            
        } catch (PDOException $e) {
            $errors[] = 'حدث خطأ أثناء تقديم الطلب: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب خدمة - DevStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/order.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">Dev<span>Store</span></div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
                <li><a href="services.php"><i class="fas fa-laptop-code"></i> الخدمات</a></li>
                <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> لوحة التحكم</a></li>
            </ul>
        </nav>
    </header>

    <!-- Order Form -->
    <section class="order-section">
        <div class="container">
            <div class="order-header">
                <h1><i class="fas fa-shopping-cart"></i> طلب خدمة جديدة</h1>
                <p>أكمل النموذج أدناه لطلب خدمة <?php echo $selected_service['name']; ?></p>
            </div>

            <div class="order-form-container">
                <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                        <li><i class="fas fa-exclamation-circle"></i> <?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form method="POST" class="order-form">
                    <input type="hidden" name="service_type" value="<?php echo $service_type; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="service_name"><i class="fas fa-laptop-code"></i> نوع الخدمة</label>
                            <input type="text" id="service_name" class="form-control" 
                                   value="<?php echo $selected_service['name']; ?>" readonly>
                            <small><?php echo $selected_service['description']; ?></small>
                        </div>
                        
                        <?php if ($selected_service['base_price'] > 0): ?>
                        <div class="form-group">
                            <label for="base_price"><i class="fas fa-tag"></i> السعر الأساسي</label>
                            <input type="text" id="base_price" class="form-control" 
                                   value="<?php echo $selected_service['base_price']; ?> ₪" readonly>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="form-group">
                        <label for="project_title"><i class="fas fa-heading"></i> عنوان المشروع *</label>
                        <input type="text" id="project_title" name="project_title" class="form-control" 
                               value="<?php echo isset($_POST['project_title']) ? htmlspecialchars($_POST['project_title']) : ''; ?>"
                               required placeholder="مثال: موقع ويب لشركة تجارية">
                    </div>

                    <div class="form-group">
                        <label for="project_description"><i class="fas fa-file-alt"></i> وصف المشروع التفصيلي *</label>
                        <textarea id="project_description" name="project_description" class="form-control" 
                                  rows="6" required placeholder="صف مشروعك بالتفصيل..."><?php echo isset($_POST['project_description']) ? htmlspecialchars($_POST['project_description']) : ''; ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="budget"><i class="fas fa-money-bill-wave"></i> الميزانية المتوقعة (₪)</label>
                            <input type="number" id="budget" name="budget" class="form-control"
                                   value="<?php echo isset($_POST['budget']) ? htmlspecialchars($_POST['budget']) : $selected_service['base_price']; ?>"
                                   min="0" step="50">
                        </div>
                        
                        <div class="form-group">
                            <label for="deadline"><i class="fas fa-calendar-alt"></i> الموعد النهائي المطلوب</label>
                            <input type="date" id="deadline" name="deadline" class="form-control"
                                   value="<?php echo isset($_POST['deadline']) ? htmlspecialchars($_POST['deadline']) : ''; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="special_requirements"><i class="fas fa-star"></i> متطلبات خاصة إضافية</label>
                        <textarea id="special_requirements" name="special_requirements" class="form-control" 
                                  rows="4" placeholder="أي متطلبات إضافية أو ميزات خاصة تريدها..."><?php echo isset($_POST['special_requirements']) ? htmlspecialchars($_POST['special_requirements']) : ''; ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane"></i> تقديم الطلب
                        </button>
                        <a href="services.php" class="btn btn-outline">العودة للخدمات</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script src="js/order.js"></script>
</body>
</html>
