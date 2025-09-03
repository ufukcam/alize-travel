<?php
require_once 'includes/config.php';
require_once 'includes/tour_manager.php';

$tourManager = new TourManager();

// Kategorilere göre turları getir
$museumTours = $tourManager->getToursByCategory('museums');
$thematicTours = $tourManager->getToursByCategory('thematic');
$surroundingTours = $tourManager->getToursByCategory('surroundings');
$franceTours = $tourManager->getToursByCategory('france');

// Kategoriler
$categories = [
    'museums' => 'Müzeler',
    'thematic' => 'Tematik Paris',
    'surroundings' => 'Paris Çevresi',
    'france' => 'Fransa Turları'
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alize Travel | VIP Seyahatler için Fransa ve Paris Rehberi</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
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
                        <a class="nav-link active" href="/">Ana Sayfa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="hakkimizda">Hakkımızda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="turlar">Programlar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.html">Hizmetlerimiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#avis">Müşteri Yorumları</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact-us.html">İletişim</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="serif-font display-4 fw-bold mb-4">Fransa'yı Farklı Bir Şekilde Keşfedin</h1>
                    <p class="lead mb-4">VIP seyahat deneyimi ile Paris, Lyon, Nice, Cannes, Versailles ve diğer ikonik yerlerde özel anlar yaşayın.</p>
                    <div class="hero-buttons">
                        <a href="#tours" class="btn btn-primary-custom me-3">Turları Keşfet</a>
                        <a href="contact-us.html" class="btn btn-outline-primary">İletişime Geç</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/alize-slider.jpg" alt="Alize Travel" class="img-fluid rounded-3 shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-padding" id="a-propos">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="serif-font display-5 fw-bold mb-4">Hakkımızda</h2>
                    <p class="lead mb-4">
                        Dr. Mehmet Kürkçü, 1989'dan beri turizm sektöründe, 1993'ten itibaren profesyonel rehber olarak çalışmaktadır. Paris-Sorbonne Üniversitesi'nde Sanat Tarihi ve Arkeoloji doktorası yapmış, Akdeniz havzasındaki sayısız arkeolojik alanı kazmış ve dünyanın en prestijli müzelerinde rehberlik yapmıştır.
                    </p>
                    <div class="about-features">
                        <div class="feature-item mb-3">
                            <i class="fas fa-graduation-cap text-primary me-3"></i>
                            <span>Sanat Tarihi Doktorası</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-globe text-primary me-3"></i>
                            <span>30+ Yıl Deneyim</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-award text-primary me-3"></i>
                            <span>Profesyonel Rehber</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/tours.jpg" alt="Turlar" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding bg-light" id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="serif-font display-5 fw-bold mb-4">Hizmetlerimiz</h2>
                    <p class="lead mb-4">
                        Özel turlar, kültürel deneyimler ve lüks seyahat hizmetleri sunuyoruz.
                    </p>
                    <div class="service-features">
                        <div class="feature-item mb-3">
                            <div class="feature-icon">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Özel Turlar</h5>
                                <p>Kişiye özel hazırlanan rota ve programlar</p>
                            </div>
                        </div>
                        <div class="feature-item mb-3">
                            <div class="feature-icon">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div class="feature-text">
                                <h5>Uzman Rehber</h5>
                                <p>Sanat tarihi doktoralı profesyonel rehber</p>
                            </div>
                        </div>
                        <div class="feature-item mb-3">
                            <div class="feature-icon">
                                <i class="fas fa-crown"></i>
                            </div>
                            <div class="feature-text">
                                <h5>VIP Hizmet</h5>
                                <p>Lüks ve konfor odaklı seyahat deneyimi</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/private-tours.jpg" alt="Özel Turlar" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Tours Section -->
    <section class="section-padding featured-tours" id="tours">
        <div class="container">
            <!-- Paris Tours -->
            <?php if (!empty($museumTours)): ?>
            <div class="tour-category mb-5">
                <h2 class="serif-font display-5 fw-bold mb-4 text-center">Paris Turları</h2>
                
                <!-- Museums -->
                <div class="tour-subcategory mb-5">
                    <h3 class="serif-font h3 fw-bold mb-4">Müzeler</h3>
                    <div class="row g-4">
                        <?php foreach ($museumTours as $tour): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="tour-card">
                                <?php if ($tour['image']): ?>
                                    <img src="assets/images/tours/<?php echo htmlspecialchars($tour['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
                                <?php else: ?>
                                    <div class="tour-image bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="tour-overlay">
                                    <h4 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h4>
                                    <a href="tour-detail.php?id=<?php echo $tour['id']; ?>" class="tour-link">Daha Fazlası</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Thematic Paris -->
            <?php if (!empty($thematicTours)): ?>
            <div class="tour-category mb-5">
                <h2 class="serif-font display-5 fw-bold mb-4 text-center">Tematik Paris Turları</h2>
                <div class="tour-subcategory mb-5">
                    <h3 class="serif-font h3 fw-bold mb-4">Farklı Mahalleler</h3>
                    <div class="row g-4">
                        <?php foreach ($thematicTours as $tour): ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="tour-card">
                                <?php if ($tour['image']): ?>
                                    <img src="assets/images/tours/<?php echo htmlspecialchars($tour['image']); ?>" 
                                         alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
                                <?php else: ?>
                                    <div class="tour-image bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fas fa-image fa-2x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="tour-overlay">
                                    <h4 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h4>
                                    <a href="tour-detail.php?id=<?php echo $tour['id']; ?>" class="tour-link">Daha Fazlası</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Paris Surroundings Day Tours -->
            <?php if (!empty($surroundingTours)): ?>
            <div class="tour-category mb-5">
                <h2 class="serif-font display-5 fw-bold mb-4 text-center">Paris Çevresi Günlük Turlar</h2>
                <div class="row g-4">
                    <?php foreach ($surroundingTours as $tour): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="tour-card">
                            <?php if ($tour['image']): ?>
                                <img src="assets/images/tours/<?php echo htmlspecialchars($tour['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
                            <?php else: ?>
                                <div class="tour-image bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="tour-overlay">
                                <h4 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h4>
                                <a href="tour-detail.php?id=<?php echo $tour['id']; ?>" class="tour-link">Daha Fazlası</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- France Tours -->
            <?php if (!empty($franceTours)): ?>
            <div class="tour-category mb-5">
                <h2 class="serif-font display-5 fw-bold mb-4 text-center">Fransa Turları</h2>
                <div class="row g-4">
                    <?php foreach ($franceTours as $tour): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="tour-card">
                            <?php if ($tour['image']): ?>
                                <img src="assets/images/tours/<?php echo htmlspecialchars($tour['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
                            <?php else: ?>
                                <div class="tour-image bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            <?php endif; ?>
                            <div class="tour-overlay">
                                <h4 class="tour-title"><?php echo htmlspecialchars($tour['title']); ?></h4>
                                <a href="tour-detail.php?id=<?php echo $tour['id']; ?>" class="tour-link">Daha Fazlası</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="section-padding bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h2 class="serif-font display-5 fw-bold mb-4">Neden Alize Travel?</h2>
                    <p class="lead mb-4">
                        Koşuşturmacalardan uzak, benzersiz aktivitelerle Paris'in tadını çıkarın. Sadece size özel, eşsiz anılar yaratın.
                    </p>
                    <div class="why-choose-features">
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <span>Kişiye özel programlar</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <span>Uzman rehber eşliği</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <span>VIP hizmet kalitesi</span>
                        </div>
                        <div class="feature-item mb-3">
                            <i class="fas fa-check-circle text-success me-3"></i>
                            <span>Esnek rezervasyon</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <img src="assets/images/culture.jpg" alt="Kültür" class="img-fluid rounded-3">
                        </div>
                        <div class="col-6">
                            <img src="assets/images/gastronomy.jpg" alt="Gastronomi" class="img-fluid rounded-3">
                        </div>
                        <div class="col-6">
                            <img src="assets/images/architecture.jpg" alt="Mimari" class="img-fluid rounded-3">
                        </div>
                        <div class="col-6">
                            <img src="assets/images/nature.jpg" alt="Doğa" class="img-fluid rounded-3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Custom Programs -->
    <section class="section-padding">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="serif-font display-5 fw-bold mb-4">Kişiye Özel Programlar</h2>
                    <p class="lead mb-4">
                        Bu kişiye özel program, Paris'i kendi bakış açınıza göre keşfetmeniz için tasarlanıyor; ister bir gün, ister hafta sonu, ister uzun bir tatil boyunca.
                    </p>
                    <div class="custom-features">
                        <div class="feature-item mb-3">
                            <h4 class="fw-bold mb-2">Kişiye Özel Program</h4>
                            <p class="text-muted mb-0">Kendi temponuzda, huzur içinde benzersiz bir Paris deneyimi programı oluşturuyoruz.</p>
                        </div>
                        <div class="feature-item mb-3">
                            <h4 class="fw-bold mb-2">Esnek Zamanlama</h4>
                            <p class="text-muted mb-0">Size uygun saatlerde, istediğiniz sürede turlar düzenliyoruz.</p>
                        </div>
                        <div class="feature-item mb-3">
                            <h4 class="fw-bold mb-2">Özel İlgi Alanları</h4>
                            <p class="text-muted mb-0">Sanat, tarih, gastronomi veya mimari - hangi konuya ilgi duyarsanız duyun, size özel program hazırlıyoruz.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/paris-vip.jpg" alt="Paris VIP" class="img-fluid rounded-3">
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="section-padding bg-light" id="avis">
        <div class="container">
            <h2 class="serif-font display-5 fw-bold mb-5 text-center">Müşteri Yorumları</h2>
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="testimonial-card p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"Dr. Mehmet Bey'in Louvre rehberliği muhteşemdi. Her eserin arkasındaki hikayeyi öğrenmek çok değerliydi."</p>
                        <div class="testimonial-author">
                            <strong>Ayşe K.</strong>
                            <small class="text-muted d-block">İstanbul</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"Versailles turu gerçekten özeldi. Kalabalıktan uzak, sakin bir atmosferde tarihi yaşadık."</p>
                        <div class="testimonial-author">
                            <strong>Mehmet A.</strong>
                            <small class="text-muted d-block">Ankara</small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="testimonial-card p-4 bg-white rounded-3 shadow-sm h-100">
                        <div class="stars mb-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <p class="mb-3">"Montmartre'da sanatçıların mahallesini keşfetmek harika bir deneyimdi. Çok teşekkürler!"</p>
                        <div class="testimonial-author">
                            <strong>Fatma S.</strong>
                            <small class="text-muted d-block">İzmir</small>
                        </div>
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
                    <a href="#" class="social-icon"><i class="fas fa-instagram"></i></a>
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
