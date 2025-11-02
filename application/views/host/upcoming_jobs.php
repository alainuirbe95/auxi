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
.upcoming-jobs-container {
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

/* Stats Section */
.stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-box {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
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
    position: relative;
}

.job-status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-assigned {
    background: rgba(23, 162, 184, 0.9);
    color: white;
}

.status-in-progress {
    background: rgba(255, 193, 7, 0.9);
    color: #000;
}

.job-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    padding-right: 120px;
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

/* Two Column Layout */
.job-content-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 12px;
    padding: 1.25rem;
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
    margin-bottom: 0.5rem;
    color: #4a5568;
    line-height: 1.6;
}

.info-section p:last-child {
    margin-bottom: 0;
}

.cleaner-contact-section {
    background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.05) 100%);
    border-color: rgba(67, 233, 123, 0.3);
}

.cleaner-contact-section h6 {
    color: #20c997;
}

.cleaner-contact-section a {
    color: #20c997;
    text-decoration: none;
    font-weight: 600;
}

.cleaner-contact-section a:hover {
    text-decoration: underline;
}

.description-section {
    grid-column: 1 / -1;
}

.job-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    padding-top: 1rem;
    border-top: 1px solid rgba(102, 126, 234, 0.2);
}

.btn-view-job {
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

.btn-view-job:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    text-decoration: none;
}

.btn-contact {
    background: white;
    color: #20c997;
    border: 2px solid #20c997;
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
    background: #20c997;
    color: white;
    transform: translateY(-2px);
    text-decoration: none;
}

/* OTP Display */
.otp-display {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe8cc 100%);
    border: 2px solid #ffc107;
    border-radius: 12px;
    padding: 1rem;
    text-align: center;
    margin-top: 1rem;
}

.otp-display h6 {
    color: #e65100;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.otp-code {
    font-size: 2rem;
    font-weight: 800;
    color: #f57c00;
    letter-spacing: 0.3em;
    font-family: monospace;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    transition: all 0.3s ease;
}

.empty-state .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

