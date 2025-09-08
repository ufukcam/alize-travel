<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/tour_manager.php';

// Giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit;
}

$tourManager = new TourManager();
$message = '';
$messageType = '';

// İşlem türünü belirle
$action = $_GET['action'] ?? 'list';

// Tur ekleme/güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $tourData = [
            'title' => $_POST['title'] ?? '',
            'subtitle' => $_POST['subtitle'] ?? '',
            'description' => $_POST['description'] ?? '',
            'short_description' => $_POST['short_description'] ?? '',
            'category' => $_POST['category'] ?? '',
            'subcategory' => $_POST['subcategory'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'duration' => $_POST['duration'] ?? '',
            'difficulty' => $_POST['difficulty'] ?? '',
            'group_size' => $_POST['group_size'] ?? '',
            'highlights' => $_POST['highlights'] ?? '',
            'included_services' => $_POST['included_services'] ?? '',
            'tour_options' => $_POST['tour_options'] ?? '',
            'ideal_for' => $_POST['ideal_for'] ?? '',
            'guide_name' => $_POST['guide_name'] ?? 'Dr. Mehmet Kürkçü',
            'guide_expertise' => $_POST['guide_expertise'] ?? 'Sanat Tarihçisi',
            'rating' => $_POST['rating'] ?? 5.0,
            'sort_order' => $_POST['sort_order'] ?? 0,
            'is_active' => $_POST['is_active'] ?? 1,
            'image' => '',
            'tour_file' => ''
        ];
        
        // Resim yükleme
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tourData['image'] = $tourManager->uploadImage($_FILES['image']);
        } elseif (isset($_POST['current_image'])) {
            $tourData['image'] = $_POST['current_image'];
        }

        // Tur dosyası yükleme (PDF/DOC/DOCX)
        if (isset($_FILES['tour_file']) && $_FILES['tour_file']['error'] === UPLOAD_ERR_OK) {
            $tourData['tour_file'] = $tourManager->uploadFile($_FILES['tour_file']);
        } elseif (isset($_POST['current_tour_file'])) {
            $tourData['tour_file'] = $_POST['current_tour_file'];
        }
        
        if (isset($_POST['tour_id']) && !empty($_POST['tour_id'])) {
            // Güncelleme
            $tourManager->updateTour($_POST['tour_id'], $tourData);
            $message = 'Tur başarıyla güncellendi!';
            $messageType = 'success';
            $action = 'list';
        } else {
            // Yeni ekleme
            $tourManager->addTour($tourData);
            $message = 'Tur başarıyla eklendi!';
            $messageType = 'success';
            $action = 'list';
        }
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tur silme işlemi
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    try {
        $tourManager->deleteTour($_GET['delete']);
        $message = 'Tur başarıyla silindi!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Tur durumu değiştirme
if (isset($_GET['toggle']) && !empty($_GET['toggle'])) {
    try {
        $tourManager->toggleTourStatus($_GET['toggle']);
        $message = 'Tur durumu değiştirildi!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Düzenleme için tur bilgilerini getir
$editTour = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editTour = $tourManager->getTourById($_GET['id']);
    if (!$editTour) {
        $action = 'list';
        $message = 'Tur bulunamadı!';
        $messageType = 'danger';
    }
}

// Tüm turları getir
$tours = $tourManager->getAllToursOrdered();

// Kategoriler
$categories = [
    'museums' => 'Müzeler',
    'thematic' => 'Tematik Paris',
    'surroundings' => 'Paris Çevresi',
    'france' => 'Fransa Turları',
    'normandiya' => 'Normandiya Turları'
];

$subcategories = [
    'museums' => 'Müzeler',
    'thematic_paris' => 'Tematik Paris: Farklı Mahalleler',
    'day_tours' => 'Günlük Turlar',
    'france_tours' => 'Fransa Geneli',
    'normandiya_tours' => 'Normandiya Turları'
];

$difficulties = ['Kolay', 'Orta', 'Zor'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tur Yönetimi - Alize Travel Admin</title>
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
        .tour-image {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
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
                        <a class="nav-link" href="dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                        <a class="nav-link active" href="turlar">
                            <i class="fas fa-map-marked-alt me-2"></i>Turlar
                        </a>
                        <a class="nav-link" href="categories.php">
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
                        <h2>
                            <?php if ($action === 'add'): ?>
                                <i class="fas fa-plus me-2"></i>Yeni Tur Ekle
                            <?php elseif ($action === 'edit'): ?>
                                <i class="fas fa-edit me-2"></i>Tur Düzenle
                            <?php else: ?>
                                <i class="fas fa-map-marked-alt me-2"></i>Tur Yönetimi
                            <?php endif; ?>
                        </h2>
                        
                        <?php if ($action === 'list'): ?>
                            <a href="?action=add" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Yeni Tur Ekle
                            </a>
                        <?php else: ?>
                            <a href="turlar" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Geri Dön
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Mesajlar -->
                    <?php if ($message): ?>
                        <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                            <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($action === 'list'): ?>
                        <!-- Tur Listesi -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Tüm Turlar</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($tours)): ?>
                                    <p class="text-muted text-center py-4">Henüz tur eklenmemiş.</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Resim</th>
                                                    <th>Başlık</th>
                                                    <th>Alt Başlık</th>
                                                    <th>Kategori</th>
                                                    <th>Dosya</th>
                                                    <th>Fiyat</th>
                                                    <th>Süre</th>
                                                    <th>Puan</th>
                                                    <th>Durum</th>
                                                    <th>İşlemler</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($tours as $tour): ?>
                                                    <tr>
                                                        <td>
                                                            <?php if ($tour['image']): ?>
                                                                <img src="../assets/images/tours/<?php echo htmlspecialchars($tour['image']); ?>" 
                                                                     alt="<?php echo htmlspecialchars($tour['title']); ?>" 
                                                                     class="tour-image">
                                                            <?php else: ?>
                                                                <div class="tour-image bg-light d-flex align-items-center justify-content-center">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($tour['title']); ?></strong>
                                                            <?php if ($tour['subtitle']): ?>
                                                                <br><small class="text-muted"><?php echo htmlspecialchars($tour['subtitle']); ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($tour['subtitle']): ?>
                                                                <small class="text-muted"><?php echo htmlspecialchars(substr($tour['subtitle'], 0, 40)) . '...'; ?></small>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary"><?php echo htmlspecialchars($categories[$tour['category']] ?? $tour['category']); ?></span>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($tour['tour_file'])): ?>
                                                                <a href="<?php echo FILE_UPLOAD_URL . htmlspecialchars($tour['tour_file']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">Görüntüle</a>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if ($tour['price']): ?>
                                                                <span class="badge bg-success">€<?php echo number_format($tour['price'], 2); ?></span>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <small><?php echo htmlspecialchars($tour['duration']); ?></small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-star me-1"></i><?php echo $tour['rating'] ?? '5.0'; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $tour['is_active'] ? 'success' : 'secondary'; ?>">
                                                                <?php echo $tour['is_active'] ? 'Aktif' : 'Pasif'; ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="?action=edit&id=<?php echo $tour['id']; ?>" 
                                                                   class="btn btn-outline-primary" title="Düzenle">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="?toggle=<?php echo $tour['id']; ?>" 
                                                                   class="btn btn-outline-<?php echo $tour['is_active'] ? 'warning' : 'success'; ?>" 
                                                                   title="<?php echo $tour['is_active'] ? 'Pasif Yap' : 'Aktif Yap'; ?>"
                                                                   onclick="return confirm('Tur durumunu değiştirmek istediğinizden emin misiniz?')">
                                                                    <i class="fas fa-<?php echo $tour['is_active'] ? 'eye-slash' : 'eye'; ?>"></i>
                                                                </a>
                                                                <a href="?delete=<?php echo $tour['id']; ?>" 
                                                                   class="btn btn-outline-danger" title="Sil"
                                                                   onclick="return confirm('Bu turu silmek istediğinizden emin misiniz?')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                    <?php else: ?>
                        <!-- Tur Ekleme/Düzenleme Formu -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <?php echo $action === 'add' ? 'Yeni Tur Bilgileri' : 'Tur Bilgilerini Düzenle'; ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <?php if ($editTour): ?>
                                        <input type="hidden" name="tour_id" value="<?php echo $editTour['id']; ?>">
                                        <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($editTour['image']); ?>">
                                    <?php endif; ?>
                                    
                                    <!-- Temel Bilgiler -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-info-circle me-2"></i>Temel Bilgiler
                                            </h6>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="mb-3">
                                                <label for="title" class="form-label">Tur Başlığı *</label>
                                                <input type="text" class="form-control" id="title" name="title" 
                                                       value="<?php echo $editTour ? htmlspecialchars($editTour['title']) : ''; ?>" 
                                                       placeholder="örn: Louvre Müzesi Turu" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="subtitle" class="form-label">Alt Başlık</label>
                                                <input type="text" class="form-control" id="subtitle" name="subtitle" 
                                                       value="<?php echo $editTour ? htmlspecialchars($editTour['subtitle'] ?? '') : ''; ?>" 
                                                       placeholder="örn: Güç ve sanatın harmonisi, dünyanın en büyük müzesi">
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Ana Açıklama</label>
                                                <textarea class="form-control" id="description" name="description" rows="4"
                                                          placeholder="Tur hakkında detaylı bilgi ve açıklama..."><?php echo $editTour ? htmlspecialchars($editTour['description']) : ''; ?></textarea>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="short_description" class="form-label">Kısa Açıklama</label>
                                                <textarea class="form-control" id="short_description" name="short_description" rows="2"
                                                          placeholder="Tur hakkında kısa özet (SEO ve kart görünümü için)..."><?php echo $editTour ? htmlspecialchars($editTour['short_description'] ?? '') : ''; ?></textarea>
                                                <small class="text-muted">Bu açıklama tur kartlarında ve SEO meta description'da kullanılır (max 160 karakter)</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="image" class="form-label">Tur Resmi *</label>
                                                <?php if ($editTour && $editTour['image']): ?>
                                                    <div class="mb-2">
                                                        <img src="../assets/images/tours/<?php echo htmlspecialchars($editTour['image']); ?>" 
                                                             alt="Mevcut resim" class="img-fluid rounded" style="max-height: 200px;">
                                                        <small class="text-muted d-block">Mevcut resim</small>
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" id="image" name="image" 
                                                       accept="image/*" <?php echo !$editTour ? 'required' : ''; ?>>
                                                <small class="text-muted">JPG, PNG, GIF (max 5MB)</small>
                                            </div>

                                            <div class="mb-3">
                                                <label for="tour_file" class="form-label">Tur Dosyası (PDF/DOC/DOCX)</label>
                                                <?php if ($editTour && !empty($editTour['tour_file'])): ?>
                                                    <div class="mb-2">
                                                        <a href="<?php echo FILE_UPLOAD_URL . htmlspecialchars($editTour['tour_file']); ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                                            Mevcut Dosyayı Görüntüle
                                                        </a>
                                                    </div>
                                                    <input type="hidden" name="current_tour_file" value="<?php echo htmlspecialchars($editTour['tour_file']); ?>">
                                                <?php endif; ?>
                                                <input type="file" class="form-control" id="tour_file" name="tour_file" accept=".pdf,.doc,.docx">
                                                <small class="text-muted">Maksimum 10MB</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Kategori ve Sınıflandırma -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-tags me-2"></i>Kategori ve Sınıflandırma
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="category" class="form-label">Ana Kategori *</label>
                                                <select class="form-select" id="category" name="category" required>
                                                    <option value="">Kategori Seçin</option>
                                                    <?php foreach ($categories as $key => $name): ?>
                                                        <option value="<?php echo $key; ?>" 
                                                                <?php echo ($editTour && $editTour['category'] === $key) ? 'selected' : ''; ?>>
                                                            <?php echo $name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="subcategory" class="form-label">Alt Kategori</label>
                                                <select class="form-select" id="subcategory" name="subcategory">
                                                    <option value="">Alt Kategori Seçin</option>
                                                    <?php foreach ($subcategories as $key => $name): ?>
                                                        <option value="<?php echo $key; ?>" 
                                                                <?php echo ($editTour && $editTour['subcategory'] === $key) ? 'selected' : ''; ?>>
                                                            <?php echo $name; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tur Detayları -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-clock me-2"></i>Tur Detayları
                                            </h6>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="duration" class="form-label">Süre *</label>
                                                <input type="text" class="form-control" id="duration" name="duration" 
                                                       placeholder="örn: 3-4 saat" 
                                                       value="<?php echo $editTour ? htmlspecialchars($editTour['duration']) : ''; ?>" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Fiyat (€)</label>
                                                <input type="number" class="form-control" id="price" name="price" 
                                                       step="0.01" min="0" 
                                                       value="<?php echo $editTour ? $editTour['price'] : ''; ?>"
                                                       placeholder="0.00">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="difficulty" class="form-label">Zorluk Seviyesi</label>
                                                <select class="form-select" id="difficulty" name="difficulty">
                                                    <option value="">Zorluk Seçin</option>
                                                    <?php foreach ($difficulties as $difficulty): ?>
                                                        <option value="<?php echo $difficulty; ?>" 
                                                                <?php echo ($editTour && $editTour['difficulty'] === $difficulty) ? 'selected' : ''; ?>>
                                                            <?php echo $difficulty; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="group_size" class="form-label">Grup Büyüklüğü</label>
                                                <input type="text" class="form-control" id="group_size" name="group_size" 
                                                       placeholder="örn: Özel Grup" 
                                                       value="<?php echo $editTour ? htmlspecialchars($editTour['group_size'] ?? '') : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tur Özellikleri -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-star me-2"></i>Tur Özellikleri
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="highlights" class="form-label">Tur Vurguları</label>
                                                <textarea class="form-control" id="highlights" name="highlights" rows="4"
                                                          placeholder="Turda görülecek önemli yerler ve özellikler..."><?php echo $editTour ? htmlspecialchars($editTour['highlights'] ?? '') : ''; ?></textarea>
                                                <small class="text-muted">Her satıra bir özellik yazın</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="included_services" class="form-label">Dahil Olan Hizmetler</label>
                                                <textarea class="form-control" id="included_services" name="included_services" rows="4"
                                                          placeholder="Tur fiyatına dahil olan hizmetler..."><?php echo $editTour ? htmlspecialchars($editTour['included_services'] ?? '') : ''; ?></textarea>
                                                <small class="text-muted">Her satıra bir hizmet yazın</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tur Seçenekleri -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-list me-2"></i>Tur Seçenekleri
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tour_options" class="form-label">Tur Seçenekleri</label>
                                                <textarea class="form-control" id="tour_options" name="tour_options" rows="4"
                                                          placeholder="Farklı tur seçenekleri ve süreleri..."><?php echo $editTour ? htmlspecialchars($editTour['tour_options'] ?? '') : ''; ?></textarea>
                                                <small class="text-muted">Klasik tur, özel program gibi seçenekler</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="ideal_for" class="form-label">İdeal Olduğu Kişiler</label>
                                                <textarea class="form-control" id="ideal_for" name="ideal_for" rows="4"
                                                          placeholder="Bu tur kimler için idealdir..."><?php echo $editTour ? htmlspecialchars($editTour['ideal_for'] ?? '') : ''; ?></textarea>
                                                <small class="text-muted">Hedef kitle tanımı</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Rehber Bilgileri -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-user-tie me-2"></i>Rehber Bilgileri
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="guide_name" class="form-label">Rehber Adı</label>
                                                <input type="text" class="form-control" id="guide_name" name="guide_name" 
                                                       value="<?php echo $editTour ? htmlspecialchars($editTour['guide_name'] ?? 'Dr. Mehmet Kürkçü') : 'Dr. Mehmet Kürkçü'; ?>"
                                                       placeholder="Rehber adı">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="guide_expertise" class="form-label">Rehber Uzmanlığı</label>
                                                <input type="text" class="form-control" id="guide_expertise" name="guide_expertise" 
                                                       value="<?php echo $editTour ? htmlspecialchars($editTour['guide_expertise'] ?? 'Sanat Tarihçisi') : 'Sanat Tarihçisi'; ?>"
                                                       placeholder="Uzmanlık alanı">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Teknik Ayarlar -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-cog me-2"></i>Teknik Ayarlar
                                            </h6>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="sort_order" class="form-label">Sıralama</label>
                                                <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                                       min="0" value="<?php echo $editTour ? $editTour['sort_order'] : '0'; ?>">
                                                <small class="text-muted">Düşük sayılar önce gösterilir</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="is_active" class="form-label">Durum</label>
                                                <select class="form-select" id="is_active" name="is_active">
                                                    <option value="1" <?php echo ($editTour && $editTour['is_active'] == 1) ? 'selected' : ''; ?>>Aktif</option>
                                                    <option value="0" <?php echo ($editTour && $editTour['is_active'] == 0) ? 'selected' : ''; ?>>Pasif</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="rating" class="form-label">Varsayılan Puan</label>
                                                <input type="number" class="form-control" id="rating" name="rating" 
                                                       min="0" max="5" step="0.1" 
                                                       value="<?php echo $editTour ? ($editTour['rating'] ?? '5.0') : '5.0'; ?>">
                                                <small class="text-muted">0.0 - 5.0 arası</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Form Butonları -->
                                    <div class="text-end border-top pt-4">
                                        <a href="turlar" class="btn btn-secondary me-2">
                                            <i class="fas fa-times me-2"></i>İptal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            <?php echo $action === 'add' ? 'Tur Ekle' : 'Güncelle'; ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Form validasyonu
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const title = document.getElementById('title').value.trim();
                    const category = document.getElementById('category').value;
                    const duration = document.getElementById('duration').value.trim();
                    const image = document.getElementById('image');
                    
                    let isValid = true;
                    let errorMessage = '';
                    
                    // Başlık kontrolü
                    if (!title) {
                        isValid = false;
                        errorMessage += 'Tur başlığı zorunludur.\n';
                    }
                    
                    // Kategori kontrolü
                    if (!category) {
                        isValid = false;
                        errorMessage += 'Kategori seçimi zorunludur.\n';
                    }
                    
                    // Süre kontrolü
                    if (!duration) {
                        isValid = false;
                        errorMessage += 'Tur süresi zorunludur.\n';
                    }
                    
                    // Resim kontrolü (sadece yeni ekleme için)
                    if (!image.files.length && !document.querySelector('input[name="current_image"]')) {
                        isValid = false;
                        errorMessage += 'Tur resmi zorunludur.\n';
                    }
                    
                    if (!isValid) {
                        e.preventDefault();
                        alert('Lütfen aşağıdaki hataları düzeltin:\n\n' + errorMessage);
                        return false;
                    }
                });
            }
            
            // Kategori değiştiğinde alt kategoriyi güncelle
            const categorySelect = document.getElementById('category');
            const subcategorySelect = document.getElementById('subcategory');
            
            if (categorySelect && subcategorySelect) {
                categorySelect.addEventListener('change', function() {
                    const selectedCategory = this.value;
                    
                    // Alt kategorileri temizle
                    subcategorySelect.innerHTML = '<option value="">Alt Kategori Seçin</option>';
                    
                    if (selectedCategory === 'museums') {
                        subcategorySelect.innerHTML += '<option value="museums">Müzeler</option>';
                    } else if (selectedCategory === 'thematic') {
                        subcategorySelect.innerHTML += '<option value="thematic_paris">Tematik Paris: Farklı Mahalleler</option>';
                    } else if (selectedCategory === 'surroundings') {
                        subcategorySelect.innerHTML += '<option value="day_tours">Günlük Turlar</option>';
                    } else if (selectedCategory === 'france') {
                        subcategorySelect.innerHTML += '<option value="france_tours">Fransa Geneli</option>';
                    }
                });
            }
        });
    </script>
</body>
</html>
