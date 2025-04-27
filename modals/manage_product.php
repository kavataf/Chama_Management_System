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
        <form class="needs-validation" id="updateProductForm" action="../helpers/manageproduct.php" method="post" novalidate>
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
                    <div class="form-group col-sm-12 col-lg-4">
                        <label for="update-loan-name">Loan Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="loan_name" id="update-loan-name" required pattern="^[A-Za-z\s]+$" title="Loan Name can only contain letters and spaces.">
                        <div class="invalid-feedback">Please enter a valid loan name (letters and spaces only).</div>
                    </div>

                    <div class="form-group col-sm-12 col-lg-4">
                        <label for="update-loan-duration">Loan Duration (Months)<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="loan_duration" id="update-loan-duration" required min="1" max="12">
                        <div class="invalid-feedback">Please enter a valid number between 1 and 12.</div>
                    </div>

                    <div class="form-group col-sm-12 col-lg-4">
                        <label for="update-maximum-limit">Maximum Limit<span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="maximum_limit" id="update-maximum-limit" required min="1">
                        <div class="invalid-feedback">Please enter a valid amount.</div>
                    </div>

                    <div class="form-group col-sm-12 col-lg-4">
                        <label for="update-loan-guarantors">Loan Guarantors<span class="text-danger">*</span></label>
                        <select required name="loan_guarantors" class="form-control" id="update-loan-guarantors">
                            <option value="">Select</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                        </select>
                        <div class="invalid-feedback">Please select an option.</div>
                    </div>

                    <div class="form-group col-sm-12 col-lg-4">
                        <label for="update-loan-description">Loan Description<span class="text-danger">*</span></label>
                        <textarea class="form-control" name="loan_description" id="update-loan-description" required rows="3" pattern="^[A-Za-z0-9\s,.'-]+$" title="Description can only contain letters, numbers, and common punctuation."></textarea>
                        <div class="invalid-feedback">Description can only contain letters, numbers, and common punctuation.</div>
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
(function () {
    'use strict';
    window.addEventListener('load', function () {
        var updateForm = document.getElementById('updateProductForm');

        if (updateForm) {
            updateForm.addEventListener('submit', function (event) {
                if (updateForm.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                updateForm.classList.add('was-validated');
            }, false);
        }
    }, false);
})();
</script>


<script>
    function setUpdateModalData(element) {
        document.getElementById('update-product-id').value = element.getAttribute('data-id');
        document.getElementById('update-loan-name').value = element.getAttribute('data-name');
        document.getElementById('update-loan-duration').value = element.getAttribute('data-duration');
        document.getElementById('update-maximum-limit').value = element.getAttribute('data-limit');
        document.getElementById('update-loan-guarantors').value = element.getAttribute('data-guarantors');
        document.getElementById('update-loan-description').value = element.getAttribute('data-description');

    }
</script>