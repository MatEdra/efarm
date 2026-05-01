<?php
include_once 'include/auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Forecast - Smart Farming Philippines</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style.css">
    <style>
        .weather-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .weather-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .weather-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }
        .temperature {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .weather-condition {
            font-size: 1.2rem;
            color: #6c757d;
        }
        .weather-stats {
            border-left: 3px solid #e9ecef;
            padding-left: 1rem;
        }
        .location-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .forecast-day {
            text-align: center;
            padding: 1rem;
            border-radius: 10px;
            background: #f8f9fa;
        }
        .weather-badge {
            font-size: 0.8rem;
            padding: 0.3rem 0.6rem;
        }
        .aqi-excellent { background-color: #28a745; }
        .aqi-good { background-color: #20c997; }
        .aqi-moderate { background-color: #ffc107; }
        .aqi-poor { background-color: #fd7e14; }
        .aqi-very-poor { background-color: #dc3545; }
    </style>
</head>

<body>
    <?php
    include("sidebar.php");
    ?>

    <!-- Main Content -->
    <div class="main-content">
        <?php
        include("top_nav.php");
        ?>
        <div id="content">
            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading weather data...</p>
            </div>

            <!-- Weather Content -->
            <div id="weatherContent" style="display: none;">
                <!-- Page Header -->
                <div class="container-fluid py-4">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card location-header">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h1 class="h3 mb-1" id="locationTitle">Weather Forecast</h1>
                                            <p class="mb-0" id="lastUpdated">Loading...</p>
                                        </div>
                                        <div class="col-auto">
                                            <div class="input-group" style="max-width: 300px;">
                                                <input type="text" id="locationInput" class="form-control" placeholder="Enter location...">
                                                <button class="btn btn-light" type="button" id="searchLocationBtn">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Current Weather -->
                    <div class="row mb-4">
                        <div class="col-lg-8 mb-4">
                            <div class="card weather-card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-sun me-2"></i>Current Weather
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 text-center">
                                            <div class="weather-icon text-warning" id="currentWeatherIcon">
                                                <i class="fas fa-sun"></i>
                                            </div>
                                            <div class="temperature" id="currentTemperature">--°C</div>
                                            <div class="weather-condition" id="currentCondition">Loading...</div>
                                            <div class="mt-2" id="currentFeelsLike">Feels like --°C</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="weather-stats">
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <small class="text-muted">Humidity</small>
                                                        <div class="fw-bold" id="currentHumidity">--%</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Wind Speed</small>
                                                        <div class="fw-bold" id="currentWind">-- km/h</div>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <small class="text-muted">Precipitation</small>
                                                        <div class="fw-bold" id="currentPrecipitation">-- mm</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Pressure</small>
                                                        <div class="fw-bold" id="currentPressure">-- hPa</div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <small class="text-muted">UV Index</small>
                                                        <div class="fw-bold" id="currentUV">--</div>
                                                    </div>
                                                    <div class="col-6">
                                                        <small class="text-muted">Visibility</small>
                                                        <div class="fw-bold" id="currentVisibility">-- km</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-4">
                            <div class="card weather-card h-100">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-wind me-2"></i>Air Quality
                                    </h5>
                                </div>
                                <div class="card-body text-center">
                                    <div class="aqi-value mb-3">
                                        <div class="h1 fw-bold" id="aqiValue">--</div>
                                        <div class="badge" id="aqiCategory">Loading</div>
                                    </div>
                                    <div class="aqi-details">
                                        <div class="row text-start">
                                            <div class="col-6 mb-2">
                                                <small>PM2.5</small>
                                                <div class="fw-bold" id="pm25">-- μg/m³</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small>PM10</small>
                                                <div class="fw-bold" id="pm10">-- μg/m³</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small>O₃</small>
                                                <div class="fw-bold" id="o3">--</div>
                                            </div>
                                            <div class="col-6 mb-2">
                                                <small>NO₂</small>
                                                <div class="fw-bold" id="no2">--</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 7-Day Forecast -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card weather-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>7-Day Forecast
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row" id="weeklyForecast">
                                        <!-- Forecast days will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Weather Charts -->
                    <div class="row">
                        <div class="col-lg-6 mb-4">
                            <div class="card weather-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-chart-line me-2"></i>Temperature Trends
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="temperatureChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mb-4">
                            <div class="card weather-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-tint me-2"></i>Precipitation & Humidity
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="precipitationChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Farming Recommendations -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card weather-card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-tractor me-2"></i>Farming Recommendations
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div id="farmingRecommendations">
                                        <!-- Recommendations will be loaded here -->
                                    </div>
                                </div>
                            </div>
                        </div>
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
    <script src="javascript.js"></script>

    <script>
        let weatherData = null;
        let temperatureChart = null;
        let precipitationChart = null;

        // Load weather data when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadWeatherData();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Location search
            document.getElementById('searchLocationBtn').addEventListener('click', searchLocation);
            document.getElementById('locationInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchLocation();
                }
            });
        }

        function searchLocation() {
            const location = document.getElementById('locationInput').value.trim();
            if (location) {
                loadWeatherData(location);
            }
        }

        // Function to load weather data using fetch
        async function loadWeatherData(location = '') {
            try {
                showLoading();
                
                const params = new URLSearchParams();
                if (location) {
                    params.append('location', location);
                }

                const response = await fetch('function/get_weather.php?' + params.toString());
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();

                if (data.success) {
                    weatherData = data.data;
                    displayWeatherData();
                    updateCharts();
                    generateRecommendations();
                    hideLoading();
                } else {
                    throw new Error(data.message || 'Failed to load weather data');
                }
            } catch (error) {
                console.error('Error loading weather:', error);
                showError('Failed to load weather data: ' + error.message);
            }
        }

        function displayWeatherData() {
            const current = weatherData.current;
            const location = weatherData.location;

            // Update location header
            document.getElementById('locationTitle').textContent = `Weather in ${location.name}`;
            document.getElementById('lastUpdated').textContent = `Last updated: ${new Date(current.last_updated).toLocaleString()}`;

            // Update current weather
            document.getElementById('currentTemperature').textContent = `${current.temp_c}°C`;
            document.getElementById('currentCondition').textContent = current.condition.text;
            document.getElementById('currentFeelsLike').textContent = `Feels like ${current.feelslike_c}°C`;
            document.getElementById('currentHumidity').textContent = `${current.humidity}%`;
            document.getElementById('currentWind').textContent = `${current.wind_kph} km/h`;
            document.getElementById('currentPrecipitation').textContent = `${current.precip_mm} mm`;
            document.getElementById('currentPressure').textContent = `${current.pressure_mb} hPa`;
            document.getElementById('currentUV').textContent = current.uv;
            document.getElementById('currentVisibility').textContent = `${current.vis_km} km`;

            // Update weather icon
            updateWeatherIcon('currentWeatherIcon', current.condition.code);

            // Update air quality
            if (weatherData.air_quality) {
                const aq = weatherData.air_quality;
                document.getElementById('aqiValue').textContent = aq.aqi;
                document.getElementById('pm25').textContent = `${aq.pm2_5.toFixed(1)} μg/m³`;
                document.getElementById('pm10').textContent = `${aq.pm10.toFixed(1)} μg/m³`;
                document.getElementById('o3').textContent = `${aq.o3.toFixed(1)}`;
                document.getElementById('no2').textContent = `${aq.no2.toFixed(1)}`;

                // Update AQI category
                const aqiCategory = getAQICategory(aq.aqi);
                const aqiBadge = document.getElementById('aqiCategory');
                aqiBadge.textContent = aqiCategory.text;
                aqiBadge.className = `badge aqi-${aqiCategory.class}`;
            }

            // Update weekly forecast
            displayWeeklyForecast();
        }

        function updateWeatherIcon(elementId, conditionCode) {
            const iconElement = document.getElementById(elementId);
            const iconClass = getWeatherIcon(conditionCode);
            iconElement.innerHTML = `<i class="fas ${iconClass}"></i>`;
        }

        function getWeatherIcon(conditionCode) {
            const icons = {
                1000: 'fa-sun', // Sunny
                1003: 'fa-cloud-sun', // Partly cloudy
                1006: 'fa-cloud', // Cloudy
                1009: 'fa-cloud', // Overcast
                1030: 'fa-smog', // Mist
                1063: 'fa-cloud-rain', // Patchy rain
                1066: 'fa-snowflake', // Patchy snow
                1069: 'fa-cloud-meatball', // Sleet
                1072: 'fa-cloud-rain', // Freezing drizzle
                1087: 'fa-bolt', // Thundery outbreaks
                1114: 'fa-wind', // Blowing snow
                1117: 'fa-wind', // Blizzard
                1135: 'fa-smog', // Fog
                1147: 'fa-smog', // Freezing fog
                1150: 'fa-cloud-rain', // Patchy light drizzle
                1153: 'fa-cloud-rain', // Light drizzle
                1168: 'fa-cloud-rain', // Freezing drizzle
                1171: 'fa-cloud-rain', // Heavy freezing drizzle
                1180: 'fa-cloud-rain', // Patchy light rain
                1183: 'fa-cloud-rain', // Light rain
                1186: 'fa-cloud-rain', // Moderate rain
                1189: 'fa-cloud-rain', // Heavy rain
                1192: 'fa-cloud-showers-heavy', // Heavy rain
                1195: 'fa-cloud-showers-heavy', // Heavy rain
                1198: 'fa-cloud-rain', // Light freezing rain
                1201: 'fa-cloud-rain', // Moderate/heavy freezing rain
                1204: 'fa-cloud-meatball', // Light sleet
                1207: 'fa-cloud-meatball', // Moderate/heavy sleet
                1210: 'fa-snowflake', // Patchy light snow
                1213: 'fa-snowflake', // Light snow
                1216: 'fa-snowflake', // Patchy moderate snow
                1219: 'fa-snowflake', // Moderate snow
                1222: 'fa-snowflake', // Patchy heavy snow
                1225: 'fa-snowflake', // Heavy snow
                1237: 'fa-cloud-meatball', // Ice pellets
                1240: 'fa-cloud-rain', // Light rain shower
                1243: 'fa-cloud-showers-heavy', // Moderate/heavy rain shower
                1246: 'fa-cloud-showers-heavy', // Torrential rain shower
                1249: 'fa-cloud-meatball', // Light sleet showers
                1252: 'fa-cloud-meatball', // Moderate/heavy sleet showers
                1255: 'fa-snowflake', // Light snow showers
                1258: 'fa-snowflake', // Moderate/heavy snow showers
                1261: 'fa-cloud-meatball', // Light showers of ice pellets
                1264: 'fa-cloud-meatball', // Moderate/heavy showers of ice pellets
                1273: 'fa-bolt', // Patchy light rain with thunder
                1276: 'fa-bolt', // Moderate/heavy rain with thunder
                1279: 'fa-bolt', // Patchy light snow with thunder
                1282: 'fa-bolt', // Moderate/heavy snow with thunder
            };
            return icons[conditionCode] || 'fa-cloud';
        }

        function getAQICategory(aqi) {
            if (aqi <= 50) return { text: 'Excellent', class: 'excellent' };
            if (aqi <= 100) return { text: 'Good', class: 'good' };
            if (aqi <= 150) return { text: 'Moderate', class: 'moderate' };
            if (aqi <= 200) return { text: 'Poor', class: 'poor' };
            return { text: 'Very Poor', class: 'very-poor' };
        }

        function displayWeeklyForecast() {
            const forecastContainer = document.getElementById('weeklyForecast');
            const forecastDays = weatherData.forecast.forecastday;

            forecastContainer.innerHTML = forecastDays.map(day => {
                const date = new Date(day.date);
                const dayName = date.toLocaleDateString('en-US', { weekday: 'short' });
                
                return `
                    <div class="col">
                        <div class="forecast-day">
                            <div class="fw-bold mb-2">${dayName}</div>
                            <div class="mb-2">
                                <i class="fas ${getWeatherIcon(day.day.condition.code)} text-warning"></i>
                            </div>
                            <div class="fw-bold text-primary">${Math.round(day.day.maxtemp_c)}°</div>
                            <div class="text-muted small">${Math.round(day.day.mintemp_c)}°</div>
                            <div class="mt-2 small">
                                <i class="fas fa-tint me-1 text-info"></i>${day.day.daily_chance_of_rain}%
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function updateCharts() {
            updateTemperatureChart();
            updatePrecipitationChart();
        }

        function updateTemperatureChart() {
            const ctx = document.getElementById('temperatureChart').getContext('2d');
            const forecastDays = weatherData.forecast.forecastday;

            if (temperatureChart) {
                temperatureChart.destroy();
            }

            const labels = forecastDays.map(day => 
                new Date(day.date).toLocaleDateString('en-US', { weekday: 'short' })
            );
            const maxTemps = forecastDays.map(day => day.day.maxtemp_c);
            const minTemps = forecastDays.map(day => day.day.mintemp_c);

            temperatureChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Max Temperature',
                            data: maxTemps,
                            borderColor: '#e74a3b',
                            backgroundColor: 'rgba(231, 74, 59, 0.1)',
                            tension: 0.4,
                            fill: false
                        },
                        {
                            label: 'Min Temperature',
                            data: minTemps,
                            borderColor: '#36b9cc',
                            backgroundColor: 'rgba(54, 185, 204, 0.1)',
                            tension: 0.4,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: '7-Day Temperature Forecast (°C)'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false
                        }
                    }
                }
            });
        }

        function updatePrecipitationChart() {
            const ctx = document.getElementById('precipitationChart').getContext('2d');
            const forecastDays = weatherData.forecast.forecastday;

            if (precipitationChart) {
                precipitationChart.destroy();
            }

            const labels = forecastDays.map(day => 
                new Date(day.date).toLocaleDateString('en-US', { weekday: 'short' })
            );
            const rainChance = forecastDays.map(day => day.day.daily_chance_of_rain);
            const humidity = forecastDays.map(day => day.day.avghumidity);

            precipitationChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Rain Chance (%)',
                            data: rainChance,
                            backgroundColor: 'rgba(54, 162, 235, 0.6)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Humidity (%)',
                            data: humidity,
                            type: 'line',
                            borderColor: '#1cc88a',
                            backgroundColor: 'rgba(28, 200, 138, 0.1)',
                            tension: 0.4,
                            fill: false
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Precipitation & Humidity Forecast'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        function generateRecommendations() {
            const current = weatherData.current;
            const container = document.getElementById('farmingRecommendations');
            
            let recommendations = [];

            // Temperature-based recommendations
            if (current.temp_c > 35) {
                recommendations.push('High temperature alert! Consider shading sensitive crops and increase irrigation frequency.');
            } else if (current.temp_c < 10) {
                recommendations.push('Low temperature warning! Protect frost-sensitive plants and consider using row covers.');
            }

            // Rain-based recommendations
            if (current.precip_mm > 20) {
                recommendations.push('Heavy rainfall expected. Ensure proper drainage and postpone field activities.');
            } else if (current.precip_mm < 1 && current.humidity < 40) {
                recommendations.push('Dry conditions. Ideal for harvesting and field preparation.');
            }

            // Wind-based recommendations
            if (current.wind_kph > 25) {
                recommendations.push('Strong winds forecasted. Secure farm structures and postpone spraying activities.');
            }

            // UV-based recommendations
            if (current.uv > 8) {
                recommendations.push('High UV index. Protect yourself with proper clothing and schedule fieldwork for early morning/late afternoon.');
            }

            // General recommendations
            if (recommendations.length === 0) {
                recommendations.push('Favorable weather conditions for most farming activities.');
            }

            container.innerHTML = recommendations.map(rec => `
                <div class="alert alert-info mb-2">
                    <i class="fas fa-info-circle me-2"></i>${rec}
                </div>
            `).join('');
        }

        function showLoading() {
            document.getElementById('loadingSpinner').style.display = 'block';
            document.getElementById('weatherContent').style.display = 'none';
        }

        function hideLoading() {
            document.getElementById('loadingSpinner').style.display = 'none';
            document.getElementById('weatherContent').style.display = 'block';
        }

        function showError(message) {
            document.getElementById('loadingSpinner').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    ${message}
                </div>
                <button class="btn btn-primary mt-2" onclick="loadWeatherData()">
                    <i class="fas fa-redo me-2"></i>Retry
                </button>
            `;
        }

        // Auto-refresh every 30 minutes
        setInterval(() => {
            loadWeatherData();
        }, 30 * 60 * 1000);
    </script>
</body>

</html>