<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modern Cleaner Edit Profile View v2.0 -->
<div class="cleaner-edit-container">
  
  <!-- Edit Header -->
  <div class="edit-header">
    <div class="edit-header-content">
      <div class="edit-title-section">
        <h1 class="edit-title">
          <i class="fas fa-user-edit"></i>
          Edit My Profile
        </h1>
        <p class="edit-subtitle">Update your profile information and cleaning services</p>
      </div>
      
      <div class="edit-actions">
        <a href="<?php echo base_url('cleaner/my-profile'); ?>" class="btn btn-secondary">
          <i class="fas fa-eye"></i> View Profile
        </a>
      </div>
    </div>
  </div>

  <!-- Cleaning Supplies Reminder Banner -->
  <div class="reminder-banner">
    <div class="reminder-content">
      <div class="reminder-icon">
        <i class="fas fa-tools"></i>
      </div>
      <div class="reminder-text">
        <h4>Important Reminder</h4>
        <p><strong>All cleaners must bring their own cleaning supplies and equipment to jobs.</strong></p>
        <p>This includes: detergents, disinfectants, vacuum cleaner, mop, cleaning cloths, gloves, and any specialized equipment needed for the cleaning services you offer.</p>
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
        <h4>Complete Your Profile!</h4>
        <p>Your profile is <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong> complete. You need at least <strong>50%</strong> completion to make offers on jobs. Complete the missing fields below.</p>
      </div>
    </div>
  </div>
  <?php else: ?>
  <div class="alert-card success">
    <div class="alert-content">
      <div class="alert-icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="alert-text">
        <h4>Profile Complete!</h4>
        <p>Your profile is <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong> complete. You can make offers on jobs and grow your cleaning business.</p>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Edit Form -->
  <div class="edit-form-container">
    <form id="profileEditForm" method="POST" class="modern-form">
      
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
                       value="<?php echo htmlspecialchars($profile->username); ?>" 
                       disabled>
                <small class="form-help">Username cannot be changed</small>
              </div>
              
              <div class="form-group">
                <label for="email" class="form-label">
                  <i class="fas fa-envelope"></i> Email
                </label>
                <input type="email" 
                       class="form-input readonly" 
                       id="email" 
                       value="<?php echo htmlspecialchars($profile->email); ?>" 
                       disabled>
                <small class="form-help">Email cannot be changed</small>
              </div>
              
              <div class="form-group">
                <label for="full_name" class="form-label">
                  <i class="fas fa-id-card"></i> Full Name
                </label>
                <input type="text" 
                       class="form-input readonly" 
                       id="full_name" 
                       value="<?php echo htmlspecialchars(trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''))); ?>" 
                       disabled>
                <small class="form-help">Contact admin to change your name</small>
              </div>
              
              <div class="form-group">
                <label for="role" class="form-label">
                  <i class="fas fa-user-tag"></i> Role
                </label>
                <input type="text" 
                       class="form-input readonly" 
                       id="role" 
                       value="Cleaner" 
                       disabled>
              </div>
              
            </div>
          </div>

          <!-- Contact Information Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-phone"></i> Contact Information</h3>
            </div>
            <div class="card-content">
              
              <div class="form-group">
                <label for="phone" class="form-label required">
                  <i class="fas fa-phone"></i> Phone Number
                </label>
                <input type="tel" 
                       class="form-input" 
                       id="phone" 
                       name="phone" 
                       value="<?php echo htmlspecialchars($profile->phone ?? ''); ?>" 
                       placeholder="e.g., +52 55 1234 5678">
                <small class="form-help">
                  <i class="fas fa-info-circle"></i> Required for 50% profile completion (10 points)
                </small>
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
                <label for="address" class="form-label required">
                  <i class="fas fa-home"></i> Address
                </label>
                <input type="text" 
                       class="form-input" 
                       id="address" 
                       name="address" 
                       value="<?php echo htmlspecialchars($profile->user_address ?? ''); ?>" 
                       placeholder="Street address">
                <small class="form-help">
                  <i class="fas fa-info-circle"></i> Required for 50% profile completion (25 points total for location)
                </small>
              </div>
              
              <div class="form-group">
                <label for="city" class="form-label required">
                  <i class="fas fa-city"></i> City
                </label>
                <input type="text" 
                       class="form-input" 
                       id="city" 
                       name="city" 
                       value="<?php echo htmlspecialchars($profile->user_city ?? ''); ?>" 
                       placeholder="City">
              </div>
              
              <div class="form-group">
                <label for="state" class="form-label required">
                  <i class="fas fa-map"></i> State
                </label>
                <select class="form-select" id="state" name="state">
                  <option value="">Select State</option>
                  <?php 
                  $mexican_states = [
                    'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 
                    'Chiapas', 'Chihuahua', 'Coahuila', 'Colima', 'Durango', 'Guanajuato', 
                    'Guerrero', 'Hidalgo', 'Jalisco', 'México', 'Michoacán', 'Morelos', 
                    'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 
                    'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 
                    'Veracruz', 'Yucatán', 'Zacatecas', 'Ciudad de México'
                  ];
                  foreach ($mexican_states as $state): 
                    $selected = ($profile->user_country ?? '') === $state ? 'selected' : '';
                  ?>
                    <option value="<?php echo $state; ?>" <?php echo $selected; ?>><?php echo $state; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              
            </div>
          </div>

        </div>

        <!-- Right Column -->
        <div class="form-column">
          
          <!-- About Me Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-user-edit"></i> About Me</h3>
            </div>
            <div class="card-content">
              
              <div class="form-group">
                <label for="bio" class="form-label required">
                  <i class="fas fa-quote-left"></i> Bio
                </label>
                <textarea class="form-textarea" 
                          id="bio" 
                          name="bio" 
                          rows="8" 
                          placeholder="Tell potential clients about yourself, your cleaning experience, and what makes you special..."><?php echo htmlspecialchars($profile->bio ?? ''); ?></textarea>
                <div class="form-help-row">
                  <small class="form-help">
                    <i class="fas fa-info-circle"></i> Required for 50% profile completion (15 points)
                  </small>
                  <small class="form-help">
                    <span id="bioCharCount">0</span> / 1000 characters
                  </small>
                </div>
              </div>
              
            </div>
          </div>

          <!-- Service Areas Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-map-marked-alt"></i> Service Areas</h3>
            </div>
            <div class="card-content">
              
              <div class="form-group">
                <label class="form-label required">
                  <i class="fas fa-location-arrow"></i> Where do you provide cleaning services?
                </label>
                <small class="form-help">
                  <i class="fas fa-info-circle"></i> Select all areas where you can travel to provide cleaning services (20 points)
                </small>
                
                <div class="checkbox-grid">
                  <?php 
                  $selected_areas = !empty($profile->service_areas) ? json_decode($profile->service_areas, true) : [];
                  foreach ($service_areas as $area): 
                  ?>
                    <div class="checkbox-item">
                      <label class="checkbox-label">
                        <input type="checkbox" 
                               name="service_areas[]" 
                               value="<?php echo htmlspecialchars($area); ?>" 
                               id="area_<?php echo preg_replace('/[^a-zA-Z0-9]/', '_', $area); ?>"
                               <?php echo in_array($area, $selected_areas) ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text"><?php echo htmlspecialchars($area); ?></span>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              
            </div>
          </div>

          <!-- Cleaning Specialties Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-star"></i> Cleaning Specialties</h3>
            </div>
            <div class="card-content">
              
              <div class="form-group">
                <label class="form-label required">
                  <i class="fas fa-broom"></i> What cleaning services do you specialize in?
                </label>
                <small class="form-help">
                  <i class="fas fa-info-circle"></i> Select all cleaning services you can provide (15 points)
                </small>
                
                <div class="checkbox-grid">
                  <?php 
                  $selected_specialties = !empty($profile->specialties) ? json_decode($profile->specialties, true) : [];
                  foreach ($specialties as $specialty): 
                  ?>
                    <div class="checkbox-item">
                      <label class="checkbox-label">
                        <input type="checkbox" 
                               name="specialties[]" 
                               value="<?php echo htmlspecialchars($specialty); ?>" 
                               id="specialty_<?php echo preg_replace('/[^a-zA-Z0-9]/', '_', $specialty); ?>"
                               <?php echo in_array($specialty, $selected_specialties) ? 'checked' : ''; ?>>
                        <span class="checkbox-custom"></span>
                        <span class="checkbox-text"><?php echo htmlspecialchars($specialty); ?></span>
                      </label>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
              
            </div>
          </div>

          <!-- Profile Settings Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-cog"></i> Profile Settings</h3>
            </div>
            <div class="card-content">
              
              <div class="form-group">
                <label class="form-label">
                  <i class="fas fa-eye"></i> Profile Visibility
                </label>
                
                <div class="checkbox-group">
                  <label class="checkbox-item">
                    <input type="checkbox" 
                           id="is_public" 
                           name="is_public" 
                           <?php echo ($profile->is_public ?? 1) ? 'checked' : ''; ?>>
                    <span class="checkbox-custom"></span>
                    <span class="checkbox-label">
                      <i class="fas fa-globe"></i>
                      Make my profile public
                    </span>
                  </label>
                </div>
                
                <small class="form-help">
                  Public profiles can be viewed by hosts when you make offers on their jobs
                </small>
              </div>
              
            </div>
          </div>

          <!-- Form Actions Card -->
          <div class="form-card actions-card">
            <div class="card-content">
              <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-large">
                  <i class="fas fa-save"></i>
                  <span>Save Profile</span>
                </button>
                
                <div class="completion-badge">
                  <span class="badge completion-<?php echo $completion['percentage'] >= 50 ? 'success' : 'warning'; ?>">
                    <i class="fas fa-chart-pie"></i> <?php echo number_format($completion['percentage'], 0); ?>% Complete
                  </span>
                </div>
                
                <a href="<?php echo base_url('cleaner/my-profile'); ?>" class="btn btn-outline">
                  <i class="fas fa-times"></i> Cancel
                </a>
              </div>
            </div>
          </div>

        </div>

      </div>
      
    </form>
  </div>

