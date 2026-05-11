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
        selectedDate: initialDate || new Date().toISOString().split('T')[0],
        availableSessions: initialSessions,

        onDateChange() {
            window.location.href = `?date=${this.selectedDate}`;
        }
    }));
});
