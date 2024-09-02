<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Management</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Include Chart.js library -->
    <style>
        /* Your existing CSS styles */
        #pieChart {
            width: 100%;
            max-width: 400px;
            /* Limiting chart width for better visualization */
            margin: 20px auto;
            /* Centering chart */
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php' ?>
    <?php
    include 'db.php';

    // Query to retrieve total money gained for each service
    $sql = "SELECT services.service_name,
                    SUM(receipt_services.price) AS money_gained
                FROM receipt_services
                LEFT JOIN services ON receipt_services.service_id = services.id
                GROUP BY services.service_name
                ORDER BY money_gained DESC";
    $result = $conn->query($sql);

    // Initialize arrays to store service names and money gained
    $service_names = array();
    $money_gained = array();

    // Process query result
    while ($row = $result->fetch_assoc()) {
        $service_names[] = $row['service_name'];
        $money_gained[] = (float)$row['money_gained'];
    }

    $conn->close();
    ?>

    <!-- Create canvas element for the chart -->
    <canvas id="pieChart"></canvas>

    <script>
        // JavaScript to create the pie chart using Chart.js
        var ctx = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($service_names); ?>,
                datasets: [{
                    label: 'Money Gained (GH₵)',
                    data: <?php echo json_encode($money_gained); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)', // Red
                        'rgba(54, 162, 235, 0.5)', // Blue
                        'rgba(255, 206, 86, 0.5)', // Yellow
                        'rgba(75, 192, 192, 0.5)', // Green
                        'rgba(153, 102, 255, 0.5)', // Purple
                        'rgba(255, 159, 64, 0.5)' // Orange
                        // Add more colors if needed
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                legend: {
                    position: 'right'
                },
                title: {
                    display: true,
                    text: 'Money Gained by Service'
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    </script>
</body>

</html>