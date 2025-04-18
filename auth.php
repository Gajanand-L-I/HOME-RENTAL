<?php
// Database connection
$servername = "localhost"; // Host name
$username = "root";        // Database username
$password = "";            // Database password
$dbname = "hii";           // Database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check if email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Directly compare the entered password with the stored password (plain text)
        if ($password === $user['password']) {
            echo "Login successful.";
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "No user found with that email.";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
