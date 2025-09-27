<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-check-circle text-success"></i>
                        Completed Jobs
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($jobs)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No completed jobs available for review.
                        </div>
                    <?php else: ?>
                        <div class="alert alert-success">
                            <i class="fas fa-clock"></i>
                            <strong>Review Completed Jobs:</strong> You have 24 hours to review completed jobs. You can close jobs you're satisfied with or dispute any issues. After 24 hours, payment will be automatically released to the cleaner.
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Cleaner</th>
                                        <th>Completed At</th>
                                        <th>Final Price</th>
                                        <th>Review Window</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs as $job): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($job->title) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars(substr($job->description, 0, 100)) ?><?= strlen($job->description) > 100 ? '...' : '' ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($job->cleaner_first_name . ' ' . $job->cleaner_last_name) ?>
                                            </td>
                                            <td>
                                                <?= date('M j, Y g:i A', strtotime($job->completed_at)) ?>
                                            </td>
                                            <td>
                                                <strong>$<?= number_format($job->final_price ?: $job->accepted_price, 2) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    Expires: <?= date('M j, Y g:i A', strtotime($job->dispute_window_ends_at)) ?>
                                                    <br>
                                                    <?php 
                                                    $time_left = strtotime($job->dispute_window_ends_at) - time();
                                                    if ($time_left > 0) {
                                                        $hours = floor($time_left / 3600);
                                                        $minutes = floor(($time_left % 3600) / 60);
                                                        $time_text = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
                                                        echo '<span class="badge badge-warning">' . $time_text . ' left</span>';
                                                    } else {
                                                        echo '<span class="badge badge-danger">Expired</span>';
                                                    }
                                                    ?>
                                                </small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-success btn-sm" 
                                                            onclick="closeJob(<?= $job->id ?>, '<?= htmlspecialchars($job->title) ?>')">
                                                        <i class="fas fa-check-circle"></i> Close Job
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-warning btn-sm" 
                                                            onclick="showDisputeForm(<?= $job->id ?>, '<?= htmlspecialchars($job->title) ?>')">
                                                        <i class="fas fa-exclamation-triangle"></i> Report Issues
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dispute Form Section -->
<div class="container-fluid" id="disputeFormContainer" style="display: none;">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                        Report Job Issues
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="hideDisputeForm()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle"></i>
                        <strong>Job Review:</strong> Please select the issues you encountered with this job. This will help us resolve the matter fairly.
                    </div>
                    
                    <form id="disputeForm">
                        <input type="hidden" id="disputeJobId" name="job_id">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <h5>Select Issues Found:</h5>
                                <div class="form-group">
                                    <div class="checkbox-group">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_quality" name="issues[]" value="quality">
                                            <label class="form-check-label" for="issue_quality">
                                                <strong>Poor Quality Work</strong> - Workmanship below expected standards
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_incomplete" name="issues[]" value="incomplete">
                                            <label class="form-check-label" for="issue_incomplete">
                                                <strong>Incomplete Work</strong> - Not all requested tasks were completed
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_damage" name="issues[]" value="damage">
                                            <label class="form-check-label" for="issue_damage">
                                                <strong>Property Damage</strong> - Items or surfaces were damaged during service
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_equipment" name="issues[]" value="equipment">
                                            <label class="form-check-label" for="issue_equipment">
                                                <strong>Equipment Issues</strong> - Cleaner's equipment caused problems
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_behavior" name="issues[]" value="behavior">
                                            <label class="form-check-label" for="issue_behavior">
                                                <strong>Unprofessional Behavior</strong> - Cleaner was rude or unprofessional
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_time" name="issues[]" value="time">
                                            <label class="form-check-label" for="issue_time">
                                                <strong>Time Issues</strong> - Significantly late or rushed work
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_supplies" name="issues[]" value="supplies">
                                            <label class="form-check-label" for="issue_supplies">
                                                <strong>Supply Issues</strong> - Used wrong or inadequate cleaning supplies
                                            </label>
                                        </div>
                                        
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="issue_other" name="issues[]" value="other">
                                            <label class="form-check-label" for="issue_other">
                                                <strong>Other Issues</strong> - Please specify in notes below
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="disputeNotes">Additional Notes</label>
                                    <textarea class="form-control" 
                                              id="disputeNotes" 
                                              name="notes" 
                                              rows="4" 
                                              maxlength="1000"
                                              placeholder="Please provide additional details about the issues encountered. Include specific examples, locations, or circumstances that would help us understand the situation better."></textarea>
                                    <small class="form-text text-muted">
                                        <span id="notesCount">0</span>/1000 characters
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">What Happens Next?</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="mb-0 small">
                                            <li>Your report will be reviewed by a moderator</li>
                                            <li>The cleaner will be notified of the issues</li>
                                            <li>Evidence and documentation will be requested</li>
                                            <li>A fair resolution will be determined</li>
                                            <li>Payment may be adjusted based on findings</li>
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <button type="button" class="btn btn-warning btn-block" onclick="submitDisputeReport()">
                                        <i class="fas fa-paper-plane"></i> Submit Report
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-block mt-2" onclick="hideDisputeForm()">
                                        <i class="fas fa-times"></i> Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDisputeForm(jobId, jobTitle) {
    $('#disputeJobId').val(jobId);
    $('#disputeFormContainer').slideDown();
    
    // Reset form
    $('#disputeForm')[0].reset();
    $('#notesCount').text('0');
    
    // Scroll to form
    $('html, body').animate({
        scrollTop: $('#disputeFormContainer').offset().top - 20
    }, 500);
}

