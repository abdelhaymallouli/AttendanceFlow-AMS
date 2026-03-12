# Service Architecture Design - AttendanceFlow-AMS

## 1. System Analysis
AttendanceFlow-AMS is a web and mobile attendance management system for training institutions. The system supports three primary actors: Administrators, Trainers, and Students. 

The core capabilities span:
- Identity and access
- Academic structure and enrollment
- Scheduling
- Attendance tracking and justification
- Reporting and notifications

To ensure scalability, maintainability, and clean separation of concerns, the system is designed using Domain-Driven Design (DDD) principles and a modular service-oriented architecture within Laravel.

---

## 2. Base (Core) Service: `AmsKernel`

The `AmsKernel` acts as the foundational layer that supports all other domain-specific services. It does not hold business logic but provides the essential infrastructure for the services to operate and communicate.

*   **Responsibilities**:
    *   **Shared Utilities**: Common helpers for date/time formatting, string manipulation, and calculations.
    *   **Shared Interfaces/Contracts**: Base interfaces for Repositories, Services, and Actions to enforce consistency.
    *   **Shared DTOs (Data Transfer Objects)**: Base classes for strongly-typed data passing between layers.
    *   **Global Event Bus**: Centralized event dispatching mechanisms.
    *   **Logging & Telemetry**: Standardized logging, error tracking, and performance monitoring.

---

## 3. Antigravity Services (Service-Oriented Design)

The system is logically partitioned into the following independent services, each owning its specific domain and data.

### **IdentityService**
*   **Purpose**: Manages all aspects of authentication, user identity, and authorization.
*   **Main Entities**: `User`, `Role`, `Permission`.
*   **Responsibilities**: Registration, Login, Token generation, Password management, Role-Based Access Control (RBAC).
*   **Example APIs**: `authenticate()`, `assignRole()`, `verifyAccess()`.

### **AcademicService**
*   **Purpose**: Owns the structural academic hierarchy and user profiles.
*   **Main Entities**: `Filiere`, `Group`, `Module`, `StudentProfile`, `TeacherProfile`.
*   **Responsibilities**: Structuring departments and groups, enrolling students, assigning teachers to modules/groups.
*   **Example APIs**: `enrollStudent()`, `assignTeacher()`, `getFiliereHierarchy()`.

### **SchedulingService**
*   **Purpose**: Manages the temporal planning of training activities.
*   **Main Entities**: `Session`.
*   **Responsibilities**: Scheduling sessions, timetable generation, conflict resolution (preventing double-booking).
*   **Example APIs**: `scheduleSession()`, `getTeacherTimetable()`, `checkConflicts()`.

### **AttendanceService**
*   **Purpose**: Records and analyzes student presence during sessions.
*   **Main Entities**: `AttendanceRecord`.
*   **Responsibilities**: Processing attendance marking (manual, bulk, mobile QR), tracking late arrivals, calculating attendance rates.
*   **Example APIs**: `markAttendance()`, `getSessionAttendance()`, `calculateStudentRate()`.

### **JustificationService**
*   **Purpose**: Manages the lifecycle and workflow of absence justifications.
*   **Main Entities**: `Justification`.
*   **Responsibilities**: Document upload processing, linking justifications to specific date ranges, managing the approval workflow (Pending -> Accepted/Rejected).
*   **Example APIs**: `submitJustification()`, `reviewJustification()`, `getPendingJustifications()`.

### **ReportingService**
*   **Purpose**: Aggregates data from various services to generate analytical insights and documents.
*   **Main Entities**: None (Aggregator mapping to specific report read-models).
*   **Responsibilities**: Generating attendance statistics, exporting PDF/Excel reports, generating monthly trainer/student summaries.
*   **Example APIs**: `generateMonthlyReport()`, `exportGroupStats()`.

### **NotificationService**
*   **Purpose**: Handles all multi-channel outbound communication.
*   **Main Entities**: `Notification`.
*   **Responsibilities**: Dispatching push notifications to the mobile app, sending emails (e.g., absence alerts), processing system alerts.
*   **Example APIs**: `sendAbsenceAlert()`, `pushSessionReminder()`.

---

## 4. Communication Strategy

To maintain loose coupling, the services communicate using two primary paradigms:

### **Synchronous (APIs / Contracts)**
When a service requires immediate data to complete an operation, it uses defined internal interfaces (Contracts) resolved via the Laravel Service Container. 
*   *Example*: `AttendanceService` synchronously calls an `AcademicService` contract to verify a student belongs to the session's group before marking attendance.

### **Asynchronous (Domain Events)**
When a state change in one service affects another, it emits a Domain Event. Other services listen to these events to react independently.
*   *Example*: When `JustificationService` accepts a medical certificate, it emits a `JustificationAccepted` event. The `AttendanceService` listens to this event and asynchronously updates the relevant `AttendanceRecord` statuses to 'justified'.

---

## 5. Service Architecture Overview

The complete modular architecture consists of the foundational kernel and seven independent domain services:

1.  **AmsKernel** (Base Infrastructure)
2.  **IdentityService** (Auth & IAM)
3.  **AcademicService** (Structure & Profiles)
4.  **SchedulingService** (Timetables)
5.  **AttendanceService** (Presence Tracking)
6.  **JustificationService** (Absence Workflows)
7.  **ReportingService** (Analytics & Exports)
8.  **NotificationService** (Alerts & Comms)
