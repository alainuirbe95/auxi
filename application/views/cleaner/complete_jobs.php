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
.jobs-progress-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Modern Header */
.modern-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.modern-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.modern-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

.stat-card.active-jobs {
    border-left-color: #667eea;
}

.stat-label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.5rem;
    display: block;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #2d3748;
}

/* Job Cards */
.jobs-grid {
    display: grid;
    gap: 1.5rem;
}

.job-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.job-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem;
    color: white;
}

.job-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    font-size: 0.9rem;
    opacity: 0.95;
}

.job-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.job-card-body {
    padding: 1.5rem;
}

.job-info-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 12px;
    padding: 1rem;
}

.info-section h6 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-section p {
    margin: 0;
    color: #4a5568;
    line-height: 1.6;
}

.time-tracker {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe8cc 100%);
    border: 2px solid #ffc107;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    text-align: center;
}

.time-tracker h6 {
    color: #e65100;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.time-elapsed {
    font-size: 2rem;
    font-weight: 800;
    color: #f57c00;
}

.job-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.btn-complete {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-complete:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    text-decoration: none;
}

.btn-contact {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-contact:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

/* Empty State */
.empty-state {
    background: white;
    border-radius: 20px;
    padding: 4rem 2rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 3rem;
    color: #6c757d;
}

.empty-state h3 {
    color: #495057;
    font-weight: 700;
    margin-bottom: 1rem;
}

.empty-state p {
    color: #6c757d;
    font-size: 1.1rem;
    margin-bottom: 2rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.empty-state .btn {
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
}

@media (max-width: 768px) {
    .job-info-grid {
        grid-template-columns: 1fr;
    }
    
    .job-actions {
        flex-direction: column;
    }
    
    .btn-complete,
    .btn-contact {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="jobs-progress-container">
    <!-- Modern Header -->
    <div class="modern-header">
        <h1>
            <i class="fas fa-tasks"></i>
            Jobs in Progress
        </h1>
        <p>Active cleaning jobs that you're currently working on</p>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card active-jobs">
            <span class="stat-label">Active Jobs</span>
            <div class="stat-value"><?php echo count($jobs); ?></div>
        </div>
    </div>

    <!-- Jobs List -->
    <?php if (empty($jobs)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <h3>No Jobs in Progress</h3>
            <p>You don't have any active cleaning jobs at the moment. Once you start a job with the OTP code, it will appear here.</p>
            <a href="<?php echo base_url('cleaner/assigned_jobs'); ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i>
                View Assigned Jobs
            </a>
        </div>
    <?php else: ?>
        <div class="jobs-grid">
            <?php foreach ($jobs as $job): ?>
                <?php
                // Calculate time elapsed since job started
                $started_timestamp = strtotime($job->started_at);
                $current_timestamp = time();
                $time_elapsed_seconds = $current_timestamp - $started_timestamp;
                $hours_elapsed = floor($time_elapsed_seconds / 3600);
                $minutes_elapsed = floor(($time_elapsed_seconds % 3600) / 60);
                
                $is_str = (isset($job->property_type) && strtolower($job->property_type) === 'str');
                ?>
                <div class="job-card">
                    <div class="job-card-header">
                        <h3 class="job-title">
                            <?php echo htmlspecialchars($job->title); ?>
                            <?php if ($is_str): ?>
                                <span class="badge bg-warning text-dark ms-2">STR</span>
                            <?php endif; ?>
                        </h3>
                        <div class="job-meta">
                            <div class="job-meta-item">
                                <i class="fas fa-calendar-alt"></i>
                                <span><?php echo date('M j, Y', strtotime($job->scheduled_date)); ?></span>
                            </div>
                            <?php if (!empty($job->scheduled_time)): ?>
                                <div class="job-meta-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo date('g:i A', strtotime($job->scheduled_time)); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="job-meta-item">
                                <i class="fas fa-play-circle"></i>
                                <span>Started <?php echo time_ago($job->started_at); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="job-card-body">
                        <!-- Time Tracker -->
                        <div class="time-tracker">
                            <h6><i class="fas fa-stopwatch me-2"></i>Time Elapsed</h6>
                            <div class="time-elapsed">
                                <?php 
                                if ($hours_elapsed > 0) {
                                    echo $hours_elapsed . 'h ' . $minutes_elapsed . 'm';
                                } else {
                                    echo $minutes_elapsed . ' minutes';
                                }
                                ?>
                            </div>
                            <small style="color: #6c757d; margin-top: 0.5rem; display: block;">
                                Estimated: <?php echo ($job->estimated_duration / 60); ?> hours
                            </small>
                        </div>
                        
                        <!-- Job Information Grid -->
                        <div class="job-info-grid">
                            <!-- Host Information -->
                            <div class="info-section">
                                <h6><i class="fas fa-user-circle"></i> Host Information</h6>
                                <p class="mb-1">
                                    <strong><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></strong>
                                </p>
                                <?php if (!empty($job->host_phone)): ?>
                                    <p class="mb-0">
                                        <i class="fas fa-phone me-1"></i>
                                        <a href="tel:<?php echo htmlspecialchars($job->host_phone); ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
                                            <?php echo htmlspecialchars($job->host_phone); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Location -->
                            <div class="info-section">
                                <h6><i class="fas fa-map-marker-alt"></i> Location</h6>
                                <p class="mb-1">
                                    <strong><?php echo htmlspecialchars($job->address); ?></strong>
                                </p>
                                <p class="mb-0 text-muted">
                                    <?php echo htmlspecialchars($job->city . ', ' . $job->state); ?>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <?php if (!empty($job->description)): ?>
                            <div class="info-section" style="margin-bottom: 1.5rem;">
                                <h6><i class="fas fa-align-left"></i> Job Description</h6>
                                <p><?php echo nl2br(htmlspecialchars($job->description)); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Special Instructions -->
                        <?php if (!empty($job->special_instructions)): ?>
                            <div class="info-section" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.05) 100%); border-color: rgba(255, 193, 7, 0.3); margin-bottom: 1.5rem;">
                                <h6 style="color: #f57c00;"><i class="fas fa-exclamation-circle"></i> Special Instructions</h6>
                                <p><?php echo nl2br(htmlspecialchars($job->special_instructions)); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Action Buttons -->
                        <div class="job-actions">
                            <a href="<?php echo base_url('cleaner/jobs-in-progress/complete/' . $job->id); ?>" class="btn-complete">
                                <i class="fas fa-check-circle"></i>
                                Complete Job
                            </a>
                            <?php if (!empty($job->host_phone)): ?>
                                <a href="tel:<?php echo htmlspecialchars($job->host_phone); ?>" class="btn-contact">
                                    <i class="fas fa-phone"></i>
                                    Call Host
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
