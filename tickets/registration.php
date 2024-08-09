
<?php
session_start();
if (!isset($_GET['e'])) {
    die("Invalid access");
    
}
include 'db_connection.php';

// Check if the user is logged in and is not an admin
if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] != 2) {
    header("Location:../login/login.php");
    exit();
}

$_SESSION['product_id'] = $_GET['e'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Code Scanner</title>
    <link rel="stylesheet" href="tickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <style>
        #video {
            margin-top: 4rem;
            width: 280px;
            height: 280px;
            object-fit: cover;

        }
        #overlay {
            position: absolute;
            top: 0;
            left: 0;
            margin-top: 4rem;
            width: 285px;
            height: 285px;
            border: 5px solid green;
            display: none;
        }
        .table td, .table th {
            text-align: center;
        }
    </style>
</head>
<body style="background-color: #f7eedd;">
    <script>
        let lastQRCode = null; // Track the last processed QR code

        function logoutFunction() {
            if (confirm('Are you sure you want to log out?')) {
                window.location.href = '../logout.php';
            }
        }

        function showFeedback(message, isSuccess) {
            Swal.fire({
                title: isSuccess ? 'Success!' : 'Error!',
                text: message,
                icon: isSuccess ? 'success' : 'error',
                confirmButtonText: 'OK'
            });
        }

        async function processQRCode(data) {
            if (data === lastQRCode) return; // Skip if the QR code is the same as the last one

            lastQRCode = data; // Update the last processed QR code

            try {
                const response = await fetch('update_order_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ qrCodeData: data, productId: '<?php echo $_SESSION['product_id']; ?>' })
                });

                if (!response.ok) {
                    console.error('Network response was not ok:', response.statusText);
                    showFeedback('Failed to update order status. Please try again.', false);
                    return;
                }

                const responseText = await response.text();
                const result = JSON.parse(responseText);

                if (result.success) {
                    showFeedback('Ticket is Approved.', true);
                } else {
                    showFeedback('Invalid or Used ticket', false);
                }
            } catch (error) {
                console.error('Error updating order status:', error);
                showFeedback('Error updating order status.', false);
            }
        }
    </script>
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
                    <a href="http://localhost/CeylonHappens/login/login.php" style="text-decoration:none;">
                        <i class="fa fa-sign-in mr-5" style="font-size:30px; color:#fff;" aria-hidden="true"></i>
                    </a>
                <?php } ?>
            </li>
        </ul>
    </nav>

    <h1>Scan the QR Code</h1>

    <div class="row">
        <div class="col-sm-5 col-md-6">
            <div style="position: relative; width: 300px; height: 300px; margin-top:50px" class="mx-auto">
                <video id="video" autoplay></video>
                <div id="overlay"></div>
            </div>
        </div>
        <div class="col-sm-5 col-md-6">
            <table style="width: 80%; margin-top:180px;" class="table table-bordered ">
                <thead style="background-color: #1ca38c; color: #ffffff;">
                    <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Phone Number</th>
                        <th scope="col">Order Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    require_once 'db_connection.php';

                    $product_id = $_SESSION['product_id'];
                    $sql = "SELECT o.order_id, u.first_name, o.phone_no, o.order_status 
                            FROM orders o 
                            JOIN users u ON o.user_id = u.user_id 
                            WHERE o.product_id = ?";
                    $stmt = $conn->prepare($sql);

                    if ($stmt === false) {
                        error_log("Prepare failed: " . $conn->error);
                        die("An error occurred. Check the log for details.");
                    }

                    $stmt->bind_param("i", $product_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result === false) {
                        error_log("Get result failed: " . $stmt->error);
                        die("An error occurred. Check the log for details.");
                    }

                    while ($row = $result->fetch_assoc()) {
                        $status = $row['order_status'] == 1 ? 'Used' : 'Not Used';
                        echo "<tr>";
                        echo "<td>{$row['order_id']}</td>";
                        echo "<td>{$row['first_name']}</td>";
                        echo "<td>{$row['phone_no']}</td>";
                        echo "<td>{$status}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (typeof jsQR === 'undefined') {
                console.error('jsQR library is not loaded.');
                return;
            }

            const video = document.getElementById('video');
            const overlay = document.getElementById('overlay');
            const constraints = { video: { facingMode: 'environment' } };

            function startVideo() {
                navigator.mediaDevices.getUserMedia(constraints)
                    .then((stream) => {
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.play();
                        requestAnimationFrame(tick);
                    })
                    .catch(err => {
                        console.error('Error accessing the camera: ', err);
                    });
            }

            async function tick() {
                if (video.readyState === video.HAVE_ENOUGH_DATA) {
                    const width = video.videoWidth;
                    const height = video.videoHeight;
                    const canvas = document.createElement('canvas');
                    canvas.width = width;
                    canvas.height = height;
                    const context = canvas.getContext('2d');
                    context.drawImage(video, 0, 0, width, height);
                    const imageData = context.getImageData(0, 0, width, height);

                    try {
                        const code = jsQR(imageData.data, imageData.width, imageData.height, {
                            inversionAttempts: "dontInvert",
                        });

                        if (code) {
                            processQRCode(code.data); // Process QR code
                            overlay.style.display = 'none'; // Hide overlay if QR code is detected
                        } else {
                            overlay.style.display = 'block'; // Show overlay if no QR code detected
                        }
                    } catch (e) {
                        console.error('Error processing QR code:', e);
                    }
                }
                requestAnimationFrame(tick);
            }

            startVideo();
        });
    </script>
   <!--Waves Container-->
   <div class="footer" style="margin-top:200px;">
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
max-height: fit-content;
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
            <p style="padding-bottom:20px; padding-top:15px; color:white;">Copyright &copy;2024 Designed by Theekshana</p>
        </div>

        <!--Content ends-->

</body>
</html>