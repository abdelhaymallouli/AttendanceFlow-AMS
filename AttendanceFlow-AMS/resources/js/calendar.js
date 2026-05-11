/**
 * Calendar App (Admin)
 * =====================
 * Alpine.js component for the admin calendar view.
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('calendarApp', (serverSessions = []) => ({
        currentDate: new Date(),
        selectedDay: null,
        calendarDays: [],
        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],

        init() {
            this.generateCalendar();
        },

        get monthYear() {
            return `${this.monthNames[this.currentDate.getMonth()]} ${this.currentDate.getFullYear()}`;
        },

        generateCalendar() {
            const year = this.currentDate.getFullYear();
            const month = this.currentDate.getMonth();

            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const daysInMonth = lastDay.getDate();
            const startingDay = firstDay.getDay();

            const today = new Date();
            today.setHours(0,0,0,0);

            this.calendarDays = [];

            // Previous month days
            const prevMonth = new Date(year, month, 0);
            for (let i = startingDay - 1; i >= 0; i--) {
                const d = new Date(year, month - 1, prevMonth.getDate() - i);
                this.calendarDays.push({
                    date: this.formatDate(d),
                    dayNumber: d.getDate(),
                    isCurrentMonth: false,
                    isToday: false,
                    sessions: []
                });
            }

            // Current month days
            for (let i = 1; i <= daysInMonth; i++) {
                const d = new Date(year, month, i);
                const dateString = this.formatDate(d);
                const isToday = d.getTime() === today.getTime();

                const daySessions = serverSessions.filter(s => s.start_time.startsWith(dateString));

                this.calendarDays.push({
                    date: dateString,
                    dayNumber: i,
                    isCurrentMonth: true,
                    isToday: isToday,
                    fullDate: d.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }),
                    sessions: daySessions
                });
            }

            // Next month days
            const remainingDays = 42 - this.calendarDays.length;
            for (let i = 1; i <= remainingDays; i++) {
                const d = new Date(year, month + 1, i);
                this.calendarDays.push({
                    date: this.formatDate(d),
                    dayNumber: i,
                    isCurrentMonth: false,
                    isToday: false,
                    sessions: []
                });
            }

            this.$nextTick(() => {
                if (typeof window.initIcons === 'function') {
                    window.initIcons();
                }
            });
        },

        previousMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
            this.generateCalendar();
        },

        nextMonth() {
            this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
            this.generateCalendar();
        },

        today() {
            this.currentDate = new Date();
            this.generateCalendar();
        },

        formatDate(date) {
            const d = new Date(date);
            let month = '' + (d.getMonth() + 1);
            let day = '' + d.getDate();
            const year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        },

        selectDay(day) {
            this.selectedDay = day;
            this.$nextTick(() => {
                if (typeof window.initIcons === 'function') {
                    window.initIcons();
                }
            });
        }
    }));
});
