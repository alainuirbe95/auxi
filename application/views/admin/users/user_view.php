<!-- Enhanced UI Version 2.0 - <?php echo time(); ?> -->
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      
      <!-- User Profile Header -->
      <div class="card profile-header-card mb-4">
        <div class="card-header bg-gradient-primary">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0 text-white">
              <i class="fas fa-user-circle mr-2"></i>User Profile
            </h3>
            <div>
              <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Users
              </a>
              <a href="<?php echo base_url('admin/edit_user/' . $user->user_id); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-1"></i>Edit User
              </a>
            </div>
          </div>
        </div>
        
        <div class="card-body p-4">
          <div class="row">
            <!-- User Avatar & Status -->
            <div class="col-md-3 text-center">
              <div class="user-avatar-container mb-3">
                <?php if (!empty($user->avatar) && file_exists('uploads/user/' . $user->avatar)): ?>
                  <img src="<?php echo base_url('uploads/user/' . $user->avatar); ?>" 
                       alt="User Avatar" class="user-avatar-img">
                <?php else: ?>
                  <div class="user-avatar-placeholder">
                    <i class="fas fa-user fa-4x"></i>
                  </div>
                <?php endif; ?>
                
                <!-- Status Overlay Badge -->
                <div class="status-overlay-badge">
                  <?php 
                    $status = $user->banned ?? '0';
                    if ($status == '0') {
                      echo '<span class="badge badge-success badge-pill"><i class="fas fa-check-circle"></i> Active</span>';
                    } else {
                      echo '<span class="badge badge-danger badge-pill"><i class="fas fa-ban"></i> Banned</span>';
                    }
                  ?>
                </div>
              </div>
              
              <!-- User Level Badge -->
              <div class="mb-3">
                <?php 
                  $user_level = $user->auth_level ?? '3';
                  switch($user_level) {
                    case '9':
                      echo '<span class="badge badge-admin badge-lg"><i class="fas fa-crown mr-1"></i>Administrator</span>';
                      break;
                    case '6':
                      echo '<span class="badge badge-host badge-lg"><i class="fas fa-home mr-1"></i>Host</span>';
                      break;
                    case '3':
                      echo '<span class="badge badge-cleaner badge-lg"><i class="fas fa-broom mr-1"></i>Cleaner</span>';
                      break;
                    default:
                      echo '<span class="badge badge-secondary badge-lg">Level ' . $user_level . '</span>';
                      break;
                  }
                ?>
              </div>
              
              <!-- User ID -->
              <div class="user-id-badge">
                <small class="text-muted">User ID:</small>
                <code class="d-block">#<?php echo htmlspecialchars($user->user_id ?? 'N/A'); ?></code>
              </div>
            </div>
            
            <!-- User Information -->
            <div class="col-md-9">
              <div class="user-info-section">
                <h3 class="user-full-name mb-2">
                  <?php 
                    $full_name = '';
                    if (!empty($user->first_name) || !empty($user->last_name)) {
                      $full_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                    } else {
                      $full_name = 'No Name Provided';
                    }
                    echo htmlspecialchars($full_name);
                  ?>
                </h3>
                <p class="user-username mb-3">
                  <i class="fas fa-at text-muted"></i> <?php echo htmlspecialchars($user->username ?? 'N/A'); ?>
                </p>
                
                <div class="row">
                  <div class="col-md-6">
                    <div class="info-group">
                      <label><i class="fas fa-envelope text-primary"></i> Email</label>
                      <a href="mailto:<?php echo htmlspecialchars($user->email ?? ''); ?>" class="info-value text-primary">
                        <?php echo htmlspecialchars($user->email ?? 'N/A'); ?>
                      </a>
                    </div>
                    
                    <div class="info-group">
                      <label><i class="fas fa-phone text-success"></i> Phone</label>
                      <span class="info-value"><?php echo htmlspecialchars($user->phone ?? 'Not provided'); ?></span>
                    </div>
                    
                    <div class="info-group">
                      <label><i class="fas fa-calendar text-info"></i> Date of Birth</label>
                      <span class="info-value">
                        <?php 
                          if (!empty($user->date_of_birth) && $user->date_of_birth != '0000-00-00') {
                            echo date('M d, Y', strtotime($user->date_of_birth));
                          } else {
                            echo 'Not provided';
                          }
                        ?>
                      </span>
                    </div>
                  </div>
                  
                  <div class="col-md-6">
                    <div class="info-group">
                      <label><i class="fas fa-map-marker-alt text-danger"></i> Location</label>
                      <span class="info-value">
                        <?php 
                          $location_parts = array_filter([
                            $user->city ?? null,
                            $user->country ?? null
                          ]);
                          echo !empty($location_parts) ? implode(', ', $location_parts) : 'Not provided';
                        ?>
                      </span>
                    </div>
                    
                    <div class="info-group">
                      <label><i class="fas fa-home text-warning"></i> Address</label>
                      <span class="info-value"><?php echo htmlspecialchars($user->address ?? 'Not provided'); ?></span>
                    </div>
                    
                    <div class="info-group">
                      <label><i class="fas fa-clock text-secondary"></i> Member Since</label>
                      <span class="info-value">
                        <?php 
                          if (!empty($user->created_at)) {
                            echo date('M d, Y', strtotime($user->created_at));
                          } else {
                            echo 'N/A';
                          }
                        ?>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Profile Completion (for Cleaners & Hosts) -->
      <?php if (in_array($user->auth_level, ['3', '6'])): ?>
      <div class="card mb-4">
        <div class="card-header bg-gradient-info">
          <h5 class="card-title mb-0 text-white">
            <i class="fas fa-tasks mr-2"></i>Profile Completion
          </h5>
        </div>
        <div class="card-body">
          <?php
            // Calculate profile completion
            $completion_score = 0;
            $max_score = 100;
            
            // Basic info (40 points)
            if (!empty($user->bio)) $completion_score += 20;
            if (!empty($user->phone)) $completion_score += 10;
            if (!empty($user->avatar)) $completion_score += 10;
            
            // Location (30 points)
            if (!empty($user->city)) $completion_score += 15;
            if (!empty($user->country)) $completion_score += 15;
            
            // Additional info (30 points)
            if (!empty($user->date_of_birth) && $user->date_of_birth != '0000-00-00') $completion_score += 15;
            if (!empty($user->address)) $completion_score += 15;
            
            $completion_percentage = $completion_score;
            $progress_color = $completion_percentage >= 80 ? 'success' : ($completion_percentage >= 50 ? 'warning' : 'danger');
          ?>
          
          <div class="row align-items-center mb-3">
            <div class="col-md-8">
              <div class="progress" style="height: 30px;">
                <div class="progress-bar bg-<?php echo $progress_color; ?> progress-bar-striped progress-bar-animated" 
                     role="progressbar" 
                     style="width: <?php echo $completion_percentage; ?>%"
                     aria-valuenow="<?php echo $completion_percentage; ?>" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                  <strong><?php echo $completion_percentage; ?>% Complete</strong>
                </div>
              </div>
            </div>
            <div class="col-md-4 text-center">
              <h3 class="mb-0 text-<?php echo $progress_color; ?>">
                <?php echo $completion_percentage; ?>%
              </h3>
              <small class="text-muted">Profile Score</small>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <h6 class="text-success"><i class="fas fa-check-circle"></i> Completed</h6>
              <ul class="list-unstyled">
                <li><i class="fas fa-check text-success"></i> Account Created</li>
                <li><i class="fas fa-check text-success"></i> Email Verified</li>
                <?php if (!empty($user->bio)): ?>
                  <li><i class="fas fa-check text-success"></i> Bio Added</li>
                <?php endif; ?>
                <?php if (!empty($user->phone)): ?>
                  <li><i class="fas fa-check text-success"></i> Phone Number</li>
                <?php endif; ?>
                <?php if (!empty($user->avatar)): ?>
                  <li><i class="fas fa-check text-success"></i> Profile Picture</li>
                <?php endif; ?>
                <?php if (!empty($user->city) && !empty($user->country)): ?>
                  <li><i class="fas fa-check text-success"></i> Location Information</li>
                <?php endif; ?>
              </ul>
            </div>
            <div class="col-md-6">
              <h6 class="text-warning"><i class="fas fa-exclamation-circle"></i> Incomplete</h6>
              <ul class="list-unstyled">
                <?php if (empty($user->bio)): ?>
                  <li><i class="fas fa-times text-danger"></i> Bio (20 points)</li>
                <?php endif; ?>
                <?php if (empty($user->phone)): ?>
                  <li><i class="fas fa-times text-danger"></i> Phone Number (10 points)</li>
                <?php endif; ?>
                <?php if (empty($user->avatar)): ?>
                  <li><i class="fas fa-times text-danger"></i> Profile Picture (10 points)</li>
                <?php endif; ?>
                <?php if (empty($user->city) || empty($user->country)): ?>
                  <li><i class="fas fa-times text-danger"></i> Location (30 points)</li>
                <?php endif; ?>
                <?php if (empty($user->date_of_birth) || $user->date_of_birth == '0000-00-00'): ?>
                  <li><i class="fas fa-times text-danger"></i> Date of Birth (15 points)</li>
                <?php endif; ?>
                <?php if (empty($user->address)): ?>
                  <li><i class="fas fa-times text-danger"></i> Full Address (15 points)</li>
                <?php endif; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Bio Section (if exists) -->
      <?php if (!empty($user->bio)): ?>
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="fas fa-quote-left mr-2"></i>Bio
          </h5>
        </div>
        <div class="card-body">
          <p class="mb-0"><?php echo nl2br(htmlspecialchars($user->bio)); ?></p>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Cleaner Service Areas (for Cleaners only) -->
      <?php if ($user->auth_level == '3' && !empty($user->service_areas)): ?>
      <div class="card mb-4">
        <div class="card-header bg-gradient-success">
          <h5 class="card-title mb-0 text-white">
            <i class="fas fa-map-marked-alt mr-2"></i>Service Areas
          </h5>
        </div>
        <div class="card-body">
          <?php
            $service_areas = json_decode($user->service_areas, true);
            if (!empty($service_areas) && is_array($service_areas)):
          ?>
            <div class="row">
              <?php foreach ($service_areas as $area): ?>
                <div class="col-md-4 col-sm-6 mb-2">
                  <span class="badge badge-info badge-service-area">
                    <i class="fas fa-map-marker-alt mr-1"></i><?php echo htmlspecialchars($area); ?>
                  </span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-muted mb-0">No service areas specified</p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Cleaner Specialties (for Cleaners only) -->
      <?php if ($user->auth_level == '3' && !empty($user->specialties)): ?>
      <div class="card mb-4">
        <div class="card-header bg-gradient-warning">
          <h5 class="card-title mb-0 text-white">
            <i class="fas fa-star mr-2"></i>Cleaning Specialties
          </h5>
        </div>
        <div class="card-body">
          <?php
            $specialties = json_decode($user->specialties, true);
            if (!empty($specialties) && is_array($specialties)):
          ?>
            <div class="row">
              <?php foreach ($specialties as $specialty): ?>
                <div class="col-md-4 col-sm-6 mb-2">
                  <span class="badge badge-warning badge-specialty">
                    <i class="fas fa-check-circle mr-1"></i><?php echo htmlspecialchars($specialty); ?>
                  </span>
                </div>
              <?php endforeach; ?>
            </div>
          <?php else: ?>
            <p class="text-muted mb-0">No specialties specified</p>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Statistics Row -->
      <div class="row">
        <!-- Security Information -->
        <div class="col-lg-4 col-md-6">
          <div class="card stats-card mb-4">
            <div class="card-header bg-gradient-danger">
              <h5 class="card-title mb-0 text-white">
                <i class="fas fa-shield-alt mr-2"></i>Security
              </h5>
            </div>
            <div class="card-body">
              <div class="security-stat">
                <label>Email Verified</label>
                <?php 
                  $email_verified = $user->email_verified ?? '0';
                  if ($email_verified == '1') {
                    echo '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Yes</span>';
                  } else {
                    echo '<span class="badge badge-warning"><i class="fas fa-exclamation-triangle"></i> No</span>';
                  }
                ?>
              </div>
              
              <div class="security-stat">
                <label>Account Locked</label>
                <?php 
                  $locked = $user->locked ?? '0';
                  if ($locked == '1') {
                    echo '<span class="badge badge-danger"><i class="fas fa-lock"></i> Yes</span>';
                  } else {
                    echo '<span class="badge badge-success"><i class="fas fa-unlock"></i> No</span>';
                  }
                ?>
              </div>
              
              <div class="security-stat">
                <label>Failed Attempts</label>
                <span class="badge <?php echo ($user->failed_login_count ?? 0) > 5 ? 'badge-danger' : 'badge-success'; ?>">
                  <?php echo htmlspecialchars($user->failed_login_count ?? '0'); ?>
                </span>
              </div>
              
              <div class="security-stat">
                <label>Password Changed</label>
                <span class="text-muted small">
                  <?php 
                    if (!empty($user->passwd_modified)) {
                      echo date('M d, Y', strtotime($user->passwd_modified));
                    } else {
                      echo 'Never';
                    }
                  ?>
                </span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Login Activity -->
        <div class="col-lg-4 col-md-6">
          <div class="card stats-card mb-4">
            <div class="card-header bg-gradient-primary">
              <h5 class="card-title mb-0 text-white">
                <i class="fas fa-sign-in-alt mr-2"></i>Login Activity
              </h5>
            </div>
            <div class="card-body">
              <div class="stat-box">
                <h2 class="stat-number text-primary"><?php echo htmlspecialchars($user->login_count ?? '0'); ?></h2>
                <p class="stat-label">Total Logins</p>
              </div>
              
              <hr>
              
              <div class="activity-info">
                <label><i class="fas fa-clock"></i> Last Login</label>
                <span class="text-muted">
                  <?php 
                    if (!empty($user->last_login)) {
                      echo date('M d, Y H:i', strtotime($user->last_login));
                    } else {
                      echo 'Never';
                    }
                  ?>
                </span>
              </div>
              
              <div class="activity-info">
                <label><i class="fas fa-network-wired"></i> Last IP</label>
                <code><?php echo htmlspecialchars($user->last_ip ?? 'N/A'); ?></code>
              </div>
              
              <div class="activity-info">
                <label><i class="fas fa-calendar-day"></i> Days Since Login</label>
                <span class="badge badge-info">
                  <?php 
                    if (!empty($user->last_login)) {
                      $days_ago = floor((time() - strtotime($user->last_login)) / (60 * 60 * 24));
                      echo $days_ago . ' days';
                    } else {
                      echo '∞';
                    }
                  ?>
                </span>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Account Stats -->
        <div class="col-lg-4 col-md-12">
          <div class="card stats-card mb-4">
            <div class="card-header bg-gradient-success">
              <h5 class="card-title mb-0 text-white">
                <i class="fas fa-chart-line mr-2"></i>Account Stats
              </h5>
            </div>
            <div class="card-body">
              <div class="row text-center">
                <div class="col-6">
                  <div class="mini-stat-box">
                    <h3 class="text-success mb-1"><?php echo htmlspecialchars($user->login_count ?? '0'); ?></h3>
                    <small class="text-muted">Logins</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="mini-stat-box">
                    <h3 class="text-info mb-1">
                      <?php 
                        if (!empty($user->created_at)) {
                          $days_member = floor((time() - strtotime($user->created_at)) / (60 * 60 * 24));
                          echo $days_member;
                        } else {
                          echo '0';
                        }
                      ?>
                    </h3>
                    <small class="text-muted">Days Member</small>
                  </div>
                </div>
              </div>
              
              <hr>
              
              <div class="activity-timeline">
                <div class="timeline-item">
                  <i class="fas fa-user-plus text-success"></i>
                  <span>Joined: <?php echo !empty($user->created_at) ? date('M d, Y', strtotime($user->created_at)) : 'N/A'; ?></span>
                </div>
                <div class="timeline-item">
                  <i class="fas fa-sign-in-alt text-primary"></i>
                  <span>Last Login: <?php echo !empty($user->last_login) ? date('M d, Y', strtotime($user->last_login)) : 'Never'; ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Quick Actions -->
      <div class="card mb-4">
        <div class="card-header bg-gradient-dark">
          <h5 class="card-title mb-0 text-white">
            <i class="fas fa-bolt mr-2"></i>Quick Actions
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
              <a href="<?php echo base_url('admin/edit_user/' . $user->user_id); ?>" class="btn btn-warning btn-block btn-action">
                <i class="fas fa-edit"></i>
                <span>Edit User</span>
              </a>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
              <?php if (($user->banned ?? '0') == '0'): ?>
                <button type="button" class="btn btn-danger btn-block btn-action" onclick="banUser(<?php echo $user->user_id; ?>)">
                  <i class="fas fa-ban"></i>
                  <span>Ban User</span>
                </button>
              <?php else: ?>
                <button type="button" class="btn btn-success btn-block btn-action" onclick="unbanUser(<?php echo $user->user_id; ?>)">
                  <i class="fas fa-check-circle"></i>
                  <span>Unban User</span>
                </button>
              <?php endif; ?>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
              <button type="button" class="btn btn-info btn-block btn-action" onclick="resetPassword(<?php echo $user->user_id; ?>)">
                <i class="fas fa-key"></i>
                <span>Reset Password</span>
              </button>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
              <button type="button" class="btn btn-secondary btn-block btn-action" onclick="deleteUser(<?php echo $user->user_id; ?>)">
                <i class="fas fa-trash"></i>
                <span>Delete User</span>
              </button>
            </div>
          </div>
          
          <!-- Action Info Alert -->
          <div class="alert alert-info mb-0 mt-3">
            <h6><i class="fas fa-info-circle mr-2"></i>Action Information</h6>
            <ul class="mb-0 small">
              <li><strong>Edit:</strong> Modify user details and permissions</li>
              <li><strong>Ban:</strong> Prevent user from logging in (reversible)</li>
              <li><strong>Reset Password:</strong> Generate temporary password (user must change on next login)</li>
              <li><strong>Delete:</strong> Permanently remove user account (cannot be undone)</li>
            </ul>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

