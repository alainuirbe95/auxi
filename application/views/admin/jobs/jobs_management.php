<?php
// time_ago() helper function
if (!function_exists('time_ago')) {
    function time_ago($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' min ago';
        if ($time < 86400) return floor($time/3600) . ' hrs ago';
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
  <div class="row mb-4">
    <!-- Total Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
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
    
    <!-- Open Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-pink">
        <div class="stats-icon">
          <i class="fas fa-folder-open"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['open_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Open</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <!-- Assigned Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-cyan">
        <div class="stats-icon">
          <i class="fas fa-user-check"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['assigned_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Assigned</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <!-- In Progress Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-yellow">
        <div class="stats-icon">
          <i class="fas fa-spinner fa-pulse"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['in_progress_jobs'] ?? 0); ?></h3>
          <p class="stats-label">In Progress</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <!-- Completed Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-success">
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
    
    <!-- Recalled Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-orange">
        <div class="stats-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['recalled_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Recalled</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <!-- Recall Settled Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-teal">
        <div class="stats-icon">
          <i class="fas fa-check-double"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['recall_settled_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Settled</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <!-- Closed Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-gray">
        <div class="stats-icon">
          <i class="fas fa-lock"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['closed_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Closed</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <!-- Cancelled Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-dark">
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
    
    <!-- Expired Jobs -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['expired_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Expired</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <!-- Active Jobs (Combined) -->
    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-3">
      <div class="stats-card stats-card-info">
        <div class="stats-icon">
          <i class="fas fa-play-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['active_jobs'] ?? 0); ?></h3>
          <p class="stats-label">Active</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Main Jobs Card -->
  <div class="modern-card">
    <div class="modern-card-header">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
          <h3 class="card-title mb-0">
            <i class="fas fa-table mr-2"></i>
            Jobs Table
          </h3>
          <p class="card-subtitle mb-0 mt-1">
            Showing <?php echo count($jobs); ?> of <?php echo $pagination['total_items'] ?? 0; ?> jobs
          </p>
        </div>
      </div>
    </div>

    <!-- Search and Filter Bar -->
    <div class="modern-filter-section">
      <?php echo form_open('admin/jobs', array('method' => 'GET', 'id' => 'filterForm')); ?>
      
      <div class="filter-row">
        <!-- Search Input -->
        <div class="filter-group filter-search">
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
            <option value="in_progress" <?php echo (isset($filters['status']) && $filters['status'] == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
            <option value="completed" <?php echo (isset($filters['status']) && $filters['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
            <option value="recalled" <?php echo (isset($filters['status']) && $filters['status'] == 'recalled') ? 'selected' : ''; ?>>Recalled</option>
            <option value="recall_settled" <?php echo (isset($filters['status']) && $filters['status'] == 'recall_settled') ? 'selected' : ''; ?>>Recall Settled</option>
            <option value="cancelled" <?php echo (isset($filters['status']) && $filters['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
            <option value="closed" <?php echo (isset($filters['status']) && $filters['status'] == 'closed') ? 'selected' : ''; ?>>Closed</option>
            <option value="expired" <?php echo (isset($filters['status']) && $filters['status'] == 'expired') ? 'selected' : ''; ?>>Expired</option>
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
        
        <!-- Per Page Selector -->
        <div class="filter-group filter-per-page">
          <label for="per_page" class="filter-label">
            <i class="fas fa-list mr-1"></i>
            Per Page
          </label>
          <select class="modern-select" id="per_page" name="per_page">
            <option value="10" <?php echo (isset($filters['per_page']) && $filters['per_page'] == 10) ? 'selected' : ''; ?>>10</option>
            <option value="20" <?php echo (isset($filters['per_page']) && $filters['per_page'] == 20) ? 'selected' : ''; ?>>20</option>
            <option value="50" <?php echo (isset($filters['per_page']) && $filters['per_page'] == 50) ? 'selected' : ''; ?>>50</option>
            <option value="100" <?php echo (isset($filters['per_page']) && $filters['per_page'] == 100) ? 'selected' : ''; ?>>100</option>
          </select>
        </div>
        
        <!-- Filter Buttons -->
        <div class="filter-actions">
          <button type="submit" class="btn btn-modern btn-primary">
            <i class="fas fa-search mr-1"></i>
            Apply
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
                <th style="width: 5%;">ID</th>
                <th style="width: 25%;">Job Details</th>
                <th style="width: 15%;">Host</th>
                <th style="width: 15%;">Cleaner</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 10%;">Price</th>
                <th style="width: 10%;">Date</th>
                <th style="width: 10%;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jobs as $job): ?>
                <tr class="job-row">
                  <td>
                    <div class="job-id">#<?php echo $job->id; ?></div>
                  </td>
                  <td>
                    <div class="job-info">
                      <h5 class="job-title"><?php echo htmlspecialchars($job->title); ?></h5>
                      <p class="job-description"><?php echo htmlspecialchars(substr($job->description, 0, 80)) . (strlen($job->description) > 80 ? '...' : ''); ?></p>
                      <div class="job-meta">
                        <span class="job-location">
                          <i class="fas fa-map-marker-alt mr-1"></i>
                          <?php echo htmlspecialchars($job->city . ', ' . $job->state); ?>
                        </span>
                        <?php if (!empty($job->scheduled_date)): ?>
                        <span class="job-scheduled ms-2">
                          <i class="fas fa-calendar mr-1"></i>
                          <?php echo date('M j, Y', strtotime($job->scheduled_date)); ?>
                        </span>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="user-info">
                      <h6 class="user-name">
                        <?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?>
                      </h6>
                      <p class="user-username">@<?php echo htmlspecialchars($job->host_username); ?></p>
                      <a href="<?php echo base_url('admin/user_profile/' . $job->host_id); ?>" class="user-link">
                        <i class="fas fa-external-link-alt"></i>
                      </a>
                    </div>
                  </td>
                  <td>
                    <?php if (!empty($job->assigned_cleaner_id)): ?>
                    <div class="user-info">
                      <h6 class="user-name">
                        <?php 
                        if (!empty($job->cleaner_first_name)) {
                            echo htmlspecialchars($job->cleaner_first_name . ' ' . $job->cleaner_last_name);
                        } else {
                            echo htmlspecialchars($job->cleaner_username ?? 'Unknown');
                        }
                        ?>
                      </h6>
                      <p class="user-username">@<?php echo htmlspecialchars($job->cleaner_username ?? 'N/A'); ?></p>
                      <?php if (!empty($job->assigned_cleaner_id)): ?>
                      <a href="<?php echo base_url('admin/user_profile/' . $job->assigned_cleaner_id); ?>" class="user-link">
                        <i class="fas fa-external-link-alt"></i>
                      </a>
                      <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <span class="text-muted small">
                      <i class="fas fa-minus"></i>
                      Not assigned
                    </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php
                    $status_class = '';
                    $status_icon = '';
                    $status_text = '';
                    switch(strtolower($job->status)) {
                      case 'open':
                        $status_class = 'status-open';
                        $status_icon = 'fa-folder-open';
                        $status_text = 'Open';
                        break;
                      case 'assigned':
                        $status_class = 'status-assigned';
                        $status_icon = 'fa-user-check';
                        $status_text = 'Assigned';
                        break;
                      case 'in_progress':
                        $status_class = 'status-progress';
                        $status_icon = 'fa-spinner';
                        $status_text = 'In Progress';
                        break;
                      case 'completed':
                        $status_class = 'status-completed';
                        $status_icon = 'fa-check-circle';
                        $status_text = 'Completed';
                        break;
                      case 'recalled':
                        $status_class = 'status-recalled';
                        $status_icon = 'fa-exclamation-triangle';
                        $status_text = 'Recalled';
                        break;
                      case 'recall_settled':
                        $status_class = 'status-settled';
                        $status_icon = 'fa-check-double';
                        $status_text = 'Settled';
                        break;
                      case 'cancelled':
                        $status_class = 'status-cancelled';
                        $status_icon = 'fa-times-circle';
                        $status_text = 'Cancelled';
                        break;
                      case 'closed':
                        $status_class = 'status-closed';
                        $status_icon = 'fa-lock';
                        $status_text = 'Closed';
                        break;
                      case 'expired':
                        $status_class = 'status-expired';
                        $status_icon = 'fa-clock';
                        $status_text = 'Expired';
                        break;
                      default:
                        $status_class = 'status-open';
                        $status_icon = 'fa-circle';
                        $status_text = ucfirst($job->status);
                    }
                    ?>
                    <span class="status-badge <?php echo $status_class; ?>">
                      <i class="fas <?php echo $status_icon; ?> mr-1"></i>
                      <?php echo $status_text; ?>
                    </span>
                  </td>
                  <td>
                    <div class="price-info">
                      <span class="price-amount">
                        $<?php echo number_format($job->final_price ?? $job->accepted_price ?? $job->suggested_price ?? 0, 2); ?>
                      </span>
                      <?php if (!empty($job->final_price) && $job->final_price != $job->suggested_price): ?>
                      <small class="price-original text-muted d-block">
                        <del>$<?php echo number_format($job->suggested_price, 2); ?></del>
                      </small>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <div class="date-info">
                      <p class="date-primary"><?php echo date('M j, Y', strtotime($job->created_at)); ?></p>
                      <p class="date-secondary"><?php echo time_ago($job->created_at); ?></p>
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
                      <?php if (in_array(strtolower($job->status), ['open', 'assigned'])): ?>
                      <button type="button" 
                              class="btn btn-sm btn-outline-danger cancel-job-btn" 
                              data-job-id="<?php echo $job->id; ?>"
                              data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                              title="Cancel Job">
                        <i class="fas fa-times"></i>
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

    <!-- Enhanced Pagination -->
    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
      <div class="modern-pagination">
        <div class="pagination-info">
          <span class="pagination-text">
            Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> 
            to <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']); ?> 
            of <?php echo number_format($pagination['total_items']); ?> jobs
          </span>
        </div>
        
        <nav aria-label="Jobs pagination">
          <ul class="pagination">
            <?php if ($pagination['has_prev']): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, array('page' => 1)))); ?>" title="First">
                  <i class="fas fa-angle-double-left"></i>
                </a>
              </li>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, array('page' => $pagination['prev_page'])))); ?>" title="Previous">
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
            
            <?php if ($pagination['has_next']): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, array('page' => $pagination['next_page'])))); ?>" title="Next">
                  <i class="fas fa-chevron-right"></i>
                </a>
              </li>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, array('page' => $pagination['total_pages'])))); ?>" title="Last">
                  <i class="fas fa-angle-double-right"></i>
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

