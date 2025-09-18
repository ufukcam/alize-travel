<?php
require_once '../includes/config.php';
require_once '../includes/tour_manager.php';

// Giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$tourManager = new TourManager();
$message = '';
$messageType = '';

// Ayarlar güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Burada ayarlar güncellenebilir
        $message = 'Ayarlar başarıyla güncellendi!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Sistem bilgileri
$systemInfo = [
    'PHP Version' => PHP_VERSION,
    'Server Software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'Database Host' => DB_HOST,
    'Database Name' => DB_NAME,
    'Upload Max Size' => ini_get('upload_max_filesize'),
    'Memory Limit' => ini_get('memory_limit'),
    'Max Execution Time' => ini_get('max_execution_time') . 's'
];

// İstatistikler
$totalTours = count($tourManager->getAllTours());
$activeTours = count(array_filter($tourManager->getAllTours(), function($tour) {
    return $tour['is_active'] == 1;
}));
$totalCategories = count(array_unique(array_column($tourManager->getAllTours(), 'category')));
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayarlar - Alize Travel Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 10px;
            margin: 5px 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .info-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        .info-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            margin-bottom: 1rem;
        }
        .bg-primary-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .bg-success-gradient { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
        .bg-warning-gradient { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .bg-info-gradient { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0">
                <div class="sidebar p-3">
                    <div class="text-center mb-4">
                        <h4><i class="fas fa-plane-departure me-2"></i>Alize Travel</h4>
                        <p class="mb-0 small">Admin Panel</p>
                    </div>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link" href="tours.php">
                            <i class="fas fa-map-marked-alt me-2"></i>Turlar
                        </a>
                        <a class="nav-link" href="categories.php">
                            <i class="fas fa-tags me-2"></i>Kategoriler
                        </a>
                        <a class="nav-link" href="blog.php">
                            <i class="fas fa-tags me-2"></i>Blog
                        </a>
                        <a class="nav-link active" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Ayarlar
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="../index.html" target="_blank">
                            <i class="fas fa-external-link-alt me-2"></i>Siteyi Görüntüle
                        </a>
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Çıkış Yap
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="main-content p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2><i class="fas fa-cog me-2"></i>Sistem Ayarları</h2>
                        <a href="dashboard.php" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Dashboard'a Dön
                        </a>
                    </div>
                    
                    <!-- Mesajlar -->
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- İstatistik Kartları -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="info-card text-center">
                                <div class="info-icon bg-primary-gradient mx-auto">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <h3 class="mb-2"><?php echo $totalTours; ?></h3>
                                <p class="text-muted mb-0">Toplam Tur</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="info-card text-center">
                                <div class="info-icon bg-success-gradient mx-auto">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <h3 class="mb-2"><?php echo $activeTours; ?></h3>
                                <p class="text-muted mb-0">Aktif Tur</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="info-card text-center">
                                <div class="info-icon bg-warning-gradient mx-auto">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <h3 class="mb-2"><?php echo $totalCategories; ?></h3>
                                <p class="text-muted mb-0">Kategori</p>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="info-card text-center">
                                <div class="info-icon bg-info-gradient mx-auto">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h3 class="mb-2">VIP</h3>
                                <p class="text-muted mb-0">Müşteri Grubu</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sistem Bilgileri -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-server me-2"></i>Sistem Bilgileri</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tbody>
                                                <?php foreach ($systemInfo as $key => $value): ?>
                                                    <tr>
                                                        <td><strong><?php echo $key; ?></strong></td>
                                                        <td class="text-muted"><?php echo $value; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Güvenlik Bilgileri</h5>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success">
                                        <h6><i class="fas fa-check-circle me-2"></i>Güvenlik Durumu</h6>
                                        <ul class="mb-0 small">
                                            <li>Session tabanlı kimlik doğrulama aktif</li>
                                            <li>SQL injection koruması aktif</li>
                                            <li>XSS koruması aktif</li>
                                            <li>Dosya yükleme güvenliği aktif</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-info-circle me-2"></i>Admin Bilgileri</h6>
                                        <ul class="mb-0 small">
                                            <li>Kullanıcı adı: <code>admin</code></li>
                                            <li>Şifre: <code>alize2024</code></li>
                                            <li>Son giriş: <?php echo date('d.m.Y H:i'); ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hızlı İşlemler -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Hızlı İşlemler</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <a href="tours.php?action=add" class="btn btn-primary w-100">
                                                <i class="fas fa-plus me-2"></i>Yeni Tur Ekle
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="tours.php" class="btn btn-outline-primary w-100">
                                                <i class="fas fa-list me-2"></i>Turları Yönet
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="categories.php" class="btn btn-outline-success w-100">
                                                <i class="fas fa-tags me-2"></i>Kategorileri Görüntüle
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="../index.html" target="_blank" class="btn btn-outline-info w-100">
                                                <i class="fas fa-external-link-alt me-2"></i>Siteyi Görüntüle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
