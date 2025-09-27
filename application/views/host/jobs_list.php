        <div class="container-fluid">
    <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                My Jobs
                            </h2>
                    <p class="page-subtitle">Manage all your cleaning job listings</p>
                </div>
                <div class="header-actions">
                    <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>
                        Create New Job
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
                        Filters & Search
                    </h5>
                    <button class="btn btn-sm btn-outline-secondary" id="toggleFilters">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
                <div class="card-body" id="filterBody">
                    <form method="GET" action="<?php echo base_url('host/jobs'); ?>" id="filterForm">
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

                            <!-- Status Filter -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="">All Statuses</option>
                                    <option value="open" <?php echo ($filters['status'] ?? '') === 'open' ? 'selected' : ''; ?>>
                                        Open (<?php echo $status_counts['open'] ?? 0; ?>)
                                    </option>
                                    <option value="assigned" <?php echo ($filters['status'] ?? '') === 'assigned' ? 'selected' : ''; ?>>
                                        Assigned (<?php echo $status_counts['assigned'] ?? 0; ?>)
                                    </option>
                                    <option value="in_progress" <?php echo ($filters['status'] ?? '') === 'in_progress' ? 'selected' : ''; ?>>
                                        In Progress (<?php echo $status_counts['in_progress'] ?? 0; ?>)
                                    </option>
                                    <option value="completed" <?php echo ($filters['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>
                                        Completed (<?php echo $status_counts['completed'] ?? 0; ?>)
                                    </option>
                                    <option value="disputed" <?php echo ($filters['status'] ?? '') === 'disputed' ? 'selected' : ''; ?>>
                                        Disputed (<?php echo $status_counts['disputed'] ?? 0; ?>)
                                    </option>
                                    <option value="closed" <?php echo ($filters['status'] ?? '') === 'closed' ? 'selected' : ''; ?>>
                                        Closed (<?php echo $status_counts['closed'] ?? 0; ?>)
                                    </option>
                                    <option value="cancelled" <?php echo ($filters['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>
                                        Cancelled (<?php echo $status_counts['cancelled'] ?? 0; ?>)
                                    </option>
                                </select>
                            </div>

                            <!-- Price Range -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Min Price</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="price_min" 
                                       value="<?php echo htmlspecialchars($filters['price_min'] ?? ''); ?>"
                                       placeholder="0" 
                                       min="0" 
                                       step="0.01">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="form-label">Max Price</label>
                                <input type="number" 
                                       class="form-control" 
                                       name="price_max" 
                                       value="<?php echo htmlspecialchars($filters['price_max'] ?? ''); ?>"
                                       placeholder="1000" 
                                       min="0" 
                                       step="0.01">
                            </div>

                            <!-- Date Range -->
                            <div class="col-md-2 mb-3">
                                <label class="form-label">From Date</label>
                                <input type="date" 
                                       class="form-control" 
                                       name="date_from" 
                                       value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
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

                        <!-- Hidden fields for sorting -->
                        <input type="hidden" name="sort" value="<?php echo htmlspecialchars($filters['sort_by'] ?? 'created_at'); ?>">
                        <input type="hidden" name="order" value="<?php echo htmlspecialchars($filters['sort_order'] ?? 'DESC'); ?>">

                        <!-- Clear Filters -->
                        <div class="row">
                            <div class="col-12">
                                <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-times me-1"></i>
                                    Clear All Filters
                                </a>
                                <span class="text-muted ms-3">
                                    Showing <?php echo count($jobs); ?> of <?php echo $total_jobs; ?> jobs
                                </span>
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
            <div class="table-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list text-info me-2"></i>
                        Jobs List
                            </h5>
                    <div class="sort-controls">
                        <label class="form-label me-2">Sort by:</label>
                        <select class="form-select form-select-sm" id="sortBy" style="width: auto;">
                            <option value="created" <?php echo ($filters['sort_by'] ?? '') === 'created' ? 'selected' : ''; ?>>Date Created</option>
                            <option value="updated" <?php echo ($filters['sort_by'] ?? '') === 'updated' ? 'selected' : ''; ?>>Last Updated</option>
                            <option value="title" <?php echo ($filters['sort_by'] ?? '') === 'title' ? 'selected' : ''; ?>>Title</option>
                            <option value="date" <?php echo ($filters['sort_by'] ?? '') === 'date' ? 'selected' : ''; ?>>Scheduled Date</option>
                            <option value="price" <?php echo ($filters['sort_by'] ?? '') === 'price' ? 'selected' : ''; ?>>Price</option>
                            <option value="status" <?php echo ($filters['sort_by'] ?? '') === 'status' ? 'selected' : ''; ?>>Status</option>
                            <option value="offers" <?php echo ($filters['sort_by'] ?? '') === 'offers' ? 'selected' : ''; ?>>Number of Offers</option>
                        </select>
                        <button class="btn btn-sm btn-outline-secondary" id="sortOrder" title="Toggle sort order">
                            <i class="fas fa-sort-amount-<?php echo ($filters['sort_order'] ?? 'DESC') === 'ASC' ? 'up' : 'down'; ?>"></i>
                        </button>
                    </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($jobs)): ?>
                                <div class="table-responsive">
                                    <table class="table table-modern">
                                        <thead>
                                            <tr>
                                        <th class="sortable" data-sort="title">
                                            Job Title
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="date">
                                            Scheduled Date
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="price">
                                            Price
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="offers">
                                            Offers
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th class="sortable" data-sort="status">
                                            Status
                                            <i class="fas fa-sort ms-1"></i>
                                        </th>
                                        <th>Cleaner</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($jobs as $job): ?>
                                                <tr>
                                                    <td>
                                                        <div class="job-info">
                                                    <strong class="job-title"><?php echo htmlspecialchars($job->title); ?></strong>
                                                    <small class="text-muted d-block">
                                                        <?php echo htmlspecialchars(substr($job->address, 0, 40)); ?>
                                                        <?php if (strlen($job->address) > 40): ?>...<?php endif; ?>
                                                    </small>
                                                    <small class="text-muted">
                                                        Created: <?php echo date('M j, Y', strtotime($job->created_at)); ?>
                                                    </small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="date-badge">
                                                            <?php 
                                                            if ($job->scheduled_date) {
                                                                $datetime = $job->scheduled_date . ($job->scheduled_time ? ' ' . $job->scheduled_time : '');
                                                                echo date('M j, Y g:i A', strtotime($datetime));
                                                            } else {
                                                                echo 'Not scheduled';
                                                            }
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="price-info">
                                                            <span class="price-badge">
                                                                $<?php echo number_format($job->suggested_price, 2); ?>
                                                            </span>
                                                            <?php if ($job->final_price && $job->final_price != $job->suggested_price): ?>
                                                                <small class="text-success d-block">
                                                                    Final: $<?php echo number_format($job->final_price, 2); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                            
                                                            <?php if ($job->status === 'closed' && $job->dispute_resolution): ?>
                                                                <!-- Dispute Resolution Payment Info -->
                                                                <?php 
                                                                $original_amount = $job->final_price ?: $job->accepted_price;
                                                                $cleaner_amount = $job->payment_amount ?: 0;
                                                                $host_refund = $original_amount - $cleaner_amount;
                                                                ?>
                                                                <div class="dispute-payment-info mt-1">
                                                                    <small class="text-warning d-block">
                                                                        <strong>Dispute Resolution:</strong>
                                                                    </small>
                                                                    <small class="text-success d-block">
                                                                        Refund: $<?php echo number_format($host_refund, 2); ?>
                                                                    </small>
                                                                    <small class="text-muted d-block">
                                                                        Cleaner: $<?php echo number_format($cleaner_amount, 2); ?>
                                                                    </small>
                                                                </div>
                                                            <?php elseif ($job->status === 'closed' && $job->payment_amount): ?>
                                                                <!-- Regular Payment Info -->
                                                                <small class="text-info d-block">
                                                                    Paid: $<?php echo number_format($job->payment_amount, 2); ?>
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="offers-badge">
                                                            <?php echo $job->offer_count; ?> offers
                                                        </span>
                                                <?php if ($job->offer_count > 0): ?>
                                                    <small class="text-muted d-block">
                                                        <a href="<?php echo base_url('host/offers'); ?>" class="text-decoration-none">
                                                            View offers
                                                        </a>
                                                    </small>
                                                <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="status-info">
                                                            <?php if ($job->status === 'closed' && $job->dispute_resolution): ?>
                                                                <!-- Dispute Resolved Status -->
                                                                <span class="status-badge status-disputed">
                                                                    Dispute Resolved
                                                                </span>
                                                                <small class="text-success d-block">
                                                                    Resolved: <?php echo date('M j, Y', strtotime($job->dispute_resolved_at)); ?>
                                                                </small>
                                                            <?php else: ?>
                                                                <!-- Regular Status -->
                                                                <span class="status-badge status-<?php echo $job->status; ?>">
                                                                    <?php echo ucfirst(str_replace('_', ' ', $job->status)); ?>
                                                                </span>
                                                                <?php if ($job->status === 'completed' && isset($job->completed_at)): ?>
                                                                    <small class="text-muted d-block">
                                                                        Completed: <?php echo date('M j, Y', strtotime($job->completed_at)); ?>
                                                                    </small>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                            <td>
                                                <?php if ($job->assigned_cleaner_name): ?>
                                                    <span class="cleaner-info">
                                                        <i class="fas fa-user me-1"></i>
                                                        <?php echo htmlspecialchars($job->assigned_cleaner_name); ?>
                                                        </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Not assigned</span>
                                                <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?php echo base_url('host/job/' . $job->id); ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                    <?php if (in_array($job->status, ['open', 'assigned'])): ?>
                                                            <a href="<?php echo base_url('host/edit_job/' . $job->id); ?>" 
                                                               class="btn btn-sm btn-outline-warning" 
                                                               title="Edit Job">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                    <?php endif; ?>
                                                    <?php if ($job->status === 'open'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-danger cancel-job-btn" 
                                                                    data-job-id="<?php echo $job->id; ?>"
                                                                    data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                                                                    title="Cancel Job">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                    <?php elseif ($job->status === 'cancelled'): ?>
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
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h5>No jobs found</h5>
                            <p class="text-muted">
                                <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['price_min']) || !empty($filters['price_max'])): ?>
                                    No jobs match your current filters. Try adjusting your search criteria.
                                <?php else: ?>
                                    Create your first cleaning job to get started!
                                <?php endif; ?>
                            </p>
                            <?php if (!empty($filters['search']) || !empty($filters['status']) || !empty($filters['price_min']) || !empty($filters['price_max'])): ?>
                                <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    Clear Filters
                                </a>
                            <?php else: ?>
                                <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Create Job
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
/* Enhanced Jobs List Styles */
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
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sort-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.sort-controls .form-select {
    width: auto;
    min-width: 150px;
}

/* Table Styles */
.table-modern {
    background: transparent;
    margin-bottom: 0;
}

.table-modern thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem;
    position: relative;
}

.table-modern thead th.sortable {
    cursor: pointer;
    user-select: none;
    transition: all 0.3s ease;
}

.table-modern thead th.sortable:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.table-modern tbody tr {
    border: none;
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: scale(1.005);
}

.table-modern tbody td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

/* Job Info */
.job-info {
    max-width: 250px;
}

.job-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 0.25rem;
    display: block;
}

