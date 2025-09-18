<?php
require_once '../includes/config.php';
require_once '../includes/tour_manager.php';

// Giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$tourManager = new TourManager();
$totalTours = count($tourManager->getAllTours());
$activeTours = count($tourManager->getToursByCategory('museums')) + 
                count($tourManager->getToursByCategory('thematic')) + 
                count($tourManager->getToursByCategory('surroundings')) + 
                count($tourManager->getToursByCategory('france'));

$recentTours = array_slice($tourManager->getAllTours(), 0, 5);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Alize Travel Admin</title>
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
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        .bg-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-success-gradient {
            background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
        }
        .bg-warning-gradient {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .bg-info-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
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
                        <a class="nav-link active" href="dashboard.php">
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
                        <a class="nav-link" href="settings.php">
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
                        <h2>Dashboard</h2>
                        <span class="text-muted">Hoş geldin, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</span>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-primary-gradient me-3">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $totalTours; ?></h3>
                                        <p class="text-muted mb-0">Toplam Tur</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-success-gradient me-3">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $activeTours; ?></h3>
                                        <p class="text-muted mb-0">Aktif Tur</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-warning-gradient me-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">VIP</h3>
                                        <p class="text-muted mb-0">Müşteri Grubu</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon bg-info-gradient me-3">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0">5.0</h3>
                                        <p class="text-muted mb-0">Ortalama Puan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Hızlı İşlemler</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="tours.php?action=add" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Yeni Tur Ekle
                                        </a>
                                        <a href="tours.php" class="btn btn-outline-primary">
                                            <i class="fas fa-list me-2"></i>Tüm Turları Görüntüle
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Son Eklenen Turlar</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (empty($recentTours)): ?>
                                        <p class="text-muted">Henüz tur eklenmemiş.</p>
                                    <?php else: ?>
                                        <div class="list-group list-group-flush">
                                            <?php foreach ($recentTours as $tour): ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1"><?php echo htmlspecialchars($tour['title']); ?></h6>
                                                        <small class="text-muted"><?php echo htmlspecialchars($tour['category']); ?></small>
                                                    </div>
                                                    <span class="badge bg-<?php echo $tour['is_active'] ? 'success' : 'secondary'; ?> rounded-pill">
                                                        <?php echo $tour['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
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
