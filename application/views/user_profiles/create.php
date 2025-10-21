<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus"></i> Create Your Profile
                    </h4>
                    <p class="text-muted mb-0">Build your professional profile to showcase your skills and attract clients</p>
                </div>
                <div class="card-body">
                    <!-- Profile Creation Form -->
                    <form id="profileForm" method="post">
                        <!-- Step 1: Basic Information -->
                        <div class="profile-step active" id="step1">
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
                                <label for="phone" class="form-label">Phone Number (Optional)</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       placeholder="Enter your phone number">
                                <small class="form-text text-muted">This will be visible on your public profile</small>
                            </div>
                        </div>

                        <!-- Step 2: About You -->
                        <div class="profile-step" id="step2">
                            <h5><i class="fas fa-user"></i> About You</h5>
                            <div class="form-group">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="4" 
                                          placeholder="Tell potential clients about yourself, your experience, and what makes you special..."></textarea>
                                <div class="form-text">
                                    <span id="bioCharCount">0</span>/500 characters
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Service Areas -->
                        <div class="profile-step" id="step3">
                            <h5><i class="fas fa-map-marker-alt"></i> Service Areas</h5>
                            <p class="text-muted">Where are you willing to provide cleaning services?</p>
                            
                            <div class="form-group">
                                <label class="form-label required">Select Service Areas</label>
                                <div class="service-areas-grid">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_areas[]" value="downtown" id="area_downtown">
                                        <label class="form-check-label" for="area_downtown">
                                            <i class="fas fa-building"></i> Downtown
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_areas[]" value="suburbs" id="area_suburbs">
                                        <label class="form-check-label" for="area_suburbs">
                                            <i class="fas fa-home"></i> Suburbs
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_areas[]" value="north_side" id="area_north">
                                        <label class="form-check-label" for="area_north">
                                            <i class="fas fa-map"></i> North Side
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_areas[]" value="south_side" id="area_south">
                                        <label class="form-check-label" for="area_south">
                                            <i class="fas fa-map"></i> South Side
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_areas[]" value="east_side" id="area_east">
                                        <label class="form-check-label" for="area_east">
                                            <i class="fas fa-map"></i> East Side
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="service_areas[]" value="west_side" id="area_west">
                                        <label class="form-check-label" for="area_west">
                                            <i class="fas fa-map"></i> West Side
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Specialties -->
                        <div class="profile-step" id="step4">
                            <h5><i class="fas fa-star"></i> Specialties</h5>
                            <p class="text-muted">What types of cleaning are you best at?</p>
                            
                            <div class="form-group">
                                <label class="form-label required">Select Your Specialties</label>
                                <div class="specialties-grid">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="residential" id="spec_residential">
                                        <label class="form-check-label" for="spec_residential">
                                            <i class="fas fa-home"></i> Residential Cleaning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="office" id="spec_office">
                                        <label class="form-check-label" for="spec_office">
                                            <i class="fas fa-building"></i> Office Cleaning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="deep_cleaning" id="spec_deep">
                                        <label class="form-check-label" for="spec_deep">
                                            <i class="fas fa-broom"></i> Deep Cleaning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="move_in_out" id="spec_move">
                                        <label class="form-check-label" for="spec_move">
                                            <i class="fas fa-truck-moving"></i> Move In/Out Cleaning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="post_construction" id="spec_construction">
                                        <label class="form-check-label" for="spec_construction">
                                            <i class="fas fa-hammer"></i> Post-Construction Cleaning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="green_cleaning" id="spec_green">
                                        <label class="form-check-label" for="spec_green">
                                            <i class="fas fa-leaf"></i> Green/Eco-Friendly Cleaning
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="window_cleaning" id="spec_windows">
                                        <label class="form-check-label" for="spec_windows">
                                            <i class="fas fa-window-maximize"></i> Window Cleaning
                                        </label>
                                    </div>
                                <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="specialties[]" value="carpet_cleaning" id="spec_carpet">
                                        <label class="form-check-label" for="spec_carpet">
                                            <i class="fas fa-couch"></i> Carpet Cleaning
                                    </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Privacy Settings -->
                        <div class="profile-step" id="step5">
                            <h5><i class="fas fa-shield-alt"></i> Privacy Settings</h5>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_public" value="1" id="is_public" checked>
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

                        <!-- Navigation Buttons -->
                        <div class="profile-navigation">
                            <button type="button" class="btn btn-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                                <i class="fas fa-arrow-left"></i> Previous
                            </button>
                            <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                                Next <i class="fas fa-arrow-right"></i>
                            </button>
                            <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                                <i class="fas fa-check"></i> Create Profile
                            </button>
                        </div>

                        <!-- Progress Indicator -->
                        <div class="progress-indicator">
                            <div class="progress-step active" data-step="1">1</div>
                            <div class="progress-step" data-step="2">2</div>
                            <div class="progress-step" data-step="3">3</div>
                            <div class="progress-step" data-step="4">4</div>
                            <div class="progress-step" data-step="5">5</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.profile-step {
    display: none;
    padding: 20px 0;
    min-height: 400px;
}

.profile-step.active {
    display: block;
}

.profile-step h5 {
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

.profile-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.progress-indicator {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-top: 20px;
    gap: 20px;
}

.progress-step {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s;
}

.progress-step.active {
    background-color: #007bff;
    color: white;
}

.progress-step.completed {
    background-color: #28a745;
    color: white;
}

.required::after {
    content: " *";
    color: #dc3545;
}

#bioCharCount {
    font-weight: 500;
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
</style>

<script>
let currentStep = 1;
const totalSteps = 5;

function changeStep(direction) {
    const steps = document.querySelectorAll('.profile-step');
    const progressSteps = document.querySelectorAll('.progress-step');
    
    // Hide current step
    steps[currentStep - 1].classList.remove('active');
    progressSteps[currentStep - 1].classList.remove('active');
    
    currentStep += direction;
    
    // Show new step
    steps[currentStep - 1].classList.add('active');
    progressSteps[currentStep - 1].classList.add('active');
    
    // Update navigation buttons
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    
    prevBtn.style.display = currentStep > 1 ? 'block' : 'none';
    
    if (currentStep < totalSteps) {
        nextBtn.style.display = 'block';
        submitBtn.style.display = 'none';
                } else {
        nextBtn.style.display = 'none';
        submitBtn.style.display = 'block';
    }
    
    // Mark completed steps
    for (let i = 0; i < currentStep - 1; i++) {
        progressSteps[i].classList.add('completed');
    }
}

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

    // Form submission
    $('#profileForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        const serviceAreas = $('input[name="service_areas[]"]:checked').length;
        const specialties = $('input[name="specialties[]"]:checked').length;
        
        if (serviceAreas === 0) {
            alert('Please select at least one service area.');
            changeStep(1); // Go to step 3
            return;
        }
        
        if (specialties === 0) {
            alert('Please select at least one specialty.');
            changeStep(1); // Go to step 4
            return;
        }
        
        // Show loading state
        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creating...').prop('disabled', true);
        
        // Submit form
        $.ajax({
            url: '<?php echo base_url("userprofile/save_profile"); ?>',
            type: 'POST',
            data: $(this).serialize(),
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