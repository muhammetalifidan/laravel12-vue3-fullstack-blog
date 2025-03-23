# Blog Yönetim Sistemi

Bu proje, Laravel 12 ve Vue 3 kullanılarak geliştirilmiş, kullanıcı rolleri, izinler, yazı yönetimi, kategoriler ve yorumlar içeren tam kapsamlı bir blog yönetim sistemidir.

## Özellikler

### Kullanıcı Yönetimi
- Kayıt ve giriş (e-posta veya telefon ile)
- Kullanıcı rolleri: admin, yazar ve normal kullanıcı
- Rol bazlı izinler

### Blog Yazıları
- Blog yazılarını oluşturma, okuma, güncelleme ve silme
- Kapak görseli yükleme
- Yayın durumu (taslak, yayında)
- Soft delete (yumuşak silme) özelliği

### Kategoriler
- Kategori oluşturma, okuma, güncelleme ve silme
- Yazılar birden fazla kategoriye ait olabilir

### Yorumlar
- Tüm kullanıcı rolleri yazılara yorum yapabilir
- Admin yorum onayı
- Admin yorumları otomatik onaylanır
- Soft delete (yumuşak silme) özelliği

### Ek Özellikler
- Yazılara yorum geldiğinde bildirimler (Mail, Database, Broadcast)
- Laravel Reverb ile gerçek zamanlı yorum güncellemeleri
- Pasif yazıların otomatik temizlenmesi (bir hafta boyunca yorum almayan yazılar)
- Tüm model değişiklikleri için aktivite günlüğü

## Kullanılan Teknolojiler

### Backend
- Laravel 12
- Laravel Sanctum (API kimlik doğrulama)
- Laravel Reverb (Yayın/Broadcasting)
- Spatie MediaLibrary (Dosya depolama)
- Spatie ActivityLog (Model değişikliği takibi)
- Spatie Permission (Kullanıcı rolleri ve izinleri)

### Frontend
- Vue 3 (Composition API)
- Tailwind CSS
- Vue Router
- Vue Select
- Vue The Mask
- Vue Yup Form

## Gereksinimler

- PHP 8.4+
- Composer
- Node.js ve NPM
- MySQL veya PostgreSQL veritabanı
- Redis (yayın için)

## Kurulum

### Seçenek 1: Standart Kurulum (Docker Olmadan)

1. Depoyu klonlayın:
```bash
git clone https://github.com/kullanici-adiniz/blog-yonetim-sistemi.git
cd blog-yonetim-sistemi
```

2. PHP bağımlılıklarını yükleyin:
```bash
composer install
```

3. .env.example dosyasını kopyalayın ve uygulama anahtarı oluşturun:
```bash
cp .env.example .env
php artisan key:generate
```

4. .env dosyasında veritabanınızı yapılandırın:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_yonetim
DB_USERNAME=root
DB_PASSWORD=
```

5. .env dosyasında Reverb'i yapılandırın:
```
REVERB_APP_ID=blog_app
REVERB_APP_KEY=uygulama_anahtari
REVERB_APP_SECRET=uygulama_sifresi
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

6. Migrasyonları çalıştırın ve veritabanını doldurun:
```bash
php artisan migrate --seed
```

7. JavaScript bağımlılıklarını yükleyin:
```bash
npm install
```

8. Frontend varlıklarını oluşturun:
```bash
npm run build
```

9. Laravel geliştirme sunucusunu başlatın:
```bash
php artisan serve
```

10. Ayrı bir terminalde Reverb sunucusunu başlatın:
```bash
php artisan reverb:start
```

11. Uygulamaya http://localhost:8000 adresinden erişin

### Seçenek 2: Docker Kurulumu (Laravel Sail ile)

1. Depoyu klonlayın:
```bash
git clone https://github.com/kullanici-adiniz/blog-yonetim-sistemi.git
cd blog-yonetim-sistemi
```

2. .env.example dosyasını kopyalayın:
```bash
cp .env.example .env
```

3. Geçici bir konteyner kullanarak Composer bağımlılıklarını yükleyin:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

4. Docker için .env dosyasını yapılandırın:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=blog_yonetim
DB_USERNAME=sail
DB_PASSWORD=password

