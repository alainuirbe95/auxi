<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Host Public Profile View - For Cleaners -->
<div class="public-profile-container">
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-header-content">
      <div class="profile-avatar-section">
        <div class="profile-avatar">
          <img src="<?php echo image_user($profile->user_id); ?>" alt="Profile Picture">
          <div class="avatar-badge verified">
            <i class="fas fa-home"></i>
            <span>Verified Host</span>
          </div>
        </div>
      </div>
      
      <div class="profile-info-section">
        <h1 class="profile-name"><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></h1>
        <p class="profile-username">@<?php echo htmlspecialchars($profile->username); ?></p>
        
        <!-- Enhanced Rating Display -->
        <div class="profile-rating-enhanced">
          <?php if (isset($review_stats) && $review_stats['total_reviews'] > 0): ?>
            <?php 
            $avg_rating = $review_stats['overall_average'];
            $badge_class = '';
            $badge_text = '';
            if ($avg_rating >= 4.8) {
                $badge_class = 'badge-platinum';
                $badge_text = '⭐ Platinum Host';
            } elseif ($avg_rating >= 4.5) {
                $badge_class = 'badge-gold';
                $badge_text = '🏆 Gold Host';
            } elseif ($avg_rating >= 4.0) {
                $badge_class = 'badge-silver';
                $badge_text = '🥈 Silver Host';
            } else {
                $badge_class = 'badge-bronze';
                $badge_text = '🥉 Rated Host';
            }
            ?>
            <div class="rating-badge-container">
              <span class="rating-badge <?php echo $badge_class; ?>"><?php echo $badge_text; ?></span>
            </div>
            <div class="rating-display-large">
              <div class="rating-number-large"><?php echo number_format($review_stats['overall_average'], 1); ?></div>
              <div class="rating-stars-large">
                <?php for($i = 1; $i <= 5; $i++): ?>
                  <i class="fas fa-star <?php echo $i <= round($review_stats['overall_average']) ? 'filled' : 'empty'; ?>"></i>
                <?php endfor; ?>
              </div>
              <div class="rating-count-large"><?php echo $review_stats['total_reviews']; ?> reviews</div>
            </div>
          <?php else: ?>
            <div class="rating-display-large">
              <div class="rating-number-large">0.0</div>
              <div class="rating-stars-large">
                <i class="far fa-star empty"></i>
                <i class="far fa-star empty"></i>
                <i class="far fa-star empty"></i>
                <i class="far fa-star empty"></i>
                <i class="far fa-star empty"></i>
              </div>
              <div class="rating-count-large">No reviews yet</div>
            </div>
          <?php endif; ?>
        </div>
        
        <div class="profile-stats-inline">
          <div class="stat-inline">
            <i class="fas fa-briefcase"></i>
            <span><?php echo $job_stats['total_jobs']; ?> Jobs Posted</span>
          </div>
          <div class="stat-inline">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $job_stats['completed_jobs']; ?> Completed</span>
          </div>
        </div>
        
        <?php if (!empty($profile->user_city) || !empty($profile->user_country)): ?>
        <div class="profile-location">
          <i class="fas fa-map-marker-alt"></i>
          <span>
            <?php 
            $location_parts = array_filter([$profile->user_city, $profile->user_country]);
            echo htmlspecialchars(implode(', ', $location_parts));
            ?>
          </span>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Profile Content -->
  <div class="profile-content">
    <div class="profile-grid">
      
      <!-- Left Column -->
      <div class="profile-column">
        
        <!-- About Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-user"></i> About</h3>
          </div>
          <div class="card-content">
            <?php if (!empty($profile->bio)): ?>
              <div class="bio-content">
                <?php echo nl2br(htmlspecialchars($profile->bio)); ?>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-user"></i>
                <p>No bio provided</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Location Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-map-marker-alt"></i> General Location</h3>
          </div>
          <div class="card-content">
            <?php if (!empty($profile->user_city) || !empty($profile->user_country)): ?>
              <div class="location-display">
                <div class="location-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="location-info">
                  <div class="location-text">
                    <?php 
                    $location_parts = array_filter([$profile->user_city, $profile->user_country]);
                    echo htmlspecialchars(implode(', ', $location_parts));
                    ?>
                  </div>
                  <small class="location-note">Exact address shared after offer acceptance</small>
                </div>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-map-marker-alt"></i>
                <p>No location specified</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

      </div>

      <!-- Right Column -->
      <div class="profile-column">
        
        <!-- Job Statistics Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-chart-bar"></i> Hosting Statistics</h3>
          </div>
          <div class="card-content">
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon bg-info">
                  <i class="fas fa-briefcase"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo $job_stats['total_jobs']; ?></div>
                  <div class="stat-label">Total Jobs</div>
                </div>
              </div>
              
              <div class="stat-card">
                <div class="stat-icon bg-warning">
                  <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo $job_stats['active_jobs']; ?></div>
                  <div class="stat-label">Active Jobs</div>
                </div>
              </div>
              
              <div class="stat-card">
                <div class="stat-icon bg-success">
                  <i class="fas fa-check"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo $job_stats['completed_jobs']; ?></div>
                  <div class="stat-label">Completed Jobs</div>
                </div>
              </div>
              
              <div class="stat-card">
                <div class="stat-icon bg-primary">
                  <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo number_format($job_stats['average_rating'], 1); ?></div>
                  <div class="stat-label">Average Rating</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Rating Summary Card -->
        <?php if (isset($review_stats) && $review_stats['total_reviews'] > 0): ?>
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-star"></i> Rating Summary</h3>
          </div>
          <div class="card-content">
            <div class="rating-summary-display">
              <div class="rating-number">
                <span class="big-rating"><?php echo number_format($review_stats['overall_average'], 1); ?></span>
                <div class="rating-stars">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= round($review_stats['overall_average']) ? 'filled' : 'empty'; ?>"></i>
                  <?php endfor; ?>
                </div>
                <div class="rating-total"><?php echo $review_stats['total_reviews']; ?> <?php echo $review_stats['total_reviews'] == 1 ? 'review' : 'reviews'; ?></div>
              </div>
              
              <!-- Category Ratings -->
              <?php if (isset($review_stats['category_averages'])): ?>
              <div class="category-ratings">
                <?php if ($review_stats['category_averages']['professionalism'] > 0): ?>
                <div class="category-item">
                  <div class="category-label">Professionalism</div>
                  <div class="category-stars">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                      <i class="fas fa-star <?php echo $i <= round($review_stats['category_averages']['professionalism']) ? 'filled' : 'empty'; ?>"></i>
                    <?php endfor; ?>
                    <span class="category-score"><?php echo number_format($review_stats['category_averages']['professionalism'], 1); ?></span>
                  </div>
                </div>
                <?php endif; ?>
                
                <?php if ($review_stats['category_averages']['quality'] > 0): ?>
                <div class="category-item">
                  <div class="category-label">Quality</div>
                  <div class="category-stars">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                      <i class="fas fa-star <?php echo $i <= round($review_stats['category_averages']['quality']) ? 'filled' : 'empty'; ?>"></i>
                    <?php endfor; ?>
                    <span class="category-score"><?php echo number_format($review_stats['category_averages']['quality'], 1); ?></span>
                  </div>
                </div>
                <?php endif; ?>
                
                <?php if ($review_stats['category_averages']['communication'] > 0): ?>
                <div class="category-item">
                  <div class="category-label">Communication</div>
                  <div class="category-stars">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                      <i class="fas fa-star <?php echo $i <= round($review_stats['category_averages']['communication']) ? 'filled' : 'empty'; ?>"></i>
                    <?php endfor; ?>
                    <span class="category-score"><?php echo number_format($review_stats['category_averages']['communication'], 1); ?></span>
                  </div>
                </div>
                <?php endif; ?>
                
                <?php if ($review_stats['category_averages']['punctuality'] > 0): ?>
                <div class="category-item">
                  <div class="category-label">Punctuality</div>
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
          </div>
        </div>
        <?php endif; ?>

        <!-- Reviews Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-comments"></i> Reviews <?php if(isset($review_stats) && $review_stats['total_reviews'] > 0): ?>(<?php echo count($reviews); ?>)<?php endif; ?></h3>
          </div>
          <div class="card-content">
            <?php if (!empty($reviews)): ?>
              <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                  <div class="review-item">
                    <div class="review-header">
                      <div class="reviewer-info">
                        <div class="reviewer-name"><?php echo htmlspecialchars($review->reviewer_name ?? 'Anonymous'); ?></div>
                        <span class="reviewer-badge">Cleaner</span>
                      </div>
                      <div class="review-rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                          <i class="fas fa-star <?php echo $i <= $review->overall_rating ? 'filled' : 'empty'; ?>"></i>
                        <?php endfor; ?>
                        <span class="rating-value"><?php echo number_format($review->overall_rating, 1); ?></span>
                      </div>
                    </div>
                    <div class="review-content">
                      <?php echo nl2br(htmlspecialchars($review->public_comment)); ?>
                    </div>
                    <?php if (!empty($review->job_title)): ?>
                    <div class="review-job">
                      <i class="fas fa-briefcase"></i>
                      Job: <?php echo htmlspecialchars($review->job_title); ?>
                    </div>
                    <?php endif; ?>
                    <div class="review-date">
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
                
                <?php if (isset($review_stats) && $review_stats['total_reviews'] > count($reviews)): ?>
                <div class="more-reviews-notice">
                  Showing <?php echo count($reviews); ?> of <?php echo $review_stats['total_reviews']; ?> reviews
                </div>
                <?php endif; ?>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-comments"></i>
                <p>No reviews yet</p>
              </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Privacy Notice Card -->
        <div class="profile-card privacy-card">
          <div class="card-content">
            <div class="privacy-notice">
              <div class="privacy-icon">
                <i class="fas fa-shield-alt"></i>
              </div>
              <div class="privacy-text">
                <h4>Privacy Protection</h4>
                <p>Contact information and full address are only shared after an offer is accepted.</p>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>

