<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Modern Host Edit Profile View v2.0 -->
<div class="host-edit-container">
  
  <!-- Edit Header -->
  <div class="edit-header">
    <div class="edit-header-content">
      <div class="edit-title-section">
        <h1 class="edit-title">
          <i class="fas fa-user-edit"></i>
          Edit My Profile
        </h1>
        <p class="edit-subtitle">Update your profile information and settings</p>
      </div>
      
      <div class="edit-actions">
        <a href="<?php echo base_url('host/my-profile'); ?>" class="btn btn-secondary">
          <i class="fas fa-eye"></i> View Profile
        </a>
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
        <p>Your profile is <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong> complete. You need at least <strong>50%</strong> completion to post jobs. Complete the missing fields below.</p>
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
        <p>Your profile is <strong><?php echo number_format($completion['percentage'], 0); ?>%</strong> complete. You can post jobs and manage your cleaning requests.</p>
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
                       value="<?php echo htmlspecialchars($profile->full_name ?? ''); ?>" 
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
                       value="Host" 
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
                  <i class="fas fa-info-circle"></i> Required for 50% profile completion (15 points)
                </small>
              </div>
              
            </div>
          </div>

          <!-- About Me Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-align-left"></i> About Me</h3>
            </div>
            <div class="card-content">
              
              <div class="form-group">
                <label for="bio" class="form-label required">
                  <i class="fas fa-quote-left"></i> Bio / Description
                </label>
                <textarea class="form-textarea" 
                          id="bio" 
                          name="bio" 
                          rows="5" 
                          placeholder="Tell cleaners about yourself and your cleaning needs..." 
                          maxlength="1000"><?php echo htmlspecialchars($profile->bio ?? ''); ?></textarea>
                <div class="form-help-row">
                  <small class="form-help">
                    <i class="fas fa-info-circle"></i> At least 30 characters required (15 points)
                  </small>
                  <small class="form-help">
                    <span id="bioCharCount">0</span> / 1000 characters
                  </small>
                </div>
              </div>
              
            </div>
          </div>

        </div>

        <!-- Right Column -->
        <div class="form-column">
          
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
                  <i class="fas fa-city"></i> City / Municipality and State
                </label>
                <select class="form-select" id="city" name="city">
                  <option value="">Select City and State</option>
                  <?php 
                  // Combine current city and state for comparison
                  $current_city = $profile->user_city ?? '';
                  $current_state = $profile->user_country ?? '';
                  $current_location = trim($current_city . ', ' . $current_state);
                  
                  foreach ($service_areas as $area): 
                    $selected = (trim($area) === trim($current_location)) ? 'selected' : '';
                  ?>
                    <option value="<?php echo htmlspecialchars($area); ?>" <?php echo $selected; ?>>
                      <?php echo htmlspecialchars($area); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <small class="form-help">
                  <i class="fas fa-info-circle"></i> Select your city/municipality and state (e.g., "San Carlos, Sonora")
                </small>
              </div>
              
            </div>
          </div>

          <!-- Profile Picture Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-camera"></i> Profile Picture</h3>
            </div>
            <div class="card-content text-center">
              
              <?php if (!empty($profile->profile_picture_url)): ?>
                <img src="<?php echo base_url($profile->profile_picture_url); ?>" 
                     alt="Profile" class="profile-preview">
              <?php else: ?>
                <div class="profile-preview-placeholder">
                  <i class="fas fa-user"></i>
                </div>
              <?php endif; ?>
              
              <div class="upload-info">
                <p class="upload-text">
                  <i class="fas fa-info-circle"></i> Profile picture upload (20 points) - Coming soon
                </p>
                <button type="button" class="btn btn-secondary" disabled>
                  <i class="fas fa-upload"></i> Upload Picture (Coming Soon)
                </button>
              </div>
              
            </div>
          </div>

          <!-- Hidden: All profiles are public by default -->
          <input type="hidden" name="is_public" value="1">

          <!-- Profile Statistics Card -->
          <div class="form-card">
            <div class="card-header">
              <h3><i class="fas fa-chart-bar"></i> Profile Statistics</h3>
            </div>
            <div class="card-content">
              
              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-icon bg-warning">
                    <i class="fas fa-star"></i>
                  </div>
                  <div class="stat-info">
                    <div class="stat-label">Rating</div>
                    <div class="stat-value">
                      <?php 
                      if ($profile->average_rating > 0) {
                        echo number_format($profile->average_rating, 1);
                      } else {
                        echo 'N/A';
                      }
                      ?>
                    </div>
                  </div>
                </div>
                
                <div class="stat-item">
                  <div class="stat-icon bg-info">
                    <i class="fas fa-comments"></i>
                  </div>
                  <div class="stat-info">
                    <div class="stat-label">Reviews</div>
                    <div class="stat-value"><?php echo $profile->total_reviews ?? 0; ?></div>
                  </div>
                </div>
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
                
                <a href="<?php echo base_url('host/my-profile'); ?>" class="btn btn-outline">
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
/* Modern Host Edit Profile Styles */
.host-edit-container {
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
  min-height: 100px;
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

/* Profile Picture */
.profile-preview, .profile-preview-placeholder {
  width: 150px;
  height: 150px;
  border-radius: 50%;
  margin: 0 auto 1rem;
  border: 4px solid #e9ecef;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.profile-preview {
  object-fit: cover;
}

.profile-preview-placeholder {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
  font-size: 4rem;
}

.upload-info {
  margin-top: 1rem;
}

.upload-text {
  color: #6c757d;
  margin-bottom: 1rem;
}

/* Checkbox Styling */
.checkbox-group {
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

/* Statistics */
.stats-grid {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.stat-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-radius: 8px;
  transition: all 0.3s ease;
}

.stat-item:hover {
  transform: translateX(5px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
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

.bg-info { background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%); }
.bg-warning { background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%); }

.stat-info {
  flex: 1;
}

.stat-label {
  font-size: 0.9rem;
  color: #6c757d;
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
  color: #495057;
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
  
  .stats-grid {
    gap: 0.75rem;
  }
  
  .stat-item {
    padding: 0.75rem;
  }
  
  .form-help-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.25rem;
  }
}

@media (max-width: 480px) {
  .host-edit-container {
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
            url: '<?php echo base_url('host/update-profile'); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            timeout: 10000, // 10 second timeout
            success: function(response) {
                console.log('AJAX Success Response:', response);
                console.log('Response success status:', response.success);
                
                if (response.success) {
                    console.log('About to show success message and redirect...');
                    
                    var redirectUrl = '<?php echo base_url('host/my-profile'); ?>';
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