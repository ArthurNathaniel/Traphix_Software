<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

$sql = "SELECT * FROM receipts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>All Receipts</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Client Name</th><th>Payment Method</th><th>Other Method</th><th>Payment Type</th><th>Amount</th><th>Amount Words</th><th>Date</th><th>Action</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["client_name"] . "</td>";
        echo "<td>" . $row["payment_method"] . "</td>";
        echo "<td>" . $row["other_method"] . "</td>";
        echo "<td>" . $row["payment_type"] . "</td>";
        echo "<td>" . $row["amount"] . "</td>";
        echo "<td>" . $row["amount_words"] . "</td>";
        echo "<td>" . $row["date"] . "</td>";
        echo "<td><a href='edit_receipt.php?id=" . $row["id"] . "'>Edit</a> | <a href='print_selected_receipt.php?id=" . $row["id"] . "' target='_blank'>Print</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
