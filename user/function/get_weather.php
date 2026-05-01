<?php
session_start();
header('Content-Type: application/json');
include_once '../../include/conn.php';

if (!isset($_SESSION['farmer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

// Get location from query parameter or use default
$location = $_GET['location'] ?? 'Manila';

try {
    // For demo purposes, we'll create mock weather data
    // In production, you would integrate with a weather API like OpenWeatherMap or WeatherAPI
    
    $mockWeatherData = generateMockWeatherData($location);
    
    echo json_encode([
        'success' => true,
        'data' => $mockWeatherData
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching weather data: ' . $e->getMessage()]);
}

function generateMockWeatherData($location) {
    // Generate realistic mock weather data
    $currentTime = time();
    
    // Base temperatures based on location (simplified)
    $baseTemp = 25; // Default temperature
    
    if (stripos($location, 'baguio') !== false) {
        $baseTemp = 18;
    } elseif (stripos($location, 'cebu') !== false) {
        $baseTemp = 28;
    } elseif (stripos($location, 'davao') !== false) {
        $baseTemp = 30;
    }
    
    // Current weather
    $current = [
        'last_updated' => date('c', $currentTime),
        'temp_c' => $baseTemp + rand(-3, 3),
        'feelslike_c' => $baseTemp + rand(-2, 4),
        'condition' => [
            'text' => getRandomCondition(),
            'code' => getRandomConditionCode()
        ],
        'wind_kph' => rand(5, 25),
        'wind_degree' => rand(0, 360),
        'pressure_mb' => rand(1000, 1020),
        'precip_mm' => rand(0, 5),
        'humidity' => rand(40, 90),
        'cloud' => rand(0, 100),
        'feelslike_c' => $baseTemp + rand(-2, 4),
        'vis_km' => rand(5, 20),
        'uv' => rand(1, 12)
    ];
    
    // Generate 7-day forecast
    $forecastDays = [];
    for ($i = 0; $i < 7; $i++) {
        $date = date('Y-m-d', strtotime("+$i days"));
        $dayTemp = $baseTemp + rand(-5, 5);
        
        $forecastDays[] = [
            'date' => $date,
            'day' => [
                'maxtemp_c' => $dayTemp + rand(2, 6),
                'mintemp_c' => $dayTemp - rand(2, 6),
                'avgtemp_c' => $dayTemp,
                'maxwind_kph' => rand(8, 30),
                'totalprecip_mm' => rand(0, 15),
                'avgvis_km' => rand(8, 15),
                'avghumidity' => rand(45, 85),
                'daily_chance_of_rain' => rand(0, 80),
                'daily_chance_of_snow' => 0,
                'condition' => [
                    'text' => getRandomCondition(),
                    'code' => getRandomConditionCode()
                ],
                'uv' => rand(3, 11)
            ]
        ];
    }
    
    // Air quality data
    $air_quality = [
        'aqi' => rand(20, 120),
        'pm2_5' => rand(5, 35),
        'pm10' => rand(10, 50),
        'o3' => rand(20, 80),
        'no2' => rand(5, 40),
        'so2' => rand(2, 15),
        'co' => rand(200, 800)
    ];
    
    return [
        'location' => [
            'name' => ucwords(strtolower($location)),
            'region' => 'Philippines',
            'country' => 'Philippines',
            'lat' => 14.5995 + (rand(-100, 100) / 1000),
            'lon' => 120.9842 + (rand(-100, 100) / 1000),
            'localtime' => date('c', $currentTime)
        ],
        'current' => $current,
        'forecast' => [
            'forecastday' => $forecastDays
        ],
        'air_quality' => $air_quality
    ];
}

function getRandomCondition() {
    $conditions = [
        'Sunny', 'Partly cloudy', 'Cloudy', 'Overcast', 
        'Light rain', 'Moderate rain', 'Heavy rain', 'Thunderstorm',
        'Mist', 'Fog', 'Light drizzle', 'Patchy rain'
    ];
    return $conditions[array_rand($conditions)];
}

function getRandomConditionCode() {
    $codes = [1000, 1003, 1006, 1009, 1030, 1063, 1066, 1087, 1135, 1150, 1180, 1273];
    return $codes[array_rand($codes)];
}

$conn->close();
?>