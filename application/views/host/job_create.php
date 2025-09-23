<?php
// time_ago() function is already declared in modern_header.php
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Job Creation Form -->
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-plus-circle text-primary me-2"></i>
                                Create New Cleaning Job
                            </h5>
                        </div>
                        <div class="card-body">
                            
                            <?php if (validation_errors() || $this->session->flashdata('text')): ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('type') ?: 'danger'; ?> alert-dismissible fade show" role="alert">
                                    <?php echo $this->session->flashdata('text') ?: validation_errors(); ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo base_url('host/process_create_job'); ?>" method="post" id="jobCreateForm">
                                
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
                                                   value="<?php echo set_value('title'); ?>" 
                                                   placeholder="e.g., Deep cleaning for 3-bedroom apartment" required>
                                            <div class="form-text">Be specific and descriptive</div>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="suggested_price" class="form-label">Suggested Price ($) *</label>
                                            <input type="number" class="form-control modern-input" id="suggested_price" name="suggested_price" 
                                                   value="<?php echo set_value('suggested_price'); ?>" 
                                                   min="1" step="0.01" placeholder="0.00" required>
                                            <div class="form-text">Cleaners can counter-offer</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Job Description *</label>
                                        <textarea class="form-control modern-input" id="description" name="description" 
                                                  rows="4" placeholder="Describe what needs to be cleaned, any special requirements, etc." required><?php echo set_value('description'); ?></textarea>
                                        <div class="form-text">Minimum 20 characters</div>
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
                                                   value="<?php echo set_value('address'); ?>" 
                                                   placeholder="Enter the complete address" required>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="city" class="form-label">City *</label>
                                            <input type="text" class="form-control modern-input" id="city" name="city" 
                                                   value="<?php echo set_value('city'); ?>" 
                                                   placeholder="New York" required>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="state" class="form-label">State *</label>
                                            <input type="text" class="form-control modern-input" id="state" name="state" 
                                                   value="<?php echo set_value('state'); ?>" 
                                                   placeholder="NY" required>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="estimated_duration" class="form-label">Estimated Duration *</label>
                                            <select class="form-control modern-select" id="estimated_duration" name="estimated_duration" required>
                                                <option value="">Select duration</option>
                                                <option value="60" <?php echo set_select('estimated_duration', '60'); ?>>1 hour</option>
                                                <option value="90" <?php echo set_select('estimated_duration', '90'); ?>>1.5 hours</option>
                                                <option value="120" <?php echo set_select('estimated_duration', '120'); ?>>2 hours</option>
                                                <option value="180" <?php echo set_select('estimated_duration', '180'); ?>>3 hours</option>
                                                <option value="240" <?php echo set_select('estimated_duration', '240'); ?>>4 hours</option>
                                                <option value="300" <?php echo set_select('estimated_duration', '300'); ?>>5 hours</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="date_time" class="form-label">Date & Time *</label>
                                            <input type="datetime-local" class="form-control modern-input" id="date_time" name="date_time" 
                                                   value="<?php echo set_value('date_time'); ?>" required>
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
                                                <option value="1" <?php echo set_select('rooms', '1'); ?>>1 Room</option>
                                                <option value="2" <?php echo set_select('rooms', '2'); ?>>2 Rooms</option>
                                                <option value="3" <?php echo set_select('rooms', '3'); ?>>3 Rooms</option>
                                                <option value="4" <?php echo set_select('rooms', '4'); ?>>4 Rooms</option>
                                                <option value="5" <?php echo set_select('rooms', '5'); ?>>5+ Rooms</option>
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
                                                
                                                $selected_extras = $this->input->post('extras');
                                                if (!is_array($selected_extras)) {
                                                    $selected_extras = [];
                                                }
                                                
                                                foreach ($extras_options as $value => $label): 
                                                ?>
                                                    <div class="form-check modern-checkbox-inline">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="extras_<?php echo $value; ?>" 
                                                               name="extras[]" 
                                                               value="<?php echo $value; ?>"
                                                               <?php echo in_array($value, $selected_extras) ? 'checked' : ''; ?>>
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
                                            <label class="form-label">Pets Present</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="pets" name="pets" value="1" 
                                                       <?php echo set_checkbox('pets', '1'); ?>>
                                                <label class="form-check-label" for="pets">
                                                    Yes, there are pets in the home
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Notes -->
                                <div class="form-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-sticky-note me-2"></i>
                                        Additional Notes
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Special Instructions</label>
                                        <textarea class="form-control modern-input" id="notes" name="notes" 
                                                  rows="3" placeholder="Any special instructions, access codes, parking info, etc."><?php echo set_value('notes'); ?></textarea>
                                        <div class="form-text">Optional: Any additional information for cleaners</div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="<?php echo base_url('host'); ?>" class="btn btn-modern btn-secondary w-100">
                                                <i class="fas fa-arrow-left me-2"></i>
                                                Back to Dashboard
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-modern btn-primary w-100">
                                                <i class="fas fa-plus-circle me-2"></i>
                                                Create Job
                                            </button>
                                        </div>
                                    </div>
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
/* Modern Card Styles */
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.modern-card .card-body {
    padding: 2rem;
}

