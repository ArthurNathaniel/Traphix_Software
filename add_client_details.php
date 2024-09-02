<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';

// If the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escape user inputs for security
    $client_name = $conn->real_escape_string($_POST['client_name']);
    $client_phone_number = $conn->real_escape_string($_POST['client_phone_number']);
    $client_whatsapp = $conn->real_escape_string($_POST['client_whatsapp']);
    $client_email = $conn->real_escape_string($_POST['client_email']);
    $client_location = $conn->real_escape_string($_POST['client_location']);
    $car_model_name = $conn->real_escape_string($_POST['car_model_name']);
    $car_registration_number = $conn->real_escape_string($_POST['car_registration_number']);
    $car_color = $conn->real_escape_string($_POST['car_color']);

    // Check if file was uploaded
    if (isset($_FILES["car_image"]) && $_FILES["car_image"]["error"] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["car_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["car_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["car_image"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($_FILES["car_image"]["tmp_name"], $target_file)) {
                echo "The file " . basename($_FILES["car_image"]["name"]) . " has been uploaded.";
                // Insert data into database
                $sql = "INSERT INTO client_details (client_name, client_phone_number, client_whatsapp, client_email, client_location, car_model_name, car_registration_number, car_color, car_image_path)
                VALUES ('$client_name', '$client_phone_number', '$client_whatsapp', '$client_email', '$client_location', '$car_model_name', '$car_registration_number', '$car_color', '$target_file')";

                if ($conn->query($sql) === TRUE) {
                    echo "<script>alert('New record created successfully'); window.location.href = 'add_client_details.php';</script>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    } else {
        echo "No file uploaded or an error occurred while uploading the file.";
    }

    // Close connection
    $conn->close();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Details</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/client.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="admin_all">
        <div class="clients_all_title">
            <h1>Client Details</h1>
        </div>
        <form id="clientForm" action="" method="post" enctype="multipart/form-data">

            <div class="clients_grid">
                <div class="clients_forms">
                    <label>Client Name:</label>
                    <input type="text" placeholder="Enter your client name" name="client_name" required>
                </div>
                <div class="clients_forms">
                    <label>Client Phone Number:</label>
                    <input type="number" placeholder="Enter your client phone number" name="client_phone_number" required>
                </div>
                <div class="clients_forms">
                    <label>Client WhatsApp Number:</label>
                    <input type="number" placeholder="Enter your client WhatsApp" name="client_whatsapp" required>
                </div>
                <div class="clients_forms">
                    <label>Client Email Address:</label>
                    <input type="email" placeholder="Enter your client email" name="client_email" required>
                </div>
                <div class="clients_forms">
                    <label>Client Location:</label>
                    <input type="text" placeholder="Enter your client location" name="client_location" required>
                </div>
            </div>
            <div class="clients_all_title">
                <h1>Client's Car Details</h1>
            </div>
            <div class="clients_grid">
                <div class="clients_forms">
                    <label>Car Model Name:</label>
                    <input type="text" placeholder="Enter car model name" name="car_model_name" required>
                </div>
                <div class="clients_forms">
                    <label>Car Registration Number:</label>
                    <input type="text" placeholder="Enter car registration number" name="car_registration_number" required>
                </div>
                <div class="clients_forms">
                    <label>Color of the Car:</label>
                    <input type="text" placeholder="Enter car color" name="car_color" required>
                </div>
                <div class="clients_forms">
                    <label>Upload Image of the Car:</label>
                    <input type="file" name="car_image" accept="image/*" required>
                </div>
            </div>

            <div class="clients_grid">
                <div class="clients_forms">
                    <button id="submitButton" type="submit">Submit</button>
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