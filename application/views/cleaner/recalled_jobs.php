<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Recalled Jobs
                    </h2>
                    <p class="page-subtitle">Jobs that have been recalled by the host for admin review</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?php echo base_url('cleaner/recalled-jobs'); ?>" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Date From
                                </label>
                                <input type="date" class="form-control" id="date_from" name="date_from" 
                                       value="<?php echo $filters['date_from'] ?? ''; ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Date To
                                </label>
                                <input type="date" class="form-control" id="date_to" name="date_to" 
                                       value="<?php echo $filters['date_to'] ?? ''; ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="reason" class="form-label">
                                    <i class="fas fa-filter me-1"></i>
                                    Reason
                                </label>
                                <select class="form-select" id="reason" name="reason">
                                    <option value="">All Reasons</option>
                                    <option value="incomplete_work" <?php echo ($filters['reason'] ?? '') === 'incomplete_work' ? 'selected' : ''; ?>>Incomplete Work</option>
                                    <option value="poor_quality" <?php echo ($filters['reason'] ?? '') === 'poor_quality' ? 'selected' : ''; ?>>Poor Quality</option>
                                    <option value="unprofessional_behavior" <?php echo ($filters['reason'] ?? '') === 'unprofessional_behavior' ? 'selected' : ''; ?>>Unprofessional Behavior</option>
                                    <option value="damage_to_property" <?php echo ($filters['reason'] ?? '') === 'damage_to_property' ? 'selected' : ''; ?>>Damage to Property</option>
                                    <option value="no_show" <?php echo ($filters['reason'] ?? '') === 'no_show' ? 'selected' : ''; ?>>No Show</option>
                                    <option value="other" <?php echo ($filters['reason'] ?? '') === 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="severity" class="form-label">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    Severity
                                </label>
                                <select class="form-select" id="severity" name="severity">
                                    <option value="">All Severity</option>
                                    <option value="low" <?php echo ($filters['severity'] ?? '') === 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo ($filters['severity'] ?? '') === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo ($filters['severity'] ?? '') === 'high' ? 'selected' : ''; ?>>High</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-1"></i>
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                        <?php if (!empty(array_filter($filters))): ?>
                        <div class="row mt-2">
                            <div class="col-12 text-end">
                                <a href="<?php echo base_url('cleaner/recalled-jobs'); ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>
                                    Clear Filters
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $total_recalled; ?></h3>
                    <p class="card-label">Total Recalled</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $pending_count; ?></h3>
                    <p class="card-label">Pending Review</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="summary-card">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="card-content">
                    <h3 class="card-value"><?php echo $settled_count; ?></h3>
                    <p class="card-label">Settled</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recalled Jobs List -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Your Recalled Jobs
                    </h5>
                    <button class="btn btn-sm btn-light" id="expandAllBtn">
                        <i class="fas fa-expand-alt me-1"></i>
                        Expand All
                    </button>
                </div>
                <div class="card-body">
                    <?php if (empty($recalled_jobs)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>No recalled jobs found.</strong> You don't have any jobs that have been recalled by hosts.
                        </div>
                    <?php else: ?>
                        <div class="list-group">
                            <?php foreach ($recalled_jobs as $job): ?>
                                <div class="list-group-item recalled-job-item">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">
                                                <i class="fas fa-broom text-primary me-2"></i>
                                                <?php echo htmlspecialchars($job->title); ?>
                                            </h5>
                                            <p class="mb-1 text-muted">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                <?php echo htmlspecialchars($job->city . ', ' . $job->state); ?>
                                                <span class="mx-2">|</span>
                                                <i class="fas fa-user me-1"></i>
                                                Host: <?php echo htmlspecialchars($job->host_name); ?>
                                            </p>
                                            <p class="mb-1">
                                                <span class="badge bg-<?php echo $job->status === 'recall_settled' ? 'success' : 'warning'; ?>">
                                                    <?php echo $job->status === 'recall_settled' ? 'Settled' : 'Under Review'; ?>
                                                </span>
                                                <?php if (!empty($job->recall_reason)): ?>
                                                    <span class="badge bg-secondary ms-2">
                                                        <?php echo ucfirst(str_replace('_', ' ', $job->recall_reason)); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($job->recall_severity)): ?>
                                                    <span class="badge bg-<?php echo $job->recall_severity === 'high' ? 'danger' : ($job->recall_severity === 'medium' ? 'warning' : 'info'); ?> ms-2">
                                                        <?php echo ucfirst($job->recall_severity); ?> Severity
                                                    </span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <button class="btn btn-outline-primary btn-sm toggle-details" data-job-id="<?php echo $job->id; ?>">
                                                <i class="fas fa-chevron-down"></i> Details
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <!-- Expandable Details -->
                                    <div class="job-details mt-3" id="details-<?php echo $job->id; ?>" style="display: none;">
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-primary"><i class="fas fa-info-circle me-2"></i>Job Information</h6>
                                                <p><strong>Scheduled:</strong> <?php echo date('M j, Y g:i A', strtotime($job->scheduled_date . ' ' . $job->scheduled_time)); ?></p>
                                                <p><strong>Completed:</strong> <?php echo $job->completed_at ? date('M j, Y g:i A', strtotime($job->completed_at)) : 'N/A'; ?></p>
                                                <p><strong>Amount:</strong> $<?php 
                                                    $amount = $job->final_price ?? $job->accepted_price ?? $job->suggested_price ?? 0;
                                                    echo number_format($amount, 2); 
                                                ?></p>
                                                <p><strong>Payment Released:</strong> <?php echo $job->payment_released_at ? date('M j, Y g:i A', strtotime($job->payment_released_at)) : 'Not released'; ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Recall Information</h6>
                                                <?php if (!empty($job->recall_reason)): ?>
                                                    <p><strong>Reason:</strong> <?php echo ucfirst(str_replace('_', ' ', $job->recall_reason)); ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($job->recall_severity)): ?>
                                                    <p><strong>Severity:</strong> <?php echo ucfirst($job->recall_severity); ?></p>
                                                <?php endif; ?>
                                                <?php if (!empty($job->recalled_at)): ?>
                                                    <p><strong>Recalled:</strong> <?php echo date('M j, Y g:i A', strtotime($job->recalled_at)); ?></p>
                                                <?php endif; ?>
                                                <p><strong>Status:</strong> 
                                                    <span class="badge bg-<?php echo ($job->recall_status ?? 'pending') === 'resolved' ? 'success' : 'warning'; ?>">
                                                        <?php echo ucfirst(str_replace('_', ' ', $job->recall_status ?? 'pending')); ?>
                                                    </span>
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($job->recall_details)): ?>
                                            <div class="mt-3">
                                                <h6 class="text-danger"><i class="fas fa-file-alt me-2"></i>Host's Recall Details</h6>
                                                <div class="alert alert-light">
                                                    <?php echo nl2br(htmlspecialchars($job->recall_details)); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($job->status === 'recall_settled' || !empty($job->admin_notes) || !empty($job->admin_decision)): ?>
                                            <div class="mt-3 resolution-section">
                                                <h6 class="<?php echo $job->status === 'recall_settled' ? 'text-success' : 'text-info'; ?>">
                                                    <i class="fas fa-<?php echo $job->status === 'recall_settled' ? 'check-circle' : 'info-circle'; ?> me-2"></i>
                                                    <?php echo $job->status === 'recall_settled' ? 'Resolution' : 'Admin Updates'; ?>
                                                </h6>
                                                <div class="alert alert-<?php echo $job->status === 'recall_settled' ? 'success' : 'info'; ?>">
                                                    <?php if (!empty($job->admin_decision)): ?>
                                                        <p class="mb-2"><strong>Admin Decision:</strong> <?php echo ucfirst(str_replace('_', ' ', $job->admin_decision)); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($job->resolution_type)): ?>
                                                        <p class="mb-2"><strong>Resolution Type:</strong> <?php echo ucfirst(str_replace('_', ' ', $job->resolution_type)); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($job->admin_notes)): ?>
                                                        <p class="mb-2"><strong>Admin Notes:</strong></p>
                                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($job->admin_notes)); ?></p>
                                                    <?php endif; ?>
                                                    <?php if (!empty($job->recall_settled_at)): ?>
                                                        <p class="mb-0 mt-2"><small class="text-muted"><strong>Settled:</strong> <?php echo date('M j, Y g:i A', strtotime($job->recall_settled_at)); ?></small></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(245, 87, 108, 0.3);
}

