<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Recalled Jobs Management
                </h1>
                <p class="page-subtitle">Review and manage all recalled jobs from hosts</p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="dashboard-stats">
        <div class="stat-card pending">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $recall_stats['pending'] ?? 0; ?></h3>
                <p>Pending Review</p>
            </div>
        </div>
        <div class="stat-card investigating">
            <div class="stat-icon">
                <i class="fas fa-search"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $recall_stats['under_investigation'] ?? 0; ?></h3>
                <p>Under Investigation</p>
            </div>
        </div>
        <div class="stat-card resolved">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $recall_stats['resolved'] ?? 0; ?></h3>
                <p>Resolved</p>
            </div>
        </div>
        <div class="stat-card total">
            <div class="stat-icon">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $recall_stats['total'] ?? 0; ?></h3>
                <p>Total Recalls</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="filter-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter text-primary me-2"></i>
                        Filter Recalled Jobs
                    </h5>
                </div>
                <div class="card-body">
                    <form method="get" action="<?php echo base_url('admin/recalled-jobs'); ?>" id="filterForm">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Date From</label>
                                <input type="date" class="form-control" name="date_from" value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Date To</label>
                                <input type="date" class="form-control" name="date_to" value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Reason</label>
                                <select class="form-select" name="reason">
                                    <option value="">All Reasons</option>
                                    <option value="poor_quality" <?php echo ($filters['reason'] ?? '') === 'poor_quality' ? 'selected' : ''; ?>>Poor Quality</option>
                                    <option value="unprofessional_behavior" <?php echo ($filters['reason'] ?? '') === 'unprofessional_behavior' ? 'selected' : ''; ?>>Unprofessional Behavior</option>
                                    <option value="safety_concerns" <?php echo ($filters['reason'] ?? '') === 'safety_concerns' ? 'selected' : ''; ?>>Safety Concerns</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Severity</label>
                                <select class="form-select" name="severity">
                                    <option value="">All Severities</option>
                                    <option value="low" <?php echo ($filters['severity'] ?? '') === 'low' ? 'selected' : ''; ?>>Low</option>
                                    <option value="medium" <?php echo ($filters['severity'] ?? '') === 'medium' ? 'selected' : ''; ?>>Medium</option>
                                    <option value="high" <?php echo ($filters['severity'] ?? '') === 'high' ? 'selected' : ''; ?>>High</option>
                                    <option value="critical" <?php echo ($filters['severity'] ?? '') === 'critical' ? 'selected' : ''; ?>>Critical</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="recall_status">
                                    <option value="">All Statuses</option>
                                    <option value="pending" <?php echo ($filters['recall_status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="under_investigation" <?php echo ($filters['recall_status'] ?? '') === 'under_investigation' ? 'selected' : ''; ?>>Under Investigation</option>
                                    <option value="resolved" <?php echo ($filters['recall_status'] ?? '') === 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label">Sort By</label>
                                <select class="form-select" name="sort">
                                    <option value="recalled_at" <?php echo ($filters['sort'] ?? '') === 'recalled_at' ? 'selected' : ''; ?>>Recall Date</option>
                                    <option value="title" <?php echo ($filters['sort'] ?? '') === 'title' ? 'selected' : ''; ?>>Job Title</option>
                                    <option value="severity" <?php echo ($filters['sort'] ?? '') === 'severity' ? 'selected' : ''; ?>>Severity</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Search Jobs</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control"
                                           name="search"
                                           value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                           placeholder="Search by title, host, or cleaner name">
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i> Apply Filters
                                    </button>
                                    <a href="<?php echo base_url('admin/recalled-jobs'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i> Clear Filters
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Recalled Jobs List -->
    <div class="row">
        <div class="col-12">
            <div class="jobs-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-info me-2"></i>
                        Recalled Jobs
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($recalled_jobs)): ?>
                        <div class="table-responsive">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th style="width: 80px;">Job ID</th>
                                        <th style="width: 200px;">Title</th>
                                        <th style="width: 180px;">Host</th>
                                        <th style="width: 180px;">Cleaner</th>
                                        <th style="width: 120px;">Recall Date</th>
                                        <th style="width: 120px;">Reason</th>
                                        <th style="width: 100px;">Severity</th>
                                        <th style="width: 120px;">Status</th>
                                        <th style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                            <?php foreach ($recalled_jobs as $job): ?>
                                <!-- Main Row -->
                                <tr class="recall-job-row" data-job-id="<?php echo $job->id; ?>">
                                    <td>
                                        <span class="badge bg-secondary">#<?php echo $job->id; ?></span>
                                    </td>
                                    <td>
                                        <div class="job-title-cell">
                                            <strong><?php echo htmlspecialchars($job->title); ?></strong>
                                            <br>
                                            <small class="text-muted">
                                                $<?php echo number_format($job->final_price ?? $job->accepted_price ?? $job->suggested_price, 2); ?>
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-info-cell">
                                            <strong><?php echo htmlspecialchars($job->host_name); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($job->host_email); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="user-info-cell">
                                            <strong><?php echo htmlspecialchars($job->cleaner_name); ?></strong>
                                            <br>
                                            <small class="text-muted"><?php echo htmlspecialchars($job->cleaner_email); ?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <small><?php echo date('M j, Y', strtotime($job->recalled_at ?? $job->updated_at)); ?></small>
                                        <br>
                                        <small class="text-muted"><?php echo date('g:i A', strtotime($job->recalled_at ?? $job->updated_at)); ?></small>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $job->recall_reason === 'poor_quality' ? 'danger' : ($job->recall_reason === 'unprofessional_behavior' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $job->recall_reason ?? 'unknown')); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $job->recall_severity === 'high' ? 'danger' : ($job->recall_severity === 'medium' ? 'warning' : 'info'); ?>">
                                            <?php echo ucfirst($job->recall_severity ?? 'low'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo $job->recall_status === 'pending' ? 'warning' : ($job->recall_status === 'under_investigation' ? 'info' : 'success'); ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $job->recall_status ?? 'pending')); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary toggle-details" data-job-id="<?php echo $job->id; ?>">
                                                <i class="fas fa-chevron-down"></i>
                                            </button>
                                            <a href="<?php echo base_url('host/public_profile/' . $job->host_id); ?>" class="btn btn-sm btn-outline-info" target="_blank">
                                                <i class="fas fa-user"></i>
                                            </a>
                                            <a href="<?php echo base_url('cleaner/public_profile/' . $job->assigned_cleaner_id); ?>" class="btn btn-sm btn-outline-info" target="_blank">
                                                <i class="fas fa-user-tie"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                
                                <!-- Details Row -->
                                <tr class="recall-details-row" id="details-<?php echo $job->id; ?>" style="display: none;">
                                    <td colspan="9">
                                        <div class="recall-details-content p-3 bg-light">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6 class="details-title">
                                                        <i class="fas fa-info-circle text-primary me-2"></i>
                                                        Recall Details
                                                    </h6>
                                                    <div class="detail-item">
                                                        <strong>Recall Reason:</strong>
                                                        <span class="ms-2"><?php echo ucfirst(str_replace('_', ' ', $job->recall_reason ?? 'Not specified')); ?></span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>Recall Details:</strong>
                                                        <p class="ms-2 mt-1"><?php echo htmlspecialchars($job->recall_details ?? 'No details provided'); ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>Severity:</strong>
                                                        <span class="ms-2 badge bg-<?php echo $job->recall_severity === 'high' ? 'danger' : ($job->recall_severity === 'medium' ? 'warning' : 'info'); ?>">
                                                            <?php echo ucfirst($job->recall_severity ?? 'low'); ?>
                                                        </span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>Evidence Notes:</strong>
                                                        <p class="ms-2 mt-1"><?php echo htmlspecialchars($job->evidence_notes ?? 'No evidence provided'); ?></p>
                                                    </div>
                                                    <div class="detail-item">
                                                        <strong>Desired Resolution:</strong>
                                                        <span class="ms-2"><?php echo ucfirst(str_replace('_', ' ', $job->desired_resolution ?? 'Not specified')); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6 class="details-title">
                                                        <i class="fas fa-gavel text-warning me-2"></i>
                                                        Admin Actions
                                                    </h6>
                                                    
                                                    <!-- Settlement Form -->
                                                    <div class="settlement-form mb-3 p-3 border rounded bg-white">
                                                        <h6 class="mb-3">
                                                            <i class="fas fa-gavel me-2"></i>
                                                            Settle This Recall
                                                        </h6>
                                                        <form class="settle-recall-form" data-job-id="<?php echo $job->id; ?>">
                                                            <div class="row">
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Admin Decision</label>
                                                                    <select name="admin_decision" class="form-select" required>
                                                                        <option value="">Select Decision</option>
                                                                        <option value="uphold_recall">Uphold Recall</option>
                                                                        <option value="dismiss_recall">Dismiss Recall</option>
                                                                        <option value="partial_resolution">Partial Resolution</option>
                                                                        <option value="require_mediation">Require Mediation</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-md-6 mb-3">
                                                                    <label class="form-label">Resolution Type</label>
                                                                    <select name="resolution_type" class="form-select" required>
                                                                        <option value="">Select Resolution</option>
                                                                        <option value="cleaner_improvement">Cleaner Improvement Required</option>
                                                                        <option value="host_education">Host Education Required</option>
                                                                        <option value="mutual_understanding">Mutual Understanding</option>
                                                                        <option value="system_improvement">System Improvement</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Admin Notes</label>
                                                                <textarea name="admin_notes" class="form-control" rows="3" placeholder="Add your notes about this recall settlement..."></textarea>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button type="submit" class="btn btn-success">
                                                                    <i class="fas fa-check me-1"></i>
                                                                    Mark as Settled
                                                                </button>
                                                                <button type="button" class="btn btn-outline-secondary" onclick="toggleDetails(<?php echo $job->id; ?>)">
                                                                    <i class="fas fa-times me-1"></i>
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    
                                                    <!-- Quick Actions -->
                                                    <div class="quick-actions">
                                                        <h6 class="mb-2">Quick Actions</h6>
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            <a href="<?php echo base_url('admin/ban_user/' . $job->host_id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to ban this host?')">
                                                                <i class="fas fa-ban me-1"></i>
                                                                Ban Host
                                                            </a>
                                                            <a href="<?php echo base_url('admin/ban_user/' . $job->assigned_cleaner_id); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to ban this cleaner?')">
                                                                <i class="fas fa-ban me-1"></i>
                                                                Ban Cleaner
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="empty-state text-center py-5">
                            <div class="empty-icon mb-3">
                                <i class="fas fa-clipboard-list fa-3x text-muted"></i>
                            </div>
                            <h5>No Recalled Jobs Found</h5>
                            <p class="text-muted">No jobs match your current filter criteria.</p>
                            <a href="<?php echo base_url('admin/recalled-jobs'); ?>" class="btn btn-primary">
                                <i class="fas fa-refresh me-1"></i>
                                Clear Filters
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Enhanced Admin Recalled Jobs Styles - Matching App Theme */
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

