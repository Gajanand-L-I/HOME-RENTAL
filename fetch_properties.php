<?php
set_time_limit(120); // Set execution time to 120 seconds

// Database connection settings
$host = 'localhost'; // your database host
$db = 'login'; // your database name
$user = 'root'; // your database username
$pass = ''; // your database password

// Create connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch properties from the database
$sql = "SELECT title, details, price, description, images FROM properties";
$result = $conn->query($sql);

$properties = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $row['images'] = json_decode($row['images']);
        $properties[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($properties);
?>
