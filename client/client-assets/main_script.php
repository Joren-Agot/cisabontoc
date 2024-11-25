<?php
include('client-assets/m_script.php');
require_once '../core/partials/config.php';


// Retrieve data from pc_info where condition is 'Working'
$sqlFetchPCInfo = "SELECT pc_id, pc, serial_no, user, dept, tech FROM pc_info WHERE `condition` = 'Working'";
$resultPCInfo = $conn->query($sqlFetchPCInfo);

$pcInfo = array();
if ($resultPCInfo && $resultPCInfo->num_rows > 0) {
    $pcInfo = $resultPCInfo->fetch_assoc();
}

// Retrieve status and details from status_details where condition is 'Working' and pc_id matches
$sqlFetchStatusDetails = "SELECT row_id, status, details FROM status_details WHERE `condition` = 'Working' AND pc_id = ?";
$stmtStatusDetails = $conn->prepare($sqlFetchStatusDetails);
$stmtStatusDetails->bind_param('i', $pcInfo['pc_id']);
$stmtStatusDetails->execute();
$resultStatusDetails = $stmtStatusDetails->get_result();

$statusDetails = array();
if ($resultStatusDetails && $resultStatusDetails->num_rows > 0) {
    while ($row = $resultStatusDetails->fetch_assoc()) {
        $statusDetails[$row['row_id']][$row['status']] = [
            'details' => $row['details']
        ];
    }
}

$stmtStatusDetails->close();

// Handle the AJAX request to save the selected cell and details
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] === 'application/json') {
    // Decode JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    $row = isset($input['row']) ? $input['row'] : null;
    $cell = isset($input['cell']) ? $input['cell'] : null;
    $details = isset($input['details']) ? $input['details'] : null;

    if ($row !== null && $cell !== null && $details !== null) {
        // Fetch pc_id from pc_info based on condition 'Working'
        $pcId = $pcInfo['pc_id'];

        // Check if a row with the same row_id exists for the pc_id and condition is 'Working'
        $sqlCheck = "SELECT id FROM status_details WHERE row_id = ? AND pc_id = ? AND `condition` = 'Working'";
        $stmtCheck = $conn->prepare($sqlCheck);
        $stmtCheck->bind_param('ii', $row, $pcId);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();

        if ($result->num_rows > 0) {
            // Update the existing row with pc_id
            $sqlUpdate = "UPDATE status_details SET status = ?, details = ? WHERE row_id = ? AND pc_id = ?";
            $stmt = $conn->prepare($sqlUpdate);
            $stmt->bind_param('ssii', $cell, $details, $row, $pcId);
        } else {
            // Insert a new row with pc_id
            $sqlInsert = "INSERT INTO status_details (row_id, status, details, `condition`, pc_id) VALUES (?, ?, ?, 'Working', ?)";
            $stmt = $conn->prepare($sqlInsert);
            $stmt->bind_param('issi', $row, $cell, $details, $pcId);
        }

        $response = array();

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['success'] = false;
            $response['error'] = $stmt->error;
        }

        echo json_encode($response);

        $stmt->close();
    } else {
        $response = array(
            'success' => false,
            'error' => 'Invalid row, cell, or details data'
        );
        echo json_encode($response);
    }

    exit(); // Ensure to exit after handling AJAX request
}

// Insert or update PC details into pc_info table
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $pc = isset($_POST['pc']) ? $_POST['pc'] : '';
    $serial_no = isset($_POST['serial_no']) ? $_POST['serial_no'] : '';
    $user = isset($_POST['user']) ? $_POST['user'] : '';
    $dept = isset($_POST['dept']) ? $_POST['dept'] : '';
    $tech = isset($_POST['tech']) ? $_POST['tech'] : '';
    $date = date('Y-m-d'); // Automatically generate current date in YYYY-MM-DD format

    // Ensure client_id is available
    if (!isset($_SESSION["client_id"])) {
        header("Location: ../index.php");
        exit();
    }
    $userId = $_SESSION['client_id'];

    // Check if a record with condition 'Working' exists
    $sqlCheckPC = "SELECT pc_id FROM pc_info WHERE `condition` = 'Working' AND client_id = ?";
    $stmtCheckPC = $conn->prepare($sqlCheckPC);
    $stmtCheckPC->bind_param('i', $userId);
    $stmtCheckPC->execute();
    $resultPC = $stmtCheckPC->get_result();

    if ($resultPC && $resultPC->num_rows > 0) {
        // Update existing record (assuming only one 'Working' record exists at a time)
        $sqlUpdatePC = "UPDATE pc_info SET pc = ?, serial_no = ?, user = ?, dept = ?, tech = ?, date = ? WHERE `condition` = 'Working' AND client_id = ?";
        $stmtPC = $conn->prepare($sqlUpdatePC);
        $stmtPC->bind_param('ssssssi', $pc, $serial_no, $user, $dept, $tech, $date, $userId);
    } else {
        // Insert new record
        $sqlInsertPC = "INSERT INTO pc_info (pc, serial_no, user, dept, tech, date, `condition`, client_id)
                        VALUES (?, ?, ?, ?, ?, ?, 'Working', ?)";
        $stmtPC = $conn->prepare($sqlInsertPC);
        $stmtPC->bind_param('ssssssi', $pc, $serial_no, $user, $dept, $tech, $date, $userId);
    }

    $pcInsertSuccess = false;

    if ($stmtPC->execute()) {
        $pcInsertSuccess = true;
    } else {
        echo "Error inserting/updating PC details: " . $stmtPC->error;
    }

    $stmtPC->close();
}

