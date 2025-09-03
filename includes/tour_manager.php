<?php
require_once 'database.php';
require_once 'config.php';

class TourManager {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Tüm turları getir
    public function getAllTours() {
        return $this->db->fetchAll(
            "SELECT * FROM tours ORDER BY category, subcategory, sort_order ASC"
        );
    }

     // Tüm turları sort_order'a göre sıralı getir
     public function getAllToursOrdered() {
        try {
            $query = "SELECT * FROM tours WHERE is_active = 1 ORDER BY sort_order ASC, title ASC";
            $result = $this->db->getConnection()->query($query);
            
            if ($result) {
                $tours = [];
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $tours[] = $row;
                }
                return $tours;
            }
            return [];
        } catch (Exception $e) {
            throw new Exception("Turlar getirilirken hata: " . $e->getMessage());
        }
    }
    
    // Kategoriye göre turları getir
    public function getToursByCategory($category) {
        return $this->db->fetchAll(
            "SELECT * FROM tours WHERE category = ? AND is_active = 1 ORDER BY sort_order ASC",
            [$category]
        );
    }
    
    // Alt kategoriye göre turları getir
    public function getToursBySubcategory($subcategory) {
        return $this->db->fetchAll(
            "SELECT * FROM tours WHERE subcategory = ? AND is_active = 1 ORDER BY sort_order ASC",
            [$subcategory]
        );
    }
    
    // Tek bir turu getir
    public function getTourById($id) {
        return $this->db->fetch(
            "SELECT * FROM tours WHERE id = ?",
            [$id]
        );
    }
    
    // Tur ekle
    public function addTour($data) {
        $sql = "INSERT INTO tours (title, subtitle, description, short_description, category, subcategory, image, price, duration, difficulty, group_size, highlights, included_services, tour_options, ideal_for, guide_name, guide_expertise, rating, sort_order, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['title'],
            $data['subtitle'] ?? '',
            $data['description'],
            $data['short_description'] ?? '',
            $data['category'],
            $data['subcategory'],
            $data['image'],
            $data['price'],
            $data['duration'],
            $data['difficulty'],
            $data['group_size'] ?? '',
            $data['highlights'] ?? '',
            $data['included_services'] ?? '',
            $data['tour_options'] ?? '',
            $data['ideal_for'] ?? '',
            $data['guide_name'] ?? 'Dr. Mehmet Kürkçü',
            $data['guide_expertise'] ?? 'Sanat Tarihçisi',
            $data['rating'] ?? 5.0,
            $data['sort_order'],
            $data['is_active'] ?? 1
        ];
        
        return $this->db->execute($sql, $params);
    }
    
    // Tur güncelle
    public function updateTour($id, $data) {
        $sql = "UPDATE tours SET 
                title = ?, subtitle = ?, description = ?, short_description = ?, category = ?, subcategory = ?, 
                image = ?, price = ?, duration = ?, difficulty = ?, group_size = ?, 
                highlights = ?, included_services = ?, tour_options = ?, ideal_for = ?, 
                guide_name = ?, guide_expertise = ?, rating = ?, sort_order = ?, 
                is_active = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        $params = [
            $data['title'],
            $data['subtitle'] ?? '',
            $data['description'],
            $data['short_description'] ?? '',
            $data['category'],
            $data['subcategory'],
            $data['image'],
            $data['price'],
            $data['duration'],
            $data['difficulty'],
            $data['group_size'] ?? '',
            $data['highlights'] ?? '',
            $data['included_services'] ?? '',
            $data['tour_options'] ?? '',
            $data['ideal_for'] ?? '',
            $data['guide_name'] ?? 'Dr. Mehmet Kürkçü',
            $data['guide_expertise'] ?? 'Sanat Tarihçisi',
            $data['rating'] ?? 5.0,
            $data['sort_order'],
            $data['is_active'] ?? 1,
            $id
        ];
        
        return $this->db->execute($sql, $params);
    }
    
    // Tur sil
    public function deleteTour($id) {
        return $this->db->execute(
            "DELETE FROM tours WHERE id = ?",
            [$id]
        );
    }
    
    // Tur durumunu değiştir (aktif/pasif)
    public function toggleTourStatus($id) {
        return $this->db->execute(
            "UPDATE tours SET is_active = NOT is_active WHERE id = ?",
            [$id]
        );
    }
    
    // Kategorileri getir
    public function getCategories() {
        return $this->db->fetchAll(
            "SELECT DISTINCT category FROM tours ORDER BY category"
        );
    }
    
    // Alt kategorileri getir
    public function getSubcategories() {
        return $this->db->fetchAll(
            "SELECT DISTINCT subcategory FROM tours ORDER BY subcategory"
        );
    }
    
    // Resim yükleme
    public function uploadImage($file) {
        $target_dir = UPLOAD_PATH;
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Klasör yoksa oluştur
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Dosya türü kontrolü
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception('Sadece JPG, PNG ve GIF dosyaları yüklenebilir.');
        }
        
        // Dosya boyutu kontrolü (5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            throw new Exception('Dosya boyutu 5MB\'dan büyük olamaz.');
        }
        
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            return $new_filename;
        } else {
            throw new Exception('Dosya yüklenirken hata oluştu.');
        }
    }
}
?>
