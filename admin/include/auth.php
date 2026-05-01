<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['type'] !== 'admin') {
    header('Location: ../admin-login.php');
    exit();
}

$user = $_SESSION['user'];
?>