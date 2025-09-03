# 🚀 Alize Travel - Dinamik Tur Sistemi

Fransa ve Paris'te VIP seyahat deneyimleri sunan Alize Travel için geliştirilmiş dinamik tur yönetim sistemi.

## ✨ Özellikler

- **Dinamik Tur Yönetimi**: MySQL veritabanı ile tur ekleme, düzenleme, silme
- **Admin Panel**: Kullanıcı dostu yönetim arayüzü
- **Resim Yönetimi**: Otomatik resim yükleme ve optimizasyon
- **Kategori Sistemi**: Müzeler, tematik turlar, çevre turları, Fransa turları
- **Responsive Tasarım**: Bootstrap 5 ile modern ve mobil uyumlu arayüz
- **Güvenlik**: Session tabanlı admin girişi

## 🗂️ Dosya Yapısı

```
alize/
├── admin/                          # Admin paneli
│   ├── /                  # Giriş sayfası
│   ├── dashboard.php              # Ana dashboard
│   ├── turlar                  # Tur yönetimi
│   ├── logout.php                 # Çıkış
│   └── assets/                    # Admin CSS/JS
├── includes/                       # PHP sınıfları
│   ├── config.php                 # Konfigürasyon
│   ├── database.php               # Veritabanı sınıfı
│   └── tour_manager.php           # Tur yönetim sınıfı
├── assets/                         # Site dosyaları
│   ├── css/
│   ├── images/
│   └── images/tours/              # Tur resimleri
├── museum-tours/                   # Müze tur sayfaları
├── install.php                     # Kurulum scripti
├── index.html                      # Ana sayfa
├── hakkimizda                   # Hakkımızda
├── services.html                   # Hizmetler
└── contact-us.html                 # İletişim
```

## 🚀 Kurulum

### 1. Gereksinimler
- PHP 7.4+
- MySQL 5.7+
- Web sunucusu (Apache/Nginx)

### 2. Veritabanı Kurulumu
```bash
# install.php dosyasını çalıştırın
http://localhost/alize/install.php
```

### 3. Konfigürasyon
`includes/config.php` dosyasında veritabanı bilgilerini güncelleyin:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'alize_tours');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Admin Girişi
- URL: `http://localhost/alize/admin/`
- Kullanıcı adı: `admin`
- Şifre: `alize2024`

## 🎯 Kullanım

### Admin Panel
1. **Dashboard**: Genel istatistikler ve hızlı işlemler
2. **Turlar**: Tur ekleme, düzenleme, silme
3. **Kategoriler**: Tur kategorilerini yönetme
4. **Ayarlar**: Sistem ayarları

### Tur Ekleme
1. Admin panelinde "Yeni Tur Ekle" butonuna tıklayın
2. Tur bilgilerini doldurun (başlık, açıklama, kategori, fiyat)
3. Resim yükleyin
4. Kaydedin

## 🔧 Teknik Detaylar

### Veritabanı Tabloları
- **tours**: Ana tur bilgileri
- **tour_details**: Tur özellikleri (opsiyonel)

### Kategoriler
- `museums`: Müze turları
- `thematic`: Tematik Paris turları
- `surroundings`: Paris çevresi günlük turlar
- `france`: Fransa geneli turlar

### Güvenlik Özellikleri
- Session tabanlı kimlik doğrulama
- SQL injection koruması (PDO prepared statements)
- XSS koruması (htmlspecialchars)
- Dosya yükleme güvenliği

## 🎨 Tasarım Özellikleri

- **Bootstrap 5**: Modern ve responsive tasarım
- **Gradient Arka Planlar**: Profesyonel görünüm
- **Font Awesome**: Zengin ikon kütüphanesi
- **Custom CSS**: Marka kimliğine uygun özel stiller

## 📱 Responsive Tasarım

- Mobil cihazlarda optimize edilmiş görünüm
- Tablet ve desktop için uyarlanmış layout
- Touch-friendly admin paneli

## 🔄 Güncelleme

Sistemi güncellemek için:
1. Yeni özellikleri ekleyin
2. Veritabanı şemasını güncelleyin
3. Test edin
4. Canlıya alın

## 📞 Destek

Teknik destek için:
- Email: info@alizetravel.com
- Telefon: +33 7 69 91 11 24

## 📄 Lisans

Bu proje Alize Travel için özel olarak geliştirilmiştir.

---

**Alize Travel** - VIP Seyahat Deneyimleri 🗼✨
