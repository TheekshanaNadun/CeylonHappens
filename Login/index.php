<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="style.css">
    <title>login page</title>
</head>

<body>
<?php 
include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName = $_POST['first_name'];
    $city = $_POST['city'];
    $email = $_POST['semail'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $interest = $_POST['interests'];
    $password = $_POST['spassword'];
    $password = md5($password);

    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);
    if($result->num_rows > 0){
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Email Address Already Exists!',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
              </script>";
    } else {
        $insertQuery = "INSERT INTO users(first_name, city, age, gender, interest, email, password)
                        VALUES ('$firstName','$city','$age','$gender','$interest','$email','$password')";
        if($conn->query($insertQuery) === TRUE){
            echo "<script>
                    Swal.fire({
                        title: 'Success!',
                        text: 'Account Created Successfully!',
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'index.php';
                        }
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error: ".$conn->error."',
                        icon: 'error'
                    });
                  </script>";
        }
    }
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['isAdmin'] = $row['isAdmin'];

        if($row['isAdmin'] == 1){
            $_SESSION['email'] = $row['email'];
            header("Location: http://localhost/CeylonHappens/admin/admin.php");
            exit();
        } elseif($row['isAdmin'] == 0) {
            header("Location: http://localhost/CeylonHappens/products/products.php");
            exit();
        } elseif($row['isAdmin'] == 2) {
            header("Location: http://localhost/CeylonHappens/tickets/tickets.php");
            exit();
        }
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Not Found, Incorrect Email or Password',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
              </script>";
    }
}
?>
        <nav class="navv">
            <a href="#" id="logo"><img src="assets/logo 2.png" class="logo2"></a>
            <ul>
            <li><a href="http://localhost/CeylonHappens/index.php">Home</a></li>
                <li><a href="http://localhost/CeylonHappens/aboutus/index.php">About Us</a></li>
                <li><a href="http://localhost/CeylonHappens/events/form.php">Event</a></li>
                <li><a href="http://localhost/CeylonHappens/news/news.php">News</a></li>
                <li><a href="http://localhost/CeylonHappens/contactus/contactus.html">Contact Us</a></li>
            </ul>
        </nav>

        
    <div class="container" id="container">
        <div class="form-container sign-up">
            <form method="post" action="">
                <h1>Log in</h1>
                <br>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="--------------------">
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="--------------------">
                </div>
                <a href="#">Forget Your Password?</a>
                <button type="submit" class="btn" value="Sign In" name="signIn">Log in</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post" action="">
                <h1 >Create Account</h1><br>
                <div class="name-group">
                    <div class="input-group">
                        <label for="first_name">Name</label>
                        <input type="text" id="name" name="first_name" placeholder="----------" required>
                    </div>
                    <div class="input-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" placeholder="----------" required>
                    </div>
                </div>
            
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="semail" name="semail" placeholder="---------------" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="spassword" placeholder="---------------" required>
                </div>
                <div class="input-group">
                    <label for="retype-password">Retype-Password</label>
                    <input type="password" id="confirm_password" name="" placeholder="--------------" required>
                    <span id="error_message" class="error"></span>
                </div>
                <div class="age-gender-group">
                    <div class="input-group">
                        <label for="age">Age</label>
                        <input type="number" id="age" name="age" placeholder="--" required>
                    </div>
                    <div class="input-group">
                        <label>Gender:</label>
                        <div class="radio-group">
                        <input type="radio" id="male" name="gender" value="male">
                        <label for="male">Male</label><p> </p>
                        <input type="radio" id="female" name="gender" value="female">
                        <label for="female">Female</label>
                    </div>
                </div>
                </div>
                <div class="input-group">
                    <label>Interests:</label>
                    <div class="radio-group">
                        <input type="checkbox" id="music" name="interests" value="music">
                        <label style="font-size: small;" for="music">Music</label><p style="margin-left: 10px;"></p>
                        <input type="checkbox" id="Tech" name="interests" value="Tech">
                        <label style="font-size: small;" for="Tech">Tech</label><p style="margin-left: 10px;"></p>
                        <input type="checkbox" id="Education" name="interests" value="Education">
                        <label style="font-size: small;" for="Education">Education</label><p style="margin-left: 10px;"></p>
                        <input type="checkbox" id="Health Care" name="interests" value="Health Care">
                        <label style="font-size: small;" for="Health Care">HealthCare</label>
                    </div>
                </div>
                <button type="submit" class="btn" value="Sign Up" name="signUp">Sign in</button>
            </form>
            
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <img src="ceylon.png" alt="logo" width="400px" height="300px"> 
                    <p>Dont have an account</p>
                    <button class="hidden" id="login">Sign Up</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <img src="ceylon.png" alt="logo" width="400px" height="300px"> 
                    <p>Already have an account</p>
                    <button class="hidden" id="register">Sign In</button>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>

    <!--Waves Container-->
    <div class="footer">
        <svg
          class="waves"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          viewBox="0 24 150 28"
          preserveAspectRatio="none"
          shape-rendering="auto"
        >
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
    margin-left: 1px;" >


        <div class="footerNav">
            <ul><li><a href="">Home</a></li>
                <li><a href="">News</a></li>
                <li><a href="">About us</a></li>
                <li><a href="">Evant</a></li>
                <li><a href="">Contact Us</a></li>
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
    <script>
        function validatePasswords() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;
            var error_message = document.getElementById("error_message");

            if (password !== confirm_password) {
                error_message.textContent = "Passwords do not match!";
                return false;
            }
            
            // If passwords match, clear the error message
            error_message.textContent = "";
            return true;
        }
    </script>
</body>

</html>