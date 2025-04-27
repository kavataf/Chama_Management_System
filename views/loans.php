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

// Fetch loan details
$sql = "SELECT a.user_id, a.loan_name, a.loan_status, a.loan_purpose, a.loan_amount, a.loan_duration, a.total_payable,
       a.application_date
FROM applications a
WHERE a.user_id = ? 
ORDER BY a.application_date DESC";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$loans = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $loans[] = $row;
    }
}

// Fetch loan products
$products = [];
$result = $mysqli->query("SELECT product_id, loan_name, loan_duration, loan_interest, maximum_limit, loan_description 
FROM products");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Fetch repayments and calculate total repayment for each loan
$sql = "SELECT loan_name, SUM(amount_paid) AS total_repayment, repayment_date 
FROM repayments WHERE user_id = ? GROUP BY loan_name";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$repayments = array();
while ($row = $result->fetch_assoc()) {
    $repayments[] = $row;
}



?>

<body id="page-top">

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

                <!-- Members Dashboard -->
                <?php if ($user_role == 'Member') : ?>
                    <!-- Main content -->
                    <div class="content">
                            <div class="row mb-4">
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanapplication')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">Loan Application</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanstatus')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">Loan Status</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanrepayment')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">Loan Repayment</span>
                                    </a>
                                </div>
                            </div>

                        <div class="row">
                            <div class="col-xl-12">
                            <div id="loanapplication" class="content-section">
                            <h3>Enter details for loan application</h3>
                            <!-- Loan Application Form -->
                            <form class="needs-validation" method="post" enctype="multipart/form-data">
                                <fieldset class="border p-2 border-success">
                                    <legend class="w-auto text-success">Loan / Personal Details</legend>
                                    <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="">Loan Name<span class="text-danger">*</span></label>
                                        <div class="d-flex align-items-center">
                                            <select required name="loan_name" id="loan_name" class="form-control" onchange="fetchLoanDetails()">
                                                <option value="">-- Select Loan Product --</option>
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?php echo htmlspecialchars($product['loan_name']); ?>">
                                                        <?php echo htmlspecialchars($product['loan_name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <div id="loadingSpinner" style="display: none; margin-left: 10px;">
                                                <div class="spinner-border text-primary spinner-border-sm" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <div class="form-group col-md-6">
                                            <label for="">Loan Duration<span class="text-danger">*</span></label>
                                            <input type="text" placeholder="" required name="loan_duration" id="loan_duration" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <!-- Loan Amount (editable) -->
                                        <div class="form-group col-md-6">
                                            <label for="loan_amount">Loan Amount (Months)<span class="text-danger">*</span></label>
                                            <input type="number" required name="loan_amount" id="loan_amount" class="form-control">
                                        </div>

                                        <!-- Interest Rate (readonly) -->
                                        <div class="form-group col-md-6">
                                            <label for="loan_interest">Interest Rate (%)<span class="text-danger">*</span></label>
                                            <input type="text" name="loan_interest" id="loan_interest" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="">Application Date<span class="text-danger">*</span></label>
                                            <input type="date" placeholder="" required name="application_date"
                                                class="form-control">
                                        </div>
                        
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label for="loan_purpose">Loan Purpose<span class="text-danger">*</span></label>
                                        <textarea required name="loan_purpose" id="loan_purpose" class="form-control"></textarea>
                                    </div>
                                </fieldset>
                                <div class="modal-footer">
                                    <div class="text-right">
                                        <button type="submit" name="apply_loan" class="btn btn-outline-primary">
                                            <i class="fas fa-save"></i> Apply
                                        </button>
                                    </div>
                                </div>


                            </form>

                            <script>
                                function fetchLoanDetails() {
                                    const loanSelect = document.getElementById('loan_name');
                                    const selectedLoan = loanSelect.value;
                                    const spinner = document.getElementById('loadingSpinner');

                                    if (!selectedLoan) {
                                        clearLoanFields();
                                        return;
                                    }

                                    spinner.style.display = 'inline-block'; // SHOW spinner

                                    fetch(`get_loan_details.php?loan_name=${encodeURIComponent(selectedLoan)}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            spinner.style.display = 'none'; // HIDE spinner

                                            if (data.status === 'success') {
                                                const loan = data.data;

                                                document.getElementById('loan_duration').value = loan.loan_duration || '';
                                                document.getElementById('loan_interest').value = loan.loan_interest || '';
                                                document.getElementById('loan_amount').value = loan.maximum_limit || '';
                                                document.getElementById('loan_purpose').value = loan.loan_description || '';
                                            } else {
                                                alert('Loan details could not be fetched.');
                                                clearLoanFields();
                                            }
                                        })
                                        .catch(error => {
                                            spinner.style.display = 'none'; // HIDE spinner
                                            console.error('Error fetching loan details:', error);
                                            alert('Something went wrong. Try again.');
                                            clearLoanFields();
                                        });
                                }

                                function clearLoanFields() {
                                    document.getElementById('loan_duration').value = '';
                                    document.getElementById('loan_interest').value = '';
                                    document.getElementById('loan_amount').value = '';
                                    document.getElementById('loan_purpose').value = '';
                                }
                            </script>




                            <?php endif; ?>
                            <?php require_once('../helpers/applications.php');?>
                            </div>
                            </div>
                        </div>

                            <!-- loan status -->
                            <div class="row">
                            <div class="col-xl-12">
                            <div id="loanstatus" class="content-section" style="display: none;">
                            <h3>My loan status.</h3>
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Loan ID</th>
                                            <th>Loan Name</th>
                                            <th>Amount</th>
                                            <th>Amount to repay</th>
                                            <th>Status</th>
                                            <th>Application Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php if (!empty($loans)) : ?>
                                        <?php foreach ($loans as $loan) : ?>
                                            <tr>
                                                <td><?php echo $loan['user_id']; ?></td>
                                                <td><?php echo $loan['loan_name']; ?></td>
                                                <td><?php echo number_format($loan['loan_amount'], 2); ?></td>
                                                <td><?php echo number_format($loan['total_payable'], 2);?></td>
                                                <td><?php echo $loan['loan_status']; ?></td>
                                                <td><?php echo date("d M Y", strtotime($loan['application_date'])); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <tr><td colspan='6'>No loan applications found</td></tr>
                                    <?php endif; ?>
                                    </tbody>
                                </table>

                            </div>
                            </div>
                            </div>
                            <!-- loan repayment -->
                            <div class="row">
                            <div class="col-xl-12">
                            <div id="loanrepayment" class="content-section" style="display: none;">
                            <h3>Repayment History.</h3>
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Loan Name</th>
                                        <th>Amount Paid</th>
                                        <th>Payment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($repayments)) : ?>
                                <?php foreach ($repayments as $repayment) : ?>
                                <tr>
                                    <td><?php echo $repayment['loan_name']; ?></td>
                                    <td><?php echo number_format($repayment['total_repayment'], 2); ?></td>
                                    <td><?php echo date("d M Y", strtotime($repayment['repayment_date'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else : ?>
                                    <tr><td colspan='5'>No loan repayments found</td></tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            </div>
                            </div>
                            </div>
                        </div>
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