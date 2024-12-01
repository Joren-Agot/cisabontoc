<?php 
include '../core/partials/session.php';
include '../core/partials/main.php';
require_once '../core/partials/config.php';

if ($_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}

// Fetch the client_id from the session
$client_id = $_SESSION['client_id'];

// Query to fetch data for the current client
$sql = "SELECT * FROM monthly_report WHERE client_id = '$client_id' ORDER BY created_at DESC";
$result = $conn->query($sql);

// Initialize an array to hold fetched data
$reports = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reports[] = $row;
    }
}


if (isset($_POST['submit'])) {
    // Get form data
    $month = $conn->real_escape_string($_POST['month']);
    $year = $conn->real_escape_string($_POST['year']);
    $submitted_by = $conn->real_escape_string($_POST['submitted_by']);
    $submitted_to = $conn->real_escape_string($_POST['submitted_to']);
    $left_card_content = $conn->real_escape_string($_POST['left_card_content']);

    // Handle task statuses (e.g., for each task 1, 2, 3, ...)
    $taskStatuses = [];
    for ($i = 1; $i <= 10; $i++) { // Adjust the loop count for the number of tasks
        if (isset($_POST["task{$i}_status"]) && $_POST["task{$i}_status"] != 'Not Selected') {
            $taskStatuses[] = $conn->real_escape_string($_POST["task{$i}_status"]);
        }
    }

    // Concatenate task statuses into a string (or store them in a separate table if needed)
    $taskStatusesString = !empty($taskStatuses) ? implode(", ", $taskStatuses) : '';

    // Handle file uploads
    $imagePaths = [];
    $errorMessages = []; // Collect errors here
    if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) {
                $errorMessages[] = "Error uploading file: " . $_FILES['images']['name'][$key];
                continue;
            }

            $fileName = basename($_FILES['images']['name'][$key]);
            $uniqueName = uniqid() . "_" . $fileName;
            $targetPath = "../core/assets/images/reports/" . $uniqueName;

            if (move_uploaded_file($tmpName, $targetPath)) {
                $imagePaths[] = $targetPath;
            } else {
                $errorMessages[] = "Failed to upload: " . $fileName;
            }
        }
    } else {
        $errorMessages[] = "No files selected or files are not in the correct format.";
    }

    // Feedback to the user
    if (!empty($imagePaths)) {
        $imageUploads = implode(",", $imagePaths);
        // Insert data into the database
        $sql = "INSERT INTO monthly_report (month, year, submitted_by, submitted_to, left_card_content, task_statuses, image_uploads, client_id)
                VALUES ('$month', '$year', '$submitted_by', '$submitted_to', '$left_card_content', '$taskStatusesString', '$imageUploads', '$client_id')";

        if ($conn->query($sql) === TRUE) {
            $response = "success";
        } else {
            $response = "error";
        }
    } else {
        $response = "upload_error";
    }
}

?>
<input type="hidden" id="responseStatus" value="<?= $response ?>">



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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Upzet' , 'title' => 'Dashboard')); ?>
                       
<div class="container">
  <!-- Top card -->
  <form method="post" enctype="multipart/form-data">
    <div class="card top-card">
      <div class="top-left">
        <!-- Folder Icon and Vertical Line -->
        <div class="icon-and-date">
          <!-- Link the folder icon to the offcanvas -->
          <a href="#" class="folder-icon" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" role="button" aria-controls="offcanvasExample">
            <img src="../core/assets/images/icons/file.png" alt="Folder Icon" />
          </a>
          <div class="vertical-line"></div>
          <!-- Month and Year Picker -->
          <select name="month" class="date-select">
            <option value="" disabled selected>Select Month</option>
            <option value="January">January</option>
            <option value="February">February</option>
            <option value="March">March</option>
            <option value="April">April</option>
            <option value="May">May</option>
            <option value="June">June</option>
            <option value="July">July</option>
            <option value="August">August</option>
            <option value="September">September</option>
            <option value="October">October</option>
            <option value="November">November</option>
            <option value="December">December</option>
          </select>
          <select name="year" class="date-select">
            <option value="" disabled selected>Select Year</option>
            <script>
              for (let year = 2015; year <= 2040; year++) {
                document.write(`<option value="${year}">${year}</option>`);
              }
            </script>
          </select>
        </div>
      </div>

      <div class="top-right">
        <!-- Submitted By and Submitted To -->
        <input type="text" name="submitted_by" placeholder="Submitted By" required />
        <input type="text" name="submitted_to" placeholder="Submitted To" required />
      </div>
    </div>

