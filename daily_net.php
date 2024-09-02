<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Proceed with displaying the page content since the user is logged in

include 'db.php';

// Get start and end dates for the current year
$thisYearStart = date('Y-01-01');
$thisYearEnd = date('Y-12-31');

// Query to get total amount for each day of the current year
$sqlYearly = "SELECT DATE(date) AS date, SUM(amount) AS total_amount FROM receipt WHERE DATE(date) BETWEEN '$thisYearStart' AND '$thisYearEnd' GROUP BY DATE(date)";
$resultYearly = $conn->query($sqlYearly);

// Create an associative array to store total amounts for each date
$yearlyRevenue = [];
while ($row = $resultYearly->fetch_assoc()) {
    $date = $row['date'];
    $total_amount = $row['total_amount'];
    $yearlyRevenue[$date] = $total_amount;
}

// Query to get total expenditure for each day of the current year
$sqlExpenditure = "SELECT DATE(date) AS date, SUM(amount) AS total_amount FROM expenditure WHERE DATE(date) BETWEEN '$thisYearStart' AND '$thisYearEnd' GROUP BY DATE(date)";
$resultExpenditure = $conn->query($sqlExpenditure);

// Create an associative array to store total expenditures for each date
$yearlyExpenditure = [];
while ($row = $resultExpenditure->fetch_assoc()) {
    $date = $row['date'];
    $total_amount = $row['total_amount'];
    $yearlyExpenditure[$date] = $total_amount;
}

// Close database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Revenue -  </title>
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
    <div class="revenue_summary_all">
        <h2>Daily Revenue</h2>

        <!-- Search Form -->

        <form id="search_form">
            <div class="search">
                <label for="search_date">Search by Date:</label>
                <input type="date" id="search_date" name="search_date">
                <button type="submit">Search</button>
            </div>
        </form>


        <table id="revenue_table">
            <tr>
                <th>Date</th>
                <th>Total Revenue</th>
                <th>Total Expenditure</th>
                <th>Net Revenue</th>
            </tr>
            <?php
            // Loop through each day of the year and display the revenue, expenditure, and net revenue
            $currentDate = strtotime($thisYearStart);
            $endDate = strtotime($thisYearEnd);
            while ($currentDate <= $endDate) {
                $date = date('Y-m-d', $currentDate);
                $total_revenue = isset($yearlyRevenue[$date]) ? $yearlyRevenue[$date] : 0;
                $total_expenditure = isset($yearlyExpenditure[$date]) ? $yearlyExpenditure[$date] : 0;
                $net_revenue = $total_revenue - $total_expenditure;
            ?>
                <tr class="revenue_row">
                    <td><?php echo $date; ?></td>
                    <td><?php echo "GH₵ " . $total_revenue; ?></td>
                    <td><?php echo "GH₵ " . $total_expenditure; ?></td>
                    <td><?php echo "GH₵ " . $net_revenue; ?></td>
                </tr>
            <?php
                // Move to the next day
                $currentDate = strtotime('+1 day', $currentDate);
            }
            ?>
        </table>
    </div>

    <script>
        document.getElementById("search_form").addEventListener("submit", function(event) {
            event.preventDefault(); // Prevent form submission

            var inputDate = document.getElementById("search_date").value;
            var table = document.getElementById("revenue_table");
            var rows = table.getElementsByTagName("tr");
            for (var i = 1; i < rows.length; i++) {
                var rowDate = rows[i].getElementsByTagName("td")[0].innerText.trim();
                if (rowDate === inputDate) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        });
    </script>
</body>

</html>