<?php
require_once 'vendor/autoload.php';
\Stripe\Stripe::setApiKey('sk_test_51PgRrPHvzim763YPxgxWiijJE6VmEiurVkDy84xBQxNcvGhhJHt6Oq9HnWmB9amzWkH7cRylnq4mR61SEyeTpMPb00FOSV9fW5');

include 'dbconnect.php';

// Start session
session_start();

// Check if parameters are provided and session data is available
if (!isset($_GET['product_id']) || !isset($_GET['quantity']) || !isset($_SESSION['user_id'])) {
    echo '<script>
        Swal.fire({
            title: "Error!",
            text: "Missing required parameters or session data.",
            icon: "error"
        }).then(function() {
            window.location.href = "index.php"; // Redirect to home or another page
        });
    </script>';
    die();
}

$product_id = $_GET['product_id'];
$quantity = $_GET['quantity'];
$user_id = $_SESSION['user_id'];
$session_id = session_id(); // Get the session ID

// Retrieve product details
$query = $conn->prepare("SELECT * FROM product WHERE product_id = ?");
if (!$query) {
    die("Prepare failed: " . $conn->error);
}
$query->bind_param('i', $product_id);
$query->execute();
$result = $query->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo '<script>
        Swal.fire({
            title: "Error!",
            text: "Product not found.",
            icon: "error"
        }).then(function() {
            window.location.href = "index.php"; // Redirect to home or another page
        });
    </script>';
    die();
}

$product_price = $product['price'];
$remaining_tickets = $product['remaining_tickets'];

// Update remaining tickets
$new_remaining_tickets = $remaining_tickets - $quantity;
$update_query = $conn->prepare("UPDATE product SET remaining_tickets = ? WHERE product_id = ?");
if (!$update_query) {
    die("Prepare failed: " . $conn->error);
}
$update_query->bind_param('ii', $new_remaining_tickets, $product_id);
$update_query->execute();

// Insert order data (excluding phone number)
$total_price = $product_price * $quantity;
$insert_query = $conn->prepare("INSERT INTO orders (product_id, quantity, total_price, user_id) VALUES (?, ?, ?, ?)");
if (!$insert_query) {
    die("Prepare failed: " . $conn->error);
}
$insert_query->bind_param('iddd', $product_id, $quantity, $total_price, $user_id);

$success = $insert_query->execute();

