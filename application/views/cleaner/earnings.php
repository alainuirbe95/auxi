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

/* Earnings Dashboard Styles */
.earnings-container {
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

/* Date Range Filter */
.date-filter-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.date-filter-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
}

.date-filter-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.date-filter-body {
    padding: 2rem;
}

.date-range-form {
    display: flex;
    align-items: end;
    gap: 1rem;
    flex-wrap: wrap;
}

.date-input-group {
    flex: 1;
    min-width: 200px;
}

.date-input-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.date-input-group input {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.date-input-group input:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    outline: none;
}

.filter-btn {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.filter-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
}

/* Summary Cards */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.summary-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.summary-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.summary-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.summary-card-title {
    font-size: 0.9rem;
    color: #666;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.summary-card-value {
    font-size: 2rem;
    font-weight: 800;
    color: #333;
    margin-bottom: 0.5rem;
}

.summary-card-subtitle {
    font-size: 0.8rem;
    color: #999;
}

/* Table Styles */
.jobs-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
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

.price-info {
    text-align: right;
}

.price-info .fw-bold {
    font-size: 1.1rem;
}

.price-info .text-info {
    font-size: 0.9rem;
}

.price-info .text-warning {
    font-size: 0.8rem;
}

.price-info .text-muted {
    font-size: 0.8rem;
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
    
    .date-range-form {
        flex-direction: column;
        align-items: stretch;
    }
    
    .date-input-group {
        min-width: auto;
    }
    
    .table-responsive {
        max-height: 500px;
    }
    
    .table th, .table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.9rem;
    }
    
    .summary-cards {
        grid-template-columns: 1fr;
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
    
    .summary-card {
        padding: 1.5rem;
    }
    
    .summary-card-value {
        font-size: 1.5rem;
    }
}
</style>

