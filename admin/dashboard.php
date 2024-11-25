<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php'; ?>
<?php
require_once '../core/partials/config.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the total count of 'Pending' requests
$sql = "SELECT COUNT(*) AS pending_count FROM work_requests WHERE status = 'Pending'";
$result = $conn->query($sql);

// Initialize pending count
$pendingCount = 0;

if ($result->num_rows > 0) {
    // Fetch the result and assign to $pendingCount
    $row = $result->fetch_assoc();
    $pendingCount = $row['pending_count'];
}

// Query to get the total count of 'Pending' job orders
$sql = "SELECT COUNT(*) AS pending_job_orders FROM job_order WHERE status = 'Pending'";
$result = $conn->query($sql);

// Initialize pending job orders count
$pendingJobOrdersCount = 0;

if ($result->num_rows > 0) {
    // Fetch the result and assign to $pendingJobOrdersCount
    $row = $result->fetch_assoc();
    $pendingJobOrdersCount = $row['pending_job_orders'];
}

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
<div class="directional-expand-section">
    <div class="container">
        <?php 
        $items = [
            ["title" => "Pending Request", "image" => "../core/assets/images/icons/request.png", "content" => "This is the content for Item 1."],
            ["title" => "Item 2", "image" => "../core/assets/images/icons/job.png", "content" => "This is the content for Item 2."],
            ["title" => "Item 5", "image" => "../core/assets/images/icons/misc.png", "content" => "This is the content for Item 5."]
        ];

        foreach ($items as $item): 
            // Add a class 'pending-item' to the Pending Request item
            $itemClass = ($item['title'] == "Pending Request") ? 'pending-item' : '';
        ?>
            <div class="item <?php echo $itemClass; ?>" onclick="toggleExpand(this)">
                <div class="image-container">
                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['title']; ?>" />
                </div>
                <h3><?php echo $item['title']; ?></h3>
                <div class="item-content">
                    <?php if ($item['title'] == "Pending Request"): ?>
                        <!-- Display the total Pending Requests count when clicked -->
                        <p>Work Requests: <?php echo $pendingCount; ?></p>
                        <!-- Display the total Pending Job Orders count when clicked -->
                        <p>Job Orders: <?php echo $pendingJobOrdersCount; ?></p>
                    <?php else: ?>
                        <p><?php echo $item['content']; ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
                            <div class="card-body">
                                <div class="position-relative m-4">
                                    <div class="progress" style="height: 1px;">
                                        <div class="progress-bar" role="progressbar" style="width: 50%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">1</button>
                                    <button type="button" class="position-absolute top-0 start-50 translate-middle btn btn-sm btn-primary rounded-pill" style="width: 2rem; height:2rem;">2</button>
                                    <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm btn-secondary rounded-pill" style="width: 2rem; height:2rem;">3</button>
                                </div>
                            </div><!-- end card-body -->


<style>
    .directional-expand-section {
        padding: 20px; /* Add spacing for integration within the dashboard */
        background-color: #f4f4f4; /* Background for this specific section */
    }

    .directional-expand-section .container {
        display: flex;
        width: 100%;
        height: 40vh; /* Set height for a banner-style layout */
        overflow: hidden;
        gap: 10px;
    }

    .directional-expand-section .item {
        flex: 1;
        height: 100%;
        position: relative;
        transition: all 0.5s ease;
        overflow: hidden;
        border-radius: 5px;
        cursor: pointer;
        border: 2px solid gray; /* Adds a gray border to the card */
    }

        /* Add the background color specifically for Pending Request item */
        .directional-expand-section .item.pending-item {
            background-color: #65c5f5; /* Set blue background for the Pending Request item */
        }
        /* Style for the "Total Pending Requests" text */
        .directional-expand-section .item.pending-item .item-content p {
            font-size: 1.3rem; /* Increase the font size */
            font-weight: bold; /* Make the text bold */
            color: #333; /* Optional: Adjust the text color */
        }

    .directional-expand-section .item .image-container {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: inherit; /* Applies the border radius to the container */
        overflow: hidden;
    }

    .directional-expand-section .item img {
        width: auto;
        height: 60%;
        max-width: 100%; /* Ensures the image does not exceed the container dimensions */
        object-fit: contain; /* Ensures the image scales proportionally */
    }

    .directional-expand-section .item h3 {
        position: absolute;
        bottom: 10px;
        left: 10px;
        margin: 0;
        padding: 10px;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 1rem;
        border-radius: 5px;
    }

    .directional-expand-section .item .item-content {
        position: absolute;
        top: 0;
        left: 100%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        padding: 20px;
        box-shadow: -5px 0 10px rgba(0, 0, 0, 0.2);
        opacity: 0;
        transform: translateX(-100%);
        transition: all 0.5s ease;
        z-index: 1;
    }

    .directional-expand-section .item.expanded {
        flex: 2;
    }

    .directional-expand-section .item.expanded .item-content {
        left: 0;
        opacity: 1;
        transform: translateX(0);
    }
</style>

<script>
    function toggleExpand(element) {
        const allItems = document.querySelectorAll('.directional-expand-section .item');
        allItems.forEach(item => {
            if (item !== element) {
                item.classList.remove('expanded');
            }
        });
        element.classList.toggle('expanded');
    }
</script>




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
