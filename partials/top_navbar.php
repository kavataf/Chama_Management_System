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
                            class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            <?php if ($user_role == 'Member') echo '
                            <div class="notification-icon position-relative" onclick="showNotifications()">
                                <span>🛎</span>
                                <span id="notification-count" class="badge bg-danger position-absolute top-0 start-100 translate-middle rounded-circle"></span>
                            </div>

                            <div id="notification-dropdown" 
                            style="display: none; position: absolute; background: white; border: 1px solid #ccc; z-index: 999;">
                                <ul id="notification-list"></ul>
                            </div>
                            
                            <div class="ml-3">
                              <img class="img-profile rounded-circle" src="../public/img/no-profile.png">
                            </div>';
                            ?>

                            <?php if ($user_role == 'System Administrator') echo '
                              <img class="img-profile rounded-circle" src="../public/img/no-profile.png">';
                              ?>
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
                        <a class="dropdown-item" href="#logoutModal" data-toggle="modal">
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
    function showNotifications() {
    let dropdown = document.getElementById("notification-dropdown");
    
    // Toggle visibility
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
        return;
    }

    // Fetch notifications
    fetch('fetch_notifications.php')
        .then(response => response.json())
        .then(data => {
            let notificationList = document.getElementById("notification-list");
            notificationList.innerHTML = ""; 
            
            if (data.length === 0) {
                notificationList.innerHTML = "<li>No new notifications</li>";
            } else {
                data.forEach(notification => {
                    let li = document.createElement("li");
                    li.textContent = notification.message;
                    notificationList.appendChild(li);
                });
            }
            dropdown.style.display = "block"; 
            dropdown.style.position = "absolute";
            dropdown.style.top = "60px"; 
            dropdown.style.right = "0";
            dropdown.style.width = "200px";
            dropdown.style.backgroundColor = "white";
            dropdown.style.border = "1px solid #ccc";
            dropdown.style.boxShadow = "0px 4px 6px rgba(0, 0, 0, 0.1)";
            dropdown.style.maxHeight = "400px"; 
            dropdown.style.overflowY = "auto"; 
            dropdown.style.padding = "10px";
            dropdown.style.zIndex = "9999";
        })
        .catch(error => console.error("Error fetching notifications:", error));
}

function updateNotificationCount() {
    fetch('fetch_notifications.php')
        .then(response => response.json())
        .then(data => {
            let countElement = document.getElementById("notification-count");
            let count = data.length;
            countElement.textContent = count > 0 ? count : "";
        });
}

// Update count every 30 seconds
setInterval(updateNotificationCount, 30000);
updateNotificationCount();

</script>