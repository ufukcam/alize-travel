<?php
require_once '../includes/config.php';

// Zaten giriş yapılmışsa dashboard'a yönlendir
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: /admin/dashboard.php');
    exit;
}

$error = '';
$success = '';

// Brute force koruması
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
    if (time() - $_SESSION['last_attempt'] < LOGIN_TIMEOUT) {
        $remaining_time = LOGIN_TIMEOUT - (time() - $_SESSION['last_attempt']);
        $error = "Çok fazla başarısız giriş denemesi. Lütfen " . ceil($remaining_time / 60) . " dakika bekleyin.";
    } else {
        // Timeout geçti, denemeleri sıfırla
        unset($_SESSION['login_attempts']);
        unset($_SESSION['last_attempt']);
    }
}

// Giriş formu gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($error)) {
    // CSRF token kontrolü
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = 'Güvenlik hatası. Lütfen sayfayı yenileyin.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Input validasyonu
        if (empty($username) || empty($password)) {
            $error = 'Kullanıcı adı ve şifre gereklidir.';
        } elseif (strlen($username) > 50 || strlen($password) > 100) {
            $error = 'Geçersiz giriş bilgileri.';
        } else {
            // Giriş denemesi sayısını artır
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['last_attempt'] = time();
            
            // BASİT ŞİFRE KONTROLÜ
            if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
                // Başarılı giriş
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username'] = $username;
                $_SESSION['admin_login_time'] = time();
                $_SESSION['admin_ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['admin_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                
                // Güvenlik log'u
                error_log("Admin girişi başarılı: " . $username . " - IP: " . $_SERVER['REMOTE_ADDR'] . " - " . date('Y-m-d H:i:s'));
                
                // Deneme sayısını sıfırla
                unset($_SESSION['login_attempts']);
                unset($_SESSION['last_attempt']);
                
                header('Location: /admin/dashboard.php');
                exit;
            } else {
                // Başarısız giriş
                error_log("Admin girişi başarısız: " . $username . " - IP: " . $_SERVER['REMOTE_ADDR'] . " - " . date('Y-m-d H:i:s'));
                
                if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
                    $error = "Çok fazla başarısız giriş denemesi. Hesabınız " . ceil(LOGIN_TIMEOUT / 60) . " dakika kilitlendi.";
                } else {
                    $remaining_attempts = MAX_LOGIN_ATTEMPTS - $_SESSION['login_attempts'];
                    $error = "Kullanıcı adı veya şifre hatalı! Kalan deneme: " . $remaining_attempts;
                }
            }
        }
    }
}

// Güvenlik başlıkları
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Alize Travel</title>
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
        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .login-body {
            padding: 2rem;
        }
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .security-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="login-card">
                    <div class="login-header">
                        <h3><i class="fas fa-plane-departure me-2"></i>Alize Travel</h3>
                        <p class="mb-0">Admin Panel Girişi</p>
                    </div>
                    <div class="login-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i><?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="loginForm">
                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Kullanıcı Adı</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" 
                                           required maxlength="50" autocomplete="username">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">Şifre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           required maxlength="100" autocomplete="current-password">
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login w-100" id="loginBtn">
                                <i class="fas fa-sign-in-alt me-2"></i>Giriş Yap
                            </button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="../index.php" class="text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Ana Sayfaya Dön
                            </a>
                        </div>
                        
                        <div class="security-info">
                            <i class="fas fa-shield-alt me-2"></i>
                            <strong>Güvenlik:</strong> Bu sayfa güvenli bağlantı ile korunmaktadır.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form güvenliği
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (!username || !password) {
                e.preventDefault();
                alert('Lütfen tüm alanları doldurun.');
                return false;
            }
            
            // Submit butonunu devre dışı bırak
            document.getElementById('loginBtn').disabled = true;
            document.getElementById('loginBtn').innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Giriş Yapılıyor...';
        });
        
        // Input validasyonu
        document.getElementById('username').addEventListener('input', function() {
            this.value = this.value.replace(/[<>]/g, '');
        });
    </script>
</body>
</html>