@media (max-width: 768px) {
    .job-content-grid {
        grid-template-columns: 1fr;
    }
    
    .job-actions {
        flex-direction: column;
    }
    
    .btn-view-job,
    .btn-contact {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="upcoming-jobs-container">
    <!-- Modern Header -->
    <div class="modern-header">
        <h1>
            <i class="fas fa-calendar-check"></i>
            Upcoming Jobs
        </h1>
        <p>Manage your assigned and in-progress cleaning jobs</p>
    </div>

    <!-- Stats -->
    <div class="stats-row">
        <div class="stat-box">
            <span class="stat-label">Total Upcoming</span>
            <div class="stat-value"><?php echo count($jobs); ?></div>
        </div>
        
        <?php
        $assigned_count = 0;
        $in_progress_count = 0;
        foreach ($jobs as $job) {
            if ($job->status === 'assigned') $assigned_count++;
            if ($job->status === 'in_progress') $in_progress_count++;
        }
        ?>
        
        <div class="stat-box" style="border-left-color: #17a2b8;">
            <span class="stat-label">Assigned</span>
            <div class="stat-value"><?php echo $assigned_count; ?></div>
        </div>
        
        <div class="stat-box" style="border-left-color: #ffc107;">
            <span class="stat-label">In Progress</span>
            <div class="stat-value"><?php echo $in_progress_count; ?></div>
        </div>
    </div>

    <!-- Jobs List -->
    <?php if (empty($jobs)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3>No Upcoming Jobs</h3>
            <p>You don't have any assigned or in-progress jobs at the moment. Create a new job or check your job offers.</p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Create New Job
                </a>
                <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-secondary">
                    <i class="fas fa-handshake me-2"></i>
                    View Offers
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="jobs-grid">
            <?php foreach ($jobs as $job): ?>
                <?php
                $is_str = (isset($job->property_type) && strtolower($job->property_type) === 'str');
                $status_display = $job->status === 'assigned' ? 'Assigned' : 'In Progress';
                $status_class = $job->status === 'assigned' ? 'status-assigned' : 'status-in-progress';
                ?>
                <div class="job-card">
                    <div class="job-card-header">
                        <span class="job-status-badge <?php echo $status_class; ?>">
                            <i class="fas fa-<?php echo $job->status === 'assigned' ? 'clipboard-check' : 'tasks'; ?>"></i>
                            <?php echo $status_display; ?>
                        </span>
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
                                <i class="fas fa-dollar-sign"></i>
                                <span>$<?php echo number_format($job->final_price ?? $job->accepted_price ?? $job->suggested_price, 2); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="job-card-body">
                        <!-- Job Information Grid -->
                        <div class="job-content-grid">
                            <!-- Job Details -->
                            <div>
                                <?php if (!empty($job->description)): ?>
                                    <div class="info-section" style="margin-bottom: 1rem;">
                                        <h6><i class="fas fa-align-left"></i> Job Description</h6>
                                        <p><?php echo nl2br(htmlspecialchars($job->description)); ?></p>
                                    </div>
                                <?php endif; ?>
                                
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
                            
                            <!-- Cleaner Contact -->
                            <div class="cleaner-contact-section">
                                <h6><i class="fas fa-user-check"></i> Assigned Cleaner</h6>
                                <p class="mb-1">
                                    <strong><?php echo htmlspecialchars(($job->cleaner_first_name ?? '') . ' ' . ($job->cleaner_last_name ?? '')); ?></strong>
                                </p>
                                <p class="mb-1">
                                    <i class="fas fa-at me-1"></i>
                                    <?php echo htmlspecialchars($job->cleaner_username ?? 'N/A'); ?>
                                </p>
                                <?php if (!empty($job->cleaner_email)): ?>
                                    <p class="mb-1">
                                        <i class="fas fa-envelope me-1"></i>
                                        <a href="mailto:<?php echo htmlspecialchars($job->cleaner_email); ?>">
                                            <?php echo htmlspecialchars($job->cleaner_email); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($job->cleaner_phone)): ?>
                                    <p class="mb-0">
                                        <i class="fas fa-phone me-1"></i>
                                        <a href="tel:<?php echo htmlspecialchars($job->cleaner_phone); ?>">
                                            <?php echo htmlspecialchars($job->cleaner_phone); ?>
                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Special Instructions -->
                        <?php if (!empty($job->special_instructions)): ?>
                            <div class="info-section description-section" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.05) 100%); border-color: rgba(255, 193, 7, 0.3); margin-bottom: 1.5rem;">
                                <h6 style="color: #f57c00;"><i class="fas fa-exclamation-circle"></i> Special Instructions</h6>
                                <p><?php echo nl2br(htmlspecialchars($job->special_instructions)); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- OTP Code Display -->
                        <?php if (!empty($job->otp_code)): ?>
                            <div class="otp-display">
                                <h6><i class="fas fa-key me-2"></i>Service Code (Share with Cleaner)</h6>
                                <div class="otp-code"><?php echo $job->otp_code; ?></div>
                                <small style="color: #6c757d; margin-top: 0.5rem; display: block;">
                                    The cleaner needs this code to start the job
                                </small>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Action Buttons -->
                        <div class="job-actions">
                            <a href="<?php echo base_url('host/job/' . $job->id); ?>" class="btn-view-job">
                                <i class="fas fa-eye"></i>
                                View Full Details
                            </a>
                            <?php if (!empty($job->cleaner_phone)): ?>
                                <a href="tel:<?php echo htmlspecialchars($job->cleaner_phone); ?>" class="btn-contact">
                                    <i class="fas fa-phone"></i>
                                    Call Cleaner
                                </a>
                            <?php endif; ?>
                            <?php if (!empty($job->cleaner_email)): ?>
                                <a href="mailto:<?php echo htmlspecialchars($job->cleaner_email); ?>" class="btn-contact">
                                    <i class="fas fa-envelope"></i>
                                    Email Cleaner
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
