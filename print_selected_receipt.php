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
    <title>Print Receipt</title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/print.css">
    <style>
        .print_title {
            text-align: center;
        }

        .nav_logo {
            width: 130px;
            height: 130px;
            background-color: #fff;
            margin-bottom: 20px;
        }

        .print_grid {
            position: relative;
        }

        .services {
            position: absolute;
            width: 100%;
            display: flex;
            justify-content: flex-start;
            align-items: center;
            flex-wrap: wrap;
            left: 150px;
            height: 70px;
        }

        .service-item {
            margin-right: 5px;
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="nav_bg">
        <div class="nav_logo"></div>
        <div class="navbar_details">
            <div class="nav_one">
                <p><strong>Location: </strong>Tema - Ghana</p>
                <p><strong>Tel: </strong>+233 24 007 9570 / +233 54 217 2430</p>
            </div>
            <div class="nav_two">
            <p><strong>WhatsApp: </strong>+233 54 217 2430</p>
            </div>
        </div>
        </div>
    </div>

    <div class="official">
        <button>OFFICIAL RECEIPT</button>
    </div>

    <div class="content">
        <?php
        include 'db.php';

        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $sql = "SELECT * FROM receipt WHERE id=$id";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<div class='print_all'>";
                echo "<div class='print_grid'>";
                echo "<p><strong>ID:</strong> TRH -" . $row["id"] . "</p>";
                echo "</div>";

                echo "<div class='print_grid'>";
                echo "<p><strong>Date:</strong> " . $row["date"] . "</p>";
                echo "</div>";

                echo "<div class='print_grid'>";
                echo "<p><strong>Client Name:</strong> " . $row["client_name"] . "</p>";
                echo "</div>";

                // Display services associated with the receipt
                echo "<div class='print_grid'>";
                echo "<p><strong>Services:</strong></p>";
                echo "<div class='services'>";
                $sql_services = "SELECT services.service_name, receipt_services.price FROM receipt_services 
                                INNER JOIN services ON receipt_services.service_id = services.id 
                                WHERE receipt_services.receipt_id = $id";
                $result_services = $conn->query($sql_services);
                if ($result_services->num_rows > 0) {
                    while ($service_row = $result_services->fetch_assoc()) {
                        echo "<div class='service-item'>" . $service_row["service_name"] . " - GH₵ " . $service_row["price"] . " , " ."</div>";
                    }
                }
                echo "</div>";
                echo "</div>";

                echo "<div class='print_grid'>";
                echo "<p><strong>Payment Method:</strong> " . $row["payment_method"] . "</p>";
                echo "</div>";

                echo "<div class='print_grid'>";
                echo "<p><strong>Other Method:</strong> " . $row["other_method"] . "</p>";
                echo "</div>";

                echo "<div class='print_grid'>";
                echo "<p><strong>Payment Type:</strong> " . $row["payment_type"] . "</p>";
                echo "</div>";

                echo "<div class='print_grid'>";
                echo "<p><strong>Amount:</strong> GH₵ " . $row["amount"] . "</p>";
                echo "</div>";

                echo "<div class='print_btn'>";
                echo "<button onclick='window.print()' class='pp'>Print Receipt</button>";
                echo "</div>";
            } else {
                echo "Receipt not found";
            }
        } else {
            echo "No receipt ID provided";
        }
        echo "</div>";
        $conn->close();
        ?>
    </div>

    <!-- Include html2pdf.js library -->
    <script src="path/to/html2pdf.js"></script>

    <script>
        function generatePDF() {
            const element = document.querySelector('.content');
            html2pdf()
                .from(element)
                .save();
        }
    </script>
</body>

</html>