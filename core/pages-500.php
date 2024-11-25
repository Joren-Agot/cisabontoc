<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>

    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => '500 Error')); ?>

    <?php include 'partials/head-css.php'; ?>


    </head>

    <body class="">
        <div class="my-5 pt-5">
            <!-- error page content -->
            <div class="w-100">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6 col-xl-5">
                            <div class="text-center">
                                <div>
                                    <h1 class="display-2 error-text fw-bold">500</h1>
                                </div>
                                <div>
                                    <h4 class="text-uppercase mt-4">Internal Server Error</h4>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
                                    <div class="mt-4">
                                        <a href="index.php" class="btn btn-primary"><i class="ri-arrow-left-line align-bottom me-2"></i>Back to Home</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- error auth page content -->

        </div>
        <!-- end error page -->

            <?php include 'partials/vendor-scripts.php'; ?>

        <script src="assets/js/app.js"></script>

    </body>
</html>
