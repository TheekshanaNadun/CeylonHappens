<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_happens";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT product_image FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($product_image);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && !empty($product_image)) {
        header("Content-Type: image/jpeg");
        echo $product_image;
    } else {
        header("Content-Type: image/jpeg");
        readfile('path/to/placeholder.jpg'); // Fallback to a placeholder image
    }

    $stmt->close();
} else {
    header("Content-Type: image/jpeg");
    readfile('path/to/placeholder.jpg'); // Fallback to a placeholder image
}

$conn->close();
?>
