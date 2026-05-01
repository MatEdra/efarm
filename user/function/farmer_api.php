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
            case 'dashboard_stats':
                getDashboardStats($farmer_id);
                break;
            case 'my_crops':
                getMyCrops($farmer_id);
                break;
            case 'recent_materials':
                getRecentMaterials();
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function getDashboardStats($farmer_id) {
    global $conn;
    
    // Get farmer's crop count
    $cropsQuery = "SELECT COUNT(*) as crop_count FROM farmer_crops WHERE farmer_id = $farmer_id";
    $cropsResult = $conn->query($cropsQuery);
    $cropCount = $cropsResult->fetch_assoc()['crop_count'];
    
    // Get total materials count
    $materialsQuery = "SELECT COUNT(*) as material_count FROM learning_materials";
    $materialsResult = $conn->query($materialsQuery);
    $materialCount = $materialsResult->fetch_assoc()['material_count'];
    
    // Get farmer details
    $farmerQuery = "SELECT farm_name, farm_location, farm_size, experience_years FROM farmers WHERE id = $farmer_id";
    $farmerResult = $conn->query($farmerQuery);
    $farmerData = $farmerResult->fetch_assoc();
    
    echo json_encode([
        'success' => true,
        'data' => [
            'active_crops' => (int)$cropCount,
            'total_materials' => (int)$materialCount,
            'weather_alerts' => 1, // Static for now
            'farm_info' => $farmerData
        ]
    ]);
}

function getMyCrops($farmer_id) {
    global $conn;
    
    $query = "SELECT fc.crop_type, fc.created_at, c.name as crop_name 
              FROM farmer_crops fc 
              LEFT JOIN crops c ON fc.crop_type = c.name 
              WHERE fc.farmer_id = $farmer_id 
              ORDER BY fc.created_at DESC 
              LIMIT 5";
    
    $result = $conn->query($query);
    $crops = [];
    
    while ($row = $result->fetch_assoc()) {
        $crops[] = [
            'name' => $row['crop_name'] ?: ucfirst($row['crop_type']),
            'type' => $row['crop_type'],
            'planted_date' => $row['created_at'],
            'status' => getCropStatus($row['created_at'])
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $crops]);
}

function getRecentMaterials() {
    global $conn;
    
    $query = "SELECT title, description, file_type, download_count, created_at 
              FROM learning_materials 
              ORDER BY created_at DESC 
              LIMIT 3";
    
    $result = $conn->query($query);
    $materials = [];
    
    while ($row = $result->fetch_assoc()) {
        $materials[] = [
            'title' => $row['title'],
            'description' => $row['description'],
            'file_type' => $row['file_type'],
            'download_count' => $row['download_count'],
            'created_at' => $row['created_at']
        ];
    }
    
    echo json_encode(['success' => true, 'data' => $materials]);
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
?>