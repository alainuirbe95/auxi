<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-gavel text-warning"></i>
                        Escalated Price Adjustments
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($counter_offers)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            No escalated price adjustment requests.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Cleaner</th>
                                        <th>Host</th>
                                        <th>Original Price</th>
                                        <th>Proposed Price</th>
                                        <th>Difference</th>
                                        <th>Reason</th>
                                        <th>Escalated</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($counter_offers as $offer): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($offer->job_title) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= date('M j, Y g:i A', strtotime($offer->scheduled_date . ' ' . $offer->scheduled_time)) ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($offer->cleaner_first_name . ' ' . $offer->cleaner_last_name) ?>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($offer->host_first_name . ' ' . $offer->host_last_name) ?>
                                            </td>
                                            <td>
                                                <strong>$<?= number_format($offer->original_price, 2) ?></strong>
                                            </td>
                                            <td>
                                                <strong class="text-primary">$<?= number_format($offer->proposed_price, 2) ?></strong>
                                            </td>
                                            <td>
                                                <?php 
                                                $difference = $offer->proposed_price - $offer->original_price;
                                                $difference_text = $difference > 0 ? "+$" . number_format($difference, 2) : "-$" . number_format(abs($difference), 2);
                                                $badge_class = $difference > 0 ? 'badge-danger' : 'badge-success';
                                                ?>
                                                <span class="badge <?= $badge_class ?>"><?= $difference_text ?></span>
                                            </td>
                                            <td>
                                                <?php if ($offer->reason): ?>
                                                    <small><?= htmlspecialchars(substr($offer->reason, 0, 100)) ?><?= strlen($offer->reason) > 100 ? '...' : '' ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">No reason provided</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M j, Y g:i A', strtotime($offer->escalated_at)) ?>
                                                </small>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm" 
                                                        onclick="resolveOffer(<?= $offer->id ?>, '<?= $offer->original_price ?>', '<?= $offer->proposed_price ?>')">
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
                <h5 class="modal-title" id="resolutionModalLabel">Resolve Price Adjustment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="resolutionForm">
                    <input type="hidden" id="offerId" name="counter_offer_id">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Original Price</label>
                                <input type="text" class="form-control" id="originalPrice" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Proposed Price</label>
                                <input type="text" class="form-control" id="proposedPrice" readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="decision">Decision</label>
                        <select class="form-control" id="decision" name="decision" required>
                            <option value="">Select decision</option>
                            <option value="approved">Approve Proposed Price</option>
                            <option value="rejected">Reject - Keep Original Price</option>
                            <option value="compromise">Compromise - Set Custom Price</option>
                        </select>
                    </div>
                    
                    <div class="form-group" id="customPriceGroup" style="display: none;">
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
                                   min="0">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Moderator Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add notes about your decision..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmResolution">Resolve</button>
            </div>
        </div>
    </div>
</div>

<script>
function resolveOffer(offerId, originalPrice, proposedPrice) {
    $('#offerId').val(offerId);
    $('#originalPrice').val('$' + parseFloat(originalPrice).toFixed(2));
    $('#proposedPrice').val('$' + parseFloat(proposedPrice).toFixed(2));
    $('#resolutionModal').modal('show');
}

$(document).ready(function() {
    // Show/hide custom price field based on decision
    $('#decision').on('change', function() {
        if ($(this).val() === 'compromise') {
            $('#customPriceGroup').slideDown();
            $('#final_price').prop('required', true);
        } else {
            $('#customPriceGroup').slideUp();
            $('#final_price').prop('required', false);
        }
    });
    
    $('#confirmResolution').on('click', function() {
        var offerId = $('#offerId').val();
        var decision = $('#decision').val();
        var finalPrice = $('#final_price').val();
        var notes = $('#notes').val();
        
        // Determine final price based on decision
        if (decision === 'approved') {
            finalPrice = $('#proposedPrice').val().replace('$', '');
        } else if (decision === 'rejected') {
            finalPrice = $('#originalPrice').val().replace('$', '');
        }
        
        if (!finalPrice) {
            alert('Please set a final price.');
            return;
        }
        
        $.ajax({
            url: '<?= base_url("counter-offers/resolve") ?>',
            type: 'POST',
            data: {
                counter_offer_id: offerId,
                decision: decision,
                final_price: finalPrice,
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
