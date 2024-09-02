<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$workers = $conn->query("SELECT id, name FROM workers");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $worker_id = $_POST['worker_id'];

    $sql = "
        SELECT 
            workers.name,
            MONTH(date) as month, 
            date,
            status,
            salary_per_day
        FROM attendance 
        JOIN workers ON attendance.worker_id = workers.id";
    
    if ($worker_id !== 'all') {
        $sql .= " WHERE worker_id = ?";
    }

    $sql .= " ORDER BY workers.name, MONTH(date), date";

    $stmt = $conn->prepare($sql);
    if ($worker_id !== 'all') {
        $stmt->bind_param("i", $worker_id);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    $attendance_details = [];
    $monthly_salaries = [];

    while ($row = $result->fetch_assoc()) {
        $worker_name = $row['name'];
        $month = $row['month'];
        $date = $row['date'];
        $status = $row['status'];
        $salary = ($status === 'Present') ? $row['salary_per_day'] : 0;

        $attendance_details[$worker_name][$month][] = [
            'date' => $date,
            'status' => $status,
            'salary' => $salary
        ];

        if (!isset($monthly_salaries[$worker_name][$month])) {
            $monthly_salaries[$worker_name][$month] = 0;
        }
        $monthly_salaries[$worker_name][$month] += $salary;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Salary</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>

    <div class="receipt_forms">
        <div class="forms">
            <h2>View Salary</h2>
        </div>

        <form method="POST">
            <div class="forms">
                <label for="worker_id">Worker:</label>
                <select name="worker_id" required>
                    <option value="all">All Workers</option>
                    <?php while ($worker = $workers->fetch_assoc()) { ?>
                        <option value="<?= $worker['id'] ?>"><?= $worker['name'] ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="forms">
                <input type="submit" value="View Monthly Salary and Attendance">
            </div>
        </form>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
            <h3>Monthly Salary Details with Attendance</h3>
            <table border='1' cellpadding='5' cellspacing='0'>
                <tr>
                    <th>Worker</th>
                    <th>Month</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Salary for the Day (GHS)</th>
                </tr>

                <?php foreach ($attendance_details as $worker_name => $months) {
                    foreach ($months as $month => $details) {
                        $month_name = date('F', mktime(0, 0, 0, $month, 10));

                        foreach ($details as $detail) {
                            echo "<tr>
                                    <td>{$worker_name}</td>
                                    <td>{$month_name}</td>
                                    <td>{$detail['date']}</td>
                                    <td>{$detail['status']}</td>
                                    <td>{$detail['salary']}</td>
                                  </tr>";
                        }

                        // Display the total salary for the month
                        echo "<tr>
                                <td colspan='4'><strong>Total for {$worker_name} in {$month_name}</strong></td>
                                <td><strong>{$monthly_salaries[$worker_name][$month]}</strong></td>
                              </tr>";
                    }
                } ?>
            </table>
        <?php } ?>
    </div>
</body>

</html>
