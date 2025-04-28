<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../partials/head.php');
require_once('../views/loan_functions.php');
$user_role = $_SESSION['user_access_level'];

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_email = $_SESSION['user_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repayment_id'], $_POST['amount'])) {
    $repayment_id = $_POST['repayment_id'];
    $amount = floatval($_POST['amount']);

    // Step 1: Get current repayment details including due_date and status
    $stmt = $mysqli->prepare("SELECT loan_amount, amount_paid, due_date, status FROM repayments WHERE repayment_id = ?");
    $stmt->bind_param("i", $repayment_id);
    $stmt->execute();
    $result_check = $stmt->get_result();
    $repayment = $result_check->fetch_assoc();

    if ($repayment) {
        $loan_amount = $repayment['loan_amount'];
        $amount_paid = $repayment['amount_paid'];
        $due_date = new DateTime($repayment['due_date']);
        $today = new DateTime();
        $status = strtolower(trim($repayment['status']));

        // Step 2: Calculate late fee (KES 5 per day after due date)
        $late_fee = 0;
        if ($status === 'overdue') {
            $days_late = $due_date < $today ? $due_date->diff($today)->days : 0;
            $late_fee = $days_late * 50;
        }

        $total_required = $loan_amount + $late_fee;
        $new_paid = $amount_paid + $amount;

        // Step 3: Determine new status
        $new_status = ($new_paid >= $total_required) ? 'paid' : 'pending';

        // Step 4: Update repayment record
        $stmt = $mysqli->prepare("UPDATE repayments SET amount_paid = ?, repayment_date = CURDATE(), status = ? WHERE repayment_id = ?");
        $stmt->bind_param("dsi", $new_paid, $new_status, $repayment_id);
        $stmt->execute();
    }
}

updateOverdue($mysqli);

if ($user_role == 'System Administrator') {
    $result = getAllRepayments($mysqli);
} elseif ($user_role == 'Member') {
    $user_id = $_SESSION['user_id'];
    $result = getRepayments($mysqli, $user_id);
}

$repay = "SELECT r.*, u.user_name, a.loan_name
FROM repayments r
JOIN users u ON u.user_id = r.user_id
LEFT JOIN applications a ON a.application_id = r.loan_id";

$result_repay = $mysqli -> query($repay);



?>

