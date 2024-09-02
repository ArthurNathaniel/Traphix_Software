<style>
    .side-logo {
        width: 220px;
        height: 180px;
    }
</style>
<div class="sidebar_all">
    <div class="logo side-logo"></div>
    <br>
    <br>
    <div class="links">
        <h3>
            <span class="icon"><i class="fa-solid fa-chart-simple"> </i></span> Dashboard
        </h3>
        <a href="add_services.php">Add Services</a>
        <a href="manage_services.php">Services Management</a>
        <a href="services_dashboard.php">Monthly Services Dashboard</a>
        <a href="years_services_dashboard.php">Yearly Services Dashboard</a>
        
        
        <h3><span class="icon"><i class="fas fa-money-bill-alt"></i></span> Net Revenue</h3>

        <a href="all_net.php" class="ll"> Net Revenue Summary </a>
        <a href="daily_net.php"> Daily Net Revenue</a>
        <a href="weekly_net.php"> Weekly Net Revenue</a>
        <a href="monthly_net.php"> Monthly Net Revenue</a>
        <a href="yearly_net.php"> Yearly Net Revenue</a>

        <h3> <span class="icon"><i class="fas fa-receipt"></i></span> Receipt</h3>

        <a href="receipt_form.php"> Generate Reciept</a>
        <a href="view_receipts.php"> Receipt Management</a>
        <a href="partial_payment.php"> Partial Payment</a>

        <h3><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Invoice</h3>

        <a href="create_invoice.php"> Generate Invoice</a>
        <a href="view_invoices.php">Invoice Management</a>
        <h3> <span class="icon"><i class="fas fa-receipt"></i></span> Expenditure</h3>
        <a href="expenditure_form.php">Generate Expenditure</a>
        <a href="view_expenditure.php">Expenditure Management</a>
        <h3><span class="icon"><i class="fas fa-user-tie"></i></span>Pay Roll</h3>

        <a href="add_worker.php">Add Workers</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="view_attendance_salary.php">View Salary</a>

        <a href="logout.php" class="log">

            <h3> <i class="fas fa-sign-out-alt"></i> LOGOUT</h3>
        </a>
    </div>

</div>
<button id="toggleButton">
    <i class="fa-solid fa-bars-staggered"></i>
</button>

<script>
    // Get the button and sidebar elements
    var toggleButton = document.getElementById("toggleButton");
    var sidebar = document.querySelector(".sidebar_all");
    var icon = toggleButton.querySelector("i");

    // Add click event listener to the button
    toggleButton.addEventListener("click", function() {
        // Toggle the visibility of the sidebar
        if (sidebar.style.display === "none" || sidebar.style.display === "") {
            sidebar.style.display = "block";
            icon.classList.remove("fa-bars-staggered");
            icon.classList.add("fa-xmark");
        } else {
            sidebar.style.display = "none";
            icon.classList.remove("fa-xmark");
            icon.classList.add("fa-bars-staggered");
        }
    });
</script>