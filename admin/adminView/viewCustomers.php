<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Customers</title>
  <!-- Include jsPDF library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <!-- Include jsPDF AutoTable plugin -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.14/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
  <div>
    <h2>All Customers</h2>
    <button id="exportBtn" class="btn btn-success mb-2">Export as PDF</button>
    <table id="customerTable">
      <thead>
        <tr>
          <th class="text-center">User ID</th>
          <th class="text-center">Username</th>
          <th class="text-center">Email</th>
          <th class="text-center">Age</th>
          <th class="text-center">City</th>
        </tr>
      </thead>
      <tbody>
        <?php
          include_once "../config/dbconnect.php";
          $sql="SELECT * from users where isAdmin=0";
          $result=$conn-> query($sql);
          $count=1;
          if ($result-> num_rows > 0){
            while ($row=$result-> fetch_assoc()) {
        ?>
        <tr>
          <td><?=$count?></td>
          <td><?=$row["first_name"]?></td>
          <td><?=$row["email"]?></td>
          <td><?=$row["age"]?></td>
          <td><?=$row["city"]?></td>
        </tr>
        <?php
              $count++;
            }
          }
        ?>
      </tbody>
    </table>
  </div>

  <script>
    document.getElementById('exportBtn').addEventListener('click', function() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();

      // Get table data
      const table = document.getElementById('customerTable');
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

      doc.save('Customers.pdf');
    });
  </script>
</body>
</html>
