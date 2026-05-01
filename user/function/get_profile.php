<?php
session_start();
header('Content-Type: application/json');
include_once '../../include/conn.php';

if (!isset($_SESSION['farmer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$farmer_id = $_SESSION['farmer_id'];

try {
    // Get farmer data
    $stmt = $conn->prepare("SELECT * FROM farmers WHERE id = ?");
    $stmt->bind_param("i", $farmer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $farmer = $result->fetch_assoc();
    $stmt->close();

    if (!$farmer) {
        echo json_encode(['success' => false, 'message' => 'Farmer not found']);
        exit();
    }

    // Get farmer crops
    $crop_stmt = $conn->prepare("SELECT crop_type FROM farmer_crops WHERE farmer_id = ?");
    $crop_stmt->bind_param("i", $farmer_id);
    $crop_stmt->execute();
    $crop_result = $crop_stmt->get_result();
    $crops = [];
    while ($row = $crop_result->fetch_assoc()) {
        $crops[] = $row['crop_type'];
    }
    $crop_stmt->close();

    // Return data
    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $farmer['id'],
            'first_name' => $farmer['first_name'],
            'last_name' => $farmer['last_name'],
            'email' => $farmer['email'],
            'phone_number' => $farmer['phone_number'],
            'farm_name' => $farmer['farm_name'],
            'farm_location' => $farmer['farm_location'],
            'farm_size' => $farmer['farm_size'],
            'experience_years' => $farmer['experience_years'],
            'last_login' => $farmer['last_login'],
            'created_at' => $farmer['created_at'],
            'crops' => $crops
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error fetching profile: ' . $e->getMessage()]);
}

$conn->close();
?>