<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modern Admin Profile View v2.0 -->
<div class="admin-profile-container">
  
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-header-content">
      <div class="profile-avatar-section">
        <?php if (!empty($profile->profile_picture_url)): ?>
          <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile" class="profile-avatar">
        <?php else: ?>
          <div class="profile-avatar-placeholder">
            <i class="fas fa-user"></i>
          </div>
        <?php endif; ?>
        <div class="profile-status-badge">
          <?php if (($profile->banned ?? '0') == '0'): ?>
            <span class="status-active"><i class="fas fa-check"></i> Active</span>
          <?php else: ?>
            <span class="status-banned"><i class="fas fa-ban"></i> Banned</span>
          <?php endif; ?>
        </div>
      </div>
      
      <div class="profile-info-section">
        <h1 class="profile-name">
          <?php echo htmlspecialchars(trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? '')) ?: 'No Name'); ?>
        </h1>
        <p class="profile-username">@<?php echo htmlspecialchars($profile->username ?? 'N/A'); ?></p>
        
        <div class="profile-badges">
          <?php 
            $user_level = $profile->auth_level ?? '3';
            switch($user_level) {
              case '9': echo '<span class="badge-admin"><i class="fas fa-crown"></i> Administrator</span>'; break;
              case '6': echo '<span class="badge-host"><i class="fas fa-home"></i> Host</span>'; break;
              case '3': echo '<span class="badge-cleaner"><i class="fas fa-broom"></i> Cleaner</span>'; break;
              default: echo '<span class="badge-default">Level ' . $user_level . '</span>'; break;
            }
          ?>
          <span class="user-id">ID: #<?php echo $profile->user_id ?? 'N/A'; ?></span>
        </div>
      </div>
      
      <div class="profile-actions">
        <a href="<?php echo base_url('admin/profiles'); ?>" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Back
        </a>
        <a href="<?php echo base_url('admin/edit_user/' . $profile->user_id); ?>" class="btn btn-primary">
          <i class="fas fa-edit"></i> Edit User
        </a>
      </div>
    </div>
  </div>

  <!-- Main Content Grid -->
  <div class="profile-content-grid">
    
    <!-- Left Column -->
    <div class="profile-left-column">
      
      <!-- Basic Information -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-user"></i> Basic Information</h3>
        </div>
        <div class="card-content">
          <div class="info-row">
            <label>Email</label>
            <span><?php echo htmlspecialchars($profile->email ?? 'Not provided'); ?></span>
          </div>
          <div class="info-row">
            <label>Phone</label>
            <span><?php echo htmlspecialchars($profile->user_phone ?? $profile->phone ?? 'Not provided'); ?></span>
          </div>
          <div class="info-row">
            <label>Date of Birth</label>
            <span>
              <?php 
                if (!empty($profile->date_of_birth) && $profile->date_of_birth != '0000-00-00') {
                  echo date('M d, Y', strtotime($profile->date_of_birth));
                } else {
                  echo 'Not provided';
                }
              ?>
            </span>
          </div>
          <div class="info-row">
            <label>Location</label>
            <span>
              <?php 
                $city = $profile->user_city ?? $profile->city ?? null;
                $country = $profile->user_country ?? $profile->country ?? null;
                $location_parts = array_filter([$city, $country]);
                echo !empty($location_parts) ? implode(', ', $location_parts) : 'Not provided';
              ?>
            </span>
          </div>
        </div>
      </div>

      <!-- Profile Completion -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-chart-pie"></i> Profile Completion</h3>
        </div>
        <div class="card-content">
          <?php
            $completion_percentage = isset($completion['percentage']) ? $completion['percentage'] : 0;
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

      <!-- Bio -->
      <?php if (!empty($profile->bio)): ?>
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-quote-left"></i> Bio</h3>
        </div>
        <div class="card-content">
          <p class="bio-text"><?php echo nl2br(htmlspecialchars($profile->bio)); ?></p>
        </div>
      </div>
      <?php endif; ?>

    </div>

    <!-- Right Column -->
    <div class="profile-right-column">
      
      <!-- Service Areas (for Cleaners) -->
      <?php if ($profile->auth_level == '3' && !empty($profile->service_areas)): ?>
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-map-marked-alt"></i> Service Areas</h3>
        </div>
        <div class="card-content">
          <?php
            $service_areas = json_decode($profile->service_areas, true);
            if (!empty($service_areas) && is_array($service_areas)):
          ?>
            <div class="service-areas">
              <?php foreach ($service_areas as $area): ?>
                <span class="service-area-tag"><?php echo htmlspecialchars($area); ?></span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="no-data">No service areas specified</p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Specialties (for Cleaners) -->
      <?php if ($profile->auth_level == '3' && !empty($profile->specialties)): ?>
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-star"></i> Cleaning Specialties</h3>
        </div>
        <div class="card-content">
          <?php
            $specialties = json_decode($profile->specialties, true);
            if (!empty($specialties) && is_array($specialties)):
          ?>
            <div class="specialties">
              <?php foreach ($specialties as $specialty): ?>
                <span class="specialty-tag"><?php echo htmlspecialchars($specialty); ?></span>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="no-data">No specialties specified</p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- Account Statistics -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-chart-line"></i> Account Statistics</h3>
        </div>
        <div class="card-content">
          <div class="stats-grid">
            <div class="stat-item">
              <div class="stat-value"><?php echo $profile->login_count ?? '0'; ?></div>
              <div class="stat-label">Logins</div>
            </div>
            <div class="stat-item">
              <div class="stat-value">
                <?php 
                  $created_at = $profile->user_created_at ?? $profile->created_at;
                  if (!empty($created_at)) {
                    $days_member = floor((time() - strtotime($created_at)) / (60 * 60 * 24));
                    echo $days_member;
                  } else {
                    echo '0';
                  }
                ?>
              </div>
              <div class="stat-label">Days Member</div>
            </div>
          </div>
          <div class="account-timeline">
            <div class="timeline-item">
              <i class="fas fa-user-plus text-success"></i>
              <span>Joined: <?php 
                $created_at = $profile->user_created_at ?? $profile->created_at;
                echo !empty($created_at) ? date('M d, Y', strtotime($created_at)) : 'N/A'; 
              ?></span>
            </div>
            <div class="timeline-item">
              <i class="fas fa-sign-in-alt text-primary"></i>
              <span>Last Login: <?php echo !empty($profile->last_login) ? date('M d, Y', strtotime($profile->last_login)) : 'Never'; ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Security Status -->
      <div class="info-card">
        <div class="card-header">
          <h3><i class="fas fa-shield-alt"></i> Security Status</h3>
        </div>
        <div class="card-content">
          <div class="security-items">
            <div class="security-item">
              <label>Email Verified</label>
              <?php 
                $email_verified = $profile->email_verified ?? '0';
                if ($email_verified == '1') {
                  echo '<span class="status-verified"><i class="fas fa-check"></i> Yes</span>';
                } else {
                  echo '<span class="status-unverified"><i class="fas fa-times"></i> No</span>';
                }
              ?>
            </div>
            <div class="security-item">
              <label>Profile Visibility</label>
              <?php 
                $visibility = $profile->profile_visibility ?? 'public';
                if ($visibility == 'public') {
                  echo '<span class="status-public"><i class="fas fa-eye"></i> Public</span>';
                } else {
                  echo '<span class="status-private"><i class="fas fa-eye-slash"></i> Private</span>';
                }
              ?>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

  <!-- Quick Actions -->
  <div class="quick-actions">
    <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
    <div class="actions-grid">
      <button class="action-btn btn-edit" onclick="window.location.href='<?php echo base_url('admin/edit_user/' . $profile->user_id); ?>'">
        <i class="fas fa-edit"></i>
        <span>Edit User</span>
      </button>
      
      <?php if (($profile->banned ?? '0') == '0'): ?>
        <button class="action-btn btn-ban" onclick="banUser(<?php echo $profile->user_id; ?>)">
          <i class="fas fa-ban"></i>
          <span>Ban User</span>
        </button>
      <?php else: ?>
        <button class="action-btn btn-unban" onclick="unbanUser(<?php echo $profile->user_id; ?>)">
          <i class="fas fa-check"></i>
          <span>Unban User</span>
        </button>
      <?php endif; ?>
      
      <button class="action-btn btn-reset" onclick="resetPassword(<?php echo $profile->user_id; ?>)">
        <i class="fas fa-key"></i>
        <span>Reset Password</span>
      </button>
      
      <button class="action-btn btn-delete" onclick="deleteUser(<?php echo $profile->user_id; ?>)">
        <i class="fas fa-trash"></i>
        <span>Delete User</span>
      </button>
    </div>
  </div>

