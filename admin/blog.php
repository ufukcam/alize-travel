<?php
require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/blog_manager.php';

// Giriş kontrolü
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: /admin/index.php');
    exit;
}

$blogManager = new BlogManager();
$message = '';
$messageType = '';

// İşlem türünü belirle
$action = $_GET['action'] ?? 'list';

// Blog yazısı ekleme/güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $postData = [
            'title' => $_POST['title'] ?? '',
            'excerpt' => $_POST['excerpt'] ?? '',
            'content' => $_POST['content'] ?? '',
            'author_name' => $_POST['author_name'] ?? 'Dr. Mehmet Kürkçü',
            'author_email' => $_POST['author_email'] ?? 'memoguide@yahoo.fr',
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => $_POST['published_at'] ?? null,
            'meta_title' => $_POST['meta_title'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'tags' => $_POST['tags'] ?? '',
            'sort_order' => $_POST['sort_order'] ?? 0,
            'featured_image' => ''
        ];
        
        // Resim yükleme
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
            $postData['featured_image'] = $blogManager->uploadImage($_FILES['featured_image']);
        } elseif (isset($_POST['current_featured_image'])) {
            $postData['featured_image'] = $_POST['current_featured_image'];
        }
        
        if (isset($_POST['post_id']) && !empty($_POST['post_id'])) {
            // Güncelleme
            $blogManager->updatePost($_POST['post_id'], $postData);
            $message = 'Blog yazısı başarıyla güncellendi!';
            $messageType = 'success';
            $action = 'list';
        } else {
            // Yeni ekleme
            $blogManager->addPost($postData);
            $message = 'Blog yazısı başarıyla eklendi!';
            $messageType = 'success';
            $action = 'list';
        }
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Blog yazısı silme işlemi
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    try {
        $blogManager->deletePost($_GET['delete']);
        $message = 'Blog yazısı başarıyla silindi!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Blog yazısı durumu değiştirme
if (isset($_GET['toggle']) && !empty($_GET['toggle'])) {
    try {
        $blogManager->togglePostStatus($_GET['toggle']);
        $message = 'Blog yazısı durumu değiştirildi!';
        $messageType = 'success';
    } catch (Exception $e) {
        $message = 'Hata: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

// Düzenleme için blog yazısı bilgilerini getir
$editPost = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $editPost = $blogManager->getPostById($_GET['id']);
    if (!$editPost) {
        $action = 'list';
        $message = 'Blog yazısı bulunamadı!';
        $messageType = 'danger';
    }
}

// Tüm blog yazılarını getir
$posts = $blogManager->getAllPosts();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Yönetimi - Alize Travel Admin</title>
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
        .post-image {
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
                        <a class="nav-link" href="tours.php">
                            <i class="fas fa-map-marked-alt me-2"></i>Turlar
                        </a>
                        <a class="nav-link active" href="blog.php">
                            <i class="fas fa-blog me-2"></i>Blog
                        </a>
                        <a class="nav-link" href="categories.php">
                            <i class="fas fa-tags me-2"></i>Kategoriler
                        </a>
                        <a class="nav-link" href="settings.php">
                            <i class="fas fa-cog me-2"></i>Ayarlar
                        </a>
                        <hr class="my-3">
                        <a class="nav-link" href="../index.php" target="_blank">
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
                                <i class="fas fa-plus me-2"></i>Yeni Blog Yazısı Ekle
                            <?php elseif ($action === 'edit'): ?>
                                <i class="fas fa-edit me-2"></i>Blog Yazısı Düzenle
                            <?php else: ?>
                                <i class="fas fa-blog me-2"></i>Blog Yönetimi
                            <?php endif; ?>
                        </h2>
                        
                        <?php if ($action === 'list'): ?>
                            <a href="?action=add" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Yeni Blog Yazısı
                            </a>
                        <?php else: ?>
                            <a href="blog.php" class="btn btn-outline-secondary">
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
                        <!-- Blog Yazıları Listesi -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Tüm Blog Yazıları</h5>
                            </div>
                            <div class="card-body">
                                <?php if (empty($posts)): ?>
                                    <p class="text-muted text-center py-4">Henüz blog yazısı eklenmemiş.</p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Resim</th>
                                                    <th>Başlık</th>
                                                    <th>Durum</th>
                                                    <th>Yazar</th>
                                                    <th>Yayın Tarihi</th>
                                                    <th>Görüntülenme</th>
                                                    <th>İşlemler</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($posts as $post): ?>
                                                    <tr>
                                                        <td>
                                                            <?php if ($post['featured_image']): ?>
                                                                <img src="../assets/images/tours/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                                                     alt="<?php echo htmlspecialchars($post['title']); ?>" 
                                                                     class="post-image">
                                                            <?php else: ?>
                                                                <div class="post-image bg-light d-flex align-items-center justify-content-center">
                                                                    <i class="fas fa-image text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($post['title']); ?></strong>
                                                            <?php if ($post['excerpt']): ?>
                                                                <br><small class="text-muted"><?php echo htmlspecialchars(substr($post['excerpt'], 0, 60)) . '...'; ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-<?php echo $post['status'] === 'published' ? 'success' : ($post['status'] === 'draft' ? 'warning' : 'secondary'); ?>">
                                                                <?php echo ucfirst($post['status']); ?>
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <small><?php echo htmlspecialchars($post['author_name']); ?></small>
                                                        </td>
                                                        <td>
                                                            <small><?php echo $post['published_at'] ? date('d.m.Y H:i', strtotime($post['published_at'])) : '-'; ?></small>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info"><?php echo $post['views']; ?></span>
                                                        </td>
                                                        <td>
                                                            <div class="btn-group btn-group-sm">
                                                                <a href="?action=edit&id=<?php echo $post['id']; ?>" 
                                                                   class="btn btn-outline-primary" title="Düzenle">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="?toggle=<?php echo $post['id']; ?>" 
                                                                   class="btn btn-outline-<?php echo $post['status'] === 'published' ? 'warning' : 'success'; ?>" 
                                                                   title="<?php echo $post['status'] === 'published' ? 'Taslağa Çevir' : 'Yayınla'; ?>"
                                                                   onclick="return confirm('Blog yazısı durumunu değiştirmek istediğinizden emin misiniz?')">
                                                                    <i class="fas fa-<?php echo $post['status'] === 'published' ? 'eye-slash' : 'eye'; ?>"></i>
                                                                </a>
                                                                <a href="?delete=<?php echo $post['id']; ?>" 
                                                                   class="btn btn-outline-danger" title="Sil"
                                                                   onclick="return confirm('Bu blog yazısını silmek istediğinizden emin misiniz?')">
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
                        <!-- Blog Yazısı Ekleme/Düzenleme Formu -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <?php echo $action === 'add' ? 'Yeni Blog Yazısı Bilgileri' : 'Blog Yazısı Bilgilerini Düzenle'; ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <form method="POST" enctype="multipart/form-data">
                                    <?php if ($editPost): ?>
                                        <input type="hidden" name="post_id" value="<?php echo $editPost['id']; ?>">
                                        <input type="hidden" name="current_featured_image" value="<?php echo htmlspecialchars($editPost['featured_image']); ?>">
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
                                                <label for="title" class="form-label">Blog Başlığı *</label>
                                                <input type="text" class="form-control" id="title" name="title" 
                                                       value="<?php echo $editPost ? htmlspecialchars($editPost['title']) : ''; ?>" 
                                                       placeholder="Blog yazısı başlığı" required>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="excerpt" class="form-label">Özet</label>
                                                <textarea class="form-control" id="excerpt" name="excerpt" rows="3"
                                                          placeholder="Blog yazısı özeti (SEO ve kart görünümü için)..."><?php echo $editPost ? htmlspecialchars($editPost['excerpt']) : ''; ?></textarea>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="content" class="form-label">İçerik *</label>
                                                <textarea class="form-control" id="content" name="content" rows="10"
                                                          placeholder="Blog yazısı içeriği..." required><?php echo $editPost ? htmlspecialchars($editPost['content']) : ''; ?></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="featured_image" class="form-label">Öne Çıkan Resim</label>
                                                <?php if ($editPost && $editPost['featured_image']): ?>
                                                    <div class="mb-2">
                                                        <img src="../assets/images/tours/<?php echo htmlspecialchars($editPost['featured_image']); ?>" 
                                                             alt="Mevcut resim" class="img-fluid rounded" style="max-height: 200px;">
                                                        <small class="text-muted d-block">Mevcut resim</small>
                                                    </div>
                                                <?php endif; ?>
                                                <input type="file" class="form-control" id="featured_image" name="featured_image" 
                                                       accept="image/*">
                                                <small class="text-muted">JPG, PNG, GIF, WebP (max 5MB)</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- SEO ve Meta -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-search me-2"></i>SEO ve Meta Bilgileri
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="meta_title" class="form-label">Meta Başlık</label>
                                                <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                                       value="<?php echo $editPost ? htmlspecialchars($editPost['meta_title']) : ''; ?>" 
                                                       placeholder="SEO için meta başlık">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="meta_description" class="form-label">Meta Açıklama</label>
                                                <input type="text" class="form-control" id="meta_description" name="meta_description" 
                                                       value="<?php echo $editPost ? htmlspecialchars($editPost['meta_description']) : ''; ?>" 
                                                       placeholder="SEO için meta açıklama">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="tags" class="form-label">Etiketler</label>
                                                <input type="text" class="form-control" id="tags" name="tags" 
                                                       value="<?php echo $editPost ? htmlspecialchars($editPost['tags']) : ''; ?>" 
                                                       placeholder="Etiketler (virgülle ayırın)">
                                                <small class="text-muted">Örnek: Paris, müze, sanat, tarih</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Yayın Ayarları -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-calendar me-2"></i>Yayın Ayarları
                                            </h6>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Durum</label>
                                                <select class="form-select" id="status" name="status">
                                                    <option value="draft" <?php echo ($editPost && $editPost['status'] === 'draft') ? 'selected' : ''; ?>>Taslak</option>
                                                    <option value="published" <?php echo ($editPost && $editPost['status'] === 'published') ? 'selected' : ''; ?>>Yayınlandı</option>
                                                    <option value="archived" <?php echo ($editPost && $editPost['status'] === 'archived') ? 'selected' : ''; ?>>Arşivlendi</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="published_at" class="form-label">Yayın Tarihi</label>
                                                <input type="datetime-local" class="form-control" id="published_at" name="published_at" 
                                                       value="<?php echo $editPost && $editPost['published_at'] ? date('Y-m-d\TH:i', strtotime($editPost['published_at'])) : ''; ?>">
                                                <small class="text-muted">Boş bırakılırsa şu anki tarih kullanılır</small>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="sort_order" class="form-label">Sıralama</label>
                                                <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                                       min="0" value="<?php echo $editPost ? $editPost['sort_order'] : '0'; ?>">
                                                <small class="text-muted">Düşük sayılar önce gösterilir</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Yazar Bilgileri -->
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <h6 class="fw-bold text-primary mb-3">
                                                <i class="fas fa-user me-2"></i>Yazar Bilgileri
                                            </h6>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="author_name" class="form-label">Yazar Adı</label>
                                                <input type="text" class="form-control" id="author_name" name="author_name" 
                                                       value="<?php echo $editPost ? htmlspecialchars($editPost['author_name']) : 'Dr. Mehmet Kürkçü'; ?>"
                                                       placeholder="Yazar adı">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="author_email" class="form-label">Yazar E-posta</label>
                                                <input type="email" class="form-control" id="author_email" name="author_email" 
                                                       value="<?php echo $editPost ? htmlspecialchars($editPost['author_email']) : 'memoguide@yahoo.fr'; ?>"
                                                       placeholder="Yazar e-posta adresi">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Form Butonları -->
                                    <div class="text-end border-top pt-4">
                                        <a href="blog.php" class="btn btn-secondary me-2">
                                            <i class="fas fa-times me-2"></i>İptal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>
                                            <?php echo $action === 'add' ? 'Blog Yazısı Ekle' : 'Güncelle'; ?>
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
                    const content = document.getElementById('content').value.trim();
                    
                    if (!title || !content) {
                        e.preventDefault();
                        alert('Lütfen başlık ve içerik alanlarını doldurun.');
                        return false;
                    }
                });
            }
        });
    </script>
</body>
</html>
