
<?php include '../core/partials/main.php'; ?>
<?php
require_once '../core/partials/config.php';
session_start();
$userId = $_SESSION['client_id'];

// Data from the form (use POST or GET as per your form method)
$control_no = $_POST['control_no'] ?? null;
$client_type = isset($_POST['client_type']) ? implode(',', $_POST['client_type']) : null; // Fix for multi-checkbox field
$date = $_POST['date'] ?? null;
$sex = $_POST['sex'] ?? null;
$age = $_POST['age'] ?? null;
$region_of_residence = $_POST['region_of_residence'] ?? null;
$service_availed = $_POST['service_availed'] ?? null;
$cc1 = isset($_POST['cc1']) ? implode(',', $_POST['cc1']) : null; // Multiple checkboxes
$cc2 = isset($_POST['cc2']) ? implode(',', $_POST['cc2']) : null;
$cc3 = isset($_POST['cc3']) ? implode(',', $_POST['cc3']) : null;

try {
    // SQL query with placeholders
    $sql = "INSERT INTO client_feedback 
            (control_no, client_type, date, sex, age, region_of_residence, service_availed, cc1, cc2, cc3) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        throw new Exception($conn->error);
    }

    // Bind parameters to the statement
    $stmt->bind_param(
        'ssssisssss', 
        $control_no,
        $client_type,
        $date,
        $sex,
        $age,
        $region_of_residence,
        $service_availed,
        $cc1,
        $cc2,
        $cc3
    );

    // Execute the query
    if ($stmt->execute()) {
        // Successfully inserted into client_feedback, now update the status of the user's work request
        $updateSql = "UPDATE work_requests SET status = 'Done' WHERE client_id = ? AND status = 'Delivered'";
        $updateStmt = $conn->prepare($updateSql);

        if ($updateStmt === false) {
            throw new Exception($conn->error);
        }

        // Bind the client ID for the update query
        $updateStmt->bind_param('i', $userId);

        // Execute the update query
        if ($updateStmt->execute()) {
            echo "Feedback submitted and status updated to 'Done'.";
        } else {
            echo "Error updating status: " . $updateStmt->error;
        }

        // Close the update statement
        $updateStmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the insert statement
    $stmt->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
        
        <!-- jvectormap -->
        <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
        <link href="../assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
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

                       
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Work Request Form</title>
<style>
    /* Style for the form */
    .work-request-form {
        padding: 20px;
        border-radius: 5px;
        background: white;
        border: 1px solid gray;
        width: 100%;
        color: black; /* Set text color to black */        
    }

    .work-request-form th,
    .work-request-form td {
        padding: 8px;
        text-align: left;
        border-bottom: 1px solid black;
        color: black;
        vertical-align: top;
    }
    /* Adjust text color */
    .form-label {
        color: black;
        font-size: 14px; /* Adjust font size */
    }

    h2 {
        text-align: center;
        margin-top: 0;
    }

    table {
        margin: auto; /* Center the table within its container */
    }

    .header-image {
        width: 50px; /* Adjust the size of the image */
        height: 50px;
        vertical-align: middle; /* Align the image with the text */
        margin-right: 5px; /* Add some space between the image and the text */
    }

    .star {
        font-size: 36px; /* Make the stars larger */
        cursor: pointer;
        color: gray;
        transition: color 0.2s;
        display: inline-block; /* Ensure stars are inline */
    }

    .star.selected {
        color: yellow;
    }

    .star-cell {
        text-align: center; /* Center align the stars */
    }

    .work-request-form h2 {
        text-align: center; /* Center the heading */
    }

    .work-request-form p {
        text-align: left; /* Align paragraphs to the left */
        margin-left: 7%; /* Adjust margin to match the alignment of the table */
        margin-bottom: 10px; /* Add spacing between paragraphs */
    }

    .work-request-form input[type="text"],
    .work-request-form input[type="date"] {
        text-align: center; /* Center the text input */
    }

    .checkbox-input {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 1px solid #000;
        margin-right: 5px;
        margin-left: 5px; /* Add spacing between checkboxes and labels */
        vertical-align: middle;
        cursor: pointer;
    }

 .input-field {
    width: 100px;
    border: none; /* Remove all borders */
    border-bottom: 1px solid black; /* Add bottom border */
    outline: none; /* Remove default focus outline */
}

    .checkbox-label {
        position: relative;
        padding-left: 25px; /* Adjust as needed */
        cursor: pointer;
    }



    .checkbox-label {
        margin-left: -25px; /* Adjust the indentation */
        cursor: pointer;
    }

    .solid-line {
        border-top: 3px solid black; /* Add solid line */
        margin-bottom: 10px; /* Add spacing */
        margin-left: 7%; 
        margin-right: 4%; 
    } 
    .scrollable-table {
        overflow-x: auto; /* Enables horizontal scrolling */
        -webkit-overflow-scrolling: touch; /* For smooth scrolling on iOS */
        margin: 20px 0; /* Optional margin for spacing */
        
    }
</style>
</head>
<body>
 <form method="post" action="" id="feedbackForm">
<div class="work-request-form">
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;}
.tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  overflow:hidden;padding:10px 5px;word-break:normal;}
.tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
  font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
