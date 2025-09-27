<?php
// Helper function for time formatting
if (!function_exists('time_ago')) {
    function time_ago($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        return floor($time/31536000) . ' years ago';
    }
}

// Helper function to get time remaining for dispute window
if (!function_exists('dispute_time_remaining')) {
    function dispute_time_remaining($dispute_window_ends_at) {
        $end_time = strtotime($dispute_window_ends_at);
        $current_time = time();
        $remaining = $end_time - $current_time;
        
        if ($remaining <= 0) return 'Expired';
        
        $hours = floor($remaining / 3600);
        $minutes = floor(($remaining % 3600) / 60);
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        } else {
            return $minutes . 'm';
        }
    }
}
?>

<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-card">
                <div class="welcome-content">
                    <h2 class="welcome-title">
                        <i class="fas fa-home text-primary me-2"></i>
                        Welcome back, <?php echo htmlspecialchars($user_info->username); ?>!
                    </h2>
                    <p class="welcome-subtitle">Manage your cleaning jobs and track your progress</p>
                </div>
                
                <?php if (!$marketplace_ready): ?>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Marketplace Setup Required:</strong> The marketplace tables need to be created to access full functionality.
                        <a href="<?php echo base_url('test_db'); ?>" class="alert-link">Check database status</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['total_jobs']; ?></h3>
                    <p>Total Jobs</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card active">
                <div class="stat-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['active_jobs']; ?></h3>
                    <p>Active Jobs</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card disputes">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['live_disputes']; ?></h3>
                    <p>Live Disputes</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card completed">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['completed_jobs']; ?></h3>
                    <p>Completed</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card closed">
                <div class="stat-icon">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['closed_jobs']; ?></h3>
                    <p>Closed</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['pending_completed']; ?></h3>
                    <p>Pending Closure</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="action-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-bolt text-warning me-2"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <?php if ($marketplace_ready): ?>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-action btn-primary w-100">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Create New Job
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-action btn-secondary w-100">
                                    <i class="fas fa-list me-2"></i>
                                    View All Jobs
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-action btn-info w-100">
                                    <i class="fas fa-handshake me-2"></i>
                                    Review Offers
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo base_url('host/completed-jobs'); ?>" class="btn btn-action btn-success w-100">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Completed Jobs
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-action btn-primary w-100" disabled>
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Create New Job
                                </button>
                                <small class="text-muted">Setup required</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="<?php echo base_url('test_db'); ?>" class="btn btn-action btn-secondary w-100">
                                    <i class="fas fa-database me-2"></i>
                                    Setup Database
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Jobs -->
        <div class="col-lg-6 mb-4">
            <div class="content-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-info me-2"></i>
                        Recent Jobs
                    </h5>
                </div>
                <div class="card-body scrollable-content">
                    <?php if (!empty($recent_jobs)): ?>
                        <div class="job-list">
                            <?php foreach ($recent_jobs as $job): ?>
                                <div class="job-item">
                                    <div class="job-header">
                                        <h6 class="job-title"><?php echo htmlspecialchars($job->title); ?></h6>
                                        <span class="job-status status-<?php echo $job->status; ?>">
                                            <?php echo ucfirst($job->status); ?>
                                        </span>
                                    </div>
                                    <div class="job-details">
                                        <div class="job-info">
                                            <span class="job-date">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo isset($job->scheduled_date) ? date('M j, Y', strtotime($job->scheduled_date)) : 'Not scheduled'; ?>
                                            </span>
                                            <span class="job-price">
                                                <i class="fas fa-dollar-sign me-1"></i>
                                                $<?php echo number_format($job->suggested_price, 2); ?>
                                            </span>
                                        </div>
                                        <div class="job-actions">
                                            <a href="<?php echo base_url('host/job/' . $job->id); ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-sm btn-outline-primary">
                                View All Jobs
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5>No jobs yet</h5>
                            <p class="text-muted">Create your first cleaning job to get started!</p>
                            <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-primary">
                                <i class="fas fa-plus-circle me-2"></i>
                                Create Job
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pending Offers -->
        <div class="col-lg-6 mb-4">
            <div class="content-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-handshake text-success me-2"></i>
                        Pending Offers
                        <?php if ($stats['pending_offers'] > 0): ?>
                            <span class="badge badge-warning"><?php echo $stats['pending_offers']; ?></span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="scrollable-content">
                        <?php if (!empty($pending_offers)): ?>
                            <div class="offer-list">
                                <?php foreach ($pending_offers as $offer): ?>
                                    <div class="offer-item">
                                        <div class="offer-header">
                                            <h6 class="offer-title"><?php echo htmlspecialchars($offer->job_title); ?></h6>
                                            <span class="offer-price">$<?php echo number_format($offer->amount, 2); ?></span>
                                        </div>
                                        <div class="offer-details">
                                            <span class="offer-cleaner">
                                                <i class="fas fa-user me-1"></i>
                                                <?php echo htmlspecialchars($offer->cleaner_username); ?>
                                            </span>
                                            <span class="offer-time">
                                                <i class="fas fa-clock me-1"></i>
                                                <?php echo time_ago($offer->created_at); ?>
                                            </span>
                                        </div>
                                        <div class="offer-actions">
                                            <a href="<?php echo base_url('host/job/' . $offer->job_id); ?>" class="btn btn-sm btn-outline-primary">
                                                Review
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center mt-3">
                                <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-sm btn-outline-success">
                                    View All Offers
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-handshake fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No pending offers</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Completed Jobs -->
    <?php if (!empty($pending_completed)): ?>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="content-card urgent">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-circle text-warning me-2"></i>
                        Pending Completed Jobs - Action Required
                        <span class="badge badge-danger"><?php echo count($pending_completed); ?></span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="scrollable-content">
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            These jobs are completed and waiting for your action. You can dispute within 24 hours or close to release payment.
                        </div>
                        <div class="job-list">
                            <?php foreach ($pending_completed as $job): ?>
                                <div class="job-item urgent">
                                    <div class="job-header">
                                        <h6 class="job-title"><?php echo htmlspecialchars($job->title); ?></h6>
                                        <span class="job-time-remaining">
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo dispute_time_remaining($job->dispute_window_ends_at); ?>
                                        </span>
                                    </div>
                                    <div class="job-details">
                                        <div class="job-info">
                                            <span class="job-cleaner">
                                                <i class="fas fa-user me-1"></i>
                                                <?php echo htmlspecialchars($job->cleaner_username ?: 'Unknown Cleaner'); ?>
                                            </span>
                                            <span class="job-completed">
                                                <i class="fas fa-check me-1"></i>
                                                Completed <?php echo time_ago($job->completed_at); ?>
                                            </span>
                                            <span class="job-amount">
                                                <i class="fas fa-dollar-sign me-1"></i>
                                                $<?php echo number_format($job->final_price ?: $job->accepted_price, 2); ?>
                                            </span>
                                        </div>
                                        <div class="job-actions">
                                            <a href="<?php echo base_url('host/completed-jobs'); ?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-gavel me-1"></i>
                                                Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?php echo base_url('host/completed-jobs'); ?>" class="btn btn-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Review All Completed Jobs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
