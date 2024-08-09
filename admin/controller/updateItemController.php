<?php
include_once "../config/dbconnect.php";

if (isset($_POST['product_id']) && isset($_POST['p_name'])) {
    $product_id = $_POST['product_id'];
    $p_name = $_POST['p_name'];
    $p_desc = $_POST['p_desc'];
    $p_price = $_POST['p_price'];
    $category = $_POST['category'];

    // Check if a new image file is uploaded
    if (isset($_FILES['newImage'])) {
        // Get the uploaded file information
        $file = $_FILES['newImage']['tmp_name'];
        $fileContent = addslashes(file_get_contents($file));

        // Update product information with new image
        $updateItem = mysqli_query($conn, "UPDATE product SET product_name='$p_name', product_desc='$p_desc', price=$p_price, category_id=$category, product_image='$fileContent' WHERE product_id=$product_id");
    } else {
        // Update product information without changing the image
        $updateItem = mysqli_query($conn, "UPDATE product SET product_name='$p_name', product_desc='$p_desc', price=$p_price, category_id=$category WHERE product_id=$product_id");
    }

    if ($updateItem) {
        echo "Update successful.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
