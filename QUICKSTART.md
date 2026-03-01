# 🚀 Quick Start Guide - GO BAKULA

Panduan cepat untuk menjalankan aplikasi GO BAKULA dalam 5 menit!

---

## 📋 Prerequisites

Pastikan sudah terinstall:
- ✅ PHP 8.2 atau lebih tinggi
- ✅ Composer
- ✅ Node.js 18.x atau lebih tinggi
- ✅ MySQL 8.x
- ✅ Git

---

## ⚡ Quick Setup (5 Menit)

### 1️⃣ Clone & Install (2 menit)
```bash
# Clone repository
git clone https://github.com/AkbariAidul/go-bakula.git
cd go-bakula

# Install dependencies
composer install
npm install
```

### 2️⃣ Environment Setup (1 menit)
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Edit `.env` file** - Sesuaikan database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=go-bakula
DB_USERNAME=root
DB_PASSWORD=
```

### 3️⃣ Database Setup (1 menit)
```bash
# Buat database (via MySQL client atau phpMyAdmin)
# Atau via command line:
mysql -u root -p -e "CREATE DATABASE go-bakula"

# Run migrations & seeders
php artisan migrate:fresh --seed

# Create storage link
php artisan storage:link
```

### 4️⃣ Build & Run (1 menit)
```bash
# Build assets
npm run build

# Start server
php artisan serve
```

### 5️⃣ Access Application
Buka browser: **http://localhost:8000**

---

## 🔑 Login Credentials

Gunakan salah satu akun berikut:

### Super Admin (Full Access)
- **Email**: admin@baritokualakab.go.id
- **Password**: password

### Admin Dinas (Manage Reports)
- **Email**: pupr@baritokualakab.go.id
- **Password**: password

### Warga (Create Reports)
- **Email**: warga@example.com
- **Password**: password

---

## 🎯 First Steps

### Sebagai Warga (Citizen)
1. Login dengan akun warga
2. Klik "Laporan" di sidebar
3. Klik "Buat Laporan"
4. Pilih kategori (contoh: Jalan Rusak)
5. Isi form dan upload foto
6. Klik "Ambil Lokasi GPS"
7. Submit laporan
8. Lihat laporan Anda di "Laporan Saya"

### Sebagai Admin
1. Login dengan akun admin
2. Lihat dashboard dengan statistik
3. Klik "Laporan" untuk melihat semua laporan
4. Klik salah satu laporan
5. Scroll ke bawah ke "Aksi Admin"
6. Ubah status laporan
7. Tambahkan catatan admin
8. Submit perubahan

### Sebagai Super Admin
1. Login dengan akun super admin
2. Akses menu "Dinas" atau "Kategori"
3. Klik "Tambah Dinas" atau "Tambah Kategori"
4. Isi form dan submit
5. Edit atau hapus data yang ada

---

## 🐛 Troubleshooting

### Error: "SQLSTATE[HY000] [1049] Unknown database"
**Solusi**: Buat database terlebih dahulu
```bash
mysql -u root -p -e "CREATE DATABASE go-bakula"
```

### Error: "npm: command not found"
**Solusi**: Install Node.js dari https://nodejs.org/

### Error: "composer: command not found"
**Solusi**: Install Composer dari https://getcomposer.org/

### Error: "Class 'PDO' not found"
**Solusi**: Enable PHP extensions di php.ini
```ini
extension=pdo_mysql
extension=mbstring
extension=openssl
```

### Error: "The stream or file could not be opened"
**Solusi**: Set permissions untuk storage
```bash
chmod -R 775 storage bootstrap/cache
```

### Port 8000 sudah digunakan
**Solusi**: Gunakan port lain
```bash
php artisan serve --port=8001
```

---

## 🔄 Development Mode

Untuk development dengan hot reload:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

Akses: http://localhost:8000

---

## 📦 Sample Data

Setelah `php artisan migrate:fresh --seed`, database akan berisi:

- **3 Users** (Super Admin, Admin Dinas, Warga)
- **4 Departments** (PUPR, BPBD, DLH, DISHUB)
- **12 Categories** (Jalan Rusak, Banjir, Sampah, dll)
- **5 Sample Reports** (berbagai status)

---

## 🎨 Features to Try

### ✅ Report Management
- Buat laporan baru dengan foto & GPS
- Filter laporan by status & priority
- Upvote laporan orang lain
- Track status laporan

### ✅ Gamification
- Lihat leaderboard
- Dapatkan poin dari aktivitas
- Naik level badge

### ✅ Admin Features
- Update status laporan
- Upload foto penyelesaian
- Manage departments & categories

---

## 📚 More Documentation

- **README.md** - Dokumentasi lengkap project
- **TESTING.md** - Panduan testing komprehensif
- **IMPLEMENTATION_STATUS.md** - Status implementasi fitur

---

## 🆘 Need Help?

**Developer**: AkbariAidul
- GitHub: [@AkbariAidul](https://github.com/AkbariAidul)
- Email: akbariaidul@gmail.com

---

## 🎉 You're Ready!

Selamat! Aplikasi GO BAKULA sudah siap digunakan. Silakan explore fitur-fitur yang tersedia dan laporkan bug jika ditemukan.

**Happy Coding! 🚀**
