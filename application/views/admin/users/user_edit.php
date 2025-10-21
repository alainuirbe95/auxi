<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modern Admin Edit User View v2.0 -->
<div class="admin-edit-container">
  
  <!-- Edit Header -->
  <div class="edit-header">
    <div class="edit-header-content">
      <div class="edit-title-section">
        <h1 class="edit-title">
          <i class="fas fa-user-edit"></i>
          Edit User: <?php echo htmlspecialchars($user->username); ?>
        </h1>
        <p class="edit-subtitle">Modify user information and settings</p>
      </div>
      
      <div class="edit-actions">
        <a href="<?php echo base_url('admin/profile/' . $user->user_id); ?>" class="btn btn-secondary">
          <i class="fas fa-eye"></i> View Profile
        </a>
        <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline">
          <i class="fas fa-arrow-left"></i> Back to Users
        </a>
      </div>
    </div>
  </div>

  <!-- Edit Form -->
  <div class="edit-form-container">
    <?php echo form_open('admin/update_user/' . $user->user_id, array('class' => 'modern-form', 'novalidate' => '')); ?>
    
    <div class="form-grid">
      
      <!-- Left Column -->
      <div class="form-column">
        
        <!-- Basic Information Card -->
        <div class="form-card">
          <div class="card-header">
            <h3><i class="fas fa-user"></i> Basic Information</h3>
          </div>
          <div class="card-content">
            
            <div class="form-group">
              <label for="username" class="form-label">
                <i class="fas fa-at"></i> Username
              </label>
              <input type="text" 
                     class="form-input readonly" 
                     id="username" 
                     value="<?php echo htmlspecialchars($user->username); ?>" 
                     readonly>
              <small class="form-help">Username cannot be changed for security reasons</small>
            </div>
            
            <div class="form-group">
              <label for="email" class="form-label required">
                <i class="fas fa-envelope"></i> Email Address
              </label>
              <input type="email" 
                     class="form-input <?php echo form_error('email') ? 'error' : ''; ?>" 
                     id="email" 
                     name="email" 
                     value="<?php echo set_value('email', $user->email); ?>" 
                     required>
              <?php if (form_error('email')): ?>
                <div class="form-error"><?php echo form_error('email'); ?></div>
              <?php endif; ?>
            </div>
            
            <div class="form-group">
              <label for="user_id" class="form-label">
                <i class="fas fa-id-card"></i> User ID
              </label>
              <input type="text" 
                     class="form-input readonly" 
                     id="user_id" 
                     value="<?php echo htmlspecialchars($user->user_id); ?>" 
                     readonly>
              <small class="form-help">User ID cannot be changed</small>
            </div>
            
          </div>
        </div>

        <!-- Personal Information Card -->
        <div class="form-card">
          <div class="card-header">
            <h3><i class="fas fa-id-card"></i> Personal Information</h3>
          </div>
          <div class="card-content">
            
            <div class="form-row">
              <div class="form-group">
                <label for="first_name" class="form-label">
                  <i class="fas fa-user"></i> First Name
                </label>
                <input type="text" 
                       class="form-input <?php echo form_error('first_name') ? 'error' : ''; ?>" 
                       id="first_name" 
                       name="first_name" 
                       value="<?php echo set_value('first_name', $user->first_name); ?>">
                <?php if (form_error('first_name')): ?>
                  <div class="form-error"><?php echo form_error('first_name'); ?></div>
                <?php endif; ?>
              </div>
              
              <div class="form-group">
                <label for="last_name" class="form-label">
                  <i class="fas fa-user"></i> Last Name
                </label>
                <input type="text" 
                       class="form-input <?php echo form_error('last_name') ? 'error' : ''; ?>" 
                       id="last_name" 
                       name="last_name" 
                       value="<?php echo set_value('last_name', $user->last_name); ?>">
                <?php if (form_error('last_name')): ?>
                  <div class="form-error"><?php echo form_error('last_name'); ?></div>
                <?php endif; ?>
              </div>
            </div>
            
            <div class="form-group">
              <label for="phone" class="form-label">
                <i class="fas fa-phone"></i> Phone Number
              </label>
              <input type="tel" 
                     class="form-input <?php echo form_error('phone') ? 'error' : ''; ?>" 
                     id="phone" 
                     name="phone" 
                     value="<?php echo set_value('phone', $user->phone); ?>"
                     placeholder="+1 (555) 123-4567">
              <?php if (form_error('phone')): ?>
                <div class="form-error"><?php echo form_error('phone'); ?></div>
              <?php endif; ?>
            </div>
            
            <div class="form-group">
              <label for="date_of_birth" class="form-label">
                <i class="fas fa-calendar"></i> Date of Birth
              </label>
              <input type="date" 
                     class="form-input <?php echo form_error('date_of_birth') ? 'error' : ''; ?>" 
                     id="date_of_birth" 
                     name="date_of_birth" 
                     value="<?php echo set_value('date_of_birth', $user->date_of_birth); ?>">
              <?php if (form_error('date_of_birth')): ?>
                <div class="form-error"><?php echo form_error('date_of_birth'); ?></div>
              <?php endif; ?>
            </div>
            
          </div>
        </div>

        <!-- Location Information Card -->
        <div class="form-card">
          <div class="card-header">
            <h3><i class="fas fa-map-marker-alt"></i> Location Information</h3>
          </div>
          <div class="card-content">
            
            <div class="form-group">
              <label for="address" class="form-label">
                <i class="fas fa-home"></i> Address
              </label>
              <textarea class="form-textarea <?php echo form_error('address') ? 'error' : ''; ?>" 
                        id="address" 
                        name="address" 
                        rows="3" 
                        placeholder="Enter full address"><?php echo set_value('address', $user->address); ?></textarea>
              <?php if (form_error('address')): ?>
                <div class="form-error"><?php echo form_error('address'); ?></div>
              <?php endif; ?>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="city" class="form-label">
                  <i class="fas fa-city"></i> City
                </label>
                <input type="text" 
                       class="form-input <?php echo form_error('city') ? 'error' : ''; ?>" 
                       id="city" 
                       name="city" 
                       value="<?php echo set_value('city', $user->city); ?>">
                <?php if (form_error('city')): ?>
                  <div class="form-error"><?php echo form_error('city'); ?></div>
                <?php endif; ?>
              </div>
              
              <div class="form-group">
                <label for="country" class="form-label">
                  <i class="fas fa-globe"></i> Country
                </label>
                <input type="text" 
                       class="form-input <?php echo form_error('country') ? 'error' : ''; ?>" 
                       id="country" 
                       name="country" 
                       value="<?php echo set_value('country', $user->country); ?>">
                <?php if (form_error('country')): ?>
                  <div class="form-error"><?php echo form_error('country'); ?></div>
                <?php endif; ?>
              </div>
            </div>
            
          </div>
        </div>

      </div>

      <!-- Right Column -->
      <div class="form-column">
        
        <!-- Account Settings Card -->
        <div class="form-card">
          <div class="card-header">
            <h3><i class="fas fa-cogs"></i> Account Settings</h3>
          </div>
          <div class="card-content">
            
            <div class="form-group">
              <label for="auth_level" class="form-label required">
                <i class="fas fa-user-shield"></i> User Level
              </label>
              <select class="form-select <?php echo form_error('auth_level') ? 'error' : ''; ?>" 
                      id="auth_level" 
                      name="auth_level" 
                      required>
                <option value="">Select User Level</option>
                <option value="3" <?php echo set_select('auth_level', '3', $user->auth_level == '3'); ?>>
                  <i class="fas fa-broom"></i> Cleaner
                </option>
                <option value="6" <?php echo set_select('auth_level', '6', $user->auth_level == '6'); ?>>
                  <i class="fas fa-home"></i> Host
                </option>
                <option value="9" <?php echo set_select('auth_level', '9', $user->auth_level == '9'); ?>>
                  <i class="fas fa-crown"></i> Administrator
                </option>
              </select>
              <?php if (form_error('auth_level')): ?>
                <div class="form-error"><?php echo form_error('auth_level'); ?></div>
              <?php endif; ?>
              <div class="form-help">
                <strong>Cleaner:</strong> Service provider access<br>
                <strong>Host:</strong> Property owner access<br>
                <strong>Administrator:</strong> Full system access
              </div>
            </div>
            
            <div class="form-group">
              <label class="form-label">
                <i class="fas fa-shield-alt"></i> Security Settings
              </label>
              
              <div class="checkbox-group">
                <label class="checkbox-item">
                  <input type="checkbox" 
                         id="email_verified" 
                         name="email_verified" 
                         value="1" 
                         <?php echo set_checkbox('email_verified', '1', ($user->email_verified ?? '0') == '1'); ?>>
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-label">
                    <i class="fas fa-check-circle"></i>
                    Mark email as verified
                  </span>
                </label>
                
                <label class="checkbox-item">
                  <input type="checkbox" 
                         id="banned" 
                         name="banned" 
                         value="1" 
                         <?php echo set_checkbox('banned', '1', ($user->banned ?? '0') == '1'); ?>>
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-label">
                    <i class="fas fa-ban"></i>
                    Ban this user
                  </span>
                </label>
                
                <label class="checkbox-item">
                  <input type="checkbox" 
                         id="locked" 
                         name="locked" 
                         value="1" 
                         <?php echo set_checkbox('locked', '1', ($user->locked ?? '0') == '1'); ?>>
                  <span class="checkbox-custom"></span>
                  <span class="checkbox-label">
                    <i class="fas fa-lock"></i>
                    Lock this account
                  </span>
                </label>
              </div>
              
              <div class="form-help">
                <strong>Email Verified:</strong> User can access all features<br>
                <strong>Banned:</strong> User cannot log in<br>
                <strong>Locked:</strong> Temporary account lock
              </div>
            </div>
            
          </div>
        </div>

        <!-- Admin Notes Card -->
        <div class="form-card">
          <div class="card-header">
            <h3><i class="fas fa-sticky-note"></i> Admin Notes</h3>
          </div>
          <div class="card-content">
            
            <div class="form-group">
              <label for="notes" class="form-label">
                <i class="fas fa-edit"></i> Internal Notes
              </label>
              <textarea class="form-textarea <?php echo form_error('notes') ? 'error' : ''; ?>" 
                        id="notes" 
                        name="notes" 
                        rows="6" 
                        placeholder="Internal notes about this user (not visible to the user)"><?php echo set_value('notes', $user->notes); ?></textarea>
              <?php if (form_error('notes')): ?>
                <div class="form-error"><?php echo form_error('notes'); ?></div>
              <?php endif; ?>
              <small class="form-help">These notes are only visible to administrators</small>
            </div>
            
          </div>
        </div>

        <!-- Form Actions Card -->
        <div class="form-card actions-card">
          <div class="card-content">
            <div class="form-actions">
              <button type="submit" class="btn btn-primary btn-large">
                <i class="fas fa-save"></i>
                <span>Update User</span>
              </button>
              
              <div class="secondary-actions">
                <a href="<?php echo base_url('admin/profile/' . $user->user_id); ?>" class="btn btn-secondary">
                  <i class="fas fa-eye"></i> View Profile
                </a>
                <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-outline">
                  <i class="fas fa-times"></i> Cancel
                </a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
    
    <?php echo form_close(); ?>
  </div>

