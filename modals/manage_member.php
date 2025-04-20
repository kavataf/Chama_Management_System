<?php
require_once('../config/config.php');
require_once('../partials/head.php');

// Fetch data from the database
$sql = "SELECT * FROM members";
$result = $mysqli->query($sql);

$members = array();
?>
<!-- Update member Modal -->
<div class="modal fade" id="updateMember" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <form action="../helpers/managemember.php" method="post">
                <div class="modal-header align-items-center">
                    <div class="text-bold">
                        <h6 class="modal-title text-bold" id="updateModalLabel">Update Member</h6>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="update-member-id">
                    <div class="row">
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-name">Name</label>
                            <input type="text" class="form-control" name="member_name" id="update-member-name" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-id-no">ID No.</label>
                            <input type="text" class="form-control" name="member_id_no" id="update-member-id-no" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-phone">Phone</label>
                            <input type="phone" class="form-control" name="member_phone" id="update-member-phone" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-gender">Gender</label>
                            <select required name="member_gender" id="update-member-gender" class="form-control">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-member-email">Email</label>
                            <input type="email" class="form-control" name="member_email" id="update-member-email" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" name="update_member">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-danger">
                <p>Cannot delete member.</p>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>



<script>
    function setUpdateModalData(element) {
        document.getElementById('update-member-id').value = element.getAttribute('data-user-id');
        document.getElementById('update-member-name').value = element.getAttribute('data-name');
        document.getElementById('update-member-id-no').value = element.getAttribute('data-id-no');
        document.getElementById('update-member-phone').value = element.getAttribute('data-phone');
        document.getElementById('update-member-gender').value = element.getAttribute('data-gender');
        document.getElementById('update-member-email').value = element.getAttribute('data-email');

    }
</script>