<?php
$servername = getenv('DB_HOST') ?: 'localhost';
$port       = (int)(getenv('DB_PORT') ?: 3306);
$username   = getenv('DB_USER') ?: 'root';
$password   = getenv('DB_PASS') ?: '';
$dbname     = getenv('DB_NAME') ?: 'smart_farming_ph';

$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