</div>

<style>
/* Modern Admin Edit User Styles */
.admin-edit-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1rem;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Edit Header */
.edit-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 15px;
  padding: 2rem;
  margin-bottom: 2rem;
  color: white;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.edit-header-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.edit-title-section {
  flex: 1;
}

.edit-title {
  font-size: 2.5rem;
  font-weight: 700;
  margin: 0 0 0.5rem 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  display: flex;
  align-items: center;
  gap: 1rem;
}

.edit-title i {
  font-size: 2rem;
}

.edit-subtitle {
  font-size: 1.1rem;
  margin: 0;
  opacity: 0.9;
}

.edit-actions {
  display: flex;
  gap: 1rem;
  flex-shrink: 0;
}

/* Form Grid */
.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 2rem;
}

/* Form Cards */
.form-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  transition: all 0.3s ease;
  margin-bottom: 2rem;
}

.form-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
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

/* Form Elements */
.form-group {
  margin-bottom: 1.5rem;
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.form-label {
  display: block;
  font-weight: 600;
  color: #495057;
  margin-bottom: 0.5rem;
  font-size: 0.95rem;
}

.form-label.required::after {
  content: ' *';
  color: #dc3545;
}

.form-label i {
  color: #667eea;
  margin-right: 0.5rem;
  width: 16px;
  text-align: center;
}

.form-input, .form-select, .form-textarea {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  font-size: 1rem;
  transition: all 0.3s ease;
  background: white;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
  outline: none;
  border-color: #667eea;
  box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-input.readonly {
  background: #f8f9fa;
  color: #6c757d;
  cursor: not-allowed;
}

.form-input.error, .form-select.error, .form-textarea.error {
  border-color: #dc3545;
  box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.form-textarea {
  resize: vertical;
  min-height: 100px;
}

.form-help {
  font-size: 0.85rem;
  color: #6c757d;
  margin-top: 0.5rem;
  line-height: 1.4;
}

.form-error {
  color: #dc3545;
  font-size: 0.85rem;
  margin-top: 0.5rem;
  font-weight: 500;
}

/* Checkbox Styling */
.checkbox-group {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin: 1rem 0;
}

.checkbox-item {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 0.75rem;
  border-radius: 8px;
  transition: all 0.3s ease;
  border: 2px solid transparent;
}

.checkbox-item:hover {
  background: rgba(102, 126, 234, 0.05);
  border-color: rgba(102, 126, 234, 0.2);
}

.checkbox-item input[type="checkbox"] {
  display: none;
}

.checkbox-custom {
  width: 20px;
  height: 20px;
  border: 2px solid #dee2e6;
  border-radius: 4px;
  margin-right: 0.75rem;
  position: relative;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.checkbox-item input[type="checkbox"]:checked + .checkbox-custom {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
}

.checkbox-item input[type="checkbox"]:checked + .checkbox-custom::after {
  content: '✓';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 12px;
  font-weight: bold;
}

.checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-weight: 500;
  color: #495057;
}

.checkbox-label i {
  color: #667eea;
  font-size: 0.9rem;
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
}

.btn-secondary:hover {
  transform: translateY(-2px);
  color: white;
  text-decoration: none;
}

.btn-outline {
  background: transparent;
  color: white;
  border: 2px solid rgba(255, 255, 255, 0.3);
}

.btn-outline:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  text-decoration: none;
}

.btn-large {
  padding: 1rem 2rem;
  font-size: 1.1rem;
  font-weight: 700;
}

/* Form Actions */
.actions-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.form-actions {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  align-items: center;
}

.secondary-actions {
  display: flex;
  gap: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .edit-header-content {
    flex-direction: column;
    text-align: center;
    gap: 1.5rem;
  }
  
  .edit-title {
    font-size: 2rem;
  }
  
  .form-grid {
    grid-template-columns: 1fr;
    gap: 1.5rem;
  }
  
  .form-row {
    grid-template-columns: 1fr;
  }
  
  .card-content {
    padding: 1.5rem;
  }
  
  .secondary-actions {
    flex-direction: column;
    width: 100%;
  }
  
  .secondary-actions .btn {
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .admin-edit-container {
    padding: 0.5rem;
  }
  
  .edit-header {
    padding: 1.5rem;
  }
  
  .edit-title {
    font-size: 1.75rem;
  }
  
  .card-content {
    padding: 1rem;
  }
  
  .form-actions {
    gap: 1rem;
  }
}
</style>

<script>
$(document).ready(function() {
    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('modern-form');
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
    
    // Enhanced form interactions
    $('.form-input, .form-select, .form-textarea').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
    
    // Smooth animations for form cards
    $('.form-card').each(function(index) {
        $(this).css('animation-delay', (index * 0.1) + 's');
    });
});
</script>