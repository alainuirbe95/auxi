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
                                                    
                                                    <!-- Cleaner Information -->
                                                    <div class="detail-item cleaner-info-box">
                                                        <label><i class="fas fa-user-check me-1"></i> Assigned Cleaner:</label>
                                                        <p class="cleaner-name">
                                                            <?php echo htmlspecialchars($cleaner_name); ?>
                                                        </p>
                                                        <?php if (!empty($job->cleaner_username)): ?>
                                                            <small class="cleaner-username">
                                                                <i class="fas fa-at me-1"></i><?php echo htmlspecialchars($job->cleaner_username); ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="details-title">Payment Information</h6>
                                                    <div class="detail-item">
                                                        <label>Your Suggested Price:</label>
                                                        <p>$<?php echo number_format($job->suggested_price, 2); ?></p>
                                                    </div>
                                                    
                                                    <?php if ($job->accepted_offer): ?>
                                                        <?php if ($job->accepted_offer->offer_type === 'counter'): ?>
                                                        <div class="detail-item">
                                                                <label>Counter Offer Accepted:</label>
                                                                <p class="text-warning fw-bold">
                                                                    $<?php echo number_format($job->accepted_offer->amount, 2); ?>
                                                                    <?php 
                                                                    $price_diff = $job->accepted_offer->amount - $job->suggested_price;
                                                                    $price_diff_percent = ($price_diff / $job->suggested_price) * 100;
                                                                    $diff_class = $price_diff > 0 ? 'text-danger' : 'text-success';
                                                                    ?>
                                                                    <small class="<?php echo $diff_class; ?> d-block">
                                                                        <?php echo $price_diff > 0 ? '+' : ''; ?><?php echo number_format($price_diff, 2); ?>
                                                                        (<?php echo $price_diff > 0 ? '+' : ''; ?><?php echo number_format($price_diff_percent, 1); ?>%)
                                                                    </small>
                                                                </p>
                                                        </div>
                                                    <?php endif; ?>
                                                        
                                                        <div class="detail-item cleaner-payout-box">
                                                            <label>Cleaner's Payout:</label>
                                                            <p class="cleaner-payout-amount">
                                                                $<?php echo number_format($job->cleaner_payout, 2); ?>
                                                            </p>
                                                            
                                                            <!-- Payout Breakdown -->
                                                            <div class="payout-breakdown">
                                                                <?php 
                                                                // Get the actual amount paid (host's payment)
                                                                $host_payment = $job->accepted_offer ? $job->accepted_offer->amount : $payment_amount;
                                                                
                                                                // Use pricing params from controller
                                                                $base_charge = $pricing_params['base_charge'];
                                                                $tax_percent = $pricing_params['tax_percent'];
                                                                $app_percent = $pricing_params['app_percent'];
                                                                
                                                                $tax_amount = ($host_payment * $tax_percent) / 100;
                                                                $app_amount = ($host_payment * $app_percent) / 100;
                                                                ?>
                                                                <div class="breakdown-item">
                                                                    <span class="breakdown-label">Base Amount:</span>
                                                                    <span class="breakdown-value">$<?php echo number_format($host_payment, 2); ?></span>
                                                                </div>
                                                                <div class="breakdown-item deduction">
                                                                    <span class="breakdown-label">- Base Fee:</span>
                                                                    <span class="breakdown-value">$<?php echo number_format($base_charge, 2); ?></span>
                                                                </div>
                                                                <div class="breakdown-item deduction">
                                                                    <span class="breakdown-label">- Tax (<?php echo number_format($tax_percent, 0); ?>%):</span>
                                                                    <span class="breakdown-value">$<?php echo number_format($tax_amount, 2); ?></span>
                                                                </div>
                                                                <div class="breakdown-item deduction">
                                                                    <span class="breakdown-label">- App Fee (<?php echo number_format($app_percent, 0); ?>%):</span>
                                                                    <span class="breakdown-value">$<?php echo number_format($app_amount, 2); ?></span>
                                                                </div>
                                                                <div class="breakdown-item total">
                                                                    <span class="breakdown-label">Cleaner Receives:</span>
                                                                    <span class="breakdown-value">$<?php echo number_format($job->cleaner_payout, 2); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="detail-item">
                                                        <label>Amount You Paid:</label>
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
                                            
                                            <!-- Review Information (from both parties) -->
                                            <?php if (!empty($job->host_review) || !empty($job->cleaner_review)): ?>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="reviews-section">
                                                        <h6 class="details-title" style="color: #667eea;">
                                                            <i class="fas fa-star me-2"></i>
                                                            Job Reviews
                                                        </h6>
                                                        
                                                        <div class="row">
                                                            <!-- Host's Review of Cleaner -->
                                                            <?php if (!empty($job->host_review)): ?>
                                                            <div class="col-md-6">
                                                                <div class="review-card host-review-card">
                                                                    <div class="review-header">
                                                                        <h6 class="review-title">
                                                                            <i class="fas fa-user-tie me-2"></i>
                                                                            Your Review of Cleaner
                                                                        </h6>
                                                                        <div class="review-rating">
                                                                            <span class="rating-stars">
                                                                                <?php 
                                                                                $overall = $job->host_review->overall_rating;
                                                                                for ($i = 1; $i <= 5; $i++) {
                                                                                    if ($i <= floor($overall)) {
                                                                                        echo '<i class="fas fa-star text-warning"></i>';
                                                                                    } elseif ($i - 0.5 <= $overall) {
                                                                                        echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                                                                    } else {
                                                                                        echo '<i class="far fa-star text-warning"></i>';
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </span>
                                                                            <span class="rating-value"><?php echo number_format($overall, 1); ?>/5</span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php if (!empty($job->host_review->public_comment)): ?>
                                                                    <div class="review-comment">
                                                                        <p><em>"<?php echo nl2br(htmlspecialchars($job->host_review->public_comment)); ?>"</em></p>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <div class="review-details">
                                                                        <div class="review-category">
                                                                            <span class="category-label">Professionalism:</span>
                                                                            <span class="category-rating"><?php echo $job->host_review->professionalism_rating; ?>/5</span>
                                                                        </div>
                                                                        <div class="review-category">
                                                                            <span class="category-label">Quality:</span>
                                                                            <span class="category-rating"><?php echo $job->host_review->quality_rating; ?>/5</span>
                                                                        </div>
                                                                        <div class="review-category">
                                                                            <span class="category-label">Communication:</span>
                                                                            <span class="category-rating"><?php echo $job->host_review->communication_rating; ?>/5</span>
                                                                        </div>
                                                                        <div class="review-category">
                                                                            <span class="category-label">Punctuality:</span>
                                                                            <span class="category-rating"><?php echo $job->host_review->punctuality_rating; ?>/5</span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="review-date">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-clock me-1"></i>
                                                                            <?php echo date('M j, Y g:i A', strtotime($job->host_review->created_at)); ?>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php else: ?>
                                                            <div class="col-md-6">
                                                                <div class="review-card no-review-card">
                                                                    <div class="no-review-content">
                                                                        <i class="fas fa-comment-slash fa-2x text-muted mb-2"></i>
                                                                        <p class="text-muted mb-0">You haven't reviewed this cleaner yet.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                            
                                                            <!-- Cleaner's Review of Host -->
                                                            <?php if (!empty($job->cleaner_review)): ?>
                                                            <div class="col-md-6">
                                                                <div class="review-card cleaner-review-card">
                                                                    <div class="review-header">
                                                                        <h6 class="review-title">
                                                                            <i class="fas fa-user-check me-2"></i>
                                                                            Cleaner's Review of You
                                                                        </h6>
                                                                        <div class="review-rating">
                                                                            <span class="rating-stars">
                                                                                <?php 
                                                                                $overall = $job->cleaner_review->overall_rating;
                                                                                for ($i = 1; $i <= 5; $i++) {
                                                                                    if ($i <= floor($overall)) {
                                                                                        echo '<i class="fas fa-star text-warning"></i>';
                                                                                    } elseif ($i - 0.5 <= $overall) {
                                                                                        echo '<i class="fas fa-star-half-alt text-warning"></i>';
                                                                                    } else {
                                                                                        echo '<i class="far fa-star text-warning"></i>';
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            </span>
                                                                            <span class="rating-value"><?php echo number_format($overall, 1); ?>/5</span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <?php if (!empty($job->cleaner_review->public_comment)): ?>
                                                                    <div class="review-comment">
                                                                        <p><em>"<?php echo nl2br(htmlspecialchars($job->cleaner_review->public_comment)); ?>"</em></p>
                                                                    </div>
                                                                    <?php endif; ?>
                                                                    
                                                                    <div class="review-details">
                                                                        <div class="review-category">
                                                                            <span class="category-label">Professionalism:</span>
                                                                            <span class="category-rating"><?php echo $job->cleaner_review->professionalism_rating; ?>/5</span>
                                                                        </div>
                                                                        <div class="review-category">
                                                                            <span class="category-label">Quality:</span>
                                                                            <span class="category-rating"><?php echo $job->cleaner_review->quality_rating; ?>/5</span>
                                                                        </div>
                                                                        <div class="review-category">
                                                                            <span class="category-label">Communication:</span>
                                                                            <span class="category-rating"><?php echo $job->cleaner_review->communication_rating; ?>/5</span>
                                                                        </div>
                                                                        <div class="review-category">
                                                                            <span class="category-label">Punctuality:</span>
                                                                            <span class="category-rating"><?php echo $job->cleaner_review->punctuality_rating; ?>/5</span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="review-date">
                                                                        <small class="text-muted">
                                                                            <i class="fas fa-clock me-1"></i>
                                                                            <?php echo date('M j, Y g:i A', strtotime($job->cleaner_review->created_at)); ?>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php else: ?>
                                                            <div class="col-md-6">
                                                                <div class="review-card no-review-card">
                                                                    <div class="no-review-content">
                                                                        <i class="fas fa-comment-slash fa-2x text-muted mb-2"></i>
                                                                        <p class="text-muted mb-0">The cleaner hasn't reviewed you yet.</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <!-- Recall Information (if job was recalled) -->
                                            <?php if (in_array($job->status, ['recalled', 'recall_settled']) && !empty($job->recall_reason)): ?>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <div class="recall-info-section">
                                                        <h6 class="details-title" style="color: #ff9800;">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Recall Information
                                                        </h6>
                                                        
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <?php if (!empty($job->recall_reason)): ?>
                                                                <div class="detail-item">
                                                                    <label>Recall Reason:</label>
                                                                    <p><?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($job->recall_reason))); ?></p>
                                                                </div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if (!empty($job->recall_severity)): ?>
                                                                <div class="detail-item">
                                                                    <label>Severity:</label>
                                                                    <p>
                                                                        <span class="badge bg-<?php echo $job->recall_severity === 'high' ? 'danger' : ($job->recall_severity === 'medium' ? 'warning' : 'info'); ?>">
                                                                            <?php echo ucfirst(htmlspecialchars($job->recall_severity)); ?>
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                                <?php endif; ?>
                                                                
                                                                <?php if (!empty($job->recalled_at)): ?>
                                                                <div class="detail-item">
                                                                    <label>Recalled On:</label>
                                                                    <p><?php echo date('M j, Y g:i A', strtotime($job->recalled_at)); ?></p>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <?php if (!empty($job->recall_details)): ?>
                                                                <div class="detail-item">
                                                                    <label>Recall Details:</label>
                                                                    <div class="recall-details-box" style="background: #fff3cd !important; border: 1px solid #ffc107 !important; border-radius: 8px; padding: 1rem; margin-bottom: 0;">
                                                                        <small><?php echo nl2br(htmlspecialchars($job->recall_details)); ?></small>
                                                                    </div>
                                                                </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Settlement Information (if job is settled) -->
                                                        <?php if ($job->status === 'recall_settled'): ?>
                                                        <div class="row mt-3">
                                                            <div class="col-12">
                                                                <div class="settlement-section">
                                                                    <h6 class="details-title" style="color: #28a745;">
                                                                        <i class="fas fa-check-circle me-2"></i>
                                                                        Recall Settlement
                                                                    </h6>
                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <?php if (!empty($job->admin_decision)): ?>
                                                                            <div class="detail-item">
                                                                                <label>Admin Decision:</label>
                                                                                <p class="text-success fw-bold">
                                                                                    <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($job->admin_decision))); ?>
                                                                                </p>
                                                                            </div>
                                                                            <?php endif; ?>
                                                                            
                                                                            <?php if (!empty($job->resolution_type)): ?>
                                                                            <div class="detail-item">
                                                                                <label>Resolution Type:</label>
                                                                                <p>
                                                                                    <?php echo ucfirst(str_replace('_', ' ', htmlspecialchars($job->resolution_type))); ?>
                                                                                </p>
                                                                            </div>
                                                                            <?php endif; ?>
                                                                            
                                                                            <?php if (!empty($job->recall_settled_at)): ?>
                                                                            <div class="detail-item">
                                                                                <label>Settled On:</label>
                                                                                <p><?php echo date('M j, Y g:i A', strtotime($job->recall_settled_at)); ?></p>
                                                                            </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        
                                                                        <div class="col-md-6">
                                                                            <?php if (!empty($job->admin_notes)): ?>
                                                                            <div class="detail-item">
                                                                                <label>Admin Notes:</label>
                                                                                <div class="admin-notes-box" style="background: #d4edda !important; border: 1px solid #28a745 !important; border-radius: 8px; padding: 1rem; margin-bottom: 0;">
                                                                                    <small><?php echo nl2br(htmlspecialchars($job->admin_notes)); ?></small>
                                                                                </div>
                                                                            </div>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                                                        <a href="<?php echo base_url('host/download_job_pdf/' . $job->id); ?>" 
                                                           class="btn btn-outline-success btn-sm"
                                                           title="Download Job Details as PDF">
                                                            <i class="fas fa-file-pdf me-1"></i>
                                                            Download PDF
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

