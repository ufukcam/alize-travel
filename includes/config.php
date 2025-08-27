<?php
// Veritabanı bağlantı ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'alizetravel_db');
define('DB_USER', 'alizetravel_user');
define('DB_PASS', 'xVxYR528W85v');

// Site ayarları
define('SITE_URL', 'http://localhost/alize');
define('ADMIN_URL', SITE_URL . '/admin');

// Upload yolları - BASİT VE KESİN
define('UPLOAD_PATH', __DIR__ . '/../assets/images/tours/');
define('UPLOAD_URL', SITE_URL . '/assets/images/tours/');

// Güvenlik ayarları - BASİT ŞİFRE SİSTEMİ
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'alize2024');

// Güvenlik sabitleri
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 dakika
define('SESSION_TIMEOUT', 3600); // 1 saat

// Hata raporlama
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session ayarları - session_start() ÖNCESİNDE yapılmalı
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // HTTPS varsa 1 yapın
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Session başlat
session_start();

// CSRF token oluştur
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
