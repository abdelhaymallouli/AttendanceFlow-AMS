/**
 * Teacher Dashboard App (Teacher)
 * ================================
 * Alpine.js data component for the teacher dashboard page.
 * Handles current session detection, stats calculation, and recent activity.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('teacherDashboard', () => ({
        // These will be populated by the Blade template via @json directives
        sessions: [],
        currentSession: null,
        totalStudents: 0,
        avgAttendance: 0,
        pendingCount: 0,
        teacherGroups: [],
        
        init() {
            this.determineStatuses();
            setTimeout(() => {
                if (typeof window.initIcons === 'function') {
                    window.initIcons();
                }
            }, 50);
        },
        
        determineStatuses() {
            const now = new Date();
            const currentMinutes = now.getHours() * 60 + now.getMinutes();

            this.sessions.forEach(s => {
                const [startH, startM] = s.start_time.split(':').map(Number);
                const [endH, endM] = s.end_time.split(':').map(Number);
                const startMinutes = startH * 60 + startM;
                const endMinutes = endH * 60 + endM;

                if (currentMinutes >= endMinutes) {
                    s.status = 'completed';
                } else if (currentMinutes >= startMinutes) {
                    s.status = 'active';
                } else {
                    s.status = 'upcoming';
                }
            });

            this.currentSession = this.sessions.find(s => s.status === 'active') || null;
        },
        
        getTypeBgClass(type) {
            const classes = { 'lecture': 'bg-purple-100', 'td': 'bg-blue-100', 'tp': 'bg-green-100' };
            return classes[type] || 'bg-gray-100';
        },
        
        getTypeTextClass(type) {
            const classes = { 'lecture': 'text-purple-600', 'td': 'text-blue-600', 'tp': 'text-green-600' };
            return classes[type] || 'text-gray-600';
        }
    }));
});