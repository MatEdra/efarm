<?php
include_once '../../include/conn.php';
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log the request
file_put_contents('debug.log', "Method: " . $_SERVER['REQUEST_METHOD'] . "\n", FILE_APPEND);
file_put_contents('debug.log', "Input: " . file_get_contents('php://input') . "\n", FILE_APPEND);

$method = $_SERVER['REQUEST_METHOD'];
$response = ['success' => false, 'message' => ''];

try {
    switch ($method) {
        case 'GET':
            handleGetRequest();
            break;
        case 'POST':
            handlePostRequest();
            break;
        case 'PUT':
            handlePutRequest();
            break;
        case 'DELETE':
            handleDeleteRequest();
            break;
        default:
            $response['error'] = 'Method not allowed';
            http_response_code(405);
            echo json_encode($response);
            break;
    }
} catch (Exception $e) {
    file_put_contents('debug.log', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
    $response['error'] = $e->getMessage();
    http_response_code(500);
    echo json_encode($response);
}

// ... include the same functions as before but add more debugging