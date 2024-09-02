<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $position = $_POST['position'];
    $salary_per_day = $_POST['salary_per_day'];

    // Check if a worker with the same name and position already exists
    $check_sql = "SELECT * FROM workers WHERE name = ? AND position = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $name, $position);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows == 0) {
        // If no duplicate is found, insert the new worker
        $sql = "INSERT INTO workers (name, gender, position, salary_per_day) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssd", $name, $gender, $position, $salary_per_day);
        $stmt->execute();
        $stmt->close();

        echo "Worker added successfully!";
    } else {
        echo "A worker with the same name and position already exists.";
    }

    $check_stmt->close();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Worker</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>
<body>
    <?php include 'sidebar.php' ?>
    <div class="receipt_forms">
        <div class="forms">
            <h1>Add Worker</h1>
        </div>
<form method="POST">
 <div class="forms">
 <label for="name">Name:</label>
 <input type="text" name="name" required>
 </div>

   <div class="forms">
   <label for="gender">Gender:</label>
    <select name="gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
    </select>
   </div>

   <div class="forms">
   <label for="position">Position:</label>
   <input type="text" name="position" required>
   </div>

    <div class="forms">
    <label for="salary_per_day">Salary per Day (GHS):</label>
    <input type="number" step="0.01" name="salary_per_day" value="100.00" required>
    </div>

<div class="forms">
<input type="submit" value="Add Worker">
</div>
</form>
</div>
</body>

</html>