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
        <h1 class="profile-name"><?php echo htmlspecialchars($profile->username); ?></h1>
        <p class="profile-role">Professional Cleaning Services</p>
        
        <div class="profile-completion">
          <div class="completion-circle">
            <div class="completion-percentage <?php echo $completion['percentage'] >= 50 ? 'success' : 'warning'; ?>">
              <?php echo number_format($completion['percentage'], 0); ?>%
            </div>
            <div class="completion-label">Complete</div>
          </div>
        </div>
        
        <div class="profile-actions">
          <a href="<?php echo base_url('cleaner/edit-profile'); ?>" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profile
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

.profile-name {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.profile-role {
  font-size: 1.2rem;
  margin: 0 0 1.5rem 0;
  opacity: 0.9;
}

.profile-completion {
  margin-bottom: 2rem;
}

.completion-circle {
  text-align: center;
}

.completion-percentage {
  font-size: 2rem;
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
  opacity: 0.8;
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