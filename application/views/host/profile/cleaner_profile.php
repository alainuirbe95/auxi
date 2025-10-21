<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-user mr-2"></i>Cleaner Profile</h1>
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
        
        <!-- Context Info -->
        <div class="alert alert-info">
            <i class="icon fas fa-info-circle"></i>
            <strong>Viewing Cleaner Profile</strong><br>
            This cleaner made an offer on your job: <strong><?php echo htmlspecialchars($job->title); ?></strong> 
            (Offer: $<?php echo number_format($offer->offered_price, 2); ?>)
        </div>
        
        <div class="row">
            <!-- Left Column: Profile Info -->
            <div class="col-md-4">
                
                <!-- Profile Picture Card -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <?php if (!empty($profile->profile_picture_url)): ?>
                                <img class="profile-user-img img-fluid img-circle" 
                                     src="<?php echo base_url($profile->profile_picture_url); ?>" 
                                     alt="<?php echo htmlspecialchars($profile->username); ?>">
                            <?php else: ?>
                                <div class="profile-user-img img-fluid img-circle d-flex align-items-center justify-content-center bg-secondary text-white" 
                                     style="width: 128px; height: 128px; margin: 0 auto; font-size: 48px;">
                                    <i class="fas fa-broom"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="profile-username text-center mt-3"><?php echo htmlspecialchars($profile->username); ?></h3>
                        
                        <div class="text-center mb-3">
                            <span class="badge badge-success badge-lg">
                                <i class="fas fa-broom"></i> Cleaner
                            </span>
                        </div>
                        
                        <!-- Rating -->
                        <?php if ($profile->average_rating > 0): ?>
                        <div class="text-center mb-3">
                            <h4 class="mb-1">
                                <i class="fas fa-star text-warning"></i> 
                                <?php echo number_format($profile->average_rating, 1); ?>
                            </h4>
                            <p class="text-muted mb-0">
                                <?php echo $profile->total_reviews; ?> <?php echo $profile->total_reviews == 1 ? 'Review' : 'Reviews'; ?>
                            </p>
                        </div>
                        <?php else: ?>
                        <div class="text-center mb-3">
                            <p class="text-muted">No reviews yet</p>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Verification Badge -->
                        <div class="text-center mb-3">
                            <?php if ($profile->verification_status === 'verified'): ?>
                                <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            <?php elseif ($profile->verification_status === 'pending'): ?>
                                <span class="badge badge-warning">
                                    <i class="fas fa-clock"></i> Pending Verification
                                </span>
                            <?php else: ?>
                                <span class="badge badge-secondary">
                                    <i class="fas fa-question-circle"></i> Unverified
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Profile Completion -->
                        <p class="text-center mb-2">
                            <strong>Profile Completion</strong>
                        </p>
                        <div class="progress mb-3" style="height: 20px;">
                            <?php 
                            $progress_class = $completion['percentage'] >= 50 ? 'bg-success' : 'bg-warning';
                            ?>
                            <div class="progress-bar <?php echo $progress_class; ?>" 
                                 role="progressbar" 
                                 style="width: <?php echo $completion['percentage']; ?>%"
                                 aria-valuenow="<?php echo $completion['percentage']; ?>" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-address-card mr-2"></i>Contact Information</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-envelope mr-2"></i>Email</strong>
                        <p class="text-muted"><?php echo htmlspecialchars($profile->email); ?></p>
                        <hr>
                        
                        <strong><i class="fas fa-phone mr-2"></i>Phone</strong>
                        <p class="text-muted">
                            <?php echo !empty($profile->phone) ? htmlspecialchars($profile->phone) : 'Not provided'; ?>
                        </p>
                    </div>
                </div>
                
            </div>
            
            <!-- Right Column: Profile Details -->
            <div class="col-md-8">
                
                <!-- About -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user mr-2"></i>About</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($profile->bio)): ?>
                            <p><?php echo nl2br(htmlspecialchars($profile->bio)); ?></p>
                        <?php else: ?>
                            <p class="text-muted"><em>No bio provided yet.</em></p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Service Areas (Cleaners Only) -->
                <?php if (!empty($profile->service_areas)): ?>
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-map-marked-alt mr-2"></i>Service Areas</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        $service_areas = json_decode($profile->service_areas, true);
                        if (is_array($service_areas) && !empty($service_areas)):
                            foreach ($service_areas as $area): ?>
                                <span class="badge badge-success mr-1 mb-1" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($area); ?>
                                </span>
                            <?php endforeach;
                        else: ?>
                            <p class="text-muted mb-0"><em>No service areas specified.</em></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Specialties (Cleaners Only) -->
                <?php if (!empty($profile->specialties)): ?>
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-star mr-2"></i>Specialties</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                        $specialties = json_decode($profile->specialties, true);
                        if (is_array($specialties) && !empty($specialties)):
                            foreach ($specialties as $specialty): ?>
                                <span class="badge badge-info mr-1 mb-1" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($specialty); ?>
                                </span>
                            <?php endforeach;
                        else: ?>
                            <p class="text-muted mb-0"><em>No specialties specified.</em></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Job Statistics -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar mr-2"></i>Work Statistics</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="info-box bg-info">
                                    <span class="info-box-icon"><i class="fas fa-handshake"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Offers Made</span>
                                        <span class="info-box-number"><?php echo isset($job_stats['total_offers_made']) ? $job_stats['total_offers_made'] : 0; ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Accepted</span>
                                        <span class="info-box-number"><?php echo isset($job_stats['offers_accepted']) ? $job_stats['offers_accepted'] : 0; ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4 col-sm-6">
                                <div class="info-box bg-primary">
                                    <span class="info-box-icon"><i class="fas fa-clipboard-check"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Completed</span>
                                        <span class="info-box-number"><?php echo isset($job_stats['jobs_completed']) ? $job_stats['jobs_completed'] : 0; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (isset($job_stats['total_offers_made']) && $job_stats['total_offers_made'] > 0): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="progress-group">
                                    <span class="progress-text">Success Rate</span>
                                    <span class="float-right">
                                        <b><?php echo isset($job_stats['jobs_completed']) && $job_stats['total_offers_made'] > 0 ? round(($job_stats['jobs_completed'] / $job_stats['total_offers_made']) * 100) : 0; ?>%</b>
                                    </span>
                                    <div class="progress progress-sm">
                                        <div class="progress-bar bg-success" 
                                             style="width: <?php echo isset($job_stats['jobs_completed']) && $job_stats['total_offers_made'] > 0 ? round(($job_stats['jobs_completed'] / $job_stats['total_offers_made']) * 100) : 0; ?>%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Recent Reviews -->
                <?php if (!empty($reviews) && count($reviews) > 0): ?>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-comments mr-2"></i>Recent Reviews</h3>
                    </div>
                    <div class="card-body">
                        <?php foreach ($reviews as $review): ?>
                        <div class="review-item mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <strong><?php echo htmlspecialchars($review->reviewer_username ?? 'Anonymous'); ?></strong>
                                    <div class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <?php if ($i <= $review->rating): ?>
                                                <i class="fas fa-star"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <?php 
                                    $review_date = new DateTime($review->created_at);
                                    echo $review_date->format('M j, Y'); 
                                    ?>
                                </small>
                            </div>
                            <p class="mb-0"><?php echo htmlspecialchars($review->comment); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Member Since -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-calendar mr-2"></i>Member Information</h3>
                    </div>
                    <div class="card-body">
                        <strong><i class="fas fa-calendar-plus mr-2"></i>Member Since</strong>
                        <p class="text-muted">
                            <?php 
                            $created = new DateTime($profile->user_created_at);
                            echo $created->format('F j, Y'); 
                            ?>
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Back Button -->
        <div class="row">
            <div class="col-12">
                <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Offers
                </a>
            </div>
        </div>
        
    </div>
</section>

