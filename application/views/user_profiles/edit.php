<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Your Profile
                    </h4>
                    <p class="text-muted mb-0">Update your profile information to showcase your skills and attract clients</p>
                </div>
                <div class="card-body">
                    <!-- Profile Edit Form -->
                    <form id="profileEditForm" method="post">
                        <!-- Basic Information -->
                        <div class="form-section">
                            <h5><i class="fas fa-info-circle"></i> Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" 
                                               value="<?php echo htmlspecialchars($user->first_name ?? ''); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" 
                                               value="<?php echo htmlspecialchars($user->last_name ?? ''); ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($profile->phone ?? ''); ?>" 
                                       placeholder="Enter your phone number">
                                <small class="form-text text-muted">This will be visible on your public profile</small>
                            </div>
                        </div>

                        <!-- About Section -->
                        <div class="form-section">
                            <h5><i class="fas fa-user"></i> About You</h5>
                            <div class="form-group">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4" 
                                          placeholder="Tell potential clients about yourself, your experience, and what makes you special..."><?php echo htmlspecialchars($profile->bio ?? ''); ?></textarea>
                                <div class="form-text">
                                    <span id="bioCharCount"><?php echo strlen($profile->bio ?? ''); ?></span>/500 characters
                                </div>
                            </div>
                        </div>

                        <!-- Service Areas -->
                        <div class="form-section">
                            <h5><i class="fas fa-map-marker-alt"></i> Service Areas</h5>
                            <p class="text-muted">Where are you willing to provide cleaning services?</p>
                            
                            <div class="form-group">
                                <label class="form-label required">Select Service Areas</label>
                                <div class="service-areas-grid">
                                    <?php 
                                    $selected_areas = $profile->service_areas ? json_decode($profile->service_areas, true) : array();
                                    $areas = array(
                                        'downtown' => array('label' => 'Downtown', 'icon' => 'fas fa-building'),
                                        'suburbs' => array('label' => 'Suburbs', 'icon' => 'fas fa-home'),
                                        'north_side' => array('label' => 'North Side', 'icon' => 'fas fa-map'),
                                        'south_side' => array('label' => 'South Side', 'icon' => 'fas fa-map'),
                                        'east_side' => array('label' => 'East Side', 'icon' => 'fas fa-map'),
                                        'west_side' => array('label' => 'West Side', 'icon' => 'fas fa-map')
                                    );
                                    
                                    foreach ($areas as $key => $area):
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_areas[]" 
                                               value="<?php echo $key; ?>" id="area_<?php echo $key; ?>"
                                               <?php echo in_array($key, $selected_areas) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="area_<?php echo $key; ?>">
                                            <i class="<?php echo $area['icon']; ?>"></i> <?php echo $area['label']; ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Specialties -->
                        <div class="form-section">
                            <h5><i class="fas fa-star"></i> Specialties</h5>
                            <p class="text-muted">What types of cleaning are you best at?</p>
                            
                            <div class="form-group">
                                <label class="form-label required">Select Your Specialties</label>
                                <div class="specialties-grid">
                                    <?php 
                                    $selected_specialties = $profile->specialties ? json_decode($profile->specialties, true) : array();
                                    $specialties = array(
                                        'residential' => array('label' => 'Residential Cleaning', 'icon' => 'fas fa-home'),
                                        'office' => array('label' => 'Office Cleaning', 'icon' => 'fas fa-building'),
                                        'deep_cleaning' => array('label' => 'Deep Cleaning', 'icon' => 'fas fa-broom'),
                                        'move_in_out' => array('label' => 'Move In/Out Cleaning', 'icon' => 'fas fa-truck-moving'),
                                        'post_construction' => array('label' => 'Post-Construction Cleaning', 'icon' => 'fas fa-hammer'),
                                        'green_cleaning' => array('label' => 'Green/Eco-Friendly Cleaning', 'icon' => 'fas fa-leaf'),
                                        'window_cleaning' => array('label' => 'Window Cleaning', 'icon' => 'fas fa-window-maximize'),
                                        'carpet_cleaning' => array('label' => 'Carpet Cleaning', 'icon' => 'fas fa-couch')
                                    );
                                    
                                    foreach ($specialties as $key => $specialty):
                                    ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" 
                                               value="<?php echo $key; ?>" id="spec_<?php echo $key; ?>"
                                               <?php echo in_array($key, $selected_specialties) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="spec_<?php echo $key; ?>">
                                            <i class="<?php echo $specialty['icon']; ?>"></i> <?php echo $specialty['label']; ?>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="form-section">
                            <h5><i class="fas fa-shield-alt"></i> Privacy Settings</h5>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_public" value="1" 
                                           id="is_public" <?php echo ($profile->is_public ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_public">
                                        Make my profile public
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Public profiles can be found by other users and appear in search results. 
                                    Private profiles are only visible to you.
                                </small>
                            </div>
                        </div>

                        <!-- Profile Photos -->
                        <div class="form-section">
                            <h5><i class="fas fa-camera"></i> Profile Photos</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="photo-upload">
                                        <label class="form-label">Profile Picture</label>
                                        <div class="photo-preview">
                                            <?php if ($profile->profile_picture_url): ?>
                                                <img src="<?php echo $profile->profile_picture_url; ?>" alt="Profile Picture" class="current-photo">
                                            <?php else: ?>
                                                <div class="no-photo">
                                                    <i class="fas fa-user fa-2x"></i>
                                                    <p>No photo</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*" class="d-none">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="$('#profile_photo').click();">
                                            <i class="fas fa-upload"></i> Upload Photo
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="photo-upload">
                                        <label class="form-label">Cover Photo (Optional)</label>
                                        <div class="photo-preview">
                                            <?php if ($profile->cover_photo_url): ?>
                                                <img src="<?php echo $profile->cover_photo_url; ?>" alt="Cover Photo" class="current-photo">
                                            <?php else: ?>
                                                <div class="no-photo">
                                                    <i class="fas fa-image fa-2x"></i>
                                                    <p>No cover photo</p>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" id="cover_photo" name="cover_photo" accept="image/*" class="d-none">
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="$('#cover_photo').click();">
                                            <i class="fas fa-upload"></i> Upload Cover Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Changes
                            </button>
                            <a href="<?php echo base_url('userprofile/my_profile'); ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-section {
    padding: 25px 0;
    border-bottom: 1px solid #e9ecef;
}

.form-section:last-child {
    border-bottom: none;
}

.form-section h5 {
    color: #007bff;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.service-areas-grid,
.specialties-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.form-check {
    padding: 15px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    transition: all 0.2s;
    cursor: pointer;
}

.form-check:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
}

.form-check-input:checked + .form-check-label {
    color: #007bff;
    font-weight: 500;
}

.form-check-input:checked ~ .form-check {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.form-check-label {
    cursor: pointer;
    margin-bottom: 0;
}

.form-check-label i {
    margin-right: 8px;
    color: #6c757d;
}

.required::after {
    content: " *";
    color: #dc3545;
}

#bioCharCount {
    font-weight: 500;
}

.photo-upload {
    text-align: center;
}

.photo-preview {
    width: 100%;
    height: 150px;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 10px;
    background: #f8f9fa;
}

.current-photo {
    max-width: 100%;
    max-height: 100%;
    border-radius: 8px;
}

.no-photo {
    color: #6c757d;
}

.no-photo i {
    margin-bottom: 10px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn {
    padding: 12px 24px;
    font-weight: 500;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.form-control[readonly] {
    background-color: #e9ecef;
    opacity: 1;
}
</style>

<script>
$(document).ready(function() {
    // Bio character count
    $('#bio').on('input', function() {
        const count = $(this).val().length;
        $('#bioCharCount').text(count);
        
        if (count > 450) {
            $('#bioCharCount').css('color', '#dc3545');
        } else if (count > 350) {
            $('#bioCharCount').css('color', '#ffc107');
        } else {
            $('#bioCharCount').css('color', '#6c757d');
        }
    });
    
    // Photo preview functionality
    $('#profile_photo, #cover_photo').on('change', function() {
        const file = this.files[0];
        const preview = $(this).siblings('.photo-preview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.html('<img src="' + e.target.result + '" alt="Preview" class="current-photo">');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Form submission
    $('#profileEditForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        const serviceAreas = $('input[name="service_areas[]"]:checked').length;
        const specialties = $('input[name="specialties[]"]:checked').length;
        
        if (serviceAreas === 0) {
            alert('Please select at least one service area.');
            return;
        }
        
        if (specialties === 0) {
            alert('Please select at least one specialty.');
            return;
        }
        
        // Show loading state
        const submitBtn = $('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
        
        // Create FormData for file uploads
        const formData = new FormData(this);
        
        // Submit form
        $.ajax({
            url: '<?php echo base_url("userprofile/update"); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect;
                } else {
                    alert(response.message);
                    submitBtn.html(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>

<?php $this->load->view('template/footer'); ?>
