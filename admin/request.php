
<?php include '../core/partials/main.php'; ?>
<?php include 'admin-script/request_script.php'; ?>

    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
        
        <!-- jvectormap -->
        <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Upzet' , 'title' => 'Dashboard')); ?>
                                                    
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs nav-justified nav-tabs-custom" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#custom-primary" role="tab">
                                                    <i class="mdi mdi-inbox me-2 align-middle font-size-18"></i> <span class="d-none d-md-inline-block">Work Request</span> 
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#custom-social" role="tab">
                                                    <i class="mdi mdi-account-group-outline me-2 align-middle font-size-18"></i> <span class="d-none d-md-inline-block">Job Order Request</span>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#custom-social" role="tab">
                                                    <i class="mdi mdi-account-group-outline me-2 align-middle font-size-18"></i> <span class="d-none d-md-inline-block">Job Order Request</span>
                                                </a>
                                            </li>

                                        </ul>
                                        
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
                                                            <div class="card" data-request-id="<?php echo $row['id']; ?>">
                                                                <div class="card-body">
                                                                    <h5 class="card-title text-primary">Pending Request 📝</h5>
                                                                    <hr>
                                                                    <ul class="request-details">
                                                                        <li>Requesting Department: &nbsp;<span class="text-primary"><?php echo $requestingDept; ?></span></li>
                                                                        <li>Date of Request: &nbsp;<span class="text-primary"><?php echo $dateOfReq; ?></span></li>
                                                                        <li>Time of Request: &nbsp;<span class="text-primary"><?php echo $timeOfReq; ?></span></li>
                                                                        <li>Action Taken: &nbsp;<span class="text-primary"><?php echo $actionTaken; ?></span></li>
                                                                        <li>Work Requested: &nbsp;<span class="text-primary"><?php echo $workRequested; ?></span></li> 
                                                                        <li>Description: &nbsp;<span class="text-primary"><?php echo $descriptionOfWorkRequest; ?></span></li>
                                                                    </ul>
                                                                    <a href="#" class="btn btn-success action-link" data-bs-toggle="modal" data-bs-target="#requestModal" data-request-id="<?php echo $row['id']; ?>">Action</a>
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
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Pending Job Order 📝</h5>
                                <hr>
                                <ul>
                                    <li>Requesting Department: &nbsp;<span class="text-primary"><?php echo $requestingDept; ?></span></li>
                                    <li>Date of Request: &nbsp;<span class="text-primary"><?php echo $dateOfReq; ?></span></li>
                                    <li>Time of Request: &nbsp;<span class="text-primary"><?php echo $timeOfReq; ?></span></li>
                                    <li>Action Taken: &nbsp;<span class="text-primary"><?php echo $actionTaken; ?></span></li>
                                    <li>Work Requested: &nbsp;<span class="text-primary"><?php echo $workRequested; ?></span></li>
                                </ul>
                                <a href="#" class="btn btn-success action-link" data-bs-toggle="modal" data-bs-target="#requestModal" data-request-id="<?php echo $jobOrderId; ?>">Action</a>
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


        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
