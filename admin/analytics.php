<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - Smart Farming Philippines</title>
    <?php include __DIR__ . '/../pwa_head.php'; ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Moment.js (required for Date Range Picker) -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
    <!-- Date Range Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php include("top_nav.php"); ?>

        <!-- Analytics Dashboard Content -->
        <div id="analytics">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Analytics Dashboard</h4>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" id="dateRangePicker" style="width: 250px;" placeholder="Select date range">
                    <button class="btn btn-outline-secondary" onclick="refreshAnalytics()">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Key Metrics Overview -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Farmers</h6>
                                    <h3 class="mb-0" id="totalFarmers">0</h3>
                                    <small class="text-success" id="farmersGrowth">
                                        <i class="fas fa-arrow-up"></i> 0% growth
                                    </small>
                                </div>
                                <div class="metric-icon bg-primary">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Learning Materials</h6>
                                    <h3 class="mb-0" id="totalMaterials">0</h3>
                                    <small class="text-success" id="materialsGrowth">
                                        <i class="fas fa-arrow-up"></i> 0% growth
                                    </small>
                                </div>
                                <div class="metric-icon bg-success">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Total Downloads</h6>
                                    <h3 class="mb-0" id="totalDownloads">0</h3>
                                    <small class="text-success" id="downloadsGrowth">
                                        <i class="fas fa-arrow-up"></i> 0% growth
                                    </small>
                                </div>
                                <div class="metric-icon bg-info">
                                    <i class="fas fa-download"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card metric-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Active Sessions</h6>
                                    <h3 class="mb-0" id="activeSessions">0</h3>
                                    <small class="text-danger" id="sessionsChange">
                                        <i class="fas fa-arrow-down"></i> 0% change
                                    </small>
                                </div>
                                <div class="metric-icon bg-warning">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1 -->
            <div class="row mb-4">
                <!-- Farmer Registration Trends -->
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Farmer Registration Trends</h5>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-secondary active" onclick="changeChartPeriod('month')">Month</button>
                                <button class="btn btn-outline-secondary" onclick="changeChartPeriod('quarter')">Quarter</button>
                                <button class="btn btn-outline-secondary" onclick="changeChartPeriod('year')">Year</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="farmerTrendsChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Material Types Distribution -->
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Learning Material Types</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="materialTypesChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2 -->
            <div class="row mb-4">
                <!-- Download Analytics -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Download Analytics</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="downloadsChart" height="150"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top Downloaded Materials -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Top Downloaded Materials</h5>
                        </div>
                        <div class="card-body">
                            <div id="topMaterialsList">
                                <div class="text-center py-3">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="mt-2">Loading top materials...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 3 -->
            <div class="row mb-4">
                <!-- Farmer Locations -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Farmer Distribution by Location</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="locationChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Platform Usage -->
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Platform Usage Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <div class="text-center p-3 border rounded">
                                        <div class="h4 text-primary mb-1" id="avgSessionTime">0m</div>
                                        <small class="text-muted">Avg. Session Time</small>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="text-center p-3 border rounded">
                                        <div class="h4 text-success mb-1" id="bounceRate">0%</div>
                                        <small class="text-muted">Bounce Rate</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 border rounded">
                                        <div class="h4 text-info mb-1" id="pagesPerSession">0</div>
                                        <small class="text-muted">Pages/Session</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 border rounded">
                                        <div class="h4 text-warning mb-1" id="returningUsers">0%</div>
                                        <small class="text-muted">Returning Users</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Activity</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="loadRecentActivity()">
                                <i class="fas fa-refresh"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>User</th>
                                            <th>Activity</th>
                                            <th>Details</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="recentActivityTable">
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading activity...</span>
                                                </div>
                                                <div class="mt-2">Loading recent activity...</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include("nav_mobile.php"); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (required for Date Range Picker) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Date Range Picker -->
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        // API endpoints
        const ANALYTICS_API = 'function/analytics_api.php';
        let charts = {};
        let currentDateRange = 'last_30_days';
        let currentPeriod = 'month';

        // Initialize date range picker
        $(document).ready(function() {
            // Set default date range (last 30 days)
            const startDate = new Date();
            startDate.setDate(startDate.getDate() - 29);
            const endDate = new Date();
            
            const startDateStr = startDate.toISOString().split('T')[0];
            const endDateStr = endDate.toISOString().split('T')[0];
            
            $('#dateRangePicker').val(`${startDateStr} to ${endDateStr}`);
            
            $('#dateRangePicker').daterangepicker({
                startDate: startDate,
                endDate: endDate,
                ranges: {
                    'Today': [new Date(), new Date()],
                    'Yesterday': [new Date(Date.now() - 86400000), new Date(Date.now() - 86400000)],
                    'Last 7 Days': [new Date(Date.now() - 6 * 86400000), new Date()],
                    'Last 30 Days': [new Date(Date.now() - 29 * 86400000), new Date()],
                    'This Month': [new Date(new Date().getFullYear(), new Date().getMonth(), 1), new Date()],
                    'Last Month': [
                        new Date(new Date().getFullYear(), new Date().getMonth() - 1, 1),
                        new Date(new Date().getFullYear(), new Date().getMonth(), 0)
                    ]
                }
            }, function(start, end) {
                currentDateRange = `${start.format('YYYY-MM-DD')}_to_${end.format('YYYY-MM-DD')}`;
                loadAnalyticsData();
            });

            // Initialize analytics
            loadAnalyticsData();
        });

        // Load all analytics data
        async function loadAnalyticsData() {
            try {
                showLoading();
                const response = await fetch(`${ANALYTICS_API}?date_range=${currentDateRange}&period=${currentPeriod}`);
                const data = await response.json();

                if (data.success) {
                    updateMetrics(data.data.metrics);
                    updateCharts(data.data.charts);
                    updateTopMaterials(data.data.top_materials);
                    updatePlatformStats(data.data.platform_stats);
                    updateRecentActivity(data.data.recent_activity);
                } else {
                    throw new Error(data.error || 'Failed to load analytics data');
                }
            } catch (error) {
                console.error('Error loading analytics data:', error);
                showError('Failed to load analytics data');
            } finally {
                hideLoading();
            }
        }

        // Update key metrics
        function updateMetrics(metrics) {
            document.getElementById('totalFarmers').textContent = metrics.total_farmers.toLocaleString();
            document.getElementById('totalMaterials').textContent = metrics.total_materials.toLocaleString();
            document.getElementById('totalDownloads').textContent = metrics.total_downloads.toLocaleString();
            document.getElementById('activeSessions').textContent = metrics.active_sessions.toLocaleString();

            // Update growth indicators
            updateGrowthIndicator('farmersGrowth', metrics.farmers_growth);
            updateGrowthIndicator('materialsGrowth', metrics.materials_growth);
            updateGrowthIndicator('downloadsGrowth', metrics.downloads_growth);
            updateGrowthIndicator('sessionsChange', metrics.sessions_change, true);
        }

        // Update growth indicator
        function updateGrowthIndicator(elementId, value, isChange = false) {
            const element = document.getElementById(elementId);
            const absValue = Math.abs(value);
            const arrow = value >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
            const colorClass = value >= 0 ? 'text-success' : 'text-danger';
            
            element.innerHTML = `<i class="fas ${arrow}"></i> ${absValue}% ${isChange ? 'change' : 'growth'}`;
            element.className = colorClass;
        }

        // Update all charts
        function updateCharts(chartData) {
            updateFarmerTrendsChart(chartData.farmer_trends);
            updateMaterialTypesChart(chartData.material_types);
            updateDownloadsChart(chartData.downloads);
            updateLocationChart(chartData.locations);
        }

        // Update farmer trends chart
        function updateFarmerTrendsChart(data) {
            const ctx = document.getElementById('farmerTrendsChart').getContext('2d');
            
            if (charts.farmerTrends) {
                charts.farmerTrends.destroy();
            }

            charts.farmerTrends = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'New Farmers',
                        data: data.new_farmers,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        tension: 0.4,
                        fill: true
                    }, {
                        label: 'Total Farmers',
                        data: data.total_farmers,
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Farmers'
                            }
                        }
                    }
                }
            });
        }

        // Update material types chart
        function updateMaterialTypesChart(data) {
            const ctx = document.getElementById('materialTypesChart').getContext('2d');
            
            if (charts.materialTypes) {
                charts.materialTypes.destroy();
            }

            const backgroundColors = [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
            ];

            charts.materialTypes = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: backgroundColors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }

        // Update downloads chart
        function updateDownloadsChart(data) {
            const ctx = document.getElementById('downloadsChart').getContext('2d');
            
            if (charts.downloads) {
                charts.downloads.destroy();
            }

            charts.downloads = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Downloads',
                        data: data.downloads,
                        backgroundColor: 'rgba(78, 115, 223, 0.8)',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Downloads'
                            }
                        }
                    }
                }
            });
        }

        // Update location chart
        function updateLocationChart(data) {
            const ctx = document.getElementById('locationChart').getContext('2d');
            
            if (charts.location) {
                charts.location.destroy();
            }

            charts.location = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Farmers',
                        data: data.farmers,
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: '#1cc88a',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Number of Farmers'
                            }
                        }
                    }
                }
            });
        }

        // Update top materials list
        function updateTopMaterials(materials) {
            const container = document.getElementById('topMaterialsList');
            
            if (materials.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No download data available</p>';
                return;
            }

            container.innerHTML = materials.map((material, index) => `
                <div class="d-flex align-items-center mb-3">
                    <div class="flex-shrink-0">
                        <span class="badge bg-primary rounded-circle me-2">${index + 1}</span>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1">${escapeHtml(material.title)}</h6>
                        <small class="text-muted">
                            <i class="fas fa-download me-1"></i>
                            ${material.download_count} downloads • 
                            ${material.file_type.toUpperCase()}
                        </small>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge bg-light text-dark">${material.percentage}%</span>
                    </div>
                </div>
            `).join('');
        }

        // Update platform statistics
        function updatePlatformStats(stats) {
            document.getElementById('avgSessionTime').textContent = stats.avg_session_time;
            document.getElementById('bounceRate').textContent = stats.bounce_rate + '%';
            document.getElementById('pagesPerSession').textContent = stats.pages_per_session;
            document.getElementById('returningUsers').textContent = stats.returning_users + '%';
        }

        // Update recent activity
        function updateRecentActivity(activities) {
            const tableBody = document.getElementById('recentActivityTable');
            
            if (activities.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                            <p class="text-muted">No recent activity</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = activities.map(activity => `
                <tr>
                    <td>
                        <small class="text-muted">${formatTime(activity.timestamp)}</small>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-light rounded me-2">
                                <i class="fas fa-user text-primary p-2"></i>
                            </div>
                            <div>
                                <div class="fw-bold">${escapeHtml(activity.user_name)}</div>
                                <small class="text-muted">${activity.user_role}</small>
                            </div>
                        </div>
                    </td>
                    <td>${escapeHtml(activity.action)}</td>
                    <td>
                        <small class="text-muted">${escapeHtml(activity.details)}</small>
                    </td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(activity.status)}">
                            ${activity.status}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        // Change chart period
        function changeChartPeriod(period) {
            // Update active button
            document.querySelectorAll('.btn-group .btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');
            
            currentPeriod = period;
            loadAnalyticsData();
        }

        // Refresh analytics data
        function refreshAnalytics() {
            loadAnalyticsData();
            Swal.fire({
                icon: 'success',
                title: 'Refreshed!',
                text: 'Analytics data has been updated',
                timer: 1500,
                showConfirmButton: false
            });
        }

        // Load recent activity
        async function loadRecentActivity() {
            try {
                const response = await fetch(`${ANALYTICS_API}?action=recent_activity`);
                const data = await response.json();

                if (data.success) {
                    updateRecentActivity(data.data);
                } else {
                    throw new Error(data.error || 'Failed to load recent activity');
                }
            } catch (error) {
                console.error('Error loading recent activity:', error);
                showError('Failed to load recent activity');
            }
        }

        // Utility functions
        function showLoading() {
            // Show loading states for various components
            document.querySelectorAll('.spinner-border').forEach(spinner => {
                spinner.style.display = 'inline-block';
            });
        }

        function hideLoading() {
            // Hide loading states
            document.querySelectorAll('.spinner-border').forEach(spinner => {
                spinner.style.display = 'none';
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 3000
            });
        }

        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) return 'Just now';
            if (diffMins < 60) return `${diffMins}m ago`;
            if (diffHours < 24) return `${diffHours}h ago`;
            if (diffDays < 7) return `${diffDays}d ago`;
            
            return date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric'
            });
        }

        function getStatusBadgeClass(status) {
            const classes = {
                'completed': 'bg-success',
                'pending': 'bg-warning',
                'failed': 'bg-danger',
                'processing': 'bg-info'
            };
            return classes[status.toLowerCase()] || 'bg-secondary';
        }

        // Export analytics data
        async function exportAnalytics(format) {
            try {
                const response = await fetch(`${ANALYTICS_API}?export=${format}&date_range=${currentDateRange}`);
                const data = await response.blob();
                
                const url = window.URL.createObjectURL(data);
                const a = document.createElement('a');
                a.href = url;
                a.download = `analytics_${currentDateRange}.${format}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

                Swal.fire({
                    icon: 'success',
                    title: 'Exported!',
                    text: `Analytics data exported as ${format.toUpperCase()}`,
                    timer: 2000
                });
            } catch (error) {
                console.error('Error exporting analytics:', error);
                showError('Failed to export analytics data');
            }
        }
    </script>

    <style>
        .metric-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e3e6f0;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            border: 1px solid #e3e6f0;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }

        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }

        .btn-group .btn.active {
            background-color: #4e73df;
            border-color: #4e73df;
            color: white;
        }

        @media (max-width: 768px) {
            .metric-card {
                margin-bottom: 1rem;
            }
            
            #dateRangePicker {
                width: 200px !important;
            }
        }
    </style>
    <script src="javascript.js"></script>
</body>

</html>