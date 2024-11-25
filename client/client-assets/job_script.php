<?php
require_once '../core/partials/config.php';
session_start();

$userId = $_SESSION['client_id'];

if ($_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}

// Check if there are pending job orders for this user
$sqlCheckPending = "SELECT COUNT(*) AS pending_job_orders_count
                    FROM job_order
                    WHERE client_id = $userId
                    AND status = 'Pending'";

$resultPending = $conn->query($sqlCheckPending);

if ($resultPending) {
    $row = $resultPending->fetch_assoc();
    $pendingJobOrdersCount = $row['pending_job_orders_count'];

    if ($pendingJobOrdersCount > 0) {
        $_SESSION['last_job_order_status'] = 'Pending'; // Set session variable if there are pending job orders
    } else {
        unset($_SESSION['last_job_order_status']); // Clear the session variable if no pending job orders
    }
} else {
    echo "Error: " . $conn->error;
}

// Handle form submission for inserting new job orders (if needed)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize inputs
    $requestingDept = mysqli_real_escape_string($conn, $_POST['requesting_dept']);
    $nameOfRequestor = mysqli_real_escape_string($conn, $_POST['name_of_requestor']);
    $workType = mysqli_real_escape_string($conn, $_POST['work_type']);
    $othersDetail = isset($_POST['others_detail']) ? mysqli_real_escape_string($conn, $_POST['others_detail']) : '';

    // Initialize arrays to store selected work requests
    $workRequested = [];

    // Check if work requested checkboxes are selected
    if (isset($_POST['work_requested'])) {
        foreach ($_POST['work_requested'] as $workRequest) {
            $workRequested[] = mysqli_real_escape_string($conn, $workRequest);
        }
    }

    // Determine the type based on work requested and work type
    $type = null; // Default to NULL

    foreach ($workRequested as $work) {
        if (in_array($work, ['Software Printing', 'System Development/Enhancement', 'Printer', 'Others']) && $workType === 'CORRECTIVE_MAINTENANCE') {
            $type = 'SOFTWARE'; // Set to SOFTWARE if it's CORRECTIVE MAINTENANCE and software-related work is selected
            break;
        } elseif ($work === 'Others' && $workType === 'PREVENTIVE_MAINTENANCE') {
            $type = null; // Set type to NULL if 'Others' is selected in PREVENTIVE_MAINTENANCE
            break; // Exit the loop once 'Others' is found in PREVENTIVE_MAINTENANCE
        } else {
            // Default to HARDWARE for other cases
            $type = 'HARDWARE';
        }
    }

    // Combine work requested into comma-separated string
    $workRequestedString = implode(", ", $workRequested);

    // Insert job order into database
    $sqlInsertJobOrder = "INSERT INTO job_order (client_id, requesting_dept, date_of_req, time_of_req, work_requested, others_detail, name_of_requestor, status, work_type, type)
                          VALUES ('$userId', '$requestingDept', CURDATE(), CURTIME(), '$workRequestedString', '$othersDetail', '$nameOfRequestor', 'Pending', '$workType', '$type')";

    if ($conn->query($sqlInsertJobOrder) === TRUE) {
        // Redirect to self after successful insertion
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $sqlInsertJobOrder . "<br>" . $conn->error;
    }
}

// Check if the last job order status is Approved or Declined
$sqlCheckLastJobOrder = "SELECT id, status
                         FROM job_order
                         WHERE client_id = $userId
                         ORDER BY date_of_req DESC, time_of_req DESC
                         LIMIT 1";

$resultLastJobOrder = $conn->query($sqlCheckLastJobOrder);

