<?php include '../core/partials/session.php'; ?>

<?php include '../core/partials/main.php'; 
if ($_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}
?>

<head>
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
    <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
    <?php include '../core/partials/head-css.php'; ?>

<style>
    .card-body {
        padding: 20px;
    }

    .card {
        min-height: 250px;
        border: 1px solid gray; /* Add border with color */
        border-radius: 10px; /* Optional: Add rounded corners */
        padding: 10px; /* Optional: Add padding inside the card */
    }

    .progress-container {
        position: relative;
        margin: 20px 0;
        display: flex;
        flex-direction: row;
    }

    .progress {
        height: 2px;
        width: 100%;
        background-color: #e9ecef;
        position: relative;
    }

    .progress-bar {
        height: 2px;
        background-color: #3498db;
        width: 0%;
        transition: width 0.3s ease;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
    }

    .status-buttons {
        position: absolute;
        width: 100%;
        top: -12px;
        display: flex;
        justify-content: space-between;
    }

    .status-button-container {
        text-align: center;
        position: relative;
        z-index: 2;
    }

    .status-button {
        width: 2rem;
        height: 2rem;
    }

    .btn-primary {
        background-color: #3498db;
    }

    .btn-secondary {
        background-color: #adb5bd;
    }

    .indicator {
        font-size: 0.875rem;
        color: #6c757d;
        margin-top: 5px;
    }

    /* Small screen adjustments */
    @media (max-width: 768px) {
        .progress-container {
            flex-direction: column;
            align-items: center;
        }

        .progress {
            width: 2px;
            height: 100%;
            background-color: #e9ecef;
            margin: 0 auto;
        }

        .progress-bar {
            width: 2px;
            height: 0%;
            background-color: #3498db;
            transition: height 0.3s ease;
        }

        .status-buttons {
            flex-direction: column;
            position: relative;
            top: 0;
            align-items: flex-start;
            gap: 15px;
        }

        .status-button-container {
            display: flex;
            align-items: center;
            flex-direction: row;
        }

        .indicator {
            margin-left: 15px;
            text-align: left;
            margin-bottom: 20px;
        }

        /* Adjust the second indicator to stack vertically */
        .indicator-2 {
            margin-left: -110px; /* Remove left margin */
            margin-top: 20px; /* Add top margin to push it below the button */
        }

    }
</style>

</head>

<?php include '../core/partials/body.php'; ?>

<div id="layout-wrapper">
    <?php include '../core/partials/menu.php'; ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'CLIENT', 'title' => 'REQUEST PROGRESS')); ?>

                
                                        <?php
                                        $current_user_id = $_SESSION['client_id'];
                                        // Update query to exclude 'Done' status and limit the result to one request
$sql = "SELECT wr.id, wr.status, wr.requesting_dept, wr.work_requested, wr.date_of_req, wr.action_taken, wr.approved_date, wr.processed_date, ci.first_name, ci.middle_name, ci.last_name
        FROM work_requests wr
        LEFT JOIN admin_info ci ON wr.cisa_head_id = ci.admin_id
        WHERE wr.client_id = ? AND wr.status NOT IN ('Done', 'Declined')
        LIMIT 1";

                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $current_user_id);
                        $stmt->execute();
                        $result = $stmt->get_result();

                                        $statuses = ["Pending" => 1, "Approved" => 2, "Processed" => 3, "Delivered" => 4];

                                        if ($row = $result->fetch_assoc()):
                                            $status = $row['status'];
                                            $progress_level = $statuses[$status] ?? 0;
                                            $progress_width = ($progress_level / 4) * 100;
                                        ?>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">OVERALL PROGRESS:</h5>
        <div class="progress-container">
            <div class="progress">
                <div class="progress-bar" style="width: <?php echo $progress_width; ?>%;"></div>
            </div>
            <div class="status-buttons">
                <!-- First Indicator with Uppercase Text -->
                <div class="status-button-container">
                    <button class="btn btn-sm <?php echo ($progress_level >= 1) ? 'btn-primary' : 'btn-secondary'; ?> rounded-pill status-button" style="border: 2px solid <?php echo ($progress_level >= 1) ? '#007b8c' : '#adb5bd'; ?>;">1</button>
                    <div class="indicator" style="color: <?php echo ($progress_level >= 1) ? '#00e0ce' : '#6c757d'; ?>;">WORK REQUEST</div>
                    <div class="indicator-2">Request Date: <span style="color: red;"><?php echo date("Y-m-d", strtotime($row['date_of_req'])); ?></span></div>
                </div>

                <!-- Second Indicator -->
                <div class="status-button-container">
                    <button class="btn btn-sm <?php echo ($progress_level >= 2) ? 'btn-primary' : 'btn-secondary'; ?> rounded-pill status-button" style="border: 2px solid <?php echo ($progress_level >= 2) ? '#007b8c' : '#adb5bd'; ?>;">2</button>
                    <div class="indicator" style="color: <?php echo ($progress_level >= 2) ? '#00e0ce' : '#6c757d'; ?>;">APPROVED BY CISA</div>
                    <div class="indicator-2">Date of Approval: <span style="color: red;">
                        <?php echo ($row['approved_date']) ? date("Y-m-d", strtotime($row['approved_date'])) : "Not Available"; ?>
                    </span></div>
                </div>

                <!-- Third Indicator -->
                <div class="status-button-container">
                    <button class="btn btn-sm <?php echo ($progress_level >= 3) ? 'btn-primary' : 'btn-secondary'; ?> rounded-pill status-button" style="border: 2px solid <?php echo ($progress_level >= 3) ? '#007b8c' : '#adb5bd'; ?>;">3</button>
                    <div class="indicator" style="color: <?php echo ($progress_level >= 3) ? '#00e0ce' : '#6c757d'; ?>;">PROCESSED</div>
                    <div class="indicator-2">Date of Processed: <span style="color: red;">
                        <?php echo ($row['processed_date']) ? date("Y-m-d", strtotime($row['processed_date'])) : "Not Available"; ?>
                    </span></div>
                </div>

                <!-- Fourth Indicator -->
                <div class="status-button-container">
                    <button class="btn btn-sm <?php echo ($progress_level >= 4) ? 'btn-primary' : 'btn-secondary'; ?> rounded-pill status-button" style="border: 2px solid <?php echo ($progress_level >= 4) ? '#007b8c' : '#adb5bd'; ?>;">4</button>
                    <div class="indicator" style="color: <?php echo ($progress_level >= 4) ? '#00e0ce' : '#6c757d'; ?>;">DELIVERED</div>
                </div>
            </div>
        </div>
    </div>
