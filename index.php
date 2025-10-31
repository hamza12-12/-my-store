<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevStore - متجر الخدمات البرمجية</title>
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
                <li><a href="#portfolio"><i class="fas fa-briefcase"></i> أعمالنا</a></li>
                <li><a href="#contact"><i class="fas fa-envelope"></i> اتصل بنا</a></li>
                <li><a href="login.php" class="login-btn"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>حلول برمجية <span>إبداعية</span> لمستقبل رقمي أفضل</h1>
            <p>نحوّل أفكارك إلى واقع رقمي متميز بأسعار تنافسية وجودة لا تضاهى</p>
            <div class="hero-buttons">
                <a href="services.php" class="cta-button">استعرض الخدمات <i class="fas fa-arrow-left"></i></a>
                <a href="#portfolio" class="cta-button secondary">أعمالنا <i class="fas fa-eye"></i></a>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="stats">
        <div class="container">
            <div class="stat-item">
                <i class="fas fa-project-diagram"></i>
                <h3>+150</h3>
                <p>مشروع مكتمل</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <h3>+80</h3>
                <p>عميل راضٍ</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-clock"></i>
                <h3>+5000</h3>
                <p>ساعة تطوير</p>
            </div>
            <div class="stat-item">
                <i class="fas fa-award"></i>
                <h3>+95%</h3>
                <p>رضا العملاء</p>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">لماذا تختار <span>DevStore؟</span></h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-rocket"></i>
                    </div>
                    <h3>تسليم سريع</h3>
                    <p>نلتزم بالمواعيد النهائية ونضمن التسليم في الوقت المحدد</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>آمن ومحمي</h3>
                    <p>أعلى معايير الأمان والحماية لبياناتك ومشاريعك</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>دعم فني 24/7</h3>
                    <p>فريق دعم فني متاح على مدار الساعة لمساعدتك</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Preview -->
    <section id="services" class="services-preview">
        <div class="container">
            <h2 class="section-title">خدماتنا <span>المميزة</span></h2>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-header">
                        <i class="fas fa-globe"></i>
                        <h3>تصميم مواقع الويب</h3>
                    </div>
                    <div class="service-body">
                        <div class="service-price">500 ₪</div>
                        <ul class="service-features">
                            <li>تصميم متجاوب مع جميع الأجهزة</li>
                            <li>واجهة مستخدم احترافية</li>
                            <li>تحسين محركات البحث SEO</li>
                            <li>دعم فني لمدة 3 أشهر</li>
                        </ul>
                        <a href="order.php?service=web-design" class="btn btn-primary">اطلب الآن</a>
                    </div>
                </div>

                <div class="service-card featured">
                    <div class="service-header">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>متاجر إلكترونية</h3>
                    </div>
                    <div class="service-body">
                        <div class="service-price">1500 ₪</div>
                        <ul class="service-features">
                            <li>متجر كامل بشهادة SSL</li>
                            <li>أنظمة دفع متعددة</li>
                            <li>لوحة تحكم متكاملة</li>
                            <li>دعم فني لمدة 6 أشهر</li>
                        </ul>
                        <a href="order.php?service=ecommerce" class="btn btn-primary">اطلب الآن</a>
                    </div>
                </div>

                <div class="service-card">
                    <div class="service-header">
                        <i class="fas fa-mobile-alt"></i>
                        <h3>تطبيقات الجوال</h3>
                    </div>
                    <div class="service-body">
                        <div class="service-price">2000 ₪</div>
                        <ul class="service-features">
                            <li>تطبيقات أندرويد و iOS</li>
                            <li>تصميم Material Design</li>
                            <li>ربط مع السيرفرات</li>
                            <li>نشر في المتاجر الرسمية</li>
                        </ul>
                        <a href="order.php?service=mobile-app" class="btn btn-primary">اطلب الآن</a>
                    </div>
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