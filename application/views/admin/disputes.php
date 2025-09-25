<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-gavel text-warning"></i>
                        Disputed Jobs
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
                            No disputed jobs pending resolution.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Cleaner</th>
                                        <th>Host</th>
                                        <th>Final Price</th>
                                        <th>Disputed At</th>
                                        <th>Dispute Reason</th>
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
                                                <?= htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name) ?>
                                            </td>
                                            <td>
                                                <strong>$<?= number_format($job->final_price ?: $job->accepted_price, 2) ?></strong>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M j, Y g:i A', strtotime($job->disputed_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <?php if ($job->dispute_reason): ?>
                                                    <small><?= htmlspecialchars(substr($job->dispute_reason, 0, 100)) ?><?= strlen($job->dispute_reason) > 100 ? '...' : '' ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">No reason provided</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm" 
                                                        onclick="resolveDispute(<?= $job->id ?>, '<?= htmlspecialchars($job->title) ?>', '<?= $job->final_price ?: $job->accepted_price ?>')">
                                                    <i class="fas fa-gavel"></i> Resolve
                                                </button>
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

<!-- Resolution Modal -->
<div class="modal fade" id="resolutionModal" tabindex="-1" role="dialog" aria-labelledby="resolutionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resolutionModalLabel">Resolve Dispute</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resolutionForm">
                    <input type="hidden" id="jobId" name="job_id">
                    
                    <div class="form-group">
                        <label for="jobTitle">Job Title</label>
                        <input type="text" class="form-control" id="jobTitle" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="originalAmount">Original Amount</label>
                        <input type="text" class="form-control" id="originalAmount" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="resolution">Resolution</label>
                        <select class="form-control" id="resolution" name="resolution" required>
                            <option value="">Select resolution</option>
                            <option value="resolved_in_favor_cleaner">Resolve in Favor of Cleaner (Full Payment)</option>
                            <option value="resolved_in_favor_host">Resolve in Favor of Host (No Payment)</option>
                            <option value="compromise">Compromise (Partial Payment)</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="compromiseAmountGroup" style="display: none;">
                        <label for="final_amount">Final Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" 
                                   class="form-control" 
                                   id="final_amount" 
                                   name="final_amount" 
                                   step="0.01" 
                                   min="0">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Resolution Notes</label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Add notes about your decision..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Resolution Options:</strong>
                        <ul class="mb-0">
                            <li><strong>In Favor of Cleaner:</strong> Full payment is released to the cleaner</li>
                            <li><strong>In Favor of Host:</strong> No payment is released (host gets refund)</li>
                            <li><strong>Compromise:</strong> Set a custom amount that both parties can accept</li>
                        </ul>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmResolution">Resolve Dispute</button>
            </div>
        </div>
    </div>
</div>

<script>
function resolveDispute(jobId, jobTitle, originalAmount) {
    $('#jobId').val(jobId);
    $('#jobTitle').val(jobTitle);
    $('#originalAmount').val('$' + parseFloat(originalAmount).toFixed(2));
    $('#resolutionModal').modal('show');
}

$(document).ready(function() {
    // Show/hide compromise amount field based on resolution
    $('#resolution').on('change', function() {
        if ($(this).val() === 'compromise') {
            $('#compromiseAmountGroup').slideDown();
            $('#final_amount').prop('required', true);
        } else {
            $('#compromiseAmountGroup').slideUp();
            $('#final_amount').prop('required', false);
        }
    });
    
    $('#confirmResolution').on('click', function() {
        var jobId = $('#jobId').val();
        var resolution = $('#resolution').val();
        var finalAmount = $('#final_amount').val();
        var notes = $('#notes').val();
        
        // Determine final amount based on resolution
        if (resolution === 'resolved_in_favor_cleaner') {
            finalAmount = $('#originalAmount').val().replace('$', '');
        } else if (resolution === 'resolved_in_favor_host') {
            finalAmount = '0';
        }
        
        if (!finalAmount && resolution !== 'resolved_in_favor_host') {
            alert('Please set a final amount.');
            return;
        }
        
        $.ajax({
            url: '<?= base_url("disputes/resolve") ?>',
            type: 'POST',
            data: {
                job_id: jobId,
                resolution: resolution,
                final_amount: finalAmount,
                notes: notes
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#resolutionModal').modal('hide');
                    location.reload();
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
</script>
