<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="row">
                    <div class="col-md-3">
                        <div class="profile-photo">
                            <?php if ($profile->profile_picture_url): ?>
                                <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile Picture" class="img-fluid rounded-circle">
                            <?php else: ?>
                                <div class="default-avatar">
                                    <i class="fas fa-user fa-3x"></i>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Verification Badge -->
                            <?php if ($profile->verification_status == 'verified'): ?>
                                <div class="verification-badge">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Verified</span>
                                </div>
                            <?php elseif ($profile->verification_status == 'pending'): ?>
                                <div class="verification-badge pending">
                                    <i class="fas fa-clock"></i>
                                    <span>Pending</span>
                                </div>
                            <?php else: ?>
                                <div class="verification-badge not-verified">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Not Verified</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-9">
                        <div class="profile-info">
                            <h1 class="profile-name">
                                <?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>
                                <span class="username">@<?php echo htmlspecialchars($profile->username); ?></span>
                            </h1>
                            
                            <!-- Rating Summary -->
                            <div class="rating-summary">
                                <div class="rating-display">
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?php echo ($i <= floor($review_stats['average_rating'])) ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                    <span class="rating-number"><?php echo number_format($review_stats['average_rating'], 1); ?></span>
                                    <span class="rating-count">(<?php echo $review_stats['total_reviews']; ?> review<?php echo $review_stats['total_reviews'] != 1 ? 's' : ''; ?>)</span>
                                </div>
                            </div>
                            
                            <!-- Quick Stats -->
                            <div class="quick-stats">
                                <div class="stat-item">
                                    <i class="fas fa-briefcase"></i>
                                    <span><?php echo $profile->total_jobs_completed; ?> jobs completed</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $profile->response_time_avg; ?>min avg response</span>
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-percentage"></i>
                                    <span><?php echo $profile->completion_rate; ?>% completion rate</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Actions -->
            <div class="profile-actions-bar">
                <div class="row">
                    <div class="col-md-8">
                        <div class="action-buttons">
                            <a href="<?php echo base_url('userprofile/edit'); ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Profile
                            </a>
                            <a href="<?php echo base_url('userprofile/verification'); ?>" class="btn btn-outline-success">
                                <i class="fas fa-shield-alt"></i> 
                                <?php echo ($profile->verification_status != 'verified') ? 'Get Verified' : 'Verification Status'; ?>
                            </a>
                            <a href="<?php echo base_url('userprofile/settings'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <div class="profile-status">
                            <span class="status-badge <?php echo $profile->is_public ? 'public' : 'private'; ?>">
                                <i class="fas fa-<?php echo $profile->is_public ? 'globe' : 'lock'; ?>"></i>
                                <?php echo $profile->is_public ? 'Public' : 'Private'; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="row mt-4">
                <!-- Left Column -->
                <div class="col-md-8">
                    <!-- About Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3><i class="fas fa-user"></i> About</h3>
                            <a href="<?php echo base_url('userprofile/edit'); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        </div>
                        <div class="bio-content">
                            <?php if ($profile->bio): ?>
                                <?php echo nl2br(htmlspecialchars($profile->bio)); ?>
                            <?php else: ?>
                                <p class="text-muted">No bio added yet. <a href="<?php echo base_url('userprofile/edit'); ?>">Add one now</a></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Specialties Section -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3><i class="fas fa-star"></i> Specialties</h3>
                            <a href="<?php echo base_url('userprofile/edit'); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        </div>
                        <div class="specialties-list">
                            <?php if ($profile->specialties): ?>
                                <?php 
                                $specialties = json_decode($profile->specialties, true);
                                $specialty_labels = array(
                                    'residential' => 'Residential Cleaning',
                                    'office' => 'Office Cleaning',
                                    'deep_cleaning' => 'Deep Cleaning',
                                    'move_in_out' => 'Move In/Out Cleaning',
                                    'post_construction' => 'Post-Construction Cleaning',
                                    'green_cleaning' => 'Green/Eco-Friendly Cleaning',
                                    'window_cleaning' => 'Window Cleaning',
                                    'carpet_cleaning' => 'Carpet Cleaning'
                                );
                                
                                if ($specialties):
                                    foreach ($specialties as $specialty):
                                        if (isset($specialty_labels[$specialty])):
                                ?>
                                    <span class="specialty-badge">
                                        <i class="fas fa-check"></i>
                                        <?php echo htmlspecialchars($specialty_labels[$specialty]); ?>
                                    </span>
                                <?php 
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            <?php else: ?>
                                <p class="text-muted">No specialties added yet. <a href="<?php echo base_url('userprofile/edit'); ?>">Add some now</a></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Service Areas -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3><i class="fas fa-map-marker-alt"></i> Service Areas</h3>
                            <a href="<?php echo base_url('userprofile/edit'); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        </div>
                        <div class="service-areas-list">
                            <?php if ($profile->service_areas): ?>
                                <?php 
                                $service_areas = json_decode($profile->service_areas, true);
                                $area_labels = array(
                                    'downtown' => 'Downtown',
                                    'suburbs' => 'Suburbs',
                                    'north_side' => 'North Side',
                                    'south_side' => 'South Side',
                                    'east_side' => 'East Side',
                                    'west_side' => 'West Side'
                                );
                                
                                if ($service_areas):
                                    foreach ($service_areas as $area):
                                        if (isset($area_labels[$area])):
                                ?>
                                    <span class="area-badge">
                                        <i class="fas fa-map-pin"></i>
                                        <?php echo htmlspecialchars($area_labels[$area]); ?>
                                    </span>
                                <?php 
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            <?php else: ?>
                                <p class="text-muted">No service areas added yet. <a href="<?php echo base_url('userprofile/edit'); ?>">Add some now</a></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Reviews Received -->
                    <div class="profile-section">
                        <div class="section-header">
                            <h3><i class="fas fa-star"></i> Reviews Received</h3>
                            <a href="<?php echo base_url('reviews/my_reviews'); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                        
                        <?php if (!empty($reviews_received)): ?>
                            <?php foreach ($reviews_received as $review): ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="reviewer-info">
                                        <strong><?php echo htmlspecialchars($review->reviewer_first_name . ' ' . $review->reviewer_last_name); ?></strong>
                                        <span class="review-date"><?php echo date('M j, Y', strtotime($review->created_at)); ?></span>
                                    </div>
                                    <div class="review-rating">
                                        <div class="stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <span class="star <?php echo ($i <= $review->rating) ? 'filled' : ''; ?>">★</span>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($review->comment): ?>
                                <div class="review-comment">
                                    <?php echo nl2br(htmlspecialchars($review->comment)); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="no-reviews">
                                <i class="fas fa-star fa-2x text-muted"></i>
                                <p class="text-muted">No reviews received yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-4">
                    <!-- Profile Stats -->
                    <div class="profile-widget">
                        <h4>Profile Statistics</h4>
                        <div class="stats-list">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Average Rating</div>
                                    <div class="stat-value"><?php echo number_format($review_stats['average_rating'], 1); ?>/5</div>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-comment"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Reviews</div>
                                    <div class="stat-value"><?php echo $review_stats['total_reviews']; ?></div>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-briefcase"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Jobs Completed</div>
                                    <div class="stat-value"><?php echo $profile->total_jobs_completed; ?></div>
                                </div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Completion Rate</div>
                                    <div class="stat-value"><?php echo $profile->completion_rate; ?>%</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Jobs -->
                    <?php if (!empty($recent_jobs)): ?>
                    <div class="profile-widget">
                        <h4>Recent Jobs</h4>
                        <div class="recent-jobs">
                            <?php foreach ($recent_jobs as $job): ?>
                            <div class="job-item">
                                <h6><a href="<?php echo base_url('jobs/view/' . $job->id); ?>"><?php echo htmlspecialchars($job->title); ?></a></h6>
                                <p class="job-status">
                                    <span class="badge badge-<?php echo $job->status == 'completed' ? 'success' : 'primary'; ?>">
                                        <?php echo ucfirst($job->status); ?>
                                    </span>
                                </p>
                                <p class="job-date"><?php echo date('M j, Y', strtotime($job->created_at)); ?></p>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Quick Actions -->
                    <div class="profile-widget">
                        <h4>Quick Actions</h4>
                        <div class="quick-actions">
                            <a href="<?php echo base_url('jobs/create'); ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-plus"></i> Create New Job
                            </a>
                            <a href="<?php echo base_url('userprofile/search'); ?>" class="btn btn-outline-primary btn-block">
                                <i class="fas fa-search"></i> Find Cleaners
                            </a>
                            <a href="<?php echo base_url('reviews/pending'); ?>" class="btn btn-outline-warning btn-block">
                                <i class="fas fa-star"></i> Pending Reviews
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 40px 0;
    border-radius: 10px;
    margin-bottom: 20px;
}

