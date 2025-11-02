<?php
// time_ago() function is already declared in modern_header.php

// Debug: Check if pricing_params is set
if (!isset($pricing_params)) {
    $pricing_params = [
        'base_charge' => 25.00,
        'tax_percent' => 10,
        'app_percent' => 15
    ];
}

// Debug output (remove after testing)
if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    echo '<!-- Pricing Params: ' . print_r($pricing_params, true) . ' -->';
}

// Parse job data for form pre-population
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
?>

<div class="container-fluid">
            
            <!-- Job Edit Form -->
            <div class="row justify-content-center">
                <div class="col-lg-10 col-xl-9 job-create-container">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-edit text-warning me-2"></i>
                                Edit Cleaning Job
                            </h5>
                        </div>
                        <div class="card-body">
                            
                            <?php if (validation_errors() || $this->session->flashdata('text')): ?>
                                <div class="alert alert-<?php echo $this->session->flashdata('type') ?: 'danger'; ?>" role="alert">
                                    <?php echo $this->session->flashdata('text') ?: validation_errors(); ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo base_url('host/process_edit_job/' . $job->id); ?>" method="post" id="jobCreateForm">
                                
                                <!-- Property Type Selection (First) -->
                                <div class="form-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-home me-2"></i>
                                        Property Type
                                    </h6>
                                    
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Select Property Type *</label>
                                            <div class="property-type-selection">
                                                <div class="form-check property-type-option">
                                                    <input class="form-check-input" type="radio" name="property_type" id="property_type_residential" value="residential" <?php echo (!empty($job->property_type) && $job->property_type == 'str') ? '' : 'checked'; ?> required>
                                                    <label class="form-check-label" for="property_type_residential">
                                                        <div class="property-icon"><i class="fas fa-home"></i></div>
                                                        <div class="property-info">
                                                            <div class="property-title">Residential Property</div>
                                                            <div class="property-desc">Standard home or apartment cleaning</div>
                                                        </div>
                                                    </label>
                                                </div>
                                                
                                                <div class="form-check property-type-option">
                                                    <input class="form-check-input" type="radio" name="property_type" id="property_type_str" value="str" <?php echo (!empty($job->property_type) && $job->property_type == 'str') ? 'checked' : ''; ?> required>
                                                    <label class="form-check-label" for="property_type_str">
                                                        <div class="property-icon"><i class="fas fa-key"></i></div>
                                                        <div class="property-info">
                                                            <div class="property-title">Short Term Rental (STR)</div>
                                                            <div class="property-desc">Airbnb, VRBO, or similar rental property</div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- STR Requirements Alert - Permanent when STR is selected -->
                                    <div id="str-requirements-alert" class="str-alert-box" style="display: <?php echo (!empty($job->property_type) && $job->property_type == 'str') ? 'block' : 'none'; ?>;">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1 text-warning"></i>
                                            <div class="flex-grow-1">
                                                <h5 class="alert-heading mb-2">Short Term Rental Requirements</h5>
                                                <p class="mb-2"><strong>Important:</strong> For STR properties, you are responsible for providing consumables needed for the next occupant.</p>
                                                <ul class="mb-3">
                                                    <li>Toilet paper and paper towels</li>
                                                    <li>Soap, shampoo, and toiletries</li>
                                                    <li>Dish soap and cleaning supplies</li>
                                                    <li>Trash bags</li>
                                                    <li>Linens and towels (fresh set)</li>
                                                </ul>
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input" type="checkbox" id="str_requirements_confirmed" name="str_requirements_confirmed" value="1" <?php echo (!empty($job->property_type) && $job->property_type == 'str') ? 'checked required' : ''; ?>>
                                                    <label class="form-check-label" for="str_requirements_confirmed">
                                                        <strong>I confirm that I have read and understand all STR requirements</strong>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
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
                                        
                                        <div class="col-md-4 mb-3" id="price-input-container">
                                            <label for="suggested_price" class="form-label">Suggested Price ($) *</label>
                                            <input type="number" class="form-control modern-input" id="suggested_price" name="suggested_price" 
                                                   value="<?php echo set_value('suggested_price', $job->suggested_price); ?>" 
                                                   min="1" step="0.01" placeholder="0.00" required>
                                            <div class="form-text">
                                                Cleaners can counter-offer
                                                <span class="hover-breakdown-hint" style="display: none;">
                                                    <i class="fas fa-mouse-pointer ms-2"></i> Hover to see payout breakdown
                                                </span>
                                            </div>
                                            
                                            <!-- Pricing Breakdown Display - Shows on Hover -->
                                            <div id="pricing-breakdown" class="pricing-breakdown-box hover-breakdown" style="display: none;">
                                                <h6 class="breakdown-title">
                                                    <i class="fas fa-calculator me-2"></i>
                                                    Payment Breakdown
                                                </h6>
                                                <div class="breakdown-item">
                                                    <span class="breakdown-label">Suggested Price:</span>
                                                    <span class="breakdown-value" id="display-suggested-price">$0.00</span>
                                                </div>
                                                <div class="breakdown-item breakdown-charge">
                                                    <span class="breakdown-label">- Base Charge:</span>
                                                    <span class="breakdown-value" id="display-base-charge">$0.00</span>
                                                </div>
                                                <div class="breakdown-item breakdown-charge">
                                                    <span class="breakdown-label">- Tax (<span id="display-tax-percent">0</span>%):</span>
                                                    <span class="breakdown-value" id="display-tax-amount">$0.00</span>
                                                </div>
                                                <div class="breakdown-item breakdown-charge">
                                                    <span class="breakdown-label">- App Fee (<span id="display-app-percent">0</span>%):</span>
                                                    <span class="breakdown-value" id="display-app-amount">$0.00</span>
                                                </div>
                                                <div class="breakdown-item breakdown-total">
                                                    <span class="breakdown-label"><strong>Cleaner Payout:</strong></span>
                                                    <span class="breakdown-value" id="display-cleaner-payout"><strong>$0.00</strong></span>
                                                </div>
                                                <div class="breakdown-notice">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    This is the estimated payout the cleaner will receive
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Job Description *</label>
                                        <textarea class="form-control modern-input" id="description" name="description" 
                                                  rows="4" placeholder="Describe what needs to be cleaned, any special requirements, etc." required><?php echo set_value('description', $job->description); ?></textarea>
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
                                                   value="<?php echo set_value('address', $job->address); ?>" 
                                                   placeholder="Enter the complete address" required>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="state" class="form-label">State *</label>
                                            <select class="form-control modern-select" id="state" name="state" required>
                                                <option value="">Select state</option>
                                                <?php
                                                $states = [
                                                    'Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Chiapas', 
                                                    'Chihuahua', 'Ciudad de México', 'Coahuila', 'Colima', 'Durango', 
                                                    'Estado de México', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 
                                                    'Michoacán', 'Morelos', 'Nayarit', 'Nuevo León', 'Oaxaca', 
                                                    'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 
                                                    'Sonora', 'Tabasco', 'Tamaulipas', 'Tlaxcala', 'Veracruz', 
                                                    'Yucatán', 'Zacatecas'
                                                ];
                                                foreach ($states as $state) {
                                                    $selected = ($job->state == $state) ? 'selected' : '';
                                                    echo '<option value="' . $state . '" ' . $selected . '>' . $state . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="city" class="form-label">City *</label>
                                            <select class="form-control modern-select" id="city" name="city" required>
                                                <option value="">Select state first</option>
                                                <!-- Will be populated by JavaScript based on selected state -->
                                            </select>
                                            <input type="hidden" id="current_city" value="<?php echo htmlspecialchars($job->city); ?>">
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
                                            <label for="job_date" class="form-label">Date *</label>
                                            <input type="date" class="form-control modern-input" id="job_date" name="job_date" 
                                                   value="<?php echo set_value('job_date', $job->scheduled_date); ?>" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="job_time" class="form-label">Time *</label>
                                            <select class="form-control modern-select" id="job_time" name="job_time" required>
                                                <option value="">Select time</option>
                                                <?php
                                                // Get current time value (without seconds)
                                                $current_time = !empty($job->scheduled_time) ? substr($job->scheduled_time, 0, 5) : '';
                                                
                                                // Generate half-hour intervals
                                                for ($hour = 6; $hour < 24; $hour++) {
                                                    $time1 = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00';
                                                    $display1 = date('g:i A', strtotime($time1));
                                                    $selected1 = ($time1 == $current_time) ? 'selected' : '';
                                                    echo '<option value="' . $time1 . '" ' . $selected1 . '>' . $display1 . '</option>';
                                                    
                                                    $time2 = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':30';
                                                    $display2 = date('g:i A', strtotime($time2));
                                                    $selected2 = ($time2 == $current_time) ? 'selected' : '';
                                                    echo '<option value="' . $time2 . '" ' . $selected2 . '>' . $display2 . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <div class="form-text">Available in 30-minute intervals</div>
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
                                                <?php
                                                // Get current room value (from array or direct)
                                                $current_rooms = is_array($rooms_array) && !empty($rooms_array) ? $rooms_array[0] : '';
                                                ?>
                                                <option value="1" <?php echo ($current_rooms == '1') ? 'selected' : ''; ?>>1 Room</option>
                                                <option value="2" <?php echo ($current_rooms == '2') ? 'selected' : ''; ?>>2 Rooms</option>
                                                <option value="3" <?php echo ($current_rooms == '3') ? 'selected' : ''; ?>>3 Rooms</option>
                                                <option value="4" <?php echo ($current_rooms == '4') ? 'selected' : ''; ?>>4 Rooms</option>
                                                <option value="5" <?php echo ($current_rooms == '5' || $current_rooms > 5) ? 'selected' : ''; ?>>5+ Rooms</option>
                                            </select>
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Pets Present</label>
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" id="pets" name="pets" value="1" 
                                                       <?php echo ($job->pets == 1 || $job->pets == '1') ? 'checked' : ''; ?>>
                                                <label class="form-check-label" for="pets">
                                                    Yes, there are pets in the home
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Additional Services Section -->
                                <div class="form-section" id="additional-services-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-plus-circle me-2"></i>
                                        Additional Services
                                    </h6>
                                    
                                    <?php 
                                                $selected_extras = $extras_array; // Use parsed extras from job
                                    ?>
                                    
                                    <!-- General Services -->
                                    <div class="services-group">
                                        <h6 class="services-group-title">
                                            <i class="fas fa-star me-2"></i>
                                            General Services
                                        </h6>
                                        <div class="extras-selection-section">
                                            <?php 
                                            $general_extras = [
                                                'deep_cleaning' => ['label' => 'Deep Cleaning', 'icon' => 'broom'],
                                                'windows' => ['label' => 'Window Cleaning', 'icon' => 'window-maximize'],
                                                'appliances' => ['label' => 'Appliance Cleaning', 'icon' => 'microchip'],
                                                'carpet' => ['label' => 'Carpet Cleaning', 'icon' => 'layer-group'],
                                                'oven' => ['label' => 'Oven Cleaning', 'icon' => 'fire'],
                                                'cabinet_interior' => ['label' => 'Cabinet Interior', 'icon' => 'toolbox'],
                                                'light_fixtures' => ['label' => 'Light Fixtures', 'icon' => 'lightbulb'],
                                                'baseboards' => ['label' => 'Baseboards', 'icon' => 'minus'],
                                                'inside_fridge' => ['label' => 'Inside Refrigerator', 'icon' => 'snowflake'],
                                                'bathroom_grout' => ['label' => 'Bathroom Grout Scrubbing', 'icon' => 'shower'],
                                                'doors_frames' => ['label' => 'Doors & Frames', 'icon' => 'door-open'],
                                                'walls' => ['label' => 'Wall Washing', 'icon' => 'paint-brush'],
                                                'ceiling_fans' => ['label' => 'Ceiling Fans', 'icon' => 'fan'],
                                                'vents' => ['label' => 'Vent Cleaning', 'icon' => 'wind'],
                                                'mirrors' => ['label' => 'Mirror Polish', 'icon' => 'glass-mirror'],
                                                'furniture_polish' => ['label' => 'Furniture Polish', 'icon' => 'chair'],
                                                'blinds' => ['label' => 'Blinds Cleaning', 'icon' => 'window-restore'],
                                                'trash_removal' => ['label' => 'Trash Removal', 'icon' => 'trash-alt'],
                                                'organization' => ['label' => 'Light Organization', 'icon' => 'boxes'],
                                                'pet_hair' => ['label' => 'Pet Hair Removal', 'icon' => 'paw'],
                                                'inside_cabinets' => ['label' => 'Inside Cabinets', 'icon' => 'archive'],
                                                'garage' => ['label' => 'Garage Sweep', 'icon' => 'warehouse'],
                                                'patio_balcony' => ['label' => 'Patio/Balcony', 'icon' => 'home'],
                                                'exterior_windows' => ['label' => 'Exterior Windows', 'icon' => 'window-frame']
                                            ];
                                            
                                            foreach ($general_extras as $value => $data): 
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
                                                        <span class="checkbox-text">
                                                            <i class="fas fa-<?php echo $data['icon']; ?> me-1"></i>
                                                            <?php echo $data['label']; ?>
                                                        </span>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                            </div>
                                        </div>
                                        
                                    <!-- STR-Specific Services -->
                                    <div class="services-group str-services" id="str-services-group" style="display: <?php echo (!empty($job->property_type) && $job->property_type == 'str') ? 'block' : 'none'; ?>;">
                                        <h6 class="services-group-title str-title">
                                            <i class="fas fa-home me-2"></i>
                                            Short Term Rental Requirements
                                        </h6>
                                        <div class="extras-selection-section">
                                            <?php 
                                            $str_extras = [
                                                'wash_linens' => ['label' => 'Wash All Linens & Towels', 'icon' => 'tshirt', 'required' => true],
                                                'check_dishes' => ['label' => 'Check & Wash Dishes', 'icon' => 'utensils', 'required' => true],
                                                'clean_refrigerator' => ['label' => 'Clean Refrigerator', 'icon' => 'snowflake', 'required' => true],
                                                'ensure_supplies' => ['label' => 'Ensure Consumables Stocked', 'icon' => 'shopping-bag', 'required' => true],
                                                'reset_beds' => ['label' => 'Make All Beds', 'icon' => 'bed', 'required' => true],
                                                'reset_kitchen' => ['label' => 'Reset Kitchen to Empty', 'icon' => 'utensils', 'required' => true],
                                                'check_amenities' => ['label' => 'Check All Amenities', 'icon' => 'clipboard-check', 'required' => false],
                                                'leave_goodies' => ['label' => 'Leave Welcome Goodies', 'icon' => 'candy-cane', 'required' => false],
                                                'reset_makeup' => ['label' => 'Reset Makeup Area', 'icon' => 'eye', 'required' => false],
                                                'reset_shower' => ['label' => 'Reset Shower Supplies', 'icon' => 'shower', 'required' => false],
                                                'reset_coffee' => ['label' => 'Reset Coffee Station', 'icon' => 'coffee', 'required' => false],
                                                'check_tv' => ['label' => 'Test TV & Electronics', 'icon' => 'tv', 'required' => false],
                                                'check_wifi' => ['label' => 'Verify WiFi Password', 'icon' => 'wifi', 'required' => false],
                                                'check_keys' => ['label' => 'Check All Keys Available', 'icon' => 'key', 'required' => false],
                                                'reset_pillows' => ['label' => 'Arrange Pillows', 'icon' => 'couch', 'required' => false],
                                                'reset_table' => ['label' => 'Set Dining Table', 'icon' => 'utensils', 'required' => false],
                                                'photo_documentation' => ['label' => 'Photo Documentation', 'icon' => 'camera', 'required' => false],
                                                'check_hvac' => ['label' => 'Check HVAC', 'icon' => 'thermometer-half', 'required' => false],
                                                'ensure_quiet' => ['label' => 'Ensure Quiet Hours Notice', 'icon' => 'volume-mute', 'required' => false],
                                                'check_smoke_detector' => ['label' => 'Test Smoke Detector', 'icon' => 'smoke', 'required' => false]
                                            ];
                                            
                                            foreach ($str_extras as $value => $data): 
                                            ?>
                                                <div class="form-check modern-checkbox-inline str-service">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="extras_<?php echo $value; ?>" 
                                                           name="extras[]" 
                                                           value="<?php echo $value; ?>"
                                                           <?php echo in_array($value, $selected_extras) ? 'checked' : ''; ?>>
                                                    <label class="form-check-label" for="extras_<?php echo $value; ?>">
                                                        <span class="checkbox-icon">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                        <span class="checkbox-text">
                                                            <i class="fas fa-<?php echo $data['icon']; ?> me-1"></i>
                                                            <?php echo $data['label']; ?>
                                                            <?php if ($data['required']): ?>
                                                                <span class="badge badge-sm badge-warning ms-1">Required</span>
                                                            <?php endif; ?>
                                                        </span>
                                                    </label>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- Selection Feedback -->
                                    <div class="form-text mt-3" id="extras-feedback">Select any additional services needed</div>
                                </div>
                                
                                <!-- Additional Notes -->
                                <div class="form-section">
                                    <h6 class="section-title">
                                        <i class="fas fa-sticky-note me-2"></i>
                                        Additional Notes
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label for="notes" class="form-label">Special Instructions / STR Requirements</label>
                                        <textarea class="form-control modern-input" id="notes" name="notes" 
                                                  rows="4" placeholder="Any special instructions, access codes, parking info, STR requirements, etc."><?php echo set_value('notes', $job->special_instructions); ?></textarea>
                                        <div class="form-text">Optional: For STR properties, include specific requirements here</div>
                                    </div>
                                </div>
                                
                                <!-- Form Actions -->
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-modern btn-secondary w-100">
                                                <i class="fas fa-arrow-left me-2"></i>
                                                Back to My Jobs
                                            </a>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" class="btn btn-modern btn-warning w-100">
                                                <i class="fas fa-save me-2"></i>
                                                Update Job
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

<style>
/* Make container wider */
.container-fluid {
    max-width: 95% !important;
    margin: 0 auto !important;
}

@media (min-width: 1200px) {
    .container-fluid {
        max-width: 97% !important;
    }
}

@media (min-width: 1400px) {
    .container-fluid {
        max-width: 98% !important;
    }
}


/* Form Container Enhancement */
.job-create-container {
    max-width: 1200px !important;
    width: 100% !important;
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

/* Services Groups Styles */
.services-group {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: rgba(102, 126, 234, 0.02);
    border-radius: 12px;
    border: 1px solid rgba(102, 126, 234, 0.1);
    transition: all 0.3s ease;
}

.services-group:hover {
    border-color: rgba(102, 126, 234, 0.3);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.08);
}

.services-group-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(102, 126, 234, 0.2);
}

.str-services {
    background: rgba(255, 193, 7, 0.05);
    border-color: rgba(255, 193, 7, 0.2);
    animation: slideDown 0.3s ease-out;
}

.str-services:hover {
    border-color: rgba(255, 193, 7, 0.4);
    box-shadow: 0 4px 12px rgba(255, 193, 7, 0.15);
}

.str-services .str-title {
    color: #ffc107;
    border-bottom-color: rgba(255, 193, 7, 0.3);
}

.str-service {
    padding: 0.75rem;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 8px;
    transition: all 0.2s ease;
}

.str-service:hover {
    background: rgba(255, 255, 255, 0.6);
    transform: translateX(5px);
}

/* Badge styles */
.badge-sm {
    font-size: 0.7rem;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-weight: 600;
}

.badge-warning {
    background-color: #ffc107;
    color: #000;
}

/* Services Grid Layout */
#additional-services-section .extras-selection-section {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
}

