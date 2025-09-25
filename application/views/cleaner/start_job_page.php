<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('text')): ?>
                <div class="alert alert-<?php echo $this->session->flashdata('type') === 'error' ? 'danger' : 'success'; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $this->session->flashdata('type') === 'error' ? 'exclamation-triangle' : 'check-circle'; ?> me-2"></i>
                    <?php echo $this->session->flashdata('text'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Start Job Card -->
            <div class="card start-job-card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-play-circle me-2"></i>
                        Start Cleaning Job
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Job Information -->
                    <div class="job-info-section">
                        <h4 class="job-title">
                            <i class="fas fa-clipboard-list me-2"></i>
                            <?php echo htmlspecialchars($job->title); ?>
                        </h4>
                        
                        <div class="job-details-grid">
                            <div class="job-detail-item">
                                <i class="fas fa-calendar-alt"></i>
                                <div>
                                    <strong>Date & Time:</strong><br>
                                    <?php 
                                    if (!empty($job->scheduled_date) && !empty($job->scheduled_time)) {
                                        $datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                                        echo date('M j, Y g:i A', strtotime($datetime));
                                    } else {
                                        echo 'Flexible';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="job-detail-item">
                                <i class="fas fa-dollar-sign"></i>
                                <div>
                                    <strong>Accepted Price:</strong><br>
                                    $<?php echo number_format($job->accepted_price, 2); ?>
                                </div>
                            </div>
                            
                            <div class="job-detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <div>
                                    <strong>Location:</strong><br>
                                    <?php echo htmlspecialchars($job->address); ?><br>
                                    <small><?php echo htmlspecialchars($job->city . ', ' . $job->state); ?></small>
                                </div>
                            </div>
                            
                            <div class="job-detail-item">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <strong>Duration:</strong><br>
                                    <?php echo $job->estimated_duration; ?> minutes
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($job->description)): ?>
                            <div class="job-description">
                                <strong>Description:</strong>
                                <p><?php echo nl2br(htmlspecialchars($job->description)); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($job->special_instructions)): ?>
                            <div class="job-instructions">
                                <strong>Special Instructions:</strong>
                                <p><?php echo nl2br(htmlspecialchars($job->special_instructions)); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- OTP Entry Section -->
                    <div class="otp-section">
                        <div class="otp-header">
                            <h4>
                                <i class="fas fa-key me-2"></i>
                                Enter Service Code
                            </h4>
                            <p class="text-muted">
                                Please enter the 6-digit service code provided by the host to start this cleaning job.
                            </p>
                        </div>
                        
                        <form method="POST" action="<?php echo base_url('cleaner/start_job'); ?>" class="otp-form">
                            <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
                            
                            <div class="otp-input-group">
                                <label for="otp_code" class="form-label">Service Code</label>
                                <input type="text" 
                                       class="form-control otp-input" 
                                       id="otp_code" 
                                       name="otp_code" 
                                       maxlength="6" 
                                       placeholder="000000"
                                       pattern="[0-9]{6}"
                                       required
                                       autocomplete="off">
                                <small class="form-text text-muted">
                                    Enter the 6-digit code shared by the host
                                </small>
                            </div>
                            
                            <div class="otp-actions">
                                <a href="<?php echo base_url('cleaner/assigned_jobs'); ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Back to Assigned Jobs
                                </a>
                                <button type="submit" class="btn btn-success btn-start">
                                    <i class="fas fa-play-circle me-1"></i>
                                    Start Job
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.start-job-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.start-job-card .card-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-bottom: none;
    padding: 1.5rem;
}

.start-job-card .card-header h3 {
    margin: 0;
    font-weight: 600;
}

.card-body {
    padding: 2rem;
}

.job-info-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #e9ecef;
}

.job-title {
    color: #2d3748;
    margin-bottom: 1.5rem;
    font-weight: 600;
}

.job-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.job-detail-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #28a745;
}

.job-detail-item i {
    font-size: 1.5rem;
    color: #28a745;
    margin-top: 0.25rem;
}

.job-description, .job-instructions {
    margin-top: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #17a2b8;
}

.otp-section {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    padding: 2rem;
    border-radius: 15px;
    border: 2px solid #e1f5fe;
}

.otp-header {
    text-align: center;
    margin-bottom: 2rem;
}

.otp-header h4 {
    color: #28a745;
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.otp-input-group {
    max-width: 400px;
    margin: 0 auto 2rem auto;
}

.otp-input {
    font-size: 2rem;
    text-align: center;
    letter-spacing: 0.5em;
    font-weight: 700;
    padding: 1rem;
    border: 3px solid #28a745;
    border-radius: 15px;
    background: white;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
}

.otp-input:focus {
    border-color: #20c997;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    outline: none;
}

.otp-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    align-items: center;
}

.btn-start {
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 10px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
    transition: all 0.3s ease;
}

.btn-start:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
}

.btn-secondary {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 500;
}

.form-text {
    text-align: center;
    margin-top: 0.5rem;
    color: #6c757d;
}

@media (max-width: 768px) {
    .job-details-grid {
        grid-template-columns: 1fr;
    }
    
    .otp-actions {
        flex-direction: column;
    }
    
    .otp-input {
        font-size: 1.5rem;
        letter-spacing: 0.3em;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp_code');
    
    if (otpInput) {
        // Focus on input when page loads
        otpInput.focus();
        
        // Only allow numbers
        otpInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
        
        // Handle paste
        otpInput.addEventListener('paste', function(e) {
            setTimeout(function() {
                otpInput.value = otpInput.value.replace(/[^0-9]/g, '').substring(0, 6);
            }, 10);
        });
        
        // Auto-submit when 6 digits are entered
        otpInput.addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                setTimeout(function() {
                    if (confirm('Are you ready to start this job? This will mark the job as in progress.')) {
                        document.querySelector('.otp-form').submit();
                    }
                }, 500);
            }
        });
    }
});
</script>
