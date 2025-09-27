<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-clock text-warning me-2"></i>
                        Expired Jobs
                    </h2>
                    <p class="page-subtitle">Jobs that expired without offers being accepted</p>
                    <div class="header-stats">
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count($expired_jobs); ?></span>
                            <span class="stat-label">Total Expired</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">$<?php echo number_format(array_sum(array_column($expired_jobs, 'suggested_price')), 2); ?></span>
                            <span class="stat-label">Lost Revenue</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number"><?php echo count(array_filter($expired_jobs, function($job) { 
                                return strtotime($job->scheduled_date) >= strtotime('-7 days'); 
                            })); ?></span>
                            <span class="stat-label">Recent (7 days)</span>
                        </div>
                    </div>
                </div>
                <div class="header-actions">
                    <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-primary">
                        <i class="fas fa-handshake me-2"></i>
                        View Active Offers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter text-info me-2"></i>
                        Search & Filter
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" id="toggleFilters">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="card-body" id="filterBody">
                    <form method="GET" action="<?php echo base_url('host/expired-jobs'); ?>" id="filterForm">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Search Jobs</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control" 
                                           name="search" 
                                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                           placeholder="Search by title, description, or address">
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Price Range</label>
                                <select class="form-select" name="price_range">
                                    <option value="">All Prices</option>
                                    <option value="0-50" <?php echo ($filters['price_range'] ?? '') === '0-50' ? 'selected' : ''; ?>>
                                        $0 - $50
                                    </option>
                                    <option value="50-100" <?php echo ($filters['price_range'] ?? '') === '50-100' ? 'selected' : ''; ?>>
                                        $50 - $100
                                    </option>
                                    <option value="100-200" <?php echo ($filters['price_range'] ?? '') === '100-200' ? 'selected' : ''; ?>>
                                        $100 - $200
                                    </option>
                                    <option value="200+" <?php echo ($filters['price_range'] ?? '') === '200+' ? 'selected' : ''; ?>>
                                        $200+
                                    </option>
                                </select>
                            </div>

                            <!-- Sort Options -->
                            <div class="col-md-3 mb-3">
                                <label class="form-label">Sort By</label>
                                <select class="form-select" name="sort">
                                    <option value="scheduled_date" <?php echo ($filters['sort_by'] ?? '') === 'scheduled_date' ? 'selected' : ''; ?>>
                                        Scheduled Date
                                    </option>
                                    <option value="title" <?php echo ($filters['sort_by'] ?? '') === 'title' ? 'selected' : ''; ?>>
                                        Job Title
                                    </option>
                                    <option value="price" <?php echo ($filters['sort_by'] ?? '') === 'price' ? 'selected' : ''; ?>>
                                        Price
                                    </option>
                                    <option value="created_at" <?php echo ($filters['sort_by'] ?? '') === 'created_at' ? 'selected' : ''; ?>>
                                        Created Date
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Order</label>
                                <select class="form-select" name="order">
                                    <option value="DESC" <?php echo ($filters['sort_order'] ?? '') === 'DESC' ? 'selected' : ''; ?>>
                                        Newest First
                                    </option>
                                    <option value="ASC" <?php echo ($filters['sort_order'] ?? '') === 'ASC' ? 'selected' : ''; ?>>
                                        Oldest First
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-1 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Clear Filters -->
                        <div class="row">
                            <div class="col-12">
                                <a href="<?php echo base_url('host/expired-jobs'); ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>
                                    Clear Filters
                                </a>
                                <span class="text-muted ms-3">
                                    Showing <?php echo count($expired_jobs); ?> expired jobs
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Expired Jobs List -->
    <div class="row">
        <div class="col-12">
            <div class="table-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-info me-2"></i>
                        Expired Jobs List
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($expired_jobs)): ?>

                        <!-- Expired Jobs Table -->
                        <div class="table-responsive">
                            <table class="table table-hover expired-jobs-table">
                                <thead>
                                    <tr>
                                        <th>Job Details</th>
                                        <th>Location</th>
                                        <th>Price</th>
                                        <th>Scheduled Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($expired_jobs as $job): ?>
                                        <tr data-job-id="<?php echo $job->id; ?>">
                                            <td>
                                                <div class="job-details-cell">
                                                    <h6 class="job-title mb-1"><?php echo htmlspecialchars($job->title); ?></h6>
                                                    <p class="job-description mb-1">
                                                        <?php echo htmlspecialchars(substr($job->description, 0, 80)) . (strlen($job->description) > 80 ? '...' : ''); ?>
                                                    </p>
                                                    <small class="text-muted">
                                                        Created: <?php echo date('M j, Y', strtotime($job->created_at)); ?>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="location-cell">
                                                    <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                    <span class="location-text">
                                                        <?php echo htmlspecialchars($job->address); ?>
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="price-badge">
                                                    $<?php echo number_format($job->suggested_price, 2); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="date-cell">
                                                    <?php 
                                                    if ($job->scheduled_date) {
                                                        $datetime = $job->scheduled_date . ($job->scheduled_time ? ' ' . $job->scheduled_time : '');
                                                        echo date('M j, Y', strtotime($datetime));
                                                        echo '<br><small class="text-muted">' . date('g:i A', strtotime($datetime)) . '</small>';
                                                    } else {
                                                        echo '<span class="text-muted">Not scheduled</span>';
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="status-badge status-expired">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Expired
                                                </span>
                                                <?php 
                                                $days_ago = floor((time() - strtotime($job->scheduled_date)) / (60 * 60 * 24));
                                                if ($days_ago <= 7): ?>
                                                    <br><span class="badge badge-recent">Recent</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewJobDetails(<?php echo $job->id; ?>)" title="View Details">
                                                        <i class="fas fa-eye me-1"></i>
                                                        View Details
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fas fa-clock fa-3x text-muted mb-3"></i>
                            <h5>No Expired Jobs</h5>
                            <p class="text-muted">
                                <?php if (!empty($filters['search'])): ?>
                                    No expired jobs match your search criteria.
                                <?php else: ?>
                                    Great! You don't have any expired jobs. All your jobs have been successfully completed or are still active.
                                <?php endif; ?>
                            </p>
                            <?php if (!empty($filters['search'])): ?>
                                <a href="<?php echo base_url('host/expired-jobs'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Clear Search
                                </a>
                            <?php else: ?>
                                <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-2"></i>
                                    Create New Job
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Expired Jobs Styles */
.container-fluid {
    max-width: 95% !important;
    margin: 0 auto !important;
    padding: 0 20px;
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

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.1) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}

