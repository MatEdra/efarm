<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard - Smart Farming Philippines</title>
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
        
        <div id="content">
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card welcome-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="card-title text-primary mb-2">Welcome back, <?php echo $_SESSION['farmer_name']; ?>! 👋</h3>
                                    <p class="card-text text-muted mb-3">
                                        Here's what's happening on your farm today. Check the weather, manage your crops, and access learning materials.
                                    </p>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-tractor me-1"></i>
                                            <?php echo $_SESSION['farm_name'] ?: 'My Farm'; ?>
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            <?php echo $_SESSION['farm_location'] ?: 'Farm Location'; ?>
                                        </span>
                                        <span class="badge bg-light text-dark">
                                            <i class="fas fa-ruler-combined me-1"></i>
                                            <?php echo $_SESSION['farm_size'] ? $_SESSION['farm_size'] . ' hectares' : 'Farm Size'; ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="weather-widget p-3 rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <div id="weatherLoading">
                                            <div class="spinner-border text-light" role="status">
                                                <span class="visually-hidden">Loading weather...</span>
                                            </div>
                                            <div class="mt-2 text-white">Loading weather...</div>
                                        </div>
                                        <div id="weatherContent" style="display: none;">
                                            <i class="fas fa-cloud-sun fa-3x text-white mb-2" id="weatherIcon"></i>
                                            <h5 class="text-white mb-1" id="currentTemp">--°C</h5>
                                            <small class="text-white" id="weatherCondition">Loading...</small>
                                            <small class="text-white d-block" id="weatherLocation"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">My Crops</h6>
                                    <h3 class="mb-0" id="activeCrops">0</h3>
                                    <small class="text-success" id="cropsStatus">
                                        <i class="fas fa-seedling"></i> Loading...
                                    </small>
                                </div>
                                <div class="stat-icon bg-success">
                                    <i class="fas fa-leaf"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Learning Materials</h6>
                                    <h3 class="mb-0" id="totalMaterials">0</h3>
                                    <small class="text-info">
                                        <i class="fas fa-book-open"></i> Available guides
                                    </small>
                                </div>
                                <div class="stat-icon bg-info">
                                    <i class="fas fa-book"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Weather Alerts</h6>
                                    <h3 class="mb-0" id="weatherAlerts">0</h3>
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i> Monitoring
                                    </small>
                                </div>
                                <div class="stat-icon bg-warning">
                                    <i class="fas fa-cloud-rain"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title text-muted mb-2">Farm Experience</h6>
                                    <h3 class="mb-0" id="experienceYears"><?php echo $_SESSION['experience_years'] ?? '0'; ?>y</h3>
                                    <small class="text-primary">
                                        <i class="fas fa-award"></i> Seasoned farmer
                                    </small>
                                </div>
                                <div class="stat-icon bg-primary">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Row -->
            <div class="row">
                <!-- My Crops Section -->
                <div class="col-xl-8 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">My Crops</h5>
                            <a href="my-crops.php" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div id="cropsList">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading crops...</span>
                                    </div>
                                    <div class="mt-2">Loading your crops...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Weather Forecast -->
                <div class="col-xl-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">7-Day Forecast</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="refreshWeather()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="weatherForecast">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading forecast...</span>
                                    </div>
                                    <div class="mt-2">Loading weather forecast...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Learning Materials -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Learning Materials</h5>
                            <a href="materials.php" class="btn btn-sm btn-outline-primary">Browse All</a>
                        </div>
                        <div class="card-body">
                            <div id="materialsList">
                                <div class="text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading materials...</span>
                                    </div>
                                    <div class="mt-2">Loading learning materials...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weather Alert -->
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning d-flex align-items-center" role="alert" id="weatherAlert" style="display: none !important;">
                        <i class="fas fa-exclamation-triangle fa-lg me-3"></i>
                        <div>
                            <h6 class="alert-heading mb-1" id="alertTitle">Weather Advisory</h6>
                            <p class="mb-0" id="alertMessage">Monitoring weather conditions...</p>
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
        const FARMER_API = 'function/farmer_api.php';
        let userLocation = null;
        
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
            getCurrentLocation();
        });

        // Get user's current location for weather data
        function getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => {
                        userLocation = {
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        };
                        getWeatherData();
                    },
                    error => {
                        console.error('Geolocation error:', error);
                        // Default to Manila coordinates if location access is denied
                        userLocation = { latitude: 14.5995, longitude: 120.9842 };
                        getWeatherData();
                    }
                );
            } else {
                // Default to Manila coordinates if geolocation is not supported
                userLocation = { latitude: 14.5995, longitude: 120.9842 };
                getWeatherData();
            }
        }

        // Get weather data from Open-Meteo API
        async function getWeatherData() {
            try {
                const response = await fetch(
                    `https://api.open-meteo.com/v1/forecast?latitude=${userLocation.latitude}&longitude=${userLocation.longitude}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&daily=weather_code,temperature_2m_max,temperature_2m_min&timezone=auto`
                );
                const data = await response.json();

                if (data && data.current) {
                    updateCurrentWeather(data);
                    updateWeatherForecast(data.daily);
                    checkWeatherAlerts(data.current);
                }
            } catch (error) {
                console.error('Error fetching weather data:', error);
                showError('Failed to load weather data');
            }
        }

        function updateCurrentWeather(weatherData) {
            const current = weatherData.current;
            const temp = Math.round(current.temperature_2m);
            const condition = getWeatherCondition(current.weather_code);
            const icon = getWeatherIcon(current.weather_code);

            document.getElementById('weatherLoading').style.display = 'none';
            document.getElementById('weatherContent').style.display = 'block';
            
            document.getElementById('currentTemp').textContent = `${temp}°C`;
            document.getElementById('weatherCondition').textContent = condition;
            document.getElementById('weatherIcon').className = `${icon} fa-3x text-white mb-2`;
            document.getElementById('weatherLocation').textContent = 'Your Location';
        }

        function updateWeatherForecast(dailyData) {
            const container = document.getElementById('weatherForecast');
            const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            
            let forecastHTML = '';
            
            for (let i = 0; i < 7; i++) {
                const date = new Date(dailyData.time[i]);
                const dayName = days[date.getDay()];
                const maxTemp = Math.round(dailyData.temperature_2m_max[i]);
                const minTemp = Math.round(dailyData.temperature_2m_min[i]);
                const weatherCode = dailyData.weather_code[i];
                const icon = getWeatherIcon(weatherCode);

                forecastHTML += `
                    <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom">
                        <div class="fw-semibold" style="width: 40px;">${dayName}</div>
                        <div class="text-center" style="width: 40px;">
                            <i class="${icon} text-warning"></i>
                        </div>
                        <div class="text-end">
                            <span class="fw-semibold">${maxTemp}°</span>
                            <span class="text-muted">${minTemp}°</span>
                        </div>
                    </div>
                `;
            }
            
            container.innerHTML = forecastHTML;
        }

        function checkWeatherAlerts(currentWeather) {
            const alertDiv = document.getElementById('weatherAlert');
            const alertTitle = document.getElementById('alertTitle');
            const alertMessage = document.getElementById('alertMessage');
            
            // Check for weather conditions that might need alerts
            if (currentWeather.temperature_2m > 35) {
                alertTitle.textContent = 'Heat Advisory';
                alertMessage.textContent = 'High temperatures expected. Ensure proper irrigation and crop protection.';
                alertDiv.style.display = 'flex !important';
                alertDiv.className = 'alert alert-danger d-flex align-items-center';
            } else if (currentWeather.weather_code >= 60 && currentWeather.weather_code <= 67) {
                // Rain codes: 61-67
                alertTitle.textContent = 'Rain Advisory';
                alertMessage.textContent = 'Rain expected. Adjust irrigation and protect sensitive crops.';
                alertDiv.style.display = 'flex !important';
                alertDiv.className = 'alert alert-warning d-flex align-items-center';
            } else if (currentWeather.wind_speed_10m > 30) {
                alertTitle.textContent = 'Wind Advisory';
                alertMessage.textContent = 'Strong winds expected. Secure farm equipment and structures.';
                alertDiv.style.display = 'flex !important';
                alertDiv.className = 'alert alert-warning d-flex align-items-center';
            } else {
                alertDiv.style.display = 'none';
            }
        }

        function getWeatherCondition(weatherCode) {
            const conditions = {
                0: 'Clear sky',
                1: 'Mainly clear',
                2: 'Partly cloudy',
                3: 'Overcast',
                45: 'Fog',
                48: 'Depositing rime fog',
                51: 'Light drizzle',
                53: 'Moderate drizzle',
                55: 'Dense drizzle',
                61: 'Slight rain',
                63: 'Moderate rain',
                65: 'Heavy rain',
                80: 'Slight rain showers',
                81: 'Moderate rain showers',
                82: 'Violent rain showers',
                95: 'Thunderstorm',
                96: 'Thunderstorm with slight hail',
                99: 'Thunderstorm with heavy hail'
            };
            return conditions[weatherCode] || 'Unknown';
        }

        function getWeatherIcon(weatherCode) {
            if (weatherCode === 0) return 'fas fa-sun';
            if (weatherCode <= 2) return 'fas fa-cloud-sun';
            if (weatherCode === 3) return 'fas fa-cloud';
            if (weatherCode <= 48) return 'fas fa-smog';
            if (weatherCode <= 55) return 'fas fa-cloud-rain';
            if (weatherCode <= 65) return 'fas fa-cloud-showers-heavy';
            if (weatherCode <= 82) return 'fas fa-cloud-sun-rain';
            if (weatherCode >= 95) return 'fas fa-bolt';
            return 'fas fa-cloud';
        }

        function refreshWeather() {
            document.getElementById('weatherLoading').style.display = 'block';
            document.getElementById('weatherContent').style.display = 'none';
            getWeatherData();
            
            Swal.fire({
                title: 'Refreshing',
                text: 'Updating weather data...',
                icon: 'info',
                timer: 1000,
                showConfirmButton: false
            });
        }

        async function loadDashboardData() {
            try {
                // Load dashboard stats
                const statsResponse = await fetch(`${FARMER_API}?action=dashboard_stats`);
                const statsData = await statsResponse.json();
                
                if (statsData.success) {
                    updateDashboardStats(statsData.data);
                }

                // Load crops
                const cropsResponse = await fetch(`${FARMER_API}?action=my_crops`);
                const cropsData = await cropsResponse.json();
                
                if (cropsData.success) {
                    updateCropsList(cropsData.data);
                }

                // Load materials
                const materialsResponse = await fetch(`${FARMER_API}?action=recent_materials`);
                const materialsData = await materialsResponse.json();
                
                if (materialsData.success) {
                    updateMaterialsList(materialsData.data);
                }

            } catch (error) {
                console.error('Error loading dashboard data:', error);
                showError('Failed to load dashboard data');
            }
        }

        function updateDashboardStats(data) {
            document.getElementById('activeCrops').textContent = data.active_crops;
            document.getElementById('totalMaterials').textContent = data.total_materials;
            document.getElementById('weatherAlerts').textContent = data.weather_alerts;
            
            // Update crops status text
            const cropsStatus = document.getElementById('cropsStatus');
            if (data.active_crops > 0) {
                cropsStatus.innerHTML = `<i class="fas fa-seedling"></i> ${data.active_crops} crops growing`;
                cropsStatus.className = 'text-success';
            } else {
                cropsStatus.innerHTML = `<i class="fas fa-info-circle"></i> No crops yet`;
                cropsStatus.className = 'text-info';
            }
        }

        function updateCropsList(crops) {
            const container = document.getElementById('cropsList');
            
            if (crops.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-seedling fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No crops added yet</p>
                        <a href="my-crops.php" class="btn btn-primary btn-sm">Add Your First Crop</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = crops.map(crop => `
                <div class="crop-item d-flex align-items-center mb-3 p-2 border rounded">
                    <div class="crop-icon me-3">
                        <i class="fas fa-seedling text-success fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${escapeHtml(crop.name)}</h6>
                        <small class="text-muted">Planted: ${formatDate(crop.planted_date)}</small>
                    </div>
                    <div class="text-end">
                        <span class="badge ${getStatusBadge(crop.status)}">${crop.status}</span>
                        <small class="text-muted d-block">${crop.type}</small>
                    </div>
                </div>
            `).join('');
        }

        function updateMaterialsList(materials) {
            const container = document.getElementById('materialsList');
            
            if (materials.length === 0) {
                container.innerHTML = '<p class="text-muted text-center">No learning materials available</p>';
                return;
            }

            container.innerHTML = `
                <div class="row">
                    ${materials.map(material => `
                        <div class="col-md-4 mb-3">
                            <div class="material-card p-3 border rounded h-100">
                                <div class="d-flex align-items-start mb-2">
                                    <i class="${getFileTypeIcon(material.file_type)} fa-lg me-2"></i>
                                    <div>
                                        <h6 class="mb-1">${escapeHtml(material.title)}</h6>
                                        <small class="text-muted">${material.file_type.toUpperCase()} • ${formatDate(material.created_at)}</small>
                                    </div>
                                </div>
                                <p class="text-muted small mb-2">${escapeHtml(material.description || 'No description available')}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark">${material.file_type}</span>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewMaterial('${material.file_type}')">
                                        ${material.file_type === 'video' ? 'Watch' : 'Read'}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function getStatusBadge(status) {
            const statusClasses = {
                'seedling': 'bg-info',
                'growing': 'bg-success',
                'flowering': 'bg-warning',
                'harvesting': 'bg-primary'
            };
            return statusClasses[status] || 'bg-secondary';
        }

        function getFileTypeIcon(fileType) {
            const icons = {
                'pdf': 'fas fa-file-pdf text-danger',
                'video': 'fas fa-film text-primary',
                'article': 'fas fa-newspaper text-success'
            };
            return icons[fileType] || 'fas fa-file text-secondary';
        }

        function viewMaterial(fileType) {
            Swal.fire({
                title: 'Opening Material',
                text: `Opening ${fileType} material...`,
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            });
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
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

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 3000
            });
        }
    </script>

    <style>
        .welcome-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border: none;
            border-left: 4px solid #4e73df;
        }

        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid #e3e6f0;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .weather-widget {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .crop-item {
            transition: background-color 0.2s ease;
        }

        .crop-item:hover {
            background-color: #f8f9fa;
        }

        .material-card {
            transition: all 0.3s ease;
        }

        .material-card:hover {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
    </style>
</body>
</html>