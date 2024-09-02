<?php


session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}



// Include database connection file
include 'db.php';

// Fetch invoices from the database
$sql = "SELECT * FROM invoices ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Invoices -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="view_invoices_all">
        <h2>View Invoices</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table border='1' class='invoice_table'>";
            echo "<thead><tr><th>ID</th><th>Billed To</th><th>Contact Number</th><th>Subtotal</th><th>VAT Tax</th><th>Total</th><th>Actions</th></tr></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["billed_to"] . "</td>";
                echo "<td>" . $row["contact_number"] . "</td>";
                echo "<td>" . $row["subtotal"] . "</td>";
                echo "<td>" . $row["vat_tax"] . "</td>";
                echo "<td>" . $row["total"] . "</td>";
                echo "<td><a href='edit_invoice.php?id=" . $row["id"] . "'>Edit</a> | <a href='delete_invoice.php?id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this invoice?\")'>Delete</a> | <a href='print_invoice.php?id=" . $row["id"] . "' target='_blank'>Print</a></td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</body>

</html>