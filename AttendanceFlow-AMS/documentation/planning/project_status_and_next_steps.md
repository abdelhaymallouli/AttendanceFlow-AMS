# Project Status, Gap Analysis & Next Steps

This document provides a comprehensive audit of the current state of the AttendanceFlow-AMS project, detailing the gaps between the original design requirements (Cahier des Charges, Use Cases) and the current implementation, along with a concrete GitHub action plan.

---

## 📍 1. Current State (What is Working)

The foundational architecture is robust and well-implemented:
- **Core Infrastructure**: Laravel 12 setup with MySQL database schema, migrations, and model factories.
- **Service Layer Pattern**: Implementation of `AcademicService`, `AttendanceService`, `IdentityService`, `JustificationService`, `ReportingService`, and `SchedulingService`.
- **TDD Compliance**: Comprehensive test suite (31 tests passing, 81 assertions) covering core service logic with both positive and negative cases.
- **Frontend Architecture**: Role-based layouts (Admin, Teacher, Student) using Tailwind CSS and Alpine.js, integrated via Vite.
- **API Foundation**: Initial stateless endpoints in `Api\` controllers with Sanctum authentication.

---

## 🔍 2. Gap Analysis (Missing Features & Discrepancies)

Comparing the implemented code against the original `Analyse` folder (Cahier des Charges, Ideation, Use Cases), the following critical features are **missing or incomplete**:

### A. Administrator Features
- ❌ **PDF/Excel Exporting**: The `ReportController` aggregates data for the view, but the functional requirement to *export dynamic reports* (PDF/Excel) as stated in the Cahier des Charges is missing.
- ❌ **Bulk Student Import**: Admin currently lacks a dedicated UI/Controller logic to import students via CSV/Excel, which is essential for initial setup.
- ❌ **Automated Reminders**: The system lacks a scheduled task (cron/job) to automatically notify teachers who forgot to mark attendance for a past session.

### B. Teacher Features
- ❌ **Detailed Delay Motifs**: The `AttendanceService` handles 'present', 'absent', and 'late' statuses, but the UI/Service currently lacks the ability for the teacher to input *real-time specific reasons* for a delay (as defined in the Mobile-First specs).
- ❌ **Conflict Warning UI**: While `SchedulingService` throws exceptions for conflicts, the frontend UI does not gracefully handle or proactively warn about these conflicts during manual session creation.

### C. Student Features
- ❌ **Automated Quota Alerts**: The system needs a background job or event listener to send email/dashboard notifications to students when their attendance rate drops below critical thresholds (e.g., < 90%).

---

## 🏗️ 3. Technical Debt & Architectural Issues (SOLID)

While the project is well-structured, several areas need refactoring to adhere strictly to Clean Architecture and SOLID principles:

### A. "Fat" Controllers (Violation of Single Responsibility)
- **Issue**: Controllers like `Admin\ReportController` and `Teacher\AttendanceController` contain direct Eloquent queries, complex aggregation logic, and business rules.
- **Solution**: Move all SQL queries and business logic to `ReportingService` and `AttendanceService`. Controllers should only validate requests, call the service, and return a view/JSON.

### B. Missing Data Transfer Objects (DTOs)
- **Issue**: Services currently accept standard PHP associative arrays (e.g., in `$request->attendance`). This lacks strong typing and makes the code fragile.
- **Solution**: Implement DTO classes (e.g., `StoreAttendanceDTO`) to strongly type data flowing from Controllers to Services.

### C. Missing Interface Abstraction (Violation of Dependency Inversion)
- **Issue**: Services are currently concrete classes. Controllers and Tests depend on these concrete implementations.
- **Solution**: Create interfaces (e.g., `AttendanceServiceInterface`) and bind them to their concrete classes in a ServiceProvider. This enables easier mocking and future swapping of implementations.

### D. Validation Logic in Controllers
- **Issue**: `Teacher\AttendanceController` handles `$request->validate()` directly within the `store` method.
- **Solution**: Extract validation into dedicated Laravel Form Requests (e.g., `StoreAttendanceRequest`).

---

## 📋 4. GitHub Action Plan & Planning Strategy

To manage this remaining workload systematically, the following Issues and Branches should be created in the GitHub repository.

### Milestone 1: Technical Cleanup (Refactoring)
Before adding new features, stabilize the existing architecture.

| Issue Title | Description | Suggested Branch |
| :--- | :--- | :--- |
| **[Refactor] Slim down ReportController & AttendanceController** | Move Eloquent queries and stat calculations into respective Services. | `refactor/clean-controllers` |
| **[Refactor] Extract Form Requests** | Move validation logic from controllers into dedicated Request classes. | `refactor/form-requests` |
| **[Tech] Implement Service Interfaces & DTOs** | Abstract concrete services behind interfaces and use DTOs for data passing. | `tech/solid-abstractions` |

### Milestone 2: Missing Core Features
Implement the remaining functional requirements.

| Issue Title | Description | Suggested Branch |
| :--- | :--- | :--- |
| **[Feature] Admin PDF/Excel Reporting Export** | Implement Maatwebsite/Laravel-Excel or DomPDF to export attendance data. | `feat/report-exports` |
| **[Feature] Student Quota Alerts System** | Create Laravel Jobs/Notifications to alert students dropping below 90% attendance. | `feat/student-alerts` |
| **[Feature] Detailed Delay Motifs for Teachers** | Update the attendance UI and database schema to accept textual reasons for lateness. | `feat/delay-motifs` |
| **[Feature] Bulk CSV Student Import** | Create an Admin interface and service method to bulk create student profiles. | `feat/csv-imports` |

---

## 🔄 5. Recommended Immediate Next Steps

1. **Commit and Push**: Commit this documentation and recent test/factory updates to the current active branch (`feat/Admin-Side`).
2. **Merge**: Create a Pull Request and merge `feat/Admin-Side` into `develop`.
3. **Branch Out**: Create a new branch from `develop` for the first refactoring task (e.g., `refactor/clean-controllers`) to begin addressing the technical debt before tackling the PDF export feature.
