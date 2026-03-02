# Layout Guide - GO BAKULA

## 📱 Adaptive Layout System

GO BAKULA menggunakan sistem layout yang berbeda berdasarkan role user untuk memberikan pengalaman terbaik sesuai kebutuhan masing-masing.

---

## 🎨 Layout Types

### 1. MobileAppLayout (WebApp Style)
**Digunakan untuk**: Warga (Citizens)

**Karakteristik**:
- 📱 Mobile-first design
- 🔽 Bottom navigation bar
- ➕ Floating Action Button (FAB) untuk create report
- 📊 Compact header dengan logo dan notifikasi
- 👤 User menu di header (klik avatar)
- 🎯 Optimized untuk touch interaction

**Navigation Items**:
- 🏠 Beranda (Dashboard)
- 📝 Laporan (All Reports)
- 📋 Saya (My Reports)
- 🏆 Ranking (Leaderboard)

**Features**:
- Sticky header dengan GO BAKULA branding
- Bottom navigation dengan icons
- FAB button untuk quick create report
- User dropdown menu (Profile & Logout)
- Responsive untuk mobile & tablet

---

### 2. DesktopLayout (Admin Dashboard Style)
**Digunakan untuk**: Admin Dinas & Super Admin

**Karakteristik**:
- 🖥️ Desktop-first design
- 📂 Left sidebar navigation
- 📊 Top bar dengan page title
- 👤 User info di bottom sidebar
- 🎯 Optimized untuk data management

**Navigation Items (Admin Dinas)**:
- 📊 Dashboard
- 📝 Laporan
- 🏆 Leaderboard

**Navigation Items (Super Admin)**:
- 📊 Dashboard
- 📝 Laporan
- 🏆 Leaderboard
- 🏢 Dinas (Departments Management)
- 📁 Kategori (Categories Management)

**Features**:
- Fixed sidebar dengan navigation
- User dropdown menu di sidebar bottom
- Topbar dengan page title dan notifications
- Wide content area untuk tables and forms

---

## 🔄 Layout Selection Logic

Layout dipilih secara otomatis berdasarkan role user:

```javascript
const layoutComponent = computed(() => {
  const roles = page.props.auth.user?.roles;
  const isWarga = roles?.includes('warga') && 
                  !roles?.includes('super_admin') && 
                  !roles?.includes('admin_dinas');
  return isWarga ? MobileAppLayout : DesktopLayout;
});
```

**Logic**:
- ✅ **Warga only** → MobileAppLayout
- ✅ **Admin Dinas** → DesktopLayout
- ✅ **Super Admin** → DesktopLayout
- ✅ **Multiple roles** → DesktopLayout (admin takes precedence)

---

## 📄 Pages Using Dynamic Layout

Semua pages berikut menggunakan dynamic layout:

### Main Pages
- ✅ Dashboard.vue
- ✅ Reports/Index.vue
- ✅ Reports/Create.vue
- ✅ Reports/Show.vue
- ✅ Reports/MyReports.vue
- ✅ Gamification/Leaderboard.vue

### Admin Only (Always DesktopLayout)
- Departments/Index.vue
- Categories/Index.vue
- Profile/Edit.vue

### Guest Pages (Static Layout)
- Welcome.vue (GuestLayout)
- Auth pages (Login, Register, etc - GuestLayout)

---

## 🎯 User Experience by Role

### Warga (Citizen) Experience
```
Login → MobileAppLayout
├── 🏠 Beranda (Dashboard with stats)
├── 📝 Laporan (Browse all reports)
├── ➕ FAB Button (Quick create report)
├── 📋 Saya (My reports only)
└── 🏆 Ranking (Leaderboard)
```

**Optimized for**:
- Quick report creation
- Easy browsing on mobile
- Touch-friendly interactions
- Minimal navigation steps

---

### Admin Dinas Experience
```
Login → DesktopLayout
├── 📊 Dashboard (Analytics & stats)
├── 📝 Laporan (Manage all reports)
│   ├── Update status
│   ├── Add admin notes
│   └── Upload completion photo
└── 🏆 Leaderboard (View rankings)
```

**Optimized for**:
- Data management
- Bulk operations
- Detailed views
- Multi-tasking

---

### Super Admin Experience
```
Login → DesktopLayout
├── 📊 Dashboard (Full analytics)
├── 📝 Laporan (Full report management)
├── 🏆 Leaderboard (Rankings)
├── 🏢 Dinas (CRUD departments)
└── 📁 Kategori (CRUD categories)
```

**Optimized for**:
- System administration
- Configuration management
- Full CRUD operations
- Advanced features

---

## 🔧 Implementation Details

