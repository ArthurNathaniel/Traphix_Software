<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Proceed with displaying the page content since the user is logged in
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Form - The Regas Autoshop</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="receipt_forms">
        <h2>Receipt Form</h2>
        <form action="save_receipt.php" method="post" name="receipt_form">
            <div class="forms">
                <label>Client Name:</label>
                <input type="text" name="client_name" required>
            </div>
            <div class="forms">
                <label>Payment Method:</label>
                <select name="payment_method" id="payment_method" onchange="toggleOther()">
                    <option value="cash">Cash</option>
                    <option value="momo">Momo</option>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="other">Other</option>
                </select>
                <div id="other_method" style="display:none;">
                    Other Method: <input type="text" name="other_method"><br>
                </div>
            </div>
            <div class="forms">
                <label>Full or Partial Payment:</label>
                <select name="payment_type" required>
                    <option value="full">Full</option>
                    <option value="partial">Partial</option>
                </select>
            </div>

            <div class="forms">
                <label>Select Service:</label><br>
                <?php
                // Include database connection file
                include 'db.php';

                // Fetch services from database
                $sql = "SELECT service_name FROM services";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<input type='checkbox' name='service[]' value='" . $row["service_name"] . "'>" . $row["service_name"] . "<br>";
                        echo "<input type='number' name='price[" . $row["service_name"] . "]' placeholder='Price for " . $row["service_name"] . "'><br>";
                    }
                }
                ?>
            </div>

            <div class="forms">
                <label>Amount (in figure):</label>
                <!-- <input type="number" name="amount" required> -->
                <input type="number" name="total_amount" value="<?php echo $total_amount; ?>">

            </div>
            <div class="forms">
                <label>Date:</label>
                <input type="date" name="date" required>
            </div>

            <div class="forms">
                <input type="submit" value="Submit">
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