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

// Kategoriler
$categories = [
    'museums' => [
        'name' => 'Müzeler',
        'description' => 'Paris\'in en önemli müzeleri ve sanat galerileri',
        'icon' => 'fas fa-landmark',
        'color' => 'primary'
    ],
    'thematic' => [
        'name' => 'Tematik Paris',
        'description' => 'Farklı mahalleler ve tematik turlar',
        'icon' => 'fas fa-map-marked-alt',
        'color' => 'success'
    ],
    'surroundings' => [
        'name' => 'Paris Çevresi',
        'description' => 'Versailles, Fontainebleau gibi günlük turlar',
        'icon' => 'fas fa-car',
        'color' => 'warning'
    ],
    'france' => [
        'name' => 'Fransa Turları',
        'description' => 'Provence, Loire Vadisi gibi uzun turlar',
        'icon' => 'fas fa-plane',
        'color' => 'info'
    ]
];

$subcategories = [
    'museums' => 'Müzeler',
    'thematic_paris' => 'Tematik Paris: Farklı Mahalleler',
    'day_tours' => 'Günlük Turlar',
    'france_tours' => 'Fransa Geneli'
];

// Her kategorideki tur sayısını hesapla
$tourCounts = [];
foreach ($categories as $key => $category) {
    $tourCounts[$key] = count($tourManager->getToursByCategory($key));
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori Yönetimi - Alize Travel Admin</title>
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
        .category-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s;
            height: 100%;
        }
        .category-card:hover {
            transform: translateY(-5px);
        }
        .category-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
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
                        <a class="nav-link active" href="categories.php">
                            <i class="fas fa-tags me-2"></i>Kategoriler
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
                        <h2><i class="fas fa-tags me-2"></i>Kategori Yönetimi</h2>
                        <a href="tours.php?action=add" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Yeni Tur Ekle
                        </a>
                    </div>
                    
                    <!-- Kategori Kartları -->
                    <div class="row g-4">
                        <?php foreach ($categories as $key => $category): ?>
                            <div class="col-md-6 col-lg-3">
                                <div class="category-card">
                                    <div class="text-center">
                                        <div class="category-icon bg-<?php echo $category['color']; ?>-gradient mx-auto">
                                            <i class="<?php echo $category['icon']; ?>"></i>
                                        </div>
                                        <h5 class="fw-bold mb-2"><?php echo $category['name']; ?></h5>
                                        <p class="text-muted small mb-3"><?php echo $category['description']; ?></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-<?php echo $category['color']; ?> rounded-pill">
                                                <?php echo $tourCounts[$key]; ?> Tur
                                            </span>
                                            <a href="tours.php?category=<?php echo $key; ?>" class="btn btn-sm btn-outline-<?php echo $category['color']; ?>">
                                                <i class="fas fa-eye me-1"></i>Görüntüle
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Alt Kategoriler -->
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0"><i class="fas fa-sitemap me-2"></i>Alt Kategoriler</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php foreach ($subcategories as $key => $name): ?>
                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <div class="d-flex align-items-center p-3 border rounded">
                                                    <i class="fas fa-folder me-3 text-primary"></i>
                                                    <div>
                                                        <h6 class="mb-1"><?php echo $name; ?></h6>
                                                        <small class="text-muted"><?php echo $key; ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Bilgi Kartı -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle me-2"></i>Kategori Yönetimi Hakkında</h6>
                                <ul class="mb-0">
                                    <li>Kategoriler otomatik olarak turlar eklendikçe güncellenir</li>
                                    <li>Her kategorideki tur sayısı gerçek zamanlı olarak hesaplanır</li>
                                    <li>Alt kategoriler tur ekleme sırasında seçilebilir</li>
                                    <li>Yeni kategori eklemek için turlar sayfasından yeni tur ekleyin</li>
                                </ul>
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
