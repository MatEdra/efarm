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
    
    // Check if farmer exists
    $query = "SELECT id, first_name, last_name, email, password, farm_name, farm_location, farm_size, experience_years 
              FROM farmers 
              WHERE phone_number = '$phone_number'";
    
    $result = $conn->query($query);
    
    if($result->num_rows > 0) {
        $farmer = $result->fetch_assoc();
        
        // Check password (no encryption for development)
        if($password === $farmer['password']) {
            // Set session variables
            $_SESSION['farmer_id'] = $farmer['id'];
            $_SESSION['farmer_name'] = $farmer['first_name'] . ' ' . $farmer['last_name'];
            $_SESSION['farmer_email'] = $farmer['email'];
            $_SESSION['farmer_phone'] = $phone_number;
            $_SESSION['farm_name'] = $farmer['farm_name'];
            $_SESSION['farm_location'] = $farmer['farm_location'];
            $_SESSION['farm_size'] = $farmer['farm_size'];
            $_SESSION['experience_years'] = $farmer['experience_years'];
            $_SESSION['user_type'] = 'farmer';
            $_SESSION['logged_in'] = true;
            
            http_response_code(200);
            echo json_encode(array(
                "success" => true,
                "message" => "Login successful.",
                "user" => [
                    "id" => $farmer['id'],
                    "name" => $farmer['first_name'] . ' ' . $farmer['last_name'],
                    "email" => $farmer['email'],
                    "phone_number" => $phone_number,
                    "farm_name" => $farmer['farm_name'],
                    "farm_location" => $farmer['farm_location'],
                    "farm_size" => $farmer['farm_size'],
                    "experience_years" => $farmer['experience_years'],
                    "type" => "farmer"
                ]
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
            "message" => "Phone number not found."
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