/* Override checked state for services in colored containers - HIGHER SPECIFICITY */
#additional-services-section .services-group .modern-checkbox-inline .form-check-input:checked ~ .form-check-label {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-color: #667eea !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4) !important;
}

#additional-services-section .services-group .modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-text {
    color: #ffffff !important;
    font-weight: 600 !important;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3) !important;
}

#additional-services-section .services-group .modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-text i {
    color: #ffffff !important;
}

#additional-services-section .services-group .modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-icon {
    background: rgba(255, 255, 255, 0.4) !important;
    color: #ffffff !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
}

/* STR services checked state - darker background for better contrast */
#additional-services-section .str-services .modern-checkbox-inline .form-check-input:checked ~ .form-check-label {
    background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%) !important;
    border-color: #f39c12 !important;
}

#additional-services-section .str-services .modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-text {
    color: #ffffff !important;
    font-weight: 600 !important;
    text-shadow: 0 1px 3px rgba(0, 0, 0, 0.4) !important;
}

#additional-services-section .str-services .modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-text i {
    color: #ffffff !important;
}

#additional-services-section .str-services .modern-checkbox-inline .form-check-input:checked ~ .form-check-label .checkbox-icon {
    background: rgba(255, 255, 255, 0.4) !important;
    color: #ffffff !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
}

