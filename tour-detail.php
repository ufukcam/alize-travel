<?php
require_once 'includes/config.php';
require_once 'includes/tour_manager.php';

// Tur ID'sini al
$tour_id = $_GET['id'] ?? null;

if (!$tour_id) {
    header('Location: index.html');
    exit;
}

$tourManager = new TourManager();
$tour = $tourManager->getTourById($tour_id);

if (!$tour || !$tour['is_active']) {
    header('Location: index.html');
    exit;
}

// Kategoriler
$categories = [
    'museums' => 'Müzeler',
    'thematic' => 'Tematik Paris',
    'surroundings' => 'Paris Çevresi',
    'france' => 'Fransa Turları',
    'normandiya' => 'Normandiya Turları'
];

// Alt kategoriler
$subcategories = [
    'museums' => 'Müzeler',
    'thematic_paris' => 'Tematik Paris: Farklı Mahalleler',
    'day_tours' => 'Günlük Turlar',
    'france_tours' => 'Fransa Geneli',
    'normandiya_tours' => 'Normandiya Turları'
];

// Zorluk seviyeleri
$difficulties = [
    'Kolay' => 'bg-success',
    'Orta' => 'bg-warning',
    'Zor' => 'bg-danger'
];

// Benzer turları getir (aynı kategoriden)
$similarTours = $tourManager->getToursByCategory($tour['category']);
$similarTours = array_filter($similarTours, function($t) use ($tour_id) {
    return $t['id'] != $tour_id;
});
$similarTours = array_slice($similarTours, 0, 4); // En fazla 4 benzer tur
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tour['title']); ?> - Alize Travel | VIP Seyahatler</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        .tour-hero {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            color: white;
            padding: 4rem 0;
        }
        .tour-image {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            height: 100%;
            transition: transform 0.3s;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .highlight-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            height: 100%;
        }
        .booking-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            position: sticky;
            top: 120px;
        }
        .vip-badge {
            background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
            color: #333;
            font-weight: bold;
        }
        .tour-meta {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin: 1.5rem 0;
        }
        .tour-meta .badge {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .highlights-list {
            list-style: none;
            padding: 0;
        }
        .highlights-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .highlights-list li:last-child {
            border-bottom: none;
        }
        .highlights-list li:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-right: 0.5rem;
        }
        .services-list {
            list-style: none;
            padding: 0;
        }
        .services-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #eee;
        }
        .services-list li:last-child {
            border-bottom: none;
        }
        .services-list li:before {
            content: "✓";
            color: #28a745;
            font-weight: bold;
            margin-right: 0.5rem;
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
            <a class="navbar-brand" href="index.php">
                <div>ALIZE TRAVEL</div>
                <div class="subtitle">VIP SEYAHATLER</div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about-us.html">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tours.php">Programlar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Hizmetlerimiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact-us.html">İletişim</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="tour-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="serif-font display-4 fw-bold mb-3"><?php echo htmlspecialchars($tour['title']); ?></h1>
                    <?php if ($tour['subtitle']): ?>
                        <h2 class="h4 text-white-50 mb-3"><?php echo htmlspecialchars($tour['subtitle']); ?></h2>
                    <?php endif; ?>
                    <p class="lead mb-4"><?php echo htmlspecialchars($tour['description']); ?></p>
                    
                    <div class="tour-meta">
                        <?php if ($tour['duration']): ?>
                            <span class="badge bg-primary"><i class="fas fa-clock me-1"></i><?php echo htmlspecialchars($tour['duration']); ?></span>
                        <?php endif; ?>
                        <?php if ($tour['group_size']): ?>
                            <span class="badge bg-success"><i class="fas fa-users me-1"></i><?php echo htmlspecialchars($tour['group_size']); ?></span>
                        <?php endif; ?>
                        <?php if ($tour['rating']): ?>
                            <span class="badge bg-warning"><i class="fas fa-star me-1"></i><?php echo $tour['rating']; ?></span>
                        <?php endif; ?>
                        <?php if ($tour['difficulty']): ?>
                            <span class="badge <?php echo $difficulties[$tour['difficulty']] ?? 'bg-secondary'; ?>">
                                <i class="fas fa-signal me-1"></i><?php echo htmlspecialchars($tour['difficulty']); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <a href="#booking" class="btn btn-light me-3">Rezervasyon Yap</a>
                    <a href="contact-us.html" class="btn btn-outline-light">İletişime Geç</a>
                </div>
                <div class="col-lg-6">
                    <?php if ($tour['image']): ?>
                        <img src="assets/images/tours/<?php echo htmlspecialchars($tour['image']); ?>" 
                             alt="<?php echo htmlspecialchars($tour['title']); ?>" class="img-fluid tour-image">
                    <?php else: ?>
                        <div class="tour-image bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Tour Details -->
    <section class="section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Tour Overview -->
                    <div class="tour-overview mb-5">
                        <h2 class="serif-font display-6 fw-bold mb-4">Tur Hakkında</h2>
                        <p class="lead mb-4"><?php echo htmlspecialchars($tour['description']); ?></p>
                        
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="feature-card text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-user-tie fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold">Uzman Rehber</h5>
                                    <p class="text-muted"><?php echo htmlspecialchars($tour['guide_name']); ?> eşliğinde</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-card text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-star fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold">Kişiye Özel</h5>
                                    <p class="text-muted">Size özel hazırlanan rota ile keyifli ve rahat ziyaret</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tour Highlights -->
                    <?php if ($tour['highlights']): ?>
                    <div class="tour-highlights mb-5">
                        <h2 class="serif-font display-6 fw-bold mb-4">Turda Sizi Bekleyenler</h2>
                        <div class="row g-4">
                            <?php 
                            $highlights = explode("\n", $tour['highlights']);
                            foreach ($highlights as $highlight):
                                if (trim($highlight)):
                            ?>
                            <div class="col-lg-6 col-md-12">
                                <div class="highlight-card">
                                    <div class="highlight-header d-flex align-items-center mb-3">
                                        <div class="highlight-icon-wrapper me-3">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars(trim($highlight)); ?></h5>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Why Choose Us -->
                    <div class="why-choose-us mb-5">
                        <h2 class="serif-font display-6 fw-bold mb-4">Neden Bizimle Tur?</h2>
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="feature-card text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-user-graduate fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold">Uzman Rehber Eşliğinde</h5>
                                    <p class="text-muted"><?php echo htmlspecialchars($tour['guide_expertise']); ?> uzmanlığında, deneyimli bir rehber ile turun en önemli noktalarını keşfedin.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="feature-card text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-heart fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold">Kişiye Özel Deneyim</h5>
                                    <p class="text-muted">Sadece standart rotaları değil, dilediğiniz yerleri derinlikli bir anlatımla dinleyin.</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="feature-card text-center">
                                    <div class="feature-icon mb-3">
                                        <i class="fas fa-crown fa-2x text-primary"></i>
                                    </div>
                                    <h5 class="fw-bold">Lüks ve Konfor</h5>
                                    <p class="text-muted">Size özel hazırlanan rota ile keyifli ve rahat bir ziyaret gerçekleştirin.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ideal For -->
                    <?php if ($tour['ideal_for']): ?>
                    <div class="ideal-for mb-5">
                        <h2 class="serif-font display-6 fw-bold mb-4">Kimler İçin İdeal?</h2>
                        <div class="row g-3">
                            <?php 
                            $idealFor = explode("\n", $tour['ideal_for']);
                            foreach ($idealFor as $ideal):
                                if (trim($ideal)):
                            ?>
                            <div class="col-md-6">
                                <div class="ideal-item d-flex align-items-center p-3 border rounded-3">
                                    <i class="fas fa-check text-primary me-3"></i>
                                    <span><?php echo htmlspecialchars(trim($ideal)); ?></span>
                                </div>
                            </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Tour Options -->
                    <?php if ($tour['tour_options']): ?>
                    <div class="tour-options mb-5">
                        <h2 class="serif-font display-6 fw-bold mb-4">Tur Seçenekleri</h2>
                        <div class="row g-4">
                            <div class="col-lg-6">
                                <div class="option-card p-4 border rounded-3 h-100">
                                    <h5 class="fw-bold text-primary mb-3">Tur Seçenekleri</h5>
                                    <ul class="list-unstyled">
                                        <?php 
                                        $options = explode("\n", $tour['tour_options']);
                                        foreach ($options as $option):
                                            if (trim($option)):
                                        ?>
                                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i><?php echo htmlspecialchars(trim($option)); ?></li>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Booking Card -->
                    <div class="booking-card p-4" id="booking">
                        <h3 class="serif-font h4 fw-bold mb-3">VIP Tur Rezervasyonu</h3>
                        <div class="price-info mb-3">
                            <div class="vip-badge mb-2">
                                <span class="badge vip-badge px-3 py-2">
                                    <i class="fas fa-crown me-2"></i>VIP HİZMET
                                </span>
                            </div>
                            <p class="text-muted mb-0">Fiyat için lütfen iletişime geçin</p>
                            <small class="text-muted">Kişiye özel fiyatlandırma</small>
                        </div>
                        
                        <div class="tour-details mb-4">
                            <?php if ($tour['duration']): ?>
                            <div class="detail-item d-flex justify-content-between mb-2">
                                <span><i class="fas fa-clock me-2"></i>Tur Süresi:</span>
                                <span class="fw-bold"><?php echo htmlspecialchars($tour['duration']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($tour['difficulty']): ?>
                            <div class="detail-item d-flex justify-content-between mb-2">
                                <span><i class="fas fa-signal me-2"></i>Zorluk:</span>
                                <span class="fw-bold"><?php echo htmlspecialchars($tour['difficulty']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($tour['group_size']): ?>
                            <div class="detail-item d-flex justify-content-between mb-2">
                                <span><i class="fas fa-users me-2"></i>Grup:</span>
                                <span class="fw-bold"><?php echo htmlspecialchars($tour['group_size']); ?></span>
                            </div>
                            <?php endif; ?>
                            
                            <div class="detail-item d-flex justify-content-between mb-2">
                                <span><i class="fas fa-user-graduate me-2"></i>Rehber:</span>
                                <span class="fw-bold"><?php echo htmlspecialchars($tour['guide_name']); ?></span>
                            </div>
                            
                            <div class="detail-item d-flex justify-content-between mb-2">
                                <span><i class="fas fa-certificate me-2"></i>Uzmanlık:</span>
                                <span class="fw-bold"><?php echo htmlspecialchars($tour['guide_expertise']); ?></span>
                            </div>
                        </div>

                        <?php if ($tour['included_services']): ?>
                        <div class="included-services mb-4">
                            <h6 class="fw-bold mb-3">Dahil Olanlar:</h6>
                            <ul class="services-list">
                                <?php 
                                $services = explode("\n", $tour['included_services']);
                                foreach ($services as $service):
                                    if (trim($service)):
                                ?>
                                <li><?php echo htmlspecialchars(trim($service)); ?></li>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <a href="contact-us.html" class="btn btn-primary w-100 mb-3">Rezervasyon Yap</a>
                        <a href="https://wa.me/33769911124" class="btn btn-success w-100">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp ile İletişim
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Related Tours -->
    <?php if (!empty($similarTours)): ?>
    <section class="section-padding bg-light">
        <div class="container">
            <h2 class="serif-font display-6 fw-bold mb-5 text-center">Benzer Turlar</h2>
            <div class="row g-4">
                <?php foreach ($similarTours as $similarTour): ?>
                <div class="col-lg-3 col-md-6">
                    <div class="tour-card">
                        <?php if ($similarTour['image']): ?>
                            <img src="assets/images/tours/<?php echo htmlspecialchars($similarTour['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($similarTour['title']); ?>" class="tour-image">
                        <?php else: ?>
                            <div class="tour-image bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        <?php endif; ?>
                        <div class="tour-overlay">
                            <h4 class="tour-title"><?php echo htmlspecialchars($similarTour['title']); ?></h4>
                            <a href="tour-detail.php?id=<?php echo $similarTour['id']; ?>" class="tour-link">Daha Fazlası</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

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
                    <a href="contact-us.html" class="btn btn-dark-custom">İLETİŞİME GEÇİN</a>
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
                        Türkçe, İngilizce, Fransızca ve Almanca dillerinde hizmet
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
    
    <!-- Custom JS -->
    <script>
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add active class to current nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPage = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPage.split('/').pop()) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
