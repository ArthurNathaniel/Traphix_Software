<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'db.php';

// Function to safely escape input values
function escape($value)
{
    global $conn;
    return mysqli_real_escape_string($conn, $value);
}

// Initialize variables
$search_term = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_term = escape($_POST['search_term']);

    // Query to retrieve receipt based on search term
    $sql = "SELECT receipt.*, 
                    GROUP_CONCAT(CONCAT(services.service_name, ' - GH₵', receipt_services.price) SEPARATOR '<br>') AS services_prices,
                    SUM(receipt_services.price) AS total_amount
            FROM receipt
            LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
            LEFT JOIN services ON receipt_services.service_id = services.id
            WHERE 
            client_name LIKE '%$search_term%' OR 
            payment_method LIKE '%$search_term%' OR 
            other_method LIKE '%$search_term%' OR 
            payment_type LIKE '%$search_term%' OR 
            receipt_services.price LIKE '%$search_term%' OR 
            date LIKE '%$search_term%'
            GROUP BY receipt.id
            ORDER BY receipt.id DESC";
} else {
    // Query to retrieve all receipts
    $sql = "SELECT receipt.*, 
                    GROUP_CONCAT(CONCAT(services.service_name, ' - GH₵', receipt_services.price) SEPARATOR '<br>') AS services_prices,
                    SUM(receipt_services.price) AS total_amount
            FROM receipt
            LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
            LEFT JOIN services ON receipt_services.service_id = services.id
            GROUP BY receipt.id
            ORDER BY receipt.id DESC";
}

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Management</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        /* Your CSS styles */
    </style>
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="manage_all">
        <h2>All Receipts</h2>
        <form method="post">
            <div class="search">
                <input type="text" name="search_term" placeholder="Enter search term" value="<?php echo $search_term; ?>">
                <button type="submit" name="search">Search</button>
            </div>
        </form>
        <?php if ($result->num_rows > 0) : ?>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Payment Method</th>
                    <th>Other Method</th>
                    <th>Payment Type</th>
                    <th>Total Amount</th>
                    <th>Date</th>
                    <th>Services and Prices</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["client_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["payment_method"]); ?></td>
                        <td><?php echo htmlspecialchars($row["other_method"]); ?></td>
                        <td><?php echo htmlspecialchars($row["payment_type"]); ?></td>
                        <td><?php echo htmlspecialchars($row["total_amount"]); ?></td>
                        <td><?php echo htmlspecialchars($row["date"]); ?></td>
                        <td><?php echo $row["services_prices"]; ?></td>
                        <td class="action-links">
                            <a href="edit_receipt.php?id=<?php echo $row['id']; ?>">Edit</a> |
                            <a href="print_selected_receipt.php?id=<?php echo $row['id']; ?>" target="_blank">Print</a> |
                            <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this receipt?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else : ?>
            <p>No results found.</p>
        <?php endif; ?>
    </div>
</body>

</html>

<?php
$conn->close();
?>