/* Badge visibility in checked state */
#additional-services-section .services-group .modern-checkbox-inline .form-check-input:checked ~ .form-check-label .badge {
    background-color: rgba(255, 255, 255, 0.4) !important;
    color: #ffffff !important;
    border: 1px solid rgba(255, 255, 255, 0.6) !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) !important;
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

/* Property Type Selection Styles */
.property-type-selection {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.property-type-option {
    flex: 1;
    min-width: 280px;
    margin: 0;
}

.property-type-option .form-check-input {
    display: none;
}

.property-type-option .form-check-label {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    border: 3px solid #e9ecef;
    border-radius: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    background: white;
    margin: 0;
}

.property-type-option .form-check-label:hover {
    border-color: #667eea;
    background: #f8f9ff;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.15);
}

.property-type-option .form-check-input:checked ~ .form-check-label {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    box-shadow: 0 5px 20px rgba(102, 126, 234, 0.25);
}

.property-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    color: white;
    font-size: 1.5rem;
    margin-right: 1rem;
    flex-shrink: 0;
}

.property-type-option .form-check-input:checked ~ .form-check-label .property-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transform: scale(1.1);
}

.property-info {
    flex: 1;
}

.property-title {
    font-weight: 600;
    font-size: 1.1rem;
    color: #495057;
    margin-bottom: 0.25rem;
}

