/**
 * Session Form App (Shared)
 * =========================
 * Alpine.js data component for session creation forms.
 * Handles duration calculation based on start/end time inputs.
 * Used by both teacher and admin session creation forms.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('sessionForm', (startTime = '09:00', endTime = '11:00') => ({
        startTime: startTime,
        endTime: endTime,
        
        get durationText() {
            if (!this.startTime || !this.endTime) return '--';
            
            const start = this.startTime.split(':');
            const end = this.endTime.split(':');
            
            const startMins = parseInt(start[0]) * 60 + parseInt(start[1]);
            const endMins = parseInt(end[0]) * 60 + parseInt(end[1]);
            
            const diff = endMins - startMins;
            
            if (diff <= 0) return 'Invalid Time';
            
            const hours = Math.floor(diff / 60);
            const mins = diff % 60;
            
            if (mins === 0) return `${hours} Hour${hours > 1 ? 's' : ''}`;
            return `${hours}h ${mins}m`;
        }
    }));
});