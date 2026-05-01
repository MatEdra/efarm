<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

$response = ['success' => false, 'data' => []];

try {
    $result = $conn->query("
        SELECT 
            COALESCE(farm_location, 'Not specified') as region,
            COUNT(*) as total_farmers,
            SUM(CASE WHEN farm_name IS NOT NULL THEN 1 ELSE 0 END) as active_farms
        FROM farmers
        GROUP BY farm_location
        ORDER BY total_farmers DESC
        LIMIT 5
    ");
    
    $regions = $result->fetch_all(MYSQLI_ASSOC);

    $response['success'] = true;
    $response['data'] = $regions;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>