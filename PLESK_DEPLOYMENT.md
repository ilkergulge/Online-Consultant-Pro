# 🚀 Laravel "Online Danışmanlık Platformu" Plesk Kurulum Rehberi

Bu doküman, geliştirdiğimiz kapsamlı Laravel projesini (Randevu, Ödeme, Zoom/Jitsi, Bildirim sistemleri dahil) Plesk kurulu bir sunucuda canlıya (Production) almak için gereken adımları detaylıca anlatır.

## 1. Sunucu ve PHP Gereksinimleri
Plesk panelinize giriş yapın ve hedef domaininiz için aşağıdaki ayarların yapıldığından emin olun:
- **PHP Sürümü:** En az **PHP 8.2** veya **8.3** seçili olmalıdır.
- **Gerekli PHP Eklentileri:** `bcmath`, `ctype`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `cURL`, `GD`, `Redis` (Kuyruk için Redis kullanacaksanız).
- **Hosting Settings (Barındırma Ayarları):** Document Root (Belge Kök Dizini) muhakkak `httpdocs/public` veya projenin ana klasörü altındaki `/public` klasörünü işaret etmelidir. Ana dizin `httpdocs` olmamalıdır!

## 2. Dosyaların Sunucuya Aktarılması
Projeyi sunucuya iki şekilde aktarabilirsiniz:
**Seçenek A: Git üzerinden (Tavsiye Edilen)**
1. Plesk panelde domaininize tıklayın ve **Git** eklentisini açın.
2. Projenizin barındığı (GitHub/GitLab vb.) repository URL'sini yapıştırın.
3. Deployment dizinini ayarlayın ve kodu sunucuya çekin (Pull).

**Seçenek B: FTP / Dosya Yöneticisi üzerinden**
1. Lokalinizde proje klasörünü `.zip` haline getirin (`node_modules` ve `vendor` hariç).
2. Plesk **File Manager (Dosya Yöneticisi)** ile `httpdocs` içine zip dosyasını yükleyip klasöre çıkartın (Extract).

## 3. Bağımlılıkların Kurulması (Composer & NPM)
Plesk'te SSH (Terminal) veya Plesk'in sunduğu PHP Composer eklentisi üzerinden:
```bash
# Bağımlılıkları yükleyin
composer install --optimize-autoloader --no-dev

# NPM paketlerini yükleyip derleyin (Eğer lokalde derleyip public/build klasörünü yüklemediyseniz)
npm install
npm run build
```

## 4. Veritabanı ve Çevre Değişkenleri (.env)
1. Plesk'te **Databases (Veritabanları)** bölümünden yeni bir MySQL veritabanı ve kullanıcısı oluşturun.
2. Dosya Yöneticisinden proje kök dizinindeki `.env.example` dosyasını `.env` olarak kopyalayın/adlandırın.
3. `.env` dosyasını düzenleyin:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sizin-domain.com

# Plesk'te oluşturduğunuz DB bilgileri
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plesk_veritabani_adiniz
DB_USERNAME=plesk_kullanici_adiniz
DB_PASSWORD=plesk_sifreniz

# Bildirimler için SMTP Ayarları (Plesk Mail veya harici)
MAIL_MAILER=smtp
MAIL_HOST=mail.sizin-domain.com
MAIL_PORT=465
MAIL_USERNAME=info@sizin-domain.com
MAIL_PASSWORD=mail_sifreniz
MAIL_ENCRYPTION=ssl

# Laravel Queues (Veritabanı veya Redis kullanılabilir)
QUEUE_CONNECTION=database
```

4. Çevre değişkenlerini ayarladıktan sonra terminalden anahtarı oluşturun ve veritabanını kurun:
```bash
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force # Gerekli başlangıç verilerini eklemek için
php artisan storage:link # Resim vb. yüklemeler için sembolik link
```

## 5. Uygulama Önbelleğini (Cache) Temizleme ve Optimize Etme
Production ortamında uygulamanın hızlı çalışması için route, view ve config dosyalarını önbelleğe alın:
```bash
php artisan optimize:clear
php artisan config:cache
php artisan event:cache
php artisan route:cache
php artisan view:cache
```

## 6. Zamanlanmış Görevler (Cron Jobs / Scheduler)
Randevu hatırlatmalarının (24 saat ve 1 saat kala) otomatik çalışması için Laravel Scheduler'ın her dakika tetiklenmesi gerekir.
1. Plesk'te **Scheduled Tasks (Zamanlanmış Görevler)** bölümüne girin.
2. "Add Task" deyin.
3. **Task Type:** "Run a PHP script" seçin.
4. **Script path:** `httpdocs/artisan` (Proje kök dizininizdeki artisan dosyası)
5. **Arguments:** `schedule:run`
6. **Run:** "Cron style" seçip `* * * * *` (Her dakika) olarak ayarlayın.
7. PHP sürümünü sunucuyla aynı seçip kaydedin.

## 7. Arkaplan İşlemleri (Queue Workers)
Mail, SMS ve toplantı linki oluşturma gibi asenkron görevlerin çalışması için Queue Worker'ın sürekli açık kalması gerekir.
Plesk'te Supervisor eklentisi yoksa veya SSH yetkiniz kısıtlıysa **Scheduled Tasks (Zamanlanmış Görevler)** üzerinden bir alternatif kurabilirsiniz:

1. Yeni bir Zamanlanmış Görev ekleyin.
2. **Task Type:** "Run a command" seçin.
3. **Command:** `php /var/www/vhosts/domaininiz.com/httpdocs/artisan queue:work --stop-when-empty`
4. **Run:** Her dakika çalışacak şekilde ayarlayın. *(Bu komut biriken işleri yapar ve bitince kapanır, bir sonraki dakika tekrar tetiklenir).*

*Eğer tam yetkili bir sunucuysa, Plesk Supervisor eklentisini kurup `php artisan queue:work` komutunu kalıcı bir proses olarak eklemeniz çok daha sağlıklıdır.*

## 8. Üçüncü Parti API Entegrasyonları (Son Adım)
Sistem başarıyla kurulduktan sonra sisteme giriş yapıp `/admin/settings` sayfasına (Sistem Ayarları) gidin. Burada canlı ortamınıza ait şu anahtarları girin:
- **Ödeme Sistemleri:** Stripe, Iyzico veya Shopier API/Secret Key'leri.
- **Video Konferans:** Zoom Account ID, Client ID, Client Secret (Aktif değilse varsayılan Jitsi çalışır).
- **SMS:** Netgsm/Twilio vb. servis sağlayıcınızın API bilgileri.
- **Sosyal Medya Girişi:** Apple, Google, Facebook girişleri için sunucunuzdaki `.env` dosyasına `GOOGLE_CLIENT_ID` vb. bilgileri ekleyin.

🎉 **Tebrikler! Projeniz canlıya başarıyla taşındı ve kullanıma hazır.**
