<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch homes from the database
$sql = "SELECT * FROM homes";
$result = $conn->query($sql);

// Check if homes exist and generate HTML output
$homes = '';
if ($result->num_rows > 0) {
    // Fetch each row and generate HTML
    while($row = $result->fetch_assoc()) {
        // Extracting home details from the database
        $name = $row['name'];
        $beds = $row['beds'];
        $baths = $row['baths'];
        $price = $row['price'];
        $image_url = $row['image_url'];

        // Check if description is available, if not set a default message
        $description = isset($row['description']) ? $row['description'] : 'No description available';

        // Assuming images are stored in an "images" folder relative to your project root
        $image_path = 'uploads/' . $image_url;  // Relative path to the image

        // Fallback to a default image if the specific image is not found
        if (!file_exists($image_path)) {
            $image_path = 'images/default.jpg';  // Provide a default image if the file doesn't exist
        }

        // Append the home details and image to the output HTML
        $homes .= '<div class="property">
                    <img src="' . $image_path . '" alt="' . $name . '">
                    <h3>' . $name . '</h3>
                    <p>Bedrooms: ' . $beds . ' | Bathrooms: ' . $baths . '</p>
                    <p>Price: ₹' . number_format($price, 2) . '</p>
                    <p>' . $description . '</p>
                    <a href="details.html?home_id=' . $row['id'] . '" class="btn-details">View Details</a>
                    <form action="deleteHome.php" method="POST" style="display:inline;">
                        <input type="hidden" name="home_id" value="' . $row['id'] . '">
                        <button type="submit" class="delete-btn">❌</button>
                    </form>
                </div>';
    }
} else {
    $homes = '<p>No homes available. Please add some properties.</p>';
}

// Output the HTML
echo $homes;

// Close the database connection
$conn->close();
?>
