# Solicode AMS - Attendance Management System

A comprehensive, mobile-responsive Attendance Management System built with HTML, Preline UI, Tailwind CSS, Alpine.js, and Lucide Icons.

## Overview

Solicode AMS (Attendance Management System) is designed to revolutionize school attendance management by eliminating the archaic "Paper-to-Excel" workflow. The system provides a "Direct-to-System" flux where data is captured at the source (the classroom) and instantly actionable for administration.

## Features

### Core Features
- **Session-Based Attendance**: Three daily time slots (9:00-11:00, 11:00-14:00, 14:00-17:00)
- **Unified Attendance Entry**: Same interface for both Admin and Teacher
- **Real-time Dashboard**: Live attendance statistics and monitoring
- **Digital Justifications**: Students can upload medical certificates and supporting documents
- **Approval Workflow**: Teachers and administrators can review and approve justifications
- **Analytics & Reports**: Comprehensive attendance analytics with charts and trends
- **Mobile Responsive**: Fully functional on desktop, tablet, and mobile devices
- **Dark Mode Support**: Built-in dark mode for comfortable viewing

### User Roles

#### 1. Administrator
- Full system access
- School-wide attendance overview
- Student management
- Calendar view with attendance data
- Reports and analytics
- Justification approval workflow

#### 2. Teacher
- Session-based attendance marking
- Class-specific student views
- Justification review for their classes
- Quick attendance entry (< 30 seconds per session)
- Class performance reports

#### 3. Student
- Personal attendance dashboard
- Real-time attendance tracking
- Digital justification submission
- Attendance calendar view
- Document upload (PDF, JPG, PNG)

## File Structure

```
maquete/
├── index.html                    # Auto-redirect to login
├── login.html                    # Login page with role selection
├── attendance-entry.html         # Unified attendance entry (Admin & Teacher)
├── admin-dashboard.html          # Admin main dashboard
├── admin-reports.html            # Analytics and reports
├── admin-students.html           # Student management
├── admin-calendar.html           # Calendar view with attendance
├── admin-justifications.html     # Justification approval workflow
├── teacher-dashboard.html        # Teacher main dashboard
├── teacher-justifications.html   # Teacher justification review
├── student-dashboard.html        # Student main dashboard
├── student-justifications.html   # Student justification submission
└── README.md                     # This file
```

## Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| **Administrator** | admin@school.edu | admin123 |
| **Teacher** | teacher@school.edu | teacher123 |
| **Student** | student@school.edu | student123 |

## Technologies Used

- **HTML5** - Semantic markup
- **Preline UI** - Modern UI component library
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework for reactivity
- **Lucide Icons** - Beautiful, consistent icon set
- **Chart.js** - Interactive charts and visualizations

## Session Structure

The system supports three daily sessions:

1. **Morning Session**: 9:00 AM - 11:00 AM
2. **Midday Session**: 11:00 AM - 14:00 PM
3. **Afternoon Session**: 14:00 PM - 17:00 PM

## Unified Attendance Entry

Both **Admin** and **Teacher** use the same `attendance-entry.html` page:
- Admin sees all classes
- Teacher sees only their assigned classes
- Same session selector (Morning/Midday/Afternoon)
- Same status buttons (Present/Absent/Late)
- Same quick actions (All Present / Reset)

## Attendance Status

- **Present (P)** - Green indicator
- **Absent (A)** - Red indicator
- **Late (L)** - Amber/Yellow indicator

## Justification Types

- **Medical** - Doctor's notes, hospital certificates
- **Family** - Family emergencies, parent letters
- **Appointment** - Official appointments
- **Other** - Other valid reasons

## Mobile Responsiveness

The system is fully responsive with:
- Collapsible sidebar navigation on mobile
- Touch-friendly buttons (min 44px)
- Optimized layouts for small screens
- Mobile-specific navigation menus
- Slide-out mobile sidebars

## Dark Mode

All pages support dark mode with:
- Automatic dark theme detection
- Consistent color scheme
- Proper contrast ratios
- Smooth transitions

## Browser Compatibility

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## Getting Started

1. Open `login.html` in your web browser
2. Select your role (Admin, Teacher, or Student)
3. Enter the demo credentials
4. Explore the system features

## Key Design Principles

1. **Direct-to-System**: No intermediate paper steps
2. **Flash Pointing**: Quick attendance entry (< 30 seconds)
3. **Real-time Sync**: Instant dashboard updates
4. **Transparency**: Students can view their attendance anytime
5. **Mobile-First**: Designed for on-the-go usage
6. **Unified Interface**: Same attendance entry for Admin & Teacher

## Future Enhancements

- [ ] Email notifications for parents
- [ ] SMS alerts for critical absences
- [ ] Integration with school information systems
- [ ] Biometric attendance integration
- [ ] Advanced analytics with AI predictions
- [ ] Multi-language support

## License

This project is for demonstration purposes.

---

Built with ❤️ for Solicode
