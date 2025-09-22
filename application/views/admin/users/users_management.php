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

<div class="container-fluid">
  <!-- Modern Header Section -->
  <div class="modern-header-section">
    <div class="header-content">
      <h1 class="page-title">
        <i class="fas fa-users mr-3"></i>
        Users Management
      </h1>
      <p class="page-subtitle">
        Manage and monitor all users in your EasyClean platform
      </p>
    </div>
  </div>

  <!-- Modern Statistics Cards -->
  <div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-primary">
        <div class="stats-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats->total_users ?? 0); ?></h3>
          <p class="stats-label">Total Users</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-success">
        <div class="stats-icon">
          <i class="fas fa-user-check"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats->active_users ?? 0); ?></h3>
          <p class="stats-label">Active Users</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-danger">
        <div class="stats-icon">
          <i class="fas fa-user-slash"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats->banned_users ?? 0); ?></h3>
          <p class="stats-label">Banned Users</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-user-plus"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats->recent_users ?? 0); ?></h3>
          <p class="stats-label">New This Month</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Main Users Table Card -->
  <div class="modern-card">
    <div class="modern-card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h3 class="card-title">
            <i class="fas fa-table mr-2"></i>
            Users Table
          </h3>
        </div>
        <div class="card-actions">
          <a href="<?php echo base_url('admin/create_user'); ?>" class="btn btn-modern btn-success">
            <i class="fas fa-user-plus mr-2"></i>
            Create New User
          </a>
        </div>
      </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="modern-filter-section">
      <?php echo form_open('admin/users', array('method' => 'GET', 'id' => 'filterForm')); ?>
      
      <div class="filter-row">
        <!-- Search Input -->
        <div class="filter-group">
          <label for="search" class="filter-label">
            <i class="fas fa-search mr-1"></i>
            Search
          </label>
          <div class="search-input-container">
            <input type="text" 
                   class="modern-input" 
                   id="search" 
                   name="search" 
                   value="<?php echo htmlspecialchars(isset($filters['search']) ? $filters['search'] : ''); ?>" 
                   placeholder="Search users...">
            <div class="search-icon">
              <i class="fas fa-search"></i>
            </div>
          </div>
        </div>
        
        <!-- Status Filter -->
        <div class="filter-group">
          <label for="status" class="filter-label">
            <i class="fas fa-filter mr-1"></i>
            Status
          </label>
          <select class="modern-select" id="status" name="status">
            <option value="">All Status</option>
            <option value="active" <?php echo (isset($filters['status']) && $filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
            <option value="banned" <?php echo (isset($filters['status']) && $filters['status'] == 'banned') ? 'selected' : ''; ?>>Banned</option>
            <option value="pending" <?php echo (isset($filters['status']) && $filters['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
          </select>
        </div>
        
        <!-- Role Filter -->
        <div class="filter-group">
          <label for="role" class="filter-label">
            <i class="fas fa-user-tag mr-1"></i>
            Role
          </label>
          <select class="modern-select" id="role" name="role">
            <option value="">All Roles</option>
            <option value="3" <?php echo (isset($filters['role']) && $filters['role'] == '3') ? 'selected' : ''; ?>>Cleaner</option>
            <option value="6" <?php echo (isset($filters['role']) && $filters['role'] == '6') ? 'selected' : ''; ?>>Host</option>
            <option value="9" <?php echo (isset($filters['role']) && $filters['role'] == '9') ? 'selected' : ''; ?>>Administrator</option>
          </select>
        </div>
        
        <!-- Sort Filter -->
        <div class="filter-group">
          <label for="sort" class="filter-label">
            <i class="fas fa-sort mr-1"></i>
            Sort By
          </label>
          <select class="modern-select" id="sort" name="sort">
            <option value="created_at_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'created_at_desc') ? 'selected' : ''; ?>>Newest First</option>
            <option value="created_at_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'created_at_asc') ? 'selected' : ''; ?>>Oldest First</option>
            <option value="username_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'username_asc') ? 'selected' : ''; ?>>Username A-Z</option>
            <option value="username_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'username_desc') ? 'selected' : ''; ?>>Username Z-A</option>
            <option value="last_login_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'last_login_desc') ? 'selected' : ''; ?>>Last Login</option>
          </select>
        </div>
        
        <!-- Filter Buttons -->
        <div class="filter-actions">
          <button type="submit" class="btn btn-modern btn-primary">
            <i class="fas fa-search mr-1"></i>
            Apply Filters
          </button>
          <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-modern btn-outline">
            <i class="fas fa-times mr-1"></i>
            Clear
          </a>
        </div>
      </div>
      
      <?php echo form_close(); ?>
    </div>

    <!-- Users Table -->
    <div class="modern-table-container">
      <?php if (empty($users)): ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-users"></i>
          </div>
          <h4>No Users Found</h4>
          <p>No users match your current filter criteria.</p>
          <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-modern btn-primary">
            <i class="fas fa-refresh mr-2"></i>
            Reset Filters
          </a>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Contact</th>
                <th>Role</th>
                <th>Status</th>
                <th>Last Login</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
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
                        $role_class = 'badge-cleaner';
                        $role_text = 'Cleaner';
                        break;
                      case 6:
                        $role_class = 'badge-host';
                        $role_text = 'Host';
                        break;
                      case 9:
                        $role_class = 'badge-admin';
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
                    <?php if ($user->banned == '1'): ?>
                      <span class="status-badge status-banned">
                        <i class="fas fa-ban mr-1"></i>
                        Banned
                      </span>
                    <?php elseif ($user->pending_verification == '1'): ?>
                      <span class="status-badge status-pending">
                        <i class="fas fa-clock mr-1"></i>
                        Pending
                      </span>
                    <?php else: ?>
                      <span class="status-badge status-active">
                        <i class="fas fa-check-circle mr-1"></i>
                        Active
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="last-login">
                      <?php if (!empty($user->last_login)): ?>
                        <p class="login-time"><?php echo date('M j, Y', strtotime($user->last_login)); ?></p>
                        <p class="login-duration"><?php echo time_ago($user->last_login); ?></p>
                      <?php else: ?>
                        <p class="no-login">Never</p>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="<?php echo base_url('admin/view_user/' . $user->user_id); ?>" 
                         class="btn btn-sm btn-outline-info" 
                         title="View Details">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="<?php echo base_url('admin/edit_user/' . $user->user_id); ?>" 
                         class="btn btn-sm btn-outline-warning" 
                         title="Edit User">
                        <i class="fas fa-edit"></i>
                      </a>
                      <?php if ($user->banned == '1'): ?>
                        <button onclick="unbanUser(<?php echo $user->user_id; ?>, '<?php echo htmlspecialchars($user->username); ?>')" 
                                class="btn btn-sm btn-outline-success" 
                                title="Unban User">
                          <i class="fas fa-unlock"></i>
                        </button>
                      <?php else: ?>
                        <button onclick="banUser(<?php echo $user->user_id; ?>, '<?php echo htmlspecialchars($user->username); ?>')" 
                                class="btn btn-sm btn-outline-danger" 
                                title="Ban User">
                          <i class="fas fa-ban"></i>
                        </button>
                      <?php endif; ?>
                      <?php if ($user->user_id != $this->session->userdata('user_id')): ?>
                        <button onclick="deleteUser(<?php echo $user->user_id; ?>, '<?php echo htmlspecialchars($user->username); ?>')" 
                                class="btn btn-sm btn-outline-danger" 
                                title="Delete User">
                          <i class="fas fa-trash"></i>
                        </button>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
      <div class="modern-pagination">
        <div class="pagination-info">
          <span>Showing <?php echo $pagination['offset'] + 1; ?> to <?php echo min($pagination['offset'] + $pagination['limit'], $pagination['total_records']); ?> of <?php echo $pagination['total_records']; ?> users</span>
        </div>
        
        <nav aria-label="Users pagination">
          <ul class="pagination">
            <?php if ($pagination['current_page'] > 1): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/users?' . http_build_query(array_merge($_GET, array('page' => $pagination['current_page'] - 1)))); ?>">
                  <i class="fas fa-chevron-left"></i>
                </a>
              </li>
            <?php endif; ?>
            
            <?php
            $start_page = max(1, $pagination['current_page'] - 2);
            $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
            
            for ($i = $start_page; $i <= $end_page; $i++):
            ?>
              <li class="page-item <?php echo ($i == $pagination['current_page']) ? 'active' : ''; ?>">
                <a class="page-link" href="<?php echo base_url('admin/users?' . http_build_query(array_merge($_GET, array('page' => $i)))); ?>">
                  <?php echo $i; ?>
                </a>
              </li>
            <?php endfor; ?>
            
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/users?' . http_build_query(array_merge($_GET, array('page' => $pagination['current_page'] + 1)))); ?>">
                  <i class="fas fa-chevron-right"></i>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</div>

<style>
/* Modern Header Section */
.modern-header-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 20px;
  padding: 3rem 2rem;
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
  background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
  opacity: 0.3;
}

.header-content {
  position: relative;
  z-index: 1;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.page-subtitle {
  font-size: 1.1rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.9;
}

/* Modern Statistics Cards */
.stats-card {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  border: none;
  height: 100%;
}

.stats-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.stats-card-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.stats-card-success {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
}

.stats-card-danger {
  background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
  color: white;
}

.stats-card-warning {
  background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
  color: white;
}

.stats-icon {
  position: absolute;
  top: 1.5rem;
  right: 1.5rem;
  font-size: 2.5rem;
  opacity: 0.3;
}

.stats-content {
  position: relative;
  z-index: 1;
}

.stats-number {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  line-height: 1;
}

.stats-label {
  font-size: 1rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.9;
  font-weight: 500;
}

.stats-decoration {
  position: absolute;
  bottom: -20px;
  right: -20px;
  width: 100px;
  height: 100px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
}

/* Modern Card */
.modern-card {
  background: white;
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  border: none;
  overflow: hidden;
}

.modern-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 2rem;
  border-bottom: 1px solid #dee2e6;
}

.modern-card-header .card-title {
  margin: 0;
  font-size: 1.5rem;
  font-weight: 600;
  color: #2c3e50;
}

.modern-card-header .card-subtitle {
  margin: 0.5rem 0 0 0;
  color: #6c757d;
  font-size: 0.95rem;
}

.card-actions .btn-modern {
  border-radius: 10px;
  padding: 0.75rem 1.5rem;
  font-weight: 500;
  transition: all 0.3s ease;
}

.card-actions .btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Modern Filter Section */
.modern-filter-section {
  background: #f8f9fa;
  padding: 2rem;
  border-bottom: 1px solid #dee2e6;
}

.filter-row {
  display: flex;
  gap: 1.5rem;
  align-items: end;
  flex-wrap: wrap;
}

.filter-group {
  flex: 1;
  min-width: 200px;
}

.filter-label {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.9rem;
}

.modern-input, .modern-select {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  font-size: 0.95rem;
  transition: all 0.3s ease;
  background: white;
}

.modern-input:focus, .modern-select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.search-input-container {
  position: relative;
}

.search-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6c757d;
  pointer-events: none;
}

.filter-actions {
  display: flex;
  gap: 0.75rem;
  align-items: end;
}

.btn-modern {
  border-radius: 10px;
  padding: 0.75rem 1.5rem;
  font-weight: 500;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-modern.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-modern.btn-outline {
  background: white;
  color: #6c757d;
  border: 2px solid #e9ecef;
}

.btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  text-decoration: none;
  color: inherit;
}

/* Modern Table */
.modern-table-container {
  padding: 0;
}

.modern-table {
  margin: 0;
  border-collapse: separate;
  border-spacing: 0;
  width: 100%;
}

.modern-table thead th {
  background: #f8f9fa;
  border: none;
  font-weight: 600;
  color: #495057;
  padding: 1.5rem 1rem;
  border-bottom: 2px solid #dee2e6;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.modern-table tbody tr {
  border-bottom: 1px solid #f1f3f4;
  transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
  transform: translateY(-1px);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.modern-table tbody td {
  padding: 1.5rem 1rem;
  border: none;
  vertical-align: middle;
}

/* User Info */
.user-info {
  display: flex;
  align-items: center;
}

.user-avatar {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  margin-right: 1rem;
  overflow: hidden;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}

.user-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-placeholder {
  color: #6c757d;
  font-size: 1.2rem;
}

.user-details h5 {
  margin: 0;
  font-weight: 600;
  color: #2c3e50;
  font-size: 1rem;
}

.user-details p {
  margin: 0.25rem 0 0 0;
  color: #6c757d;
  font-size: 0.85rem;
}

/* Contact Info */
.contact-info p {
  margin: 0.25rem 0;
  font-size: 0.9rem;
  color: #495057;
}

/* Badges */
.badge {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.badge-cleaner {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
}

.badge-host {
  background: linear-gradient(135deg, #007bff, #6610f2);
  color: white;
}

.badge-admin {
  background: linear-gradient(135deg, #dc3545, #e83e8c);
  color: white;
}

/* Status Badges */
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.4rem 0.8rem;
  border-radius: 15px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.status-active {
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
}

.status-banned {
  background: linear-gradient(135deg, #dc3545, #c82333);
  color: white;
}

.status-pending {
  background: linear-gradient(135deg, #ffc107, #e0a800);
  color: #212529;
}

/* Last Login */
.last-login p {
  margin: 0.25rem 0;
  font-size: 0.9rem;
}

.login-time {
  color: #495057;
  font-weight: 500;
}

.login-duration {
  color: #6c757d;
  font-size: 0.8rem;
}

.no-login {
  color: #6c757d;
  font-style: italic;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.action-buttons .btn {
  border-radius: 8px;
  padding: 0.5rem 0.75rem;
  font-size: 0.8rem;
  transition: all 0.3s ease;
  border: 2px solid;
}

.action-buttons .btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 4rem 2rem;
}

.empty-icon {
  font-size: 4rem;
  color: #dee2e6;
  margin-bottom: 1.5rem;
}

.empty-state h4 {
  color: #6c757d;
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.empty-state p {
  color: #adb5bd;
  margin-bottom: 2rem;
}

/* Pagination */
.modern-pagination {
  padding: 2rem;
  background: #f8f9fa;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.pagination-info {
  color: #6c757d;
  font-size: 0.9rem;
  font-weight: 500;
}

.pagination {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  gap: 0.5rem;
}

.page-item {
  margin: 0;
}

.page-link {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 1rem;
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 10px;
  color: #495057;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s ease;
  min-width: 45px;
  height: 45px;
}

.page-link:hover {
  background: #667eea;
  border-color: #667eea;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
  text-decoration: none;
}

.page-item.active .page-link {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
  color: white;
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.page-link i {
  font-size: 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .modern-header-section {
    padding: 2rem 1rem;
  }
  
  .page-title {
    font-size: 2rem;
  }
  
  .filter-row {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filter-group {
    min-width: auto;
  }
  
  .filter-actions {
    justify-content: center;
  }
  
  .modern-table {
    font-size: 0.85rem;
  }
  
  .action-buttons {
    justify-content: center;
  }
  
  .modern-pagination {
    flex-direction: column;
    text-align: center;
  }
  
  .pagination {
    justify-content: center;
  }
}

@media (max-width: 576px) {
  .stats-card {
    padding: 1.5rem;
  }
  
  .stats-number {
    font-size: 2rem;
  }
  
  .user-info {
    flex-direction: column;
    text-align: center;
  }
  
  .user-avatar {
    margin-right: 0;
    margin-bottom: 0.5rem;
  }
}
</style>

<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('#status, #role, #sort').on('change', function() {
        $('#filterForm').submit();
    });
    
    // Add loading state to form submission
    $('#filterForm').on('submit', function() {
        $('.btn-modern').prop('disabled', true);
        $('.btn-modern i').removeClass().addClass('fas fa-spinner fa-spin');
    });
    
    // Add animation to table rows
    $('.modern-table tbody tr').each(function(index) {
        $(this).css('opacity', '0').delay(index * 50).animate({
            opacity: 1
        }, 500);
    });
});

// User action functions
function banUser(userId, username) {
    if (confirm('Are you sure you want to ban user "' + username + '"?\n\nThis will prevent them from accessing the platform.')) {
        window.location.href = '<?php echo base_url('admin/ban_user/'); ?>' + userId;
    }
}

function unbanUser(userId, username) {
    if (confirm('Are you sure you want to unban user "' + username + '"?\n\nThis will restore their access to the platform.')) {
        window.location.href = '<?php echo base_url('admin/unban_user/'); ?>' + userId;
    }
}

function deleteUser(userId, username) {
    if (confirm('Are you sure you want to delete user "' + username + '"?\n\nThis action cannot be undone and will permanently remove all user data.')) {
        window.location.href = '<?php echo base_url('admin/delete_user/'); ?>' + userId;
    }
}
</script>