        <!-- Modal Structure -->
        <div class="modal fade" id="requestModal<?php echo $requestId; ?>" tabindex="-1" aria-labelledby="requestModalLabel<?php echo $requestId; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestModalLabel<?php echo $requestId; ?>"><?php echo $requestingDept; ?> - Request Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Work Requested:</strong> <?php echo $workRequested; ?></p>
                        <p><strong>Description:</strong> <?php echo $description; ?></p>
                        <p><strong>Date of Request:</strong> <?php echo date("M d, Y", strtotime($dateOfReq)); ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

<!-- Modal -->
        <div class="modal fade" id="requestModals<?php echo $requestId; ?>" tabindex="-1" aria-labelledby="requestModalLabel<?php echo $requestId; ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="requestModalLabel">Job Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul>
                    <li>Requesting Department: &nbsp;<span class="text-primary"><?php echo htmlspecialchars($requestingDept); ?></span></li>
                    <li>Work Type: &nbsp;<span class="text-primary"><?php echo htmlspecialchars($workType); ?></span></li>
                    <li>Date of Request: &nbsp;<span class="text-primary" id="modalDateOfReq<?php echo $requestId; ?>"></span></li>
                    <li>Time of Request: &nbsp;<span class="text-primary" id="modalTimeOfReq<?php echo $requestId; ?>"></span></li>
                    <li>Action Taken: &nbsp;<span class="text-primary" id="modalActionTaken<?php echo $requestId; ?>"></span></li>
                    <li>Work Requested: &nbsp;<span class="text-primary" id="modalWorkRequested<?php echo $requestId; ?>"></span></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Take Action</button>
            </div>
        </div>
    </div>
</div>