.header-content {
    text-align: center;
}

.page-title {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    font-size: 1rem;
    opacity: 0.95;
    margin-bottom: 0;
}

/* Summary Cards */
.summary-card {
    background: white;
    border-radius: 10px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: all 0.3s ease;
}

.summary-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.card-icon i {
    font-size: 1.5rem;
    color: white;
}

.card-content {
    flex-grow: 1;
}

.card-value {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #2d3748;
}

.card-label {
    font-size: 0.9rem;
    color: #718096;
    margin-bottom: 0;
}

/* Recalled Job Items */
.recalled-job-item {
    border-left: 4px solid #f5576c;
    margin-bottom: 1rem;
    transition: all 0.2s ease;
}

.recalled-job-item:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.toggle-details {
    transition: all 0.2s ease;
}

.toggle-details.active i {
    transform: rotate(180deg);
}

.job-details {
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 1000px;
    }
}

/* Resolution Section and Recall Details - ALWAYS STAY VISIBLE */
.resolution-section,
.job-details .alert,
.job-details h6,
.job-details .alert-light,
.job-details .alert-success,
.job-details .alert-info {
    display: block;
    visibility: visible;
    opacity: 1;
    position: relative;
}

.resolution-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #28a745;
}

.resolution-section .alert {
    margin-bottom: 0;
}
</style>

