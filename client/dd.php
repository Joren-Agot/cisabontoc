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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Upzet' , 'title' => 'Dashboard')); ?>
                       
<div class="container">
  <!-- Top card -->
  <div class="card top-card">
    <div class="top-left">
      <!-- Month and Year Picker -->
      <select>
        <option>January</option>
        <option>February</option>
        <option>March</option>
        <!-- Add more months as needed -->
      </select>
      <select>
        <option>2024</option>
        <option>2025</option>
        <option>2026</option>
        <!-- Add more years as needed -->
      </select>
    </div>
    <div class="top-right">
      <!-- Submitted By and Submitted To -->
      <input type="text" placeholder="Submitted By" />
      <input type="text" placeholder="Submitted To" />
    </div>
  </div>

  <!-- Cards below -->
  <div class="cards">
    <div class="card left-card">Card 1 (Left)</div>
    <div class="right-cards">
      <div class="card">Card 2</div>
      <div class="card">Card 3</div>
    </div>
  </div>
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
    width: 30%; /* Makes the right side cards take 65% of the container's width */
    gap: 1px;
  }

  .card {
    padding: 20px;
    background-color: #f0f0f0;
    border: 1px solid #ccc;
  }
</style>



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
