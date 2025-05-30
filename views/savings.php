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

// fetch
$sql = "SELECT 
    s.user_id, u.member_name, s.email, s.reference, s.created_at, SUM(s.amount_saved) AS total_savings
FROM savings s JOIN members u ON s.user_id = u.user_id
GROUP BY s.user_id, u.member_name, s.email
ORDER BY total_savings DESC";
$result = $mysqli -> query($sql);
$savings = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $savings[] = $row;
    }
}


$user_id = $_SESSION['user_id'];


?>

<body id="page-top">

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
                         <div id="loanapplication" class="content-section">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Member Savings</h5><div class="text-right">
                                            <a href="../reports/pdf/savings.php" class="btn btn-info m-2">
                                                <i class="fas fa-file-pdf"></i> Generate Report
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Member Name</th>
                                                        <th>Email</th>
                                                        <th>Reference No</th>
                                                        <th>Amount</th>
                                                        <th>Savings Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($savings)) : ?>
                                                    <?php foreach ($savings as $key => $saving) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($saving['member_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['email']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['reference']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['total_savings']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['created_at']); ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="8">No records found</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php require_once('../modals/manage_savings.php'); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col-lg-12">
                         <div id="loanstatus" class="content-section" style="display: none;">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Shares</h5>
                                        <a href="#addshares" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm">
                                            <button type="button" class="btn btn-block btn-primary">Add new shares</button></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Member Name</th>
                                                        <th>Share Amount</th>
                                                        <th>Purchase Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($shares)) : ?>
                                                    <?php foreach ($shares as $key => $share) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($share['member_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($share['share_amount']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($share['purchase_date']); ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="8">No records found</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                        </div>
                    </div> -->

                <?php elseif ($user_role == 'Member') : ?>
                    <!-- Main content -->
                    <div class="content">
                            <div class="row">
                                <div class="col-xl-12">
                                <div class="content-section">
                                    <h2>Want to make a saving?</h2>
                                <form id="paymentForm" class="needs-validation" novalidate>
                                    <fieldset class="border p-3 mb-3">
                                        <legend class="w-auto text-success">Enter details</legend>

                                        <div class="form-group">
                                            <label for="amount">Saving Amount <span class="text-danger">*</span></label>
                                            <input type="number" id="amount" required class="form-control" placeholder="Enter amount" min="1">
                                            <div class="invalid-feedback">
                                                Please enter a valid amount greater than zero.
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" id="email" required class="form-control" placeholder="Enter email">
                                            <div class="invalid-feedback">
                                                Please enter a valid email address.
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary">Save and Pay</button>
                                        </div>
                                    </fieldset>
                                </form>
                                <script>
                                    document.getElementById("paymentForm").addEventListener("submit", function(event) {
                                        if (!this.checkValidity()) {
                                            event.preventDefault(); 
                                            event.stopPropagation(); 
                                        }

                                        this.classList.add('was-validated');
                                    });

                                    document.getElementById("amount").addEventListener("input", function() {
                                        if (this.validity.valid) {
                                            this.classList.remove('is-invalid');
                                        } else {
                                            this.classList.add('is-invalid');
                                        }
                                    });

                                    document.getElementById("email").addEventListener("input", function() {
                                        if (this.validity.valid) {
                                            this.classList.remove('is-invalid');
                                        } else {
                                            this.classList.add('is-invalid');
                                        }
                                    });
                                </script>

                                <script src="https://js.paystack.co/v1/inline.js"></script>
                                <script>
                                    const paymentForm = document.getElementById('paymentForm');
                                    paymentForm.addEventListener("submit", payWithPaystack, false);

                                    function payWithPaystack(e) {
                                        e.preventDefault();

                                        let email = document.getElementById("email").value;
                                        let amount = document.getElementById("amount").value;

                                        if (!email || !amount) {
                                            alert("Please fill in all fields");
                                            return;
                                        }

                                        let handler = PaystackPop.setup({
                                            key: 'pk_test_2d11faf4649f14c3568d4df5f9faddf55ca9a65d', 
                                            email: email,
                                            amount: amount * 100, // Paystack accepts amount in kobo/cents
                                            currency: "KES",
                                            channels: ['mobile_money'], 
                                            callback: function(response) {
                                                // Payment was successful
                                                alert('Payment complete! Reference: ' + response.reference);

                                                // Send payment reference and other details to server
                                                fetch('verify_payment.php', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/json'
                                                    },
                                                    body: JSON.stringify({
                                                        reference: response.reference,
                                                        email: email,
                                                        amount: amount
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    if (data.status === 'success') {
                                                        alert('Saving recorded successfully!');
                                                        window.location.href = "savings.php"; 
                                                    } else {
                                                        alert('Error recording saving: ' + data.message);
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    alert('Failed to record saving.');
                                                });
                                            },
                                            onClose: function() {
                                                alert('Transaction was not completed, window closed.');
                                            },
                                        });
                                        handler.openIframe();
                                    }
                                </script>





                                </div>
                                </div>
                            </div>
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
    <?php require_once('../customs/scripts/functions.php'); ?>
    <?php require_once('../customs/scripts/ajax.php'); ?>

    <script>
    function showSection(sectionId) {
        // Hide all sections
        var sections = document.getElementsByClassName('content-section');
        for (var i = 0; i < sections.length; i++) {
            sections[i].style.display = 'none';
        }

        // Show the selected section
        document.getElementById(sectionId).style.display = 'block';
    }

    $(document).ready(function() {
        $('#datatable').DataTable();
    });
    </script>

    <?php require_once('../partials/scripts.php'); ?>
</body>


</html>