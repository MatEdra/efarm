<?php
header('Content-Type: application/json');
include_once '../../include/conn.php';
session_start();

// Check if farmer is logged in
if (!isset($_SESSION['farmer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

$farmer_id = $_SESSION['farmer_id'];
$method = $_SERVER['REQUEST_METHOD'];

try {
    if ($method === 'GET') {
        $action = $_GET['action'] ?? '';
        
        switch($action) {
            case 'my_crops':
                getMyCrops($farmer_id);
                break;
            case 'available_crops':
                getAvailableCrops();
                break;
            case 'crop_details':
                $crop_id = $_GET['crop_id'] ?? '';
                getCropDetails($crop_id);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
        
        switch($action) {
            case 'add_crop':
                addCrop($farmer_id, $input);
                break;
            case 'update_crop':
                updateCrop($input);
                break;
            case 'delete_crop':
                deleteCrop($input);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function getMyCrops($farmer_id) {
    global $conn;
    
    $query = "SELECT fc.id, fc.crop_type, fc.created_at, 
                     c.name as crop_name, c.description, c.water_requirements, c.sunlight_requirements,
                     s.name as season_name
              FROM farmer_crops fc 
              LEFT JOIN crops c ON fc.crop_type = c.name 
              LEFT JOIN seasons s ON c.season_id = s.id
              WHERE fc.farmer_id = $farmer_id 
              ORDER BY fc.created_at DESC";
    
    $result = $conn->query($query);
    $crops = [];
    
    while ($row = $result->fetch_assoc()) {
        $crops[] = [
            'id' => $row['id'],
            'name' => $row['crop_name'] ?: ucfirst($row['crop_type']),
            'type' => $row['crop_type'],
            'description' => $row['description'],
            'planted_date' => $row['created_at'],
            'season' => $row['season_name'],
            'water_requirements' => $row['water_requirements'],
            'sunlight_requirements' => $row['sunlight_requirements'],
            'status' => getCropStatus($row['created_at']),
            'growth_stage' => getGrowthStage($row['created_at'])
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $crops]);
}

function getAvailableCrops() {
    global $conn;
    
    $query = "SELECT c.id, c.name, c.description, c.planting_guide, 
                     c.water_requirements, c.sunlight_requirements,
                     s.name as season_name
              FROM crops c 
              LEFT JOIN seasons s ON c.season_id = s.id
              ORDER BY c.name";
    
    $result = $conn->query($query);
    $crops = [];
    
    while ($row = $result->fetch_assoc()) {
        $crops[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'planting_guide' => $row['planting_guide'],
            'season' => $row['season_name'],
            'water_requirements' => $row['water_requirements'],
            'sunlight_requirements' => $row['sunlight_requirements']
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $crops]);
}

function addCrop($farmer_id, $data) {
    global $conn;
    
    $crop_type = $conn->real_escape_string($data['crop_type'] ?? '');
    
    if (empty($crop_type)) {
        echo json_encode(['success' => false, 'message' => 'Crop type is required']);
        return;
    }
    
    // Check if crop already exists for this farmer
    $checkQuery = "SELECT id FROM farmer_crops WHERE farmer_id = $farmer_id AND crop_type = '$crop_type'";
    $checkResult = $conn->query($checkQuery);
    
    if ($checkResult->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'You already have this crop in your farm']);
        return;
    }
    
    $query = "INSERT INTO farmer_crops (farmer_id, crop_type) VALUES ($farmer_id, '$crop_type')";
    
    if ($conn->query($query)) {
        echo json_encode([
            'success' => true,
            'message' => 'Crop added successfully',
            'crop_id' => $conn->insert_id
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to add crop: ' . $conn->error
        ]);
    }
}

function deleteCrop($data) {
    global $conn;
    
    $crop_id = $conn->real_escape_string($data['crop_id'] ?? '');
    
    if (empty($crop_id)) {
        echo json_encode(['success' => false, 'message' => 'Crop ID is required']);
        return;
    }
    
    $query = "DELETE FROM farmer_crops WHERE id = $crop_id";
    
    if ($conn->query($query)) {
        echo json_encode([
            'success' => true,
            'message' => 'Crop removed successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to remove crop: ' . $conn->error
        ]);
    }
}

function getCropDetails($crop_id) {
    global $conn;
    
    $query = "SELECT fc.id, fc.crop_type, fc.created_at, 
                     c.name as crop_name, c.description, c.planting_guide,
                     c.water_requirements, c.sunlight_requirements,
                     s.name as season_name, s.description as season_description
              FROM farmer_crops fc 
              LEFT JOIN crops c ON fc.crop_type = c.name 
              LEFT JOIN seasons s ON c.season_id = s.id
              WHERE fc.id = $crop_id";
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $crop = $result->fetch_assoc();
        $cropData = [
            'id' => $crop['id'],
            'name' => $crop['crop_name'] ?: ucfirst($crop['crop_type']),
            'type' => $crop['crop_type'],
            'description' => $crop['description'],
            'planting_guide' => $crop['planting_guide'],
            'planted_date' => $crop['created_at'],
            'season' => $crop['season_name'],
            'season_description' => $crop['season_description'],
            'water_requirements' => $crop['water_requirements'],
            'sunlight_requirements' => $crop['sunlight_requirements'],
            'status' => getCropStatus($crop['created_at']),
            'growth_stage' => getGrowthStage($crop['created_at'])
        ];
        
        echo json_encode(['success' => true, 'data' => $cropData]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Crop not found']);
    }
}

function updateCrop($data) {
    global $conn;
    
    $crop_id = $conn->real_escape_string($data['crop_id'] ?? '');
    $crop_type = $conn->real_escape_string($data['crop_type'] ?? '');
    
    if (empty($crop_id) || empty($crop_type)) {
        echo json_encode(['success' => false, 'message' => 'Crop ID and type are required']);
        return;
    }
    
    $query = "UPDATE farmer_crops SET crop_type = '$crop_type' WHERE id = $crop_id";
    
    if ($conn->query($query)) {
        echo json_encode([
            'success' => true,
            'message' => 'Crop updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update crop: ' . $conn->error
        ]);
    }
}

function getCropStatus($plantedDate) {
    $planted = new DateTime($plantedDate);
    $now = new DateTime();
    $interval = $now->diff($planted);
    $days = $interval->days;
    
    if ($days < 7) return 'seedling';
    if ($days < 30) return 'growing';
    if ($days < 60) return 'flowering';
    return 'harvesting';
}

function getGrowthStage($plantedDate) {
    $planted = new DateTime($plantedDate);
    $now = new DateTime();
    $interval = $now->diff($planted);
    $days = $interval->days;
    
    if ($days < 7) return 'Early Growth (1-7 days)';
    if ($days < 30) return 'Vegetative Stage (1-4 weeks)';
    if ($days < 60) return 'Flowering Stage (1-2 months)';
    return 'Maturation Stage (2+ months)';
}
?>