REVERB_APP_ID=blog_app
REVERB_APP_KEY=uygulama_anahtari
REVERB_APP_SECRET=uygulama_sifresi
REVERB_HOST=reverb
REVERB_PORT=8080
REVERB_SCHEME=http
```

5. Laravel Sail kullanarak Docker konteynerlerini başlatın:
```bash
./vendor/bin/sail up -d
```

6. Uygulama anahtarı oluşturun:
```bash
./vendor/bin/sail artisan key:generate
```

7. Migrasyonları çalıştırın ve veritabanını doldurun:
```bash
./vendor/bin/sail artisan migrate --seed
```

8. JavaScript bağımlılıklarını yükleyin:
```bash
./vendor/bin/sail npm install
```

9. Frontend varlıklarını oluşturun:
```bash
./vendor/bin/sail npm run build
```

10. Reverb sunucusunu başlatın:
```bash
./vendor/bin/sail artisan reverb:start
```

11. Uygulamaya http://localhost adresinden erişin

## Varsayılan Kullanıcı Hesapları

Veritabanını doldurduktan sonra, aşağıdaki kullanıcı hesapları kullanılabilir:

1. Admin Kullanıcısı:
   - E-posta: admin@example.com
   - Şifre: password

2. Yazar Kullanıcısı:
   - E-posta: writer@example.com
   - Şifre: password

3. Normal Kullanıcı:
   - E-posta: user@example.com
   - Şifre: password

## API Rotaları

### Kimlik Doğrulama

| Metod | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| POST | `/api/v1/login` | Kullanıcı girişi | Hayır |
| POST | `/api/v1/register` | Yeni kullanıcı kaydı | Hayır |
| DELETE | `/api/v1/logout` | Kullanıcı çıkışı | Evet |

### Yazılar (Posts)

| Metod | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| GET | `/api/v1/posts` | Tüm yazıları listele | Hayır |
| GET | `/api/v1/posts/{post}` | Yazı detaylarını görüntüle | Hayır |
| POST | `/api/v1/posts` | Yeni yazı oluştur | Evet |
| PUT/PATCH | `/api/v1/posts/{post}` | Yazıyı güncelle | Evet |
| DELETE | `/api/v1/posts/{post}` | Yazıyı sil | Evet |
| PUT | `/api/v1/posts/{post}/status` | Yazı durumunu güncelle | Evet |

### Kategoriler (Categories)

| Metod | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| GET | `/api/v1/categories` | Tüm kategorileri listele | Hayır |
| GET | `/api/v1/categories/{category}` | Kategori detaylarını görüntüle | Hayır |
| POST | `/api/v1/categories` | Yeni kategori oluştur | Evet |
| PUT/PATCH | `/api/v1/categories/{category}` | Kategoriyi güncelle | Evet |
| DELETE | `/api/v1/categories/{category}` | Kategoriyi sil | Evet |

### Yorumlar (Comments)

| Metod | URI | İşlev | Kimlik Doğrulama |
|--------|-----|-------|-----------------|
| GET | `/api/v1/posts/{post}/comments` | Yazı yorumlarını listele | Hayır |
| GET | `/api/v1/posts/{post}/comments/{comment}` | Yorum detaylarını görüntüle | Hayır |
| POST | `/api/v1/posts/{post}/comments` | Yeni yorum oluştur | Evet |
| PUT/PATCH | `/api/v1/posts/{post}/comments/{comment}` | Yorumu güncelle | Evet |
| DELETE | `/api/v1/posts/{post}/comments/{comment}` | Yorumu sil | Evet |
| PUT | `/api/v1/posts/{post}/comments/{comment}/status` | Yorum durumunu güncelle | Evet |

## Geliştirme

### Vite Geliştirme Sunucusunu Çalıştırma

Frontend üzerinde hot reloading ile geliştirme modunda çalışmak için:

```bash
# Docker Olmadan
npm run dev

# Docker ile
./vendor/bin/sail npm run dev
```

### Zamanlanmış Görevler

Uygulama, pasif yazıları temizlemek için zamanlanmış bir görev içerir (bir hafta boyunca yorum almayan yazılar):

```bash
# Docker Olmadan
php artisan schedule:work

# Docker ile
./vendor/bin/sail artisan schedule:work
```


## Proje Yapısı

### Backend

- **app/Models**: Tüm Eloquent modellerini içerir
- **app/Http/Controllers**: Tüm API kontrolcülerini içerir
- **app/Http/Requests**: Doğrulama için form isteklerini içerir
- **app/Http/Resources**: Yanıt dönüşümü için API kaynaklarını içerir
- **app/Policies**: Yetkilendirme politikalarını içerir
- **app/Observers**: Etkinlikler için model gözlemcilerini içerir
- **app/Events**: Yayın için etkinlik sınıflarını içerir
- **app/Notifications**: Bildirim sınıflarını içerir
- **app/Enums**: Enum tanımlamalarını içerir
- **database/migrations**: Veritabanı migrasyonlarını içerir
- **database/seeders**: Veritabanı tohumlayıcılarını içerir
- **routes/api.php**: API rotalarını içerir

### Frontend

- **resources/js/components**: Vue bileşenlerini içerir
- **resources/js/views**: Vue sayfa bileşenlerini içerir
- **resources/js/router**: Vue router yapılandırmasını içerir
- **resources/js/App.vue**: Ana Vue bileşenini içerir
- **resources/css/app.css**: Tailwind ile ana CSS dosyasını içerir

## Lisans

Blog Yönetim Sistemi, [MIT lisansı](https://opensource.org/licenses/MIT) altında lisanslanan açık kaynaklı bir yazılımdır.