.property-type-option .form-check-input:checked ~ .form-check-label .property-title {
    color: #667eea;
}

.property-desc {
    font-size: 0.875rem;
    color: #6c757d;
}

.property-type-option .form-check-input:checked ~ .form-check-label .property-desc {
    color: #495057;
}

/* STR Requirements Alert - Permanent and Non-Dismissible */
.str-alert-box {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.1) 100%);
    border: 2px solid #ffc107;
    border-left: 5px solid #ffc107;
    border-radius: 15px;
    padding: 1.5rem;
    margin-top: 1rem;
    box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);
    position: relative;
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.str-alert-box .alert-heading {
    color: #856404;
    font-weight: 600;
    font-size: 1.25rem;
}

.str-alert-box ul {
    margin-left: 1.5rem;
    color: #856404;
}

.str-alert-box ul li {
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.str-alert-box p {
    color: #856404;
}

.str-alert-box .form-check-label {
    color: #856404;
    font-weight: 600;
}

/* No close button - permanent alert */
.str-alert-box::before {
    content: none;
}

/* Responsive adjustments for property type */
@media (max-width: 768px) {
    .property-type-selection {
        flex-direction: column;
    }
    
    .property-type-option {
        min-width: 100%;
    }
    
    .property-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
}

/* Pricing Breakdown Styles - Hover Display */
#price-input-container {
    position: relative;
}

