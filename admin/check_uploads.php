<?php
// check_uploads.php - Place this in your project root
$uploadDir = 'uploads/materials/';

echo "<h2>Upload Directory Check</h2>";
echo "<p>Checking directory: " . realpath($uploadDir) . "</p>";

if (!is_dir($uploadDir)) {
    echo "<p style='color: red;'>Directory does not exist!</p>";
    if (mkdir($uploadDir, 0755, true)) {
        echo "<p style='color: green;'>Directory created successfully</p>";
    } else {
        echo "<p style='color: red;'>Failed to create directory</p>";
    }
} else {
    echo "<p style='color: green;'>Directory exists</p>";
}

if (is_writable($uploadDir)) {
    echo "<p style='color: green;'>Directory is writable</p>";
} else {
    echo "<p style='color: red;'>Directory is NOT writable</p>";
}

// List files
echo "<h3>Files in upload directory:</h3>";
$files = scandir($uploadDir);
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $filePath = $uploadDir . $file;
        echo "<li>" . $file . " (" . filesize($filePath) . " bytes)</li>";
    }
}
echo "</ul>";

// Test file creation
echo "<h3>Test File Creation:</h3>";
$testFile = $uploadDir . 'test.txt';
if (file_put_contents($testFile, 'Test content ' . date('Y-m-d H:i:s'))) {
    echo "<p style='color: green;'>File creation successful: " . $testFile . "</p>";
    echo "<p>File content: " . file_get_contents($testFile) . "</p>";
    unlink($testFile);
    echo "<p style='color: green;'>Test file deleted</p>";
} else {
    echo "<p style='color: red;'>File creation failed</p>";
}
?>  