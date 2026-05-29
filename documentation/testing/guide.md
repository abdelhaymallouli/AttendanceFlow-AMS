# Testing Documentation: AttendanceFlow-AMS

This documentation outlines the testing strategy implemented to ensure the reliability and quality of the AttendanceFlow-AMS application.

## 🚀 Global Strategy (TDD)

The project follows a **Test-Driven Development (TDD)** approach. Every business feature is accompanied by a suite of automated tests covering both successful scenarios ("Happy Path") and failure cases ("Negative Testing").

---

## 📂 Test Structure

Tests are divided into two main categories in accordance with Laravel standards:

### 1. Unit Tests (`tests/Unit`)
- **Objective**: Test functions or methods in complete isolation (no database access).
- **Performance**: Near-instant execution.
- **Example**: `ReportingServiceUnitTest` verifies the presence rate calculation logic using mocked collections instead of real database records.

### 2. Feature Tests (`tests/Feature`)
- **Objective**: Test real-world business scenarios involving the database and multiple components.
- **Organization**: Service tests are grouped in `tests/Feature/Services/`.
- **Services Covered**:
  - `AcademicServiceTest`: Enrolment, profiles, and academic hierarchy.
  - `AttendanceServiceTest`: Attendance marking, bulk operations, and idempotency.
  - `IdentityServiceTest`: Authentication, roles, and session management.
  - `JustificationServiceTest`: Absence management and automatic status updates.
  - `ReportingServiceTest`: Report generation based on real data ranges.
  - `SchedulingServiceTest`: Planning and teacher conflict detection.

---

## 🛠️ Tools and Techniques

### 🧬 Model Factories
We use Laravel Factories to generate realistic test data. This keeps tests isolated and prevents them from breaking when the underlying data structure changes.

### 🧪 Negative Testing (Failure Cases)
The suite doesn't just test success; it also ensures the system fails correctly when it should:
- **Duplicates**: Attempting to enroll a student with an existing matricule.
- **Conflicts**: Attempting to schedule a session when a teacher is already busy.
- **Security**: Attempting to authenticate with an incorrect password.
- **Missing Data**: Ensuring the system handles non-existent records gracefully.

---

## 💻 How to Run Tests

To run the entire test suite, use the following command in the project root:

```bash
php artisan test
```

To run only Unit tests:
```bash
php artisan test --testsuite=Unit
```

To run a specific test file:
```bash
php artisan test tests/Feature/Services/AttendanceServiceTest.php
```

---

## 📐 Quality Standards
- **Clean Code**: Minimized redundancy using `setUp()` methods.
- **Independence**: Each test uses the `RefreshDatabase` trait to ensure a clean environment for every run.
- **Professional Assertions**: Use of strict type assertions and state verification (`assertDatabaseHas`).
