# Admin Controllers Documentation

The Admin controllers handle the management and high-level analytical functions of the system.

## 📄 `Admin\DashboardController`
- **Role**: Provides the global overview for administrators.
- **Key Features**: Aggregates top-level statistics (total users, sessions today, pending justifications).

## 📄 `Admin\StudentController`
- **Role**: Comprehensive student management.
- **Key Features**:
  - `index()`: Paginated list of students with search (name, email, matricule) and group filtering.
  - **Risk Detection**: Logic to identify students with attendance rates < 90%.

## 📄 `Admin\ReportController`
- **Role**: The "Brain" of the administrative reporting.
- **Key Features**:
  - **Global Stats**: Calculates overall attendance rate and absence trends.
  - **Class Performance**: Ranks groups by their collective attendance rate.
  - **Monthly Trends**: Provides data for charts showing attendance evolution over months.
  - **At-Risk Analysis**: Deep dive into specific students failing to meet attendance requirements.

## 📄 `Admin\JustificationController`
- **Role**: Administrative review of absence excuses.
- **Key Features**: List and process (approve/reject) justification documents submitted by students.

## 📄 `Admin\AttendanceController`
- **Role**: Administrative override of attendance.
- **Key Features**: Allows admins to modify or point attendance for any session in the system.

## 📄 `Admin\CalendarController`
- **Role**: Visual schedule management.
- **Key Features**: Manages the global calendar view for all groups and modules.
