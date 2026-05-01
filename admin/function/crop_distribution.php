<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

$response = ['success' => false, 'data' => []];

try {
    $result = $conn->query("
        SELECT 
            crop_type as crop,
            COUNT(*) as count
        FROM farmer_crops 
        GROUP BY crop_type 
        ORDER BY count DESC
        LIMIT 5
    ");
    
    $crops = $result->fetch_all(MYSQLI_ASSOC);

    $response['success'] = true;
    $response['data'] = $crops;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>