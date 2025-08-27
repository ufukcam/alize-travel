<?php
require_once 'config.php';

class Security {
    
    /**
     * Admin giriş kontrolü
     */
    public static function checkAdminLogin() {
        if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
            header('Location: index.php');
            exit;
        }
        
        // Session timeout kontrolü
        if (isset($_SESSION['admin_login_time']) && (time() - $_SESSION['admin_login_time']) > SESSION_TIMEOUT) {
            session_destroy();
            header('Location: index.php?error=timeout');
            exit;
        }
        
        // IP değişikliği kontrolü
        if (isset($_SESSION['admin_ip']) && $_SESSION['admin_ip'] !== $_SERVER['REMOTE_ADDR']) {
            session_destroy();
            header('Location: index.php?error=ip_changed');
            exit;
        }
        
        // User agent değişikliği kontrolü
        if (isset($_SESSION['admin_user_agent']) && $_SESSION['admin_user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
            session_destroy();
            header('Location: index.php?error=agent_changed');
            exit;
        }
        
        // Session'ı yenile
        $_SESSION['admin_login_time'] = time();
    }
    
    /**
     * CSRF token kontrolü
     */
    public static function checkCSRFToken($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }
    
    /**
     * Input sanitization
     */
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Dosya yükleme güvenliği
     */
    public static function validateFileUpload($file) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Dosya yükleme hatası: ' . $file['error']);
        }
        
        if ($file['size'] > $max_size) {
            throw new Exception('Dosya boyutu çok büyük. Maksimum 5MB olmalıdır.');
        }
        
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception('Geçersiz dosya türü. Sadece JPG, PNG ve GIF dosyaları kabul edilir.');
        }
        
        // Dosya içeriği kontrolü
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mime_type, $allowed_types)) {
            throw new Exception('Dosya türü doğrulanamadı.');
        }
        
        return true;
    }
    
    /**
     * Güvenlik log'u
     */
    public static function logSecurityEvent($event, $details = '') {
        $log_entry = date('Y-m-d H:i:s') . " - " . $event;
        if ($details) {
            $log_entry .= " - " . $details;
        }
        $log_entry .= " - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown');
        $log_entry .= " - User Agent: " . ($_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');
        
        error_log("SECURITY: " . $log_entry);
    }
    
    /**
     * Güvenlik başlıkları
     */
    public static function setSecurityHeaders() {
        header('X-Frame-Options: DENY');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' https://cdn.jsdelivr.net; style-src \'self\' \'unsafe-inline\' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; font-src \'self\' https://fonts.gstatic.com; img-src \'self\' data: https:;');
    }
    
    /**
     * Şifre gücü kontrolü
     */
    public static function validatePasswordStrength($password) {
        $errors = [];
        
        if (strlen($password) < 8) {
            $errors[] = 'Şifre en az 8 karakter olmalıdır.';
        }
        
        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = 'Şifre en az bir büyük harf içermelidir.';
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = 'Şifre en az bir küçük harf içermelidir.';
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            $errors[] = 'Şifre en az bir rakam içermelidir.';
        }
        
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $errors[] = 'Şifre en az bir özel karakter içermelidir.';
        }
        
        return $errors;
    }
    
    /**
     * Rate limiting
     */
    public static function checkRateLimit($action, $max_attempts = 10, $time_window = 3600) {
        $key = "rate_limit_{$action}_" . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 0, 'first_attempt' => time()];
        }
        
        $current_time = time();
        $time_diff = $current_time - $_SESSION[$key]['first_attempt'];
        
        if ($time_diff > $time_window) {
            $_SESSION[$key] = ['count' => 1, 'first_attempt' => $current_time];
            return true;
        }
        
        if ($_SESSION[$key]['count'] >= $max_attempts) {
            return false;
        }
        
        $_SESSION[$key]['count']++;
        return true;
    }
}
?>
