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
    <title>Yearly Services Dashboard</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <?php
    include 'db.php';

    // Get the selected year from the form, default to the current year if not set
    $selected_year = isset($_POST['year']) ? (int)$_POST['year'] : date('Y');

    // Initialize an array to store yearly data
    $yearly_data = array();

    // Query to retrieve each service and money gained from each service in the selected year
    $sql = "SELECT YEAR(receipt.date) AS year, 
                services.service_name,
                SUM(receipt_services.price) AS money_gained
            FROM receipt
            LEFT JOIN receipt_services ON receipt.id = receipt_services.receipt_id
            LEFT JOIN services ON receipt_services.service_id = services.id
            WHERE YEAR(receipt.date) = ?
            GROUP BY YEAR(receipt.date), services.id
            ORDER BY money_gained DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $selected_year);
    $stmt->execute();
    $result = $stmt->get_result();

    // Store the result for each year
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $year = $row['year'];
            $service_name = $row['service_name'];
            $money_gained = $row['money_gained'];

            // Add data to the yearly_data array, organized by service
            if (!isset($yearly_data[$year])) {
                $yearly_data[$year] = array();
            }
            $yearly_data[$year][$service_name] = $money_gained;
        }
    }

    ?>

    <div class='manage_all'>
   <div class="forms">
   <h2>Services and Money Gained for Each Year</h2>
   </div>
        <!-- Year selection form -->
        <form method="POST" action="">
          <div class="forms">
          <label for="year">Select Year:</label>
            <select id="year" name="year" required>
                <?php
                for ($year = 2024; $year <= 2054; $year++) {
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
                <th><?php echo $selected_year; ?></th>
            </tr>
            <?php if (empty($yearly_data)): ?>
                <tr>
                    <td colspan="2">No data available for the selected year.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($yearly_data[$selected_year] as $service_name => $money_gained) {
                    echo "<tr>";
                    echo "<td>{$service_name}</td>";
                    echo "<td>" . number_format($money_gained, 2) . "</td>";
                    echo "</tr>";
                } ?>
            <?php endif; ?>
        </table>
    </div>

    <div class='manage_all'>
        <h2>Ranking</h2>
        <table>
            <tr><th>Year</th><th>Service</th><th>Total Money Gained (GH₵)</th></tr>
            <?php if (empty($yearly_data)): ?>
                <tr>
                    <td colspan="3">No data available for the selected year.</td>
                </tr>
            <?php else: ?>
                <?php
                foreach ($yearly_data[$selected_year] as $service_name => $money_gained) {
                    echo "<tr>";
                    echo "<td>{$selected_year}</td>";
                    echo "<td>{$service_name}</td>";
                    echo "<td>" . number_format($money_gained, 2) . "</td>";
                    echo "</tr>";
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
