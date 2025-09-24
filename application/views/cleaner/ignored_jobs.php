<?php
// Helper function for time formatting
if (!function_exists('time_ago')) {
    function time_ago($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
?>

<style>
/* Ignored Jobs Styles */
.ignored-jobs-container {
    padding: 2rem 0;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.job-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
    border-left: 4px solid #dc3545;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.job-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    padding: 1.5rem;
    color: white;
    position: relative;
}

.job-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.job-price {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.job-date {
    font-size: 0.9rem;
    opacity: 0.9;
}

.job-body {
    padding: 1.5rem;
}

.job-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.job-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.job-detail i {
    color: #dc3545;
    width: 16px;
}

.job-host {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.host-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.host-info h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.host-info small {
    color: #666;
}

.ignored-info {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.ignored-info i {
    color: #856404;
    font-size: 1.2rem;
}

.ignored-info span {
    color: #856404;
    font-size: 0.9rem;
}

.job-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-job {
    flex: 1;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #e9ecef;
}

.btn-view:hover {
    background: #e9ecef;
    color: #333;
    text-decoration: none;
}

.btn-unignore {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
}

.btn-unignore:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.no-jobs {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.no-jobs i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-jobs h3 {
    color: #999;
    margin-bottom: 1rem;
}

.no-jobs p {
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .jobs-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .job-details {
        grid-template-columns: 1fr;
    }
    
    .job-actions {
        flex-direction: column;
    }
}
</style>

<div class="ignored-jobs-container">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">
                        <i class="fas fa-eye-slash text-danger me-2"></i>
                        Ignored Jobs
                    </h2>
                    <p class="text-muted mb-0">Jobs you've chosen to ignore. You can unignore them anytime.</p>
                </div>
                <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back to Jobs
                </a>
            </div>
        </div>
    </div>

    <!-- Jobs Grid -->
    <?php if (!empty($jobs)): ?>
        <div class="jobs-grid">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title"><?php echo htmlspecialchars($job->title); ?></h3>
                        <div class="job-price">$<?php echo number_format($job->suggested_price, 2); ?></div>
                        <div class="job-date">
                            <i class="fas fa-clock me-1"></i>
                            <?php 
                            if (isset($job->scheduled_date) && isset($job->scheduled_time)) {
                                $datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                                echo date('M j, Y g:i A', strtotime($datetime));
                            } elseif (isset($job->date_time)) {
                                echo date('M j, Y g:i A', strtotime($job->date_time));
                            } else {
                                echo 'Flexible';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <div class="job-body">
                        <p class="job-description">
                            <?php echo htmlspecialchars($job->description); ?>
                        </p>
                        
                        <div class="job-details">
                            <div class="job-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($job->city . ', ' . $job->state); ?></span>
                            </div>
                            <div class="job-detail">
                                <i class="fas fa-home"></i>
                                <span>
                                    <?php 
                                    $rooms = is_string($job->rooms) ? json_decode($job->rooms, true) : $job->rooms;
                                    if (is_array($rooms)) {
                                        echo htmlspecialchars(implode(', ', $rooms)) . ' rooms';
                                    } else {
                                        echo htmlspecialchars($job->rooms) . ' rooms';
                                    }
                                    ?>
                                </span>
                            </div>
                            <?php if (!empty($job->extras)): ?>
                                <div class="job-detail">
                                    <i class="fas fa-plus"></i>
                                    <span>
                                        <?php 
                                        $extras = is_string($job->extras) ? json_decode($job->extras, true) : $job->extras;
                                        if (is_array($extras)) {
                                            echo htmlspecialchars(implode(', ', $extras));
                                        } else {
                                            echo htmlspecialchars($job->extras);
                                        }
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            <?php if ($job->pets == 1 || $job->pets == '1'): ?>
                                <div class="job-detail">
                                    <i class="fas fa-paw"></i>
                                    <span>Pets present</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="ignored-info">
                            <i class="fas fa-eye-slash"></i>
                            <span>Ignored <?php echo time_ago($job->ignored_at); ?></span>
                        </div>
                        
                        <div class="job-host">
                            <div class="host-avatar">
                                <?php echo strtoupper(substr($job->host_first_name, 0, 1) . substr($job->host_last_name, 0, 1)); ?>
                            </div>
                            <div class="host-info">
                                <h6><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></h6>
                                <small>
                                    <i class="fas fa-clock me-1"></i>
                                    Posted <?php echo time_ago($job->created_at); ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="job-actions">
                            <a href="<?php echo base_url('cleaner/job/' . $job->id); ?>" class="btn-job btn-view">
                                <i class="fas fa-eye me-1"></i>
                                View Details
                            </a>
                            <button class="btn-job btn-unignore" onclick="unignoreJob(<?php echo $job->id; ?>)">
                                <i class="fas fa-eye me-1"></i>
                                Unignore
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-jobs">
            <i class="fas fa-eye-slash"></i>
            <h3>No Ignored Jobs</h3>
            <p>You haven't ignored any jobs yet. Jobs you ignore will appear here.</p>
            <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn btn-primary">
                <i class="fas fa-search me-1"></i>
                Browse Jobs
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
// Unignore job function
function unignoreJob(jobId) {
    if (confirm('Are you sure you want to unignore this job? It will appear in your job search again.')) {
        // Create a form to submit the unignore request
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo base_url('cleaner/unignore_job'); ?>';
        
        const jobIdInput = document.createElement('input');
        jobIdInput.type = 'hidden';
        jobIdInput.name = 'job_id';
        jobIdInput.value = jobId;
        
        form.appendChild(jobIdInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