/* Cleaner Payout Styling */
.cleaner-payout-box {
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    border-left: 4px solid #667eea;
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
}

.cleaner-payout-box label {
    color: #667eea !important;
    font-weight: 700;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.cleaner-payout-amount {
    color: #667eea !important;
    font-weight: 700 !important;
    font-size: 1.3rem !important;
    margin: 0.5rem 0 0.5rem 0 !important;
}

/* Payout Breakdown Styling */
.payout-breakdown {
    background: white;
    border-radius: 8px;
    padding: 0.75rem;
    margin-top: 0.75rem;
    border: 1px solid #e9ecef;
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.4rem 0;
    font-size: 0.9rem;
}

.breakdown-item.deduction {
    color: #dc3545;
}

.breakdown-item.deduction .breakdown-label {
    color: #dc3545;
}

.breakdown-item.deduction .breakdown-value {
    color: #dc3545;
}

.breakdown-item.total {
    border-top: 2px solid #667eea;
    margin-top: 0.5rem;
    padding-top: 0.75rem;
    font-weight: 700;
    font-size: 1rem;
}

.breakdown-item.total .breakdown-label {
    color: #667eea;
    font-weight: 700;
}

.breakdown-item.total .breakdown-value {
    color: #667eea;
    font-weight: 700;
    font-size: 1.1rem;
}

.breakdown-label {
    color: #495057;
    font-weight: 500;
}

.breakdown-value {
    color: #333;
    font-weight: 600;
}

/* Empty detail items (hide if no content) */
.detail-item:has(p:empty) {
    display: none;
}

/* Cleaner Info Box */
.cleaner-info-box {
    background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
    border-left: 4px solid #28a745;
    padding: 1rem;
    border-radius: 8px;
    margin: 1rem 0;
}

.cleaner-info-box label {
    color: #28a745 !important;
    font-weight: 700;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
}

.cleaner-name {
    color: #333 !important;
    font-weight: 600 !important;
    font-size: 1.1rem !important;
    margin: 0.25rem 0 !important;
}

.cleaner-username {
    display: inline-block;
    background: #28a74520;
    color: #28a745;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-weight: 500;
    font-size: 0.85rem;
    margin-top: 0.5rem;
}

.cleaner-username i {
    opacity: 0.7;
}

/* Recall Information Section */
.recall-info-section {
    background: linear-gradient(135deg, #fff8e6 0%, #fff3e0 100%);
    border-left: 4px solid #ff9800;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.settlement-section {
    background: linear-gradient(135deg, #e8f5e9 0%, #f1f8e9 100%);
    border-left: 4px solid #28a745;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.recall-info-section .details-title,
.settlement-section .details-title {
    background: transparent;
    border-bottom: 2px solid rgba(0, 0, 0, 0.1);
}

/* Recall Details and Admin Notes Boxes - PERMANENT (No auto-hide) */
.recall-details-box,
.admin-notes-box {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: static !important;
    animation: none !important;
    transition: none !important;
}

.recall-details-box small,
.admin-notes-box small {
    color: #333 !important;
    display: block !important;
    visibility: visible !important;
}

/* Review Section */
.reviews-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #fff 100%);
    border-left: 4px solid #667eea;
    border-radius: 8px;
    padding: 1.5rem;
    margin-top: 1rem;
}

.reviews-section .details-title {
    background: transparent;
    border-bottom: 2px solid rgba(102, 126, 234, 0.2);
}

.review-card {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    height: 100%;
    transition: all 0.3s ease;
}

.review-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.host-review-card {
    border-left: 3px solid #667eea;
}

.cleaner-review-card {
    border-left: 3px solid #28a745;
}

.no-review-card {
    background: #f8f9fa;
    border: 1px dashed #dee2e6;
    display: flex;
    align-items: center;
    justify-content: center;
}

.no-review-content {
    text-align: center;
    padding: 2rem 1rem;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid #e9ecef;
}

.review-title {
    font-size: 0.95rem;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.review-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rating-stars {
    font-size: 0.9rem;
}

.rating-value {
    font-weight: 700;
    color: #667eea;
    font-size: 0.95rem;
}

.review-comment {
    margin-bottom: 1rem;
    padding: 0.75rem;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border-radius: 8px;
}

.review-comment p {
    margin: 0;
    color: #495057;
    font-size: 0.9rem;
    line-height: 1.5;
}

.review-details {
    margin-bottom: 1rem;
}

.review-category {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.review-category:last-child {
    border-bottom: none;
}

.category-label {
    font-size: 0.85rem;
    color: #6c757d;
    font-weight: 500;
}

.category-rating {
    font-weight: 600;
    color: #667eea;
    font-size: 0.9rem;
}

.review-date {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f8f9fa;
}

.review-date small {
    font-size: 0.8rem;
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
