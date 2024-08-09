<?php 

include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName=$_POST['first_name'];
    $city=$_POST['city'];
    $email=$_POST['email'];
    $age=$_POST['age'];
    $gender=$_POST['gender'];
    $interest=$_POST['interests'];
    $password=$_POST['password'];
    $password=md5($password);

    $checkEmail="SELECT * From users where email='$email'";
    $result=$conn->query($checkEmail);
    if($result->num_rows>0){
        echo "<script>
                alert('Email Address Already Exists !');
                window.location.href='index.php';
              </script>";
    } else {
        $insertQuery="INSERT INTO users(first_name,city,age,gender,interest,email,password)
                      VALUES ('$firstName','$city','$age','$gender','$interest','$email','$password')";
        if($conn->query($insertQuery) == TRUE){
            header("location: index.php");
        } else {
            echo "Error: ".$conn->error;
        }
    }
}

if(isset($_POST['signIn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password = md5($password);

    $sql = "SELECT * FROM users WHERE email='$email' and password='$password'";
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
                alert('User Not Found, Incorrect Email or Password');
                window.location.href='index.php';
              </script>";
    }
}
?>  
