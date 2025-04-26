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

$products = array();
$sql = "SELECT * FROM products";
$results = $mysqli -> query($sql);
if($results -> num_rows > 0){
    while($row = $results -> fetch_assoc()){
        $products[] = $row;
    }
}
// calculate total repayment amount
$member_products = array();
$sql = "SELECT a.loan_name, a.loan_status, a.loan_purpose, a.loan_amount, a.loan_duration,
       IFNULL(p.loan_interest, 0) AS interest,
       IFNULL(p.processing_fee, 0) AS processing_fee,
       IFNULL(p.loan_penalty, 0) AS penalty,
       (a.loan_amount + (a.loan_amount * p.loan_interest / 100) + p.processing_fee + IFNULL(p.loan_penalty, 0)) AS total_to_repay
FROM applications a
LEFT JOIN products p ON a.loan_id = p.loan_id
WHERE a.user_id = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $member_products[] = $row;
    }
}

$sql = "SELECT a.user_id, a.loan_name, a.loan_status, a.loan_purpose, a.loan_amount, a.loan_duration,
       IFNULL(p.loan_interest, 0) AS interest,
       IFNULL(p.processing_fee, 0) AS processing_fee,
       IFNULL(p.loan_penalty, 0) AS penalty,
       (a.loan_amount + (a.loan_amount * p.loan_interest / 100) + p.processing_fee + IFNULL(p.loan_penalty, 0)) AS total_to_repay,
       a.application_date
FROM applications a
LEFT JOIN products p ON a.loan_id = p.loan_id
WHERE a.user_id = ? 
ORDER BY application_date DESC";

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

                <?php if ($user_role == 'System Administrator') : ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Products</h5>
                                        <a href="#addproduct" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm"><button type="button" class="btn btn-block btn-primary">Add product</button></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- breakdown table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Loan Name</th>
                                                        <th>Loan Interest</th>
                                                        <th>Loan Duration</th>
                                                        <th>Processing Fee</th>
                                                        <th>Maximum limit</th>
                                                        <th>Loan Penalty</th>
                                                        <th>File</th>
                                                        <th>loan Description</th>
                                                        <th>Manage</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($products)) : ?>
                                                    <?php foreach ($products as $key => $product) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($product['loan_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['loan_interest']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['loan_duration']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['processing_fee']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['maximum_limit']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['loan_penalty']); ?>
                                                        </td>
                                                        <td><?php echo '<a href="' . htmlspecialchars($product['thumbnail']) . 
                                                        '" target="_blank">View file</a>'; ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['loan_description']); ?>
                                                        </td>
                                                        <td class="sorting_1" style="width: 15%;">
                                                            <a data-toggle="modal" href="#updateproduct"
                                                                class="badge badge-primary"
                                                                data-id="<?php echo $product['product_id']; ?>"
                                                                data-name="<?php echo htmlspecialchars($product['loan_name']); ?>"
                                                                data-interest="<?php echo htmlspecialchars($product['loan_interest']); ?>"
                                                                data-duration="<?php echo htmlspecialchars($product['loan_duration']); ?>"
                                                                data-fee="<?php echo htmlspecialchars($product['processing_fee']); ?>"
                                                                data-limit="<?php echo htmlspecialchars($product['maximum_limit']); ?>"
                                                                data-guarantors="<?php echo htmlspecialchars($product['loan_guarantors']); ?>"
                                                                data-savings="<?php echo htmlspecialchars($product['member_savings']); ?>"
                                                                data-penalty="<?php echo htmlspecialchars($product['loan_penalty']); ?>"
                                                                data-description="<?php echo htmlspecialchars($product['loan_description']); ?>"
                                                                onclick="setUpdateModalData(this)">
                                                                <i class="fas fa-edit"></i> Update
                                                            </a>
                                                            <a class="badge badge-danger" 
                                                            data-id="<?php echo htmlspecialchars($product['product_id']);?>" href="#deleteModal"
                                                            data-toggle="modal">
                                                                <i class="fas fa-trash"></i> Delete</a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="9">No records found</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                    <?php require_once('../modals/add_product.php'); ?>
                                                    <?php require_once('../modals/manage_product.php'); ?>
                                                </tbody>
                                            </table>
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
                            <div class="card card-yellow card-outline">
                                <div class="card-header">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-0">
                                        <h5 class="card-title m-0">Loan products</h5>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-12 table-responsive">
                                            <!-- breakdown table -->
                                            <table class="table table-bordered" id="datatable">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10px">No</th>
                                                        <th>Loan Name</th>
                                                        <th>Maximum Amount</th>
                                                        <th>Loan Interest</th>
                                                        <th>Loan Duration (months)</th>
                                                        <th>Guarantors</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($products)) : ?>
                                                    <?php foreach ($products as $key => $product) : ?>
                                                    <tr>
                                                        <td><?php echo ($key + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($product['loan_name']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['maximum_limit']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['loan_interest']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['loan_duration']); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($product['loan_guarantors']); ?>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else : ?>
                                                    <tr>
                                                        <td colspan="9">No records found</td>
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