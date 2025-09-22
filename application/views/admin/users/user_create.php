<style>
/* Modern Create User Page Styles */
.modern-create-user {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  padding: 2rem 0;
}

.create-user-container {
  max-width: 1200px;
  margin: 0 auto;
}

.modern-card {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(20px);
  border-radius: 20px;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
  overflow: hidden;
  position: relative;
}

.modern-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #667eea, #764ba2, #f093fb);
}

.modern-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 2rem;
  border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.modern-card-title {
  font-size: 2rem;
  font-weight: 700;
  color: #2c3e50;
  margin: 0;
  display: flex;
  align-items: center;
}

.modern-card-title i {
  background: linear-gradient(135deg, #667eea, #764ba2);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
  margin-right: 1rem;
}

.modern-card-body {
  padding: 2rem;
}

.section-card {
  background: rgba(255, 255, 255, 0.8);
  border-radius: 15px;
  border: 1px solid rgba(0, 0, 0, 0.05);
  margin-bottom: 2rem;
  overflow: hidden;
  transition: all 0.3s ease;
}

.section-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.section-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 1.5rem;
  margin: 0;
}

.section-title {
  font-size: 1.3rem;
  font-weight: 600;
  margin: 0;
  display: flex;
  align-items: center;
}

.section-title i {
  margin-right: 0.75rem;
  font-size: 1.1rem;
}

.section-body {
  padding: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

.form-label {
  font-weight: 600;
  color: #2c3e50;
  margin-bottom: 0.5rem;
  display: flex;
  align-items: center;
}

.form-control {
  border: 2px solid #e9ecef;
  border-radius: 10px;
  padding: 0.75rem 1rem;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: rgba(255, 255, 255, 0.9);
}

.form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  background: white;
}

.form-control.is-invalid {
  border-color: #dc3545;
  box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.invalid-feedback {
  color: #dc3545;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.form-text {
  color: #6c757d;
  font-size: 0.875rem;
  margin-top: 0.25rem;
}

.text-danger {
  color: #dc3545 !important;
}

.text-muted {
  color: #6c757d !important;
}

/* User Level Dropdown Styling */
#auth_level {
  background-color: #fff !important;
  border: 2px solid #e9ecef !important;
  border-radius: 8px !important;
  font-size: 1rem !important;
  line-height: 1.5 !important;
  padding: 0.5rem 0.75rem !important;
  height: auto !important;
  min-height: 45px !important;
  transition: all 0.3s ease !important;
}

#auth_level:focus {
  border-color: #667eea !important;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
  outline: none !important;
}

#auth_level option {
  background-color: #fff !important;
  color: #333 !important;
  padding: 0.5rem !important;
  font-size: 1rem !important;
  line-height: 1.5 !important;
}

#auth_level option:hover {
  background-color: #f8f9fa !important;
  color: #333 !important;
}

#auth_level option:checked {
  background-color: #667eea !important;
  color: #fff !important;
}


/* Modern Buttons */
.btn-modern {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  color: white;
  padding: 0.75rem 2rem;
  border-radius: 25px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
  color: white;
}

.btn-secondary-modern {
  background: rgba(108, 117, 125, 0.1);
  border: 2px solid #6c757d;
  color: #6c757d;
  padding: 0.75rem 2rem;
  border-radius: 25px;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}

.btn-secondary-modern:hover {
  background: #6c757d;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(108, 117, 125, 0.3);
}

/* Form Check Styling */
.form-check {
  padding-left: 2rem;
}

.form-check-input {
  width: 1.25rem;
  height: 1.25rem;
  border: 2px solid #e9ecef;
  border-radius: 6px;
  background: white;
  transition: all 0.3s ease;
}

.form-check-input:checked {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
}

.form-check-label {
  font-weight: 500;
  color: #2c3e50;
  cursor: pointer;
}

/* Responsive Design */
@media (max-width: 768px) {
  .modern-create-user {
    padding: 1rem 0;
  }
  
  .modern-card-header,
  .modern-card-body,
  .section-body {
    padding: 1.5rem;
  }
  
  .modern-card-title {
    font-size: 1.5rem;
  }
  
  .section-title {
    font-size: 1.1rem;
  }
}

/* Animation for form elements */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.section-card {
  animation: fadeInUp 0.6s ease forwards;
}

.section-card:nth-child(1) { animation-delay: 0.1s; }
.section-card:nth-child(2) { animation-delay: 0.2s; }
.section-card:nth-child(3) { animation-delay: 0.3s; }
.section-card:nth-child(4) { animation-delay: 0.4s; }
</style>

