<?php
// time_ago() helper function
if (!function_exists('time_ago')) {
    function time_ago($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        return floor($time/31536000) . ' years ago';
    }
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Job Edit Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body text-center py-4">
                            <h2 class="mb-3">
                                <i class="fas fa-edit text-warning me-2"></i>
                                Edit Job - <?php echo htmlspecialchars($job->title); ?>
                            </h2>
                            <p class="text-muted mb-0">Modify job details and settings</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Edit Form -->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9 job-edit-container">
                    <div class="modern-card">
                        <div class="card-body">
                            <?php if (validation_errors()): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <?php echo validation_errors(); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->session->flashdata('text')): ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('type'); ?>">
                                    <i class="fas fa-<?php echo $this->session->flashdata('type') == 'success' ? 'check-circle' : 'exclamation-triangle'; ?> me-2"></i>
                                    <?php echo $this->session->flashdata('text'); ?>
                                </div>
                            <?php endif; ?>

                            <form action="<?php echo base_url('admin/process_edit_job/' . $job->id); ?>" method="POST" id="jobEditForm">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                
                                <!-- Basic Information -->
                                <div class="section-card mb-4">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Basic Information
                                        </h5>
                                    </div>
                                    <div class="section-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="title" class="form-label">Job Title *</label>
                                                <input type="text" class="form-control modern-input" id="title" name="title" 
                                                       value="<?php echo set_value('title', $job->title); ?>" 
                                                       placeholder="e.g., Deep Clean Apartment" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="status" class="form-label">Status *</label>
                                                <select class="form-control modern-select" id="status" name="status" required>
                                                    <option value="open" <?php echo set_select('status', 'open', $job->status == 'open'); ?>>Open</option>
                                                    <option value="active" <?php echo set_select('status', 'active', $job->status == 'active'); ?>>Active</option>
                                                    <option value="assigned" <?php echo set_select('status', 'assigned', $job->status == 'assigned'); ?>>Assigned</option>
                                                    <option value="completed" <?php echo set_select('status', 'completed', $job->status == 'completed'); ?>>Completed</option>
                                                    <option value="cancelled" <?php echo set_select('status', 'cancelled', $job->status == 'cancelled'); ?>>Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="description" class="form-label">Description *</label>
                                                <textarea class="form-control modern-input" id="description" name="description" 
                                                          rows="4" placeholder="Describe the cleaning job in detail..." required><?php echo set_value('description', $job->description); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location Information -->
                                <div class="section-card mb-4">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-map-marker-alt text-success me-2"></i>
                                            Location Information
                                        </h5>
                                    </div>
                                    <div class="section-body">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="address" class="form-label">Address *</label>
                                                <input type="text" class="form-control modern-input" id="address" name="address" 
                                                       value="<?php echo set_value('address', $job->address); ?>" 
                                                       placeholder="123 Main Street, Apt 4B" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">City *</label>
                                                <input type="text" class="form-control modern-input" id="city" name="city" 
                                                       value="<?php echo set_value('city', $job->city); ?>" 
                                                       placeholder="New York" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="state" class="form-label">State *</label>
                                                <input type="text" class="form-control modern-input" id="state" name="state" 
                                                       value="<?php echo set_value('state', $job->state); ?>" 
                                                       placeholder="NY" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Schedule & Pricing -->
                                <div class="section-card mb-4">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-calendar-alt text-info me-2"></i>
                                            Schedule & Pricing
                                        </h5>
                                    </div>
                                    <div class="section-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="scheduled_date" class="form-label">Scheduled Date *</label>
                                                <input type="date" class="form-control modern-input" id="scheduled_date" name="scheduled_date" 
                                                       value="<?php echo set_value('scheduled_date', $job->scheduled_date); ?>" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="scheduled_time" class="form-label">Scheduled Time *</label>
                                                <input type="time" class="form-control modern-input" id="scheduled_time" name="scheduled_time" 
                                                       value="<?php echo set_value('scheduled_time', $job->scheduled_time); ?>" required>
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
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="suggested_price" class="form-label">Suggested Price *</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="number" class="form-control modern-input" id="suggested_price" name="suggested_price" 
                                                           value="<?php echo set_value('suggested_price', $job->suggested_price); ?>" 
                                                           step="0.01" min="0" placeholder="0.00" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal & Additional Information -->
                                <div class="section-card mb-4">
                                    <div class="section-header">
                                        <h5 class="section-title">
                                            <i class="fas fa-home text-warning me-2"></i>
                                            Personal & Additional Information
                                        </h5>
                                    </div>
                                    <div class="section-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="rooms" class="form-label">Number of Rooms *</label>
                                                <select class="form-control modern-select" id="rooms" name="rooms" required>
                                                    <option value="">Select rooms</option>
                                                    <option value="1" <?php echo set_select('rooms', '1', in_array('1', json_decode($job->rooms, true) ?: [])); ?>>1 Room</option>
                                                    <option value="2" <?php echo set_select('rooms', '2', in_array('2', json_decode($job->rooms, true) ?: [])); ?>>2 Rooms</option>
                                                    <option value="3" <?php echo set_select('rooms', '3', in_array('3', json_decode($job->rooms, true) ?: [])); ?>>3 Rooms</option>
                                                    <option value="4" <?php echo set_select('rooms', '4', in_array('4', json_decode($job->rooms, true) ?: [])); ?>>4 Rooms</option>
                                                    <option value="5" <?php echo set_select('rooms', '5', in_array('5', json_decode($job->rooms, true) ?: [])); ?>>5+ Rooms</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Pets Present</label>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="pets" name="pets" 
                                                           <?php echo set_checkbox('pets', '1', $job->pets == 1); ?>>
                                                    <label class="form-check-label" for="pets">
                                                        <i class="fas fa-paw me-1"></i>Pets are present in the home
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
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
                                                    
                                                    $selected_extras = json_decode($job->extras ?? '[]', true);
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
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="special_instructions" class="form-label">Special Instructions</label>
                                                <textarea class="form-control modern-input" id="special_instructions" name="special_instructions" 
                                                          rows="3" placeholder="Any special instructions or notes for the cleaner..."><?php echo set_value('special_instructions', $job->special_instructions); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Form Actions -->
                                <div class="form-actions">
                                    <a href="<?php echo base_url('admin/view_job/' . $job->id); ?>" 
                                       class="btn btn-modern btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-modern btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Job
                                    </button>
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
/* Content Layout */
.content {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
}

