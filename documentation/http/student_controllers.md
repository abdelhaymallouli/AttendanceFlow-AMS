# Student Controllers Documentation

The Student controllers provide self-service access to attendance data and justification submission.

## 📄 `Student\DashboardController`
- **Role**: Personal overview for the student.
- **Key Features**:
  - **Attendance Rate**: Calculation of the student's personal attendance percentage across all modules.
  - **Absence Counter**: Total hours missed (weighted by session duration).
  - **Timeline**: Upcoming sessions for the student's specific group.

## 📄 `Student\JustificationController`
- **Role**: Submission of absence excuses.
- **Key Features**:
  - Form to upload justification details (reason, date range).
  - View history of submitted justifications and their status (Pending, Approved, Rejected).
