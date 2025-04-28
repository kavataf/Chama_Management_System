<?php
session_start();
require_once('../config/config.php');
require_once('../config/checklogin.php');
require_once('../partials/head.php');
require_once('../helpers/addmember.php'); 
require_once('../helpers/managemember.php'); 
$user_role = $_SESSION['user_access_level'];

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// fetch
$sql = "SELECT user_id, member_name, member_gender, member_id_no, member_phone, 
member_email, created_at, member_avatar, member_status
 FROM members";
$result = $mysqli -> query($sql);
$members = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $members[] =  [
        'user_id' => $row['user_id'],
        'name' => $row['member_name'],
        'gender' => $row['member_gender'],
        'id' => $row['member_id_no'],
        'phone' => $row['member_phone'],
        'email' => $row['member_email'],
        'joined' => $row['created_at'],
        'avatar' => $row['member_avatar'],
        'status' => $row['member_status'],
        'lastLogin' => 'N/A'
    ];
    }
}

$sql2 = "SELECT a.loan_name, a.loan_amount, a.application_date, a.loan_status, u.user_id_no
 FROM applications a
 JOIN users u
 ON u.user_id = a.user_id";
$result = $mysqli -> query($sql2);
$loans = array();
if($result -> num_rows > 0){
    while($row = $result -> fetch_assoc()){
       $loans[] = $row;
    }
}

?>

