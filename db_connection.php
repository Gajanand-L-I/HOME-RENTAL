<?php
// Database configuration
$servername = "localhost";  // Database host (use "localhost" for local servers)
$username = "root";         // Database username (typically "root" for local servers)
$password = "";             // Database password (empty for local development)
$dbname = "login_system";    // Replace with your database name

// Create connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