.stat-card.pending::before {
    background: linear-gradient(135deg, #ffc107, #ff8f00);
}

.stat-card.investigating::before {
    background: linear-gradient(135deg, #17a2b8, #007bff);
}

.stat-card.resolved::before {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.stat-card.total::before {
    background: linear-gradient(135deg, #6f42c1, #e83e8c);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
    margin-bottom: 1rem;
    position: relative;
    overflow: hidden;
}

.stat-card.pending .stat-icon {
    background: linear-gradient(135deg, #ffc107, #ff8f00);
}

.stat-card.investigating .stat-icon {
    background: linear-gradient(135deg, #17a2b8, #007bff);
}

.stat-card.resolved .stat-icon {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.stat-card.total .stat-icon {
    background: linear-gradient(135deg, #6f42c1, #e83e8c);
}

.stat-content h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: #2c3e50;
}

.stat-content p {
    color: #6c757d;
    font-weight: 500;
    margin: 0;
}

/* Filter and Jobs Cards */
.filter-card, .jobs-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: none;
    margin-bottom: 2rem;
    overflow: hidden;
}

.filter-card .card-header, .jobs-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.filter-card .card-title, .jobs-card .card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.filter-card .card-body, .jobs-card .card-body {
    padding: 2rem;
}

/* Modern Table Styling */
.table-responsive {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.modern-table {
    width: 100%;
    margin-bottom: 0;
    background: white;
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.modern-table thead th {
    padding: 1rem 0.75rem;
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
    text-align: left;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modern-table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #e9ecef;
}

.modern-table tbody tr:hover {
    background-color: #f8f9fa;
    transition: all 0.2s ease;
}

.job-title-cell strong {
    color: #2c3e50;
    font-weight: 600;
}

.user-info-cell strong {
    color: #2c3e50;
    font-weight: 600;
}

/* Button Styling */
.btn-group .btn {
    margin-right: 2px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.toggle-details {
    transition: all 0.3s ease;
    border-radius: 50%;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.toggle-details:hover {
    transform: scale(1.1);
}

/* Details Section Styling */
.recall-details-content {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    border-left: 4px solid #667eea;
    border-radius: 10px;
    margin: 1rem;
}

.details-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
    display: flex;
    align-items: center;
}

.detail-item {
    margin-bottom: 1rem;
    padding: 0.5rem 0;
}

.detail-item strong {
    color: #2c3e50;
    font-weight: 600;
}

.settlement-form {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    border: 2px solid #28a745;
    transition: all 0.3s ease;
}

.settlement-form:hover {
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.2);
}

.quick-actions {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 2px solid #e9ecef;
}

.quick-actions h6 {
    color: #dc3545;
    font-weight: 600;
    margin-bottom: 1rem;
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-icon {
    margin-bottom: 1.5rem;
}

.empty-state h5 {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 2rem;
}

/* Animation for details row */
.recall-details-row {
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

/* Form Styling */
.form-control, .form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.btn-outline-danger {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-outline-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}
</style>

<script>
$(document).ready(function() {
    // Filter form auto-submit
    $('#filterForm select, #filterForm input').on('change keyup', function() {
        clearTimeout(window.filterTimeout);
        window.filterTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });
    
    // Toggle details for each row
    $('.toggle-details').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const jobId = $(this).data('job-id');
        toggleDetails(jobId);
    });
    
    // Prevent clicks inside the settlement form from collapsing the dropdown
    $('.settlement-form').on('click', function(e) {
        e.stopPropagation();
    });

    // Settle recall inline form submission
    $('.settle-recall-form').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        const form = $(this);
        const jobId = form.data('job-id');
        const formData = form.serialize();
        
        // Show loading state
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Settling...');
        
        $.ajax({
            url: '<?php echo base_url("admin/settle_recall"); ?>',
            type: 'POST',
            data: formData + '&job_id=' + jobId,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert('Recall settled successfully!');
                    
                    // Reload the page to show updated status
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                alert('An error occurred while settling the recall. Please try again.');
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});

// Toggle details function
function toggleDetails(jobId) {
    const detailsRow = $('#details-' + jobId);
    const toggleBtn = $('.toggle-details[data-job-id="' + jobId + '"]');
    const icon = toggleBtn.find('i');
    
    if (detailsRow.is(':visible')) {
        detailsRow.slideUp(300);
        icon.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        toggleBtn.removeClass('btn-primary').addClass('btn-outline-primary');
    } else {
        // Close all other open details
        $('.recall-details-row:visible').slideUp(300);
        $('.toggle-details i').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        $('.toggle-details').removeClass('btn-primary').addClass('btn-outline-primary');
        
        // Open this one
        detailsRow.slideDown(300);
        icon.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        toggleBtn.removeClass('btn-outline-primary').addClass('btn-primary');
    }
}
</script>