<!-- Offcanvas -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Saved Reports</h5>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <!-- Inside the offcanvas body -->
    <?php
    // Fetch reports from the database for the current client
$sql = "SELECT id, month, year FROM monthly_report WHERE client_id = $client_id ORDER BY year DESC, month DESC";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0): ?>
      <ul class="list-group">
        <?php while ($report = $result->fetch_assoc()): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($report['month']) ?> <?= htmlspecialchars($report['year']) ?>
            <!-- Trigger AJAX or form submission to load report details -->
            <a href="prints.php?id=<?= htmlspecialchars($report['id']) ?>" class="btn btn-primary btn-sm">View</a>
          </li>
        <?php endwhile; ?>
      </ul>
    <?php else: ?>
      <p>No reports found for this client.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Custom CSS for black border on list items -->
<style>
  .list-group-item {
    border: 1px solid black; /* Black border with thicker lines */
    border-radius: 8px;       /* Optional: round the corners */
  }
</style>

    <!-- Cards below -->
    <div class="cards">
      <div class="card left-card">
        <textarea id="elm1" name="left_card_content"></textarea>
      </div>
      <div class="right-cards">
<div class="card">
    <h5>ACCOMPLISHED?</h5>
    <div id="taskList">
        <div class="task" data-task-id="1">
            <label>1. Task</label>
            <input type="radio" name="task1_status" value="Accomplished" id="task1_accom" style="margin-left: 53px;"> YES
            <input type="radio" name="task1_status" value="Not Accomplished" id="task1_not_accom" style="margin-left: 20px;"> NO
        </div>
    </div>
    <button type="button" id="addTaskBtn" onclick="addTask()">+ Add Task</button>
</div>

<script>
    let taskCount = 1; // Start with one task already added

    function addTask() {
        taskCount++; // Increment task count
        const taskList = document.getElementById('taskList');
        const newTask = document.createElement('div');
        newTask.classList.add('task');
        newTask.setAttribute('data-task-id', taskCount);
        newTask.innerHTML = `
            <label>${taskCount}. Task</label>
            <input type="radio" name="task${taskCount}_status" value="Accomplished" id="task${taskCount}_accom" style="margin-left: 50px;"> YES
            <input type="radio" name="task${taskCount}_status" value="Not Accomplished" id="task${taskCount}_not_accom" style="margin-left: 20px;"> NO
        `;
        taskList.appendChild(newTask); // Append new task to the list
    }
</script>


        <div class="card">
          Card 3
          <!-- Image upload and dynamically added upload fields -->
          <div class="image-upload-container">
            <!-- Image preview area -->
            <div id="imagePreviews"></div>
            <!-- Multiple file upload button -->
            <input type="file" id="imageUpload1" name="images[]" accept="image/*" multiple  onchange="addImageUpload()">
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Button -->
    <button type="submit" name="submit">Submit</button>
  </form>
</div>


<!-- Modal for displaying the large image -->
<div id="imageModal" class="image-modal" onclick="closeModal()">
  <span class="close">&times;</span>
  <img class="modal-content" id="modalImage" />
  <div id="caption"></div>
</div>

<style>

  /* Container for all cards */
  .container {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 20px; /* Space between the cards */
  }

  /* Style for the top card */
  .top-card {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Creates two equal columns */
    padding: 20px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
    margin-bottom: 20px;
  }

  /* Left side of the top card (Month and Year Picker) */
  .top-left {
    display: flex;
    gap: 10px;
    justify-content: flex-start;
  }

  .top-left select {
    padding: 5px;
    font-size: 14px;
  }

  /* Right side of the top card (Submitted By and Submitted To) */
  .top-right {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .top-right input {
    padding: 5px;
    font-size: 14px;
    border: 1px solid #ccc;
  }

  /* Cards container */
  .cards {
    display: flex;
    justify-content: space-between;
  }

  /* Left card */
  .left-card {
    width: 70%; /* Makes the left card take 30% of the container's width */
    padding: 20px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
  }

  /* Right cards stacked vertically */
  .right-cards {
    display: flex;
    flex-direction: column;
    width: 28%; /* Makes the right side cards take 65% of the container's width */
    gap: 1px;
  }

  .card {
    padding: 20px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
  }

  /* Image upload container */
  .image-upload-container {
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    align-items: flex-start;
    margin-top: 10px;
  }

/* Image preview container */
#imagePreviews {
  display: flex;
  flex-wrap: wrap; /* Allow the images to wrap onto new lines */
  gap: 10px; /* Add space between images */
}

