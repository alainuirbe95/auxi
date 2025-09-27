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

/* Completed Jobs Styles */
.completed-jobs-container {
    padding: 2rem 0;
}

.page-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    text-align: center;
}

.page-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.page-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

/* Table Styles */
.jobs-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
}

.table-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.enhanced-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
    font-size: 0.95rem;
}

.enhanced-table thead {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.enhanced-table thead th {
    padding: 1rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border: none;
    white-space: nowrap;
}

.enhanced-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.enhanced-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.enhanced-table tbody td {
    padding: 1.25rem 1.5rem;
    vertical-align: middle;
    border: none;
}

/* Status Badge Styles */
.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-completed {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.status-disputed {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.status-closed {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

/* Job Title Styling */
.job-title {
    font-weight: 600;
    color: #2c3e50;
    font-size: 1.1rem;
    margin-bottom: 0.25rem;
}

.job-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-address {
    color: #495057;
    font-size: 0.85rem;
    font-weight: 500;
}

/* Price Styling */
.price-amount {
    font-size: 1.2rem;
    font-weight: 700;
    color: #28a745;
}

.price-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Date Styling */
.date-info {
    text-align: center;
}

.date-label {
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.date-value {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

/* Host Info Styling */
.host-info {
    text-align: center;
}

.host-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.host-username {
    color: #6c757d;
    font-size: 0.8rem;
    font-style: italic;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-state p {
    font-size: 1.1rem;
    margin-bottom: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .enhanced-table {
        font-size: 0.8rem;
    }
    
    .enhanced-table thead th,
    .enhanced-table tbody td {
        padding: 0.75rem 0.5rem;
    }
    
    .job-title {
        font-size: 1rem;
    }
    
    .price-amount {
        font-size: 1rem;
    }
}

/* Scrollable table container */
.scrollable-table {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}

.scrollable-table::-webkit-scrollbar {
    height: 8px;
}

.scrollable-table::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.scrollable-table::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.scrollable-table::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Expandable Details Styling */
.details-row {
    background: #f8f9fa;
}

.job-details-content {
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    margin: 1rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.details-section {
    border-left: 4px solid #667eea;
    padding-left: 1rem;
}

/* Price Adjustment Styling */
.price-adjustment-item {
    background: #fff8e1;
    border-left: 4px solid #ff9800 !important;
}

.price-adjustment-item strong {
    color: #e65100;
}

/* Dispute Information Styling */
.dispute-reason-box {
    background: #ffebee;
    border-color: #f44336 !important;
    max-height: 150px;
    overflow-y: auto;
}

.resolution-notes-box {
    max-height: 150px;
    overflow-y: auto;
}

.price-info {
    text-align: center;
}

.dispute-payment-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.5rem;
    margin-top: 0.25rem;
}

.dispute-payment-info small {
    font-size: 0.75rem;
    line-height: 1.2;
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.badge-warning {
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, #4caf50 0%, #388e3c 100%);
    color: white;
}

/* Button Styling */
.btn-outline-info {
    border-color: #17a2b8;
    color: #17a2b8;
    transition: all 0.3s ease;
}

.btn-outline-info:hover {
    background: #17a2b8;
    border-color: #17a2b8;
    color: white;
    transform: translateY(-1px);
}
</style>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h2>
                    <i class="fas fa-check-circle me-2"></i>
                    Completed Jobs
                </h2>
                <p>View all your successfully completed cleaning jobs</p>
            </div>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="row">
        <div class="col-12">
            <div class="jobs-table-container">
                <div class="table-header">
                    <h5>
                        <i class="fas fa-clipboard-check me-2"></i>
                        Your Completed Jobs (<?php echo count($completed_jobs); ?>)
                    </h5>
                </div>
                
                <?php if (!empty($completed_jobs)): ?>
                <div class="scrollable-table">
                    <table class="enhanced-table">
                        <thead>
                            <tr>
                                <th>Job Details</th>
                                <th>Host</th>
                                <th>Price</th>
                                <th>Completed Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($completed_jobs as $job): ?>
                            <tr>
                                <td>
                                    <div class="job-title"><?php echo htmlspecialchars($job->title); ?></div>
                                    <div class="job-description"><?php echo htmlspecialchars($job->description); ?></div>
                                    <div class="job-address">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo htmlspecialchars($job->address . ', ' . $job->city . ', ' . $job->state); ?>
                                    </div>
                                </td>
                                <td class="host-info">
                                    <div class="host-name">
                                        <?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?>
                                    </div>
                                    <div class="host-username">
                                        @<?php echo htmlspecialchars($job->host_username); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <!-- Show original suggested price -->
                                        <div class="price-amount">
                                            $<?php echo number_format($job->suggested_price, 2); ?>
                                        </div>
                                        <div class="price-label">Original Price</div>
                                        
                                        <!-- Show final price if different -->
                                        <?php if ($job->final_price && $job->final_price != $job->suggested_price): ?>
                                            <small class="text-success d-block">
                                                Final: $<?php echo number_format($job->final_price, 2); ?>
                                            </small>
                                        <?php elseif ($job->accepted_price && $job->accepted_price != $job->suggested_price): ?>
                                            <small class="text-success d-block">
                                                Agreed: $<?php echo number_format($job->accepted_price, 2); ?>
                                            </small>
                                        <?php endif; ?>
                                        
                                        <?php if ($job->dispute_info && $job->dispute_info['dispute_resolution']): ?>
                                            <!-- Dispute Resolution Payment Info -->
                                            <?php 
                                            $original_amount = $job->final_price ?: $job->accepted_price;
                                            $cleaner_amount = $job->dispute_info['payment_amount'] ?: 0;
                                            $host_refund = $original_amount - $cleaner_amount;
                                            ?>
                                            <div class="dispute-payment-info mt-1">
                                                <small class="text-warning d-block">
                                                    <strong>Dispute Resolution:</strong>
                                                </small>
                                                <small class="text-success d-block">
                                                    Received: $<?php echo number_format($cleaner_amount, 2); ?>
                                                </small>
                                                <small class="text-muted d-block">
                                                    Host Refund: $<?php echo number_format($host_refund, 2); ?>
                                                </small>
                                            </div>
                                        <?php elseif ($job->status === 'closed' && $job->payment_amount): ?>
                                            <!-- Regular Payment Info -->
                                            <small class="text-info d-block">
                                                Paid: $<?php echo number_format($job->payment_amount, 2); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="date-info">
                                    <div class="date-label">Completed</div>
                                    <div class="date-value">
                                        <?php echo date('M j, Y', strtotime($job->completed_at)); ?>
                                    </div>
                                    <div class="date-value" style="font-size: 0.8rem; color: #6c757d;">
                                        <?php echo date('g:i A', strtotime($job->completed_at)); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $status_class = 'status-completed';
                                    $status_text = 'Completed';
                                    $dispute_info = '';
                                    
                                    // Check job status and dispute resolution
                                    if ($job->status === 'disputed') {
                                        $status_class = 'status-disputed';
                                        $status_text = 'Disputed';
                                    } elseif ($job->status === 'closed') {
                                        if ($job->dispute_resolution) {
                                            $status_class = 'status-disputed';
                                            $status_text = 'Dispute Resolved';
                                            $dispute_info = '<br><small class="text-muted">Resolved: ' . date('M j, Y', strtotime($job->dispute_resolved_at)) . '</small>';
                                        } else {
                                            $status_class = 'status-closed';
                                            $status_text = 'Closed';
                                        }
                                    } elseif ($job->status === 'completed') {
                                        $status_class = 'status-completed';
                                        $status_text = 'Completed';
                                    }
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                    <?php echo $dispute_info; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" onclick="toggleJobDetails(<?php echo $job->id; ?>)">
                                        <i class="fas fa-info-circle"></i> Details
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Expandable Details Row -->
                            <tr id="details-<?php echo $job->id; ?>" class="details-row" style="display: none;">
                                <td colspan="6">
                                    <div class="job-details-content">
                                        
                                        <!-- Price Adjustment Requests -->
                                        <?php if (!empty($job->price_adjustments)): ?>
                                        <div class="details-section mb-3">
                                            <h6 class="text-warning mb-2">
                                                <i class="fas fa-dollar-sign me-2"></i>
                                                Price Adjustment Requests
                                            </h6>
                                            <?php foreach ($job->price_adjustments as $adjustment): ?>
                                                <div class="price-adjustment-item border rounded p-3 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <strong>Requested Amount:</strong> $<?php echo number_format($adjustment->requested_amount, 2); ?><br>
                                                            <strong>Reason:</strong> <?php echo htmlspecialchars($adjustment->price_reason); ?><br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-clock me-1"></i>
                                                                Requested: <?php echo date('M j, Y g:i A', strtotime($adjustment->created_at)); ?>
                                                            </small>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <span class="badge badge-warning">
                                                                <?php echo ucfirst($adjustment->status); ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <?php endif; ?>

                                        <!-- Dispute Information -->
                                        <?php if (!empty($job->dispute_info) || $job->status === 'disputed' || ($job->status === 'closed' && $job->dispute_resolution)): ?>
                                        <div class="details-section">
                                            <h6 class="text-danger mb-2">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Dispute Information
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="text-primary mb-2">Dispute Details</h6>
                                                    <?php 
                                                    // Use dispute_info array if available, otherwise use job properties directly
                                                    $disputed_at = !empty($job->dispute_info) ? $job->dispute_info['disputed_at'] : $job->disputed_at;
                                                    $dispute_reason = !empty($job->dispute_info) ? $job->dispute_info['dispute_reason'] : $job->dispute_reason;
                                                    ?>
                                                    <p><strong>Disputed On:</strong> <?php echo $disputed_at ? date('M j, Y g:i A', strtotime($disputed_at)) : 'Not available'; ?></p>
                                                    <p><strong>Reason:</strong></p>
                                                    <div class="dispute-reason-box p-3 bg-light border rounded mb-3">
                                                        <small><?php echo $dispute_reason ? nl2br(htmlspecialchars($dispute_reason)) : 'No reason provided'; ?></small>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <?php 
                                                    // Check if dispute is resolved
                                                    $is_resolved = !empty($job->dispute_info) ? $job->dispute_info['dispute_resolution'] : $job->dispute_resolution;
                                                    $resolved_at = !empty($job->dispute_info) ? $job->dispute_info['dispute_resolved_at'] : $job->dispute_resolved_at;
                                                    $resolution_notes = !empty($job->dispute_info) ? $job->dispute_info['dispute_resolution_notes'] : $job->dispute_resolution_notes;
                                                    ?>
                                                    <?php if ($is_resolved): ?>
                                                        <h6 class="text-success mb-2">Resolution</h6>
                                                        <p><strong>Resolved On:</strong> <?php echo $resolved_at ? date('M j, Y g:i A', strtotime($resolved_at)) : 'Not available'; ?></p>
                                                        
                                                        <?php 
                                                        // Get the resolution type
                                                        $resolution_type = !empty($job->dispute_info) ? $job->dispute_info['dispute_resolution'] : $job->dispute_resolution;
                                                        $resolution_badge_class = 'badge-success';
                                                        $resolution_text = 'Resolved';
                                                        
                                                        if ($resolution_type === 'resolved_in_favor_cleaner') {
                                                            $resolution_badge_class = 'badge-success';
                                                            $resolution_text = 'Resolved in Your Favor';
                                                        } elseif ($resolution_type === 'resolved_in_favor_host') {
                                                            $resolution_badge_class = 'badge-warning';
                                                            $resolution_text = 'Resolved in Host\'s Favor';
                                                        } elseif ($resolution_type === 'compromise') {
                                                            $resolution_badge_class = 'badge-info';
                                                            $resolution_text = 'Compromise Resolution';
                                                        }
                                                        ?>
                                                        
                                                        <p><strong>Status:</strong> <span class="badge <?php echo $resolution_badge_class; ?>"><?php echo $resolution_text; ?></span></p>
                                                        <?php if ($resolution_notes): ?>
                                                            <p><strong>Resolution Notes:</strong></p>
                                                            <div class="resolution-notes-box p-3 bg-success bg-opacity-10 border border-success rounded">
                                                                <small><?php echo nl2br(htmlspecialchars($resolution_notes)); ?></small>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <h6 class="text-warning mb-2">Under Review</h6>
                                                        <p><strong>Status:</strong> <span class="badge badge-warning">Under Review</span></p>
                                                        <p class="text-muted">A moderator is reviewing the dispute and will provide a resolution soon.</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>

                                        <?php if (empty($job->price_adjustments) && empty($job->dispute_info) && $job->status !== 'disputed' && !($job->status === 'closed' && $job->dispute_resolution)): ?>
                                        <div class="text-center text-muted py-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            No additional details available for this job.
                                        </div>
                                        <?php endif; ?>
                                        
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-clipboard-check"></i>
                    <h4>No Completed Jobs Yet</h4>
                    <p>You haven't completed any jobs yet. Start by browsing available jobs and completing your first assignment!</p>
                    <a href="<?php echo base_url('cleaner'); ?>" class="btn btn-primary mt-3">
                        <i class="fas fa-search me-2"></i>
                        Browse Available Jobs
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Add any additional JavaScript functionality here if needed
    console.log('Completed Jobs page loaded');
});

// Toggle job details
function toggleJobDetails(jobId) {
    const detailsRow = document.getElementById('details-' + jobId);
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    
    if (detailsRow.style.display === 'none') {
        detailsRow.style.display = 'table-row';
        icon.className = 'fas fa-chevron-up';
        button.innerHTML = '<i class="fas fa-chevron-up"></i> Hide';
    } else {
        detailsRow.style.display = 'none';
        icon.className = 'fas fa-info-circle';
        button.innerHTML = '<i class="fas fa-info-circle"></i> Details';
    }
}
</script>
