/**
 * Reports App (Admin)
 * ====================
 * Handles initialization of Chart.js charts for the admin reports view.
 * Data is passed from the controller via window.reportData.
 */
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);

function initCharts() {
    const monthlyCtx = document.getElementById('monthlyTrendChart');
    if (!monthlyCtx) return;

    const data = window.reportData || {};

    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: data.monthlyLabels || ['Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Attendance Rate',
                data: data.monthlyRates || [97.2, 96.5, 95.8, 94.3],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    min: 90,
                    max: 100,
                    ticks: { callback: function (value) { return value + '%'; } }
                }
            }
        }
    });

    const statusCtx = document.getElementById('statusDistributionChart');
    if (statusCtx) {
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: data.statusLabels || ['Present', 'Unjustified Absence', 'Justified Absence', 'Late'],
                datasets: [{
                    data: data.statusData || [95.8, 2.1, 1.8, 0.3],
                    backgroundColor: ['#22c55e', '#ef4444', '#3b82f6', '#f59e0b'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }

    let trendsChartInitialized = false;
    window.initTrendsChart = function () {
        if (trendsChartInitialized) return;
        const weekdayCtx = document.getElementById('weekdayTrendChart');
        if (weekdayCtx) {
            new Chart(weekdayCtx, {
                type: 'bar',
                data: {
                    labels: data.weekdayLabels || ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    datasets: [{
                        label: 'Absences',
                        data: data.weekdayData || [48, 32, 28, 35, 52],
                        backgroundColor: '#ef4444',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }
        trendsChartInitialized = true;
    };
}

document.addEventListener('DOMContentLoaded', initCharts);