<body id="page-top">
<style>
.d-none {
    display: none !important;
}
</style>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once('../partials/navbar.php'); ?>
        <?php require_once('../partials/top_navbar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Main content  executive dashbord-->
            <div class="level">

                <?php if ($user_role == 'System Administrator') : ?>
                
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">All Loan Repayments</h5>
                                        <div class="d-flex">
                                            <a href="../reports/pdf/repayments.php" class="btn btn-info btn-sm shadow-sm">
                                                <i class="fas fa-file-pdf"></i> Generate Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                        <div class="d-flex mb-3">
                                            <div class="input-group col-6 me-3">
                                                <input type="text" id="nameFilter" class="form-control" placeholder="Filter by Member Name" onkeyup="filterTable()">
                                            </div>

                                            <div class="input-group" style="width: 200px;">
                                                <span class="input-group-text mr-2">Rows</span>
                                                <select id="rowsPerPage" class="form-select" onchange="filterTable()" 
                                                style="width: 100px; outline: none;">
                                                    <option value="5">5</option>
                                                    <option value="10" selected>10</option>
                                                    <option value="25">25</option>
                                                    <option value="1000">All</option>
                                                </select>
                                            </div>

                                        </div>

                                            <!-- breakdown table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                    <th>Member ID</th><th>Loan ID</th><th>Due Date</th><th>Amount Due</th>
                                                    <th>Paid</th><th>Status</th><th>Payment Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php while ($repayresult = $result_repay->fetch_assoc()) { ?>
                                                    <tr style="background: <?= $repayresult['status'] == 'overdue' ? '#fdd' : '#dfd' ?>;">
                                                        <td><?= $repayresult['user_name'] ?></td>
                                                        <td><?= $repayresult['loan_name'] ?></td>
                                                        <td><?= $repayresult['due_date'] ?></td>
                                                        <td><?= $repayresult['loan_amount'] ?></td>
                                                        <td><?= $repayresult['amount_paid'] ?></td>
                                                        <td><?= ucfirst($repayresult['status']) ?></td>
                                                        <td><?= $repayresult['repayment_date'] ?></td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                            <script>
                                               function filterTable() {
                                                    const input = document.getElementById("nameFilter").value.toLowerCase();
                                                    const rowsPerPage = parseInt(document.getElementById("rowsPerPage").value, 10);
                                                    const table = document.getElementById("datatable");
                                                    const tr = table.getElementsByTagName("tr");

                                                    let visibleCount = 0;

                                                    for (let i = 1; i < tr.length; i++) {
                                                        const td = tr[i].getElementsByTagName("td")[0];
                                                        if (td) {
                                                            const textValue = td.textContent || td.innerText;
                                                            if (textValue.toLowerCase().indexOf(input) > -1) {
                                                                if (visibleCount < rowsPerPage || rowsPerPage >= 1000) {
                                                                    tr[i].style.display = "";
                                                                    visibleCount++;
                                                                } else {
                                                                    tr[i].style.display = "none";
                                                                }
                                                            } else {
                                                                tr[i].style.display = "none";
                                                            }
                                                        }
                                                    }
                                                }

                                            </script>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <!-- Members Dashboard -->
                    <?php elseif ($user_role == 'Member') : ?>
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>My Loan Installments</h2>
                                <table class="table table-bordered" id="datatable">
                                    <tr>
                                        <th>Due Date</th><th>Amount Due</th><th>Paid</th><th>Status</th><th>Action</th>
                                    </tr>
                                    <?php
                                    if ($result->num_rows === 0) {
                                        echo "<tr><td colspan='5'>⚠️ No Loan installments found</td></tr>";
                                    }
                                    while ($row = $result->fetch_assoc()) {
                                        $loan_amount = $row['loan_amount'];
                                        $due_date = new DateTime($row['due_date']);
                                        $today = new DateTime();
                                        $late_fee = 0;

                                        if (strtolower(trim($row['status'])) === 'overdue') {
                                            $days_late = $due_date->diff($today)->days;
                                            $late_fee = $days_late * 50; 
                                        }

                                        $total_due = $loan_amount + $late_fee;
                                    ?>
                                    <tr style="background: <?= $row['status'] == 'overdue' ? '#fdd' : '#dfd' ?>;">
                                        <td><?= $row['due_date'] ?></td>
                                        <td>
                                            <?= $loan_amount ?>
                                            <?php if ($late_fee > 0): ?>
                                                <br><small class="text-danger">Late Fee: <?= $late_fee ?> <br>Total: <?= $total_due ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $row['amount_paid'] ?></td>
                                        <td><?= ucfirst($row['status']) ?></td>
                                        <td>
                                            <?php
                                            $status = strtolower(trim($row['status']));
                                            if ($status === 'pending' || $status === 'overdue') {
                                            ?>
                                               <!-- Repayment Form -->
                                                <form id="paymentForm" method="POST">
                                                    <input type="hidden" name="repayment_id" value="<?= $row['repayment_id'] ?>">
                                                    <input type="number" name="amount" step="0.01" max="<?= $total_due - $row['amount_paid'] ?>" required class="form-control" placeholder="Max: <?= $total_due - $row['amount_paid'] ?>">

                                                    <!-- Button and Spinner -->
                                                    <button type="submit" class="btn btn-outline-success mt-1" id="payButton">
                                                        <span id="buttonText">Pay with M-Pesa</span>
                                                        <span id="spinner" class="d-none">
                                                            <div class="spinner-border spinner-border-sm" role="status">
                                                                <span class="sr-only">Loading...</span>
                                                            </div>
                                                        </span>
                                                    </button>
                                                </form>

                                            <?php } else {
                                                echo "✔️ <span class='badge bg-success'>Paid</span>";
                                            } ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                

                                <script src="https://js.paystack.co/v1/inline.js"></script>

                                <script>
                                  const paymentForm = document.getElementById('paymentForm');
                                    paymentForm.addEventListener("submit", payWithPaystack, false);

                                    function payWithPaystack(e) {
                                        e.preventDefault();

                                        const payButton = document.getElementById('payButton');
                                        const buttonText = document.getElementById('buttonText');
                                        const spinner = document.getElementById('spinner');

                                        // Disable button and show spinner immediately
                                        payButton.disabled = true;
                                        buttonText.textContent = 'Processing...';
                                        spinner.classList.remove('d-none');  

                                        let email = "<?php echo $_SESSION['user_email']; ?>";  
                                        const amount = document.querySelector('[name="amount"]').value;
                                        const repaymentId = document.querySelector('[name="repayment_id"]').value;

                                        if (!amount || !repaymentId) {
                                            alert("Please fill in the required fields.");
                                            return;
                                        }

                                        let handler = PaystackPop.setup({
                                            key: 'pk_test_2d11faf4649f14c3568d4df5f9faddf55ca9a65d',
                                            amount: amount * 100, 
                                            email: email, 
                                            currency: "KES",
                                            channels: ['mobile_money'], 
                                            callback: function(response) {
                                                // Payment successful, hide spinner
                                                spinner.classList.add('d-none');  
                                                alert('Payment complete! Reference: ' + response.reference);

                                                // Send the payment reference to the server for verification
                                                fetch('process_mpesa_payment.php', {
                                                    method: 'POST',
                                                    body: JSON.stringify({
                                                        reference: response.reference,
                                                        repayment_id: repaymentId,
                                                        amount: amount
                                                    }),
                                                    headers: {
                                                        'Content-Type': 'application/json'
                                                    }
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.status === 'success') {
                                                        alert('Payment Recorded!');
                                                        window.location.reload(); 
                                                    } else {
                                                        alert('Error: ' + data.message);
                                                    }
                                                })
                                                .catch(error => {
                                                    alert('Error during payment verification: ' + error.message);
                                                    console.error('Error:', error);
                                                });
                                            },
                                            onClose: function() {
                                                // Hide the spinner if the user closes the payment window
                                                spinner.classList.add('d-none');  
                                                alert('Transaction was not completed.');
                                                payButton.disabled = false;  
                                                buttonText.textContent = 'Pay with M-Pesa'; 
                                            }
                                        });

                                        handler.openIframe();  // Open Paystack pop-up for payment
                                    }

                                </script>

                            </div>
                        </div>

                <?php endif; ?>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->


        <!-- End of Main Content -->

        <!-- Footer -->
        <?php require_once('../partials/footer.php');?>

        <!-- End of Footer -->


        <!-- End of Page Wrapper -->
    </div>
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php require_once('../partials/scripts.php'); ?>
</body>
</html>