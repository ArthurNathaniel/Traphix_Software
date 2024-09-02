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
    <title>Receipt Management</title>
    <?php include 'cdn.php' ?>
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
    </style>
</head>

<body>
    <?php include 'sidebar.php' ?>


    <?php
    include 'db.php';

    // Check if a receipt ID is provided for deletion
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        // Delete associated records from receipt_services table first
        $sql_delete_services = "DELETE FROM receipt_services WHERE receipt_id = ?";
        $stmt_delete_services = $conn->prepare($sql_delete_services);
        $stmt_delete_services->bind_param("i", $delete_id);
        if ($stmt_delete_services->execute()) {
            // Now delete the receipt from the receipt table
            $sql_delete = "DELETE FROM receipt WHERE id = ?";
            $stmt = $conn->prepare($sql_delete);
            $stmt->bind_param("i", $delete_id);
            if ($stmt->execute()) {
                echo "<script>alert('Receipt with ID $delete_id has been deleted successfully.');window.location.href = 'view_receipts.php';</script>";
                // Redirect back to this page to refresh the table
                // header("Location: view_receipts.php");
                exit();
            } else {
                echo "Error deleting receipt record: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error deleting receipt services records: " . $stmt_delete_services->error;
        }
        $stmt_delete_services->close();
    }

    // Process search form submission
    if (isset($_POST['search'])) {
        $search_term = $_POST['search_term'];

        // Query to retrieve receipt based on search term
        $sql_search = "SELECT receipt.*, GROUP_CONCAT(CONCAT(services.service_name, ' - $', receipt_services.price) SEPARATOR '<br>') AS services_prices
                FROM receipt
                LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
                LEFT JOIN services ON receipt_services.service_id = services.id
                WHERE 
                client_name LIKE ? OR 
                payment_method LIKE ? OR 
                other_method LIKE ? OR 
                payment_type LIKE ? OR 
                amount LIKE ? OR 
                date LIKE ?
                GROUP BY receipt.id
                ORDER BY receipt.id DESC";
        $stmt = $conn->prepare($sql_search);
        $search_term = "%$search_term%";
        $stmt->bind_param("ssssss", $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        // Query to retrieve all receipt in descending order of ID
        $sql = "SELECT receipt.*, GROUP_CONCAT(CONCAT(services.service_name, ' -  GH₵ ', receipt_services.price) SEPARATOR '<br>') AS services_prices
                FROM receipt
                LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
                LEFT JOIN services ON receipt_services.service_id = services.id
                GROUP BY receipt.id
                ORDER BY receipt.id DESC";
        $result = $conn->query($sql);
    }

    if ($result->num_rows > 0) {
        echo "<div class='manage_all'>";
        echo "<h2>All receipt</h2>";

        // Search Form
        echo "<form method='post'>";
        echo "<div class='search'>";
        echo "<input type='text' name='search_term' placeholder='Enter search term'>";
        echo "<button type='submit' name='search'>Search</button>";
        echo "</div>";
        echo "</form>";

        echo "<table>";
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
            echo "<td>" . $row["services_prices"]  . "</td>";
            // echo "<td class='action-links'>
            //                 <a href='edit_receipt.php?id=" . $row['id'] . "'>Edit</a> |
            //                 <a href='print_selected_receipt.php?id=" . $row['id'] . "' target='_blank'>Print</a> |
            //                 <a href='?delete_id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this receipt?')\">Delete</a>
            //             </td>";
                        echo "<td class='action-links'>
                        <a href='print_selected_receipt.php?id=" . $row['id'] . "' target='_blank'> <i class='fa-solid fa-print'></i> </a> 
                    </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }
    echo "</div>";
    $conn->close();
    ?>

</body>

</html>