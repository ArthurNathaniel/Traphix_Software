<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
include 'db.php'; // Include the database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    // Prepare the SQL statement to check for existing expenditure
    $check_sql = $conn->prepare("SELECT * FROM expenditure WHERE name=? AND phone=? AND date=? AND description=? AND amount=?");
    $check_sql->bind_param("ssssd", $name, $phone, $date, $description, $amount);
    $check_sql->execute();
    $result = $check_sql->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('This expenditure already exists. Please enter a unique entry.');</script>";
    } else {
        try {
            // Prepare the SQL statement to insert data into the expenditure table
            $insert_sql = $conn->prepare("INSERT INTO expenditure (name, phone, date, description, amount) VALUES (?, ?, ?, ?, ?)");
            $insert_sql->bind_param("ssssd", $name, $phone, $date, $description, $amount);

            if ($insert_sql->execute()) {
                echo "<script>alert('Expenditure recorded successfully'); window.location.href = 'view_expenditure.php';</script>";
            } else {
                echo "Error: " . $insert_sql->error;
            }

            $insert_sql->close();
        } catch (mysqli_sql_exception $e) {
            // Check if the error is due to a unique constraint violation
            if ($e->getCode() == 1062) {
                echo "<script>alert('This expenditure already exists. Please enter a unique entry.');</script>";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    }

    $check_sql->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenditure Forms -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="create_invoice_all">

        <h1>Expenditure</h1>
        <br>
        <br>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="forms">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>

            <div class="forms">
                <label for="phone">Phone Number:</label>
                <input type="text" id="phone" name="phone">
            </div>

            <div class="forms">
                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>
            </div>

            <div class="forms">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description">
            </div>

            <div class="forms">
                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" step="0.01" required>
            </div>

            <div class="forms">
                <input type="submit" name="submit" value="Submit">
            </div>

        </form>
    </div>
    <script src="../js/sidebar.js"></script>
</body>

</html>