.stats-card-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.stats-card-pink {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
}

.stats-card-cyan {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  color: white;
}

.stats-card-yellow {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
  color: white;
}

.stats-card-success {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
}

.stats-card-orange {
  background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
  color: white;
}

.stats-card-teal {
  background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
  color: white;
}

.stats-card-gray {
  background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);
  color: white;
}

.stats-card-dark {
  background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
  color: white;
}

.stats-card-info {
  background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
  color: white;
}

.stats-card-warning {
  background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
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
  border: none;
  overflow: hidden;
}

.modern-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #dee2e6;
}

.modern-card-header .card-title {
  margin: 0;
  font-size: 1.3rem;
  font-weight: 600;
  color: #2c3e50;
}

.modern-card-header .card-subtitle {
  margin: 0;
  color: #6c757d;
  font-size: 0.85rem;
}

.card-actions .btn-modern {
  border-radius: 10px;
  padding: 0.65rem 1.25rem;
  font-weight: 500;
  font-size: 0.9rem;
  transition: all 0.3s ease;
}

.card-actions .btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Modern Filter Section */
.modern-filter-section {
  background: #f8f9fa;
  padding: 1.5rem 2rem;
  border-bottom: 1px solid #dee2e6;
}

.filter-row {
  display: flex;
  gap: 1rem;
  align-items: end;
  flex-wrap: wrap;
}

