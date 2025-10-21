<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-history text-primary me-2"></i>
                        Past Jobs
                    </h2>
                    <p class="page-subtitle">View your closed and recalled jobs with payment information</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value">$<?php echo number_format($summary['total_paid'], 2); ?></h3>
                    <p class="card-label">Total Paid</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $summary['total_jobs']; ?></h3>
                    <p class="card-label">Total Jobs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-calculator"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value">$<?php echo number_format($summary['average_payment'], 2); ?></h3>
                    <p class="card-label">Average Payment</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $summary['recalled_jobs']; ?></h3>
                    <p class="card-label">Recalled Jobs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter text-info me-2"></i>
                        Filter & Search
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" id="toggleFilters">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="card-body" id="filterBody">
                    <form method="GET" action="<?php echo base_url('host/payment-history'); ?>" id="filterForm">
                        <div class="row">
                            <!-- Date Range -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">From Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="date_from" 
                                       value="<?php echo htmlspecialchars($filters['date_from']); ?>">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">To Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="date_to" 
                                       value="<?php echo htmlspecialchars($filters['date_to']); ?>">
                            </div>
                            
                            <!-- Search -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Search</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           name="search" 
                                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                           placeholder="Job title, cleaner name, or address">
                                </div>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="closed" <?php echo ($filters['status'] ?? '') === 'closed' ? 'selected' : ''; ?>>
                                        Closed
                                    </option>
                                    <option value="completed" <?php echo ($filters['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>
                                        Completed
                                    </option>
                                </select>
                            </div>
                            
                            <div class="col-md-1 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Clear Filters -->
                        <div class="row">
                            <div class="col-12">
                                <a href="<?php echo base_url('host/payment-history'); ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>
                                    Clear All Filters
                                </a>
                                <span class="text-muted ms-3">
                                    Showing <?php echo count($past_jobs); ?> jobs
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jobs List -->
    <div class="row">
        <div class="col-12">
            <div class="jobs-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-info me-2"></i>
                        Past Jobs
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($past_jobs)): ?>
                        <div class="jobs-list">
                            <?php foreach ($past_jobs as $job): ?>
                                <?php 
                                $payment_amount = $job->payment_amount ?: ($job->final_price ?: $job->accepted_price ?: 0);
                                $cleaner_name = trim(($job->cleaner_first_name ?? '') . ' ' . ($job->cleaner_last_name ?? ''));
                                if (empty($cleaner_name)) {
                                    $cleaner_name = $job->cleaner_username ?? 'Unknown Cleaner';
                                }
                                ?>
                                <div class="job-item" data-job-id="<?php echo $job->id; ?>">
                                    <div class="job-header" onclick="toggleJobDetails(<?php echo $job->id; ?>)">
                                        <div class="job-info">
                                            <div class="job-title">
                                                <h6 class="mb-1"><?php echo htmlspecialchars($job->title); ?></h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-user me-1"></i>
                                                    <?php echo htmlspecialchars($cleaner_name); ?>
                                                </small>
                                            </div>
                                            <div class="job-meta">
                                                <span class="job-date">
                                                    <i class="fas fa-calendar me-1"></i>
                                                    <?php 
                                                    if ($job->payment_released_at) {
                                                        echo date('M j, Y', strtotime($job->payment_released_at));
                                                    } elseif ($job->updated_at) {
                                                        echo date('M j, Y', strtotime($job->updated_at));
                                                    } else {
                                                        echo 'Date not available';
                                                    }
                                                    ?>
                                                </span>
                                                <span class="job-status status-<?php echo $job->status; ?>">
                                                    <?php if ($job->status === 'recalled'): ?>
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Recalled
                                                    <?php elseif ($job->status === 'recall_settled'): ?>
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        Recall Settled
                                                    <?php else: ?>
                                                        <?php echo ucfirst($job->status); ?>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="job-payment">
                                            <div class="payment-amount">
                                                $<?php echo number_format($payment_amount, 2); ?>
                                            </div>
                                            <div class="payment-label">Paid</div>
                                        </div>
                                        <div class="job-toggle">
                                            <i class="fas fa-chevron-down toggle-icon"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Expandable Job Details -->
                                    <div class="job-details" id="job-details-<?php echo $job->id; ?>" style="display: none;">
                                        <div class="details-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="details-title">Job Information</h6>
                                                    <div class="detail-item">
                                                        <label>Description:</label>
                                                        <p><?php echo htmlspecialchars($job->description); ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Address:</label>
                                                        <p><?php echo htmlspecialchars($job->address); ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Scheduled Date:</label>
                                                        <p>
                                                            <?php 
                                                            if ($job->scheduled_date) {
                                                                $datetime = $job->scheduled_date . ($job->scheduled_time ? ' ' . $job->scheduled_time : '');
                                                                echo date('M j, Y g:i A', strtotime($datetime));
                                                            } else {
                                                                echo 'Not scheduled';
                                                            }
                                                            ?>
                                                        </p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <label>Duration:</label>
                                                        <p><?php echo $job->estimated_duration ? $job->estimated_duration . ' hours' : 'Not specified'; ?></p>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="details-title">Payment Information</h6>
                                                    <div class="detail-item">
                                                        <label>Original Price:</label>
                                                        <p>$<?php echo number_format($job->suggested_price, 2); ?></p>
                                                    </div>
                                                    <?php if ($job->accepted_price && $job->accepted_price != $job->suggested_price): ?>
                                                        <div class="detail-item">
                                                            <label>Accepted Price:</label>
                                                            <p>$<?php echo number_format($job->accepted_price, 2); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if ($job->final_price && $job->final_price != $job->accepted_price): ?>
                                                        <div class="detail-item">
                                                            <label>Final Price:</label>
                                                            <p>$<?php echo number_format($job->final_price, 2); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="detail-item">
                                                        <label>Amount Paid:</label>
                                                        <p class="payment-highlight">$<?php echo number_format($payment_amount, 2); ?></p>
                                                    </div>
                                                    <?php if ($job->payment_released_at): ?>
                                                        <div class="detail-item">
                                                            <label>Payment Released:</label>
                                                            <p><?php echo date('M j, Y g:i A', strtotime($job->payment_released_at)); ?></p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <?php if (!empty($job->rooms) || !empty($job->extras) || $job->pets): ?>
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <h6 class="details-title">Job Details</h6>
                                                        <div class="job-specs">
                                                            <?php if (!empty($job->rooms)): ?>
                                                                <div class="spec-item">
                                                                    <i class="fas fa-home me-2"></i>
                                                                    <?php 
                                                                    $rooms = is_string($job->rooms) ? json_decode($job->rooms, true) : $job->rooms;
                                                                    if (is_array($rooms)) {
                                                                        echo htmlspecialchars(implode(', ', $rooms)) . ' rooms';
                                                                    } else {
                                                                        echo htmlspecialchars($job->rooms) . ' rooms';
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (!empty($job->extras)): ?>
                                                                <div class="spec-item">
                                                                    <i class="fas fa-plus me-2"></i>
                                                                    <?php 
                                                                    $extras = is_string($job->extras) ? json_decode($job->extras, true) : $job->extras;
                                                                    if (is_array($extras)) {
                                                                        echo htmlspecialchars(implode(', ', $extras));
                                                                    } else {
                                                                        echo htmlspecialchars($job->extras);
                                                                    }
                                                                    ?>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if ($job->pets == 1 || $job->pets == '1'): ?>
                                                                <div class="spec-item">
                                                                    <i class="fas fa-paw me-2"></i>
                                                                    Pets present
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Job Actions -->
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="job-actions">
                                                        <a href="<?php echo base_url('host/job/' . $job->id); ?>" 
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-eye me-1"></i>
                                                            View Details
                                                        </a>
                                                        <?php if ($job->status === 'closed'): ?>
                                                            <a href="<?php echo base_url('host/recall_job/' . $job->id); ?>" 
                                                               class="btn btn-warning btn-sm" 
                                                               title="Recall Job for Admin Review">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                                Recall Job
                                                            </a>
                                                        <?php elseif ($job->status === 'recalled'): ?>
                                                            <span class="badge bg-warning text-dark">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                                Under Review
                                                            </span>
                                                        <?php elseif ($job->status === 'recall_settled'): ?>
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i>
                                                                Recall Settled
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5>No Closed Jobs Found</h5>
                            <p class="text-muted">
                                <?php if (!empty(array_filter($filters))): ?>
                                    No jobs match your current filters. Try adjusting your search criteria.
                                <?php else: ?>
                                    You haven't completed any jobs yet. Once you close jobs, they will appear here with payment information.
                                <?php endif; ?>
                            </p>
                            <?php if (!empty(array_filter($filters))): ?>
                                <a href="<?php echo base_url('host/payment-history'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Clear Filters
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
/* Earnings Page Styles */
.container-fluid {
    max-width: 95% !important;
    margin: 0 auto !important;
    padding: 0 20px;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6c757d;
    margin: 0;
}

/* Summary Cards */
.summary-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
}

.summary-card:nth-child(1) .card-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.summary-card:nth-child(2) .card-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.summary-card:nth-child(3) .card-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.card-value {
    font-size: 1.8rem;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.card-label {
    color: #6c757d;
    margin: 0;
    font-size: 0.9rem;
}

/* Filter Card */
.filter-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.filter-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-card .card-body {
    padding: 2rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Jobs Card */
.jobs-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.jobs-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1.5rem;
}

/* Job Items */
.job-item {
    border-bottom: 1px solid #f8f9fa;
    transition: all 0.3s ease;
}

.job-item:last-child {
    border-bottom: none;
}

.job-header {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.job-header:hover {
    background: rgba(102, 126, 234, 0.05);
}

.job-info {
    flex: 1;
}

.job-title h6 {
    color: #333;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.job-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-top: 0.5rem;
}

.job-date {
    color: #6c757d;
    font-size: 0.9rem;
}

.job-status {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-closed {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

.status-recalled {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
    animation: pulse 2s infinite;
}

.status-recall_settled {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

@keyframes pulse {
    0%, 100% {
        box-shadow: 0 0 5px rgba(255, 193, 7, 0.5);
    }
    50% {
        box-shadow: 0 0 15px rgba(255, 193, 7, 0.8);
    }
}

.status-completed {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.job-payment {
    text-align: center;
    margin: 0 2rem;
}

.payment-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #28a745;
}

.payment-label {
    color: #6c757d;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.job-toggle {
    color: #6c757d;
    transition: all 0.3s ease;
}

.toggle-icon {
    transition: transform 0.3s ease;
}

.job-item.expanded .toggle-icon {
    transform: rotate(180deg);
}

/* Job Details */
.job-details {
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.details-content {
    padding: 2rem;
}

.details-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #667eea;
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-item label {
    font-weight: 600;
    color: #495057;
    display: block;
    margin-bottom: 0.25rem;
}

.detail-item p {
    margin: 0;
    color: #6c757d;
}

.payment-highlight {
    color: #28a745 !important;
    font-weight: 600;
    font-size: 1.1rem;
}

.job-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
}

.spec-item {
    background: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    color: #495057;
    border: 1px solid #e9ecef;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        max-width: 100%;
        padding: 0 15px;
    }
    
    .summary-card {
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .job-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .job-payment {
        margin: 0;
        align-self: flex-end;
    }
    
    .job-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .details-content {
        padding: 1rem;
    }
    
    .job-specs {
        flex-direction: column;
    }
    
    .job-actions {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .job-actions .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Job Actions */
.job-actions {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    padding: 1rem 0;
    border-top: 1px solid #e9ecef;
    margin-top: 1rem;
}

.job-actions .btn {
    border-radius: 6px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.job-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}
</style>

<script>
$(document).ready(function() {
    // Toggle filters visibility
    $('#toggleFilters').click(function() {
        $('#filterBody').slideToggle();
        const icon = $(this).find('i');
        icon.toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    // Auto-submit form on filter change
    $('#filterForm select, #filterForm input[type="date"]').change(function() {
        $('#filterForm').submit();
    });
    
    // Search with debounce
    let searchTimeout;
    $('#filterForm input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });

});

function toggleJobDetails(jobId) {
    const details = $('#job-details-' + jobId);
    const jobItem = details.closest('.job-item');
    const toggleIcon = jobItem.find('.toggle-icon');
    
    if (details.is(':visible')) {
        details.slideUp();
        jobItem.removeClass('expanded');
    } else {
        details.slideDown();
        jobItem.addClass('expanded');
    }
}
</script>
