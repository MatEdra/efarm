<?php
session_start();
header('Content-Type: application/json');
include_once '../../include/conn.php';

if (!isset($_SESSION['farmer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

try {
    // Get learning materials with their categories
    $query = "
        SELECT 
            lm.*,
            GROUP_CONCAT(mc.category_name) as categories
        FROM learning_materials lm
        LEFT JOIN material_categories mc ON lm.id = mc.material_id
        WHERE lm.file_path IS NOT NULL
        GROUP BY lm.id
        ORDER BY lm.created_at DESC
    ";
    
    $result = $conn->query($query);
    
    $materials = [];
    while ($row = $result->fetch_assoc()) {
        $categories = $row['categories'] ? explode(',', $row['categories']) : [];
        
        // Format file size for display
        $fileSize = formatFileSize($row['file_size']);
        
        $materials[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'description' => $row['description'],
            'file_type' => $row['file_type'],
            'file_path' => $row['file_path'],
            'file_size' => $fileSize,
            'duration' => $row['duration'],
            'download_count' => $row['download_count'],
            'created_at' => $row['created_at'],
            'categories' => $categories
        ];
    }

    echo json_encode([
        'success' => true,
        'data' => $materials
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching learning materials: ' . $e->getMessage()]);
}

function formatFileSize($bytes) {
    if ($bytes == 0) return '0 B';
    $k = 1024;
    $sizes = ['B', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes) / log($k));
    return round($bytes / pow($k, $i), 2) . ' ' . $sizes[$i];
}

$conn->close();
?>