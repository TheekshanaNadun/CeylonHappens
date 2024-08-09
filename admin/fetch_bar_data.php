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

// Fetch products and their total ticket counts
$sql = "SELECT p.product_name, SUM(o.quantitypip install jupyter) AS total_tickets 
        FROM product p
        LEFT JOIN orders o ON p.product_id = o.product_id
        GROUP BY p.product_name";

$result = $conn->query($sql);

// Error handling
if ($result === false) {
    echo "Error: " . $conn->error;
    $conn->close();
    exit();
}

$products = [];
$ticketCounts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row['product_name'];
        $ticketCounts[] = $row['total_tickets'];
    }
} else {
    echo "No results found.";
}

$conn->close();

// Output results as JSON
echo json_encode([
    'products' => $products,
    'ticketCounts' => $ticketCounts
]);
?>
