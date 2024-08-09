<?php


session_start();
// Check if user is logged in and has admin privileges
if (!isset($_SESSION['user_id']) || $_SESSION['isAdmin'] != 2) {
    // Output JavaScript for alert and redirection
    echo '<script type="text/javascript">
            alert("Access Denied");
            window.location.href = "../login/index.php";
          </script>';
    exit();
}
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_happens"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle search query
$where = "";
if (isset($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $where = "WHERE title LIKE '%$search%' OR description LIKE '%$search%'";
}

// Fetch events from the database
$qry = $conn->query("SELECT * FROM product $where");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets - Ceylon Happens</title>
    <link rel="stylesheet" href="tickets.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style type="text/css">
        body {
            background: #F4F7FD;
            margin-top: 20px;
        }
        
        .card-margin {
            margin-bottom: 1.875rem;
        }
        
        .card {
            border: 0;
            box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
            -webkit-box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
            -moz-box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
            -ms-box-shadow: 0px 0px 10px 0px rgba(82, 63, 105, 0.1);
        }
        
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #ffffff;
            background-clip: border-box;
            border: 1px solid #e6e4e9;
            border-radius: 8px;
        }
        
        .card .card-header.no-border {
            border: 0;
        }
        
        .card .card-header {
            background: none;
            padding: 0 0.9375rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            min-height: 50px;
        }
        
        .card-header:first-child {
            border-radius: calc(8px - 1px) calc(8px - 1px) 0 0;
        }
        
        .widget-49 .widget-49-title-wrapper {
            display: flex;
            align-items: center;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-primary {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #edf1fc;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-primary .widget-49-date-day {
            color: #4e73e5;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-primary .widget-49-date-month {
            color: #4e73e5;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-secondary {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #fcfcfd;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-secondary .widget-49-date-day {
            color: #dde1e9;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-secondary .widget-49-date-month {
            color: #dde1e9;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-success {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #e8faf8;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-success .widget-49-date-day {
            color: #17d1bd;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-success .widget-49-date-month {
            color: #17d1bd;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-info {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #ebf7ff;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-info .widget-49-date-day {
            color: #36afff;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-info .widget-49-date-month {
            color: #36afff;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-warning {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: floralwhite;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-warning .widget-49-date-day {
            color: #FFC868;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-warning .widget-49-date-month {
            color: #FFC868;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-danger {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #feeeef;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-danger .widget-49-date-day {
            color: #F95062;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-danger .widget-49-date-month {
            color: #F95062;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-light {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #fefeff;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-light .widget-49-date-day {
            color: #f7f9fa;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-light .widget-49-date-month {
            color: #f7f9fa;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-dark {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #ebedee;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-dark .widget-49-date-day {
            color: #394856;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-dark .widget-49-date-month {
            color: #394856;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-base {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #f0fafb;
            width: 4rem;
            height: 4rem;
            border-radius: 50%;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-base .widget-49-date-day {
            color: #68CBD7;
            font-weight: 500;
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-date-base .widget-49-date-month {
            color: #68CBD7;
            line-height: 1;
            font-size: 1rem;
            text-transform: uppercase;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-meeting-info {
            display: flex;
            flex-direction: column;
            margin-left: 1rem;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-meeting-info .widget-49-pro-title {
            color: #3c4142;
            font-size: 14px;
        }
        
        .widget-49 .widget-49-title-wrapper .widget-49-meeting-info .widget-49-meeting-time {
            color: #B1BAC5;
            font-size: 13px;
        }
        
        .widget-49 .widget-49-meeting-points {
            font-weight: 400;
            font-size: 13px;
            margin-top: .5rem;
        }
        
        .widget-49 .widget-49-meeting-points .widget-49-meeting-item {
            display: list-item;
            color: #727686;
        }
        
        .widget-49 .widget-49-meeting-points .widget-49-meeting-item span {
            margin-left: .5rem;
        }
        
        .widget-49 .widget-49-meeting-action {
            text-align: right;
        }
        
        .widget-49 .widget-49-meeting-action a {
            text-transform: uppercase;
        }
    </style>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap');
</style>

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


    <div class="container pt-2">
        <form action="" method="get" class="mb-3">
            <div class="w-100 d-flex justify-content-center">
                <div class="input-group col-md-5">
                    <input type="text" class="form-control" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : "" ?>" placeholder="Search Event">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-light border">
                            <i class="fas fa-search text-muted"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <hr>
        <div class="col-md-12">
            <div class="row">
                <?php while ($row = $qry->fetch_assoc()): ?>
                    <div class="col-lg-4">
                        <div class="card card-margin">
                            <div class="card-header no-border">
                                <h5 class="card-title"><dt><b><?php echo $row['product_name'] ?></b></dt></h5>
                            </div>
                            <div class="card-body pt-0">
                                <div class="widget-49">
                                    <div class="widget-49-title-wrapper">
                                        <div class="widget-49-date-warning">
                                            <span class="widget-49-date-day"><?php echo date('d', strtotime($row['event_date'])); ?></span>
                                            <span class="widget-49-date-month"><?php echo date('M', strtotime($row['event_date'])); ?></span>
                                        </div>
                                        <div class="widget-49-meeting-info">
                                            <span class="widget-49-pro-title"><?php echo $row['event_location']; ?></span>
                                            <span class="widget-49-meeting-time"><?php echo date('H:i', strtotime($row['event_date'])); ?> by <?php echo $row['product_name']; ?></span>
                                        </div>
                                    </div>
                                    <ol class="widget-49-meeting-points">
                                        <span><?php echo $row['product_desc']; ?></span>
                                    </ol>
                                    <div class="widget-49-meeting-action">
                                    <a href="registration.php?e=<?php echo urlencode($row['product_id']); ?>" class="btn btn-sm btn-flash-border-warning">QR read</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

   




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