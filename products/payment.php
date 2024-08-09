<?php
session_start();
include 'dbconnect.php'; 

if (!isset($_GET['product_id'])) {
    die("Product ID is required");
}

$product_id = $_GET['product_id'];

// Fetch product details from the database
$query = "SELECT * FROM product WHERE product_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found");
}

$stripePublicKey = 'pk_test_51PgRrPHvzim763YPamkVSV2GTtgXZntbtguBznEQVMfWCy7heL6LkBCsStrQNZUH758PHdQsDRpMPr4IxfFRUlbV008l4L1kyh'; // Replace with your actual Stripe public key
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buy Product</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>Buy Product: <?php echo htmlspecialchars($product['product_name']); ?></h1>
        <p><?php echo htmlspecialchars($product['description']); ?></p>
        <p>Price: <?php echo htmlspecialchars($product['price']); ?></p>
        <button id="checkout-button" class="btn btn-primary">Buy Now</button>
    </div>

    <script>
        const stripe = Stripe('<?php echo $stripePublicKey; ?>');

        document.getElementById('checkout-button').addEventListener('click', () => {
    fetch('create_checkout_session.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            product_id: '<?php echo $product_id; ?>'
        })
    })
    .then(response => response.json())
    .then(session => {
        if (session.error) {
            throw new Error(session.error);
        }
        return stripe.redirectToCheckout({ sessionId: session.id });
    })
    .then(result => {
        if (result.error) {
            alert(result.error.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

    </script>
</body>
</html>