.tg .tg-c3ow{border-color:inherit;text-align:center;vertical-align:top}
.tg .tg-0pky{border-color:inherit;text-align:left;vertical-align:top}
</style>
<table class="tg" style="undefined;table-layout: fixed; width: 825px">
<colgroup>
<col style="width: 336px">
<col style="width: 86px">
<col style="width: 73px">
<col style="width: 104px">
<col style="width: 63px">
<col style="width: 87px">
<col style="width: 76px">
</colgroup>

    <h2>HELP US SERVE YOU BETTER!</h2>
    <p>Control No: <input type="text" class="input-field" placeholder="Control No"></p>
    <p>This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices. Your feedback on your recently concluded transaction will help this office provide a better service. Personal information shared will be kept confidential and you always have the option to not answer this form.</p>
    <p>&nbsp;</p>
<p>
    Client type:
    <input type="checkbox" class="checkbox-input" id="citizen" name="client_type[]" value="Citizen">
    <label for="citizen">Citizen</label>
    <input type="checkbox" class="checkbox-input" id="business" name="client_type[]" value="Business">
    <label for="business">Business</label>
    <input type="checkbox" class="checkbox-input" id="government" name="client_type[]" value="Government">
    <label for="government">Government (Employee or another agency)</label>
</p>
<p>
    Date: <input type="date" class="input-field" name="date" value="<?php echo date('Y-m-d'); ?>" readonly>&nbsp; &nbsp; &nbsp; &nbsp;

    Sex: 
    <input type="checkbox" class="checkbox-input" id="male" name="sex[]" value="Male">
    <label for="male">Male</label>
    <input type="checkbox" class="checkbox-input" id="female" name="sex[]" value="Female">
    <label for="female">Female</label>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
    Age: <input type="text" class="input-field" name="age" placeholder="">
</p>
<p>&nbsp;</p>
<p>
    Region of residence: <input type="text" class="input-field" name="region_of_residence" placeholder="">&nbsp; &nbsp;&nbsp; &nbsp; &nbsp;
    Service Availed: <input type="text" class="input-field" name="service_availed" placeholder="">
</p>

<div class="solid-line"></div>
<p>
    INSTRUCTIONS: Check mark (✔ ) your answer to the Citizen’s Charter (CC) questions. The Citizen’s Charter is an official document that reflects the services of a government agency/office including its requirements, fees, and processing times among others.
</p>
<p>
    CC1. Which of the following best describes your awareness of a CC?
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc1-1" name="cc1[]" value="1">
    <label class="checkbox-label" for="cc1-1">1. I know what a CC is and I saw this office’s CC.</label>
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc1-2" name="cc1[]" value="2">
    <label class="checkbox-label" for="cc1-2">2. I know what a CC is but I did NOT see this office’s CC.</label>
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc1-3" name="cc1[]" value="3">
    <label class="checkbox-label" for="cc1-3">3. I learned of the CC only when I saw this office’s CC.</label>
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc1-4" name="cc1[]" value="4">
    <label class="checkbox-label" for="cc1-4">4. I do not know what a CC is and I did not see one in this office. (Answer ‘N/A’ on CC2 and CC3)</label>
</p>
<p>
    CC2. If aware of CC (answered 1-3 in CC1), would you say that the CC of this office was …?
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc2-1" name="cc2[]" value="1">
    <label class="checkbox-label" for="cc2-1">1. Easy to see</label>
    <input type="checkbox" class="checkbox-input" id="cc2-4" name="cc2[]" value="4">
    <label class="checkbox-label" for="cc2-4">4. Not visible at all</label>
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc2-2" name="cc2[]" value="2">
    <label class="checkbox-label" for="cc2-2">2. Somewhat easy to see</label>
    <input type="checkbox" class="checkbox-input" id="cc2-5" name="cc2[]" value="5">
    <label class="checkbox-label" for="cc2-5">5. N/A</label>
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc2-3" name="cc2[]" value="3">
    <label class="checkbox-label" for="cc2-3">3. Difficult to see</label>
</p>
<p>
    CC3. If aware of CC (answered codes 1-3 in CC1), how much did the CC help you in your transaction?
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc3-1" name="cc3[]" value="1">
    <label class="checkbox-label" for="cc3-1">1. Helped very much</label>
    <input type="checkbox" class="checkbox-input" id="cc3-2" name="cc3[]" value="2">
    <label class="checkbox-label" for="cc3-2">2. Somewhat helped</label>
