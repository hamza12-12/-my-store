<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'devstore');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_NAME', 'DevStore');
define('SITE_URL', 'http://localhost/my-store');
define('ADMIN_EMAIL', 'info@devstore.com');
define('ENVIRONMENT', 'development');

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    require_once 'init_database.php';
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>