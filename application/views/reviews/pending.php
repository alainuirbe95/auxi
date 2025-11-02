<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-clock"></i> Pending Reviews
                    </h4>
                    <p class="text-muted mb-0">You have 48 hours from job completion to submit your review</p>
                </div>
                <div class="card-body">
                    <?php if (!empty($pending_reviews)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            You have <strong><?php echo count($pending_reviews); ?></strong> job<?php echo count($pending_reviews) != 1 ? 's' : ''; ?> 
                            that need<?php echo count($pending_reviews) == 1 ? 's' : ''; ?> your review.
                        </div>

                        <div class="row">
                            <?php foreach ($pending_reviews as $job): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card job-card">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-briefcase"></i>
                                            <?php echo htmlspecialchars($job->title); ?>
                                        </h5>
                                        
                                        <div class="job-details mb-3">
                                            <p class="mb-2">
                                                <i class="fas fa-user"></i>
                                                <strong>Review:</strong> 
                                                <?php echo htmlspecialchars($job->other_party_first_name . ' ' . $job->other_party_last_name); ?>
                                                (@<?php echo htmlspecialchars($job->other_party_username); ?>)
                                            </p>
                                            
                                            <p class="mb-2">
                                                <i class="fas fa-calendar"></i>
                                                <strong>Completed:</strong> 
                                                <?php echo date('M j, Y g:i A', strtotime($job->completed_at)); ?>
                                            </p>
                                            
                                            <p class="mb-2">
                                                <i class="fas fa-clock"></i>
                                                <strong>Review Window Expires:</strong> 
                                                <span class="text-warning">
                                                    <?php echo date('M j, Y g:i A', strtotime($job->review_window_expires_at)); ?>
                                                </span>
                                            </p>
                                            
                                            <p class="mb-0">
                                                <i class="fas fa-dollar-sign"></i>
                                                <strong>Final Price:</strong> 
                                                $<?php echo number_format($job->final_price, 2); ?>
                                            </p>
                                        </div>

                                        <!-- Time Remaining -->
                                        <div class="time-remaining mb-3">
                                            <?php
                                            $time_remaining = strtotime($job->review_window_expires_at) - time();
                                            $hours_remaining = floor($time_remaining / 3600);
                                            $minutes_remaining = floor(($time_remaining % 3600) / 60);
                                            
                                            if ($time_remaining > 0):
                                                if ($hours_remaining > 24):
                                                    $days_remaining = floor($hours_remaining / 24);
                                                    $hours_remaining = $hours_remaining % 24;
                                                    $time_text = $days_remaining . ' day' . ($days_remaining != 1 ? 's' : '') . ' and ' . $hours_remaining . ' hour' . ($hours_remaining != 1 ? 's' : '');
                                                elseif ($hours_remaining > 0):
                                                    $time_text = $hours_remaining . ' hour' . ($hours_remaining != 1 ? 's' : '') . ' and ' . $minutes_remaining . ' minute' . ($minutes_remaining != 1 ? 's' : '');
                                                else:
                                                    $time_text = $minutes_remaining . ' minute' . ($minutes_remaining != 1 ? 's' : '');
                                                endif;
                                                
                                                $alert_class = ($hours_remaining < 6) ? 'alert-danger' : (($hours_remaining < 24) ? 'alert-warning' : 'alert-success');
                                            else:
                                                $time_text = 'Expired';
                                                $alert_class = 'alert-danger';
                                            endif;
                                            ?>
                                            
                                            <div class="alert <?php echo $alert_class; ?> mb-0">
                                                <i class="fas fa-hourglass-half"></i>
                                                <strong>Time Remaining:</strong> <?php echo $time_text; ?>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="job-actions">
                                            <?php if ($time_remaining > 0): ?>
                                                <a href="<?php echo base_url('reviews/create/' . $job->id); ?>" class="btn btn-primary">
                                                    <i class="fas fa-star"></i> Write Review
                                                </a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary" disabled>
                                                    <i class="fas fa-clock"></i> Review Window Expired
                                                </button>
                                            <?php endif; ?>
                                            
                                            <a href="<?php echo base_url('jobs/view/' . $job->id); ?>" class="btn btn-outline-secondary">
                                                <i class="fas fa-eye"></i> View Job
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">All Caught Up!</h5>
                            <p class="text-muted">
                                You don't have any pending reviews at the moment. 
                                Reviews will appear here after you complete jobs.
                            </p>
                            <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.job-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.2s;
    height: 100%;
}

.job-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.job-details p {
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.job-details i {
    width: 16px;
    color: #6c757d;
}

.time-remaining .alert {
    font-size: 0.9rem;
    padding: 10px 15px;
    border-radius: 6px;
}

.job-actions {
    text-align: center;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.job-actions .btn {
    margin: 0 5px;
    min-width: 120px;
}

.card-title {
    color: #007bff;
    font-weight: 600;
    margin-bottom: 15px;
}

.card-title i {
    margin-right: 8px;
    color: #6c757d;
}

.text-warning {
    color: #ffc107 !important;
    font-weight: 600;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.text-muted {
    color: #6c757d !important;
}
</style>

<script>
$(document).ready(function() {
    // Update time remaining every minute
    setInterval(function() {
        $('.time-remaining').each(function() {
            const $this = $(this);
            const expiresAt = $this.data('expires-at');
            if (expiresAt) {
                const now = new Date().getTime();
                const expireTime = new Date(expiresAt).getTime();
                const timeRemaining = expireTime - now;
                
                if (timeRemaining > 0) {
                    const hours = Math.floor(timeRemaining / (1000 * 60 * 60));
                    const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                    
                    let timeText;
                    if (hours > 24) {
                        const days = Math.floor(hours / 24);
                        const remainingHours = hours % 24;
                        timeText = days + ' day' + (days !== 1 ? 's' : '') + ' and ' + remainingHours + ' hour' + (remainingHours !== 1 ? 's' : '');
                    } else if (hours > 0) {
                        timeText = hours + ' hour' + (hours !== 1 ? 's' : '') + ' and ' + minutes + ' minute' + (minutes !== 1 ? 's' : '');
                    } else {
                        timeText = minutes + ' minute' + (minutes !== 1 ? 's' : '');
                    }
                    
                    $this.find('.alert').removeClass('alert-success alert-warning alert-danger');
                    if (hours < 6) {
                        $this.find('.alert').addClass('alert-danger');
                    } else if (hours < 24) {
                        $this.find('.alert').addClass('alert-warning');
                    } else {
                        $this.find('.alert').addClass('alert-success');
                    }
                    
                    $this.find('strong').next().text(' ' + timeText);
                } else {
                    $this.find('.alert').removeClass('alert-success alert-warning').addClass('alert-danger');
                    $this.find('strong').next().text(' Expired');
                    $this.closest('.job-card').find('.btn-primary').removeClass('btn-primary').addClass('btn-secondary').prop('disabled', true).html('<i class="fas fa-clock"></i> Review Window Expired');
                }
            }
        });
    }, 60000); // Update every minute
});
</script>

<?php $this->load->view('template/footer'); ?>
