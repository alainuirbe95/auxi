<?php
// Helper function for time ago
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

<style>
/* Enhanced Dashboard Styles */
.dashboard-welcome {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.dashboard-welcome::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.welcome-content {
    position: relative;
    z-index: 2;
}

.welcome-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.welcome-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: none;
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
    background: var(--card-gradient);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.stat-card:hover::before {
    height: 6px;
}

.stat-card.total { --card-gradient: linear-gradient(135deg, #667eea, #764ba2); }
.stat-card.pending { --card-gradient: linear-gradient(135deg, #f093fb, #f5576c); }
.stat-card.active { --card-gradient: linear-gradient(135deg, #4facfe, #00f2fe); }
.stat-card.banned { --card-gradient: linear-gradient(135deg, #fa709a, #fee140); }
.stat-card.success { --card-gradient: linear-gradient(135deg, #43e97b, #38f9d7); }
.stat-card.info { --card-gradient: linear-gradient(135deg, #4facfe, #00f2fe); }

.stat-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    background: var(--card-gradient);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.stat-number {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
    line-height: 1;
}

.stat-label {
    color: #718096;
    font-size: 0.9rem;
    font-weight: 500;
    margin: 0.5rem 0 0 0;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    margin-top: 1rem;
    transition: all 0.3s ease;
}

.stat-link:hover {
    color: #764ba2;
    text-decoration: none;
    transform: translateX(5px);
}

.stat-link i {
    margin-left: 0.5rem;
    transition: transform 0.3s ease;
}

.stat-link:hover i {
    transform: translateX(3px);
}

.pending-alert {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    border: none;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 5px 20px rgba(252, 182, 159, 0.3);
    border-left: 5px solid #f093fb;
}

.pending-alert .alert-icon {
    font-size: 1.5rem;
    color: #d69e2e;
    margin-right: 1rem;
}

.pending-alert .alert-content {
    flex: 1;
}

.pending-alert .alert-content h5 {
    color: #744210;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.pending-alert .alert-content p {
    color: #975a16;
    margin-bottom: 1rem;
}

.pending-alert .btn {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    border: none;
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
    color: white;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
}

.pending-alert .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(240, 147, 251, 0.4);
    color: white;
    text-decoration: none;
}

.quick-actions {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.quick-actions h3 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.quick-actions h3 i {
    margin-right: 0.75rem;
    color: #667eea;
}

.action-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-btn {
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 1.5rem;
    text-decoration: none;
    color: #4a5568;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.action-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.5s ease;
}

.action-btn:hover::before {
    left: 100%;
}

.action-btn:hover {
    border-color: #667eea;
    color: #667eea;
    text-decoration: none;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
}

.action-btn i {
    font-size: 2rem;
    margin-bottom: 0.75rem;
    color: #667eea;
}

.action-btn span {
    font-weight: 500;
    font-size: 0.9rem;
}

.recent-activity {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.recent-activity h3 {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
}

.recent-activity h3 i {
    margin-right: 0.75rem;
    color: #667eea;
}

.activity-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #e2e8f0;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    font-size: 1rem;
    color: white;
}

.activity-icon.new-user { background: linear-gradient(135deg, #4facfe, #00f2fe); }
.activity-icon.pending { background: linear-gradient(135deg, #f093fb, #f5576c); }
.activity-icon.approved { background: linear-gradient(135deg, #a8edea, #fed6e3); }

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 500;
    color: #2d3748;
    margin-bottom: 0.25rem;
}

.activity-description {
    font-size: 0.85rem;
    color: #4a5568;
    margin-bottom: 0.25rem;
}

.activity-time {
    font-size: 0.8rem;
    color: #718096;
}

/* Priority Section Styles */
.priority-section {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
}

.priority-section .section-title {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    font-size: 1.25rem;
}

.priority-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid;
    height: 100%;
}

.priority-card.pending {
    border-left-color: #f093fb;
}

.priority-card.danger {
    border-left-color: #f5576c;
}

.priority-card.info {
    border-left-color: #4facfe;
}

.priority-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.priority-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
}

.priority-card.pending .priority-icon {
    background: linear-gradient(135deg, #f093fb, #f5576c);
}

.priority-card.danger .priority-icon {
    background: linear-gradient(135deg, #f5576c, #fa709a);
}

.priority-card.info .priority-icon {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.priority-content h4 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.priority-content p {
    color: #718096;
    margin-bottom: 1rem;
    font-weight: 500;
}

/* System Alerts Styles */
.system-alerts .alert {
    border: none;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    margin-bottom: 1rem;
    box-shadow: 0 3px 15px rgba(0,0,0,0.1);
}

.system-alerts .alert-icon {
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.alert-warning .alert-icon {
    background: linear-gradient(135deg, #f093fb, #f5576c);
}

.alert-danger .alert-icon {
    background: linear-gradient(135deg, #f5576c, #fa709a);
}

.alert-info .alert-icon {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
}

.alert-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.alert-message {
    color: #6c757d;
    margin-bottom: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dashboard-welcome {
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .welcome-title {
        font-size: 2rem;
    }
    
    .welcome-subtitle {
        font-size: 1rem;
    }
    
    .dashboard-stats {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .action-grid {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 2rem;
    }
    
    .priority-section .row {
        flex-direction: column;
    }
    
    .priority-section .col-md-4 {
        margin-bottom: 1rem;
    }
    
    .priority-card {
        padding: 1rem;
    }
    
    .priority-content h4 {
        font-size: 1.5rem;
    }
    
    .system-alerts .alert {
        padding: 0.75rem 1rem;
    }
    
    .system-alerts .alert-icon {
        width: 30px;
        height: 30px;
        font-size: 1rem;
    }
}
</style>

<!-- Dashboard Content -->
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="dashboard-welcome">
        <div class="welcome-content">
            <h1 class="welcome-title">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Welcome back, <?php echo $this->session->userdata('username') ?: 'Admin'; ?>!
            </h1>
            <p class="welcome-subtitle">
                Here's what's happening with your EasyClean platform today.
            </p>
            <div class="row">
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span><?php echo date('l, F j, Y'); ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span><?php echo date('g:i A'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Users Alert -->
    <?php if (isset($pending_users_count) && $pending_users_count > 0): ?>
        <div class="pending-alert alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <div class="alert-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="alert-content">
                    <h5>New User Registrations Awaiting Review</h5>
                    <p>You have <?php echo $pending_users_count; ?> user(s) waiting for approval. Review their information and activate their accounts.</p>
                    <a href="<?php echo base_url('admin/pending_users'); ?>" class="btn">
                        <i class="fas fa-eye mr-2"></i>
                        Review Now
                    </a>
                </div>
                <button type="button" class="close ml-3" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- System Alerts -->
    <?php if (!empty($system_alerts)): ?>
        <div class="system-alerts mb-4">
            <?php foreach ($system_alerts as $alert): ?>
                <div class="alert alert-<?php echo $alert['type']; ?> alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="alert-icon me-3">
                            <i class="<?php echo $alert['icon']; ?>"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="alert-title mb-1"><?php echo $alert['title']; ?></h6>
                            <p class="alert-message mb-0"><?php echo $alert['message']; ?></p>
                        </div>
                        <div class="alert-actions">
                            <a href="<?php echo $alert['action_url']; ?>" class="btn btn-sm btn-outline-<?php echo $alert['type']; ?>">
                                <?php echo $alert['action_text']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Priority Data Section -->
    <div class="priority-section mb-4">
        <h3 class="section-title">
            <i class="fas fa-exclamation-circle text-warning me-2"></i>
            Priority Actions Required
        </h3>
        <div class="row">
            <div class="col-md-4">
                <div class="priority-card pending">
                    <div class="priority-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="priority-content">
                        <h4><?php echo isset($pending_users_count) ? $pending_users_count : '0'; ?></h4>
                        <p>Pending User Approvals</p>
                        <a href="<?php echo base_url('admin/pending_users'); ?>" class="btn btn-sm btn-warning">
                            Review Now
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="priority-card danger">
                    <div class="priority-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="priority-content">
                        <h4><?php echo isset($job_stats['dispute_count']) ? $job_stats['dispute_count'] : '0'; ?></h4>
                        <p>Active Disputes</p>
                        <a href="<?php echo base_url('admin/disputes'); ?>" class="btn btn-sm btn-danger">
                            View Disputes
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="priority-card info">
                    <div class="priority-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="priority-content">
                        <h4><?php echo isset($payment_stats['by_status']['failed']) ? $payment_stats['by_status']['failed'] : '0'; ?></h4>
                        <p>Failed Payments</p>
                        <a href="<?php echo base_url('admin/payments'); ?>" class="btn btn-sm btn-info">
                            View Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="dashboard-stats">
        <!-- Total Users -->
        <div class="stat-card total">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number"><?php echo isset($total_users) ? $total_users : '0'; ?></h3>
                    <p class="stat-label">Total Users</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/users'); ?>" class="stat-link">
                View all users <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Active Users -->
        <div class="stat-card active">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number"><?php echo isset($active_users) ? $active_users : '0'; ?></h3>
                    <p class="stat-label">Active Users</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/users'); ?>" class="stat-link">
                View active <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Total Jobs -->
        <div class="stat-card total">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number"><?php echo isset($job_stats['total_jobs']) ? $job_stats['total_jobs'] : '0'; ?></h3>
                    <p class="stat-label">Total Jobs</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/jobs'); ?>" class="stat-link">
                View all jobs <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Total Offers -->
        <div class="stat-card active">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number"><?php echo isset($offer_stats['total_offers']) ? $offer_stats['total_offers'] : '0'; ?></h3>
                    <p class="stat-label">Total Offers</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/offers'); ?>" class="stat-link">
                View all offers <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Total Revenue -->
        <div class="stat-card success">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number">$<?php echo isset($payment_stats['total_amount']) ? number_format($payment_stats['total_amount'], 2) : '0.00'; ?></h3>
                    <p class="stat-label">Total Revenue</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/payments'); ?>" class="stat-link">
                View payments <i class="fas fa-arrow-right"></i>
            </a>
        </div>

        <!-- Recent Users -->
        <div class="stat-card info">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number"><?php echo isset($recent_users) ? $recent_users : '0'; ?></h3>
                    <p class="stat-label">New Users (30 days)</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/users'); ?>" class="stat-link">
                View recent <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h3>
            <i class="fas fa-bolt"></i>
            Quick Actions
        </h3>
        <div class="action-grid">
            <a href="<?php echo base_url('admin/create_user'); ?>" class="action-btn">
                <i class="fas fa-user-plus"></i>
                <span>Create New User</span>
            </a>
            <a href="<?php echo base_url('admin/pending_users'); ?>" class="action-btn">
                <i class="fas fa-user-clock"></i>
                <span>Review Pending Users</span>
            </a>
            <a href="<?php echo base_url('admin/users'); ?>" class="action-btn">
                <i class="fas fa-users"></i>
                <span>Manage All Users</span>
            </a>
            <a href="<?php echo base_url('admin/media'); ?>" class="action-btn">
                <i class="fas fa-images"></i>
                <span>Media Management</span>
            </a>
        </div>
    </div>

    <!-- Recent Activity & Data -->
    <div class="row">
        <div class="col-md-6">
    <div class="recent-activity">
        <h3>
            <i class="fas fa-history"></i>
                    Recent User Activity
        </h3>
        <?php if (!empty($recent_activity)): ?>
            <?php foreach ($recent_activity as $activity): ?>
                <div class="activity-item">
                    <div class="activity-icon <?php echo $activity['icon_class']; ?>">
                        <i class="<?php echo $activity['icon']; ?>"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title"><?php echo $activity['title']; ?></div>
                        <div class="activity-description"><?php echo $activity['description']; ?></div>
                        <div class="activity-time"><?php echo time_ago($activity['time']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-4">
                <div class="text-muted">
                    <i class="fas fa-inbox fa-2x mb-3"></i>
                            <p>No recent user activity</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
        </div>
        
        <div class="col-md-6">
            <div class="recent-activity">
                <h3>
                    <i class="fas fa-briefcase"></i>
                    Recent Jobs
                </h3>
                <?php if (!empty($recent_jobs)): ?>
                    <?php foreach ($recent_jobs as $job): ?>
                        <div class="activity-item">
                            <div class="activity-icon new-user">
                                <i class="fas fa-broom"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title"><?php echo htmlspecialchars($job->title); ?></div>
                                <div class="activity-description">
                                    Host: <?php echo htmlspecialchars($job->host_username); ?> | 
                                    Status: <span class="badge badge-<?php echo $job->status === 'completed' ? 'success' : ($job->status === 'in_progress' ? 'warning' : 'info'); ?>"><?php echo ucfirst($job->status); ?></span>
                                </div>
                                <div class="activity-time"><?php echo time_ago($job->created_at); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <div class="text-muted">
                            <i class="fas fa-briefcase fa-2x mb-3"></i>
                            <p>No recent jobs</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Offers -->
    <?php if (!empty($recent_offers)): ?>
    <div class="recent-activity mt-4">
        <h3>
            <i class="fas fa-handshake"></i>
            Recent Offers
        </h3>
        <div class="row">
            <?php foreach ($recent_offers as $offer): ?>
                <div class="col-md-6 mb-3">
                    <div class="activity-item">
                        <div class="activity-icon approved">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">$<?php echo number_format($offer->amount, 2); ?> - <?php echo htmlspecialchars($offer->job_title); ?></div>
                            <div class="activity-description">
                                Cleaner: <?php echo htmlspecialchars($offer->cleaner_username); ?> | 
                                Status: <span class="badge badge-<?php echo $offer->status === 'accepted' ? 'success' : ($offer->status === 'pending' ? 'warning' : 'secondary'); ?>"><?php echo ucfirst($offer->status); ?></span>
                            </div>
                            <div class="activity-time"><?php echo time_ago($offer->created_at); ?></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Add animation to stat cards on load
    $('.stat-card').each(function(index) {
        $(this).css('opacity', '0').delay(index * 100).animate({
            opacity: 1
        }, 500);
    });
    
    // Add hover effects to action buttons
    $('.action-btn').hover(
        function() {
            $(this).find('i').addClass('fa-bounce');
        },
        function() {
            $(this).find('i').removeClass('fa-bounce');
        }
    );
});
</script>