</div>

<style>
/* Modern Admin Profile Styles */
.admin-profile-container {
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

.status-active, .status-banned {
  background: rgba(255, 255, 255, 0.9);
  color: #28a745;
  padding: 0.25rem 0.5rem;
  border-radius: 12px;
  font-size: 0.75rem;
  font-weight: 600;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.status-banned {
  color: #dc3545;
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

.badge-admin, .badge-host, .badge-cleaner, .badge-default {
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

.btn {
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s ease;
  cursor: pointer;
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.btn-secondary:hover {
  background: rgba(255, 255, 255, 0.3);
  color: white;
  text-decoration: none;
}

.btn-primary {
  background: rgba(255, 255, 255, 0.9);
  color: #667eea;
}

.btn-primary:hover {
  background: white;
  color: #667eea;
  text-decoration: none;
  transform: translateY(-2px);
}

/* Content Grid */
.profile-content-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
  margin-bottom: 2rem;
}

/* Info Cards */
.info-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
}

.info-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #dee2e6;
}

.card-header h3 {
  margin: 0;
  font-size: 1.1rem;
  font-weight: 600;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.card-content {
  padding: 1.5rem;
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

/* Bio */
.bio-text {
  line-height: 1.6;
  color: #495057;
  margin: 0;
}

/* Service Areas & Specialties */
.service-areas, .specialties {
  display: flex;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.service-area-tag, .specialty-tag {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 0.4rem 0.8rem;
  border-radius: 15px;
  font-size: 0.85rem;
  font-weight: 500;
}

.specialty-tag {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.no-data {
  color: #6c757d;
  font-style: italic;
  margin: 0;
}

/* Statistics */
.stats-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stat-item {
  text-align: center;
  padding: 1rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
}

.stat-value {
  font-size: 2rem;
  font-weight: 700;
  color: #667eea;
  margin-bottom: 0.25rem;
}

.stat-label {
  font-size: 0.85rem;
  color: #6c757d;
  font-weight: 500;
}

/* Timeline */
.account-timeline {
  border-top: 1px solid #f1f3f4;
  padding-top: 1rem;
}

.timeline-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  padding: 0.5rem 0;
  font-size: 0.9rem;
  color: #495057;
}

.timeline-item i {
  width: 20px;
  text-align: center;
}

/* Security Items */
.security-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.security-item {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
}

.security-item label {
  font-weight: 600;
  color: #495057;
  margin: 0;
}

.status-verified, .status-unverified, .status-public, .status-private {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.85rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 0.25rem;
}

.status-verified {
  background: #d4edda;
  color: #155724;
}

.status-unverified {
  background: #f8d7da;
  color: #721c24;
}

.status-public {
  background: #cce5ff;
  color: #004085;
}

.status-private {
  background: #e2e3e5;
  color: #383d41;
}

/* Quick Actions */
.quick-actions {
  background: white;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.quick-actions h3 {
  margin: 0 0 1.5rem 0;
  color: #495057;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.actions-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.action-btn {
  padding: 1rem;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  color: white;
}

.btn-edit {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.btn-ban {
  background: linear-gradient(135deg, #dc3545 0%, #e83e8c 100%);
}

.btn-unban {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.btn-reset {
  background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
}

.btn-delete {
  background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.action-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
  text-decoration: none;
  color: white;
}

.action-btn i {
  font-size: 1.5rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .profile-header-content {
    flex-direction: column;
    text-align: center;
    gap: 1.5rem;
  }
  
  .profile-content-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .profile-name {
    font-size: 2rem;
  }
  
  .actions-grid {
    grid-template-columns: 1fr;
  }
  
  .stats-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 480px) {
  .admin-profile-container {
    padding: 0.5rem;
  }
  
  .profile-header {
    padding: 1.5rem;
  }
  
  .profile-avatar, .profile-avatar-placeholder {
    width: 100px;
    height: 100px;
  }
  
  .profile-name {
    font-size: 1.75rem;
  }
  
  .card-content {
    padding: 1rem;
  }
}
</style>

<script>
function banUser(userId) {
  if (confirm('Are you sure you want to ban this user? They will not be able to log in.')) {
    window.location.href = '<?php echo base_url('admin/ban_user/'); ?>' + userId;
  }
}

function unbanUser(userId) {
  if (confirm('Are you sure you want to unban this user? They will be able to log in again.')) {
    window.location.href = '<?php echo base_url('admin/unban_user/'); ?>' + userId;
  }
}

function resetPassword(userId) {
  if (confirm('Are you sure you want to reset this user\'s password? A temporary password will be generated and displayed to you.')) {
    window.location.href = '<?php echo base_url('admin/reset_password/'); ?>' + userId;
  }
}

function deleteUser(userId) {
  if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
    window.location.href = '<?php echo base_url('admin/delete_user/'); ?>' + userId;
  }
}
</script>