.hover-breakdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1000;
    margin-top: 0.5rem;
    display: none;
    pointer-events: none;
    visibility: hidden;
    opacity: 0;
    animation: fadeInSlideDown 0.2s ease-out;
}

@keyframes fadeInSlideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Show breakdown on hover/focus */
#price-input-container:hover .hover-breakdown,
#price-input-container:focus-within .hover-breakdown {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    pointer-events: auto;
}

#price-input-container:hover .hover-breakdown-hint,
#price-input-container:focus-within .hover-breakdown-hint {
    display: inline !important;
}

.pricing-breakdown-box {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border: 2px solid #667eea;
    border-radius: 12px;
    padding: 1rem;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
}

.hover-breakdown-hint {
    color: #667eea;
    font-size: 0.85rem;
    font-weight: 500;
    animation: fadeInHint 0.3s ease-out;
}

@keyframes fadeInHint {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
        padding-top: 0;
        padding-bottom: 0;
    }
    to {
        opacity: 1;
        max-height: 500px;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
}

.breakdown-title {
    font-size: 0.9rem;
    font-weight: 600;
    color: #667eea;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid rgba(102, 126, 234, 0.2);
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.4rem 0;
    font-size: 0.85rem;
}

.breakdown-item.breakdown-charge {
    color: #dc3545;
}

.breakdown-item.breakdown-total {
    border-top: 2px solid #667eea;
    margin-top: 0.5rem;
    padding-top: 0.75rem;
    font-size: 1rem;
    color: #667eea;
}

.breakdown-label {
    color: #495057;
}

.breakdown-value {
    font-weight: 600;
    color: inherit;
}

.breakdown-notice {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.5rem;
    padding-top: 0.5rem;
    border-top: 1px solid rgba(102, 126, 234, 0.1);
}

