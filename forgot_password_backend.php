<?php
// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "hii"; 

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
        echo "Passwords do not match.";
        exit();
    }

    // Check if username exists
    $checkQuery = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "Username does not exist.";
    } else {
        // Update the password in plain text
        $updateQuery = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $newPassword, $username);

        if ($stmt->execute()) {
            echo "Password reset successful.";
            // Redirect to login page after successful reset
            header("Location: login.html");
            exit();
        } else {
            echo "Error during password reset.";
        }
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
