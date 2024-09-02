<?php
// Include database connection file
include 'db.php';

// Check if service ID is provided in the URL
if (isset($_GET['id'])) {
    $service_id = $_GET['id'];

    // Prepare a delete statement
    $sql = "DELETE FROM services WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $service_id);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to manage_services.php after successful deletion
            header("location: manage_services.php");
            exit();
        } else {
            echo "Error deleting service.";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing delete statement.";
    }
} else {
    echo "Service ID not provided.";
}

// Close database connection
$conn->close();
