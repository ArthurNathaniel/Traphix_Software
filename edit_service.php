<!DOCTYPE html>
<html>

<head>
    <title>Edit Service</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>

    <div class="manage_all">
        <h1>Edit Service</h1>

        <?php
        // Include database connection file
        include 'db.php';

        // Check if service ID is provided in the URL
        if (isset($_GET['id'])) {
            $service_id = $_GET['id'];

            // Retrieve service details from the database
            $sql = "SELECT * FROM services WHERE id = $service_id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $service_name = $row['service_name'];
        ?>

                <form action="update_service.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $service_id; ?>">
                    <div class="forms">
                        <label for="service_name">Service Name:</label><br>
                        <input type="text" id="service_name" name="service_name" value="<?php echo $service_name; ?>" required>
                    </div>
                    <div class="forms">
                        <input type="submit" value="Update">
                    </div>
                </form>

        <?php
            } else {
                echo "Service not found.";
            }
        } else {
            echo "Service ID not provided.";
        }

        // Close database connection
        $conn->close();
        ?>
    </div>
</body>

</html>