# Teacher Controllers Documentation

The Teacher controllers focus on classroom management and the daily task of tracking attendance.

## 📄 `Teacher\DashboardController`
- **Role**: Daily landing page for teachers.
- **Key Features**:
  - Displays the teacher's schedule for the current day.
  - Links to "live" sessions that need attendance marking.

## 📄 `Teacher\AttendanceController`
- **Role**: Core utility for taking attendance.
*   **Method: `show(Session $session)`**:
    - **Security**: Verifies that the teacher is the one assigned to the session (or an admin).
    - **Data**: Fetches all students in the class group and any existing attendance records.
*   **Method: `store(Request $request, Session $session)`**:
    - **Logic**: Performs a bulk `updateOrCreate` on attendance records based on the form input.
    - **Status Types**: `present`, `absent`, `late`.
    - **Feedback**: Redirects back to the dashboard with a success notification.
