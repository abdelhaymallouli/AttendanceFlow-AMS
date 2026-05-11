# Admin Views Documentation

The Admin interface provides a high-level command center for the entire AttendanceFlow-AMS system.

## 📄 `admin/dashboard.blade.php`
- **Role**: Global landing page for administrators.
- **Visuals**: Displays key performance indicators (KPIs) like total active students, average attendance rate, and pending justifications.
- **Components**: Often includes summary cards and quick-action buttons.

## 📄 `admin/students/index.blade.php`
- **Role**: Master student list.
- **Features**:
  - Search bar for filtering by name or matricule.
  - Group filter dropdown.
  - Status indicators (e.g., "At Risk" badge for students with low attendance).
  - Actions for editing or viewing detailed student profiles.

## 📄 `admin/reports.blade.php`
- **Role**: Analytical hub.
- **Visuals**:
  - Charts (likely using Chart.js or similar) showing monthly trends.
  - Tables ranking class groups by performance.
  - List of "At Risk" students needing intervention.
- **Interactions**: Date range pickers to filter statistical data.

## 📄 `admin/justifications.blade.php`
- **Role**: Workflow management for justifications.
- **Features**:
  - List of pending justification requests.
  - Preview modals for viewing uploaded documents.
  - "Approve" and "Reject" actions that trigger status updates in the database.

## 📄 `admin/calendar.blade.php`
- **Role**: Holistic scheduling view.
- **Visuals**: A full-screen calendar (possibly using FullCalendar) showing all sessions across all groups and modules.

## 📄 `admin/attendance/`
- **Role**: Manual attendance overrides.
- **Features**: Allows an admin to select a specific session and modify the presence records for any student, overriding previous entries if necessary.
