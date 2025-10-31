<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// التحقق من تسجيل الدخول
require_login();

$user_id = $_SESSION['user_id'];
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_notifications':
            $email_notifications = isset($_POST['email_notifications']) ? 1 : 0;
            $sms_notifications = isset($_POST['sms_notifications']) ? 1 : 0;
            
            // هنا يمكنك حفظ الإعدادات في قاعدة البيانات
            // مثال: $pdo->prepare("UPDATE users SET email_notifications = ?, sms_notifications = ? WHERE id = ?")->execute([$email_notifications, $sms_notifications, $user_id]);
            
            $success = 'تم تحديث إعدادات الإشعارات بنجاح';
            break;
            
        case 'update_privacy':
            $profile_visibility = $_POST['profile_visibility'] ?? 'private';
            $data_sharing = isset($_POST['data_sharing']) ? 1 : 0;
            
            // حفظ إعدادات الخصوصية
            $success = 'تم تحديث إعدادات الخصوصية بنجاح';
            break;
            
        case 'update_interface':
            $theme = $_POST['theme'] ?? 'light';
            $language = $_POST['language'] ?? 'ar';
            $timezone = $_POST['timezone'] ?? 'Asia/Riyadh';
            
            $success = 'تم تحديث إعدادات الواجهة بنجاح';
            break;
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الإعدادات - DevStore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .settings-tabs {
            background: var(--white);
            border-radius: 10px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .tab-buttons {
            display: flex;
            background: #f8f9fa;
            border-bottom: 1px solid #eee;
        }
        
        .tab-btn {
            flex: 1;
            padding: 1rem;
            background: none;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            color: var(--text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .tab-btn:hover {
            background: #e9ecef;
        }
        
        .tab-btn.active {
            background: var(--white);
            color: var(--secondary-color);
            border-bottom: 2px solid var(--secondary-color);
        }
        
        .tab-content {
            padding: 2rem;
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
            animation: fadeIn 0.3s ease;
        }
        
        .setting-group {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .setting-group:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }
        
        .setting-group h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .setting-option {
            margin-bottom: 1rem;
        }
        
        .setting-description {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.25rem;
            margin-right: 2rem;
        }
        
        .checkbox {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            font-weight: 500;
        }
        
        .checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
        }
        
        .security-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .sessions-list {
            margin: 1rem 0;
        }
        
        .session-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 0.5rem;
        }
        
        .session-info {
            flex: 1;
        }
        
        .session-info strong {
            display: block;
            margin-bottom: 0.25rem;
        }
        
        .session-info span {
            color: #666;
            font-size: 0.9rem;
        }
        
        .session-status {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .session-item.current .session-status {
            background: #007bff;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @media (max-width: 768px) {
            .tab-buttons {
                flex-direction: column;
            }
            
            .security-actions {
                flex-direction: column;
            }
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
                        <a href="settings.php" class="active"><i class="fas fa-cog"></i> الإعدادات</a>
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
                <a href="settings.php" class="nav-item active">
                    <i class="fas fa-cog"></i>
                    <span>الإعدادات</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            <div class="section-header">
                <h1><i class="fas fa-cog"></i> الإعدادات</h1>
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

            <div class="settings-tabs">
                <div class="tab-buttons">
                    <button class="tab-btn active" data-tab="notifications">
                        <i class="fas fa-bell"></i> الإشعارات
                    </button>
                    <button class="tab-btn" data-tab="privacy">
                        <i class="fas fa-shield-alt"></i> الخصوصية
                    </button>
                    <button class="tab-btn" data-tab="interface">
                        <i class="fas fa-desktop"></i> الواجهة
                    </button>
                    <button class="tab-btn" data-tab="security">
                        <i class="fas fa-lock"></i> الأمان
                    </button>
                </div>

                <div class="tab-content">
                    <!-- إعدادات الإشعارات -->
                    <div class="tab-pane active" id="notifications">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_notifications">
                            
                            <div class="setting-group">
                                <h3><i class="fas fa-envelope"></i> الإشعارات البريدية</h3>
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="email_notifications" value="1" checked>
                                        إشعارات البريد الإلكتروني
                                    </label>
                                    <p class="setting-description">استلام إشعارات عن حالة الطلبات والعروض الجديدة</p>
                                </div>
                                
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="promotional_emails" value="1" checked>
                                        البريد الترويجي
                                    </label>
                                    <p class="setting-description">استلام عروض خاصة وأخبار عن الخدمات الجديدة</p>
                                </div>
                            </div>

                            <div class="setting-group">
                                <h3><i class="fas fa-sms"></i> إشعارات التطبيق</h3>
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="push_notifications" value="1" checked>
                                        الإشعارات الفورية
                                    </label>
                                    <p class="setting-description">إشعارات فورية عن تحديثات الطلبات والرسائل</p>
                                </div>
                                
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="order_updates" value="1" checked>
                                        تحديثات الطلبات
                                    </label>
                                    <p class="setting-description">إشعارات عند تغيير حالة الطلبات</p>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ إعدادات الإشعارات
                            </button>
                        </form>
                    </div>

                    <!-- إعدادات الخصوصية -->
                    <div class="tab-pane" id="privacy">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_privacy">
                            
                            <div class="setting-group">
                                <h3><i class="fas fa-eye"></i> إعدادات الظهور</h3>
                                <div class="setting-option">
                                    <label>ظهور الملف الشخصي:</label>
                                    <select name="profile_visibility" class="form-control">
                                        <option value="public">عام (يمكن للجميع رؤيته)</option>
                                        <option value="private" selected>خاص (أنا فقط)</option>
                                        <option value="friends">للعملاء فقط</option>
                                    </select>
                                    <p class="setting-description">التحكم في من يمكنه رؤية ملفك الشخصي ومشاريعك</p>
                                </div>
                            </div>

                            <div class="setting-group">
                                <h3><i class="fas fa-database"></i> مشاركة البيانات</h3>
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="data_sharing" value="1">
                                        السماح بمشاركة البيانات للإحصائيات
                                    </label>
                                    <p class="setting-description">مشاركة بيانات استخدام مجهولة المصدر لتحسين الخدمة</p>
                                </div>
                                
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="third_party" value="1">
                                        مشاركة البيانات مع شركاء موثوقين
                                    </label>
                                    <p class="setting-description">مشاركة معلومات أساسية مع شركاء الخدمة</p>
                                </div>
                            </div>

                            <div class="setting-group">
                                <h3><i class="fas fa-search"></i> إعدادات البحث</h3>
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="search_visibility" value="1" checked>
                                        الظهور في نتائج البحث
                                    </label>
                                    <p class="setting-description">السماح بظهور ملفك في نتائج البحث داخل المنصة</p>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ إعدادات الخصوصية
                            </button>
                        </form>
                    </div>

                    <!-- إعدادات الواجهة -->
                    <div class="tab-pane" id="interface">
                        <form method="POST">
                            <input type="hidden" name="action" value="update_interface">
                            
                            <div class="setting-group">
                                <h3><i class="fas fa-palette"></i> المظهر</h3>
                                <div class="setting-option">
                                    <label>السمة:</label>
                                    <select name="theme" class="form-control">
                                        <option value="light">فاتح</option>
                                        <option value="dark">داكن</option>
                                        <option value="auto">تلقائي (حسب النظام)</option>
                                    </select>
                                    <p class="setting-description">اختر المظهر المناسب لك</p>
                                </div>
                                
                                <div class="setting-option">
                                    <label>كثافة الألوان:</label>
                                    <select name="color_intensity" class="form-control">
                                        <option value="normal">عادي</option>
                                        <option value="high">عالية</option>
                                        <option value="low">منخفضة</option>
                                    </select>
                                </div>
                            </div>

                            <div class="setting-group">
                                <h3><i class="fas fa-language"></i> اللغة والمنطقة</h3>
                                <div class="setting-option">
                                    <label>اللغة:</label>
                                    <select name="language" class="form-control">
                                        <option value="ar" selected>العربية</option>
                                        <option value="en">English</option>
                                    </select>
                                </div>
                                
                                <div class="setting-option">
                                    <label>المنطقة الزمنية:</label>
                                    <select name="timezone" class="form-control">
                                        <option value="Asia/Riyadh" selected>الرياض (UTC+3)</option>
                                        <option value="Asia/Dubai">دبي (UTC+4)</option>
                                        <option value="Europe/London">لندن (UTC+0)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="setting-group">
                                <h3><i class="fas fa-desktop"></i> إعدادات العرض</h3>
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="compact_mode" value="1">
                                        الوضع المدمج
                                    </label>
                                    <p class="setting-description">عرض المزيد من المحتوى في الشاشة</p>
                                </div>
                                
                                <div class="setting-option">
                                    <label class="checkbox">
                                        <input type="checkbox" name="animations" value="1" checked>
                                        الحركات والتحريك
                                    </label>
                                    <p class="setting-description">تفعيل الحركات والانتقالات في الواجهة</p>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> حفظ إعدادات الواجهة
                            </button>
                        </form>
                    </div>

                    <!-- إعدادات الأمان -->
                    <div class="tab-pane" id="security">
                        <div class="setting-group">
                            <h3><i class="fas fa-shield-alt"></i> أمان الحساب</h3>
                            <div class="security-actions">
                                <a href="change-password.php" class="btn btn-outline">
                                    <i class="fas fa-key"></i> تغيير كلمة المرور
                                </a>
                                <a href="two-factor.php" class="btn btn-outline">
                                    <i class="fas fa-mobile-alt"></i> المصادقة الثنائية
                                </a>
                                <a href="security-questions.php" class="btn btn-outline">
                                    <i class="fas fa-question-circle"></i> أسئلة الأمان
                                </a>
                            </div>
                        </div>

                        <div class="setting-group">
                            <h3><i class="fas fa-desktop"></i> جلسات التسجيل</h3>
                            <div class="sessions-list">
                                <div class="session-item current">
                                    <div class="session-info">
                                        <strong>هذه الجلسة</strong>
                                        <span>جهازك الحالي • <?php echo $_SERVER['HTTP_USER_AGENT']; ?></span>
                                    </div>
                                    <span class="session-status">نشطة</span>
                                </div>
                            </div>
                            <button class="btn btn-outline btn-sm">
                                <i class="fas fa-sign-out-alt"></i> تسجيل الخروج من جميع الأجهزة
                            </button>
                        </div>

                        <div class="setting-group">
                            <h3><i class="fas fa-history"></i> سجل النشاط</h3>
                            <p>آخر نشاط للحساب: <?php echo date('Y-m-d H:i'); ?></p>
                            <a href="activity-log.php" class="btn btn-outline btn-sm">
                                <i class="fas fa-list"></i> عرض السجل الكامل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/dashboard.js"></script>
    <script>
        // تبديل التبويبات
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // إزالة النشاط من جميع الأزرار
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                
                // إضافة النشاط للعناصر المحددة
                this.classList.add('active');
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
        
        // حفظ الإعدادات تلقائياً
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('change', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> حفظ التغييرات';
                }
            });
        });
    </script>
</body>
</html>