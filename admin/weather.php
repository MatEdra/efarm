<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast - Isabela Area - Smart Farming Philippines</title>
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

        <!-- Weather Forecast Content -->
        <div id="weather">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>7-Day Weather Forecast - Isabela Area</h4>
                <button class="btn btn-success" onclick="fetchWeatherForecast()">
                    <i class="fas fa-sync-alt me-2"></i>Refresh Forecast
                </button>
            </div>

            <!-- Location Info -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="card-title">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                Isabela Province, Philippines
                            </h5>
                            <p class="card-text text-muted mb-0">
                                Coordinates: 16.75°N, 121.75°E | Agricultural Region
                            </p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <div class="text-muted small">Last Updated</div>
                                    <div class="fw-bold" id="lastUpdated">-</div>
                                </div>
                                <div>
                                    <div class="text-muted small">Data Source</div>
                                    <div class="fw-bold">Open-Meteo API</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Current Weather -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-thermometer-half fa-3x"></i>
                            </div>
                            <h3 id="currentTemp">-</h3>
                            <p class="mb-0">Current Temperature</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-tint fa-3x"></i>
                            </div>
                            <h3 id="currentRain">-</h3>
                            <p class="mb-0">Rainfall (Today)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-wind fa-3x"></i>
                            </div>
                            <h3 id="currentWind">-</h3>
                            <p class="mb-0">Wind Speed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7-Day Forecast Cards -->
            <div class="row mb-4" id="forecastCards">
                <!-- Forecast cards will be loaded here -->
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading forecast...</span>
                    </div>
                    <div class="mt-2">Loading 7-day forecast for Isabela...</div>
                </div>
            </div>

            <!-- Weather Charts -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">7-Day Temperature Forecast</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="temperatureChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Daily Rainfall Prediction</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="rainfallChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Farming Recommendations -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-seedling me-2 text-success"></i>
                        Farming Recommendations for Isabela
                    </h5>
                </div>
                <div class="card-body">
                    <div id="farmingRecommendations">
                        <div class="text-center py-3">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="visually-hidden">Loading recommendations...</span>
                            </div>
                            <div class="mt-2">Analyzing weather data for farming advice...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Forecast Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Detailed 7-Day Forecast</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportData('csv')"><i class="fas fa-file-csv me-2"></i>CSV</a></li>
                            <li><a class="dropdown-item" href="#" onclick="exportData('json')"><i class="fas fa-file-code me-2"></i>JSON</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Day</th>
                                    <th>Condition</th>
                                    <th>Max Temp</th>
                                    <th>Min Temp</th>
                                    <th>Rainfall</th>
                                    <th>Humidity</th>
                                    <th>Wind Speed</th>
                                    <th>UV Index</th>
                                </tr>
                            </thead>
                            <tbody id="forecastTableBody">
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading forecast data...</span>
                                        </div>
                                        <div class="mt-2">Loading forecast data for Isabela...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    include("nav_mobile.php");
    ?>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Open-Meteo API configuration for Isabela
        const ISABELA_LATITUDE = 16.75;
        const ISABELA_LONGITUDE = 121.75;
        const OPEN_METEO_URL = 'https://api.open-meteo.com/v1/forecast';
        
        let temperatureChart = null;
        let rainfallChart = null;
        let currentWeatherData = null;

        // Load weather forecast data from Open-Meteo
        async function loadWeatherData() {
            try {
                showLoading();
                
                // Fetch 7-day forecast from Open-Meteo
                const response = await fetch(`${OPEN_METEO_URL}?latitude=${ISABELA_LATITUDE}&longitude=${ISABELA_LONGITUDE}&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,weathercode,windspeed_10m_max,uv_index_max&timezone=Asia%2FManila&forecast_days=7`);
                const data = await response.json();

                if (data && data.daily) {
                    currentWeatherData = data;
                    processWeatherData(data);
                    updateCurrentWeather(data);
                    updateForecastInfo();
                } else {
                    throw new Error('Invalid data received from weather API');
                }
            } catch (error) {
                console.error('Error loading weather forecast:', error);
                showError('Failed to load weather forecast data from Open-Meteo API');
            }
        }

        // Process Open-Meteo data
        function processWeatherData(data) {
            const forecast = [];
            const daily = data.daily;
            
            for (let i = 0; i < daily.time.length; i++) {
                forecast.push({
                    date: daily.time[i],
                    temperature_max: Math.round(daily.temperature_2m_max[i]),
                    temperature_min: Math.round(daily.temperature_2m_min[i]),
                    precipitation: daily.precipitation_sum[i] ? daily.precipitation_sum[i].toFixed(1) : 0,
                    weathercode: daily.weathercode[i],
                    condition: getWeatherCondition(daily.weathercode[i]),
                    wind_speed: Math.round(daily.windspeed_10m_max[i]),
                    uv_index: daily.uv_index_max[i] ? daily.uv_index_max[i].toFixed(1) : 0,
                    humidity: 75 + Math.random() * 20 // Estimated humidity
                });
            }
            
            displayForecastCards(forecast);
            displayForecastTable(forecast);
            updateCharts(forecast);
            updateFarmingRecommendations(forecast);
        }

        // Update current weather display
        function updateCurrentWeather(data) {
            const today = data.daily.time[0];
            const todayIndex = data.daily.time.indexOf(today);
            
            document.getElementById('currentTemp').textContent = 
                `${Math.round((data.daily.temperature_2m_max[todayIndex] + data.daily.temperature_2m_min[todayIndex]) / 2)}°C`;
            document.getElementById('currentRain').textContent = 
                `${data.daily.precipitation_sum[todayIndex] ? data.daily.precipitation_sum[todayIndex].toFixed(1) : 0}mm`;
            document.getElementById('currentWind').textContent = 
                `${Math.round(data.daily.windspeed_10m_max[todayIndex])} km/h`;
        }

        // Display 7-day forecast cards
        function displayForecastCards(forecast) {
            const forecastCards = document.getElementById('forecastCards');
            
            forecastCards.innerHTML = forecast.map(day => `
                <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
                    <div class="card forecast-card h-100 ${getForecastCardClass(day.condition)}">
                        <div class="card-body text-center">
                            <h6 class="card-title mb-2">${formatDay(day.date)}</h6>
                            <div class="small text-muted mb-2">${formatDate(day.date)}</div>
                            
                            <div class="weather-icon mb-3">
                                <i class="${getConditionIcon(day.condition)} fa-2x ${getConditionColor(day.condition)}"></i>
                            </div>
                            
                            <div class="temperature mb-2">
                                <span class="h5">${day.temperature_max}°</span>
                                <span class="text-muted">/${day.temperature_min}°</span>
                            </div>
                            
                            <div class="condition mb-2">
                                <small class="fw-bold">${day.condition}</small>
                            </div>
                            
                            <div class="weather-details">
                                <div class="row small text-muted">
                                    <div class="col-6">
                                        <i class="fas fa-tint me-1"></i>
                                        ${day.precipitation}mm
                                    </div>
                                    <div class="col-6">
                                        <i class="fas fa-wind me-1"></i>
                                        ${day.wind_speed}km/h
                                    </div>
                                </div>
                                <div class="row small text-muted mt-1">
                                    <div class="col-12">
                                        <i class="fas fa-sun me-1"></i>
                                        UV: ${day.uv_index}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Display forecast in table
        function displayForecastTable(forecast) {
            const tableBody = document.getElementById('forecastTableBody');

            tableBody.innerHTML = forecast.map(day => `
                <tr>
                    <td>${formatDate(day.date)}</td>
                    <td>${formatDay(day.date)}</td>
                    <td>
                        <span class="weather-condition ${getConditionClass(day.condition)}">
                            <i class="${getConditionIcon(day.condition)} me-1"></i>
                            ${escapeHtml(day.condition)}
                        </span>
                    </td>
                    <td><strong>${day.temperature_max}°C</strong></td>
                    <td>${day.temperature_min}°C</td>
                    <td>${day.precipitation}mm</td>
                    <td>${Math.round(day.humidity)}%</td>
                    <td>${day.wind_speed} km/h</td>
                    <td>
                        <span class="badge ${getUVIndexClass(day.uv_index)}">${day.uv_index}</span>
                    </td>
                </tr>
            `).join('');
        }

        // Update charts
        function updateCharts(forecast) {
            updateTemperatureChart(forecast);
            updateRainfallChart(forecast);
        }

        // Update temperature chart
        function updateTemperatureChart(forecast) {
            const ctx = document.getElementById('temperatureChart').getContext('2d');
            
            if (temperatureChart) {
                temperatureChart.destroy();
            }

            const labels = forecast.map(day => formatDayShort(day.date));
            const maxTemps = forecast.map(day => day.temperature_max);
            const minTemps = forecast.map(day => day.temperature_min);

            temperatureChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Max Temperature (°C)',
                            data: maxTemps,
                            borderColor: '#ff6b6b',
                            backgroundColor: 'rgba(255, 107, 107, 0.1)',
                            tension: 0.4,
                            fill: true
                        },
                        {
                            label: 'Min Temperature (°C)',
                            data: minTemps,
                            borderColor: '#4ecdc4',
                            backgroundColor: 'rgba(78, 205, 196, 0.1)',
                            tension: 0.4,
                            fill: true
                        }
                    ]
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
                            beginAtZero: false,
                            title: {
                                display: true,
                                text: 'Temperature (°C)'
                            }
                        }
                    }
                }
            });
        }

        // Update rainfall chart
        function updateRainfallChart(forecast) {
            const ctx = document.getElementById('rainfallChart').getContext('2d');
            
            if (rainfallChart) {
                rainfallChart.destroy();
            }

            const labels = forecast.map(day => formatDayShort(day.date));
            const rainfall = forecast.map(day => parseFloat(day.precipitation));

            rainfallChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Rainfall (mm)',
                        data: rainfall,
                        backgroundColor: rainfall.map(value => 
                            value > 10 ? '#4d96ff' : 
                            value > 5 ? '#6bcf7f' : 
                            value > 0 ? '#ffd93d' : '#e9ecef'
                        ),
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
                                text: 'Rainfall (mm)'
                            }
                        }
                    }
                }
            });
        }

        // Update farming recommendations
        function updateFarmingRecommendations(forecast) {
            const recommendationsContainer = document.getElementById('farmingRecommendations');
            const recommendations = generateFarmingRecommendations(forecast);
            
            recommendationsContainer.innerHTML = `
                <div class="row">
                    ${recommendations.map(rec => `
                        <div class="col-md-6 mb-3">
                            <div class="card border-${rec.type}">
                                <div class="card-body">
                                    <h6 class="card-title text-${rec.type}">
                                        <i class="${rec.icon} me-2"></i>
                                        ${rec.title}
                                    </h6>
                                    <p class="card-text small">${rec.description}</p>
                                    ${rec.actions ? `
                                        <div class="mt-2">
                                            <strong>Recommended Actions:</strong>
                                            <ul class="small mb-0 mt-1">
                                                ${rec.actions.map(action => `<li>${action}</li>`).join('')}
                                            </ul>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        // Generate farming recommendations based on forecast
        function generateFarmingRecommendations(forecast) {
            const recommendations = [];
            
            // Analyze rainfall patterns for Isabela
            const totalRainfall = forecast.reduce((sum, day) => sum + parseFloat(day.precipitation), 0);
            const rainyDays = forecast.filter(day => parseFloat(day.precipitation) > 5).length;
            const heavyRainDays = forecast.filter(day => parseFloat(day.precipitation) > 20).length;
            
            // Rainfall recommendations
            if (heavyRainDays > 0) {
                recommendations.push({
                    title: 'Heavy Rainfall Alert',
                    type: 'danger',
                    icon: 'fas fa-cloud-showers-heavy',
                    description: `${heavyRainDays} day(s) of heavy rainfall expected. Risk of flooding and soil erosion.`,
                    actions: [
                        'Clear drainage canals in rice fields',
                        'Secure farm equipment and supplies',
                        'Harvest mature crops before heavy rain',
                        'Avoid fertilizer application before rain'
                    ]
                });
            } else if (totalRainfall > 30) {
                recommendations.push({
                    title: 'Moderate Rainfall Expected',
                    type: 'warning',
                    icon: 'fas fa-cloud-rain',
                    description: `Total ${totalRainfall.toFixed(1)}mm rainfall expected over 7 days. Good for irrigation but monitor drainage.`,
                    actions: [
                        'Good time for transplanting rice seedlings',
                        'Monitor corn fields for waterlogging',
                        'Schedule planting of vegetables',
                        'Check irrigation systems'
                    ]
                });
            } else if (totalRainfall < 5) {
                recommendations.push({
                    title: 'Dry Conditions',
                    type: 'info',
                    icon: 'fas fa-sun',
                    description: 'Low rainfall expected. Irrigation needed for crops.',
                    actions: [
                        'Activate irrigation systems for rice fields',
                        'Water corn fields in early morning',
                        'Monitor soil moisture in vegetable gardens',
                        'Consider drought-resistant crop varieties'
                    ]
                });
            }

            // Temperature analysis
            const avgMaxTemp = forecast.reduce((sum, day) => sum + day.temperature_max, 0) / forecast.length;
            const highTempDays = forecast.filter(day => day.temperature_max > 35).length;
            
            if (highTempDays > 0) {
                recommendations.push({
                    title: 'High Temperature Alert',
                    type: 'danger',
                    icon: 'fas fa-temperature-high',
                    description: `${highTempDays} day(s) with temperatures above 35°C. Heat stress risk for crops.`,
                    actions: [
                        'Increase irrigation frequency for rice',
                        'Provide shade for vegetable seedlings',
                        'Water during early morning hours',
                        'Monitor corn for heat stress symptoms'
                    ]
                });
            }

            // Isabela-specific crop recommendations
            recommendations.push({
                title: 'Isabela Crop Planning',
                type: 'success',
                icon: 'fas fa-tractor',
                description: 'Optimal planting schedule for Isabela main crops.',
                actions: [
                    'Rice: Good conditions for transplanting',
                    'Corn: Monitor soil moisture for planting',
                    'Vegetables: Ideal for leafy vegetables',
                    'Fruits: Mango and citrus care needed'
                ]
            });

            // General farming advice for Isabela
            recommendations.push({
                title: 'Regional Farming Tips',
                type: 'primary',
                icon: 'fas fa-map-marked-alt',
                description: 'Specific advice for Cagayan Valley region.',
                actions: [
                    'Prepare for possible Northeast monsoon effects',
                    'Monitor river levels for flood warnings',
                    'Check weather updates regularly',
                    'Coordinate with local agriculture office'
                ]
            });

            return recommendations;
        }

        // Update forecast information
        function updateForecastInfo() {
            document.getElementById('lastUpdated').textContent = new Date().toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Fetch weather forecast from Open-Meteo
        async function fetchWeatherForecast() {
            try {
                Swal.fire({
                    title: 'Updating forecast...',
                    text: 'Fetching latest weather data for Isabela',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                await loadWeatherData();

                Swal.fire({
                    icon: 'success',
                    title: 'Forecast Updated!',
                    text: 'Latest weather data for Isabela has been loaded',
                    timer: 2000
                });
            } catch (error) {
                console.error('Error fetching weather forecast:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: 'Could not fetch latest weather data'
                });
            }
        }

        // Export data
        async function exportData(format) {
            try {
                if (!currentWeatherData) {
                    showError('No data available to export');
                    return;
                }

                let content, mimeType, filename;

                if (format === 'csv') {
                    content = generateCSV(currentWeatherData);
                    mimeType = 'text/csv';
                    filename = `isabela_weather_forecast_${new Date().toISOString().split('T')[0]}.csv`;
                } else {
                    content = JSON.stringify(currentWeatherData, null, 2);
                    mimeType = 'application/json';
                    filename = `isabela_weather_forecast_${new Date().toISOString().split('T')[0]}.json`;
                }

                const blob = new Blob([content], { type: mimeType });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

                Swal.fire({
                    icon: 'success',
                    title: 'Export Successful!',
                    text: `Weather data exported as ${format.toUpperCase()}`,
                    timer: 2000
                });
            } catch (error) {
                console.error('Error exporting data:', error);
                showError('Failed to export data');
            }
        }

        // Generate CSV from weather data
        function generateCSV(data) {
            const headers = ['Date', 'Max Temp (°C)', 'Min Temp (°C)', 'Rainfall (mm)', 'Condition', 'Wind Speed (km/h)', 'UV Index'];
            let csv = headers.join(',') + '\n';
            
            data.daily.time.forEach((date, index) => {
                const row = [
                    date,
                    data.daily.temperature_2m_max[index],
                    data.daily.temperature_2m_min[index],
                    data.daily.precipitation_sum[index] || 0,
                    getWeatherCondition(data.daily.weathercode[index]),
                    data.daily.windspeed_10m_max[index],
                    data.daily.uv_index_max[index] || 0
                ];
                csv += row.join(',') + '\n';
            });
            
            return csv;
        }

        // Utility functions
        function showLoading() {
            document.getElementById('forecastCards').innerHTML = `
                <div class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading forecast...</span>
                    </div>
                    <div class="mt-2">Loading 7-day forecast for Isabela...</div>
                </div>
            `;
            document.getElementById('forecastTableBody').innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading forecast data...</span>
                        </div>
                        <div class="mt-2">Loading forecast data for Isabela...</div>
                    </td>
                </tr>
            `;
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

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function formatDay(dateString) {
            const date = new Date(dateString);
            const today = new Date();
            const tomorrow = new Date();
            tomorrow.setDate(today.getDate() + 1);

            if (date.toDateString() === today.toDateString()) {
                return 'Today';
            } else if (date.toDateString() === tomorrow.toDateString()) {
                return 'Tomorrow';
            } else {
                return date.toLocaleDateString('en-US', { weekday: 'long' });
            }
        }

        function formatDayShort(dateString) {
            const date = new Date(dateString);
            const today = new Date();
            const tomorrow = new Date();
            tomorrow.setDate(today.getDate() + 1);

            if (date.toDateString() === today.toDateString()) {
                return 'Today';
            } else if (date.toDateString() === tomorrow.toDateString()) {
                return 'Tom';
            } else {
                return date.toLocaleDateString('en-US', { weekday: 'short' });
            }
        }

        // Open-Meteo weather code to condition mapping
        function getWeatherCondition(weathercode) {
            const conditions = {
                0: 'Clear sky',
                1: 'Mainly clear',
                2: 'Partly cloudy',
                3: 'Overcast',
                45: 'Foggy',
                48: 'Foggy',
                51: 'Light drizzle',
                53: 'Moderate drizzle',
                55: 'Dense drizzle',
                61: 'Slight rain',
                63: 'Moderate rain',
                65: 'Heavy rain',
                80: 'Rain showers',
                81: 'Rain showers',
                82: 'Violent rain showers',
                95: 'Thunderstorm',
                96: 'Thunderstorm',
                99: 'Thunderstorm'
            };
            return conditions[weathercode] || 'Unknown';
        }

        function getConditionClass(condition) {
            if (condition.includes('rain') || condition.includes('drizzle') || condition.includes('shower')) {
                return 'text-primary';
            } else if (condition.includes('thunder') || condition.includes('storm')) {
                return 'text-danger';
            } else if (condition.includes('cloud')) {
                return 'text-secondary';
            } else if (condition.includes('fog')) {
                return 'text-muted';
            } else {
                return 'text-warning';
            }
        }

        function getConditionColor(condition) {
            if (condition.includes('rain') || condition.includes('drizzle') || condition.includes('shower')) {
                return 'text-primary';
            } else if (condition.includes('thunder') || condition.includes('storm')) {
                return 'text-danger';
            } else if (condition.includes('cloud')) {
                return 'text-secondary';
            } else if (condition.includes('fog')) {
                return 'text-muted';
            } else {
                return 'text-warning';
            }
        }

        function getConditionIcon(condition) {
            if (condition.includes('rain') || condition.includes('drizzle') || condition.includes('shower')) {
                return 'fas fa-cloud-rain';
            } else if (condition.includes('thunder') || condition.includes('storm')) {
                return 'fas fa-bolt';
            } else if (condition.includes('cloud')) {
                return 'fas fa-cloud';
            } else if (condition.includes('fog')) {
                return 'fas fa-smog';
            } else {
                return 'fas fa-sun';
            }
        }

        function getForecastCardClass(condition) {
            if (condition.includes('rain') || condition.includes('drizzle') || condition.includes('shower')) {
                return 'border-primary';
            } else if (condition.includes('thunder') || condition.includes('storm')) {
                return 'border-danger';
            } else if (condition.includes('cloud')) {
                return 'border-secondary';
            } else if (condition.includes('fog')) {
                return 'border-muted';
            } else {
                return 'border-warning';
            }
        }

        function getUVIndexClass(uvIndex) {
            const uv = parseFloat(uvIndex);
            if (uv <= 2) return 'bg-success';
            if (uv <= 5) return 'bg-warning';
            if (uv <= 7) return 'bg-orange';
            if (uv <= 10) return 'bg-danger';
            return 'bg-purple';
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadWeatherData();
        });
    </script>

    <style>
        .forecast-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border: 2px solid #e9ecef;
        }

        .forecast-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .weather-condition {
            font-weight: 500;
        }

        .weather-icon {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bg-orange {
            background-color: #ff9800 !important;
        }

        .bg-purple {
            background-color: #9c27b0 !important;
        }

        @media (max-width: 768px) {
            .forecast-card {
                margin-bottom: 1rem;
            }
        }
    </style>
    <script src="javascript.js"></script>
</body>

</html>