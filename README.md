# GO BAKULA 🌿

**Gerbang Online Basis Aduan dan Komunikasi Untuk Lingkungan Aman**

Smart City Dashboard untuk Kabupaten Barito Kuala - Platform pelaporan terpadu berbasis web untuk warga melaporkan keluhan infrastruktur, lingkungan, dan layanan publik.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat&logo=laravel)
![Vue.js](https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=flat&logo=vue.js)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.x-38B2AC?style=flat&logo=tailwind-css)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=flat&logo=mysql)

## 🎯 Fitur Utama

### Untuk Warga (Citizen)
- 📝 **Buat Laporan** - Laporkan masalah dengan foto real-time dan GPS otomatis
- 📍 **Tracking Status** - Pantau progress laporan dari pending hingga selesai
- 👍 **Upvote System** - Dukung laporan warga lain (>50 upvotes = URGENT)
- 🏆 **Gamifikasi** - Dapatkan poin dan badge untuk kontribusi
- 📊 **Leaderboard** - Lihat ranking warga peduli

### Untuk Admin Dinas
- ✅ **Verifikasi Laporan** - Review dan verifikasi laporan masuk
- 🔄 **Update Status** - Ubah status laporan (Diproses, Selesai, Ditolak)
- 📸 **Upload Bukti** - Unggah foto penyelesaian pekerjaan
- 📈 **Dashboard Analytics** - Lihat statistik laporan per dinas

### Untuk Super Admin
- 🏢 **Manajemen Dinas** - CRUD dinas/instansi pemerintah
- 📁 **Manajemen Kategori** - CRUD kategori keluhan
- 👥 **Manajemen User** - Kelola akses pengguna
- 🗺️ **Heatmap** - Visualisasi area dengan laporan terbanyak
- ⏱️ **SLA Monitoring** - Tracking waktu penyelesaian

## 🏗️ Arsitektur

### Tech Stack
- **Backend**: Laravel 11.x (PHP 8.2+)
- **Frontend**: Vue 3 (Composition API) + Inertia.js
- **Styling**: Tailwind CSS + Poppins Font
- **Database**: MySQL 8.x
- **Real-time**: Laravel Reverb (WebSocket)
- **Authentication**: Laravel Breeze + Sanctum
- **Permissions**: Spatie Laravel Permission

### Design Pattern
- **Domain-Driven Design (DDD)**
- **Repository Pattern**
- **Service Layer Pattern**
- **SOLID Principles**

### Struktur Direktori
```
app/
├── Domains/
│   ├── Reports/
│   │   ├── Models/
│   │   ├── Services/
│   │   └── Repositories/
│   ├── Categories/
│   ├── Departments/
│   └── Gamification/
├── Http/
│   ├── Controllers/
│   └── Middleware/
resources/
├── js/
│   ├── Pages/
│   ├── Layouts/
│   └── Components/
└── css/
```

## 🚀 Instalasi

### Requirements
- PHP >= 8.2
- Composer
- Node.js >= 18.x
- MySQL >= 8.x
- Git

### Step-by-Step

1. **Clone Repository**
```bash
git clone https://github.com/AkbariAidul/go-bakula.git
cd go-bakula
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Environment Setup**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Configuration**

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=go-bakula
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run Migrations & Seeders**
```bash
php artisan migrate:fresh --seed
```

6. **Storage Link**
```bash
php artisan storage:link
```

7. **Build Assets**
```bash
npm run build
# atau untuk development
npm run dev
```

8. **Run Application**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## 👥 Default Users

Setelah seeding, gunakan akun berikut untuk testing:

| Role | Email | Password |
|------|-------|----------|
| Super Admin | admin@baritokualakab.go.id | password |
| Admin Dinas PUPR | pupr@baritokualakab.go.id | password |
| Warga | warga@example.com | password |

## 📱 UI/UX Design

### Design Language
- **Style**: BWA (BuildWithAngga) - Clean, white, minimalist
- **Typography**: Poppins (300, 400, 500, 600, 700)
- **Color Palette**:
  - Primary: Green (#22c55e) - Lingkungan/Keamanan
  - Accent: Slate - Teks
  - Background: White/Off-white (#F8FAFC)

### Adaptive Layout
- **Desktop/Tablet Landscape**: Sidebar navigation + Topbar
- **Mobile/Tablet Portrait**: Bottom navigation + FAB

## 🗂️ Database Schema

### Tables
- `users` - User accounts
- `departments` - Dinas/Instansi (PUPR, BPBD, DLH, DISHUB)
- `categories` - Kategori keluhan (Jalan Rusak, Banjir, Sampah, dll)
- `reports` - Laporan warga
- `upvotes` - Upvote laporan
- `user_points` - Poin & badge gamifikasi
- `roles` & `permissions` - RBAC (Spatie)

## 🎮 Gamifikasi

### Sistem Poin
- ✅ Laporan selesai: **+10 poin**
- 👍 Memberikan upvote: **+1 poin**

### Badge Levels
| Badge | Poin Required |
|-------|---------------|
| 🥉 Pemula | 0 |
| 🥈 Warga Peduli | 50 |
| 🥇 Pahlawan Lingkungan | 100 |
| 💎 Guardian Kota | 250 |
| 👑 Legend | 500 |

## 🔐 Role & Permissions

### Roles
1. **super_admin** - Full access
2. **admin_dinas** - Manage reports for their department
3. **warga** - Create and view reports

### Permissions
- `view reports`
- `create reports`
- `update reports`
- `delete reports`
- `verify reports`
- `complete reports`
- `manage departments`
- `manage categories`
- `view analytics`
- `manage users`

## 📊 API Endpoints

### Reports
```
GET    /reports              - List all reports
POST   /reports              - Create new report
GET    /reports/{id}         - Show report detail
PATCH  /reports/{id}/status  - Update status (admin)
POST   /reports/{id}/upvote  - Toggle upvote
```

### Departments (Super Admin)
```
GET    /departments          - List departments
POST   /departments          - Create department
PUT    /departments/{id}     - Update department
DELETE /departments/{id}     - Delete department
```

### Categories (Super Admin)
```
GET    /categories           - List categories
POST   /categories           - Create category
PUT    /categories/{id}      - Update category
DELETE /categories/{id}      - Delete category
```

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=ReportTest
```

## 📝 Development Workflow

### Git Workflow
```bash
# Checkout dev branch
git checkout dev

# Create feature branch
git checkout -b feature/nama-fitur

# Commit changes
git add .
git commit -m "feat: deskripsi fitur"

# Push to remote
git push origin feature/nama-fitur

# Create Pull Request to dev
```

### Commit Convention
- `feat:` - New feature
- `fix:` - Bug fix
- `docs:` - Documentation
- `style:` - Formatting
- `refactor:` - Code refactoring
- `test:` - Testing
- `chore:` - Maintenance

## 🤝 Contributing

1. Fork repository
2. Create feature branch
3. Commit changes
4. Push to branch
5. Create Pull Request

## 📄 License

This project is proprietary software for Kabupaten Barito Kuala.

## 👨‍💻 Developer

Developed by **AkbariAidul**
- GitHub: [@AkbariAidul](https://github.com/AkbariAidul)
- Email: akbariaidul@gmail.com

## 🙏 Acknowledgments

- Laravel Framework
- Vue.js Community
- Tailwind CSS
- Spatie Packages
- BuildWithAngga Design System

---

**GO BAKULA** - Wujudkan Barito Kuala yang Lebih Baik Bersama! 🌿✨
