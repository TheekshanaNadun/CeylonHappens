<?php
require_once 'db_connection.php';

// Retrieve POST data
$data = json_decode(file_get_contents('php://input'), true);
$qrCodeData = $data['qrCodeData'];
$productId = $data['productId'];

// Prepare SQL query to find the order
$sql = "SELECT order_id FROM orders WHERE qr_code = ? AND product_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'SQL prepare error: ' . $conn->error]);
    exit;
}

$stmt->bind_param("si", $qrCodeData, $productId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $orderId = $row['order_id'];

    // Prepare SQL query to update the order status
    $updateSql = "UPDATE orders SET order_status = 1 WHERE order_id = ?";
    $updateStmt = $conn->prepare($updateSql);

    if ($updateStmt === false) {
        echo json_encode(['success' => false, 'message' => 'SQL prepare error: ' . $conn->error]);
        exit;
    }

    $updateStmt->bind_param("i", $orderId);
    if ($updateStmt->execute()) {
        if ($updateStmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Order status not updated, no rows affected']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Error executing update query: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
}
?>
