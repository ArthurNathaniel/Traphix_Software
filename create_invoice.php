<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Invoice  </title>
    <?php include 'cdn.php' ?>
    <link rel="stylesheet" href="./css/base.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="create_invoice_all">
        <h2>Create Invoice</h2>
        <form action="save_invoice.php" method="post">
            <div class="forms">
                <label>Billed to:</label>
                <input type="text" name="billed_to" required>
            </div>
            <div class="forms">
                <label>Contact Number:</label>
                <input type="text" name="contact_number" required>
            </div>

            <table border="1" class="invoice_table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Service</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Amount</th>
                        <th>Actions</th>
                        <th><button type="button" onclick="addRow()">Add Row</button></th><!-- Add this column for delete button -->
                    </tr>
                </thead>
                <tbody id="invoice_table_body">
                    <tr>
                        <td class="row_number">1</td>
                        <td><input type="text" name="service[]" required></td>
                        <td><input type="number" name="day[]" oninput="calculateAmount(this)" required></td>
                        <td><input type="number" name="price[]" oninput="calculateAmount(this)" required></td>
                        <td><input type="number" name="amount[]" required readonly></td>
                        <td>
                            <button type="button" onclick="removeRow(this)">Remove</button>
                        </td> <!-- Add delete button -->
                    </tr>
                </tbody>
            </table>

            <div class="forms">
                <label>Subtotal:</label>
                <input type="number" id="subtotal" name="subtotal" required readonly>
            </div>
            <div class="forms">
                <label>VAT Tax:</label>
                <input type="number" id="vat_tax" name="vat_tax" oninput="calculateTotal()" required>
            </div>
            <div class="forms">
                <label>Total:</label>
                <input type="number" id="total" name="total" required readonly>
            </div>

            <div class="forms">
                <button type="submit" style="color: #fff;">Save Invoice</button>
            </div>
        </form>
    </div>

    <script>
        function addRow() {
            var tableBody = document.getElementById("invoice_table_body");
            var rowCount = tableBody.rows.length + 1;

            var newRow = tableBody.insertRow();
            newRow.innerHTML = `
                <td class="row_number">${rowCount}</td>
                <td><input type="text" name="service[]" required></td>
                <td><input type="number" name="day[]" oninput="calculateAmount(this)" required></td>
                <td><input type="number" name="price[]" oninput="calculateAmount(this)" required></td>
                <td><input type="number" name="amount[]" required readonly></td>
                <td>
                    <button type="button" onclick="removeRow(this)">Remove</button>
                </td>
            `;

            updateRowNumbers();
        }

        function removeRow(button) {
            var row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateRowNumbers();
            calculateSubtotal();
            calculateTotal();
        }

        function updateRowNumbers() {
            var rows = document.querySelectorAll("#invoice_table_body tr");
            rows.forEach(function(row, index) {
                row.querySelector(".row_number").textContent = index + 1;
            });
        }

        function calculateAmount(input) {
            var row = input.parentNode.parentNode;
            var day = parseFloat(row.querySelector("input[name='day[]']").value);
            var price = parseFloat(row.querySelector("input[name='price[]']").value);
            var amount = isNaN(day) || isNaN(price) ? 0 : day * price;
            row.querySelector("input[name='amount[]']").value = amount;
            calculateSubtotal();
            calculateTotal();
        }

        function calculateSubtotal() {
            var amounts = document.querySelectorAll("input[name='amount[]']");
            var subtotal = 0;
            amounts.forEach(function(amountInput) {
                subtotal += parseFloat(amountInput.value);
            });
            document.getElementById("subtotal").value = subtotal;
        }

        function calculateTotal() {
            var subtotal = parseFloat(document.getElementById("subtotal").value);
            var vatTax = parseFloat(document.getElementById("vat_tax").value);
            var total = subtotal + vatTax;
            document.getElementById("total").value = total;
        }
    </script>
    <script src="../js/sidebar.js"></script>
</body>

</html>