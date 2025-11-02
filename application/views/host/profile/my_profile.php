<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modern Host Profile View v2.0 -->
<div class="host-profile-container">
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-header-content">
      <div class="profile-avatar-section">
        <?php if (!empty($profile->profile_picture_url)): ?>
          <img src="<?php echo base_url($profile->profile_picture_url); ?>" alt="Profile" class="profile-avatar">
        <?php else: ?>
          <div class="profile-avatar-placeholder">
            <i class="fas fa-user"></i>
          </div>
        <?php endif; ?>
        <div class="profile-status-badge">
          <span class="status-active"><i class="fas fa-check"></i> Active</span>
        </div>
      </div>
      
      <div class="profile-info-section">
        <h1 class="profile-name">
          <?php echo htmlspecialchars($profile->username); ?>
        </h1>
        <p class="profile-username"><?php echo htmlspecialchars($profile->email); ?></p>
        
        <div class="profile-badges">
          <span class="badge-host"><i class="fas fa-home"></i> Host</span>
          <span class="user-id">ID: #<?php echo $profile->user_id ?? 'N/A'; ?></span>
        </div>
      </div>
      
      <div class="profile-actions">
        <a href="<?php echo base_url('host/edit-profile'); ?>" class="btn btn-primary">
          <i class="fas fa-edit"></i> Edit Profile
        </a>
      </div>
    </div>
  </div>

  <!-- Profile Completion Alert -->
  <?php if ($completion['percentage'] < 50): ?>
  <div class="alert-card warning">
    <div class="alert-content">
      <div class="alert-icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div class="alert-text">
        <h4>Profile Incomplete!</h4>
        <p>Your profile is only <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong> complete. You need at least <strong>50%</strong> completion to post jobs.</p>
        <a href="<?php echo base_url('host/edit-profile'); ?>" class="btn btn-warning">
          <i class="fas fa-edit"></i> Complete Profile Now
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Main Content Grid -->
  <div class="profile-content-grid">
    
    <!-- Left Column -->
    <div class="profile-left-column">
      
      <!-- Profile Completion -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-chart-pie"></i> Profile Completion</h3>
        </div>
        <div class="card-content">
          <?php
            $completion_percentage = $completion['percentage'] ?? 0;
            $progress_class = $completion_percentage >= 80 ? 'success' : ($completion_percentage >= 50 ? 'warning' : 'danger');
          ?>
          <div class="progress-section">
            <div class="progress-circle">
              <div class="progress-value"><?php echo $completion_percentage; ?>%</div>
            </div>
            <div class="progress-info">
              <div class="progress-bar">
                <div class="progress-fill bg-<?php echo $progress_class; ?>" style="width: <?php echo $completion_percentage; ?>%"></div>
              </div>
              <p class="progress-text">Profile is <?php echo $completion_percentage; ?>% complete</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Contact Information -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-address-card"></i> Contact Information</h3>
        </div>
        <div class="card-content">
          <div class="info-row">
            <label>Email</label>
            <span><?php echo htmlspecialchars($profile->email); ?></span>
          </div>
          <div class="info-row">
            <label>Phone</label>
            <span><?php echo !empty($profile->phone) ? htmlspecialchars($profile->phone) : 'Not provided'; ?></span>
          </div>
          <div class="info-row">
            <label>Location</label>
            <span>
              <?php 
                $location_parts = array_filter([
                  $profile->city ?? null,
                  $profile->state ?? null
                ]);
                echo !empty($location_parts) ? htmlspecialchars(implode(', ', $location_parts)) : 'Not provided'; 
              ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Missing Information Alert -->
      <?php if ($completion['percentage'] < 50 && !empty($completion['missing'])): ?>
      <div class="info-card warning-card">
        <div class="card-header">
          <h3><i class="fas fa-exclamation-triangle"></i> Missing Information</h3>
        </div>
        <div class="card-content">
          <p>Complete these fields to reach <strong>50%</strong> profile completion and post jobs:</p>
          <ul class="missing-list">
            <?php foreach ($completion['missing'] as $item): ?>
              <li><?php echo htmlspecialchars($item); ?></li>
            <?php endforeach; ?>
          </ul>
          <div class="text-center">
            <a href="<?php echo base_url('host/edit-profile'); ?>" class="btn btn-warning">
              <i class="fas fa-edit"></i> Complete Profile
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>

    <!-- Right Column -->
    <div class="profile-right-column">
      
      <!-- About Me -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-user"></i> About Me</h3>
        </div>
        <div class="card-content">
          <?php if (!empty($profile->bio)): ?>
            <p class="bio-text"><?php echo nl2br(htmlspecialchars($profile->bio)); ?></p>
          <?php else: ?>
            <div class="no-bio">
              <i class="fas fa-info-circle"></i>
              <p>You haven't added a bio yet.</p>
              <a href="<?php echo base_url('host/edit-profile'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Bio Now
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Job Statistics -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-chart-bar"></i> Job Statistics</h3>
        </div>
        <div class="card-content">
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-icon bg-info">
                <i class="fas fa-clipboard-list"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value"><?php echo isset($job_stats['total_jobs']) ? $job_stats['total_jobs'] : 0; ?></div>
                <div class="stat-label">Total Jobs</div>
              </div>
            </div>
            
            <div class="stat-item">
              <div class="stat-icon bg-success">
                <i class="fas fa-check-circle"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value"><?php echo isset($job_stats['completed_jobs']) ? $job_stats['completed_jobs'] : 0; ?></div>
                <div class="stat-label">Completed</div>
              </div>
            </div>
            
            <div class="stat-item">
              <div class="stat-icon bg-warning">
                <i class="fas fa-clock"></i>
              </div>
              <div class="stat-info">
                <div class="stat-value"><?php echo isset($job_stats['active_jobs']) ? $job_stats['active_jobs'] : 0; ?></div>
                <div class="stat-label">Active</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Account Information -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-info-circle"></i> Account Information</h3>
        </div>
        <div class="card-content">
          <div class="account-info">
            <div class="account-item">
              <label><i class="fas fa-calendar-plus"></i> Member Since</label>
              <span>
                <?php 
                $created = new DateTime($profile->user_created_at);
                echo $created->format('M d, Y'); 
                ?>
              </span>
            </div>
            
            <div class="account-item">
              <label><i class="fas fa-calendar-check"></i> Last Updated</label>
              <span>
                <?php 
                if (!empty($profile->updated_at)) {
                  $updated = new DateTime($profile->updated_at);
                  echo $updated->format('M d, Y g:i A');
                } else {
                  echo 'Never';
                }
                ?>
              </span>
            </div>
            
            <div class="account-item">
              <label><i class="fas fa-eye"></i> Profile Visibility</label>
              <span>
                <?php if ($profile->is_public): ?>
                  <span class="badge-success">Public</span>
                <?php else: ?>
                  <span class="badge-secondary">Private</span>
                <?php endif; ?>
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- My Reviews Section -->
      <?php if (isset($review_stats) && $review_stats['total_reviews'] > 0): ?>
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-star"></i> My Reviews (<?php echo $review_stats['total_reviews']; ?>)</h3>
        </div>
        <div class="card-content">
          <!-- Rating Summary -->
            <div class="rating-summary-box">
              <div class="overall-rating">
                <div class="rating-number"><?php echo number_format($review_stats['overall_average'] ?? 0, 1); ?></div>
                <div class="rating-stars">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= round($review_stats['overall_average'] ?? 0) ? 'filled' : 'empty'; ?>"></i>
                  <?php endfor; ?>
                </div>
                <div class="rating-text"><?php echo $review_stats['total_reviews'] ?? 0; ?> reviews</div>
              </div>
            
            <!-- Category Ratings -->
            <?php if (isset($review_stats['category_averages'])): ?>
            <div class="category-ratings-list">
              <?php if ($review_stats['category_averages']['professionalism'] > 0): ?>
              <div class="category-rating-item">
                <span class="category-label">Professionalism</span>
                <div class="category-stars">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= round($review_stats['category_averages']['professionalism']) ? 'filled' : 'empty'; ?>"></i>
                  <?php endfor; ?>
                  <span class="category-score"><?php echo number_format($review_stats['category_averages']['professionalism'], 1); ?></span>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if ($review_stats['category_averages']['quality'] > 0): ?>
              <div class="category-rating-item">
                <span class="category-label">Quality</span>
                <div class="category-stars">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= round($review_stats['category_averages']['quality']) ? 'filled' : 'empty'; ?>"></i>
                  <?php endfor; ?>
                  <span class="category-score"><?php echo number_format($review_stats['category_averages']['quality'], 1); ?></span>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if ($review_stats['category_averages']['communication'] > 0): ?>
              <div class="category-rating-item">
                <span class="category-label">Communication</span>
                <div class="category-stars">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= round($review_stats['category_averages']['communication']) ? 'filled' : 'empty'; ?>"></i>
                  <?php endfor; ?>
                  <span class="category-score"><?php echo number_format($review_stats['category_averages']['communication'], 1); ?></span>
                </div>
              </div>
              <?php endif; ?>
              
              <?php if ($review_stats['category_averages']['punctuality'] > 0): ?>
              <div class="category-rating-item">
                <span class="category-label">Punctuality</span>
                <div class="category-stars">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= round($review_stats['category_averages']['punctuality']) ? 'filled' : 'empty'; ?>"></i>
                  <?php endfor; ?>
                  <span class="category-score"><?php echo number_format($review_stats['category_averages']['punctuality'], 1); ?></span>
                </div>
              </div>
              <?php endif; ?>
            </div>
            <?php endif; ?>
          </div>
          
          <!-- Reviews List -->
          <?php if (!empty($reviews)): ?>
          <div class="reviews-list-container">
            <h4 style="margin: 1.5rem 0 1rem 0; color: #495057;">Recent Reviews</h4>
            <?php foreach ($reviews as $review): ?>
            <div class="review-card">
              <div class="review-header-row">
                <div class="reviewer-info">
                  <strong><?php echo htmlspecialchars($review->reviewer_name ?? 'Anonymous'); ?></strong>
                  <span class="reviewer-badge-small">Cleaner</span>
                </div>
                <div class="review-rating-display">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= $review->overall_rating ? 'filled' : 'empty'; ?>"></i>
                  <?php endfor; ?>
                  <span class="rating-value-small"><?php echo number_format($review->overall_rating, 1); ?></span>
                </div>
              </div>
              <div class="review-comment-text">
                <?php echo htmlspecialchars($review->public_comment); ?>
              </div>
              <?php if (!empty($review->job_title)): ?>
              <div class="review-job-ref">
                <i class="fas fa-briefcase"></i> Job: <?php echo htmlspecialchars($review->job_title); ?>
              </div>
              <?php endif; ?>
              <div class="review-date-text">
                <?php 
                $review_date = new DateTime($review->created_at);
                $now = new DateTime();
                $diff = $now->diff($review_date);
                
                if ($diff->days == 0) {
                  echo 'Today';
                } elseif ($diff->days == 1) {
                  echo 'Yesterday';
                } elseif ($diff->days < 7) {
                  echo $diff->days . ' days ago';
                } elseif ($diff->days < 30) {
                  echo floor($diff->days / 7) . ' week' . (floor($diff->days / 7) > 1 ? 's' : '') . ' ago';
                } else {
                  echo $review_date->format('M j, Y');
                }
                ?>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php else: ?>
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-star"></i> My Reviews</h3>
        </div>
        <div class="card-content">
          <div class="empty-state-box">
            <i class="fas fa-comments"></i>
            <p>No reviews yet</p>
            <small>Reviews from cleaners will appear here after they complete jobs for you.</small>
          </div>
        </div>
      </div>
      <?php endif; ?>

    </div>

  </div>

