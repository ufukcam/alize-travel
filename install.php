<?php
require_once 'includes/config.php';

try {
    // Ana veritabanı bağlantısı (veritabanı olmadan)
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // Veritabanını oluştur
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Veritabanı oluşturuldu: " . DB_NAME . "<br>";
    
    // Veritabanını seç
    $pdo->exec("USE " . DB_NAME);
    
    // Turlar tablosunu oluştur
    $sql = "CREATE TABLE IF NOT EXISTS tours (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        subtitle VARCHAR(500),
        description TEXT,
        category VARCHAR(100) NOT NULL,
        subcategory VARCHAR(100),
        image VARCHAR(255),
        price DECIMAL(10,2),
        duration VARCHAR(100),
        difficulty VARCHAR(50),
        group_size VARCHAR(100),
        highlights TEXT,
        included_services TEXT,
        tour_options TEXT,
        ideal_for TEXT,
        guide_name VARCHAR(255),
        guide_expertise VARCHAR(255),
        rating DECIMAL(3,1) DEFAULT 5.0,
        is_active BOOLEAN DEFAULT 1,
        sort_order INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Tours tablosu oluşturuldu<br>";
    
    // Örnek veriler ekle
    $sampleTours = [
        [
            'title' => 'Louvre Müzesi Turu',
            'subtitle' => 'Güç ve sanatın harmonisi, dünyanın en büyük müzesi',
            'description' => 'Dünyanın en büyük sanat müzelerinden biri olan Louvre\'da Mona Lisa, Venüs heykeli ve sayısız başyapıtı keşfedin. Orta Çağ\'dan günümüze uzanan bu devasa yapı yalnızca bir sanat galerisi değil insanlık tarihinin de adeta bir aynasıdır.',
            'category' => 'museums',
            'subcategory' => 'museums',
            'image' => 'louvre.jpg',
            'price' => 150.00,
            'duration' => '3-4 saat',
            'difficulty' => 'Kolay',
            'group_size' => 'Özel Grup',
            'highlights' => "Mona Lisa\nVenüs de Milo\nFransız Resimleri\nSaray Mimarisi",
            'included_services' => "Profesyonel rehber\nMüze giriş bileti\nÖzel erişim\nFotoğraf izni",
            'tour_options' => "Louvre Başyapıtları Turu: 1,5 saat\nOpsiyonel: Tam gün özel program",
            'ideal_for' => "Sanatseverler\nKültür turlarına değer verenler\nAileler ve çiftler",
            'guide_name' => 'Dr. Mehmet Kürkçü',
            'guide_expertise' => 'Sanat Tarihçisi',
            'rating' => 5.0,
            'sort_order' => 1
        ],
        [
            'title' => 'Orsay Müzesi Turu',
            'subtitle' => '19. yüzyıl sanatının şaheserleri',
            'description' => '19. yüzyıl Fransız sanatının en güzel örneklerini barındıran eski tren istasyonu müzesi. Monet, Van Gogh, Renoir gibi ustaların eserlerini keşfedin.',
            'category' => 'museums',
            'subcategory' => 'museums',
            'image' => 'orsay.jpg',
            'price' => 120.00,
            'duration' => '2-3 saat',
            'difficulty' => 'Kolay',
            'group_size' => 'Özel Grup',
            'highlights' => "İzlenimci resimler\nHeykel galerisi\nMimari yapı\nTren istasyonu atmosferi",
            'included_services' => "Profesyonel rehber\nMüze giriş bileti\nÖzel anlatım",
            'tour_options' => "Klasik Tur: 2 saat\nÖzel Program: 3 saat",
            'ideal_for' => "Sanat tarihi meraklıları\nİzlenimci resim severler\nMimari ilgililer",
            'guide_name' => 'Dr. Mehmet Kürkçü',
            'guide_expertise' => 'Sanat Tarihçisi',
            'rating' => 5.0,
            'sort_order' => 2
        ],
        [
            'title' => 'Versailles Sarayı Turu',
            'subtitle' => 'Fransa\'nın ihtişamının simgesi',
            'description' => 'Fransa\'nın en görkemli sarayı ve bahçeleri ile tarihi bir yolculuk. XIV. Louis\'in "Güneş Kral" olarak anıldığı dönemin en etkileyici eserlerini keşfedin.',
            'category' => 'surroundings',
            'subcategory' => 'day_tours',
            'image' => 'versailles.jpg',
            'price' => 200.00,
            'duration' => '6-8 saat',
            'difficulty' => 'Orta',
            'group_size' => 'Özel Grup',
            'highlights' => "Saray odaları\nAynalı Salon\nBahçeler ve çeşmeler\nTrianon sarayları",
            'included_services' => "Profesyonel rehber\nSaray giriş bileti\nBahçe turu\nUlaşım",
            'tour_options' => "Klasik Tur: 6 saat\nLüks Program: 8 saat",
            'ideal_for' => "Tarih meraklıları\nLüks deneyim arayanlar\nBahçe ve mimari severler",
            'guide_name' => 'Dr. Mehmet Kürkçü',
            'guide_expertise' => 'Sanat Tarihçisi',
            'rating' => 5.0,
            'sort_order' => 1
        ]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO tours (title, subtitle, description, category, subcategory, image, price, duration, difficulty, group_size, highlights, included_services, tour_options, ideal_for, guide_name, guide_expertise, rating, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($sampleTours as $tour) {
        $stmt->execute([
            $tour['title'],
            $tour['subtitle'],
            $tour['description'],
            $tour['category'],
            $tour['subcategory'],
            $tour['image'],
            $tour['price'],
            $tour['duration'],
            $tour['difficulty'],
            $tour['group_size'],
            $tour['highlights'],
            $tour['included_services'],
            $tour['tour_options'],
            $tour['ideal_for'],
            $tour['guide_name'],
            $tour['guide_expertise'],
            $tour['rating'],
            $tour['sort_order']
        ]);
    }
    
    echo "✅ Örnek turlar eklendi<br>";
    
    // Tur detayları tablosunu oluştur
    $sql = "CREATE TABLE IF NOT EXISTS tour_details (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tour_id INT,
        feature_title VARCHAR(255),
        feature_description TEXT,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    echo "✅ Tour details tablosu oluşturuldu<br>";
    
    echo "<br>🎉 Kurulum tamamlandı!<br>";
    echo "<a href='admin/index.php' class='btn btn-primary'>Admin Paneline Git</a><br>";
    echo "<a href='index.html' class='btn btn-secondary'>Ana Sayfaya Git</a>";
    
} catch (PDOException $e) {
    die("❌ Hata: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurulum - Alize Travel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .install-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="install-card">
            <h2 class="text-center mb-4">🚀 Alize Travel Kurulum</h2>
            <div class="text-center">
                <!-- PHP çıktısı burada görünecek -->
            </div>
        </div>
    </div>
</body>
</html>
