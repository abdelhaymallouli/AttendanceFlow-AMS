/**
 * Teacher Attendance Session Selector
 * ===================================
 * Alpine.js data component for the teacher attendance index page.
 * Filters sessions by selected date without any page reload.
 *
 * Usage:
 *   <div x-data="teacherAttendanceApp(initialSessions)">
 *
 * Expects: initialSessions = array of session objects with { start_time, url, ... }
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('teacherAttendanceApp', (initialSessions = [], initialDate = '') => ({
        selectedDate: initialDate || new Date().toISOString().split('T')[0],
        availableSessions: initialSessions,

        onDateChange(selectedDate) {
            this.selectedDate = selectedDate;
            window.location.href = `?date=${this.selectedDate}`;
        }
    }));
});