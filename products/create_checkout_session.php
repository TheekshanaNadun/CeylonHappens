<?php
require_once 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51PgRrPHvzim763YPxgxWiijJE6VmEiurVkDy84xBQxNcvGhhJHt6Oq9HnWmB9amzWkH7cRylnq4mR61SEyeTpMPb00FOSV9fW5');

// Retrieve JSON from POST body
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$product_id = $json_obj['product_id'];
$quantity = $json_obj['quantity'];


include 'dbconnect.php';
$query = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
$query->bind_param('i', $product_id);
$query->execute();
$result = $query->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    http_response_code(404);
    echo json_encode(['error' => 'Product not found']);
    exit();
}

$product_name = $product['product_name'];
$product_price = $product['price'] * 100; // Convert to cents

header('Content-Type: application/json');

try {
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $product_name,
                ],
                'unit_amount' => $product_price,
            ],
            'quantity' => $quantity,
        ]],
        'mode' => 'payment',
        'success_url' => 'http://localhost/CeylonHappens/products/success.php?product_id=' . $product_id . '&quantity=' . $quantity . '&order_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'http://localhost/CeylonHappens/products.php',
    ]);

    echo json_encode(['id' => $session->id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
