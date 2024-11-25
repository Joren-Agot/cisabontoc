<?php
// Include the database connection file
include '../core/partials/session.php'; 
include '../core/partials/main.php'; 
include '../core/partials/config.php';

// Define variables and initialize with empty values
$username = $password = $useremail = $first_name = $last_name = $course = "";
$username_err = $password_err = $useremail_err = $first_name_err = $last_name_err = $course_err = "";
$success = $error = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate username
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Validate email
    if (empty(trim($_POST["useremail"]))) {
        $useremail_err = "Please enter your email.";
    } else {
        $useremail = trim($_POST["useremail"]);
    }

    // Validate password (no hash, store as plain text)
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate first name
    if (empty(trim($_POST["first_name"]))) {
        $first_name_err = "Please enter your first name.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if (empty(trim($_POST["last_name"]))) {
        $last_name_err = "Please enter your last name.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    // Validate course
    if (empty(trim($_POST["course"]))) {
        $course_err = "Please enter the course.";
    } else {
        $course = trim($_POST["course"]);
    }

    // Check input errors before inserting into database
    if (empty($username_err) && empty($password_err) && empty($useremail_err) && empty($first_name_err) && empty($last_name_err) && empty($course_err)) {

        // Prepare SQL to insert into student_info
        $sql = "INSERT INTO student_info (first_name, last_name, email, course) VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssss", $first_name, $last_name, $useremail, $course);

            if ($stmt->execute()) {
                // Get the ID of the newly inserted student_info
                $student_info_id = $stmt->insert_id;

                // Insert into the student table with plain text password
                $sql2 = "INSERT INTO student (username, password, student_id) VALUES (?, ?, ?)";

                if ($stmt2 = $conn->prepare($sql2)) {
                    $stmt2->bind_param("ssi", $username, $password, $student_info_id);  // Use $student_info_id here

                    if ($stmt2->execute()) {
                        $success = "Registration successful!";
                    } else {
                        $error = "Error: " . $stmt2->error;
                    }
                }
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
    }

    // Close the connection
    $conn->close();
}

?>




<!-- SweetAlert2 JavaScript -->
<script src="assets/libs/sweetalert2/sweetalert2.all.min.js"></script>






<!DOCTYPE html>
<html lang="en">

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Register')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<body class="bg-pattern">
    <div class="bg-overlay"></div>
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-6 col-md-8">
                    <div class="card">
                        <div class="card-body p-4">
                            <div class="text-center">
                                <a href="index.php" class="">
                                    <img src="assets/images/logo-sm.ico" alt="" height="40" class="auth-logo logo-dark mx-auto">
                                    <img src="assets/images/logo-sm.ico" alt="" height="40" class="auth-logo logo-light mx-auto">
                            <h4 class="font-size-18 text-muted mt-2 text-center">Student Registration</h4>
                                </a>
                            </div>

                            <form class="form-horizontal" novalidate action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="form-container">
                                    <div class="column">
                                    <div class="mb-4">
                                        <label class="form-label" for="course">Course of Study<span class="text-danger"> * </span></label>
                                        <select class="form-control" id="course" name="course">
                                            <option value="">Select a course</option>
                                            <option value="Marine Biology">Bachelor of Science in Marine Biology</option>
                                            <option value="Information Technology">Bachelor of Science Information Technology</option>
                                            <option value="Fisheries">Bachelor of Science Fisheries</option>
                                            <option value="Agriculture">Bachelor of Science Agriculture</option>
                                        </select>
                                        <span class="text-danger"><?php echo $course_err; ?></span>
                                    </div>

                                        <div class="mb-4 <?= !empty($username_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="username">Username<span class="text-danger"> * </span></label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
                                            <span class="text-danger"><?php echo $username_err; ?></span>
                                        </div>

                                        <div class="mb-4 <?= !empty($useremail_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="useremail">Email<span class="text-danger"> * </span></label>
                                            <input type="email" class="form-control" id="useremail" name="useremail" placeholder="Enter email">
                                            <span class="text-danger"><?php echo $useremail_err; ?></span>
                                        </div>

                                        <div class="mb-4 <?= !empty($password_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="userpassword">Password<span class="text-danger"> * </span></label>
                                            <input type="password" class="form-control" id="userpassword" name="password" placeholder="Enter password">
                                            <span class="text-danger"><?php echo $password_err; ?></span>
                                        </div>

                                        <div class="mb-4 <?= !empty($first_name_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="first_name">First Name<span class="text-danger"> * </span></label>
                                            <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name">
                                            <span class="text-danger"><?php echo $first_name_err; ?></span>
                                        </div>

                                        <div class="mb-4 <?= !empty($last_name_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="last_name">Last Name<span class="text-danger"> * </span></label>
                                            <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name">
                                            <span class="text-danger"><?php echo $last_name_err; ?></span>
                                        </div>

                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="term-conditionCheck">
                                            <label class="form-check-label fw-normal" for="term-conditionCheck">I accept <a href="#" class="text-primary">Terms and Conditions</a></label>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button class="btn btn-info waves-effect waves-light" type="submit">Register</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php if ($success): ?>
    <script>
        Swal.fire({
            title: 'Success!',
            text: '<?php echo $success; ?>',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'auth-login.php';
        });
    </script>
<?php elseif ($error): ?>
    <script>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo $error; ?>',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
    <!-- end Account pages -->
        <link href="../assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <script src="assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
    <?php include 'partials/vendor-scripts.php'; ?>

    <script src="assets/js/app.js"></script>

</body>
</html>