/* Enhanced Dashboard Styles */
.container-fluid {
    max-width: 95% !important;
    margin: 0 auto !important;
    padding: 0 20px;
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

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border: none;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    margin-bottom: 2rem;
}

.welcome-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    color: #6c757d;
    font-size: 1.1rem;
    margin: 0;
}

/* Statistics Cards */
.stat-card {
    background: white;
    border: none;
    border-radius: 15px;
    padding: 1.5rem 1rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #667eea;
}

.stat-card.active::before { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.disputes::before { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); }
.stat-card.completed::before { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.stat-card.closed::before { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.stat-card.pending::before { background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%); }

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.stat-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.2rem;
}

.stat-card.active .stat-icon { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-card.disputes .stat-icon { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); }
.stat-card.completed .stat-icon { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); }
.stat-card.closed .stat-icon { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.stat-card.pending .stat-icon { background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%); }

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #333;
}

.stat-content p {
    color: #6c757d;
    font-weight: 500;
    margin: 0;
    font-size: 0.9rem;
}

/* Action Card */
.action-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.action-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.action-card .card-body {
    padding: 2rem;
}

.btn-action {
    border: none;
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.btn-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
}

.btn-action.btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.btn-action.btn-secondary { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.btn-action.btn-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); }
.btn-action.btn-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }

