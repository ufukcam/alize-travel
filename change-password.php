<?php
require_once 'includes/config.php';
require_once 'includes/security.php';

// Güvenlik başlıkları
Security::setSecurityHeaders();

$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    try {
        // Mevcut şifre kontrolü
        if (!password_verify($current_password, ADMIN_PASSWORD_HASH)) {
            throw new Exception('Mevcut şifre hatalı!');
        }
        
        // Yeni şifre eşleşme kontrolü
        if ($new_password !== $confirm_password) {
            throw new Exception('Yeni şifreler eşleşmiyor!');
        }
        
        // Şifre gücü kontrolü
        $password_errors = Security::validatePasswordStrength($new_password);
        if (!empty($password_errors)) {
            throw new Exception('Şifre gücü yetersiz: ' . implode(', ', $password_errors));
        }
        
        // Yeni hash oluştur
        $new_hash = password_hash($new_password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        // Config dosyasını güncelle
        $config_content = file_get_contents('includes/config.php');
        $new_config = str_replace(
            "define('ADMIN_PASSWORD_HASH', '" . ADMIN_PASSWORD_HASH . "');",
            "define('ADMIN_PASSWORD_HASH', '" . $new_hash . "');",
            $config_content
        );
        
        if (file_put_contents('includes/config.php', $new_config)) {
            $message = 'Şifre başarıyla değiştirildi! Yeni şifre: ' . $new_password;
            $messageType = 'success';
            
            // Güvenlik log'u
            Security::logSecurityEvent('Admin şifresi değiştirildi', 'IP: ' . $_SERVER['REMOTE_ADDR']);
        } else {
            throw new Exception('Config dosyası güncellenemedi!');
        }
        
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Şifre Değiştirme - Alize Travel</title>
    <meta name="robots" content="noindex, nofollow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .password-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 500px;
            margin: 0 auto;
        }
        .security-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.9rem;
        }
        .password-strength {
            margin-top: 0.5rem;
        }
        .strength-bar {
            height: 5px;
            border-radius: 3px;
            transition: all 0.3s;
        }
        .strength-weak { background: #dc3545; width: 25%; }
        .strength-medium { background: #ffc107; width: 50%; }
        .strength-strong { background: #28a745; width: 75%; }
        .strength-very-strong { background: #20c997; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <div class="password-card">
            <div class="text-center mb-4">
                <h2><i class="fas fa-shield-alt text-primary me-2"></i>Admin Şifre Değiştirme</h2>
                <p class="text-muted">Güvenli bir şifre belirleyin</p>
            </div>
            
            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST" id="passwordForm">
                <div class="mb-3">
                    <label for="current_password" class="form-label">Mevcut Şifre</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
                
                <div class="mb-3">
                    <label for="new_password" class="form-label">Yeni Şifre</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                    <div class="password-strength">
                        <div class="strength-bar" id="strengthBar"></div>
                        <small class="text-muted" id="strengthText">Şifre gücü: Zayıf</small>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="confirm_password" class="form-label">Yeni Şifre (Tekrar)</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-key me-2"></i>Şifreyi Değiştir
                </button>
                
                <div class="text-center">
                    <a href="admin/index.php" class="text-muted text-decoration-none">
                        <i class="fas fa-arrow-left me-1"></i>Admin Paneline Dön
                    </a>
                </div>
            </form>
            
            <div class="security-info">
                <h6><i class="fas fa-info-circle me-2"></i>Güvenli Şifre Gereksinimleri:</h6>
                <ul class="mb-0 small">
                    <li>En az 8 karakter</li>
                    <li>En az bir büyük harf (A-Z)</li>
                    <li>En az bir küçük harf (a-z)</li>
                    <li>En az bir rakam (0-9)</li>
                    <li>En az bir özel karakter (!@#$%^&*)</li>
                </ul>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Şifre gücü kontrolü
        document.getElementById('new_password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');
            
            let strength = 0;
            let strengthClass = 'strength-weak';
            let strengthLabel = 'Zayıf';
            
            if (password.length >= 8) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    strengthClass = 'strength-weak';
                    strengthLabel = 'Zayıf';
                    break;
                case 2:
                    strengthClass = 'strength-medium';
                    strengthLabel = 'Orta';
                    break;
                case 3:
                case 4:
                    strengthClass = 'strength-strong';
                    strengthLabel = 'Güçlü';
                    break;
                case 5:
                    strengthClass = 'strength-very-strong';
                    strengthLabel = 'Çok Güçlü';
                    break;
            }
            
            strengthBar.className = 'strength-bar ' + strengthClass;
            strengthText.textContent = 'Şifre gücü: ' + strengthLabel;
        });
        
        // Form validasyonu
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Yeni şifreler eşleşmiyor!');
                return false;
            }
            
            if (newPassword.length < 8) {
                e.preventDefault();
                alert('Şifre en az 8 karakter olmalıdır!');
                return false;
            }
        });
    </script>
</body>
</html>
