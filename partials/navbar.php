<!-- Top Navbar -->

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient sidebar sidebar-dark accordion" id="accordionSidebar"
    style="background-color: #124491;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="home">
        <div class="sidebar-brand-icon">
            <img src="../public/img/chama_logo.png" class="brand-image img-circle elevation-3"
                style="opacity: 1; height: 40px;">
        </div>
        <div class="sidebar-brand-text mx-2">COMS</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="home">
            <i class="fas fa-fw fa-home"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manage
    </div>

    <li class="nav-item">
        <a class="nav-link" href="contributions">
            <i class="fas fa-fw fa-money-bill"></i>
            <span>Contributions</span>
        </a>
    </li>

    <?php

    use Cloudinary\Api\Provisioning\UserRole;

    if ($user_role == 'System Administrator') echo '
    <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                aria-expanded="true" aria-controls="collapsePages">
                <i class="fas fa-fw fa-landmark"></i>
                <span>Loans</span>
            </a>
            <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <a class="collapse-item" href="applications">Applications</a>
                    <a class="collapse-item" href="repayments">Repayments</a>
                </div>
            </div>
        </li>';
    ?>
    <?php if ($user_role == 'System Administrator') echo '
    <li class="nav-item">
        <a class="nav-link" href="members">
            <i class="fas fa-fw fa-users"></i>
            <span>Members</span>
        </a>
    </li>';
    ?>

<?php if ($user_role == 'Member') echo '
    <li class="nav-item">
        <a class="nav-link" href="loans">
            <i class="fas fa-fw fa-landmark"></i>
            <span>Loans</span>
        </a>
    </li>';
    ?>

<?php if ($user_role == 'Member') echo '
    <li class="nav-item">
        <a class="nav-link" href="repayments">
            <i class="fas fa-money-bill"></i>
            <span>Repayments</span>
        </a>
    </li>';
    ?>

    <li class="nav-item">
        <a class="nav-link" href="savings">
            <i class="fas fa-fw fa-piggy-bank"></i>
            <span>Savings</span>
        </a>
    </li>
    <?php if ($user_role == 'System Administrator') echo '
    <li class="nav-item">
        <a class="nav-link" href="expenses">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>Expenses</span>
        </a>
    </li>';
    ?>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        More
    </div>

        

    <?php if ($user_role == 'System Administrator') echo '
    <li class="nav-item">
        <a class="nav-link" href="reports">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Reports</span>
        </a>
    </li>';
    ?>
    <li class="nav-item">
        <a class="nav-link" href="products">
            <i class="fas fa-fw fa-gift"></i>
            <span>Products</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="sms">
            <i class="fas fa-fw fa-comment"></i>
            <span>SMS</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="emails">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Emails</span>
        </a>
    </li>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

</ul>

<!-- End of Sidebar -->

<!-- Content Wrapper -->