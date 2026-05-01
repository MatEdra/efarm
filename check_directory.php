<?php
// check_directory.php
echo "<h2>Directory Structure Check</h2>";

$baseDir = __DIR__;
$uploadDir = $baseDir . '/uploads/materials/';

echo "<p>Base Directory: " . $baseDir . "</p>";
echo "<p>Upload Directory: " . $uploadDir . "</p>";

// Check if directory exists
if (!is_dir($uploadDir)) {
    echo "<p style='color: red;'>Upload directory does NOT exist!</p>";
    
    // Try to create it
    if (mkdir($uploadDir, 0755, true)) {
        echo "<p style='color: green;'>✓ Upload directory created successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Failed to create upload directory</p>";
        echo "<p>Error: " . error_get_last()['message'] . "</p>";
    }
} else {
    echo "<p style='color: green;'>✓ Upload directory exists</p>";
}

// Check if directory is writable
if (is_writable($uploadDir)) {
    echo "<p style='color: green;'>✓ Upload directory is writable</p>";
} else {
    echo "<p style='color: red;'>✗ Upload directory is NOT writable</p>";
    
    // Try to fix permissions (Windows)
    if (chmod($uploadDir, 0755)) {
        echo "<p style='color: green;'>✓ Permissions fixed</p>";
    } else {
        echo "<p style='color: red;'>✗ Could not fix permissions</p>";
    }
}

// Test file creation
$testFile = $uploadDir . 'test_file.txt';
if (file_put_contents($testFile, 'Test content ' . date('Y-m-d H:i:s'))) {
    echo "<p style='color: green;'>✓ File creation test successful</p>";
    
    // Read it back
    $content = file_get_contents($testFile);
    echo "<p>Test file content: " . $content . "</p>";
    
    // Delete test file
    if (unlink($testFile)) {
        echo "<p style='color: green;'>✓ Test file deleted successfully</p>";
    } else {
        echo "<p style='color: red;'>✗ Could not delete test file</p>";
    }
} else {
    echo "<p style='color: red;'>✗ File creation test failed</p>";
}

// List current files in upload directory
echo "<h3>Current files in upload directory:</h3>";
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    echo "<ul>";
    foreach ($files as $file) {
        if ($file != '.' && $file != '..') {
            $filePath = $uploadDir . $file;
            $fileSize = filesize($filePath);
            $fileTime = date('Y-m-d H:i:s', filemtime($filePath));
            echo "<li>{$file} ({$fileSize} bytes, modified: {$fileTime})</li>";
        }
    }
    echo "</ul>";
}

echo "<h3>Server Information:</h3>";
echo "<p>PHP Version: " . PHP_VERSION . "</p>";
echo "<p>Server Software: " . $_SERVER['SERVER_SOFTWARE'] . "</p>";
echo "<p>Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
?>