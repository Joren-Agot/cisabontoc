<?php

// Include database connection script
require_once '../core/partials/config.php'; // Assuming the second script is named db_connection.php
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
// Fetch data from the database
$sql = "SELECT 
            pc_info.client_id,
            pc_info.pc,
            pc_info.serial_no,
            pc_info.user,
            pc_info.dept,
            pc_info.tech,
            pc_info.date,
            pc_info.condition,
            pc_info.message_status
        FROM 
            pc_info
        JOIN 
            client_info ON pc_info.client_id = client_info.client_id";

$result = $conn->query($sql); // Use $conn instead of $conn
$computers = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $computers[] = $row;
    }
}


// Initialize arrays to keep track of files assigned to each computer's cells for each office
$offices = [
    "Registrar's Office" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "Cashier's Office" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "Accounting Office" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "Procurement's Office" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "Budget Officer's Office" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "Administrative Office" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "College Dean" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "SAS" => [
        'Computer 1' => [],
        'Computer 2' => []
    ],
    "RIES" => [
        'Computer 1' => [],
        'Computer 2' => []
    ]
];

// Function to assign a file to a specific cell
function assignFileToCell($office, $computer, $cellNumber, $fileIndex)
{
    global $offices;
    $offices[$office][$computer][$cellNumber] = $fileIndex;
}

// Loop through fetched computers and assign files to cells based on the office and month
foreach ($computers as $index => $computer) {
    $office = $computer['dept'];
    $month = date('n', strtotime($computer['date'])); // Get month number (1 to 12)

    // Check if the computer belongs to one of the specified offices
    if (isset($offices[$office])) {
        // Determine the cell number based on the month and available slots
        $cellNumber = ($month - 1) % 12 + 1; // Calculate cell number (1 to 12)

        // Check if the cell for this month and computer is available
        if (!isset($offices[$office]['Computer 1'][$cellNumber])) {
            assignFileToCell($office, 'Computer 1', $cellNumber, $index + 1); // Assign file to Computer 1
        } elseif (!isset($offices[$office]['Computer 2'][$cellNumber])) {
            assignFileToCell($office, 'Computer 2', $cellNumber, $index + 1); // Assign file to Computer 2
        }
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Styles for screen */
        .content-wrapper {
            background-color: #ffffff; /* Background color for the entire content wrapper */
            border-radius: 8px;
            padding: 20px; /* Adjust padding as needed */
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); /* Optional: Add box shadow for depth */
            margin-top: 20px; /* Adjust margin-top for space from navbar */
            margin-left: 3px;
            margin-right: 1px;
        }
        
        .tg  {
            border-collapse: collapse;
            border-spacing: 0;
            width: 100%;
        }

        .tg td, .tg th {
            border: 1px solid black;
            color: black;
            font-family: Cambria, sans-serif;
            font-size: 15px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
        }

        .tg .tg-0pky {
            border-color: inherit;
            text-align: left;
            vertical-align: top;
        }
        .tg .tg-fymr{
          border-color:inherit;
          font-weight:bold;
          text-align:left;
          vertical-align:top
        }
        /* Center the image and adjust its size */
        .centered-image {
            display: flex;
            justify-content: center; /* Center horizontally */
            margin-bottom: 10px; /* Add some space below the image */
        }

        .centered-image img {
            max-width: 100%; /* Ensure the image fits within its container */
            width: 60%; /* Adjust the width to your desired size */
            height: auto; /* Maintain the aspect ratio */
            display: block; /* Make the image a block-level element */
        }

          .centered-image-2 {
            display: flex;
            justify-content: right; /* Center horizontally */
            margin-bottom: 10px; /* Add some space below the image */
        }

        .centered-image-2 img {
            max-width: 100%; /* Ensure the image fits within its container */
            width: 15%; /* Adjust the width to your desired size */
            height: auto; /* Maintain the aspect ratio */
            display: block; /* Make the image a block-level element */
        }

        /* Styles for print */
        @media print {
            .content-wrapper {
                background-color: transparent !important; /* Make background transparent for printing */
                box-shadow: none !important; /* Remove box shadow for printing */
                border-radius: 0 !important; /* Remove border radius for printing */
                padding: 0 !important; /* Remove padding for printing */
                margin: 0 !important; /* Remove margins for printing */
            }
            
        .tg td, .tg th {
            border: 1px solid black;
            color: black;
            font-family: Cambria, sans-serif;
            font-size: 15px;
            overflow: hidden;
            padding: 10px 5px;
            word-break: normal;
            white-space: nowrap;
        }

        .tg .tg-0pky {
            border-color: inherit;
            text-align: left;
            vertical-align: top;
        }
        .tg .tg-fymr{
          border-color:inherit;
          font-weight:bold;
          text-align:left;
          vertical-align:top
        }

                    .success-cell {
                background-color: #28a745 !important; /* Bootstrap success color */
                color: white !important; /* White text color for better readability */
            }

            .warning-cell {
                background-color: orange !important;
            }



            .centered-image {
                justify-content: center; /* Center horizontally when printing */
                margin-bottom: 10px; /* Add some space below the image when printing */
            }

            .centered-image img {
                max-width: 100%; /* Ensure the image fits within content when printing */
                height: auto; /* Maintain aspect ratio */
                width: 70%; /* Adjust the width as needed */
            }
        .page-header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100px; /* Adjust height as needed */
            background-image: url('../base/img/elements/mp_slsu.png');
            background-repeat: no-repeat;
            background-size: contain; /* Ensure image fits within header */
            background-position: top right; /* Adjust position as needed */
            z-index: -1; /* Move the header image below content */
        }
        }
            .intro {
                margin-bottom: 2px; /* Adjust margin bottom between intro text and table for printing */
                margin-top: 10px;
                color: black !important;
                text-align: center !important;
                font-weight: bold !important;
                font-family: Cambria,sans-serif !important;
                font-size: 20px;
            }
        .success-cell {
            background-color: #28a745; /* Bootstrap success color */
            color: white; /* White text color for better readability */
            cursor: pointer;
        }
        .warning-cell{
          background-color: orange;
        }


    </style>
</head>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById("myModal");
        var modalContent = document.getElementById("modalContent");
        var span = document.getElementsByClassName("close")[0];
        var cells = document.querySelectorAll(".success-cell");

        cells.forEach(function(cell) {
            cell.addEventListener('click', function(event) {
                var rect = cell.getBoundingClientRect();
                var modalWidth = modalContent.offsetWidth;
                var modalHeight = modalContent.offsetHeight;

                // Adjust modal position to stay near the clicked cell
                var topPosition = rect.top + window.scrollY + rect.height + 10; // 10px below the cell
                var leftPosition = rect.left + window.scrollX + (rect.width / 2) - (modalWidth / 2); // Center horizontally

                // Ensure modal stays within the viewport
                if (leftPosition < 0) {
                    leftPosition = 10; // Minimum left position
                } else if (leftPosition + modalWidth > window.innerWidth) {
                    leftPosition = window.innerWidth - modalWidth - 10; // 10px from the right edge
                }

                if (topPosition + modalHeight > window.innerHeight) {
                    topPosition = rect.top + window.scrollY - modalHeight - 10; // 10px above the cell
                }

                modalContent.style.top = topPosition + "px";
                modalContent.style.left = leftPosition + "px";

                // Display modal
                modal.style.display = "block";
            });
        });

        span.onclick = function() {
            modal.style.display = "none";
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    });
</script>

