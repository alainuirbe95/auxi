<?php
// Helper function for time formatting
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
/* Assigned Jobs Styles */
.assigned-jobs-container {
    padding: 2rem 0;
}

.assigned-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    color: white;
    text-align: center;
}

.assigned-header h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.assigned-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
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
    border-left: 4px solid #28a745; /* Green border for assigned */
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.assigned-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(40, 167, 69, 0.9);
    color: white;
    padding: 0.5rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
    z-index: 5;
}

.job-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

.job-price .accepted-price {
    color: #fff3cd;
    font-size: 1rem;
    font-weight: 600;
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
    color: #28a745;
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
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

.host-contact {
    margin-top: 0.5rem;
    display: flex;
    gap: 0.5rem;
}

.host-contact a {
    color: #28a745;
    text-decoration: none;
    font-size: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.host-contact a:hover {
    color: #20c997;
    text-decoration: underline;
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

.btn-start-job {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
}

.btn-start-job:hover {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-view-details {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #e9ecef;
}

.btn-view-details:hover {
    background: #e9ecef;
    color: #333;
    text-decoration: none;
}

.assignment-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
    border-left: 4px solid #28a745;
}

.assignment-info h6 {
    margin: 0 0 0.5rem 0;
    color: #28a745;
    font-weight: 600;
}

.assignment-info small {
    color: #666;
}

.otp-info {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(40, 167, 69, 0.2);
}

.otp-code {
    text-align: center;
    margin-top: 0.5rem;
}

.otp-number {
    display: inline-block;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    letter-spacing: 0.2em;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
    margin-bottom: 0.5rem;
}

.otp-note {
    display: block;
    color: #666;
    font-size: 0.85rem;
    line-height: 1.4;
    margin-top: 0.5rem;
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
    
    .host-contact {
        flex-direction: column;
    }
}
</style>

<div class="assigned-jobs-container">
    <!-- Header Section -->
    <div class="assigned-header">
        <h2>
            <i class="fas fa-clipboard-check me-2"></i>
            Assigned Jobs
        </h2>
        <p>Jobs that have been assigned to you by hosts</p>
    </div>

    <!-- Assigned Jobs Grid -->
    <?php if (!empty($assigned_jobs)): ?>
        <div class="jobs-grid">
            <?php foreach ($assigned_jobs as $job): ?>
                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title"><?php echo htmlspecialchars($job->title); ?></h3>
                        <div class="job-price">
                            $<?php echo number_format($job->accepted_price, 2); ?>
                            <div class="accepted-price">Accepted Price</div>
                        </div>
                        <div class="job-date">
                            <i class="fas fa-clock me-1"></i>
                            <?php 
                            if (isset($job->scheduled_date) && isset($job->scheduled_time)) {
                                $datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                                echo date('M j, Y g:i A', strtotime($datetime));
                            } else {
                                echo 'Flexible';
                            }
                            ?>
                        </div>
                        <div class="assigned-badge">
                            <i class="fas fa-check-circle"></i>
                            Assigned
                        </div>
                    </div>
                    
                    <div class="job-body">
                        <div class="assignment-info">
                            <h6>
                                <i class="fas fa-calendar-check me-1"></i>
                                Assignment Details
                            </h6>
                            <small>
                                Assigned <?php echo time_ago($job->assignment_date); ?> • 
                                Job ID: #<?php echo $job->id; ?>
                            </small>
                            <?php if (!empty($job->otp_code)): ?>
                                <div class="otp-info">
                                    <h6>
                                        <i class="fas fa-key me-1"></i>
                                        Service Code (OTP)
                                    </h6>
                                    <div class="otp-code">
                                        <span class="otp-number"><?php echo $job->otp_code; ?></span>
                                        <small class="otp-note">
                                            Share this code with the host when you arrive to start the service
                                        </small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
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
                                <div class="host-contact">
                                    <?php if (!empty($job->host_email)): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($job->host_email); ?>">
                                            <i class="fas fa-envelope"></i>
                                            Email
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($job->host_phone)): ?>
                                        <a href="tel:<?php echo htmlspecialchars($job->host_phone); ?>">
                                            <i class="fas fa-phone"></i>
                                            Call
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="job-actions">
                            <?php if ($job->status === 'assigned'): ?>
                                <?php 
                                // Check if job can be started (30 minutes before scheduled time)
                                $can_start = false;
                                $start_message = '';
                                
                                if (!empty($job->scheduled_date) && !empty($job->scheduled_time)) {
                                    $scheduled_datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                                    $job_start_time = strtotime($scheduled_datetime);
                                    $thirty_min_before = $job_start_time - (30 * 60); // 30 minutes before
                                    $current_time = time();
                                    
                                    if ($current_time >= $thirty_min_before) {
                                        $can_start = true;
                                    } else {
                                        $time_diff = $thirty_min_before - $current_time;
                                        $hours = floor($time_diff / 3600);
                                        $minutes = floor(($time_diff % 3600) / 60);
                                        
                                        if ($hours > 0) {
                                            $start_message = "Can start in {$hours}h {$minutes}m";
                                        } else {
                                            $start_message = "Can start in {$minutes} minutes";
                                        }
                                    }
                                }
                                ?>
                                
                                <?php if ($can_start): ?>
                                    <a href="<?php echo base_url('cleaner/start_job_page/' . $job->id); ?>" class="btn-job btn-start-job">
                                        <i class="fas fa-play-circle me-1"></i>
                                        Start Job
                                    </a>
                                <?php else: ?>
                                    <span class="btn-job btn-disabled" title="<?php echo $start_message; ?>">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo $start_message; ?>
                                    </span>
                                <?php endif; ?>
                            <?php elseif ($job->status === 'in_progress'): ?>
                                <span class="btn-job btn-active">
                                    <i class="fas fa-clock me-1"></i>
                                    In Progress
                                </span>
                            <?php endif; ?>
                            <a href="<?php echo base_url('cleaner/job/' . $job->id); ?>" class="btn-job btn-view-details">
                                <i class="fas fa-eye me-1"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <div class="no-jobs">
            <i class="fas fa-clipboard-list"></i>
            <h3>No Assigned Jobs</h3>
            <p>
                You don't have any assigned jobs yet. 
                <a href="<?php echo base_url('cleaner/jobs'); ?>" style="color: #28a745; text-decoration: none;">Browse available jobs</a> 
                and make offers to get started!
            </p>
        </div>
    <?php endif; ?>
</div>


<style>
.btn-active {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%) !important;
    color: white !important;
    border: none !important;
    cursor: not-allowed !important;
}

.btn-disabled {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%) !important;
    color: white !important;
    border: none !important;
    cursor: not-allowed !important;
    opacity: 0.7 !important;
}
</style>
