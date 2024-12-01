<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php'; ?>
<?php

include('client-assets/m_script.php');

if (!isset($_SESSION["client_id"])) {
    header("Location: ../index.php");
    exit();
}

$userId = $_SESSION['client_id'];

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

// Retrieve user data from client_info table
$sqlUserData = "SELECT first_name, middle_name, last_name, image_path
                FROM client_info
                WHERE client_id = $userId";

$resultUserData = $conn->query($sqlUserData);

if ($resultUserData) {
    if ($resultUserData->num_rows > 0) {
        $userData = $resultUserData->fetch_assoc();

        $firstName = $userData['first_name'];
        $middleName = $userData['middle_name'];
        $lastName = $userData['last_name'];
        $profileImage = $userData['image_path'];

        if ($profileImage === null) {
            $profileImage = '../base/img/avatars/profile_default.png';
        }
    }
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

    <head>
        
        
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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Upzet' , 'title' => 'MIS MAINTENANCE')); ?>
      <?php include 'client-assets/m_style.php'; ?>                 



                                    <!-- Content wrapper -->
                            <div class="content-wrapper">
                                <!-- Content -->
                        <div id="inputDetailsModal" class="modal">
                            <div class="modal-content">
                                <textarea id="inputDetailsTextArea" placeholder="Enter details..."></textarea>
                                <button id="saveInputDetailsBtn">Save</button>
                            </div>
                        </div>

                                <div class="container-xxl flex-grow-1 container-p-y">
                                    <div class="row">
                                        <div class="col-xxl">
                                        <div class=" mb-4">
                                            <div class="card">
                                                <div class="card-body">
             
                            <form method="post" action="">
                                <div class="card-body position-relative">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-header mb-0">Desktop/Laptop Maintenance</h5>
                                        <div class="d-flex align-items-center position-relative">
                                                                <!-- Toast Notification Template -->
                                            <i id="icon" class="menu-icon bx bx-message-error bx-tada-hover me-2 flipped-icon"></i>
                                            <button id="saveBtn" type="submit" class="btn btn-primary">Save Info</button>

                                        </div>
                                    </div>
                                </div>
                


                                <div class="table-responsive text-nowrap">
                            <table class="table table-bordered" style="max-width: 100%; table-layout: auto; word-wrap: break-word; white-space: normal;">
                                <thead>
                                    <tr class="item-row">
                                        <th>Title:</th>
                                        <th colspan="4">Desktop/Laptop Maintenance</th>
                                        <th colspan="5">Frequency: Yearly</th>
                                    </tr>
                                </thead>                            
                                <tbody>
                                    <tr>
                                        <td>PC:</td>
                                        <td colspan="3"><input type="text" class="form-control" id="pc_input" name="pc" value="<?= isset($pcInfo['pc']) ? $pcInfo['pc'] : '' ?>" required></td>
                                        <td>Serial No.:</td>
                                        <td colspan="5"><input type="text" class="form-control" id="serial_no_input" name="serial_no" value="<?= isset($pcInfo['serial_no']) ? $pcInfo['serial_no'] : '' ?>" required></td>
                                    </tr>
<tr>
    <td>User:</td>
    <td colspan="3"><input type="text" class="form-control" id="user_input" name="user" value="<?= isset($pcInfo['user']) ? $pcInfo['user'] : '' ?>" required></td>
    <td>Dept.:</td>
    <td colspan="5">
        <div class="position-relative">
            <select class="form-control custom-select" id="dept_input" name="dept" required>
                <option value="">Select Department</option>
                <option value="Registrar's Office" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "Registrar's Office" ? 'selected' : '' ?>>Registrar's Office</option>
                <option value="Cashier's Office" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "Cashier's Office" ? 'selected' : '' ?>>Cashier's Office</option>
                <option value="Accounting Office" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "Accounting Office" ? 'selected' : '' ?>>Accounting Office</option>
                <option value="Procurement's Office" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "Procurement's Office" ? 'selected' : '' ?>>Procurement's Office</option>
                <option value="Budget Officer's Office" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "Budget Officer's Office" ? 'selected' : '' ?>>Budget Officer's Office</option>
                <option value="Administrative Office" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "Administrative Office" ? 'selected' : '' ?>>Administrative Office</option>
                <option value="College Dean" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "College Dean" ? 'selected' : '' ?>>College Dean</option>
                <option value="SAS" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "SAS" ? 'selected' : '' ?>>SAS</option>
                <option value="RIES" <?= isset($pcInfo['dept']) && $pcInfo['dept'] === "RIES" ? 'selected' : '' ?>>RIES</option>
            </select>
        </div>
    </td>
</tr>

                                    <tr>
                                        <td>Tech:</td>
                                        <td colspan="3"><input type="text" class="form-control" id="tech_input" name="tech" value="<?= isset($pcInfo['tech']) ? $pcInfo['tech'] : '' ?>" required></td>
                                        <td>Date:</td>
                                        <td colspan="5">
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                    <input class="form-control" type="date" id="date_input" name="date" disabled />
                                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>

                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                    <tr class="item-row">
                                        <td>Item&nbsp;#</td>
                                        <td>Task</td>
                                        <td colspan="5">Description</td>
                                        <td class="vertical-text">OK</td>
                                        <td class="vertical-text">Repair</td>
                                        <td class="vertical-text">N/A</td>
                                    </tr>

    <tr id="row1">
        <td>1.</td>
        <td>System Boot</td>
        <td colspan="5">Boot system from a cold start. Monitor for errors and speed of entire boot process.</td>
        <td id="ok" data-row="1" data-cell="ok" data-status="ok" class="status-cell">
            <div class="status-content">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu color-dropdown">
                        <a class="dropdown-item select-color" data-row="1" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                        <a class="dropdown-item input-details" href="#">📄 Details</a>
                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                    </div>
                </div>
            </div>
        </td>
        <td id="repair" data-row="1" data-cell="repair" data-status="repair" class="status-cell">
            <div class="status-content">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu color-dropdown">
                        <a class="dropdown-item select-color" data-row="1" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                        <a class="dropdown-item input-details" href="#">📄 Details</a>
                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                    </div>
                </div>
            </div>
        </td>
        <td id="na" data-row="1" data-cell="na" data-status="na" class="status-cell">
            <div class="status-content">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button">
                        <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-menu color-dropdown">
                        <a class="dropdown-item select-color" data-row="1" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                        <a class="dropdown-item input-details" href="#">📄 Details</a>
                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                    </div>
                </div>
            </div>
        </td>
    </tr>
 

                                        <tr id="row2" class="disabled-row">
                                            <td>2.</td>
                                            <td>System Log-in</td>
                                            <td colspan="5">Monitor for Errors. Monitor login script.</td>
                                            <td id="ok" data-row="2" data-cell="ok" data-status="ok" class="status-cell">
                                                <div class="status-content">
                                                    <div class="dropdown">
                                                        <button class="dropdown-toggle" type="button">
                                                            <i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu color-dropdown">
                                                            <a class="dropdown-item select-color" data-row="2" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                            <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                            <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="repair" data-row="2" data-cell="repair" data-status="repair" class="status-cell">
                                                <div class="status-content">
                                                    <div class="dropdown">
                                                        <button class="dropdown-toggle" type="button">
                                                            <i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu color-dropdown">
                                                            <a class="dropdown-item select-color" data-row="2" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                            <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                            <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="na" data-row="2" data-cell="na" data-status="na" class="status-cell">
                                                <div class="status-content">
                                                    <div class="dropdown">
                                                        <button class="dropdown-toggle" type="button">
                                                            <i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu color-dropdown">
                                                            <a class="dropdown-item select-color" data-row="2" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                            <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                            <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr id="row3" class="disabled-row">
                                            <td rowspan="5">3.</td>
                                            <td rowspan="5">Network Settings</td>

                                            <td colspan="5">TCP/IP and/or IPX Settings are Correct</td>
                                            <td id="ok" data-row="3" data-cell="ok" data-status="ok" class="status-cell">
                                                <div class="status-content">
                                                    <div class="dropdown">
                                                        <button class="dropdown-toggle" type="button">
                                                            <i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu color-dropdown">
                                                            <a class="dropdown-item select-color" data-row="3" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                            <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                            <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="repair" data-row="3" data-cell="repair" data-status="repair" class="status-cell">
                                                <div class="status-content">
                                                    <div class="dropdown">
                                                        <button class="dropdown-toggle" type="button">
                                                            <i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu color-dropdown">
                                                            <a class="dropdown-item select-color" data-row="3" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                            <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                            <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="na" data-row="3" data-cell="na" data-status="na" class="status-cell">
                                                <div class="status-content">
                                                    <div class="dropdown">
                                                        <button class="dropdown-toggle" type="button">
                                                            <i class="fas fa-caret-down"></i>
                                                        </button>
                                                        <div class="dropdown-menu color-dropdown">
                                                            <a class="dropdown-item select-color" data-row="3" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                            <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                            <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <tr id="row4" class="disabled-row">
                                    <td colspan="5">Domain Name</td>
                                    <td id="ok" data-row="4" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="4" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="4" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="4" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="4" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="4" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row5" class="disabled-row">
                                            <td colspan="5">Security Settings</td>
                                    <td id="ok" data-row="5" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="5" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="5" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="5" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="5" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="5" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row6" class="disabled-row">
                                            <td colspan="5">Client Configurations</td>
                                    <td id="ok" data-row="6" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="6" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="6" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="6" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="6" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="6" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                        <tr id="row7" class="disabled-row">
                                            <td colspan="5">Computer Name</td>
                                    <td id="ok" data-row="7" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="7" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="7" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="7" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="7" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="7" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row8" class="disabled-row">
                                            <td rowspan="6">4.</td>
                                            <td rowspan="6">Computer Hardware Settings</td>
                                            <td colspan="5">Verify Device Manager settings</td>
                                    <td id="ok" data-row="8" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="8" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="8" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="8" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="8" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="8" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row9" class="disabled-row">
                                            <td colspan="5">BIOS Up-To_Date</td>
                                    <td id="ok" data-row="9" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="9" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="9" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="9" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="9" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="9" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row10" class="disabled-row">
                                            <td colspan="5">HardDisk</td>
                                    <td id="ok" data-row="10" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="10" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="10" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="10" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="10" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="10" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row11" class="disabled-row">
                                            <td colspan="5">DVD or CD/RW-drive firmware up-to-date</td>                 
                                    <td id="ok" data-row="11" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="11" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="11" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="11" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="11" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="11" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                        <tr id="row12" class="disabled-row">
                                            <td colspan="5">Memory is O.K</td>
                                    <td id="ok" data-row="12" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="12" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="12" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="12" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="12" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="12" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row13" class="disabled-row">
                                            <td colspan="5">For Laptop: battery run-time is norm</td>
                                    <td id="ok" data-row="13" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="13" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="13" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="13" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="13" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="13" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                        <tr id="row14" class="disabled-row">
                                            <td>5.</td>
                                            <td>Browser/Proxy Settings</td>
                                            <td colspan="5">Verify proper settings and operation</td>
                                    <td id="ok" data-row="14" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="14" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="14" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="14" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="14" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="14" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row15" class="disabled-row">
                                            <td>6.</td>
                                            <td>Proper Software loads</td>
                                            <td colspan="5">Required software is installed and operating</td>
                                    <td id="ok" data-row="15" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="15" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="15" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="15" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="15" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="15" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row16" class="disabled-row">
                                            <td rowspan="2">7.</td>
                                            <td rowspan="2">Viruses, and malware</td>
                                            <td colspan="5">Anti-virus installed</td>
                                    <td id="ok" data-row="16" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="16" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="16" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="16" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="16" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="16" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row17" class="disabled-row">
                                            <td colspan="5">Virus scan done</td>
                                    <td id="ok" data-row="17" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="17" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="17" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="17" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="17" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="17" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row18" class="disabled-row">
                                            <td rowspan="4">8.</td>
                                            <td rowspan="4">Clearance</td>
                                            <td colspan="5">Unused software removed</td>
                                    <td id="ok" data-row="18" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="18" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="18" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="18" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="18" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="18" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row19" class="disabled-row">
                                            <td colspan="5">Temporary files removed</td>
                                    <td id="ok" data-row="19" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="19" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="19" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="19" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="19" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="19" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row20" class="disabled-row">
                                            <td colspan="5">Recycle Bin and caches emptied</td>
                                    <td id="ok" data-row="20" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="20" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="20" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="20" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="20" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="20" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row21" class="disabled-row">
                                            <td colspan="5">Periphery devices clean</td>
                                    <td id="ok" data-row="21" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="21" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="21" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="21" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="21" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="21" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row22" class="disabled-row">
                                            <td rowspan="5">9.</td>
                                            <td rowspan="5">Interiors, and cleaning</td>
                                            <td colspan="5">Dust removed</td>
                                    <td id="ok" data-row="22" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="22" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="22" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="22" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="22" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="22" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row23" class="disabled-row">
                                            <td colspan="5">No loose parts</td>
                                    <td id="ok" data-row="23" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="23" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="23" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="23" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="23" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="23" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr  id="row24" class="disabled-row">
                                            <td colspan="5">Airflow is O.K.</td>
                                    <td id="ok" data-row="24" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="24" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="24" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="24" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="24" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="24" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row25" class="disabled-row">
                                            <td colspan="5">Cables unplugged and re-plugged</td>
                                    <td id="ok" data-row="25" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="25" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="25" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="25" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="25" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="25" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row26" class="disabled-row">
                                            <td colspan="5">Fans are operating</td>
                                    <td id="ok" data-row="26" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="26" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="26" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="26" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="26" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="26" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row27" class="disabled-row">
                                            <td rowspan="7">10.</td>
                                            <td rowspan="7">Peripheral Devices</td>
                                            <td colspan="5">Mouse</td>
                                    <td id="ok" data-row="27" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="27" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="27" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="27" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="27" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="27" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row28" class="disabled-row">
                                            <td colspan="5">Keyboard</td>
                                    <td id="ok" data-row="28" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="28" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="28" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="28" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="28" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="28" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row29" class="disabled-row">
                                            <td colspan="5">Monitor</td>
                                    <td id="ok" data-row="29" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="29" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="29" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="29" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="29" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="29" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row30" class="disabled-row">
                                            <td colspan="5">UPS</td>
                                    <td id="ok" data-row="30" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="30" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="30" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="30" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="30" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="30" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row31" class="disabled-row">
                                            <td colspan="5">Printer</td>
                                    <td id="ok" data-row="31" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="31" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="31" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="31" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="31" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="31" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row32" class="disabled-row">
                                            <td colspan="5">Telephone Extension</td>
                                    <td id="ok" data-row="32" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="32" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="32" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="32" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="32" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="32" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr id="row33" class="disabled-row">
                                            <td colspan="5">FAX</td>
                                    <td id="ok" data-row="33" data-cell="ok" data-status="ok" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="33" data-cell="ok" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="repair" data-row="33" data-cell="repair" data-status="repair" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="33" data-cell="repair" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td id="na" data-row="33" data-cell="na" data-status="na" class="status-cell">
                                        <div class="status-content">
                                            <div class="dropdown">
                                                <button class="dropdown-toggle" type="button">
                                                    <i class="fas fa-caret-down"></i>
                                                </button>
                                                <div class="dropdown-menu color-dropdown">
                                                    <a class="dropdown-item select-color" data-row="33" data-cell="na" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                    <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                    <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

 

                              </tbody>
                            </table>
                            </div>
                          </form>

 <form action="confirm.php" method="post">
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
                        </div>
                      </div>
                <div class="content-backdrop fade"></div>
            </div>
        </div>
      </div>
        <!-- Content wrapper -->


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
