# Teacher Views Documentation

The Teacher views are designed for high efficiency in active teaching environments, matching the Preline design system and maquete mockups.

## 📄 `teacher/dashboard.blade.php`
- **Role**: Daily task manager for teachers.
- **Data Source**: `Teacher\DashboardController@index` — passes `$sessionsData`, `$stats`, `$recentActivity`, `$teacherGroups`, `$currentSession`.
- **Visuals**:
  - **Current Session Banner**: Gradient blue banner with pulse dot, shown when a session is active. Links directly to attendance marking.
  - **Stats Cards** (4): Total Students, Present Today (computed from avg attendance), Absent Today, Pending Justifications (linked).
  - **Quick Actions** (left column): Take Attendance, View Sessions, Export Report, Send Notifications buttons.
  - **My Classes Overview**: Per-group attendance rates with color coding (green >= 95%, amber >= 90%, red < 90%).
  - **Today's Sessions** (right column): List view with status-colored rows (blue=active, green=completed, gray=upcoming), type icons, module name, time, group, student count. Action link per session.
  - **Recent Activity**: Dynamic feed from DB — last 5 attendance records + last 3 justifications, status-colored.
- **Alpine.js**: Client-side status determination (active/completed/upcoming) by comparing current time to session times.
- **Filter**: Sessions filtered to `whereDate('start_time', Carbon::today())` — only today's sessions shown.

## 📄 `teacher/attendance/index.blade.php`
- **Role**: Session picker for attendance entry. Teacher selects a date then clicks a session.
- **Data Source**: `Teacher\AttendanceController@index` — passes `$sessions` (filtered by teacher + date) and `$date`.
- **Visuals**:
  - Stepped header: "Select Date & Session" with numbered step badge.
  - Date picker input with `x-model` and `@change` redirect to `?date=YYYY-MM-DD`.
  - Session cards grid (1/2/3 cols responsive): each shows time, duration, module name, group name. Links to `teacher.sessions.attendance.show`.
  - Empty state when no sessions exist for the selected date.
- **Alpine.js**: `attendanceApp(sessions, date)` function — stores sessions, handles date change via redirect.

## 📄 `teacher/attendance/show.blade.php`
- **Role**: Core attendance input tool for marking student status.
- **Data Source**: `Teacher\AttendanceController@show` — passes `$session`, `$students`, `$existingRecords`.
- **Visuals** (matches `admin/attendance/show.blade.php` style):
  - **Session Context Banner**: Blue info bar with module name, group, time, student count. "Change session" button links back to index.
  - **Controls Bar**: Search input (DOM-based filtering), Quick Actions (All Present / Reset buttons).
  - **Summary Bar**: Present/Absent/Late/Unmarked counts with colored dots, uppercase labels, `bg-gray-50` background. Save button with shadow + hover effects.
  - **Student List**: Table with columns #, Student ID, Name, Status. Uses `<x-student-attendance-row>` component with radio buttons (`peer-checked:` CSS) for status selection.
  - **Empty State**: "No students found in this group" when group has no students.
- **Alpine.js**: Inline `x-data` — `updateStats()` reads DOM radio buttons, `markAllPresent()` / `clearAll()` toggle radio states, `filterRows()` shows/hides rows via `querySelectorAll`.
- **No `@push('scripts')` or custom CSS** — fully self-contained in the Alpine attribute.

## 📄 `teacher/sessions/create.blade.php`
- **Role**: Self-service session creation for teachers.
- **Data Source**: `Teacher\SessionController@create` — passes `$modules` and `$groups` filtered by the teacher's assigned pairs via `module_teacher_group` pivot.
- **Visuals**:
  - Card-based form with module dropdown, group dropdown, session type selector (Lecture/TD/TP), date picker, start/end time inputs.
  - **Alpine.js Duration Calculator**: Automatically computes and displays session duration as the user adjusts start/end times.
  - Validation errors displayed inline from `store()` failure (pivot check or SchedulingService conflict).
  - On success, redirects to `teacher.sessions.attendance.show` for immediate attendance marking.
