<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include 'db.php';

// Check if a date is provided
if (isset($_GET['date'])) {
    $date = $_GET['date'];

    // Initialize an empty array to store service revenues
    $service_revenues = array();

    // Query to fetch all services
    $sql_services = "SELECT * FROM services";
    $result_services = $conn->query($sql_services);

    // Initialize service revenues to 0 for all services
    while ($row = $result_services->fetch_assoc()) {
        $service_revenues[$row['name']] = 0;
    }

    // Query to fetch receipt for the provided date
    $sql_receipt = "SELECT services, amount FROM receipt WHERE DATE(date) = '$date'";
    $result_receipt = $conn->query($sql_receipt);

    // Calculate revenue for each service
    while ($row = $result_receipt->fetch_assoc()) {
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
} else {
    // If no date is provided, set the service revenues to an empty array
    $service_revenues = array();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Revenue by Service</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="yearly_revenue">
        <div class="title">
            <h2>Daily Revenue by Service</h2>
        </div>
        <br>
        
        <form action="" method="get">
            <div class="forms">
                <label for="date">Select Date:</label>
                <input type="date" id="date" name="date" value="<?php echo isset($date) ? $date : ''; ?>" required>
            </div>
            <div class="forms">
                <button type="submit">Calculate Revenue</button>
            </div>
        </form>
        <div class="result">
            <?php if (isset($date)) : ?>
                <h3>Revenue for <?php echo $date; ?>:</h3>
                <?php if (!empty($service_revenues)) : ?>
                    <table>
                        <tr>
                            <th>Service</th>
                            <th>Revenue</th>
                        </tr>
                        <?php foreach ($service_revenues as $service => $revenue) : ?>
                            <tr>
                                <td><?php echo $service; ?></td>
                                <td>$<?php echo number_format($revenue, 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php else : ?>
                    <p>No revenue data available for the selected date.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>