.container-fluid {
    max-width: 95% !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 20px !important;
}

/* Desktop Layout - Better Proportions */
@media (min-width: 1200px) {
    .container-fluid {
        max-width: 92% !important;
        padding: 0 25px !important;
    }
}

@media (min-width: 1400px) {
    .container-fluid {
        max-width: 90% !important;
        padding: 0 30px !important;
    }
}

/* Responsive Layout */
@media (max-width: 991.98px) {
    .container-fluid {
        max-width: 98% !important;
        padding: 0 15px !important;
    }
}

@media (max-width: 767.98px) {
    .container-fluid {
        max-width: 100% !important;
        padding: 0 10px !important;
    }
}

/* Job Edit Container */
.job-edit-container {
    max-width: 1100px;
}

@media (min-width: 1200px) {
    .job-edit-container {
        max-width: 1100px;
    }
}

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

/* Section Cards */
.section-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.9) 100%);
    border: 1px solid rgba(102, 126, 234, 0.1);
    border-radius: 15px;
    padding: 0;
    margin-bottom: 1.5rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.section-card:hover {
    border-color: rgba(102, 126, 234, 0.2);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.1);
}

.section-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(102, 126, 234, 0.1);
}

.section-title {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
}

.section-body {
    padding: 1.5rem;
}

/* Form Styles */
.modern-input, .modern-select {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    transition: all 0.3s ease;
    background-color: white;
    min-height: 45px;
    font-size: 0.9rem;
    line-height: 1.4;
}

.modern-input:focus, .modern-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    background-color: white;
}

/* Select Dropdown Specific Styles */
.modern-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 1rem;
    padding-right: 2.5rem;
}

.modern-select option {
    background-color: white;
    color: #333;
    padding: 0.5rem 0.75rem;
    font-size: 0.9rem;
    line-height: 1.4;
    border: none;
    height: auto;
    min-height: 35px;
}

.modern-select option:hover {
    background-color: #f8f9fa;
    color: #667eea;
}

.modern-select option:checked {
    background-color: #667eea;
    color: white;
}

/* Ensure dropdown options are visible */
.modern-select:focus option {
    background-color: white;
    color: #333;
    z-index: 9999;
}

/* Fix dropdown container overflow issues */
.modern-card .card-body {
    overflow: visible;
}

.modern-card {
    overflow: visible;
}

/* Ensure select dropdowns are not clipped */
select.modern-select {
    z-index: 10;
    position: relative;
}

select.modern-select:focus {
    z-index: 1000;
}

/* Additional dropdown styling for better visibility */
.modern-select option {
    display: block;
    white-space: nowrap;
    overflow: visible;
    text-overflow: initial;
    max-width: none;
    width: auto;
    min-width: 100%;
}

/* Override any Bootstrap or other framework conflicts */
select.form-control.modern-select {
    height: auto !important;
    min-height: 45px !important;
    padding: 0.75rem 2.5rem 0.75rem 1rem !important;
    font-size: 0.9rem !important;
    line-height: 1.4 !important;
    background-color: white !important;
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
}

