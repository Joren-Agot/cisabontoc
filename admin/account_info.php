<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php'; ?>
<?php include 'admin-script/info-script.php'; ?>

    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Information')); ?>
        
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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Account' , 'title' => 'User Information')); ?>
<?php
// Role to image mapping
$roleImages = [
    'staff' => '../core/assets/images/icons/staff.png',
    'client' => '../core/assets/images/icons/client.png',
    'admin' => '../core/assets/images/roles/admin.png',
    // Add more roles and corresponding image paths
];

// Determine the selected role image
$selectedImage = isset($roleImages[$userType]) ? $roleImages[$userType] : '../core/assets/images/icons/profile.png';
?>                    
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
        }

        .table-responsive {
            margin: 1px 0;
        }

        .table-wrapper {
            min-width: 1000px;
            background: #fff;
            padding: 20px 25px;
            border-radius: 3px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-title {
            padding-bottom: 15px;
            background: #299be4;
            color: #fff;
            padding: 16px 30px;
            margin: -20px -25px 10px;
            border-radius: 3px 3px 0 0;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title .btn {
            color: #566787;
            float: right;
            font-size: 13px;
            background: #fff;
            border: none;
            min-width: 50px;
            border-radius: 2px;
            margin-left: 10px;
        }

        .table-title .btn:hover,
        .table-title .btn:focus {
            color: #566787;
            background: #f2f2f2;
        }



        table.table-hover tbody tr:hover {
            background: #6c757d;
        }

        .text-center {
            text-align: center;
        }
    .table-title .row {
        display: flex;
        align-items: center; /* Vertically center the image and dropdown */
        justify-content: space-between; /* Space between the image and dropdown */
    }

    .heading-container {
        display: flex;
        align-items: center; /* Aligns the image vertically in the center */
    }

    .heading-container img {
        width: 90px; /* Set image width */
        height: 90px; /* Set image height */
        object-fit: cover; /* Ensure the image fits within the circle */
   
    }

    .role-options {
        margin-left: 20px; /* Space between the image and dropdown */
        text-align: right; /* Align label to the right side */
    }

    .role-options label {
        margin-right: 10px; /* Space between label and dropdown */
    }

    .role-options select {
        height: 35px; /* Adjust height of the dropdown for better alignment */
    }
        /* Additional Custom Styling for Responsiveness */
        @media (max-width: 767px) {
            /* Adjust heading-container for smaller screens */
            .heading-container img {
                width: 40px;  /* Adjust image size */
                height: 40px;
            }

            /* Stack the filter options below the image on smaller screens */
            .heading-container {
                text-align: center;
                margin-bottom: 10px;
            }

            /* Ensure the filter select dropdown takes full width */
            #staffOptions select {
                width: 100%;
            }

            /* Adjust table layout for mobile screens */
            .table-responsive {
                overflow-x: auto;
            }

            .table th, .table td {
                padding: 8px;
            }
        }
</style>
</head>
<body>
<div class="container">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                        <div class="heading-container">
                         <img src="<?php echo $selectedImage; ?>" alt="Role Image">
                     
                
                    <div class="col-xs-7 text-right">
                        <div id="staffOptions" class="role-options">
                            <label for="user_type">Filter by User Type:</label>
                            <form method="POST" action="">
                                <select id="user_type" name="user_type" onchange="this.form.submit()">
                                    <option value="staff" <?php echo ($userType == 'staff') ? 'selected' : ''; ?>>Staff</option>
                                    <option value="client" <?php echo ($userType == 'client') ? 'selected' : ''; ?>>Client</option>
                                    <?php
                                    $rolesSql = "SELECT role_name FROM roles";
                                    $rolesResult = $conn->query($rolesSql);
                                    while ($roleRow = $rolesResult->fetch_assoc()) {
                                        $roleName = $roleRow['role_name'];
                                        $selected = ($userType == $roleName) ? 'selected' : '';
                                        echo "<option value=\"$roleName\" $selected>$roleName</option>";
                                    }
                                    ?>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
</div>
            <!-- Table Structure -->
            <table class="table table-hover table-bordered border-secondary">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
<?php if (count($userdata) > 0): ?>
    <?php foreach ($userdata as $index => $user): ?>
        <tr>
            <td><?php echo $index + 1; ?></td>
            <td>
                <img src="<?php echo isset($user['image_path']) ? $user['image_path'] : '../core/assets/images/users/admin/profile_default.png'; ?>" alt="User Image" style="width: 50px; height: 50px;">
            </td>
            <td><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td>
                <?php 
                if ($userType == 'client' || $userType == 'staff') {
                    $roleName = isset($user['role']) ? $user['role'] : 'Unknown';
                    echo htmlspecialchars($roleName);
                } else {
                    $roleName = isset($user['role_name']) ? $user['role_name'] : 'Unknown';
                    echo htmlspecialchars($roleName);
                }
                ?>
            </td>
            <td>
                <a href="#" class="btn btn-info btn-sm" title="View">View</a>
                <a href="#" class="btn btn-secondary btn-sm" title="Edit">Edit</a>
                <a href="#" class="btn btn-danger btn-sm" title="Delete">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
<?php else: ?>
    <tr>
        <td colspan="6" class="text-center">No users found.</td>
    </tr>
<?php endif; ?>
                </tbody>
            </table>
        </div>
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


        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
