<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Order Details</title>
  <!-- Include jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>

</head>
<body>
  <div id="ordersBtn">
    <h2>Order Details</h2>
    <button id="exportBtn" class="btn btn-success mb-2">Export as PDF</button>
    <table class="table table-striped" id="orderTable">
      <thead>
        <tr>
          <th>Order ID</th>
          <th>Customer ID</th>
          <th>Contact</th>
          <th>OrderDate</th>
          <th>Payment Method</th>
          <th>Order Status</th>
          <th>Ticket Status</th>
          <th>More Details</th>
        </tr>
      </thead>
      <?php
      include_once "../config/dbconnect.php";
      $sql="SELECT * from orders";
      $result=$conn-> query($sql);
      
      if ($result-> num_rows > 0){
        while ($row=$result-> fetch_assoc()) {
      ?>
      <tr>
        <td><?=$row["order_id"]?></td>
        <td><?=$row["product_id"]?></td>
        <td><?=$row["phone_no"]?></td>
        <td><?=$row["order_date"]?></td>
        <td><?=$row["pay_method"]?></td>
        <?php 
        if($row["order_status"]==0){
        ?>
        <td><button class="btn btn-danger" onclick="ChangeOrderStatus('<?=$row['order_id']?>')">Pending</button></td>
        <?php
        } else {
        ?>
        <td><button class="btn btn-success" onclick="ChangeOrderStatus('<?=$row['order_id']?>')">Delivered</button></td>
        <?php
        }
        if($row["pay_status"]==0){
        ?>
        <td><button class="btn btn-danger" onclick="ChangePay('<?=$row['order_id']?>')">Not Used</button></td>
        <?php
        } else if($row["pay_status"]==1){
        ?>
        <td><button class="btn btn-success" onclick="ChangePay('<?=$row['order_id']?>')">Used</button></td>
        <?php
        }
        ?>
        <td><a class="btn btn-primary openPopup" data-href="./adminView/viewEachOrder.php?orderID=<?=$row['order_id']?>" href="javascript:void(0);">View</a></td>
      </tr>
      <?php
        }
      }
      ?>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="viewModal" role="dialog">
    <div class="modal-dialog modal-lg">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Order Details</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="order-view-modal modal-body">
        </div>
      </div><!--/ Modal content-->
    </div><!-- /Modal dialog-->
  </div>

  <script>
    // For view order modal
    $(document).ready(function(){
      $('.openPopup').on('click', function(){
        var dataURL = $(this).attr('data-href');
        $('.order-view-modal').load(dataURL, function(){
          $('#viewModal').modal({show: true});
        });
      });

      // Export table data to PDF
      document.getElementById('exportBtn').addEventListener('click', function(){
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        const table = document.getElementById('orderTable');
        let rowCount = table.rows.length;

        let pdfTable = [];
        for(let i = 0; i < rowCount; i++) {
          let row = [];
          let colCount = table.rows[i].cells.length;
          for(let j = 0; j < colCount; j++) {
            row.push(table.rows[i].cells[j].innerText);
          }
          pdfTable.push(row);
        }

        doc.autoTable({
          head: [pdfTable[0]],
          body: pdfTable.slice(1),
        });

        doc.save('EventDetails.pdf');
      });
    });
  </script>
  <!-- Include jsPDF AutoTable plugin -->
</body>
</html>
