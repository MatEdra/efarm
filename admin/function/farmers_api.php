<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        case 'PUT':
            handlePutRequest();
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
    error_log("API Error: " . $e->getMessage());
    $response['error'] = 'Server error: ' . $e->getMessage();
    http_response_code(500);
    echo json_encode($response);
}

function handleGetRequest() {
    global $conn, $response;
    
    if (isset($_GET['id'])) {
        // Get single farmer
        $farmerId = (int)$_GET['id'];
        $stmt = $conn->prepare("
            SELECT f.*, GROUP_CONCAT(fc.crop_type) as crops
            FROM farmers f
            LEFT JOIN farmer_crops fc ON f.id = fc.farmer_id
            WHERE f.id = ?
            GROUP BY f.id
        ");
        
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        $stmt->bind_param("i", $farmerId);
        
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $farmer = $result->fetch_assoc();
            $farmer['crops'] = $farmer['crops'] ? explode(',', $farmer['crops']) : [];
            $response['success'] = true;
            $response['data'] = $farmer;
        } else {
            $response['error'] = 'Farmer not found';
            http_response_code(404);
        }
        
        $stmt->close();
    } else {
        // Get farmers list with pagination and filters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Build WHERE clause for filters
        $whereConditions = [];
        $params = [];
        $types = '';
        
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = "%{$_GET['search']}%";
            $whereConditions[] = "(f.first_name LIKE ? OR f.last_name LIKE ? OR f.phone_number LIKE ? OR f.farm_location LIKE ?)";
            $params = array_merge($params, [$search, $search, $search, $search]);
            $types .= 'ssss';
        }
        
        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $whereConditions[] = "f.farm_location LIKE ?";
            $params[] = '%' . $_GET['location'] . '%';
            $types .= 's';
        }
        
        if (isset($_GET['gender']) && !empty($_GET['gender'])) {
            $whereConditions[] = "f.gender = ?";
            $params[] = $_GET['gender'];
            $types .= 's';
        }
        
        if (isset($_GET['farm_size']) && !empty($_GET['farm_size'])) {
            switch ($_GET['farm_size']) {
                case '0-5':
                    $whereConditions[] = "f.farm_size BETWEEN 0 AND 5";
                    break;
                case '5-10':
                    $whereConditions[] = "f.farm_size BETWEEN 5 AND 10";
                    break;
                case '10+':
                    $whereConditions[] = "f.farm_size > 10";
                    break;
            }
        }
        
        $whereClause = $whereConditions ? "WHERE " . implode(" AND ", $whereConditions) : "";
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM farmers f $whereClause";
        if ($params) {
            $countStmt = $conn->prepare($countSql);
            if (!$countStmt) {
                throw new Exception('Count prepare failed: ' . $conn->error);
            }
            if (!empty($types)) {
                $countStmt->bind_param($types, ...$params);
            }
            if (!$countStmt->execute()) {
                throw new Exception('Count execute failed: ' . $countStmt->error);
            }
            $totalResult = $countStmt->get_result();
            $totalRows = $totalResult->fetch_assoc()['total'];
            $countStmt->close();
        } else {
            $totalResult = $conn->query($countSql);
            if (!$totalResult) {
                throw new Exception('Count query failed: ' . $conn->error);
            }
            $totalRows = $totalResult->fetch_assoc()['total'];
        }
        
        $totalPages = ceil($totalRows / $limit);
        
        // Get farmers data
        $sql = "
            SELECT f.*, GROUP_CONCAT(fc.crop_type) as crops
            FROM farmers f
            LEFT JOIN farmer_crops fc ON f.id = fc.farmer_id
            $whereClause
            GROUP BY f.id
            ORDER BY f.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        // Add pagination parameters
        $params[] = $limit;
        $params[] = $offset;
        $types .= 'ii';
        
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        
        $result = $stmt->get_result();
        $farmers = $result->fetch_all(MYSQLI_ASSOC);
        
        // Process crops for each farmer
        foreach ($farmers as &$farmer) {
            $farmer['crops'] = $farmer['crops'] ? explode(',', $farmer['crops']) : [];
        }
        
        $response['success'] = true;
        $response['data'] = [
            'farmers' => $farmers,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_farmers' => $totalRows,
                'limit' => $limit
            ]
        ];
        
        $stmt->close();
    }
    
    echo json_encode($response);
}

