
<?php
require_once '../core/partials/config.php';
session_start();


$userId = $_SESSION['client_id'];

if ($_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}


// Check if there are pending requests for this user
$sqlCheckPending = "SELECT COUNT(*) AS pending_requests_count
                    FROM work_requests
                    WHERE client_id = $userId
                    AND status = 'Pending'";
$resultPending = $conn->query($sqlCheckPending);

if ($resultPending) {
    $row = $resultPending->fetch_assoc();
    $pendingRequestsCount = $row['pending_requests_count'];

    if ($pendingRequestsCount > 0) {
        $_SESSION['last_request_status'] = 'Pending';
    } else {
        unset($_SESSION['last_request_status']); // Clear the session variable if no pending requests
    }
} else {
    echo "Error: " . $conn->error;
}

// Check the status of the last request
$sqlCheckLastRequest = "SELECT status
                        FROM work_requests
                        WHERE client_id = $userId
                        ORDER BY date_of_req DESC, time_of_req DESC
                        LIMIT 1";
$resultLastRequest = $conn->query($sqlCheckLastRequest);

if ($resultLastRequest) {
    $lastRequest = $resultLastRequest->fetch_assoc();

    if ($lastRequest) {
        if ($lastRequest['status'] === 'Processed') {
            $_SESSION['last_request_status'] = 'Processed';
        } elseif ($lastRequest['status'] === 'Approved') {
            $_SESSION['last_request_status'] = 'Approved';
        } elseif ($lastRequest['status'] === 'Delivered') {
            $_SESSION['last_request_status'] = 'Delivered';
        } elseif ($lastRequest['status'] === 'Declined') {
            $_SESSION['last_request_status'] = 'Declined';
        }
    }
} else {
    echo "Error: " . $conn->error;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $requestingDept = mysqli_real_escape_string($conn, $_POST['requesting_dept']);
    $workRequested = isset($_POST['work_requested']) ? implode(", ", $_POST['work_requested']) : '';
    $descriptionOfWorkRequest = mysqli_real_escape_string($conn, $_POST['description_of_work_request']);
    $othersDetail = isset($_POST['others_detail']) ? mysqli_real_escape_string($conn, $_POST['others_detail']) : '';

    // Insert into database
    $sqlInsert = "INSERT INTO work_requests (client_id, requesting_dept, date_of_req, time_of_req, work_requested, others_detail, description_of_work_request, action_taken, status, cisa_head_id)
                  VALUES ('$userId', '$requestingDept', CURDATE(), CURTIME(), '$workRequested', '$othersDetail', '$descriptionOfWorkRequest', '', 'Pending', NULL)";

    if ($conn->query($sqlInsert) === TRUE) {
        // Redirect to self after successful insertion
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $sqlInsert . "<br>" . $conn->error;
    }
}


?>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Get today's date and time
    const today = new Date();
    const date = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
    const time = ('0' + today.getHours()).slice(-2) + ':' + ('0' + today.getMinutes()).slice(-2);

    // Set date and time inputs
    document.getElementById('html5-date-input').value = date;
    document.getElementById('html5-time-input').value = time;
  });
</script>


            <style>
              /* General Form Styles */
form {
    font-family: Arial, sans-serif;

    padding: 20px;
    border-radius: 1px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Section Headers */
h4, h6 {
    color: #495057;
    font-weight: bold;
}

h4 {
    font-size: 24px;
    margin-bottom: 1rem;
}

h6 {
    font-size: 16px;
    margin-bottom: 0.5rem;
}

/* Input Fields */
input[type="text"], 
input[type="date"], 
input[type="time"], 
textarea, 
.form-control {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 5px;
    font-size: 14px;
    width: 100%;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus, 
input[type="date"]:focus, 
input[type="time"]:focus, 
textarea:focus, 
.form-control:focus {
    border-color: #007bff;
  
}

/* Read-Only Fields */
input[readonly], 
textarea[readonly] {
    background-color: #e9ecef;
    border-color: #ced4da;
    color: #6c757d;
}

/* Checkbox Styling */
.form-check-label {
    margin-left: 8px;
    font-size: 14px;
    color: #495057;
   background-color: transparent !important;
}

.form-check-input:checked {
    background-color: #007bff !important;
    border-color: #007bff;
}

/* Button Styling */
button[type="submit"] {
    background-color: #007bff;
    color: #fff;
    padding: 10px 20px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

.gray-border {
  border: 1px solid black; /* Light gray border */
  border-radius: 0.25rem;    /* Optional: Rounds the corners slightly */
}


/* Aligning Labels and Icons */
.form-label {
    display: flex;
    align-items: center;
    font-weight: bold;
    color: #495057;
}

.input-group-text {
    background-color: #e9ecef;
    border: 1px solid #ced4da;
}

/* Checkbox Container */
.row .col-sm-6 {
 
    border-radius: 8px;
}

textarea {
    min-height: 80px;
    resize: vertical;
}

/* Responsive Layout */
@media (max-width: 768px) {
    .container-xxl, .container-fluid {
        padding: 0 10px;
    }

    .form-label {
        font-size: 14px;
    }

    button[type="submit"] {
        width: 100%;
        font-size: 14px;
        padding: 8px;
    }
}

            </style>


    <head>
        
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

    </body>
</html>