</p>
<p style="margin-left: 130px;">
    <input type="checkbox" class="checkbox-input" id="cc3-3" name="cc3[]" value="3">
    <label class="checkbox-label" for="cc3-3">3. Did not help</label>
    <input type="checkbox" class="checkbox-input" id="cc3-4" name="cc3[]" value="4">
    <label class="checkbox-label" for="cc3-4">4. N/A</label>
</p>

<div class="solid-line"></div>
</table>
<p>
    INSTRUCTIONS: For SQD 0-8, please click how many stars (⭐) on the column that best corresponds to your answer.
</p>
<ul style="padding-left: 70px;">
    <li>⭐⭐⭐⭐⭐ - For Strongly Agree</li>
    <li>⭐⭐⭐⭐  - For Agree</li>
    <li>⭐⭐⭐    - Neither Agree nor Disagree</li>
    <li>⭐⭐      - Disagree</li>
    <li>⭐       - Strongly Disagree</li>
</ul>

        <div class="scrollable-table">
            <table class="tg" style="undefined;table-layout: fixed; width: 825px">
                <colgroup>
                    <col style="width: 336px">
                    <col style="width: 86px">
                    <col style="width: 73px">
                    <col style="width: 104px">
                    <col style="width: 63px">
                    <col style="width: 87px">
                    <col style="width: 76px">
                </colgroup>
<thead>
<tr>
    <th class="tg-0pky"></th>
    <th class="tg-c3ow"><img src="../Core/assets/images/icons/strongly_disagree.png" class="header-image" alt="Strongly Disagree">Strongly Disagree</th>
    <th class="tg-c3ow"><img src="../Core/assets/images/icons/disagree.png" class="header-image" alt="Disagree">Disagree</th>
    <th class="tg-c3ow"><img src="../Core/assets/images/icons/neutral.png" class="header-image" alt="Neither Agree nor Disagree">Neither Agree nor Disagree</th>
    <th class="tg-c3ow"><img src="../Core/assets/images/icons/agree.png" class="header-image" alt="Agree">Agree</th>
    <th class="tg-c3ow"><img src="../Core/assets/images/icons/strongly_agree.png" class="header-image" alt="Strongly Agree">Strongly Agree</th>
    <th class="tg-c3ow">Not Applicable</th>
  </tr>
</thead>
<tbody>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD0.</span> I am satisfied with the service that I availed.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD1</span>. I spent a reasonable amount of time for my transaction.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD2</span>. The office followed the transaction requirements and steps based on the information provided.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD3</span>. The steps (including payment) I needed to do for my transaction were easy and simple.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD4</span>. I easily found information about my transaction from the office or its website.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD5</span>. I paid a reasonable amount of fees for my transaction.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD6</span>. I feel the office was fair for everyone, or <span style="font-style:italic">"walang palakasan"</span> during my transaction.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD7</span>. I was treated courteously by the staff, and (if asked for help) the staff was helpful.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>
  <tr>
    <td class="tg-0pky"><span style="font-weight:bold">SQD8</span>. I got what I needed from the government office, or (if denied) denial of request was sufficiently explained to me.</td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="1">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="2">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="3">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="4">&#9733;</span></td>
    <td class="tg-c3ow star-cell"><span class="star" data-value="5">&#9733;</span></td>
    <td class="tg-0pky"></td>
  </tr>

</tbody>
</table>
</div>
</div>
<div>
<button type="submit" class="btn btn-primary" id="submitButton">
    Done
</button>

</div>
</form>
<script>
    // JavaScript for handling star rating click
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.star').forEach(star => {
            star.addEventListener('click', function () {
                const stars = this.parentElement.parentElement.querySelectorAll('.star');
                let clickedValue = this.getAttribute('data-value');
                stars.forEach(s => {
                    s.classList.toggle('selected', s.getAttribute('data-value') <= clickedValue);
                });
            });
        });
    });

</script>

<script>
    document.getElementById("submitButton").addEventListener("click", function(event) {
        event.preventDefault();  // Prevent form submission

        // Show SweetAlert confirmation
        Swal.fire({
            title: "Are you sure?",
            text: "Once you submit, your request will be processed.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: 'Yes, Submit',
            cancelButtonText: 'No, Cancel',
        }).then((result) => {
            if (result.isConfirmed) {
                // Show success message before submitting the form
                Swal.fire({
                    title: 'Successfully Submitted!',
                    text: 'Your request has been successfully processed.',
                    icon: 'success',
                    timer: 2000, // Display the message for 2 seconds
                    showConfirmButton: false // Hide the confirm button
                }).then(() => {
                    // Submit the form after the success message is shown
                    document.querySelector('form').submit();

                    // Redirect to another page after the success message
                    window.location.href = 'work_request.php'; // Change this to your desired redirect page
                });
            }
        });
    });
</script>

</body>
</html>


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
        <script src="../core/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
        <?php include '../core/partials/vendor-scripts.php'; ?>


        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
