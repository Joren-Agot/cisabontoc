<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>
               <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                <li>
                    <a href="../admin/dashboard.php" class="waves-effect">
                        <i class="mdi mdi-home-variant-outline"></i><span
                            class="badge rounded-pill bg-primary float-end">3</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="../admin/calendar.php" class=" waves-effect">
                        <i class="mdi mdi-calendar-outline"></i>
                        <span>Calendar</span>
                    </a>
                </li>

                <li class="menu-title">Account</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-account-circle-outline"></i>
                        <span>Accounts</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="../admin/account.php">Create Account</a></li>
                        <li><a href="../admin/account_info.php">Account Information</a></li>
                    </ul>
                </li>

                <li class="menu-title">Form Request</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-bar-chart-line"></i>
                        <span>Request</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                    <li><a href="form_request.php">Pending Request</a></li>
                     
                    </ul>
                </li>

                <li class="menu-title">Files</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-eraser-fill"></i>
                        <span>Forms</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="../admin/form-editors.php">Monthly Acomplishment Report Editor</a></li> 
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-bar-chart-line"></i>
                        <span>Charts</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                    <li><a href="../admin/m-report.php">Preventive Maintenance Chart</a></li>
                    </ul>
                </li>
                <?php endif; ?>


                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "client"): ?>
                <li>
                    <a href="../client/dashboard.php" class="waves-effect">
                        <i class="mdi mdi-home-variant-outline"></i><span
                            class="badge rounded-pill bg-primary float-end">3</span>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-title">Request</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-file-edit-line"></i>
                        <span>Form Request</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="../client/work_request.php">Work Request Form</a></li>
                        <li><a href="../client/job_order.php">Job Order Form</a></li>
                        <li><a href="../core/request.php">Request</a></li>
                    </ul>
                </li>

                <?php endif; ?>
             <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "staff"): ?>
                <li>
                    <a href="../staff/dashboard.php" class="waves-effect">
                        <i class="mdi mdi-home-variant-outline"></i><span
                            class="badge rounded-pill bg-primary float-end">3</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="../staff/calendar.php" class=" waves-effect">
                        <i class="mdi mdi-calendar-outline"></i>
                        <span>Calendar</span>
                    </a>
                </li>

                <li class="menu-title">Form Request</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-bar-chart-line"></i>
                        <span>Request</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                    <li><a href="form_request.php">Pending Request</a></li>
                     
                    </ul>
                </li>
                <?php endif; ?>
             <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "id"): ?>
                <li>
                    <a href="../other/request.php" class="waves-effect">
                        <i class="mdi mdi-home-variant-outline"></i><span
                            class="badge rounded-pill bg-primary float-end">3</span>
                        <span>Dashboard</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->