### MobileAppLayout Features

**Header**:
```vue
<header class="bg-white shadow-sm sticky top-0 z-10">
  - GO BAKULA logo
  - Notification bell (with badge)
  - User avatar (clickable for menu)
</header>
```

**Bottom Navigation**:
```vue
<nav class="fixed bottom-0 left-0 right-0">
  - 4-5 navigation items
  - Active state highlighting
  - Icon + label
</nav>
```

**FAB Button**:
```vue
<Link class="fixed bottom-20 right-4 w-16 h-16">
  - Quick access to create report
  - Floating above bottom nav
  - Green primary color
</Link>
```

**User Menu**:
```vue
<div class="absolute top-16 right-4">
  - User name & role
  - Profile link
  - Logout button
</div>
```

---

### DesktopLayout Features

**Sidebar**:
```vue
<aside class="w-64 bg-white fixed h-full">
  - GO BAKULA branding
  - Navigation menu
  - User info at bottom
  - Dropdown menu
</aside>
```

**Topbar**:
```vue
<header class="bg-white shadow-sm sticky top-0">
  - Page title
  - Notification bell
  - Quick actions
</header>
```

**User Menu**:
```vue
<div class="absolute bottom-full">
  - Profile link
  - Logout button
  - Hover effects
</div>
```

---

## 📱 Responsive Behavior

### MobileAppLayout
- **Mobile (< 768px)**: Full mobile experience
- **Tablet (768px - 1024px)**: Slightly wider content
- **Desktop (> 1024px)**: Max-width container with mobile nav

### DesktopLayout
- **Mobile (< 768px)**: Sidebar hidden, hamburger menu (future)
- **Tablet (768px - 1024px)**: Sidebar visible, compact content
- **Desktop (> 1024px)**: Full sidebar + wide content area

---

## 🎨 Design Consistency

Both layouts maintain:
- ✅ Same color scheme (Green primary #22c55e)
- ✅ Same typography (Poppins font)
- ✅ Same component styles
- ✅ Same interaction patterns
- ✅ Consistent spacing (Tailwind scale)

---

## 🔄 Switching Between Layouts

Users can experience different layouts by:

1. **Logout** from current account
2. **Login** with different role
3. **Layout automatically changes**

Example:
```bash
# Test as Warga (MobileAppLayout)
Login: warga@example.com / password

# Test as Admin (DesktopLayout)
Login: pupr@baritokualakab.go.id / password

# Test as Super Admin (DesktopLayout)
Login: admin@baritokualakab.go.id / password
```

---

## 🚀 Future Enhancements

### MobileAppLayout
- [ ] Pull-to-refresh
- [ ] Swipe gestures
- [ ] Offline mode (PWA)
- [ ] Push notifications
- [ ] Camera integration improvements

### DesktopLayout
- [ ] Collapsible sidebar
- [ ] Dark mode
- [ ] Keyboard shortcuts
- [ ] Multi-window support
- [ ] Advanced filters

### Both
- [ ] Theme customization
- [ ] Accessibility improvements
- [ ] Performance optimization
- [ ] Animation enhancements

---

## 📝 Developer Notes

### Adding New Pages

When creating new pages, use dynamic layout:

```vue
<template>
  <component :is="layoutComponent" page-title="Your Title">
    <!-- Your content -->
  </component>
</template>

<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';
import MobileAppLayout from '@/Layouts/MobileAppLayout.vue';

const page = usePage();

const layoutComponent = computed(() => {
  const roles = page.props.auth.user?.roles;
  const isWarga = roles?.includes('warga') && 
                  !roles?.includes('super_admin') && 
                  !roles?.includes('admin_dinas');
  return isWarga ? MobileAppLayout : DesktopLayout;
});
</script>
```

### Modifying Layouts

**MobileAppLayout**: `resources/js/Layouts/MobileAppLayout.vue`
**DesktopLayout**: `resources/js/Layouts/DesktopLayout.vue`

After changes:
```bash
npm run build  # Production
npm run dev    # Development with hot reload
```

---

## ✅ Testing Checklist

### MobileAppLayout (Warga)
- [ ] Bottom navigation works
- [ ] FAB button creates report
- [ ] User menu opens/closes
- [ ] Logout works
- [ ] All pages render correctly
- [ ] Touch interactions smooth
- [ ] Responsive on mobile

### DesktopLayout (Admin)
- [ ] Sidebar navigation works
- [ ] User dropdown works
- [ ] Logout works
- [ ] All pages render correctly
- [ ] Mouse interactions smooth
- [ ] Responsive on desktop

---

**Last Updated**: 2026-03-01
**Version**: 1.1.0
