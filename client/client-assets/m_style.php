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

<style>
        .vertical-text {
            writing-mode: vertical-rl; /* Rotate text vertically */
            transform: rotate(180deg); /* Compatibility for older browsers */
            text-align: center; /* Center text */
        }

        .item-row {
            background-color: #c6c6c6 !important; /* Gray background */
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


</style>