<style>
/* Page Background */
.container-fluid {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
  padding: 2rem 1rem;
}

/* Modern Card Styling */
.card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  transition: all 0.3s ease;
  overflow: hidden;
  background: white;
}

.card:hover {
  box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
  transform: translateY(-2px);
}

.profile-header-card {
  position: relative;
  overflow: visible;
}

.profile-header-card .card-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  padding: 2rem 1.5rem;
  position: relative;
  overflow: hidden;
}

.profile-header-card .card-header::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: headerFloat 15s ease-in-out infinite;
}

@keyframes headerFloat {
  0%, 100% { transform: translate(0, 0) rotate(0deg); }
  50% { transform: translate(-20px, -20px) rotate(180deg); }
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
  overflow: hidden;
}

.bg-gradient-primary::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(45deg, rgba(255,255,255,0.1) 25%, transparent 25%, transparent 75%, rgba(255,255,255,0.1) 75%);
  background-size: 20px 20px;
  opacity: 0.3;
}

.bg-gradient-info {
  background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
  position: relative;
  overflow: hidden;
}

.bg-gradient-success {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  position: relative;
  overflow: hidden;
}

.bg-gradient-warning {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  position: relative;
  overflow: hidden;
}

.bg-gradient-danger {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
  position: relative;
  overflow: hidden;
}

