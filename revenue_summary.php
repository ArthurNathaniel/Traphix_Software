<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Get today's date
$today = date("Y-m-d");

// Get start and end dates for the current week
$thisMonday = date('Y-m-d', strtotime('monday this week'));
$thisSunday = date('Y-m-d', strtotime('sunday this week'));

// Get start and end dates for the current month
$thisMonthStart = date('Y-m-01');
$thisMonthEnd = date('Y-m-t');

// Get start and end dates for the current year
$thisYearStart = date('Y-01-01');
$thisYearEnd = date('Y-12-31');

// Query to get total amount for today
$sqlToday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$today'";
$resultToday = $conn->query($sqlToday);
$rowToday = $resultToday->fetch_assoc();
$total_amount_today = $rowToday['total_amount'];

// Query to get total amount for the current week
$sqlWeek = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) BETWEEN '$thisMonday' AND '$thisSunday'";
$resultWeek = $conn->query($sqlWeek);
$rowWeek = $resultWeek->fetch_assoc();
$total_amount_week = $rowWeek['total_amount'];

// Query to get total amount for the current month
$sqlMonth = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) BETWEEN '$thisMonthStart' AND '$thisMonthEnd'";
$resultMonth = $conn->query($sqlMonth);
$rowMonth = $resultMonth->fetch_assoc();
$total_amount_month = $rowMonth['total_amount'];

// Query to get total amount for the current year
$sqlYear = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) BETWEEN '$thisYearStart' AND '$thisYearEnd'";
$resultYear = $conn->query($sqlYear);
$rowYear = $resultYear->fetch_assoc();
$total_amount_year = $rowYear['total_amount'];

// Query to get total amount for all time
$sqlAllTime = "SELECT SUM(amount) AS total_amount FROM receipts";
$resultAllTime = $conn->query($sqlAllTime);
$rowAllTime = $resultAllTime->fetch_assoc();
$total_amount_all_time = $rowAllTime['total_amount'];

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue Summary -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="revenue_summary_all">
        <div class="revenue_title">
            <h1>Revenue Summary</h1>
            <a href="all_net.php">Net Revenue</a>
        </div>
        <div class="revenue_grid">


            <div class="revenue">
                <h2>Daily Revenue</h2>
                <p>Total amount received today: </p>
                <?php echo "<h1> GH₵ " . number_format(($total_amount_today ?? 0), 0, '.', ','); ?></h1>
            </div>
            <div class="revenue">
                <h2>Weekly Revenue</h2>
                <p>Total amount received this week: </p>
                <?php echo "<h1> GH₵ " . number_format(($total_amount_week ?? 0), 0, '.', ','); ?></h1>

            </div>
            <div class="revenue">

                <h2>Monthly Revenue</h2>
                <p>Total amount received this month:</p>
                <?php echo "<h1> GH₵ " . number_format(($total_amount_month ?? 0), 0, '.', ','); ?></h1>

            </div>
            <div class="revenue">
                <h2>Yearly Revenue</h2>
                <p>Total amount received this year:</p>
                <?php echo "<h1> GH₵ " . number_format(($total_amount_year ?? 0), 0, '.', ','); ?></h1>

            </div>
            <div class="revenue">
                <h2>All Time Revenue</h2>
                <p>Total amount received all time:</p>
                <?php echo "<h1> GH₵ " . number_format(($total_amount_all_time ?? 0), 0, '.', ','); ?></h1>
            </div>


        </div>
    </div>
</body>

</html>