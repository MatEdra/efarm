<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Include database connection
include_once '../include/conn.php';

// Get POST data
$data = json_decode(file_get_contents("php://input"));

// Check required fields
if(
    !empty($data->firstName) &&
    !empty($data->lastName) &&
    !empty($data->email) &&
    !empty($data->phoneNumber) &&
    !empty($data->password) &&
    !empty($data->farmLocation) &&
    !empty($data->gender)
) {
    // Sanitize input data
    $first_name = $conn->real_escape_string($data->firstName);
    $last_name = $conn->real_escape_string($data->lastName);
    $email = $conn->real_escape_string($data->email);
    $phone_number = $conn->real_escape_string($data->phoneNumber);
    $password = $conn->real_escape_string($data->password);
    $gender = $conn->real_escape_string($data->gender);
    $farm_name = $conn->real_escape_string($data->farmName ?? '');
    $farm_location = $conn->real_escape_string($data->farmLocation);
    $farm_size = $conn->real_escape_string($data->farmSize ?? 0);
    
    // Validate gender value
    $valid_genders = ['Male', 'Female', 'Other'];
    if (!in_array($gender, $valid_genders)) {
        http_response_code(400);
        echo json_encode(array(
            "success" => false, 
            "message" => "Invalid gender value. Must be Male, Female, or Other."
        ));
        $conn->close();
        exit;
    }
    
    // Check if phone number or email already exists
    $check_query = "SELECT id FROM farmers WHERE phone_number = '$phone_number' OR email = '$email'";
    $check_result = $conn->query($check_query);
    
    if($check_result->num_rows > 0) {
        http_response_code(400);
        echo json_encode(array(
            "success" => false, 
            "message" => "Phone number or email already exists."
        ));
    } else {
        // Hash password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert farmer data with gender
        $insert_query = "INSERT INTO farmers (first_name, last_name, email, phone_number, password, gender, farm_name, farm_location, farm_size, experience_years) 
                        VALUES ('$first_name', '$last_name', '$email', '$phone_number', '$hashed_password', '$gender', '$farm_name', '$farm_location', '$farm_size', 0)";
        
        if($conn->query($insert_query)) {
            $farmer_id = $conn->insert_id;
            
            // Insert farmer crops if provided
            if(!empty($data->crops) && is_array($data->crops)) {
                foreach($data->crops as $crop) {
                    $crop_clean = $conn->real_escape_string($crop);
                    $crop_query = "INSERT INTO farmer_crops (farmer_id, crop_type) VALUES ('$farmer_id', '$crop_clean')";
                    $conn->query($crop_query);
                }
            }
            
            http_response_code(201);
            echo json_encode(array(
                "success" => true,
                "message" => "Farmer account created successfully.",
                "user" => array(
                    "id" => $farmer_id,
                    "name" => $first_name . ' ' . $last_name,
                    "email" => $email,
                    "phone_number" => $phone_number,
                    "gender" => $gender,
                    "location" => $farm_location,
                    "type" => "farmer"
                )
            ));
        } else {
            http_response_code(500);
            echo json_encode(array(
                "success" => false, 
                "message" => "Error creating account: " . $conn->error
            ));
        }
    }
} else {
    http_response_code(400);
    $missing_fields = [];
    if (empty($data->firstName)) $missing_fields[] = "firstName";
    if (empty($data->lastName)) $missing_fields[] = "lastName";
    if (empty($data->email)) $missing_fields[] = "email";
    if (empty($data->phoneNumber)) $missing_fields[] = "phoneNumber";
    if (empty($data->password)) $missing_fields[] = "password";
    if (empty($data->farmLocation)) $missing_fields[] = "farmLocation";
    if (empty($data->gender)) $missing_fields[] = "gender";
    
    echo json_encode(array(
        "success" => false, 
        "message" => "Unable to create farmer account. All required fields must be filled.",
        "missing_fields" => $missing_fields
    ));
}

$conn->close();
?>