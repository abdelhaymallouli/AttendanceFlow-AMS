# Teacher Views Documentation

The Teacher views are designed for high efficiency in active teaching environments.

## 📄 `teacher/dashboard.blade.php`
- **Role**: Daily task manager for teachers.
- **Visuals**:
  - **Today's Schedule**: List of sessions scheduled for the current teacher today.
  - **Action Link**: A prominent "Mark Attendance" button for the currently active session.

## 📄 `teacher/attendance.blade.php`
- **Role**: The core attendance input tool.
- **Design**:
  - Optimised for quick selection (likely using checkboxes or radio groups).
  - Student names and photos (if available).
  - **One-Click Bulk Actions**: (e.g., "Mark all as present").
  - **Save Workflow**: Validates the input and redirects to the dashboard with confirmation.
