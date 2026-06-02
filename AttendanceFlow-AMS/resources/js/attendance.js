/**
 * Attendance Session Selector (Admin)
 * =====================================
 * Alpine.js data component for the admin attendance index page.
 * Filters sessions by selected date without any page reload.
 *
 * Usage:
 *   <div x-data="attendanceApp(initialSessions)">
 *
 * Expects: initialSessions = array of session objects with { start_time, url, ... }
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceApp', (initialSessions = [], initialDate = '') => ({
        selectedDate: initialDate,
        availableSessions: initialSessions,

        onDateChange(value) {
            window.location.href = `?date=${value}`;
        }
    }));
});