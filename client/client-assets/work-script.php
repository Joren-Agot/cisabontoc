
<?php
require_once '../core/partials/config.php';
session_start();
if (isset($_SESSION['last_request_status'])) {
    echo "Session Status: " . $_SESSION['last_request_status']; // Check if status is set
}

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
        $_SESSION['last_request_status'] = 'Pending'; // Set session variable if there are pending requests
    } else {
        unset($_SESSION['last_request_status']); // Clear the session variable if no pending requests
    }
} else {
    echo "Error: " . $conn->error;
}

// Check if the last request status is Approved
$sqlCheckLastRequest = "SELECT status
                        FROM work_requests
                        WHERE client_id = $userId
                        ORDER BY date_of_req DESC, time_of_req DESC
                        LIMIT 1";

$resultLastRequest = $conn->query($sqlCheckLastRequest);

if ($resultLastRequest) {
    $lastRequest = $resultLastRequest->fetch_assoc();

    if ($lastRequest) {
        if ($lastRequest['status'] === 'Approved') {
            $_SESSION['last_request_status'] = 'Approved'; // Update session variable if last request was approved
        } elseif ($lastRequest['status'] === 'Declined') {
            $_SESSION['last_request_status'] = 'Declined'; // Update session variable if last request was declined
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




<style>
  .menu-item:hover {
    background-color: lightblue;
  }
  body.dark-mode {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .menu-inner.dark-mode {
            background-color: #252525;
            color: #ffffff;
        }

        .card.dark-mode {
            background-color: #2c2c2c;
            color: #ffffff;
        }

.content-wrapper {
    position: relative;
    z-index: 1; /* Ensure the content wrapper is above the backdrop */
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Adjust backdrop opacity */
    z-index: 1; /* Place the backdrop behind the content */
}

</style>

                            <!-- Modal Pending -->
                            <div class="modal fade" id="modalPending" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalPendingTitle">Pending</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <p id="modalPendingContent">Your request is still pending. Please wait for approval.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" id="closeModalBtnPending" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Approved -->
                            <div class="modal fade" id="modalApproved" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalApprovedTitle">Approved</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <p id="modalApprovedContent">Your request has been approved.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" id="closeModalBtnApproved" data-bs-dismiss="modal" onclick="window.location.href='dashboard_client.php'">
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary" id="proceedModal">
                                                Proceed
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Declined -->
                            <div class="modal fade" id="modalDeclined" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDeclinedTitle">Declined</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <p id="modalDeclinedContent">Your request has been declined. Please contact support for further assistance.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" id="closeModalBtnDeclined" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
<script>
    $(document).ready(function() {
        // Directly show the modal to see if it's working
        $('#modalPending').modal('show');
    });
</script>

<script>
$(document).ready(function() {
    // Function to show modal and prevent backdrop close
    function showModal(modalId) {
        $(modalId).modal({
            backdrop: 'static', // Prevent closing on backdrop click
            keyboard: false // Prevent closing when pressing escape key
        });
        $(modalId).modal('show');
    }

    // Check if session variable 'last_request_status' is set and is 'Pending', 'Approved', or 'Declined'
    <?php if (isset($_SESSION['last_request_status']) && $_SESSION['last_request_status'] === 'Pending'): ?>
        // Show the pending modal
        showModal('#modalPending');
    <?php elseif (isset($_SESSION['last_request_status']) && $_SESSION['last_request_status'] === 'Approved'): ?>
        // Show the approved modal
        showModal('#modalApproved');
    <?php elseif (isset($_SESSION['last_request_status']) && $_SESSION['last_request_status'] === 'Declined'): ?>
        // Show the declined modal
        showModal('#modalDeclined');
    <?php endif; ?>

    // Handle the proceed button action
    $('#closeModalBtnApproved').on('click', function() {
        // Redirect to dashboard_client.php
        window.location.href = 'dashboard_client.php';
    });

    $('#proceedModal').on('click', function() {
        // Redirect to request_review.php
        window.location.href = 'request_review.php';
    });

    // Add event listener when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent modal from closing on backdrop click for all modals
        $('#modalPending, #modalApproved, #modalDeclined').on('hide.bs.modal', function(e) {
            if (e.target === this) {
                e.preventDefault(); // Prevent closing the modal
            }
        });
    });

    // Handle the close button action for the Declined modal
    $('#closeModalBtnDeclined').on('click', function() {
        // Update the status to 'Done'
        $.ajax({
            url: 'update_status.php', // Change this URL to the PHP file that will handle the status update
            type: 'POST',
            data: {
                client_id: '<?php echo $userId; ?>',
                status: 'Done'
            },
            success: function(response) {
                // Handle success response
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
            }
        });
    });
});
</script>

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

<script>
  // JavaScript for handling single checkbox selection and enabling/disabling textarea
  document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.work-request');
    const othersCheckbox = document.getElementById('others');
    const othersTextarea = document.getElementById('others-detail');

    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        // Uncheck all other checkboxes except the current one
        if (this.checked) {
          checkboxes.forEach(cb => {
            if (cb !== this) {
              cb.checked = false;
            }
          });
        }

        // Enable/disable textarea based on the Others checkbox
        othersTextarea.disabled = !othersCheckbox.checked;
        if (!othersCheckbox.checked) {
          othersTextarea.value = ''; // Clear textarea value if Others is unchecked
        }
      });
    });

    // Additional event listener for Others checkbox to handle initial state
    othersCheckbox.addEventListener('change', function() {
      othersTextarea.disabled = !this.checked;
      if (!this.checked) {
        othersTextarea.value = ''; // Clear textarea value if Others is unchecked
        }
      });

      // Initialize state based on initial checkbox state
      othersTextarea.disabled = !othersCheckbox.checked;

      // Event listener for Others checkbox change
      othersCheckbox.addEventListener('change', function() {
        checkboxes.forEach(cb => {
          if (cb !== othersCheckbox) {
            cb.checked = false; // Uncheck other checkboxes if Others is checked
          }
        });

        othersTextarea.disabled = !this.checked;
        if (!this.checked) {
          othersTextarea.value = ''; // Clear textarea value if Others is unchecked
        }
      });
    });
</script>

            <style>
              /* General Form Styles */
form {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
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

/* Card Styling */
.card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    margin-bottom: 20px;
    background-color: #fff;
    padding: 20px;
}

.card-header {
    font-size: 18px;
    font-weight: bold;
    color: #495057;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 10px;
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
    background-color: #f8f9fa;
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