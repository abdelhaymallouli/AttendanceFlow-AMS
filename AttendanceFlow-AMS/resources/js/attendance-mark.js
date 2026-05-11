/**
 * Attendance Mark App (Teacher)
 * ==============================
 * Alpine.js data component for the teacher attendance marking page.
 * Handles student search, bulk actions, and real-time statistics.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('attendanceMark', (studentsCount = 0) => ({
        searchQuery: '',
        stats: { present: 0, absent: 0, late: 0, unmarked: studentsCount },
        
        init() {
            this.updateStats();
            setTimeout(() => {
                if (typeof window.initIcons === 'function') {
                    window.initIcons();
                }
            }, 50);
        },
        
        updateStats() {
            const statuses = Array.from(document.querySelectorAll('input[type=radio]:checked')).map(r => r.value);
            this.stats.present = statuses.filter(s => s === 'present').length;
            this.stats.absent = statuses.filter(s => s === 'absent').length;
            this.stats.late = statuses.filter(s => s === 'late').length;
            this.stats.unmarked = this.stats.unmarked - statuses.length;
        },
        
        markAllPresent() {
            document.querySelectorAll('input[type=radio][value=present]').forEach(el => el.checked = true);
            this.updateStats();
        },
        
        clearAll() {
            document.querySelectorAll('input[type=radio]').forEach(el => el.checked = false);
            this.updateStats();
        },
        
        filterRows() {
            const query = this.searchQuery.toLowerCase();
            document.querySelectorAll('.student-row').forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        }
    }));
});