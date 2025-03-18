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
                                <label for="loan_id">Loan ID<span class="text-danger">*</span></label>
                                <input type="text" name="loan_id" required class="form-control" placeholder="Enter Loan ID">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="loan_name">Loan Name<span class="text-danger">*</span></label>
                                <input type="text" placeholder="Enter Loan Name" required name="loan_name" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="loan_interest">Loan Interest (%)<span class="text-danger">*</span></label>
                                <input type="number" step="0.01" required name="loan_interest" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="loan_duration">Loan Duration (Months)<span class="text-danger">*</span></label>
                                <input type="number" required name="loan_duration" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="processing_fee">Processing Fee<span class="text-danger">*</span></label>
                                <input type="number" required name="processing_fee" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="maximum_limit">Maximum Limit<span class="text-danger">*</span></label>
                                <input type="number" required name="maximum_limit" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="loan_guarantors">Loan Guarantors<span class="text-danger">*</span></label>
                                <select required name="loan_guarantors" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="member_savings">Member Savings<span class="text-danger">*</span></label>
                                <input type="number" required name="member_savings" class="form-control">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="thumbnail">Thumbnail <span class="text-danger">*</span></label>
                                <input type="file" name="thumbnail" class="form-control" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="loan_penalty">Loan Penalty<span class="text-danger">*</span></label>
                                <input type="number" required name="loan_penalty" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="loan_description">Loan Description<span class="text-danger">*</span></label>
                            <textarea name="loan_description" required class="form-control" rows="3"></textarea>
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