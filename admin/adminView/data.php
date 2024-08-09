<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ceylon_happens"; // Replace with your actual database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch categories and their event counts
$sql = "SELECT c.category_name, COUNT(p.product_id) AS event_count 
        FROM category c
        LEFT JOIN product p ON c.category_id = p.category_id
        GROUP BY c.category_id";

$result = $conn->query($sql);

$categories = [];
$eventCounts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $categories[] = $row['category_name'];
        $eventCounts[] = $row['event_count'];
    }
}
// Fetch products and their total ticket counts
$sql = "SELECT p.product_name, SUM(o.quantity) AS total_tickets 
        FROM product p
        LEFT JOIN orders o ON p.product_id = o.product_id
        GROUP BY p.product_id";

$result = $conn->query($sql);

$products = [];
$ticketCounts = [];

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row['product_name'];
        $ticketCounts[] = $row['total_tickets'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events by Category</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <style>
        .pie {
            width: 400px;
            height: 400px;
            float: left;
        }
        .bar {
            padding-top:100px;
            width: 400px;
            height: 400px;
            float: right;
        }
        .charts{
            
        
    flex-direction: column;

        }
    </style>
</head>
<body>
    <div class="charts">
<div class="pie">
<canvas id="myPieChart"></canvas>
    </div>
    <div class="bar">
        <canvas id="myBarChart"></canvas>
    </div>
    </div>
<script>
    $(document).ready(function(){
        // AJAX request to fetch data
        $.ajax({
            url: 'fetch_data.php',
            method: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
              
                const categories = data.categories;
                const eventCounts = data.eventCounts;
                

                // Create the pie chart
                const ctx = document.getElementById('myPieChart').getContext('2d');
                const myPieChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: categories,
                        datasets: [{
                            data: eventCounts,
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#66BB6A'],
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Events by Category'
                            }
                            
                        }
                    }
                });
                
            }
        });

        

        
    
    
     // AJAX request to fetch bar chart data
     $.ajax({
            url: 'fetch_bar_data.php', // Replace with your actual PHP script path for bar chart
            method: 'GET',
            success: function(response) {
                const data = JSON.parse(response);
                const products = data.products;
                const ticketCounts = data.ticketCounts;

                // Create the bar chart
                const barCtx = document.getElementById('myBarChart').getContext('2d');
                new Chart(barCtx, {
                    type: 'bar',
                    data: {
                        labels: products,
                        datasets: [{
                            label: 'Total Tickets',
                            data: ticketCounts,
                            backgroundColor: '#36A2EB',
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Total Tickets by Events'
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error (Bar Chart): ' + status + error);
            }
        });
        

        
    });

    
</script>

</body>
</html>
