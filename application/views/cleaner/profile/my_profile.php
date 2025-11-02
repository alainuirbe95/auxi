<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modern Cleaner Profile View v2.0 -->
<div class="cleaner-profile-container">
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-header-content">
      <div class="profile-avatar-section">
        <div class="profile-avatar">
          <img src="<?php echo image_user($profile->user_id); ?>" alt="Profile Picture">
          <div class="avatar-status">
            <div class="status-badge cleaner">
              <i class="fas fa-broom"></i>
              <span>Cleaner</span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="profile-info-section">
        <div class="name-badges-row">
          <h1 class="profile-name"><?php echo htmlspecialchars($profile->username); ?></h1>
          <?php if (!empty($profile->services_str)): ?>
          <span class="str-badge">
            <i class="fas fa-home"></i> STR Specialist
          </span>
          <?php endif; ?>
        </div>
        <p class="profile-role">
          Professional Cleaning Services
          <?php if (!empty($profile->first_name) && !empty($profile->last_name)): ?>
          · <?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>
          <?php endif; ?>
        </p>
        
        <!-- Quick Stats Row -->
        <div class="quick-stats-row">
          <div class="quick-stat">
            <i class="fas fa-calendar-check"></i>
            <span>Member since <?php echo date('M Y', strtotime($profile->created_at ?? 'now')); ?></span>
          </div>
          <?php if (isset($job_stats['completed_jobs']) && $job_stats['completed_jobs'] > 0): ?>
          <div class="quick-stat">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $job_stats['completed_jobs']; ?> jobs completed</span>
          </div>
          <?php endif; ?>
          <?php if (isset($review_stats['total_reviews']) && $review_stats['total_reviews'] > 0): ?>
          <div class="quick-stat">
            <i class="fas fa-star"></i>
            <span><?php echo number_format($review_stats['overall_average'], 1); ?> rating (<?php echo $review_stats['total_reviews']; ?>)</span>
          </div>
          <?php endif; ?>
        </div>
        
        <div class="profile-completion">
          <div class="completion-circle">
            <div class="completion-percentage <?php echo $completion['percentage'] >= 50 ? 'success' : 'warning'; ?>">
              <?php echo number_format($completion['percentage'], 0); ?>%
            </div>
            <div class="completion-label">Profile Complete</div>
          </div>
          <?php if ($completion['percentage'] >= 50): ?>
          <div class="can-work-badge">
            <i class="fas fa-check-circle"></i> Ready to work
          </div>
          <?php else: ?>
          <div class="cannot-work-badge">
            <i class="fas fa-exclamation-circle"></i> Complete to work
          </div>
          <?php endif; ?>
        </div>
        
        <div class="profile-actions">
          <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profile
          </a>
          <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn btn-secondary">
            <i class="fas fa-search"></i> Browse Jobs
          </a>
        </div>
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
        <p>Your profile is only <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong> complete. You need at least <strong>50%</strong> completion to make offers on jobs.</p>
        <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-warning">
          <i class="fas fa-edit"></i> Complete Profile Now
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Profile Content -->
  <div class="profile-content">
    <div class="profile-grid">
      
      <!-- Left Column -->
      <div class="profile-column">
        
        <!-- Contact Information Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-phone"></i> Contact Information</h3>
          </div>
          <div class="card-content">
            <div class="info-group">
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-envelope"></i>
                </div>
                <div class="info-details">
                  <div class="info-label">Email</div>
                  <div class="info-value"><?php echo htmlspecialchars($profile->email); ?></div>
                </div>
              </div>
              
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-phone"></i>
                </div>
                <div class="info-details">
                  <div class="info-label">Phone</div>
                  <div class="info-value"><?php echo htmlspecialchars($profile->phone ?? 'Not provided'); ?></div>
                </div>
              </div>
              
              <div class="info-item">
                <div class="info-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="info-details">
                  <div class="info-label">Location</div>
                  <div class="info-value">
                    <?php 
                    $location_parts = array_filter([
                      $profile->user_city,
                      $profile->user_country
                    ]);
                    echo !empty($location_parts) ? htmlspecialchars(implode(', ', $location_parts)) : 'Not provided';
                    ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Service Areas Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-map-marked-alt"></i> Service Areas</h3>
          </div>
          <div class="card-content">
            <?php 
            $areas = !empty($profile->service_areas) ? json_decode($profile->service_areas, true) : [];
            if (is_array($areas) && !empty($areas)): 
            ?>
              <div class="tags-container">
                <?php foreach ($areas as $area): ?>
                  <span class="tag tag-info"><?php echo htmlspecialchars($area); ?></span>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-map-marker-alt"></i>
                <p>No service areas specified</p>
                <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-outline">Add service areas</a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Specialties Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-star"></i> Cleaning Specialties</h3>
          </div>
          <div class="card-content">
            <?php 
            $specialties = !empty($profile->specialties) ? json_decode($profile->specialties, true) : [];
            if (is_array($specialties) && !empty($specialties)): 
            ?>
              <div class="tags-container">
                <?php foreach ($specialties as $specialty): ?>
                  <span class="tag tag-success"><?php echo htmlspecialchars($specialty); ?></span>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-star"></i>
                <p>No specialties specified</p>
                <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-outline">Add specialties</a>
              </div>
            <?php endif; ?>
            
            <!-- STR Services Indicator -->
            <?php if (!empty($profile->services_str)): ?>
            <div class="str-service-indicator mt-3">
              <div class="str-indicator-content">
                <div class="str-indicator-icon">
                  <i class="fas fa-home"></i>
                </div>
                <div class="str-indicator-text">
                  <strong>Short Term Rental Specialist</strong>
                  <p>This cleaner specializes in STR turnover cleaning for vacation rentals, Airbnb, VRBO, and similar properties.</p>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Equipment & Supplies Reminder -->
        <div class="profile-card reminder-card">
          <div class="card-header">
            <h3><i class="fas fa-tools"></i> Equipment & Supplies</h3>
          </div>
          <div class="card-content">
            <div class="reminder-content">
              <div class="reminder-icon">
                <i class="fas fa-info-circle"></i>
              </div>
              <div class="reminder-text">
                <h4>Important Reminder</h4>
                <p><strong>All cleaners must bring their own cleaning supplies and equipment to jobs.</strong></p>
                <p>This includes detergents, disinfectants, vacuum cleaner, mop, cleaning cloths, gloves, and specialized equipment.</p>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Right Column -->
      <div class="profile-column">
        
        <!-- About Me Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-user"></i> About Me</h3>
          </div>
          <div class="card-content">
            <?php if (!empty($profile->bio)): ?>
              <div class="bio-content">
                <?php echo nl2br(htmlspecialchars($profile->bio)); ?>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-user"></i>
                <p>No bio provided yet</p>
                <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-outline">Add bio now</a>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Job Statistics Card -->
        <?php if (!empty($job_stats)): ?>
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-chart-bar"></i> Job Statistics</h3>
          </div>
          <div class="card-content">
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon bg-success">
                  <i class="fas fa-check"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo $job_stats['completed_jobs'] ?? 0; ?></div>
                  <div class="stat-label">Jobs Completed</div>
                </div>
              </div>
              
              <div class="stat-card">
                <div class="stat-icon bg-warning">
                  <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo $job_stats['active_jobs'] ?? 0; ?></div>
                  <div class="stat-label">Active Jobs</div>
                </div>
              </div>
              
              <div class="stat-card">
                <div class="stat-icon bg-info">
                  <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo number_format($job_stats['average_rating'] ?? 0, 1); ?></div>
                  <div class="stat-label">Average Rating</div>
                </div>
              </div>
              
              <div class="stat-card">
                <div class="stat-icon bg-primary">
                  <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number">$<?php echo number_format($job_stats['total_earnings'] ?? 0, 0); ?></div>
                  <div class="stat-label">Total Earnings</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- My Reviews Section -->
        <?php if (isset($review_stats) && isset($review_stats['total_reviews']) && $review_stats['total_reviews'] > 0): ?>
        <div class="profile-card">
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
                    <span class="reviewer-badge-small">Host</span>
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
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-star"></i> My Reviews</h3>
          </div>
          <div class="card-content">
            <div class="empty-state-box">
              <i class="fas fa-comments"></i>
              <p>No reviews yet</p>
              <small>Reviews from hosts will appear here after you complete jobs.</small>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <!-- Profile Completion Details Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-tasks"></i> Profile Completion</h3>
          </div>
          <div class="card-content">
            <div class="completion-details">
              <div class="completion-section">
                <h5>Required Fields (50% minimum)</h5>
                <div class="completion-items">
                  <div class="completion-item <?php echo !empty($profile->bio) && strlen($profile->bio) >= 50 ? 'completed' : 'pending'; ?>">
                    <div class="completion-icon">
                      <i class="fas <?php echo !empty($profile->bio) && strlen($profile->bio) >= 50 ? 'fa-check' : 'fa-times'; ?>"></i>
                    </div>
                    <div class="completion-text">
                      <div class="completion-title">Bio</div>
                      <div class="completion-points"><?php echo !empty($profile->bio) && strlen($profile->bio) >= 50 ? '15' : '0'; ?> points</div>
                    </div>
                  </div>
                  
                  <div class="completion-item <?php echo !empty($profile->phone) ? 'completed' : 'pending'; ?>">
                    <div class="completion-icon">
                      <i class="fas <?php echo !empty($profile->phone) ? 'fa-check' : 'fa-times'; ?>"></i>
                    </div>
                    <div class="completion-text">
                      <div class="completion-title">Phone</div>
                      <div class="completion-points"><?php echo !empty($profile->phone) ? '10' : '0'; ?> points</div>
                    </div>
                  </div>
                  
                  <div class="completion-item <?php echo !empty($profile->service_areas) ? 'completed' : 'pending'; ?>">
                    <div class="completion-icon">
                      <i class="fas <?php echo !empty($profile->service_areas) ? 'fa-check' : 'fa-times'; ?>"></i>
                    </div>
                    <div class="completion-text">
                      <div class="completion-title">Service Areas</div>
                      <div class="completion-points"><?php echo !empty($profile->service_areas) ? '20' : '0'; ?> points</div>
                    </div>
                  </div>
                  
                  <div class="completion-item <?php echo !empty($profile->specialties) ? 'completed' : 'pending'; ?>">
                    <div class="completion-icon">
                      <i class="fas <?php echo !empty($profile->specialties) ? 'fa-check' : 'fa-times'; ?>"></i>
                    </div>
                    <div class="completion-text">
                      <div class="completion-title">Specialties</div>
                      <div class="completion-points"><?php echo !empty($profile->specialties) ? '15' : '0'; ?> points</div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="completion-section">
                <h5>Additional Fields</h5>
                <div class="completion-items">
                  <div class="completion-item <?php echo !empty($profile->profile_picture_url) ? 'completed' : 'optional'; ?>">
                    <div class="completion-icon">
                      <i class="fas <?php echo !empty($profile->profile_picture_url) ? 'fa-check' : 'fa-times'; ?>"></i>
                    </div>
                    <div class="completion-text">
                      <div class="completion-title">Profile Picture</div>
                      <div class="completion-points">20 points</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="completion-action">
              <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Complete Your Profile
              </a>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

