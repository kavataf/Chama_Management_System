<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
require_once('../helpers/manageproduct.php');

// Fetch data from the database
$sql = "SELECT * FROM products";
$result = $mysqli->query($sql);

$products = array();
?>
<!-- Update product Modal -->
<div class="modal fade" id="updateproduct" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <form action="../helpers/manageproduct.php" method="post">
                <div class="modal-header align-items-center">
                    <div class="text-bold">
                        <h6 class="modal-title text-bold" id="updateModalLabel">Update Product</h6>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="update-product-id">
                    <div class="row">
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-loan-name">Loan Name</label>
                            <input type="text" class="form-control" name="loan_name" id="update-loan-name" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-loan-interest">Loan Interest</label>
                            <input type="number" class="form-control" name="loan_interest" id="update-loan-interest" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-loan-duration">Loan Duration</label>
                            <input type="text" class="form-control" name="loan_duration" id="update-loan-duration" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-processing-fee">Processing Fee</label>
                            <input type="number" class="form-control" name="processing_fee" id="update-processing-fee" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-maximum-limit">Maximum Limit</label>
                            <input type="number" class="form-control" name="maximum_limit" id="update-maximum-limit" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-loan-guarantors">Loan Guarantors</label>
                                <select required name="loan_guarantors" class="form-control" id="update-loan-guarantors">
                                    <option value="">Select</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-savings">Member Savings</label>
                            <input type="number" class="form-control" name="member_savings" id="update-member-savings" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-loan-penalty">Loan Penalty</label>
                            <input type="number" class="form-control" name="loan_penalty" id="update-loan-penalty" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-loan-description">Loan Description</label>
                            <input type="text" class="form-control" name="loan_description" id="update-loan-description" required>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
                <h5 class="modal-title">Delete Product</h5>
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
        document.getElementById('update-product-id').value = element.getAttribute('data-id');
        document.getElementById('update-loan-name').value = element.getAttribute('data-name');
        document.getElementById('update-loan-interest').value = element.getAttribute('data-interest');
        document.getElementById('update-loan-duration').value = element.getAttribute('data-duration');
        document.getElementById('update-processing-fee').value = element.getAttribute('data-fee');
        document.getElementById('update-maximum-limit').value = element.getAttribute('data-limit');
        document.getElementById('update-loan-guarantors').value = element.getAttribute('data-guarantors');
        document.getElementById('update-member-savings').value = element.getAttribute('data-savings');
        document.getElementById('update-loan-penalty').value = element.getAttribute('data-penalty');
        document.getElementById('update-loan-description').value = element.getAttribute('data-description');

    }
</script>