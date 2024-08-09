<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_happens"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories and their event counts
$sql = "SELECT c.category_name, COUNT(p.product_id) AS event_count 
        FROM category c
        LEFT JOIN product p ON c.category_id = p.category_id
        GROUP BY c.category_id";

$result = $conn->query($sql);

$categories = [];
$eventCounts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row['category_name'];
        $eventCounts[] = $row['event_count'];
    }
}

$conn->close();

// Return data as JSON
echo json_encode(['categories' => $categories, 'eventCounts' => $eventCounts]);

?>
