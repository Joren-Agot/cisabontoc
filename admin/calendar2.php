<?php include '../core/partials/session.php'; ?>
<?php include '../core/partials/main.php';
  include 'admin-script/calendar-script.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>


    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Calendar')); ?>

        <!-- Plugin css -->
        <!-- <link rel="stylesheet" href="../core/assets/libs/@fullcalendar/core/main.min.css" type="text/css">
        <link rel="stylesheet" href="../core/assets/libs/@fullcalendar/daygrid/main.min.css" type="text/css">
        <link rel="stylesheet" href="../core/assets/libs/@fullcalendar/bootstrap/main.min.css" type="text/css">
        <link rel="stylesheet" href="../core/assets/libs/@fullcalendar/timegrid/main.min.css" type="text/css"> -->

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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'Upzet' , 'title' => 'Calendar')); ?>

                        <div class="row mb-4">
                            <div class="col-xl-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <button type="button" class="btn font-16 btn-primary waves-effect waves-light w-100"
                                            id="btn-new-event" data-bs-toggle="modal" data-bs-target="#event-modal">
                                            Create New Event
                                        </button>
            
                                        <div id="external-events">
                                            <br>
                                            <p class="text-muted">Drag and drop your event or click in the calendar</p>
                                            <div class="external-event fc-event bg-success" data-class="bg-success">
                                                <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>New Event
                                                Planning
                                            </div>
                                            <div class="external-event fc-event bg-info" data-class="bg-info">
                                                <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>Meeting
                                            </div>
                                            <div class="external-event fc-event bg-warning" data-class="bg-warning">
                                                <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>Generating
                                                Reports
                                            </div>
                                            <div class="external-event fc-event bg-danger" data-class="bg-danger">
                                                <i class="mdi mdi-checkbox-blank-circle font-size-11 me-2"></i>Create
                                                New theme
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> <!-- end col-->
                            <div class="col-xl-9">
                                <div class="card mb-0">
                                    <div class="card-body">
                                        <div id="calendar"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row-->
                        <div style='clear:both'></div>
            
                        <!-- Add New Event MODAL -->
                        <div class="modal fade" id="event-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header py-3 px-4">
                                        <h5 class="modal-title" id="modal-title">Event</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
            
                                    <div class="modal-body p-4">
                                        <form class="needs-validation" name="event-form" id="form-event" novalidate>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Event Name</label>
                                                        <input class="form-control" placeholder="Insert Event Name" type="text"
                                                            name="title" id="event-title" required value="">
                                                        <div class="invalid-feedback">Please provide a valid event name
                                                        </div>
                                                    </div>
                                                </div> <!-- end col-->
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Category</label>
                                                        <select class="form-select" name="category" id="event-category">
                                                            <option  selected> --Select-- </option>
                                                            <option value="bg-danger">Danger</option>
                                                            <option value="bg-success">Success</option>
                                                            <option value="bg-primary">Primary</option>
                                                            <option value="bg-info">Info</option>
                                                            <option value="bg-dark">Dark</option>
                                                            <option value="bg-warning">Warning</option>
                                                        </select>
                                                        <div class="invalid-feedback">Please select a valid event
                                                            category</div>
                                                    </div>
                                                </div> <!-- end col-->
                                            </div> <!-- end row-->
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <button type="button" class="btn btn-danger"
                                                        id="btn-delete-event">Delete</button>
                                                </div> <!-- end col-->
                                                <div class="col-6 text-end">
                                                    <button type="button" class="btn btn-light me-1"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success" id="btn-save-event">Save</button>
                                                </div> <!-- end col-->
                                            </div> <!-- end row-->
                                        </form>
                                    </div>
                                </div>
                                <!-- end modal-content-->
                            </div>
                            <!-- end modal dialog-->
                        </div>
                        <!-- end modal-->

                    </div> <!-- container-fluid -->
                </div>
                <!-- End Page-content -->
                
                <?php include '../core/partials/footer.php'; ?>

            </div>
            <!-- end main content-->
<script>
// Handle the event form submission
document.getElementById('form-event').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    var eventTitle = document.getElementById('event-title').value;
    var eventCategory = document.getElementById('event-category').value;

    // Get the current date and time
    var currentDate = new Date();
    
    // Format current date and time in 'YYYY-MM-DD HH:MM:SS' format
    var eventStart = currentDate.toISOString().slice(0, 19).replace('T', ' '); // Current time as start
    var eventEnd = new Date(currentDate.getTime() + 60 * 60 * 1000); // Add 1 hour to current time for end
    eventEnd = eventEnd.toISOString().slice(0, 19).replace('T', ' '); // Format end time

    // Send data using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '', true); // Posting to the same page
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert('Event saved successfully');
            location.reload(); // Reload the page to reflect changes
        } else {
            alert('Error saving event');
        }
    };
    xhr.send('title=' + eventTitle + '&category=' + eventCategory + '&start=' + eventStart + '&end=' + eventEnd);
});


// PHP array converted to JSON, passed into JavaScript
var scheds = <?php echo json_encode($sched_res); ?>;

// Initialize the calendar and display the events
$('#calendar').fullCalendar({
    events: scheds, // Pass the PHP events array as the JavaScript events data
    eventClassName: function(event) {
        return event.className; // Apply the className (for colors/styles) to each event
    },
    eventRender: function(event, element) {
        // Ensure the event's start and end are in the correct format
        var eventStart = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
        var eventEnd = moment(event.end).format('YYYY-MM-DD HH:mm:ss');
        
        event.start = eventStart;
        event.end = eventEnd;
    },
    // Other fullCalendar options can go here
});

</script>

        </div>
        <!-- END layout-wrapper -->

        <?php include '../core/partials/right-sidebar.php'; ?>

        <?php include '../core/partials/vendor-scripts.php'; ?>

        <!-- plugin js -->
        <!-- <script src="../core/assets/libs/moment/min/moment.min.js"></script>
        <script src="../core/assets/libs/jquery-ui-dist/jquery-ui.min.js"></script>
        <script src="../core/assets/libs/@fullcalendar/core/main.min.js"></script>
        <script src="../core/assets/libs/@fullcalendar/bootstrap/main.min.js"></script>
        <script src="../core/assets/libs/@fullcalendar/daygrid/main.min.js"></script>
        <script src="../core/assets/libs/@fullcalendar/timegrid/main.min.js"></script>
        <script src="../core/assets/libs/@fullcalendar/interaction/main.min.js"></script> -->
        <script src="../core/assets/libs/fullcalendar/index.global.min.js"></script>
        <!-- Calendar init -->
        <script src="../core/assets/js/pages/calendar.init.js"></script>

        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
