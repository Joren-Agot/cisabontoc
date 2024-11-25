
<?php include '../core/partials/main.php'; ?>
<?php include 'admin-script/request_script.php'; ?>

    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
        
        <!-- jvectormap -->
        <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
        <link href="../assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <?php include '../core/partials/head-css.php'; ?>

    </head>

    <?php include '../core/partials/body.php'; ?>

        <!-- Begin page -->
        <div id="layout-wrapper">

        <?php include '../core/partials/menu.php'; ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
<div class="main-content">
<div class="page-content">
    <div class="container-fluid">
        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Upzet', 'title' => 'Dashboard')); ?>
        
        <div class="d-flex justify-content-between mb-4">
            <select id="sortBy" class="form-select w-auto" onchange="sortRequests()">
                <option value="date">Sort by Date</option>
                <option value="dept">Sort by Department</option>
            </select>
        </div>

        <div class="card mb-0">
            <div class="card-body">

<ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#custom-primary" role="tab">
            <img src="../core/assets/images/icons/work.png" alt="Work Request" class="gif-move" data-gif="../Core/assets/images/icons/work.gif">
            <span class="d-none d-md-inline-block">Work Request</span> 
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#custom-social" role="tab">
            <img src="../core/assets/images/icons/job.png" alt="Job Order Request" class="gif-move" data-gif="../Core/assets/images/icons/job.gif">
            <span class="d-none d-md-inline-block">Job Order Request</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#custom-social" role="tab">
            <img src="../core/assets/images/icons/misc.png" alt="Miscellaneous Request" class="gif-move" data-gif="../Core/assets/images/icons/misc.gif">
            <span class="d-none d-md-inline-block">Miscellaneous Request</span>
        </a>
    </li>
</ul>

<style>
    .nav-tabs-custom .nav-link {
        border: 2px solid gray; /* Gray outline */
        border-radius: 5px; /* Rounded corners */
        transition: all 0.3s ease; /* Smooth hover transition */
        padding: 10px 15px; /* Adjust padding for better spacing */
    }

    /* Highlight active tab with a darker gray */
    .nav-tabs-custom .nav-link.active {
        border-color: #555; /* Darker gray for active tab */
        background-color: #f8f9fa; /* Light background for active tab */
    }

    /* Add hover effect */
    .nav-tabs-custom .nav-link:hover {
        border-color: #777; /* Slightly darker gray on hover */
        background-color: #f8f9fa; /* Light gray background on hover */
        transform: scale(1.05); /* Slightly enlarge tab on hover */
    }

    .gif-move {
        width: 30px; /* Adjust size as needed */
        height: auto; /* Maintain aspect ratio */
        transition: transform 0.3s ease; /* Smooth transition for hover effect */
    }

    .nav-link:hover .gif-move {
        transform: scale(1.2); /* Slightly enlarge the image */
    }
        /* Add small gray outline to all cards */
    .card-t {
        border: 1px solid #ccc; /* Light gray outline */
        border-radius: 5px; /* Slightly rounded corners */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
        transition: all 0.3s ease; /* Smooth transition for hover effect */
    }

    /* Add hover effect to make the outline more pronounced */
    .card-t:hover {
        border-color: #888; /* Darker gray on hover */
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* More pronounced shadow on hover */
        transform: translateY(-3px); /* Slight lift effect */
    }
</style>

<script>
    // JavaScript to handle GIF swapping
    document.querySelectorAll('.nav-link').forEach(function(link) {
        const img = link.querySelector('.gif-move'); // Select the image inside the tab
        const originalSrc = img.src; // Static image path
        const gifSrc = img.getAttribute('data-gif'); // GIF path

        // Swap to GIF on hover
        link.addEventListener('mouseenter', function() {
            img.src = gifSrc;
        });

        // Revert to static image on hover out
        link.addEventListener('mouseleave', function() {
            img.src = originalSrc;
        });

        // Toggle image on click
        link.addEventListener('click', function() {
            if (img.src === gifSrc) {
                img.src = originalSrc;
            } else {
                img.src = gifSrc;
            }
        });
    });
