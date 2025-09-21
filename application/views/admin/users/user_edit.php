<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      
      <!-- Form Header -->
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
              <i class="fas fa-user-edit mr-2"></i>Edit User: <?php echo htmlspecialchars($user->username); ?>
            </h3>
            <div>
              <a href="<?php echo base_url('admin/view_user/' . $user->user_id); ?>" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-eye mr-1"></i>View User
              </a>
              <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Users
              </a>
            </div>
          </div>
        </div>
        
        <div class="card-body">
          <?php echo form_open('admin/update_user/' . $user->user_id, array('class' => 'needs-validation', 'novalidate' => '')); ?>
          
          <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
              
              <!-- Basic Information -->
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-user mr-2"></i>Basic Information
                  </h3>
                </div>
                <div class="card-body">
                  
                  <!-- Username (Read-only) -->
                  <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           value="<?php echo htmlspecialchars($user->username); ?>" 
                           readonly>
                    <small class="form-text text-muted">Username cannot be changed for security reasons.</small>
                  </div>
                  
                  <!-- Email -->
                  <div class="form-group">
                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" 
                           class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                           id="email" 
                           name="email" 
                           value="<?php echo set_value('email', $user->email); ?>" 
                           required>
                    <div class="invalid-feedback">
                      <?php echo form_error('email') ? form_error('email') : 'Please provide a valid email address.'; ?>
                    </div>
                  </div>
                  
                  <!-- User ID (Read-only) -->
                  <div class="form-group">
                    <label for="user_id" class="form-label">User ID</label>
                    <input type="text" 
                           class="form-control" 
                           id="user_id" 
                           value="<?php echo htmlspecialchars($user->user_id); ?>" 
                           readonly>
                    <small class="form-text text-muted">User ID cannot be changed.</small>
                  </div>
                  
                </div>
              </div>
              
              <!-- Personal Information -->
              <div class="card card-info card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-id-card mr-2"></i>Personal Information
                  </h3>
                </div>
                <div class="card-body">
                  
                  <!-- First Name -->
                  <div class="form-group">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" 
                           class="form-control <?php echo form_error('first_name') ? 'is-invalid' : ''; ?>" 
                           id="first_name" 
                           name="first_name" 
                           value="<?php echo set_value('first_name', $user->first_name); ?>">
                    <div class="invalid-feedback">
                      <?php echo form_error('first_name'); ?>
                    </div>
                  </div>
                  
                  <!-- Last Name -->
                  <div class="form-group">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" 
                           class="form-control <?php echo form_error('last_name') ? 'is-invalid' : ''; ?>" 
                           id="last_name" 
                           name="last_name" 
                           value="<?php echo set_value('last_name', $user->last_name); ?>">
                    <div class="invalid-feedback">
                      <?php echo form_error('last_name'); ?>
                    </div>
                  </div>
                  
                  <!-- Phone -->
                  <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" 
                           class="form-control <?php echo form_error('phone') ? 'is-invalid' : ''; ?>" 
                           id="phone" 
                           name="phone" 
                           value="<?php echo set_value('phone', $user->phone); ?>"
                           placeholder="+1 (555) 123-4567">
                    <div class="invalid-feedback">
                      <?php echo form_error('phone'); ?>
                    </div>
                  </div>
                  
                  <!-- Date of Birth -->
                  <div class="form-group">
                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                    <input type="date" 
                           class="form-control <?php echo form_error('date_of_birth') ? 'is-invalid' : ''; ?>" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           value="<?php echo set_value('date_of_birth', $user->date_of_birth); ?>">
                    <div class="invalid-feedback">
                      <?php echo form_error('date_of_birth'); ?>
                    </div>
                  </div>
                  
                </div>
              </div>
              
            </div>
            
            <!-- Right Column -->
            <div class="col-md-6">
              
              <!-- Account Settings -->
              <div class="card card-warning card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-cogs mr-2"></i>Account Settings
                  </h3>
                </div>
                <div class="card-body">
                  
                  <!-- User Level -->
                  <div class="form-group">
                    <label for="auth_level" class="form-label">User Level <span class="text-danger">*</span></label>
                    <select class="form-control <?php echo form_error('auth_level') ? 'is-invalid' : ''; ?>" 
                            id="auth_level" 
                            name="auth_level" 
                            required>
                      <option value="">Select User Level</option>
                      <option value="3" <?php echo set_select('auth_level', '3', $user->auth_level == '3'); ?>>Cleaner</option>
                      <option value="6" <?php echo set_select('auth_level', '6', $user->auth_level == '6'); ?>>Host</option>
                      <option value="9" <?php echo set_select('auth_level', '9', $user->auth_level == '9'); ?>>Administrator</option>
                    </select>
                    <div class="invalid-feedback">
                      <?php echo form_error('auth_level') ? form_error('auth_level') : 'Please select a user level.'; ?>
                    </div>
                    <small class="form-text text-muted">
                      <strong>Cleaner:</strong> Service provider access<br>
                      <strong>Host:</strong> Property owner access<br>
                      <strong>Administrator:</strong> Full system access
                    </small>
                  </div>
                  
                  <!-- Email Verification -->
                  <div class="form-group">
                    <div class="form-check">
                      <input class="form-check-input" 
                             type="checkbox" 
                             id="email_verified" 
                             name="email_verified" 
                             value="1" 
                             <?php echo set_checkbox('email_verified', '1', ($user->email_verified ?? '0') == '1'); ?>>
                      <label class="form-check-label" for="email_verified">
                        Mark email as verified
                      </label>
                    </div>
                    <small class="form-text text-muted">Check this if the user's email has been verified.</small>
                  </div>
                  
                  <!-- Account Status -->
                  <div class="form-group">
                    <div class="form-check">
                      <input class="form-check-input" 
                             type="checkbox" 
                             id="banned" 
                             name="banned" 
                             value="1" 
                             <?php echo set_checkbox('banned', '1', ($user->banned ?? '0') == '1'); ?>>
                      <label class="form-check-label" for="banned">
                        Ban this user
                      </label>
                    </div>
                    <small class="form-text text-muted">Check this to ban the user from logging in.</small>
                  </div>
                  
                  <!-- Account Lock -->
                  <div class="form-group">
                    <div class="form-check">
                      <input class="form-check-input" 
                             type="checkbox" 
                             id="locked" 
                             name="locked" 
                             value="1" 
                             <?php echo set_checkbox('locked', '1', ($user->locked ?? '0') == '1'); ?>>
                      <label class="form-check-label" for="locked">
                        Lock this account
                      </label>
                    </div>
                    <small class="form-text text-muted">Check this to temporarily lock the user's account.</small>
                  </div>
                  
                </div>
              </div>
              
              <!-- Location Information -->
              <div class="card card-secondary card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-map-marker-alt mr-2"></i>Location Information
                  </h3>
                </div>
                <div class="card-body">
                  
                  <!-- Address -->
                  <div class="form-group">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control <?php echo form_error('address') ? 'is-invalid' : ''; ?>" 
                              id="address" 
                              name="address" 
                              rows="3" 
                              placeholder="Enter full address"><?php echo set_value('address', $user->address); ?></textarea>
                    <div class="invalid-feedback">
                      <?php echo form_error('address'); ?>
                    </div>
                  </div>
                  
                  <!-- City -->
                  <div class="form-group">
                    <label for="city" class="form-label">City</label>
                    <input type="text" 
                           class="form-control <?php echo form_error('city') ? 'is-invalid' : ''; ?>" 
                           id="city" 
                           name="city" 
                           value="<?php echo set_value('city', $user->city); ?>">
                    <div class="invalid-feedback">
                      <?php echo form_error('city'); ?>
                    </div>
                  </div>
                  
                  <!-- Country -->
                  <div class="form-group">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" 
                           class="form-control <?php echo form_error('country') ? 'is-invalid' : ''; ?>" 
                           id="country" 
                           name="country" 
                           value="<?php echo set_value('country', $user->country); ?>">
                    <div class="invalid-feedback">
                      <?php echo form_error('country'); ?>
                    </div>
                  </div>
                  
                </div>
              </div>
              
              <!-- Admin Notes -->
              <div class="card card-dark card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-sticky-note mr-2"></i>Admin Notes
                  </h3>
                </div>
                <div class="card-body">
                  
                  <!-- Notes -->
                  <div class="form-group">
                    <label for="notes" class="form-label">Internal Notes</label>
                    <textarea class="form-control <?php echo form_error('notes') ? 'is-invalid' : ''; ?>" 
                              id="notes" 
                              name="notes" 
                              rows="4" 
                              placeholder="Internal notes about this user (not visible to the user)"><?php echo set_value('notes', $user->notes); ?></textarea>
                    <div class="invalid-feedback">
                      <?php echo form_error('notes'); ?>
                    </div>
                    <small class="form-text text-muted">These notes are only visible to administrators.</small>
                  </div>
                  
                </div>
              </div>
              
            </div>
          </div>
          
          <!-- Form Actions -->
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-body text-center">
                  <button type="submit" class="btn btn-success btn-lg mr-3">
                    <i class="fas fa-save mr-2"></i>Update User
                  </button>
                  <a href="<?php echo base_url('admin/view_user/' . $user->user_id); ?>" class="btn btn-info btn-lg mr-3">
                    <i class="fas fa-eye mr-2"></i>View User
                  </a>
                  <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary btn-lg">
                    <i class="fas fa-times mr-2"></i>Cancel
                  </a>
                </div>
              </div>
            </div>
          </div>
          
          <?php echo form_close(); ?>
          
        </div>
      </div>
      
    </div>
  </div>