.filter-group {
  flex: 1;
  min-width: 150px;
}

.filter-group.filter-search {
  min-width: 250px;
}

.filter-group.filter-per-page {
  min-width: 100px;
  max-width: 120px;
}

.filter-label {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.4rem;
  font-size: 0.85rem;
}

.modern-input, .modern-select {
  width: 100%;
  padding: 0.65rem 0.9rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 0.9rem;
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
  right: 0.9rem;
  top: 50%;
  transform: translateY(-50%);
  color: #6c757d;
  pointer-events: none;
}

.filter-actions {
  display: flex;
  gap: 0.6rem;
  align-items: end;
}

.btn-modern {
  border-radius: 8px;
  padding: 0.65rem 1.25rem;
  font-weight: 500;
  font-size: 0.9rem;
  transition: all 0.3s ease;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  white-space: nowrap;
}

.btn-modern.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.btn-modern.btn-success {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
}

.btn-modern.btn-outline {
  background: white;
  color: #6c757d;
  border: 2px solid #e9ecef;
}

.btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  text-decoration: none;
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
  padding: 1rem 0.8rem;
  border-bottom: 2px solid #dee2e6;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.modern-table tbody tr {
  border-bottom: 1px solid #f1f3f4;
  transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
  background: #f8f9fa;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
}

.modern-table tbody td {
  padding: 1rem 0.8rem;
  border: none;
  vertical-align: middle;
}

/* Job ID */
.job-id {
  font-weight: 700;
  color: #667eea;
  font-size: 0.9rem;
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
  font-size: 0.95rem;
  line-height: 1.3;
}

.job-description {
  margin: 0.3rem 0;
  color: #6c757d;
  font-size: 0.8rem;
  line-height: 1.4;
}

.job-meta {
  margin-top: 0.4rem;
}

.job-location,
.job-scheduled {
  font-size: 0.75rem;
  color: #6c757d;
}

