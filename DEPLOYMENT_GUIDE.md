# 📋 PANDUAN DEPLOYMENT KE CPANEL - TUNAS TIRTA FRESH

## **PRE-DEPLOYMENT CHECKLIST**

Pastikan semua ini sudah dilakukan sebelum upload:

### ✅ **1. Generate APP_KEY Baru**

Jalankan di terminal lokal Anda:

```bash
php artisan key:generate
```

Catat APP_KEY yang dihasilkan, nanti diperlukan.

---

### ✅ **2. Setup Database di cPanel**

**Via cPanel:**

1. Login ke cPanel → **MySQL Databases**
2. Buat database baru:
   - Name: `yourusername_tunas_tirta_db`
   - Username: `yourusername_tunas_user`
   - Password: Generate strong password (gunakan tool password generator)
   - Add user to database, grant ALL privileges

Catat credentials ini untuk `.env`

---

### ✅ **3. Setup SSL Certificate**

**Via cPanel:**

1. Login ke cPanel → **AutoSSL**
2. Pilih domain dan install AutoSSL (Gratis via Let's Encrypt)
3. Tunggu ~5 menit sampai aktif
4. Verify: Buka https://yourdomain.com (harus bisa)

---

### ✅ **4. Setup .htaccess untuk Force HTTPS & Laravel**

Upload file `.htaccess` ini ke `/public_html/` (atau `public/` folder):

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Force HTTPS
    RewriteCond %{HTTPS} off
    RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

    # Laravel public folder redirect
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [QSA,L]
</IfModule>
```

---

### ✅ **5. Konfigurasi File Permissions**

Jalankan via SSH/Terminal:

```bash
# Masuk ke folder project
cd ~/public_html/tunas-tirta/

# Set permissions untuk storage & cache
chmod -R 775 storage bootstrap/cache
chmod -R 755 public/

# Set ownership (ganti dengan username cPanel Anda)
chown -R username:username .
```

---

## **DEPLOYMENT STEPS**

### **Step 1: Upload Files**

Ada 2 cara:

**Cara A: Via FTP/SFTP**
- Gunakan FileZilla atau Cyberduck
- Upload semua file KECUALI:
  - `.env.local`
  - `.env.production`
  - `node_modules/`
  - `vendor/` (akan di-install di server)

**Cara B: Via Git**
```bash
cd ~/public_html/tunas-tirta
git init
git remote add origin https://github.com/yourrepo/tunas-tirta-fresh.git
git pull origin main
```

---

### **Step 2: Persiapkan Environment**

**Di cPanel File Manager atau SSH:**

1. Buka `public_html/tunas-tirta/` folder
2. Buat file `.env` baru dengan konten dari `.env.production`
3. Update nilai-nilai berikut:

```env
APP_KEY=base64:HASIL_DARI_php_artisan_key:generate
APP_URL=https://yourdomain.com

# Database (dari Step 2 di atas)
DB_HOST=localhost
DB_DATABASE=yourusername_tunas_tirta_db
DB_USERNAME=yourusername_tunas_user
DB_PASSWORD=strong_password_dari_cpanel

# Google OAuth
GOOGLE_CLIENT_ID=dari_google_console
GOOGLE_CLIENT_SECRET=dari_google_console
GOOGLE_REDIRECT_URL=https://yourdomain.com/auth/google/callback

# Midtrans
MIDTRANS_SERVER_KEY=dari_midtrans_sandbox
MIDTRANS_CLIENT_KEY=dari_midtrans_sandbox
MIDTRANS_IS_PRODUCTION=false

# Xendit
XENDIT_API_KEY=dari_xendit
XENDIT_CALLBACK_TOKEN=dari_xendit_webhook

# Fonnte
WHATSAPP_ADMIN=628xxxxxxxxx
FONNTE_TOKEN=dari_fonnte

# RajaOngkir
RAJAONGKIR_API_KEY=dari_rajaongkir
```

---

### **Step 3: Install Dependencies**

**Via SSH/Terminal:**

```bash
cd ~/public_html/tunas-tirta/

# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies
npm install

# Build assets
npm run build
```

---

### **Step 4: Setup Database**

```bash
# Generate APP_KEY (jika belum)
php artisan key:generate

# Run migrations
php artisan migrate --force

# Clear cache
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### **Step 5: Seeding (Optional)**

Jika ingin data dummy:

```bash
php artisan db:seed
```

---

### **Step 6: Cek Permissions & Ownership**

```bash
# Pastikan storage & cache writable
chmod -R 775 storage bootstrap/cache

# Pastikan public folder accessible
chmod -R 755 public/
```

---

## **POST-DEPLOYMENT VERIFICATION**

### ✅ **Cek Apakah Aplikasi Berjalan**

1. Buka browser: `https://yourdomain.com`
2. Verifikasi:
   - ✅ Halaman home muncul
   - ✅ HTTPS aktif (padlock icon)
   - ✅ Tidak ada error message
   - ✅ Database koneksi OK

---

### ✅ **Cek Environment**

Buat file `public/test.php` untuk verifikasi:

```php
<?php
echo "Environment: " . env('APP_ENV') . "<br>";
echo "Debug: " . (env('APP_DEBUG') ? 'ON' : 'OFF') . "<br>";
echo "Database: " . env('DB_DATABASE') . "<br>";
echo "URL: " . env('APP_URL') . "<br>";
?>
```

Akses: `https://yourdomain.com/test.php`

Hasil yang diharapkan:
```
Environment: production
Debug: OFF
Database: yourusername_tunas_tirta_db
URL: https://yourdomain.com
```

**Hapus file test.php setelah verifikasi!**

---

### ✅ **Test Payment Gateway**

1. Cek Midtrans sandbox: https://dashboard.sandbox.midtrans.com
2. Cek Xendit sandbox: https://dashboard.xendit.co
3. Setup webhooks di dashboard:
   - **Midtrans:** `https://yourdomain.com/api/webhooks/midtrans`
   - **Xendit:** `https://yourdomain.com/api/webhooks/xendit`

---

### ✅ **Test Webhook**

Gunakan Postman atau curl:

```bash
curl -X POST https://yourdomain.com/api/webhooks/midtrans \
  -H "Content-Type: application/json" \
  -d '{
    "transaction_status": "settlement",
    "order_id": "ORD-12345",
    "gross_amount": 100000,
    "signature_key": "test"
  }'
```

Response yang diharapkan: `200 OK`

---

## **TROUBLESHOOTING**

### ❌ **Error: APP_KEY not set**

```bash
php artisan key:generate
```

---

### ❌ **Error: The storage path does not exist**

```bash
# Create missing directories
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs

chmod -R 775 storage
```

---

### ❌ **Error: SQLSTATE[HY000]: General error**

Database tidak terkoneksi. Cek:
- DB_HOST correct
- DB_USERNAME correct
- DB_PASSWORD correct
- Database sudah dibuat

---

### ❌ **Error: Call to undefined function config_path**

```bash
php artisan config:cache
php artisan route:cache
```

---

### ❌ **403 Forbidden Error**

Cek `.htaccess` di folder `public/`. Pastikan sudah upload dengan benar.

---

### ❌ **HTTPS Redirect Loop**

Matikan automatic HTTPS redirect di `.htaccess` jika sudah di-handle cPanel.

---

## **MAINTENANCE & MONITORING**

### 📊 **Monitor Logs**

```bash
# Real-time log monitoring
tail -f storage/logs/laravel.log

# Via cPanel - Raw Access Logs
Login cPanel → Raw Access Logs → Pilih domain
```

---

### 🔧 **Clear Cache Regularly**

```bash
php artisan cache:clear
php artisan view:cache
```

---

### 💾 **Backup Database**

Via cPanel → MySQL Databases → Backup

Atau via SSH:

```bash
mysqldump -u yourusername_tunas_user -p yourusername_tunas_tirta_db > backup.sql
```

---

### ⏰ **Setup Auto-Backup**

```bash
# Buat cron job di cPanel
# Jalankan setiap hari jam 2 pagi
0 2 * * * cd /home/yourusername/public_html/tunas-tirta && php artisan backup:run
```

---

## **SECURITY HARDENING**

### 🔒 **After Going Live:**

1. ✅ Ubah database password
2. ✅ Regenerate all API keys di provider (Midtrans, Google, Fonnte, etc)
3. ✅ Enable 2FA di provider dashboards
4. ✅ Monitor login activity
5. ✅ Setup firewall rules di cPanel

---

## **PRODUCTION CHECKLIST AKHIR**

- [ ] APP_ENV = production
- [ ] APP_DEBUG = false
- [ ] APP_URL = https://yourdomain.com
- [ ] DATABASE = connected & tested
- [ ] SSL = installed & verified
- [ ] .htaccess = properly configured
- [ ] STORAGE = writable (775)
- [ ] PUBLIC = readable (755)
- [ ] PAYMENT GATEWAY = tested
- [ ] WEBHOOKS = configured & tested
- [ ] HTTPS FORCE = working
- [ ] LOGS = monitored
- [ ] BACKUP = configured

---

## **SUPPORT**

Jika ada error:

1. Cek `storage/logs/laravel.log` untuk detail error
2. Cek `public/error_log` untuk PHP errors
3. Cek email di cPanel untuk system notifications

---

**Good luck! 🚀**
