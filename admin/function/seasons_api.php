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
    
    if (isset($_GET['id'])) {
        // Get single season
        $seasonId = (int)$_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM seasons WHERE id = ?");
        $stmt->bind_param("i", $seasonId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $season = $result->fetch_assoc();
            $response['success'] = true;
            $response['data'] = $season;
        } else {
            $response['error'] = 'Season not found';
            http_response_code(404);
        }
        
        $stmt->close();
    } elseif (isset($_GET['season_id'])) {
        // Get crops for a specific season
        $seasonId = (int)$_GET['season_id'];
        $stmt = $conn->prepare("
            SELECT c.*, s.name as season_name 
            FROM crops c 
            LEFT JOIN seasons s ON c.season_id = s.id 
            WHERE c.season_id = ?
        ");
        $stmt->bind_param("i", $seasonId);
        $stmt->execute();
        $result = $stmt->get_result();
        $crops = $result->fetch_all(MYSQLI_ASSOC);
        
        $response['success'] = true;
        $response['data'] = ['crops' => $crops];
        $stmt->close();
    } elseif (isset($_GET['crop_id'])) {
        // Get single crop
        $cropId = (int)$_GET['crop_id'];
        $stmt = $conn->prepare("
            SELECT c.*, s.name as season_name 
            FROM crops c 
            LEFT JOIN seasons s ON c.season_id = s.id 
            WHERE c.id = ?
        ");
        $stmt->bind_param("i", $cropId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $crop = $result->fetch_assoc();
            $response['success'] = true;
            $response['data'] = $crop;
        } else {
            $response['error'] = 'Crop not found';
            http_response_code(404);
        }
        
        $stmt->close();
    } else {
        // Get all seasons with stats
        $seasonsResult = $conn->query("SELECT * FROM seasons ORDER BY start_month");
        $seasons = $seasonsResult->fetch_all(MYSQLI_ASSOC);
        
        $stats = getSeasonsStats();
        
        $response['success'] = true;
        $response['data'] = [
            'seasons' => $seasons,
            'stats' => $stats
        ];
    }
    
    echo json_encode($response);
}

function handlePostRequest() {
    global $conn, $response;
    
    // Debug: Log all POST data
    error_log("POST data received: " . print_r($_POST, true));
    
    // Check if this is a crop operation
    if (isset($_POST['crop_id'])) {
        // Update existing crop
        handleUpdateCrop();
    } 
    // Check if this is a season update operation
    elseif (isset($_POST['season_id']) && isset($_POST['name']) && isset($_POST['start_month'])) {
        // Update existing season
        handleUpdateSeason();
    }
    // Check if this is a new crop (has planting_guide field)
    elseif (isset($_POST['planting_guide'])) {
        // Add new crop
        handleAddCrop();
    }
    // Check if this is a new season (has start_month and end_month)
    elseif (isset($_POST['start_month']) && isset($_POST['end_month'])) {
        // Add new season
        handleAddSeason();
    }
    else {
        $response['error'] = 'Invalid request. Missing required fields.';
        echo json_encode($response);
        return;
    }
}

function handleAddSeason() {
    global $conn, $response;
    
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $startMonth = $_POST['start_month'] ?? '';
    $endMonth = $_POST['end_month'] ?? '';
    
    if (empty($name) || empty($startMonth) || empty($endMonth)) {
        $response['error'] = 'Season name, start month, and end month are required';
        echo json_encode($response);
        return;
    }
    
    $stmt = $conn->prepare("INSERT INTO seasons (name, description, start_month, end_month) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssii", $name, $description, $startMonth, $endMonth);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Season added successfully';
        $response['season_id'] = $stmt->insert_id;
    } else {
        $response['error'] = 'Database error: ' . $stmt->error;
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleUpdateSeason() {
    global $conn, $response;
    
    $seasonId = $_POST['season_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $startMonth = $_POST['start_month'] ?? '';
    $endMonth = $_POST['end_month'] ?? '';
    
    if (empty($seasonId) || empty($name) || empty($startMonth) || empty($endMonth)) {
        $response['error'] = 'All fields are required';
        echo json_encode($response);
        return;
    }
    
    $stmt = $conn->prepare("UPDATE seasons SET name = ?, description = ?, start_month = ?, end_month = ? WHERE id = ?");
    $stmt->bind_param("ssiii", $name, $description, $startMonth, $endMonth, $seasonId);
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Season updated successfully';
    } else {
        $response['error'] = 'Database error: ' . $stmt->error;
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleAddCrop() {
    global $conn, $response;
    
    $seasonId = $_POST['season_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $plantingGuide = $_POST['planting_guide'] ?? '';
    
    error_log("Adding crop - Season ID: $seasonId, Name: $name, Planting Guide: $plantingGuide");
    
    if (empty($seasonId) || empty($name) || empty($plantingGuide)) {
        $response['error'] = 'Season, crop name, and planting guide are required';
        echo json_encode($response);
        return;
    }
    
    // Check if crops table has the new columns
    $tableCheck = $conn->query("SHOW COLUMNS FROM crops LIKE 'water_requirements'");
    $hasExtraColumns = ($tableCheck->num_rows > 0);
    
    if ($hasExtraColumns) {
        $waterRequirements = $_POST['water_requirements'] ?? '';
        $sunlightRequirements = $_POST['sunlight_requirements'] ?? '';
        
        $stmt = $conn->prepare("INSERT INTO crops (name, description, season_id, planting_guide, water_requirements, sunlight_requirements) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisss", $name, $description, $seasonId, $plantingGuide, $waterRequirements, $sunlightRequirements);
    } else {
        $stmt = $conn->prepare("INSERT INTO crops (name, description, season_id, planting_guide) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $name, $description, $seasonId, $plantingGuide);
    }
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Crop added successfully';
        $response['crop_id'] = $stmt->insert_id;
    } else {
        $response['error'] = 'Database error: ' . $stmt->error;
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleUpdateCrop() {
    global $conn, $response;
    
    $cropId = $_POST['crop_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $plantingGuide = $_POST['planting_guide'] ?? '';
    
    if (empty($cropId) || empty($name) || empty($plantingGuide)) {
        $response['error'] = 'Crop name and planting guide are required';
        echo json_encode($response);
        return;
    }
    
    // Check if crops table has the new columns
    $tableCheck = $conn->query("SHOW COLUMNS FROM crops LIKE 'water_requirements'");
    $hasExtraColumns = ($tableCheck->num_rows > 0);
    
    if ($hasExtraColumns) {
        $waterRequirements = $_POST['water_requirements'] ?? '';
        $sunlightRequirements = $_POST['sunlight_requirements'] ?? '';
        
        $stmt = $conn->prepare("UPDATE crops SET name = ?, description = ?, planting_guide = ?, water_requirements = ?, sunlight_requirements = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $description, $plantingGuide, $waterRequirements, $sunlightRequirements, $cropId);
    } else {
        $stmt = $conn->prepare("UPDATE crops SET name = ?, description = ?, planting_guide = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $description, $plantingGuide, $cropId);
    }
    
    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Crop updated successfully';
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
    
    if (isset($data['id'])) {
        // Delete season
        $seasonId = (int)$data['id'];
        
        // First delete associated crops
        $deleteCropsStmt = $conn->prepare("DELETE FROM crops WHERE season_id = ?");
        $deleteCropsStmt->bind_param("i", $seasonId);
        $deleteCropsStmt->execute();
        $deleteCropsStmt->close();
        
        // Then delete season
        $stmt = $conn->prepare("DELETE FROM seasons WHERE id = ?");
        $stmt->bind_param("i", $seasonId);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Season and associated crops deleted successfully';
        } else {
            $response['error'] = 'Database error: ' . $stmt->error;
        }
        
        $stmt->close();
    } elseif (isset($data['crop_id'])) {
        // Delete crop
        $cropId = (int)$data['crop_id'];
        
        $stmt = $conn->prepare("DELETE FROM crops WHERE id = ?");
        $stmt->bind_param("i", $cropId);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Crop deleted successfully';
        } else {
            $response['error'] = 'Database error: ' . $stmt->error;
        }
        
        $stmt->close();
    } else {
        $response['error'] = 'ID or crop_id is required';
    }
    
    echo json_encode($response);
}

function getSeasonsStats() {
    global $conn;
    
    $stats = [];
    
    // Total seasons
    $result = $conn->query("SELECT COUNT(*) as total FROM seasons");
    $stats['total_seasons'] = $result->fetch_assoc()['total'];
    
    // Total crops
    $result = $conn->query("SELECT COUNT(*) as total FROM crops");
    $stats['total_crops'] = $result->fetch_assoc()['total'];
    
    // Dry season crops (assuming dry season has start_month 11 and end_month 4)
    $result = $conn->query("SELECT COUNT(*) as total FROM crops c JOIN seasons s ON c.season_id = s.id WHERE s.start_month = 11 AND s.end_month = 4");
    $stats['dry_season_crops'] = $result->fetch_assoc()['total'];
    
    // Wet season crops (assuming wet season has start_month 5 and end_month 10)
    $result = $conn->query("SELECT COUNT(*) as total FROM crops c JOIN seasons s ON c.season_id = s.id WHERE s.start_month = 5 AND s.end_month = 10");
    $stats['wet_season_crops'] = $result->fetch_assoc()['total'];
    
    return $stats;
}
?>