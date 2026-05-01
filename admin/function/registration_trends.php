<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

$response = ['success' => false, 'data' => []];

try {
    $result = $conn->query("
        SELECT 
            MONTHNAME(created_at) as month,
            MONTH(created_at) as month_num,
            COUNT(*) as registrations
        FROM farmers 
        WHERE YEAR(created_at) = YEAR(CURDATE())
        GROUP BY MONTH(created_at), MONTHNAME(created_at)
        ORDER BY month_num
    ");
    
    $trends = $result->fetch_all(MYSQLI_ASSOC);

    $response['success'] = true;
    $response['data'] = $trends;

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>