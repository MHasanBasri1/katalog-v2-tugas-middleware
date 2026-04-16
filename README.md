# Kataloque - Katalog Produk Modern v2

Kataloque adalah sistem katalog produk (marketplace-like) modern yang dibangun menggunakan Laravel. Aplikasi ini dirancang untuk kemudahan manajemen produk, kategori, artikel blog, banner iklan, dan sistem voucher untuk member.

## 🚀 Fitur Utama
- **Admin Panel Premium**: Manajemen produk, kategori, banner, artikel, dan member.
- **Voucher System**: Buat dan validasi voucher (Nominal/Persentase) untuk promosi.
- **Member Dashboard**: Halaman khusus member untuk kelola profil, favorit, dan melihat voucher tersedia.
- **Product Discovery**: Pencarian produk cepat dengan filter kategori dan pengurutan (terbaru, terlaris, rating).
- **SEO Optimized**: Meta tag otomatis, struktur heading yang benar, dan sitemap generation.
- **Responsive Design**: Tampilan optimal di Mobile, Tablet, maupun Desktop.
- **API v1 Ready**: Endpoint API yang lengkap untuk integrasi dengan Flutter (Mobile App).

## 🛠️ Tech Stack
- **Backend**: Laravel 10.x / 11.x (PHP 8.2+)
- **Frontend**: Blade, Tailwind CSS, Alpine.js, Livewire
- **Database**: MySQL / MariaDB
- **Asset Bundler**: Vite
- **Integrasi**: Google Login (OAuth), Tabler Icons, FontAwesome 6

## 📋 Persyaratan Sistem
- PHP >= 8.2
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Composer
- Node.js & NPM

## ⚙️ Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd katalog-v2
   ```

2. **Instal Dependensi (PHP)**
   ```bash
   composer install
   ```

3. **Instal Dependensi (JS)**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` dan sesuaikan pengaturan database serta mail.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Migrasi & Seed Database**
   ```bash
   php artisan migrate --seed
   ```

6. **Build Asset**
   ```bash
   npm run dev
   # atau untuk produksi:
   npm run build
   ```

7. **Jalankan Aplikasi**
   ```bash
   php artisan serve
   ```

## 📖 Dokumentasi API
Dokumentasi endpoint API lengkap untuk Flutter dapat ditemukan di file:
[docs/API_ENDPOINTS.txt](docs/API_ENDPOINTS.txt)

## 📄 Lisensi
Sistem ini bersifat proprietary (pribadi). Hak cipta © 2024 Kataloque.
