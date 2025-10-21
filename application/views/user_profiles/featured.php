<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Featured Header -->
            <div class="featured-header">
                <h2><i class="fas fa-crown"></i> Top Rated Cleaners</h2>
                <p class="text-muted">Meet our most trusted and highly-rated cleaning professionals</p>
            </div>

            <!-- Top Performers Section -->
            <div class="top-performers-section">
                <h3><i class="fas fa-trophy"></i> Top Performers</h3>
                <div class="top-performers-grid">
                    <?php foreach (array_slice($profiles, 0, 3) as $index => $profile): ?>
                    <div class="top-performer-card rank-<?php echo $index + 1; ?>">
                        <div class="rank-badge">
                            <i class="fas fa-medal"></i>
                            <span><?php echo $index + 1; ?></span>
                        </div>
                        
                        <div class="profile-photo">
                            <?php if ($profile->profile_picture_url): ?>
                                <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile Picture">
                            <?php else: ?>
                                <div class="default-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="profile-info">
                            <h4 class="profile-name">
                                <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>">
                                    <?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>
                                </a>
                            </h4>
                            <p class="username">@<?php echo htmlspecialchars($profile->username); ?></p>
                            
                            <div class="rating-display">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo ($i <= floor($profile->average_rating ?? 0)) ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-number"><?php echo number_format($profile->average_rating ?? 0, 1); ?></span>
                                <span class="rating-count">(<?php echo $profile->total_reviews; ?> reviews)</span>
                            </div>
                            
                            <div class="achievement-stats">
                                <div class="stat">
                                    <i class="fas fa-briefcase"></i>
                                    <span><?php echo $profile->total_jobs_completed; ?> jobs completed</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-percentage"></i>
                                    <span><?php echo $profile->completion_rate; ?>% completion rate</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-clock"></i>
                                    <span><?php echo $profile->response_time_avg; ?>min avg response</span>
                                </div>
                            </div>
                            
                            <?php if ($profile->verification_status == 'verified'): ?>
                            <div class="verification-badge">
                                <i class="fas fa-check-circle"></i>
                                <span>Verified Professional</span>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="action-section">
                            <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>" class="btn btn-primary">
                                <i class="fas fa-eye"></i> View Profile
                            </a>
                            <a href="<?php echo base_url('jobs/create'); ?>" class="btn btn-success">
                                <i class="fas fa-plus"></i> Hire Now
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- All Featured Cleaners -->
            <div class="all-featured-section">
                <h3><i class="fas fa-star"></i> All Featured Cleaners</h3>
                <div class="featured-cleaners-grid">
                    <?php foreach ($profiles as $profile): ?>
                    <div class="featured-cleaner-card">
                        <div class="card-header">
                            <div class="profile-photo">
                                <?php if ($profile->profile_picture_url): ?>
                                    <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile Picture">
                                <?php else: ?>
                                    <div class="default-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($profile->verification_status == 'verified'): ?>
                                    <div class="verification-badge">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="rating-badge">
                                <i class="fas fa-star"></i>
                                <?php echo number_format($profile->average_rating ?? 0, 1); ?>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="profile-name">
                                <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>">
                                    <?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>
                                </a>
                            </h5>
                            <p class="username">@<?php echo htmlspecialchars($profile->username); ?></p>
                            
                            <div class="rating-summary">
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <span class="star <?php echo ($i <= floor($profile->average_rating ?? 0)) ? 'filled' : ''; ?>">★</span>
                                    <?php endfor; ?>
                                </div>
                                <span class="rating-text">(<?php echo $profile->total_reviews; ?> reviews)</span>
                            </div>
                            
                            <div class="quick-stats">
                                <div class="stat">
                                    <i class="fas fa-briefcase"></i>
                                    <span><?php echo $profile->total_jobs_completed; ?> jobs</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-percentage"></i>
                                    <span><?php echo $profile->completion_rate; ?>% completion</span>
                                </div>
                            </div>
                            
                            <!-- Specialties Preview -->
                            <?php if ($profile->specialties): ?>
                            <div class="specialties-preview">
                                <?php 
                                $specialties = json_decode($profile->specialties, true);
                                $specialty_labels = array(
                                    'residential' => 'Residential',
                                    'office' => 'Office',
                                    'deep_cleaning' => 'Deep Clean',
                                    'move_in_out' => 'Move Clean',
                                    'post_construction' => 'Construction',
                                    'green_cleaning' => 'Eco-Friendly',
                                    'window_cleaning' => 'Windows',
                                    'carpet_cleaning' => 'Carpet'
                                );
                                
                                if ($specialties && count($specialties) > 0):
                                    $displayed = 0;
                                    foreach ($specialties as $specialty):
                                        if (isset($specialty_labels[$specialty]) && $displayed < 3):
                                            echo '<span class="specialty-tag">' . htmlspecialchars($specialty_labels[$specialty]) . '</span>';
                                            $displayed++;
                                        endif;
                                    endforeach;
                                    
                                    if (count($specialties) > 3):
                                        echo '<span class="specialty-tag more">+' . (count($specialties) - 3) . ' more</span>';
                                    endif;
                                endif;
                                ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-footer">
                            <a href="<?php echo base_url('userprofile/view/' . $profile->user_id); ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> View Profile
                            </a>
                            <a href="<?php echo base_url('jobs/create'); ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Hire
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="cta-section">
                <div class="cta-content">
                    <h3>Can't find what you're looking for?</h3>
                    <p>Browse all our cleaners or search for specific services</p>
                    <div class="cta-buttons">
                        <a href="<?php echo base_url('userprofile/browse'); ?>" class="btn btn-primary">
                            <i class="fas fa-users"></i> Browse All Cleaners
                        </a>
                        <a href="<?php echo base_url('userprofile/search'); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-search"></i> Search Cleaners
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.featured-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 60px 0;
    border-radius: 10px;
    margin-bottom: 40px;
    text-align: center;
}