</div>

<style>
/* Modern Cleaner Profile Styles */
.cleaner-profile-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Profile Header */
.profile-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 20px;
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
  flex-shrink: 0;
}

.profile-avatar {
  position: relative;
  width: 150px;
  height: 150px;
  border-radius: 50%;
  overflow: hidden;
  border: 4px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.profile-avatar img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.avatar-status {
  position: absolute;
  bottom: -5px;
  right: -5px;
}

.status-badge {
  background: rgba(255, 255, 255, 0.9);
  color: #333;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.status-badge.cleaner {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
}

.profile-info-section {
  flex: 1;
}

.name-badges-row {
  display: flex;
  align-items: center;
  gap: 1rem;
  flex-wrap: wrap;
  margin-bottom: 0.5rem;
}

.profile-name {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.str-badge {
  background: rgba(255, 193, 7, 0.9);
  color: #333;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.9rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.profile-role {
  font-size: 1.1rem;
  margin: 0 0 1rem 0;
  opacity: 0.9;
}

.quick-stats-row {
  display: flex;
  flex-wrap: wrap;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
  padding: 1rem 0;
  border-top: 1px solid rgba(255, 255, 255, 0.2);
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.quick-stat {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.95rem;
  opacity: 0.95;
}

.quick-stat i {
  font-size: 1.1rem;
}

.profile-completion {
  margin-bottom: 2rem;
  display: flex;
  align-items: center;
  gap: 2rem;
}

.completion-circle {
  text-align: center;
}

.completion-percentage {
  font-size: 2.5rem;
  font-weight: 700;
  margin-bottom: 0.25rem;
}

.completion-percentage.success {
  color: #28a745;
}

.completion-percentage.warning {
  color: #ffc107;
}

.completion-label {
  font-size: 0.9rem;
  opacity: 0.9;
}

.can-work-badge {
  background: rgba(40, 167, 69, 0.2);
  border: 2px solid rgba(40, 167, 69, 0.5);
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 25px;
  font-size: 1rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.cannot-work-badge {
  background: rgba(255, 193, 7, 0.2);
  border: 2px solid rgba(255, 193, 7, 0.5);
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 25px;
  font-size: 1rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.profile-actions {
  display: flex;
  gap: 1rem;
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
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.5rem;
  flex-shrink: 0;
  background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
}

.alert-text {
  flex: 1;
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

/* Profile Grid */
.profile-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

/* Profile Cards */
.profile-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  margin-bottom: 2rem;
}

.profile-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.profile-card.reminder-card {
  background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
  border: 2px solid #667eea;
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

/* Info Groups */
.info-group {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.info-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.info-item:hover {
  transform: translateX(5px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}

.info-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1rem;
  flex-shrink: 0;
}

.info-details {
  flex: 1;
}

.info-label {
  font-size: 0.9rem;
  color: #6c757d;
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.info-value {
  font-size: 1rem;
  font-weight: 600;
  color: #495057;
}

/* Tags */
.tags-container {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.tag {
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.tag-info {
  background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
  color: white;
}

.tag-success {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
}

/* STR Service Indicator */
.str-service-indicator {
  margin-top: 1.5rem;
  padding-top: 1.5rem;
  border-top: 2px dashed #dee2e6;
}

.str-indicator-content {
  background: linear-gradient(135deg, #fff9e6 0%, #ffe6f0 100%);
  border: 2px solid #ffc107;
  border-radius: 10px;
  padding: 1.25rem;
  display: flex;
  align-items: flex-start;
  gap: 1rem;
}

.str-indicator-icon {
  width: 45px;
  height: 45px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.str-indicator-text {
  flex: 1;
}

.str-indicator-text strong {
  display: block;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 1.05rem;
}

.str-indicator-text p {
  margin: 0;
  color: #6c757d;
  font-size: 0.9rem;
  line-height: 1.5;
}

.mt-3 {
  margin-top: 1rem;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 2rem;
  color: #6c757d;
}

.empty-state i {
  font-size: 3rem;
  margin-bottom: 1rem;
  opacity: 0.5;
}

.empty-state p {
  margin: 0 0 1rem 0;
  font-size: 1.1rem;
}

/* Reminder Card */
.reminder-content {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.reminder-icon {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 1.2rem;
  flex-shrink: 0;
}

.reminder-text h4 {
  margin: 0 0 0.5rem 0;
  color: #495057;
  font-weight: 600;
}

.reminder-text p {
  margin: 0 0 0.5rem 0;
  color: #6c757d;
  line-height: 1.5;
}

.reminder-text p:last-child {
  margin: 0;
}

/* Bio Content */
.bio-content {
  line-height: 1.6;
  color: #495057;
  font-size: 1rem;
}

/* Statistics */
.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.stat-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
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

.bg-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); }
.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }
.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
.bg-primary { background: linear-gradient(135deg, #007bff 0%, #6610f2 100%); }

.stat-info {
  flex: 1;
}

.stat-number {
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

/* Completion Details */
.completion-details {
  margin-bottom: 2rem;
}

.completion-section {
  margin-bottom: 2rem;
}

.completion-section h5 {
  margin: 0 0 1rem 0;
  color: #495057;
  font-weight: 600;
  font-size: 1.1rem;
}

.completion-items {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.completion-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border-radius: 8px;
  transition: all 0.3s ease;
}

.completion-item.completed {
  background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
  border: 1px solid #28a745;
}

.completion-item.pending {
  background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
  border: 1px solid #dc3545;
}

.completion-item.optional {
  background: linear-gradient(135deg, #e2e3e5 0%, #d6d8db 100%);
  border: 1px solid #6c757d;
}

.completion-icon {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.9rem;
  flex-shrink: 0;
}

.completion-item.completed .completion-icon {
  background: #28a745;
  color: white;
}

.completion-item.pending .completion-icon {
  background: #dc3545;
  color: white;
}

.completion-item.optional .completion-icon {
  background: #6c757d;
  color: white;
}

.completion-text {
  flex: 1;
}

.completion-title {
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
}

.completion-points {
  font-size: 0.85rem;
  color: #6c757d;
}

.completion-action {
  text-align: center;
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

.btn-secondary {
  background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
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

.btn-outline {
  background: transparent;
  color: #667eea;
  border: 2px solid #667eea;
}

.btn-outline:hover {
  background: #667eea;
  color: white;
  text-decoration: none;
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

/* Responsive Design */
@media (max-width: 768px) {
  .profile-header-content {
    flex-direction: column;
    text-align: center;
    gap: 1.5rem;
  }
  
  .name-badges-row {
    flex-direction: column;
    align-items: center;
  }
  
  .profile-name {
    font-size: 2rem;
  }
  
  .quick-stats-row {
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
  }
  
  .profile-completion {
    flex-direction: column;
    gap: 1rem;
  }
  
  .profile-actions {
    flex-direction: column;
  }
  
  .profile-grid {
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
    grid-template-columns: 1fr;
  }
  
  .reminder-content {
    flex-direction: column;
    text-align: center;
  }
  
  .completion-items {
    gap: 0.5rem;
  }
  
  .completion-item {
    padding: 0.75rem;
  }
}

@media (max-width: 480px) {
  .cleaner-profile-container {
    padding: 0.5rem;
  }
  
  .profile-header {
    padding: 1.5rem;
  }
  
  .profile-name {
    font-size: 1.75rem;
  }
  
  .profile-avatar {
    width: 120px;
    height: 120px;
  }
  
  .card-content {
    padding: 1rem;
  }
  
  .profile-actions {
    flex-direction: column;
  }
}
</style>