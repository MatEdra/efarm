<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database connection
include_once '../include/conn.php';

// Get POST data
$data = json_decode(file_get_contents("php://input"));

if(!empty($data->phone_number) && !empty($data->password)) {
    $phone_number = $conn->real_escape_string($data->phone_number);
    $password = $conn->real_escape_string($data->password);
    
    // Check if admin exists
    $query = "SELECT id, first_name, last_name, email, password, role 
              FROM admins 
              WHERE phone_number = '$phone_number'";
    
    $result = $conn->query($query);
    
    if($result->num_rows > 0) {
        $admin = $result->fetch_assoc();
        
        // Check password
        if($password === $admin['password']) {
            // Set session variables
            $_SESSION['user'] = array(
                "id" => $admin['id'],
                "name" => $admin['first_name'] . ' ' . $admin['last_name'],
                "email" => $admin['email'],
                "phone_number" => $phone_number,
                "role" => $admin['role'],
                "type" => "admin"
            );
            
            http_response_code(200);
            echo json_encode(array(
                "success" => true,
                "message" => "Admin login successful.",
                "user" => $_SESSION['user']
            ));
        } else {
            http_response_code(401);
            echo json_encode(array(
                "success" => false, 
                "message" => "Invalid password."
            ));
        }
    } else {
        http_response_code(401);
        echo json_encode(array(
            "success" => false, 
            "message" => "Admin phone number not found."
        ));
    }
} else {
    http_response_code(400);
    echo json_encode(array(
        "success" => false, 
        "message" => "Phone number and password are required."
    ));
}

$conn->close();
?>