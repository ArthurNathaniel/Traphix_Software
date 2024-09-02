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
    <title>Partial Payments</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #25624d;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .action-links a {
            margin-right: 5px;
        }

        .action-links a:last-child {
            margin-right: 0;
        }

        #noResults {
            text-align: center;
            color: red;
            display: none;
        }
    </style>
    <script>
        function searchTable() {
            // Get the search term from the input
            let input = document.getElementById("searchInput");
            let filter = input.value.toLowerCase();
            let table = document.getElementById("paymentTable");
            let tr = table.getElementsByTagName("tr");
            let noResults = document.getElementById("noResults");

            let foundAny = false;

            // Loop through all table rows, and hide those that don't match the search query
            for (let i = 1; i < tr.length; i++) { // Start loop from 1 to skip the header row
                let tdArray = tr[i].getElementsByTagName("td");
                let found = false;
                for (let j = 0; j < tdArray.length; j++) {
                    let td = tdArray[j];
                    if (td) {
                        if (td.innerHTML.toLowerCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                tr[i].style.display = found ? "" : "none";
                if (found) {
                    foundAny = true;
                }
            }

            // Show or hide the "No results found" row
            noResults.style.display = foundAny ? "none" : "";
        }
    </script>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <?php
    include 'db.php';

    // Query to retrieve only partial payments
    $sql = "SELECT receipt.*, GROUP_CONCAT(CONCAT(services.service_name, ' - GH₵ ', receipt_services.price) SEPARATOR '<br>') AS services_prices
            FROM receipt
            LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
            LEFT JOIN services ON receipt_services.service_id = services.id
            WHERE payment_type = 'Partial'
            GROUP BY receipt.id
            ORDER BY receipt.id DESC";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<div class='manage_all'>";
        echo "<h2>Partial Payments</h2>";

        // Search Input
        echo "<div class='search'>";
        echo "<input type='text' id='searchInput' onkeyup='searchTable()' placeholder='Enter search term'>";
        echo "</div>";

        echo "<table id='paymentTable'>";
        echo "<tr><th>ID</th><th>Client Name</th><th>Payment Method</th><th>Other Method</th><th>Payment Type</th><th>Amount</th><th>Date</th><th>Services and Prices</th><th>Action</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["client_name"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["payment_method"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["other_method"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["payment_type"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["amount"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
            echo "<td>" . $row["services_prices"] . "</td>";
            echo "<td class='action-links'>
                        <a href='edit_receipt.php?id=" . $row['id'] . "'> <i class='fa-solid fa-pen-to-square'></i> </a> |
                        <a href='print_selected_receipt.php?id=" . $row['id'] . "' target='_blank'> <i class='fa-solid fa-print'></i> </a> 
                    </td>";
            echo "</tr>";
        }
        // Add a row to indicate no results found
        echo "<tr id='noResults'><td colspan='9'>No results found</td></tr>";
        echo "</table>";
    } else {
        echo "No partial payments found.";
    }
    echo "</div>";
    $conn->close();
    ?>

</body>

</html>