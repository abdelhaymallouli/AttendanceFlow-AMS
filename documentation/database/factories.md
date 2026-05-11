# Database Factories: AttendanceFlow-AMS

This document describes how Laravel Factories are used within the project to manage mock data generation, automated testing, and database seeding.

## 🏗️ What are Factories?

Factories are blueprints for your database models. They allow you to generate large amounts of "fake" but realistic data using the [Faker](https://github.com/fzaninotto/Faker) library. In this project, they are essential for our **TDD (Test-Driven Development)** workflow.

---

## 🛠️ Usage in the Project

### 1. Automated Testing (Primary)
Our **Feature Tests** rely entirely on factories. Instead of manually inserting rows into the database, we use:
```php
$student = StudentProfile::factory()->create();
```
This ensures each test starts with a clean, predictable set of data.

### 2. Recursive Relationships
Many of our factories are linked. When you create a **Session**, the factory automatically creates the necessary dependencies:
- A **Module**
- A **Teacher Profile**
- A **Group**
This prevents "Foreign Key" constraint errors and simplifies test setup.

---

## 📂 Detailed Factory Roles

| Factory | Responsibility | Key Attributes |
| :--- | :--- | :--- |
| **UserFactory** | Creates core system accounts. | Name, Email, Password, Remember Token. |
| **StudentProfileFactory** | Handles student-specific data. | Link to User, Group ID, Matricule. |
| **TeacherProfileFactory** | Handles teacher-specific data. | Link to User, Specialty. |
| **FiliereFactory** | Defines academic departments. | Name (e.g., "Informatique"), Code. |
| **GroupFactory** | Defines student classes. | Name (e.g., "DEV101"), Link to Filiere. |
| **ModuleFactory** | Defines subjects/modules. | Name, Coefficient, Hours. |
| **SessionFactory** | Links teaching activities in time. | Start Time, End Time, Type (CM/TD/TP). |
| **JustificationFactory** | Simulates absence justifications. | Reason, Start/End Date, Status (pending/accepted). |
| **AttendanceRecordFactory**| Tracks presence history. | Status (present/absent/late), Date. |

---

## 💡 Best Practices & States

1.  **Active State**: Use `Session::factory()->active()->create()` to quickly generate a session that is happening right now. This is perfect for testing real-time attendance features.
2.  **State Management**: Use factory states to define specific scenarios (e.g., `User::factory()->admin()->create()`).
3.  **Idempotency**: Tests use the `RefreshDatabase` trait, so factories always start from an empty database.

3.  **Data Integrity**: Factories are configured to respect the relationships defined in the Class Diagram.

---

## 🚀 How to use them for development

If you want to fill your local database with random data for UI testing:
```bash
php artisan tinker
# Inside tinker:
App\Models\User::factory()->count(50)->create();
```
