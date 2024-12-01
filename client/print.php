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
    <link rel="stylesheet" href="printted.css" />
    <?php include '../core/partials/head-css.php'; ?>
</head>

<?php include '../core/partials/body.php'; ?>

<!-- Begin page -->
<div id="layout-wrapper">

<?php include '../core/partials/menu.php'; ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <style type="text/css">

        .table{
  height: 700px;
}

    </style>
 
   <!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="style.css" />
  <style>
    /* General background color */
    body {
      background-color: #f4f4f4; /* You can set any background color here */
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    /* Print-specific styles */
    @media print {
      body {
        background-color: white; /* Set background color to white for printing */
      }

      /* Hide the print button */
      .page-header button {
        display: none;
      }


      /* Main content formatting */
      .main-content {
        padding-top: 80px; /* Space for header */
      }

      /* Adjust table layout */
      table {
        width: 100%;
        border-collapse: collapse;
      }

      th, td {
        padding: 8px;
        text-align: center;
        border: 1px solid #ccc;
      }

      caption {
        font-size: 1rem;
        font-weight: bold;
        color: black;
        margin-bottom: 20px;
      }

      .submitted-info {
        font-size: 0.9rem;
        text-align: center;
        margin-top: 20px;
      }

      /* Page breaks */
      .page {
        page-break-before: always;
        margin-top: 50px; /* Space for the header */
      }

      .page-footer-space, .page-header-space {
        display: none;
      }
    }

    /* Add other styling to elements as needed */
  </style>
</head>

<body>

  <div class="page-header">
  </div>

  <div class="page-footer">
  </div>

  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">

        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Upzet', 'title' => 'Dashboard')); ?>

        <!-- Begin your custom content here -->
        <?php
        require_once '../core/partials/config.php';

        // Query to fetch the most recent monthly report
        $sql = "SELECT * FROM monthly_report ORDER BY created_at DESC LIMIT 1";
        $result = $conn->query($sql);

        // Initialize variables
        $month = '';
        $year = '';
        $content = '';
        $submitted_by = '';
        $submitted_to = '';

        if ($result->num_rows > 0) {
          // Fetch the most recent entry
          $row = $result->fetch_assoc();
          $month = $row['month'];
          $year = $row['year'];
          $content = $row['left_card_content'];
          $submitted_by = $row['submitted_by'];
          $submitted_to = $row['submitted_to'];
        } else {
          echo "No data found.";
        }

        $conn->close();
        ?>
<div class="monthly-accomplishment">
  <div style="font-size: 1rem; font-weight: bold; margin-top: 80px; color: black; text-align: center">MONTHLY ACCOMPLISHMENT REPORT</div>
  <div style="font-size: 0.9rem; margin-top: 5px; color: black; text-align: center">OFFICE: COLLEGE OF COMPUTER STUDIES AND INFORMATION TECHNOLOGY</div>
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
              <td>Pending</td> <!-- Placeholder for remarks -->
            </tr>
          </tbody>
        </table>

        <div class="submitted-info">
          <!-- Footer content -->
          <p>Submitted From: <?php echo $submitted_by; ?></p>
          <p>Submitted To: <?php echo $submitted_to; ?></p>
        </div>

        <!-- End of custom content -->
      </div>
    </div>
  </div>

</body>

</html>

            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


    </div>

        <?php include '../core/partials/footer.php'; ?>
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
