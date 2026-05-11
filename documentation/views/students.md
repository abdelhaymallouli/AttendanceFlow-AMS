# Student Views Documentation

The Student views focus on transparency and self-management of attendance records.

## 📄 `student/dashboard.blade.php`
- **Role**: Personal attendance summary and absence tracker.
- **Data Source**: `Student\DashboardController@index` — passes `$studentProfile`, `$stats`, `$recentAbsences`, `$recentHistory`.
- **Visuals (Mockup style: `rounded-[2.5rem]` cards, slate-900 dark accents, italic uppercase typography)**:
  - **Profile Header**: User avatar initial, name, student ID badge, group badge.
  - **Stats Cards** (2): Total Presence (%), Total Absences (hours, computed from real `duration_hours`).
  - **Weekly Progress Bar**: Dark card with animated progress bar, goal "Above 90% Attendance".
  - **Session History**: Last 5 attendance records with color-coded status (green=present, amber=late, red=absent), module name, date, time.
  - **Absence Details**: Dedicated red-themed section showing up to 10 recent absent/late records with module name, date, time, duration. Summary alert box showing total absence hours. Empty state with green checkmark when no absences.
- **Right Column**:
  - **Quick Justification Card**: Links to justification upload page.
  - **Today's Schedule**: Upcoming sessions for the student's group with timeline UI (blue dots, border-left connector).
- **Fixes applied**:
  - Added missing `use App\Models\Session;` import (was crashing).
  - Replaced hardcoded 2.5h per session with real `duration_hours` from DB.
  - Removed non-existent `justified` status stat (no such status in `attendance_records`).
  - View now uses `$recentHistory` from controller instead of making inline DB query.

## 📄 `student/justifications.blade.php`
- **Role**: Absence justification workspace.
- **Data Source**: `Student\JustificationController@index` — passes `$justifications`.
- **Features**:
  - Upload form with reason text input and file upload (PDF/JPG, max 10MB).
  - History table showing past justifications with status badges (pending=amber, approved=green, rejected=red).
