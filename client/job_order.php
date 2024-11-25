
<?php include '../core/partials/main.php'; ?>
<?php include 'client-assets/job_script.php'; ?>
    <head>
        
    <?php includeFileWithVariables('../core/partials/title-meta.php', array('title' => 'Dashboard')); ?>
        <link href="../assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />s
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

                        <?php includeFileWithVariables('../core/partials/page-title.php', array('pagetitle' => 'CISA' , 'title' => 'Job Order')); ?>
                       <?php include 'client-assets/jobmodal.php'; ?>                  
                      <!-- Content -->
                      <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="py-3 mb-4"><span class="text-muted fw-light">CISA/</span> JOB ORDER FORM</h4>
                        <div class="row">
                          <div class="col-xxl">
                
                                <form id="workRequestForm" action="job_order.php" method="POST">
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
                                           <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                          <input class="form-control" type="date" id="html5-date-input" name="date_of_req" disabled />
                                         
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-sm-3 mb-3">
                                      <div class="form-group">
                                        <label for="html5-time-input" class="form-label"><h6>Time of Request:</h6></label>
                                        <div class="input-group">
                                           <span class="input-group-text"><i class=" far fa-clock"></i></span>
                                          <input class="form-control" type="time" id="html5-time-input" name="time_of_req" disabled />
                                         
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="row mb-3">
                                    <div class="col-sm-6 mb-3">
                                      <div class="form-group">
                                        <label for="name_of_requestor" class="form-label"><h6>Name of Requestor:</h6></label>
                                        <div class="input-group input-group-merge">
                                          <span id="basic-icon-default-name" class="input-group-text">
                                            <i class=" ri-user-line"></i>
                                          </span>
                                          <input
                                            type="text"
                                            id="name_of_requestor"
                                            name="name_of_requestor"
                                            class="form-control"
                                            placeholder=""
                                            aria-label="John Doe"
                                            aria-describedby="basic-icon-default-name"
                                            required
                                          />
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <hr class="m-2" />
                                  <div class="text-center mb-3">
                                    <h4>Job Description</h4>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="form-check mt-3">
                                      <input
                                        name="work_type"
                                        class="form-check-input"
                                        type="radio"
                                        value="PREVENTIVE_MAINTENANCE"
                                        id="jobDescriptionRadio"
                                        required
                                      />
                                      <label class="form-check-label black-label" for="jobDescriptionRadio">PREVENTIVE MAINTENACE:</label>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-check mt-3">
                                        <input class="form-check-input job-description-check" type="checkbox" id="cpu" value="Cpu" name="work_requested[]" />
                                        <label class="form-check-label" for="cpu">Cpu</label>
                                      </div>
                                      <div class="form-check mt-3">
                                        <input class="form-check-input job-description-check" type="checkbox" id="printer" value="Printer" name="work_requested[]" />
                                        <label class="form-check-label" for="printer">Printer</label>
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-check mt-3">
                                        <input class="form-check-input job-description-check" type="checkbox" id="software-printing" value="Software Printing" name="work_requested[]" />
                                        <label class="form-check-label" for="software-printing">Software Printing</label>
                                      </div>
                                      <div class="form-check mt-3">
                                        <input class="form-check-input job-description-check" type="checkbox" id="others" value="Others" name="work_requested[]" />
                                        <label class="form-check-label" for="others">Others:</label>
                                        <div class="input-group input-group-merge mt-2">
                                          <textarea class="form-control" id="others-detail" placeholder="Specify other work request" name="others_detail"></textarea>
                                        </div>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="row mb-3">
                                    <div class="form-check mt-3">
                                      <input
                                        class="form-check-input"
                                        type="radio"
                                        id="correctiveMaintenanceRadio"
                                        value="CORRECTIVE_MAINTENANCE"
                                        name="work_type"
                                        required
                                      />
                                      <label class="form-check-label black-label" for="correctiveMaintenanceRadio">CORRECTIVE MAINTENANCE</label>
                                    </div>
                                  </div>
                                  <div class="row mb-3">
                                    <div class="col-sm-6">
                                      <div class="form-check mt-3">
                                        <h6 class="black-label">HARDWARE:</h6>
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="hardware-cpu"
                                          value="Cpu"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="hardware-cpu">CPU</label>
                                        <input type="hidden" name="hardware_category_id[]" value="1">
                                      </div>
                                      <div class="form-check mt-3">
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="hardware-printer"
                                          value="Printer"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="hardware-printer">Printer</label>
                                        <input type="hidden" name="hardware_category_id[]" value="1">
                                      </div>
                                      <div class="form-check mt-3">
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="hardware-software-printing"
                                          value="Software Printing"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="hardware-software-printing">LAN Troubleshooting</label>
                                        <input type="hidden" name="hardware_category_id[]" value="1">
                                      </div>
                                      <div class="form-check mt-3">
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="hardware-others"
                                          value="Others"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="hardware-others">LAN Installation</label>
                                        <input type="hidden" name="hardware_category_id[]" value="1">
                                      </div>
                                    </div>
                                    <div class="col-sm-6">
                                      <div class="form-check mt-3">
                                        <h6 class="black-label">SOFTWARE:</h6>
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="software-dev"
                                          value="System Development/Enhancement"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="software-dev">SOFTWARE (Anti-Virus, Office, etc)</label>
                                        <input type="hidden" name="software_category_id[]" value="2">
                                      </div>
                                      <div class="form-check mt-3">
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="software-printer"
                                          value="Printer"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="software-printer">Enrollment System</label>
                                        <input type="hidden" name="software_category_id[]" value="2">
                                      </div>
                                      <div class="form-check mt-3">
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="software-software-printing"
                                          value="Software Printing"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="software-software-printing">System Re-Installation(OS)</label>
                                        <input type="hidden" name="software_category_id[]" value="2">
                                      </div>
                                      <div class="form-check mt-3">
                                        <input
                                          class="form-check-input corrective-maintenance-check"
                                          type="checkbox"
                                          id="software-others"
                                          value="Others"
                                          name="work_requested[]"
                                        />
                                        <label class="form-check-label" for="software-others">Others:</label>
                                        <div class="input-group input-group-merge mt-2">
                                          <textarea
                                            class="form-control"
                                            id="software-others-detail"
                                            placeholder="Specify other work request"
                                            name="others_detail"
                                            disabled
                                          ></textarea>
                                        </div>
                                        <input type="hidden" name="software_category_id[]" value="2">
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
                                                placeholder="CAN Staff Only"
                                                aria-label="CAN Staff Only"
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
                                                placeholder="CAN OFFICER"
                                                aria-label="CAN Staff Only"
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
                                </form>
                              
                          </div>
                        </div>
                      </div>
                    </div>


                    </div>
                    <!-- container-fluid -->
                </div>
                <!-- End Page-content -->

                <?php include '../core/partials/footer.php'; ?>

            </div>
            <!-- end main content-->
<script>
  document.getElementById('sendBtn').addEventListener('click', function() {
    // Show SweetAlert
    Swal.fire({
      title: 'Are you sure?',
      text: 'Do you want to send this request?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, send it!',
      cancelButtonText: 'Cancel'
    }).then((result) => {
      if (result.isConfirmed) {
        // If user confirms, submit the form
        document.getElementById('workRequestForm').submit();
      }
    });
  });
</script>
        </div>
        <!-- END layout-wrapper -->

        <?php include '../core/partials/sidebar.php'; ?>

        <?php include '../core/partials/vendor-scripts.php'; ?>

        <script src="../core/assets/libs/sweetalert2/sweetalert2.all.min.js"></script>
        <!-- jquery.vectormap map -->
        <script src="../core/assets/libs/jqvmap/jquery.vmap.min.js"></script>
        <script src="../core/assets/libs/jqvmap/maps/jquery.vmap.usa.js"></script>


        <script src="../core/assets/js/app.js"></script>

    </body>
</html>
