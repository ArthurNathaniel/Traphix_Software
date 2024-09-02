<?php
include 'db.php'; // Include the database connection file

// Fetch expenditures from the database, grouped by month and ordered chronologically
$sql = "SELECT MONTH(date) AS month, YEAR(date) AS year, 
        SUM(amount) AS total_amount 
        FROM expenditure 
        GROUP BY YEAR(date), MONTH(date) 
        ORDER BY YEAR(date), MONTH(date)";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenditure List -  </title>
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

        <h1>Expenditure List</h1>
        <br>
        <br>
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Year</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . date('F', mktime(0, 0, 0, $row["month"], 1)) . "</td>"; // Format month name
                        echo "<td>" . $row["year"] . "</td>";
                        echo "<td>" . $row["total_amount"] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No expenditures found</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
    <script src="../js/sidebar.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>