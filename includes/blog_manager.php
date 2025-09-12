<?php
require_once 'database.php';
require_once 'config.php';

class BlogManager {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    // Slug oluştur
    public function createSlug($title) {
        $slug = strtolower(trim($title));
        $slug = str_replace(['ç', 'ğ', 'ı', 'ö', 'ş', 'ü'], ['c', 'g', 'i', 'o', 's', 'u'], $slug);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Benzersizlik kontrolü
        $originalSlug = $slug;
        $counter = 1;
        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    // Slug var mı kontrol et
    private function slugExists($slug, $excludeId = null) {
        $sql = "SELECT id FROM blog_posts WHERE slug = ?";
        $params = [$slug];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = $this->db->fetch($sql, $params);
        return $result !== false;
    }
    
    // Tüm blog yazılarını getir
    public function getAllPosts($status = null) {
        $sql = "SELECT * FROM blog_posts";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY published_at DESC, created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    // Yayınlanmış blog yazılarını getir
    public function getPublishedPosts($limit = null, $offset = 0) {
        $sql = "SELECT * FROM blog_posts WHERE status = 'published' AND published_at <= NOW() ORDER BY published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }
        
        return $this->db->fetchAll($sql);
    }
    
    // Blog yazısını ID ile getir
    public function getPostById($id) {
        return $this->db->fetch(
            "SELECT * FROM blog_posts WHERE id = ?",
            [$id]
        );
    }
    
    // Blog yazısını slug ile getir
    public function getPostBySlug($slug) {
        return $this->db->fetch(
            "SELECT * FROM blog_posts WHERE slug = ? AND status = 'published'",
            [$slug]
        );
    }
    
    // Blog yazısı ekle
    public function addPost($data) {
        $slug = $this->createSlug($data['title']);
        
        $sql = "INSERT INTO blog_posts (title, slug, excerpt, content, featured_image, author_name, author_email, status, published_at, meta_title, meta_description, tags, sort_order) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['title'],
            $slug,
            $data['excerpt'] ?? '',
            $data['content'],
            $data['featured_image'] ?? '',
            $data['author_name'] ?? 'Dr. Mehmet Kürkçü',
            $data['author_email'] ?? 'memoguide@yahoo.fr',
            $data['status'] ?? 'draft',
            $data['published_at'] ?? null,
            $data['meta_title'] ?? '',
            $data['meta_description'] ?? '',
            $data['tags'] ?? '',
            $data['sort_order'] ?? 0
        ];
        
        return $this->db->execute($sql, $params);
    }
    
    // Blog yazısını güncelle
    public function updatePost($id, $data) {
        $existingPost = $this->getPostById($id);
        if (!$existingPost) {
            throw new Exception('Blog yazısı bulunamadı');
        }
        
        // Başlık değiştiyse slug'ı güncelle
        $slug = $existingPost['slug'];
        if ($data['title'] !== $existingPost['title']) {
            $slug = $this->createSlug($data['title'], $id);
        }
        
        $sql = "UPDATE blog_posts SET 
                title = ?, slug = ?, excerpt = ?, content = ?, featured_image = ?, 
                author_name = ?, author_email = ?, status = ?, published_at = ?, 
                meta_title = ?, meta_description = ?, tags = ?, sort_order = ?, 
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        $params = [
            $data['title'],
            $slug,
            $data['excerpt'] ?? '',
            $data['content'],
            $data['featured_image'] ?? '',
            $data['author_name'] ?? 'Dr. Mehmet Kürkçü',
            $data['author_email'] ?? 'memoguide@yahoo.fr',
            $data['status'] ?? 'draft',
            $data['published_at'] ?? null,
            $data['meta_title'] ?? '',
            $data['meta_description'] ?? '',
            $data['tags'] ?? '',
            $data['sort_order'] ?? 0,
            $id
        ];
        
        return $this->db->execute($sql, $params);
    }
    
    // Blog yazısını sil
    public function deletePost($id) {
        return $this->db->execute(
            "DELETE FROM blog_posts WHERE id = ?",
            [$id]
        );
    }
    
    // Blog yazısı durumunu değiştir
    public function togglePostStatus($id) {
        $post = $this->getPostById($id);
        if (!$post) {
            throw new Exception('Blog yazısı bulunamadı');
        }
        
        $newStatus = $post['status'] === 'published' ? 'draft' : 'published';
        $publishedAt = $newStatus === 'published' ? date('Y-m-d H:i:s') : null;
        
        return $this->db->execute(
            "UPDATE blog_posts SET status = ?, published_at = ? WHERE id = ?",
            [$newStatus, $publishedAt, $id]
        );
    }
    
    // Görüntülenme sayısını artır
    public function incrementViews($id) {
        return $this->db->execute(
            "UPDATE blog_posts SET views = views + 1 WHERE id = ?",
            [$id]
        );
    }
    
    // Resim yükleme
    public function uploadImage($file) {
        $target_dir = UPLOAD_PATH;
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = 'blog_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        
        // Klasör yoksa oluştur
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        
        // Dosya türü kontrolü
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($file_extension, $allowed_types)) {
            throw new Exception('Sadece JPG, PNG, GIF ve WebP dosyaları yüklenebilir.');
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
    
    // Popüler yazıları getir
    public function getPopularPosts($limit = 5) {
        return $this->db->fetchAll(
            "SELECT * FROM blog_posts WHERE status = 'published' AND published_at <= NOW() ORDER BY views DESC LIMIT ?",
            [$limit]
        );
    }
    
    // Son yazıları getir
    public function getRecentPosts($limit = 5) {
        return $this->db->fetchAll(
            "SELECT * FROM blog_posts WHERE status = 'published' AND published_at <= NOW() ORDER BY published_at DESC LIMIT ?",
            [$limit]
        );
    }
    
    // Etiketlere göre yazıları getir
    public function getPostsByTag($tag, $limit = 10) {
        return $this->db->fetchAll(
            "SELECT * FROM blog_posts WHERE status = 'published' AND published_at <= NOW() AND tags LIKE ? ORDER BY published_at DESC LIMIT ?",
            ['%' . $tag . '%', $limit]
        );
    }
    
    // Arama
    public function searchPosts($query, $limit = 10) {
        return $this->db->fetchAll(
            "SELECT * FROM blog_posts WHERE status = 'published' AND published_at <= NOW() AND (title LIKE ? OR content LIKE ? OR excerpt LIKE ?) ORDER BY published_at DESC LIMIT ?",
            ['%' . $query . '%', '%' . $query . '%', '%' . $query . '%', $limit]
        );
    }
}
?>