/* Ensure breakdown is visible when shown */
#pricing-breakdown[style*="display: block"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation and enhancement
    const form = document.getElementById('jobCreateForm');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Mexican States and Cities Data
    const mexicanStatesCities = {
        'Aguascalientes': ['Aguascalientes', 'Jesus Maria', 'Rincon de Romos', 'Calvillo', 'San Francisco de los Romo', 'Pabellón de Arteaga', 'Asientos', 'El Llano', 'Tepezalá', 'Cosío'],
        'Baja California': ['Tijuana', 'Mexicali', 'Ensenada', 'Rosarito', 'Tecate', 'San Felipe', 'La Paz', 'Playas de Rosarito', 'Villa del Mar', 'Los Algodones'],
        'Baja California Sur': ['La Paz', 'Cabo San Lucas', 'San José del Cabo', 'Mulegé', 'Todos Santos', 'Loreto', 'La Ventana', 'Santiago', 'Villa Morelos', 'El Triunfo'],
        'Campeche': ['Campeche', 'Ciudad del Carmen', 'Champotón', 'Escárcega', 'Calakmul', 'Palizada', 'Hopelchén', 'Calkiní', 'Tenabo', 'Hecelchakán'],
        'Chiapas': ['Tuxtla Gutiérrez', 'San Cristóbal de las Casas', 'Tapachula', 'Palenque', 'Comitán', 'Villaflores', 'Pijijiapan', 'Puerto Madero', 'Suchiate', 'Arriaga'],
        'Chihuahua': ['Chihuahua', 'Ciudad Juárez', 'Cuauhtémoc', 'Parral', 'Delicias', 'Camargo', 'Nuevo Casas Grandes', 'Ojinaga', 'Jiménez', 'Aldama'],
        'Ciudad de México': ['Álvaro Obregón', 'Azcapotzalco', 'Benito Juárez', 'Coyoacán', 'Cuajimalpa', 'Cuauhtémoc', 'Gustavo A. Madero', 'Iztacalco', 'Iztapalapa', 'Magdalena Contreras', 'Miguel Hidalgo', 'Milpa Alta', 'Tláhuac', 'Tlalpan', 'Venustiano Carranza', 'Xochimilco'],
        'Coahuila': ['Saltillo', 'Torreón', 'Monclova', 'Piedras Negras', 'Ciudad Acuña', 'Ramos Arizpe', 'Matamoros', 'San Pedro', 'Sabinas', 'Nava'],
        'Colima': ['Colima', 'Manzanillo', 'Tecomán', 'Villa de Álvarez', 'Cómala', 'Coquimatlán', 'Armería', 'Cuauhtémoc', 'Ixtlahuacán', 'Minatitlán'],
        'Durango': ['Durango', 'Gómez Palacio', 'Lerdo', 'Ciudad Guadalupe Victoria', 'Pueblo Nuevo', 'El Salto', 'Nombre de Dios', 'San Juan del Río', 'Vicente Guerrero', 'Peñón Blanco'],
        'Estado de México': ['Toluca', 'Naucalpan', 'Ecatepec', 'Nezahualcóyotl', 'Atizapán', 'Tlalnepantla', 'Cuautitlán', 'Chimalhuacán', 'Cuautitlán Izcalli', 'Tultitlán', 'Huehuetoca', 'Nicolás Romero', 'Texcoco', 'Los Reyes'],
        'Guanajuato': ['León', 'Irapuato', 'Celaya', 'Guanajuato', 'Salamanca', 'Silicayo', 'San Miguel de Allende', 'Dolores Hidalgo', 'Moroleón', 'Acámbaro', 'Uriangato', 'Pénjamo'],
        'Guerrero': ['Acapulco', 'Chilpancingo', 'Iguala', 'Taxco', 'Zihuatanejo', 'Puerto Escondido', 'Coyuca de Benítez', 'Pie de la Cuesta', 'Barra Vieja', 'Copala'],
        'Hidalgo': ['Pachuca', 'Tulancingo', 'Tula', 'Ixmiquilpan', 'Actopan', 'Mineral del Monte', 'Real del Monte', 'Huasca', 'Atotonilco', 'Tepeji del Río'],
        'Jalisco': ['Guadalajara', 'Zapopan', 'Tlaquepaque', 'Tonalá', 'Puerto Vallarta', 'Chapala', 'Lagos de Moreno', 'Tepatitlán', 'Zacoalco', 'Tala', 'Ocotlán', 'Ciudad Guzmán'],
        'Michoacán': ['Morelia', 'Uruapan', 'Zamora', 'Pátzcuaro', 'Lázaro Cárdenas', 'Sahuayo', 'Lázaro Cárdenas', 'Apatzingán', 'Jiquilpan', 'Tanhuato'],
        'Morelos': ['Cuernavaca', 'Cuautla', 'Jiutepec', 'Tepoztlán', 'Temixco', 'Yautepec', 'Oaxtepec', 'Huitzilac', 'Tlaltizapán', 'Emiliano Zapata'],
        'Nayarit': ['Tepic', 'Bahía de Banderas', 'Ixtlán del Río', 'Amatlán de Cañas', 'Xalisco', 'Compostela', 'Sayulita', 'San Blas', 'Ruíz', 'Jala'],
        'Nuevo León': ['Monterrey', 'San Pedro Garza García', 'Guadalupe', 'San Nicolás de los Garza', 'Apodaca', 'Escobedo', 'Santa Catarina', 'San Nicolás', 'Ciudad Guadalupe', 'General Escobedo'],
        'Oaxaca': ['Oaxaca', 'Salina Cruz', 'Juchitán', 'Huajuapan de León', 'Puerto Escondido', 'San Pablo Villa de Mitla', 'Huautla de Jiménez', 'Santo Domingo Tehuantepec', 'Tlacolula', 'Mitla'],
        'Puebla': ['Puebla', 'Cholula', 'Tehuacán', 'Atlixco', 'San Martín Texmelucan', 'Zacatlán', 'Córdoba', 'Chignahuapan', 'Cuetzalan', 'San Andrés Cholula'],
        'Querétaro': ['Querétaro', 'San Juan del Río', 'Corregidora', 'El Marqués', 'Colón', 'Amealco', 'Cadereyta', 'Pedro Escobedo', 'Jalpan', 'San Joaquín'],
        'Quintana Roo': ['Cancún', 'Playa del Carmen', 'Chetumal', 'Cozumel', 'Tulum', 'Puerto Morelos', 'Bacalar', 'Akumal', 'Mahahual', 'Holbox', 'Isla Mujeres', 'Puerto Aventuras'],
        'San Luis Potosí': ['San Luis Potosí', 'Soledad de Graciano Sánchez', 'Ciudad Valles', 'Matehuala', 'Rioverde', 'Tamazunchale', 'Ciudad Fernández', 'Vanegas', 'Catorce', 'Real de Catorce'],
        'Sinaloa': ['Culiacán', 'Mazatlán', 'Los Mochis', 'Guamúchil', 'Sinaloa de Leyva', 'Navolato', 'El Fuerte', 'Angostura', 'Mocorito', 'La Cruz'],
        'Sonora': ['Hermosillo', 'Ciudad Obregón', 'Nogales', 'Navojoa', 'Puerto Peñasco', 'San Luis Río Colorado', 'Guaymas', 'San Carlos', 'Empalme', 'Sonoyta', 'Cananea', 'Álamos', 'Caborca', 'Magdalena de Kino'],
        'Tabasco': ['Villahermosa', 'Cárdenas', 'Comalcalco', 'Paraíso', 'Macuspana', 'Frontera', 'Teapa', 'Reforma', 'Nacajuca', 'Centro'],
        'Tamaulipas': ['Reynosa', 'Matamoros', 'Tampico', 'Ciudad Victoria', 'Ciudad Madero', 'Altamira', 'Nuevo Laredo', 'El Mante', 'Soto la Marina', 'Río Bravo'],
        'Tlaxcala': ['Tlaxcala', 'Apizaco', 'Huamantla', 'Chiautempan', 'Tlaxco', 'Calpulalpan', 'Nanacamilpa', 'Contla', 'San Pablo del Monte', 'Tepetitla'],
        'Veracruz': ['Veracruz', 'Xalapa', 'Coatzacoalcos', 'Poza Rica', 'Córdoba', 'Orizaba', 'Catemaco', 'Alvarado', 'Boca del Río', 'Puerto Escondido'],
        'Yucatán': ['Mérida', 'Valladolid', 'Progreso', 'Tizimín', 'Motul', 'Izamal', 'Dzibilchaltún', 'Celestún', 'Dzidzantún', 'Akumal'],
        'Zacatecas': ['Zacatecas', 'Fresnillo', 'Guadalupe', 'Jerez', 'Calera', 'Río Grande', 'Sombrerete', 'Guadalupe', 'Valparaíso', 'Miguel Auza']
    };
    
    // State-City Dynamic Population
    const stateSelect = document.getElementById('state');
    const citySelect = document.getElementById('city');
    
    if (stateSelect && citySelect) {
        stateSelect.addEventListener('change', function() {
            const selectedState = this.value;
            
            // Clear and disable city dropdown
            citySelect.innerHTML = '<option value="">Select city</option>';
            citySelect.disabled = true;
            
            if (selectedState && mexicanStatesCities[selectedState]) {
                // Enable and populate city dropdown
                citySelect.disabled = false;
                const cities = mexicanStatesCities[selectedState];
                
                cities.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city;
                    option.textContent = city;
                    citySelect.appendChild(option);
                });
                
                console.log(`Loaded ${cities.length} cities for ${selectedState}`);
            }
        });
        
        // EDIT MODE: Trigger city population on page load if state is already selected
        const currentCity = document.getElementById('current_city');
        if (stateSelect.value && currentCity && currentCity.value) {
            // Trigger change event to populate cities
            stateSelect.dispatchEvent(new Event('change'));
            
            // Wait for cities to populate, then select the current city
            setTimeout(() => {
                citySelect.value = currentCity.value;
                console.log('Pre-selected city:', currentCity.value);
            }, 100);
        }
    }
    
    // STR Requirements Handler
    const propertyTypeRadios = document.querySelectorAll('input[name="property_type"]');
    const strAlert = document.getElementById('str-requirements-alert');
    const strConfirmationCheckbox = document.getElementById('str_requirements_confirmed');
    const strServicesGroup = document.getElementById('str-services-group');
    
    // Initialize on page load - check which property type is selected
    const initialPropertyType = document.querySelector('input[name="property_type"]:checked');
    if (initialPropertyType && initialPropertyType.value !== 'str') {
        // Make sure checkbox is not required for residential
        strConfirmationCheckbox.required = false;
    }
    
    propertyTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'str') {
                // Show STR requirements alert
                strAlert.style.display = 'block';
                strConfirmationCheckbox.required = true;
                
                // Show STR-specific services
                if (strServicesGroup) {
                    strServicesGroup.style.display = 'block';
                }
                
                // Scroll to alert smoothly
                setTimeout(() => {
                    strAlert.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }, 100);
            } else {
                // Hide STR requirements alert
                strAlert.style.display = 'none';
                strConfirmationCheckbox.required = false;
                strConfirmationCheckbox.checked = false;
                
                // Hide STR-specific services
                if (strServicesGroup) {
                    strServicesGroup.style.display = 'none';
                    
                    // Uncheck all STR-specific checkboxes
                    const strCheckboxes = strServicesGroup.querySelectorAll('input[type="checkbox"]');
                    strCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                }
            }
        });
    });
    
    // Ensure STR confirmation is validated on form submit
    form.addEventListener('submit', function(e) {
        const isSTR = document.getElementById('property_type_str').checked;
        if (isSTR && !strConfirmationCheckbox.checked) {
            e.preventDefault();
            alert('Please confirm that you have read and understand all STR requirements.');
            strConfirmationCheckbox.focus();
            return false;
        }
    });
    
    // Checkbox enhancement for additional services
    const extrasCheckboxes = document.querySelectorAll('input[name="extras[]"]');
    const extrasFeedback = document.getElementById('extras-feedback');
    const extrasContainer = document.querySelector('.extras-selection-section');
    
    if (extrasCheckboxes.length > 0 && extrasFeedback) {
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
    
    // Date validation
    const dateInput = document.getElementById('job_date');
    dateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate < today) {
            this.classList.add('is-invalid');
            let feedback = this.parentNode.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                this.parentNode.appendChild(feedback);
            }
            feedback.textContent = 'Please select today or a future date.';
        } else {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
            const feedback = this.parentNode.querySelector('.invalid-feedback');
            if (feedback) {
                feedback.remove();
            }
        }
    });
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    dateInput.setAttribute('min', today);
    
    // Pricing Calculator
    const pricingParams = {
        baseCharge: <?php echo isset($pricing_params['base_charge']) ? $pricing_params['base_charge'] : 25.00; ?>,
        taxPercent: <?php echo isset($pricing_params['tax_percent']) ? $pricing_params['tax_percent'] : 10; ?>,
        appPercent: <?php echo isset($pricing_params['app_percent']) ? $pricing_params['app_percent'] : 15; ?>
    };
    
    console.log('Pricing params loaded:', pricingParams);
    
    const priceInput = document.getElementById('suggested_price');
    const pricingBreakdown = document.getElementById('pricing-breakdown');
    
    if (!priceInput) {
        console.error('Price input not found!');
    }
    if (!pricingBreakdown) {
        console.error('Pricing breakdown not found!');
    }
    
    function calculatePricing(suggestedPrice) {
        console.log('Calculating pricing for:', suggestedPrice);
        
        if (!suggestedPrice || suggestedPrice <= 0 || isNaN(suggestedPrice)) {
            console.log('Invalid price, not updating breakdown');
            return;
        }
        
        const baseCharge = pricingParams.baseCharge;
        const taxAmount = (suggestedPrice * pricingParams.taxPercent) / 100;
        const appAmount = (suggestedPrice * pricingParams.appPercent) / 100;
        const cleanerPayout = suggestedPrice - baseCharge - taxAmount - appAmount;
        
        console.log('Calculated:', {
            baseCharge,
            taxAmount,
            appAmount,
            cleanerPayout
        });
        
        // Update display elements
        const elements = {
            suggestedPrice: document.getElementById('display-suggested-price'),
            baseCharge: document.getElementById('display-base-charge'),
            taxPercent: document.getElementById('display-tax-percent'),
            taxAmount: document.getElementById('display-tax-amount'),
            appPercent: document.getElementById('display-app-percent'),
            appAmount: document.getElementById('display-app-amount'),
            cleanerPayout: document.getElementById('display-cleaner-payout')
        };
        
        if (elements.suggestedPrice) elements.suggestedPrice.textContent = '$' + suggestedPrice.toFixed(2);
        if (elements.baseCharge) elements.baseCharge.textContent = '-$' + baseCharge.toFixed(2);
        if (elements.taxPercent) elements.taxPercent.textContent = pricingParams.taxPercent;
        if (elements.taxAmount) elements.taxAmount.textContent = '-$' + taxAmount.toFixed(2);
        if (elements.appPercent) elements.appPercent.textContent = pricingParams.appPercent;
        if (elements.appAmount) elements.appAmount.textContent = '-$' + appAmount.toFixed(2);
        if (elements.cleanerPayout) elements.cleanerPayout.innerHTML = '<strong>$' + cleanerPayout.toFixed(2) + '</strong>';
        
        console.log('Display updated successfully');
    }
    
    // Real-time calculation and formatting
    if (priceInput) {
        priceInput.addEventListener('input', function() {
            let value = this.value;
            
            // Format: Allow only 2 decimal places
            if (value && !isNaN(value)) {
                if (value.includes('.')) {
                    const parts = value.split('.');
                    if (parts[1] && parts[1].length > 2) {
                        this.value = parts[0] + '.' + parts[1].substring(0, 2);
                    }
                }
            }
            
            // Calculate pricing
            const numValue = parseFloat(this.value);
            console.log('Price input changed:', numValue);
            calculatePricing(numValue);
        });
        
        // Calculate on page load if value exists
        if (priceInput.value) {
            calculatePricing(parseFloat(priceInput.value));
        }
    }
});
</script>
