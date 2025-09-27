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
                        <i class="fas fa-broom text-success me-2"></i>
                        Welcome back, <?php echo htmlspecialchars($user_info->username); ?>!
                    </h2>
                    <p class="welcome-subtitle">Track your cleaning jobs, earnings, and manage your work</p>
                </div>
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
                    <h3><?php echo $stats['disputed_jobs']; ?></h3>
                    <p>Disputed Jobs</p>
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
                    <p>Completed Jobs</p>
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
                    <p>Closed Jobs</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="stat-card earnings">
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-content">
                    <h3>$<?php echo number_format($stats['potential_earnings'], 2); ?></h3>
                    <p>Potential Earnings</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Current Jobs -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-tasks text-primary me-2"></i>
                            Current Jobs
                        </h4>
                        <span class="badge badge-primary"><?php echo count($assigned_jobs); ?></span>
                    </div>
                    <a href="<?php echo base_url('cleaner/assigned_jobs'); ?>" class="btn btn-sm btn-outline-primary">
                        View All
                    </a>
                </div>
                <div class="card-body scrollable-content">
                    <?php if (!empty($assigned_jobs)): ?>
                        <?php foreach (array_slice($assigned_jobs, 0, 3) as $job): ?>
                            <div class="job-item">
                                <div class="job-info">
                                    <h6 class="job-title"><?php echo htmlspecialchars($job->title); ?></h6>
                                    <div class="job-details">
                                        <span class="job-price">$<?php echo number_format($job->suggested_price, 2); ?></span>
                                        <span class="job-date">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php 
                                            if (isset($job->scheduled_date) && isset($job->scheduled_time)) {
                                                $datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                                                echo date('M j, Y g:i A', strtotime($datetime));
                                            } else {
                                                echo 'Flexible';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="job-status">
                                    <span class="badge status-<?php echo $job->status; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $job->status)); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No current jobs assigned</p>
                            <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn btn-primary">
                                Browse Available Jobs
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pending Disputes -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                            Disputed Jobs
                        </h4>
                        <span class="badge badge-warning"><?php echo count($pending_disputes); ?></span>
                    </div>
                    <a href="<?php echo base_url('cleaner/my-disputed-jobs'); ?>" class="btn btn-sm btn-outline-warning">
                        View All
                    </a>
                </div>
                <div class="card-body scrollable-content">
                    <?php if (!empty($pending_disputes)): ?>
                        <?php foreach (array_slice($pending_disputes, 0, 3) as $dispute): ?>
                            <div class="dispute-item">
                                <div class="dispute-info">
                                    <h6 class="dispute-title"><?php echo htmlspecialchars($dispute->title); ?></h6>
                                    <div class="dispute-details">
                                        <span class="dispute-amount">$<?php echo number_format($dispute->suggested_price, 2); ?></span>
                                        <span class="dispute-host">
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($dispute->host_name); ?>
                                        </span>
                                    </div>
                                    <div class="dispute-time">
                                        <i class="fas fa-clock me-1"></i>
                                        Disputed <?php echo time_ago($dispute->disputed_at); ?>
                                    </div>
                                </div>
                                <div class="dispute-status">
                                    <span class="badge badge-warning">Under Review</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">No disputed jobs</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Pending Price Adjustments -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-dollar-sign text-info me-2"></i>
                            Price Adjustments
                        </h4>
                        <span class="badge badge-info"><?php echo count($pending_price_adjustments); ?></span>
                    </div>
                    <a href="<?php echo base_url('cleaner/price-adjustment-disputes'); ?>" class="btn btn-sm btn-outline-info">
                        View All
                    </a>
                </div>
                <div class="card-body scrollable-content">
                    <?php if (!empty($pending_price_adjustments)): ?>
                        <?php foreach (array_slice($pending_price_adjustments, 0, 3) as $adjustment): ?>
                            <div class="adjustment-item">
                                <div class="adjustment-info">
                                    <h6 class="adjustment-title"><?php echo htmlspecialchars($adjustment->job_title); ?></h6>
                                    <div class="adjustment-details">
                                        <span class="adjustment-amount">$<?php echo number_format($adjustment->requested_amount, 2); ?></span>
                                        <span class="adjustment-original">
                                            (Original: $<?php echo number_format($adjustment->suggested_price, 2); ?>)
                                        </span>
                                    </div>
                                    <div class="adjustment-time">
                                        <i class="fas fa-clock me-1"></i>
                                        Requested <?php echo time_ago($adjustment->created_at); ?>
                                    </div>
                                </div>
                                <div class="adjustment-status">
                                    <span class="badge badge-info">Pending</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <p class="text-muted">No pending price adjustments</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Recent Jobs -->
        <div class="col-lg-6 mb-4">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="header-content">
                        <h4 class="card-title">
                            <i class="fas fa-history text-secondary me-2"></i>
                            Recent Jobs
                        </h4>
                        <span class="badge badge-secondary"><?php echo count($recent_jobs); ?></span>
                    </div>
                    <a href="<?php echo base_url('cleaner/completed'); ?>" class="btn btn-sm btn-outline-secondary">
                        View All
                    </a>
                </div>
                <div class="card-body scrollable-content">
                    <?php if (!empty($recent_jobs)): ?>
                        <?php foreach ($recent_jobs as $job): ?>
                            <div class="recent-job-item">
                                <div class="job-info">
                                    <h6 class="job-title"><?php echo htmlspecialchars($job->title); ?></h6>
                                    <div class="job-details">
                                        <span class="job-price">$<?php echo number_format($job->suggested_price, 2); ?></span>
                                        <span class="job-host">
                                            <i class="fas fa-user me-1"></i>
                                            <?php echo htmlspecialchars($job->host_name); ?>
                                        </span>
                                    </div>
                                    <div class="job-time">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo time_ago($job->created_at); ?>
                                    </div>
                                </div>
                                <div class="job-status">
                                    <span class="badge status-<?php echo $job->status; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $job->status)); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <i class="fas fa-history fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent jobs</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

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

/* Welcome Card */
.welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.welcome-content {
    text-align: center;
}

.welcome-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.welcome-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Statistics Cards */
.stat-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card.total::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.active::before { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.stat-card.disputes::before { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
.stat-card.completed::before { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
.stat-card.closed::before { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.stat-card.earnings::before { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }

.stat-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.stat-card.total .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-card.active .stat-icon { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.stat-card.disputes .stat-icon { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
.stat-card.completed .stat-icon { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
.stat-card.closed .stat-icon { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); }
.stat-card.earnings .stat-icon { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }

.stat-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 100%;
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 800;
    margin: 0 0 0.5rem 0;
    color: #333;
    line-height: 1.1;
}

.stat-content p {
    margin: 0;
    color: #666;
    font-weight: 600;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    line-height: 1.2;
}

/* Dashboard Cards */
.dashboard-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.card-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
}

.badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
}

.badge-primary { background: #007bff; color: white; }
.badge-warning { background: #ffc107; color: #212529; }
.badge-info { background: #17a2b8; color: white; }
.badge-secondary { background: #6c757d; color: white; }

.card-body {
    padding: 1.5rem;
    flex: 1;
    overflow: hidden;
}

.scrollable-content {
    max-height: 400px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 10px;
}

.scrollable-content::-webkit-scrollbar {
    width: 6px;
}

.scrollable-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.scrollable-content::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

/* Job Items */
.job-item, .dispute-item, .adjustment-item, .recent-job-item {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.job-item:hover, .dispute-item:hover, .adjustment-item:hover, .recent-job-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.job-title, .dispute-title, .adjustment-title {
    margin: 0 0 0.5rem 0;
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}

.job-details, .dispute-details, .adjustment-details {
    display: flex;
    gap: 1rem;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.job-price, .dispute-amount, .adjustment-amount {
    font-weight: 700;
    color: #28a745;
}

.job-date, .dispute-time, .adjustment-time {
    font-size: 0.8rem;
    color: #999;
}

.job-host, .dispute-host {
    font-size: 0.9rem;
    color: #666;
}

.adjustment-original {
    font-size: 0.8rem;
    color: #999;
}

/* Status Badges */
.status-assigned { background: #17a2b8; color: white; }
.status-in_progress { background: #ffc107; color: #212529; }
.status-completed { background: #28a745; color: white; }
.status-disputed { background: #dc3545; color: white; }
.status-closed { background: #6c757d; color: white; }

/* No Data State */
.no-data {
    text-align: center;
    padding: 3rem 1rem;
    color: #666;
}

.no-data i {
    margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .stat-card {
        padding: 1.5rem 1rem;
        margin-bottom: 1rem;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .stat-content h3 {
        font-size: 2rem;
    }
    
    .stat-content p {
        font-size: 0.85rem;
    }
    
    .welcome-title {
        font-size: 1.5rem;
    }
    
    .card-header {
        padding: 1rem;
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .header-content {
        width: 100%;
        justify-content: space-between;
    }
    
    .job-item, .dispute-item, .adjustment-item, .recent-job-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .job-details, .dispute-details, .adjustment-details {
        flex-direction: column;
        gap: 0.25rem;
    }
}

@media (max-width: 576px) {
    .stat-card {
        padding: 1.25rem 0.75rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .stat-content h3 {
        font-size: 1.75rem;
    }
    
    .stat-content p {
        font-size: 0.8rem;
    }
}
</style>