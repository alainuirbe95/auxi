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
                                    
                                    <button type="button" class="btn btn-warning" onclick="showInconsistencyForm(<?= $job->id ?>)">
                                        <i class="fas fa-plus"></i> Report New Issue
                                    </button>
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
                                            <label for="final_price">Final Price</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" 
                                                       class="form-control" 
                                                       id="final_price" 
                                                       name="final_price" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="<?= $job->accepted_price ?>"
                                                       placeholder="Enter final price">
                                            </div>
                                            <small class="form-text text-muted">
                                                Agreed price: $<?= number_format($job->accepted_price, 2) ?> | 
                                                If you change this amount, a price adjustment request will be sent to the host for approval.
                                            </small>
                                        </div>
                                        
                                        <div class="form-group" id="price_reason_group" style="display: none;">
                                            <label for="price_reason">Reason for Price Adjustment</label>
                                            <textarea class="form-control" 
                                                      id="price_reason" 
                                                      name="price_reason" 
                                                      rows="3" 
                                                      maxlength="500"
                                                      placeholder="Explain why the price needs to be adjusted (e.g., additional work required, unexpected conditions, etc.)"></textarea>
                                            <small class="form-text text-muted">
                                                <span id="reasonCount">0</span>/500 characters
                                            </small>
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
                                        
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Important:</strong> Once you complete this job, the host will be notified and will have 24 hours to dispute the service if there are any issues. Payment will be automatically released after the dispute window expires.
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
    var originalPrice = <?= $job->accepted_price ?>;
    
    // Character counter for completion notes
    $('#completion_notes').on('input', function() {
        var length = $(this).val().length;
        $('#notesCount').text(length);
    });
    
    // Character counter for price reason
    $('#price_reason').on('input', function() {
        var count = $(this).val().length;
        $('#reasonCount').text(count);
    });
    
    // Show/hide price reason field based on price change
    $('#final_price').on('input', function() {
        var currentPrice = parseFloat($(this).val()) || 0;
        if (Math.abs(currentPrice - originalPrice) > 0.01) {
            $('#price_reason_group').slideDown();
            $('#price_reason').prop('required', true);
        } else {
            $('#price_reason_group').slideUp();
            $('#price_reason').prop('required', false);
        }
    });
    
    // Form submission
    $('#completionForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!$('#confirm_completion').is(':checked')) {
            alert('Please confirm that the service has been completed.');
            return;
        }
        
        // Format final price as decimal before submission
        var finalPrice = $('#final_price').val();
        if (finalPrice && finalPrice !== '') {
            var formattedPrice = parseFloat(finalPrice).toFixed(2);
            $('#final_price').val(formattedPrice);
        }
        
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

function showInconsistencyForm(jobId) {
    $.ajax({
        url: '<?= base_url("cleaner/jobs-in-progress/inconsistency-form/") ?>' + jobId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#inconsistencyModalBody').html(response.form_html);
                $('#inconsistencyModal').modal('show');
            } else {
                alert('Error: ' + response.message);
            }
        },
        error: function() {
            alert('An error occurred while loading the form.');
        }
    });
}
</script>
