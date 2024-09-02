<?php

session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}


include 'db.php';

// Check if ID parameter is set and valid
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Delete client details by ID
    $sql = "DELETE FROM client_details WHERE id = $id";

    if ($conn->query($sql) === TRUE) {

        echo "<script>alert('Record deleted successfully');window.location.href = 'client_details.php';</script>";

    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid ID";
}

// Close connection
$conn->close();
