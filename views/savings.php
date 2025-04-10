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
$sql = "SELECT s.savings_id, u.user_name, s.reference_no, s.amount, s.savings_date, s.payment_method 
          FROM savings s
          JOIN users u ON s.user_id = u.user_id
          ORDER BY s.savings_date DESC";
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
// Fetch member's savings
$savings_query = "SELECT SUM(amount) AS total_savings FROM savings WHERE user_id = ?";
$stmt = $mysqli->prepare($savings_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($total_savings);
$stmt->fetch();
$stmt->close();

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
                                                        <th>Ref. No</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Payment Method</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($savings)) : ?>
                                                    <?php foreach ($savings as $key => $saving) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($saving['user_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['reference_no']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['amount']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['savings_date']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($saving['payment_method']); ?>
                                                        </td>
                                                        <td class="sorting_1" style="width: 15%;">
                                                            <a data-toggle="modal" href="#updatesaving"
                                                                class="badge badge-primary"
                                                                data-id="<?php echo $saving['savings_id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($saving['user_name']); ?>"
                                                                data-ref="<?php echo htmlspecialchars($saving['reference_no']); ?>"
                                                                data-amount="<?php echo htmlspecialchars($saving['amount']); ?>"
                                                                data-date="<?php echo htmlspecialchars($saving['savings_date']); ?>"
                                                                data-method="<?php echo htmlspecialchars($saving['payment_method']); ?>"
                                                                onclick="setUpdateModalData(this)">
                                                                <i class="fas fa-edit"></i> Update
                                                            </a>
                                                            <a class="badge badge-danger" 
                                                            data-id="<?php echo htmlspecialchars($saving['savings_id']);?>" href="#deleteModal"
                                                            data-toggle="modal">
                                                                <i class="fas fa-trash"></i> Delete</a>
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
                         <div id="loanstatus" class="content-section">
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
                                                        <td class="sorting_1" style="width: 15%;">
                                                            <a data-toggle="modal" href="#updateshare"
                                                                class="badge badge-primary"
                                                                data-id="<?php echo $share['id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($share['member_name']); ?>"
                                                                data-ref="<?php echo htmlspecialchars($share['share_amount']); ?>"
                                                                data-amount="<?php echo htmlspecialchars($share['purchase_date']); ?>"
                                                                onclick="setUpdateModalData2(this)">
                                                                <i class="fas fa-edit"></i> Update
                                                            </a>
                                                            <a class="badge badge-danger" 
                                                            data-id="<?php echo htmlspecialchars($share['id']);?>" href="#deleteshare"
                                                            data-toggle="modal">
                                                                <i class="fas fa-trash"></i> Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="8">No records found</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php require_once('../modals/add_shares.php'); ?>
                                                    <?php require_once('../modals/manage_shares.php'); ?>
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
                            <div class="row mb-4">
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanapplication')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                        </span>
                                        <span class="text">View Savings</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanstatus')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">Purchase Shares</span>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-4 mb-4">
                                    <a href="#" class="btn btn-primary btn-icon-split"
                                        onclick="showSection('loanrepayment')">
                                        <span class="icon text-white-50">
                                            <i class="fa fa-eye"></i>
                                        </span>
                                        <span class="text">View Dividends</span>
                                    </a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12">
                                <div id="loanapplication" class="content-section">
                                    <h3>Savings</h3>
                                        <table class="table table-bordered" id="datatable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10px">No</th>
                                                            <th>Member Name</th>
                                                            <th>Ref. No</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>
                                                            <th>Payment Method</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($member_savings)) : ?>
                                                        <?php foreach ($member_savings as $key => $member_saving) : ?>
                                                        <tr>
                                                            <td><?php echo ($key + 1); ?></td>
                                                            <td><?php echo htmlspecialchars($member_saving['user_name']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['reference_no']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['amount']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['savings_date']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['payment_method']); ?>
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
                                <?php require_once('../helpers/addsavings.php');?>
                                </div>
                                </div>
                            </div>

                            <!-- loan status -->
                            <div class="row">
                            <div class="col-xl-12">
                            <div id="loanstatus" class="content-section">
                            <h2>Purchase Shares</h2>
                            <form class="needs-validation" method="post" enctype="multipart/form-data" action="../helpers/addsavings.php">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="share_amount">Share Amount:<span class="text-danger">*</span></label>
                                            <input type="number" placeholder="" id="share_amount" required name="share_amount" class="form-control">
                                        </div>
                                    </div>
                                    
                                <div class="modal-footer">
                                    <div class="text-right">
                                        <button type="submit" value="Purchase Shares" name="purchase_shares" class="btn btn-outline-primary">
                                            <i class="fas fa-save"></i> Pay
                                        </button>
                                    </div>
                                </div>

                                <?php require_once('../helpers/addsavings.php');?>
                            </form>
                            </div>
                            </div>
                            </div>
                            <!-- loan repayment -->
                            <div class="row">
                            <div class="col-xl-12">
                            <div id="loanrepayment" class="content-section">
                                <h3>View Dividends</h3>
                                <table class="table table-bordered" id="datatable">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10px">No</th>
                                                            <th>Member Name</th>
                                                            <th>Ref. No</th>
                                                            <th>Amount</th>
                                                            <th>Date</th>
                                                            <th>Payment Method</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (!empty($member_savings)) : ?>
                                                        <?php foreach ($member_savings as $key => $member_saving) : ?>
                                                        <tr>
                                                            <td><?php echo ($key + 1); ?></td>
                                                            <td><?php echo htmlspecialchars($member_saving['user_name']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['reference_no']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['amount']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['savings_date']); ?>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($member_saving['payment_method']); ?>
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