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

// Start session for admin ID
session_start();

// Define consistent file paths
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../') . '/');
define('UPLOAD_DIR', ROOT_PATH . 'uploads/materials/');
define('WEB_UPLOAD_PATH', 'uploads/materials/');

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
        // Get single material
        $materialId = (int)$_GET['id'];
        $stmt = $conn->prepare("
            SELECT m.*, a.first_name, a.last_name 
            FROM learning_materials m 
            LEFT JOIN admins a ON m.uploaded_by = a.id 
            WHERE m.id = ?
        ");
        
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
        $stmt->bind_param("i", $materialId);
        
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $material = $result->fetch_assoc();
            
            // Get categories for this material
            $material['categories'] = getMaterialCategories($materialId);
            
            $response['success'] = true;
            $response['data'] = $material;
        } else {
            $response['error'] = 'Material not found';
            http_response_code(404);
        }
        
        $stmt->close();
    } else {
        // Get materials list with pagination and filters
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit = 9;
        $offset = ($page - 1) * $limit;
        
        // Build WHERE clause for filters
        $whereConditions = [];
        $params = [];
        $types = '';
        
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = "%{$_GET['search']}%";
            $whereConditions[] = "(m.title LIKE ? OR m.description LIKE ?)";
            $params = array_merge($params, [$search, $search]);
            $types .= 'ss';
        }
        
        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $whereConditions[] = "m.file_type = ?";
            $params[] = $_GET['type'];
            $types .= 's';
        }
        
        $whereClause = $whereConditions ? "WHERE " . implode(" AND ", $whereConditions) : "";
        
        // Build ORDER BY clause
        $orderBy = "ORDER BY m.created_at DESC";
        if (isset($_GET['sort']) && !empty($_GET['sort'])) {
            switch ($_GET['sort']) {
                case 'oldest':
                    $orderBy = "ORDER BY m.created_at ASC";
                    break;
                case 'title':
                    $orderBy = "ORDER BY m.title ASC";
                    break;
                case 'popular':
                    $orderBy = "ORDER BY m.download_count DESC";
                    break;
            }
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM learning_materials m $whereClause";
        if ($params) {
            $countStmt = $conn->prepare($countSql);
            if (!$countStmt) {
                throw new Exception('Count prepare failed: ' . $conn->error);
            }
            $countStmt->bind_param($types, ...$params);
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
        
        // Get materials data
        $sql = "
            SELECT m.*, a.first_name, a.last_name 
            FROM learning_materials m 
            LEFT JOIN admins a ON m.uploaded_by = a.id 
            $whereClause 
            $orderBy 
            LIMIT ? OFFSET ?
        ";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $conn->error);
        }
        
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
        $materials = $result->fetch_all(MYSQLI_ASSOC);
        
        // Get categories for each material
        foreach ($materials as &$material) {
            $material['categories'] = getMaterialCategories($material['id']);
        }
        
        // Get stats
        $stats = getMaterialsStats();
        
        $response['success'] = true;
        $response['data'] = [
            'materials' => $materials,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_materials' => $totalRows,
                'limit' => $limit
            ],
            'stats' => $stats
        ];
        
        $stmt->close();
    }
    
    echo json_encode($response);
}

