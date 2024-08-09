<?php
include_once "../config/dbconnect.php";

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    error_log("Fetching image for product ID: " . $product_id); // Log product ID
    $stmt = $conn->prepare("SELECT product_image FROM product WHERE product_id = ?");
    if (!$stmt) {
        error_log("Prepare failed: " . $conn->error); // Log prepare error
    }
    $stmt->bind_param("i", $product_id);
    if (!$stmt->execute()) {
        error_log("Execute failed: " . $stmt->error); // Log execute error
    }
    $stmt->store_result();
    $stmt->bind_result($product_image);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && !empty($product_image)) {
        header("Content-Type: image/jpeg");
        echo $product_image;
    } else {
        error_log("Image not found for ID: " . $product_id); // Log error
        header("HTTP/1.0 404 Not Found");
        echo "Image not found.";
    }

    $stmt->close();
} else {
    error_log("No ID provided."); // Log error
    header("HTTP/1.0 400 Bad Request");
    echo "No ID provided.";
}
?>
