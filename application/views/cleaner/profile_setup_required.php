<div class="container-fluid">
    <!-- Profile Setup Required Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="setup-required-header">
                <div class="header-content">
                    <div class="icon-wrapper">
                        <i class="fas fa-user-cog"></i>
                    </div>
                    <div class="text-content">
                        <h2 class="page-title">Profile Setup Required</h2>
                        <p class="page-subtitle">Complete your profile to start browsing and applying for cleaning jobs</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Completion Status -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="completion-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-primary me-2"></i>
                        Profile Completion Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="completion-progress">
                        <div class="progress-circle">
                            <svg class="progress-ring" width="120" height="120">
                                <circle class="progress-ring-circle" 
                                        stroke="#e9ecef" 
                                        stroke-width="8" 
                                        fill="transparent" 
                                        r="52" 
                                        cx="60" 
                                        cy="60"/>
                                <circle class="progress-ring-circle progress-ring-fill" 
                                        stroke="#667eea" 
                                        stroke-width="8" 
                                        fill="transparent" 
                                        r="52" 
                                        cx="60" 
                                        cy="60" 
                                        style="stroke-dasharray: 326.73; stroke-dashoffset: <?php echo 326.73 - (326.73 * $completion['percentage'] / 100); ?>;"/>
                            </svg>
                            <div class="progress-text">
                                <span class="percentage"><?php echo $completion['percentage']; ?>%</span>
                                <span class="label">Complete</span>
                            </div>
                        </div>
                        <div class="completion-details">
                            <h4>Minimum Required: 50%</h4>
                            <p class="text-muted mb-3">
                                You need to complete at least 50% of your profile to access job listings and make offers.
                            </p>
                            <div class="missing-items">
                                <h6 class="mb-2">Missing Requirements:</h6>
                                <ul class="list-unstyled">
                                    <?php foreach ($completion['missing'] as $item): ?>
                                        <li class="missing-item">
                                            <i class="fas fa-times-circle text-danger me-2"></i>
                                            <?php echo htmlspecialchars($item); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Setup Guide -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="setup-guide-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-check text-success me-2"></i>
                        Quick Setup Guide
                    </h5>
                </div>
                <div class="card-body">
                    <div class="setup-steps">
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="step-content">
                                <h6>Basic Information</h6>
                                <p class="text-muted">Ensure your name and email are complete</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-file-text"></i>
                            </div>
                            <div class="step-content">
                                <h6>Write Your Bio</h6>
                                <p class="text-muted">Tell hosts about yourself (minimum 50 characters)</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="step-content">
                                <h6>Add Phone Number</h6>
                                <p class="text-muted">So hosts can contact you directly</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="step-content">
                                <h6>Upload Profile Picture</h6>
                                <p class="text-muted">Help hosts recognize you</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div class="step-content">
                                <h6>Set Service Areas</h6>
                                <p class="text-muted">Choose the areas where you can work</p>
                            </div>
                        </div>
                        <div class="step-item">
                            <div class="step-icon">
                                <i class="fas fa-broom"></i>
                            </div>
                            <div class="step-content">
                                <h6>Add Cleaning Specialties</h6>
                                <p class="text-muted">Specify what types of cleaning you do</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="action-buttons">
                <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-primary btn-lg">
                    <i class="fas fa-edit me-2"></i>
                    Complete My Profile
                </a>
                <a href="<?php echo base_url('cleaner/my-profile'); ?>" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-eye me-2"></i>
                    View Current Profile
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* Profile Setup Required Styles */
.container-fluid {
    max-width: 95% !important;
    margin: 0 auto !important;
    padding: 0 20px;
}

/* Setup Required Header */
.setup-required-header {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.1) 100%);
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    border: 2px solid rgba(255, 193, 7, 0.2);
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
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
    font-size: 1.1rem;
}

/* Completion Card */
.completion-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.completion-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.completion-progress {
    display: flex;
    align-items: center;
    gap: 2rem;
    padding: 2rem;
}

.progress-circle {
    position: relative;
    flex-shrink: 0;
}

.progress-ring {
    transform: rotate(-90deg);
}

.progress-ring-circle {
    transition: stroke-dashoffset 0.5s ease-in-out;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.percentage {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
}

.label {
    display: block;
    font-size: 0.8rem;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.completion-details h4 {
    color: #333;
    margin-bottom: 0.5rem;
}

.missing-items {
    margin-top: 1rem;
}

.missing-item {
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.missing-item:last-child {
    border-bottom: none;
}

/* Setup Guide Card */
.setup-guide-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.setup-guide-card .card-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.setup-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    padding: 2rem;
}

.step-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.step-item:hover {
    background: #e9ecef;
    transform: translateY(-2px);
}

.step-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.step-content h6 {
    color: #333;
    margin-bottom: 0.25rem;
    font-weight: 600;
}

.step-content p {
    margin: 0;
    font-size: 0.9rem;
}

/* Action Buttons */
.action-buttons {
    text-align: center;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.action-buttons .btn {
    margin: 0 0.5rem;
    padding: 0.75rem 2rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    border-color: #6c757d;
    color: white;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        max-width: 100%;
        padding: 0 15px;
    }
    
    .header-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .completion-progress {
        flex-direction: column;
        text-align: center;
        gap: 1.5rem;
    }
    
    .setup-steps {
        grid-template-columns: 1fr;
    }
    
    .action-buttons .btn {
        display: block;
        width: 100%;
        margin: 0.5rem 0;
    }
}
</style>