/* Content Cards */
.content-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
    height: 500px;
    display: flex;
    flex-direction: column;
}

.content-card.urgent {
    border: 2px solid #ffa726;
}

.content-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1.5rem;
    flex-shrink: 0;
    height: auto;
}

.content-card .card-body {
    padding: 0;
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 0;
    height: calc(500px - 80px); /* Subtract header height */
}

.scrollable-content {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 1.5rem;
    min-height: 0;
    height: 100%;
    max-height: calc(500px - 80px - 3rem); /* Subtract header and padding */
    -webkit-overflow-scrolling: touch;
}

/* Custom Scrollbar */
.scrollable-content::-webkit-scrollbar {
    width: 6px;
}

.scrollable-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.scrollable-content::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
}

.scrollable-content::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

/* Firefox scrollbar */
.scrollable-content {
    scrollbar-width: thin;
    scrollbar-color: #667eea #f1f1f1;
}

/* Job Items */
.job-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.job-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.job-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.job-item.urgent {
    border-left: 4px solid #ffa726;
    background: #fff3cd;
}

.job-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.job-title {
    font-weight: 600;
    color: #333;
    margin: 0;
    font-size: 1rem;
}

.job-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.job-info {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.job-info span {
    font-size: 0.85rem;
    color: #6c757d;
}

.job-status, .job-time-remaining {
    padding: 0.25rem 0.5rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.job-status.status-open { background: #f093fb; color: white; }
.job-status.status-assigned { background: #4facfe; color: white; }
.job-status.status-in_progress { background: #ffa726; color: white; }
.job-status.status-completed { background: #43e97b; color: white; }
.job-status.status-disputed { background: #ff6b6b; color: white; }
.job-status.status-closed { background: #6c757d; color: white; }
.job-status.status-expired { background: #ffc107; color: white; }
.job-status.status-price_adjustment_requested { background: #9c27b0; color: white; }

.job-time-remaining {
    background: #ffa726;
    color: white;
}

/* Offer Items */
.offer-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.offer-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 1rem;
    transition: all 0.3s ease;
}

.offer-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.offer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.offer-title {
    font-weight: 600;
    color: #333;
    margin: 0;
    font-size: 1rem;
}

.offer-price {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.9rem;
}

.offer-details {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.offer-details span {
    font-size: 0.85rem;
    color: #6c757d;
}

.offer-actions {
    text-align: right;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-state i {
    opacity: 0.5;
}

/* Badges */
.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.badge-warning {
    background: #ffa726;
    color: white;
}

.badge-danger {
    background: #dc3545;
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        max-width: 100%;
        padding: 0 15px;
    }
    
    .stat-card {
        margin-bottom: 1rem;
    }
    
    .content-card {
        height: auto;
        margin-bottom: 2rem;
    }
    
    .scrollable-content {
        max-height: none;
    }
    
    .job-details {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .job-info {
        flex-direction: column;
        gap: 0.25rem;
    }
}

/* Force scrolling to work */
.scrollable-content {
    position: relative;
}

.scrollable-content::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity: 0.3;
}
</style>

<script>
$(document).ready(function() {
    // Force scrollable content to work properly
    $('.scrollable-content').each(function() {
        var $this = $(this);
        var scrollHeight = this.scrollHeight;
        var clientHeight = this.clientHeight;
        
        if (scrollHeight > clientHeight) {
            $this.css('overflow-y', 'auto');
        }
    });
});
</script>