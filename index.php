<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/tour_manager.php';

// Turları getir
$db = new Database();
$tourManager = new TourManager($db);

try {
    $tours = $tourManager->getAllToursOrdered();
    
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
} catch (Exception $e) {
    $categorizedTours = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alize Travel - VIP Seyahatler için Fransa ve Paris Rehberi</title>
    
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
            <a class="navbar-brand" href="index.php">
                <div>Alize Travel</div>
                <div class="subtitle">FRANSA & PARİS</div>
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
                        <a class="nav-link" href="services.html">Hizmetlerimiz</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact-us.html">İletişim</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Slider Section -->
    <section class="hero-slider" id="accueil">
        <!-- Slide 1 -->
        <div class="slide active" style="background-image: url('https://d25tea7qfcsjlw.cloudfront.net/27932/modul/614189/e716.jpg')">
            <div class="slide-overlay">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="hero-title">
                                <div class="title-line-1">VIP Seyahatler için</div>
                                <div class="title-line-2">Fransa ve Paris Rehberi</div>
                            </div>
                            <p class="lead mb-5">Paris ve Fransa'nın zarafetini, sanatını ve lüksünü keşfetmek için</p>
                            <a href="#contact" class="mb-3 btn btn-primary-custom">İLETİŞİME GEÇİN</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="slide" style="background-image: url('assets/images/alize-slider.jpg')">
            <div class="slide-overlay">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="hero-title">
                                <div class="title-line-1">Lüks ve Zarafet</div>
                                <div class="title-line-2">Paris'in Gizli Hazineleri</div>
                            </div>
                            <p class="lead mb-5">Eiffel Kulesi'nden Champs-Élysées'e, Paris'in en özel mekanlarını keşfedin</p>
                            <a href="#services" class="btn btn-primary-custom">HİZMETLERİMİZİ GÖRÜN</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="slide" style="background-image: url('assets/images/versailles-1.jpg')">
            <div class="slide-overlay">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8 text-center">
                            <div class="hero-title">
                                <div class="title-line-1">Özel Turlar</div>
                                <div class="title-line-2">Versailles ve Şatolar</div>
                            </div>
                            <p class="lead mb-5">Fransa'nın en muhteşem şatolarında unutulmaz deneyimler yaşayın</p>
                            <a href="#about" class="btn btn-primary-custom">HAKKIMIZDA</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Arrows -->
        <button class="slider-nav prev" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-nav next" onclick="changeSlide(1)">
            <i class="fas fa-chevron-right"></i>
        </button>

        <!-- Pagination Dots -->
        <div class="pagination-dots">
            <span class="dot active" onclick="currentSlide(1)"></span>
            <span class="dot" onclick="currentSlide(2)"></span>
            <span class="dot" onclick="currentSlide(3)"></span>
        </div>
        
        <!-- WhatsApp Float Button -->
        <div class="whatsapp-float">
            <a href="https://wa.me/33769911124" target="_blank" class="whatsapp-btn">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section class="section-padding" id="a-propos">
        <div class="container">
            <!-- Paris ve Fransa VIP Geziler Section -->
            <div class="row justify-content-center mb-5">
                <div class="col-lg-10">
                    <div class="vip-intro-card p-5 rounded-3 shadow-sm bg-light">
                        <div class="text-center mb-4">
                            <div class="vip-badge mb-3">
                                <span class="badge bg-primary px-3 py-2 fs-6">VIP Deneyim</span>
                            </div>
                            <h2 class="serif-font display-6 fw-bold mb-4 text-primary">
                                Paris ve Fransa VIP Geziler
                            </h2>
                            <p class="h5 text-muted mb-0">Özel Rehber ile Kişiye Özgü Deneyimler</p>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="vip-feature d-flex align-items-start">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-eye text-gold fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Deneyimli Perspektif</h5>
                                        <p class="text-muted mb-0">
                                            Dünyanın farklı ülkelerinde rehberliğini yaptığım sayısız gezide çoğu zaman ayrıntılara gereken özenin gösterilmediğini, hizmetlerin gerçek anlamda kişiselleştirilmediğini gözlemledim.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="vip-feature d-flex align-items-start">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-star text-gold fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Özel Tasarım</h5>
                                        <p class="text-muted mb-0">
                                            İşte bu yüzden Paris ve Fransa VIP Geziler, Özel Rehber'i tasarladık. Amacımız; Paris ve Fransa'da size özel, titizlikle hazırlanmış ve gizliliğe önem veren bir hizmet sunmak.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="vip-feature d-flex align-items-start">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-gem text-gold fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Otantik Deneyimler</h5>
                                        <p class="text-muted mb-0">
                                            Tüm bilgi ve deneyimimizi, size otantik ve ayrıcalıklı anlar yaşatmak için kullanıyoruz. Göreceğiniz bölgeler ve mekânların gizli kalmış güzelliklerini sunuyoruz.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="vip-feature d-flex align-items-start">
                                    <div class="feature-icon me-3">
                                        <i class="fas fa-crown text-gold fs-4"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-2">Lüks & Zarafet</h5>
                                        <p class="text-muted mb-0">
                                            Her ziyaretin eşsiz ve unutulmaz bir deneyime dönüşmesi için çalışıyoruz. Lüks ve zarafeti bir araya getiren, tamamen kişiselleştirilmiş yolculuklar.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mt-4 pt-3 border-top">
                            <p class="h6 text-primary fw-bold mb-0">
                                <i class="fas fa-heart me-2"></i>
                                Sizi, lüks ve zarafeti bir araya getiren, tamamen kişiselleştirilmiş bir yolculuğa davet ediyoruz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-profile-image">
                        <img src="assets/images/mrmehmet.jpeg" 
                             alt="Mr. Mehmet" class="profile-image-about">
                        
                        <!-- Social Media Icons -->
                        <div class="social-media-icons-about">
                            <a href="https://www.instagram.com/dardanelli" class="social-icon-circle">
                                <i class="fab fa-instagram"></i>
                            </a> 
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <h2 class="serif-font display-5 fw-bold mb-4">Hakkımızda</h2>
                    <p class="lead mb-4">
                        Fransız yaşam tarzı ve olağanüstü seyahatler konusunda tutkulu olan Dr. Mehmet Kürkçü, sanat tarihi, arkeoloji ve kültür alanlarında uzmanlaşmış, 30+ yıllık deneyime sahip seçkin bir rehberdir. Size Fransa ve Paris'te özel deneyimler sunuyoruz, 4 dilde hizmet veriyoruz: Türkçe, İngilizce, Fransızca ve Almanca.
                    </p>
                    
                    <p class="mb-4">
                        Dr. Mehmet Kürkçü, 1989'dan beri turizm sektöründe, 1993'ten itibaren profesyonel rehber olarak çalışmaktadır. Paris-Sorbonne Üniversitesi'nde Sanat Tarihi ve Arkeoloji doktorası yapmış, Akdeniz havzasındaki sayısız arkeolojik alanı yerinde incelemiştir. Bugün tüm Fransa'da geçerli "Guide-Conférencier" belgesine sahip olarak, gezginlere yalnızca bilgiyi değil, kültürün ruhunu da aktarmaktadır.
                    </p>
                    
                    <a href="about-us.html" class="btn btn-primary-custom">Devamı</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="section-padding" id="services">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="serif-font display-5 fw-bold mb-4">Hizmetlerimiz</h2>
                <p class="lead">
                    Alize Travel, Paris, Lyon, Nice, Cannes ve Versailles gibi prestijli şehirlerde veya özel şatolar etrafında deneyiminizi kişiselleştirmenize olanak tanır. İşte en popüler aktivitelerin bir özeti.
                </p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="service-card">
                        <img src="assets/images/tours.jpg" 
                             alt="Visites">
                        <div class="card-body text-center p-4">
                            <h4 class="serif-font f w-bold">
                                <a href="tours.php" class="text-decoration-none text-dark">Turlar</a>
                            </h4>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="service-card">
                        <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" 
                             alt="Aktiviteler">
                        <div class="card-body text-center p-4">
                            <h4 class="serif-font fw-bold">
                                <a href="tours.php" class="text-decoration-none text-dark">Aktiviteler</a>
                            </h4>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="service-card">
                        <img src="assets/images/private-tours.jpg" 
                             alt="Özel Turlar">
                        <div class="card-body text-center p-4">
                            <h4 class="serif-font fw-bold">
                                <a href="tours.php" class="text-decoration-none text-dark">Özel Turlar</a>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <a href="services.html" class="btn btn-primary-custom">
                    <i class="fas fa-book-open me-2"></i>Tümünü Oku
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Tours Section - DİNAMİK -->
    <section class="section-padding featured-tours" id="tours">
        <div class="container">
            <?php if (!empty($categorizedTours)): ?>
                <!-- Paris Tours -->
                <?php if (isset($categorizedTours['museums'])): ?>
                <div class="tour-category mb-5">
                    <h2 class="serif-font display-5 fw-bold mb-4 text-center">Paris Turları</h2>
                    
                    <!-- Museums -->
                    <div class="tour-subcategory mb-5">
                        <h3 class="serif-font h3 fw-bold mb-4">Müzeler</h3>
                        <div class="row g-4">
                            <?php foreach ($categorizedTours['museums'] as $tour): ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="tour-card">
                                    <?php
                                    // Dinamik resim seçimi
                                    $imagePath = '';
                                    $defaultImage = 'assets/images/louvre.jpg';
                                    
                                    if (!empty($tour['image'])) {
                                        $dbImagePath = 'assets/images/tours/' . $tour['image'];
                                        if (file_exists($dbImagePath)) {
                                            $imagePath = $dbImagePath;
                                        } else {
                                            $imagePath = $defaultImage;
                                        }
                                    } else {
                                        $imagePath = $defaultImage;
                                    }
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
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
                <?php if (isset($categorizedTours['thematic'])): ?>
                <div class="tour-category mb-5">
                    <h3 class="serif-font h3 fw-bold mb-4">Tematik Paris: Farklı Mahalleler</h3>
                    <div class="row g-4">
                        <?php foreach ($categorizedTours['thematic'] as $tour): ?>
                        <div class="col-lg-3 col-md-6">
                            <div class="tour-card">
                                <?php
                                // Dinamik resim seçimi
                                $imagePath = '';
                                $defaultImage = 'assets/images/montmartre.jpg';
                                
                                if (!empty($tour['image'])) {
                                    $dbImagePath = 'assets/images/tours/' . $tour['image'];
                                    if (file_exists($dbImagePath)) {
                                        $imagePath = $dbImagePath;
                                    } else {
                                        $imagePath = $defaultImage;
                                    }
                                } else {
                                    $imagePath = $defaultImage;
                                }
                                ?>
                                <img src="<?php echo $imagePath; ?>" 
                                     alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
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
                
                <!-- Paris Surroundings Day Tours -->
                <?php if (isset($categorizedTours['surroundings'])): ?>
                <div class="tour-category mb-5">
                    <h2 class="serif-font display-5 fw-bold mb-4 text-center">Paris Çevresi Günlük Turlar</h2>
                    
                    <!-- Castles -->
                    <div class="tour-subcategory mb-5">
                       <!-- <h3 class="serif-font h3 fw-bold mb-4">Şatolar</h3> -->
                        <div class="row g-4">
                            <?php foreach ($categorizedTours['surroundings'] as $tour): ?>
                            <div class="col-lg-4">
                                <div class="tour-card">
                                    <?php
                                    // Dinamik resim seçimi
                                    $imagePath = '';
                                    $defaultImage = 'assets/images/versailles.jpg';
                                    
                                    if (!empty($tour['image'])) {
                                        $dbImagePath = 'assets/images/tours/' . $tour['image'];
                                        if (file_exists($dbImagePath)) {
                                            $imagePath = $dbImagePath;
                                        } else {
                                            $imagePath = $defaultImage;
                                        }
                                    } else {
                                        $imagePath = $defaultImage;
                                    }
                                    ?>
                                    <img src="<?php echo $imagePath; ?>" 
                                         alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
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
                
                <!-- France Tours -->
                <?php if (isset($categorizedTours['france'])): ?>
                <div class="tour-category">
                    <h2 class="serif-font display-5 fw-bold mb-4 text-center">Fransa Turları</h2>
                    <div class="row g-4">
                        <?php foreach ($categorizedTours['france'] as $tour): ?>
                        <div class="col-lg-3">
                            <div class="tour-card">
                                <?php
                                // Dinamik resim seçimi
                                $imagePath = '';
                                $defaultImage = 'assets/images/provence.jpg';
                                
                                if (!empty($tour['image'])) {
                                    $dbImagePath = 'assets/images/tours/' . $tour['image'];
                                    if (file_exists($dbImagePath)) {
                                        $imagePath = $dbImagePath;
                                    } else {
                                        $imagePath = $defaultImage;
                                    }
                                } else {
                                    $imagePath = $defaultImage;
                                }
                                ?>
                                <img src="<?php echo $imagePath; ?>" 
                                     alt="<?php echo htmlspecialchars($tour['title']); ?>" class="tour-image">
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
                
            <?php else: ?>
                <!-- Turlar yoksa statik içerik göster -->
                <div class="tour-category mb-5">
                    <h2 class="serif-font display-5 fw-bold mb-4 text-center">Paris Turları</h2>
                    
                    <!-- Museums -->
                    <div class="tour-subcategory mb-5">
                        <h3 class="serif-font h3 fw-bold mb-4">Müzeler</h3>
                        <div class="row g-4">
                            <div class="col-lg-4 col-md-6">
                                <div class="tour-card">
                                    <img src="assets/images/louvre.jpg" 
                                         alt="Louvre Müzesi" class="tour-image">
                                    <div class="tour-overlay">
                                        <h4 class="tour-title">Louvre Müzesi</h4>
                                        <a href="museum-tours/louvre-tour.html" class="tour-link">Daha Fazlası</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <div class="tour-card">
                                    <img src="assets/images/orsay.jpg" 
                                         alt="Orsay Müzesi" class="tour-image">
                                    <div class="tour-overlay">
                                        <h4 class="tour-title">Orsay Müzesi</h4>
                                        <a href="museum-tours/orsay-tour.html" class="tour-link">Daha Fazlası</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <div class="tour-card">
                                    <img src="assets/images/versailles.jpg" 
                                         alt="Versailles Merkezi" class="tour-image">
                                    <div class="tour-overlay">
                                        <h4 class="tour-title">Versailles Sarayı</h4>
                                        <a href="museum-tours/versailles-tour.html" class="tour-link">Daha Fazlası</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Diğer bölümler aynı kalacak -->
    <!-- Why Personalized Experience Section -->
    <section class="section-padding personalized-experience">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="serif-font display-5 fw-bold mb-4">Neden Kişiye Özel Deneyim?</h2>
                <p class="lead text-muted">Size özel tasarlanmış, unutulmaz Paris deneyimleri</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="experience-card">
                        <div class="experience-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h3 class="serif-font h4 fw-bold mb-3">Rahatlık</h3>
                        <p class="text-muted">
                            Kendi temponuza ve zevkinize uygun bir deneyim yaşayın. Acele etmeden, istediğiniz kadar zaman ayırarak Paris'in güzelliklerini keşfedin.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="experience-card">
                        <div class="experience-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h3 class="serif-font h4 fw-bold mb-3">Konfor</h3>
                        <p class="text-muted">
                            Özel rehberler, şoförler, şefler ve uzmanlar eşliğinde kaliteli hizmetlerin keyfini çıkarın. Her detay sizin için özenle planlanır.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="experience-card">
                        <div class="experience-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="serif-font h4 fw-bold mb-3">Kişiye Özel Deneyim</h3>
                        <p class="text-muted">
                            Koşuşturmacalardan uzak, benzersiz aktivitelerle Paris'in tadını çıkarın. Sadece size özel, eşsiz anılar yaratın.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

       <!-- How Your Journey Takes Shape Section -->
       <section class="section-padding journey-shape">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="serif-font display-5 fw-bold mb-4">Seyahatiniz Nasıl Şekilleniyor?</h2>
                    
                    <div class="journey-steps">
                        <div class="step-item">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h4 class="fw-bold mb-2">İletişime Geçin</h4>
                                <p class="text-muted mb-0">İsteklerinizi ve beklentilerinizi konuşmak için bizimle iletişime geçin.</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h4 class="fw-bold mb-2">Aktiviteleri Seçin</h4>
                                <p class="text-muted mb-0">Aktivitelerinizi seçin veya size özel önerilerimizi değerlendirin.</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h4 class="fw-bold mb-2">Organizasyon</h4>
                                <p class="text-muted mb-0">Sorunsuz ve keyifli bir deneyim garantisiyle ulaşımdan aktivitelere kadar her şeyi sizin için organize ediyoruz.</p>
                            </div>
                        </div>
                        
                        <div class="step-item">
                            <div class="step-number">4</div>
                            <div class="step-content">
                                <h4 class="fw-bold mb-2">Kişiye Özel Program</h4>
                                <p class="text-muted mb-0">Kendi temponuzda, huzur içinde benzersiz bir Paris deneyimi programı oluşturuyoruz.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="journey-note mt-4">
                        <p class="lead text-muted">
                            Bu kişiye özel program, Paris'i kendi bakış açınıza göre keşfetmeniz için tasarlanıyor; ister bir gün, ister hafta sonu, ister uzun bir tatil boyunca.
                        </p>
                        <p class="h5 text-gold fw-bold">
                            Size de sadece keyfini çıkarmak kalıyor.
                        </p>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="journey-image">
                        <img src="assets/images/alize-vip.jpg" 
                             alt="Paris Deneyimi" class="img-fluid rounded-3">
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Testimonials Section -->
    <section class="section-padding testimonials-section" id="avis">
        <div class="container text-center">
            <h2 class="serif-font display-5 fw-bold mb-4">Müşteri Yorumları</h2>
            <div class="star-rating mb-4">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
            </div>
            
            <!-- Testimonials Carousel -->
            <div class="testimonials-carousel">
                <div class="testimonials-wrapper" id="testimonialsWrapper">
                    <?php
                    // Testimonials verilerini getir
                    try {
                        $testimonialsQuery = "SELECT * FROM testimonials WHERE is_active = 1 ORDER BY created_at DESC";
                        $testimonialsResult = $db->getConnection()->query($testimonialsQuery);
                        
                        if ($testimonialsResult && $testimonialsResult->rowCount() > 0) {
                            while ($testimonial = $testimonialsResult->fetch(PDO::FETCH_ASSOC)) {
                                $initials = strtoupper(substr($testimonial['first_name'], 0, 1) . substr($testimonial['last_name'], 0, 1));
                                ?>
                                <div class="testimonial-item">
                                    <div class="testimonial-content">
                                        <blockquote class="blockquote">
                                            <p class="lead mb-3">
                                                "<?php echo htmlspecialchars($testimonial['comment']); ?>"
                                            </p>
                                            <footer class="blockquote-footer fw-bold">
                                                <?php echo htmlspecialchars($testimonial['first_name'] . ' ' . $testimonial['last_name'][0] . '. - ' . $testimonial['country']); ?>
                                            </footer>
                                        </blockquote>
                                    </div>
                                </div>
                                <?php
                            }
                        } else {
                            // Varsayılan testimonial göster
                            ?>
                            <div class="testimonial-item">
                                <div class="testimonial-content">
                                    <div class="testimonial-avatar">
                                        <span class="avatar-initials">BM</span>
                                    </div>
                                    <blockquote class="blockquote">
                                        <p class="lead mb-3">
                                            "Astrid Caternet ist eine erstklassige Reiseleiterin mit umfassendem Wissen und großer Kompetenz. Die Eindrücke von Marseille während unseres kurzen Treffens waren großartig. Sie ist freundlich, hilfsbereit und hat ein enormes Wissen. Das wird unvergesslich!"
                                        </p>
                                        <footer class="blockquote-footer fw-bold">Bernd M. - Almanya</footer>
                                    </blockquote>
                                </div>
                            </div>
                            <?php
                        }
                    } catch (Exception $e) {
                        // Hata durumunda varsayılan testimonial göster
                        ?>
                        <div class="testimonial-item">
                            <div class="testimonial-content">
                                <div class="testimonial-avatar">
                                    <span class="avatar-initials">BM</span>
                                </div>
                                <blockquote class="blockquote">
                                    <p class="lead mb-3">
                                        "Astrid Caternet ist eine erstklassige Reiseleiterin mit umfassendem Wissen und großer Kompetenz. Die Eindrücke von Marseille während unseres kurzen Treffens waren großartig. Sie ist freundlich, hilfsbereit und hat ein enormes Wissen. Das wird unvergesslich!"
                                    </p>
                                    <footer class="blockquote-footer fw-bold">Bernd M. - Almanya</footer>
                                </blockquote>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <!-- Navigation Arrows -->
                <button class="testimonial-nav prev" onclick="changeTestimonial(-1)">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="testimonial-nav next" onclick="changeTestimonial(1)">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Pagination Dots -->
                <div class="testimonial-dots" id="testimonialDots">
                    <!-- JavaScript ile doldurulacak -->
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
                    <p class="col-lg-4 mb-4 mb-lg-0">
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Hero Slider Functionality -->
    <script>
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        let slideInterval;
        
        // Initialize slider
        function initSlider() {
            showSlide(currentSlideIndex);
            startAutoPlay();
        }
        
        // Show specific slide
        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Show current slide
            if (slides[index]) {
                slides[index].classList.add('active');
                dots[index].classList.add('active');
            }
        }
        
        // Change slide
        function changeSlide(direction) {
            currentSlideIndex += direction;
            
            // Loop around
            if (currentSlideIndex >= slides.length) {
                currentSlideIndex = 0;
            } else if (currentSlideIndex < 0) {
                currentSlideIndex = slides.length - 1;
            }
            
            showSlide(currentSlideIndex);
            resetAutoPlay();
        }
        
        // Go to specific slide
        function currentSlide(index) {
            currentSlideIndex = index - 1;
            showSlide(currentSlideIndex);
            resetAutoPlay();
        }
        
        // Auto-play functionality
        function startAutoPlay() {
            slideInterval = setInterval(() => {
                changeSlide(1);
            }, 5000); // Change slide every 5 seconds
        }
        
        function resetAutoPlay() {
            clearInterval(slideInterval);
            startAutoPlay();
        }
        
        // Pause auto-play on hover
        document.querySelector('.hero-slider').addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });
        
        document.querySelector('.hero-slider').addEventListener('mouseleave', () => {
            startAutoPlay();
        });
        
        // Initialize when page loads
        document.addEventListener('DOMContentLoaded', initSlider);
        
        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.querySelector('.hero-slider').addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.querySelector('.hero-slider').addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    changeSlide(1); // Swipe left
                } else {
                    changeSlide(-1); // Swipe right
                }
            }
        }
        
        // Testimonials Carousel Functionality
        let currentTestimonialIndex = 0;
        const testimonialItems = document.querySelectorAll('.testimonial-item');
        const testimonialDots = document.getElementById('testimonialDots');
        let testimonialInterval;
        
        // Initialize testimonials carousel
        function initTestimonialsCarousel() {
            if (testimonialItems.length > 1) {
                createTestimonialDots();
                showTestimonial(currentTestimonialIndex);
                startTestimonialAutoPlay();
            }
        }
        
        // Create pagination dots
        function createTestimonialDots() {
            testimonialDots.innerHTML = '';
            for (let i = 0; i < testimonialItems.length; i++) {
                const dot = document.createElement('span');
                dot.className = 'testimonial-dot';
                dot.onclick = () => currentTestimonial(i);
                testimonialDots.appendChild(dot);
            }
        }
        
        // Show specific testimonial
        function showTestimonial(index) {
            // Hide all testimonials
            testimonialItems.forEach(item => item.style.display = 'none');
            const dots = document.querySelectorAll('.testimonial-dot');
            dots.forEach(dot => dot.classList.remove('active'));
            
            // Show current testimonial
            if (testimonialItems[index]) {
                testimonialItems[index].style.display = 'block';
                if (dots[index]) {
                    dots[index].classList.add('active');
                }
            }
        }
        
        // Change testimonial
        function changeTestimonial(direction) {
            currentTestimonialIndex += direction;
            
            // Loop around
            if (currentTestimonialIndex >= testimonialItems.length) {
                currentTestimonialIndex = 0;
            } else if (currentTestimonialIndex < 0) {
                currentTestimonialIndex = testimonialItems.length - 1;
            }
            
            showTestimonial(currentTestimonialIndex);
            resetTestimonialAutoPlay();
        }
        
        // Go to specific testimonial
        function currentTestimonial(index) {
            currentTestimonialIndex = index;
            showTestimonial(currentTestimonialIndex);
            resetTestimonialAutoPlay();
        }
        
        // Auto-play functionality for testimonials
        function startTestimonialAutoPlay() {
            if (testimonialItems.length > 1) {
                testimonialInterval = setInterval(() => {
                    changeTestimonial(1);
                }, 4000); // Change testimonial every 4 seconds
            }
        }
        
        function resetTestimonialAutoPlay() {
            if (testimonialInterval) {
                clearInterval(testimonialInterval);
                startTestimonialAutoPlay();
            }
        }
        
        // Pause auto-play on hover for testimonials
        const testimonialsCarousel = document.querySelector('.testimonials-carousel');
        if (testimonialsCarousel) {
            testimonialsCarousel.addEventListener('mouseenter', () => {
                if (testimonialInterval) {
                    clearInterval(testimonialInterval);
                }
            });
            
            testimonialsCarousel.addEventListener('mouseleave', () => {
                startTestimonialAutoPlay();
            });
        }
        
        // Initialize testimonials when page loads
        document.addEventListener('DOMContentLoaded', () => {
            initSlider();
            initTestimonialsCarousel();
        });
    </script>
</body>
</html>
