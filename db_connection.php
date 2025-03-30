<?php
// Database Connection
$servername = "localhost";
$username = "root";
$password = ""; // Default for XAMPP is an empty password
$database = "online_exam_system"; // Change this if your database name is different

$conn = new mysqli($servername, $username, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
