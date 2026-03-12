# Database Testing & Seeding Guide

This guide explains how to initialize the AttendanceFlow-AMS database and populate it with test data using the CSV-based seeding system.

## Prerequisites
- PHP 8.2+
- Composer
- MySQL Server
- Laravel 12.x

## Database Initialization

1. **Configure Environment**: Ensure your `.env` file has the correct database credentials.
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=attendanceflow_ams
   DB_USERNAME=root
   DB_PASSWORD=your_password
   ```

2. **Create Database**: Ensure the database exists in MySQL.
   ```sql
   CREATE DATABASE IF NOT EXISTS attendanceflow_ams;
   ```

3. **Run Migrations**: Create the table structure.
   ```bash
   php artisan migrate:fresh
   ```

## Seeding Test Data

We use a custom CSV seeder to populate the database with realistic data.

### 1. CSV Data Location
All test data is stored in: `database/data/`.
- `filieres.csv`: Departments.
- `groups.csv`: Student groups.
- `modules.csv`: Academic modules.
- `users.csv`: System users.
- `student_profiles.csv`: Student-specific data.
- `teacher_profiles.csv`: Teacher-specific data.
- `sessions.csv`: Class sessions.
- `attendance_records.csv`: Student attendance.
- `justifications.csv`: Absence justifications.

### 2. Execute Seeding
Run the following command to populate all tables:
```bash
php artisan db:seed --class=CsvSeeder
```

## Verification
You can verify the data using Tinker:
```bash
php artisan tinker --execute="echo 'Students: ' . \App\Models\StudentProfile::count();"
```

## Troubleshooting
- **Constraint Errors**: Ensure you run `migrate:fresh` before seeding to avoid duplicate ID conflicts.
- **CSV Format**: Ensure CSV headers match the `CsvSeeder` parsing logic exactly.
