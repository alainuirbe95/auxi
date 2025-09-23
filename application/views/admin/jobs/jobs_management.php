<?php
// time_ago() helper function
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
        <i class="fas fa-clipboard-list mr-3"></i>
        Job Management
      </h1>
      <p class="page-subtitle">
        Manage and monitor all jobs across the platform
      </p>
    </div>
  </div>

  <!-- Modern Statistics Cards -->
  <div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-primary">
        <div class="stats-icon">
          <i class="fas fa-clipboard-list"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['total_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Total Jobs</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-success">
        <div class="stats-icon">
          <i class="fas fa-play-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['active_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Active Jobs</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-danger">
        <div class="stats-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['completed_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Completed</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-times-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['cancelled_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Cancelled</p>
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
            Jobs Table
          </h3>
        </div>
        <div class="card-actions">
          <a href="<?php echo base_url('admin/create_job'); ?>" class="btn btn-modern btn-success">
            <i class="fas fa-plus-circle mr-2"></i>
            Create New Job
          </a>
        </div>
      </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="modern-filter-section">
      <?php echo form_open('admin/jobs', array('method' => 'GET', 'id' => 'filterForm')); ?>
      
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
                   placeholder="Search jobs...">
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
            <option value="open" <?php echo (isset($filters['status']) && $filters['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
            <option value="assigned" <?php echo (isset($filters['status']) && $filters['status'] == 'assigned') ? 'selected' : ''; ?>>Assigned</option>
            <option value="completed" <?php echo (isset($filters['status']) && $filters['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="cancelled" <?php echo (isset($filters['status']) && $filters['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
          </select>
        </div>
        
        <!-- Host Filter -->
        <div class="filter-group">
          <label for="host" class="filter-label">
            <i class="fas fa-user-tag mr-1"></i>
            Host
          </label>
          <select class="modern-select" id="host" name="host">
            <option value="">All Hosts</option>
            <?php if (isset($hosts) && is_array($hosts)): ?>
              <?php foreach ($hosts as $host): ?>
                <option value="<?php echo $host->user_id; ?>" <?php echo (isset($filters['host']) && $filters['host'] == $host->user_id) ? 'selected' : ''; ?>>
                  <?php echo htmlspecialchars($host->first_name . ' ' . $host->last_name); ?>
                </option>
              <?php endforeach; ?>
            <?php endif; ?>
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
            <option value="title_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'title_asc') ? 'selected' : ''; ?>>Title A-Z</option>
            <option value="title_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'title_desc') ? 'selected' : ''; ?>>Title Z-A</option>
            <option value="price_desc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'price_desc') ? 'selected' : ''; ?>>Highest Price</option>
            <option value="price_asc" <?php echo (isset($filters['sort']) && $filters['sort'] == 'price_asc') ? 'selected' : ''; ?>>Lowest Price</option>
          </select>
        </div>
        
        <!-- Filter Buttons -->
        <div class="filter-actions">
          <button type="submit" class="btn btn-modern btn-primary">
            <i class="fas fa-search mr-1"></i>
            Apply Filters
          </button>
          <a href="<?php echo base_url('admin/jobs'); ?>" class="btn btn-modern btn-outline">
            <i class="fas fa-times mr-1"></i>
            Clear
          </a>
        </div>
      </div>
      
      <?php echo form_close(); ?>
    </div>

    <!-- Jobs Table -->
    <div class="modern-table-container">
      <?php if (empty($jobs)): ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-clipboard-list"></i>
          </div>
          <h4>No Jobs Found</h4>
          <p>No jobs match your current filter criteria.</p>
          <a href="<?php echo base_url('admin/jobs'); ?>" class="btn btn-modern btn-primary">
            <i class="fas fa-refresh mr-2"></i>
            Reset Filters
          </a>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th>Job Details</th>
                <th>Host</th>
                <th>Status</th>
                <th>Price</th>
                <th>Created</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jobs as $job): ?>
                <tr class="job-row">
                  <td>
                    <div class="job-info">
                      <h5 class="job-title"><?php echo htmlspecialchars($job->title); ?></h5>
                      <p class="job-description"><?php echo htmlspecialchars(substr($job->description, 0, 100)) . (strlen($job->description) > 100 ? '...' : ''); ?></p>
                      <div class="job-meta">
                        <span class="job-location">
                          <i class="fas fa-map-marker-alt mr-1"></i>
                          <?php echo htmlspecialchars($job->city . ', ' . $job->state); ?>
                        </span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="host-info">
                      <h6 class="host-name"><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></h6>
                      <p class="host-username">@<?php echo htmlspecialchars($job->host_username); ?></p>
                    </div>
                  </td>
                  <td>
                    <?php
                    $status_class = '';
                    $status_text = '';
                    switch(strtolower($job->status)) {
                      case 'open':
                        $status_class = 'status-open';
                        $status_text = 'Open';
                        break;
                      case 'assigned':
                        $status_class = 'status-assigned';
                        $status_text = 'Assigned';
                        break;
                      case 'completed':
                        $status_class = 'status-completed';
                        $status_text = 'Completed';
                        break;
                      case 'cancelled':
                        $status_class = 'status-cancelled';
                        $status_text = 'Cancelled';
                        break;
                      default:
                        $status_class = 'status-open';
                        $status_text = ucfirst($job->status);
                    }
                    ?>
                    <span class="status-badge <?php echo $status_class; ?>">
                      <i class="fas fa-circle mr-1"></i>
                      <?php echo $status_text; ?>
                    </span>
                  </td>
                  <td>
                    <div class="price-info">
                      <span class="price-amount">$<?php echo number_format($job->suggested_price, 2); ?></span>
                    </div>
                  </td>
                  <td>
                    <div class="created-info">
                      <p class="created-date"><?php echo date('M j, Y', strtotime($job->created_at)); ?></p>
                      <p class="created-duration"><?php echo time_ago($job->created_at); ?></p>
                    </div>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="<?php echo base_url('admin/view_job/' . $job->id); ?>" 
                         class="btn btn-sm btn-outline-primary" 
                         title="View Details">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="<?php echo base_url('admin/edit_job/' . $job->id); ?>" 
                         class="btn btn-sm btn-outline-warning" 
                         title="Edit Job">
                        <i class="fas fa-edit"></i>
                      </a>
                      <?php if (strtolower($job->status) == 'open' || strtolower($job->status) == 'assigned'): ?>
                      <button type="button" 
                              class="btn btn-sm btn-outline-danger cancel-job-btn" 
                              data-job-id="<?php echo $job->id; ?>"
                              data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                              title="Cancel Job">
                        <i class="fas fa-times"></i>
                      </button>
                      <?php elseif (strtolower($job->status) == 'cancelled'): ?>
                      <button type="button" 
                              class="btn btn-sm btn-outline-danger delete-job-btn" 
                              data-job-id="<?php echo $job->id; ?>"
                              data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                              title="Delete Job">
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
          <span>Showing <?php echo $pagination['offset'] + 1; ?> to <?php echo min($pagination['offset'] + $pagination['limit'], $pagination['total_records']); ?> of <?php echo $pagination['total_records']; ?> jobs</span>
        </div>
        
        <nav aria-label="Jobs pagination">
          <ul class="pagination">
            <?php if ($pagination['current_page'] > 1): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, array('page' => $pagination['current_page'] - 1)))); ?>">
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
                <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, array('page' => $i)))); ?>">
                  <?php echo $i; ?>
                </a>
              </li>
            <?php endfor; ?>
            
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, array('page' => $pagination['current_page'] + 1)))); ?>">
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