</div>



                <?php endif; ?>

                <div class="row">
                    <!-- First Card with Form Fields (Split into Two Columns) -->
<!-- First Card with Form Fields (Split into Two Columns) -->
<div class="col-md-8">
    <div class="card mb-4">
        <h5 class="card-title">DETAILS:</h5>
        <div class="card-body">
            <form action="work_request.php" method="POST">
                <div class="row">
                    <!-- Left Column of First Card -->
                    <div class="col-md-6">
                        <!-- Date and Time of Request -->

                        <!-- Requesting Dept./Unit/Office -->
                        <h6>Requesting Dept./Unit/Office:</h6>
                        <p class="text-primary"><?php echo isset($row['requesting_dept']) ? htmlspecialchars($row['requesting_dept']) : 'N/A'; ?></p>

                        <!-- Work Requested -->
                        <h6>Work Requested:</h6>                                           
                        <p class="text-primary"><?php echo isset($row['work_requested']) ? htmlspecialchars($row['work_requested']) : 'N/A'; ?></p>  
                    </div>

                    <!-- Right Column for Action Taken -->
                    <div class="col-md-6">
                        <h6>Action Taken:</h6>                                           
                        <p class="text-primary"><?php echo isset($row['action_taken']) ? htmlspecialchars($row['action_taken']) : 'N/A'; ?></p> 

                        <h6>Approved By:</h6>                                           
                        <p class="text-primary">
                            <?php 
                            if (isset($row['first_name'])) {
                                echo htmlspecialchars($row['first_name']) . ' ' . htmlspecialchars($row['middle_name']) . ' ' . htmlspecialchars($row['last_name']);
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>                                          
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


                    <!-- Second Card with Form Fields -->
<!-- Request Information Card -->
<div class="col-md-4">
    <div class="card mb-4">
        <div class="card-body">
            <form action="rate.php" method="POST">
                <h5>Request Information</h5>
                <p>Status: <?php echo isset($status) ? htmlspecialchars($status) : 'N/A'; ?></p>

                <?php if ($status === 'Delivered'): ?>
                    <!-- Form for 'Delivered' status with action to rate.php -->
                    <button type="submit" name="action" value="proceed" class="btn btn-success btn-block">Proceed</button>
                </form>
                <?php endif; ?>
        </div>
    </div>
</div>

                </div>
            </div> <!-- container-fluid -->
        </div> <!-- page-content -->
    </div> <!-- main-content -->
</div> <!-- layout-wrapper -->

<?php include '../core/partials/footer.php'; ?>
<?php include '../core/partials/right-sidebar.php'; ?>

<?php include '../core/partials/vendor-scripts.php'; ?>

<!-- jquery.vectormap map -->
<script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
<script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>

<script src="../core/assets/js/app.js"></script>

