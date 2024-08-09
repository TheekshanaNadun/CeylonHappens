<?php
include 'dbconnect.php';

// Get POST data
$order_id = $_POST['order_id'];
$phone_no = $_POST['phone_no'];

// Update phone number in the orders table
$update_query = $conn->prepare("UPDATE orders SET phone_no = ? WHERE order_id = ?");
if (!$update_query) {
    die("Prepare failed: " . $conn->error);
}
$update_query->bind_param('si', $phone_no, $order_id);
$success = $update_query->execute();

if ($success) {
    echo 'Phone number updated successfully.';
} else {
    echo 'Failed to update phone number: ' . $conn->error;
}
?>
