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

    // Fetch client details by ID
    $sql = "SELECT * FROM client_details WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $client = $result->fetch_assoc();
    } else {
        echo "No client found with ID: $id";
        exit;
    }
} else {
    echo "Invalid ID";
    exit;
}

// Update client details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_name = $conn->real_escape_string($_POST['client_name']);
    $client_phone_number = $conn->real_escape_string($_POST['client_phone_number']);
    $client_whatsapp = $conn->real_escape_string($_POST['client_whatsapp']);
    $client_email = $conn->real_escape_string($_POST['client_email']);
    $client_location = $conn->real_escape_string($_POST['client_location']);
    $car_model_name = $conn->real_escape_string($_POST['car_model_name']);
    $car_registration_number = $conn->real_escape_string($_POST['car_registration_number']);
    $car_color = $conn->real_escape_string($_POST['car_color']);

    // Check if a file is uploaded
    if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] === UPLOAD_ERR_OK) {
        $car_image_temp = $_FILES['car_image']['tmp_name'];
        $car_image_name = $_FILES['car_image']['name'];

        // Move the uploaded file to a permanent location
        move_uploaded_file($car_image_temp, "uploads/" . $car_image_name);

        // Update the database with the file path
        $car_image_path = "uploads/" . $car_image_name;

        // Add the car image path to the SQL query
        $sql = "UPDATE client_details SET 
                client_name = '$client_name', 
                client_phone_number = '$client_phone_number', 
                client_whatsapp = '$client_whatsapp', 
                client_email = '$client_email', 
                client_location = '$client_location',
                car_model_name = '$car_model_name',
                car_registration_number = '$car_registration_number',
                car_color = '$car_color',
                car_image = '$car_image_path'
                WHERE id = $id";
    } else {
        // Update the database without the file path
        $sql = "UPDATE client_details SET 
                client_name = '$client_name', 
                client_phone_number = '$client_phone_number', 
                client_whatsapp = '$client_whatsapp', 
                client_email = '$client_email', 
                client_location = '$client_location',
                car_model_name = '$car_model_name',
                car_registration_number = '$car_registration_number',
                car_color = '$car_color'
                WHERE id = $id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Client details updated successfully'); window.location.href = 'client_details.php';</script>";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client Details -  </title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/client.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="admin_all">
        <h2>Edit Client Details</h2>
        <form id="clientForm" action="" method="post" enctype="multipart/form-data">

            <div class="clients_grid">
                <div class="clients_forms">
                    <label>Client Name:</label>
                    <input type="text" placeholder="Enter your client name" name="client_name" value="<?php echo $client['client_name']; ?>" required>
                </div>
                <div class="clients_forms">
                    <label>Client Phone Number:</label>
                    <input type="number" placeholder="Enter your client phone number" name="client_phone_number" value="<?php echo $client['client_phone_number']; ?>" required>
                </div>
                <div class="clients_forms">
                    <label>Client WhatsApp Number:</label>
                    <input type="number" placeholder="Enter your client WhatsApp" name="client_whatsapp" value="<?php echo $client['client_whatsapp']; ?>" required>
                </div>
                <div class="clients_forms">
                    <label>Client Email Address:</label>
                    <input type="email" placeholder="Enter your client email" name="client_email" value="<?php echo $client['client_email']; ?>" required>
                </div>
                <div class="clients_forms">
                    <label>Client Location:</label>
                    <input type="text" placeholder="Enter your client location" name="client_location" value="<?php echo $client['client_location']; ?>" required>
                </div>
            </div>

            <div class="clients_all_title">
                <h1>Client's Car Details</h1>
            </div>
            <div class="clients_grid">
                <div class="clients_forms">
                    <label>Car Model Name:</label>
                    <input type="text" placeholder="Enter car model name" name="car_model_name" value="<?php echo $client['car_model_name']; ?>" required>
                </div>
                <div class="clients_forms">
                    <label>Car Registration Number:</label>
                    <input type="text" placeholder="Enter car registration number" name="car_registration_number" value="<?php echo $client['car_registration_number']; ?>" required>
                </div>
                <div class="clients_forms">
                    <label>Color of the Car:</label>
                    <input type="text" placeholder="Enter car color" name="car_color" value="<?php echo $client['car_color']; ?>" required>
                </div>
                <div class="clients_forms">
                    <label>Car Image:</label>
                    <input type="file" name="car_image" accept="image/*">
                </div>
            </div>

            <div class="clients_grid">
                <div class="clients_forms">
                    <button id="submitButton" type="submit">Update</button>
                </div>
            </div>
        </form>
    </div>
    <script src="../js/sidebar.js"></script>
    <script>
        document.getElementById("clientForm").addEventListener("submit", function() {
            document.getElementById("submitButton").innerText = "Please wait...";
        });
    </script>
</body>

</html>