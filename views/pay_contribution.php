<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../partials/head.php');
$user_role = $_SESSION['user_access_level'];

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Contribution ID.");
}

$contribution_id = (int) $_GET['id'];

// Fetch contribution details
$contribution_query = mysqli_query($mysqli, "SELECT * FROM contributions WHERE contribution_id = $contribution_id");
if (!$contribution_query || mysqli_num_rows($contribution_query) == 0) {
    die("Contribution not found.");
}
$contribution = mysqli_fetch_assoc($contribution_query);

// Fetch member's previous payment for this contribution
$payment_query = mysqli_query($mysqli, "SELECT SUM(amount_paid) AS total_paid 
    FROM member_contributions 
    WHERE contribution_id = $contribution_id AND member_id = $user_id");

$payment = mysqli_fetch_assoc($payment_query);
$total_paid = $payment['total_paid'] ?? 0;

// Calculate remaining amount
$amount_required = $contribution['amount'] - $total_paid;
if ($amount_required < 0) {
    $amount_required = 0; 
}

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

     
        <div></div>
        <!-- Content Header (Page header) -->

        <!-- /.content-header -->

        <!-- Main content  executive dashbord-->
        <div class="level">
        <div class="container mt-4">
            <h2>Pay Contribution: <?php echo htmlspecialchars($contribution['title']); ?></h2>
            <p>Amount Required: <strong><?php echo number_format($amount_required, 2); ?></strong></p>

            <form id="paymentForm">
                <input type="hidden" id="contribution_id" value="<?php echo $contribution_id; ?>">
                <input type="hidden" id="member_id" value="<?php echo $user_id; ?>">

                <div class="mb-3">
                    <label for="amount" class="form-label">Enter Amount:</label>
                    <input type="number" id="amount_paid" name="amount_paid" class="form-control" required min="1" max="<?php echo $amount_required; ?>">
                    <div id="amountError" class="text-danger d-none">Please enter a valid amount between 1 and <?php echo $amount_required; ?>.</div>
                </div>

                <button type="submit" id="payButton" class="btn btn-success">
                    <span id="buttonText">Make Payment</span>
                    <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
                <a href="contributions.php" class="btn btn-secondary">Cancel</a>
            </form>

            <script>
                document.getElementById('paymentForm').addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Hide previous error messages
                    document.getElementById('amountError').classList.add('d-none');

                    // Get the input value
                    const amountPaid = document.getElementById('amount_paid').value;
                    const minAmount = 1;
                    const maxAmount = <?php echo $amount_required; ?>;

                    // Validate amount
                    if (amountPaid < minAmount || amountPaid > maxAmount) {
                        document.getElementById('amountError').classList.remove('d-none');
                    } else {
                        const payButton = document.getElementById('payButton');
                        const buttonText = document.getElementById('buttonText');
                        const spinner = document.getElementById('spinner');

                        // Disable button and show spinner
                        payButton.disabled = true;
                        buttonText.textContent = 'Processing...';
                        spinner.classList.remove('d-none');

                        const contribution_id = document.getElementById('contribution_id').value;
                        const member_id = document.getElementById('member_id').value;
                        const amount = document.getElementById('amount_paid').value;

                        function payWithPaystack() {
                            let email = "<?php echo $_SESSION['user_email']; ?>";
                            let amount = document.getElementById('amount_paid').value;
                            let contribution_id = document.getElementById('contribution_id').value;
                            let member_id = document.getElementById('member_id').value;

                            if (!email || !amount) {
                                alert("Please fill in all fields");
                                return;
                            }

                            let handler = PaystackPop.setup({
                                key: 'pk_test_2d11faf4649f14c3568d4df5f9faddf55ca9a65d',
                                email: email,
                                amount: amount * 100, // Paystack expects the amount in kobo (1 KES = 100 Kobo)
                                currency: "KES",
                                channels: ['mobile_money'],
                                callback: function(response) {
                                    // Payment complete, verify
                                    fetch('../helpers/verify_contribution.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            reference: response.reference,
                                            contribution_id: contribution_id,
                                            member_id: member_id,
                                            amount: amount
                                        })
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.status === 'success') {
                                            alert('Contribution recorded successfully!');
                                            window.location.href = "contributions.php";
                                        } else {
                                            alert('Error recording contribution: ' + data.message);
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        alert('Failed to record contribution.');
                                    });
                                },
                                onClose: function() {
                                    alert('Transaction was not completed.');
                                    payButton.disabled = false;
                                    buttonText.textContent = 'Make Payment';
                                    spinner.classList.add('d-none');
                                },
                            });

                            handler.openIframe();
                        }

                        // Call the function here to start the payment process
                        payWithPaystack();
                    }
                });
            </script>


        </div>
            
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
<script src="https://js.paystack.co/v1/inline.js"></script>

<?php require_once('../partials/scripts.php'); ?>
</body>
</html>
