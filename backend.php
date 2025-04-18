<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "properties_db"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch properties from the database
$sql = "SELECT * FROM properties";
$result = $conn->query($sql);

$properties = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $properties[] = array(
            'title' => $row['title'],
            'details' => $row['details'],
            'price' => $row['price'],
            'description' => $row['description'],
            'images' => json_decode($row['images'])
        );
    }
}

// Return the properties as a JSON response
header('Content-Type: application/json');
echo json_encode($properties);

$conn->close();
?>