.bg-gradient-dark {
  background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
  position: relative;
  overflow: hidden;
}

.card-header {
  position: relative;
  z-index: 2;
}

/* User Avatar Styling */
.user-avatar-container {
  position: relative;
  display: inline-block;
  animation: fadeInScale 0.6s ease-out;
}

@keyframes fadeInScale {
  0% {
    opacity: 0;
    transform: scale(0.8);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.user-avatar-img {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  object-fit: cover;
  border: 6px solid white;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15), 0 0 0 3px rgba(102, 126, 234, 0.2);
  transition: all 0.4s ease;
}

.user-avatar-img:hover {
  transform: scale(1.05);
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.2), 0 0 0 4px rgba(102, 126, 234, 0.4);
}

.user-avatar-placeholder {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15), 0 0 0 3px rgba(102, 126, 234, 0.2);
  margin: 0 auto;
  position: relative;
  overflow: hidden;
}

.user-avatar-placeholder::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
  animation: avatarPulse 3s ease-in-out infinite;
}

@keyframes avatarPulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.2); opacity: 0.5; }
}

.user-avatar-placeholder i {
  position: relative;
  z-index: 1;
}

.status-overlay-badge {
  position: absolute;
  bottom: 5px;
  right: 5px;
  animation: bounceIn 0.8s ease-out 0.3s both;
}

