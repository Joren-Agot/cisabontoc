<?php
require_once '../core/partials/config.php';


$userId = $_SESSION['client_id'];

if ($_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}


// Fetch the latest approved request details
$sqlFetchApprovedRequest = "SELECT requesting_dept, date_of_req, time_of_req, action_taken, cisa_head_id, description_of_work_request
                           FROM work_requests
                           WHERE client_id = $userId
                             AND status = 'Approved'
                           ORDER BY id DESC
                           LIMIT 1";

$resultApprovedRequest = $conn->query($sqlFetchApprovedRequest);

if ($resultApprovedRequest) {
    if ($resultApprovedRequest->num_rows > 0) {
        $approvedRequestData = $resultApprovedRequest->fetch_assoc();

        $requestingDept = $approvedRequestData['requesting_dept'];
        $dateOfReq = $approvedRequestData['date_of_req'];
        $timeOfReq = $approvedRequestData['time_of_req'];
        $actionTaken = $approvedRequestData['action_taken'];
        $cisaHeadId = $approvedRequestData['cisa_head_id'];
        $descriptionOfWorkRequest = $approvedRequestData['description_of_work_request'];

        // Assuming you have a way to fetch CISA Head's name based on $cisaHeadId
        // You would fetch it from another table where you store CISA Head details
        // For now, we'll use a placeholder value
        $approvedBy = "CISA Head"; // Placeholder value
    } else {
        echo "No approved request found for user ID $userId";
    }
} else {
    echo "Error: " . $conn->error;
}

?>






<style type="text/css">
    input[type="text"]:focus, 
input[type="date"]:focus, 
input[type="time"]:focus, 
textarea:focus, 
.form-control:focus {
    border-color: #007bff;
  
}
</style>






    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
        
        <!-- jvectormap -->
        <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

        <?php include '../core/partials/head-css.php'; ?>

    </head>

    <?php include '../core/partials/body.php'; ?>


            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <?php include '../core/partials/right-sidebar.php'; ?>

        <?php include '../core/partials/vendor-scripts.php'; ?>


        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>