</div>

<style>
/* Public Profile Styles */
.public-profile-container {
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

.avatar-badge {
  position: absolute;
  bottom: -5px;
  right: -5px;
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

.avatar-badge.verified {
  background: linear-gradient(135deg, #007bff 0%, #6f42c1 100%);
  color: white;
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

.profile-rating {
  margin-bottom: 1rem;
}

.profile-rating-enhanced {
  margin: 1.5rem 0;
}

.rating-badge-container {
  margin-bottom: 1rem;
}

.rating-badge {
  display: inline-block;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-size: 1rem;
  font-weight: 700;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.badge-platinum {
  background: linear-gradient(135deg, #e8e8e8 0%, #ffffff 100%);
  color: #333;
  border: 2px solid #d4af37;
}

.badge-gold {
  background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
  color: #333;
  border: 2px solid #d4af37;
}

.badge-silver {
  background: linear-gradient(135deg, #c0c0c0 0%, #e8e8e8 100%);
  color: #333;
  border: 2px solid #a8a8a8;
}

.badge-bronze {
  background: linear-gradient(135deg, #cd7f32 0%, #e89c5e 100%);
  color: white;
  border: 2px solid #a86628;
}

.rating-display-large {
  background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
  border-radius: 15px;
  padding: 1.5rem;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.rating-number-large {
  font-size: 4rem;
  font-weight: 700;
  color: #f57c00;
  line-height: 1;
  margin-bottom: 0.5rem;
}

.rating-stars-large {
  font-size: 2rem;
  margin-bottom: 0.5rem;
}

.rating-stars-large i.filled {
  color: #ffc107;
}

.rating-stars-large i.empty {
  color: #dee2e6;
}

.rating-count-large {
  font-size: 1.1rem;
  color: #6c757d;
  font-weight: 600;
}

.rating-display {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.2rem;
}

.rating-display i {
  color: #ffc107;
}

.rating-value {
  font-weight: 700;
  font-size: 1.5rem;
}

.rating-count {
  opacity: 0.8;
}

.profile-stats-inline {
  display: flex;
  gap: 2rem;
  margin-bottom: 1rem;
}

.stat-inline {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.1rem;
}

.stat-inline i {
  opacity: 0.8;
}

.profile-location {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1.1rem;
  opacity: 0.9;
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

.profile-card.privacy-card {
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

/* Bio Content */
.bio-content {
  line-height: 1.6;
  color: #495057;
  font-size: 1rem;
}

/* Location Display */
.location-display {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1.5rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
}

.location-icon {
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

.location-info {
  flex: 1;
}

.location-text {
  font-size: 1.1rem;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.25rem;
}

.location-note {
  color: #6c757d;
  font-size: 0.85rem;
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
  margin: 0;
  font-size: 1.1rem;
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

/* Reviews */
.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.review-item {
  padding: 1.5rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
  border-left: 4px solid #667eea;
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.75rem;
}

.reviewer-name {
  font-weight: 600;
  color: #495057;
}

.review-rating i.filled {
  color: #ffc107;
}

.review-rating i.empty {
  color: #dee2e6;
}

.review-content {
  color: #495057;
  line-height: 1.5;
  margin-bottom: 0.75rem;
}

.review-date {
  font-size: 0.85rem;
  color: #6c757d;
}

.review-job {
  font-size: 0.9rem;
  color: #6c757d;
  margin-bottom: 0.5rem;
}

.reviewer-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.reviewer-badge {
  font-size: 0.75rem;
  background: #667eea;
  color: white;
  padding: 0.2rem 0.5rem;
  border-radius: 4px;
}

.rating-value {
  margin-left: 0.5rem;
  font-weight: 600;
  color: #495057;
}

.more-reviews-notice {
  text-align: center;
  padding: 1rem;
  color: #6c757d;
  font-size: 0.9rem;
}

/* Rating Summary */
.rating-summary-display {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.rating-number {
  text-align: center;
  padding: 1rem;
  background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
  border-radius: 8px;
}

.big-rating {
  font-size: 3rem;
  font-weight: 700;
  color: #f57c00;
  display: block;
  margin-bottom: 0.5rem;
}

.rating-stars {
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.rating-stars i.filled {
  color: #ffc107;
}

.rating-stars i.empty {
  color: #dee2e6;
}

.rating-total {
  font-size: 0.9rem;
  color: #6c757d;
}

.category-ratings {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.category-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 6px;
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
  font-size: 0.9rem;
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
  font-size: 0.9rem;
}

/* Privacy Notice */
.privacy-notice {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.privacy-icon {
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

.privacy-text h4 {
  margin: 0 0 0.5rem 0;
  color: #495057;
  font-weight: 600;
}

.privacy-text p {
  margin: 0;
  color: #6c757d;
  line-height: 1.5;
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
  
  .profile-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .card-content {
    padding: 1.5rem;
  }
  
  .profile-stats-inline {
    flex-direction: column;
    gap: 1rem;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .public-profile-container {
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
}
</style>
