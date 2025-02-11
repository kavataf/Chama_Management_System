<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
require_once('../helpers/allqueries.php');

// Fetch data from the database
$sql = "SELECT * FROM savings";
$result = $mysqli->query($sql);

$savings = array();
?>
<!-- Update saving Modal -->
<div class="modal fade" id="updatesaving" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <form action="../helpers/allqueries.php" method="post">
                <div class="modal-header align-items-center">
                    <div class="text-bold">
                        <h6 class="modal-title text-bold" id="updateModalLabel">Update saving</h6>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="savings_id" id="update-saving-id">
                    <div class="row">
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-name">Member Name</label>
                            <input type="text" class="form-control" name="member_name" id="update-member-name" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-id-no">ID No</label>
                            <input type="text" class="form-control" name="member_id_no" id="update-member-id-no" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-phone">Contact</label>
                            <input type="text" class="form-control" name="member_phone" id="update-member-phone" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-reference-no">Ref. No</label>
                            <input type="text" class="form-control" name="reference_no" id="update-reference-no" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-amount">Amount</label>
                            <input type="amount" class="form-control" name="amount" id="update-amount" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-savings-date">Date</label>
                            <input type="date" class="form-control" name="savings_date" id="update-savings-date" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-payment-method">Payment Method</label>
                            <select required name="payment_method" id="update-payment-method" class="form-control">
                                <option value="">Select</option>
                                <option value="Mpesa">Mpesa</option>
                                <option value="Bank">Bank</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_saving">Save changes</button>
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
                <h5 class="modal-title">Delete saving</h5>
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
        document.getElementById('update-saving-id').value = element.getAttribute('data-id');
        document.getElementById('update-member-name').value = element.getAttribute('data-name');
        document.getElementById('update-member-id-no').value = element.getAttribute('data-no');
        document.getElementById('update-member-phone').value = element.getAttribute('data-phone');
        document.getElementById('update-reference-no').value = element.getAttribute('data-ref');
        document.getElementById('update-amount').value = element.getAttribute('data-amount');
        document.getElementById('update-savings-date').value = element.getAttribute('data-date');
        document.getElementById('update-payment-method').value = element.getAttribute('data-method');

    }
</script>