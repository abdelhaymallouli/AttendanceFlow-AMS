/**
 * Justifications App (Admin)
 * ============================
 * Alpine.js data component for the admin justifications page.
 * Handles filtering by status and search query.
 *
 * Usage:
 *   <div x-data="justificationsApp(serverJustifications)">
 *
 * Expects: serverData = array of justification objects from the server
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('justificationsApp', (serverData = []) => ({
        searchQuery: '',
        filterStatus: 'pending',
        justifications: serverData,
        filteredJustifications: [],

        init() {
            this.filterJustifications();
        },

        get pendingCount() {
            return this.justifications.filter(j => j.status === 'pending').length;
        },

        get approvedCount() {
            return this.justifications.filter(j => j.status === 'approved').length;
        },

        get rejectedCount() {
            return this.justifications.filter(j => j.status === 'rejected').length;
        },

        filterJustifications() {
            let filtered = this.justifications;

            if (this.filterStatus !== 'all') {
                filtered = filtered.filter(j => j.status === this.filterStatus);
            }

            if (this.searchQuery.trim() !== '') {
                const query = this.searchQuery.toLowerCase();
                filtered = filtered.filter(j =>
                    j.studentName.toLowerCase().includes(query) ||
                    j.studentId.toLowerCase().includes(query)
                );
            }

            this.filteredJustifications = filtered;

            // Re-initialize Lucide icons after Alpine updates the DOM
            this.$nextTick(() => {
                if (typeof window.initIcons === 'function') {
                    window.initIcons();
                }
            });
        }
    }));
});
