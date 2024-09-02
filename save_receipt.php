<?php
session_start();
include 'db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $client_name = $_POST['client_name'];
    $payment_method = $_POST['payment_method'];
    $other_method = isset($_POST['other_method']) ? $_POST['other_method'] : "";
    $payment_type = $_POST['payment_type'];
    $date = $_POST['date'];
    // Initialize total amount as integer
    $total_amount = 0;
    // Calculate the total amount based on selected services and their prices
    if (isset($_POST['price'])) {
        foreach ($_POST['price'] as $price) {
            // Ensure price is converted to integer before addition
            $total_amount += (int)$price;
        }
    }
    // Insert receipt data into the database
    $sql_receipt = "INSERT INTO receipt (client_name, payment_method, other_method, payment_type, amount, date) 
                    VALUES ('$client_name', '$payment_method', '$other_method', '$payment_type', '$total_amount', '$date')";
    // Execute the query
    if ($conn->query($sql_receipt) === TRUE) {
        // Get the ID of the inserted receipt
        $receipt_id = $conn->insert_id;
        // Loop through selected services and their prices
        foreach ($_POST['service'] as $service) {
            $service_price = $_POST['price'][$service];
            // Insert service and price into the receipt_services table
            $sql_service = "SELECT id FROM services WHERE service_name = '$service'";
            $result_service = $conn->query($sql_service);
            if ($result_service->num_rows > 0) {
                $row = $result_service->fetch_assoc();
                $service_id = $row['id'];
                $sql_receipt_services = "INSERT INTO receipt_services (receipt_id, service_id, price) 
                                         VALUES ('$receipt_id', '$service_id', '$service_price')";
                $conn->query($sql_receipt_services);
            } else {
                echo "Error: Service '$service' not found.";
            }
        }
        echo "<script>alert('Receipt created successfully'); window.location.href = 'receipt_form.php';</script>";
    } else {
        echo "Error: " . $sql_receipt . "<br>" . $conn->error;
    }
    // Close database connection
    $conn->close();
}