.profile-photo {
    position: relative;
    text-align: center;
}

.profile-photo img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.default-avatar {
    width: 150px;
    height: 150px;
    background: rgba(255,255,255,0.2);
    border: 4px solid white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.verification-badge {
    position: absolute;
    bottom: 10px;
    right: 10px;
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: bold;
}

.verification-badge.verified {
    background: #28a745;
}

.verification-badge.pending {
    background: #ffc107;
}

.verification-badge.not-verified {
    background: #dc3545;
}

.profile-name {
    margin-bottom: 10px;
}

.username {
    font-size: 1rem;
    font-weight: normal;
    opacity: 0.8;
}

.rating-summary {
    margin-bottom: 20px;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 10px;
}

.stars {
    display: inline-block;
    font-size: 1.5rem;
}

.star {
    color: #ddd;
}

.star.filled {
    color: #ffc107;
}

.rating-number {
    font-size: 1.5rem;
    font-weight: bold;
}

.rating-count {
    color: rgba(255,255,255,0.8);
}

.quick-stats {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.9rem;
}

.stat-item i {
    opacity: 0.8;
}

.profile-actions-bar {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.status-badge {
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
}

.status-badge.public {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.status-badge.private {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.profile-section {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.profile-section h3 {
    color: #007bff;
    margin-bottom: 0;
    font-size: 1.2rem;
}

.bio-content {
    line-height: 1.6;
    color: #555;
}

.specialties-list,
.service-areas-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.specialty-badge,
.area-badge {
    background: #e7f3ff;
    color: #007bff;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    border: 1px solid #b3d7ff;
}

.specialty-badge i,
.area-badge i {
    margin-right: 5px;
}

.review-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    background: #f8f9fa;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.reviewer-info strong {
    color: #007bff;
}

.review-date {
    color: #6c757d;
    font-size: 0.9rem;
    margin-left: 10px;
}

.review-comment {
    line-height: 1.6;
    color: #555;
}

.no-reviews {
    text-align: center;
    padding: 40px 20px;
}

.profile-widget {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-bottom: 20px;
}

.profile-widget h4 {
    color: #007bff;
    margin-bottom: 15px;
    font-size: 1.1rem;
}

.stats-list {
    margin-top: 10px;
}

.stats-list .stat-item {
    display: flex;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.stats-list .stat-item:last-child {
    border-bottom: none;
}

.stat-icon {
    width: 40px;
    height: 40px;
    background: #e7f3ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
}

.stat-icon i {
    color: #007bff;
    font-size: 1.2rem;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 2px;
}

.stat-value {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
}

.job-item {
    padding: 10px 0;
    border-bottom: 1px solid #e9ecef;
}

.job-item:last-child {
    border-bottom: none;
}

.job-item h6 {
    margin-bottom: 5px;
}

.job-item a {
    color: #007bff;
    text-decoration: none;
}

.job-item a:hover {
    text-decoration: underline;
}

.job-date {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 0;
}

.quick-actions .btn {
    margin-bottom: 10px;
}

.quick-actions .btn:last-child {
    margin-bottom: 0;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn {
    font-weight: 500;
}
</style>

<?php $this->load->view('template/footer'); ?>
