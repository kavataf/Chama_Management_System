<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
require_once('../helpers/addrepayment.php');

// Fetch users from the database
$query = "SELECT user_id, user_name FROM users WHERE user_access_level = 'member' ORDER BY user_name ASC";
$result = $mysqli->query($query);
?>
<div class="modal fade" id="addrepayment" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add loan repayment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" enctype="multipart/form-data">
                    <!-- Personal Information -->
                    <fieldset class="border p-2 border-success">
                        <legend class="w-auto text-success">loan Details</legend>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="user_id">Select Member:<span class="text-danger">*</span></label>
                                    <select required name="user_id" class="form-control">
                                        <option value="">-- Select Member --</option>
                                        <?php while ($row = $result->fetch_assoc()) : ?>
                                        <option value="<?= $row['user_id']; ?>"><?= $row['user_name']; ?></option>
                                    <?php endwhile; ?>
                                    </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Loan Name<span class="text-danger">*</span></label>
                                <input type="text" required name="loan_name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Loan Amount<span class="text-danger">*</span></label>
                                <input type="amount" placeholder="123xxxxx" required name="loan_amount"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Loan Interest<span class="text-danger">*</span></label>
                                <input type="amount" required name="loan_interest"
                                    class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Processing Fee<span class="text-danger">*</span></label>
                                <input type="amount" placeholder="123xxxxx" required name="processing_fee"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Amount Paid<span class="text-danger">*</span></label>
                                <input type="amount" placeholder="123xxxxx" required name="amount_paid"
                                    class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Date<span class="text-danger">*</span></label>
                                <input type="date" placeholder="" required name="repayment_date"
                                    class="form-control">
                            </div>
                        </div> 
                    </fieldset>
                    <div class="modal-footer">
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="loan_details" class="btn btn-outline-primary">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once('../partials/scripts.php');
    require_once('../customs/scripts/functions.php');
    require_once('../customs/scripts/ajax.php'); ?>