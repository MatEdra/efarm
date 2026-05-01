<?php
session_start();
header('Content-Type: application/json');
include_once '../../include/conn.php';

if (!isset($_SESSION['farmer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
$material_id = $input['material_id'] ?? null;

if (!$material_id) {
    echo json_encode(['success' => false, 'message' => 'Material ID is required']);
    exit();
}

try {
    // Increment download count
    $stmt = $conn->prepare("UPDATE learning_materials SET download_count = download_count + 1 WHERE id = ?");
    $stmt->bind_param("i", $material_id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Download count updated']);
    } else {
        throw new Exception('Failed to update download count');
    }
    
    $stmt->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

$conn->close();
?>