</div>

<style>
.card-outline {
  border-top: 3px solid;
}

.card-outline.card-primary {
  border-top-color: #007bff;
}

.card-outline.card-info {
  border-top-color: #17a2b8;
}

.card-outline.card-warning {
  border-top-color: #ffc107;
}

.card-outline.card-secondary {
  border-top-color: #6c757d;
}

.card-outline.card-dark {
  border-top-color: #343a40;
}

.form-label {
  font-weight: 600;
  color: #495057;
}

.text-danger {
  color: #dc3545 !important;
}

.form-text {
  font-size: 0.875rem;
}

.btn-lg {
  padding: 0.75rem 2rem;
  font-size: 1.1rem;
}

/* Read-only field styling */
.form-control[readonly] {
  background-color: #f8f9fa;
  opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 767.98px) {
  .card-body {
    padding: 1rem;
  }
  
  .btn-lg {
    padding: 0.5rem 1.5rem;
    font-size: 1rem;
  }
}
</style>

<script>
$(document).ready(function() {
    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    
    // Warn about changing user level
    $('#auth_level').change(function() {
        var currentLevel = '<?php echo $user->auth_level; ?>';
        var newLevel = $(this).val();
        
        if (currentLevel !== newLevel) {
            var levelNames = {
                '3': 'Cleaner',
                '6': 'Host', 
                '9': 'Administrator'
            };
            
            var currentLevelName = levelNames[currentLevel] || 'Unknown';
            var newLevelName = levelNames[newLevel] || 'Unknown';
            
            if (confirm('Are you sure you want to change the user level from "' + currentLevelName + '" to "' + newLevelName + '"? This will affect the user\'s permissions.')) {
                return true;
            } else {
                $(this).val(currentLevel);
                return false;
            }
        }
    });
    
    // Warn about banning/unbanning
    $('#banned').change(function() {
        var isChecked = $(this).is(':checked');
        var action = isChecked ? 'ban' : 'unban';
        
        if (!confirm('Are you sure you want to ' + action + ' this user?')) {
            $(this).prop('checked', !isChecked);
        }
    });
});
</script>
