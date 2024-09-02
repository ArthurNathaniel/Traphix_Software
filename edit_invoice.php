<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


// Establish a database connection
include 'db.php';

// Fetch invoices from the database
$sql = "SELECT * FROM invoices";
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoices -  </title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="edit_invoice_all">
        <h2>Edit Invoices</h2>
        <?php if ($result->num_rows > 0) : ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Invoice ID</th>
                        <th>Billed To</th>
                        <th>Contact Number</th>
                        <th>Subtotal</th>
                        <th>VAT Tax</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['billed_to']; ?></td>
                            <td><?php echo $row['contact_number']; ?></td>
                            <td><?php echo $row['subtotal']; ?></td>
                            <td><?php echo $row['vat_tax']; ?></td>
                            <td><?php echo $row['total']; ?></td>
                            <td>
                                <a href="edit_single_invoice.php?id=<?php echo $row['id']; ?>">Edit</a> |
                                <a href="delete_invoice.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this invoice?')">Delete</a> |
                                <a href="print_invoice.php?id=<?php echo $row['id']; ?>" target="_blank">Print</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No invoices found.</p>
        <?php endif; ?>
    </div>
</body>

</html>