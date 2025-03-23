# Blog Management System

## Proje Görevleri

### Geliştirme Ortamı
- [x] Laravel 12 kurulumu
- [x] Laravel Sail ile geliştirme ortamı kurulumu
- [x] Laravel Sanctum kurulumu
- [x] Laravel Reverb kurulumu
- [x] Spatie MediaLibrary kurulumu
- [x] Spatie Permission kurulumu
- [x] Spatie ActivityLog kurulumu

### Kullanıcı Yönetimi
- [x] Kullanıcı kayıt ve giriş işlemleri
- [x] Kullanıcı rolleri
- [x] Kullanıcı izinleri

### Blog Yönetimi
- [x] Blog gönderileri CRUD işlemleri
- [x] Blog gönderileri için medya ekleme
- [x] Blog gönderileri için izin kontrolü
- [x] Kategori yönetimi
- [x] Yorum yönetimi
- [x] Yorum onaylama

### Ek Özellikler
- [x] Bildirim sistemi
- [x] Yeni veri canlı yayın
- [x] Haftalık veri temizliği (Schedule)
- [!] Seed veri
- [!] Activity Log
- [x] Postman collection
- [x] Github repository

## API Route

Bu API, blog yönetim sistemi için RESTful endpoint'ler sağlar. API, v1 sürüm yönetimi kullanır ve kimlik doğrulama gerektiren işlemler için Laravel Sanctum'u kullanır.

### Kimlik Doğrulama

| Method | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| POST | `/api/v1/login` | Kullanıcı girişi | Hayır |
| POST | `/api/v1/register` | Yeni kullanıcı kaydı | Hayır |
| DELETE | `/api/v1/logout` | Kullanıcı çıkışı | Evet |

### Gönderiler (Posts)

| Method | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| GET | `/api/v1/posts` | Tüm gönderileri listele | Hayır |
| GET | `/api/v1/posts/{post}` | Gönderi detaylarını görüntüle | Hayır |
| POST | `/api/v1/posts` | Yeni gönderi oluştur | Evet |
| PUT/PATCH | `/api/v1/posts/{post}` | Gönderiyi güncelle | Evet |
| DELETE | `/api/v1/posts/{post}` | Gönderiyi sil | Evet |
| PUT | `/api/v1/posts/{post}/status` | Gönderi durumunu güncelle | Evet |

### Kategoriler (Categories)

| Method | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| GET | `/api/v1/categories` | Tüm kategorileri listele | Hayır |
| GET | `/api/v1/categories/{category}` | Kategori detaylarını görüntüle | Hayır |
| POST | `/api/v1/categories` | Yeni kategori oluştur | Evet |
| PUT/PATCH | `/api/v1/categories/{category}` | Kategoriyi güncelle | Evet |
| DELETE | `/api/v1/categories/{category}` | Kategoriyi sil | Evet |

### Yorumlar (Comments)

| Method | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| GET | `/api/v1/posts/{post}/comments` | Gönderi yorumlarını listele | Hayır |
| GET | `/api/v1/posts/{post}/comments/{comment}` | Yorum detaylarını görüntüle | Hayır |
| POST | `/api/v1/posts/{post}/comments` | Yeni yorum oluştur | Evet |
| PUT/PATCH | `/api/v1/posts/{post}/comments/{comment}` | Yorumu güncelle | Evet |
| DELETE | `/api/v1/posts/{post}/comments/{comment}` | Yorumu sil | Evet |
| PUT | `/api/v1/posts/{post}/comments/{comment}/status` | Yorum durumunu güncelle | Evet |