
<?php include '../core/partials/main.php'; ?>
<?php include 'client-assets/review_script.php'; ?>
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
                        <?php include 'client-assets/review_modal.php'; ?>                       

<h4 class="py-3 mb-4"><span class="text-muted fw-light">CISA/</span> WORK REQUEST FORM</h4>

<div class="row">
    <!-- First Card with Form Fields (Split into Two Columns) -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <form action="work_request.php" method="POST">
                    <div class="row">
                        <!-- Left Column of First Card -->
                        <div class="col-md-6">
                            <!-- Date and Time of Request -->
                            <h6>Date and Time of Request:</h6>
                            <p class="text-primary">
                                <?php echo isset($dateOfReq) ? htmlspecialchars($dateOfReq) : 'N/A'; ?> - 
                                <?php echo isset($timeOfReq) ? htmlspecialchars($timeOfReq) : 'N/A'; ?>
                            </p>

                            <!-- Requesting Dept./Unit/Office -->
                            <h6>Requesting Dept./Unit/Office:</h6>
                            <p class="text-primary"><?php echo isset($requestingDept) ? htmlspecialchars($requestingDept) : 'N/A'; ?></p>

                            <!-- Work Requested -->
                            <h6>Work Requested:</h6>
                            <ul class="text-primary">
                                <?php
                                if (isset($workRequested)) {
                                    foreach ($workRequested as $work) {
                                        echo "<li>" . htmlspecialchars($work) . "</li>";
                                    }
                                } else {
                                    echo "<li>N/A</li>";
                                }
                                ?>
                            </ul>

                            <!-- Detailed Description of Work Request -->
                            <h6>Detailed Description of Work Request:</h6>
                            <p class="text-primary"><?php echo isset($descriptionOfWorkRequest) ? nl2br(htmlspecialchars($descriptionOfWorkRequest)) : 'N/A'; ?></p>
                        </div>

                        <!-- Right Column of First Card -->
                        <div class="col-md-6">
                            <!-- Action Taken -->
                            <h6>Action Taken:</h6>
                            <p class="text-primary"><?php echo isset($actionTaken) ? htmlspecialchars($actionTaken) : 'ISU Staff Only'; ?></p>

                            <!-- Approval Section -->
                            <h6>Approved By:</h6>
                            <p class="text-primary"><?php echo isset($approvedBy) ? htmlspecialchars($approvedBy) : 'CISA HEAD'; ?></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Second Card with Button Only (Right Column) -->
    <div class="col-md-4">
        <div class="card mb-4">
                         <h6>Please proceed after checking the reviewed approved request:</h6>
            <div class="card-body d-flex align-items-center justify-content-center">
                <a href="rate.php" class="btn btn-primary">Reviewed and Proceed</a>
            </div>
        </div>
    </div>
</div>


                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php include '../core/partials/footer.php'; ?>

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <?php include '../core/partials/right-sidebar.php'; ?>

        <?php include '../core/partials/vendor-scripts.php'; ?>


        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
