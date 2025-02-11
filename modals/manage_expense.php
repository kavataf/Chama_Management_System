<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');

// Fetch data from the database
$sql = "SELECT * FROM expenses";
$result = $mysqli->query($sql);

$expenses = array();
?>
<!-- Update expense Modal -->
<div class="modal fade" id="updateexpense" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <form action="../helpers/allqueries.php" method="post">
                <div class="modal-header align-items-center">
                    <div class="text-bold">
                        <h6 class="modal-title text-bold" id="updateModalLabel">Update expense</h6>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="expense_id" id="update-expense-id">
                    <div class="row">
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-vendor">Vendor</label>
                            <input type="text" class="form-control" name="vendor_name" id="update-vendor" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-expense-type">Expense type</label>
                            <input type="text" class="form-control" name="expense_type" id="update-expense-type" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-reference-no">Ref. Number</label>
                            <input type="text" class="form-control" name="reference_no" id="update-reference-no" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-expense-amount">Expense Amount</label>
                            <input type="text" class="form-control" name="expense_amount" id="update-expense-amount" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_expense">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


 <!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-danger">
                <p>Cannot delete this item.</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>



<script>
    function setUpdateModalData(element) {
        document.getElementById('update-expense-id').value = element.getAttribute('data-id');
        document.getElementById('update-vendor').value = element.getAttribute('data-name');
        document.getElementById('update-expense-type').value = element.getAttribute('data-type');
        document.getElementById('update-reference-no').value = element.getAttribute('data-ref');
        document.getElementById('update-expense-amount').value = element.getAttribute('data-amount');

    }
</script>