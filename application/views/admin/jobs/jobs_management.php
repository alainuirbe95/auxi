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
    text-align: center;
    padding: 1.5rem;
    height: 100%;
}

.stats-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.stats-number {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #333;
}

.stats-label {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 0;
}

/* Desktop Statistics Layout */
@media (min-width: 1200px) {
    .stats-card {
        padding: 1.75rem 1.25rem;
    }
    
    .stats-icon {
        font-size: 2.75rem;
        margin-bottom: 1rem;
    }
    
    .stats-number {
        font-size: 2.25rem;
        margin-bottom: 0.5rem;
    }
    
    .stats-label {
        font-size: 0.95rem;
    }
}

/* Filter Section Desktop Layout */
@media (min-width: 1200px) {
    .modern-card .card-body .row.g-3 {
        margin: 0 -0.75rem;
    }
    
    .modern-card .card-body .row.g-3 > [class*="col-"] {
        padding: 0 0.75rem;
    }
    
    .modern-card .card-body .row.g-3 .col-md-3 {
        flex: 0 0 22%;
        max-width: 22%;
    }
    
    .modern-card .card-body .row.g-3 .col-md-2 {
        flex: 0 0 18%;
        max-width: 18%;
    }
    
    .modern-card .card-body .row.g-3 .col-md-3:last-child {
        flex: 0 0 22%;
        max-width: 22%;
    }
}

/* Table Styles */
.table-modern {
    background: transparent;
    width: 100%;
    table-layout: auto;
}

.table-modern thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.table-modern tbody td {
    border: none;
    padding: 1rem 0.75rem;
    vertical-align: middle;
    word-wrap: break-word;
}

/* Desktop Table Column Adjustments */
@media (min-width: 1200px) {
    .table-modern thead th,
    .table-modern tbody td {
        padding: 1.25rem 1rem;
    }
    
    .table-modern thead th {
        font-size: 0.9rem;
    }
}

/* Make specific columns wider on desktop */
@media (min-width: 1200px) {
    .table-modern th:nth-child(1), /* Job Details */
    .table-modern td:nth-child(1) {
        width: 25%;
        min-width: 200px;
    }
    
    .table-modern th:nth-child(2), /* Host */
    .table-modern td:nth-child(2) {
        width: 15%;
        min-width: 150px;
    }
    
    .table-modern th:nth-child(3), /* Date & Time */
    .table-modern td:nth-child(3) {
        width: 15%;
        min-width: 140px;
    }
    
    .table-modern th:nth-child(4), /* Price */
    .table-modern td:nth-child(4) {
        width: 10%;
        min-width: 100px;
    }
    
    .table-modern th:nth-child(5), /* Status */
    .table-modern td:nth-child(5) {
        width: 10%;
        min-width: 100px;
    }
    
    .table-modern th:nth-child(6), /* Created */
    .table-modern td:nth-child(6) {
        width: 10%;
        min-width: 120px;
    }
    
    .table-modern th:nth-child(7), /* Actions */
    .table-modern td:nth-child(7) {
        width: 15%;
        min-width: 150px;
    }
}

/* Badge Styles */
.date-badge, .price-badge {
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

/* Button Group Styling */
.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-group .btn i {
    font-size: 0.8rem;
}

/* Cancel/Delete Job Buttons */
.cancel-job-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.delete-job-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
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

/* Pagination */
.modern-pagination .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    border-radius: 20px;
    margin: 0 2px;
    padding: 0.5rem 1rem;
    transition: all 0.3s ease;
}

.modern-pagination .page-link:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-2px);
}

.modern-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

/* Form Styles */
.modern-input, .modern-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background-color: white;
    min-height: 45px;
    font-size: 0.9rem;
    line-height: 1.4;
}

.modern-input:focus, .modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background-color: white;
}

/* Select Dropdown Specific Styles */
.modern-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
    padding-right: 2.5rem;
}

.modern-select option {
    background-color: white;
    color: #333;
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
    line-height: 1.4;
    border: none;
    height: auto;
    min-height: 35px;
}

.modern-select option:hover {
    background-color: #f8f9fa;
    color: #667eea;
}

.modern-select option:checked {
    background-color: #667eea;
    color: white;
}

/* Ensure dropdown options are visible */
.modern-select:focus option {
    background-color: white;
    color: #333;
    z-index: 9999;
}

/* Fix dropdown container overflow issues */
.modern-card .card-body {
    overflow: visible;
}

.modern-card {
    overflow: visible;
}

/* Ensure select dropdowns are not clipped */
select.modern-select {
    z-index: 10;
    position: relative;
}

select.modern-select:focus {
    z-index: 1000;
}

/* Additional dropdown styling for better visibility */
.modern-select option {
    display: block;
    white-space: nowrap;
    overflow: visible;
    text-overflow: initial;
    max-width: none;
    width: auto;
    min-width: 100%;
}

/* Override any Bootstrap or other framework conflicts */
select.form-control.modern-select {
    height: auto !important;
    min-height: 45px !important;
    padding: 0.75rem 2.5rem 0.75rem 1rem !important;
    font-size: 0.9rem !important;
    line-height: 1.4 !important;
    background-color: white !important;
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
}

