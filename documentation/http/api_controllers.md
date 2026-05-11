# API Controllers Documentation (Stateless)

The API controllers provide JSON endpoints for external integrations or mobile applications using Laravel Sanctum for token-based authentication.

## 📄 `Api\AuthController`
- **Role**: Stateless identity management.
- **Key Features**:
  - `login()`: Authenticates users and generates a Sanctum `plainTextToken`.
  - `logout()`: Revokes the current access token.
  - `me()`: Returns the authenticated user profile and roles.

## 📄 `Api\AcademicController`
- **Role**: Fetching structural data.
- **Key Features**: Endpoints to list Groups, Modules, and Filieres.

## 📄 `Api\AttendanceController`
- **Role**: Mobile attendance marking.
- **Key Features**: Allows teachers to mark attendance via JSON payload, enabling "offline-first" mobile sync capabilities.

## 📄 `Api\JustificationController`
- **Role**: Justification management via API.
- **Key Features**: Submission and status tracking.

## 📄 `Api\StatsController`
- **Role**: Analytical endpoints.
- **Key Features**: Provides data for mobile dashboards (Attendance rates, trends).
