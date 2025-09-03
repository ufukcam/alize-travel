<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/tour_manager.php';

// Tüm turları getir
$db = new Database();
$tourManager = new TourManager($db);

try {
    $tours = $tourManager->getAllToursOrdered();
} catch (Exception $e) {
    $tours = [];
    $error = 'Turlar yüklenirken bir hata oluştu.';
}

// Kategorilere göre turları grupla
$categorizedTours = [];
if ($tours) {
    foreach ($tours as $tour) {
        $category = $tour['category'];
        if (!isset($categorizedTours[$category])) {
            $categorizedTours[$category] = [];
        }
        $categorizedTours[$category][] = $tour;
    }
}

// Kategori isimlerini Türkçe'ye çevir
$categoryNames = [
    'museums' => 'Müze Turları',
    'thematic' => 'Tematik Turlar',
    'surroundings' => 'Çevre Turları',
    'france' => 'Fransa Turları',
    'normandiya' => 'Normandiya Turları'
];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paris ve Fransa Turları | Alize Travel - VIP Seyahat Rehberi</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Paris ve Fransa'nın en güzel yerlerini keşfedin. Müze turları, tematik turlar, şato gezileri, Normandiya turları. Dr. Mehmet Kürkçü rehberliğinde unutulmaz deneyimler.">
    <meta name="keywords" content="Paris turları, Fransa turları, müze turları, Louvre, Orsay, Versailles, şato turları, Normandiya, tematik turlar, özel rehber">
    <meta name="author" content="Alize Travel">
    <meta name="robots" content="index, follow">
    <meta name="language" content="Turkish">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="Paris ve Fransa Turları | Alize Travel">
    <meta property="og:description" content="Paris ve Fransa'nın en güzel yerlerini keşfedin. Müze turları, tematik turlar, şato gezileri.">
    <meta property="og:image" content="https://alizetravel.com/assets/images/tours.jpg">
    <meta property="og:url" content="https://alizetravel.com/turlar/">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Alize Travel">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="https://alizetravel.com/turlar/">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .tour-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .tour-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .tour-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        .category-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }
        .rating {
            color: #ffc107;
        }
        .duration-badge {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 3rem;
        }
        .filter-buttons {
            margin-bottom: 2rem;
        }
        .filter-btn {
            margin: 0.25rem;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
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
                <div>Alize Travel</div>
                <div class="subtitle">FRANSA & PARİS</div>
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
                        <a class="nav-link" href="iletisim">İletişim</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h1 class="serif-font display-5 fw-bold mb-3">Tüm Turlarımız</h1>
            <p class="lead mb-0">Paris ve Fransa'nın en güzel yerlerini keşfedin</p>
        </div>
    </section>

    <!-- Filter Buttons -->
    <div class="container">
        <div class="filter-buttons text-center">
            <button class="btn btn-outline-primary filter-btn active" data-filter="all">
                <i class="fas fa-globe me-2"></i>Tümü
            </button>
            <?php foreach ($categoryNames as $key => $name): ?>
                <button class="btn btn-outline-primary filter-btn" data-filter="<?php echo $key; ?>">
                    <i class="fas fa-map-marker-alt me-2"></i><?php echo $name; ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Tours Section -->
    <section class="section-padding">
        <div class="container">
            <?php if (empty($categorizedTours)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h3>Henüz tur bulunmuyor</h3>
                    <p class="text-muted">Yakında harika turlarımızı ekleyeceğiz!</p>
                </div>
            <?php else: ?>
                <?php foreach ($categorizedTours as $category => $tours): ?>
                    <div class="category-section mb-5" data-category="<?php echo $category; ?>">
                        <h2 class="serif-font display-5 fw-bold mb-4 text-center">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            <?php echo $categoryNames[$category]; ?>
                        </h2>
                        <div class="row g-4">
                            <?php foreach ($tours as $tour): ?>
                                <div class="col-lg-4 col-md-6 tour-item" data-category="<?php echo $category; ?>">
                                    <div class="card tour-card h-100">
                                        <div class="position-relative">
                                            <?php
                                            // Basit resim seçimi
                                            $imagePath = '';
                                            
                                            // Default resim (splash resimlerinden)
                                            $defaultImage = 'assets/images/alize-slider.jpg';
                                            
                                            if (!empty($tour['image'])) {
                                                // Veritabanında resim yolu varsa
                                                $dbImagePath = 'assets/images/tours/' . $tour['image'];
                                                if (file_exists($dbImagePath)) {
                                                    $imagePath = $dbImagePath; // DB'deki resmi kullan
                                                } else {
                                                    $imagePath = $defaultImage; // Default resmi kullan
                                                }
                                            } else {
                                                // Veritabanında resim yolu yok, default resmi kullan
                                                $imagePath = $defaultImage;
                                            }
                                            ?>
                                            <img src="<?php echo $imagePath; ?>" 
                                                 class="tour-image" 
                                                 alt="<?php echo htmlspecialchars($tour['title']); ?>"
                                                 onerror="this.src='<?php echo $defaultImage; ?>'">
                                            
                                            <!-- Category Badge -->
                                            <span class="badge category-badge bg-primary">
                                                <?php echo $categoryNames[$category]; ?>
                                            </span>
                                            
                                            <!-- Duration Badge -->
                                            <span class="duration-badge position-absolute bottom-0 start-0 m-3">
                                                <i class="fas fa-clock me-1"></i><?php echo htmlspecialchars($tour['duration']); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title"><?php echo htmlspecialchars($tour['title']); ?></h5>
                                            
                                            <?php if ($tour['subtitle']): ?>
                                                <p class="card-text text-muted mb-2">
                                                    <?php echo htmlspecialchars($tour['subtitle']); ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <!-- Rating -->
                                            <div class="mb-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star rating <?php echo $i <= $tour['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                                <?php endfor; ?>
                                                <span class="ms-2 text-muted">(<?php echo $tour['rating']; ?>/5)</span>
                                            </div>
                                            
                                            <!-- Highlights -->
                                            <?php if ($tour['highlights']): ?>
                                                <p class="card-text small text-muted mb-3">
                                                    <i class="fas fa-star me-1"></i>
                                                    <?php 
                                                    $highlights = explode(',', $tour['highlights']);
                                                    echo htmlspecialchars(trim($highlights[0])) . (count($highlights) > 1 ? '...' : '');
                                                    ?>
                                                </p>
                                            <?php endif; ?>
                                            
                                            <!-- Button -->
                                            <div class="mt-auto">
                                                <a href="tur/<?php echo $tour['id']; ?>/<?php echo strtolower(str_replace([' ', 'ç', 'ğ', 'ı', 'ö', 'ş', 'ü'], ['-', 'c', 'g', 'i', 'o', 's', 'u'], $tour['title'])); ?>" 
                                                   class="btn btn-primary-custom w-100">
                                                    <i class="fas fa-info-circle me-2"></i>Detayları Gör
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
                        <a href="#" class="social-icon me-3"><i class="fab fa-instagram"></i></a>
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

    <!-- WhatsApp Floating Button -->
    <div class="whatsapp-float">
        <a href="https://wa.me/33769911124?text=Merhaba! Alize Travel hakkında bilgi almak istiyorum." 
           class="whatsapp-btn" 
           target="_blank" 
           rel="noopener noreferrer"
           title="WhatsApp ile iletişime geçin">
            <i class="fab fa-whatsapp"></i>
            <div class="whatsapp-bubble">Merhaba! Size nasıl yardımcı olabilirim? 💬</div>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const tourItems = document.querySelectorAll('.tour-item');
            const categorySections = document.querySelectorAll('.category-section');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    // Update active button
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    
                    // Show/hide tours based on filter
                    if (filter === 'all') {
                        categorySections.forEach(section => {
                            section.style.display = 'block';
                        });
                        tourItems.forEach(item => {
                            item.style.display = 'block';
                        });
                    } else {
                        categorySections.forEach(section => {
                            if (section.getAttribute('data-category') === filter) {
                                section.style.display = 'block';
                            } else {
                                section.style.display = 'none';
                            }
                        });
                    }
                });
            });
        });

        // Smooth scroll to top
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    </script>
</body>
</html>
