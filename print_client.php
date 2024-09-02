<?php
session_start();

// Redirect to login page if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'db.php';

// Check if client ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $client_id = $_GET['id'];

    // Query to retrieve client details
    $sql = "SELECT * FROM client_details WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch client details
        $client = $result->fetch_assoc();
    } else {
        echo "Client not found.";
        exit; // Stop further execution
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "Invalid client ID.";
    exit; // Stop further execution
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Client Details -  </title>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/client.css">
    <link rel="stylesheet" href="./css/invoice.css">
</head>

<body>
    <div class="navbar_top">
        <div class="nav_logo"></div>
        <div class="nav_title">
            <h1>CLIENT DETAILS</h1>
        </div>
    </div>
    <div class="print_client_all">
        <h1>Client Details</h1>
        <p><strong>Client Name:</strong> <?= $client['client_name'] ?></p>
        <p><strong>Phone Number:</strong> <?= $client['client_phone_number'] ?></p>
        <p><strong>WhatsApp Number:</strong> <?= $client['client_whatsapp'] ?></p>
        <p><strong>Email Address:</strong> <?= $client['client_email'] ?></p>
        <p><strong>Location:</strong> <?= $client['client_location'] ?></p>
        <br>
        <br>
        <div class="car_details">
            <h1>Car Details</h1>
            <p><strong>Car Model Name:</strong> <?= $client['car_model_name'] ?></p>
            <p><strong>Car Registration Number:</strong> <?= $client['car_registration_number'] ?></p>
            <p><strong>Car Color:</strong> <?= $client['car_color'] ?></p>
            <br>
            <br>
            <h1>Car Image</h1>
            <img src="<?php echo $client['car_image_path']; ?>" alt="Car Image">


        </div>
    </div>

    <script>
        // JavaScript to trigger the print dialog when the page loads
        window.onload = function() {
            window.print();
        };
    </script>

    <div class="last_details">
        <div class="nav_logo"></div>
        <div class="location">
            <h2>CONTACT US:</h2>
            <p><i class="fa-solid fa-location-dot"></i> Kronum-Kwapra</p>
            <p><i class="fa-solid fa-phone"></i> +233 24 956 7725</p>
        </div>
    </div>
</body>

</html>

<style>
    img {

        width: 100%;
        height: 150px;
        object-fit: contain;
    }
</style>