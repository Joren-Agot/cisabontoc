<?php
// Include config file
require_once "partials/config.php";

// Define variables and initialize with empty values
$useremail = $username =  $password = $confirm_password = "";
$useremail_err = $username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate useremail
    if (empty(trim($_POST["useremail"]))) {
        $useremail_err = "Please enter a useremail.";
    } elseif (!filter_var($_POST["useremail"], FILTER_VALIDATE_EMAIL)) {
        $useremail_err = "Invalid email format";
    } else {
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE useremail = ?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_useremail);

            // Set parameters
            $param_useremail = trim($_POST["useremail"]);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                /* store result */
                mysqli_stmt_store_result($stmt);

                if (mysqli_stmt_num_rows($stmt) == 1) {
                    
                    $useremail = trim($_POST["useremail"]);
                } else {
                    $useremail_err = "Useremail something went wrong.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
            
        }
    }

    

    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must have atleast 8 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please enter a confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // Check input errors before inserting in database
    if (empty($useremail_err) && empty($password_err) && empty($confirm_password_err)) {

        // Prepare an insert statement
        //$sql = "INSERT INTO user (useremail, username, password ,token) VALUES (?, ?, ?, ?)";
        $sql ="UPDATE users SET password = ? WHERE useremail = '$useremail'";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s",  $param_password, );

            // Set parameters
            $param_useremail = $useremail;
            //$param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            //$param_token = bin2hex(random_bytes(50));
            

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Redirect to index page
                header("location: auth-login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($link);
}

?>
<?php include 'partials/main.php'; ?>


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
                                        <img src="assets/images/logo-dark.png" alt="" height="24" class="auth-logo logo-dark mx-auto">
                                        <img src="assets/images/logo-light.png" alt="" height="24" class="auth-logo logo-light mx-auto">
                                    </a>
                                </div>
                                
                                <h4 class="font-size-18 text-muted text-center mt-2">RESET</h4>
                                <p class="text-muted text-center mb-4">Reset Password.</p>
                                <form class="form-horizontal" novalidate action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                                    <div class="row">
                                        <div class="col-md-12">
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
                                            <div class="mb-4 <?= !empty($confirm_password_err) ? 'has-error' : ''; ?>">
                                                <label class="form-label" for="confirm_password">Confirm Password<span class="text-danger"> * </span></label>
                                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter confirm password">
                                                <span class="text-danger"><?php echo $confirm_password_err; ?></span>
                                            </div>
                                            
                                            <div class="d-grid mt-4">
                                                <button class="btn btn-primary waves-effect waves-light" type="submit">Reset Password</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                           
                            <p class="text-white-50">© <script>document.write(new Date().getFullYear())</script> Upzet. Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesdesign</p>
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