/* Header Stats */
.header-stats {
    display: flex;
    gap: 2rem;
    margin-top: 1rem;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.stat-label {
    font-size: 0.85rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.page-subtitle {
    color: #6c757d;
    margin: 0;
}

.header-actions .btn {
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
}

/* Filter Card */
.filter-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.filter-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-card .card-body {
    padding: 2rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background: #f8f9fa;
    border-color: #dee2e6;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Table Card */
.table-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1.5rem;
}


/* Expired Jobs Table */
.expired-jobs-table {
    margin-bottom: 0;
    border: none;
}

.expired-jobs-table thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem 0.75rem;
    vertical-align: middle;
}

.expired-jobs-table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.expired-jobs-table tbody tr:hover {
    background-color: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}


.expired-jobs-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border: none;
}

/* Table Cell Styles */
.job-details-cell .job-title {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.25rem;
}

.job-details-cell .job-description {
    color: #666;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 0.25rem;
}

.location-cell {
    display: flex;
    align-items: center;
}

.location-text {
    font-size: 0.9rem;
    color: #666;
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.price-badge {
    font-weight: 600;
    color: #28a745;
    font-size: 1rem;
}

.date-cell {
    font-size: 0.9rem;
    color: #333;
}

.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.action-buttons .btn {
    padding: 0.375rem 0.5rem;
    font-size: 0.8rem;
    border-radius: 6px;
}

/* Badge Styles */
.badge-recent {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 10px;
    margin-left: 0.5rem;
}

/* Status Badge */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    white-space: nowrap;
    display: inline-flex;
    align-items: center;
}

.status-expired {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        max-width: 100%;
        padding: 0 15px;
    }
    
    .page-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .header-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .expired-jobs-table {
        font-size: 0.85rem;
    }
    
    .expired-jobs-table th,
    .expired-jobs-table td {
        padding: 0.5rem 0.25rem;
    }
    
    .location-text {
        max-width: 150px;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .action-buttons .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .filter-card .card-body .row > div {
        margin-bottom: 1rem;
    }
}
</style>

<script>
$(document).ready(function() {
    // Toggle filters visibility
    $('#toggleFilters').click(function() {
        $('#filterBody').slideToggle();
        const icon = $(this).find('i');
        icon.toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    // Auto-submit form on filter change
    $('#filterForm select').change(function() {
        $('#filterForm').submit();
    });
    
    // Search with debounce
    let searchTimeout;
    $('#filterForm input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });

});

// Individual job actions
function viewJobDetails(jobId) {
    // Open job details in a modal or redirect to job page
    window.open('<?php echo base_url('host/job/'); ?>' + jobId, '_blank');
}

</script>
