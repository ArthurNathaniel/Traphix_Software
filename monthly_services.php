<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db.php';

// Initialize an empty array to store service revenues
$service_revenues = array();

// Query to fetch all services
$sql_services = "SELECT * FROM services";
$result_services = $conn->query($sql_services);

// Initialize service revenues to 0 for all services for each month
while ($row = $result_services->fetch_assoc()) {
    $service_revenues[$row['name']] = array(
        'January' => 0,
        'February' => 0,
        'March' => 0,
        'April' => 0,
        'May' => 0,
        'June' => 0,
        'July' => 0,
        'August' => 0,
        'September' => 0,
        'October' => 0,
        'November' => 0,
        'December' => 0
    );
}

// Query to fetch receipts grouped by month and service
$sql_receipts = "SELECT services, amount, DATE_FORMAT(date, '%Y-%m') AS month_year FROM receipts";
$result_receipts = $conn->query($sql_receipts);

// Calculate revenue for each service for each month
while ($row = $result_receipts->fetch_assoc()) {
    $services = unserialize($row['services']);
    $month_year = $row['month_year'];
    $month = date('F', strtotime($month_year . '-01'));
    foreach ($services as $service_id => $price) {
        // Query to fetch service name based on ID
        $sql_service_name = "SELECT name FROM services WHERE id = $service_id";
        $result_service_name = $conn->query($sql_service_name);
        if ($result_service_name->num_rows > 0) {
            $service_name = $result_service_name->fetch_assoc()['name'];
            // Increment revenue for the service for the respective month
            $service_revenues[$service_name][$month] += $price;
        }
    }
}

// Function to format the revenue amount
function formatRevenue($revenue)
{
    return '$' . number_format($revenue, 2);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue by Service -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="monthly_revenue">
        <h2>Monthly Revenue by Service</h2>
        <table>
            <thead>
                <tr>
                    <th>Service</th>
                    <th>January</th>
                    <th>February</th>
                    <th>March</th>
                    <th>April</th>
                    <th>May</th>
                    <th>June</th>
                    <th>July</th>
                    <th>August</th>
                    <th>September</th>
                    <th>October</th>
                    <th>November</th>
                    <th>December</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($service_revenues as $service => $revenues) : ?>
                    <tr>
                        <td><?php echo $service; ?></td>
                        <?php foreach ($revenues as $revenue) : ?>
                            <td><?php echo formatRevenue($revenue); ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>