<div class="earnings-container">
    <!-- Page Header -->
    <div class="page-header">
        <h2>
            <i class="fas fa-dollar-sign me-3"></i>
            My Earnings
        </h2>
        <p>Track your completed jobs and payment history</p>
    </div>

    <!-- Date Range Filter -->
    <div class="date-filter-card">
        <div class="date-filter-header">
            <h5>
                <i class="fas fa-calendar-alt text-success me-2"></i>
                Filter by Date Range
            </h5>
        </div>
        <div class="date-filter-body">
            <form method="GET" action="<?php echo base_url('cleaner/earnings'); ?>" class="date-range-form">
                <div class="date-input-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                <div class="date-input-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                <button type="submit" class="filter-btn">
                    <i class="fas fa-search me-2"></i>
                    Filter
                </button>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <div class="summary-card-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="summary-card-title">Total Earnings</div>
            <div class="summary-card-value">$<?php echo number_format($earnings_summary['total_earnings'], 2); ?></div>
            <div class="summary-card-subtitle">All time earnings</div>
        </div>
        
        <div class="summary-card">
            <div class="summary-card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="summary-card-title">Closed Jobs</div>
            <div class="summary-card-value"><?php echo count($closed_jobs); ?></div>
            <div class="summary-card-subtitle">Jobs in selected period</div>
        </div>
        
        <div class="summary-card">
            <div class="summary-card-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="summary-card-title">Average Earnings</div>
            <div class="summary-card-value">$<?php echo count($closed_jobs) > 0 ? number_format(array_sum(array_column($closed_jobs, 'payment_amount')) / count($closed_jobs), 2) : '0.00'; ?></div>
            <div class="summary-card-subtitle">Per job in period</div>
        </div>
    </div>

    <!-- Closed Jobs Table -->
    <?php if (!empty($closed_jobs)): ?>
        <div class="jobs-table-container">
            <div class="table-header">
                <h5>
                    <i class="fas fa-list me-2"></i>
                    Closed Jobs History
                </h5>
                <div class="sort-controls">
                    <small class="text-muted">
                        <?php echo count($closed_jobs); ?> jobs closed
                    </small>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Job Title</th>
                            <th>Host</th>
                            <th>Payment Released</th>
                            <th>Original Price</th>
                            <th>Final Payment</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($closed_jobs as $job): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($job->title); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars(substr($job->description, 0, 50)) . '...'; ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($job->host_name); ?></td>
                                <td>
                                    <?php if ($job->payment_released_at): ?>
                                        <?php echo date('M j, Y', strtotime($job->payment_released_at)); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not released</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-success fw-bold">
                                    $<?php echo number_format($job->suggested_price, 2); ?>
                                    <?php if (!empty($job->price_adjustments)): ?>
                                        <?php 
                                        $approved_adjustment = null;
                                        foreach ($job->price_adjustments as $adjustment) {
                                            if ($adjustment->status === 'approved') {
                                                $approved_adjustment = $adjustment;
                                                break;
                                            }
                                        }
                                        ?>
                                        <?php if ($approved_adjustment): ?>
                                            <br><small class="text-info">
                                                <i class="fas fa-arrow-up"></i> Counter: $<?php echo number_format($approved_adjustment->requested_amount, 2); ?>
                                            </small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td class="text-primary fw-bold">
                                    $<?php echo number_format($job->payment_amount ?: $job->suggested_price, 2); ?>
                                </td>
                                <td>
                                    <span class="badge bg-success">Closed</span>
                                    <?php if ($job->dispute_info): ?>
                                        <br><small class="text-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Disputed
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-details" onclick="toggleJobDetails(<?php echo $job->id; ?>)">
                                        <i class="fas fa-chevron-down me-1"></i>Details
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Expandable Details Row -->
                            <tr class="job-details-row" id="details-<?php echo $job->id; ?>" style="display: none;">
                                <td colspan="7">
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
                                                    <?php if (!empty($job->price_adjustments)): ?>
                                                        <?php 
                                                        $approved_adjustment = null;
                                                        foreach ($job->price_adjustments as $adjustment) {
                                                            if ($adjustment->status === 'approved') {
                                                                $approved_adjustment = $adjustment;
                                                                break;
                                                            }
                                                        }
                                                        ?>
                                                        <?php if ($approved_adjustment): ?>
                                                            <tr>
                                                                <td><strong>Counter Offer:</strong></td>
                                                                <td class="text-info fw-bold">
                                                                    $<?php echo number_format($approved_adjustment->requested_amount, 2); ?>
                                                                    <small class="text-muted d-block">
                                                                        Approved on <?php echo date('M j, Y', strtotime($approved_adjustment->approved_at)); ?>
                                                                    </small>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <tr>
                                                        <td><strong>Final Payment:</strong></td>
                                                        <td class="text-primary fw-bold">$<?php echo number_format($job->payment_amount ?: $job->suggested_price, 2); ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Completed:</strong></td>
                                                        <td><?php echo $job->completed_at ? date('M j, Y g:i A', strtotime($job->completed_at)) : 'Not set'; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong>Payment Released:</strong></td>
                                                        <td><?php echo $job->payment_released_at ? date('M j, Y g:i A', strtotime($job->payment_released_at)) : 'Not released'; ?></td>
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
                                                        Price Adjustments History
                                                    </h6>
                                                    <div class="price-adjustments">
                                                        <?php foreach ($job->price_adjustments as $adjustment): ?>
                                                            <div class="adjustment-item mb-2 p-2 bg-light rounded">
                                                                <div class="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <span class="fw-bold">$<?php echo number_format($adjustment->requested_amount, 2); ?></span>
                                                                        <?php if ($adjustment->reason): ?>
                                                                            <div class="text-muted small"><?php echo htmlspecialchars($adjustment->reason); ?></div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                    <div class="text-end">
                                                                        <small class="text-muted"><?php echo date('M j, Y', strtotime($adjustment->created_at)); ?></small>
                                                                        <div class="mt-1">
                                                                            <span class="badge badge-<?php echo $adjustment->status === 'approved' ? 'success' : ($adjustment->status === 'rejected' ? 'danger' : 'warning'); ?>">
                                                                                <?php echo ucfirst($adjustment->status); ?>
                                                                            </span>
                                                                        </div>
                                                                    </div>
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
            <h3>No Closed Jobs Found</h3>
            <p>
                No closed jobs found in the selected date range. Closed jobs appear here after payment has been released.
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
// Toggle job details row
function toggleJobDetails(jobId) {
    const detailsRow = document.getElementById('details-' + jobId);
    const toggleButton = document.querySelector(`[onclick="toggleJobDetails(${jobId})"]`);
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