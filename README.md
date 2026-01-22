# 📊 Laporan Bulanan

Aplikasi Pelaporan Kegiatan Bulanan berbasis Laravel dengan fitur lengkap untuk mengelola dan memverifikasi laporan kegiatan.

![Laravel](https://img.shields.io/badge/Laravel-11-red?style=flat-square&logo=laravel)
![PHP](https://img.shields.io/badge/PHP-8.2+-blue?style=flat-square&logo=php)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## ✨ Fitur Utama

### 👤 Untuk Anggota
- 📝 Lapor kegiatan dengan foto sebelum & sesudah
- 📍 Lokasi GPS otomatis atau pilih manual di peta
- 💰 Pencatatan anggaran kegiatan
- 📊 Dashboard statistik kegiatan & anggaran
- 🔄 Sistem revisi kegiatan dari admin

### 👨‍💼 Untuk Admin
- ✅ Verifikasi kegiatan (Setuju/Revisi/Tolak)
- 📝 Berikan catatan revisi untuk perbaikan
- 📈 Lihat statistik anggaran bulanan (chart)
- 👥 Manajemen user (ubah role admin/anggota)
- 📥 Export laporan ke Excel & Word

### 🎨 Fitur Umum
- 🌙 Dark/Light mode
- 📱 Responsive design
- 🖼️ Upload foto profil dengan cropper (seperti Instagram)
- 🔐 Autentikasi aman

## 🚀 Instalasi

### Prasyarat
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite atau MySQL

### Langkah Instalasi

```bash
# Clone repository
git clone https://github.com/ZUNiar-HilMI/laporan-bulanan.git
cd laporan-bulanan

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Buat database SQLite
touch database/database.sqlite

# Jalankan migrasi
php artisan migrate

# Link storage
php artisan storage:link

# Jalankan server
php artisan serve
```

Buka `http://localhost:8000` di browser.

## 📸 Screenshot

| Dashboard | Verifikasi Admin |
|-----------|------------------|
| Statistik kegiatan & anggaran | Approve/Revisi/Reject kegiatan |

| Profile | Lapor Kegiatan |
|---------|----------------|
| Edit foto dengan cropper | Form dengan GPS & peta |

## 🛠️ Tech Stack

- **Backend:** Laravel 11
- **Database:** SQLite / MySQL
- **Frontend:** Blade, Bootstrap 5, Chart.js
- **Maps:** Leaflet.js + OpenStreetMap
- **Image Cropper:** Cropper.js
- **Export:** PhpSpreadsheet, PhpWord

## 📁 Struktur Folder

```
├── app/
│   ├── Exports/          # Export Excel & Word
│   ├── Http/Controllers/ # Controllers
│   ├── Models/           # Eloquent Models
│   └── Middleware/       # Role Middleware
├── database/migrations/  # Database migrations
├── resources/views/      # Blade templates
├── routes/web.php        # Web routes
└── public/               # Public assets
```

## 👨‍💻 Kontributor

- **ZUNiar-HilMI** - Developer

## 📄 Lisensi

Project ini menggunakan lisensi MIT.

---

⭐ Jika project ini membantu, berikan bintang!
