/**
 * ASIC Repair - Analytics Charts
 * Chart.js integration for dashboards and analytics
 */

(function () {
    'use strict';

    const Analytics = {
        charts: {},
        defaultColors: {
            primary: '#3b82f6',
            success: '#10b981',
            warning: '#f59e0b',
            danger: '#ef4444',
            info: '#6366f1',
            secondary: '#64748b'
        },

        /**
         * Initialize Analytics
         */
        init: function () {
            // Check for Chart.js
            if (typeof Chart === 'undefined') {
                console.log('Chart.js not loaded, skipping analytics init');
                return;
            }

            // Set Chart.js defaults
            this.setChartDefaults();

            // Auto-init charts with data attributes
            this.autoInitCharts();

            console.log('Analytics initialized');
        },

        /**
         * Set Chart.js default options
         */
        setChartDefaults: function () {
            Chart.defaults.font.family = "'Inter', 'Segoe UI', system-ui, sans-serif";
            Chart.defaults.font.size = 12;
            Chart.defaults.color = getComputedStyle(document.documentElement)
                .getPropertyValue('--text-secondary').trim() || '#64748b';

            // Responsive defaults
            Chart.defaults.responsive = true;
            Chart.defaults.maintainAspectRatio = false;

            // Animation
            Chart.defaults.animation = {
                duration: 750,
                easing: 'easeOutQuart'
            };
        },

        /**
         * Auto-initialize charts with data-chart attribute
         */
        autoInitCharts: function () {
            const chartElements = document.querySelectorAll('[data-chart]');

            chartElements.forEach(element => {
                const chartType = element.dataset.chart;
                const chartUrl = element.dataset.chartUrl;
                const chartId = element.id || 'chart-' + Math.random().toString(36).substr(2, 9);

                if (!element.id) element.id = chartId;

                if (chartUrl) {
                    this.loadChartData(chartId, chartUrl, chartType);
                }
            });
        },

        /**
         * Load chart data from URL
         */
        loadChartData: function (chartId, url, chartType) {
            const container = document.getElementById(chartId);
            if (!container) return;

            // Show loading
            container.innerHTML = `
                <div class="chart-loading">
                    <div class="spinner-border text-primary"></div>
                    <span>กำลังโหลดข้อมูล...</span>
                </div>
            `;

            fetch(url)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // Create canvas
                    container.innerHTML = '<canvas></canvas>';
                    const canvas = container.querySelector('canvas');

                    // Create chart
                    this.createChart(canvas, chartType, data);
                })
                .catch(error => {
                    console.error('Chart load error:', error);
                    container.innerHTML = `
                        <div class="chart-error">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>ไม่สามารถโหลดข้อมูลได้</span>
                        </div>
                    `;
                });
        },

        /**
         * Create a chart
         */
        createChart: function (canvas, type, data) {
            const chartId = canvas.parentElement.id;

            // Destroy existing chart
            if (this.charts[chartId]) {
                this.charts[chartId].destroy();
            }

            const options = this.getChartOptions(type, data);

            this.charts[chartId] = new Chart(canvas.getContext('2d'), {
                type: type,
                data: data,
                options: options
            });

            return this.charts[chartId];
        },

        /**
         * Get chart options based on type
         */
        getChartOptions: function (type, data) {
            const baseOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        cornerRadius: 8,
                        titleFont: { weight: 'bold' }
                    }
                }
            };

            switch (type) {
                case 'line':
                    return {
                        ...baseOptions,
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                border: { display: false }
                            }
                        },
                        elements: {
                            line: {
                                tension: 0.4,
                                borderWidth: 2
                            },
                            point: {
                                radius: 3,
                                hoverRadius: 6
                            }
                        }
                    };

                case 'bar':
                    return {
                        ...baseOptions,
                        scales: {
                            x: {
                                grid: { display: false },
                                border: { display: false }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    drawBorder: false
                                },
                                border: { display: false }
                            }
                        },
                        borderRadius: 4,
                        borderSkipped: false
                    };

                case 'doughnut':
                case 'pie':
                    return {
                        ...baseOptions,
                        cutout: type === 'doughnut' ? '70%' : 0,
                        plugins: {
                            ...baseOptions.plugins,
                            legend: {
                                position: 'right',
                                labels: {
                                    usePointStyle: true,
                                    padding: 15
                                }
                            }
                        }
                    };

                default:
                    return baseOptions;
            }
        },

        /**
         * Create a line chart for time series data
         */
        createTimeSeriesChart: function (canvasId, labels, datasets, options = {}) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return null;

            const defaultDatasetOptions = {
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 3,
                pointHoverRadius: 6
            };

            const formattedDatasets = datasets.map((ds, index) => ({
                ...defaultDatasetOptions,
                backgroundColor: this.hexToRgba(ds.color || this.getColorByIndex(index), 0.1),
                borderColor: ds.color || this.getColorByIndex(index),
                ...ds
            }));

            return this.createChart(canvas, 'line', {
                labels: labels,
                datasets: formattedDatasets
            });
        },

        /**
         * Create a bar chart
         */
        createBarChart: function (canvasId, labels, datasets, options = {}) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return null;

            const defaultDatasetOptions = {
                backgroundColor: this.defaultColors.primary,
                borderRadius: 4
            };

            const formattedDatasets = datasets.map((ds, index) => ({
                ...defaultDatasetOptions,
                backgroundColor: ds.colors || this.getColorByIndex(index),
                ...ds
            }));

            return this.createChart(canvas, 'bar', {
                labels: labels,
                datasets: formattedDatasets
            });
        },

        /**
         * Create a doughnut chart
         */
        createDoughnutChart: function (canvasId, labels, data, colors = null) {
            const canvas = document.getElementById(canvasId);
            if (!canvas) return null;

            return this.createChart(canvas, 'doughnut', {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: colors || [
                        this.defaultColors.primary,
                        this.defaultColors.success,
                        this.defaultColors.warning,
                        this.defaultColors.danger,
                        this.defaultColors.info,
                        this.defaultColors.secondary
                    ],
                    borderWidth: 0
                }]
            });
        },

        /**
         * Create system metrics chart (CPU, RAM, Disk)
         */
        createSystemMetricsChart: function (canvasId, period = '24h') {
            const container = document.getElementById(canvasId);
            if (!container) return;

            // Show loading
            container.innerHTML = `
                <div class="chart-loading">
                    <div class="spinner-border text-primary"></div>
                    <span>กำลังโหลดข้อมูล...</span>
                </div>
            `;

            fetch(`/admin/monitoring/graph-data?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = '<canvas></canvas>';
                    const canvas = container.querySelector('canvas');

                    this.createChart(canvas, 'line', {
                        labels: data.labels,
                        datasets: [
                            {
                                label: 'CPU (%)',
                                data: data.cpu,
                                borderColor: this.defaultColors.primary,
                                backgroundColor: this.hexToRgba(this.defaultColors.primary, 0.1),
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'RAM (%)',
                                data: data.ram,
                                borderColor: this.defaultColors.success,
                                backgroundColor: this.hexToRgba(this.defaultColors.success, 0.1),
                                tension: 0.4,
                                fill: true
                            },
                            {
                                label: 'Disk (%)',
                                data: data.disk,
                                borderColor: this.defaultColors.warning,
                                backgroundColor: this.hexToRgba(this.defaultColors.warning, 0.1),
                                tension: 0.4,
                                fill: true
                            }
                        ]
                    });
                })
                .catch(error => {
                    console.error('System metrics error:', error);
                    container.innerHTML = `
                        <div class="chart-error">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>ไม่สามารถโหลดข้อมูล metrics ได้</span>
                        </div>
                    `;
                });
        },

        /**
         * Create revenue chart
         */
        createRevenueChart: function (canvasId, period = 'weekly') {
            const container = document.getElementById(canvasId);
            if (!container) return;

            container.innerHTML = `
                <div class="chart-loading">
                    <div class="spinner-border text-primary"></div>
                    <span>กำลังโหลดข้อมูล...</span>
                </div>
            `;

            fetch(`/api/reports/revenue?period=${period}`)
                .then(response => response.json())
                .then(data => {
                    container.innerHTML = '<canvas></canvas>';
                    const canvas = container.querySelector('canvas');

                    this.createChart(canvas, 'bar', {
                        labels: data.labels,
                        datasets: [{
                            label: 'รายได้ (฿)',
                            data: data.values,
                            backgroundColor: this.defaultColors.success,
                            borderRadius: 6
                        }]
                    });
                })
                .catch(error => {
                    console.error('Revenue chart error:', error);
                    container.innerHTML = `
                        <div class="chart-error">
                            <i class="bi bi-exclamation-circle"></i>
                            <span>ไม่สามารถโหลดข้อมูลรายได้ได้</span>
                        </div>
                    `;
                });
        },

        /**
         * Update chart with new data
         */
        updateChart: function (chartId, newData) {
            if (!this.charts[chartId]) return;

            const chart = this.charts[chartId];
            chart.data = newData;
            chart.update('active');
        },

        /**
         * Refresh chart period
         */
        refreshPeriod: function (chartId, period, fetchUrl) {
            const container = document.getElementById(chartId);
            if (!container) return;

            const url = fetchUrl + (fetchUrl.includes('?') ? '&' : '?') + 'period=' + period;
            this.loadChartData(chartId, url, 'line');
        },

        /**
         * Destroy a chart
         */
        destroyChart: function (chartId) {
            if (this.charts[chartId]) {
                this.charts[chartId].destroy();
                delete this.charts[chartId];
            }
        },

        /**
         * Get color by index
         */
        getColorByIndex: function (index) {
            const colors = [
                this.defaultColors.primary,
                this.defaultColors.success,
                this.defaultColors.warning,
                this.defaultColors.danger,
                this.defaultColors.info,
                this.defaultColors.secondary
            ];
            return colors[index % colors.length];
        },

        /**
         * Convert hex to rgba
         */
        hexToRgba: function (hex, alpha) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        },

        /**
         * Format number for display
         */
        formatNumber: function (num, decimals = 0) {
            return new Intl.NumberFormat('th-TH', {
                minimumFractionDigits: decimals,
                maximumFractionDigits: decimals
            }).format(num);
        },

        /**
         * Format currency
         */
        formatCurrency: function (amount) {
            return new Intl.NumberFormat('th-TH', {
                style: 'currency',
                currency: 'THB',
                minimumFractionDigits: 0
            }).format(amount);
        }
    };

    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        Analytics.init();
    });

    // Expose to global scope
    window.Analytics = Analytics;

})();

// Period selector handler
if (typeof jQuery !== 'undefined') {
    jQuery(document).on('click', '.period-selector .btn', function () {
        const $btn = jQuery(this);
        const $container = $btn.closest('.chart-card');
        const chartId = $container.find('[data-chart]').attr('id');
        const period = $btn.data('period');
        const url = $container.find('[data-chart]').data('chart-url');

        // Update active state
        $btn.siblings().removeClass('active');
        $btn.addClass('active');

        // Refresh chart
        if (chartId && url) {
            Analytics.refreshPeriod(chartId, period, url);
        }
    });
}
