<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php'; // Include your database connection file

// Retrieve data from the database
$sql = "SELECT * FROM client_details";
$result = mysqli_query($conn, $sql);

// Check if there are any records
if (mysqli_num_rows($result) > 0) {
    // Display data in a table
    echo "<h2>Client Details</h2>";
    echo "<table border='1'>";
    echo "<tr><th>Recipient Name</th><th>Date</th><th>Driving License Number</th><th>Contact Number</th><th>Durations (Days)</th><th>Ghana Card ID</th><th>Destination</th><th>Car Registration</th><th>Amount</th><th>Ghana Card Front</th><th>Ghana Card Back</th><th>Actions</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['recipient_name'] . "</td>";
        echo "<td>" . $row['date'] . "</td>";
        echo "<td>" . $row['driving_license_number'] . "</td>";
        echo "<td>" . $row['contact_number'] . "</td>";
        echo "<td>" . $row['duration'] . "</td>";
        echo "<td>" . $row['ghana_card_id'] . "</td>";
        echo "<td>" . $row['destination'] . "</td>";
        echo "<td>" . $row['car_registration'] . "</td>";
        echo "<td>" . $row['amount'] . "</td>";
        echo "<td><img src='uploads/" . $row['client_ghana_card_front'] . "' alt='Ghana Card Front' width='100'></td>";
        echo "<td><img src='uploads/" . $row['client_ghana_card_back'] . "' alt='Ghana Card Back' width='100'></td>";
        echo "<td><a href='edit_data.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_data.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this record?\")'>Delete</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No records found";
}

// Close connection
mysqli_close($conn);