/* Job Info */
.job-info {
  display: flex;
  flex-direction: column;
}

.job-title {
  margin: 0;
  font-weight: 600;
  color: #2c3e50;
  font-size: 1rem;
}

.job-description {
  margin: 0.25rem 0;
  color: #6c757d;
  font-size: 0.85rem;
}

.job-meta {
  margin-top: 0.5rem;
}

.job-location {
  font-size: 0.8rem;
  color: #6c757d;
}

/* Host Info */
.host-info h6 {
  margin: 0;
  font-weight: 600;
  color: #2c3e50;
  font-size: 0.95rem;
}

.host-info p {
  margin: 0.25rem 0 0 0;
  color: #6c757d;
  font-size: 0.8rem;
}

/* Price Info */
.price-info {
  text-align: center;
}

.price-amount {
  font-size: 1.1rem;
  font-weight: 700;
  color: #28a745;
}

/* Created Info */
.created-info p {
  margin: 0.25rem 0;
  font-size: 0.9rem;
}

.created-date {
  color: #495057;
  font-weight: 500;
}

.created-duration {
  color: #6c757d;
  font-size: 0.8rem;
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

.status-open {
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
  
  .job-info {
    text-align: center;
  }
  
  .host-info {
    text-align: center;
  }
}
</style>

<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('#status, #host, #sort').on('change', function() {
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
    
    // Cancel job button click handler
    $(document).on('click', '.cancel-job-btn', function(e) {
        e.preventDefault();
        var jobId = $(this).data('job-id');
        var jobTitle = $(this).data('job-title');
        cancelJob(jobId, jobTitle);
    });
    
    // Delete job button click handler
    $(document).on('click', '.delete-job-btn', function(e) {
        e.preventDefault();
        var jobId = $(this).data('job-id');
        var jobTitle = $(this).data('job-title');
        deleteJob(jobId, jobTitle);
    });
});

// Job action functions
function cancelJob(jobId, jobTitle) {
    if (confirm('Are you sure you want to cancel job "' + jobTitle + '"?\n\nThis action cannot be undone.')) {
        // Show loading state
        $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        // Make AJAX request
        $.ajax({
            url: '<?php echo base_url('admin/cancel_job'); ?>',
            type: 'POST',
            data: {
                job_id: jobId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message and reload page
                    alert('Job cancelled successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    // Reset button
                    $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-times"></i>');
                }
            },
            error: function() {
                alert('An error occurred while cancelling the job.');
                // Reset button
                $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-times"></i>');
            }
        });
    }
}

function deleteJob(jobId, jobTitle) {
    if (confirm('Are you sure you want to permanently delete job "' + jobTitle + '"?\n\nThis action cannot be undone and will permanently remove all job data.')) {
        // Show loading state
        $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        // Make AJAX request
        $.ajax({
            url: '<?php echo base_url('admin/delete_job'); ?>',
            type: 'POST',
            data: {
                job_id: jobId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message and reload page
                    alert('Job deleted successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    // Reset button
                    $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            },
            error: function() {
                alert('An error occurred while deleting the job.');
                // Reset button
                $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    }
}
</script>