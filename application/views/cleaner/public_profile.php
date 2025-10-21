<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Cleaner Public Profile View - For Hosts -->
<div class="public-profile-container">
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-header-content">
      <div class="profile-avatar-section">
        <div class="profile-avatar">
          <img src="<?php echo image_user($profile->user_id); ?>" alt="Profile Picture">
          <div class="avatar-badge verified">
            <i class="fas fa-broom"></i>
            <span>Verified Cleaner</span>
          </div>
        </div>
      </div>
      
      <div class="profile-info-section">
        <h1 class="profile-name"><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></h1>
        <p class="profile-username">@<?php echo htmlspecialchars($profile->username); ?></p>
        
        <div class="profile-rating">
          <div class="rating-display">
            <i class="fas fa-star"></i>
            <span class="rating-value"><?php echo number_format($job_stats['average_rating'], 1); ?></span>
            <span class="rating-count">(<?php echo $job_stats['total_reviews']; ?> reviews)</span>
          </div>
        </div>
        
        <div class="profile-stats-inline">
          <div class="stat-inline">
            <i class="fas fa-check-circle"></i>
            <span><?php echo $job_stats['completed_jobs']; ?> Jobs Completed</span>
          </div>
          <div class="stat-inline">
            <i class="fas fa-clock"></i>
            <span><?php echo $job_stats['active_jobs']; ?> Active</span>
          </div>
        </div>
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
                  <span class="tag tag-area"><?php echo htmlspecialchars($area); ?></span>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-map-marker-alt"></i>
                <p>No service areas specified</p>
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
                  <span class="tag tag-specialty"><?php echo htmlspecialchars($specialty); ?></span>
                <?php endforeach; ?>
              </div>
            <?php else: ?>
              <div class="empty-state">
                <i class="fas fa-star"></i>
                <p>No specialties specified</p>
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
            <h3><i class="fas fa-chart-bar"></i> Performance Statistics</h3>
          </div>
          <div class="card-content">
            <div class="stats-grid">
              <div class="stat-card">
                <div class="stat-icon bg-success">
                  <i class="fas fa-check"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo $job_stats['completed_jobs']; ?></div>
                  <div class="stat-label">Jobs Completed</div>
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
                <div class="stat-icon bg-info">
                  <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo number_format($job_stats['average_rating'], 1); ?></div>
                  <div class="stat-label">Average Rating</div>
                </div>
              </div>
              
              <div class="stat-card">
                <div class="stat-icon bg-primary">
                  <i class="fas fa-comments"></i>
                </div>
                <div class="stat-info">
                  <div class="stat-number"><?php echo $job_stats['total_reviews']; ?></div>
                  <div class="stat-label">Total Reviews</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Reviews Card -->
        <div class="profile-card">
          <div class="card-header">
            <h3><i class="fas fa-comments"></i> Reviews</h3>
          </div>
          <div class="card-content">
            <?php if (!empty($reviews)): ?>
              <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                  <div class="review-item">
                    <div class="review-header">
                      <div class="reviewer-name"><?php echo htmlspecialchars($review->reviewer_name); ?></div>
                      <div class="review-rating">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                          <i class="fas fa-star <?php echo $i <= $review->rating ? 'filled' : 'empty'; ?>"></i>
                        <?php endfor; ?>
                      </div>
                    </div>
                    <div class="review-content">
                      <?php echo nl2br(htmlspecialchars($review->comment)); ?>
                    </div>
                    <div class="review-date">
                      <?php echo time_ago($review->created_at); ?>
                    </div>
                  </div>
                <?php endforeach; ?>
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
                <p>Contact information is only shared after an offer is accepted.</p>
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
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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

.tag-area {
  background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
  color: white;
}

.tag-specialty {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
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
