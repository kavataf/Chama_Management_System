<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
require_once('../helpers/addexpense.php');
?>
<div class="modal fade" id="addexpense" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" enctype="multipart/form-data">
                    <!-- expense Information -->
                    <fieldset class="border p-2 border-success">
                        <legend class="w-auto text-success">Expense Details</legend>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Vendor<span class="text-danger">*</span></label>
                                <input type="text" placeholder="enter name" required name="vendor_name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Expense type<span class="text-danger">*</span></label>
                                <input type="text" placeholder="123xxxxx" required name="expense_type"
                                    class="form-control">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Ref. Number<span class="text-danger">*</span></label>
                                <input type="text" required name="reference_no" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Expense Amount<span class="text-danger">*</span></label>
                                <input type="text" placeholder="123xxxxx" required name="expense_amount"
                                    class="form-control">
                            </div>
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="expense_details" class="btn btn-outline-primary">
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