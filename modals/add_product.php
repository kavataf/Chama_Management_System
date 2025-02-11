<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
require_once('../helpers/addproduct.php');
?>
<div class="modal fade" id="addproduct" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" enctype="multipart/form-data">
                    <!-- product Information -->
                    <fieldset class="border p-2 border-success">
                        <legend class="w-auto text-success">Product Details</legend>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Loan Name<span class="text-danger">*</span></label>
                                <input type="text" placeholder="enter loan" required name="loan_name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Loan Interest<span class="text-danger">*</span></label>
                                <input type="text" placeholder="" required name="loan_interest"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Loan Duration<span class="text-danger">*</span></label>
                                <input type="text" required name="loan_duration" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Processing Fee<span class="text-danger">*</span></label>
                                <input type="text" placeholder="" required name="processing_fee"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Maximum Limit<span class="text-danger">*</span></label>
                                <input type="text" required name="maximum_limit" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Loan Guarantors<span class="text-danger">*</span></label>
                                <select required name="loan_guarantors" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Member Savings<span class="text-danger">*</span></label>
                                <input type="text" required name="member_savings" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="thumbnail">Thumbnail <span class="text-danger">*</span></label>
                                <input type="file" name="thumbnail" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Loan Penalty<span class="text-danger">*</span></label>
                                <input type="text" required name="loan_penalty" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Loan Description<span class="text-danger">*</span></label>
                                <input type="text" placeholder="" required name="loan_description"
                                    class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="product_details" class="btn btn-outline-primary">
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