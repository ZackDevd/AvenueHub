<?php
// === Database Connection ===

$host = "localhost";     // Database host (default for XAMPP)
$user = "root";          // Default XAMPP user
$pass = "";              // Leave empty if no password
$dbname = "avenuehub_db"; // Your database name

// === Create Connection ===
$conn = new mysqli($host, $user, $pass, $dbname);

// === Check Connection ===
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Optional: set character encoding (important for text data)
$conn->set_charset("utf8mb4");
?>
