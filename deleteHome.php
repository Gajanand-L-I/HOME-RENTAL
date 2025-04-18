<?php
// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the property ID from the POST data
    $homeId = $_POST['home_id'];

    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "home_rental";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to delete the home from the database
    $sql = "DELETE FROM homes WHERE id = $homeId";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the home page after successful deletion
        header('Location: adminhome.html');
    } else {
        echo "Error deleting property: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
