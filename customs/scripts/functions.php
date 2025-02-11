<!-- toggle password script -->
<style>
.input-group-text {
    cursor: pointer;
}
</style>
<script>
function togglePasswordVisibility() {
    const passwordField = document.getElementById('password', '');
    const toggleIcon = document.getElementById('togglePassword');

    const isPasswordVisible = passwordField.type === 'text';

    if (isPasswordVisible) {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Set initial state of the icon
document.getElementById('togglePassword').classList.add('fa-eye-slash');
</script>




<!-- calculate balance script -->
<script>
function calculateBalance() {
    // Get the initial payment input value
    let initialPayment = document.querySelector('input[name="trainee_fee_paid"]').value;
    // Convert to a number and handle invalid input
    let certificateFee = document.querySelector('input[name="trainee_certificate_fee"]').value;
    initialPayment = parseFloat(initialPayment);
    certificateFee = parseFloat(certificateFee);
    if (isNaN(initialPayment)) {
        initialPayment = 0;
    }
    // Calculate the balance
    const balance = certificateFee - initialPayment;
    // Set the balance field value
    document.querySelector('input[name="trainee_balance"]').value = balance.toFixed(2);
}
</script>




<script>
document.getElementById('asset_category').addEventListener('change', function() {
    var selectedValue = this.value;
    var name = document.getElementById('name');
    var quantity = document.getElementById('quantity');
    var sn = document.getElementById('sn');
    var type = document.getElementById('type');
    var aquisitionValue = document.getElementById('aquisitionValue');
    var status = document.getElementById('status');
    var makeModel = document.getElementById('make-model');
    var size = document.getElementById('size');
    var ownership = document.getElementById('ownership');

    // Hide all form fields initially
    name.style.display = 'none';
    quantity.style.display = 'none';
    sn.style.display = 'none';
    type.style.display = 'none';
    aquisitionValue.style.display = 'none';
    status.style.display = 'none';
    makeModel.style.display = 'none';
    size.style.display = 'none';
    ownership.style.display = 'none';

    // Show specific form fields based on the selected category
    if (selectedValue === 'Furnitures') {
        sn.style.display = 'block';
        name.style.display = 'block';
        type.style.display = 'block';
        quantity.style.display = 'block';
        aquisitionValue.style.display = 'block';
        status.style.display = 'block';
    } else if (selectedValue === 'Equipment') {
        sn.style.display = 'block';
        name.style.display = 'block';
        quantity.style.display = 'block';
        aquisitionValue.style.display = 'block';
        status.style.display = 'block';
        makeModel.style.display = 'block';
    } else if (selectedValue === 'Study Rooms') {
        size.style.display = 'block';
        ownership.style.display = 'block';
        status.style.display = 'block';
    } else if (selectedValue === 'Teaching Aids') {
        sn.style.display = 'block';
        name.style.display = 'block';
        aquisitionValue.style.display = 'block';
        status.style.display = 'block';
    } else if (selectedValue === '') {
        sn.style.display = 'none';
        name.style.display = 'none';
        type.style.display = 'none';
        quantity.style.display = 'none';
        aquisitionValue.style.display = 'none';
        status.style.display = 'none';
        makeModel.style.display = 'none';
        size.style.display = 'none';
        ownership.style.display = 'none';
    } else {

        sn.style.display = 'block';
        name.style.display = 'block';
        type.style.display = 'block';
        quantity.style.display = 'block';
        aquisitionValue.style.display = 'block';
        status.style.display = 'block';
        makeModel.style.display = 'block';
        size.style.display = 'block';
        ownership.style.display = 'block';
    }
});
</script>

<script>
function showSection(sectionId) {
    var sections = document.querySelectorAll('.content-section');
    sections.forEach(function(section) {
        section.style.display = 'none';
    });
    document.getElementById(sectionId).style.display = 'block';
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('user_email');
    const phoneInput = document.getElementById('user_phone');
    const form = emailInput.closest('form');

    form.addEventListener('submit', function(event) {
        let isValid = true;

        if (!validateEmail(emailInput.value)) {
            isValid = false;
            emailInput.classList.add('is-invalid');
        } else {
            emailInput.classList.remove('is-invalid');
        }

        if (!validatePhoneNumber(phoneInput.value)) {
            isValid = false;
            phoneInput.classList.add('is-invalid');
        } else {
            phoneInput.classList.remove('is-invalid');
        }

        if (!isValid) {
            event.preventDefault();
        }
    });

    emailInput.addEventListener('input', function() {
        if (validateEmail(emailInput.value)) {
            emailInput.classList.remove('is-invalid');
        }
    });

    phoneInput.addEventListener('input', function() {
        if (validatePhoneNumber(phoneInput.value)) {
            phoneInput.classList.remove('is-invalid');
        }
    });

    function validateEmail(email) {
        const emailRegex = /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/;
        return emailRegex.test(email);
    }

    function validatePhoneNumber(phone) {
        const phoneRegex = /^(0|\+254)[0-9]{9}$/;
        return phoneRegex.test(phone);
    }
});
</script>

<!-- config date fields -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    var dateInput = document.getElementById('trainee_date_of_completion');
    var today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('max', today);

    var traineeStatus = document.getElementById('trainee_certificate_status');
    if (traineeStatus) {
        traineeStatus.setAttribute('max', today);
    }
});
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
    var dateInput = document.getElementById('date_of_completion');

    // Get today's date
    var today = new Date();

    // Calculate one month from today
    var nextMonth = new Date(today.setMonth(today.getMonth() + 1));

    // Format the date as YYYY-MM-DD
    var minDate = nextMonth.toISOString().split('T')[0];

    // Set the min attribute to one month from today
    dateInput.setAttribute('min', minDate);
});
</script>



