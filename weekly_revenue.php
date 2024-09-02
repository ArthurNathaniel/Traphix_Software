<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}



include 'db.php';

// Get start and end dates for the current week
$thisMonday = date('Y-m-d', strtotime('monday this week'));
$thisTuesday = date('Y-m-d', strtotime('tuesday this week'));
$thisWednesday = date('Y-m-d', strtotime('wednesday this week'));
$thisThursday = date('Y-m-d', strtotime('thursday this week'));
$thisFriday = date('Y-m-d', strtotime('friday this week'));
$thisSaturday = date('Y-m-d', strtotime('saturday this week'));
$thisSunday = date('Y-m-d', strtotime('sunday this week'));

// Query to get total amount for each day of the current week
$sqlMonday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$thisMonday'";
$sqlTuesday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$thisTuesday'";
$sqlWednesday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$thisWednesday'";
$sqlThursday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$thisThursday'";
$sqlFriday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$thisFriday'";
$sqlSaturday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$thisSaturday'";
$sqlSunday = "SELECT SUM(amount) AS total_amount FROM receipts WHERE DATE(date) = '$thisSunday'";

// Execute queries
$resultMonday = $conn->query($sqlMonday);
$resultTuesday = $conn->query($sqlTuesday);
$resultWednesday = $conn->query($sqlWednesday);
$resultThursday = $conn->query($sqlThursday);
$resultFriday = $conn->query($sqlFriday);
$resultSaturday = $conn->query($sqlSaturday);
$resultSunday = $conn->query($sqlSunday);

// Fetch total amounts for each day
$total_amount_monday = $resultMonday->fetch_assoc()['total_amount'];
$total_amount_tuesday = $resultTuesday->fetch_assoc()['total_amount'];
$total_amount_wednesday = $resultWednesday->fetch_assoc()['total_amount'];
$total_amount_thursday = $resultThursday->fetch_assoc()['total_amount'];
$total_amount_friday = $resultFriday->fetch_assoc()['total_amount'];
$total_amount_saturday = $resultSaturday->fetch_assoc()['total_amount'];
$total_amount_sunday = $resultSunday->fetch_assoc()['total_amount'];

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Revenue -  </title>
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
            background-color: #f2f2f2;
        }

        /* Apply red color to today's row */
        .today {
            background-color: rgb(190, 2, 2);

            color: white;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="revenue_summary_all">
        <h2>Weekly Revenue (<?php echo date('M j, Y', strtotime($thisMonday)); ?> - <?php echo date('M j, Y', strtotime($thisSunday)); ?>)</h2>

        <table>
            <tr>
                <th>Day</th>
                <th>Date</th>
                <th>Total Amount</th>
            </tr>
            <tr <?php echo date('Y-m-d') == $thisMonday ? 'class="today"' : ''; ?>>
                <td>Monday</td>
                <td><?php echo date('M j, Y', strtotime($thisMonday)); ?></td>
                <td><?php echo "GH₵ " . ($total_amount_monday ?? 0); ?></td>
            </tr>
            <tr <?php echo date('Y-m-d') == $thisTuesday ? 'class="today"' : ''; ?>>
                <td>Tuesday</td>
                <td><?php echo date('M j, Y', strtotime($thisTuesday)); ?></td>
                <td><?php echo "GH₵ " . ($total_amount_tuesday ?? 0); ?></td>
            </tr>
            <tr <?php echo date('Y-m-d') == $thisWednesday ? 'class="today"' : ''; ?>>
                <td>Wednesday</td>
                <td><?php echo date('M j, Y', strtotime($thisWednesday)); ?></td>
                <td><?php echo "GH₵ " . ($total_amount_wednesday ?? 0); ?></td>
            </tr>
            <tr <?php echo date('Y-m-d') == $thisThursday ? 'class="today"' : ''; ?>>
                <td>Thursday</td>
                <td><?php echo date('M j, Y', strtotime($thisThursday)); ?></td>
                <td><?php echo "GH₵ " . ($total_amount_thursday ?? 0); ?></td>
            </tr>
            <tr <?php echo date('Y-m-d') == $thisFriday ? 'class="today"' : ''; ?>>
                <td>Friday</td>
                <td><?php echo date('M j, Y', strtotime($thisFriday)); ?></td>
                <td><?php echo "GH₵ " . ($total_amount_friday ?? 0); ?></td>
            </tr>
            <tr <?php echo date('Y-m-d') == $thisSaturday ? 'class="today"' : ''; ?>>
                <td>Saturday</td>
                <td><?php echo date('M j, Y', strtotime($thisSaturday)); ?></td>
                <td><?php echo "GH₵ " . ($total_amount_saturday ?? 0); ?></td>
            </tr>
            <tr <?php echo date('Y-m-d') == $thisSunday ? 'class="today"' : ''; ?>>
                <td>Sunday</td>
                <td><?php echo date('M j, Y', strtotime($thisSunday)); ?></td>
                <td><?php echo "GH₵ " . ($total_amount_sunday ?? 0); ?></td>
            </tr>
        </table>
    </div>
</body>

</html>