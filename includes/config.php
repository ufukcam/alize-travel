<?php
// Veritabanı bağlantı ayarları
define('DB_HOST', 'localhost');
define('DB_NAME', 'alizetravel_db');
define('DB_USER', 'alizetravel_user');
define('DB_PASS', 'xVxYR528W85v');

// Site ayarları
define('SITE_URL', 'http://alizetravel.com/');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOAD_PATH', $_SERVER['DOCUMENT_ROOT'] . '/assets/images/tours/');
define('UPLOAD_URL', SITE_URL . '/assets/images/tours/');

// Güvenlik ayarları
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'alize2024');

// Hata raporlama
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session başlat
session_start();
?>
