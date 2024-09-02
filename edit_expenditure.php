<?php
include 'db.php'; // Include the database connection file
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
// Check if expenditure ID is provided in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $expenditure_id = $_GET['id'];

    // Fetch the expenditure data from the database based on the provided ID
    $sql = "SELECT * FROM expenditure WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $expenditure_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $expenditure = $result->fetch_assoc();

        // Process form submission
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Retrieve form data
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $date = $_POST['date'];
            $description = $_POST['description'];
            $amount = $_POST['amount'];

            // Update the expenditure in the database
            $update_sql = "UPDATE expenditure SET name=?, phone=?, date=?, description=?, amount=? WHERE id=?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("ssssdi", $name, $phone, $date, $description, $amount, $expenditure_id);

            if ($stmt->execute()) {
                // Redirect to view expenditure page after successful update
                header("Location: view_expenditure.php");
                exit();
            } else {
                echo "Error updating record: " . $stmt->error;
            }

            $stmt->close();
        }
    } else {
        echo "Expenditure not found.";
    }
} else {
    echo "Expenditure ID not provided.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expenditure -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="create_invoice_all">
        <h1>Edit Expenditure</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $expenditure_id; ?>" method="post">
            <div class="forms">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo $expenditure['name']; ?>" required>
            </div>

            <div class="forms">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone" value="<?php echo $expenditure['phone']; ?>">
            </div>

            <div class="forms">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" value="<?php echo $expenditure['date']; ?>" required>
            </div>

            <div class="forms">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description" value="<?php echo $expenditure['description']; ?>">
            </div>

            <div class="forms">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" value="<?php echo $expenditure['amount']; ?>" required>
            </div>

            <div class="forms">
                <input type="submit" name="submit" value="Update">
            </div>
        </form>
    </div>
    <script src="../js/sidebar.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>