select.form-control.modern-select:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25) !important;
    background-color: white !important;
}

/* Ensure the dropdown options are fully visible */
select.form-control.modern-select option {
    background-color: white !important;
    color: #333 !important;
    padding: 0.5rem 0.75rem !important;
    font-size: 0.9rem !important;
    line-height: 1.4 !important;
    height: auto !important;
    min-height: 35px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
}

/* Extras Selection */
.extras-selection-section {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 0.5rem !important;
    padding: 0.5rem 0 !important;
    align-items: center !important;
    flex-direction: row !important;
}

.extras-selection-section .form-check {
    margin: 0 !important;
    padding: 0 !important;
}

.modern-checkbox-inline .form-check-label {
    display: inline-flex !important;
    padding: 0.5rem 0.75rem !important;
    border-radius: 20px !important;
    min-height: 36px !important;
    white-space: nowrap !important;
    font-size: 0.85rem !important;
    width: auto !important;
    margin: 0 !important;
    flex-direction: row !important;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px solid transparent;
    cursor: pointer;
    transition: all 0.3s ease;
    align-items: center;
}

.modern-checkbox-inline .form-check-label:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-color: #667eea;
    transform: translateY(-1px);
}

.modern-checkbox-inline .form-check-input {
    display: none;
}

.modern-checkbox-inline .form-check-input:checked + .form-check-label {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.checkbox-icon {
    margin-right: 0.5rem;
    font-size: 0.8rem;
    opacity: 0.7;
}

.checkbox-text {
    font-weight: 500;
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
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    color: white;
}

.btn-modern.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
}

.btn-modern.btn-secondary:hover {
    background: linear-gradient(135deg, #5a6268 0%, #3d4449 100%);
    box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
    color: white;
}

/* Button Positioning and Layout Fixes */
.btn-modern {
    white-space: nowrap;
    min-width: 140px;
}

/* Form Actions Container */
.form-actions {
    margin-top: 2rem;
    padding: 1.5rem 0;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
    background: rgba(248, 249, 250, 0.3);
    border-radius: 0 0 15px 15px;
    margin-left: -1.5rem;
    margin-right: -1.5rem;
    margin-bottom: -1.5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
}

/* Responsive Button Layout */
@media (max-width: 768px) {
    .form-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn-modern {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .btn-modern:last-child {
        margin-bottom: 0;
    }
}

/* Ensure buttons stay within container */
.modern-card .card-body {
    position: relative;
    overflow: visible;
}

/* Button container specific styling */
.d-flex.justify-content-between {
    flex-wrap: wrap;
    gap: 1.5rem;
    align-items: center;
    width: 100%;
    max-width: 100%;
}

/* Ensure buttons don't overflow */
.form-actions .btn-modern {
    flex-shrink: 0;
    max-width: 200px;
}

@media (max-width: 576px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        align-items: stretch;
    }
    
    .d-flex.justify-content-between .btn-modern {
        width: 100%;
        margin-bottom: 0.5rem;
        max-width: 100%;
    }
    
    .form-actions {
        margin-left: -1rem;
        margin-right: -1rem;
        padding: 1rem;
    }
}

/* Form Switch */
.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

/* Alert Styles */
.alert {
    border: none;
    border-radius: 10px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

/* Input Group */
.input-group-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: 2px solid #667eea;
    border-right: none;
    font-weight: 600;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-left: none;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

/* Responsive Design */
@media (max-width: 768px) {
    .extras-selection-section {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    
    .modern-checkbox-inline .form-check-label {
        justify-content: center;
        text-align: center;
    }
    
    .job-edit-container {
        max-width: 100%;
    }
}
</style>

<script>
$(document).ready(function() {
    // Extras selection feedback
    const extrasCheckboxes = document.querySelectorAll('input[name="extras[]"]');
    const extrasFeedback = document.getElementById('extras-feedback');
    
    function updateExtrasFeedback() {
        const selectedExtras = Array.from(extrasCheckboxes).filter(cb => cb.checked);
        const count = selectedExtras.length;
        
        if (count === 0) {
            extrasFeedback.textContent = 'Select any additional services needed';
            extrasFeedback.className = 'form-text';
        } else if (count === 1) {
            extrasFeedback.textContent = '1 additional service selected';
            extrasFeedback.className = 'form-text text-success';
        } else {
            extrasFeedback.textContent = count + ' additional services selected';
            extrasFeedback.className = 'form-text text-success';
        }
    }
    
    // Add event listeners to checkboxes
    extrasCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateExtrasFeedback);
    });
    
    // Initial feedback update
    updateExtrasFeedback();
    
    // Form submission with loading state
    $('#jobEditForm').on('submit', function() {
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Updating...');
    });
});
</script>