<script>
$(document).ready(function() {
    // Restore previously expanded details from localStorage
    const expandedJobs = JSON.parse(localStorage.getItem('expandedRecallJobs') || '[]');
    expandedJobs.forEach(function(jobId) {
        const $details = $('#details-' + jobId);
        const $button = $('.toggle-details[data-job-id="' + jobId + '"]');
        if ($details.length && $button.length) {
            $details.show();
            $button.addClass('active');
            $button.html('<i class="fas fa-chevron-up"></i> Hide Details');
        }
    });
    
    // Toggle job details
    $('.toggle-details').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const jobId = $(this).data('job-id');
        const $details = $('#details-' + jobId);
        const $button = $(this);
        
        // Toggle the details
        $details.slideToggle(300, function() {
            // After animation completes, save state
            const isVisible = $details.is(':visible');
            let expandedJobs = JSON.parse(localStorage.getItem('expandedRecallJobs') || '[]');
            
            if (isVisible) {
                // Add to expanded list if not already there
                if (!expandedJobs.includes(jobId)) {
                    expandedJobs.push(jobId);
                }
            } else {
                // Remove from expanded list
                expandedJobs = expandedJobs.filter(id => id !== jobId);
            }
            
            localStorage.setItem('expandedRecallJobs', JSON.stringify(expandedJobs));
        });
        
        $button.toggleClass('active');
        
        if ($button.hasClass('active')) {
            $button.html('<i class="fas fa-chevron-up"></i> Hide Details');
        } else {
            $button.html('<i class="fas fa-chevron-down"></i> Details');
        }
    });
    
    // Prevent any accidental clicks from closing the details
    $('.job-details').on('click', function(e) {
        e.stopPropagation();
    });
    
    // CRITICAL: Prevent ANY script from hiding resolution sections or recall details
    // Override any attempts to hide these elements
    setInterval(function() {
        $('.resolution-section, .job-details h6, .job-details .alert').each(function() {
            if ($(this).css('display') === 'none' || $(this).css('visibility') === 'hidden' || $(this).css('opacity') === '0') {
                $(this).css({
                    'display': 'block',
                    'visibility': 'visible',
                    'opacity': '1'
                });
            }
        });
    }, 100);
    
    // Expand/Collapse All button
    $('#expandAllBtn').on('click', function(e) {
        e.preventDefault();
        const $button = $(this);
        const isExpanding = $button.text().includes('Expand');
        
        if (isExpanding) {
            // Expand all
            $('.job-details').slideDown(300);
            $('.toggle-details').addClass('active').html('<i class="fas fa-chevron-up"></i> Hide Details');
            $button.html('<i class="fas fa-compress-alt me-1"></i> Collapse All');
            
            // Save all as expanded
            const allJobIds = [];
            $('.toggle-details').each(function() {
                allJobIds.push($(this).data('job-id'));
            });
            localStorage.setItem('expandedRecallJobs', JSON.stringify(allJobIds));
        } else {
            // Collapse all
            $('.job-details').slideUp(300);
            $('.toggle-details').removeClass('active').html('<i class="fas fa-chevron-down"></i> Details');
            $button.html('<i class="fas fa-expand-alt me-1"></i> Expand All');
            
            // Clear localStorage
            localStorage.setItem('expandedRecallJobs', JSON.stringify([]));
        }
    });
});
</script>