function handlePostRequest() {
    global $conn, $response;
    
    // Get raw POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // If not JSON, try form data
    if (json_last_error() !== JSON_ERROR_NONE) {
        $data = $_POST;
    }
    
    // Validate required fields
    $required = ['first_name', 'last_name', 'email', 'phone_number', 'password', 'gender'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $response['error'] = "Missing required field: $field";
            echo json_encode($response);
            return;
        }
    }
    
    // Validate gender
    $validGenders = ['Male', 'Female', 'Other'];
    if (!in_array($data['gender'], $validGenders)) {
        $response['error'] = "Invalid gender value. Must be Male, Female, or Other.";
        echo json_encode($response);
        return;
    }
    
    $firstName = trim($data['first_name']);
    $lastName = trim($data['last_name']);
    $email = trim($data['email']);
    $phone = trim($data['phone_number']);
    $gender = trim($data['gender']);
    $dateOfBirth = isset($data['date_of_birth']) && !empty($data['date_of_birth']) ? trim($data['date_of_birth']) : null;
    $farmName = isset($data['farm_name']) ? trim($data['farm_name']) : '';
    $farmLocation = isset($data['farm_location']) ? trim($data['farm_location']) : '';
    $farmSize = isset($data['farm_size']) && $data['farm_size'] !== '' ? (float)$data['farm_size'] : null;
    $experience = isset($data['experience_years']) && $data['experience_years'] !== '' ? (int)$data['experience_years'] : 0;
    $password = $data['password'];
    $crops = isset($data['crops']) ? (is_array($data['crops']) ? $data['crops'] : json_decode($data['crops'], true)) : [];
    
    // Check if email or phone already exists
    $checkStmt = $conn->prepare("SELECT id FROM farmers WHERE email = ? OR phone_number = ?");
    if (!$checkStmt) {
        throw new Exception('Check prepare failed: ' . $conn->error);
    }
    
    $checkStmt->bind_param("ss", $email, $phone);
    if (!$checkStmt->execute()) {
        throw new Exception('Check execute failed: ' . $checkStmt->error);
    }
    
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $response['error'] = 'Email or phone number already exists';
        echo json_encode($response);
        $checkStmt->close();
        return;
    }
    $checkStmt->close();
    
    // Hash password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert farmer
    $stmt = $conn->prepare("
        INSERT INTO farmers (first_name, last_name, email, phone_number, gender, date_of_birth, farm_name, farm_location, farm_size, experience_years, password)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    if (!$stmt) {
        throw new Exception('Insert prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("ssssssssdis", $firstName, $lastName, $email, $phone, $gender, $dateOfBirth, $farmName, $farmLocation, $farmSize, $experience, $hashedPassword);
    
    if ($stmt->execute()) {
        $farmerId = $stmt->insert_id;
        
        // Insert crops
        if (!empty($crops) && is_array($crops)) {
            $cropStmt = $conn->prepare("INSERT INTO farmer_crops (farmer_id, crop_type) VALUES (?, ?)");
            if ($cropStmt) {
                foreach ($crops as $crop) {
                    if (!empty(trim($crop))) {
                        $cropStmt->bind_param("is", $farmerId, $crop);
                        $cropStmt->execute();
                    }
                }
                $cropStmt->close();
            }
        }
        
        $response['success'] = true;
        $response['message'] = 'Farmer added successfully';
        $response['farmer_id'] = $farmerId;
    } else {
        throw new Exception('Insert execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handlePutRequest() {
    global $conn, $response;
    
    // Get raw PUT data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        $response['error'] = 'Invalid JSON: ' . json_last_error_msg();
        echo json_encode($response);
        return;
    }
    
    // Validate required fields
    $required = ['farmer_id', 'first_name', 'last_name', 'email', 'phone_number', 'gender'];
    foreach ($required as $field) {
        if (empty($data[$field])) {
            $response['error'] = "Missing required field: $field";
            echo json_encode($response);
            return;
        }
    }
    
    // Validate gender
    $validGenders = ['Male', 'Female', 'Other'];
    if (!in_array($data['gender'], $validGenders)) {
        $response['error'] = "Invalid gender value. Must be Male, Female, or Other.";
        echo json_encode($response);
        return;
    }
    
    $farmerId = (int)$data['farmer_id'];
    $firstName = trim($data['first_name']);
    $lastName = trim($data['last_name']);
    $email = trim($data['email']);
    $phone = trim($data['phone_number']);
    $gender = trim($data['gender']);
    $dateOfBirth = isset($data['date_of_birth']) && !empty($data['date_of_birth']) ? trim($data['date_of_birth']) : null;
    $farmName = isset($data['farm_name']) ? trim($data['farm_name']) : '';
    $farmLocation = isset($data['farm_location']) ? trim($data['farm_location']) : '';
    $farmSize = isset($data['farm_size']) && $data['farm_size'] !== '' ? (float)$data['farm_size'] : null;
    $experience = isset($data['experience_years']) && $data['experience_years'] !== '' ? (int)$data['experience_years'] : 0;
    $password = isset($data['password']) ? trim($data['password']) : '';
    $crops = isset($data['crops']) ? (is_array($data['crops']) ? $data['crops'] : []) : [];
    
    // Check if email or phone already exists for other farmers
    $checkStmt = $conn->prepare("SELECT id FROM farmers WHERE (email = ? OR phone_number = ?) AND id != ?");
    if (!$checkStmt) {
        throw new Exception('Check prepare failed: ' . $conn->error);
    }
    
    $checkStmt->bind_param("ssi", $email, $phone, $farmerId);
    if (!$checkStmt->execute()) {
        throw new Exception('Check execute failed: ' . $checkStmt->error);
    }
    
    $checkResult = $checkStmt->get_result();
    
    if ($checkResult->num_rows > 0) {
        $response['error'] = 'Email or phone number already exists for another farmer';
        echo json_encode($response);
        $checkStmt->close();
        return;
    }
    $checkStmt->close();
    
    // Update farmer
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("
            UPDATE farmers 
            SET first_name = ?, last_name = ?, email = ?, phone_number = ?, gender = ?, date_of_birth = ?, 
                farm_name = ?, farm_location = ?, farm_size = ?, experience_years = ?, password = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        if (!$stmt) {
            throw new Exception('Update prepare failed: ' . $conn->error);
        }
        
        // CORRECTED: Fixed parameter binding - 12 parameters for 12 placeholders
        $stmt->bind_param("ssssssssdisi", 
            $firstName, $lastName, $email, $phone, $gender, $dateOfBirth, 
            $farmName, $farmLocation, $farmSize, $experience, $hashedPassword, $farmerId
        );
    } else {
        $stmt = $conn->prepare("
            UPDATE farmers 
            SET first_name = ?, last_name = ?, email = ?, phone_number = ?, gender = ?, date_of_birth = ?,
                farm_name = ?, farm_location = ?, farm_size = ?, experience_years = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        if (!$stmt) {
            throw new Exception('Update prepare failed: ' . $conn->error);
        }
        
        $stmt->bind_param("ssssssssdii",
            $firstName, $lastName, $email, $phone, $gender, $dateOfBirth,
            $farmName, $farmLocation, $farmSize, $experience, $farmerId
        );
    }
    
    if ($stmt->execute()) {
        // Update crops - delete existing and insert new
        $deleteStmt = $conn->prepare("DELETE FROM farmer_crops WHERE farmer_id = ?");
        if ($deleteStmt) {
            $deleteStmt->bind_param("i", $farmerId);
            $deleteStmt->execute();
            $deleteStmt->close();
        }
        
        if (!empty($crops) && is_array($crops)) {
            $cropStmt = $conn->prepare("INSERT INTO farmer_crops (farmer_id, crop_type) VALUES (?, ?)");
            if ($cropStmt) {
                foreach ($crops as $crop) {
                    if (!empty(trim($crop))) {
                        $cropStmt->bind_param("is", $farmerId, $crop);
                        $cropStmt->execute();
                    }
                }
                $cropStmt->close();
            }
        }
        
        $response['success'] = true;
        $response['message'] = 'Farmer updated successfully';
    } else {
        throw new Exception('Update execute failed: ' . $stmt->error);
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
    
    $farmerId = isset($data['id']) ? (int)$data['id'] : 0;
    
    if ($farmerId === 0) {
        $response['error'] = 'Farmer ID is required';
        echo json_encode($response);
        return;
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete farmer crops first
        $cropStmt = $conn->prepare("DELETE FROM farmer_crops WHERE farmer_id = ?");
        if (!$cropStmt) {
            throw new Exception('Crop delete prepare failed: ' . $conn->error);
        }
        $cropStmt->bind_param("i", $farmerId);
        if (!$cropStmt->execute()) {
            throw new Exception('Crop delete execute failed: ' . $cropStmt->error);
        }
        $cropStmt->close();
        
        // Delete farmer
        $stmt = $conn->prepare("DELETE FROM farmers WHERE id = ?");
        if (!$stmt) {
            throw new Exception('Farmer delete prepare failed: ' . $conn->error);
        }
        $stmt->bind_param("i", $farmerId);
        
        if ($stmt->execute()) {
            $conn->commit();
            $response['success'] = true;
            $response['message'] = 'Farmer deleted successfully';
        } else {
            throw new Exception('Farmer delete execute failed: ' . $stmt->error);
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $conn->rollback();
        $response['error'] = $e->getMessage();
    }
    
    echo json_encode($response);
}

// Close database connection
$conn->close();
?>