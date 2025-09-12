<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/blog_manager.php';

$blogManager = new BlogManager();

// Sayfalama
$page = $_GET['page'] ?? 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Blog yazılarını getir
$posts = $blogManager->getPublishedPosts($limit, $offset);

// Toplam yazı sayısı
$totalPosts = count($blogManager->getPublishedPosts());
$totalPages = ceil($totalPosts / $limit);

// Popüler yazılar
$popularPosts = $blogManager->getPopularPosts(3);

// Son yazılar
$recentPosts = $blogManager->getRecentPosts(3);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Alize Travel | VIP Seyahatler için Fransa ve Paris Rehberi</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Paris ve Fransa hakkında uzman rehberimizden blog yazıları. Seyahat ipuçları, müze rehberleri, kültürel deneyimler ve daha fazlası.">
    <meta name="keywords" content="Paris blog, Fransa seyahat, müze rehberi, seyahat ipuçları, Dr. Mehmet Kürkçü, Paris deneyimleri">
    <meta name="author" content="Alize Travel">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Turkish">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Blog - Alize Travel">
    <meta property="og:description" content="Paris ve Fransa hakkında uzman rehberimizden blog yazıları.">
    <meta property="og:image" content="https://alizetravel.com/assets/images/alize-slider.jpg">
    <meta property="og:url" content="https://alizetravel.com/blog">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Alize Travel">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://alizetravel.com/blog">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        .blog-hero {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            color: white;
            padding: 4rem 0;
        }
        .blog-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
            height: 100%;
        }
        .blog-card:hover {
            transform: translateY(-5px);
        }
        .blog-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .blog-content {
            padding: 1.5rem;
        }
        .blog-meta {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }
        .blog-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }
        .blog-excerpt {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .blog-read-more {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }
        .blog-read-more:hover {
            color: #5a6fd8;
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
        .pagination {
            justify-content: center;
            margin-top: 3rem;
        }
        .page-link {
            border-radius: 8px;
            margin: 0 2px;
            border: none;
            color: #667eea;
        }
        .page-link:hover {
            background-color: #667eea;
            color: white;
        }
        .page-item.active .page-link {
            background-color: #667eea;
            border-color: #667eea;
        }
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
    <section class="blog-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h1 class="serif-font display-4 fw-bold mb-3">Blog</h1>
                    <p class="lead mb-0">Paris ve Fransa hakkında uzman rehberimizden deneyimler, ipuçları ve öneriler</p>
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
                    <?php if (empty($posts)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                            <h3 class="text-muted">Henüz blog yazısı yok</h3>
                            <p class="text-muted">Yakında harika içeriklerle burada olacağız!</p>
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php foreach ($posts as $post): ?>
                                <div class="col-md-6">
                                    <article class="blog-card">
                                        <?php if ($post['featured_image']): ?>
                                            <img src="/assets/images/tours/<?php echo htmlspecialchars($post['featured_image']); ?>" 
                                                 alt="<?php echo htmlspecialchars($post['title']); ?>" class="blog-image">
                                        <?php else: ?>
                                            <div class="blog-image bg-light d-flex align-items-center justify-content-center">
                                                <i class="fas fa-image fa-2x text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="blog-content">
                                            <div class="blog-meta">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo date('d.m.Y', strtotime($post['published_at'])); ?>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-user me-1"></i>
                                                <?php echo htmlspecialchars($post['author_name']); ?>
                                                <span class="mx-2">•</span>
                                                <i class="fas fa-eye me-1"></i>
                                                <?php echo $post['views']; ?>
                                            </div>
                                            
                                            <h2 class="blog-title">
                                                <a href="blog/<?php echo $post['slug']; ?>" class="text-decoration-none text-dark">
                                                    <?php echo htmlspecialchars($post['title']); ?>
                                                </a>
                                            </h2>
                                            
                                            <?php if ($post['excerpt']): ?>
                                                <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
                                            <?php endif; ?>
                                            
                                            <a href="blog/<?php echo $post['slug']; ?>" class="blog-read-more">
                                                Devamını Oku <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </article>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <nav aria-label="Blog sayfalama">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">
                                                <i class="fas fa-chevron-left"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    
                                    <?php if ($page < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">
                                                <i class="fas fa-chevron-right"></i>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
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
                    
                    <!-- Son Yazılar -->
                    <?php if (!empty($recentPosts)): ?>
                        <div class="sidebar-card">
                            <h3 class="sidebar-title">
                                <i class="fas fa-clock me-2"></i>Son Yazılar
                            </h3>
                            <?php foreach ($recentPosts as $recentPost): ?>
                                <div class="sidebar-post">
                                    <?php if ($recentPost['featured_image']): ?>
                                        <img src="/assets/images/tours/<?php echo htmlspecialchars($recentPost['featured_image']); ?>" 
                                             alt="<?php echo htmlspecialchars($recentPost['title']); ?>" class="sidebar-post-image">
                                    <?php else: ?>
                                        <div class="sidebar-post-image bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="sidebar-post-content">
                                        <h4 class="sidebar-post-title">
                                            <a href="blog/<?php echo $recentPost['slug']; ?>" class="text-decoration-none text-dark">
                                                <?php echo htmlspecialchars($recentPost['title']); ?>
                                            </a>
                                        </h4>
                                        <div class="sidebar-post-meta">
                                            <?php echo date('d.m.Y', strtotime($recentPost['published_at'])); ?>
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
