<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db.php';

// Initialize an empty array to store yearly revenues
$yearly_revenues = array();

// Loop through the years from 2024 to 2034
for ($year = 2024; $year <= 2034; $year++) {
    // Query to fetch total revenue for each year
    $sql_yearly_revenue = "SELECT SUM(amount) AS total_revenue FROM receipt WHERE YEAR(date) = $year";
    $result_yearly_revenue = $conn->query($sql_yearly_revenue);
    $row = $result_yearly_revenue->fetch_assoc();
    $yearly_revenues[$year] = $row['total_revenue'] ? $row['total_revenue'] : 0;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearly Revenue -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="yearly_revenue">
        <h2>Yearly Revenue from 2024 to 2034</h2>
        <table>
            <thead>
                <tr>
                    <th>Year</th>
                    <th>Total Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($yearly_revenues as $year => $revenue) : ?>
                    <tr>
                        <td><?php echo $year; ?></td>
                        <td><?php echo '$' . number_format($revenue, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>