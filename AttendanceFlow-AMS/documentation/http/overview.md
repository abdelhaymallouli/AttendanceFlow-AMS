# HTTP Layer Architecture: AttendanceFlow-AMS

This document describes the design and organization of the HTTP layer, specifically focusing on how Controllers handle incoming requests and bridge them to the business logic.

## 🏗️ Design Pattern: Role-Based Controller Isolation

The application uses a **Role-Based Namespace** strategy. Instead of grouping by "Resource" (e.g., `AttendanceController` handling everyone), we group by "Persona" (Admin, Teacher, Student).

### Benefits:
- **Security**: Simplified middleware application (e.g., all controllers in `Admin/` can be protected by a single `role:admin` middleware).
- **Simplicity**: Each controller only handles the specific needs of that user type, avoiding complex "if/else" logic based on roles.
- **Maintainability**: Clear separation of concerns between what an Admin sees vs. what a Student sees.

---

## 📂 Namespace Overview

| Namespace | Responsibility | Target Audience |
| :--- | :--- | :--- |
| `App\Http\Controllers\Auth` | Handles login and authentication sessions. | All users (guest state). |
| `App\Http\Controllers\Admin` | Full system management, complex reporting, and administrative overrides. | Administrators. |
| `App\Http\Controllers\Teacher`| Attendance marking and pedagogical dashboard. | Teachers / Instructors. |
| `App\Http\Controllers\Student`| Viewing attendance records and submitting justifications. | Students. |
| `App\Http\Controllers\Api` | Stateless JSON endpoints for mobile or external integrations. | Mobile App / AJAX. |

---

## 📐 Architecture Principles

### 1. Request Validation
Every controller uses Laravel's `Request` validation or Form Requests to ensure data integrity before any processing.

### 2. Thin Controllers vs. Fat Services (Current State)
*   **Observation**: Currently, some controllers contain direct Eloquent queries and business logic (e.g., calculating attendance rates).
*   **Target**: Controllers should act as "traffic controllers"—receiving input, calling a Service, and returning a View or JSON. 
*   **Action Plan**: Transition logic to `App\Services` to improve testability.

### 3. Route Model Binding
The project uses **Implicit Route Model Binding** (e.g., `public function show(Session $session)`) to automatically fetch models from IDs in the URL, reducing boilerplate code.

---

## 🔗 Documentation Index

- [📂 Admin Controllers](admin_controllers.md)
- [📂 Teacher Controllers](teacher_controllers.md)
- [📂 Student Controllers](student_controllers.md)
- [📂 API Controllers](api_controllers.md)
- [📂 Auth Controllers](auth_controllers.md)
