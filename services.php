 <?php
// خدمات المتجر
$services = [
    [
        'id' => 'web-design',
        'icon' => 'fas fa-globe',
        'title' => 'تصميم مواقع الويب',
        'price' => '500 ₪',
        'description' => 'تصميم مواقع احترافية متجاوبة مع جميع الأجهزة',
        'features' => ['تصميم متجاوب', 'واجهة مستخدم احترافية', 'تحسين SEO', 'دعم 3 أشهر'],
        'popular' => false
    ],
    [
        'id' => 'ecommerce',
        'icon' => 'fas fa-shopping-cart',
        'title' => 'متاجر إلكترونية',
        'price' => '1500 ₪',
        'description' => 'متاجر متكاملة بأنظمة الدفع وإدارة المخزون',
        'features' => ['شهادة SSL', 'أنظمة دفع متعددة', 'لوحة تحكم', 'دعم 6 أشهر'],
        'popular' => true
    ],
    [
        'id' => 'mobile-app',
        'icon' => 'fas fa-mobile-alt',
        'title' => 'تطبيقات الجوال',
        'price' => '2000 ₪',
        'description' => 'تطبيقات أندرويد و iOS بأعلى المعايير',
        'features' => ['أندرويد و iOS', 'Material Design', 'ربط سيرفرات', 'نشر في المتاجر'],
        'popular' => false
    ],
    [
        'id' => 'custom-software',
        'icon' => 'fas fa-code',
        'title' => 'برمجة مخصصة',
        'price' => ' حسب المتطلبات',
        'description' => 'حلول برمجية مخصصة لاحتياجات عملك',
        'features' => ['تحليل متطلبات', 'تصميم معماري', 'اختبارات شاملة', 'دعم مستمر'],
        'popular' => false
    ],
    [
        'id' => 'seo',
        'icon' => 'fas fa-chart-line',
        'title' => 'تحسين محركات البحث',
        'price' => '300 ₪/شهر',
        'description' => 'تحسين ترتيب موقعك في محركات البحث',
        'features' => ['تحليل الكلمات', 'تحسين المحتوى', 'روابط خلفية', 'تقارير شهرية'],
        'popular' => false
    ],
    [
        'id' => 'maintenance',
        'icon' => 'fas fa-tools',
        'title' => 'صيانة المواقع',
        'price' => '200 ₪/شهر',
        'description' => 'صيانة دورية واستضافة واحترافية',
        'features' => ['استضافة سحابية', 'نسخ احتياطي', 'مراقبة أداء', 'دعم فني'],
        'popular' => false
    ]
];
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خدماتنا - DevStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <nav>
            <div class="logo">Dev<span>Store</span></div>
            <ul class="nav-links">
                <li><a href="index.php"><i class="fas fa-home"></i> الرئيسية</a></li>
                <li><a href="services.php" class="active"><i class="fas fa-laptop-code"></i> الخدمات</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> إنشاء حساب</a></li>
            </ul>
        </nav>
    </header>

    <!-- Services Hero -->
    <section class="page-hero">
        <div class="container">
            <h1>خدماتنا الاحترافية</h1>
            <p>اكتشف مجموعة خدماتنا البرمجية الشاملة</p>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="services-section">
        <div class="container">
            <div class="services-grid">
                <?php foreach ($services as $service): ?>
                <div class="service-card <?php echo $service['popular'] ? 'featured' : ''; ?>">
                    <?php if ($service['popular']): ?>
                    <div class="popular-badge">الأكثر طلباً</div>
                    <?php endif; ?>
                    
                    <div class="service-header">
                        <i class="<?php echo $service['icon']; ?>"></i>
                        <h3><?php echo $service['title']; ?></h3>
                    </div>
                    
                    <div class="service-body">
                        <div class="service-description">
                            <?php echo $service['description']; ?>
                        </div>
                        
                        <div class="service-price"><?php echo $service['price']; ?></div>
                        
                        <ul class="service-features">
                            <?php foreach ($service['features'] as $feature): ?>
                            <li><?php echo $feature; ?></li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="service-actions">
                            <a href="service-details.php?id=<?php echo $service['id']; ?>" class="btn btn-outline">
                                <i class="fas fa-info-circle"></i> التفاصيل
                            </a>
                            <a href="order.php?service=<?php echo $service['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> اطلب الآن
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>هل تبحث عن حل برمجي مخصص؟</h2>
            <p>فريقنا جاهز لتحويل فكرتك إلى واقع ملموس</p>
            <a href="order.php?service=custom" class="cta-button">
                <i class="fas fa-lightbulb"></i> ناقش فكرتك معنا
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>DevStore</h3>
                    <p>شركة رائدة في تقديم الحلول البرمجية والتقنية المبتكرة</p>
                </div>
                <div class="footer-section">
                    <h4>روابط سريعة</h4>
                    <ul>
                        <li><a href="index.php">الرئيسية</a></li>
                        <li><a href="services.php">الخدمات</a></li>
                        <li><a href="login.php">تسجيل الدخول</a></li>
                        <li><a href="register.php">إنشاء حساب</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>اتصل بنا</h4>
                    <p><i class="fas fa-phone"></i> +966 123 456 789</p>
                    <p><i class="fas fa-envelope"></i> info@devstore.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 DevStore. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
