<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hellow"; // Changed database name to 'hellow'

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];  // Plain text password
    $confirmPassword = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $upi_id = $_POST['upi_id'];
    $bank_account = $_POST['bank_account'];
    $ifsc_code = $_POST['ifsc_code'];
    $role = 'admin'; // Default to 'admin', or you can conditionally set this

    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
        exit();
    }

    // Check if email or username already exists
    $checkQuery = "SELECT * FROM admin WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $email, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email or username already in use.";
    } else {
        // Insert new admin into the database (without hashing the password)
        $insertQuery = "INSERT INTO admin (name, email, username, password, phone, upi_id, bank_account, ifsc_code, role) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssssssss", $name, $email, $username, $password, $phone, $upi_id, $bank_account, $ifsc_code, $role);

        if ($stmt->execute()) {
            echo "Admin successfully registered!";
            // Redirect to login page after successful registration
            header("Location: login.html"); 
            exit();
        } else {
            echo "Error during registration.";
        }
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
