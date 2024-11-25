<?php include 'partials/session.php'; ?>
<?php include 'partials/main.php'; ?>

    <head>
        
    <?php includeFileWithVariables('partials/title-meta.php', array('title' => 'Coming Soon')); ?>

    <?php include 'partials/head-css.php'; ?>


    </head>

    <body class="bg-pattern">
        <div class="bg-overlay"></div>
        <div class="account-pages my-5 pt-5">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mb-5">
                            <a href="index.php" class="logo"><img src="assets/images/logo-light.png" height="24" alt="logo"></a>

                            <h5 class="font-size-16 text-white-50 mb-4">Responsive Bootstrap 5 Admin Dashboard</h5>

                            <h4 class="text-white mt-5">Let's get started with Upzet</h4>
                            <p class="text-white-50">It will be as simple as Occidental in fact it will be Occidental.</p>

                            <div class="row justify-content-center mt-5">
                                <div class="col-md-8">
                                    <div data-countdown="2024/08/08" class="counter-number"></div>
                                </div><!-- end col-->
                            </div><!-- end row-->
                        </div>
                    </div>
                    <!-- end col-->
                </div>
                <!-- end row -->
            </div>
        </div>
        <!-- end Account pages -->

        <?php include 'partials/vendor-scripts.php'; ?>

        <!-- Plugins js-->
        <script src="assets/libs/jquery-countdown/jquery.countdown.min.js"></script>

        <!-- Countdown js -->
        <script src="assets/js/pages/coming-soon.init.js"></script>

    </body>
</html>
