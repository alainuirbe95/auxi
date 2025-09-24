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
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body text-center py-4">
                            <h2 class="mb-3">
                                <i class="fas fa-home text-primary me-2"></i>
                                Welcome back, <?php echo htmlspecialchars($user_info->username); ?>!
                            </h2>
                            <p class="text-muted mb-0">Manage your cleaning jobs and track your progress</p>
                            
                            <?php if (!$marketplace_ready): ?>
                                <div class="alert alert-info mt-3" style="max-width: 600px; margin: 0 auto;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Marketplace Setup Required:</strong> The marketplace tables need to be created to access full functionality. 
                                    <a href="<?php echo base_url('test_db'); ?>" class="alert-link">Check database status</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['total_jobs']; ?></h3>
                            <p>Total Jobs</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card active">
                        <div class="stats-icon">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['active_jobs']; ?></h3>
                            <p>Active Jobs</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card completed">
                        <div class="stats-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-content">
                            <h3><?php echo $stats['completed_jobs']; ?></h3>
                            <p>Completed</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="stats-card earnings">
                        <div class="stats-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="stats-content">
                            <h3>$<?php echo number_format($stats['total_spent'], 2); ?></h3>
                            <p>Total Spent</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-bolt text-warning me-2"></i>
                                Quick Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if ($marketplace_ready): ?>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-modern btn-primary w-100">
                                            <i class="fas fa-plus-circle me-2"></i>
                                            Create New Job
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-modern btn-secondary w-100">
                                            <i class="fas fa-list me-2"></i>
                                            View All Jobs
                                        </a>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-modern btn-info w-100">
                                            <i class="fas fa-handshake me-2"></i>
                                            Review Offers
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <button class="btn btn-modern btn-primary w-100" disabled>
                                            <i class="fas fa-plus-circle me-2"></i>
                                            Create New Job
                                        </button>
                                        <small class="text-muted">Setup required</small>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <a href="<?php echo base_url('test_db'); ?>" class="btn btn-modern btn-secondary w-100">
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
                <div class="col-lg-8 mb-4">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock text-info me-2"></i>
                                Recent Jobs
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($recent_jobs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-modern">
                                        <thead>
                                            <tr>
                                                <th>Job Title</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                <th>Offers</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_jobs as $job): ?>
                                                <tr>
                                                    <td>
                                                        <div class="job-info">
                                                            <strong><?php echo htmlspecialchars($job->title); ?></strong>
                                                            <small class="text-muted d-block"><?php echo htmlspecialchars(substr($job->address, 0, 30)); ?>...</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="date-badge">
                                                            <?php 
                                                            // Use scheduled_date from database
                                                            $job_date = isset($job->scheduled_date) ? $job->scheduled_date : '';
                                                            if ($job_date) {
                                                                echo date('M j, Y', strtotime($job_date));
                                                            } else {
                                                                echo 'Not scheduled';
                                                            }
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="price-badge">
                                                            $<?php echo number_format($job->suggested_price, 2); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="offers-badge">
                                                            <?php echo $job->offer_count; ?> offers
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge status-<?php echo $job->status; ?>">
                                                            <?php echo ucfirst($job->status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="<?php echo base_url('host/job/' . $job->id); ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5>No jobs yet</h5>
                                    <p class="text-muted">Create your first cleaning job to get started!</p>
                                    <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-modern btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Create Job
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Pending Offers -->
                <div class="col-lg-4 mb-4">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-handshake text-success me-2"></i>
                                Pending Offers
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($pending_offers)): ?>
                                <?php foreach ($pending_offers as $offer): ?>
                                    <div class="offer-item">
                                        <div class="offer-header">
                                            <strong><?php echo htmlspecialchars($offer->job_title); ?></strong>
                                            <span class="offer-price">$<?php echo number_format($offer->amount, 2); ?></span>
                                        </div>
                                        <div class="offer-details">
                                            <small class="text-muted">
                                                From: <?php echo htmlspecialchars($offer->cleaner_username); ?>
                                            </small>
                                            <small class="text-muted d-block">
                                                <?php echo time_ago($offer->created_at); ?>
                                            </small>
                                        </div>
                                        <div class="offer-actions mt-2">
                                            <a href="<?php echo base_url('host/job/' . $offer->job_id); ?>" class="btn btn-sm btn-outline-primary">
                                                Review
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                                
                                <div class="text-center mt-3">
                                    <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-sm btn-modern btn-secondary">
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
    </section>
</div>

<style>
/* Content Layout */
.content {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
}

.container-fluid {
    max-width: 95% !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 20px !important;
}

/* Responsive Layout */
@media (max-width: 991.98px) {
    .container-fluid {
        max-width: 98% !important;
        padding: 0 15px !important;
    }
}

@media (max-width: 767.98px) {
    .container-fluid {
        max-width: 100% !important;
        padding: 0 10px !important;
    }
}

/* Table Styles */
.table-modern {
    background: transparent;
}

.table-modern thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table-modern tbody td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

/* Badge Styles */
.date-badge, .price-badge, .offers-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
    display: inline-block;
}

.price-badge {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.offers-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    white-space: nowrap;
    min-width: 60px;
    text-align: center;
}

.status-open {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.status-active {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.status-assigned {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.status-completed {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.status-cancelled {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

/* Modern Card Styles */
.modern-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.modern-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.modern-card .card-body {
    padding: 2rem;
}

/* Statistics Cards */
.stats-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    border: none;
    border-radius: 20px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stats-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-card.active::before {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-card.completed::before {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stats-card.earnings::before {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.stats-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
}

.stats-card.active .stats-icon {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stats-card.completed .stats-icon {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stats-card.earnings .stats-icon {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.stats-content h3 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.stats-content p {
    color: #6c757d;
    font-weight: 500;
    margin: 0;
}

/* Button Styles */
.btn-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.btn-modern.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern.btn-secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
}

.btn-modern.btn-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.3);
}

/* Table Styles */
.table-modern {
    background: transparent;
}

.table-modern thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem;
}

.table-modern tbody tr {
    border: none;
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}

.table-modern tbody td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

/* Badge Styles */
.date-badge, .price-badge, .offers-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.price-badge {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.offers-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-active {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

.status-assigned {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: white;
}

.status-completed {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.status-cancelled {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
}

/* Offer Items */
.offer-item {
    background: rgba(255,255,255,0.7);
    border-radius: 15px;
    padding: 1rem;
    margin-bottom: 1rem;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.offer-item:hover {
    background: rgba(255,255,255,0.9);
    transform: translateX(5px);
}

.offer-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.offer-price {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-weight: 600;
    font-size: 0.9rem;
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

/* Responsive */
@media (max-width: 768px) {
    .stats-card {
        margin-bottom: 1rem;
    }
    
    .modern-card .card-body {
        padding: 1.5rem;
    }
    
    .btn-modern {
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
    }
}
</style>
