<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      
      <!-- User Profile Header -->
      <div class="card mb-4">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
              <i class="fas fa-user mr-2"></i>User Profile
            </h3>
            <div>
              <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Users
              </a>
              <a href="<?php echo base_url('admin/edit_user/' . $user->user_id); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit mr-1"></i>Edit User
              </a>
            </div>
          </div>
        </div>
        
        <div class="card-body">
          <div class="row">
            <!-- User Avatar -->
            <div class="col-md-3 text-center">
              <div class="user-avatar mb-3">
                <?php if (!empty($user->avatar) && file_exists('uploads/user/' . $user->avatar)): ?>
                  <img src="<?php echo base_url('uploads/user/' . $user->avatar); ?>" 
                       alt="User Avatar" class="rounded-circle img-thumbnail" 
                       style="width: 120px; height: 120px; object-fit: cover;">
                <?php else: ?>
                  <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto" 
                       style="width: 120px; height: 120px;">
                    <i class="fas fa-user fa-3x text-white"></i>
                  </div>
                <?php endif; ?>
              </div>
              
              <!-- Status Badge -->
              <div class="mb-3">
                <?php 
                  $status = $user->banned ?? '0';
                  $status_text = '';
                  $status_class = '';
                  
                  switch($status) {
                    case '0':
                      $status_text = 'Active';
                      $status_class = 'badge-success';
                      break;
                    case '1':
                      $status_text = 'Banned';
                      $status_class = 'badge-danger';
                      break;
                    default:
                      $status_text = 'Unknown';
                      $status_class = 'badge-secondary';
                      break;
                  }
                ?>
                <span class="badge <?php echo $status_class; ?> badge-lg"><?php echo $status_text; ?></span>
              </div>
            </div>
            
            <!-- User Information -->
            <div class="col-md-9">
              <h4 class="mb-3">
                <?php 
                  $full_name = '';
                  if (!empty($user->first_name) || !empty($user->last_name)) {
                    $full_name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));
                  } else {
                    $full_name = 'No Name Provided';
                  }
                  echo htmlspecialchars($full_name);
                ?>
              </h4>
              
              <div class="row">
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <td class="font-weight-bold text-muted">User ID:</td>
                      <td><?php echo htmlspecialchars($user->user_id ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">Username:</td>
                      <td><?php echo htmlspecialchars($user->username ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">Email:</td>
                      <td>
                        <a href="mailto:<?php echo htmlspecialchars($user->email ?? ''); ?>" class="text-primary">
                          <?php echo htmlspecialchars($user->email ?? 'N/A'); ?>
                        </a>
                      </td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">First Name:</td>
                      <td><?php echo htmlspecialchars($user->first_name ?? 'N/A'); ?></td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">Last Name:</td>
                      <td><?php echo htmlspecialchars($user->last_name ?? 'N/A'); ?></td>
                    </tr>
                  </table>
                </div>
                
                <div class="col-md-6">
                  <table class="table table-borderless table-sm">
                    <tr>
                      <td class="font-weight-bold text-muted">Registration Date:</td>
                      <td>
                        <?php 
                          if (!empty($user->created_at)) {
                            echo date('M d, Y H:i:s', strtotime($user->created_at));
                          } else {
                            echo 'N/A';
                          }
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">Last Login:</td>
                      <td>
                        <?php 
                          if (!empty($user->last_login)) {
                            echo date('M d, Y H:i:s', strtotime($user->last_login));
                          } else {
                            echo 'Never';
                          }
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">Last IP:</td>
                      <td>
                        <code><?php echo htmlspecialchars($user->last_ip ?? 'N/A'); ?></code>
                      </td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">Login Count:</td>
                      <td><?php echo htmlspecialchars($user->login_count ?? '0'); ?></td>
                    </tr>
                    <tr>
                      <td class="font-weight-bold text-muted">User Level:</td>
                      <td>
                        <?php 
                          $user_level = $user->auth_level ?? '3';
                          switch($user_level) {
                            case '9':
                              echo '<span class="badge badge-danger">Administrator</span>';
                              break;
                            case '6':
                              echo '<span class="badge badge-warning">Host</span>';
                              break;
                            case '3':
                              echo '<span class="badge badge-info">Cleaner</span>';
                              break;
                            default:
                              echo '<span class="badge badge-secondary">Level ' . $user_level . '</span>';
                              break;
                          }
                        ?>
                      </td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Additional Information -->
      <div class="row">
        <!-- Security Information -->
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="fas fa-shield-alt mr-2"></i>Security Information
              </h5>
            </div>
            <div class="card-body">
              <table class="table table-borderless table-sm">
                <tr>
                  <td class="font-weight-bold text-muted">Password Last Changed:</td>
                  <td>
                    <?php 
                      if (!empty($user->passwd_modified)) {
                        echo date('M d, Y H:i:s', strtotime($user->passwd_modified));
                      } else {
                        echo 'N/A';
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-bold text-muted">Failed Login Attempts:</td>
                  <td>
                    <span class="badge <?php echo ($user->failed_login_count ?? 0) > 5 ? 'badge-danger' : 'badge-success'; ?>">
                      <?php echo htmlspecialchars($user->failed_login_count ?? '0'); ?>
                    </span>
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-bold text-muted">Account Locked:</td>
                  <td>
                    <?php 
                      $locked = $user->locked ?? '0';
                      if ($locked == '1') {
                        echo '<span class="badge badge-danger">Yes</span>';
                      } else {
                        echo '<span class="badge badge-success">No</span>';
                      }
                    ?>
                  </td>
                </tr>
                <tr>
                  <td class="font-weight-bold text-muted">Email Verified:</td>
                  <td>
                    <?php 
                      $email_verified = $user->email_verified ?? '0';
                      if ($email_verified == '1') {
                        echo '<span class="badge badge-success">Yes</span>';
                      } else {
                        echo '<span class="badge badge-warning">No</span>';
                      }
                    ?>
                  </td>
                </tr>
              </table>
            </div>
          </div>
        </div>
        
        <!-- Account Statistics -->
        <div class="col-md-6">
          <div class="card mb-4">
            <div class="card-header">
              <h5 class="card-title mb-0">
                <i class="fas fa-chart-bar mr-2"></i>Account Statistics
              </h5>
            </div>
            <div class="card-body">
              <div class="row text-center">
                <div class="col-6">
                  <div class="border rounded p-3">
                    <h4 class="text-primary mb-1"><?php echo htmlspecialchars($user->login_count ?? '0'); ?></h4>
                    <small class="text-muted">Total Logins</small>
                  </div>
                </div>
                <div class="col-6">
                  <div class="border rounded p-3">
                    <h4 class="text-info mb-1">
                      <?php 
                        if (!empty($user->last_login)) {
                          $days_ago = floor((time() - strtotime($user->last_login)) / (60 * 60 * 24));
                          echo $days_ago;
                        } else {
                          echo '∞';
                        }
                      ?>
                    </h4>
                    <small class="text-muted">Days Since Last Login</small>
                  </div>
                </div>
              </div>
              
              <hr>
              
              <div class="row">
                <div class="col-12">
                  <h6 class="text-muted mb-2">Account Activity</h6>
                  <div class="progress mb-2" style="height: 20px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: <?php echo min(100, ($user->login_count ?? 0) * 2); ?>%">
                      <?php echo htmlspecialchars($user->login_count ?? '0'); ?> Logins
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Quick Actions -->
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="fas fa-cogs mr-2"></i>Quick Actions
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 mb-2">
              <a href="<?php echo base_url('admin/edit_user/' . $user->user_id); ?>" class="btn btn-warning btn-block">
                <i class="fas fa-edit mr-1"></i>Edit User
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <?php if (($user->banned ?? '0') == '0'): ?>
                <button type="button" class="btn btn-danger btn-block" onclick="banUser(<?php echo $user->user_id; ?>)">
                  <i class="fas fa-ban mr-1"></i>Ban User
                </button>
              <?php else: ?>
                <button type="button" class="btn btn-success btn-block" onclick="unbanUser(<?php echo $user->user_id; ?>)">
                  <i class="fas fa-check mr-1"></i>Unban User
                </button>
              <?php endif; ?>
            </div>
            <div class="col-md-3 mb-2">
              <button type="button" class="btn btn-info btn-block" onclick="resetPassword(<?php echo $user->user_id; ?>)">
                <i class="fas fa-key mr-1"></i>Reset Password
              </button>
            </div>
            <div class="col-md-3 mb-2">
              <button type="button" class="btn btn-secondary btn-block" onclick="deleteUser(<?php echo $user->user_id; ?>)">
                <i class="fas fa-trash mr-1"></i>Delete User
              </button>
            </div>
          </div>
          
          <!-- Password Reset Info -->
          <div class="alert alert-info mt-3">
            <h6><i class="fas fa-info-circle mr-2"></i>Password Reset Information</h6>
            <p class="mb-0 small">
              <strong>Reset Password:</strong> Generates a secure temporary password that you can share with the user. 
              The user will be <strong>forced to change their password on their next login</strong> for security purposes.
            </p>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

<style>
.user-avatar img {
  border: 3px solid #e9ecef;
}

.badge-lg {
  font-size: 0.9rem;
  padding: 0.5rem 1rem;
}

.table-borderless td {
  border: none;
  padding: 0.5rem 0;
}

.font-weight-bold {
  font-weight: 600 !important;
}

.progress {
  background-color: #e9ecef;
}

.card-title {
  color: #495057;
}

.text-muted {
  color: #6c757d !important;
}

@media (max-width: 767.98px) {
  .user-avatar img {
    width: 80px !important;
    height: 80px !important;
  }
  
  .rounded-circle {
    width: 80px !important;
    height: 80px !important;
  }
  
  .card-body {
    padding: 1rem;
  }
  
  .btn-block {
    margin-bottom: 0.5rem;
  }
}
</style>

<script>
function banUser(userId) {
  if (confirm('Are you sure you want to ban this user? They will not be able to log in.')) {
    // Redirect to ban user endpoint
    window.location.href = '<?php echo base_url('admin/ban_user/'); ?>' + userId;
  }
}

function unbanUser(userId) {
  if (confirm('Are you sure you want to unban this user? They will be able to log in again.')) {
    // Redirect to unban user endpoint
    window.location.href = '<?php echo base_url('admin/unban_user/'); ?>' + userId;
  }
}

function resetPassword(userId) {
  if (confirm('Are you sure you want to reset this user\'s password?')) {
    // Add reset password functionality
    alert('Reset password functionality will be implemented');
  }
}

function deleteUser(userId) {
  if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
    // Redirect to delete user endpoint
    window.location.href = '<?php echo base_url('admin/delete_user/'); ?>' + userId;
  }
}

function resetPassword(userId) {
  if (confirm('Are you sure you want to reset this user\'s password? A temporary password will be generated and displayed to you.')) {
    window.location.href = '<?php echo base_url('admin/reset_password/'); ?>' + userId;
  }
}

</script>
