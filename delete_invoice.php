<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Proceed with displaying the page content since the user is logged in

// Establish a database connection
include 'db.php';

// Check if invoice ID is provided
if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Delete related rows in the invoice_services table
    $delete_services_sql = "DELETE FROM invoice_services WHERE invoice_id = ?";
    if ($stmt = $conn->prepare($delete_services_sql)) {
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error preparing the delete statement for invoice services: " . $conn->error;
        
        exit();
    }

    // Delete the invoice
    $delete_invoice_sql = "DELETE FROM invoices WHERE id = ?";
    if ($stmt = $conn->prepare($delete_invoice_sql)) {
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();

        // Redirect to the view invoices page after deletion
        header("Location: view_invoices.php");
        exit();
    } else {
        echo "Error preparing the delete statement for invoices: " . $conn->error;
        exit();
    }
} else {
    echo "Invoice ID not provided.";
    exit();
}

// Close the database connection
$conn->close();
