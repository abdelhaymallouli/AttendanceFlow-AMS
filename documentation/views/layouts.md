# Layouts & Navigation Documentation

The layout system ensures a consistent look and feel across all role-based dashboards.

## 📄 `layouts/dashboard.blade.php`
- **Role**: The master container for all authenticated pages.
- **Key Sections**:
  - **HTML Head**: Loads Google Fonts (Inter, Outfit), Vite assets (Tailwind/JS), and CSRF tokens.
  - **Mobile Sidebar**: A slide-over panel powered by Alpine.js for small screens.
  - **Desktop Sidebar**: A fixed left-hand navigation menu.
  - **Main Content Area**: Uses `@yield('content')` to inject page-specific views.
  - **User Profile Area**: Located at the bottom of the sidebar, containing the logout button.

## 📂 `layouts/partials/`
This folder contains role-specific fragments to avoid complex "if" logic in the main layout.

### `sidebar-admin.blade.php`
- Contains links to: Dashboard, Students, Reports, Justifications, and Calendar.

### `sidebar-teacher.blade.php`
- Contains links to: Dashboard (Schedule) and Attendance Marking history.

### `sidebar-student.blade.php`
- Contains links to: Dashboard (Stats) and Justification history.
