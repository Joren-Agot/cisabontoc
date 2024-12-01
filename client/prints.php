<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php';
if ($_SESSION['role'] !== 'client') {
    header("Location: ../index.php");
    exit;
}
 ?>

    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
        
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
<?php
require_once '../core/partials/config.php';

// Check if the report ID is set
if (isset($_GET['id'])) {
    $report_id = $_GET['id'];

    // Query to fetch the report details
    $sql = "SELECT * FROM monthly_report WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $report_id); // Bind the report_id as an integer parameter
        $stmt->execute();
        $result = $stmt->get_result();

        // Initialize variables
        $month = '';
        $year = '';
        $content = '';
        $submitted_by = '';
        $submitted_to = '';
        $images = [];
        $taskStatuses = [];

        if ($result->num_rows > 0) {
            // Fetch the report details
            $row = $result->fetch_assoc();
            $month = $row['month'];
            $year = $row['year'];
            $content = $row['left_card_content'];
            $submitted_by = $row['submitted_by'];
            $submitted_to = $row['submitted_to'];
            $taskStatuses = explode(',', $row['task_statuses']); // Assuming task statuses are stored as comma-separated values

            // Fetch image uploads as LONGTEXT (assuming it stores URLs or paths)
            $images_raw = $row['image_uploads'];

            // Split images if they are separated by a delimiter (e.g., comma or space)
            if (!empty($images_raw)) {
                $images = preg_split('/[\s,]+/', $images_raw);
            }
        } else {
            echo "Report not found.";
        }

        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="thisprint.css" />
    <style>
    /* Center the content */
    .center-wrapper {
      display: flex;
      justify-content: center;
      height: 100vh;
      width: 100%;
    }
  </style>
</head>

<body>
  <div class="page-header" style="text-align: center">
    <div class="left-image"></div>
  <div class="right-image"></div>
  </div>

  <div class="page-footer">
    <div class="left-image"></div> <!-- First image aligned right -->
    <div class="right-image"></div> <!-- Second image aligned right -->
  </div>

  <table>
    <thead>
      <tr>
        <td>
          <div class="page-header-space"></div>
        </td>
      </tr>
    </thead>

    <tbody>
      <tr>
        <td>
          <div class="page">
                <div class="monthly-accomplishment" style="margin-top: 120px;">
                  <div style="font-size: 1rem; font-weight: bold; margin-top: -100px; color: black; text-align: center">
                    MONTHLY ACCOMPLISHMENT REPORT
                  </div>
                  <div style="font-size: 0.9rem; margin-top: 5px; color: black; text-align: center">
                    OFFICE: COLLEGE OF COMPUTER STUDIES AND INFORMATION TECHNOLOGY
                  </div>
                  <div style="font-size: 1rem; margin-top: 5px; font-weight: bold; color: black; text-align: center">
                    MONTH: <?php echo $month; ?> <?php echo $year; ?>
                  </div>
                </div>
                
                <!-- Table for displaying report -->
                <table class="table table-hover table-bordered border-secondary" style="margin-top: 40px; text-align: center;">
                  <colgroup>
                    <col style="width: 164px">
                    <col style="width: 390px">
                    <col style="width: 264px">
                  </colgroup>
                  <thead>
                    <tr>
                      <th>DATE</th>
                      <th>ACCOMPLISHMENT</th>
                      <th>REMARKS<br>(Progress and deviations from the plan)</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><?php echo $month; ?> <?php echo $year; ?></td> <!-- Display current month -->
                      <td><?php echo $content; ?></td> <!-- Display the content -->
<td>
    <?php
        $counter = 1;
        // Loop through the task statuses and display them as numbered items
        foreach ($taskStatuses as $status) {
            $status = trim($status); // Trim any leading or trailing spaces
            if ($status == 'Accomplished' || $status == 'Not Accomplished') {
                echo "{$counter}. {$status}<br>";
                $counter++;
            }
        }
    ?>
</td>

                    </tr>
                  </tbody>
                </table>

<div class="submitted-info">
    <p>Submitted From: <?php echo $submitted_by; ?></p>
    <p>Submitted To: <?php echo $submitted_to; ?></p>
</div>
</div>

<!-- Image Gallery Wrapper -->
<div class="page">
  <div class="image-gallery" style="margin-top: 20px;">
                 <div class="monthly-accomplishment" style="margin-top: 120px;">
                  <div style="font-size: 1rem; font-weight: bold; margin-top: -100px; color: black; text-align: center">
                    PROOF OF CLASSES
                  </div>
                  <div style="font-size: 0.9rem; margin-top: 5px; color: black; text-align: center">
                    OFFICE: COLLEGE OF COMPUTER STUDIES AND INFORMATION TECHNOLOGY
                  </div>
                  <div style="font-size: 1rem; margin-top: 5px; font-weight: bold;color: black; text-align: center">
                    MONTH: <?php echo $month; ?> <?php echo $year; ?>
                  </div>
                </div>
    <div class="image-grid">
      <?php
      if (!empty($images)) {
          foreach ($images as $image) {
              if (file_exists($image)) {
                  echo '<div class="image-item"><img src="' . htmlspecialchars($image) . '" alt="Uploaded Image" class="image-thumbnail" /></div>';
              } else {
                  echo '<p>Invalid image path: ' . htmlspecialchars($image) . '</p>';
              }
          }
      } else {
          echo '<p>No images uploaded.</p>';
      }
      ?>
    </div>
  </div>
</div>

<style type="text/css">
.image-gallery {
    text-align: center;
}

.image-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr); /* 2 items per row */
    gap: 16px;
    padding: 20px;
}

.image-item {
    overflow: hidden;
    border-radius: 8px;
    width: 100%; /* Ensure the item occupies full width of its grid cell */
    height: 200px; /* Fixed height for each image */
    background: transparent; /* Make the background invisible */
    display: flex; /* To allow centering of images */
    justify-content: center;
    align-items: center;
}

.image-thumbnail {
    width: auto; /* Keep original width */
    height: 100%; /* Ensure the image fills the container's height */
    object-fit: contain; /* Maintain aspect ratio without cropping */
    border-radius: 8px;
    transition: transform 0.3s ease;
}

.image-thumbnail:hover {
    transform: scale(1.05);
}

</style>

        </td>
      </tr>
    </tbody>

    <tfoot>
      <tr>
        <td>
          <div class="page-footer-space"></div>
        </td>
      </tr>
    </tfoot>

  </table>

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

        <?php include '../core/partials/vendor-scripts.php'; ?>


        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
