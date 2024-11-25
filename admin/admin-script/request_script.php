<?php
require_once '../core/partials/config.php';
session_start();

$userId = $_SESSION['admin_id'];

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle status update request
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['id']) && isset($data['status'])) {
        $id = $data['id'];
        $status = $data['status'];

        // Get the current date and time for approval date
        $approvalDate = date('Y-m-d H:i:s');  // Current date and time

        // Update status in work_requests table
        $stmt = $conn->prepare("UPDATE work_requests SET status = ?, approved_date = ? WHERE id = ?");
        $stmt->bind_param('ssi', $status, $approvalDate, $id);  // Bind the approval date

        if ($stmt->execute()) {
            // If the status is Approved or Declined, perform the following actions
            if ($status == 'Approved' || $status == 'Declined') {
                // Update message_status to "Read"
                $stmt2 = $conn->prepare("UPDATE work_requests SET message_status = 'Read' WHERE id = ?");
                $stmt2->bind_param('i', $id);
                $stmt2->execute();

                // Update cisa_head_id to the current logged-in user (admin) when approved
                $stmt3 = $conn->prepare("UPDATE work_requests SET cisa_head_id = ? WHERE id = ?");
                $stmt3->bind_param('ii', $userId, $id);
                $stmt3->execute();
            }

            // Update status in job_order table
            $stmt4 = $conn->prepare("UPDATE job_order SET status = ? WHERE id = ?");
            $stmt4->bind_param('si', $status, $id);

            if ($stmt4->execute()) {
                // Update message_status to "Read" in job_order if status is Approved or Declined
                if ($status == 'Approved' || $status == 'Declined') {
                    $stmt5 = $conn->prepare("UPDATE job_order SET message_status = 'Read' WHERE id = ?");
                    $stmt5->bind_param('i', $id);
                    $stmt5->execute();
                }
            }

            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update status']);
        }
        $stmt->close();
        exit;
    }
}

$sqlFetchPendingJobOrders = "SELECT id, requesting_dept, date_of_req, time_of_req, work_type, type, action_taken, work_requested
                             FROM job_order
                             WHERE status = 'Pending'";

$sqlFetchPendingRequests = "SELECT id, requesting_dept, date_of_req, time_of_req, action_taken, description_of_work_request, work_requested
                            FROM work_requests
                            WHERE status = 'Pending'";

// Get the sort parameter from the URL
$sortBy = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date'; // Default to sorting by date

// Modify your query to handle dynamic sorting for both job orders and work requests
if ($sortBy == 'date') {
    $sqlFetchPendingJobOrders .= " ORDER BY date_of_req DESC"; // Sort by date descending
    $sqlFetchPendingRequests .= " ORDER BY date_of_req DESC"; // Sort by date descending
} elseif ($sortBy == 'dept') {
    $sqlFetchPendingJobOrders .= " ORDER BY requesting_dept ASC"; // Sort by department ascending
    $sqlFetchPendingRequests .= " ORDER BY requesting_dept ASC"; // Sort by department ascending
} else {
    // Default or fallback sorting (can modify as needed)
    $sqlFetchPendingJobOrders .= " ORDER BY date_of_req DESC"; // Default to sorting by date
    $sqlFetchPendingRequests .= " ORDER BY date_of_req DESC"; // Default to sorting by date
}

// Execute both queries
$resultPendingJobOrders = $conn->query($sqlFetchPendingJobOrders);
$resultPendingRequests = $conn->query($sqlFetchPendingRequests);
?>


<style type="text/css">


.unread.d-flex {
    flex-wrap: wrap; /* Allow wrapping on smaller screens */
}

.col-mail-1, .col-mail-2 {
    flex: 1; /* Make both columns take equal space */
    min-width: 0; /* Allow content to shrink within the space */
}

.col-mail-2 .date {
    white-space: nowrap; /* Prevent wrapping for the date */
    overflow: hidden;
    text-overflow: ellipsis; /* Add ellipsis if the date is too long */
    font-size: 0.85rem; /* Adjust font size if needed */
}

@media (max-width: 576px) {
    /* Smaller screen adjustments */
    .col-mail-2 .subject {
        font-size: 0.85rem;
    }
    .col-mail-2 .date {
        font-size: 0.75rem; /* Reduce date font size for mobile */
    }
}


</style>