.cleaner-info {
    font-size: 0.9rem;
    color: #495057;
}

/* Badge Styles */
.date-badge, .price-badge, .offers-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    white-space: nowrap;
    display: inline-block;
}

.price-badge {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
}

.dispute-payment-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.5rem;
    margin-top: 0.25rem;
}

.dispute-payment-info small {
    font-size: 0.75rem;
    line-height: 1.2;
}

.offers-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
    white-space: nowrap;
    min-width: 80px;
    text-align: center;
}

.status-open { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; }
.status-assigned { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; }
.status-in_progress { background: linear-gradient(135deg, #ffa726 0%, #ff9800 100%); color: white; }
.status-completed { background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%); color: white; }
.status-disputed { background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); color: white; }
.status-closed { background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; }
.status-cancelled { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; }
.status-expired { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white; }
.status-price_adjustment_requested { background: linear-gradient(135deg, #9c27b0 0%, #673ab7 100%); color: white; }

/* Button Styles */
.btn-group .btn {
    margin-right: 2px;
    border-radius: 6px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.btn-outline-primary:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
}

.btn-outline-warning:hover {
    background-color: #ffa726;
    border-color: #ffa726;
    color: white;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
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
    
    .table-card .card-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .sort-controls {
        width: 100%;
        justify-content: space-between;
    }
    
    .filter-card .card-body .row > div {
        margin-bottom: 1rem;
    }
    
    .table-responsive {
        font-size: 0.9rem;
    }
    
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
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
    
    // Sort functionality
    $('#sortBy').change(function() {
        updateSortUrl();
    });
    
    $('#sortOrder').click(function() {
        const currentOrder = '<?php echo $filters['sort_order'] ?? 'DESC'; ?>';
        const newOrder = currentOrder === 'ASC' ? 'DESC' : 'ASC';
        const icon = $(this).find('i');
        
        // Update hidden input
        $('input[name="order"]').val(newOrder);
        icon.removeClass('fa-sort-amount-up fa-sort-amount-down');
        icon.addClass('fa-sort-amount-' + (newOrder === 'ASC' ? 'up' : 'down'));
        
        updateSortUrl();
    });
    
    function updateSortUrl() {
        const sortBy = $('#sortBy').val();
        const sortOrder = $('input[name="order"]').val();
        
        // Update hidden inputs
        $('input[name="sort"]').val(sortBy);
        
        // Submit form
        $('#filterForm').submit();
    }
    
    // Table header sorting
    $('.sortable').click(function() {
        const sortField = $(this).data('sort');
        const currentSort = '<?php echo $filters['sort_by'] ?? 'created_at'; ?>';
        const currentOrder = '<?php echo $filters['sort_order'] ?? 'DESC'; ?>';
        
        let newOrder = 'ASC';
        if (currentSort === sortField && currentOrder === 'ASC') {
            newOrder = 'DESC';
        }
        
        // Update form values
        $('input[name="sort"]').val(sortField);
        $('input[name="order"]').val(newOrder);
        $('#sortBy').val(sortField);
        
        // Update sort order button icon
        const icon = $('#sortOrder i');
        icon.removeClass('fa-sort-amount-up fa-sort-amount-down');
        icon.addClass('fa-sort-amount-' + (newOrder === 'ASC' ? 'up' : 'down'));
        
        // Submit form
        $('#filterForm').submit();
    });
    
    // Auto-submit form on filter change
    $('#filterForm select, #filterForm input[type="number"], #filterForm input[type="date"]').change(function() {
        // Don't auto-submit search input to allow typing
        if ($(this).attr('name') !== 'search') {
            $('#filterForm').submit();
        }
    });
    
    // Search with debounce
    let searchTimeout;
    $('#filterForm input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });
    
    // Cancel job functionality
    $('.cancel-job-btn').on('click', function() {
        const jobId = $(this).data('job-id');
        const jobTitle = $(this).data('job-title');
        
        if (confirm(`Are you sure you want to cancel the job "${jobTitle}"? This action cannot be undone.`)) {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '<?php echo base_url('host/cancel_job'); ?>',
                method: 'POST',
                data: { job_id: jobId },
                success: function(response) {
                    if (response.success) {
                        alert('Job cancelled successfully!');
                        location.reload();
                    } else {
                        alert('Error cancelling job: ' + (response.message || 'Unknown error'));
                        $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-times"></i>');
                    }
                },
                error: function() {
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
        
        if (confirm(`Are you sure you want to permanently delete the job "${jobTitle}"? This action cannot be undone.`)) {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '<?php echo base_url('host/delete_job'); ?>',
                method: 'POST',
                data: { job_id: jobId },
                success: function(response) {
                    if (response && response.success) {
                        alert('Job deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting job: ' + (response && response.message ? response.message : 'Unknown error'));
                        $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                    }
                },
                error: function(xhr, status, error) {
                    let errorMessage = 'Error deleting job. Please try again.';
                    if (xhr.responseText) {
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            if (errorResponse.message) {
                                errorMessage = 'Error: ' + errorResponse.message;
                            }
                        } catch (e) {
                            errorMessage = 'Error: ' + xhr.responseText;
                        }
                    }
                    alert(errorMessage);
                    $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    });
});
</script>