/* Mini image preview */
.image-preview {
  width: 100%; /* Set the width to around half of the container */
  height: 100px; /* Fixed height for image previews */
  object-fit: cover;
  margin-bottom: 10px; /* Space between images */
  border-radius: 5px; /* Optional, for rounded corners */
  cursor: pointer;
}


  /* Remove button next to image preview */
  .remove-button {
    background-color: red;
    color: white;
    border: none;
    padding: 5px;
    position: absolute;
    top: 0;
    right: 0;
    cursor: pointer;
    border-radius: 50%;
  }

  /* Modal styles */
  .image-modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.8); /* Black with opacity */
    padding-top: 60px;
  }

  .modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 700px;
  }

  .close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
  }

  .close:hover,
  .close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
  }

  #caption {
    text-align: center;
    color: #ccc;
    padding: 10px 0;
  }

    .date-select {
    font-size: 12px; /* Adjust to make text smaller */
    padding: 5px;    /* Adjust padding to balance appearance */
    width: auto;     /* Make the select elements smaller */
  }

  .top-left {
    display: flex;
    gap: 10px; /* Space between the dropdowns */
    align-items: center; /* Align vertically */
  }
  .icon-and-date {
  display: flex;
  align-items: center;
}

.folder-icon img {
  width: 24px; /* Adjust the size as needed */
  height: 24px;
  object-fit: contain;
  margin-right: 10px;
}

.folder-icon:hover {
  color: #0056b3;
}

.vertical-line {
  width: 1px;
  height: 80px;
  background-color: black;
  margin: 0 15px;
}

</style>

<script>
function addImageUpload() {
    const input = document.getElementById("imageUpload1");

    if (input.files && input.files.length > 0) {
        const previewsContainer = document.getElementById("imagePreviews");
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];

            // Create a new image element for the preview
            const previewImg = document.createElement('img');
            previewImg.classList.add('image-preview');
            previewImg.src = URL.createObjectURL(file);
            previewImg.onclick = function () {
                openModal(previewImg.src); // Open modal on click
            };

            // Create a div to contain the image and the remove button
            const imageDiv = document.createElement('div');
            imageDiv.style.position = 'relative'; // Positioning for remove button

            // Add the image to the div
            imageDiv.appendChild(previewImg);

            // Create the remove button
            const removeButton = document.createElement("button");
            removeButton.classList.add("remove-button");
            removeButton.textContent = "X";

            // Add the remove functionality
            removeButton.addEventListener("click", function () {
                imageDiv.remove(); // Remove the image and the button
            });

            // Append the remove button and the image to the preview container
            imageDiv.appendChild(removeButton);
            previewsContainer.appendChild(imageDiv);
        }
    }
}


  // Function to open the modal and show the large image
  function openModal(imageSrc) {
    const modal = document.getElementById("imageModal");
    const modalImage = document.getElementById("modalImage");
    const caption = document.getElementById("caption");

    modal.style.display = "block";
    modalImage.src = imageSrc;
    caption.innerHTML = "Click to close"; // Optional caption text
  }

  // Function to close the modal
  function closeModal() {
    const modal = document.getElementById("imageModal");
    modal.style.display = "none";
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const responseStatus = document.getElementById("responseStatus").value;

    if (responseStatus === "success") {
        Swal.fire({
            icon: 'success',
            title: 'Report Submitted!',
            text: 'Your report has been submitted successfully.',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'monthly_accomplishment.php';
        });
    } else if (responseStatus === "error") {
        Swal.fire({
            icon: 'error',
            title: 'Submission Failed!',
            text: 'An error occurred while saving your report.',
            confirmButtonText: 'OK'
        });
    } else if (responseStatus === "upload_error") {
        Swal.fire({
            icon: 'error',
            title: 'Upload Error!',
            text: 'No files selected or files are not in the correct format.',
            confirmButtonText: 'OK'
        });
    }
});
</script>




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
       <script src="../core/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>

        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>

        <script src="../admin/assets/libs/tinymce/tinymce.min.js"></script>
                <script src="../admin/assets/js/pages/form-editor.init.js"></script>
        <script src="../core/assets/js/app.js"></script>
        <script src="../admin/email-editor.init.js"></script>


    </body>
</html>
