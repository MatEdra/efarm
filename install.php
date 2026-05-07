<?php
include_once 'include/conn.php';

$sql = file_get_contents(__DIR__ . '/smart_farming_ph.sql');

// Remove comments and split into individual statements
$sql = preg_replace('/--.*$/m', '', $sql);
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);
$statements = array_filter(array_map('trim', explode(';', $sql)));

$errors = [];
$success = 0;

foreach ($statements as $statement) {
    if (empty($statement)) continue;
    if ($conn->query($statement)) {
        $success++;
    } else {
        $errors[] = htmlspecialchars($conn->error) . '<br><small>' . htmlspecialchars(substr($statement, 0, 100)) . '</small>';
    }
}

echo "<h3>Install Results</h3>";
echo "Statements executed: <b>$success</b><br>";
echo "Errors: <b>" . count($errors) . "</b><br><br>";

if (!empty($errors)) {
    echo "<b>Error details:</b><br>";
    foreach ($errors as $e) echo "- $e<br><br>";
} else {
    echo "<b style='color:green'>All tables created and data imported successfully!</b>";
}
?>