/* Form Section Styles */
.form-section {
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.section-title {
    color: #495057;
    font-weight: 600;
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #667eea;
    display: inline-block;
}

/* Form Input Styles */
.modern-input {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
}

.modern-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
    transform: translateY(-2px);
}

.modern-input:hover {
    border-color: #adb5bd;
}

/* Modern Select Styles */
.modern-select {
    border: 2px solid #e9ecef;
    border-radius: 15px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: rgba(255,255,255,0.9);
    min-height: 50px;
    height: auto;
}

.modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background: white;
    transform: translateY(-2px);
}

.modern-select:hover {
    border-color: #adb5bd;
}

/* Select Options Styling */
.modern-select option {
    background-color: white;
    color: #333;
    padding: 8px 12px;
    font-size: 0.95rem;
    border: none;
    border-bottom: 1px solid #f0f0f0;
}

.modern-select option:hover {
    background-color: #f8f9fa;
    color: #667eea;
}

.modern-select option:checked {
    background-color: #667eea;
    color: white;
}

/* Modern Inline Checkbox Styling - Override Bootstrap */
.extras-selection-section {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0.5rem !important;
    padding: 0.5rem 0 !important;
    align-items: center !important;
    flex-direction: row !important;
}

/* Override Bootstrap form-check styling */
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

.modern-checkbox-inline .form-check-input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
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
    border-color: #667eea;
    background: #f8f9ff;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
}

.checkbox-icon {
    margin-right: 0.5rem;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
    border-radius: 3px;
    color: transparent;
    transition: all 0.3s ease;
    font-size: 0.8rem;
    flex-shrink: 0;
}

.checkbox-text {
    font-weight: 500;
    color: #495057;
    transition: color 0.3s ease;
    font-size: 0.85rem;
    line-height: 1.2;
}

/* Checked state */
.modern-checkbox-inline .form-check-input:checked ~ .form-check-label {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.25);
}

.modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-icon {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    transform: scale(1.1);
}

.modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-text {
    color: white;
    font-weight: 600;
}

/* Focus state */
.modern-checkbox-inline .form-check-input:focus ~ .form-check-label {
    outline: 2px solid #667eea;
    outline-offset: 2px;
}

/* Ripple effect */
.modern-checkbox-inline .form-check-label::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.3s ease, height 0.3s ease;
}

.modern-checkbox-inline .form-check-input:checked ~ .form-check-label::before {
    width: 100%;
    height: 100%;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .extras-selection-section {
        gap: 0.4rem;
    }
    
    .modern-checkbox-inline .form-check-label {
        padding: 0.4rem 0.6rem;
        min-height: 32px;
        font-size: 0.8rem;
    }
    
    .checkbox-icon {
        margin-right: 0.4rem;
        width: 14px;
        height: 14px;
        font-size: 0.7rem;
    }
    
    .checkbox-text {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .extras-selection-section {
        gap: 0.3rem;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .modern-checkbox-inline {
        width: 100%;
    }
    
    .modern-checkbox-inline .form-check-label {
        width: 100%;
        justify-content: flex-start;
        padding: 0.5rem 0.75rem;
        min-height: 40px;
    }
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* Checkbox Styles */
.form-check-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-top: 0.125rem;
    border-radius: 0.375rem;
    border: 2px solid #e9ecef;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.form-check-label {
    font-weight: 500;
    color: #495057;
    margin-left: 0.5rem;
}

/* Button Styles */
.btn-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}

.btn-modern.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern.btn-secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 5px 15px rgba(240, 147, 251, 0.3);
}

.btn-modern.btn-secondary:hover {
    background: linear-gradient(135deg, #e085f1 0%, #f04d5c 100%);
    box-shadow: 0 8px 25px rgba(240, 147, 251, 0.4);
}

/* Alert Styles */
.alert {
    border: none;
    border-radius: 15px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%);
    border-left: 4px solid #43e97b;
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, rgba(240, 147, 251, 0.1) 0%, rgba(245, 87, 108, 0.1) 100%);
    border-left: 4px solid #f5576c;
    color: #721c24;
}

.alert-info {
    background: linear-gradient(135deg, rgba(79, 172, 254, 0.1) 0%, rgba(0, 242, 254, 0.1) 100%);
    border-left: 4px solid #4facfe;
    color: #0c5460;
}

