<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

$response = ['success' => false, 'data' => []];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 4;

try {
    $result = $conn->query("
        (SELECT 'farmer_registered' as type, CONCAT('New farmer: ', first_name, ' ', last_name) as description, created_at as activity_date 
         FROM farmers ORDER BY created_at DESC LIMIT 1)
        UNION
        (SELECT 'material_uploaded' as type, CONCAT('New material: ', title) as description, created_at as activity_date 
         FROM learning_materials ORDER BY created_at DESC LIMIT 1)
        UNION
        (SELECT 'weather_alert' as type, CONCAT('Weather update for ', location) as description, created_at as activity_date 
         FROM weather_data ORDER BY created_at DESC LIMIT 1)
        UNION
        (SELECT 'report_generated' as type, 'Monthly report generated' as description, NOW() as activity_date)
        ORDER BY activity_date DESC
        LIMIT $limit
    ");
    
    $activities = $result->fetch_all(MYSQLI_ASSOC);

    $response['success'] = true;
    $response['data'] = $activities;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>