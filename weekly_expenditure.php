<?php

include 'db.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get the start and end dates for the current week (Monday to Sunday)
$thisMonday = date('Y-m-d', strtotime('monday this week'));
$thisSunday = date('Y-m-d', strtotime('sunday this week'));

// Query to get total expenditure for each day of the current week (Monday to Sunday)
$sqlWeekly = "SELECT DATE(date) AS expenditure_date, 
                     SUM(amount) AS total_amount 
              FROM expenditure 
              WHERE DATE(date) BETWEEN '$thisMonday' AND '$thisSunday' 
              GROUP BY DATE(date)";
$resultWeekly = $conn->query($sqlWeekly);

// Create an associative array to store total amounts for each day
$weeklyExpenditure = [];
while ($row = $resultWeekly->fetch_assoc()) {
    $expenditure_date = $row['expenditure_date'];
    $total_amount = $row['total_amount'];
    $weeklyExpenditure[$expenditure_date] = $total_amount;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Expenditure -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #25624d;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="create_invoice_all">
        <h2>Weekly Expenditure (<?php echo date('M j', strtotime($thisMonday)); ?> - <?php echo date('M j', strtotime($thisSunday)); ?>)</h2>
        <br>
        <table>
            <tr>
                <th>Date</th>
                <th>Total Expenditure</th>
            </tr>
            <?php
            // Iterate through each day of the week and display the total expenditure
            $currentDate = strtotime($thisMonday);
            $endDate = strtotime($thisSunday);
            while ($currentDate <= $endDate) {
                $date = date('Y-m-d', $currentDate);
                $total_amount = isset($weeklyExpenditure[$date]) ? $weeklyExpenditure[$date] : 0;
                echo "<tr>";
                echo "<td>$date</td>";
                echo "<td>GH₵ $total_amount</td>";
                echo "</tr>";
                // Move to the next day
                $currentDate = strtotime('+1 day', $currentDate);
            }
            ?>
        </table>
    </div>
</body>

</html>