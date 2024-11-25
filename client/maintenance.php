<?php include '../core/partials/session.php'; ?>
<?php include('client-assets/m_script.php'); ?>
<?php include '../core/partials/main.php'; ?>
<?php

include('client-assets/m_script.php');
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








<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-menu-fixed layout-compact"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../core/new style/assets/"
  data-template="vertical-menu-template-free">
 

  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - Analytics | Sneat - Bootstrap 5 HTML Admin Template - Pro</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../core/new style/assets/img/favicon/fav.ico" />

    <link rel="stylesheet" href="../core/new style/assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../core/new style/assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../core/new style/assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../core/new style/assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../core/new style/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../core/new style/assets/vendor/libs/apex-charts/apex-charts.css" />

     <!-- Link external CSS file -->
    <link rel="stylesheet" href="../base/fonts/poppins.css">

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="../core/new style/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="../core/new style/assets/js/config.js"></script>
  </head>

  <body>

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
            <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
<div id="inputDetailsModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Input Details</h2>
        <textarea id="inputDetailsTextArea" placeholder="Enter details..."></textarea>
        <button id="saveInputDetailsBtn">Save</button>
    </div>
</div>

        <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="py-3 mb-4"><span class="text-muted fw-light">CISA/</span>MIS MAINTENANCE</h4>
            <div class="row">
                <div class="col-xxl">
                <div class=" mb-4">
                    <div class="card">
                        <div class="card-body">
<div class="card">
    <form method="post" action="">
        <div class="card-body position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-header mb-0">Desktop/Laptop Maintenance</h5>
                <div class="d-flex align-items-center position-relative">
                                        <!-- Toast Notification Template -->
                    <div id="toast"  class="bs-toast toast show bg-primary" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
                        <div class="toast-header">
                            <strong class="me-auto">Notification</strong>
                           <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            💡Please save the inputed information first before proceeding to the task.
                        </div>
                    </div>
                    <i id="icon" class="menu-icon bx bx-message-error bx-tada-hover me-2 flipped-icon"></i>
                    <button id="saveBtn" type="submit" class="btn btn-primary">Save Info</button>

                </div>
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
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        </tr>
                                        <tr id="row11" class="disabled-row">
                                            <td colspan="5">DVD or CD/RW-drive firmware up-to-date</td>                 
                                            <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        </tr>

                                        <tr id="row12" class="disabled-row">
                                            <td colspan="5">Memory is O.K</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row13" class="disabled-row">
                                            <td colspan="5">For Laptop: battery run-time is norm</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
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
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
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
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
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
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row17" class="disabled-row">
                                            <td colspan="5">Virus scan done</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
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
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row19" class="disabled-row">
                                            <td colspan="5">Temporary files removed</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row20" class="disabled-row">
                                            <td colspan="5">Recycle Bin and caches emptied</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row21" class="disabled-row">
                                            <td colspan="5">Periphery devices clean</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
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
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row23" class="disabled-row">
                                            <td colspan="5">No loose parts</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr  id="row24" class="disabled-row">
                                            <td colspan="5">Airflow is O.K.</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row25" class="disabled-row">
                                            <td colspan="5">Cables unplugged and re-plugged</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row26" class="disabled-row">
                                            <td colspan="5">Fans are operating</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
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
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row28" class="disabled-row">
                                            <td colspan="5">Keyboard</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row29" class="disabled-row">
                                            <td colspan="5">Monitor</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row30" class="disabled-row">
                                            <td colspan="5">UPS</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row31" class="disabled-row">
                                            <td colspan="5">Printer</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row32" class="disabled-row">
                                            <td colspan="5">Telephone Extension</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                        <tr id="row33" class="disabled-row">
                                            <td colspan="5">FAX</td>
                                        <td data-status="ok" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#8BC34A" href="#"><span style="background-color: #8BC34A;" class="color-badge"></span>Select OK</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="repair" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#FF5722" href="#"><span style="background-color: #FF5722;" class="color-badge"></span>Select Repair</a>
                                                        <a class="dropdown-item input-details disabled" href="#">📄 Details</a>
                                                        <a class="dropdown-item remove-color" href="#">❌ Remove</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td data-status="na" class="status-cell">
                                            <div class="status-content">
                                                <div class="dropdown">
                                                    <button class="dropdown-toggle" type="button">
                                                        <i class="fas fa-caret-down"></i>
                                                    </button>
                                                    <div class="dropdown-menu color-dropdown">
                                                        <a class="dropdown-item select-color" data-color="#607D8B" href="#"><span style="background-color: #607D8B;" class="color-badge"></span>Select N/A</a>
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
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
