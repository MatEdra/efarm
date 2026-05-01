<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

$response = ['success' => false, 'data' => []];
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;

try {
    $stmt = $conn->prepare("
        SELECT 
            CONCAT(first_name, ' ', last_name) as name, 
            phone_number as phone, 
            COALESCE(farm_location, 'Not specified') as location, 
            COALESCE(farm_size, 0) as farm_size, 
            DATE(created_at) as join_date,
            'Active' as status
        FROM farmers 
        ORDER BY created_at DESC 
        LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $farmers = $result->fetch_all(MYSQLI_ASSOC);

    $response['success'] = true;
    $response['data'] = $farmers;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>