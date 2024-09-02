<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db.php';

// Get the current week's Monday
$start_date = date('Y-m-d', strtotime('monday this week'));

// Calculate the end date (Sunday)
$end_date = date('Y-m-d', strtotime('sunday this week'));

// Initialize an empty array to store service revenues
$service_revenues = array();

// Query to fetch all services
$sql_services = "SELECT * FROM services";
$result_services = $conn->query($sql_services);

// Initialize service revenues to 0 for all services
while ($row = $result_services->fetch_assoc()) {
    $service_revenues[$row['name']] = 0;
}

// Query to fetch receipts for the current week
$sql_receipts = "SELECT services, amount, DATE(date) AS date FROM receipts WHERE DATE(date) >= '$start_date' AND DATE(date) <= '$end_date'";
$result_receipts = $conn->query($sql_receipts);

// Calculate revenue for each service
while ($row = $result_receipts->fetch_assoc()) {
    $services = unserialize($row['services']);
    foreach ($services as $service_id => $price) {
        // Query to fetch service name based on ID
        $sql_service_name = "SELECT name FROM services WHERE id = $service_id";
        $result_service_name = $conn->query($sql_service_name);
        if ($result_service_name->num_rows > 0) {
            $service_name = $result_service_name->fetch_assoc()['name'];
            // Increment revenue for the service
            $service_revenues[$service_name] += $price;
        }
    }
}

// Function to format the date
function formatDate($date)
{
    return date('D, d M Y', strtotime($date));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weekly Revenue by Service -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="yearly_revenue">
        <h2>Weekly Revenue by Service</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <?php foreach ($service_revenues as $service => $revenue) : ?>
                        <th><?php echo $service; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo formatDate($start_date) . ' to ' . formatDate($end_date); ?></td>
                    <?php foreach ($service_revenues as $revenue) : ?>
                        <td>$<?php echo number_format($revenue, 2); ?></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>