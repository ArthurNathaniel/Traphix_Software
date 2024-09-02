<?php

include 'db.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Get start and end dates for the current year
$thisYear = date('Y');
$thisYearStart = $thisYear . '-01-01';
$thisYearEnd = $thisYear . '-12-31';

// Calculate the next 10 years
$next10Years = [];
for ($i = 0; $i < 10; $i++) {
    $nextYear = date('Y', strtotime("+$i year"));
    $next10Years[] = $nextYear;
}

// Query to get total amount for each year
$sqlYearly = "SELECT YEAR(date) AS year, SUM(amount) AS total_amount FROM receipt WHERE YEAR(date) BETWEEN '$thisYear' AND '" . end($next10Years) . "' GROUP BY YEAR(date)";
$resultYearly = $conn->query($sqlYearly);

// Create an associative array to store total amounts for each year
$yearlyRevenue = [];
while ($row = $resultYearly->fetch_assoc()) {
    $year = $row['year'];
    $total_amount = $row['total_amount'];
    $yearlyRevenue[$year] = $total_amount;
}

// Query to get total expenditure for each year
$sqlExpenditure = "SELECT YEAR(date) AS year, SUM(amount) AS total_amount FROM expenditure WHERE YEAR(date) BETWEEN '$thisYear' AND '" . end($next10Years) . "' GROUP BY YEAR(date)";
$resultExpenditure = $conn->query($sqlExpenditure);

// Create an associative array to store total expenditures for each year
$yearlyExpenditure = [];
while ($row = $resultExpenditure->fetch_assoc()) {
    $year = $row['year'];
    $total_amount = $row['total_amount'];
    $yearlyExpenditure[$year] = $total_amount;
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Revenue -  </title>
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
    <div class="yearly_revenue">
        <h2>Yearly Revenue</h2>

        <!-- Search Form -->
        <div class="search">
            <label for="search_year">Search by Year:</label>
            <input type="text" id="search_year" placeholder="Enter year">
            <button onclick="searchYear()">Search</button>
        </div>

        <table id="revenue_table">
            <tr>
                <th>Year</th>
                <th>Total Revenue</th>
                <th>Total Expenditure</th>
                <th>Net Revenue</th>
            </tr>
            <?php
            // Iterate through each year and display the total amount, expenditure, and net revenue
            foreach ($next10Years as $year) {
                $total_revenue = isset($yearlyRevenue[$year]) ? $yearlyRevenue[$year] : 0;
                $total_expenditure = isset($yearlyExpenditure[$year]) ? $yearlyExpenditure[$year] : 0;
                $net_revenue = $total_revenue - $total_expenditure;
                echo "<tr>";
                echo "<td>$year</td>";
                echo "<td>GH₵ $total_revenue</td>";
                echo "<td>GH₵ $total_expenditure</td>";
                echo "<td>GH₵ $net_revenue</td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>

    <script>
        function searchYear() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("search_year");
            filter = input.value.toUpperCase();
            table = document.getElementById("revenue_table");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>
</body>

</html>