<?php
function initialize_database() {
    global $pdo;
    
    try {
        // إنشاء قاعدة البيانات إذا لم تكن موجودة
        $pdo->exec("CREATE DATABASE IF NOT EXISTS devstore CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        $pdo->exec("USE devstore");
        
        // جدول المستخدمين
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INT PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(100) NOT NULL,
                email VARCHAR(255) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(20),
                avatar VARCHAR(255),
                role ENUM('user', 'admin') DEFAULT 'user',
                is_verified BOOLEAN DEFAULT FALSE,
                verification_code VARCHAR(10),
                reset_token VARCHAR(100),
                reset_expires DATETIME,
                last_login DATETIME,
                last_activity DATETIME,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // جدول الطلبات
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INT PRIMARY KEY AUTO_INCREMENT,
                user_id INT NOT NULL,
                service_type VARCHAR(50) NOT NULL,
                project_title VARCHAR(255) NOT NULL,
                project_description TEXT NOT NULL,
                budget DECIMAL(10,2),
                deadline DATE,
                special_requirements TEXT,
                status ENUM('pending', 'approved', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending',
                assigned_to INT,
                final_price DECIMAL(10,2),
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // إنشاء مستخدم admin افتراضي (كلمة المرور: 123456)
        $hashed_password = password_hash('123456', PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO users (name, email, password, role, is_verified) 
            VALUES (?, ?, ?, 'admin', TRUE)
        ");
        $stmt->execute(['مدير النظام', 'admin@devstore.com', $hashed_password]);
        
        return true;
        
    } catch (PDOException $e) {
        error_log("خطأ في إنشاء قاعدة البيانات: " . $e->getMessage());
        return false;
    }
}

// استدعاء الدالة لتهيئة قاعدة البيانات
initialize_database();
?>