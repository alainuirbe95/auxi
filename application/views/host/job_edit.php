<?php
// time_ago() function is already declared in modern_header.php

// Parse job data for form
$extras_array = [];
if (!empty($job->extras)) {
    $extras_array = json_decode($job->extras, true);
    if (!is_array($extras_array)) {
        $extras_array = [];
    }
}

$rooms_array = [];
if (!empty($job->rooms)) {
    $rooms_array = json_decode($job->rooms, true);
    if (!is_array($rooms_array)) {
        $rooms_array = [];
    }
}

// Combine scheduled_date and scheduled_time for datetime-local input
$datetime_value = '';
if (!empty($job->scheduled_date) && !empty($job->scheduled_time)) {
    $datetime_value = $job->scheduled_date . 'T' . substr($job->scheduled_time, 0, 5);
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Job Edit Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit text-warning me-2"></i>
                                Edit Cleaning Job
                            </h5>
                        </div>
                        <div class="card-body">
                            
                            <?php if (validation_errors() || $this->session->flashdata('text')): ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('type') ?: 'danger'; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $this->session->flashdata('text') ?: validation_errors(); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo base_url('host/process_edit_job/' . $job->id); ?>" method="post" id="jobEditForm">
                                
                                <!-- Basic Information -->
                                <div class="form-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Basic Information
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="title" class="form-label">Job Title *</label>
                                            <input type="text" class="form-control modern-input" id="title" name="title" 
                                                   value="<?php echo set_value('title', $job->title); ?>" 
                                                   placeholder="e.g., Deep cleaning for 3-bedroom apartment" required>
                                            <div class="form-text">Be specific and descriptive</div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="suggested_price" class="form-label">Suggested Price ($) *</label>
                                            <input type="number" class="form-control modern-input" id="suggested_price" name="suggested_price" 
                                                   value="<?php echo set_value('suggested_price', $job->suggested_price); ?>" 
                                                   min="1" step="0.01" placeholder="0.00" required>
                                            <div class="form-text">Cleaners can counter-offer</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Job Description *</label>
                                        <textarea class="form-control modern-input" id="description" name="description" 
                                                  rows="4" placeholder="Describe the cleaning requirements in detail..." required><?php echo set_value('description', $job->description); ?></textarea>
                                        <div class="form-text">Be detailed about what needs to be cleaned</div>
                                    </div>
                                </div>
                                
                                <!-- Location & Timing -->
                                <div class="form-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-map-marker-alt me-2"></i>
                                        Location & Timing
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="address" class="form-label">Address *</label>
                                            <input type="text" class="form-control modern-input" id="address" name="address" 
                                                   value="<?php echo set_value('address', $job->address); ?>" 
                                                   placeholder="Enter the complete address" required>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="city" class="form-label">City *</label>
                                            <input type="text" class="form-control modern-input" id="city" name="city" 
                                                   value="<?php echo set_value('city', $job->city); ?>" 
                                                   placeholder="New York" required>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="state" class="form-label">State *</label>
                                            <input type="text" class="form-control modern-input" id="state" name="state" 
                                                   value="<?php echo set_value('state', $job->state); ?>" 
                                                   placeholder="NY" required>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="estimated_duration" class="form-label">Estimated Duration *</label>
                                            <select class="form-control modern-select" id="estimated_duration" name="estimated_duration" required>
                                                <option value="">Select duration</option>
                                                <option value="60" <?php echo set_select('estimated_duration', '60', $job->estimated_duration == 60); ?>>1 hour</option>
                                                <option value="90" <?php echo set_select('estimated_duration', '90', $job->estimated_duration == 90); ?>>1.5 hours</option>
                                                <option value="120" <?php echo set_select('estimated_duration', '120', $job->estimated_duration == 120); ?>>2 hours</option>
                                                <option value="180" <?php echo set_select('estimated_duration', '180', $job->estimated_duration == 180); ?>>3 hours</option>
                                                <option value="240" <?php echo set_select('estimated_duration', '240', $job->estimated_duration == 240); ?>>4 hours</option>
                                                <option value="300" <?php echo set_select('estimated_duration', '300', $job->estimated_duration == 300); ?>>5 hours</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="date_time" class="form-label">Date & Time *</label>
                                            <input type="datetime-local" class="form-control modern-input" id="date_time" name="date_time" 
                                                   value="<?php echo set_value('date_time', $datetime_value); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Job Details -->
                                <div class="form-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-clipboard-list me-2"></i>
                                        Job Details
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="rooms" class="form-label">Number of Rooms *</label>
                                            <select class="form-control modern-select" id="rooms" name="rooms" required>
                                                <option value="">Select rooms</option>
                                                <option value="1" <?php echo set_select('rooms', '1', in_array('1', $rooms_array)); ?>>1 Room</option>
                                                <option value="2" <?php echo set_select('rooms', '2', in_array('2', $rooms_array)); ?>>2 Rooms</option>
                                                <option value="3" <?php echo set_select('rooms', '3', in_array('3', $rooms_array)); ?>>3 Rooms</option>
                                                <option value="4" <?php echo set_select('rooms', '4', in_array('4', $rooms_array)); ?>>4 Rooms</option>
                                                <option value="5" <?php echo set_select('rooms', '5', in_array('5', $rooms_array)); ?>>5+ Rooms</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Additional Services</label>
                                            <div class="extras-selection-section">
                                                <?php 
                                                $extras_options = [
                                                    'windows' => 'Window Cleaning',
                                                    'appliances' => 'Appliance Cleaning',
                                                    'carpet' => 'Carpet Cleaning',
                                                    'deep_cleaning' => 'Deep Cleaning',
                                                    'refrigerator' => 'Refrigerator Cleaning',
                                                    'oven' => 'Oven Cleaning',
                                                    'cabinet_interior' => 'Cabinet Interior',
                                                    'light_fixtures' => 'Light Fixtures'
                                                ];
                                                
                                                foreach ($extras_options as $value => $label): 
                                                ?>
                                                    <div class="form-check modern-checkbox-inline">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="extras_<?php echo $value; ?>" 
                                                               name="extras[]" 
                                                               value="<?php echo $value; ?>"
                                                               <?php echo in_array($value, $extras_array) ? 'checked' : ''; ?>>
                                                        <label class="form-check-label" for="extras_<?php echo $value; ?>">
                                                            <span class="checkbox-icon">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                            <span class="checkbox-text"><?php echo $label; ?></span>
                                                        </label>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="form-text" id="extras-feedback">Select any additional services needed</div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="pets" name="pets" value="1" 
                                                       <?php echo set_checkbox('pets', '1', $job->pets == 1); ?>>
                                                <label class="form-check-label" for="pets">
                                                    Pets present in home
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Special Instructions</label>
                                        <textarea class="form-control modern-input" id="notes" name="notes" 
                                                  rows="3" placeholder="Any special instructions or notes for the cleaner..."><?php echo set_value('notes', $job->special_instructions); ?></textarea>
                                        <div class="form-text">Optional: Any specific requirements or notes</div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-modern btn-warning w-100">
                                        <i class="fas fa-save me-2"></i>
                                        Update Job
                                    </button>
                                    <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-modern btn-secondary w-100 mt-2">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        Back to Jobs
                                    </a>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<style>
/* Include all the same styles as job_create.php */
.modern-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    border: none;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.modern-card .card-header {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.modern-card .card-body {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e9ecef;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.modern-input {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
}

.modern-input:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    background: white;
}

.modern-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
}

.modern-select:focus {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    background: white;
}

/* Extras Selection Styling */
.extras-selection-section {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0.5rem !important;
    padding: 0.5rem 0 !important;
    align-items: center !important;
    flex-direction: row !important;
}

.extras-selection-section .form-check {
    display: inline-block !important;
    margin: 0 !important;
    padding: 0 !important;
}

.extras-selection-section .form-check-input {
    position: absolute !important;
    opacity: 0 !important;
}

.modern-checkbox-inline {
    position: relative;
    margin: 0 !important;
    flex-shrink: 0;
    display: inline-block !important;
}

.modern-checkbox-inline .form-check-label {
    display: inline-flex !important;
    align-items: center !important;
    cursor: pointer;
    padding: 0.5rem 0.75rem !important;
    background: white !important;
    border: 2px solid #e9ecef !important;
    border-radius: 20px !important;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-height: 36px !important;
    white-space: nowrap !important;
    font-size: 0.85rem !important;
    width: auto !important;
    margin: 0 !important;
    flex-direction: row !important;
}

.modern-checkbox-inline .form-check-label:hover {
    border-color: #ffc107;
    background: rgba(255, 193, 7, 0.1) !important;
    transform: translateY(-1px);
}

.modern-checkbox-inline .form-check-input:checked + .form-check-label {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%) !important;
    border-color: #ff8c00 !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
}

