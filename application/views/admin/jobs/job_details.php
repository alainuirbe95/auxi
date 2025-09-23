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

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Job Details Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="mb-2">
                                        <i class="fas fa-clipboard-list text-primary me-2"></i>
                                        <?php echo htmlspecialchars($job->title); ?>
                                    </h2>
                                    <p class="text-muted mb-0">Job ID: #<?php echo $job->id; ?></p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="status-badge status-<?php echo $job->status; ?>">
                                        <?php echo ucfirst($job->status); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Job Information -->
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle text-info me-2"></i>
                                Job Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary">Description</h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($job->description)); ?></p>
                                    
                                    <h6 class="text-primary">Address</h6>
                                    <p class="text-muted"><?php echo htmlspecialchars($job->address); ?></p>
                                    
                                    <h6 class="text-primary">City & State</h6>
                                    <p class="text-muted"><?php echo htmlspecialchars($job->city . ', ' . $job->state); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary">Date & Time</h6>
                                    <p class="text-muted">
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
                                    </p>
                                    
                                    <h6 class="text-primary">Duration</h6>
                                    <p class="text-muted"><?php echo isset($job->estimated_duration) ? $job->estimated_duration . ' minutes' : 'Not specified'; ?></p>
                                    
                                    <h6 class="text-primary">Suggested Price</h6>
                                    <p class="text-muted">$<?php echo number_format($job->suggested_price, 2); ?></p>
                                </div>
                            </div>
                            
                            <!-- Additional Job Details -->
                            <div class="row mt-3">
                                <div class="col-sm-6">
                                    <h6 class="text-primary">Rooms</h6>
                                    <?php 
                                    $rooms = json_decode($job->rooms, true);
                                    if (is_array($rooms) && !empty($rooms)) {
                                        echo '<p class="text-muted">' . implode(', ', $rooms) . '</p>';
                                    } else {
                                        echo '<p class="text-muted">Not specified</p>';
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-6">
                                    <h6 class="text-primary">Additional Services</h6>
                                    <?php 
                                    $extras = json_decode($job->extras ?? '[]', true);
                                    if (is_array($extras) && !empty($extras)) {
                                        echo '<ul class="list-unstyled mb-0">';
                                        foreach ($extras as $extra) {
                                            echo '<li><i class="fas fa-check text-success me-2"></i>' . htmlspecialchars($extra) . '</li>';
                                        }
                                        echo '</ul>';
                                    } else {
                                        echo '<p class="text-muted">None selected</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($job->special_instructions)): ?>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-primary">Special Instructions</h6>
                                    <p class="text-muted"><?php echo nl2br(htmlspecialchars($job->special_instructions)); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (isset($job->pets) && $job->pets): ?>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <span class="badge bg-info"><i class="fas fa-paw me-1"></i> Pets Present</span>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Host Information & Actions -->
                <div class="col-lg-4">
                    <!-- Host Information -->
                    <div class="modern-card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user text-success me-2"></i>
                                Host Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="host-profile">
                                <div class="host-avatar">
                                    <i class="fas fa-user-circle fa-3x text-primary"></i>
                                </div>
                                <div class="host-details">
                                    <h6 class="host-name"><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></h6>
                                    <p class="host-username text-muted">@<?php echo htmlspecialchars($job->host_username); ?></p>
                                    <p class="host-email text-muted"><?php echo htmlspecialchars($job->host_email); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Actions -->
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs text-warning me-2"></i>
                                Admin Actions
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo base_url('admin/edit_job/' . $job->id); ?>" 
                                   class="btn btn-modern btn-warning">
                                    <i class="fas fa-edit me-2"></i>Edit Job
                                </a>
                                
                                <?php if ($job->status != 'cancelled'): ?>
                                <button type="button" 
                                        class="btn btn-modern btn-danger cancel-job-btn" 
                                        data-job-id="<?php echo $job->id; ?>"
                                        data-job-title="<?php echo htmlspecialchars($job->title); ?>">
                                    <i class="fas fa-times me-2"></i>Cancel Job
                                </button>
                                <?php endif; ?>
                                
                                <?php if ($job->status == 'cancelled'): ?>
                                <button type="button" 
                                        class="btn btn-modern btn-danger delete-job-btn" 
                                        data-job-id="<?php echo $job->id; ?>"
                                        data-job-title="<?php echo htmlspecialchars($job->title); ?>">
                                    <i class="fas fa-trash me-2"></i>Delete Job
                                </button>
                                <?php endif; ?>
                                
                                <a href="<?php echo base_url('admin/jobs'); ?>" 
                                   class="btn btn-modern btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Jobs
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Offers Section (if any) -->
            <?php if (!empty($offers)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-handshake text-info me-2"></i>
                                Offers (<?php echo count($offers); ?>)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-modern">
                                    <thead>
                                        <tr>
                                            <th>Cleaner</th>
                                            <th>Offer Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($offers as $offer): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($offer->cleaner_username ?? 'Unknown'); ?></td>
                                            <td><?php echo ucfirst($offer->offer_type ?? 'N/A'); ?></td>
                                            <td>$<?php echo number_format($offer->amount ?? 0, 2); ?></td>
                                            <td>
                                                <span class="status-badge status-<?php echo $offer->status ?? 'pending'; ?>">
                                                    <?php echo ucfirst($offer->status ?? 'Pending'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo time_ago($offer->created_at ?? ''); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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

/* Status Badge */
.status-badge {
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    white-space: nowrap;
    min-width: 80px;
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

/* Host Profile */
.host-profile {
    text-align: center;
}

.host-avatar {
    margin-bottom: 1rem;
}

.host-name {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #333;
}

.host-username {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.host-email {
    font-size: 0.85rem;
    margin-bottom: 0;
}

/* Button Styles */
.btn-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    color: white;
}

.btn-modern.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
}

.btn-modern.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800 0%, #e67e00 100%);
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
}

.btn-modern.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
}

.btn-modern.btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.4);
}

.btn-modern.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-modern.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #3d4449 100%);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
}

/* Table Styles */
.table-modern {
    background: transparent;
}

.table-modern thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
}

.table-modern tbody td {
    border: none;
    padding: 1rem;
    vertical-align: middle;
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
            $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
            
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
                        $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-times me-2"></i>Cancel Job');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Cancel job error:', xhr.responseText, status, error);
                    alert('Error cancelling job. Please try again.');
                    $('.cancel-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-times me-2"></i>Cancel Job');
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
            $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Processing...');
            
            // Make AJAX request to delete job
            $.ajax({
                url: '<?php echo base_url('admin/delete_job'); ?>',
                method: 'POST',
                data: {
                    job_id: jobId,
                    <?php echo $this->security->get_csrf_token_name(); ?>: '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    console.log('Delete job response:', response);
                    if (response.success) {
                        alert('Job deleted successfully!');
                        window.location.href = '<?php echo base_url('admin/jobs'); ?>';
                    } else {
                        alert('Error deleting job: ' + (response.message || 'Unknown error'));
                        $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash me-2"></i>Delete Job');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Delete job error:', xhr.responseText, status, error);
                    alert('Error deleting job. Please try again.');
                    $('.delete-job-btn[data-job-id="' + jobId + '"]').prop('disabled', false).html('<i class="fas fa-trash me-2"></i>Delete Job');
                }
            });
        }
    });
});
</script>
