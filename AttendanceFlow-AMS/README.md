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

## Testing

To verify the core services of the application, run the following command:

```bash
php artisan test tests/Feature --filter ServiceTest
```

### Latest Test Results
- **Status:** PASS
- **Tests:** 26 passed
- **Assertions:** 72 assertions
- **Duration:** 6.21s

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
