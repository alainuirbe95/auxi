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

/* Statistics Cards */
.stats-row {
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
    color: #333;
}

.stat-label {
    font-size: 0.9rem;
    color: #666;
    font-weight: 500;
}

.stat-card.total .stat-number { color: #667eea; }
.stat-card.earnings .stat-number { color: #28a745; }
.stat-card.disputed .stat-number { color: #ffc107; }


/* Table Styles */
.jobs-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h5 {
    margin: 0;
    color: #333;
    font-weight: 600;
}

.sort-controls {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.table-responsive {
    max-height: 600px;
    overflow-y: auto;
    overflow-x: hidden;
}

.table-responsive::-webkit-scrollbar {
    width: 6px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #28a745;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #218838;
}

.table {
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem;
    position: sticky;
    top: 0;
    z-index: 10;
}

.table td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f4;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

/* Status Badges */
.status-badge {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-completed {
    background: #d4edda;
    color: #155724;
}

.status-disputed {
    background: #fff3cd;
    color: #856404;
}

.status-closed {
    background: #d1ecf1;
    color: #0c5460;
}

/* Action Buttons */
.btn-details {
    background: #6c757d;
    color: white;
    border: none;
    padding: 0.4rem 0.8rem;
    border-radius: 8px;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.btn-details:hover {
    background: #5a6268;
    color: white;
    transform: translateY(-1px);
}

/* Job Details Row */
.job-details-row {
    background: #f8f9fa;
}

.job-details-content {
    padding: 1.5rem;
    border-top: 1px solid #dee2e6;
}

.job-details-content h6 {
    color: #495057;
    font-weight: 600;
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 0.5rem;
    margin-bottom: 1rem;
}

.job-details-content .table-sm td {
    padding: 0.5rem 0.75rem;
    border: none;
}

.job-details-content .table-sm td:first-child {
    width: 30%;
    font-weight: 600;
    color: #6c757d;
}

.price-adjustments .adjustment-item {
    border: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.price-adjustments .adjustment-item:hover {
    background: #e9ecef !important;
    transform: translateX(5px);
}

/* No Data State */
.no-data {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.no-data i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-data h3 {
    color: #999;
    margin-bottom: 1rem;
}

.no-data p {
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        padding: 1.5rem;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .sort-controls {
        flex-direction: column;
        gap: 0.5rem;
        width: 100%;
    }
    
    .table-responsive {
        max-height: 500px;
    }
    
    .table th, .table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.9rem;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0 10px;
    }
    
    .page-header {
        padding: 1rem;
        margin-bottom: 1rem;
    }
    
    
    .table th, .table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.8rem;
    }
    
    .status-badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
}
</style>

<div class="completed-jobs-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>
            <i class="fas fa-check-circle me-2"></i>
            Completed Jobs
        </h2>
        <p class="mb-0">View and manage all your completed cleaning jobs</p>
    </div>

    <!-- Statistics Row -->
    <div class="row stats-row">
        <div class="col-lg-4 col-md-4 col-sm-6 mb-3">
            <div class="stat-card total">
                <div class="stat-number"><?php echo $total_jobs; ?></div>
                <div class="stat-label">Total Completed</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 mb-3">
            <div class="stat-card earnings">
                <div class="stat-number">$<?php echo number_format($potential_earnings, 2); ?></div>
                <div class="stat-label">Potential Earnings</div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 mb-3">
            <div class="stat-card disputed">
                <div class="stat-number"><?php echo $disputed_count; ?></div>
                <div class="stat-label">Disputed Jobs</div>
            </div>
        </div>
    </div>


    <!-- Jobs Table -->
    <?php if (!empty($completed_jobs)): ?>
        <div class="jobs-table-container">
            <div class="table-header">
                <h5>
                    <i class="fas fa-list me-2"></i>
                    Completed Jobs
                </h5>
                <div class="sort-controls">
                    <small class="text-muted">
                        <?php echo count($completed_jobs); ?> jobs completed
                    </small>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Host</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Completed</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($completed_jobs as $job): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($job->title); ?></div>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars(substr($job->description, 0, 50)) . (strlen($job->description) > 50 ? '...' : ''); ?>
                                    </small>
                                </td>
                                <td>
                                    <div><?php echo htmlspecialchars($job->host_name); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($job->host_email); ?></small>
                                </td>
                                <td>
                                    <div class="price-info">
                                        <span class="fw-bold text-success">
                                            $<?php echo number_format($job->suggested_price, 2); ?>
                                        </span>
                                        <?php if ($job->dispute_info && $job->dispute_info['payment_amount']): ?>
                                            <br><small class="text-info">
                                                Final: $<?php echo number_format($job->dispute_info['payment_amount'], 2); ?>
                                            </small>
                                        <?php endif; ?>
                                        <?php if ($job->dispute_info): ?>
                                            <br><small class="text-warning">
                                                <i class="fas fa-exclamation-triangle"></i> Disputed
                                            </small>
                                        <?php endif; ?>
                                        <?php if ($job->status === 'closed' && $job->dispute_resolution): ?>
                                            <br><small class="text-muted">
                                                <i class="fas fa-check-circle"></i> Resolved
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $job->status; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $job->status)); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($job->completed_at): ?>
                                        <div><?php echo date('M j, Y', strtotime($job->completed_at)); ?></div>
                                        <small class="text-muted"><?php echo date('g:i A', strtotime($job->completed_at)); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Not set</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-details toggle-details" 
                                            data-job-id="<?php echo $job->id; ?>"
                                            onclick="toggleJobDetails(<?php echo $job->id; ?>)">
                                        <i class="fas fa-chevron-down me-1"></i>
                                        Details
                                    </button>
                                </td>
                            </tr>
                            <!-- Expandable Details Row -->
                            <tr class="job-details-row" id="details-<?php echo $job->id; ?>" style="display: none;">
                                <td colspan="6">
                                    <div class="job-details-content">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="mb-3">
                                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                                    Job Information
                                                </h6>
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td><strong>Title:</strong></td>
                                                        <td><?php echo htmlspecialchars($job->title); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Description:</strong></td>
                                                        <td><?php echo htmlspecialchars($job->description); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Original Price:</strong></td>
                                                        <td class="text-success fw-bold">$<?php echo number_format($job->suggested_price, 2); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Status:</strong></td>
                                                        <td><span class="status-badge status-<?php echo $job->status; ?>"><?php echo ucfirst(str_replace('_', ' ', $job->status)); ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Completed:</strong></td>
                                                        <td><?php echo $job->completed_at ? date('M j, Y g:i A', strtotime($job->completed_at)) : 'Not set'; ?></td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="mb-3">
                                                    <i class="fas fa-user text-info me-2"></i>
                                                    Host Information
                                                </h6>
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td><strong>Name:</strong></td>
                                                        <td><?php echo htmlspecialchars($job->host_name); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Email:</strong></td>
                                                        <td><?php echo htmlspecialchars($job->host_email); ?></td>
                                                    </tr>
                                                </table>
                                                
                                                <?php if ($job->dispute_info): ?>
                                                    <h6 class="mb-3 mt-4">
                                                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                                        Dispute Information
                                                    </h6>
                                                    <table class="table table-sm table-borderless">
                                                        <tr>
                                                            <td><strong>Disputed At:</strong></td>
                                                            <td><?php echo $job->dispute_info['disputed_at'] ? date('M j, Y g:i A', strtotime($job->dispute_info['disputed_at'])) : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Reason:</strong></td>
                                                            <td><?php echo $job->dispute_info['dispute_reason'] ? htmlspecialchars($job->dispute_info['dispute_reason']) : 'N/A'; ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Resolution:</strong></td>
                                                            <td><?php echo $job->dispute_info['dispute_resolution'] ? ucfirst(str_replace('_', ' ', $job->dispute_info['dispute_resolution'])) : 'Pending'; ?></td>
                                                        </tr>
                                                        <?php if ($job->dispute_info['dispute_resolution_notes']): ?>
                                                            <tr>
                                                                <td><strong>Notes:</strong></td>
                                                                <td><?php echo htmlspecialchars($job->dispute_info['dispute_resolution_notes']); ?></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php if ($job->dispute_info['payment_amount']): ?>
                                                            <tr>
                                                                <td><strong>Final Payment:</strong></td>
                                                                <td class="text-success fw-bold">$<?php echo number_format($job->dispute_info['payment_amount'], 2); ?></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                        <?php if ($job->dispute_info['dispute_resolved_at']): ?>
                                                            <tr>
                                                                <td><strong>Resolved At:</strong></td>
                                                                <td><?php echo date('M j, Y g:i A', strtotime($job->dispute_info['dispute_resolved_at'])); ?></td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </table>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($job->price_adjustments)): ?>
                                                    <h6 class="mb-3 mt-4">
                                                        <i class="fas fa-dollar-sign text-success me-2"></i>
                                                        Price Adjustments
                                                    </h6>
                                                    <div class="price-adjustments">
                                                        <?php foreach ($job->price_adjustments as $adjustment): ?>
                                                            <div class="adjustment-item mb-2 p-2 bg-light rounded">
                                                                <div class="d-flex justify-content-between">
                                                                    <span class="fw-bold">$<?php echo number_format($adjustment->requested_amount, 2); ?></span>
                                                                    <small class="text-muted"><?php echo date('M j, Y', strtotime($adjustment->created_at)); ?></small>
                                                                </div>
                                                                <?php if ($adjustment->reason): ?>
                                                                    <div class="text-muted small"><?php echo htmlspecialchars($adjustment->reason); ?></div>
                                                                <?php endif; ?>
                                                                <div class="mt-1">
                                                                    <span class="badge badge-<?php echo $adjustment->status === 'pending' ? 'warning' : ($adjustment->status === 'approved' ? 'success' : 'danger'); ?>">
                                                                        <?php echo ucfirst($adjustment->status); ?>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php else: ?>
        <div class="no-data">
            <i class="fas fa-clipboard-list"></i>
            <h3>No Completed Jobs Found</h3>
            <p>
                You haven't completed any jobs yet. Once you complete your first job, it will appear here.
            </p>
        </div>
    <?php endif; ?>
</div>


<script>
// Toggle job details row
function toggleJobDetails(jobId) {
    const detailsRow = document.getElementById('details-' + jobId);
    const toggleButton = document.querySelector(`[data-job-id="${jobId}"]`);
    const chevronIcon = toggleButton.querySelector('i');
    
    if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
        // Show details
        detailsRow.style.display = 'table-row';
        chevronIcon.classList.remove('fa-chevron-down');
        chevronIcon.classList.add('fa-chevron-up');
        toggleButton.innerHTML = '<i class="fas fa-chevron-up me-1"></i>Hide Details';
    } else {
        // Hide details
        detailsRow.style.display = 'none';
        chevronIcon.classList.remove('fa-chevron-up');
        chevronIcon.classList.add('fa-chevron-down');
        toggleButton.innerHTML = '<i class="fas fa-chevron-down me-1"></i>Details';
    }
}
</script>