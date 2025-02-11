<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
require_once('../helpers/addrepayment.php');
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
                                <label for="">Member Name<span class="text-danger">*</span></label>
                                <input type="text" placeholder="enter name" required name="member_name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">ID No<span class="text-danger">*</span></label>
                                <input type="text" placeholder="123xxxxx" required name="member_id_no"
                                    class="form-control">
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