</div>

<style>
/* Modern Host Profile Styles */
.host-profile-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Profile Header */
.profile-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  padding: 2rem;
  margin-bottom: 2rem;
  color: white;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.profile-header-content {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.profile-avatar-section {
  position: relative;
  flex-shrink: 0;
}

.profile-avatar, .profile-avatar-placeholder {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  border: 4px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.profile-avatar-placeholder {
  background: rgba(255, 255, 255, 0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
}

.profile-status-badge {
  position: absolute;
  bottom: 5px;
  right: 5px;
}

.status-active {
  background: rgba(255, 255, 255, 0.9);
  color: #28a745;
  padding: 0.25rem 0.5rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.profile-info-section {
  flex: 1;
}

.profile-name {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.profile-username {
  font-size: 1.2rem;
  margin: 0 0 1rem 0;
  opacity: 0.9;
}

.profile-badges {
  display: flex;
  gap: 1rem;
  align-items: center;
  flex-wrap: wrap;
}

.badge-host {
  background: rgba(255, 255, 255, 0.2);
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 600;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.user-id {
  background: rgba(255, 255, 255, 0.1);
  padding: 0.25rem 0.75rem;
  border-radius: 15px;
  font-size: 0.9rem;
  font-family: monospace;
}

.profile-actions {
  display: flex;
  gap: 1rem;
  flex-shrink: 0;
}

/* Alert Card */
.alert-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  margin-bottom: 2rem;
  overflow: hidden;
}

.alert-card.warning {
  border-left: 4px solid #ffc107;
}

.alert-content {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.5rem;
}

.alert-icon {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  flex-shrink: 0;
}

.alert-text h4 {
  margin: 0 0 0.5rem 0;
  color: #333;
  font-weight: 600;
}

.alert-text p {
  margin: 0 0 1rem 0;
  color: #666;
}

/* Content Grid */
.profile-content-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

/* Info Cards */
.info-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  margin-bottom: 2rem;
}

.info-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.info-card.warning-card {
  border-left: 4px solid #ffc107;
}

.card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.5rem;
  border-bottom: 1px solid #dee2e6;
}

.card-header h3 {
  margin: 0;
  font-size: 1.2rem;
  font-weight: 600;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.card-header i {
  color: #667eea;
  font-size: 1.1rem;
}

.card-content {
  padding: 2rem;
}

/* Progress Section */
.progress-section {
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.progress-circle {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-weight: 700;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.progress-info {
  flex: 1;
}

.progress-bar {
  width: 100%;
  height: 8px;
  background: #e9ecef;
  border-radius: 4px;
  overflow: hidden;
  margin-bottom: 0.5rem;
}

.progress-fill {
  height: 100%;
  border-radius: 4px;
  transition: width 1s ease;
}

.bg-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
.bg-danger { background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%); }

.progress-text {
  margin: 0;
  color: #6c757d;
  font-size: 0.9rem;
}

/* Info Rows */
.info-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f1f3f4;
}

.info-row:last-child {
  border-bottom: none;
}

.info-row label {
  font-weight: 600;
  color: #6c757d;
  min-width: 120px;
}

.info-row span {
  color: #495057;
  text-align: right;
}

/* Missing List */
.missing-list {
  margin: 1rem 0;
  padding-left: 1.5rem;
}

.missing-list li {
  color: #6c757d;
  margin-bottom: 0.5rem;
}

/* Bio */
.bio-text {
  line-height: 1.6;
  color: #495057;
  margin: 0;
}

.no-bio {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
}

.no-bio i {
  font-size: 3rem;
  margin-bottom: 1rem;
  color: #dee2e6;
}

/* Statistics */
.stats-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.stat-item:hover {
  transform: translateX(5px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.stat-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
.bg-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }

.stat-info {
  flex: 1;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #495057;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.9rem;
  color: #6c757d;
  font-weight: 500;
}

/* Account Info */
.account-info {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.account-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
}

.account-item label {
  font-weight: 600;
  color: #495057;
  margin: 0;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.account-item i {
  color: #667eea;
}

.badge-success {
  background: #d4edda;
  color: #155724;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 600;
}

.badge-secondary {
  background: #e2e3e5;
  color: #383d41;
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 600;
}

/* Buttons */
.btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
  cursor: pointer;
  font-size: 1rem;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  color: white;
  text-decoration: none;
}

.btn-warning {
  background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
  color: white;
}

.btn-warning:hover {
  transform: translateY(-2px);
  color: white;
  text-decoration: none;
}

.btn-sm {
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .profile-header-content {
    flex-direction: column;
    text-align: center;
    gap: 1.5rem;
  }
  
  .profile-name {
    font-size: 2rem;
  }
  
  .profile-content-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .card-content {
    padding: 1.5rem;
  }
  
  .alert-content {
    flex-direction: column;
    text-align: center;
  }
  
  .stats-grid {
    gap: 0.75rem;
  }
  
  .stat-item {
    padding: 0.75rem;
  }
}

@media (max-width: 480px) {
  .host-profile-container {
    padding: 0.5rem;
  }
  
  .profile-header {
    padding: 1.5rem;
  }
  
  .profile-name {
    font-size: 1.75rem;
  }
  
  .card-content {
    padding: 1rem;
  }
}

/* Reviews Section */
.rating-summary-box {
  background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
  border-radius: 10px;
  padding: 1.5rem;
  margin-bottom: 1.5rem;
}

.overall-rating {
  text-align: center;
  margin-bottom: 1.5rem;
}

.rating-number {
  font-size: 3.5rem;
  font-weight: 700;
  color: #f57c00;
  line-height: 1;
}

.rating-stars {
  font-size: 1.5rem;
  margin: 0.5rem 0;
}

.rating-stars i.filled {
  color: #ffc107;
}

.rating-stars i.empty {
  color: #dee2e6;
}

.rating-text {
  font-size: 1rem;
  color: #6c757d;
}

.category-ratings-list {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.category-rating-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: white;
  border-radius: 8px;
}

.category-label {
  font-weight: 600;
  color: #495057;
}

.category-stars {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.category-stars i {
  font-size: 1rem;
}

.category-stars i.filled {
  color: #ffc107;
}

.category-stars i.empty {
  color: #dee2e6;
}

.category-score {
  margin-left: 0.5rem;
  font-weight: 600;
  color: #495057;
}

.reviews-list-container {
  margin-top: 1.5rem;
}

.review-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
  padding: 1.25rem;
  margin-bottom: 1rem;
  border-left: 4px solid #667eea;
}

.review-header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.reviewer-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.reviewer-badge-small {
  font-size: 0.75rem;
  background: #667eea;
  color: white;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
}

.review-rating-display {
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.review-rating-display i {
  font-size: 0.9rem;
}

.review-rating-display i.filled {
  color: #ffc107;
}

.review-rating-display i.empty {
  color: #dee2e6;
}

.rating-value-small {
  margin-left: 0.5rem;
  font-weight: 600;
  color: #495057;
  font-size: 0.9rem;
}

.review-comment-text {
  color: #495057;
  line-height: 1.5;
  margin-bottom: 0.75rem;
}

.review-job-ref {
  font-size: 0.9rem;
  color: #6c757d;
  margin-bottom: 0.5rem;
}

.review-date-text {
  font-size: 0.85rem;
  color: #6c757d;
}

.empty-state-box {
  text-align: center;
  padding: 3rem 1rem;
  color: #6c757d;
}

.empty-state-box i {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state-box p {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}

.empty-state-box small {
  color: #6c757d;
}
</style>