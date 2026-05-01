<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

$response = ['success' => false, 'data' => []];

try {
    // Total Farmers
    $result = $conn->query("SELECT COUNT(*) as total FROM farmers");
    $totalFarmers = $result->fetch_assoc()['total'];

    // Total Learning Materials
    $result = $conn->query("SELECT COUNT(*) as total FROM learning_materials");
    $totalMaterials = $result->fetch_assoc()['total'];

    // Active Farms
    $result = $conn->query("SELECT COUNT(*) as total FROM farmers WHERE farm_name IS NOT NULL AND farm_location IS NOT NULL");
    $activeFarms = $result->fetch_assoc()['total'];

    // Weather Alerts
    $result = $conn->query("SELECT COUNT(*) as total FROM weather_data WHERE date = CURDATE() AND precipitation > 50");
    $weatherAlerts = $result->fetch_assoc()['total'];

    // Weekly farmer growth
    $result = $conn->query("SELECT COUNT(*) as weekly_growth FROM farmers WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $weeklyGrowth = $result->fetch_assoc()['weekly_growth'];

    // Monthly material growth
    $result = $conn->query("SELECT COUNT(*) as monthly_growth FROM learning_materials WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
    $monthlyGrowth = $result->fetch_assoc()['monthly_growth'];

    $response['success'] = true;
    $response['data'] = [
        'total_farmers' => (int)$totalFarmers,
        'total_materials' => (int)$totalMaterials,
        'active_farms' => (int)$activeFarms,
        'weather_alerts' => (int)$weatherAlerts,
        'weekly_growth' => (int)$weeklyGrowth,
        'monthly_growth' => (int)$monthlyGrowth
    ];

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
?>