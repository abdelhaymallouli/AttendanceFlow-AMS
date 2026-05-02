# AttendanceFlow-AMS

AttendanceFlow-AMS is a comprehensive Attendance Management System (AMS) built with Laravel designed to streamline scheduling, attendance tracking, and reporting.

## Features

- **Academic Management:** Handle courses, levels, and academic sessions.
- **Attendance Tracking:** Real-time attendance monitoring for students and staff.
- **Identity & Security:** Secure identity management and access control.
- **Justification System:** Manage and review justifications for absences.
- **Reporting:** Generate detailed reports on attendance trends and statistics.
- **Scheduling:** Automated and manual scheduling of academic activities.

## Getting Started

### Prerequisites

- PHP >= 8.1
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/abdelhaymallouli/AttendanceFlow-AMS.git
   ```
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Configure your environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations:
   ```bash
   php artisan migrate
   ```

## 📚 Documentation

Detailed documentation for the various system components can be found in the `documentation` directory:

- [📂 Project Vision & Analysis](documentation/analysis/vision_and_problem.md)
- [📂 Technical Specs & Roadmap](documentation/analysis/technical_specs.md)
- [📂 Database Schema & Seeders](documentation/database/)

- [📂 Model Factories Guide](documentation/database/factories.md)
- [📂 HTTP & Controllers Architecture](documentation/http/overview.md)
- [📂 View Layer & Dashboards](documentation/views/admin.md)
- [📂 Git Workflow & Milestones](documentation/git/workflow.md)
- [📂 Services Architecture](documentation/services/architecture.md)



- [📂 Testing Guide & TDD Strategy](documentation/testing/guide.md)

## 🧪 Testing

The project follows a TDD approach. To run the full suite:

```bash
php artisan test
```

### Latest Test Results
- **Status:** PASS ✅
- **Tests:** 31 passed
- **Assertions:** 81 assertions
- **Coverage:** Service Layer (100%)


## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
