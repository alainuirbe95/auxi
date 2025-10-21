<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        Completed Jobs
                    </h2>
                    <p class="page-subtitle">Review and complete jobs that cleaners have finished</p>
                    <div class="header-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($jobs_needing_review); ?></span>
                            <span class="stat-label">Need Review</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($jobs_past_review_window); ?></span>
                            <span class="stat-label">Past Review Window</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($jobs_needing_review) + count($jobs_past_review_window); ?></span>
                            <span class="stat-label">Total Completed</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-list me-2"></i>
                        View All Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter text-info me-2"></i>
                        Filters & Search
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" id="toggleFilters">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="card-body" id="filterBody">
                    <form method="GET" action="<?php echo base_url('host/completed-jobs'); ?>" id="filterForm">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Search Jobs</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           name="search" 
                                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                           placeholder="Search by title, cleaner, or description">
                                </div>
                            </div>
                            <!-- Sort By -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sort By</label>
                                <select class="form-select" name="sort">
                                    <option value="completed_at" <?php echo ($filters['sort_by'] ?? '') === 'completed_at' ? 'selected' : ''; ?>>
                                        Completion Date
                                    </option>
                                    <option value="title" <?php echo ($filters['sort_by'] ?? '') === 'title' ? 'selected' : ''; ?>>
                                        Job Title
                                    </option>
                                    <option value="final_price" <?php echo ($filters['sort_by'] ?? '') === 'final_price' ? 'selected' : ''; ?>>
                                        Price
                                    </option>
                                </select>
                            </div>
                            <!-- Order -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Order</label>
                                <select class="form-select" name="order">
                                    <option value="DESC" <?php echo ($filters['sort_order'] ?? '') === 'DESC' ? 'selected' : ''; ?>>
                                        Newest First
                                    </option>
                                    <option value="ASC" <?php echo ($filters['sort_order'] ?? '') === 'ASC' ? 'selected' : ''; ?>>
                                        Oldest First
                                    </option>
                                </select>
                            </div>
                            <!-- Apply Button -->
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
                                <a href="<?php echo base_url('host/completed-jobs'); ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>
                                    Clear Filters
                                </a>
                                <span class="text-muted ms-3">
                                    Showing <?php echo count($jobs_needing_review) + count($jobs_past_review_window); ?> completed jobs
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jobs Needing Review -->
    <?php if (!empty($jobs_needing_review)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-clock text-warning me-2"></i>
                            Jobs Needing Review (24 Hour Window)
                        </h5>
                        <small>These jobs need your review within 24 hours. Complete them to release payment to the cleaner.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Details</th>
                                        <th>Cleaner</th>
                                        <th>Completed</th>
                                        <th>Price</th>
                                        <th>Time Remaining</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs_needing_review as $job): ?>
                                        <?php
                                        $completed_time = strtotime($job->completed_at);
                                        $review_deadline = $completed_time + (24 * 60 * 60);
                                        $time_remaining = $review_deadline - time();
                                        $hours_remaining = max(0, floor($time_remaining / 3600));
                                        $minutes_remaining = max(0, floor(($time_remaining % 3600) / 60));
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="job-details-cell">
                                                    <h6 class="job-title mb-1"><?php echo htmlspecialchars($job->title); ?></h6>
                                                    <p class="job-description mb-1">
                                                        <?php echo htmlspecialchars(substr($job->description, 0, 80)) . (strlen($job->description) > 80 ? '...' : ''); ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        Address: <?php echo htmlspecialchars($job->address); ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="cleaner-info">
                                                    <strong><?php echo htmlspecialchars($job->cleaner_first_name . ' ' . $job->cleaner_last_name); ?></strong>
                                                    <br>
                                                    <small class="text-muted">@<?php echo htmlspecialchars($job->cleaner_username); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="completion-info">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    <?php echo date('M j, Y', strtotime($job->completed_at)); ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo date('g:i A', strtotime($job->completed_at)); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="price-badge">
                                                    $<?php echo number_format($job->final_price ?: $job->accepted_price, 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if ($job->status === 'recalled'): ?>
                                                    <span class="recalled-status text-warning">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Under Review
                                                    </span>
                                                <?php elseif ($time_remaining > 0): ?>
                                                    <span class="time-remaining text-warning">
                                                        <i class="fas fa-clock me-1"></i>
                                                        <?php echo $hours_remaining; ?>h <?php echo $minutes_remaining; ?>m
                                                    </span>
                                                <?php else: ?>
                                                    <span class="time-expired text-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Expired
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <?php if ($job->status === 'recalled'): ?>
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Under Review
                                                        </span>
                                                        <a href="<?php echo base_url('host/job/' . $job->id); ?>" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="View Job Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-success btn-sm complete-job-btn" 
                                                                data-job-id="<?php echo $job->id; ?>"
                                                                data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                                                                title="Complete Job & Release Payment">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Complete Job
                                                        </button>
                                                        <a href="<?php echo base_url('host/recall_job/' . $job->id); ?>" 
                                                           class="btn btn-warning btn-sm" 
                                                           title="Recall Job for Admin Review">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Recall Job
                                                        </a>
                                                        <a href="<?php echo base_url('host/job/' . $job->id); ?>" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="View Job Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Jobs Past Review Window -->
    <?php if (!empty($jobs_past_review_window)): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Jobs Past Review Window
                        </h5>
                        <small>These jobs are past the 24-hour review window. You can still complete them, but payment may have been automatically released.</small>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Details</th>
                                        <th>Cleaner</th>
                                        <th>Completed</th>
                                        <th>Price</th>
                                        <th>Days Past</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobs_past_review_window as $job): ?>
                                        <?php
                                        $completed_time = strtotime($job->completed_at);
                                        $days_past = floor((time() - $completed_time) / (24 * 60 * 60));
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="job-details-cell">
                                                    <h6 class="job-title mb-1"><?php echo htmlspecialchars($job->title); ?></h6>
                                                    <p class="job-description mb-1">
                                                        <?php echo htmlspecialchars(substr($job->description, 0, 80)) . (strlen($job->description) > 80 ? '...' : ''); ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        Address: <?php echo htmlspecialchars($job->address); ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="cleaner-info">
                                                    <strong><?php echo htmlspecialchars($job->cleaner_first_name . ' ' . $job->cleaner_last_name); ?></strong>
                                                    <br>
                                                    <small class="text-muted">@<?php echo htmlspecialchars($job->cleaner_username); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="completion-info">
                                                    <i class="fas fa-check-circle text-success me-1"></i>
                                                    <?php echo date('M j, Y', strtotime($job->completed_at)); ?>
                                                    <br>
                                                    <small class="text-muted"><?php echo date('g:i A', strtotime($job->completed_at)); ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="price-badge">
                                                    $<?php echo number_format($job->final_price ?: $job->accepted_price, 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="days-past text-danger">
                                                    <i class="fas fa-clock me-1"></i>
                                                    <?php echo $days_past; ?> day<?php echo $days_past != 1 ? 's' : ''; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <?php if ($job->status === 'recalled'): ?>
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Under Review
                                                        </span>
                                                        <a href="<?php echo base_url('host/job/' . $job->id); ?>" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="View Job Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-warning btn-sm complete-job-btn" 
                                                                data-job-id="<?php echo $job->id; ?>"
                                                                data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                                                                title="Complete Job (Payment may have been auto-released)">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Complete Job
                                                        </button>
                                                        <a href="<?php echo base_url('host/recall_job/' . $job->id); ?>" 
                                                           class="btn btn-danger btn-sm" 
                                                           title="Recall Job for Admin Review">
                                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                                            Recall Job
                                                        </a>
                                                        <a href="<?php echo base_url('host/job/' . $job->id); ?>" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="View Job Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if (empty($jobs_needing_review) && empty($jobs_past_review_window)): ?>
        <div class="row">
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                    <h5>No Completed Jobs</h5>
                    <p class="text-muted">
                        <?php if (!empty($filters['search'])): ?>
                            No completed jobs match your search criteria.
                        <?php else: ?>
                            You don't have any completed jobs waiting for review. Completed jobs will appear here for you to review and complete.
                        <?php endif; ?>
                    </p>
                    <?php if (!empty($filters['search'])): ?>
                        <a href="<?php echo base_url('host/completed-jobs'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            Clear Search
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Recall Job Modal -->
<div class="modal fade" id="recallJobModal" tabindex="-1" role="dialog" aria-labelledby="recallJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="recallJobModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Recall Job for Admin Review
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Important:</strong> Recalling a job will still release payment to the cleaner, but will notify the admin for review and potential legal action.
                </div>
                
                <form id="recallJobForm">
                    <input type="hidden" id="recall_job_id" name="job_id">
                    
                    <div class="mb-3">
                        <label for="recall_reason" class="form-label">Reason for Recall <span class="text-danger">*</span></label>
                        <select class="form-select" id="recall_reason" name="recall_reason" required>
                            <option value="">Select a reason...</option>
                            <option value="poor_quality">Poor Quality Work</option>
                            <option value="incomplete_service">Incomplete Service</option>
                            <option value="damage_caused">Damage to Property</option>
                            <option value="unprofessional_behavior">Unprofessional Behavior</option>
                            <option value="safety_concerns">Safety Concerns</option>
                            <option value="contract_violation">Contract Violation</option>
                            <option value="other">Other (specify in details)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="recall_details" class="form-label">Detailed Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="recall_details" name="recall_details" rows="5" 
                                  placeholder="Please provide a detailed description of the issues encountered. Include specific examples, photos if available, and any relevant information that will help the admin review this case." 
                                  required></textarea>
                        <div class="form-text">
                            <span id="detailsCount">0</span>/1000 characters
                        </div>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> False or malicious recalls may result in account suspension. Please ensure your recall is legitimate and well-documented.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <button type="button" class="btn btn-danger" id="submitRecall">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    Submit Recall
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.header-content {
    flex: 1;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

.header-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.header-actions {
    margin-left: 2rem;
}

/* Filter Card */
.filter-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.filter-card .card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    border-radius: 15px 15px 0 0;
    padding: 1rem 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-card .card-body {
    padding: 1.5rem;
}

/* Job Cards */
.card {
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: none;
    margin-bottom: 2rem;
}

.card-header {
    border-radius: 15px 15px 0 0;
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.card-body {
    padding: 1.5rem;
}

/* Table Styling */
.table {
    margin-bottom: 0;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem 0.75rem;
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
}

.job-details-cell {
    max-width: 300px;
}

.job-title {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.job-description {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.cleaner-info {
    text-align: center;
}

.price-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1.1rem;
}

.time-remaining {
    font-weight: 600;
}

.time-expired {
    font-weight: 600;
}

.days-past {
    font-weight: 600;
}

.completion-info {
    text-align: center;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
}

.btn {
    border-radius: 25px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
    border: none;
    color: white;
}

.btn-outline-primary {
    border: 2px solid #007bff;
    color: #007bff;
}

.btn-outline-primary:hover {
    background: #007bff;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.empty-state i {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

.empty-state h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .header-actions {
        margin-left: 0;
        margin-top: 1rem;
    }
    
    .header-stats {
        justify-content: center;
        gap: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .btn {
        font-size: 0.8rem;
        padding: 0.4rem 0.8rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Toggle filters
    $('#toggleFilters').on('click', function() {
        $('#filterBody').slideToggle();
        const icon = $(this).find('i');
        icon.toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    // Auto-submit form on filter change
    $('#filterForm select').change(function() {
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

    // Complete job functionality
    $('.complete-job-btn').on('click', function() {
        const jobId = $(this).data('job-id');
        const jobTitle = $(this).data('job-title');
        const $button = $(this);
        
        if (confirm(`Are you sure you want to complete the job "${jobTitle}"? This will release payment to the cleaner and cannot be undone.`)) {
            // Show loading state
            $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
            
            $.ajax({
                url: '<?php echo base_url("host/complete_job"); ?>',
                type: 'POST',
                data: {
                    job_id: jobId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                        $button.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i>Complete Job');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    $button.prop('disabled', false).html('<i class="fas fa-check-circle me-1"></i>Complete Job');
                }
            });
        }
    });

    // Recall job functionality
    $('.recall-job-btn').on('click', function() {
        const jobId = $(this).data('job-id');
        const jobTitle = $(this).data('job-title');
        
        // Set job ID in modal
        $('#recall_job_id').val(jobId);
        
        // Update modal title with job name
        $('#recallJobModalLabel').html('<i class="fas fa-exclamation-triangle me-2"></i>Recall Job: ' + jobTitle);
        
        // Reset form
        $('#recallJobForm')[0].reset();
        $('#recall_job_id').val(jobId);
        $('#detailsCount').text('0');
        
        // Show modal
        $('#recallJobModal').modal('show');
    });

    // Character counter for recall details
    $('#recall_details').on('input', function() {
        const count = $(this).val().length;
        $('#detailsCount').text(count);
        
        // Limit to 1000 characters
        if (count > 1000) {
            $(this).val($(this).val().substring(0, 1000));
            $('#detailsCount').text('1000');
        }
    });

    // Submit recall
    $('#submitRecall').on('click', function() {
        const $button = $(this);
        const formData = $('#recallJobForm').serialize();
        
        // Validate form
        if (!$('#recall_reason').val()) {
            alert('Please select a reason for the recall.');
            return;
        }
        
        if (!$('#recall_details').val().trim()) {
            alert('Please provide detailed description of the issues.');
            return;
        }
        
        if ($('#recall_details').val().length < 20) {
            alert('Please provide a more detailed description (at least 20 characters).');
            return;
        }
        
        // Show loading state
        $button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Submitting...');
        
        $.ajax({
            url: '<?php echo base_url("host/recall_job"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    $('#recallJobModal').modal('hide');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    $button.prop('disabled', false).html('<i class="fas fa-exclamation-triangle me-1"></i>Submit Recall');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $button.prop('disabled', false).html('<i class="fas fa-exclamation-triangle me-1"></i>Submit Recall');
            }
        });
    });

    // Reset form when modal is hidden
    $('#recallJobModal').on('hidden.bs.modal', function() {
        $('#recallJobForm')[0].reset();
        $('#detailsCount').text('0');
        $('#submitRecall').prop('disabled', false).html('<i class="fas fa-exclamation-triangle me-1"></i>Submit Recall');
    });
});
</script>
