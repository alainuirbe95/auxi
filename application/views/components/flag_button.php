<?php
// Flag Button Component
// Usage: Load this view with $job_id and $user_id variables
?>

<div class="flag-button-container">
    <button type="button" 
            class="btn btn-sm btn-outline-warning flag-job-btn" 
            data-job-id="<?php echo $job_id; ?>"
            data-user-id="<?php echo $user_id; ?>"
            title="Flag this job for review">
        <i class="fas fa-flag"></i>
        Flag Job
    </button>
</div>

<!-- Flag Job Modal -->
<div class="modal fade" id="flagJobModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-flag mr-2"></i>
                    Flag Job for Review
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="flagJobForm">
                    <div class="form-group">
                        <label for="flagReason">Reason for Flagging</label>
                        <select class="form-control" id="flagReason" name="flag_reason" required>
                            <option value="">Select a reason</option>
                            <option value="inappropriate_content">Inappropriate Content</option>
                            <option value="spam">Spam or Fake Job</option>
                            <option value="safety_concerns">Safety Concerns</option>
                            <option value="price_too_low">Price Too Low</option>
                            <option value="price_too_high">Price Too High</option>
                            <option value="unclear_requirements">Unclear Requirements</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="flagDetails">Additional Details</label>
                        <textarea class="form-control" 
                                  id="flagDetails" 
                                  name="flag_details" 
                                  rows="4" 
                                  placeholder="Please provide additional details about why you're flagging this job..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="submitFlag">
                    <i class="fas fa-flag mr-1"></i>
                    Submit Flag
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.flag-button-container {
    display: inline-block;
}

.flag-job-btn {
    transition: all 0.3s ease;
}

.flag-job-btn:hover {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    transform: scale(1.05);
}

.flag-job-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #ffc107 0%, #ff8f00 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-header .close:hover {
    opacity: 1;
}

.form-control:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}
</style>

<script>
$(document).ready(function() {
    // Flag job button click handler
    $(document).on('click', '.flag-job-btn', function(e) {
        e.preventDefault();
        $('#flagJobModal').modal('show');
    });

    // Submit flag
    $('#submitFlag').on('click', function() {
        const $button = $(this);
        const jobId = $('.flag-job-btn').data('job-id');
        const flagReason = $('#flagReason').val();
        const flagDetails = $('#flagDetails').val();

        if (!flagReason) {
            alert('Please select a reason for flagging this job.');
            return;
        }

        // Show loading state
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Submitting...');

        // Determine the flag endpoint based on current user role
        let flagUrl = '';
        const currentUrl = window.location.pathname;
        if (currentUrl.includes('/admin/')) {
            flagUrl = '<?php echo base_url('admin/flag_job'); ?>';
        } else if (currentUrl.includes('/host/')) {
            flagUrl = '<?php echo base_url('host/flag_job'); ?>';
        } else {
            flagUrl = '<?php echo base_url('admin/flag_job'); ?>'; // Default to admin
        }

        // Make AJAX request
        $.ajax({
            url: flagUrl,
            type: 'POST',
            data: {
                job_id: jobId,
                flag_reason: flagReason,
                flag_details: flagDetails
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert('Job flagged successfully! Thank you for your report.');
                    
                    // Close modal and reset form
                    $('#flagJobModal').modal('hide');
                    $('#flagJobForm')[0].reset();
                    
                    // Disable the flag button to prevent duplicate flags
                    $('.flag-job-btn').prop('disabled', true).html('<i class="fas fa-check mr-1"></i>Flagged');
                } else {
                    alert('Error: ' + response.message);
                    // Reset button
                    $button.prop('disabled', false).html('<i class="fas fa-flag mr-1"></i>Submit Flag');
                }
            },
            error: function() {
                alert('An error occurred while flagging the job. Please try again.');
                // Reset button
                $button.prop('disabled', false).html('<i class="fas fa-flag mr-1"></i>Submit Flag');
            }
        });
    });

    // Reset form when modal is hidden
    $('#flagJobModal').on('hidden.bs.modal', function() {
        $('#flagJobForm')[0].reset();
        $('#submitFlag').prop('disabled', false).html('<i class="fas fa-flag mr-1"></i>Submit Flag');
    });
});
</script>