select.form-control.modern-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
    background-color: white !important;
}

/* Ensure the dropdown options are fully visible */
select.form-control.modern-select option {
    background-color: white !important;
    color: #333 !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.9rem !important;
    line-height: 1.4 !important;
    height: auto !important;
    min-height: 35px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
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

.btn-modern.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-modern.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #3d4449 100%);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Jobs Management Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body text-center py-4">
                            <h2 class="mb-3">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                Job Management
                            </h2>
                            <p class="text-muted mb-0">Manage all cleaning jobs across the platform</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="modern-card stats-card">
                        <div class="card-body text-center">
                            <div class="stats-icon">
                                <i class="fas fa-clipboard-list text-primary"></i>
                            </div>
                            <h3 class="stats-number"><?php echo $pagination['total_items']; ?></h3>
                            <p class="stats-label">Total Jobs</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="modern-card stats-card">
                        <div class="card-body text-center">
                            <div class="stats-icon">
                                <i class="fas fa-clock text-warning"></i>
                            </div>
                            <h3 class="stats-number"><?php echo isset($filters['status']) && $filters['status'] == 'open' ? count($jobs) : 'N/A'; ?></h3>
                            <p class="stats-label">Open Jobs</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="modern-card stats-card">
                        <div class="card-body text-center">
                            <div class="stats-icon">
                                <i class="fas fa-check-circle text-success"></i>
                            </div>
                            <h3 class="stats-number"><?php echo isset($filters['status']) && $filters['status'] == 'completed' ? count($jobs) : 'N/A'; ?></h3>
                            <p class="stats-label">Completed</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="modern-card stats-card">
                        <div class="card-body text-center">
                            <div class="stats-icon">
                                <i class="fas fa-times-circle text-danger"></i>
                            </div>
                            <h3 class="stats-number"><?php echo isset($filters['status']) && $filters['status'] == 'cancelled' ? count($jobs) : 'N/A'; ?></h3>
                            <p class="stats-label">Cancelled</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body">
                            <form method="GET" action="<?php echo base_url('admin/jobs'); ?>" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Search</label>
                                    <input type="text" class="form-control modern-input" id="search" name="search" 
                                           value="<?php echo isset($view_filters['search']) ? htmlspecialchars($view_filters['search']) : ''; ?>" 
                                           placeholder="Search jobs, hosts...">
                                </div>
                                <div class="col-md-2">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control modern-select" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="open" <?php echo (isset($view_filters['status']) && $view_filters['status'] == 'open') ? 'selected' : ''; ?>>Open</option>
                                        <option value="active" <?php echo (isset($view_filters['status']) && $view_filters['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="assigned" <?php echo (isset($view_filters['status']) && $view_filters['status'] == 'assigned') ? 'selected' : ''; ?>>Assigned</option>
                                        <option value="completed" <?php echo (isset($view_filters['status']) && $view_filters['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                                        <option value="cancelled" <?php echo (isset($view_filters['status']) && $view_filters['status'] == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="sort" class="form-label">Sort By</label>
                                    <select class="form-control modern-select" id="sort" name="sort">
                                        <option value="created_at_desc" <?php echo (isset($view_filters['sort']) && $view_filters['sort'] == 'created_at_desc') ? 'selected' : ''; ?>>Newest First</option>
                                        <option value="created_at_asc" <?php echo (isset($view_filters['sort']) && $view_filters['sort'] == 'created_at_asc') ? 'selected' : ''; ?>>Oldest First</option>
                                        <option value="title_asc" <?php echo (isset($view_filters['sort']) && $view_filters['sort'] == 'title_asc') ? 'selected' : ''; ?>>Title A-Z</option>
                                        <option value="title_desc" <?php echo (isset($view_filters['sort']) && $view_filters['sort'] == 'title_desc') ? 'selected' : ''; ?>>Title Z-A</option>
                                        <option value="suggested_price_desc" <?php echo (isset($view_filters['sort']) && $view_filters['sort'] == 'suggested_price_desc') ? 'selected' : ''; ?>>Price High-Low</option>
                                        <option value="suggested_price_asc" <?php echo (isset($view_filters['sort']) && $view_filters['sort'] == 'suggested_price_asc') ? 'selected' : ''; ?>>Price Low-High</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-modern btn-primary">
                                            <i class="fas fa-search me-2"></i>Filter
                                        </button>
                                        <a href="<?php echo base_url('admin/jobs'); ?>" class="btn btn-modern btn-secondary">
                                            <i class="fas fa-times me-2"></i>Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jobs Table -->
            <div class="row">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list text-info me-2"></i>
                                All Jobs
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($jobs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-modern">
                                        <thead>
                                            <tr>
                                                <th>Job Details</th>
                                                <th>Host</th>
                                                <th>Date & Time</th>
                                                <th>Price</th>
                                                <th>Status</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($jobs as $job): ?>
                                                <tr>
                                                    <td>
                                                        <div class="job-info">
                                                            <strong><?php echo htmlspecialchars($job->title); ?></strong>
                                                            <small class="text-muted d-block"><?php echo htmlspecialchars(substr($job->address, 0, 40)); ?>...</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="host-info">
                                                            <strong><?php echo htmlspecialchars($job->host_username); ?></strong>
                                                            <small class="text-muted d-block"><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="date-badge">
                                                            <?php 
                                                            $job_date = isset($job->scheduled_date) ? $job->scheduled_date : '';
                                                            $job_time = isset($job->scheduled_time) ? $job->scheduled_time : '';
                                                            
                                                            if ($job_date && $job_time) {
                                                                $datetime = $job_date . ' ' . $job_time;
                                                                echo date('M j, Y g:i A', strtotime($datetime));
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
                                                        <span class="status-badge status-<?php echo $job->status; ?>">
                                                            <?php echo ucfirst($job->status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="text-muted">
                                                            <?php echo time_ago($job->created_at); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
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
                                                            <?php if ($job->status != 'cancelled'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-danger cancel-job-btn" 
                                                                    data-job-id="<?php echo $job->id; ?>"
                                                                    data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                                                                    title="Cancel Job">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <?php endif; ?>
                                                            <?php if ($job->status == 'cancelled'): ?>
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

                                <!-- Pagination -->
                                <?php if ($pagination['total_pages'] > 1): ?>
                                    <div class="pagination-wrapper mt-4">
                                        <nav aria-label="Jobs pagination">
                                            <ul class="pagination modern-pagination justify-content-center">
                                                <?php if ($pagination['has_prev']): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, ['page' => $pagination['prev_page']]))); ?>">
                                                            <i class="fas fa-chevron-left"></i>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                                
                                                <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                                                    <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                                                        <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, ['page' => $i]))); ?>">
                                                            <?php echo $i; ?>
                                                        </a>
                                                    </li>
                                                <?php endfor; ?>
                                                
                                                <?php if ($pagination['has_next']): ?>
                                                    <li class="page-item">
                                                        <a class="page-link" href="<?php echo base_url('admin/jobs?' . http_build_query(array_merge($_GET, ['page' => $pagination['next_page']]))); ?>">
                                                            <i class="fas fa-chevron-right"></i>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </nav>
                                        
                                        <div class="pagination-info text-center mt-2">
                                            <small class="text-muted">
                                                Showing <?php echo (($pagination['current_page'] - 1) * $pagination['per_page']) + 1; ?> to 
                                                <?php echo min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']); ?> 
                                                of <?php echo $pagination['total_items']; ?> jobs
                                            </small>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <h5>No jobs found</h5>
                                    <p class="text-muted">No jobs match your current filters.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Cancel job functionality
    $('.cancel-job-btn').on('click', function() {
        const jobId = $(this).data('job-id');
        const jobTitle = $(this).data('job-title');
        
        if (confirm(`Are you sure you want to cancel the job "${jobTitle}"? This action cannot be undone.`)) {
            // Show loading state
            $(this).prop('disabled', true);
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Make AJAX request to cancel job
            $.ajax({
                url: '<?php echo base_url('admin/cancel_job'); ?>',
                method: 'POST',
                data: {
                    job_id: jobId,
                    <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    console.log('Cancel job response:', response);
                    if (response.success) {
                        alert('Job cancelled successfully!');
                        location.reload();
                    } else {
                        alert('Error cancelling job: ' + (response.message || 'Unknown error'));
                        $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-times"></i>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Cancel job error:', xhr.responseText, status, error);
                    alert('Error cancelling job. Please try again.');
                    $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-times"></i>');
                }
            });
        }
    });
    
    // Delete job functionality
    $('.delete-job-btn').on('click', function() {
        const jobId = $(this).data('job-id');
        const jobTitle = $(this).data('job-title');
        
        console.log('Delete job clicked - Job ID:', jobId, 'Job Title:', jobTitle);
        
        if (confirm(`Are you sure you want to permanently delete the job "${jobTitle}"? This action cannot be undone.`)) {
            // Show loading state
            $(this).prop('disabled', true);
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Prepare data for AJAX request
            const ajaxData = {
                job_id: jobId,
                <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
            };
            
            console.log('AJAX data being sent:', ajaxData);
            console.log('CSRF token name:', '<?php echo $this->security->get_csrf_token_name(); ?>');
            console.log('CSRF token value:', '<?php echo $this->security->get_csrf_hash(); ?>');
            
            // Make AJAX request to delete job
            $.ajax({
                url: '<?php echo base_url('admin/delete_job'); ?>',
                method: 'POST',
                data: ajaxData,
                dataType: 'json',
                success: function(response) {
                    console.log('Delete job response:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response success:', response.success);
                    
                    if (response && response.success) {
                        alert('Job deleted successfully!');
                        location.reload();
                    } else {
                        const errorMsg = (response && response.message) ? response.message : 'Unknown error';
                        console.log('Delete failed with message:', errorMsg);
                        alert('Error deleting job: ' + errorMsg);
                        $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Delete job AJAX error:', xhr.responseText, status, error);
                    console.log('XHR status:', xhr.status);
                    console.log('XHR responseText:', xhr.responseText);
                    alert('Error deleting job. Please try again.');
                    $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    });
});
</script>
