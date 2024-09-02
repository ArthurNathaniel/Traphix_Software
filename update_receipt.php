

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include database connection file
include 'db.php';

// Retrieve form data
$receipt_id = $_POST['receipt_id'];
$client_name = $_POST['client_name'];
$payment_method = $_POST['payment_method'];
$other_method = isset($_POST['other_method']) ? $_POST['other_method'] : "";
$payment_type = $_POST['payment_type'];
$total_amount = $_POST['total_amount'];

// Format the date using PHP's date() function
$date = date('Y-m-d', strtotime($_POST['date']));
$original_date = $_POST['original_date']; // Retrieve the original date value

// Check if the date has been changed
if ($date != $original_date) {
    // If the date has changed, update it in the database
    $sql_update_date = "UPDATE receipt SET date = ? WHERE id = ?";
    $stmt_update_date = $conn->prepare($sql_update_date);
    $stmt_update_date->bind_param("si", $date, $receipt_id);
    if (!$stmt_update_date->execute()) {
        echo "Error updating date: " . $conn->error;
        // Handle error here
    }
    $stmt_update_date->close();
}

// Update other receipt data in the database
$sql_update_receipt = "UPDATE receipt 
                        SET client_name = ?, payment_method = ?, other_method = ?, payment_type = ?, amount = ?
                        WHERE id = ?";
$stmt = $conn->prepare($sql_update_receipt);
$stmt->bind_param("ssssdi", $client_name, $payment_method, $other_method, $payment_type, $total_amount, $receipt_id);

if ($stmt->execute()) {
    // Update services and their prices
    if (isset($_POST['service']) && isset($_POST['price'])) {
        $services = $_POST['service'];
        $prices = $_POST['price'];

        // Delete existing services associated with the receipt
        $sql_delete_services = "DELETE FROM receipt_services WHERE receipt_id = ?";
        $stmt_delete = $conn->prepare($sql_delete_services);
        $stmt_delete->bind_param("i", $receipt_id);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Insert updated services into the receipt_services table
        foreach ($services as $service) {
            $price = $prices[$service];
            // Retrieve service ID
            $sql_service_id = "SELECT id FROM services WHERE service_name = ?";
            $stmt_service_id = $conn->prepare($sql_service_id);
            $stmt_service_id->bind_param("s", $service);
            $stmt_service_id->execute();
            $result_service_id = $stmt_service_id->get_result();
            $row_service_id = $result_service_id->fetch_assoc();
            $service_id = $row_service_id['id'];
            $stmt_service_id->close();

            // Insert service and price into the receipt_services table
            $sql_insert_service = "INSERT INTO receipt_services (receipt_id, service_id, price) 
                                    VALUES (?, ?, ?)";
            $stmt_insert_service = $conn->prepare($sql_insert_service);
            $stmt_insert_service->bind_param("iid", $receipt_id, $service_id, $price);
            $stmt_insert_service->execute();
            $stmt_insert_service->close();
        }
    }

    // Redirect to view_receipts.php after successful update
    header("Location: view_receipts.php");
    exit();
} else {
    echo "Error updating receipt: " . $conn->error;
    // Handle error here
}

// Close prepared statement and database connection
$stmt->close();
$conn->close();



?>
