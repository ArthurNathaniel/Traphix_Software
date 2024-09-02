<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}
// Establish a database connection
include 'db.php';

// Check if invoice ID is provided
if (isset($_GET['id'])) {
    $invoice_id = $_GET['id'];

    // Fetch invoice data based on the provided ID
    $sql = "SELECT * FROM invoices WHERE id = $invoice_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Invoice not found.";
        exit;
    }
} else {
    echo "Invoice ID not provided.";
    exit;
}

// Fetch invoice details
$sql_details = "SELECT * FROM invoice_services WHERE invoice_id = $invoice_id";
$result_details = $conn->query($sql_details);

// Close the database connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Invoice</title>
    <?php include 'cdn.php'; ?>
    <link rel="stylesheet" href="./css/base.css">
    <link rel="stylesheet" href="./css/invoice.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <style>
        th {
            background-color:#25624d;
            color: #fff;
        }

        .print_btn button {
            margin-block: 20px;
            margin-left: 5%;
            padding: 15px 35px;
            background-color:#25624d;
            border: none;
            color: #fff;
            text-transform: uppercase;
        }

        .nav_logo {
            height: 100px;
            width: 100px;
        }
    </style>
</head>

<body id="content">
    <div class="print_btn" id="printBtnContainer">
        <button id="downloadBtn">Download PDF</button>
    </div>


    <div class="navbar_top">
        <div class="nav_logo"></div>
        <div class="nav_title">
            <h1>INVOICE</h1>
            <p><strong>Tel: </strong>+233 24 007 9570 / +233 54 217 2430</p>

        </div>
    </div>
    <div id="print-section">

        <!-- <h2>Invoice Details</h2> -->
        <?php
        $dateToday = date("Y-m-d");
        ?>
        <div class="client_details">
            <p><strong>Invoice ID:</strong> <?php echo $row['id']; ?></p>
            <p><strong>Billed to:</strong> <?php echo $row['billed_to']; ?></p>
            <p><strong>Contact Number:</strong> <?php echo $row['contact_number']; ?></p>
            <p><strong>Date:</strong> <?php echo $dateToday; ?></p>
        </div>

        <!-- Invoice details table -->
        <table border="1">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Service</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_details->num_rows > 0) {
                    $row_number = 1;
                    while ($row_detail = $result_details->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_number++ . "</td>";
                        echo "<td>" . $row_detail["service"] . "</td>";
                        echo "<td>" . $row_detail["day"] . "</td>";
                        echo "<td>" . $row_detail["price"] . "</td>";
                        echo "<td>" . $row_detail["amount"] . "</td>";

                        echo "</tr>";
                    }
                    echo "<tr><td colspan='4' align='right'><strong >Subtotal:</strong></td><td><p> " . $row["subtotal"] . "</p></td></tr>";
                    echo "<tr><td colspan='4' align='right'><strong >Tax:</strong></td><td><p> " . $row["vat_tax"] . "</p></td></tr>";
                    echo "<tr><td colspan='4' align='right'><strong >Total:</strong></td><td><h1> " . 'GH₵ '  . $row["total"] . "</h1></td></tr>";
                } else {
                    echo "<tr><td colspan='5'>No invoice details found.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="payment_details">
            <h2>Payment Info:</h2>
            <p><strong>Mobile Money Number: </strong>+233 24 007 9570 </p>
            <p><strong>Mobile Money Name: </strong>Luster Cleaning Solutions</p>


        </div>

    </div>
    <div class="navbar_top">
        <div class="nav_logo"></div>
        <div class="nav_title">
            <h1>INVOICE</h1>
            <p><strong>Tel: </strong>+233 24 007 9570 / +233 54 217 2430</p>
        </div>
    </div>
    <script>
        // Function to handle button click event
        document.getElementById('downloadBtn').addEventListener('click', function() {
            // Hide the download button
            document.getElementById('printBtnContainer').style.display = 'none';

            // Get the billed to name
            const billedTo = '<?php echo $row["billed_to"]; ?>';

            // Select the element containing HTML content to be converted
            const element = document.getElementById('content');
            // Options for PDF generation
            const options = {
                filename: 'Luster_Cleaning_Solutions-' + billedTo + '.pdf', // Set PDF filename with billed to name
                image: {
                    type: 'jpeg',
                    quality: 0.98
                }, // Image quality
                html2canvas: {
                    scale: 2
                }, // Scale for better resolution
                jsPDF: {
                    unit: 'in',
                    format: 'letter',
                    orientation: 'portrait'
                }, // PDF format options
                // Callback function to show the download button again after PDF generation
                onComplete: function(pdf) {
                    document.getElementById('printBtnContainer').style.display = 'block';
                }
            };
            // Convert HTML to PDF
            html2pdf().from(element).set(options).save();
        });
    </script>


    <!-- Button to trigger printing -->

</body>

</html>