@keyframes bounceIn {
  0% {
    opacity: 0;
    transform: scale(0.3);
  }
  50% {
    opacity: 1;
    transform: scale(1.1);
  }
  70% {
    transform: scale(0.9);
  }
  100% {
    transform: scale(1);
  }
}

.status-overlay-badge .badge {
  padding: 0.6rem 0.9rem;
  font-size: 0.85rem;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  border: 2px solid white;
  font-weight: 600;
}

/* Badge Styling */
.badge {
  transition: all 0.3s ease;
}

.badge:hover {
  transform: scale(1.05);
}

.badge-lg {
  font-size: 1rem;
  padding: 0.75rem 1.25rem;
  border-radius: 25px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
  animation: slideInDown 0.5s ease-out;
  position: relative;
  overflow: hidden;
}

@keyframes slideInDown {
  0% {
    opacity: 0;
    transform: translateY(-20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.badge-lg::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
  transition: left 0.5s ease;
}

.badge-lg:hover::before {
  left: 100%;
}

.badge-admin {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
  box-shadow: 0 4px 20px rgba(245, 87, 108, 0.4);
}

.badge-host {
  background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
  color: #8B4513;
  box-shadow: 0 4px 20px rgba(252, 182, 159, 0.4);
}

.badge-cleaner {
  background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
  color: #0c7993;
  box-shadow: 0 4px 20px rgba(168, 237, 234, 0.4);
}

.badge-service-area {
  padding: 0.6rem 1.2rem;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  display: inline-block;
  width: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 10px;
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
  transition: all 0.3s ease;
  animation: fadeInUp 0.5s ease-out backwards;
  animation-delay: calc(var(--i) * 0.05s);
}

.badge-service-area:hover {
  transform: translateY(-3px);
  box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
}

@keyframes fadeInUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.badge-specialty {
  padding: 0.6rem 1.2rem;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 0.75rem;
  display: inline-block;
  width: 100%;
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  color: white;
  border-radius: 10px;
  box-shadow: 0 3px 10px rgba(240, 147, 251, 0.3);
  transition: all 0.3s ease;
  animation: fadeInUp 0.5s ease-out backwards;
  animation-delay: calc(var(--i) * 0.05s);
}

.badge-specialty:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 6px 20px rgba(240, 147, 251, 0.5);
}

/* User Info Section */
.user-info-section {
  padding-left: 1rem;
}

.user-full-name {
  font-size: 2rem;
  font-weight: 700;
  color: #2c3e50;
  margin-bottom: 0.5rem;
}

.user-username {
  font-size: 1.1rem;
  color: #6c757d;
}

.user-id-badge {
  margin-top: 1rem;
  padding: 0.5rem;
  background: #f8f9fa;
  border-radius: 8px;
}

.user-id-badge code {
  font-size: 1.1rem;
  font-weight: 600;
  color: #667eea;
}

/* Info Groups */
.info-group {
  margin-bottom: 1.25rem;
}

.info-group label {
  display: block;
  font-weight: 600;
  font-size: 0.875rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.info-value {
  display: block;
  font-size: 1rem;
  color: #2c3e50;
  font-weight: 500;
}

/* Stats Cards */
.stats-card .card-header {
  border: none;
  padding: 1rem 1.25rem;
}

.stats-card .card-body {
  padding: 1.5rem;
}

.security-stat {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.75rem 0;
  border-bottom: 1px solid #e9ecef;
}

.security-stat:last-child {
  border-bottom: none;
}

.security-stat label {
  font-weight: 600;
  color: #6c757d;
  margin: 0;
}

.stat-box {
  text-align: center;
  padding: 1rem 0;
}

.stat-number {
  font-size: 3rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  line-height: 1;
}

.stat-label {
  color: #6c757d;
  font-size: 0.9rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin: 0;
}

.activity-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.5rem 0;
  border-bottom: 1px solid #e9ecef;
}

.activity-info:last-child {
  border-bottom: none;
}

.activity-info label {
  font-weight: 600;
  color: #6c757d;
  margin: 0;
  font-size: 0.875rem;
}

.mini-stat-box {
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 8px;
  margin-bottom: 1rem;
}

.activity-timeline {
  margin-top: 1rem;
}

.timeline-item {
  display: flex;
  align-items: center;
  padding: 0.5rem 0;
  font-size: 0.9rem;
  color: #6c757d;
}

.timeline-item i {
  margin-right: 0.75rem;
  font-size: 1.1rem;
}

/* Action Buttons */
.btn-action {
  padding: 1.5rem;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 15px;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  min-height: 120px;
  border: none;
  position: relative;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.btn-action::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s, height 0.6s;
}

.btn-action:hover::before {
  width: 300px;
  height: 300px;
}

.btn-action i {
  font-size: 2.5rem;
  margin-bottom: 0.75rem;
  position: relative;
  z-index: 1;
  transition: all 0.3s ease;
}

.btn-action span {
  position: relative;
  z-index: 1;
}

.btn-action:hover {
  transform: translateY(-5px) scale(1.05);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.btn-action:hover i {
  transform: scale(1.2) rotate(5deg);
}

.btn-action:active {
  transform: translateY(-2px) scale(1.02);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
}

/* Enhanced Button Colors */
.btn-warning {
  background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
  border: none;
  color: white;
}

.btn-warning:hover {
  background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
  color: white;
}

.btn-danger {
  background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
  border: none;
  color: white;
}

.btn-danger:hover {
  background: linear-gradient(135deg, #fee140 0%, #fa709a 100%);
  color: white;
}

.btn-success {
  background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
  border: none;
  color: white;
}

.btn-success:hover {
  background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
  color: white;
}

.btn-info {
  background: linear-gradient(135deg, #36d1dc 0%, #5b86e5 100%);
  border: none;
  color: white;
}

.btn-info:hover {
  background: linear-gradient(135deg, #5b86e5 0%, #36d1dc 100%);
  color: white;
}

.btn-secondary {
  background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
  border: none;
  color: white;
}

.btn-secondary:hover {
  background: linear-gradient(135deg, #34495e 0%, #2c3e50 100%);
  color: white;
}

.btn-light {
  background: white;
  border: 2px solid rgba(255, 255, 255, 0.5);
  color: #667eea;
  backdrop-filter: blur(10px);
  box-shadow: 0 4px 15px rgba(255, 255, 255, 0.2);
}

.btn-light:hover {
  background: rgba(255, 255, 255, 0.95);
  border-color: white;
  color: #764ba2;
  box-shadow: 0 6px 20px rgba(255, 255, 255, 0.4);
}

/* Progress Bar */
.progress {
  background-color: #e9ecef;
  border-radius: 15px;
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: visible;
}

.progress-bar {
  border-radius: 15px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
  transition: width 1s ease-in-out;
}

.progress-bar::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background: linear-gradient(90deg, 
    transparent, 
    rgba(255,255,255,0.4), 
    transparent
  );
  animation: shimmer 2s infinite;
}

@keyframes shimmer {
  0% { transform: translateX(-100%); }
  100% { transform: translateX(100%); }
}

/* Info Group Enhancements */
.info-group {
  padding: 0.75rem;
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
  border-radius: 10px;
  border-left: 4px solid #667eea;
  transition: all 0.3s ease;
  animation: fadeIn 0.6s ease-out backwards;
  animation-delay: calc(var(--i) * 0.1s);
}

@keyframes fadeIn {
  0% {
    opacity: 0;
    transform: translateX(-10px);
  }
  100% {
    opacity: 1;
    transform: translateX(0);
  }
}

.info-group:hover {
  background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
  transform: translateX(5px);
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.15);
}

/* Stats Card Enhancements */
.stats-card {
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.stat-box {
  animation: countUp 1s ease-out;
}

@keyframes countUp {
  0% {
    opacity: 0;
    transform: scale(0.5);
  }
  50% {
    transform: scale(1.1);
  }
  100% {
    opacity: 1;
    transform: scale(1);
  }
}

.mini-stat-box {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(240, 242, 245, 0.9) 100%);
  border: 2px solid rgba(102, 126, 234, 0.1);
  transition: all 0.3s ease;
}

.mini-stat-box:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  border-color: rgba(102, 126, 234, 0.3);
}

/* Alert Styling */
.alert {
  border: none;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  backdrop-filter: blur(10px);
}

.alert-info {
  background: linear-gradient(135deg, rgba(54, 209, 220, 0.1) 0%, rgba(91, 134, 229, 0.1) 100%);
  border-left: 4px solid #36d1dc;
}

/* User Name Styling */
.user-full-name {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  animation: textShine 3s ease-in-out infinite;
}

@keyframes textShine {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

/* Glassmorphism Effects */
.user-id-badge {
  background: rgba(255, 255, 255, 0.8);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255, 255, 255, 0.3);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
}

/* Loading Animation for Page Load */
@keyframes pageLoad {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

.card {
  animation: pageLoad 0.6s ease-out backwards;
}

.card:nth-child(1) { animation-delay: 0.1s; }
.card:nth-child(2) { animation-delay: 0.2s; }
.card:nth-child(3) { animation-delay: 0.3s; }
.card:nth-child(4) { animation-delay: 0.4s; }
.card:nth-child(5) { animation-delay: 0.5s; }
.card:nth-child(6) { animation-delay: 0.6s; }

/* Responsive */
@media (max-width: 767.98px) {
  .user-avatar-img,
  .user-avatar-placeholder {
    width: 100px;
    height: 100px;
  }
  
  .user-full-name {
    font-size: 1.5rem;
  }
  
  .stat-number {
    font-size: 2rem;
  }
  
  .btn-action {
    min-height: auto;
    padding: 0.75rem;
  }
  
  .btn-action i {
    font-size: 1.5rem;
    margin-bottom: 0.25rem;
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
