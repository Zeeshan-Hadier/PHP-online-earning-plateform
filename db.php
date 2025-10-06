<?php
// Database configuration
$host = "localhost";     // Usually 'localhost'
$user = "root";          // Your MySQL username
$pass = "";              // Your MySQL password
$db   = "profitlux";     // Database name

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set character set to UTF-8
$conn->set_charset("utf8");

// ✅ Connected successfully
// echo "Database connected successfully";
?>