.featured-header h2 {
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.top-performers-section {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.top-performers-section h3 {
    color: #007bff;
    margin-bottom: 30px;
    text-align: center;
    font-size: 1.8rem;
}

.top-performers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 30px;
}

.top-performer-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 30px;
    text-align: center;
    position: relative;
    transition: all 0.3s ease;
    border: 3px solid transparent;
}

.top-performer-card.rank-1 {
    border-color: #ffc107;
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
}

.top-performer-card.rank-2 {
    border-color: #c0c0c0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.top-performer-card.rank-3 {
    border-color: #cd7f32;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.top-performer-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.rank-badge {
    position: absolute;
    top: -15px;
    left: 50%;
    transform: translateX(-50%);
    background: #007bff;
    color: white;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.rank-badge.rank-1 {
    background: #ffc107;
    color: #000;
}

.rank-badge.rank-2 {
    background: #c0c0c0;
    color: #000;
}

.rank-badge.rank-3 {
    background: #cd7f32;
    color: white;
}

.rank-badge i {
    font-size: 1.2rem;
    margin-bottom: 2px;
}

.rank-badge span {
    font-size: 1.5rem;
    line-height: 1;
}

.profile-photo {
    position: relative;
    margin: 20px auto 20px auto;
}

.profile-photo img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.default-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 4px solid white;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.default-avatar i {
    font-size: 3rem;
    color: #6c757d;
}

.verification-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    background: #28a745;
    color: white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.profile-name {
    margin-bottom: 5px;
}

.profile-name a {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.3rem;
}

.profile-name a:hover {
    text-decoration: underline;
}

.username {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 15px;
}

.rating-display {
    margin-bottom: 20px;
}

.stars {
    display: inline-block;
    font-size: 1.5rem;
    margin-right: 10px;
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
    margin-right: 5px;
}

.rating-count {
    color: #6c757d;
    font-size: 1rem;
}

.achievement-stats {
    margin-bottom: 20px;
}

.achievement-stats .stat {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 0.9rem;
    color: #555;
}

.achievement-stats .stat i {
    color: #007bff;
}

.verification-badge {
    background: #28a745;
    color: white;
    padding: 8px 15px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 20px;
    display: inline-block;
}

.verification-badge i {
    margin-right: 5px;
}

.action-section {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.action-section .btn {
    padding: 12px 20px;
    font-weight: 500;
    border-radius: 8px;
}

.all-featured-section {
    background: white;
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    margin-bottom: 30px;
}

.all-featured-section h3 {
    color: #007bff;
    margin-bottom: 30px;
    text-align: center;
    font-size: 1.8rem;
}

.featured-cleaners-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

.featured-cleaner-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    overflow: hidden;
}

.featured-cleaner-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.card-header {
    position: relative;
    padding: 20px;
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.featured-cleaner-card .profile-photo img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
}

.featured-cleaner-card .default-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    border: 3px solid white;
}

.featured-cleaner-card .default-avatar i {
    font-size: 2rem;
    color: #6c757d;
}

.rating-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #ffc107;
    color: #000;
    border-radius: 12px;
    padding: 4px 8px;
    font-size: 0.8rem;
    font-weight: bold;
}

.card-body {
    padding: 20px;
}

.featured-cleaner-card .profile-name a {
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
}

.featured-cleaner-card .profile-name a:hover {
    text-decoration: underline;
}

.featured-cleaner-card .username {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 10px;
}

.rating-summary {
    margin-bottom: 15px;
}

.rating-summary .stars {
    font-size: 1rem;
    margin-right: 5px;
}

.rating-text {
    font-size: 0.9rem;
    color: #6c757d;
}

.quick-stats {
    display: flex;
    justify-content: center;
    gap: 15px;
    margin-bottom: 15px;
}

.quick-stats .stat {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.8rem;
    color: #6c757d;
}

.quick-stats .stat i {
    color: #007bff;
}

.specialties-preview {
    margin-bottom: 15px;
}

.specialty-tag {
    display: inline-block;
    background: #e7f3ff;
    color: #007bff;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    margin: 2px;
    border: 1px solid #b3d7ff;
}

.specialty-tag.more {
    background: #f8f9fa;
    color: #6c757d;
    border-color: #dee2e6;
}

.card-footer {
    padding: 15px 20px;
    background: #f8f9fa;
    display: flex;
    gap: 10px;
}

.card-footer .btn {
    flex: 1;
    font-size: 0.9rem;
    padding: 8px 12px;
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 50px;
    border-radius: 10px;
    text-align: center;
    margin-bottom: 30px;
}

.cta-content h3 {
    margin-bottom: 15px;
    font-size: 1.8rem;
}

.cta-content p {
    margin-bottom: 25px;
    font-size: 1.1rem;
    opacity: 0.9;
}

.cta-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.cta-buttons .btn {
    padding: 12px 25px;
    font-weight: 500;
    border-radius: 8px;
}

.btn {
    font-weight: 500;
}
</style>

<?php $this->load->view('template/footer'); ?>
