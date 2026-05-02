# Technical Specifications & Requirements

This document outlines the functional and technical requirements derived from the initial analysis.

## 🛠️ Functional Requirements by Role

### 1. Teacher (Mobile-First)
- **Flash Attendance**: Rapid marking of presence/absence per session.
- **Session History**: View and edit recent attendance logs.
- **Conflict Management**: Prevent double-scheduling or overlapping sessions.

### 2. Administrator (Back-Office)
- **Global Monitoring**: Real-time dashboard of all active sessions.
- **Validation Hub**: Tool to review and finalize teacher-submitted data.
- **Justification Management**: Review, approve, or reject student documents.
- **Dynamic Reporting**: Export attendance trends to PDF/Excel.

### 3. Student (Self-Service)
- **Assiduité Dashboard**: Personal attendance rate visualization.
- **Digital Justification**: Uploading medical or administrative excuses.
- **Alert System**: Notifications when approaching attendance thresholds.

---

## 🏗️ System Architecture

### Modular Service Design
The system is logically partitioned into specialized services:
1. **IAM (Identity & Access Management)**: Powered by Laravel & Spatie Permission.
2. **Academic Service**: Manages Filieres, Groups, Modules, and Sessions.
3. **Attendance Service**: Manages the core logic of pointage and justifications.

### Dynamic Sessions
Unlike static systems, AttendanceFlow-AMS uses **Configurable Sessions**:
- `start_time` & `end_time` are flexible.
- `duration_hours` is calculated dynamically.
- `type` (CM, TD, TP) allows for granular reporting per course style.

---

## 💻 Tech Stack Choice
- **Backend**: Laravel 12 (Service Pattern, Eloquent ORM).
- **Frontend**: Tailwind CSS & Alpine.js (UX focused on speed).
- **Security**: Spatie Permissions for robust role-based access control.
- **Environment**: PHP 8.1+, MySQL.
