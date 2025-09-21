<div class="container-fluid">
  <div class="row">
    <div class="col-md-12">
      
      <!-- Form Header -->
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between align-items-center">
            <h3 class="card-title mb-0">
              <i class="fas fa-user-plus mr-2"></i>Create New User
            </h3>
            <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary btn-sm">
              <i class="fas fa-arrow-left mr-1"></i>Back to Users
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <?php echo form_open('admin/create_user', array('class' => 'needs-validation', 'novalidate' => '')); ?>
          
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
                  
                  <!-- Username -->
                  <div class="form-group">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control <?php echo form_error('username') ? 'is-invalid' : ''; ?>" 
                           id="username" 
                           name="username" 
                           value="<?php echo set_value('username'); ?>" 
                           maxlength="12"
                           required>
                    <div class="invalid-feedback">
                      <?php echo form_error('username') ? form_error('username') : 'Please provide a valid username.'; ?>
                    </div>
                    <small class="form-text text-muted">Maximum 12 characters. Must be unique.</small>
                  </div>
                  
                  <!-- Email -->
                  <div class="form-group">
                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                    <input type="email" 
                           class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" 
                           id="email" 
                           name="email" 
                           value="<?php echo set_value('email'); ?>" 
                           required>
                    <div class="invalid-feedback">
                      <?php echo form_error('email') ? form_error('email') : 'Please provide a valid email address.'; ?>
                    </div>
                  </div>
                  
                  <!-- Password -->
                  <div class="form-group">
                    <label for="passwd" class="form-label">Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <input type="password" 
                             class="form-control <?php echo form_error('passwd') ? 'is-invalid' : ''; ?>" 
                             id="passwd" 
                             name="passwd" 
                             required>
                      <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                          <i class="fas fa-eye"></i>
                        </button>
                      </div>
                      <div class="invalid-feedback">
                        <?php echo form_error('passwd') ? form_error('passwd') : 'Please provide a password.'; ?>
                      </div>
                    </div>
                    <small class="form-text text-muted">Minimum 8 characters with uppercase, lowercase, number and special character.</small>
                  </div>
                  
                  <!-- Confirm Password -->
                  <div class="form-group">
                    <label for="passwd_confirm" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" 
                           class="form-control <?php echo form_error('passwd_confirm') ? 'is-invalid' : ''; ?>" 
                           id="passwd_confirm" 
                           name="passwd_confirm" 
                           required>
                    <div class="invalid-feedback">
                      <?php echo form_error('passwd_confirm') ? form_error('passwd_confirm') : 'Passwords do not match.'; ?>
                    </div>
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
                           value="<?php echo set_value('first_name'); ?>">
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
                           value="<?php echo set_value('last_name'); ?>">
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
                           value="<?php echo set_value('phone'); ?>"
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
                           value="<?php echo set_value('date_of_birth'); ?>">
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
                      <option value="3" <?php echo set_select('auth_level', '3'); ?>>Cleaner</option>
                      <option value="6" <?php echo set_select('auth_level', '6'); ?>>Host</option>
                      <option value="9" <?php echo set_select('auth_level', '9'); ?>>Administrator</option>
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
                             <?php echo set_checkbox('email_verified', '1'); ?>>
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
                             <?php echo set_checkbox('banned', '1'); ?>>
                      <label class="form-check-label" for="banned">
                        Ban this user
                      </label>
                    </div>
                    <small class="form-text text-muted">Check this to create the user in banned status.</small>
                  </div>
                  
                  <!-- Send Welcome Email -->
                  <div class="form-group">
                    <div class="form-check">
                      <input class="form-check-input" 
                             type="checkbox" 
                             id="send_welcome_email" 
                             name="send_welcome_email" 
                             value="1" 
                             <?php echo set_checkbox('send_welcome_email', '1', true); ?>>
                      <label class="form-check-label" for="send_welcome_email">
                        Send welcome email
                      </label>
                    </div>
                    <small class="form-text text-muted">Send a welcome email with login credentials.</small>
                  </div>
                  
                </div>
              </div>
              
              <!-- Additional Information -->
              <div class="card card-secondary card-outline">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-info-circle mr-2"></i>Additional Information
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
                              placeholder="Enter full address"><?php echo set_value('address'); ?></textarea>
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
                           value="<?php echo set_value('city'); ?>">
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
                           value="<?php echo set_value('country'); ?>">
                    <div class="invalid-feedback">
                      <?php echo form_error('country'); ?>
                    </div>
                  </div>
                  
                  <!-- Notes -->
                  <div class="form-group">
                    <label for="notes" class="form-label">Admin Notes</label>
                    <textarea class="form-control <?php echo form_error('notes') ? 'is-invalid' : ''; ?>" 
                              id="notes" 
                              name="notes" 
                              rows="3" 
                              placeholder="Internal notes about this user"><?php echo set_value('notes'); ?></textarea>
                    <div class="invalid-feedback">
                      <?php echo form_error('notes'); ?>
                    </div>
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
                    <i class="fas fa-user-plus mr-2"></i>Create User
                  </button>
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

/* Password strength indicator */
.password-strength {
  height: 4px;
  margin-top: 0.5rem;
  border-radius: 2px;
  transition: all 0.3s ease;
}

.password-strength.weak {
  background-color: #dc3545;
  width: 25%;
}

.password-strength.fair {
  background-color: #ffc107;
  width: 50%;
}

.password-strength.good {
  background-color: #17a2b8;
  width: 75%;
}

.password-strength.strong {
  background-color: #28a745;
  width: 100%;
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
    // Password toggle functionality
    $('#togglePassword').click(function() {
        const passwordField = $('#passwd');
        const icon = $(this).find('i');
        
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Password confirmation validation
    $('#passwd_confirm').on('input', function() {
        const password = $('#passwd').val();
        const confirmPassword = $(this).val();
        
        if (password !== confirmPassword) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
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
});
</script>
