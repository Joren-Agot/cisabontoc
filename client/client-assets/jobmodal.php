<style>
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
</head>
<body>
<!-- Modal -->
<div class="modal fade" id="modalPending" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
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
                <button type="button" class="btn btn-outline-secondary" id="closeModalBtn" data-bs-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

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
                <button type="button" class="btn btn-outline-secondary" id="closeModalBtn" data-bs-dismiss="modal" onclick="window.location.href='../Client/dashboard.php'">
                    Close
                </button>
                <button type="button" class="btn btn-primary" id="proceedModal">
                    Proceed
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

    // Check if session variable 'last_job_order_status' is set and is 'Pending', 'Approved', or 'Declined'
    <?php if (isset($_SESSION['last_job_order_status']) && in_array($_SESSION['last_job_order_status'], ['Pending', 'Approved', 'Declined'])): ?>
        // Show the corresponding modal based on the session variable
        <?php if ($_SESSION['last_job_order_status'] === 'Pending'): ?>
            showModal('#modalPending');
        <?php elseif ($_SESSION['last_job_order_status'] === 'Approved'): ?>
            showModal('#modalApproved');
        <?php elseif ($_SESSION['last_job_order_status'] === 'Declined'): ?>
            showModal('#modalDeclined');
        <?php endif; ?>
    <?php endif; ?>

    // Handle the proceed button action for approved modal
    $('#proceedModal').on('click', function() {
        window.location.href = 'job_review.php';
    });

    // Close modal button action
    $('#closeModalBtn').on('click', function() {
        window.location.href = '../Client/dashboard.php';
    });

    // Add event listener when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent modal from closing on backdrop click for all modals
        $('#modalPending').on('hide.bs.modal', function(e) {
            if (e.target === this) {
                e.preventDefault(); // Prevent closing the modal
            }
        });

        $('#modalApproved').on('hide.bs.modal', function(e) {
            if (e.target === this) {
                e.preventDefault(); // Prevent closing the modal
            }
        });

        $('#modalDeclined').on('hide.bs.modal', function(e) {
            if (e.target === this) {
                e.preventDefault(); // Prevent closing the modal
            }
        });
    });
});
</script>
