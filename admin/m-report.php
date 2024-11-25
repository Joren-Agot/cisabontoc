<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php'; ?>
<?php include 'admin-script/m-script.php'; ?>


    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Maintenance')); ?>
        
        <!-- jvectormap -->
        <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />

        <?php include '../core/partials/head-css.php'; ?>
<style>
 .modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgba(0, 0, 0, 0.4); /* Black with opacity */
}

.modal-content {
    background-color: #fefefe;
    padding: 20px;
    border: 1px solid #888;
    width: 90%; /* Responsive width for smaller screens */
    max-width: 400px; /* Limit max width */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    position: absolute; /* Position the content relative to the modal */
    top: 50%; /* Center modal vertically */
    left: 50%; /* Center modal horizontally */
    transform: translate(-50%, -50%); /* Center modal precisely */
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
</style>
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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Chart' , 'title' => 'Preventive Maintenance')); ?>
                       


<body>

        <div class="content-wrapper">
            <div class="centered-image">
                <img src="../core/assets/images/slsu/mp_slsu.png" alt="Image description">
            </div>
            <p class="intro">PREVENTIVE MAINTENANCE SCHEDULE</p>
                <table class="tg">
                    <thead>
                        <tr>
                        <th class="tg-fymr">OFFICES</th>
                        <th class="tg-fymr">JAN</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">FEB</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">MAR</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">APR</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">MAY</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">JUN</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">JUL</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">AUG</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">SEP</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">OCT</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">NOV</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <th class="tg-fymr">DEC</th>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        </tr>
                    </thead>



                    <tbody>
                      <tr>
                        <th class="tg-0pky">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                        <td class="tg-0pky"></td>
                      </tr>
                      <tr>
        <?php foreach ($offices as $officeName => $officeData): ?>
            <tr>
                <th class="tg-fymr"><?php echo $officeName; ?></th>
                <?php
                for ($i = 1; $i <= 24; $i++) {
                    echo '<th class="tg-fymr"></th>';
                }
                ?>
            </tr>
            <tr>
                <td class="tg-0pky">Computer 1:</td>
                <?php
                for ($i = 1; $i <= 24; $i++) {
                    echo '<td class="tg-0pky';
                    if ($i % 2 == 1 && isset($officeData['Computer 1'][ceil($i / 2)])) {
                        echo ' success-cell">'; // Apply success-cell class if there's data
                    } elseif ($i % 2 == 1) {
                        echo ' warning-cell">'; // Apply warning-cell class if there's no data
                    } else {
                        echo '">'; // Otherwise, normal cell
                    }
                    echo '</td>';
                }
                ?>
            </tr>
            <tr>
                <td class="tg-0pky">Computer 2:</td>
                <?php
                for ($i = 1; $i <= 24; $i++) {
                    echo '<td class="tg-0pky';
                    if ($i % 2 == 1 && isset($officeData['Computer 2'][ceil($i / 2)])) {
                        echo ' success-cell">'; // Apply success-cell class if there's data
                    } elseif ($i % 2 == 1) {
                        echo ' warning-cell">'; // Apply warning-cell class if there's no data
                    } else {
                        echo '">'; // Otherwise, normal cell
                    }
                    echo '</td>';
                }
                ?>
            </tr>
        <?php endforeach; ?>
    </tr>
    <td class="tg-0pky">&nbsp;</td>
    <?php
    // Output empty cells for the third row
    for ($i = 1; $i <= 24; $i++) {
        echo '<td class="tg-0pky"></td>';
    }
    ?>
</tr>

                    </tbody>
                </table>
        <div class="centered-image-2">
            <img src="../core/assets/images/slsu/mp_slsu2.png" alt="Image description">
            <img src="../core/assets/images/slsu/mp_slsu3.png" alt="Image description">
        </div>
              </div>


</body>
</html>
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasBackdrop" aria-labelledby="offcanvasBackdropLabel">
    <div class="offcanvas-header">
        <h5 id="offcanvasBackdropLabel" class="offcanvas-title"><i class="menu-icon bx bx-envelope"></i> Messages</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
</div>

<div id="myModal" class="modal">
    <div class="modal-content" id="modalContent">
        <span class="close">&times;</span>
        <p><strong>Client ID:</strong> <span id="modalClientID"><?= htmlspecialchars($computer['client_id']) ?></span></p>
        <p><strong>PC:</strong> <span id="modalPC"><?= htmlspecialchars($computer['pc']) ?></span></p>
        <p><strong>Serial No:</strong> <span id="modalSerialNo"><?= htmlspecialchars($computer['serial_no']) ?></span></p>
        <p><strong>User:</strong> <span id="modalUser"><?= htmlspecialchars($computer['user']) ?></span></p>
        <p><strong>Dept:</strong> <span id="modalDept"><?= htmlspecialchars($computer['dept']) ?></span></p>
        <p><strong>Tech:</strong> <span id="modalTech"><?= htmlspecialchars($computer['tech']) ?></span></p>
        <p><strong>Date:</strong> <span id="modalDate"><?= htmlspecialchars($computer['date']) ?></span></p>
        <p><strong>Condition:</strong> <span id="modalCondition"><?= htmlspecialchars($computer['condition']) ?></span></p>
        <p><strong>Message Status:</strong> <span id="modalMessageStatus"><?= htmlspecialchars($computer['message_status']) ?></span></p>
    </div>
</div>

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

        <!-- apexcharts js -->
        <script src="../core/assets/libs/apexcharts/apexcharts.min.js"></script>

        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>

        <script src="../core/assets/js/pages/dashboard.init.js"></script>

        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
