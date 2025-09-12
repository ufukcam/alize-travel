<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/blog_manager.php';

// Blog slug'ını al
$slug = $_GET['slug'] ?? null;

if (!$slug) {
    header('Location: blog.php');
    exit;
}

$blogManager = new BlogManager();
$post = $blogManager->getPostBySlug($slug);

if (!$post) {
    header('Location: blog.php');
    exit;
}

// Görüntülenme sayısını artır
$blogManager->incrementViews($post['id']);

// İlgili yazılar
$relatedPosts = $blogManager->getRecentPosts(3);
$relatedPosts = array_filter($relatedPosts, function($p) use ($post) {
    return $p['id'] != $post['id'];
});
$relatedPosts = array_slice($relatedPosts, 0, 3);

// Popüler yazılar
$popularPosts = $blogManager->getPopularPosts(5);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['meta_title'] ?: $post['title']); ?> - Alize Travel | VIP Seyahatler</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo htmlspecialchars($post['meta_description'] ?: $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160)); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($post['tags']); ?>, Paris turları, Fransa turları, Dr. Mehmet Kürkçü, özel rehber, VIP tur">
    <meta name="author" content="<?php echo htmlspecialchars($post['author_name']); ?>">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Turkish">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php echo htmlspecialchars($post['title']); ?> - Alize Travel">
    <meta property="og:description" content="<?php echo htmlspecialchars($post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160)); ?>">
    <meta property="og:image" content="<?php echo $post['featured_image'] ? 'https://alizetravel.com/assets/images/tours/' . htmlspecialchars($post['featured_image']) : 'https://alizetravel.com/assets/images/alize-slider.jpg'; ?>">
    <meta property="og:url" content="https://alizetravel.com/blog/<?php echo $post['slug']; ?>">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="Alize Travel">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://alizetravel.com/blog/<?php echo $post['slug']; ?>">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        .blog-detail-hero {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            color: white;
            padding: 4rem 0;
        }
        .blog-content {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 3rem;
            margin-bottom: 2rem;
        }
        .blog-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .blog-meta .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .blog-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }
        .blog-excerpt {
            font-size: 1.25rem;
            color: #6c757d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .blog-body {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #333;
        }
        .blog-body h2 {
            font-size: 1.8rem;
            font-weight: 600;
            margin: 2rem 0 1rem 0;
            color: #333;
        }
        .blog-body h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem 0;
            color: #333;
        }
        .blog-body p {
            margin-bottom: 1.5rem;
        }
        .blog-body img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 1.5rem 0;
        }
        .blog-body blockquote {
            border-left: 4px solid #667eea;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: #6c757d;
        }
        .sidebar-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }
        .sidebar-post {
            display: flex;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }
        .sidebar-post:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .sidebar-post-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            margin-right: 1rem;
        }
        .sidebar-post-content {
            flex: 1;
        }
        .sidebar-post-title {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.25rem;
            line-height: 1.3;
        }
        .sidebar-post-meta {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .author-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            text-align: center;
        }
        .author-image {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }
        .share-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 2rem;
        }
        .share-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: white;
            transition: transform 0.3s;
        }
        .share-btn:hover {
            transform: translateY(-2px);
            color: white;
        }
        .share-facebook { background-color: #3b5998; }
        .share-twitter { background-color: #1da1f2; }
        .share-whatsapp { background-color: #25d366; }
        .share-linkedin { background-color: #0077b5; }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <span class="me-4"><i class="fas fa-phone me-2"></i>+33 7 69 91 11 24</span>
                    <span><i class="fas fa-envelope me-2"></i>info@alizetravel.com</span>
                </div>
                <div class="col-md-6 text-end">
                    <a href="https://www.instagram.com/alizetravelparis" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/33769911124?text=Merhaba!%20Alize%20Travel%20hakk%C4%B1nda%20bilgi%20almak%20istiyorum." class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div>ALIZE TRAVEL</div>
                <div class="subtitle">VIP SEYAHATLER</div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hakkimizda">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="turlar">Programlar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hizmetler">Hizmetlerimiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="blog">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="iletisim">İletişim</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="blog-detail-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <div class="blog-meta justify-content-center">
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar me-1"></i>
                            <?php echo date('d.m.Y', strtotime($post['published_at'])); ?>
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-user me-1"></i>
                            <?php echo htmlspecialchars($post['author_name']); ?>
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-eye me-1"></i>
                            <?php echo $post['views']; ?> görüntülenme
                        </span>
                    </div>
                    <h1 class="serif-font blog-title"><?php echo htmlspecialchars($post['title']); ?></h1>
                    <?php if ($post['excerpt']): ?>
                        <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <!-- Main Content -->
                <div class="col-lg-8">
                    <article class="blog-content">
                        <?php if ($post['featured_image']): ?>
                            <img src="/assets/images/tours/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                 alt="<?php echo htmlspecialchars($post['title']); ?>" class="img-fluid rounded mb-4">
                        <?php endif; ?>
                        
                        <div class="blog-body">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                        
                        <!-- Paylaşım Butonları -->
                        <div class="share-buttons">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://alizetravel.com/blog/' . $post['slug']); ?>" 
                               target="_blank" class="share-btn share-facebook" title="Facebook'ta Paylaş">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('https://alizetravel.com/blog/' . $post['slug']); ?>&text=<?php echo urlencode($post['title']); ?>" 
                               target="_blank" class="share-btn share-twitter" title="Twitter'da Paylaş">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/?text=<?php echo urlencode($post['title'] . ' - ' . 'https://alizetravel.com/blog/' . $post['slug']); ?>" 
                               target="_blank" class="share-btn share-whatsapp" title="WhatsApp'ta Paylaş">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode('https://alizetravel.com/blog/' . $post['slug']); ?>" 
                               target="_blank" class="share-btn share-linkedin" title="LinkedIn'de Paylaş">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </article>
                    
                    <!-- İlgili Yazılar -->
                    <?php if (!empty($relatedPosts)): ?>
                        <div class="blog-content">
                            <h3 class="sidebar-title mb-4">
                                <i class="fas fa-bookmark me-2"></i>İlgili Yazılar
                            </h3>
                            <div class="row g-4">
                                <?php foreach ($relatedPosts as $relatedPost): ?>
                                    <div class="col-md-4">
                                        <div class="sidebar-post">
                                            <?php if ($relatedPost['featured_image']): ?>
                                                <img src="/assets/images/tours/<?php echo htmlspecialchars($relatedPost['featured_image']); ?>" 
                                                     alt="<?php echo htmlspecialchars($relatedPost['title']); ?>" class="sidebar-post-image">
                                            <?php else: ?>
                                                <div class="sidebar-post-image bg-light d-flex align-items-center justify-content-center">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="sidebar-post-content">
                                                <h4 class="sidebar-post-title">
                                                    <a href="blog/<?php echo $relatedPost['slug']; ?>" class="text-decoration-none text-dark">
                                                        <?php echo htmlspecialchars($relatedPost['title']); ?>
                                                    </a>
                                                </h4>
                                                <div class="sidebar-post-meta">
                                                    <?php echo date('d.m.Y', strtotime($relatedPost['published_at'])); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Yazar Bilgisi -->
                    <div class="author-card">
                        <img src="/assets/images/mrmehmet.jpg" alt="<?php echo htmlspecialchars($post['author_name']); ?>" class="author-image">
                        <h4><?php echo htmlspecialchars($post['author_name']); ?></h4>
                        <p class="text-muted mb-3"><?php echo htmlspecialchars($post['author_email']); ?></p>
                        <p class="small">Paris ve Fransa konusunda uzman rehber. 30+ yıllık deneyimle size en iyi seyahat deneyimini sunuyor.</p>
                        <a href="iletisim" class="btn btn-primary btn-sm">İletişime Geç</a>
                    </div>
                    
                    <!-- Popüler Yazılar -->
                    <?php if (!empty($popularPosts)): ?>
                        <div class="sidebar-card">
                            <h3 class="sidebar-title">
                                <i class="fas fa-fire me-2"></i>Popüler Yazılar
                            </h3>
                            <?php foreach ($popularPosts as $popularPost): ?>
                                <div class="sidebar-post">
                                    <?php if ($popularPost['featured_image']): ?>
                                        <img src="/assets/images/tours/<?php echo htmlspecialchars($popularPost['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($popularPost['title']); ?>" class="sidebar-post-image">
                                    <?php else: ?>
                                        <div class="sidebar-post-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="sidebar-post-content">
                                        <h4 class="sidebar-post-title">
                                            <a href="blog/<?php echo $popularPost['slug']; ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($popularPost['title']); ?>
                                            </a>
                                        </h4>
                                        <div class="sidebar-post-meta">
                                            <i class="fas fa-eye me-1"></i><?php echo $popularPost['views']; ?> görüntülenme
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- İletişim -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">
                            <i class="fas fa-envelope me-2"></i>İletişim
                        </h3>
                        <p class="mb-3">Seyahat planlarınız için bizimle iletişime geçin.</p>
                        <a href="iletisim" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-paper-plane me-2"></i>Mesaj Gönder
                        </a>
                        <a href="https://wa.me/33769911124" class="btn btn-success w-100">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <div class="mb-3">
                        <h3 class="serif-font fw-bold text-white">ALIZE TRAVEL</h3>
                        <p class="text-white-50">Fransa ve Paris Turist Rehberi</p>
                    </div>
                    <p class="text-white-50">
                        Alize Travel ile Fransa'yı farklı bir şekilde keşfedin. Paris, Lyon, Nice, Cannes, Versailles ve diğer ikonik yerlerde özel deneyimler.
                    </p>
                    <a href="iletisim" class="btn btn-dark-custom">İLETİŞİME GEÇİN</a>
                </div>
                
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h5 class="text-white mb-3">İletişim</h5>
                    <p class="text-white-50 mb-2">
                        <i class="fas fa-phone me-2"></i>+33 7 69 91 11 24
                    </p>
                    <p class="text-white-50 mb-2">
                        <i class="fas fa-envelope me-2"></i>info@alizetravel.com
                    </p>
                    <p class="text-white-50">
                        <i class="fas fa-map-marker-alt me-2"></i>9 Rue du Lieutenant d'Estienne d'Orves, 94700 Maisons Alfort, France
                    </p>
                </div>
                
                <div class="col-lg-4">
                    <h5 class="text-white mb-3">Bizi Takip Edin</h5>
                    <div class="mb-3">
                        <a href="https://www.instagram.com/alizetravelparis" class="social-icon me-3"><i class="fab fa-instagram"></i></a>
                        <a href="https://wa.me/33769911124?text=Merhaba!%20Alize%20Travel%20hakk%C4%B1nda%20bilgi%20almak%20istiyorum." class="social-icon"><i class="fab fa-whatsapp"></i></a>
                    </div>
                    <p class="text-white-50 small">
                        Türkçe, İngilizce, Fransızca dillerinde hizmet
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bottom Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">© Copyright 2024 Alize Travel. Designed by kodix.net | Şartlar ve Koşullar | Gizlilik Politikası</small>
                </div>
                <div class="col-md-6 text-end">
                    <a href="https://www.instagram.com/alizetravelparis" class="social-icon"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/33769911124?text=Merhaba!%20Alize%20Travel%20hakk%C4%B1nda%20bilgi%20almak%20istiyorum." class="social-icon"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
