<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Complete Your Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <?php foreach ($breadcrumbs as $crumb): ?>
                        <?php if (isset($crumb['active']) && $crumb['active']): ?>
                            <li class="breadcrumb-item active"><?php echo $crumb['title']; ?></li>
                        <?php else: ?>
                            <li class="breadcrumb-item"><a href="<?php echo base_url($crumb['url']); ?>"><?php echo $crumb['title']; ?></a></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-md-8 offset-md-2">
                
                <!-- Warning Card -->
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Profile Completion Required</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                <i class="fas fa-user-lock" style="font-size: 80px; color: #ffc107;"></i>
                            </div>
                            <h3 class="text-warning">You Cannot Post Jobs Yet</h3>
                            <p class="lead">Your profile is only <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong> complete.</p>
                        </div>
                        
                        <div class="alert alert-warning">
                            <h5><i class="icon fas fa-info-circle"></i> Why is this required?</h5>
                            <p class="mb-0">
                                To maintain quality and trust in our marketplace, all hosts must have at least 
                                <strong>50% profile completion</strong> before posting jobs. This helps cleaners 
                                learn more about you and your cleaning needs, resulting in better matches and 
                                more successful jobs.
                            </p>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <h5 class="mb-2">Profile Completion Progress</h5>
                            <div class="progress" style="height: 30px;">
                                <div class="progress-bar bg-warning" 
                                     role="progressbar" 
                                     style="width: <?php echo $completion['percentage']; ?>%"
                                     aria-valuenow="<?php echo $completion['percentage']; ?>" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong>
                                </div>
                            </div>
                            <p class="text-muted mt-2 mb-0">
                                You need <strong><?php echo 50 - $completion['percentage']; ?>%</strong> more to unlock job posting
                            </p>
                        </div>
                        
                        <!-- Missing Information -->
                        <?php if (!empty($completion['missing'])): ?>
                        <div class="mb-4">
                            <h5 class="mb-3">
                                <i class="fas fa-list-check mr-2"></i>
                                Complete These Required Fields
                            </h5>
                            <div class="list-group">
                                <?php foreach ($completion['missing'] as $item): ?>
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-circle-notch text-warning mr-2"></i>
                                        <?php echo htmlspecialchars($item); ?>
                                    </span>
                                    <span class="badge badge-warning">Required</span>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Host Profile Requirements -->
                        <div class="callout callout-info">
                            <h5><i class="fas fa-clipboard-list mr-2"></i>Host Profile Requirements (50% = 50 points)</h5>
                            <ul class="mb-0">
                                <li><strong>Basic Info</strong> (Name, Email) - 25 points <span class="text-success"><i class="fas fa-check"></i> Auto-complete</span></li>
                                <li><strong>Bio</strong> (At least 30 characters) - 15 points</li>
                                <li><strong>Phone Number</strong> - 15 points</li>
                                <li><strong>Profile Picture</strong> - 20 points <span class="text-muted">(Coming soon)</span></li>
                                <li><strong>Location</strong> (Address, City, State) - 25 points</li>
                            </ul>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="text-center mt-4">
                            <a href="<?php echo base_url('host/edit-profile'); ?>" class="btn btn-warning btn-lg">
                                <i class="fas fa-edit"></i> Complete My Profile Now
                            </a>
                            <a href="<?php echo base_url('host'); ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Benefits Card -->
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-check-circle mr-2"></i>
                            <strong>Benefits of a Complete Profile</strong>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5><i class="fas fa-users text-success mr-2"></i>More Offers</h5>
                                    <p class="text-muted">Cleaners are more likely to bid on jobs from hosts with complete profiles.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5><i class="fas fa-handshake text-success mr-2"></i>Better Matches</h5>
                                    <p class="text-muted">Detailed profiles help match you with cleaners who fit your needs.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5><i class="fas fa-shield-alt text-success mr-2"></i>Build Trust</h5>
                                    <p class="text-muted">A complete profile shows you're a serious and reliable host.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <h5><i class="fas fa-star text-success mr-2"></i>Higher Quality</h5>
                                    <p class="text-muted">Get better quality service from verified, professional cleaners.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
        
    </div>
</section>

