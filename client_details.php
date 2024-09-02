<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
// Function to fetch all client details
function fetchAllClientDetails($conn)
{
    $sql = "SELECT * FROM client_details";
    $result = $conn->query($sql);
    $clients = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
    }

    return $clients;
}

// Fetch all client details
$clients = fetchAllClientDetails($conn);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Client Details </title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/client.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="admin_all">
        <h2>Client Details</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Client Name</th>
                    <th>Phone Number</th>
                    <th>WhatsApp Number</th>
                    <th>Email Address</th>
                    <th>Location</th>
                    <th>Car Model</th>
                    <th>Registration Number</th>
                    <th>Car Color</th>
                    <th>Car Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client) { ?>
                    <tr>
                        <td><?php echo $client['id']; ?></td>
                        <td><?php echo $client['client_name']; ?></td>
                        <td><?php echo $client['client_phone_number']; ?></td>
                        <td><?php echo $client['client_whatsapp']; ?></td>
                        <td><?php echo $client['client_email']; ?></td>
                        <td><?php echo $client['client_location']; ?></td>
                        <td><?php echo $client['car_model_name']; ?></td>
                        <td><?php echo $client['car_registration_number']; ?></td>
                        <td><?php echo $client['car_color']; ?></td>
                        <td>
                            <img src="<?php echo $client['car_image_path']; ?>" alt="Car Image" width="100">
                        </td>
                        <td>
                            <a href="edit.php?id=<?php echo $client['id']; ?>">Edit</a>
                            <a href="delete.php?id=<?php echo $client['id']; ?>" class="ml-2" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
                            <a href="print_client.php?id=<?php echo $client['id']; ?>" class="ml-2">Print</a> <!-- New line for Print option -->
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>



</body>

</html>