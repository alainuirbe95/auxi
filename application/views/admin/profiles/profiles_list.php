<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modern Admin Profiles List v2.0 -->
<div class="profiles-container">
  
  <!-- Page Header -->
  <div class="page-header">
    <div class="header-content">
      <div class="header-title-section">
        <h1 class="page-title">
          <i class="fas fa-id-card"></i>
          User Profiles
        </h1>
        <p class="page-subtitle">Manage and review all user profiles</p>
      </div>
      
      <div class="header-actions">
        <a href="<?php echo base_url('admin/profile-statistics'); ?>" class="btn btn-info">
          <i class="fas fa-chart-bar"></i> View Statistics
        </a>
      </div>
    </div>
  </div>

  <!-- Quick Stats Cards -->
  <div class="stats-overview">
    <div class="stat-card stat-total">
      <div class="stat-icon">
        <i class="fas fa-users"></i>
      </div>
      <div class="stat-details">
        <div class="stat-number"><?php echo $pagination['total_records']; ?></div>
        <div class="stat-label">Total Profiles</div>
      </div>
    </div>
    
    <div class="stat-card stat-pending">
      <div class="stat-icon">
        <i class="fas fa-clock"></i>
      </div>
      <div class="stat-details">
        <div class="stat-number"><?php echo $pending_verification_count; ?></div>
        <div class="stat-label">Pending Verification</div>
      </div>
    </div>
    
    <div class="stat-card stat-cleaners">
      <div class="stat-icon">
        <i class="fas fa-broom"></i>
      </div>
      <div class="stat-details">
        <div class="stat-number"><?php echo count(array_filter($profiles, function($p) { return $p->auth_level == 3; })); ?></div>
        <div class="stat-label">Cleaner Profiles</div>
      </div>
    </div>
    
    <div class="stat-card stat-hosts">
      <div class="stat-icon">
        <i class="fas fa-home"></i>
      </div>
      <div class="stat-details">
        <div class="stat-number"><?php echo count(array_filter($profiles, function($p) { return $p->auth_level == 6; })); ?></div>
        <div class="stat-label">Host Profiles</div>
      </div>
    </div>
  </div>

  <!-- Filters Card -->
  <div class="filters-card">
    <div class="card-header">
      <h3><i class="fas fa-filter"></i> Filters & Search</h3>
      <button type="button" class="collapse-btn" id="toggleFilters">
        <i class="fas fa-chevron-down"></i>
      </button>
    </div>
    <div class="card-content" id="filtersContent">
      <form method="get" action="<?php echo base_url('admin/profiles'); ?>" id="filterForm">
        <div class="filters-grid">
          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-search"></i> Search
            </label>
            <input type="text" 
                   name="search" 
                   class="filter-input" 
                   placeholder="Name, email, username..." 
                   value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>">
          </div>
          
          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-user-tag"></i> Role
            </label>
            <select name="role" class="filter-select">
              <option value="">All Roles</option>
              <option value="3" <?php echo (isset($filters['role']) && $filters['role'] == 3) ? 'selected' : ''; ?>>Cleaner</option>
              <option value="6" <?php echo (isset($filters['role']) && $filters['role'] == 6) ? 'selected' : ''; ?>>Host</option>
              <option value="9" <?php echo (isset($filters['role']) && $filters['role'] == 9) ? 'selected' : ''; ?>>Admin</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-shield-alt"></i> Verification
            </label>
            <select name="verification_status" class="filter-select">
              <option value="">All Status</option>
              <option value="verified" <?php echo (isset($filters['verification_status']) && $filters['verification_status'] == 'verified') ? 'selected' : ''; ?>>Verified</option>
              <option value="pending" <?php echo (isset($filters['verification_status']) && $filters['verification_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
              <option value="unverified" <?php echo (isset($filters['verification_status']) && $filters['verification_status'] == 'unverified') ? 'selected' : ''; ?>>Unverified</option>
              <option value="rejected" <?php echo (isset($filters['verification_status']) && $filters['verification_status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-eye"></i> Visibility
            </label>
            <select name="is_public" class="filter-select">
              <option value="">All</option>
              <option value="1" <?php echo (isset($filters['is_public']) && $filters['is_public'] == '1') ? 'selected' : ''; ?>>Public</option>
              <option value="0" <?php echo (isset($filters['is_public']) && $filters['is_public'] == '0') ? 'selected' : ''; ?>>Private</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-sort"></i> Sort By
            </label>
            <select name="sort_by" class="filter-select">
              <option value="created_at" <?php echo (isset($filters['sort_by']) && $filters['sort_by'] == 'created_at') ? 'selected' : ''; ?>>Date Created</option>
              <option value="username" <?php echo (isset($filters['sort_by']) && $filters['sort_by'] == 'username') ? 'selected' : ''; ?>>Username</option>
              <option value="completion" <?php echo (isset($filters['sort_by']) && $filters['sort_by'] == 'completion') ? 'selected' : ''; ?>>Profile Completion</option>
              <option value="rating" <?php echo (isset($filters['sort_by']) && $filters['sort_by'] == 'rating') ? 'selected' : ''; ?>>Rating</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label class="filter-label">
              <i class="fas fa-arrow-down-up-across-line"></i> Order
            </label>
            <select name="sort_order" class="filter-select">
              <option value="DESC" <?php echo (isset($filters['sort_order']) && $filters['sort_order'] == 'DESC') ? 'selected' : ''; ?>>Descending</option>
              <option value="ASC" <?php echo (isset($filters['sort_order']) && $filters['sort_order'] == 'ASC') ? 'selected' : ''; ?>>Ascending</option>
            </select>
          </div>
        </div>
        
        <div class="filter-actions">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Apply Filters
          </button>
          <a href="<?php echo base_url('admin/profiles'); ?>" class="btn btn-outline">
            <i class="fas fa-times"></i> Clear All
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Profiles Table -->
  <div class="profiles-table-card">
    <div class="card-header">
      <h3><i class="fas fa-list"></i> Profiles List (<?php echo $pagination['total_records']; ?>)</h3>
    </div>
    <div class="card-content">
      <?php if (empty($profiles)): ?>
        <div class="empty-state">
          <i class="fas fa-users-slash"></i>
          <h3>No Profiles Found</h3>
          <p>No profiles match your current filter criteria</p>
          <a href="<?php echo base_url('admin/profiles'); ?>" class="btn btn-primary">
            <i class="fas fa-refresh"></i> Clear Filters
          </a>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>Email</th>
                <th>Profile Completion</th>
                <th>Rating</th>
                <th>Verification</th>
                <th>Visibility</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($profiles as $profile): ?>
                <tr class="profile-row">
                  <td>
                    <div class="user-cell">
                      <div class="user-avatar">
                        <?php if (!empty($profile->profile_picture_url)): ?>
                          <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile">
                        <?php else: ?>
                          <div class="avatar-placeholder">
                            <?php echo strtoupper(substr($profile->first_name, 0, 1) . substr($profile->last_name, 0, 1)); ?>
                          </div>
                        <?php endif; ?>
                      </div>
                      <div class="user-info">
                        <div class="user-name"><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></div>
                        <div class="user-username">@<?php echo htmlspecialchars($profile->username); ?></div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <?php
                    $role_badges = array(
                        3 => '<span class="badge badge-cleaner"><i class="fas fa-broom"></i> Cleaner</span>',
                        6 => '<span class="badge badge-host"><i class="fas fa-home"></i> Host</span>',
                        9 => '<span class="badge badge-admin"><i class="fas fa-crown"></i> Admin</span>'
                    );
                    echo isset($role_badges[$profile->auth_level]) ? $role_badges[$profile->auth_level] : '<span class="badge badge-secondary">Unknown</span>';
                    ?>
                  </td>
                  <td>
                    <div class="email-cell">
                      <i class="fas fa-envelope"></i>
                      <span><?php echo htmlspecialchars($profile->email); ?></span>
                    </div>
                  </td>
                  <td>
                    <div class="completion-cell">
                      <div class="completion-bar-container">
                        <div class="completion-bar">
                          <div class="completion-fill <?php echo $profile->completion_percentage >= 50 ? 'success' : 'warning'; ?>" 
                               style="width: <?php echo $profile->completion_percentage; ?>%"></div>
                        </div>
                        <div class="completion-text"><?php echo $profile->completion_percentage; ?>%</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <?php if ($profile->average_rating > 0): ?>
                      <div class="rating-cell">
                        <i class="fas fa-star"></i>
                        <span><?php echo number_format($profile->average_rating, 1); ?></span>
                        <small>(<?php echo $profile->total_reviews; ?>)</small>
                      </div>
                    <?php else: ?>
                      <span class="text-muted">No reviews</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php
                    $verification_badges = array(
                        'verified' => '<span class="badge badge-verified"><i class="fas fa-check-circle"></i> Verified</span>',
                        'pending' => '<span class="badge badge-pending"><i class="fas fa-clock"></i> Pending</span>',
                        'unverified' => '<span class="badge badge-unverified"><i class="fas fa-question-circle"></i> Unverified</span>',
                        'rejected' => '<span class="badge badge-rejected"><i class="fas fa-times-circle"></i> Rejected</span>'
                    );
                    echo isset($verification_badges[$profile->verification_status]) ? $verification_badges[$profile->verification_status] : $verification_badges['unverified'];
                    ?>
                  </td>
                  <td>
                    <?php if ($profile->is_public == 1): ?>
                      <span class="badge badge-public"><i class="fas fa-eye"></i> Public</span>
                    <?php else: ?>
                      <span class="badge badge-private"><i class="fas fa-eye-slash"></i> Private</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="date-cell">
                      <i class="fas fa-calendar"></i>
                      <span><?php echo date('M j, Y', strtotime($profile->created_at)); ?></span>
                    </div>
                  </td>
                  <td>
                    <div class="actions-cell">
                      <a href="<?php echo base_url('admin/profile/' . $profile->user_id); ?>" 
                         class="action-btn btn-view" 
                         title="View Profile">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="<?php echo base_url('admin/profile/edit/' . $profile->user_id); ?>" 
                         class="action-btn btn-edit" 
                         title="Edit Profile">
                        <i class="fas fa-edit"></i>
                      </a>
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
    <?php if ($pagination['total_pages'] > 1): ?>
    <div class="card-footer">
      <div class="pagination-info">
        Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> 
        to <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total_records']); ?> 
        of <?php echo $pagination['total_records']; ?> profiles
      </div>
      
      <div class="pagination-controls">
        <?php if ($pagination['current_page'] > 1): ?>
          <a href="<?php echo base_url('admin/profiles?page=' . ($pagination['current_page'] - 1) . '&' . http_build_query($filters)); ?>" 
             class="pagination-btn">
            <i class="fas fa-chevron-left"></i>
          </a>
        <?php endif; ?>
        
        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
          <a href="<?php echo base_url('admin/profiles?page=' . $i . '&' . http_build_query($filters)); ?>" 
             class="pagination-btn <?php echo ($i == $pagination['current_page']) ? 'active' : ''; ?>">
            <?php echo $i; ?>
          </a>
        <?php endfor; ?>
        
        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
          <a href="<?php echo base_url('admin/profiles?page=' . ($pagination['current_page'] + 1) . '&' . http_build_query($filters)); ?>" 
             class="pagination-btn">
            <i class="fas fa-chevron-right"></i>
          </a>
        <?php endif; ?>
      </div>
    </div>
    <?php endif; ?>
  </div>

</div>

<style>
/* Modern Profiles List Styles */
.profiles-container {
  max-width: 1600px;
  margin: 0 auto;
  padding: 1rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Page Header */
.page-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  padding: 2rem;
  margin-bottom: 2rem;
  color: white;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.header-title-section {
  flex: 1;
}

.page-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.page-title i {
  font-size: 2rem;
}

.page-subtitle {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.9;
}

.header-actions {
  display: flex;
  gap: 1rem;
}

/* Stats Overview */
.stats-overview {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.stat-card {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  display: flex;
  align-items: center;
  gap: 1.5rem;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.stat-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.stat-total .stat-icon { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
.stat-pending .stat-icon { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
.stat-cleaners .stat-icon { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-hosts .stat-icon { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }

.stat-details {
  flex: 1;
}

.stat-number {
  font-size: 2rem;
  font-weight: 700;
  color: #333;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.9rem;
  color: #6c757d;
  font-weight: 500;
}

/* Filters Card */
.filters-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  margin-bottom: 2rem;
  overflow: hidden;
}

.filters-card .card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.filters-card .card-header h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 600;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.filters-card .card-header i {
  color: #667eea;
}

.collapse-btn {
  background: transparent;
  border: none;
  color: #667eea;
  font-size: 1.2rem;
  cursor: pointer;
  transition: all 0.3s ease;
}

.collapse-btn:hover {
  color: #764ba2;
  transform: scale(1.1);
}

.collapse-btn.collapsed i {
  transform: rotate(-90deg);
}

.card-content {
  padding: 2rem;
}

.filters-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.filter-label {
  font-weight: 600;
  color: #495057;
  font-size: 0.9rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.filter-label i {
  color: #667eea;
  width: 16px;
}

.filter-input, .filter-select {
  padding: 0.75rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
}

.filter-input:focus, .filter-select:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.filter-actions {
  display: flex;
  gap: 1rem;
  justify-content: flex-end;
}

/* Profiles Table Card */
.profiles-table-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
}

.profiles-table-card .card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
}

.profiles-table-card .card-header h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 600;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.profiles-table-card .card-header i {
  color: #667eea;
}

/* Modern Table */
.table-responsive {
  overflow-x: auto;
}

.modern-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.modern-table thead {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.modern-table thead th {
  padding: 1rem 1.5rem;
  font-weight: 600;
  font-size: 0.9rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: white;
  border: none;
  white-space: nowrap;
}

.modern-table thead th:first-child {
  border-radius: 0;
}

.modern-table thead th:last-child {
  border-radius: 0;
}

.modern-table tbody tr {
  transition: all 0.3s ease;
  border-bottom: 1px solid #f0f0f0;
}

.modern-table tbody tr:hover {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

.modern-table tbody td {
  padding: 1.25rem 1.5rem;
  color: #495057;
  vertical-align: middle;
}

/* User Cell */
.user-cell {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.user-avatar {
  flex-shrink: 0;
}

.user-avatar img,
.avatar-placeholder {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #e9ecef;
}

.avatar-placeholder {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1rem;
  font-weight: 700;
}

.user-info {
  flex: 1;
}

.user-name {
  font-weight: 600;
  color: #333;
  margin-bottom: 0.25rem;
}

.user-username {
  font-size: 0.85rem;
  color: #6c757d;
}

/* Email Cell */
.email-cell {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.email-cell i {
  color: #667eea;
  font-size: 0.85rem;
}

/* Date Cell */
.date-cell {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
}

.date-cell i {
  color: #667eea;
  font-size: 0.85rem;
}

/* Completion Cell */
.completion-cell {
  min-width: 150px;
}

.completion-bar-container {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.completion-bar {
  flex: 1;
  height: 10px;
  background: #e9ecef;
  border-radius: 10px;
  overflow: hidden;
}

.completion-fill {
  height: 100%;
  border-radius: 10px;
  transition: width 0.5s ease;
  position: relative;
}

.completion-fill.success {
  background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
  box-shadow: 0 0 10px rgba(40, 167, 69, 0.4);
}

.completion-fill.warning {
  background: linear-gradient(90deg, #ffc107 0%, #fd7e14 100%);
  box-shadow: 0 0 10px rgba(255, 193, 7, 0.4);
}

.completion-text {
  font-weight: 700;
  color: #495057;
  font-size: 0.9rem;
  min-width: 40px;
  text-align: right;
}

/* Rating Cell */
.rating-cell {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.rating-cell i {
  color: #ffc107;
}

.rating-cell span {
  font-weight: 600;
  color: #495057;
}

.rating-cell small {
  color: #6c757d;
}

/* Badges */
.badge {
  padding: 0.4rem 0.8rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  white-space: nowrap;
}

.badge-cleaner { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.badge-host { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
.badge-admin { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; }
.badge-verified { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; }
.badge-pending { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); color: white; }
.badge-unverified { background: #6c757d; color: white; }
.badge-rejected { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; }
.badge-public { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; }
.badge-private { background: #6c757d; color: white; }

/* Actions Cell */
.actions-cell {
  display: flex;
  gap: 0.5rem;
}

.action-btn {
  width: 38px;
  height: 38px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  transition: all 0.3s ease;
  font-size: 0.9rem;
}

.btn-view {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-view:hover {
  background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
  color: white;
  text-decoration: none;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-edit {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
}

.btn-edit:hover {
  background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
  color: white;
  text-decoration: none;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 4rem;
  color: #6c757d;
}

.empty-state i {
  font-size: 5rem;
  margin-bottom: 1.5rem;
  opacity: 0.3;
}

.empty-state h3 {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
  color: #495057;
}

.empty-state p {
  font-size: 1.1rem;
  margin-bottom: 1.5rem;
}

/* Pagination */
.card-footer {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.5rem;
  border-top: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.pagination-info {
  color: #6c757d;
  font-size: 0.9rem;
  font-weight: 500;
}

.pagination-controls {
  display: flex;
  gap: 0.5rem;
}

.pagination-btn {
  padding: 0.5rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  color: #495057;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
  min-width: 40px;
  text-align: center;
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.pagination-btn:hover {
  border-color: #667eea;
  background: #667eea;
  color: white;
  text-decoration: none;
  transform: translateY(-2px);
}

.pagination-btn.active {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
  color: white;
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  font-size: 1rem;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  color: white;
  text-decoration: none;
}

.btn-info {
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
  color: white;
}

.btn-info:hover {
  transform: translateY(-2px);
  color: white;
  text-decoration: none;
}

.btn-outline {
  background: transparent;
  color: #667eea;
  border: 2px solid #667eea;
}

.btn-outline:hover {
  background: #667eea;
  color: white;
  text-decoration: none;
}

/* Responsive Design */
@media (max-width: 1200px) {
  .stats-overview {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .header-content {
    flex-direction: column;
    gap: 1.5rem;
    text-align: center;
  }
  
  .page-title {
    font-size: 2rem;
    justify-content: center;
  }
  
  .stats-overview {
    grid-template-columns: 1fr;
  }
  
  .filters-grid {
    grid-template-columns: 1fr;
  }
  
  .card-content {
    padding: 1.5rem;
  }
  
  .card-footer {
    flex-direction: column;
    gap: 1rem;
  }
  
  .pagination-controls {
    width: 100%;
    justify-content: center;
  }
  
  .modern-table {
    font-size: 0.85rem;
  }
  
  .modern-table thead th,
  .modern-table tbody td {
    padding: 0.75rem;
  }
  
  .user-cell {
    flex-direction: column;
    text-align: center;
  }
}

@media (max-width: 480px) {
  .profiles-container {
    padding: 0.5rem;
  }
  
  .page-header {
    padding: 1.5rem;
  }
  
  .page-title {
    font-size: 1.75rem;
  }
  
  .card-content {
    padding: 1rem;
  }
}
</style>

<script>
$(document).ready(function() {
    // Toggle filters collapse
    $('#toggleFilters').on('click', function() {
        $(this).toggleClass('collapsed');
        $('#filtersContent').slideToggle(300);
    });
    
    // Animate progress bars on load
    $('.completion-fill').each(function() {
        const $fill = $(this);
        const width = $fill.css('width');
        $fill.css('width', '0');
        setTimeout(function() {
            $fill.css('width', width);
        }, 100);
    });
});
</script>