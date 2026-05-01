<?php
// Start session and check if farmer is logged in
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_type'] !== 'farmer') {
    // Redirect to login if not authenticated
    header('Location: login.php');
    exit();
}
?>