</script>
                <!-- Tab panes -->
                <div class="tab-content pt-4">
                    <div class="tab-pane active" id="custom-primary" role="tabpanel">
                        <div class="container-xxl flex-grow-1 container-p-y">
                            <div class="row">
                                <?php
                                if ($resultPendingRequests && $resultPendingRequests->num_rows > 0) {
                                    while ($row = $resultPendingRequests->fetch_assoc()) {
                                        // Extract data for each pending request
                                        $requestingDept = $row['requesting_dept'];
                                        $dateOfReq = $row['date_of_req'];
                                        $timeOfReq = $row['time_of_req'];
                                        $actionTaken = $row['action_taken'];
                                        $descriptionOfWorkRequest = $row['description_of_work_request'];
                                        $workRequested = $row['work_requested']; // Fetching work_requested column

                                        // Display each request as a card
                                ?>
                                <div class="col-lg-6 mb-4 order-0">
                                    <div class="card-t" data-request-id="<?php echo $row['id']; ?>">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary">Pending Request 📝</h5>
                                            <hr>
                                            <ul class="request-details">
                                                <li>Requesting Department: &nbsp;<span class="text-primary field-display"><?php echo $requestingDept; ?></span>
                                                    <input type="text" class="form-control field-input d-none" value="<?php echo $requestingDept; ?>">
                                                </li>
                                                <li>Date of Request: &nbsp;<span class="text-primary"><?php echo $dateOfReq; ?></span></li>
                                                <li>Time of Request: &nbsp;<span class="text-primary"><?php echo $timeOfReq; ?></span></li>
                                                <li>Action Taken: &nbsp;<span class="text-primary field-display"><?php echo $actionTaken; ?></span>
                                                    <input type="text" class="form-control field-input d-none" value="<?php echo $actionTaken; ?>">
                                                </li>
                                                <li>Work Requested: &nbsp;<span class="text-primary field-display"><?php echo $workRequested; ?></span>
                                                    <input type="text" class="form-control field-input d-none" value="<?php echo $workRequested; ?>">
                                                </li>
                                                <li>Description: &nbsp;<span class="text-primary field-display"><?php echo $descriptionOfWorkRequest; ?></span>
                                                    <input type="text" class="form-control field-input d-none" value="<?php echo $descriptionOfWorkRequest; ?>">
                                                </li>
                                            </ul>
                                            <a href="#" class="btn btn-success action-link" onclick="toggleEditable(this)" data-request-id="<?php echo $row['id']; ?>">Edit</a>

                                            <div class="btn-group ms-2">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Action
                                                </button>

                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" id="sa-success" href="#" onclick="updateStatus(<?php echo $row['id']; ?>, 'Approved')">Approve</a></li>
                                                    <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $row['id']; ?>, 'Declined')">Decline</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                    }
                                } else {
                                    echo "No pending requests found.";
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="custom-social" role="tabpanel">
                        <div class="row">
                            <?php
                            if ($resultPendingJobOrders && $resultPendingJobOrders->num_rows > 0) {
                                while ($row = $resultPendingJobOrders->fetch_assoc()) {
                                    // Extract data for each pending job order
                                    $requestingDept = isset($row['requesting_dept']) ? htmlspecialchars($row['requesting_dept']) : 'N/A';
                                    $dateOfReq = isset($row['date_of_req']) ? htmlspecialchars($row['date_of_req']) : 'N/A';
                                    $timeOfReq = isset($row['time_of_req']) ? htmlspecialchars($row['time_of_req']) : 'N/A';
                                    $actionTaken = isset($row['action_taken']) ? htmlspecialchars($row['action_taken']) : 'N/A';
                                    $workRequested = isset($row['work_requested']) ? htmlspecialchars($row['work_requested']) : 'N/A';
                                    $jobOrderId = $row['id'];

                                    // Display each job order as a card
                            ?>
                            <div class="col-lg-6 mb-4">
                                <div class="card-t" data-request-id="<?php echo $jobOrderId; ?>">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">Pending Job Order 📝</h5>
                                        <hr>
                                        <ul class="request-details">
                                            <li>Requesting Department: &nbsp;<span class="text-primary field-display"><?php echo $requestingDept; ?></span>
                                                <input type="text" class="form-control field-input d-none" value="<?php echo $requestingDept; ?>">
                                            </li>
                                            <li>Date of Request: &nbsp;<span class="text-primary field-display"><?php echo $dateOfReq; ?></span>
                                                <input type="text" class="form-control field-input d-none" value="<?php echo $dateOfReq; ?>">
                                            </li>
                                            <li>Time of Request: &nbsp;<span class="text-primary field-display"><?php echo $timeOfReq; ?></span>
                                                <input type="text" class="form-control field-input d-none" value="<?php echo $timeOfReq; ?>">
                                            </li>
                                            <li>Action Taken: &nbsp;<span class="text-primary field-display"><?php echo $actionTaken; ?></span>
                                                <input type="text" class="form-control field-input d-none" value="<?php echo $actionTaken; ?>">
                                            </li>
                                            <li>Work Requested: &nbsp;<span class="text-primary field-display"><?php echo $workRequested; ?></span>
                                                <input type="text" class="form-control field-input d-none" value="<?php echo $workRequested; ?>">
                                            </li>
                                        </ul>
                                        <a href="#" class="btn btn-success action-link" onclick="toggleEditable(this)" data-request-id="<?php echo $jobOrderId; ?>">Edit</a>
                                        <a href="#" class="btn btn-success action-link" onclick="updateRequest(<?php echo $row['id']; ?>)">Update</a>

                                        <div class="btn-group ms-2">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Action
                                            </button>

                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" id="sa-success" href="#" onclick="updateStatus(<?php echo $row['id']; ?>, 'Approved')">Approve</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="updateStatus(<?php echo $row['id']; ?>, 'Declined')">Decline</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                }
                            } else {
                                echo "<p>No pending job orders found.</p>";
                            }
                            ?>
                        </div>
                    </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>





                                <!-- end card -->
                            </div>
                        </div>
                        <!-- end row -->


                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php include '../core/partials/footer.php'; ?>

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->
<?php include 'admin-script/request_card.php'; ?>
        <?php include '../core/partials/right-sidebar.php'; ?>

        <?php include '../core/partials/vendor-scripts.php'; ?>

        <!-- Sweet Alerts js -->
        <script src="../core/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- Sweet alert init js-->
        <script src="../core/assets/js/pages/aalert.js"></script>
        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