<body id="page-top">
<style>
    body { background-color: #f8f9fa; }
    .sidebar { height: 100vh; background-color: #fff; border-right: 1px solid #dee2e6; }
    .sidebar .nav-link { color: #333; }
    .member-list .list-group-item:hover { cursor: pointer; background-color: #f1f1f1; }
    .details-card { background-color: #fff; border: 1px solid #dee2e6; border-radius: .5rem; padding: 1rem; }
    .admin-actions button { margin-bottom: .5rem; }
  </style>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php require_once('../partials/navbar.php'); ?>
        <?php require_once('../partials/top_navbar.php'); ?>
        <!-- End of Topbar -->

        <!-- Begin Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container mt-0  container-fluid">
                    <?php if ($user_role == 'System Administrator') : ?>
                    <div class="row">
                         <!-- Member List -->
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Members</h5>
                                <div class="d-flex">
                                    <a href="#addmember" data-toggle="modal" style="text-decoration:none;" class="d-none d-sm-inline-block shadow-sm">
                                    <button class="btn btn-primary btn-sm">+ Add Member</button> </a>
                                    <a href="../reports/pdf/members.php" class="btn btn-info btn-sm shadow-sm ml-2">
                                        <i class="fas fa-download"></i> 
                                    </a>
                                </div>
                            </div>
                            <input id="searchInput" class="form-control mb-2" placeholder="Search members...">
                            <ul class="list-group member-list" id="memberList">
                            <!-- JS will populate this -->
                            </ul>
                        </div>
                        <!-- Member Details -->
                        <div class="col-md-8">
                            <div class="details-card" id="memberDetails">
                            <h5 class="mb-3">Select a member to view details</h5>
                            </div>
                            <?php require_once("../modals/add_member.php"); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div><!-- /.container-fluid -->
                <!-- End of Main Content -->
            </div>

            <!-- Footer -->
            <?php require_once('../partials/footer.php'); ?>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php require_once('../partials/scripts.php'); ?>

    <script>
    $(document).ready(function() {
        $('#datatable').DataTable();
    });
    </script>


<script>
    // Members and loans data from PHP
    const members = <?php echo json_encode($members); ?>;
    const loans = <?php echo json_encode($loans); ?>;

    // Render member list
    function renderMemberList(filter = '') {
        const list = document.getElementById('memberList');
        list.innerHTML = '';

        members.forEach((member, index) => {
            if (member.name.toLowerCase().includes(filter.toLowerCase())) {
                const item = document.createElement('li');
                item.className = 'list-group-item d-flex align-items-center';
                item.style.cursor = 'pointer';
                item.innerHTML = `
                    <img src="../public/img/no-profile.png" class="rounded-circle me-2" width="40" height="40">
                    <span class="ml-1">${member.name}</span>
                `;
                item.onclick = () => showDetails(index);
                list.appendChild(item);
            }
        });
    }

    // Search filter
    document.getElementById('searchInput').addEventListener('input', function(e) {
        renderMemberList(e.target.value);
    });

    // Show member details
    function showDetails(index) {
        const m = members[index];
        const container = document.getElementById('memberDetails');

        const memberLoans = loans.filter(loan => loan.user_id_no === m.id);

        let loanRows = '';
        if (memberLoans.length > 0) {
            memberLoans.forEach(loan => {
                loanRows += `
                <tr>
                    <td>${loan.loan_name}</td>
                    <td>Ksh${loan.loan_amount}</td>
                    <td><span class="badge ${getStatusClass(loan.loan_status)}">${loan.loan_status}</span></td>
                    <td>${loan.application_date}</td>
                </tr>
                `;
            });
        } else {
            loanRows = `<tr><td colspan="4" class="text-muted">No loan applications</td></tr>`;
        }

        container.innerHTML = `
            <div class="d-flex align-items-start mb-3">
                <img src="../public/img/no-profile.png" class="rounded-circle me-3" width="60" height="60">
                <div class="d-flex flex-column">
                    <h5 class="mb-1 ml-2">${m.name}</h5>
                    <small class="text-muted mb-1 ml-2">@${m.email.split('@')[0]}</small>
                    <span class="badge ${m.status == 'active' ? 'bg-success text-white' : 'bg-danger text-white'} ml-2">
                        ${m.status}
                    </span>
                </div>
            </div>

            <ul class="list-group mb-3">
                <li class="list-group-item"><strong>Member ID:</strong> ${m.id}</li>
                <li class="list-group-item"><strong>Gender:</strong> ${m.gender}</li>
                <li class="list-group-item"><strong>Phone:</strong> ${m.phone}</li>
                <li class="list-group-item"><strong>Email:</strong> ${m.email}</li>
                <li class="list-group-item"><strong>Date Joined:</strong> ${m.joined}</li>
            </ul>

            <h5 class="mt-4">Loan Applications</h5>
            <div class="table-responsive">
                <table class="table table-borderless align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Loan Type</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date Applied</th>
                        </tr>
                    </thead>
                    <tbody>${loanRows}</tbody>
                </table>
            </div>

            <h5 class="mt-4">Quick Actions</h5>
            <div class="row g-2">
                <div class="col-md-6">
                    <a href="#updateMember" data-toggle="modal"
                        data-user-id="${m.id}"
                        data-name="${m.name}"
                        data-id-no="${m.id}"
                        data-gender="${m.gender}"
                        data-phone="${m.phone}"
                        data-email="${m.email}"
                        onclick="setUpdateModalData(this)">
                        <button class="btn btn-outline-primary w-100">Edit Member Info</button>
                    </a>
                </div>
                <div class="col-md-6 mb-2">
                    <button class="btn btn-outline-warning w-100" onclick="setSuspendMember(${m.id})" data-toggle="modal" data-target="#suspendModal">
                        Suspend
                    </button>
                </div>

                <div class="col-12 mb-2">
                    <a href="#deleteModal" data-toggle="modal">
                        <button class="btn btn-outline-danger w-100">Delete Member</button>
                    </a>
                </div>
            </div>
        `;
    }

    let selectedMemberId = null;


function setSuspendMember(id) {
    selectedMemberId = id;
    console.log("Selected Member ID: " + selectedMemberId); 
}

// Ensure event listener is added when the modal opens
$('#suspendModal').on('shown.bs.modal', function () {
    // Attach the event listener to the OK button only when the modal is visible
    document.querySelector('#suspendModal button[name="suspend_member"]').addEventListener('click', function() {
        suspendMember();
    });
});

function suspendMember() {
    if (selectedMemberId) {
        fetch('../helpers/suspend_member.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: selectedMemberId, action: 'suspend' })
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); 
            if (data.success) {
                location.reload(); 
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    } else {
        alert("No member selected!");
    }
}




    // Loan status badge color
    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'approved': return 'bg-success text-white';
            case 'pending': return 'bg-warning text-dark';
            case 'rejected': return 'bg-danger text-white';
            default: return 'bg-secondary';
        }
    }

    // Initial load
    renderMemberList();
</script>

<?php require_once("../modals/manage_member.php"); ?>

<!-- Toast notification -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 1055">
  <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body">
        Member suspended successfully!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

</body>


</html>