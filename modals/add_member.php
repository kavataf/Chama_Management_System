<?php
require_once('../config/config.php');
require_once('../partials/head.php');
require_once('../config/codeGen.php');
?>
<div class="modal fade" id="addmember" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Member</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="post" enctype="multipart/form-data">
                    <!-- Personal Information -->
                    <fieldset class="border p-2 border-success">
                        <legend class="w-auto text-success">Personal Information</legend>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Full Name<span class="text-danger">*</span></label>
                                <input type="text" placeholder="enter your fullname" required name="member_name" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">ID No<span class="text-danger">*</span></label>
                                <input type="text" placeholder="123xxxxx" required name="member_id_no"
                                    class="form-control">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="">Gender<span class="text-danger">*</span></label>
                                <select required name="member_gender" class="form-control">
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="">Email<span class="text-danger">*</span></label>
                                <input type="email" placeholder="enter your email" required name="member_email" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="">Phone<span class="text-danger">*</span></label>
                                <input type="phone" placeholder="123xxxxx" required name="member_phone"
                                    class="form-control">
                            </div>

                            
                        </div>
                    </fieldset>
                    <div class="modal-footer">
                        <div class="text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" name="member_details" class="btn btn-outline-primary">
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