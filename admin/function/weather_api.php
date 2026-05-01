<?php
include_once '../../include/conn.php';
include_once '../include/auth.php';

header('Content-Type: application/json');

// Disable display errors to prevent HTML output
ini_set('display_errors', 0);

$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => ''];

// Check database connection
if ($conn->connect_error) {
    $response['error'] = 'Database connection failed: ' . $conn->connect_error;
    echo json_encode($response);
    exit();
}

try {
    switch ($method) {
        case 'GET':
            handleGetRequest();
            break;
        case 'POST':
            handlePostRequest();
            break;
        case 'DELETE':
            handleDeleteRequest();
            break;
        default:
            $response['error'] = 'Method not allowed';
            http_response_code(405);
            echo json_encode($response);
            break;
    }
} catch (Exception $e) {
    $response['error'] = 'Server error: ' . $e->getMessage();
    http_response_code(500);
    echo json_encode($response);
}

function handleGetRequest() {
    global $conn, $response;
    
    // Check if this is an export request
    if (isset($_GET['export'])) {
        handleExport();
        return;
    }
    
    if (isset($_GET['id'])) {
        // Get single weather record
        $weatherId = (int)$_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM weather_data WHERE id = ?");
        $stmt->bind_param("i", $weatherId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $weather = $result->fetch_assoc();
            $response['success'] = true;
            $response['data'] = $weather;
        } else {
            $response['error'] = 'Weather record not found';
            http_response_code(404);
        }
        
        $stmt->close();
    } else {
        // Get weather data with pagination and filters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Build WHERE clause for filters
        $whereConditions = [];
        $params = [];
        $types = '';
        
        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $whereConditions[] = "location = ?";
            $params[] = $_GET['location'];
            $types .= 's';
        }
        
        if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
            $whereConditions[] = "date >= ?";
            $params[] = $_GET['date_from'];
            $types .= 's';
        }
        
        if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
            $whereConditions[] = "date <= ?";
            $params[] = $_GET['date_to'];
            $types .= 's';
        }
        
        $whereClause = $whereConditions ? "WHERE " . implode(" AND ", $whereConditions) : "";
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM weather_data $whereClause";
        if ($params) {
            $countStmt = $conn->prepare($countSql);
            $countStmt->bind_param($types, ...$params);
            $countStmt->execute();
            $totalResult = $countStmt->get_result();
            $totalRows = $totalResult->fetch_assoc()['total'];
            $countStmt->close();
        } else {
            $totalResult = $conn->query($countSql);
            $totalRows = $totalResult->fetch_assoc()['total'];
        }
        
        $totalPages = ceil($totalRows / $limit);
        
        // Get weather data
        $sql = "SELECT * FROM weather_data $whereClause ORDER BY date DESC, location ASC LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($sql);
        
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $weather = $result->fetch_all(MYSQLI_ASSOC);
        
        // Get stats
        $stats = getWeatherStats($whereClause, $params, $types);
        
        // Get chart data
        $charts = getChartData();
        
        $response['success'] = true;
        $response['data'] = [
            'weather' => $weather,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_records' => $totalRows,
                'limit' => $limit
            ],
            'stats' => $stats,
            'charts' => $charts
        ];
        
        $stmt->close();
    }
    
    echo json_encode($response);
}

function handlePostRequest() {
    global $conn, $response;
    
    if (isset($_POST['weather_id'])) {
        // Update existing weather record
        handleUpdateWeather();
    } else {
        // Add new weather record
        handleAddWeather();
    }
}

