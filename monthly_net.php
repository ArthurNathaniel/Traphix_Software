<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Get start and end dates for the current year
$thisYearStart = date('Y-01-01');
$thisYearEnd = date('Y-12-31');

// Query to get total amount for each month of the current year
$sqlYearly = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total_amount FROM receipt WHERE DATE(date) BETWEEN '$thisYearStart' AND '$thisYearEnd' GROUP BY MONTH(date)";
$resultYearly = $conn->query($sqlYearly);

// Create an associative array to store total amounts for each month
$yearlyRevenue = [];
while ($row = $resultYearly->fetch_assoc()) {
    $month = $row['month'];
    $total_amount = $row['total_amount'];
    $yearlyRevenue[$month] = $total_amount;
}

// Query to get total expenditure for each month of the current year
$sqlExpenditure = "SELECT DATE_FORMAT(date, '%Y-%m') AS month, SUM(amount) AS total_amount FROM expenditure WHERE DATE(date) BETWEEN '$thisYearStart' AND '$thisYearEnd' GROUP BY MONTH(date)";
$resultExpenditure = $conn->query($sqlExpenditure);

// Create an associative array to store total expenditures for each month
$yearlyExpenditure = [];
while ($row = $resultExpenditure->fetch_assoc()) {
    $month = $row['month'];
    $total_amount = $row['total_amount'];
    $yearlyExpenditure[$month] = $total_amount;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue Revenue - The  Rub Haven </title>
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

        .month {
            background-color: blue;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="yearly_revenue">
        <h2>Monthly Revenue</h2>

        <table>
            <tr>
                <th>Month</th>
                <th>Total Revenue</th>
                <th>Total Expenditure</th>
                <th>Net Revenue</th>
            </tr>
            <?php
            // Loop through each month of the year and display the revenue and expenditure
            for ($month = 1; $month <= 12; $month++) {
                $monthFormatted = str_pad($month, 2, '0', STR_PAD_LEFT); // Pad single-digit months with leading zero
                $date = date('Y') . '-' . $monthFormatted;
                $total_revenue = isset($yearlyRevenue[$date]) ? $yearlyRevenue[$date] : 0;
                $total_expenditure = isset($yearlyExpenditure[$date]) ? $yearlyExpenditure[$date] : 0;
                $net_revenue = $total_revenue - $total_expenditure;
            ?>
                <tr>
                    <td><?php echo date('F', strtotime($date)); ?></td>
                    <td><?php echo "GH₵ " . $total_revenue; ?></td>
                    <td><?php echo "GH₵ " . $total_expenditure; ?></td>
                    <td><?php echo "GH₵ " . $net_revenue; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</body>

</html>