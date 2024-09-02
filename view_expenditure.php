<?php
include 'db.php'; // Include the database connection file
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Function to delete an expenditure
function deleteExpenditure($conn, $expenditureId)
{
    $sql = "DELETE FROM expenditure WHERE id=$expenditureId";
    if ($conn->query($sql) === TRUE) {
        return true; // Deletion successful
    } else {
        return false; // Error occurred
    }
}

// Check if delete request is sent
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    if (deleteExpenditure($conn, $deleteId)) {
        // Redirect back to this page after successful deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Handle error
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch expenditures from the database
$sql = "SELECT * FROM expenditure";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenditure List -  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #25624d;
            color: #fff;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="create_invoice_all">

        <h1>Expenditure List</h1>
        <div class="lists">
            <a href="daily_expenditure.php">Daily Expenditure</a>
            <a href="weekly_expenditure.php">Weekly Expenditure</a>
            <a href="monthly_expenditure.php">Monthly Expenditure</a>
            <a href="yearly_expenditure.php">Yearly Expenditure</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone Number</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Action</th> <!-- Added Action column -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["name"] . "</td>";
                        echo "<td>" . $row["phone"] . "</td>";
                        echo "<td>" . $row["date"] . "</td>";
                        echo "<td>" . $row["description"] . "</td>";
                        echo "<td>" . $row["amount"] . "</td>";
                        // Add edit button with a link to edit_expenditure.php passing expenditure id as a parameter
                        echo "<td>";
                        echo "<a href='edit_expenditure.php?id=" . $row["id"] . "'>Edit</a>";
                        echo " | ";
                        echo "<a href='?delete_id=" . $row["id"] . "' onclick='return confirm(\"Are you sure you want to delete this expenditure?\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No expenditures found</td></tr>";
                }
                ?>
            </tbody>
        </table>

    </div>
    <script src="../js/sidebar.js"></script>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>