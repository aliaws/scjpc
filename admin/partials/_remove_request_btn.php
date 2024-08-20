<?php
    $status = !isset($status) ? 'Processing': $status;
    if($status == 'Processed'):
?>
    <button data-api-action = "remove-all-processed-exports"
            id="remove_all_processed_exports"
            type="button" class="btn btn-danger remove_exports">
        Remove All Processed Requests
    </button>
<?php endif; ?>
<?php
if($status == 'Pending'):
?>
    <button data-api-action = "remove-pending-exports"
            id="remove_pending_exports"
            type="button" class="btn btn-danger remove_exports">
        Remove Pending Requests
    </button>
<?php endif; ?>
<?php
if($status == 'Processing'):
?>
    <button data-api-action = "remove-processing-exports"
            id="remove_processing_exports"
            type="button" class="btn btn-danger remove_exports">
        Remove Processing Requests
    </button>
<?php endif; ?>