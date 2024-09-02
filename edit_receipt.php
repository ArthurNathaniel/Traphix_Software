<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'db.php';

// Check if ID parameter is provided
if (!isset($_GET['id'])) {
    echo "Error: Receipt ID not provided.";
    exit();
}

// Retrieve receipt ID from URL parameter
$receipt_id = $_GET['id'];

// Fetch receipt data from the database
$sql_receipt = "SELECT * FROM receipt WHERE id = ?";
$stmt = $conn->prepare($sql_receipt);
$stmt->bind_param("i", $receipt_id);
$stmt->execute();
$result_receipt = $stmt->get_result();

// Check if receipt exists
if ($result_receipt->num_rows == 0) {
    echo "Error: Receipt not found.";
    exit();
}

$row_receipt = $result_receipt->fetch_assoc();
$stmt->close();

// Fetch services associated with the receipt from the database
$sql_services = "SELECT service_id, price FROM receipt_services WHERE receipt_id = ?";
$stmt = $conn->prepare($sql_services);
$stmt->bind_param("i", $receipt_id);
$stmt->execute();
$result_services = $stmt->get_result();

// Store services and their prices in an associative array
$services = [];
while ($row = $result_services->fetch_assoc()) {
    $services[$row['service_id']] = $row['price'];
}
$stmt->close();

// Fetch all available services
$sql_all_services = "SELECT * FROM services";
$result_all_services = $conn->query($sql_all_services);

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Receipt -  </title>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="receipt_forms">
        <h2>Edit Receipt</h2>
        <form action="update_receipt.php" method="post" name="receipt_form">
            <input type="hidden" name="receipt_id" value="<?php echo $receipt_id; ?>">
            <div class="forms">
                <label>Client Name:</label>
                <input type="text" name="client_name" value="<?php echo htmlspecialchars($row_receipt['client_name']); ?>" required>
            </div>
            <div class="forms">
                <label>Payment Method:</label>
                <select name="payment_method" id="payment_method" onchange="toggleOther()">
                    <option value="cash" <?php if ($row_receipt['payment_method'] == 'cash') echo 'selected'; ?>>Cash</option>
                    <option value="momo" <?php if ($row_receipt['payment_method'] == 'momo') echo 'selected'; ?>>Momo</option>
                    <option value="bank_transfer" <?php if ($row_receipt['payment_method'] == 'bank_transfer') echo 'selected'; ?>>Bank Transfer</option>
                    <option value="other" <?php if ($row_receipt['payment_method'] == 'other') echo 'selected'; ?>>Other</option>
                </select>
                <div id="other_method" style="display:<?php echo ($row_receipt['payment_method'] == 'other') ? 'block' : 'none'; ?>;">
                    Other Method: <input type="text" name="other_method" value="<?php echo htmlspecialchars($row_receipt['other_method']); ?>"><br>
                </div>
            </div>
            <div class="forms">
                <label>Full or Partial Payment:</label>
                <select name="payment_type" required>
                    <option value="full" <?php if ($row_receipt['payment_type'] == 'full') echo 'selected'; ?>>Full</option>
                    <option value="partial" <?php if ($row_receipt['payment_type'] == 'partial') echo 'selected'; ?>>Partial</option>
                </select>
            </div>

            <div class="forms">
                <label>Select Service:</label><br>
                <table>
                    <thead>
                        <tr>
                            <th>Service</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($result_all_services as $service) {
                            echo "<tr>";
                            echo "<td class='ccc'><input type='checkbox' name='service[]' value='" . $service["service_name"] . "' ";
                            if (isset($services[$service["id"]])) echo "checked";
                            echo ">" . $service["service_name"] . "</td>";
                            echo "<td class='inn'><input type='number' name='price[" . $service["service_name"] . "]' placeholder='Price for " . $service["service_name"] . "' value='" . ($services[$service["id"]] ?? '') . "'></td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="forms">
                <label>Total Amount:</label>
                <input type="number" name="total_amount" value="<?php echo htmlspecialchars($row_receipt['amount']); ?>">
            </div>

            <div class="forms">
                <label>Date:</label>
                <input type="date" name="date" value="<?php echo htmlspecialchars($row_receipt['date']); ?>" required>
                <input type="hidden" name="original_date" value="<?php echo htmlspecialchars($row_receipt['date']); ?>">
            </div>



            <div class="forms">
                <input type="submit" value="Update">
            </div>
        </form>
    </div>

    <script>
        function toggleOther() {
            var select = document.getElementById("payment_method");
            var otherMethodDiv = document.getElementById("other_method");
            if (select.value === "other") {
                otherMethodDiv.style.display = "block";
            } else {
                otherMethodDiv.style.display = "none";
            }
        }
    </script>
</body>

</html>