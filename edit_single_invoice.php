<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// Check if invoice ID is provided
if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Fetch invoice data based on the provided ID
    $sql = "SELECT * FROM invoices WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $invoice_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Invoice not found.";
        exit;
    }
    $stmt->close();

    // Fetch invoice details
    $details_sql = "SELECT * FROM invoice_services WHERE invoice_id = ?";
    $details_stmt = $conn->prepare($details_sql);
    $details_stmt->bind_param("i", $invoice_id);
    $details_stmt->execute();
    $details_result = $details_stmt->get_result();
} else {
    echo "Invoice ID not provided.";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $billed_to = $_POST['billed_to'];
    $contact_number = $_POST['contact_number'];
    $subtotal = $_POST['subtotal'];
    $vat_tax = $_POST['vat_tax'];
    $total = $_POST['total'];

    // Update invoice header data in the database
    $update_sql = "UPDATE invoices SET billed_to = ?, contact_number = ?, subtotal = ?, vat_tax = ?, total = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssdddi", $billed_to, $contact_number, $subtotal, $vat_tax, $total, $invoice_id);

    if ($update_stmt->execute()) {
        // Update invoice details
        $service = $_POST['service'];
        $day = $_POST['day'];
        $price = $_POST['price'];
        $amount = $_POST['amount'];

        // Delete existing invoice details
        $delete_sql = "DELETE FROM invoice_services WHERE invoice_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $invoice_id);
        $delete_stmt->execute();
        $delete_stmt->close();

        // Insert new invoice details
        $insert_sql = "INSERT INTO invoice_services (invoice_id, service, day, price, amount) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);

        for ($i = 0; $i < count($service); $i++) {
            $insert_stmt->bind_param("issdd", $invoice_id, $service[$i], $day[$i], $price[$i], $amount[$i]);
            $insert_stmt->execute();
        }

        $insert_stmt->close();

        echo "<script>alert('Invoice updated successfully.'); window.location.href = 'view_invoices.php';</script>";
    } else {
        echo "Error updating invoice: " . $conn->error;
    }
    $update_stmt->close();
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Invoice -  </title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        .invoice_details_table input[type="text"],
        .invoice_details_table input[type="number"] {
            width: 100%;
        }

        .invoice_details_table th,
        .invoice_details_table td {
            padding: 8px;
        }

        .invoice_details_table th:nth-child(1),
        .invoice_details_table td:nth-child(1) {
            width: 5%;
        }

        .invoice_details_table th:nth-child(5),
        .invoice_details_table td:nth-child(5) {
            width: 15%;
        }

        .invoice_details_table th:last-child,
        .invoice_details_table td:last-child {
            width: 10%;
        }

        .add-row-button,
        .delete-row-button {
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="create_invoice_all">
        <h2>Edit Invoice</h2>
        <form action="" method="post">
            <div class="forms">
                <label>Billed to:</label>
                <input type="text" name="billed_to" value="<?php echo $row['billed_to']; ?>" required>
            </div>
            <div class="forms">
                <label>Contact Number:</label>
                <input type="text" name="contact_number" value="<?php echo $row['contact_number']; ?>" required>
            </div>
            <div class="forms">
                <label>Subtotal:</label>
                <input type="number" name="subtotal" value="<?php echo $row['subtotal']; ?>" required>
            </div>
            <div class="forms">
                <label>VAT Tax:</label>
                <input type="number" name="vat_tax" value="<?php echo $row['vat_tax']; ?>" required>
            </div>
            <div class="forms">
                <label>Total:</label>
                <input type="number" name="total" value="<?php echo $row['total']; ?>" required>
            </div>

            <h3>Invoice Details</h3>
            <table border="1" class="invoice_details_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Service</th>
                        <th>Day</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $row_number = 1;
                    while ($detail_row = $details_result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>{$row_number}</td>";
                        echo "<td><input type='text' name='service[]' value='{$detail_row['service']}' required></td>";
                        echo "<td><input type='number' name='day[]' value='{$detail_row['day']}' required></td>";
                        echo "<td><input type='number' name='price[]' value='{$detail_row['price']}' required></td>";
                        echo "<td><input type='number' name='amount[]' value='{$detail_row['amount']}' required></td>";
                        echo "<td class='actions'><span class='add-row-button' onclick='addRow(this)'>+</span> <span class='delete-row-button' onclick='deleteRow(this)'>-</span></td>";
                        echo "</tr>";
                        $row_number++;
                    }
                    ?>
                </tbody>
            </table>
            <div class="forms">
                <button type="submit">Update Invoice</button>
            </div>
        </form>
    </div>

    <script>
        function addRow(button) {
            var row = button.parentNode.parentNode;
            var newRow = row.cloneNode(true);
            newRow.querySelector('.add-row-button').innerText = '+';
            newRow.querySelector('.delete-row-button').innerText = '-';
            newRow.querySelector('.add-row-button').setAttribute('onclick', 'addRow(this)');
            newRow.querySelector('.delete-row-button').setAttribute('onclick', 'deleteRow(this)');
            var table = row.parentNode;
            table.appendChild(newRow);
        }

        function deleteRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }
    </script>
</body>

</html>