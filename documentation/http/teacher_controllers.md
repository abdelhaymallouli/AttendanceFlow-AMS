# Teacher Controllers Documentation

The Teacher controllers focus on classroom management and the daily task of tracking attendance.

## 📄 `Teacher\DashboardController`
- **Role**: Daily landing page for teachers.
- **Key Features**:
  - Displays the teacher's schedule for the current day (filtered to today via `whereDate('start_time', Carbon::today())`).
  - Links to "live" sessions that need attendance marking.
  - **Stats computation**: Total students (via groups with student_profiles count), average attendance percentage, pending justifications count.
  - **Per-group attendance rates**: For each group the teacher is assigned to (via `module_teacher_group` pivot), computes attendance rate from `AttendanceRecord` counts.
  - **Recent activity feed**: Concatenates last 5 attendance records + last 3 justifications for the teacher's students, sorted by time.
- **Data passed to view**: `$teacherProfile`, `$sessionsData` (mapped session array), `$currentSession` (active session or null), `$stats`, `$recentActivity`, `$teacherGroups`.

## 📄 `Teacher\SessionController`
- **Role**: Self-service session creation for teachers.
- **Key Features**:
  - **`create()`**: Loads `$modules` and `$groups` filtered through the `module_teacher_group` pivot — teachers can only create sessions for (module, group) pairs they are assigned to.
  - **`store()`**: Validates input (module, group, type, date, start/end time), verifies the teacher is assigned to the (module, group) pair via pivot DB query, computes duration, calls `SchedulingService::scheduleSession()` for conflict detection. On success redirects to attendance marking; on error returns with input + error message.
- **Routes**: `GET /teacher/sessions/create`, `POST /teacher/sessions`.

## 📄 `Teacher\AttendanceController`
- **Role**: Core utility for taking attendance.
- **Methods**:
  * **`index(Request $request)`**:
    - Reads `?date=` query param (defaults to today).
    - Queries sessions for the teacher filtered by date, ordered ascending.
    - Returns `teacher.attendance.index` view with `$sessions` and `$date`.
  * **`show(Session $session)`**:
    - **Security**: Verifies that the teacher is the one assigned to the session (or an admin).
    - **Data**: Fetches all students in the class group with their user profiles, and any existing attendance records as `[student_profile_id => status]` map.
    - Returns `teacher.attendance.show` view with `$session`, `$students`, `$existingRecords`.
  * **`store(Request $request, Session $session)`**:
    - **Auth**: Same check as `show()`.
    - **Validation**: `attendance` must be an array of `present|absent|late` values.
    - **Logic**: Performs a bulk `updateOrCreate` on attendance records based on `[studentId => status]` form input. Sets `date` from session's `start_time`.
    - **Feedback**: Redirects to teacher dashboard with success notification.
- **Routes**:
  - `GET /teacher/attendance` → `teacher.attendance.index`
  - `GET /teacher/sessions/{session}/attendance` → `teacher.sessions.attendance.show`
  - `POST /teacher/sessions/{session}/attendance` → `teacher.sessions.attendance.store`
