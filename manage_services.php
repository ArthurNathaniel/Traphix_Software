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
    <title>Manage Services</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>

    <div class="yearly_revenue">
        <h1>Manage Services</h1>
        <table>
            <tr>
                <!-- <th>Service ID</th> -->
                <th>Service Name</th>
                <!-- <th>Action</th> -->
            </tr>
            <?php
            // Include database connection file
            include 'db.php';

            // Retrieve services data from the database
            $sql = "SELECT * FROM services";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    // echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["service_name"] . "</td>";
                    // echo "<td><a href='edit_service.php?id=" . $row["id"] . "'>Edit</a> | <a href='delete_service.php?id=" . $row["id"] . "'>Delete</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='3'>No services found</td></tr>";
            }
            $conn->close();
            ?>
        </table>
    </div>
</body>

</html>