</div>

<style>
/* Modern Cleaner Edit Profile Styles */
.cleaner-edit-container {
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

/* Reminder Banner */
.reminder-banner {
  background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
  border: 2px solid #667eea;
  border-radius: 12px;
  padding: 1.5rem;
  margin-bottom: 2rem;
  box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
}

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

.alert-card.success {
  border-left: 4px solid #28a745;
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
}

.alert-card.warning .alert-icon {
  background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
}

.alert-card.success .alert-icon {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.alert-text h4 {
  margin: 0 0 0.5rem 0;
  color: #333;
  font-weight: 600;
}

.alert-text p {
  margin: 0;
  color: #666;
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

.form-card.actions-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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

.form-textarea {
  resize: vertical;
  min-height: 120px;
}

.form-help {
  font-size: 0.85rem;
  color: #6c757d;
  margin-top: 0.5rem;
  line-height: 1.4;
}

.form-help-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 0.5rem;
}

/* Checkbox Grid */
.checkbox-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 0.75rem;
  margin-top: 1rem;
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

.checkbox-label {
  display: flex;
  align-items: center;
  cursor: pointer;
  width: 100%;
}

.checkbox-label input[type="checkbox"] {
  display: none;
}

.checkbox-custom {
  width: 18px;
  height: 18px;
  border: 2px solid #dee2e6;
  border-radius: 4px;
  margin-right: 0.75rem;
  position: relative;
  transition: all 0.3s ease;
  flex-shrink: 0;
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
}

.checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
  content: '✓';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  font-size: 10px;
  font-weight: bold;
}

.checkbox-text {
  font-size: 0.9rem;
  color: #495057;
  font-weight: 500;
}

/* Profile Settings Checkbox */
.checkbox-group {
  margin: 1rem 0;
}

.checkbox-group .checkbox-item {
  border: 2px solid transparent;
}

.checkbox-group .checkbox-item:hover {
  background: rgba(102, 126, 234, 0.05);
  border-color: rgba(102, 126, 234, 0.2);
}

.checkbox-group .checkbox-label {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  font-weight: 500;
  color: #495057;
}

.checkbox-group .checkbox-label i {
  color: #667eea;
  font-size: 0.9rem;
}

/* Form Actions */
.form-actions {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  align-items: center;
}

.completion-badge {
  text-align: center;
}

.badge {
  padding: 0.75rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 1rem;
}

.completion-success {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.completion-warning {
  background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
  color: white;
  box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3);
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
  color: #667eea;
  border: 2px solid #667eea;
}

.btn-outline:hover {
  background: #667eea;
  color: white;
  text-decoration: none;
}

.btn-large {
  padding: 1rem 2rem;
  font-size: 1.1rem;
  font-weight: 700;
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
  
  .card-content {
    padding: 1.5rem;
  }
  
  .alert-content {
    flex-direction: column;
    text-align: center;
  }
  
  .reminder-content {
    flex-direction: column;
    text-align: center;
  }
  
  .checkbox-grid {
    grid-template-columns: 1fr;
  }
  
  .form-help-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
  }
}