function handlePostRequest() {
    global $conn, $response;
    
    // Check if this is a download action
    $input = file_get_contents('php://input');
    $jsonData = json_decode($input, true);
    
    if ($jsonData && isset($jsonData['action']) && $jsonData['action'] === 'download') {
        handleDownload($jsonData['id']);
        return;
    }
    
    // Check if this is an update action (from edit form)
    if (isset($_POST['material_id'])) {
        handleUpdate();
        return;
    }
    
    // Otherwise, it's a new material upload
    // Check if file was uploaded
    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $response['error'] = 'Please select a file to upload';
        echo json_encode($response);
        return;
    }
    
    $uploadedFile = $_FILES['file'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $fileType = $_POST['file_type'] ?? '';
    $duration = $_POST['duration'] ?? null;
    $categories = isset($_POST['categories']) ? (is_array($_POST['categories']) ? $_POST['categories'] : [$_POST['categories']]) : [];
    
    // Validate required fields
    if (empty($title) || empty($fileType)) {
        $response['error'] = 'Title and file type are required';
        echo json_encode($response);
        return;
    }
    
    // Validate file type
    $allowedTypes = ['pdf', 'video', 'article'];
    if (!in_array($fileType, $allowedTypes)) {
        $response['error'] = 'Invalid file type';
        echo json_encode($response);
        return;
    }
    
    // File validation based on type
    $maxSize = 50 * 1024 * 1024; // 50MB default
    $allowedExtensions = [];
    
    switch ($fileType) {
        case 'pdf':
            $maxSize = 50 * 1024 * 1024; // 50MB
            $allowedExtensions = ['pdf'];
            break;
        case 'video':
            $maxSize = 500 * 1024 * 1024; // 500MB
            $allowedExtensions = ['mp4', 'mov', 'avi', 'mkv'];
            break;
        case 'article':
            $maxSize = 20 * 1024 * 1024; // 20MB
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'txt'];
            break;
    }
    
    // Check file size
    if ($uploadedFile['size'] > $maxSize) {
        $response['error'] = 'File size exceeds maximum allowed size (' . formatFileSize($maxSize) . ')';
        echo json_encode($response);
        return;
    }
    
    // Check file extension
    $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        $response['error'] = 'Invalid file extension. Allowed: ' . implode(', ', $allowedExtensions);
        echo json_encode($response);
        return;
    }
    
    // Create uploads directory if it doesn't exist
    if (!is_dir(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0755, true)) {
            $response['error'] = 'Failed to create upload directory: ' . UPLOAD_DIR;
            echo json_encode($response);
            return;
        }
    }
    
    // Check if directory is writable
    if (!is_writable(UPLOAD_DIR)) {
        $response['error'] = 'Upload directory is not writable: ' . UPLOAD_DIR;
        echo json_encode($response);
        return;
    }
    
    // Generate unique filename
    $originalName = pathinfo($uploadedFile['name'], PATHINFO_FILENAME);
    $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName) . '.' . $fileExtension;
    $filePath = WEB_UPLOAD_PATH . $fileName; // Store web-accessible path
    $fullFilePath = UPLOAD_DIR . $fileName; // Full server path for file operations
    
    // Move uploaded file
    if (!move_uploaded_file($uploadedFile['tmp_name'], $fullFilePath)) {
        $response['error'] = 'Failed to upload file. Please check directory permissions.';
        error_log("Upload failed: Could not move file to $fullFilePath");
        echo json_encode($response);
        return;
    }
    
    // Get admin ID from session
    $adminId = $_SESSION['admin_id'] ?? 1; // Default to 1 if not set
    
    // Insert into database
    $stmt = $conn->prepare("
        INSERT INTO learning_materials (title, description, file_type, file_path, file_size, duration, uploaded_by)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    if (!$stmt) {
        // Delete uploaded file if database insert fails
        if (file_exists($fullFilePath)) {
            unlink($fullFilePath);
        }
        $response['error'] = 'Database prepare failed: ' . $conn->error;
        echo json_encode($response);
        return;
    }
    
    $fileSize = $uploadedFile['size'];
    $stmt->bind_param("sssssii", $title, $description, $fileType, $filePath, $fileSize, $duration, $adminId);
    
    if ($stmt->execute()) {
        $materialId = $stmt->insert_id;
        
        // Save categories
        if (!empty($categories)) {
            saveMaterialCategories($materialId, $categories);
        }
        
        $response['success'] = true;
        $response['message'] = 'Material uploaded successfully';
        $response['material_id'] = $materialId;
        $response['file_path'] = $filePath; // Return the web path for reference
    } else {
        // Delete uploaded file if database insert fails
        if (file_exists($fullFilePath)) {
            unlink($fullFilePath);
        }
        $response['error'] = 'Database insert failed: ' . $stmt->error;
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleUpdate() {
    global $conn, $response;
    
    $materialId = $_POST['material_id'] ?? '';
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $categories = isset($_POST['categories']) ? (is_array($_POST['categories']) ? $_POST['categories'] : [$_POST['categories']]) : [];
    
    if (empty($materialId) || empty($title)) {
        $response['error'] = 'Material ID and title are required';
        echo json_encode($response);
        return;
    }
    
    // Check if new file was uploaded
    if (isset($_FILES['new_file']) && $_FILES['new_file']['error'] === UPLOAD_ERR_OK) {
        $uploadedFile = $_FILES['new_file'];
        
        // Get current file info
        $currentStmt = $conn->prepare("SELECT file_path FROM learning_materials WHERE id = ?");
        $currentStmt->bind_param("i", $materialId);
        $currentStmt->execute();
        $currentResult = $currentStmt->get_result();
        $currentMaterial = $currentResult->fetch_assoc();
        $currentStmt->close();
        
        // Delete old file
        if ($currentMaterial && !empty($currentMaterial['file_path'])) {
            $oldFilePath = ROOT_PATH . $currentMaterial['file_path'];
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        
        // Generate unique filename for new file
        $originalName = pathinfo($uploadedFile['name'], PATHINFO_FILENAME);
        $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
        $fileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName) . '.' . $fileExtension;
        $filePath = WEB_UPLOAD_PATH . $fileName;
        $fullFilePath = UPLOAD_DIR . $fileName;
        $fileSize = $uploadedFile['size'];
        
        if (!move_uploaded_file($uploadedFile['tmp_name'], $fullFilePath)) {
            $response['error'] = 'Failed to upload new file';
            echo json_encode($response);
            return;
        }
        
        // Update with new file
        $stmt = $conn->prepare("
            UPDATE learning_materials 
            SET title = ?, description = ?, file_path = ?, file_size = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("ssssi", $title, $description, $filePath, $fileSize, $materialId);
    } else {
        // Update without changing file
        $stmt = $conn->prepare("
            UPDATE learning_materials 
            SET title = ?, description = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param("ssi", $title, $description, $materialId);
    }
    
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    if ($stmt->execute()) {
        // Update categories
        if (!empty($categories)) {
            // Delete existing categories
            $deleteStmt = $conn->prepare("DELETE FROM material_categories WHERE material_id = ?");
            $deleteStmt->bind_param("i", $materialId);
            $deleteStmt->execute();
            $deleteStmt->close();
            
            // Save new categories
            saveMaterialCategories($materialId, $categories);
        }
        
        $response['success'] = true;
        $response['message'] = 'Material updated successfully';
    } else {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    echo json_encode($response);
}

function handleDownload($materialId) {
    global $conn, $response;
    
    if ($materialId === 0) {
        $response['error'] = 'Material ID is required';
        echo json_encode($response);
        return;
    }
    
    // First get the material to verify it exists and get file path
    $stmt = $conn->prepare("SELECT file_path, title, file_type FROM learning_materials WHERE id = ?");
    
    if (!$stmt) {
        $response['error'] = 'Database error: ' . $conn->error;
        echo json_encode($response);
        return;
    }
    
    $stmt->bind_param("i", $materialId);
    
    if (!$stmt->execute()) {
        $response['error'] = 'Failed to execute query: ' . $stmt->error;
        $stmt->close();
        echo json_encode($response);
        return;
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $response['error'] = 'Material not found';
        $stmt->close();
        echo json_encode($response);
        return;
    }
    
    $material = $result->fetch_assoc();
    $stmt->close();
    
    // Verify file exists using consistent path
    $fullFilePath = ROOT_PATH . $material['file_path'];
    if (!file_exists($fullFilePath)) {
        $response['error'] = 'File not found on server: ' . $fullFilePath;
        echo json_encode($response);
        return;
    }
    
    // Increment download count
    $updateStmt = $conn->prepare("UPDATE learning_materials SET download_count = download_count + 1 WHERE id = ?");
    
    if (!$updateStmt) {
        $response['error'] = 'Database error: ' . $conn->error;
        echo json_encode($response);
        return;
    }
    
    $updateStmt->bind_param("i", $materialId);
    
    if ($updateStmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Download count updated';
        $response['file_path'] = $material['file_path']; // Web-accessible path
        $response['file_name'] = $material['title'] . '.' . pathinfo($material['file_path'], PATHINFO_EXTENSION);
        $response['full_path'] = $fullFilePath; // For debugging
    } else {
        $response['error'] = 'Failed to update download count: ' . $updateStmt->error;
    }
    
    $updateStmt->close();
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
    
    $materialId = isset($data['id']) ? (int)$data['id'] : 0;
    
    if ($materialId === 0) {
        $response['error'] = 'Material ID is required';
        echo json_encode($response);
        return;
    }
    
    // Get file path before deletion
    $fileStmt = $conn->prepare("SELECT file_path FROM learning_materials WHERE id = ?");
    $fileStmt->bind_param("i", $materialId);
    $fileStmt->execute();
    $fileResult = $fileStmt->get_result();
    $material = $fileResult->fetch_assoc();
    $fileStmt->close();
    
    // Delete from database
    $stmt = $conn->prepare("DELETE FROM learning_materials WHERE id = ?");
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }
    
    $stmt->bind_param("i", $materialId);
    
    if ($stmt->execute()) {
        // Delete physical file using consistent path
        if ($material && !empty($material['file_path'])) {
            $filePath = ROOT_PATH . $material['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        // Delete categories
        $deleteCategoriesStmt = $conn->prepare("DELETE FROM material_categories WHERE material_id = ?");
        $deleteCategoriesStmt->bind_param("i", $materialId);
        $deleteCategoriesStmt->execute();
        $deleteCategoriesStmt->close();
        
        $response['success'] = true;
        $response['message'] = 'Material deleted successfully';
    } else {
        throw new Exception('Execute failed: ' . $stmt->error);
    }
    
    $stmt->close();
    echo json_encode($response);
}

// Helper function to get material categories
function getMaterialCategories($materialId) {
    global $conn;
    
    $categories = [];
    $stmt = $conn->prepare("SELECT category_name FROM material_categories WHERE material_id = ?");
    $stmt->bind_param("i", $materialId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category_name'];
    }
    
    $stmt->close();
    return $categories;
}

// Helper function to save material categories
function saveMaterialCategories($materialId, $categories) {
    global $conn;
    
    $stmt = $conn->prepare("INSERT INTO material_categories (material_id, category_name) VALUES (?, ?)");
    
    foreach ($categories as $category) {
        $stmt->bind_param("is", $materialId, $category);
        $stmt->execute();
    }
    
    $stmt->close();
}

function getMaterialsStats() {
    global $conn;
    
    $stats = [];
    
    // Total PDFs
    $result = $conn->query("SELECT COUNT(*) as total FROM learning_materials WHERE file_type = 'pdf'");
    $stats['total_pdf'] = $result->fetch_assoc()['total'];
    
    // Total Videos
    $result = $conn->query("SELECT COUNT(*) as total FROM learning_materials WHERE file_type = 'video'");
    $stats['total_videos'] = $result->fetch_assoc()['total'];
    
    // Total Articles
    $result = $conn->query("SELECT COUNT(*) as total FROM learning_materials WHERE file_type = 'article'");
    $stats['total_articles'] = $result->fetch_assoc()['total'];
    
    // Total Downloads
    $result = $conn->query("SELECT SUM(download_count) as total FROM learning_materials");
    $stats['total_downloads'] = $result->fetch_assoc()['total'] ?? 0;
    
    return $stats;
}

// Add this helper function for file size formatting
function formatFileSize($bytes) {
    if ($bytes == 0) return '0 B';
    $k = 1024;
    $sizes = ['B', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}
?>