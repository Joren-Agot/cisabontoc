<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php';
require_once '../core/partials/config.php';
?>

<head>
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
    
    <!-- jvectormap -->
    <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
    
    <?php include '../core/partials/head-css.php'; ?>
    
    <style>
        /* Custom CSS for Card Borders */
        .custom-border {
            border: 1px solid gray; /* Blue border */
            border-radius: 8px; /* Rounded corners */
            padding: 15px; /* Padding inside the card */
        }
    </style>
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

            <!-- Begin form section -->
            <div class="row">
                <div class="col-lg-6">
                    <!-- First Card with Border -->
                    <div class="card custom-border">
                        <div class="card-body">
                       <h5 class="card-title text-center">Request Details</h5>

                            
                            <form method="POST" action="">
                                <!-- Request Type Dropdown -->
                                <div class="form-group">
                                    <label for="request_type">Requestor:</label>
                                    <select class="form-control" id="request_type" name="request_type">
                                        <option value="student">Student</option>
                                        <option value="faculty">Faculty</option>
                                        <option value="instructor">Instructor</option>
                                        <option value="guest">Guest</option>
                                    </select>
                                </div>
                                
                                <!-- Purpose Dropdown -->
                                <div class="form-group">
                                    <label for="purpose">Purpose of Request:</label>
                                    <select class="form-control" id="purpose" name="purpose">
                                        <option value="class_material">Class Material</option>
                                        <option value="assembly">Assembly (Meeting etc)</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="cleaning_material">Cleaning Material</option>
                                    </select>
                                </div>
            <!-- Checkbox Section for Items -->
            <div class="form-group">
                <label for="items">Select Items Needed:</label><br>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="chairs" name="items[]" value="chairs">
                    <label class="form-check-label" for="chairs">Chairs</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="gardening_tools" name="items[]" value="gardening_tools">
                    <label class="form-check-label" for="gardening_tools">Gardening Tools</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="wires" name="items[]" value="wires">
                    <label class="form-check-label" for="wires">Wires</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="audio_system" name="items[]" value="audio_system">
                    <label class="form-check-label" for="audio_system">Audio System</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="cleaning_materials" name="items[]" value="cleaning_materials">
                    <label class="form-check-label" for="cleaning_materials">Cleaning Materials (broom, etc)</label>
                </div>
            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Second Card with Border and Submit Button -->
                    <div class="card custom-border">
                        <div class="card-body">
                            <h5 class="card-title">Submit Request</h5>
                            <form method="POST" action="">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End form section -->

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
