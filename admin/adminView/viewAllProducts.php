<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Product Items</title>
  <!-- Include jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- Include jsPDF AutoTable plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>
 
</head>
<body>
  <div>
    <h2>Event Items</h2>
    <button id="exportBtn" class="btn btn-success mb-2">Export as PDF</button>
    <table class="table" id="productTable">
      <thead>
        <tr>
          <th class="text-center">Event ID</th>
          <th class="text-center">Event Image</th>
          <th class="text-center">Event Name</th>
          <th class="text-center">Event Description</th>
          <th class="text-center">Category Name</th>
          <th class="text-center">Unit Price</th>
          <th class="text-center" colspan="2">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          include_once "../config/dbconnect.php";
          $sql="SELECT * from product, category WHERE product.category_id=category.category_id";
          $result=$conn-> query($sql);
          $count=1;
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><img height='100px' src='fetch_image.php?id=<?php echo $row["product_id"]; ?>'></td>
          <td><?=$row["product_name"]?></td>
          <td><?=$row["product_desc"]?></td>
          <td><?=$row["category_name"]?></td>
          <td><?=$row["price"]?></td>
          <td><button class="btn btn-primary" style="height:40px" onclick="itemEditForm('<?=$row['product_id']?>')">Edit</button></td>
          <td><button class="btn btn-danger" style="height:40px" onclick="itemDelete('<?=$row['product_id']?>')">Delete</button></td>
        </tr>
        <?php
              $count++;
            }
          }
        ?>
      </tbody>
    </table>

    <!-- Trigger the modal with a button -->
    <!--<button type="button" class="btn btn-secondary" style="height:40px" data-toggle="modal" data-target="#myModal">
      Add Event
    </button>-->

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">New Event</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form enctype='multipart/form-data' onsubmit="addItems()" method="POST">
              <div class="form-group">
                <label for="name">Event Name:</label>
                <input type="text" class="form-control" id="p_name" required>
              </div>
              <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="p_price" required>
              </div>
              <div class="form-group">
                <label for="qty">Description:</label>
                <input type="text" class="form-control" id="p_desc" required>
              </div>
              <div class="form-group">
                <label>Category:</label>
                <select id="category" class="form-control">
                  <option disabled selected>Select category</option>
                  <?php
                    $sql="SELECT * from category";
                    $result = $conn-> query($sql);
                    if ($result-> num_rows > 0){
                      while($row = $result-> fetch_assoc()){
                        echo"<option value='".$row['category_id']."'>".$row['category_name'] ."</option>";
                      }
                    }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label for="file">Choose Image:</label>
                <input type="file" class="form-control-file" id="file">
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-secondary" id="upload" style="height:40px">Add Event</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal" style="height:40px">Close</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.getElementById('exportBtn').addEventListener('click', function() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();

      // Get table data
      const table = document.getElementById('productTable');
      const rows = Array.from(table.querySelectorAll('tbody tr')).map(tr =>
        Array.from(tr.querySelectorAll('td')).map(td => td.innerText)
      );
      const columns = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText);

      // Debug: log the data to console
      console.log('Columns:', columns);
      console.log('Rows:', rows);

      doc.autoTable({
        head: [columns],
        body: rows
      });

      doc.save('Events.pdf');
    });
  </script>

 
</body>
</html>
