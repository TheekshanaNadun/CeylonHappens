<?php
session_start();
header('Content-Type: application/json');

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Username = $_POST['first_name'];
    $Email = $_POST['email'];
    $City = $_POST['city'];
    $Password = $_POST['password'];
    // Hashing password for better security
    $Password = md5($Password);

    // Connecting to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "ceylon_happens";

    // Creating connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        $response['status'] = 'error';
        $response['message'] = "Sorry we failed to connect: " . mysqli_connect_error();
    } else {
        // Submitting to database
        $sql = "INSERT INTO user (name, email, city, password) VALUES ('$Username', '$Email', '$City', '$Password')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $response['status'] = 'success';
            $response['message'] = 'Sign in successful... you may now login.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Sign in Failed... Please try again.';
        }
    }
    echo json_encode($response);
}
?>
