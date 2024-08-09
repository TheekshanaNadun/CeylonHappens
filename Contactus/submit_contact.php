<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_happens";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

// Insert data into the database
$sql = "INSERT INTO newsletters (first_name, last_name, email, phone, message) VALUES ('$first_name', '$last_name', '$email', '$phone', '$message')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Message sent successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
}

$conn->close();
?>
