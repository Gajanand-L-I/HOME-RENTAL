<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "home_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the user's query
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Initialize SQL query
$sql = "SELECT * FROM homes WHERE 1"; // Start with a general SELECT query

// Initialize filter string for feedback
$filterString = 'Filters applied: ';

// Flag to check if any valid filters are found
$validFilterFound = false;

// Check if the query asks for available homes
if (preg_match('/available\s*homes/i', $query)) {
    // Modify SQL to show all homes
    $sql = "SELECT * FROM homes"; // No filtering, show all homes
    $filterString = "No filters applied: Showing all homes";
    $validFilterFound = true;
}

// Process the user's query for price, beds, baths, and description

// Handle price filters (e.g., price >= 10000, price <= 5000)
if (preg_match('/price\s*(<=?|>=?|=)\s*(\d+)/i', $query, $matches)) {
    $operator = $matches[1]; // Comparison operator (<=, >=, =)
    $price = (int)$matches[2]; // Price value
    $sql .= " AND price $operator $price"; // Add the filter to the SQL query
    $filterString .= "Price: $operator $price, ";
    $validFilterFound = true;
} elseif (preg_match('/price\s*(\d+)/i', $query, $matches)) {
    // If no operator is provided, assume an exact price match
    $price = (int)$matches[1];
    $sql .= " AND price = $price";
    $filterString .= "Price: = $price, ";
    $validFilterFound = true;
}

// Handle beds filters (e.g., beds >= 2)
if (preg_match('/(\d+)\s*beds?/i', $query, $matches)) {
    $beds = (int)$matches[1];
    $sql .= " AND beds >= $beds"; // Filter homes with at least the requested number of beds
    $filterString .= "Beds: >= $beds, ";
    $validFilterFound = true;
}

// Handle baths filters (e.g., baths >= 2)
if (preg_match('/(\d+)\s*baths?/i', $query, $matches)) {
    $baths = (int)$matches[1];
    $sql .= " AND baths >= $baths"; // Filter homes with at least the requested number of bathrooms
    $filterString .= "Bathrooms: >= $baths, ";
    $validFilterFound = true;
}

// Handle description filters (e.g., description: MARATHA)
if (preg_match('/description\s*[:=]?\s*(\w.+)/i', $query, $matches)) {
    $description = $matches[1];
    $sql .= " AND description LIKE '%$description%'"; // Filter homes with matching description
    $filterString .= "Description: $description, ";
    $validFilterFound = true;
}

// Remove the last comma and space from the filter string
$filterString = rtrim($filterString, ', ');

// Check if any valid filters were applied
if (!$validFilterFound) {
    // If no valid filters were found, return a helpful message to the user
    $response = "No valid filters found. Please enter a valid query. Here are some examples of how you can search:<br>";
    $response .= "<ul><li>Search by price: price 10000 or price >= 10000</li>";
    $response .= "<li>Search by number of beds: 2 beds</li>";
    $response .= "<li>Search by number of baths: 1 baths</li>";
    $response .= "<li>Search by description: description: Modern</li></ul>";
    $response .= "<strong>To see all available homes, simply type 'available homes'.</strong>";
    echo $response;
    $conn->close();
    exit;
}

// Fetch homes from the database based on the filters
$result = $conn->query($sql);

// Prepare the response based on the results
$response = '';
if ($result->num_rows > 0) {
    // Loop through the results and display each home
    while ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $beds = $row['beds'];
        $baths = $row['baths'];
        $price = $row['price'];
        $description = $row['description'];

        // Add HTML content for each home
        $response .= "<div style='margin-bottom: 20px;'>";
        $response .= "<strong>Home:</strong> $name<br>";
        $response .= "<strong>Price:</strong> ₹$price<br>";
        $response .= "<strong>Bedrooms:</strong> $beds | <strong>Bathrooms:</strong> $baths<br>";
        $response .= "<strong>Description:</strong> $description<br>";
        $response .= "</div>";
    }
} else {
    // If no homes match the filters, show this message
    $response = "No homes found matching your criteria: $filterString. Please refine your search.";
}

// Output the response
echo "<h2>$filterString</h2>";
echo $response;

$conn->close();
?>
