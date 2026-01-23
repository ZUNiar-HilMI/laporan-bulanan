# Laporan Bulanan

Aplikasi pelaporan kegiatan bulanan berbasis Laravel untuk mengelola, memverifikasi, dan mengekspor data laporan kegiatan secara terstruktur.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue?style=flat-square)

---

## Fitur

### Anggota
- Pelaporan kegiatan dengan dokumentasi foto (sebelum dan sesudah)
- Pencatatan lokasi GPS secara otomatis atau manual melalui peta interaktif
- Pencatatan anggaran kegiatan
- Dashboard statistik personal
- Sistem revisi berdasarkan feedback admin

### Administrator
- Verifikasi kegiatan dengan opsi setuju, revisi, atau tolak
- Pemberian catatan revisi untuk perbaikan
- Visualisasi statistik anggaran bulanan
- Manajemen pengguna dan pengaturan role
- Export laporan ke format Excel dan Word

### Umum
- Mode tampilan terang dan gelap
- Desain responsif untuk berbagai perangkat
- Upload dan crop foto profil
- Sistem autentikasi yang aman

---

## Persyaratan Sistem

- PHP 8.2 atau lebih tinggi
- Composer
- Node.js dan NPM
- SQLite atau MySQL

---

## Instalasi

```bash
# Clone repository
git clone https://github.com/ZUNiar-HilMI/laporan-bulanan.git
cd laporan-bulanan

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate

# Setup database (SQLite)
touch database/database.sqlite

# Jalankan migrasi database
php artisan migrate

# Buat symbolic link untuk storage
php artisan storage:link

# Jalankan development server
php artisan serve
```

Akses aplikasi melalui `http://localhost:8000`

---

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | Laravel 12 |
| Database | SQLite / MySQL |
| Frontend | Blade, Bootstrap 5 |
| Visualisasi | Chart.js |
| Peta | Leaflet.js, OpenStreetMap |
| Image Processing | Cropper.js |
| Export | PhpSpreadsheet, PhpWord |

---

## Struktur Proyek

```
laporan-bulanan/
├── app/
│   ├── Exports/              # Logic export Excel dan Word
│   ├── Http/Controllers/     # Controllers
│   ├── Models/               # Eloquent Models
│   └── Middleware/           # Role-based middleware
├── database/
│   └── migrations/           # Database migrations
├── resources/
│   └── views/                # Blade templates
├── routes/
│   └── web.php               # Web routes
└── public/                   # Public assets
```

---

## Penggunaan

### Development

```bash
# Menjalankan semua service development secara bersamaan
composer dev
```

### Testing

```bash
composer test
```

### Setup Cepat

```bash
composer setup
```

---

## Kontributor

- ZUNiar-HilMI

---

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](LICENSE).