@media (max-width: 480px) {
  .cleaner-edit-container {
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
  
  .reminder-banner {
    padding: 1rem;
  }
}
</style>

<!-- JavaScript -->
<script>
$(document).ready(function() {
    // Character counter for bio
    function updateCharCount() {
        const bioLength = $('#bio').val().length;
        $('#bioCharCount').text(bioLength);
    }
    
    $('#bio').on('input', updateCharCount);
    updateCharCount(); // Initial count
    
    // Handle form submission
    $('#profileEditForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalBtnText = submitBtn.html();
        
        // Disable button and show loading
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: '<?php echo base_url('cleaner/update-profile'); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            timeout: 10000, // 10 second timeout
            success: function(response) {
                console.log('AJAX Success Response:', response);
                console.log('Response success status:', response.success);
                
                if (response.success) {
                    console.log('About to show success message and redirect...');
                    
                    var redirectUrl = '<?php echo base_url('cleaner/my-profile'); ?>';
                    console.log('Will redirect to:', redirectUrl);
                    
                    // Show success message briefly
                    alert('Success! ' + response.message);
                    
                    // Multiple redirect attempts to ensure it works
                    setTimeout(function() {
                        console.log('Attempting redirect...');
                        
                        // Method 1: Direct assignment
                        window.location.href = redirectUrl;
                        
                        // Method 2: Backup after 500ms
                        setTimeout(function() {
                            if (window.location.href.indexOf('edit-profile') !== -1) {
                                console.log('First redirect failed, trying backup method...');
                                window.location.assign(redirectUrl);
                            }
                        }, 500);
                        
                        // Method 3: Force redirect after 1 second
                        setTimeout(function() {
                            if (window.location.href.indexOf('edit-profile') !== -1) {
                                console.log('Backup redirect failed, forcing redirect...');
                                window.location.replace(redirectUrl);
                            }
                        }, 1000);
                        
                    }, 1200);
                } else {
                    console.log('Response was not successful:', response.message);
                    alert('Error: ' + response.message);
                    submitBtn.prop('disabled', false).html(originalBtnText);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', {
                    status: status,
                    error: error,
                    responseText: xhr.responseText,
                    statusCode: xhr.status
                });
                
                // Re-enable button
                submitBtn.prop('disabled', false).html(originalBtnText);
                
                let errorMessage = 'An error occurred. Please try again.';
                
                // Handle timeout specifically
                if (status === 'timeout') {
                    errorMessage = 'Request timed out. Please try again.';
                }
                
                // Try to parse error response
                try {
                    let errorData = JSON.parse(xhr.responseText);
                    if (errorData.message) {
                        errorMessage = errorData.message;
                    }
                } catch(e) {
                    if (xhr.responseText) {
                        errorMessage = 'Server error: ' + xhr.responseText.substring(0, 100);
                    }
                }
                
                alert('Error: ' + errorMessage);
            }
        });
    });
});
</script>