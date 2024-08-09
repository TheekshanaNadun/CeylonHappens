<?php
require_once 'vendor/autoload.php';
include 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'] ?? null;
    $qr_code = $_POST['qr_code'] ?? null;

    if (!$order_id || !$qr_code) {
        echo json_encode(['success' => false, 'error' => 'Missing order_id or qr_code']);
        exit();
    }

    // Update the database with QR code data
    $update_query = $conn->prepare("UPDATE orders SET qr_code = ? WHERE order_id = ?");
    if (!$update_query) {
        echo json_encode(['success' => false, 'error' => 'Error preparing SQL statement: ' . $conn->error]);
        exit();
    }

    $update_query->bind_param('si', $qr_code, $order_id);
    $success = $update_query->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}
?>
