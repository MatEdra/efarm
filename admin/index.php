<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart Farming Philippines</title>
    <?php include __DIR__ . '/../pwa_head.php'; ?>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php include("top_nav.php"); ?>

        <!-- Dashboard Content -->
        <div id="dashboard">
            <!-- Stats Overview -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number" id="totalFarmers">0</div>
                    <div class="stat-label">Total Farmers</div>
                    <small class="text-muted" id="weeklyGrowth">Loading...</small>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="stat-number" id="totalMaterials">0</div>
                    <div class="stat-label">Learning Materials</div>
                    <small class="text-muted" id="monthlyGrowth">Loading...</small>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-tractor"></i>
                    </div>
                    <div class="stat-number" id="activeFarms">0</div>
                    <div class="stat-label">Active Farms</div>
                    <small class="text-muted">Across regions</small>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-cloud-rain"></i>
                    </div>
                    <div class="stat-number" id="weatherAlerts">0</div>
                    <div class="stat-label">Weather Alerts</div>
                    <small class="text-muted">Active warnings</small>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="farmers.php" class="action-btn">
                    <i class="fas fa-user-plus fa-2x mb-3"></i>
                    <div>Add Farmer</div>
                </a>
                <a href="materials.php" class="action-btn">
                    <i class="fas fa-upload fa-2x mb-3"></i>
                    <div>Upload Materials</div>
                </a>
                <a href="seasons.php" class="action-btn">
                    <i class="fas fa-seedling fa-2x mb-3"></i>
                    <div>Crop Planning</div>
                </a>
                <a href="analytics.php" class="action-btn">
                    <i class="fas fa-chart-pie fa-2x mb-3"></i>
                    <div>View Reports</div>
                </a>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="chart-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Farmer Registration Trend</h5>
                            <div class="spinner-border spinner-border-sm" role="status" id="chartSpinner">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <canvas id="registrationChart" height="250"></canvas>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-container">
                        <h5 class="mb-3">Crop Distribution</h5>
                        <canvas id="cropChart" height="250"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Farmers -->
            <div class="row mt-4">
                <div class="col-lg-8">
                    <div class="table-container">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Recent Farmer Registrations</h5>
                            <a href="farmers.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Phone</th>
                                        <th>Location</th>
                                        <th>Farm Size</th>
                                        <th>Join Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recentFarmersTable">
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <div class="spinner-border" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="chart-container">
                        <h5 class="mb-3">System Activity</h5>
                        <div class="activity-timeline" id="activityTimeline">
                            <div class="text-center">
                                <div class="spinner-border" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regional Distribution -->
            <div class="table-container mt-4">
                <h5 class="mb-3">Farmers by Region</h5>
                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Region</th>
                                        <th>Farmers</th>
                                        <th>Active Farms</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody id="regionsTable">
                                    <tr>
                                        <td colspan="4" class="text-center">
                                            <div class="spinner-border spinner-border-sm" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="chart-container h-100">
                            <h6 class="text-center mb-3">Regional Distribution</h6>
                            <canvas id="regionChart" height="200"></canvas>
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
    <script src="javascript.js"></script>
    <script>
        // API endpoints
        const API_BASE = 'function/';
        const ENDPOINTS = {
            stats: 'dashboard_stats.php',
            recentFarmers: 'recent_farmers.php',
            registrationTrends: 'registration_trends.php',
            cropDistribution: 'crop_distribution.php',
            farmersByRegion: 'farmers_by_region.php',
            systemActivities: 'system_activities.php'
        };

        // Chart instances
        let registrationChart, cropChart, regionChart;

        // Fetch data from API
        async function fetchData(endpoint) {
            try {
                const response = await fetch(API_BASE + endpoint);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();
                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                return { success: false, error: error.message };
            }
        }

        // Load all dashboard data
        async function loadDashboardData() {
            try {
                // Load stats
                const statsResponse = await fetchData(ENDPOINTS.stats);
                if (statsResponse.success) {
                    updateStats(statsResponse.data);
                }

                // Load recent farmers
                const farmersResponse = await fetchData(ENDPOINTS.recentFarmers);
                if (farmersResponse.success) {
                    updateRecentFarmers(farmersResponse.data);
                }

                // Load registration trends
                const trendsResponse = await fetchData(ENDPOINTS.registrationTrends);
                if (trendsResponse.success) {
                    updateRegistrationChart(trendsResponse.data);
                }

                // Load crop distribution
                const cropsResponse = await fetchData(ENDPOINTS.cropDistribution);
                if (cropsResponse.success) {
                    updateCropChart(cropsResponse.data);
                }

                // Load farmers by region
                const regionsResponse = await fetchData(ENDPOINTS.farmersByRegion);
                if (regionsResponse.success) {
                    updateRegionsTable(regionsResponse.data);
                    updateRegionChart(regionsResponse.data);
                }

                // Load system activities
                const activitiesResponse = await fetchData(ENDPOINTS.systemActivities);
                if (activitiesResponse.success) {
                    updateActivities(activitiesResponse.data);
                }

            } catch (error) {
                console.error('Error loading dashboard data:', error);
                showError('Failed to load dashboard data');
            }
        }

        // Update stats cards
        function updateStats(stats) {
            document.getElementById('totalFarmers').textContent = formatNumber(stats.total_farmers || 0);
            document.getElementById('totalMaterials').textContent = formatNumber(stats.total_materials || 0);
            document.getElementById('activeFarms').textContent = formatNumber(stats.active_farms || 0);
            document.getElementById('weatherAlerts').textContent = formatNumber(stats.weather_alerts || 0);
            
            document.getElementById('weeklyGrowth').textContent = `+${stats.weekly_growth || 0} this week`;
            document.getElementById('monthlyGrowth').textContent = `+${stats.monthly_growth || 0} this month`;
        }

        // Update recent farmers table
        function updateRecentFarmers(farmers) {
            const tableBody = document.getElementById('recentFarmersTable');
            
            if (farmers.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No farmers found</td></tr>';
                return;
            }

            tableBody.innerHTML = farmers.map(farmer => `
                <tr>
                    <td>${escapeHtml(farmer.name)}</td>
                    <td>${escapeHtml(farmer.phone)}</td>
                    <td>${escapeHtml(farmer.location)}</td>
                    <td>${farmer.farm_size ? farmer.farm_size + ' ha' : 'N/A'}</td>
                    <td>${farmer.join_date}</td>
                    <td><span class="badge bg-success">${farmer.status}</span></td>
                </tr>
            `).join('');
        }

        // Update registration chart
        function updateRegistrationChart(trendsData) {
            const ctx = document.getElementById('registrationChart').getContext('2d');
            document.getElementById('chartSpinner').style.display = 'none';

            const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const registrationData = new Array(12).fill(0);

            trendsData.forEach(trend => {
                const monthIndex = trend.month_num - 1;
                registrationData[monthIndex] = trend.registrations;
            });

            if (registrationChart) {
                registrationChart.destroy();
            }

            registrationChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthNames,
                    datasets: [{
                        label: 'New Farmers',
                        data: registrationData,
                        borderColor: '#2e7d32',
                        backgroundColor: 'rgba(46, 125, 50, 0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 3
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
                            grid: {
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Update crop chart
        function updateCropChart(cropsData) {
            const ctx = document.getElementById('cropChart').getContext('2d');

            if (cropChart) {
                cropChart.destroy();
            }

            cropChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: cropsData.map(crop => crop.crop),
                    datasets: [{
                        data: cropsData.map(crop => crop.count),
                        backgroundColor: [
                            '#2e7d32',
                            '#4caf50',
                            '#8bc34a',
                            '#cddc39',
                            '#ffc107'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Update regions table
        function updateRegionsTable(regionsData) {
            const tableBody = document.getElementById('regionsTable');
            
            if (regionsData.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-muted">No regional data found</td></tr>';
                return;
            }

            tableBody.innerHTML = regionsData.map(region => {
                const progress = region.total_farmers > 0 ? (region.active_farms / region.total_farmers) * 100 : 0;
                const progressClass = progress >= 70 ? 'bg-success' : (progress >= 50 ? 'bg-warning' : 'bg-danger');
                
                return `
                    <tr>
                        <td>${escapeHtml(region.region)}</td>
                        <td>${region.total_farmers}</td>
                        <td>${region.active_farms}</td>
                        <td>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar ${progressClass}" style="width: ${progress}%"></div>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Update region chart
        function updateRegionChart(regionsData) {
            const ctx = document.getElementById('regionChart').getContext('2d');

            if (regionChart) {
                regionChart.destroy();
            }

            regionChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: regionsData.map(region => region.region),
                    datasets: [{
                        data: regionsData.map(region => region.total_farmers),
                        backgroundColor: [
                            '#2e7d32',
                            '#4caf50',
                            '#8bc34a',
                            '#cddc39',
                            '#ffc107'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        }

        // Update system activities
        function updateActivities(activitiesData) {
            const timeline = document.getElementById('activityTimeline');
            
            if (activitiesData.length === 0) {
                timeline.innerHTML = '<div class="text-center text-white">No recent activities</div>';
                return;
            }

            timeline.innerHTML = activitiesData.map(activity => {
                const iconClass = activity.type === 'farmer_registered' ? 'user-plus' :
                                activity.type === 'material_uploaded' ? 'file-upload' :
                                activity.type === 'weather_alert' ? 'cloud-rain' : 'chart-line';
                
                const date = new Date(activity.activity_date);
                const formattedDate = date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                const formattedTime = date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });
                
                return `
                    <div class="activity-item">
                        <div class="activity-icon bg-white text-success">
                            <i class="fas fa-${iconClass}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">${escapeHtml(activity.description)}</div>
                            <div class="activity-time">${formattedDate}, ${formattedTime}</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Utility functions
        function formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }

        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 3000
            });
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            
            // Refresh data every 5 minutes
            setInterval(loadDashboardData, 300000);
        });
    </script>
</body>

</html>