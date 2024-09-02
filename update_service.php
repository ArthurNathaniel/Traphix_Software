<?php
// Include database connection file
include 'db.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate service ID and name
    $service_id = $_POST['id'];
    $service_name = $_POST['service_name'];

    // Update service name in the database
    $sql = "UPDATE services SET service_name = '$service_name' WHERE id = $service_id";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Service updated successfully.');</script>";
        // Redirect back to manage_services.php or any other appropriate page
        header("Location: manage_services.php");
        exit();
    } else {
        echo "Error updating service: " . $conn->error;
    }

    // Close database connection
    $conn->close();
} else {
    // If form is not submitted, redirect back to the manage_services.php page or show an error message
    header("Location: manage_services.php");
    exit();
}
?>
