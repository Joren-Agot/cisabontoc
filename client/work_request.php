
<?php include '../core/partials/main.php'; ?>
<?php include 'client-assets/work-scripts.php'; ?>
    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
        
        <!-- jvectormap -->
        <link href="../core/assets/libs/jqvmap/jqvmap.min.css" rel="stylesheet" />
        <link href="../assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
        <?php include '../core/partials/head-css.php'; ?>

    </head>

    <?php include '../core/partials/body.php'; ?>

        <!-- Begin page -->
        <div id="layout-wrapper">

        <?php include '../core/partials/menu.php'; ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
<style type="text/css">.gray-border {
  border: 1px solid black; /* Light gray border */
  border-radius: 0.25rem;    /* Optional: Rounds the corners slightly */
}</style>
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'CISA' , 'title' => 'Work Request')); ?>                 

                            <!-- Content wrapper -->
                            <div class="content-wrapper">
                              <!-- Content -->
                              <div class="container-xxl flex-grow-1 container-p-y">
                                <h4 class="py-3 mb-4"><span class="text-muted fw-light">CISA/</span> WORK REQUEST FORM</h4>

                                <!-- Basic Layout & Basic with Icons -->
                                <div class="row">
                                  <div class="col-xxl">
                            
                                      <div class="card-header d-flex align-items-center justify-content-between">
                                      </div>
                                      <div class="card gray-border">
                                        <form id="workRequestForm" action="work_request.php" method="POST">
                                          <div class="row mb-3">
                                            <div class="col-sm-6 mb-3">
                                              <div class="form-group">
                                                <label for="req_d_u_o" class="form-label"><h6>Requesting Dept./Unit/Office:</h6></label>
                                                <div class="input-group input-group-merge">
                                                  <span id="basic-icon-default-company2" class="input-group-text">
                                                    <i class="far fa-building"></i>
                                                  </span>
                                                  <input
                                                    type="text"
                                                    id="req_d_u_o"
                                                    name="requesting_dept"
                                                    class="form-control"
                                                    placeholder=""
                                                    aria-label="ACME Inc."
                                                    aria-describedby="basic-icon-default-company2"
                                                    required
                                                  />
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                              <div class="form-group">
                                                <label for="html5-date-input" class="form-label"><h6>Date of Request:</h6></label>
                                                <div class="input-group">
                                                <span class="input-group-text"><i class=" far fa-calendar-alt"></i></span>
                                                  <input class="form-control" type="date" value="" id="html5-date-input" name="date_of_req" disabled />
                                                  
                                                </div>
                                              </div>
                                            </div>
                                            <div class="col-sm-3 mb-3">
                                              <div class="form-group">
                                                <label for="html5-time-input" class="form-label"><h6>Time of Request:</h6></label>
                                                <div class="input-group">
                                                <span class="input-group-text"><i class=" far fa-clock"></i></span>
                                                  <input class="form-control" type="time" value=":30:00" id="html5-time-input" name="time_of_req" disabled />
                                                  
                                                </div>
                                              </div>
                                            </div>
                                          </div>

                                          <div class="row mb-3">
                                            <label for="work-request" class="form-label"><h6>Work Requested:</h6></label>
                                            <div class="col-sm-6">
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="system-dev" value="System Development/Enhancement" name="work_requested[]" />
                                                <label class="form-check-label" for="system-dev">System Development/Enhancement</label>
                                              </div>
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="website-dev" value="Website Development/Enhancement" name="work_requested[]" />
                                                <label class="form-check-label" for="website-dev">Website Development/Enhancement</label>
                                              </div>
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="is-account" value="Information System (IS) Account" name="work_requested[]" />
                                                <label class="form-check-label" for="is-account">Information System (IS) Account</label>
                                              </div>
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="class-schedule" value="Class Schedule" name="work_requested[]" />
                                                <label class="form-check-label" for="class-schedule">Class Schedule</label>
                                              </div>
                                            </div>
                                            <div class="col-sm-6">
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="student-grades" value="Student Grades" name="work_requested[]" />
                                                <label class="form-check-label" for="student-grades">Student Grades</label>
                                              </div>
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="student-info" value="Student Information" name="work_requested[]" />
                                                <label class="form-check-label" for="student-info">Student Information</label>
                                              </div>
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="sub-domain" value="Sub-domain registration" name="work_requested[]" />
                                                <label class="form-check-label" for="sub-domain">Sub-domain registration</label>
                                              </div>
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="email-reg" value="Email Registration" name="work_requested[]" />
                                                <label class="form-check-label" for="email-reg">Email Registration</label>
                                              </div>
                                              <div class="form-check mt-3">
                                                <input class="form-check-input work-request" type="checkbox" id="others" value="Others" name="work_requested[]" />
                                                <label class="form-check-label" for="others">Others:</label>
                                                <div class="input-group input-group-merge mt-2">
                                                  <textarea class="form-control" id="others-detail" placeholder="Specify other work request" name="others_detail"></textarea>
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <hr class="m-3" />
                                          <div class="card-body">
                                            <div class="row gy-3">
                                              <div class="row mb-3">
                                                <label for="work-request" class="form-label"><h6> Detailed Description of Work Request: (Required)</h6></label>
                                                <div class="col-sm-12 custom-width">
                                                  <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-message2" class="input-group-text">
                                                      <i class="far fa-comment-dots"></i>
                                                    </span>
                                                    <textarea
                                                      id="basic-icon-default-message"
                                                      class="form-control"
                                                      placeholder="Input the Details"
                                                      aria-label="Input the Details"
                                                      aria-describedby="basic-icon-default-message2"
                                                      name="description_of_work_request"
                                                      required></textarea>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="row mb-3">
                                                <div class="col-sm-12 mb-3">
                                                  <div class="form-group">
                                                    <label for="req_d_u_o" class="form-label"><h6>Action Taken:</h6></label>
                                                    <div class="input-group input-group-merge">
                                                      <span id="basic-icon-default-company2" class="input-group-text">
                                                      </span>
                                                      <input
                                                        type="text"
                                                        id="basic-icon-default-company"
                                                        class="form-control"
                                                        placeholder="ISU Staff Only"
                                                        aria-label="ISU Staff Only"
                                                        aria-describedby="basic-icon-default-company2"
                                                        name="action_taken"
                                                        readonly /> 
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="row mb-3">
                                                <div class="col-sm-12 mb-3">
                                                  <div class="form-group">
                                                    <label for="req_d_u_o" class="form-label"><h6>THIS WILL BE APPROVED BY:</h6></label>
                                                    <div class="input-group input-group-merge">
                                                      <span id="basic-icon-default-company2" class="input-group-text">
                                                      </span>
                                                      <input
                                                        type="text"
                                                        id="basic-icon-default-company"
                                                        class="form-control"
                                                        placeholder="CISA HEAD"
                                                        aria-label="ISU Staff Only"
                                                        aria-describedby="basic-icon-default-company2"
                                                        name="approved_by"
                                                        readonly/> 
                                                    </div>
                                                  </div>
                                                </div>
                                              </div>
                                              <div class="row justify-content-end">
                                               <div class="col-sm-15">
                                                  <button type="button" class="btn btn-primary" id="sendBtn">Send</button>
                                                </div>
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
                            <!-- Content wrapper -->                    
