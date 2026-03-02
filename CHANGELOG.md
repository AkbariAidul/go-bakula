# Changelog

All notable changes to GO BAKULA project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-03-01

### 🎉 Initial Release

#### Added - Backend
- Laravel 11 project setup with MySQL database
- Domain-Driven Design (DDD) architecture implementation
- Repository Pattern for data access
- Service Layer for business logic
- Spatie Laravel Permission for RBAC (3 roles, 10 permissions)
- Laravel Breeze for authentication
- Inertia.js for SSR
- Database migrations for all tables (users, departments, categories, reports, upvotes, user_points)
- Seeders for initial data (roles, permissions, departments, categories, sample reports)
- Models with proper relationships (User, Department, Category, Report, Upvote, UserPoint)
- Controllers for all features (Dashboard, Reports, Upvotes, Departments, Categories, Leaderboard)
- Services for business logic (ReportService, UpvoteService, GamificationService)
- Form validation (client & server side)
- File upload handling with validation
- GPS location capture
- Role-based access control middleware

#### Added - Frontend
- Vue 3 with Composition API
- Tailwind CSS 3.x with custom configuration
- Poppins font integration
- DesktopLayout with sidebar navigation
- MobileAppLayout with bottom navigation (prepared for mobile)
- DetectDevice middleware for responsive layouts
- Welcome page with GO BAKULA branding
- Dashboard with statistics cards and recent reports
- Reports pages:
  - Index (list with filters & pagination)
  - Create (form with GPS & camera integration)
  - Show (detail with upvote & admin actions)
  - MyReports (user's own reports)
- Gamification Leaderboard page
- Admin pages:
  - Departments management (CRUD with modal)
  - Categories management (CRUD with modal)
- Auth pages from Breeze (Login, Register, etc)
- Profile management pages

#### Added - Features
- **For Warga (Citizens)**:
  - Create reports with photo upload and GPS location
  - View all reports with filters (status, priority)
  - View own reports
  - Upvote other reports
  - Track report status timeline
  - View leaderboard and earn badges
  
- **For Admin Dinas**:
  - View all reports
  - Update report status (Verified, In Progress, Completed, Rejected)
  - Add admin notes
  - Upload completion photo
  - View dashboard analytics
  
- **For Super Admin**:
  - All admin_dinas features
  - Manage departments (CRUD)
  - Manage categories (CRUD)
  - View comprehensive statistics

- **Gamification System**:
  - Points for completed reports (+10 points)
  - Points for upvotes (+1 point)
  - 5 badge levels (Pemula, Warga Peduli, Pahlawan Lingkungan, Guardian Kota, Legend)
  - Leaderboard with top 50 users
  - Automatic badge calculation and upgrades

- **Upvote System**:
  - One-click upvote/downvote toggle
  - Real-time count updates
  - Automatic urgent marking when upvotes >= 50
  - Gamification integration

#### Added - Documentation
- README.md with comprehensive project documentation
- QUICKSTART.md for 5-minute setup guide
- TESTING.md with detailed testing checklist
- IMPLEMENTATION_STATUS.md with complete feature status
- CHANGELOG.md (this file)
- Code comments in critical sections

#### Added - Configuration
- Tailwind config with BWA design system
- Vite config for Vue 3
- Git configuration with .gitignore
- Environment example file (.env.example)
- EditorConfig for consistent coding style

#### Technical Details
- **Backend**: Laravel 11.x, PHP 8.2+, MySQL 8.x
- **Frontend**: Vue 3, Inertia.js, Tailwind CSS 3.x
- **Authentication**: Laravel Breeze + Sanctum
- **Permissions**: Spatie Laravel Permission
- **Design**: BWA (BuildWithAngga) - Clean, white, minimalist
- **Font**: Poppins (300, 400, 500, 600, 700)
- **Primary Color**: Green (#22c55e)
- **Architecture**: DDD + Repository + Service Layer

#### Database Schema
- users (with roles)
- departments (4 seeded: PUPR, BPBD, DLH, DISHUB)
- categories (12 seeded with icons & colors)
- reports (with GPS, photos, status tracking)
- upvotes (many-to-many user-report)
- user_points (gamification data)
- roles & permissions (Spatie)

#### Test Data
- 3 test users (Super Admin, Admin Dinas, Warga)
- 4 departments with 12 categories
- 5 sample reports with various statuses
- All with proper relationships

### 🔒 Security
- CSRF protection enabled
- SQL injection prevention (Eloquent ORM)
- XSS prevention (Vue escaping)
- File upload validation (type, size)
- Role-based access control
- Password hashing (bcrypt)
- Sanctum token authentication

### 🎨 UI/UX
- Responsive design (desktop, tablet, mobile)
- Smooth transitions and animations
- Loading states on form submissions
- Success/error toast messages
- Emoji icons for visual appeal
- Color-coded status badges
- Clean and minimalist design
- Consistent spacing and typography

### 📝 Git Commits
- Total commits: 15+
- Branch: dev
- Commit convention: Conventional Commits (feat, fix, docs, style, refactor, test, chore)
- All commits in Indonesian language as requested

### 🐛 Known Issues
- Sample report photos use placeholder paths (need real uploads via UI)
- GPS may require HTTPS on production (browser security)
- MobileAppLayout created but not fully integrated with device detection
- Laravel Reverb not configured for real-time notifications

### 📋 Not Implemented (Future)
- Real-time notifications via Laravel Reverb
- Google Maps heatmap integration
- Email notifications (SMTP)
- PDF/Excel export features
- Advanced analytics with charts
- Unit tests (PHPUnit, Vitest)
- API documentation (Swagger/OpenAPI)
- PWA or native mobile app

---

## [Unreleased]

### Added
- Dynamic layout system based on user role
- MobileAppLayout (WebApp style) for Warga (citizens)
- DesktopLayout (Admin dashboard style) for Admin & Super Admin
- User dropdown menu in both layouts (Profile & Logout)
- Floating Action Button (FAB) in MobileAppLayout for quick report creation
- Bottom navigation in MobileAppLayout
- LAYOUT_GUIDE.md documentation

### Changed
- All main pages now use dynamic layout selection
- Dashboard, Reports, Leaderboard adapt to user role
- Warga users get mobile-optimized experience
- Admin users get desktop-optimized experience

---

## [1.0.0] - 2026-03-01

### Planned Features
- Real-time notifications
- Email notifications on status changes
- Google Maps heatmap for report locations
- Export reports to PDF/Excel
- Advanced analytics dashboard with charts
- Multi-language support (Indonesian, English)
- Mobile app (PWA or React Native)
- Unit and integration tests
- API documentation
- Performance optimization
- Caching layer (Redis)
- Queue system for heavy tasks

---

## Version History

- **1.0.0** (2026-03-01) - Initial release with all core features
- **0.1.0** (2026-02-27) - Project initialization

---

## Contributing

Please read CONTRIBUTING.md for details on our code of conduct and the process for submitting pull requests.

## Developer

**AkbariAidul**
- GitHub: [@AkbariAidul](https://github.com/AkbariAidul)
- Email: akbariaidul@gmail.com

---

**Note**: This project is proprietary software for Kabupaten Barito Kuala.
