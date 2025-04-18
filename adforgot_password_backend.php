<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "hellow"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Check if username exists
    $checkQuery = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($checkQuery);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("Username does not exist.");
    }

    // Update the password in plain text
    $updateQuery = "UPDATE admin SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($updateQuery);
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $newPassword, $username);

    if ($stmt->execute()) {
        header("Location: login.html");
        exit();
    } else {
        die("Error during password reset.");
    }

    $stmt->close();
}

$conn->close();
?>