$response = [];
if ($success) {
    $order_id = $conn->insert_id; // Get the last inserted order ID

    // Prepare QR code data
    $order_data = [
        'product_id' => $product_id,
        'quantity' => $quantity,
        'total_price' => $total_price,
        'user_id' => $user_id,
        'session_id' => $session_id // Include session ID
    ];
    $order_data_json = json_encode($order_data);
    $order_data_base64 = base64_encode($order_data_json);

    // Update QR code in the database
    $update_query = $conn->prepare("UPDATE orders SET qr_code = ? WHERE order_id = ?");
    if (!$update_query) {
        die("Prepare failed: " . $conn->error);
    }
    $update_query->bind_param('si', $order_data_base64, $order_id);
    $success = $update_query->execute();

    if ($success) {
        $response['success'] = true;
        $response['message'] = 'Order placed successfully. Please enter your phone number.';
    } else {
        $response['success'] = false;
        $response['message'] = 'Failed to save QR code: ' . $conn->error;
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Failed to place order: ' . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <style>
        .qr-code-container {
            text-align: center;
            margin-top: 20px;
        }
        .qr-code-container img {
            border: 2px solid #000;
            padding: 10px;
            background: #fff;
        }
    </style>
    
</head>
<body style="background-color: #f7eedd;">
<nav class="navv">
    <a href="#" id="logo"><img src="assets/logo 2.png" class="logo2"></a>
    <ul>
        <li><a href="http://localhost/CeylonHappens/index.php">home</a></li>
        <li><a href="http://localhost/CeylonHappens/aboutus/index.php">About Us</a></li>
        <li><a href="http://localhost/CeylonHappens/events/form.php">Event</a></li>
        <li><a href="http://localhost/CeylonHappens/news/news.php">News</a></li>
        <li><a href="http://localhost/CeylonHappens/contactus/contactus.html">Contact Us</a></li>
        <li>  
            <?php if (isset($_SESSION['user_id'])) { ?>
                <button type="button" onclick="logoutFunction()" style="text-decoration:none;" class="btn btn-outline-light mt-3 ml-6">Log Out</button>
            <?php } else { ?>
                <a href="http://localhost/CeylonHappens/login/index.php" class="btn">Log In</a>
            <?php } ?>
        </li>
    </ul>
</nav>

<div style="display: flex; align-items: center; flex-direction: column; justify-content: center;">
    <h1>Payment Successful!</h1>
    <p>Thank you for your purchase.</p>
    <p>This is your ticket.</p>
    
    <div class="qr-code-container" id="qr-container" style="display:none;">
        <div id="qrcode"></div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var orderDataBase64 = <?php echo json_encode($order_data_base64); ?>;
            var qrCodeContainer = document.getElementById('qrcode');
            var qrContainer = document.getElementById('qr-container');

            // Generate QR code with Base64-encoded data
            new QRCode(qrCodeContainer, {
                text: orderDataBase64,
                width: 150,
                height: 150,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.L
            });

            // Show SweetAlert based on PHP response
            <?php if ($response['success']) { ?>
                Swal.fire({
                    title: 'Success!',
                    text: '<?php echo $response['message']; ?>',
                    icon: 'success',
                    input: 'tel',
                    inputLabel: 'Please enter your phone number',
                    inputPlaceholder: 'Enter your phone number',
                    inputAttributes: {
                        maxlength: 15,
                        autocapitalize: 'off',
                        autocorrect: 'off'
                    },
                    confirmButtonText: 'Submit',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var phoneNumber = result.value;
                        // Send phone number to server
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'update_phone.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.send('order_id=' + <?php echo $order_id; ?> + '&phone_no=' + encodeURIComponent(phoneNumber));
                        xhr.onload = function() {
                            if (xhr.status === 200) {
                                Swal.fire({
                                    title: 'Success!',
                                    text: 'Phone number updated successfully.',
                                    icon: 'success'
                                });
                            } else {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Failed to update phone number.',
                                    icon: 'error'
                                });
                            }
                        };
                    }
                });
                qrContainer.style.display = 'block'; // Show QR code
            <?php } else { ?>
                Swal.fire({
                    title: 'Error!',
                    text: '<?php echo $response['message']; ?>',
                    icon: 'error'
                });
            <?php } ?>
        });

        function logoutFunction() {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = '../logout.php';
            }
        }
    </script>
</div>

<!-- Footer -->
<div class="footer">
    <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
        <defs>
            <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z" />
        </defs>
        <g class="parallax">
            <use xlink:href="#gentle-wave" x="48" y="0" fill="rgb(111, 197, 182)" />
            <use xlink:href="#gentle-wave" x="48" y="3" fill="rgb(61, 176, 157)" />
            <use xlink:href="#gentle-wave" x="48" y="5" fill="rgba(255,255,255,0.3)" />
            <use xlink:href="#gentle-wave" x="48" y="7" fill="rgb(27, 161, 139)" />
        </g>
    </svg>
</div>

<!-- Footer Content -->
<div class="contentss flex">
    <img src="assets/logo 2.png" style="max-width: 140px; height: auto; margin-top: 15px; margin-left: 1px;">
    <div class="footerNav">
        <ul>
        <li><a href="http://localhost/CeylonHappens/index.php">Home</a></li>
                <li><a href="http://localhost/CeylonHappens/aboutus/index.php">About Us</a></li>
                <li><a href="http://localhost/CeylonHappens/events/form.php">Event</a></li>
                <li><a href="http://localhost/CeylonHappens/news/news.php">News</a></li>
                <li><a href="http://localhost/CeylonHappens/contactus/contactus.html">Contact Us</a></li>
        </ul>
    </div>
    <div class="socialIcons">
        <a href=""><i class="fa-brands fa-facebook"></i></a>
        <a href=""><i class="fa-brands fa-instagram"></i></a>
        <a href=""><i class="fa-brands fa-twitter"></i></a>
        <a href=""><i class="fa-brands fa-google-plus"></i></a>
        <a href=""><i class="fa-brands fa-youtube"></i></a>
    </div>
    <p style="padding-bottom: 20px; padding-top: 15px; color: white;">Copyright &copy; 2024; Designed by Theekshana Nadun</p>
</div>
</body>
</html>
