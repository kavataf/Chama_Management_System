<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Topbar Search -->
            <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                <div class="input-group">
                    <div class="container">
                        <a href="home" class="navbar-brand">

                            <span class="brand-text font-weight-light">
                            <b>Chama Management System <small>(COMS)</small>.</b>
                            </span>
                        </a>

                    </div>
                </div>
            </form>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                        aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small"
                                    placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>


                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span
                            class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                        <?php if ($user_role == 'Member'): ?>
                            <span class="notification-icon position-relative pc-head-link"
                            id="notificationDropdownToggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                            🛎
                            <span id="notification-count"
                                class="badge bg-success pc-h-badge position-absolute top-0 translate-middle rounded-circle"></span>
                            </span>
                        <?php endif; ?>

                        <img class="img-profile rounded-circle" src="../public/img/no-profile.png">

                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="profile">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                        <!-- <a class="dropdown-item" href="#">
                        <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                        Activity Log
                    </a> -->
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>

                    </div>
                    <?php require_once('../partials/logout.php'); ?>
                </li>

            </ul>

        </nav>
 
<?php require_once('../partials/scripts.php'); ?>
<?php require_once('../customs/scripts/functions.php'); ?>
<?php require_once('../customs/scripts/ajax.php'); ?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const notificationToggle = document.getElementById("notificationDropdownToggle");
    const notificationDropdown = document.querySelector(".dropdown-notification");
    const notificationList = document.getElementById("notification-list");
    const notificationCount = document.getElementById("notification-count");

    let dropdownVisible = false;

    // Toggle notification dropdown manually
    notificationToggle.addEventListener("click", function (e) {
        e.stopPropagation();
        dropdownVisible = !dropdownVisible;
        notificationDropdown.style.display = dropdownVisible ? "block" : "none";
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (e) {
        if (!notificationDropdown.contains(e.target) && !notificationToggle.contains(e.target)) {
            notificationDropdown.style.display = "none";
            dropdownVisible = false;
        }
    });

    // Fetch notifications from server
    function fetchNotifications() {
        fetch('fetch_notifications.php')
            .then(response => response.json())
            .then(data => {
                notificationList.innerHTML = "";
                if (data.length > 0) {
                    data.forEach(msg => {
                        const li = document.createElement("div");
                        li.className = "dropdown-item text-wrap";
                        li.textContent = msg.message || msg; // adapt based on your PHP response format
                        notificationList.appendChild(li);
                    });
                    notificationCount.textContent = data.length;
                } else {
                    notificationList.innerHTML = '<div class="dropdown-item text-muted">No notifications</div>';
                    notificationCount.textContent = "";
                }
            })
            .catch(error => {
                console.error("Error fetching notifications:", error);
                notificationList.innerHTML = '<div class="dropdown-item text-muted">Failed to load notifications</div>';
            });
    }

    fetchNotifications();

    // Mark all as read
    document.querySelector(".btn-link.btn-sm").addEventListener("click", function (e) {
        e.preventDefault();
        notificationCount.textContent = "";
        // Optionally: send a request to mark all as read in the backend
    });

    // Clear all notifications
    document.querySelector(".link-danger").addEventListener("click", function (e) {
        e.preventDefault();
        notificationList.innerHTML = '<div class="dropdown-item text-muted">No notifications</div>';
        notificationCount.textContent = "";
        // Optionally: send a request to clear all notifications in the backend
    });
});
</script>
