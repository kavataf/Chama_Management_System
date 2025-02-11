<?php
require_once('../config/config.php');
require_once('../partials/head.php');

// Fetch data from the database
$sql = "SELECT * FROM trainees c INNER JOIN cic_centres sc 
ON c.trainee_centre_id  = sc.cic_centre_id
-- INNER JOIN ward w ON w.ward_id = c.cic_centre_ward_id;";
$result = $mysqli->query($sql);

$trainees = array();

$sub_counties_result = $mysqli->query("SELECT sub_county_id, sub_county_name FROM sub_county");
$wards_result = $mysqli->query("SELECT ward_id, ward_name FROM ward");
?>
<!-- Update Trainee Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <form action="../helpers/update_trainee.php" method="post">
                <div class="modal-header align-items-center">
                    <div class="text-bold">
                        <h6 class="modal-title text-bold" id="updateModalLabel">Update Trainee</h6>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="trainee_id" id="update-trainee-id">
                    <div class="row">
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-name">Name</label>
                            <input type="text" class="form-control" name="trainee_name" id="update-trainee-name" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-id-no">Registration No.</label>
                            <input type="text" class="form-control" name="trainee_reg_no" id="update-trainee-id-no" readonly required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-phone">Phone</label>
                            <input type="text" class="form-control" name="trainee_phone" id="update-trainee-phone" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-gender">Gender</label>
                            <select required name="trainee_gender" id="update-trainee-gender" class="form-control">
                                <option value="">Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Binary">Other</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-disability">Any disability?</label>
                            <select required name="trainee_disability" id="update-trainee-disability" class="form-control">
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-postal-code">Postal Address</label>
                            <input type="text" class="form-control" name="postal_code" id="update-postal-code" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-home-town">Home Town</label>
                            <input type="text" class="form-control" name="home_town" id="update-home-town" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-sub-county">Sub County</label>
                            <select class="form-control" required name="trainee_sub_county_id" id="update-sub-county">
                                <option value="">Select</option>
                                <?php while ($row = $sub_counties_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['sub_county_id']; ?>">
                                        <?php echo $row['sub_county_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-ward">Ward</label>
                            <select class="form-control" required name="trainee_ward_id" id="update-ward">
                                <option value="">Select</option>
                                <?php while ($row = $wards_result->fetch_assoc()) { ?>
                                    <option value="<?php echo $row['ward_id']; ?>">
                                        <?php echo $row['ward_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-next-of-kin-name">Guardian Name</label>
                            <input type="text" class="form-control" name="trainee_next_of_kin_name" id="update-trainee-next-of-kin-name" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-next-of-kin-phone">Guardian Phone Number</label>
                            <input type="text" class="form-control" name="trainee_next_of_kin_phone" id="update-trainee-next-of-kin-phone" required>
                        </div>
                        <?php if ($user_role == 'Instructor') : ?>
                            <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                                <label for="">Community Information Centre</label>
                                <input type="text" value="<?php echo htmlspecialchars($centre_name); ?>" class="form-control" readonly>
                                <input type="hidden" name="trainee_centre_id" value="<?php echo htmlspecialchars($cic_centre_id); ?>">
                            </div>
                        <?php else : ?>
                            <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                                <label for="update-trainee-centre-id">Community Information Centre</label>
                                <select class="form-control" required name="trainee_centre_id" id="update-trainee-centre-id">
                                    <option value="">Select</option>
                                    <?php
                                    $fetch_sql = mysqli_query($mysqli, "SELECT * FROM cic_centres");
                                    if (mysqli_num_rows($fetch_sql) > 0) {
                                        while ($cics = mysqli_fetch_array($fetch_sql)) {
                                    ?>
                                            <option value="<?php echo $cics['cic_centre_id']; ?>">
                                                <?php echo $cics['cic_centre_name']; ?>
                                            </option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-date-of-admission">Date of Admission</label>
                            <input type="date" class="form-control" name="trainee_date_of_admission" id="update-trainee-date-of-admission" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-date-of-completion">Expected Completion Date</label>
                            <input type="date" class="form-control" name="trainee_date_of_completion" id="update-trainee-date-of-completion" required>
                        </div>
                        <div class="form-group col-sm-12 col-lg-4 col-xl-4">
                            <label for="update-trainee-certificate-fee">Certificate fee</label>
                            <input type="number" class="form-control" name="trainee_certificate_fee" id="update-trainee-certificate-fee" required>
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



<script>
    function setUpdateModalData(element) {
        document.getElementById('update-trainee-id').value = element.getAttribute('data-id');
        document.getElementById('update-trainee-name').value = element.getAttribute('data-name');
        document.getElementById('update-trainee-id-no').value = element.getAttribute('data-id-no');
        document.getElementById('update-trainee-phone').value = element.getAttribute('data-phone');
        document.getElementById('update-trainee-gender').value = element.getAttribute('data-gender');
        document.getElementById('update-trainee-disability').value = element.getAttribute('data-disability');
        document.getElementById('update-postal-code').value = element.getAttribute('data-postal-code');
        document.getElementById('update-home-town').value = element.getAttribute('data-home-town');
        document.getElementById('update-sub-county').value = element.getAttribute('data-sub-county');
        document.getElementById('update-ward').value = element.getAttribute('data-ward');
        document.getElementById('update-trainee-next-of-kin-name').value = element.getAttribute('data-next-of-kin-name');
        document.getElementById('update-trainee-next-of-kin-phone').value = element.getAttribute('data-next-of-kin-phone');
        document.getElementById('update-trainee-date-of-admission').value = element.getAttribute('data-date-of-admission');
        document.getElementById('update-trainee-date-of-completion').value = element.getAttribute(
            'data-date-of-completion');
        document.getElementById('update-trainee-certificate-fee').value = element.getAttribute('data-certificate-fee');

    }
</script>