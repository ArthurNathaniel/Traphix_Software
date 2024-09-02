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
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Style the chart canvas */
        #barChart {
            width: 80%;
            height: 400px;
            /* Adjust height as needed */
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php' ?>
    <?php
    include 'db.php';

    // Initialize arrays to store labels and data for the chart
    $labels = array();
    $data = array();

    // Loop through each month
    for ($month = 1; $month <= 12; $month++) {
        // Query to retrieve total money gained for each month
        $sql = "SELECT SUM(receipt_services.price) AS money_gained
                FROM receipt
                LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
                WHERE MONTH(receipt.date) = $month";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Store month name as label
        $month_name = date('F', mktime(0, 0, 0, $month, 1));
        $labels[] = $month_name;

        // Store money gained as data
        $data[] = $row['money_gained'] ? (float)$row['money_gained'] : 0;
    }

    $conn->close();
    ?>

    <!-- Create canvas element for the chart -->
    <canvas id="barChart"></canvas>

    <script>
        // JavaScript to create the bar chart using Chart.js
        var ctx = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Money Gained (GH₵)',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Blue color for bars
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>

</html>