/* User Info */
.user-info {
  position: relative;
}

.user-info h6 {
  margin: 0;
  font-weight: 600;
  color: #2c3e50;
  font-size: 0.9rem;
}

.user-info p {
  margin: 0.2rem 0 0 0;
  color: #6c757d;
  font-size: 0.75rem;
}

.user-link {
  position: absolute;
  top: 0;
  right: 0;
  color: #667eea;
  font-size: 0.7rem;
  opacity: 0;
  transition: opacity 0.2s ease;
}

.user-info:hover .user-link {
  opacity: 1;
}

/* Price Info */
.price-info {
  text-align: center;
}

.price-amount {
  font-size: 1rem;
  font-weight: 700;
  color: #28a745;
}

.price-original {
  font-size: 0.75rem;
  margin-top: 0.1rem;
}

/* Date Info */
.date-info p {
  margin: 0.2rem 0;
  font-size: 0.85rem;
}

.date-primary {
  color: #495057;
  font-weight: 500;
}

.date-secondary {
  color: #6c757d;
  font-size: 0.75rem;
}

/* Status Badges */
.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 0.35rem 0.7rem;
  border-radius: 12px;
  font-size: 0.7rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.3px;
  white-space: nowrap;
}

.status-open {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
}

.status-assigned {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  color: white;
}

.status-progress {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
  color: white;
}

.status-completed {
  background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
  color: white;
}

.status-recalled {
  background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
  color: white;
}

.status-settled {
  background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
  color: white;
}

.status-cancelled {
  background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
  color: white;
}

.status-closed {
  background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);
  color: white;
}

.status-expired {
  background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
  color: white;
}

/* Action Buttons */
.action-buttons {
  display: flex;
  gap: 0.4rem;
  flex-wrap: wrap;
  justify-content: center;
}

.action-buttons .btn {
  border-radius: 6px;
  padding: 0.4rem 0.6rem;
  font-size: 0.8rem;
  transition: all 0.2s ease;
  border: 2px solid;
}

.action-buttons .btn:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem 2rem;
}

.empty-icon {
  font-size: 3rem;
  color: #dee2e6;
  margin-bottom: 1rem;
}

.empty-state h4 {
  color: #6c757d;
  margin-bottom: 0.5rem;
  font-weight: 600;
}

.empty-state p {
  color: #adb5bd;
  margin-bottom: 1.5rem;
}

/* Enhanced Pagination */
.modern-pagination {
  padding: 1.5rem 2rem;
  background: #f8f9fa;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
}

.pagination-info {
  color: #6c757d;
  font-size: 0.85rem;
  font-weight: 500;
}

.pagination {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  gap: 0.4rem;
}

.page-item {
  margin: 0;
}

.page-link {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.6rem 0.8rem;
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  color: #495057;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.2s ease;
  min-width: 38px;
  height: 38px;
  font-size: 0.85rem;
}

.page-link:hover {
  background: #667eea;
  border-color: #667eea;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
  text-decoration: none;
}

.page-item.active .page-link {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
  color: white;
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
}

.page-link i {
  font-size: 0.75rem;
}

/* Responsive Design */
@media (max-width: 992px) {
  .modern-header-section {
    padding: 2rem 1.5rem;
  }
  
  .page-title {
    font-size: 1.75rem;
  }
  
  .filter-row {
    flex-direction: column;
    align-items: stretch;
  }
  
  .filter-group {
    min-width: auto;
  }
  
  .filter-group.filter-search,
  .filter-group.filter-per-page {
    min-width: auto;
    max-width: none;
  }
  
  .filter-actions {
    justify-content: stretch;
  }
  
  .filter-actions .btn {
    flex: 1;
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
    flex-wrap: wrap;
  }
}

@media (max-width: 768px) {
  .stats-card {
    padding: 1.25rem;
  }
  
  .stats-number {
    font-size: 1.75rem;
  }
  
  .modern-table tbody td {
    padding: 0.8rem 0.5rem;
  }
  
  .user-link {
    position: static;
    opacity: 1;
    display: block;
    margin-top: 0.3rem;
  }
}
</style>

<script>
$(document).ready(function() {
    // Auto-submit form on filter change
    $('#status, #sort, #per_page').on('change', function() {
        $('#filterForm').submit();
    });
    
    // Add loading state to form submission
    $('#filterForm').on('submit', function() {
        $('.btn-modern.btn-primary').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i>Loading...');
    });
    
    // Cancel job button click handler
    $(document).on('click', '.cancel-job-btn', function(e) {
        e.preventDefault();
        var jobId = $(this).data('job-id');
        var jobTitle = $(this).data('job-title');
        cancelJob(jobId, jobTitle);
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
</script>