if ($resultLastJobOrder) {
    $lastJobOrder = $resultLastJobOrder->fetch_assoc();

    if ($lastJobOrder) {
        $lastJobOrderId = $lastJobOrder['id'];
        $lastJobOrderStatus = $lastJobOrder['status'];

        // Check if the status is Approved or Declined
        if ($lastJobOrderStatus === 'Approved' || $lastJobOrderStatus === 'Declined') {
            // Set the session variable
            $_SESSION['last_job_order_status'] = $lastJobOrderStatus;
            // Buffer the output for the modal
            ob_start();
?>
            <!-- Modal for Approved or Declined Status -->
            <div id="jobOrderModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <p>Your request has been <?php echo strtolower($lastJobOrderStatus); ?>.</p>
                    <button id="okButton">OK</button>
                    <!-- Additional details or action can be added here -->
                </div>
            </div>

            <script>
                // Modal script to display and handle interactions
                var modal = document.getElementById('jobOrderModal');
                var okButton = document.getElementById('okButton');

                okButton.onclick = function() {
                    // Handle OK button action (e.g., close modal, redirect, etc.)
                    modal.style.display = 'none';
                }

                // Close the modal if the user clicks outside of it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = 'none';
                    }
                }

                // Automatically close modal after 5 seconds (optional)
                setTimeout(function() {
                    modal.style.display = 'none';
                }, 5000);
            </script>
<?php
            // Update message_status to "Read" for the approved or declined job order
            $sqlUpdateMessageStatus = "UPDATE job_order SET message_status = 'Read' WHERE id = $lastJobOrderId";
            if ($conn->query($sqlUpdateMessageStatus) !== TRUE) {
                echo "Error updating message status: " . $conn->error;
            }

            // End output buffering and send output
            ob_end_flush();
        }
    } else {
        echo "Error: No last job order found for user ID $userId";
    }
} else {
    echo "Error: " . $conn->error;
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

    // Function to handle "Others" checkboxes and textareas
    function handleOthersCheckbox(othersCheckboxId, othersTextareaId) {
      const othersCheckbox = document.getElementById(othersCheckboxId);
      const othersTextarea = document.getElementById(othersTextareaId);

      if (!othersCheckbox || !othersTextarea) return; // Check if elements exist

      othersCheckbox.addEventListener('change', function() {
        othersTextarea.disabled = !this.checked;
        if (!this.checked) {
          othersTextarea.value = '';
        }
      });

      othersTextarea.disabled = !othersCheckbox.checked;
    }

    // Handle "Others" checkboxes and textareas for JOB DESCRIPTION
    handleOthersCheckbox('others', 'others-detail');

    // Handle "Others" checkboxes and textareas for CORRECTIVE MAINTENANCE
    handleOthersCheckbox('hardware-others', 'hardware-others-detail');
    handleOthersCheckbox('software-others', 'software-others-detail');

    // Function to handle radio button change events
    function handleRadioChange() {
      const jobDescriptionRadio = document.getElementById('jobDescriptionRadio');
      const correctiveMaintenanceRadio = document.getElementById('correctiveMaintenanceRadio');
      const jobDescriptionCheckboxes = document.querySelectorAll('.job-description-check');
      const correctiveMaintenanceCheckboxes = document.querySelectorAll('.corrective-maintenance-check');

      // Initially disable all checkboxes
      jobDescriptionCheckboxes.forEach(cb => cb.disabled = true);
      correctiveMaintenanceCheckboxes.forEach(cb => cb.disabled = true);

      // Function to disable all checkboxes except the specified one
      function disableOtherCheckboxes(checkboxes, selectedCheckbox) {
        checkboxes.forEach(cb => {
          if (cb !== selectedCheckbox) {
            cb.checked = false;
          }
        });
      }

      // Event listeners for JOB DESCRIPTION radio button and checkboxes
      jobDescriptionRadio.addEventListener('change', function() {
        if (this.checked) {
          jobDescriptionCheckboxes.forEach(cb => {
            cb.disabled = false;
          });
          correctiveMaintenanceCheckboxes.forEach(cb => {
            cb.checked = false;
            cb.disabled = true;
          });
        }
      });

      jobDescriptionCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
          if (this.checked) {
            disableOtherCheckboxes(jobDescriptionCheckboxes, this);
          }
        });
      });

      // Event listeners for CORRECTIVE MAINTENANCE radio button and checkboxes
      correctiveMaintenanceRadio.addEventListener('change', function() {
        if (this.checked) {
          correctiveMaintenanceCheckboxes.forEach(cb => {
            cb.disabled = false;
          });
          jobDescriptionCheckboxes.forEach(cb => {
            cb.checked = false;
            cb.disabled = true;
          });
        }
      });

      correctiveMaintenanceCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
          if (this.checked) {
            disableOtherCheckboxes(correctiveMaintenanceCheckboxes, this);
          }
        });
      });
    }

    // Call the function to handle radio button change events
    handleRadioChange();
  });
</script>

            <style>
              /* General Form Styles */
form {
    font-family: Arial, sans-serif;
    background-color: white;
    padding: 20px;
    border: 1px solid gray;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Section Headers */
h4, h6 {
    color: white;
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
    padding: 10px;
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
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
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
    background-color: white;
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