/* Form Actions */
.form-actions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .modern-card .card-body {
        padding: 1.5rem;
    }
    
    .form-section {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
    }
    
    .btn-modern {
        padding: 0.5rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .modern-input {
        padding: 0.5rem 0.75rem;
    }
}

/* Loading State */
.btn-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

/* Form Validation */
.is-invalid {
    border-color: #f5576c !important;
    box-shadow: 0 0 0 0.2rem rgba(245, 87, 108, 0.25) !important;
}

.is-valid {
    border-color: #43e97b !important;
    box-shadow: 0 0 0 0.2rem rgba(67, 233, 123, 0.25) !important;
}

.invalid-feedback {
    color: #f5576c;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.valid-feedback {
    color: #43e97b;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and enhancement
    const form = document.getElementById('jobCreateForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Checkbox enhancement for additional services
    const extrasCheckboxes = document.querySelectorAll('input[name="extras[]"]');
    const extrasFeedback = document.getElementById('extras-feedback');
    const extrasContainer = document.querySelector('.extras-selection-section');
    
    if (extrasCheckboxes.length > 0) {
        function updateExtrasFeedback() {
            const checkedBoxes = Array.from(extrasCheckboxes).filter(cb => cb.checked);
            const selectedServices = checkedBoxes.map(cb => {
                const label = cb.nextElementSibling;
                const textSpan = label.querySelector('.checkbox-text');
                return textSpan ? textSpan.textContent : label.textContent;
            });
            
            if (checkedBoxes.length > 0) {
                extrasFeedback.innerHTML = `<strong>${checkedBoxes.length} service(s) selected:</strong> ${selectedServices.join(', ')}`;
                extrasFeedback.style.color = '#667eea';
                extrasFeedback.style.fontWeight = '500';
            } else {
                extrasFeedback.innerHTML = 'Select any additional services needed';
                extrasFeedback.style.color = '#6c757d';
                extrasFeedback.style.fontWeight = 'normal';
            }
        }
        
        // Add change event listeners to all checkboxes
        extrasCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateExtrasFeedback);
            
            // Add click animation
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.style.transform = 'scale(1.02)';
                    setTimeout(() => {
                        this.parentElement.style.transform = 'scale(1)';
                    }, 150);
                }
            });
        });
        
        // Initialize feedback
        updateExtrasFeedback();
        
        // Debug: Log checkbox events
        extrasCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                console.log('Checkbox changed:', this.value, this.checked);
                const checkedValues = Array.from(extrasCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                console.log('All checked values:', checkedValues);
            });
        });
    }
    
    // Real-time validation
    const inputs = form.querySelectorAll('.modern-input');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            validateField(this);
        });
        
        input.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                validateField(this);
            }
        });
    });
    
    // Form submission - simplified
    form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Job...';
        
        // Let the form submit naturally (don't prevent default)
        // The browser's native validation will handle required fields
    });
    
    function validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let message = '';
        
        // Remove existing validation classes
        field.classList.remove('is-valid', 'is-invalid');
        
        // Remove existing feedback
        const existingFeedback = field.parentNode.querySelector('.invalid-feedback, .valid-feedback');
        if (existingFeedback) {
            existingFeedback.remove();
        }
        
        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            message = 'This field is required.';
        }
        
        // Specific field validations
        if (value && field.name === 'title') {
            if (value.length < 5) {
                isValid = false;
                message = 'Title must be at least 5 characters long.';
            }
        }
        
        if (value && field.name === 'description') {
            if (value.length < 20) {
                isValid = false;
                message = 'Description must be at least 20 characters long.';
            }
        }
        
        if (value && field.name === 'suggested_price') {
            const price = parseFloat(value);
            if (price <= 0) {
                isValid = false;
                message = 'Price must be greater than $0.';
            }
        }
        
        if (value && field.name === 'address') {
            if (value.length < 10) {
                isValid = false;
                message = 'Please enter a complete address.';
            }
        }
        
        // Apply validation result
        if (isValid && value) {
            field.classList.add('is-valid');
        } else if (!isValid) {
            field.classList.add('is-invalid');
            
            // Add feedback message
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = message;
            field.parentNode.appendChild(feedback);
        }
        
        return isValid;
    }
    
    // Price input formatting
    const priceInput = document.getElementById('suggested_price');
    priceInput.addEventListener('input', function() {
        let value = this.value;
        if (value && !isNaN(value)) {
            // Allow only 2 decimal places
            if (value.includes('.')) {
                const parts = value.split('.');
                if (parts[1] && parts[1].length > 2) {
                    this.value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            }
        }
    });
    
    // Date/time input validation
    const dateTimeInput = document.getElementById('date_time');
    dateTimeInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const now = new Date();
        
        if (selectedDate <= now) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Please select a future date and time.';
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        }
    });
});
</script>