<script>
  document.getElementById('sendBtn').addEventListener('click', function () {
    // Show SweetAlert for confirmation
    Swal.fire({
      title: 'Are you sure?',
      text: 'Do you want to send this request?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, send it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // Simulate form submission
        document.getElementById('workRequestForm').submit();

        // Display success message
        Swal.fire({
          title: 'Success!',
          text: 'Your request has been submitted successfully.',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          // Add a delay before redirecting to another page
          setTimeout(() => {
            window.location.href = 'progress.php'; // Redirect to the next page
          }, 2000); // 2000 milliseconds = 2 seconds delay
        });
      }
    });
  });
</script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Simulate PHP session variable in JavaScript
    let lastRequestStatus = "<?php echo $_SESSION['last_request_status'] ?? ''; ?>";

    if (lastRequestStatus) {
      let countdown = 3; // Countdown timer
      let title = '';
      let message = '';
      let redirect = false; // Flag for redirection

      // Customize messages and determine redirection based on the status
      if (lastRequestStatus === 'Pending') {
        title = 'Pending Request';
        message = 'Your request is still pending. Redirecting in <b>' + countdown + '</b> seconds...';
        redirect = true; // Redirect for pending requests
      } else if (lastRequestStatus === 'Approved') {
        title = 'Request Approved';
        message = 'Congratulations! Your request has been approved.';
        redirect = true; // Redirect immediately for approved
      } else if (lastRequestStatus === 'Processed') {
        title = 'Request Processed';
        message = 'Your request has been processed successfully.';
        redirect = true; // Redirect immediately for processed
          } else if (lastRequestStatus === 'Delivered') {
        title = 'Request Progress Completed';
        message = 'Your request has been Completed successfully.';
        redirect = true; // Redirect immediately for processed
      } else if (lastRequestStatus === 'Declined') {
        title = 'Request Declined';
        message = 'Unfortunately, your request was declined. Please review.';
        redirect = false; // Redirect immediately for declined
      }

      if (title && message) {
        Swal.fire({
          title: title,
          html: message,
          icon: redirect ? 'info' : (lastRequestStatus === 'Declined' ? 'error' : 'success'),
          showConfirmButton: !redirect, // Hide confirm button if redirecting
          allowOutsideClick: !redirect, // Allow outside click only if not redirecting
          timer: redirect ? countdown * 1000 : null, // Timer for redirecting
          timerProgressBar: redirect, // Show progress bar if redirecting
          didOpen: () => {
            if (redirect && lastRequestStatus === 'Pending') {
              // Update the countdown dynamically for Pending status
              const interval = setInterval(() => {
                countdown--;
                Swal.update({
                  html: 'Your request is still pending. Redirecting in <b>' + countdown + '</b> seconds...'
                });
                if (countdown <= 0) {
                  clearInterval(interval);
                  window.location.href = 'progress.php'; // Redirect after countdown finishes
                }
              }, 1000);
            } else if (redirect) {
              // Immediately redirect for Approved, Processed, or Declined
              setTimeout(() => {
                window.location.href = 'progress.php';
              }, 1000); // Wait 1 second before redirecting
            }
          }
        });
      }
    }
  });
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

        <script src="../core/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
