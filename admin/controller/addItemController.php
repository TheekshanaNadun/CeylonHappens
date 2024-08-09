<?php
    include_once "../config/dbconnect.php";
    
    if(isset($_POST['upload']))
    {
       
        $ProductName = $_POST['p_name'];
        $desc= $_POST['p_desc'];
        $price = $_POST['p_price'];
        $category = $_POST['category'];
       
        $file = $_FILES['newImage']['tmp_name'];
        $fileContent = addslashes(file_get_contents($file));

    
        

         $insert = mysqli_query($conn,"INSERT INTO product
         (product_name,product_image,price,product_desc,category_id) 
         VALUES ('$ProductName','$filecontent',$price,'$desc','$category')");
 
         if(!$insert)
         {
             echo mysqli_error($conn);
         }
         else
         {
             echo "Records added successfully.";
         }
     
    }
        
?>