.checkbox-icon {
    margin-right: 0.5rem;
    font-size: 0.8rem;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.modern-checkbox-inline .form-check-input:checked + .form-check-label .checkbox-icon {
    opacity: 1;
}

.checkbox-text {
    font-weight: 500;
}

.btn-modern {
    background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
    border: none;
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.4);
    background: linear-gradient(135deg, #ffb300 0%, #ff7f00 100%);
    color: white;
}

.btn-secondary-modern {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    border: none;
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
    color: white;
}

.btn-secondary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
    background: linear-gradient(135deg, #5a6268 0%, #343a40 100%);
    color: white;
}

.form-actions {
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

/* Responsive Design */
@media (max-width: 768px) {
    .extras-selection-section {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    
    .modern-checkbox-inline .form-check-label {
        justify-content: center !important;
        text-align: center !important;
    }
}
</style>

<script>
$(document).ready(function() {
    const form = document.getElementById('jobEditForm');
    const inputs = form.querySelectorAll('input, select, textarea');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Extras checkboxes functionality
    const extrasCheckboxes = document.querySelectorAll('input[name="extras[]"]');
    const extrasFeedback = document.getElementById('extras-feedback');
    
    function updateExtrasFeedback() {
        const checked = document.querySelectorAll('input[name="extras[]"]:checked');
        if (checked.length > 0) {
            extrasFeedback.textContent = `${checked.length} service(s) selected`;
            extrasFeedback.style.color = '#28a745';
        } else {
            extrasFeedback.textContent = 'Select any additional services needed';
            extrasFeedback.style.color = '#6c757d';
        }
    }
    
    extrasCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateExtrasFeedback();
            
            // Add animation
            const label = this.nextElementSibling;
            if (this.checked) {
                label.style.transform = 'scale(1.05)';
                setTimeout(() => {
                    label.style.transform = 'scale(1)';
                }, 200);
            }
        });
    });
    
    // Initial feedback update
    updateExtrasFeedback();
    
    // Form submission
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating Job...';
        
        // Let the form submit naturally
    });
});
</script>
