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
        border: 1px solid gray;
        border-radius: 10px;
        padding: 10px;
    }

    .progress-container {
        position: relative;
        margin: 20px 0;
        display: flex;
        flex-direction: row;
    }

    .progress {
        height: 5px;
        width: 100%;
        left: 10px;
        background-color: #e9ecef;
        position: relative;
    }

    .progress-bar {
        height: 5px;
        background: linear-gradient(to right, #00c6ff, #0072ff);
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
        left: -10px;
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
    width: 80%; /* Keep it at 100% width */
    height: 5px; /* Keep the height consistent */
    background-color: #e9ecef;
    margin: 0 auto;
    transform: rotate(90deg);
    transform-origin: left center; /* Adjust rotation point to keep it aligned */
    top: -5px;
    left: -33px; /* Ensure it doesn't go out of the card */
}


    .progress-bar {
        width: 100%;
        height: 5px;
        background-color: #3498db;
        transition: width 0.3s ease;
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
/* Ensure indicator 1 and 2 are in separate rows */
.indicator-container {
    display: flex;
    width: 100%;
    flex-direction: column; /* Arrange the indicators in a vertical column */
    margin-bottom: 10px; /* Add space between rows */
    position: relative; /* Make the container relative for absolute positioning */
}

.indicator {
    margin-left: 15px;
    text-align: left;
    width: 100%; /* Ensure it fits within the container */
    margin-bottom: 35px; /* Space between indicator 1 and 2 */
}

.indicator-2 {
    position: absolute; /* Make it independent of the flow */
    text-align: left;
    left: 0; /* Adjust this value to align it to the left side */
    top: -20%; /* Position it below the first indicator */
    margin-left: 40px; /* Adjust for fine-tuning */
    width: 200%; /* Ensure it fits within the container */
    margin-bottom: 0px; /* Space below the second indicator */
    margin-top: 40px; /* Add top margin if you want */
    z-index: 10; /* Ensure it stays above other elements */
}
}


</style>

<?php include '../core/partials/body.php'; ?>

<div id="layout-wrapper">
    <?php include '../core/partials/menu.php'; ?>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'CLIENT', 'title' => 'REQUEST PROGRESS')); ?>

                <?php
                $current_user_id = $_SESSION['client_id'];

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
               $progress_width = 0;

                if ($progress_level == 1) {
                    // For Pending, set a minimal progress (e.g., 10%)
                    $progress_width = 5;
                } elseif ($progress_level == 2) {
                    // For Approved, set a mid-level progress (e.g., 50%)
                    $progress_width = 35;
                } else {
                    // For other statuses, calculate the progress as usual
                    $progress_width = (($progress_level - 1) / (count($statuses) - 1)) * 100;
                }

                ?>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">OVERALL PROGRESS:</h5>
                        <div class="progress-container">
                            <div class="progress">
                                <div class="progress-bar" style="width: <?php echo $progress_width; ?>%;"></div>
                            </div>
                            <div class="status-buttons">
                                <?php
                                $indicators = [
                                    1 => ['text' => 'WORK REQUEST', 'date_field' => 'date_of_req'],
                                    2 => ['text' => 'APPROVED BY CISA', 'date_field' => 'approved_date'],
                                    3 => ['text' => 'PROCESSED', 'date_field' => 'processed_date'],
                                    4 => ['text' => 'DELIVERED', 'date_field' => null],
                                ];
                                foreach ($indicators as $level => $indicator) {
                                    $is_active = $progress_level >= $level;
                                    $btn_class = $is_active ? 'btn-primary' : 'btn-secondary';
                                    $border_color = $is_active ? '#007b8c' : '#adb5bd';
                                    $text_color = $is_active ? '#00e0ce' : '#6c757d';
                                    $date_text = $indicator['date_field'] && $row[$indicator['date_field']] ? date("Y-m-d", strtotime($row[$indicator['date_field']])) : 'Not Available';
                                ?>
                                <div class="status-button-container">
                                    <button class="btn btn-sm <?php echo $btn_class; ?> rounded-pill status-button" style="border: 2px solid <?php echo $border_color; ?>;"><?php echo $level ; ?></button>
                                    <div class="indicator" style="color: <?php echo $text_color; ?>;"><?php echo $indicator['text']; ?></div>
                                    <?php if ($indicator['date_field']) { ?>
                                    <div class="indicator-2"><?php echo ucfirst(str_replace('_', ' ', $indicator['date_field'])); ?>: <span style="color: red;"><?php echo $date_text; ?></span></div>
                                    <?php } ?>
                                </div>
                                <?php } ?>
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
            <form action="rate.php" method="">
                <h5>Request Information</h5>
                <p>Status: <?php echo isset($status) ? htmlspecialchars($status) : 'N/A'; ?></p>

                <?php if ($status === 'Delivered'): ?>
                    <!-- Form for 'Delivered' status with action to rate.php -->
                    <button type="submit" name="action" value="go" class="btn btn-success btn-block">Proceed</button>
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

