<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-edit"></i> Edit Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <?php foreach ($breadcrumbs as $crumb): ?>
                            <?php if (isset($crumb['active']) && $crumb['active']): ?>
                                <li class="breadcrumb-item active"><?php echo $crumb['title']; ?></li>
                            <?php else: ?>
                                <li class="breadcrumb-item"><a href="<?php echo base_url($crumb['url']); ?>"><?php echo $crumb['title']; ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Profile Completion Alert -->
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-<?php echo $completion['percentage'] >= 50 ? 'success' : 'warning'; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="icon fas fa-<?php echo $completion['percentage'] >= 50 ? 'check' : 'exclamation-triangle'; ?>"></i> Profile Completion: <?php echo $completion['percentage']; ?>%</h5>
                        <?php if ($completion['percentage'] < 50): ?>
                            <p>This profile is below the minimum 50% completion required for the user to perform key actions (creating jobs for hosts, making offers for cleaners).</p>
                        <?php else: ?>
                            <p>This profile meets the minimum requirements!</p>
                        <?php endif; ?>
                        <?php if (!empty($completion['missing'])): ?>
                            <p><strong>Missing:</strong> <?php echo implode(', ', $completion['missing']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <form id="profileEditForm" method="post">
                <input type="hidden" name="user_id" value="<?php echo $profile->user_id; ?>">
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        
                        <!-- Basic Information -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-user"></i> Basic Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?>" disabled>
                                    <small class="form-text text-muted">To change name, edit user account details</small>
                                </div>

                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($profile->username); ?>" disabled>
                                    <small class="form-text text-muted">Username cannot be changed</small>
                                </div>

                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" value="<?php echo htmlspecialchars($profile->email); ?>" disabled>
                                    <small class="form-text text-muted">To change email, edit user account details</small>
                                </div>

                                <div class="form-group">
                                    <label>Role</label>
                                    <select class="form-control" disabled>
                                        <option <?php echo $profile->auth_level == 3 ? 'selected' : ''; ?>>Cleaner</option>
                                        <option <?php echo $profile->auth_level == 6 ? 'selected' : ''; ?>>Host</option>
                                        <option <?php echo $profile->auth_level == 9 ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <small class="form-text text-muted">Role cannot be changed from profile</small>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-phone"></i> Contact Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="phone">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" 
                                           value="<?php echo htmlspecialchars($profile->phone ?? $profile->user_phone ?? ''); ?>" 
                                           placeholder="Enter phone number">
                                    <small class="form-text text-muted">Required for profile completion</small>
                                </div>

                                <div class="form-group">
                                    <label for="city">City</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($profile->user_city ?? ''); ?>" disabled>
                                    <small class="form-text text-muted">To change location, edit user account details</small>
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-align-left"></i> About / Bio</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="bio">Bio <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="bio" name="bio" rows="6" 
                                              placeholder="Tell us about yourself..."><?php echo htmlspecialchars($profile->bio ?? ''); ?></textarea>
                                    <small class="form-text text-muted">
                                        Minimum <?php echo $profile->auth_level == 3 ? '50' : '30'; ?> characters required. 
                                        <span id="bioCharCount">Current: <?php echo strlen($profile->bio ?? ''); ?></span>
                                    </small>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        
                        <!-- Profile Settings -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-cog"></i> Profile Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" id="is_public" name="is_public" value="1" 
                                               <?php echo ($profile->is_public == 1) ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="is_public">Public Profile</label>
                                    </div>
                                    <small class="form-text text-muted">When enabled, profile is visible to other users in appropriate contexts</small>
                                </div>
                            </div>
                        </div>

                        <!-- Cleaner-Specific Fields -->
                        <?php if ($profile->auth_level == 3): ?>
                            
                            <!-- Service Areas -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-map-marker-alt"></i> Service Areas <span class="text-danger">*</span></h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-2">Select the areas where you provide services:</p>
                                    <div style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                        <?php 
                                        $current_areas = !empty($profile->service_areas) ? json_decode($profile->service_areas, true) : array();
                                        if (!is_array($current_areas)) $current_areas = array();
                                        
                                        foreach ($service_areas as $area): 
                                        ?>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" 
                                                       id="area_<?php echo sanitize_title($area); ?>" 
                                                       name="service_areas[]" 
                                                       value="<?php echo htmlspecialchars($area); ?>"
                                                       <?php echo in_array($area, $current_areas) ? 'checked' : ''; ?>>
                                                <label class="custom-control-label" for="area_<?php echo sanitize_title($area); ?>">
                                                    <?php echo htmlspecialchars($area); ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <small class="form-text text-muted">Select at least one service area for profile completion</small>
                                </div>
                            </div>

                            <!-- Specialties -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-certificate"></i> Specialties <span class="text-danger">*</span></h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted mb-2">Select your cleaning specialties:</p>
                                    <?php 
                                    $current_specialties = !empty($profile->specialties) ? json_decode($profile->specialties, true) : array();
                                    if (!is_array($current_specialties)) $current_specialties = array();
                                    
                                    foreach ($specialties_list as $key => $label): 
                                    ?>
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" 
                                                   id="specialty_<?php echo $key; ?>" 
                                                   name="specialties[]" 
                                                   value="<?php echo $key; ?>"
                                                   <?php echo in_array($key, $current_specialties) ? 'checked' : ''; ?>>
                                            <label class="custom-control-label" for="specialty_<?php echo $key; ?>">
                                                <?php echo htmlspecialchars($label); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                    <small class="form-text text-muted">Select at least one specialty for profile completion</small>
                                </div>
                            </div>

                        <?php endif; ?>

                        <!-- Profile Picture -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-camera"></i> Profile Picture</h3>
                            </div>
                            <div class="card-body text-center">
                                <?php if (!empty($profile->profile_picture_url)): ?>
                                    <img src="<?php echo $profile->profile_picture_url; ?>" class="img-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="Profile Picture">
                                <?php else: ?>
                                    <div class="img-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; font-size: 60px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                <?php endif; ?>
                                <p class="text-muted">Profile picture management coming soon</p>
                                <small class="form-text text-muted">For now, profile pictures can be managed through user account settings</small>
                            </div>
                        </div>

                        <!-- Statistics (Read-only) -->
                        <?php if ($profile->auth_level == 3): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistics (Read-only)</h3>
                            </div>
                            <div class="card-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-6">Jobs Completed:</dt>
                                    <dd class="col-sm-6"><?php echo $profile->total_jobs_completed; ?></dd>
                                    
                                    <dt class="col-sm-6">Completion Rate:</dt>
                                    <dd class="col-sm-6"><?php echo number_format($profile->completion_rate, 1); ?>%</dd>
                                    
                                    <dt class="col-sm-6">Average Rating:</dt>
                                    <dd class="col-sm-6">
                                        <?php if ($profile->average_rating > 0): ?>
                                            <?php echo number_format($profile->average_rating, 1); ?> / 5.0
                                        <?php else: ?>
                                            No reviews yet
                                        <?php endif; ?>
                                    </dd>
                                    
                                    <dt class="col-sm-6">Total Reviews:</dt>
                                    <dd class="col-sm-6"><?php echo $profile->total_reviews; ?></dd>
                                </dl>
                                <small class="form-text text-muted">Statistics are automatically updated based on job performance</small>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <button type="submit" class="btn btn-primary btn-lg" id="saveBtn">
                                    <i class="fas fa-save"></i> Save Profile
                                </button>
                                <a href="<?php echo base_url('admin/profile/' . $profile->user_id); ?>" class="btn btn-default btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <a href="<?php echo base_url('admin/profiles'); ?>" class="btn btn-secondary btn-lg float-right">
                                    <i class="fas fa-list"></i> Back to List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Character count for bio
    $('#bio').on('input', function() {
        var count = $(this).val().length;
        $('#bioCharCount').text('Current: ' + count);
    });
    
    // Form submission
    $('#profileEditForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        var saveBtn = $('#saveBtn');
        var originalText = saveBtn.html();
        
        // Disable button and show loading
        saveBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
        
        $.ajax({
            url: '<?php echo base_url("admin/update_profile"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert('Profile updated successfully!');
                    
                    // Redirect to view profile
                    window.location.href = '<?php echo base_url("admin/profile/" . $profile->user_id); ?>';
                } else {
                    alert('Error: ' + response.message);
                    saveBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                alert('Failed to save profile. Please try again.');
                console.error('Error:', error);
                saveBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});

// Helper function to sanitize title for checkbox IDs
function sanitize_title(title) {
    return title.toLowerCase().replace(/[^a-z0-9]+/g, '_');
}
</script>

<style>
.img-circle {
    border-radius: 50%;
}
.card {
    margin-bottom: 1.5rem;
}
.custom-control {
    margin-bottom: 0.5rem;
}
</style>

<?php
// Helper function for checkbox IDs
function sanitize_title($str) {
    return strtolower(preg_replace('/[^a-z0-9]+/i', '_', $str));
}
?>


