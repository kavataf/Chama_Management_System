<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
require_once('../helpers/allqueries.php');

// Fetch data from the database
$sql = "SELECT * FROM shares";
$result = $mysqli->query($sql);

$shares = array();
?>
<!-- Update share Modal -->
<div class="modal fade" id="updateshare" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <form action="../helpers/allqueries.php" method="post">
                <div class="modal-header align-items-center">
                    <div class="text-bold">
                        <h6 class="modal-title text-bold" id="updateModalLabel">Update share</h6>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="update-share-id">
                    <div class="row">
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-name">Member Name</label>
                            <input type="text" class="form-control" name="member_name" id="update-member-name" required>
                        </div>
                        
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-share-amount">Share Amount</label>
                            <input type="number" class="form-control" name="share_amount" id="update-share-amount" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-purchase-date">Purchase Date</label>
                            <input type="date" class="form-control" name="purchase_date" id="update-purchase-date" required>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_share">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

 <!-- Delete Modal -->
 <div class="modal fade" id="deleteshare" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete share</h5>
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
    function setUpdateModalData2(element) {
        document.getElementById('update-share-id').value = element.getAttribute('data-id');
        document.getElementById('update-member-name').value = element.getAttribute('data-name');
        document.getElementById('update-share-amount').value = element.getAttribute('data-ref');
        document.getElementById('update-purchase-date').value = element.getAttribute('data-amount');

    }
</script>