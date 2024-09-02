<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Management</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        /* Your existing CSS styles */
    </style>
</head>

<body>
    <?php include 'sidebar.php' ?>
    <?php
    include 'db.php';

    // Initialize an array to store monthly data
    $monthly_data = array();

    // Loop through each month
    for ($month = 1; $month <= 12; $month++) {
        // Query to retrieve each service and money gained from each service in a month
        $sql = "SELECT YEAR(receipt.date) AS year, MONTH(receipt.date) AS month, 
                    services.service_name,
                    SUM(receipt_services.price) AS money_gained
                FROM receipt
                LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
                LEFT JOIN services ON receipt_services.service_id = services.id
                WHERE MONTH(receipt.date) = $month
                GROUP BY YEAR(receipt.date), MONTH(receipt.date), services.id
                ORDER BY year DESC, month DESC, money_gained DESC";
        $result = $conn->query($sql);

        // Store the result for the month
        $monthly_data[$month] = $result;
    }

    // Display table
    echo "<div class='manage_all'>";
    echo "<h2>Services and Money Gained for Each Month</h2>";
    echo "<table>";
    echo "<tr><th>Month</th><th>Service</th><th>Money Gained (GH₵)</th></tr>";
    for ($month = 1; $month <= 12; $month++) {
        $month_name = date('F', mktime(0, 0, 0, $month, 1));
        if ($monthly_data[$month]->num_rows > 0) {
            while ($row = $monthly_data[$month]->fetch_assoc()) {
                echo "<tr>";
                echo "<td>$month_name {$row['year']}</td>";
                echo "<td>{$row['service_name']}</td>";
                echo "<td>" . number_format($row['money_gained'], 2) . "</td>";
                echo "</tr>";
            }
        } else {
            // If no data for the month, display N/A
            echo "<tr>";
            echo "<td>$month_name</td>";
            echo "<td colspan='2'>N/A</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
    echo "</div>";

    $conn->close();
    ?>
</body>

</html>