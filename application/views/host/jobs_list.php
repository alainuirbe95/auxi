<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Jobs List Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body text-center py-4">
                            <h2 class="mb-3">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                My Jobs
                            </h2>
                            <p class="text-muted mb-0">Manage all your cleaning job listings</p>
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
                                                <th>Job Title</th>
                                                <th>Date</th>
                                                <th>Price</th>
                                                <th>Offers</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($jobs as $job): ?>
                                                <tr>
                                                    <td>
                                                        <div class="job-info">
                                                            <strong><?php echo htmlspecialchars($job->title); ?></strong>
                                                            <small class="text-muted d-block"><?php echo htmlspecialchars(substr($job->address, 0, 30)); ?>...</small>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="date-badge">
                                                            <?php 
                                                            // Use scheduled_date from database
                                                            $job_date = isset($job->scheduled_date) ? $job->scheduled_date : $job->date_time;
                                                            echo date('M j, Y', strtotime($job_date)); 
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="price-badge">
                                                            $<?php echo number_format($job->suggested_price, 2); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="offers-badge">
                                                            <?php echo $job->offer_count; ?> offers
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="status-badge status-<?php echo $job->status; ?>">
                                                            <?php echo ucfirst($job->status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?php echo base_url('host/job/' . $job->id); ?>" 
                                                               class="btn btn-sm btn-outline-primary" 
                                                               title="View Details">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="<?php echo base_url('host/edit_job/' . $job->id); ?>" 
                                                               class="btn btn-sm btn-outline-warning" 
                                                               title="Edit Job">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <?php if ($job->status == 'open'): ?>
                                                            <button type="button" 
                                                                    class="btn btn-sm btn-outline-danger cancel-job-btn" 
                                                                    data-job-id="<?php echo $job->id; ?>"
                                                                    data-job-title="<?php echo htmlspecialchars($job->title); ?>"
                                                                    title="Cancel Job">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                            <?php elseif ($job->status == 'cancelled'): ?>
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
                                    <h5>No jobs yet</h5>
                                    <p class="text-muted">Create your first cleaning job to get started!</p>
                                    <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-modern btn-primary">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Create Job
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

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

/* Table Styles */
.table-modern {
    background: transparent;
}

.table-modern thead th {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem;
}

.table-modern tbody tr {
    border: none;
    transition: all 0.3s ease;
}

.table-modern tbody tr:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: scale(1.01);
}

.table-modern tbody td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
}

/* Badge Styles */
.date-badge, .price-badge, .offers-badge {
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

.offers-badge {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
    color: #6c757d;
}

.empty-state i {
    opacity: 0.5;
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

/* Cancel Job Button */
.cancel-job-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

/* Delete Job Button */
.delete-job-btn:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
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
</style>

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
                url: '<?php echo base_url('host/cancel_job'); ?>',
                method: 'POST',
                data: {
                    job_id: jobId,
                    <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message and reload page
                        alert('Job cancelled successfully!');
                        location.reload();
                    } else {
                        alert('Error cancelling job: ' + (response.message || 'Unknown error'));
                        // Reset button
                        $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false);
                        $('.cancel-job-btn[data-job-id="' + jobId + '"]').html('<i class="fas fa-times"></i>');
                    }
                },
                error: function() {
                    alert('Error cancelling job. Please try again.');
                    // Reset button
                    $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false);
                    $('.cancel-job-btn[data-job-id="' + jobId + '"]').html('<i class="fas fa-times"></i>');
                }
            });
        }
    });
    
    // Delete job functionality
    $('.delete-job-btn').on('click', function() {
        const jobId = $(this).data('job-id');
        const jobTitle = $(this).data('job-title');
        
        if (confirm(`Are you sure you want to permanently delete the job "${jobTitle}"? This action cannot be undone.`)) {
            // Show loading state
            $(this).prop('disabled', true);
            $(this).html('<i class="fas fa-spinner fa-spin"></i>');
            
            // Make AJAX request to delete job
            $.ajax({
                url: '<?php echo base_url('host/delete_job'); ?>',
                method: 'POST',
                data: {
                    job_id: jobId,
                    <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    console.log('Delete job response:', response);
                    if (response.success) {
                        alert('Job deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error deleting job: ' + (response.message || 'Unknown error'));
                        $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Delete job error:', xhr.responseText, status, error);
                    alert('Error deleting job. Please try again.');
                    $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            });
        }
    });
});
</script>
