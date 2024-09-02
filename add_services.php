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
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="receipt_forms">
        <h1>Add a New Service</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="forms">
                <label for="service_name">Service Name:</label><br>
                <input type="text" id="service_name" name="service_name" required>
            </div>
            <div class="forms">
                <input type="submit" value="Submit">
            </div>
        </form>
    </div>

    <?php
    // Include database connection file
    include 'db.php';

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate service name
        $service_name = $_POST['service_name'];

        // Check if the service name already exists in the database
        $check_query = "SELECT * FROM services WHERE service_name = '$service_name'";
        $result = $conn->query($check_query);

        if ($result->num_rows > 0) {
            // Service name already exists
            echo "<script>alert('Service already exists.');</script>";
        } else {
            // Insert service name into database
            $sql = "INSERT INTO services (service_name) VALUES ('$service_name')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Service added successfully.');</script>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Close database connection
        $conn->close();
    }
    ?>

</body>

</html>