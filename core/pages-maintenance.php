<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>

    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Maintenance')); ?>

    <?php include 'partials/head-css.php'; ?>

    </head>

    <body>
       
        <div class="py-5">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="text-center mt-sm-5 mb-4">
                            <a href="index.php" class="">
                                <img src="assets/images/logo-dark.png" alt="" height="24" class="auth-logo logo-dark mx-auto">
                                <img src="assets/images/logo-light.png" alt="" height="24" class="auth-logo logo-light mx-auto">
                            </a>
                            <p class="mt-3">Responsive Bootstrap 5 Admin Dashboard</p>

                            <div class="mt-5">
                                <div class="mb-4">
                                    <i class="ri-tools-fill display-3"></i>
                                </div>
                                <h4>Site is Under Maintenance</h4>
                                <p>Please check back in sometime</p>

                                <div class="mt-4 pt-2">
                                    <a href="index.php" class="btn btn-primary">Back to Home</a>
                                </div>
                                
                            </div>
                        </div>

                    </div>
                </div>
                <!-- end row -->

                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="card-body">
                                <div class="avatar-sm mx-auto mb-4">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-broadcast-line font-size-24"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15 text-uppercase">Why is the Site Down ?</h5>
                                <p class="mb-0">There are many variations of passages of
                                    Lorem Ipsum available, but the majority have suffered alteration.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="card-body">
                                <div class="avatar-sm mx-auto mb-4">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-time-line font-size-24"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15 text-uppercase">
                                    What is the Downtime ?</h5>
                                <p class="mb-0">Contrary to popular belief, Lorem Ipsum is not
                                    simply random text. It has roots in a piece of classical.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="card-body">
                                <div class="avatar-sm mx-auto mb-4">
                                    <div class="avatar-title rounded-circle">
                                        <i class="ri-mail-line font-size-24"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15 text-uppercase">
                                    Do you need Support ?</h5>
                                <p class="mb-0">If you are going to use a passage of Lorem
                                    Ipsum, you need to be sure there isn't anything embar.. <a
                                            href="mailto:no-reply@domain.com"
                                            class="text-decoration-underline">no-reply@domain.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->

            </div>
            <!-- end container -->
            
        </div>
        <!-- end auth-page -->

        <?php include 'partials/vendor-scripts.php'; ?>

    </body>
</html>
