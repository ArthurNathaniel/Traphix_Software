<?php


// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $billed_to = $_POST['billed_to'];
    $contact_number = $_POST['contact_number'];
    $services = $_POST['service'];
    $days = $_POST['day'];
    $prices = $_POST['price'];
    $amounts = $_POST['amount'];
    $subtotal = $_POST['subtotal'];
    $vat_tax = $_POST['vat_tax'];
    $total = $_POST['total'];

    // Validate and sanitize the data (perform validation/sanitization as needed)

    // Include your database connection file
    include 'db.php';

    // Prepare and bind the SQL statement
    $sql = "INSERT INTO invoices (billed_to, contact_number, subtotal, vat_tax, total) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssddd", $billed_to, $contact_number, $subtotal, $vat_tax, $total);

    // Execute the statement
    if ($stmt->execute()) {
        // Get the ID of the last inserted record
        $invoice_id = $conn->insert_id;

        // Insert individual service details into another table (assuming your table structure)
        $services_sql = "INSERT INTO invoice_services (invoice_id, service, day, price, amount) VALUES (?, ?, ?, ?, ?)";
        $stmt_services = $conn->prepare($services_sql);

        // Loop through each service and insert into the database
        for ($i = 0; $i < count($services); $i++) {
            $stmt_services->bind_param("isddd", $invoice_id, $services[$i], $days[$i], $prices[$i], $amounts[$i]);
            $stmt_services->execute();
        }

        // Close the statement
        $stmt_services->close();

        // Redirect to a success page or display a success message
        // header("Location: invoice_success.php");
        echo "<script>alert('Invoice created successfully'); window.location.href = 'create_invoice.php';</script>";
        exit();
    } else {
        // Handle errors
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If the form wasn't submitted via POST method, handle accordingly
    echo "Form submission method not recognized";
}
