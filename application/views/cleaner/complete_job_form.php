<style>
/* Container styling for wider desktop view */
.container-fluid {
    max-width: 95% !important;
    margin: 0 auto !important;
}

@media (min-width: 1200px) {
    .container-fluid {
        max-width: 97% !important;
    }
}

@media (min-width: 1400px) {
    .container-fluid {
        max-width: 98% !important;
    }
}

/* Permanent warning and info boxes - completely custom, no Bootstrap alert classes */
.permanent-warning-box {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border: 2px solid #ffc107;
    border-left: 6px solid #ffc107;
    color: #856404;
    padding: 1.25rem 1.5rem;
    margin: 1.5rem 0;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.2);
    position: relative;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    z-index: 10;
}

.permanent-warning-box i {
    color: #ffc107;
    margin-right: 0.75rem;
    font-size: 1.2rem;
}

.permanent-info-box {
    background: linear-gradient(135deg, #cce7ff 0%, #b3d9ff 100%);
    border: 2px solid #007bff;
    border-left: 6px solid #007bff;
    color: #004085;
    padding: 1.25rem 1.5rem;
    margin: 1.5rem 0;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    box-shadow: 0 4px 8px rgba(0, 123, 255, 0.2);
    position: relative;
    display: block !important;
    opacity: 1 !important;
    visibility: visible !important;
    z-index: 10;
}

.permanent-info-box i {
    color: #007bff;
    margin-right: 0.75rem;
    font-size: 1.2rem;
}

/* Ensure these boxes are never hidden or faded */
.permanent-warning-box,
.permanent-info-box {
    animation: none !important;
    transition: none !important;
    transform: none !important;
}

/* Override any potential hiding */
.permanent-warning-box *,
.permanent-info-box * {
    display: inline !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle text-success"></i>
                        Complete Job: <?= htmlspecialchars($job->title) ?>
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Job Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Job Information</h5>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Host:</strong></td>
                                    <td><?= htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Address:</strong></td>
                                    <td><?= htmlspecialchars($job->address) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Scheduled:</strong></td>
                                    <td>
                                        <?= date('M j, Y', strtotime($job->scheduled_date)) ?>
                                        <?php if ($job->scheduled_time): ?>
                                            at <?= date('g:i A', strtotime($job->scheduled_time)) ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Started:</strong></td>
                                    <td><?= date('M j, Y g:i A', strtotime($job->started_at)) ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Suggested Price:</strong></td>
                                    <td>$<?= number_format($job->suggested_price, 2) ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Job Description</h5>
                            <p><?= nl2br(htmlspecialchars($job->description)) ?></p>
                        </div>
                    </div>

                    <!-- Inconsistencies Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Report Any Issues or Inconsistencies
                                    </h5>
                                    <small>Please report any problems you encountered during the service before completing the job.</small>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($inconsistencies)): ?>
                                        <div class="mb-3">
                                            <h6>Previously Reported Issues:</h6>
                                            <?php foreach ($inconsistencies as $inconsistency): ?>
                                                <div class="alert alert-warning">
                                                    <strong><?= ucfirst(str_replace('_', ' ', $inconsistency->inconsistency_type)) ?></strong>
                                                    <span class="badge badge-<?= $inconsistency->severity === 'critical' ? 'danger' : ($inconsistency->severity === 'high' ? 'warning' : 'info') ?>">
                                                        <?= ucfirst($inconsistency->severity) ?>
                                                    </span>
                                                    <p class="mb-0 mt-1"><?= nl2br(htmlspecialchars($inconsistency->description)) ?></p>
                                                    <small class="text-muted">Reported on <?= date('M j, Y g:i A', strtotime($inconsistency->reported_at)) ?></small>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="permanent-warning-box">
                                        <i class="fas fa-video fa-lg"></i>
                                        <strong>Video Proof Required:</strong> Please send a WhatsApp video to the host showing the completed cleaning work before submitting this completion form.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Completion Form -->
                    <div class="row">
                        <div class="col-12">
                            <form id="completionForm">
                                <input type="hidden" name="job_id" value="<?= $job->id ?>">
                                
                                <div class="card">
                                    <div class="card-header bg-success text-white">
                                        <h5 class="mb-0">
                                            <i class="fas fa-check-circle"></i>
                                            Job Completion Details
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Final Price</label>
                                            <div class="alert alert-success">
                                                <i class="fas fa-dollar-sign"></i>
                                                <strong>Agreed Price: $<?= number_format($job->accepted_price, 2) ?></strong>
                                                <br>
                                                <small class="text-muted">The price is locked and cannot be changed at this stage.</small>
                                            </div>
                                            <input type="hidden" name="final_price" value="<?= $job->accepted_price ?>">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="completion_notes">Completion Notes</label>
                                            <textarea class="form-control" 
                                                      id="completion_notes" 
                                                      name="completion_notes" 
                                                      rows="4" 
                                                      maxlength="1000"
                                                      placeholder="Add any notes about the completed service (optional)"></textarea>
                                            <small class="form-text text-muted">
                                                <span id="notesCount">0</span>/1000 characters
                                            </small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="confirm_completion" required>
                                                <label class="form-check-label" for="confirm_completion">
                                                    <strong>I confirm that the service has been completed successfully</strong>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="permanent-info-box">
                                            <i class="fas fa-info-circle fa-lg"></i>
                                            <strong>Important:</strong> Once you complete this job, the host will be notified and will have 24 hours to mark the job as complete and release payment. If the host doesn't take action within 24 hours, payment will be automatically delivered. The host cannot dispute the service - if they have any issues, they can raise a "recall" after the service is closed.
                                        </div>
                                        
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="fas fa-check-circle"></i> Complete Job
                                            </button>
                                            <a href="<?= base_url('cleaner/jobs-in-progress') ?>" class="btn btn-secondary btn-lg ml-2">
                                                <i class="fas fa-arrow-left"></i> Back to Jobs
                                            </a>
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
</div>

<!-- Inconsistency Modal -->
<div class="modal fade" id="inconsistencyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Report Service Issue
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="inconsistencyModalBody">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Price is locked, no original price tracking needed
    
    // Ensure permanent warning and info boxes stay visible
    function ensurePermanentBoxesVisible() {
        $('.permanent-warning-box, .permanent-info-box').each(function() {
            $(this).css({
                'display': 'block !important',
                'opacity': '1 !important',
                'visibility': 'visible !important',
                'position': 'relative !important',
                'z-index': '10 !important'
            });
        });
    }
    
    // Run immediately and periodically
    ensurePermanentBoxesVisible();
    setInterval(ensurePermanentBoxesVisible, 1000); // Check every second
    
    // Prevent any hiding attempts
    $('.permanent-warning-box, .permanent-info-box').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        ensurePermanentBoxesVisible();
    });
    
    // Character counter for completion notes
    $('#completion_notes').on('input', function() {
        var length = $(this).val().length;
        $('#notesCount').text(length);
    });
    
    // Price is locked, no adjustment logic needed
    
    // Form submission
    $('#completionForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#confirm_completion').is(':checked')) {
            alert('Please confirm that the service has been completed.');
            return;
        }
        
        // Price is locked, no formatting needed
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '<?= base_url("cleaner/jobs-in-progress/process-completion") ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect;
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
});

// showInconsistencyForm function removed - replaced with WhatsApp video reminder
</script>
