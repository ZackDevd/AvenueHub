<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "business_web";

$conn = new mysqli($host, $user, $pass, $db);

// Set charset
$conn->set_charset("utf8mb4");

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
