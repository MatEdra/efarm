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
            case 'farm_details':
                getFarmDetails($farmer_id);
                break;
            case 'farm_stats':
                getFarmStats($farmer_id);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $action = $input['action'] ?? '';
        
        switch($action) {
            case 'update_farm':
                updateFarmDetails($farmer_id, $input);
                break;
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

function getFarmDetails($farmer_id) {
    global $conn;
    
    $query = "SELECT farm_name, farm_location, farm_size, experience_years, 
                     first_name, last_name, email, phone_number, created_at
              FROM farmers 
              WHERE id = $farmer_id";
    
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $farmData = $result->fetch_assoc();
        
        // Get crop statistics
        $cropsQuery = "SELECT COUNT(*) as total_crops, 
                              GROUP_CONCAT(DISTINCT crop_type) as crop_types
                       FROM farmer_crops 
                       WHERE farmer_id = $farmer_id";
        $cropsResult = $conn->query($cropsQuery);
        $cropsData = $cropsResult->fetch_assoc();
        
        echo json_encode([
            'success' => true,
            'data' => [
                'farm_info' => $farmData,
                'crops_info' => $cropsData
            ]
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Farm data not found']);
    }
}

function getFarmStats($farmer_id) {
    global $conn;
    
    // Total crops
    $cropsQuery = "SELECT COUNT(*) as total_crops FROM farmer_crops WHERE farmer_id = $farmer_id";
    $cropsResult = $conn->query($cropsQuery);
    $totalCrops = $cropsResult->fetch_assoc()['total_crops'];
    
    // Crop types distribution
    $typesQuery = "SELECT crop_type, COUNT(*) as count 
                   FROM farmer_crops 
                   WHERE farmer_id = $farmer_id 
                   GROUP BY crop_type";
    $typesResult = $conn->query($typesQuery);
    $cropTypes = [];
    
    while ($row = $typesResult->fetch_assoc()) {
        $cropTypes[] = [
            'type' => $row['crop_type'],
            'count' => $row['count']
        ];
    }
    
    // Farm age (based on registration)
    $ageQuery = "SELECT TIMESTAMPDIFF(MONTH, created_at, NOW()) as months_since_registration 
                 FROM farmers 
                 WHERE id = $farmer_id";
    $ageResult = $conn->query($ageQuery);
    $monthsRegistered = $ageResult->fetch_assoc()['months_since_registration'];
    
    echo json_encode([
        'success' => true,
        'data' => [
            'total_crops' => (int)$totalCrops,
            'crop_types' => $cropTypes,
            'months_registered' => $monthsRegistered
        ]
    ]);
}

function updateFarmDetails($farmer_id, $data) {
    global $conn;
    
    $farm_name = $conn->real_escape_string($data['farm_name'] ?? '');
    $farm_location = $conn->real_escape_string($data['farm_location'] ?? '');
    $farm_size = $conn->real_escape_string($data['farm_size'] ?? '');
    $experience_years = $conn->real_escape_string($data['experience_years'] ?? '');
    
    $query = "UPDATE farmers SET 
              farm_name = '$farm_name',
              farm_location = '$farm_location',
              farm_size = '$farm_size',
              experience_years = '$experience_years',
              updated_at = NOW()
              WHERE id = $farmer_id";
    
    if ($conn->query($query)) {
        // Update session data
        $_SESSION['farm_name'] = $farm_name;
        $_SESSION['farm_location'] = $farm_location;
        $_SESSION['farm_size'] = $farm_size;
        $_SESSION['experience_years'] = $experience_years;
        
        echo json_encode([
            'success' => true,
            'message' => 'Farm details updated successfully'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to update farm details: ' . $conn->error
        ]);
    }
}
?>