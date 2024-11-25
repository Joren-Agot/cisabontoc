<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php'; ?>
<?php include 'admin-script/account-script.php'; ?>
 <?php include 'admin-script/account-script2.php'; 

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Creation</title>
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
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px; /* Location of the box */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* Center the modal */
            padding: 20px;
            border: 1px solid #888;
            width: 400px; /* Set a fixed width for the square shape */
            height: 400px; /* Set a fixed height for the square shape */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional: add some shadow for depth */
            border-radius: 8px; /* Optional: add rounded corners */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

.form-group {
    margin-bottom: 15px; /* Space between form groups */
}

.form-group label {
    display: block; /* Ensure label is on its own line */
    margin-bottom: 5px; /* Space between label and input */
}

.form-group input {
    width: 100%; /* Full width for input fields */
    padding: 10px; /* Space inside the input */
    border-radius: 4px; /* Rounded corners for inputs */
    border: 1px solid #ccc; /* Border for inputs */
}
</style>
</head>
<body>
    <h2 style="text-align: center;">Create an Account</h2>
    <div class="card">
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-container">
                <div class="column">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>

                    <label for="password">Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" required>
                        <span class="toggle-password" onclick="togglePasswordVisibility()">
                            <i class='ri-eye-line' id="togglePasswordIcon"></i>
                        </span>
                    </div>

<label for="role">Select Role:</label>
<select id="role" name="role" onchange="showRoleOptions(); handleRoleSelection()" required>
    <option value="">Select Role</option>
    <option value="staff">Staff</option>
    <option value="client">Client</option>
    <?php
    // Dynamically adding roles from the database
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Skip staff and client as they're already manually added
            if ($row['role_name'] !== 'staff' && $row['role_name'] !== 'client') {
                echo '<option value="' . strtolower($row['role_name']) . '">' . $row['role_name'] . '</option>';
            }
        }
    }
    ?>
    <option value="add_new_role">Add New Role +</option> <!-- Updated option -->
</select>

                    <div id="staffOptions" class="role-options">
                        <label for="staff_type">Select Staff Type:</label>
                        <select id="staff_type" name="staff_type">
                            <option value="cashier">Cashier Staff</option>
                            <option value="nurse">Nurse Staff</option>
                            <option value="registrar">Registrar Staff</option>
                        </select>
                    </div>

                    <div id="clientOptions" class="role-options">
                        <label for="client_type">Select Client Type:</label>
                        <select id="client_type" name="client_type">
                            <option value="guest">Guest</option>
                            <option value="visitor">Visitor</option>
                        </select>
                    </div>
                <div id="dynamicRoleOptions" class="role-options">
                    <label for="dynamic_type">Select Role Type:</label>
                    <select id="dynamic_type" name="dynamic_type">
    <?php
    // Dynamically adding roles from the database
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Skip staff and client as they're already manually added
            if ($row['role_type'] !== 'staff' && $row['role_type'] !== 'client') {
                echo '<option value="' . strtolower($row['role_type']) . '">' . $row['role_type'] . '</option>';
            }
        }
    }
    ?>
                    </select>
                </div>
              </div>


                <div class="column">
                    <label for="first_name">First Name:</label>
                    <input type="text" id="first_name" name="first_name" required>

                    <label for="middle_name">Middle Name:</label>
                    <input type="text" id="middle_name" name="middle_name">

                    <label for="last_name">Last Name:</label>
                    <input type="text" id="last_name" name="last_name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <input type="submit" value="Create Account">
        </form>
    </div>

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Add New Role</h2>
        <form id="newRoleForm" action="add_role.php" method="POST">
            <div class="form-group">
                <label for="newRole">Role Name:</label>
                <input type="text" id="newRole" name="newRole" required>
            </div>
            <div class="form-group">
                <label for="newType">Role Type:</label>
                <input type="text" id="newType" name="newType" required>
            </div>
            <input type="submit" value="Add Role" style="width: 100%;">
        </form>
    </div>
</div>



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