function hideDisputeForm() {
    $('#disputeFormContainer').slideUp();
}

function submitDisputeReport() {
    var jobId = $('#disputeJobId').val();
    var selectedIssues = [];
    var notes = $('#disputeNotes').val();
    
    // Get selected issues
    $('input[name="issues[]"]:checked').each(function() {
        selectedIssues.push($(this).val());
    });
    
    // Validation
    if (selectedIssues.length === 0) {
        alert('Please select at least one issue to report.');
        return;
    }
    
    if (notes.length > 1000) {
        alert('Notes cannot exceed 1000 characters.');
        return;
    }
    
    // Confirm submission
    var issuesText = selectedIssues.join(', ');
    var confirmMessage = 'Are you sure you want to submit this report?\n\nIssues: ' + issuesText;
    if (notes.trim()) {
        confirmMessage += '\n\nNotes: ' + notes.substring(0, 100) + (notes.length > 100 ? '...' : '');
    }
    confirmMessage += '\n\nThis will prevent automatic payment release and require moderator review.';
    
    if (confirm(confirmMessage)) {
        submitDisputeWithForm(jobId, selectedIssues, notes);
    }
}

function submitDisputeWithForm(jobId, issues, notes) {
    $.ajax({
        url: '<?= base_url("disputes/dispute-job") ?>',
        type: 'POST',
        data: {
            job_id: jobId,
            issues: JSON.stringify(issues),
            notes: notes
        },
        dataType: 'json',
        beforeSend: function() {
            $('button[onclick="submitDisputeReport()"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Submitting...');
        },
        success: function(response) {
            if (response.success) {
                alert('Issue report submitted successfully. A moderator will review your report.');
                hideDisputeForm();
                location.reload();
            } else {
                alert('Error: ' + response.message);
                $('button[onclick="submitDisputeReport()"]').prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Submit Report');
            }
        },
        error: function() {
            alert('An error occurred. Please try again.');
            $('button[onclick="submitDisputeReport()"]').prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Submit Report');
        }
    });
}

function closeJob(jobId, jobTitle) {
    if (confirm('Are you sure you want to close this job? This will release payment to the cleaner and cannot be undone.')) {
        $.ajax({
            url: '<?= base_url("disputes/close-job") ?>',
            type: 'POST',
            data: {
                job_id: jobId
            },
            dataType: 'json',
            beforeSend: function() {
                // Show loading state
                $('button[onclick*="' + jobId + '"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Job closed successfully. Payment has been released to the cleaner.');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    $('button[onclick*="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Close Job');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $('button[onclick*="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Close Job');
            }
        });
    }
}

$(document).ready(function() {
    // Character counter for notes
    $('#disputeNotes').on('input', function() {
        var count = $(this).val().length;
        $('#notesCount').text(count);
        
        // Change color based on character count
        if (count > 900) {
            $('#notesCount').addClass('text-danger');
        } else if (count > 800) {
            $('#notesCount').addClass('text-warning');
        } else {
            $('#notesCount').removeClass('text-danger text-warning');
        }
    });
    
    // Handle "Other Issues" checkbox
    $('#issue_other').on('change', function() {
        if ($(this).is(':checked')) {
            $('#disputeNotes').attr('placeholder', 'Please specify the other issues encountered. Include specific examples, locations, or circumstances that would help us understand the situation better.');
        } else {
            $('#disputeNotes').attr('placeholder', 'Please provide additional details about the issues encountered. Include specific examples, locations, or circumstances that would help us understand the situation better.');
        }
    });
});

</script>

<style>
/* Make container wider */
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
</style>