?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusDetails = <?php echo json_encode($statusDetails); ?>;

    Object.keys(statusDetails).forEach(rowId => {
        const rowStatuses = statusDetails[rowId];
        Object.keys(rowStatuses).forEach(status => {
            const cell = document.querySelector(`td[data-row='${rowId}'][data-cell='${status}']`);
            if (cell) {
                let color;
                switch (status) {
                    case 'ok':
                        color = '#8BC34A';
                        break;
                    case 'repair':
                        color = '#FF5722';
                        break;
                    case 'na':
                        color = '#607D8B';
                        break;
                }
                if (color) {
                    const dropdownItem = cell.querySelector(`.dropdown-item.select-color[data-color='${color}']`);
                    if (dropdownItem) {
                        dropdownItem.click();
                    }
                }
            }
        });
    });
});
</script>

<style>
        .vertical-text {
            writing-mode: vertical-rl; /* Rotate text vertically */
            transform: rotate(180deg); /* Compatibility for older browsers */
            text-align: center; /* Center text */
        }

        .item-row {
            background-color: #c6c6c6; /* Gray background */
        }
        .status-cell {
    position: relative;
}
.status-content {
    display: flex;
    align-items: center;
}
.dropdown {
    position: relative;
}
.dropdown-toggle {
    background: none;
    border: none;
    cursor: pointer;
    display: none; /* Hide dropdown toggle button initially */
}
.status-cell:hover .dropdown-toggle {
    display: inline-block; /* Display dropdown toggle on hover */
}

.color-badge {
    display: inline-block;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    margin-right: 5px;
}

.color-dropdown {
    position: absolute;
    top: calc(100% + 5px);
    left: 50%;
    transform: translateX(-50%);
    display: none;
    z-index: 1000; /* Ensure dropdown is above other content */
    background-color: #fff;
    padding: 5px;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
}

.dropdown-menu.open {
    display: block;
}

.dropdown-item {
    display: block;
    text-decoration: none;
    color: #333;
    cursor: pointer;
}

.status-cell:hover .dropdown,
.status-cell.clicked .dropdown {
    display: block; /* Show dropdown on hover or click */
}

/* Add this CSS to adjust the padding and width of specific cells */
.adjust-left {
    padding-right: 10px; /* Adjust the padding to move the line */
    width: 100px; /* Adjust width as needed */
}

.adjust-left-input {
    width: calc(100% - 10px); /* Adjust input width to fit within the cell */
}

.dropdown-item.disabled {
    pointer-events: none; /* Disable pointer events */
    opacity: 0.5; /* Reduce opacity to indicate disabled state */
    color: #999 !important;/* Pale text color */
}

/* Modal Styles */
/* Adjusted CSS for modal */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5); /* Black background with transparency */
}

.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    max-width: 400px; /* Adjusted maximum width */
    position: relative;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    text-align: center; /* Center text content */
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
}

/* Adjusted button style */
#saveInputDetailsBtn {
    padding: 10px 20px; /* Adjusted padding */
    margin-top: 10px; /* Spacing from textarea */
    background-color: #007bff; /* Button color */
    color: white; /* Button text color */
    border: none;
    cursor: pointer;
    border-radius: 4px; /* Rounded corners */
    transition: background-color 0.3s;
}

#saveInputDetailsBtn:hover {
    background-color: #0056b3; /* Darker color on hover */
}

        .disabled-row {
            color: rgba(0, 0, 0, 0.5);
            pointer-events: none;
        }

        .enabled-row {
            color: rgba(0, 0, 0, 1);
            pointer-events: auto;
        }

    .disabled-row {
        background-color: #e0e0e0; /* Darker gray background color for disabled rows */
    }

    .enable-animation {
        position: relative; /* Ensure position for absolute gradient animation */
        overflow: hidden; /* Hide overflowing content */
        background-color: #e0e0e0; /* Darker gray background color */
        animation: fadeOutBackground 2s forwards, gradientFadeOut 2s 1s forwards; /* Fade-out animation */
    }

    @keyframes fadeOutBackground {
        from {
            background-color: #e0e0e0; /* Start with darker gray */
        }
        to {
            background-color: transparent; /* Fade out to transparent */
        }
    }

    @keyframes gradientFadeOut {
        from {
            background-size: 100% 100%; /* Start with full size */
        }
        to {
            background-size: 200% 200%; /* Zoom out to double size */
            background-position: 100% 0; /* Slide gradient out to the left */
        }
    }


    /* Adjust toast styles */
    .toast {
        position: absolute;
        top: 0; /* Adjust top position as needed */
        left: 0; /* Adjust left position as needed */
        max-width: 400px; /* Adjust max-width as needed */
        max-height: 400px; /* Adjust max-height to limit toast size */
        overflow: hidden;
        z-index: 1000; /* Ensure toast is above other elements */
    }

    /* Adjust toast-body for better text alignment */
    .toast-body {
        padding: 0.5rem; /* Adjust padding as needed */
    }
        .flipped-icon {
        transform: scaleX(-1); /* Flip horizontally */
        /* or */
        /* transform: scaleY(-1); */ /* Flip vertically */
    }
</style>