# Testing Guide - GO BAKULA

## Setup Testing Environment

### 1. Database Setup
```bash
# Fresh migration with seeders
php artisan migrate:fresh --seed

# Or run specific seeder
php artisan db:seed --class=ReportSeeder
```

### 2. Start Development Server
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (optional, for hot reload)
npm run dev
```

### 3. Access Application
- URL: http://localhost:8000
- Default users created by seeder

## Test Accounts

| Role | Email | Password | Access Level |
|------|-------|----------|--------------|
| Super Admin | admin@baritokualakab.go.id | password | Full access |
| Admin Dinas | pupr@baritokualakab.go.id | password | Manage reports |
| Warga | warga@example.com | password | Create reports |

## Testing Checklist

### 1. Authentication Flow ✓
- [ ] Visit homepage (/)
- [ ] Click "Masuk" button
- [ ] Login with test account
- [ ] Verify redirect to dashboard
- [ ] Check user info in sidebar
- [ ] Logout functionality

### 2. Dashboard (All Roles) ✓
- [ ] View stats cards (Total, Pending, Progress, Done, Urgent)
- [ ] Verify stats numbers match database
- [ ] View recent reports list
- [ ] Click on report to view details
- [ ] Check navigation sidebar

### 3. Reports - View & Filter (All Roles) ✓
**URL**: /reports

- [ ] View all reports list
- [ ] Filter by status (Pending, Verified, In Progress, Completed, Rejected)
- [ ] Filter by priority (Urgent, Normal)
- [ ] Reset filters
- [ ] Pagination works
- [ ] Click report card to view details

### 4. Reports - Create (Warga Only) ✓
**URL**: /reports/create

- [ ] Select category (grid of categories with icons)
- [ ] Enter report title
- [ ] Enter description
- [ ] Upload photo (camera/file)
  - Test with image < 5MB
  - Test with image > 5MB (should fail)
- [ ] Get GPS location
  - Allow browser location access
  - Verify latitude/longitude displayed
- [ ] Submit report
- [ ] Verify redirect to report detail page
- [ ] Check report appears in "Laporan Saya"

### 5. Reports - Detail View ✓
**URL**: /reports/{id}

- [ ] View report photo
- [ ] View report details (title, description, category, department)
- [ ] View location info
- [ ] View status timeline
- [ ] View upvote count
- [ ] Upvote button (if not own report)
  - Click to upvote
  - Click again to remove upvote
  - Verify count updates
- [ ] Admin notes (if exists)
- [ ] Completion photo (if completed)

### 6. Reports - My Reports (Warga Only) ✓
**URL**: /reports/my-reports

- [ ] View only user's own reports
- [ ] Empty state if no reports
- [ ] Click "Buat Laporan Baru" button
- [ ] Click report to view details

### 7. Reports - Admin Actions (Admin Only) ✓
**URL**: /reports/{id} (when logged in as admin)

- [ ] View "Aksi Admin" section
- [ ] Change status dropdown
  - Verified
  - In Progress
  - Completed
  - Rejected
- [ ] Add admin notes
- [ ] Submit status update
- [ ] Verify status updated
- [ ] Upload completion photo (for completed status)

### 8. Upvote System ✓
- [ ] Login as different user (not report owner)
- [ ] View report detail
- [ ] Click upvote button
- [ ] Verify count increases
- [ ] Check gamification points awarded (+1 point)
- [ ] Click upvote again to remove
- [ ] Verify count decreases
- [ ] Check points removed (-1 point)
- [ ] Test urgent marking (>50 upvotes)

### 9. Gamification - Leaderboard ✓
**URL**: /leaderboard

- [ ] View top users ranked by points
- [ ] Verify medal icons for top 3
- [ ] View user badges
- [ ] View reports completed count
- [ ] View upvotes given count
- [ ] Check badge info section
- [ ] Verify badge levels:
  - 🥉 Pemula (0 points)
  - 🥈 Warga Peduli (50 points)
  - 🥇 Pahlawan Lingkungan (100 points)
  - 💎 Guardian Kota (250 points)
  - 👑 Legend (500 points)

### 10. Departments Management (Super Admin Only) ✓
**URL**: /departments

- [ ] View departments grid
- [ ] Click "Tambah Dinas"
- [ ] Fill form:
  - Name
  - Code
  - Description
  - Contact email
  - Contact phone
  - Active status
- [ ] Submit and verify created
- [ ] Edit department
- [ ] Delete department (with confirmation)
- [ ] Verify categories count
- [ ] Verify reports count

### 11. Categories Management (Super Admin Only) ✓
**URL**: /categories

- [ ] View categories grid
- [ ] Click "Tambah Kategori"
- [ ] Fill form:
  - Department
  - Name
  - Icon (emoji)
  - Color (color picker)
  - Description
  - Active status
- [ ] Submit and verify created
- [ ] Edit category
- [ ] Delete category (with confirmation)
- [ ] Verify color border on card

### 12. Role-Based Access Control ✓
**Test as Warga**:
- [ ] Can access: Dashboard, Reports, My Reports, Leaderboard
- [ ] Cannot access: Departments, Categories
- [ ] Can create reports
- [ ] Cannot update report status
- [ ] Can upvote other reports

**Test as Admin Dinas**:
- [ ] Can access: Dashboard, Reports, Leaderboard
- [ ] Cannot access: Departments, Categories, My Reports
- [ ] Cannot create reports (optional)
- [ ] Can update report status
- [ ] Can upload completion photo

**Test as Super Admin**:
- [ ] Can access: All pages
- [ ] Can manage departments
- [ ] Can manage categories
- [ ] Can update report status
- [ ] Full CRUD access

### 13. Responsive Design ✓
- [ ] Test on desktop (1920x1080)
- [ ] Test on tablet landscape (1024x768)
- [ ] Test on tablet portrait (768x1024)
- [ ] Test on mobile (375x667)
- [ ] Verify sidebar on desktop
- [ ] Verify bottom nav on mobile (if MobileAppLayout used)

### 14. Form Validation ✓
**Report Creation**:
- [ ] Submit without category (should fail)
- [ ] Submit without title (should fail)
- [ ] Submit without description (should fail)
- [ ] Submit without photo (should fail)
- [ ] Submit without GPS (should fail)
- [ ] Submit with invalid photo format (should fail)
- [ ] Submit with photo > 5MB (should fail)

**Department/Category**:
- [ ] Submit without required fields (should fail)
- [ ] Submit duplicate code/name (should fail)
- [ ] Submit invalid email format (should fail)

### 15. Performance & UX ✓
- [ ] Page load time < 2 seconds
- [ ] Image loading with proper sizing
- [ ] Smooth transitions and animations
- [ ] Loading states on form submissions
- [ ] Success/error messages display
- [ ] Proper error handling
- [ ] No console errors

### 16. Data Integrity ✓
- [ ] Create report → verify in database
- [ ] Update status → verify timestamps updated
- [ ] Upvote → verify count incremented
- [ ] Complete report → verify points awarded
- [ ] Delete report → verify cascade deletes
- [ ] Urgent marking at 50 upvotes

## Known Issues & Limitations

1. **Photo Upload**: Sample reports use placeholder paths. Real photos need to be uploaded via UI.
2. **GPS**: Requires browser location permission. May not work on localhost without HTTPS.
3. **Real-time**: Laravel Reverb not yet configured for real-time notifications.
4. **Mobile Layout**: MobileAppLayout created but not fully integrated with device detection.

## Next Steps

1. **Add Real-time Notifications**: Configure Laravel Reverb for live updates
2. **Add Heatmap**: Integrate Google Maps for location visualization
3. **Add Analytics**: More detailed statistics and charts
4. **Add Export**: PDF/Excel export for reports
5. **Add Email Notifications**: Notify users on status changes
6. **Add Mobile App**: PWA or native mobile app
7. **Add Tests**: PHPUnit tests for backend, Vitest for frontend

## Bug Reporting

If you find any bugs during testing:
1. Note the steps to reproduce
2. Take screenshots if applicable
3. Check browser console for errors
4. Report to developer with details

## Success Criteria

All checklist items should be ✓ before considering the application production-ready.

---

**Last Updated**: 2026-03-01
**Tested By**: [Your Name]
**Version**: 1.0.0
