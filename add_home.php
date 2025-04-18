<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $beds = mysqli_real_escape_string($conn, $_POST['beds']);
    $baths = mysqli_real_escape_string($conn, $_POST['baths']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']); // Capturing the description

    if (isset($_FILES['image'])) {
        $image = $_FILES['image'];
        $imageName = $image['name'];
        $imageTmpName = $image['tmp_name'];

        $uploadsDir = __DIR__ . '/uploads/';
        if (!file_exists($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $imageNewName = uniqid('', true) . "." . pathinfo($imageName, PATHINFO_EXTENSION);
        $imageDestination = $uploadsDir . $imageNewName;

        if ($image['error'] === 0) {
            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                // Modify the query to include description
                $stmt = $conn->prepare("INSERT INTO homes (name, beds, baths, price, description, image_url) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssdss", $name, $beds, $baths, $price, $description, $imageNewName);

                if ($stmt->execute()) {
                    echo "success";
                } else {
                    echo "Error: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "Error moving the uploaded image!";
            }
        } else {
            echo "Error uploading image!";
        }
    } else {
        echo "No image uploaded!";
    }
}

$conn->close();
?>