function handleAddWeather() {
    global $conn, $response;
    
    $location = $_POST['location'] ?? '';
    $date = $_POST['date'] ?? '';
    $temperatureMax = $_POST['temperature_max'] ?? null;
    $temperatureMin = $_POST['temperature_min'] ?? null;
    $condition = $_POST['condition'] ?? null;
    $precipitation = $_POST['precipitation'] ?? null;
    $humidity = $_POST['humidity'] ?? null;
    
    if (empty($location) || empty($date)) {
        $response['error'] = 'Location and date are required';
        echo json_encode($response);
        return;
    }
    
    // Check if record already exists for this location and date
    $checkStmt = $conn->prepare("SELECT id FROM weather_data WHERE location = ? AND date = ?");
    $checkStmt->bind_param("ss", $location, $date);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $response['error'] = 'Weather record already exists for this location and date';
        $checkStmt->close();
        echo json_encode($response);
        return;
    }
    $checkStmt->close();
    
    $stmt = $conn->prepare("
        INSERT INTO weather_data (location, date, temperature_max, temperature_min, `condition`, precipitation, humidity)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("ssddssi", $location, $date, $temperatureMax, $temperatureMin, $condition, $precipitation, $humidity);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Weather data added successfully';
        $response['weather_id'] = $stmt->insert_id;
    } else {
        $response['error'] = 'Database error: ' . $stmt->error;
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleUpdateWeather() {
    global $conn, $response;
    
    $weatherId = $_POST['weather_id'] ?? '';
    $location = $_POST['location'] ?? '';
    $date = $_POST['date'] ?? '';
    $temperatureMax = $_POST['temperature_max'] ?? null;
    $temperatureMin = $_POST['temperature_min'] ?? null;
    $condition = $_POST['condition'] ?? null;
    $precipitation = $_POST['precipitation'] ?? null;
    $humidity = $_POST['humidity'] ?? null;
    
    if (empty($weatherId) || empty($location) || empty($date)) {
        $response['error'] = 'Weather ID, location, and date are required';
        echo json_encode($response);
        return;
    }
    
    $stmt = $conn->prepare("
        UPDATE weather_data 
        SET location = ?, date = ?, temperature_max = ?, temperature_min = ?, `condition` = ?, precipitation = ?, humidity = ?
        WHERE id = ?
    ");
    
    $stmt->bind_param("ssddssii", $location, $date, $temperatureMax, $temperatureMin, $condition, $precipitation, $humidity, $weatherId);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Weather data updated successfully';
    } else {
        $response['error'] = 'Database error: ' . $stmt->error;
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleDeleteRequest() {
    global $conn, $response;
    
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['error'] = 'Invalid JSON in request';
        echo json_encode($response);
        return;
    }
    
    $weatherId = isset($data['id']) ? (int)$data['id'] : 0;
    
    if ($weatherId === 0) {
        $response['error'] = 'Weather ID is required';
        echo json_encode($response);
        return;
    }
    
    $stmt = $conn->prepare("DELETE FROM weather_data WHERE id = ?");
    $stmt->bind_param("i", $weatherId);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Weather data deleted successfully';
    } else {
        $response['error'] = 'Database error: ' . $stmt->error;
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleExport() {
    global $conn, $response;
    
    $format = $_GET['export'] ?? 'json';
    $whereConditions = [];
    $params = [];
    $types = '';
    
    if (isset($_GET['location']) && !empty($_GET['location'])) {
        $whereConditions[] = "location = ?";
        $params[] = $_GET['location'];
        $types .= 's';
    }
    
    if (isset($_GET['date_from']) && !empty($_GET['date_from'])) {
        $whereConditions[] = "date >= ?";
        $params[] = $_GET['date_from'];
        $types .= 's';
    }
    
    if (isset($_GET['date_to']) && !empty($_GET['date_to'])) {
        $whereConditions[] = "date <= ?";
        $params[] = $_GET['date_to'];
        $types .= 's';
    }
    
    $whereClause = $whereConditions ? "WHERE " . implode(" AND ", $whereConditions) : "";
    
    $sql = "SELECT * FROM weather_data $whereClause ORDER BY date DESC, location ASC";
    $stmt = $conn->prepare($sql);
    
    if ($params) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $weather = $result->fetch_all(MYSQLI_ASSOC);
    
    if ($format === 'csv') {
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="weather_data.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Add CSV header
        fputcsv($output, ['Date', 'Location', 'Condition', 'Max Temp (°C)', 'Min Temp (°C)', 'Precipitation (mm)', 'Humidity (%)']);
        
        // Add data rows
        foreach ($weather as $row) {
            fputcsv($output, [
                $row['date'],
                $row['location'],
                $row['condition'] ?? 'N/A',
                $row['temperature_max'] ?? 'N/A',
                $row['temperature_min'] ?? 'N/A',
                $row['precipitation'] ?? 'N/A',
                $row['humidity'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
        exit;
    } else {
        // JSON export
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="weather_data.json"');
        echo json_encode($weather, JSON_PRETTY_PRINT);
        exit;
    }
}

function getWeatherStats($whereClause = '', $params = [], $types = '') {
    global $conn;
    
    $stats = [];
    
    // Total records
    $countSql = "SELECT COUNT(*) as total FROM weather_data $whereClause";
    if ($params) {
        $countStmt = $conn->prepare($countSql);
        $countStmt->bind_param($types, ...$params);
        $countStmt->execute();
        $totalResult = $countStmt->get_result();
        $stats['total_records'] = $totalResult->fetch_assoc()['total'];
        $countStmt->close();
    } else {
        $totalResult = $conn->query($countSql);
        $stats['total_records'] = $totalResult->fetch_assoc()['total'];
    }
    
    // Average temperature
    $avgTempSql = "SELECT AVG((temperature_max + temperature_min) / 2) as avg_temp FROM weather_data $whereClause AND temperature_max IS NOT NULL AND temperature_min IS NOT NULL";
    if ($params) {
        $avgStmt = $conn->prepare($avgTempSql);
        $avgStmt->bind_param($types, ...$params);
        $avgStmt->execute();
        $avgResult = $avgStmt->get_result();
        $avgTemp = $avgResult->fetch_assoc()['avg_temp'];
        $stats['avg_temperature'] = $avgTemp ? round($avgTemp, 1) : 0;
        $avgStmt->close();
    } else {
        $avgResult = $conn->query($avgTempSql);
        $avgTemp = $avgResult->fetch_assoc()['avg_temp'];
        $stats['avg_temperature'] = $avgTemp ? round($avgTemp, 1) : 0;
    }
    
    // Total rainfall
    $rainSql = "SELECT SUM(precipitation) as total_rain FROM weather_data $whereClause AND precipitation IS NOT NULL";
    if ($params) {
        $rainStmt = $conn->prepare($rainSql);
        $rainStmt->bind_param($types, ...$params);
        $rainStmt->execute();
        $rainResult = $rainStmt->get_result();
        $stats['total_rainfall'] = $rainResult->fetch_assoc()['total_rain'] ?? 0;
        $rainStmt->close();
    } else {
        $rainResult = $conn->query($rainSql);
        $stats['total_rainfall'] = $rainResult->fetch_assoc()['total_rain'] ?? 0;
    }
    
    // Most common condition
    $conditionSql = "SELECT `condition`, COUNT(*) as count FROM weather_data $whereClause AND `condition` IS NOT NULL GROUP BY `condition` ORDER BY count DESC LIMIT 1";
    if ($params) {
        $condStmt = $conn->prepare($conditionSql);
        $condStmt->bind_param($types, ...$params);
        $condStmt->execute();
        $condResult = $condStmt->get_result();
        $commonCond = $condResult->fetch_assoc();
        $stats['most_common_condition'] = $commonCond['condition'] ?? 'N/A';
        $condStmt->close();
    } else {
        $condResult = $conn->query($conditionSql);
        $commonCond = $condResult->fetch_assoc();
        $stats['most_common_condition'] = $commonCond['condition'] ?? 'N/A';
    }
    
    return $stats;
}

function getChartData() {
    global $conn;
    
    $charts = [];
    
    // Temperature data for last 7 days
    $tempSql = "SELECT date, AVG(temperature_max) as avg_max, AVG(temperature_min) as avg_min 
                FROM weather_data 
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                AND temperature_max IS NOT NULL AND temperature_min IS NOT NULL
                GROUP BY date 
                ORDER BY date ASC";
    $tempResult = $conn->query($tempSql);
    $tempData = $tempResult->fetch_all(MYSQLI_ASSOC);
    
    $charts['temperature'] = [
        'labels' => array_map(function($row) {
            return date('M j', strtotime($row['date']));
        }, $tempData),
        'max_temp' => array_map(function($row) {
            return round($row['avg_max'], 1);
        }, $tempData),
        'min_temp' => array_map(function($row) {
            return round($row['avg_min'], 1);
        }, $tempData)
    ];
    
    // Weather conditions distribution
    $condSql = "SELECT `condition`, COUNT(*) as count 
                FROM weather_data 
                WHERE `condition` IS NOT NULL 
                GROUP BY `condition` 
                ORDER BY count DESC";
    $condResult = $conn->query($condSql);
    $condData = $condResult->fetch_all(MYSQLI_ASSOC);
    
    $charts['conditions'] = [
        'labels' => array_map(function($row) {
            return $row['condition'];
        }, $condData),
        'counts' => array_map(function($row) {
            return $row['count'];
        }, $condData)
    ];
    
    return $charts;
}
?>