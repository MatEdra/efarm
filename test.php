<?php
$host = getenv('DB_HOST') ?: 'localhost';
$port = (int)(getenv('DB_PORT') ?: 3306);
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$name = getenv('DB_NAME') ?: 'smart_farming_ph';

echo "Host: $host<br>";
echo "Port: $port<br>";
echo "User: $user<br>";
echo "DB: $name<br><br>";

$conn = new mysqli($host, $user, $pass, $name, $port);

if ($conn->connect_error) {
    echo "Connection FAILED: " . $conn->connect_error;
} else {
    echo "Connection OK<br><br>";
    $result = $conn->query("SHOW TABLES");
    echo "Tables:<br>";
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "<br>";
    }
}
?>
