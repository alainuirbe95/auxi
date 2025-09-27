<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-gavel text-warning"></i>
                        Dispute Queue
                    </h3>
                    <div class="card-tools">
                        <span class="badge badge-warning"><?= count($disputes) ?> Pending</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($disputes)): ?>
                        <div class="alert alert-success m-3">
                            <i class="fas fa-check-circle"></i>
                            <strong>All Clear!</strong> No pending disputes.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="5%">ID</th>
                                        <th width="25%">Job</th>
                                        <th width="15%">Host</th>
                                        <th width="15%">Cleaner</th>
                                        <th width="10%">Amount</th>
                                        <th width="10%">Disputed</th>
                                        <th width="10%">Age</th>
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($disputes as $dispute): ?>
                                        <tr class="dispute-row" data-dispute-id="<?= $dispute->id ?>">
                                            <td>
                                                <strong>#<?= $dispute->id ?></strong>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?= htmlspecialchars($dispute->title) ?></strong><br>
                                                    <small class="text-muted"><?= date('M j', strtotime($dispute->completed_at)) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <?= htmlspecialchars($dispute->host_name) ?><br>
                                                    <small class="text-muted"><?= htmlspecialchars($dispute->host_email) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <?= htmlspecialchars($dispute->cleaner_name) ?><br>
                                                    <small class="text-muted"><?= htmlspecialchars($dispute->cleaner_email) ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>$<?= number_format($dispute->final_price ?: $dispute->accepted_price, 2) ?></strong>
                                            </td>
                                            <td>
                                                <?= date('M j, g:i A', strtotime($dispute->disputed_at)) ?>
                                            </td>
                                            <td>
                                                <?php 
                                                $disputeDate = new DateTime($dispute->disputed_at);
                                                $now = new DateTime();
                                                $age = $now->diff($disputeDate);
                                                $hours = ($age->days * 24) + $age->h;
                                                if ($hours < 24) {
                                                    echo '<span class="badge badge-danger">' . $hours . 'h ago</span>';
                                                } elseif ($hours < 48) {
                                                    echo '<span class="badge badge-warning">' . $age->days . 'd ago</span>';
                                                } else {
                                                    echo '<span class="badge badge-secondary">' . $age->days . 'd ago</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-primary resolve-btn" data-dispute-id="<?= $dispute->id ?>">
                                                    <i class="fas fa-gavel"></i> Resolve
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Hidden resolution row -->
                                        <tr class="resolution-row d-none" data-dispute-id="<?= $dispute->id ?>">
                                            <td colspan="8">
                                                <div class="p-3 bg-light border-top">
                                                    <div class="row">
                                                        <!-- Dispute Details -->
                                                        <div class="col-md-6">
                                                            <h6 class="text-primary mb-3">
                                                                <i class="fas fa-info-circle"></i> Dispute Details
                                                            </h6>
                                                            <div class="mb-3">
                                                                <strong>Reason:</strong>
                                                                <div class="dispute-reason-display p-2 mt-1 border rounded bg-light">
                                                                    <small class="text-dark"><?= nl2br(htmlspecialchars($dispute->dispute_reason)) ?></small>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <small><strong>Job:</strong> <?= htmlspecialchars($dispute->title) ?></small><br>
                                                                    <small><strong>Completed:</strong> <?= date('M j, Y g:i A', strtotime($dispute->completed_at)) ?></small>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small><strong>Amount:</strong> $<?= number_format($dispute->final_price ?: $dispute->accepted_price, 2) ?></small><br>
                                                                    <small><strong>Disputed:</strong> <?= date('M j, Y g:i A', strtotime($dispute->disputed_at)) ?></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Resolution Form -->
                                                        <div class="col-md-6">
                                                            <h6 class="text-success mb-3">
                                                                <i class="fas fa-balance-scale"></i> Resolution
                                                            </h6>
                                                            <form class="resolution-form" data-dispute-id="<?= $dispute->id ?>">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label">Cleaner Payout Percentage:</label>
                                                                    <input type="range" 
                                                                           class="form-control-range percentage-slider" 
                                                                           min="0" 
                                                                           max="100" 
                                                                           value="75"
                                                                           data-original-price="<?= $dispute->final_price ?: $dispute->accepted_price ?>">
                                                                    <div class="d-flex justify-content-between text-muted">
                                                                        <small>0%</small>
                                                                        <small>100%</small>
                                                                    </div>
                                                                    <div class="text-center mt-2">
                                                                        <span class="badge badge-info percentage-display">75%</span>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="payout-preview mb-3 p-2 bg-white border rounded">
                                                                    <div class="row text-center">
                                                                        <div class="col-6">
                                                                            <div class="text-success">
                                                                                <strong>Cleaner Gets</strong><br>
                                                                                <span class="cleaner-amount">$<?= number_format(($dispute->final_price ?: $dispute->accepted_price) * 0.75, 2) ?></span>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <div class="text-danger">
                                                                                <strong>Host Refund</strong><br>
                                                                                <span class="host-refund-amount">$<?= number_format(($dispute->final_price ?: $dispute->accepted_price) * 0.25, 2) ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label">Resolution Notes:</label>
                                                                    <textarea class="form-control form-control-sm" 
                                                                              rows="2" 
                                                                              maxlength="500"
                                                                              placeholder="Brief explanation of resolution..."></textarea>
                                                                </div>
                                                                
                                                                <div class="text-right">
                                                                    <button type="button" class="btn btn-sm btn-secondary cancel-resolve-btn mr-2">
                                                                        Cancel
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-success submit-resolve-btn">
                                                                        <i class="fas fa-check"></i> Resolve & Pay
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Quick Guidelines -->
                        <div class="card-footer bg-light">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-success"><strong>Minor Issues (75-90%):</strong> Quality, timing</small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-warning"><strong>Moderate (50-75%):</strong> Equipment, supplies</small>
                                </div>
                                <div class="col-md-4">
                                    <small class="text-danger"><strong>Major (0-50%):</strong> Damage, safety</small>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle resolve button click
    $('.resolve-btn').click(function() {
        var disputeId = $(this).data('dispute-id');
        
        // Hide any other open resolution rows
        $('.resolution-row').addClass('d-none');
        
        // Show this dispute's resolution row
        $('.resolution-row[data-dispute-id="' + disputeId + '"]').removeClass('d-none');
        
        // Scroll to the resolution form
        $('html, body').animate({
            scrollTop: $('.resolution-row[data-dispute-id="' + disputeId + '"]').offset().top - 100
        }, 300);
    });
    
    // Handle cancel button
    $('.cancel-resolve-btn').click(function() {
        $(this).closest('.resolution-row').addClass('d-none');
    });
    
    // Handle percentage slider changes
    $('.percentage-slider').on('input', function() {
        var percentage = $(this).val();
        var originalPrice = parseFloat($(this).data('original-price'));
        var cleanerAmount = (originalPrice * percentage / 100).toFixed(2);
        var hostRefund = (originalPrice - cleanerAmount).toFixed(2);
        
        var form = $(this).closest('.resolution-form');
        form.find('.percentage-display').text(percentage + '%');
        form.find('.cleaner-amount').text('$' + cleanerAmount);
        form.find('.host-refund-amount').text('$' + hostRefund);
    });
    
    // Handle resolution submission
    $('.submit-resolve-btn').click(function() {
        var btn = $(this);
        var form = btn.closest('.resolution-form');
        var disputeId = form.data('dispute-id');
        var percentage = form.find('.percentage-slider').val();
        var notes = form.find('textarea').val();
        
        if (confirm('Resolve this dispute with ' + percentage + '% to cleaner? This cannot be undone.')) {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            
            $.ajax({
                url: '<?= base_url("disputes/resolve-dispute") ?>',
                type: 'POST',
                data: {
                    dispute_id: disputeId,
                    cleaner_percentage: percentage,
                    resolution_notes: notes
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Dispute resolved successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                        btn.prop('disabled', false).html('<i class="fas fa-check"></i> Resolve & Pay');
                    }
                },
                error: function() {
                    alert('An error occurred while resolving the dispute.');
                    btn.prop('disabled', false).html('<i class="fas fa-check"></i> Resolve & Pay');
                }
            });
        }
    });
});
</script>