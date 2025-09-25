<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-dollar-sign text-warning"></i>
                        Price Adjustment Requests
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
                            No pending price adjustment requests.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Cleaner</th>
                                        <th>Original Price</th>
                                        <th>Proposed Price</th>
                                        <th>Difference</th>
                                        <th>Reason</th>
                                        <th>Expires</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($counter_offers as $offer): ?>
                                        <tr>
                                            <td>
                                                <strong><?= htmlspecialchars($offer->title) ?></strong>
                                                <br>
                                                <small class="text-muted"><?= date('M j, Y g:i A', strtotime($offer->scheduled_date . ' ' . $offer->scheduled_time)) ?></small>
                                            </td>
                                            <td>
                                                <?= htmlspecialchars($offer->cleaner_first_name . ' ' . $offer->cleaner_last_name) ?>
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
                                                <?php if ($offer->price_reason): ?>
                                                    <small><?= htmlspecialchars(substr($offer->price_reason, 0, 100)) ?><?= strlen($offer->price_reason) > 100 ? '...' : '' ?></small>
                                                <?php else: ?>
                                                    <small class="text-muted">No reason provided</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    <?= date('M j, Y g:i A', strtotime($offer->expires_at)) ?>
                                                    <br>
                                                    <?php 
                                                    $time_left = strtotime($offer->expires_at) - time();
                                                    if ($time_left > 0) {
                                                        $hours = floor($time_left / 3600);
                                                        $minutes = floor(($time_left % 3600) / 60);
                                                        $time_text = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
                                                        echo '<span class="badge badge-info">' . $time_text . ' left</span>';
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
                                                            onclick="approveOffer(<?= $offer->counter_offer_id ?>)">
                                                        <i class="fas fa-check"></i> Approve
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm" 
                                                            onclick="rejectOffer(<?= $offer->counter_offer_id ?>)">
                                                        <i class="fas fa-times"></i> Reject
                                                    </button>
                                                    <button type="button" 
                                                            class="btn btn-warning btn-sm" 
                                                            onclick="escalateOffer(<?= $offer->counter_offer_id ?>)">
                                                        <i class="fas fa-gavel"></i> Escalate
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

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel">Respond to Price Adjustment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="responseForm">
                    <input type="hidden" id="offerId" name="counter_offer_id">
                    <input type="hidden" id="actionType" name="action_type">
                    
                    <div class="form-group">
                        <label for="responseText">Your Response (Optional)</label>
                        <textarea class="form-control" id="responseText" name="response" rows="3" placeholder="Add any comments about your decision..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <strong>Note:</strong> If you escalate this request, it will be reviewed by a moderator who will make the final decision.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmResponse">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
function approveOffer(offerId) {
    $('#offerId').val(offerId);
    $('#actionType').val('approve');
    $('#responseModalLabel').text('Approve Price Adjustment');
    $('#confirmResponse').removeClass('btn-danger btn-warning').addClass('btn-success').text('Approve');
    $('#responseModal').modal('show');
}

function rejectOffer(offerId) {
    $('#offerId').val(offerId);
    $('#actionType').val('reject');
    $('#responseModalLabel').text('Reject Price Adjustment');
    $('#confirmResponse').removeClass('btn-success btn-warning').addClass('btn-danger').text('Reject');
    $('#responseModal').modal('show');
}

function escalateOffer(offerId) {
    $('#offerId').val(offerId);
    $('#actionType').val('escalate');
    $('#responseModalLabel').text('Escalate to Moderator');
    $('#confirmResponse').removeClass('btn-success btn-danger').addClass('btn-warning').text('Escalate');
    $('#responseModal').modal('show');
}

$(document).ready(function() {
    $('#confirmResponse').on('click', function() {
        var offerId = $('#offerId').val();
        var actionType = $('#actionType').val();
        var response = $('#responseText').val();
        
        var url = '';
        switch(actionType) {
            case 'approve':
                url = '<?= base_url("counter-offers/approve") ?>';
                break;
            case 'reject':
                url = '<?= base_url("counter-offers/reject") ?>';
                break;
            case 'escalate':
                url = '<?= base_url("counter-offers/escalate") ?>';
                break;
        }
        
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                counter_offer_id: offerId,
                response: response
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#responseModal').modal('hide');
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
