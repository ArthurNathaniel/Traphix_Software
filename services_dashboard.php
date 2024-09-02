<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Services Dashboard</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        /* Your existing CSS styles */
    </style>
</head>

<body>
    <?php include 'sidebar.php' ?>
    <?php
    include 'db.php';

    // Get the selected year from the form, default to the current year if not set
    $selected_year = isset($_POST['year']) ? (int)$_POST['year'] : date('Y');

    // Initialize an array to store monthly data
    $monthly_data = array();

    // Query to retrieve each service and money gained from each service in the selected year
    $sql = "SELECT YEAR(receipt.date) AS year, MONTH(receipt.date) AS month, 
                services.service_name,
                SUM(receipt_services.price) AS money_gained
            FROM receipt
            LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
            LEFT JOIN services ON receipt_services.service_id = services.id
            WHERE YEAR(receipt.date) = ?
            GROUP BY YEAR(receipt.date), MONTH(receipt.date), services.id
            ORDER BY month DESC, money_gained DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_year);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store the result for the month
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $year = $row['year'];
            $month = $row['month'];
            $service_name = $row['service_name'];
            $money_gained = $row['money_gained'];

            // Add data to the monthly_data array, organized by service
            if (!isset($monthly_data[$service_name])) {
                $monthly_data[$service_name] = array();
            }
            $monthly_data[$service_name][] = array('year' => $year, 'month' => $month, 'money_gained' => $money_gained);
        }
    }

    // Sort services based on their total money gained
    $sorted_services = $monthly_data;
    uasort($sorted_services, function ($a, $b) {
        $total_a = array_sum(array_column($a, 'money_gained'));
        $total_b = array_sum(array_column($b, 'money_gained'));
        return $total_b - $total_a;
    });

    ?>

    <div class='manage_all'>
     <div class="forms">
     <h2>Services and Money Gained for Each Month</h2>
     </div>
        <!-- Year selection form -->
        <form method="POST" action="">
          <div class="forms">
          <label for="year">Select Year:</label>
            <select id="year" name="year" required>
                <?php
                for ($year = 2024; $year <= 2050; $year++) {
                    echo "<option value='$year'" . ($year == $selected_year ? " selected" : "") . ">$year</option>";
                }
                ?>
            </select>
          </div>
            <div class="forms">
            <input type="submit" value="Filter">
            </div>
        </form>
        
        <table>
            <tr><th>Service</th>
                <?php for ($month = 1; $month <= 12; $month++) {
                    $month_name = date('M', mktime(0, 0, 0, $month, 1));
                    echo "<th>$month_name</th>";
                } ?>
            </tr>
            <?php if (empty($monthly_data)): ?>
                <tr>
                    <td colspan="13">No data available for the selected year.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($sorted_services as $service_name => $data) {
                    echo "<tr>";
                    echo "<td>{$service_name}</td>";
                    for ($month = 1; $month <= 12; $month++) {
                        $money_gained = 0;
                        foreach ($data as $item) {
                            if ($item['month'] == $month) {
                                $money_gained = $item['money_gained'];
                                break;
                            }
                        }
                        echo "<td>" . number_format($money_gained, 2) . "</td>";
                    }
                    echo "</tr>";
                } ?>
            <?php endif; ?>
        </table>
    </div>

    <div class='manage_all'>
        <h2>Ranking</h2>
        <table>
            <tr><th>Rank</th><th>Service</th><th>Total Money Gained (GH₵)</th></tr>
            <?php if (empty($monthly_data)): ?>
                <tr>
                    <td colspan="3">No data available for the selected year.</td>
                </tr>
            <?php else: ?>
                <?php
                $rank = 1;
                foreach ($sorted_services as $service_name => $data) {
                    $total_money_gained = array_sum(array_column($data, 'money_gained'));
                    echo "<tr>";
                    echo "<td>{$rank}</td>";
                    echo "<td>{$service_name}</td>";
                    echo "<td>" . number_format($total_money_gained, 2) . "</td>";
                    echo "</tr>";
                    $rank++;
                }
                ?>
            <?php endif; ?>
        </table>
    </div>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>

</html>
