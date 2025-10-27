<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

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

<div class="container-fluid">
  <!-- Modern Header Section -->
  <div class="modern-header-section">
    <div class="header-content">
      <h1 class="page-title">
        <i class="fas fa-user-times mr-3"></i>
        Rejected Users
      </h1>
      <p class="page-subtitle">
        Manage users who were rejected by administrators
      </p>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row mb-4">
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-danger">
        <div class="stats-icon">
          <i class="fas fa-user-times"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['total_rejected']); ?></h3>
          <p class="stats-label">Total Rejected</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-calendar-day"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['rejected_today']); ?></h3>
          <p class="stats-label">Rejected Today</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-orange">
        <div class="stats-icon">
          <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['rejected_this_week']); ?></h3>
          <p class="stats-label">This Week</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-info">
        <div class="stats-icon">
          <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['rejected_this_month']); ?></h3>
          <p class="stats-label">This Month</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Role Breakdown -->
  <div class="row mb-4">
    <div class="col-md-4 mb-3">
      <div class="stats-card stats-card-success">
        <div class="stats-icon">
          <i class="fas fa-user"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['by_role']['cleaner']); ?></h3>
          <p class="stats-label">Cleaners</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="stats-card stats-card-primary">
        <div class="stats-icon">
          <i class="fas fa-user-tie"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['by_role']['host']); ?></h3>
          <p class="stats-label">Hosts</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-md-4 mb-3">
      <div class="stats-card stats-card-gray">
        <div class="stats-icon">
          <i class="fas fa-user-shield"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['by_role']['admin']); ?></h3>
          <p class="stats-label">Admins</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Rejected Users Table -->
  <div class="modern-card">
    <div class="modern-card-header">
      <h3 class="modern-card-title">
        <i class="fas fa-list mr-2"></i>
        Rejected Users List
      </h3>
      <div class="modern-card-tools">
        <button onclick="refreshTable()" class="btn btn-modern btn-secondary">
          <i class="fas fa-sync-alt mr-1"></i>
          Refresh
        </button>
      </div>
    </div>

    <div class="modern-card-body p-0">
      <?php if (empty($rejected_users)): ?>
        <div class="empty-state">
          <i class="fas fa-user-times fa-3x mb-3 text-muted"></i>
          <h4>No Rejected Users</h4>
          <p class="text-muted">There are currently no rejected users in the system.</p>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Rejected</th>
                <th>Rejected By</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rejected_users as $user): ?>
                <tr class="user-row">
                  <td>
                    <div class="user-info">
                      <div class="user-avatar">
                        <?php if (!empty($user->avatar)): ?>
                          <img src="<?php echo base_url('uploads/avatars/' . $user->avatar); ?>" alt="Avatar">
                        <?php else: ?>
                          <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                          </div>
                        <?php endif; ?>
                      </div>
                      <div class="user-details">
                        <h5 class="user-name"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></h5>
                        <p class="user-username">@<?php echo htmlspecialchars($user->username); ?></p>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="contact-info">
                      <p class="contact-email">
                        <i class="fas fa-envelope mr-1"></i>
                        <?php echo htmlspecialchars($user->email); ?>
                      </p>
                      <?php if (!empty($user->phone)): ?>
                        <p class="contact-phone">
                          <i class="fas fa-phone mr-1"></i>
                          <?php echo htmlspecialchars($user->phone); ?>
                        </p>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <?php
                    $role_class = '';
                    $role_text = '';
                    switch($user->auth_level) {
                      case 3:
                        $role_class = 'badge-success';
                        $role_text = 'Cleaner';
                        break;
                      case 6:
                        $role_class = 'badge-primary';
                        $role_text = 'Host';
                        break;
                      case 9:
                        $role_class = 'badge-danger';
                        $role_text = 'Administrator';
                        break;
                      default:
                        $role_class = 'badge-secondary';
                        $role_text = 'Unknown';
                    }
                    ?>
                    <span class="badge <?php echo $role_class; ?>"><?php echo $role_text; ?></span>
                  </td>
                  <td>
                    <div class="rejection-info">
                      <p class="rejection-date">
                        <i class="fas fa-calendar-times mr-1"></i>
                        <?php echo date('M j, Y', strtotime($user->rejected_at ?? $user->created_at)); ?>
                      </p>
                      <p class="rejection-time">
                        <small class="text-muted"><?php echo time_ago($user->rejected_at ?? $user->created_at); ?></small>
                      </p>
                    </div>
                  </td>
                  <td>
                    <span class="rejected-by">
                      <i class="fas fa-user-shield mr-1"></i>
                      <?php echo htmlspecialchars($user->rejected_by ?? 'System'); ?>
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="<?php echo base_url('admin/view_user/' . $user->user_id); ?>" 
                         class="btn btn-sm btn-modern btn-info" 
                         title="View Details">
                        <i class="fas fa-eye"></i>
                      </a>
                      <button onclick="restoreUser(<?php echo $user->user_id; ?>, '<?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>')" 
                              class="btn btn-sm btn-modern btn-success" 
                              title="Restore User">
                        <i class="fas fa-undo"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
