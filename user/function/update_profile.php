<?php
session_start();
header('Content-Type: application/json');
include_once '../../include/conn.php';

if (!isset($_SESSION['farmer_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$farmer_id = $_SESSION['farmer_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get form data
        $first_name = $conn->real_escape_string($_POST['first_name'] ?? '');
        $last_name = $conn->real_escape_string($_POST['last_name'] ?? '');
        $email = $conn->real_escape_string($_POST['email'] ?? '');
        $phone_number = $conn->real_escape_string($_POST['phone_number'] ?? '');
        $farm_name = $conn->real_escape_string($_POST['farm_name'] ?? '');
        $farm_location = $conn->real_escape_string($_POST['farm_location'] ?? '');
        $farm_size = floatval($_POST['farm_size'] ?? 0);
        $experience_years = intval($_POST['experience_years'] ?? 0);
        $crops = isset($_POST['crops']) ? $_POST['crops'] : [];

        // Validate required fields
        if (empty($first_name) || empty($last_name) || empty($email) || empty($phone_number)) {
            throw new Exception('All required fields must be filled');
        }

        // Check if email or phone already exists (excluding current user)
        $check_stmt = $conn->prepare("SELECT id FROM farmers WHERE (email = ? OR phone_number = ?) AND id != ?");
        $check_stmt->bind_param("ssi", $email, $phone_number, $farmer_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            throw new Exception('Email or phone number already exists');
        }
        $check_stmt->close();

        // Update farmer information
        $update_stmt = $conn->prepare("UPDATE farmers SET first_name = ?, last_name = ?, email = ?, phone_number = ?, farm_name = ?, farm_location = ?, farm_size = ?, experience_years = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->bind_param("ssssssdii", $first_name, $last_name, $email, $phone_number, $farm_name, $farm_location, $farm_size, $experience_years, $farmer_id);

        if ($update_stmt->execute()) {
            // Update crops
            $delete_stmt = $conn->prepare("DELETE FROM farmer_crops WHERE farmer_id = ?");
            $delete_stmt->bind_param("i", $farmer_id);
            $delete_stmt->execute();
            $delete_stmt->close();

            // Insert new crops
            if (!empty($crops)) {
                $insert_stmt = $conn->prepare("INSERT INTO farmer_crops (farmer_id, crop_type) VALUES (?, ?)");
                foreach ($crops as $crop) {
                    $crop_type = $conn->real_escape_string($crop);
                    $insert_stmt->bind_param("is", $farmer_id, $crop_type);
                    $insert_stmt->execute();
                }
                $insert_stmt->close();
            }

            echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
        } else {
            throw new Exception('Failed to update profile: ' . $conn->error);
        }

        $update_stmt->close();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

$conn->close();
?>