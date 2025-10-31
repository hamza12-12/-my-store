<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

$service_id = $_GET['id'] ?? 'web-design';

// معلومات الخدمات
$services = [
    'web-design' => [
        'name' => 'تصميم مواقع الويب',
        'price' => '500 ₪',
        'description' => 'تصميم موقع ويب احترافي متجاوب مع جميع الأجهزة',
        'features' => [
            'تصميم متجاوب مع جميع الأجهزة',
            'واجهة مستخدم احترافية وسهلة الاستخدام',
            'تحسين محركات البحث (SEO)',
            'سرعة تحميل عالية',
            'دعم فني لمدة 3 أشهر',
            'شهادة SSL مجانية',
            'استضافة مجانية لمدة سنة'
        ],
        'delivery_time' => '7-14 يوم',
        'revisions' => '3 مرات تعديل'
    ],
    'ecommerce' => [
        'name' => 'متاجر إلكترونية',
        'price' => '1500 ₪', 
        'description' => 'متجر إلكتروني متكامل بأنظمة الدفع والمخزون',
        'features' => [
            'تصميم متجر إلكتروني متكامل',
            'أنظمة دفع متعددة (بطاقات، PayPal)',
            'إدارة المخزون والطلبات',
            'لوحة تحكم متقدمة',
            'شهادة SSL مجانية',
            'استضافة مجانية لمدة سنة',
            'دعم فني لمدة 6 أشهر',
            'تدريب على استخدام النظام'
        ],
        'delivery_time' => '14-30 يوم',
        'revisions' => '5 مرات تعديل'
    ],
    'mobile-app' => [
        'name' => 'تطبيقات الجوال',
        'price' => '2000 ₪',
        'description' => 'تطبيقات أندرويد و iOS بأعلى المعايير',
        'features' => [
            'تطبيقات أندرويد و iOS',
            'تصميم Material Design',
            'ربط مع السيرفرات وال APIs',
            'نشر في متجري Google Play و App Store',
            'شهادة أمان عالية',
            'دعم فني لمدة سنة',
            'تحديثات مجانية لمدة 6 أشهر'
        ],
        'delivery_time' => '30-60 يوم',
        'revisions' => '4 مرات تعديل'
    ]
];

$service = $services[$service_id] ?? $services['web-design'];
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $service['name']; ?> - DevStore</title>
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
                <li><a href="services.php"><i class="fas fa-laptop-code"></i> الخدمات</a></li>
                <li><a href="login.php"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
                <li><a href="register.php"><i class="fas fa-user-plus"></i> إنشاء حساب</a></li>
            </ul>
        </nav>
    </header>

    <!-- Service Details Hero -->
    <section class="page-hero service-hero">
        <div class="container">
            <div class="hero-content">
                <h1><?php echo $service['name']; ?></h1>
                <p><?php echo $service['description']; ?></p>
                <div class="service-meta">
                    <span class="price"><?php echo $service['price']; ?></span>
                    <span class="delivery-time"><i class="fas fa-clock"></i> <?php echo $service['delivery_time']; ?></span>
                    <span class="revisions"><i class="fas fa-sync"></i> <?php echo $service['revisions']; ?></span>
                </div>
                <a href="order.php?service=<?php echo $service_id; ?>" class="cta-button">
                    <i class="fas fa-shopping-cart"></i> اطلب الخدمة الآن
                </a>
            </div>
        </div>
    </section>

    <!-- Service Details -->
    <section class="service-details-section">
        <div class="container">
            <div class="details-grid">
                <!-- المميزات -->
                <div class="features-card">
                    <h2><i class="fas fa-star"></i> مميزات الخدمة</h2>
                    <ul class="features-list">
                        <?php foreach ($service['features'] as $feature): ?>
                        <li>
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo $feature; ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- معلومات إضافية -->
                <div class="info-cards">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                        <h3>سرعة التنفيذ</h3>
                        <p>نلتزم بمواعيد التسليم المتفق عليها ونضمن جودة عالية في أسرع وقت</p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>أمان وحماية</h3>
                        <p>نطبق أعلى معايير الأمان لحماية بياناتك ومشروعك من أي تهديدات</p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>دعم فني متكامل</h3>
                        <p>فريق دعم فني متاح على مدار الساعة لمساعدتك في أي استفسار</p>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-medal"></i>
                        </div>
                        <h3>جودة مضمونة</h3>
                        <p>نضمن لك الحصول على منتج نهائي بجودة عالية تنافس أفضل المعايير</p>
                    </div>
                </div>
            </div>

            <!-- عملية العمل -->
            <div class="work-process">
                <h2><i class="fas fa-cogs"></i> كيف نعمل</h2>
                <div class="process-steps">
                    <div class="process-step">
                        <div class="step-number">1</div>
                        <h3>التخطيط والتحليل</h3>
                        <p>نقوم بتحليل متطلباتك وتخطيط المشروع بدقة</p>
                    </div>
                    <div class="process-step">
                        <div class="step-number">2</div>
                        <h3>التصميم والتطوير</h3>
                        <p>نصمم ونطور الحل البرمجي وفق أحدث التقنيات</p>
                    </div>
                    <div class="process-step">
                        <div class="step-number">3</div>
                        <h3>الاختبار والمراجعة</h3>
                        <p>نختبر المنتج بدقة ونجري التعديلات اللازمة</p>
                    </div>
                    <div class="process-step">
                        <div class="step-number">4</div>
                        <h3>التسليم والدعم</h3>
                        <p>نسلم المشروع وندعمك في مرحلة التشغيل</p>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <div class="service-cta">
                <h2>مستعد لبدء مشروعك؟</h2>
                <p>لا تتردد في التواصل معنا لتحويل فكرتك إلى واقع ملموس</p>
                <div class="cta-buttons">
                    <a href="order.php?service=<?php echo $service_id; ?>" class="cta-button primary">
                        <i class="fas fa-shopping-cart"></i> اطلب الخدمة الآن
                    </a>
                    <a href="contact.php" class="cta-button secondary">
                        <i class="fas fa-envelope"></i> تواصل معنا
                    </a>
                </div>
            </div>
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