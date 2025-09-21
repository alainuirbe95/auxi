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

.activity-time {
    font-size: 0.8rem;
    color: #718096;
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

        <!-- Pending Users -->
        <div class="stat-card pending">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number"><?php echo isset($pending_users_count) ? $pending_users_count : '0'; ?></h3>
                    <p class="stat-label">Pending Reviews</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/pending_users'); ?>" class="stat-link">
                Review now <i class="fas fa-arrow-right"></i>
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

        <!-- Banned Users -->
        <div class="stat-card banned">
            <div class="stat-header">
                <div>
                    <h3 class="stat-number"><?php echo isset($banned_users) ? $banned_users : '0'; ?></h3>
                    <p class="stat-label">Banned Users</p>
                </div>
                <div class="stat-icon">
                    <i class="fas fa-user-times"></i>
                </div>
            </div>
            <a href="<?php echo base_url('admin/users'); ?>" class="stat-link">
                Manage banned <i class="fas fa-arrow-right"></i>
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

    <!-- Recent Activity -->
    <div class="recent-activity">
        <h3>
            <i class="fas fa-history"></i>
            Recent Activity
        </h3>
        <div class="activity-item">
            <div class="activity-icon new-user">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">New user registration</div>
                <div class="activity-time">2 minutes ago</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon pending">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">User account pending review</div>
                <div class="activity-time">5 minutes ago</div>
            </div>
        </div>
        <div class="activity-item">
            <div class="activity-icon approved">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="activity-content">
                <div class="activity-title">User account approved</div>
                <div class="activity-time">1 hour ago</div>
            </div>
        </div>
    </div>
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