/* Modern Header Section */
.modern-header-section {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  border-radius: 20px;
  padding: 2.5rem 2rem;
  margin-bottom: 2rem;
  color: white;
  position: relative;
  overflow: hidden;
}

.modern-header-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
  opacity: 0.3;
}

.header-content {
  position: relative;
  z-index: 1;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.page-subtitle {
  font-size: 1rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.95;
}

/* Modern Statistics Cards */
.stats-card {
  background: white;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  border: none;
  height: 100%;
}

.stats-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.stats-card-danger {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
  color: white;
}

.stats-card-warning {
  background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
  color: white;
}

.stats-card-orange {
  background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
  color: white;
}

.stats-card-info {
  background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
  color: white;
}

.stats-card-success {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
}

.stats-card-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.stats-card-gray {
  background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);
  color: white;
}

.stats-icon {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 2rem;
  opacity: 0.3;
}

.stats-content {
  position: relative;
  z-index: 1;
}

.stats-number {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  line-height: 1;
}

.stats-label {
  font-size: 0.9rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.95;
  font-weight: 500;
}

.stats-decoration {
  position: absolute;
  bottom: -15px;
  right: -15px;
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
}

/* Modern Card */
.modern-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  height: 100%;
}

.modern-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modern-card-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
  color: #495057;
}

.modern-card-tools {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.modern-card-body {
  padding: 1.5rem;
}

/* Modern Table */
.modern-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.modern-table thead {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.modern-table thead th {
  padding: 1rem;
  font-weight: 600;
  color: #495057;
  border-bottom: 2px solid #dee2e6;
  text-align: left;
}

.modern-table tbody td {
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
}

.modern-table tbody tr:hover {
  background-color: #f8f9fa;
}

/* User Info */
.user-info {
  display: flex;
  align-items: center;
}

.user-avatar {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  margin-right: 12px;
  overflow: hidden;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  color: #6c757d;
  font-size: 18px;
}

.user-details h5 {
  margin: 0;
  font-weight: 600;
  color: #2c3e50;
  font-size: 1rem;
}

.user-details p {
  margin: 0;
  color: #6c757d;
  font-size: 0.85rem;
}

.contact-info p {
  margin: 0.25rem 0;
  font-size: 0.9rem;
  color: #495057;
}

.rejection-info p {
  margin: 0.25rem 0;
  font-size: 0.9rem;
}

.rejection-date {
  color: #dc3545;
  font-weight: 600;
}

.rejected-by {
  color: #6c757d;
  font-size: 0.9rem;
}

.action-buttons {
  display: flex;
  gap: 0.5rem;
}

.btn-modern {
  border-radius: 8px;
  padding: 0.4rem 0.8rem;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-modern:hover {
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
}

.empty-state h4 {
  color: #6c757d;
  margin-bottom: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
  .modern-header-section {
    padding: 2rem 1.5rem;
  }
  
  .page-title {
    font-size: 1.75rem;
  }
  
  .stats-card {
    padding: 1.25rem;
  }
  
  .stats-number {
    font-size: 1.75rem;
  }
  
  .modern-table tbody td {
    padding: 0.8rem 0.5rem;
  }
  
  .action-buttons {
    flex-direction: column;
  }
}
</style>

<script>
$(document).ready(function() {
    // Refresh table function
    window.refreshTable = function() {
        location.reload();
    };

    // Restore user function
    window.restoreUser = function(userId, userName) {
        if (confirm('Are you sure you want to restore user "' + userName + '"?\n\nThis will unban their account and allow them to access the platform again.')) {
            window.location.href = '<?php echo base_url('admin/restore_user/'); ?>' + userId;
        }
    };

    // Add animation to table rows on load
    $('.modern-table tbody tr').each(function(index) {
        $(this).css('opacity', '0').delay(index * 100).animate({
            opacity: 1
        }, 500);
    });
});
</script>
