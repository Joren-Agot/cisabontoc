<style>
  .menu-item:hover {
    background-color: lightblue;
  }
  body.dark-mode {
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .menu-inner.dark-mode {
            background-color: #252525;
            color: #ffffff;
        }

        .card.dark-mode {
            background-color: #2c2c2c;
            color: #ffffff;
        }

.content-wrapper {
    position: relative;
    z-index: 1; /* Ensure the content wrapper is above the backdrop */
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7); /* Adjust backdrop opacity */
    z-index: 1; /* Place the backdrop behind the content */
}

</style>

                            <!-- Modal Pending -->
<div class="modal fade" id="modalPending" tabindex="-1" aria-labelledby="testModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalPendingTitle">Pending</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <p id="modalPendingContent">Your request is still pending. Please wait for approval.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" id="closeModalBtnPending" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Approved -->
                            <div class="modal fade" id="modalApproved" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalApprovedTitle">Approved</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <p id="modalApprovedContent">Your request has been approved.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" id="closeModalBtnApproved" data-bs-dismiss="modal" onclick="window.location.href='../dashboard.php'">
                                                Close
                                            </button>
                                            <button type="button" class="btn btn-primary" id="proceedModal">
                                                Proceed
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Declined -->
                            <div class="modal fade" id="modalDeclined" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDeclinedTitle">Declined</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <p id="modalDeclinedContent">Your request has been declined. Please contact support for further assistance.</p>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" id="closeModalBtnDeclined" data-bs-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

<script>
$(document).ready(function() {
    // Function to show modal and prevent backdrop close
    function showModal(modalId) {
        $(modalId).modal({
            backdrop: 'static', // Prevent closing on backdrop click
            keyboard: false // Prevent closing when pressing escape key
        });
        $(modalId).modal('show');
    }

    // Check if session variable 'last_request_status' is set and is 'Pending', 'Approved', or 'Declined'
    <?php if (isset($_SESSION['last_request_status']) && $_SESSION['last_request_status'] === 'Pending'): ?>
        // Show the pending modal
        showModal('#modalPending');
    <?php elseif (isset($_SESSION['last_request_status']) && $_SESSION['last_request_status'] === 'Approved'): ?>
        // Show the approved modal
        showModal('#modalApproved');
    <?php elseif (isset($_SESSION['last_request_status']) && $_SESSION['last_request_status'] === 'Declined'): ?>
        // Show the declined modal
        showModal('#modalDeclined');
    <?php endif; ?>

    // Handle the proceed button action
    $('#closeModalBtnApproved').on('click', function() {
        // Redirect to dashboard_client.php
        window.location.href = '../Client/dashboard.php';
    });

    $('#proceedModal').on('click', function() {
        // Redirect to request_review.php
        window.location.href = 'request_review.php';
    });

    // Add event listener when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent modal from closing on backdrop click for all modals
        $('#modalPending, #modalApproved, #modalDeclined').on('hide.bs.modal', function(e) {
            if (e.target === this) {
                e.preventDefault(); // Prevent closing the modal
            }
        });
    });

    // Handle the close button action for the Declined modal
    $('#closeModalBtnDeclined').on('click', function() {
        // Update the status to 'Done'
        $.ajax({
            url: 'update_status.php', // Change this URL to the PHP file that will handle the status update
            type: 'POST',
            data: {
                client_id: '<?php echo $userId; ?>',
                status: 'Done'
            },
            success: function(response) {
                // Handle success response
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error response
                console.error(error);
            }
        });
    });
});

</script>