<div class="modern-create-user">
  <div class="create-user-container">
    <div class="row">
      <div class="col-md-12">
        
        <!-- Modern Form Header -->
        <div class="modern-card">
          <div class="modern-card-header">
            <div class="d-flex justify-content-between align-items-center">
              <h3 class="modern-card-title">
                <i class="fas fa-user-plus"></i>Create New User
              </h3>
              <a href="<?php echo base_url('admin/users'); ?>" class="btn-secondary-modern">
                <i class="fas fa-arrow-left mr-1"></i>Back to Users
              </a>
            </div>
          </div>
        
          <div class="modern-card-body">
            <?php echo form_open('admin/create_user', array('class' => 'needs-validation', 'novalidate' => '')); ?>
            
            <div class="row">
              <!-- Left Column -->
              <div class="col-md-6">
                
                <!-- Basic Information -->
                <div class="section-card">
                  <div class="section-header">
                    <h3 class="section-title">
                      <i class="fas fa-user"></i>Basic Information
                    </h3>
                  </div>
                  <div class="section-body">
                  
                  <!-- Username -->
                  <div class="form-group">
                    <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                    <input type="text" 
                           class="form-control <?php echo form_error('username') ? 'is-invalid' : ''; ?>" 
                           id="username" 
                           name="username" 
                           value="<?php echo set_value('username'); ?>" 
                           maxlength="50"
                           required>
                    <div class="invalid-feedback">
                      <?php echo form_error('username') ? form_error('username') : 'Please provide a valid username.'; ?>
                    </div>
                    <small class="form-text text-muted">Maximum 50 characters. Must be unique.</small>
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
              
            </div>
            
            <!-- Right Column -->
            <div class="col-md-6">
              
              <!-- Account Settings -->
              <div class="section-card">
                <div class="section-header">
                  <h3 class="section-title">
                    <i class="fas fa-cogs"></i>Account Settings
                  </h3>
                </div>
                <div class="section-body">
                  
                  <!-- User Level -->
                  <div class="form-group">
                    <label for="auth_level" class="form-label">User Level <span class="text-danger">*</span></label>
                    <select class="form-control custom-select <?php echo form_error('auth_level') ? 'is-invalid' : ''; ?>" 
                            id="auth_level" 
                            name="auth_level" 
                            required
                            style="height: auto; min-height: 45px; padding: 0.5rem 0.75rem;">
                      <option value="">Select User Level</option>
                      <option value="3" <?php echo set_select('auth_level', '3'); ?>>Cleaner</option>
                      <option value="6" <?php echo set_select('auth_level', '6'); ?>>Host</option>
                      <option value="9" <?php echo set_select('auth_level', '9'); ?>>Administrator</option>
                    </select>
                    <div class="invalid-feedback">
                      <?php echo form_error('auth_level') ? form_error('auth_level') : 'Please select a user level.'; ?>
                    </div>
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
              
              
            </div>
          </div>
          
          <!-- Personal & Additional Information -->
          <div class="section-card">
            <div class="section-header">
              <h3 class="section-title">
                <i class="fas fa-id-card"></i>Personal & Additional Information
              </h3>
            </div>
            <div class="section-body">
              
              <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                  <!-- First Name -->
                  <div class="form-group">
                    <label for="first_name" class="form-label">First Name <span class="text-muted">(Optional)</span></label>
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
                    <label for="last_name" class="form-label">Last Name <span class="text-muted">(Optional)</span></label>
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
                    <label for="phone" class="form-label">Phone Number <span class="text-muted">(Optional)</span></label>
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
                    <label for="date_of_birth" class="form-label">Date of Birth <span class="text-muted">(Optional)</span></label>
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
                
                <!-- Right Column -->
                <div class="col-md-6">
                  <!-- Address -->
                  <div class="form-group">
                    <label for="address" class="form-label">Address <span class="text-muted">(Optional)</span></label>
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
                    <label for="city" class="form-label">City <span class="text-muted">(Optional)</span></label>
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
                    <label for="country" class="form-label">Country <span class="text-muted">(Optional)</span></label>
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
                    <label for="notes" class="form-label">Admin Notes <span class="text-muted">(Optional)</span></label>
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
              <div class="section-card">
                <div class="section-body text-center">
                  <button type="submit" class="btn-modern mr-3">
                    <i class="fas fa-user-plus mr-2"></i>Create User
                  </button>
                  <a href="<?php echo base_url('admin/users'); ?>" class="btn-secondary-modern">
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
    
    // Enhanced form functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Add click animation to buttons
        const buttons = document.querySelectorAll('.btn-modern, .btn-secondary-modern');
        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Add ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.pointerEvents = 'none';
                
                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        
    });
    
    // Add ripple animation CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Form validation
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
</script>

