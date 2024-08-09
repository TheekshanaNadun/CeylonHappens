<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_happens";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if form data is submitted
if (
    isset($_POST['event-title']) &&
    isset($_POST['event-description']) &&
    isset($_POST['event-category']) &&
    isset($_POST['event-date']) &&
    isset($_POST['event-time']) &&
    isset($_POST['event-location']) &&
    isset($_POST['organizer-name']) &&
    isset($_POST['organizer-email']) &&
    isset($_POST['organizer-phone']) &&
    isset($_POST['ticket-price']) &&
    isset($_POST['number-tickets'])
) {
    $event_title = $conn->real_escape_string($_POST['event-title']);
    $event_description = $conn->real_escape_string($_POST['event-description']);
    $event_category = (int)$_POST['event-category'];
    $event_date = $conn->real_escape_string($_POST['event-date']);
    $event_time = $conn->real_escape_string($_POST['event-time']);
    $event_location = $conn->real_escape_string($_POST['event-location']);
    $organizer_name = $conn->real_escape_string($_POST['organizer-name']);
    $organizer_email = $conn->real_escape_string($_POST['organizer-email']);
    $organizer_phone = $conn->real_escape_string($_POST['organizer-phone']);
    $ticket_price = (float)$_POST['ticket-price'];
    $numbertickets = (int)$_POST['number-tickets'];

    // Initialize image variable
    $fileContent = null;

    // Check if a new image file is uploaded
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['event_image']['tmp_name'];
        $fileContent = addslashes(file_get_contents($file));
    }

    // Construct the SQL query
    if ($fileContent) {
        $sql = "INSERT INTO product (product_name, product_desc, price, category_id, product_image, remaining_tickets, event_date, event_time, event_location, organizer_name, organizer_email, organizer_phone)
                VALUES ('$event_title', '$event_description', $ticket_price, $event_category, '$fileContent', $numbertickets, '$event_date', '$event_time', '$event_location', '$organizer_name', '$organizer_email', '$organizer_phone')";
    } else {
        $sql = "INSERT INTO product (product_name, product_desc, price, category_id, remaining_tickets, event_date, event_time, event_location, organizer_name, organizer_email, organizer_phone)
                VALUES ('$event_title', '$event_description', $ticket_price, $event_category, $numbertickets, '$event_date', '$event_time', '$event_location', '$organizer_name', '$organizer_email', '$organizer_phone')";
    }

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Event published successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
}

$conn->close();
?>
