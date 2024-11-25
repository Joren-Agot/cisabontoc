
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
                                        </ul>
                                        
                                    <!-- Tab panes -->
                                    <div class="tab-content pt-4">
                                        <div class="tab-pane active" id="custom-primary" role="tabpanel">
                                            <ul class="message-list mb-0">
                                                <?php
                                                if ($resultPendingRequests && $resultPendingRequests->num_rows > 0) {
                                                    while ($row = $resultPendingRequests->fetch_assoc()) {
                                                        // Extract necessary data for each pending request
                                                        $requestingDept = $row['requesting_dept'];
                                                        $workRequested = $row['work_requested'];
                                                        $description = $row['description_of_work_request'];
                                                        $dateOfReq = $row['date_of_req'];
                                                        $requestId = $row['id'];
                                                ?>
                                                 <li class="unread d-flex" id="item<?php echo $requestId; ?>" onclick="toggleCard('card<?php echo $requestId; ?>', this)">
                                                            <!-- Department Column -->
                                                            <div class="col-mail col-mail-1">
                                                                <div class="checkbox-wrapper-mail">
                                                                    <input type="checkbox" id="chk<?php echo $requestId; ?>">
                                                                    <label for="chk<?php echo $requestId; ?>" class="toggle"></label>
                                                                </div>
                                                                <a href="#" style="padding-left: 20px;">
                                                                    <span class="bg-info badge me-2" style="font-size: .85rem;"><?php echo $requestingDept; ?></span>
                                                                </a>
                                                            </div>
                                                            <!-- Date Column -->
                                                            <div class="col-mail col-mail-2">
                                                                <span class="subject">
                                                                    <?php echo $workRequested; ?>
                                                                    <span class="text-secondary"> - <?php echo $description; ?></span>
                                                                </span>
                                                                <span class="date"><?php echo date("M d, Y", strtotime($dateOfReq)); ?></span>
                                                            </div>
                                                        </li>
                                                        <!-- Card for this request -->
                                                        <div id="card<?php echo $requestId; ?>" class="card mb-3" style="display: none;">
                                                            <div class="card-body">
                                                                <p class="card-title">🏢 Department:&nbsp;<span class="text-primary"> <?php echo $requestingDept; ?></span></p>
                                                                <p><strong>📋  Work Requested:</strong> &nbsp;<span class="text-primary"><?php echo $workRequested; ?></span></p>
                                                                <p><strong>Description:</strong>&nbsp;<span class="text-secondary"> <?php echo $description; ?></span></p>
                                                                <p><strong>Date of Request:</strong> <?php echo date("M d, Y", strtotime($dateOfReq)); ?></p>
                                                            </div>
                                                        </div>
                                                <?php
                                                    }
                                                } else {
                                                    echo "<li>No pending work requests found.</li>";
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    

                                            <div class="tab-pane" id="custom-social" role="tabpanel">
                                                <ul class="message-list mb-0">
                                           <?php
                                            if ($resultPendingJobOrders && $resultPendingJobOrders->num_rows > 0) {
                                                while ($row = $resultPendingJobOrders->fetch_assoc()) {
                                                    // Extract necessary data for each pending job order
                                                    $requestingDept = $row['requesting_dept'];
                                                    $workType = $row['work_type'];
                                                    $type = $row['type'];
                                                    $dateOfReq = $row['date_of_req'];
                                                    $timeOfReq = $row['time_of_req'];
                                                    $requestId = $row['id'];
                                                    ?>

                                                 <li class="unread d-flex">
                                                      <!-- Department Column -->
                                                           <div class="col-mail col-mail-1">
                                                         <div class="checkbox-wrapper-mail">
                                                              <input type="checkbox" id="chk<?php echo $requestId; ?>">
                                                                <label for="chk<?php echo $requestId; ?>" class="toggle"></label>
                                                                </div>
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#requestModals<?php echo $requestId; ?>" style="padding-left: 20px;">
                                                                <span class="bg-info badge me-2" style="font-size: .85rem;"><?php echo $requestingDept; ?></span>
                                                                <?php include 'admin-script/rmodal.php'; ?>
                                                            </div>
                                                        <div class="col-mail col-mail-2">
                                                            <span class="subject">
                                                                <?php echo htmlspecialchars($workType); ?>
                                                                <span class="text-secondary"> - <?php echo htmlspecialchars($type); ?></span>
                                                            </span>
                                                            <div class="date"><?php echo date("M d, Y", strtotime("$dateOfReq $timeOfReq")); ?></div>
                                                        </div>
                                                    </a>
                                                    </li>
                                                    <?php
                                                }
                                            } else {
                                                echo "<li>No pending job orders found.</li>";
                                            }
                                            ?>
 
                                                            <a href="#" class="subject"></span>
                                                            </a>
                                                </ul>
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
