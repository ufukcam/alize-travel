<?php
// Veritabanı bağlantı ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'alizetravel_db');
define('DB_USER', 'alizetravel_user');
define('DB_PASS', 'xVxYR528W85v');

// Site ayarları
define('SITE_URL', 'http://alizetravel.com/');
define('ADMIN_URL', SITE_URL . '/admin');

// Upload yolları - BASİT VE KESİN
define('UPLOAD_PATH', __DIR__ . '/../assets/images/tours/');
define('UPLOAD_URL', SITE_URL . '/assets/images/tours/');

// Tur dosyaları (PDF vb.) için upload yolları
define('FILE_UPLOAD_PATH', __DIR__ . '/../assets/images/files/');
define('FILE_UPLOAD_URL', SITE_URL . '/assets/images/files/');

// SMTP ayarları (SMTP ile e-posta gönderimi için)
// Doldurun: örn. smtp.gmail.com, 587, tls
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'mail.alizetravel.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USER', getenv('SMTP_USER') ?: 'no-reply@alizetravel.com');
define('SMTP_PASS', getenv('SMTP_PASS') ?: '%]OBtSXk=&_;FZfJ');
define('SMTP_SECURE', getenv('SMTP_SECURE') ?: 'tls'); // tls | ssl | none
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: 'no-reply@alizetravel.com');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Alize Travel');

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
