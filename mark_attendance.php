<?php
include 'db.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $worker_ids = $_POST['worker_id'];
    $date = $_POST['date'];
    $statuses = $_POST['status'];

    foreach ($worker_ids as $index => $worker_id) {
        $status = $statuses[$index];

        // Check if attendance already exists for this worker on the specified date
        $check_sql = "SELECT * FROM attendance WHERE worker_id = ? AND date = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("is", $worker_id, $date);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows == 0) {
            // If no duplicate is found, insert the new attendance record
            $sql = "INSERT INTO attendance (worker_id, date, status) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $worker_id, $date, $status);
            $stmt->execute();
            $stmt->close(); // Close the statement after executing it
        } else {
            echo "Attendance for worker ID $worker_id on $date already exists.<br>";
        }

        $check_stmt->close(); // Close the check statement after executing it
    }

    echo "Attendance marking process completed!";
}

$workers = $conn->query("SELECT id, name FROM workers");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Attendance</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php' ?>
    <div class="receipt_forms">
        <form method="POST">
            <div class="forms">
            <label for="date">Date:</label>
            <input type="date" name="date" required>
            </div>

            <table border="1" cellpadding="5" cellspacing="0">
                <tr>
                    <th>Worker Name</th>
                    <th>Status</th>
                </tr>

                <?php while ($worker = $workers->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <?= $worker['name'] ?>
                            <input type="hidden" name="worker_id[]" value="<?= $worker['id'] ?>">
                        </td>
                        <td>
                          <div class="forms">
                          <select name="status[]" required>
                                <option value="Present">Present</option>
                                <option value="Absent">Absent</option>
                            </select>
                          </div>
                        </td>
                    </tr>
                <?php } ?>
            </table><br>

          <div class="forms">
          <input type="submit" value="Mark Attendance">
          </div>
        </form>

    </div>
    </form>
    </div>
</body>

</html>