<!-- show file content -->

<script>
function previewFile() {
    const fileInput = document.getElementById('file_upload');
    const filePreview = document.getElementById('file_preview');
    const previewToggle = document.getElementById('preview_toggle');
    const files = fileInput.files;

    filePreview.innerHTML = ''; // Clear previous preview

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileType = file.type;

        const reader = new FileReader();
        reader.onload = function(e) {
            if (fileType.startsWith('image/')) {
                // Image preview
                const imgElement = document.createElement('img');
                imgElement.src = e.target.result;
                filePreview.appendChild(imgElement);
            } else if (fileType === 'application/pdf') {
                // PDF preview
                const pdfEmbed = document.createElement('embed');
                pdfEmbed.src = e.target.result;
                pdfEmbed.style.width = '100%';
                pdfEmbed.style.height =
                    '400px'; // Fixed height for PDF preview
                filePreview.appendChild(pdfEmbed);
            }
        };

        reader.readAsDataURL(file);
    }

    previewToggle.style.display = 'block'; // Show preview toggle button
}

function togglePreview() {
    const filePreview = document.getElementById('file_preview');
    const previewToggle = document.getElementById('preview_toggle');

    if (filePreview.style.display === 'none') {
        filePreview.style.display = 'block';
        previewToggle.innerHTML =
            '<i class="fas fa-eye-slash"></i> Close Preview';

        // Add event listener to close preview when clicking outside
        document.addEventListener('click', closePreviewOnClickOutside);
    } else {
        filePreview.style.display = 'none';
        previewToggle.innerHTML = '<i class="fas fa-eye"></i> Preview';

        // Remove event listener when preview is closed
        document.removeEventListener('click', closePreviewOnClickOutside);
    }
}

function closePreviewOnClickOutside(event) {
    const filePreview = document.getElementById('file_preview');
    const previewToggle = document.getElementById('preview_toggle');

    if (!filePreview.contains(event.target) && !previewToggle.contains(event
            .target)) {
        // Clicked outside the preview and toggle button, so close the preview
        filePreview.style.display = 'none';
        previewToggle.innerHTML = '<i class="fas fa-eye"></i> Preview';

        // Remove event listener after closing the preview
        document.removeEventListener('click', closePreviewOnClickOutside);
    }
}
</script>