<?php
session_start();
include_once '../../include/conn.php';

if (!isset($_SESSION['farmer_id'])) {
    http_response_code(401);
    die('Unauthorized access');
}

$material_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($material_id === 0) {
    http_response_code(400);
    die('Material ID is required');
}

try {
    // Get material information
    $stmt = $conn->prepare("SELECT file_path, title, file_type FROM learning_materials WHERE id = ?");
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        http_response_code(404);
        die('Material not found');
    }
    
    $material = $result->fetch_assoc();
    $stmt->close();
    
    // Build the absolute file path
    $rootPath = $_SERVER['DOCUMENT_ROOT'] . '/efarm/';
    $filePath = $material['file_path'];
    $fullFilePath = $rootPath . $filePath;
    
    // Verify file exists
    if (!file_exists($fullFilePath)) {
        http_response_code(404);
        die('File not found: ' . $fullFilePath);
    }
    
    // Increment download count
    $updateStmt = $conn->prepare("UPDATE learning_materials SET download_count = download_count + 1 WHERE id = ?");
    $updateStmt->bind_param("i", $material_id);
    $updateStmt->execute();
    $updateStmt->close();
    
    // Set headers for file download
    $fileName = $material['title'] . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fullFilePath));
    
    // Clear output buffer
    ob_clean();
    flush();
    
    // Read the file and output it
    readfile($fullFilePath);
    exit;
    
} catch (Exception $e) {
    http_response_code(500);
    die('Server error: ' . $e->getMessage());
}

$conn->close();
?>