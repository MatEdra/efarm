<?php
include_once '../../include/conn.php';
include_once '../include/auth.php';

// Get material ID from query string
$materialId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($materialId === 0) {
    http_response_code(400);
    die('Material ID is required');
}

// Get material from database
$stmt = $conn->prepare("SELECT file_path, title, file_type FROM learning_materials WHERE id = ?");
$stmt->bind_param("i", $materialId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404);
    die('Material not found');
}

$material = $result->fetch_assoc();
$stmt->close();

// Build ABSOLUTE file path
$rootPath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'efarm' . DIRECTORY_SEPARATOR;
$filePath = $material['file_path'];
$fileName = basename($filePath);
$fullFilePath = $rootPath . 'uploads' . DIRECTORY_SEPARATOR . 'materials' . DIRECTORY_SEPARATOR . $fileName;

// Verify file exists
if (!file_exists($fullFilePath)) {
    http_response_code(404);
    die('File not found: ' . $fullFilePath);
}

// Increment download count
$updateStmt = $conn->prepare("UPDATE learning_materials SET download_count = download_count + 1 WHERE id = ?");
$updateStmt->bind_param("i", $materialId);
$updateStmt->execute();
$updateStmt->close();

// Set headers for download
$fileName = $material['title'] . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Length: ' . filesize($fullFilePath));
header('Cache-Control: must-revalidate');
header('Pragma: public');

// Output file
readfile($fullFilePath);
exit;
?>