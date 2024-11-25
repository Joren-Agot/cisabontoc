<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>

    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => '404 Error')); ?>

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
                                    <h1 class="display-2 error-text fw-bold">4<i class="ri-ghost-smile-fill align-bottom text-primary mx-1"></i>4</h1>
                                </div>
                                <div>
                                    <h4 class="text-uppercase mt-4">Sorry, page not found</h4>
                                    <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque</p>
                                    <div class="mt-4">
                                        <a href="index.php" class="btn btn-primary"><i class="ri-arrow-left-line align-bottom mr-2"></i>Back to Home</a>
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

    </body>
</html>
