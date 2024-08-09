<?php
session_start();
include 'dbconnect.php';

// Check if the user is logged in and is not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] != 0) {
    header("Location: ../login/index.php");
    exit();
}

// Fetch products from the database
$query = "SELECT * FROM product";
$result = $conn->query($query);

if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Listing</title>
    <link rel="stylesheet" href="tickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <style>
        .card-img-top {
            width: 100%;
            height: 200px; /* Adjust the height as needed */
            object-fit: cover;
            aspect-ratio: 1/1;
        }
        .modal-content {
            margin-top: 25%;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            
        }
        .modal-body img {
            border-radius: 10px;
            margin-bottom: 15px;
            width: 150px; /* Adjust the size as needed */
            height: 150px; /* Ensure 1:1 ratio */
            object-fit: cover;
        }
        .modal-body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .modal-footer {
            justify-content: center;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .form-group {
            width: 100%;
        }
    </style>
</head>
<body style="background-color: #f7eedd;
">
   <script>
function logoutFunction() {
    // Confirm logout
    if (confirm('Are you sure you want to log out?')) {
        // Redirect to logout.php
        window.location.href = '../logout.php';
    }
}
</script>
 <!-- nav -->
 
        <nav class="navv">
            <a href="#" id="logo"><img src="assets/logo 2.png" class="logo2"></a>
            <ul>
            <li><a href="http://localhost/CeylonHappens/index.php">home</a></li>
                <li><a href="http://localhost/CeylonHappens/aboutus/index.php">About Us</a></li>
                <li><a href="http://localhost/CeylonHappens/events/form.php">Event</a></li>
                <li><a href="http://localhost/CeylonHappens/news/news.php">News</a></li>
                <li><a href="http://localhost/CeylonHappens/contactus/contactus.html">Contact Us</a></li>
                <li>  
    <?php           
    if(isset($_SESSION['user_id'])){
      ?>
      <button type="button" onclick="logoutFunction()" style="text-decoration:none;" class="btn btn-outline-light mt-3 ml-6">
        Log Out
      </button>
      <?php
    } else {
        ?>
        <a href="http://localhost/CeylonHappens/login/login.php" style="text-decoration:none;">
                <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
        </a>
        <?php
    } ?>
</li>
            </ul>
        </nav>

    
      
</nav>

    <div class="container">
        <h1 class="mt-5 mb-3">Events</h1>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <?php if (!empty($row['product_image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>" class="card-img-top" alt="Product Image">
                    <?php else: ?>
                    <img src="placeholder.jpg" class="card-img-top" alt="Placeholder Image">
                    <?php endif; ?>
                    <div class="card-body" style="text-align: center;">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                        <p class="card-text">Price: $ <?php echo htmlspecialchars($row['price']); ?></p>
                        <p class="card-text">Remaining Tickets: <?php echo htmlspecialchars($row['remaining_tickets']); ?></p>
                        <button class="btn btn-primary" onclick="showOverlay('<?php echo $row['product_id']; ?>', '<?php echo htmlspecialchars($row['product_name']); ?>', '<?php echo htmlspecialchars($row['product_desc']); ?>', '<?php echo htmlspecialchars($row['price']); ?>', 'data:image/jpeg;base64,<?php echo base64_encode($row['product_image']); ?>')">Buy</button>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <!-- Overlay Modal -->
    <div class="modal fade" id="overlay" tabindex="-1" aria-labelledby="overlayTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="overlayTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img id="overlayImage" src="" alt="Product Image" class="img-fluid mb-3">
                    <p id="overlayDesc"></p>
                    <p id="overlayPrice"></p>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" id="quantity" min="1" max="10" value="1" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-success" onclick="createCheckoutSession()">Checkout</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripePublicKey = 'pk_test_51PgRrPHvzim763YPamkVSV2GTtgXZntbtguBznEQVMfWCy7heL6LkBCsStrQNZUH758PHdQsDRpMPr4IxfFRUlbV008l4L1kyh'; // Replace with your actual Stripe public key
        const stripe = Stripe(stripePublicKey);

        function showOverlay(productId, productName, productDesc, productPrice, productImage) {
            document.getElementById('overlayTitle').innerText = productName;
            document.getElementById('overlayDesc').innerText = productDesc;
            document.getElementById('overlayPrice').innerText = 'Price: $' + productPrice;
            document.getElementById('overlayImage').src = productImage;
            $('#overlay').modal('show');
            document.getElementById('overlay').dataset.productId = productId;
        }

        function closeOverlay() {
            $('#overlay').modal('hide');
        }

        function createCheckoutSession() {
            var productId = document.getElementById('overlay').dataset.productId;
            var quantity = document.getElementById('quantity').value;

            fetch('create_checkout_session.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
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
        }
    </script>

    <!--Waves Container-->
    <div class="footer">
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
      <defs>
        <path
          id="gentle-wave"
          d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z"
        />
      </defs>
      <g class="parallax">
        <use
          xlink:href="#gentle-wave"
          x="48"
          y="0"
          fill="rgb(111, 197, 182)"
        />
        <use
          xlink:href="#gentle-wave"
          x="48"
          y="3"
          fill="rgb(61, 176, 157)"
        />
        <use
          xlink:href="#gentle-wave"
          x="48"
          y="5"
          fill="rgba(255,255,255,0.3)"
        />
        <use xlink:href="#gentle-wave" x="48" y="7" fill="rgb(27, 161, 139)" />
      </g>
    </svg>
    </div>
    <!--Waves end-->
    </div>
    <!--Header ends-->

    <!--Content starts-->
    <div class="contentss flex">
        <img src="assets/logo 2.png" style=" max-width: 140px;
height:auto;
margin-top: 15px;
margin-left: 1px;">


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
        <p style="padding-bottom:20px; padding-top:15px; color:white;">Copyright &copy;2024; Designed by Theekshana Nadun</p>
    </div>

    <!--Content ends-->

</body>

</html>