<?php
// Initialize the session
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

// Include config file
require_once "partials/config.php";

// Fetch roles from the database
$roles = [];
$query = "SELECT * FROM roles"; // Adjust your query according to your table structure
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $roles[] = $row; // Collect roles
    }
}

// Define variables and initialize with empty values
$username = $password = $role = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username is empty
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Check if role is selected
    if (empty(trim($_POST["role"]))) {
        $role = "";
    } else {
        $role = trim($_POST["role"]);
    }

    // Validate credentials
    if (empty($username_err) && empty($password_err) && !empty($role)) {
        // Prepare a select statement
        $query = "SELECT * FROM $role WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);

            $_SESSION["role"] = $role; // Store role in session

            switch ($role) {
                case 'client':
                    $_SESSION["client_id"] = $user['id'];
                    header("Location: ../client/dashboard.php");
                    break;
                case 'admin':
                    $_SESSION["admin_id"] = $user['id'];
                    header("Location: ../admin/dashboard.php");
                    break;
                case 'staff':
                    $_SESSION["staff_id"] = $user['id'];
                     header("Location: ../staff/dashboard.php");
                    break;
                case 'student':
                    $_SESSION["student_id"] = $user['id'];
                     header("Location: ../other/request.php");
                    break;

                default:
                    $_SESSION["id"] = $user['id']; // Store generic user ID
                    header("Location: request.php"); // Redirect to a generic dashboard or based on role
                    break;          
            }
        } else {
            $username_err = "Invalid username or password. Please make sure it is also the correct ROLE.";
        }
    }

    // Close connection
    mysqli_close($conn);
}
?>

<?php include 'partials/main.php'; ?>

<head>
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Login')); ?>
    <?php include 'partials/head-css.php'; ?>
</head>
<style>
.slsu {
  background-image: url("../assets/images/slsu.png") !important;
  background-size: cover;
  background-position: center;
}
</style>
<body class="slsu">
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
                            <h4 class="font-size-18 text-muted mt-2 text-center">Welcome Back!</h4>
                                </a>
                            </div>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-4 <?= !empty($username_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
                                            <span class="text-danger"><?php echo $username_err; ?></span>
                                        </div>
                                        <div class="mb-4 <?= !empty($password_err) ? 'has-error' : ''; ?>">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password" class="form-control" id="userpassword" name="password" placeholder="Enter password">
                                            <span class="text-danger"><?php echo $password_err; ?></span>
                                        </div>

                                        <div class="mb-4">
                                            <label for="role">Login As: </label>
                                            <select id="role" name="role" class="form-control">
                                                <option value="">Select Role</option>
                                                <option value="client">Client</option>
                                                <option value="admin">Admin</option>
                                                <option value="staff">Staff</option>
                                               
                                                <?php foreach ($roles as $role): ?>
                                                    <option value="<?php echo htmlspecialchars($role['role_name']); ?>"><?php echo htmlspecialchars($role['role_name']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button class="btn btn-info waves-effect waves-light" type="submit">Log In</button>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                        <div class="mt-5 text-center">
                            <p class="text-white-50">For Students: <a href="auth-register.php" class="fw-medium text-primary"> Register Here </a> </p>
                        </div>
                </div>
            </div>
            <!-- end row -->
        </div>
    </div>
    <!-- end Account pages -->

    <?php include 'partials/vendor-scripts.php'; ?>

    <script src="assets/js/app.js"></script>

</body>
</html>
