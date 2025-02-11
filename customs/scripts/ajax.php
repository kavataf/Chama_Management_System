<script>
function showError(message) {
    $('#trainee_reg_no').addClass('error-input');
    $('#trainee_reg_no_error').text(message).show();
    $('#saveButton').prop('disabled', true);
}

function clearError() {
    $('#trainee_reg_no').removeClass('error-input');
    $('#trainee_reg_no_error').text('').hide();
    checkFormValidity();
}

function checkFormValidity() {
    if ($('#trainee_id').val() && $('#trainee_name').val() && $('#transaction_centre_id').val()) {
        $('#saveButton').prop('disabled', false);
    } else {
        $('#saveButton').prop('disabled', true);
    }
}

$(document).ready(function() {
    $('#trainee_reg_no').on('blur', function() {
        var regNo = $(this).val();
        $('#loadingSpinner').show();

        $.ajax({
            type: 'POST',
            url: '../helpers/dynamic_data.php',
            data: {
                trainee_reg_no: regNo,
                action: 'fetch_trainee'
            },
            success: function(response) {
                var data = JSON.parse(response);
                setTimeout(function() {
                    $('#loadingSpinner').hide();
                    if (data.status === 'success') {
                        clearError();
                        $('#trainee_id').val(data.trainee_id);
                        $('#transaction_centre_id').val(data.trainee_centre_id);
                        $('#trainee_name').val(data.trainee_name);
                        $('#trainee_id_container').show();
                        $('#transaction_centre_id_container').show();
                        checkFormValidity();
                    } else {
                        showError(data.message);
                        $('#trainee_id').val('');
                        $('#trainee_name').val('');
                        $('#transaction_centre_id').val('');
                        $('#trainee_id_container').hide();
                        $('#transaction_centre_id_container').hide();
                    }
                }, 1000); // 2-second delay for the loading spinner
            },
            error: function() {
                $('#loadingSpinner').hide();
                showError('Error occurred while fetching trainee details.');
            }
        });
    });

    // Additional event listeners to validate form fields when they change
    $('#trainee_id, #trainee_name, #transaction_centre_id').on('input', checkFormValidity);
});
</script>

<script>
$(document).ready(function() {
    $('#sub_county').change(function() {
        var sub_county_id = $(this).val();

        if (sub_county_id) {
            $.ajax({
                type: 'GET',
                url: '../helpers/dynamic_data.php',
                data: {
                    sub_county_id: sub_county_id
                },
                dataType: 'json',
                success: function(response) {
                    $('#ward').empty(); // Clear existing options
                    $('#ward').append(
                        '<option value="">Select</option>'); // Add default option
                    $.each(response, function(index, ward) {
                        $('#ward').append('<option value="' + ward.ward_id + '">' +
                            ward.ward_name + '</option>'); // Add each ward option
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Failed to fetch wards:', error); // Log any errors
                }
            });
        } else {
            $('#ward').empty(); // If no sub_county selected, empty the ward options
            $('#ward').append('<option value="">Select</option>'); // Add default option
        }
    });
});
</script>