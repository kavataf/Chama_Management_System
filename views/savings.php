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
$sql = "SELECT s.savings_id, s.email, u.member_name, s.reference, s.amount_saved, s.created_at
          FROM savings s
          JOIN members u ON s.user_id = u.user_id
          ORDER BY s.created_at DESC";
$result = $mysqli -> query($sql);
$savings = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $savings[] = $row;
    }
}

// fetch shares
$sql2 = "SELECT s.id, u.member_name, s.share_amount, s.purchase_date 
          FROM shares s
          JOIN members u ON s.user_id = u.user_id
          ORDER BY s.purchase_date DESC";
$result = $mysqli -> query($sql2);
$shares = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $shares[] = $row;
    }
}

$user_id = $_SESSION['user_id'];
// // Fetch member's savings
// $savings_query = "SELECT SUM(amount) AS total_savings FROM savings WHERE user_id = ?";
// $stmt = $mysqli->prepare($savings_query);
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $stmt->bind_result($total_savings);
// $stmt->fetch();
// $stmt->close();

// Fetch member's shares
$shares_query = "SELECT SUM(share_amount) AS total_shares FROM shares WHERE user_id = ?";
$stmt = $mysqli->prepare($shares_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_shares);
$stmt->fetch();
$stmt->close();

// Fetch member's dividends
$dividends_query = "SELECT SUM(dividend_amount) AS total_dividends FROM dividends WHERE user_id = ?";
$stmt = $mysqli->prepare($dividends_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_dividends);
$stmt->fetch();
$stmt->close();

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
                    <div class="row mb-4">
                                <div class="col-xl-6 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanapplication')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">Savings</span>
                                    </a>
                                </div>
                                <div class="col-xl-6 col-md-6 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanstatus')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">Shares</span>
                                    </a>
                                </div>
                            </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                         <div id="loanapplication" class="content-section">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Savings</h5>
                                        <a href="#addsavings" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm"><button type="button" class="btn btn-block btn-primary">Add new savings</button></a>
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
                                                        <td><?php echo htmlspecialchars($saving['amount_saved']); ?>
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
                                                    <?php require_once('../modals/add_savings.php'); ?>
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
                    <div class="row">
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
                                            <!-- table -->
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
                    </div>

                <?php elseif ($user_role == 'Member') : ?>
                    <!-- Main content -->
                    <div class="content">
                            <div class="row">
                                <div class="col-xl-12">
                                <div class="content-section">
                                    <h2>Want to make a saving?</h2>
                                <form id="paymentForm" class="needs-validation">
                                    <fieldset class="border p-3 mb-3">
                                        <legend class="w-auto text-success">Enter details</legend>

                                        <div class="form-group">
                                            <label for="amount">Saving Amount <span class="text-danger">*</span></label>
                                            <input type="number" id="amount" required class="form-control" placeholder="Enter amount">
                                        </div>

                                        <!-- <div class="form-group">
                                            <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                            <input type="text" id="phone" required class="form-control" placeholder="e.g. 07XXXXXXXX">
                                        </div> -->

                                        <div class="form-group">
                                            <label for="email">Email Address <span class="text-danger">*</span></label>
                                            <input type="email" id="email" required class="form-control" placeholder="Enter email">
                                        </div>

                                        <div class="text-right">
                                            <button type="submit" class="btn btn-primary">Save and Pay</button>
                                        </div>
                                    </fieldset>
                                </form>

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