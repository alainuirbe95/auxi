<div class="container-fluid">
            
            <!-- Job Details -->
            <div class="row">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                Job Details
                            </h5>
                                <div class="quick-actions">
                                    <?php if (in_array($job->status, ['open', 'offers_received'])): ?>
                                        <a href="<?php echo base_url('host/edit_job/' . $job->id); ?>" class="btn btn-light btn-sm ms-2" title="Edit Job">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($job->assigned_cleaner_id)): ?>
                                        <a href="<?php echo base_url('cleaner/public-profile/' . $job->assigned_cleaner_id); ?>" class="btn btn-info btn-sm ms-2" title="View Cleaner Profile">
                                            <i class="fas fa-user-circle me-1"></i> View Cleaner Profile
                                        </a>
                                    <?php endif; ?>
                                    <a href="<?php echo base_url('host/jobs'); ?>" class="btn btn-secondary btn-sm ms-2" title="Back to Jobs">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo htmlspecialchars($job->title); ?></h4>
                                    <p class="text-muted"><?php echo htmlspecialchars($job->description); ?></p>
                                    
                                    <!-- Quick Summary Box -->
                                    <div class="summary-box mb-4">
                                        <?php 
                                        // Determine actual prices based on accepted offer
                                        if (!empty($accepted_offer)) {
                                            $actual_host_price = $accepted_offer->amount;
                                            // Calculate cleaner payout from accepted offer
                                            if (!empty($accepted_offer->cleaner_payout)) {
                                                $actual_cleaner_payout = $accepted_offer->cleaner_payout;
                                            } else {
                                                // Fallback calculation
                                                $tax_amount = ($actual_host_price * $pricing_params['tax_percent']) / 100;
                                                $app_amount = ($actual_host_price * $pricing_params['app_percent']) / 100;
                                                $actual_cleaner_payout = $actual_host_price - $pricing_params['base_charge'] - $tax_amount - $app_amount;
                                            }
                                            $is_counter_offer = ($accepted_offer->offer_type === 'counter');
                                        } else {
                                            $actual_host_price = $job->suggested_price;
                                            $tax_amount = ($actual_host_price * $pricing_params['tax_percent']) / 100;
                                            $app_amount = ($actual_host_price * $pricing_params['app_percent']) / 100;
                                            $actual_cleaner_payout = $actual_host_price - $pricing_params['base_charge'] - $tax_amount - $app_amount;
                                            $is_counter_offer = false;
                                        }
                                        ?>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="summary-item">
                                                    <i class="fas fa-dollar-sign text-success"></i>
                                                    <div>
                                                        <small class="text-muted"><?php echo $is_counter_offer ? 'Final Price (Counter Offer)' : 'Your Price'; ?></small>
                                                        <strong class="d-block">$<?php echo number_format($actual_host_price, 2); ?></strong>
                                                        <?php if ($is_counter_offer): ?>
                                                            <small class="text-muted d-block" style="font-size: 0.75rem;">
                                                                Original: $<?php echo number_format($job->suggested_price, 2); ?>
                                                            </small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="summary-item">
                                                    <i class="fas fa-money-bill-wave text-info"></i>
                                                    <div>
                                                        <small class="text-muted">Cleaner Payout</small>
                                                        <strong class="d-block">$<?php echo number_format($actual_cleaner_payout, 2); ?></strong>
                                                        <?php if ($is_counter_offer): ?>
                                                            <small class="badge bg-warning text-dark" style="font-size: 0.7rem;">Counter Offer</small>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="summary-item">
                                                    <i class="fas fa-clock text-warning"></i>
                                                    <div>
                                                        <small class="text-muted">Duration</small>
                                                        <strong class="d-block"><?php echo isset($job->estimated_duration) ? ($job->estimated_duration / 60) . ' hours' : 'Not specified'; ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <?php
                                    // Define job date and time variables
                                    $job_date = isset($job->scheduled_date) ? $job->scheduled_date : '';
                                    $job_time = isset($job->scheduled_time) ? $job->scheduled_time : '';
                                    ?>
                                    
                                    <div class="job-info">
                                        <!-- Property Type Badge -->
                                        <?php if (!empty($job->property_type)): ?>
                                        <div class="row mb-3">
                                            <div class="col-12">
                                                <span class="badge badge-lg <?php echo $job->property_type === 'str' ? 'bg-warning' : 'bg-info'; ?>" style="font-size: 1rem; padding: 0.75rem 1.5rem;">
                                                    <i class="fas fa-<?php echo $job->property_type === 'str' ? 'home' : 'building'; ?> me-2"></i>
                                                    <?php echo $job->property_type === 'str' ? 'Short Term Rental' : 'Residential'; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Pricing Breakdown -->
                                        <?php 
                                        // Use actual prices (from accepted offer or suggested)
                                        $breakdown_host_price = $actual_host_price;
                                        $breakdown_tax_amount = ($breakdown_host_price * $pricing_params['tax_percent']) / 100;
                                        $breakdown_app_amount = ($breakdown_host_price * $pricing_params['app_percent']) / 100;
                                        $breakdown_cleaner_payout = $actual_cleaner_payout;
                                        ?>
                                        
                                        <div class="pricing-breakdown-box mt-3">
                                            <h6 class="section-subtitle">
                                                <i class="fas fa-calculator me-2"></i>Pricing Breakdown
                                                <?php if ($is_counter_offer): ?>
                                                    <span class="badge bg-warning text-dark ms-2" style="font-size: 0.75rem;">Counter Offer Accepted</span>
                                                <?php endif; ?>
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="pricing-row">
                                                        <span class="pricing-label"><?php echo $is_counter_offer ? 'Final Price (You Pay):' : 'Your Price:'; ?></span>
                                                        <span class="pricing-value">$<?php echo number_format($breakdown_host_price, 2); ?></span>
                                                    </div>
                                                    <?php if ($is_counter_offer && $breakdown_host_price != $job->suggested_price): ?>
                                                        <div class="pricing-row" style="background: rgba(255, 193, 7, 0.1); padding: 0.5rem; margin: 0.5rem 0; border-radius: 8px;">
                                                            <span class="pricing-label">Original Suggested:</span>
                                                            <span class="pricing-value text-muted">$<?php echo number_format($job->suggested_price, 2); ?></span>
                                                        </div>
                                                        <div class="pricing-row" style="font-weight: 700; color: <?php echo ($breakdown_host_price < $job->suggested_price) ? '#28a745' : '#f57c00'; ?>;">
                                                            <span class="pricing-label">Price Difference:</span>
                                                            <span class="pricing-value">
                                                                <?php 
                                                                $price_diff = $breakdown_host_price - $job->suggested_price;
                                                                echo ($price_diff > 0 ? '+' : '') . '$' . number_format($price_diff, 2);
                                                                ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="pricing-row pricing-deduction">
                                                        <span class="pricing-label">- Base Charge:</span>
                                                        <span class="pricing-value">-$<?php echo number_format($pricing_params['base_charge'], 2); ?></span>
                                                    </div>
                                                    <div class="pricing-row pricing-deduction">
                                                        <span class="pricing-label">- Tax (<?php echo $pricing_params['tax_percent']; ?>%):</span>
                                                        <span class="pricing-value">-$<?php echo number_format($breakdown_tax_amount, 2); ?></span>
                                                    </div>
                                                    <div class="pricing-row pricing-deduction">
                                                        <span class="pricing-label">- App Fee (<?php echo $pricing_params['app_percent']; ?>%):</span>
                                                        <span class="pricing-value">-$<?php echo number_format($breakdown_app_amount, 2); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="cleaner-payout-box">
                                                        <div class="payout-label">Cleaner Payout</div>
                                                        <div class="payout-amount">$<?php echo number_format($breakdown_cleaner_payout, 2); ?></div>
                                                        <small class="text-muted">This is what the cleaner will receive</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Location & Timing Section -->
                                        <div class="row mt-3">
                                            <div class="col-sm-6">
                                                <h6 class="section-subtitle"><i class="fas fa-map-marker-alt me-2"></i>Location</h6>
                                                <p class="mb-1"><strong>Address:</strong> <?php echo htmlspecialchars($job->address); ?></p>
                                                <p class="mb-1"><strong>City:</strong> <?php echo htmlspecialchars($job->city ?? 'Not specified'); ?></p>
                                                <p class="mb-1"><strong>State:</strong> <?php echo htmlspecialchars($job->state ?? 'Not specified'); ?></p>
                                                <?php if (!empty($job->zip_code)): ?>
                                                    <p class="mb-0"><strong>ZIP Code:</strong> <?php echo htmlspecialchars($job->zip_code); ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <h6 class="section-subtitle"><i class="fas fa-calendar-alt me-2"></i>Schedule</h6>
                                                <p class="mb-1"><strong>Date:</strong> <?php echo $job_date ? date('M j, Y', strtotime($job_date)) : 'Not scheduled'; ?></p>
                                                <p class="mb-1"><strong>Time:</strong> <?php echo $job_time ? date('g:i A', strtotime($job_time)) : 'Not scheduled'; ?></p>
                                                <p class="mb-0"><strong>Duration:</strong> <?php echo isset($job->estimated_duration) ? ($job->estimated_duration / 60) . ' hours' : 'Not specified'; ?></p>
                                            </div>
                                        </div>
                                        
                                        <!-- Property Details Section -->
                                        <div class="row mt-3">
                                            <div class="col-sm-6">
                                                <h6 class="section-subtitle"><i class="fas fa-door-open me-2"></i>Property Details</h6>
                                                <p class="mb-1"><strong>Rooms:</strong> 
                                                    <?php 
                                                    $rooms = json_decode($job->rooms, true);
                                                    if (is_array($rooms) && !empty($rooms)) {
                                                        echo implode(', ', $rooms);
                                                    } else {
                                                        echo 'Not specified';
                                                    }
                                                    ?>
                                                </p>
                                                <?php if (isset($job->pets) && $job->pets): ?>
                                                    <p class="mb-0"><span class="badge bg-info"><i class="fas fa-paw me-1"></i> Pets Present</span></p>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <h6 class="section-subtitle"><i class="fas fa-list-check me-2"></i>Additional Services</h6>
                                                <?php 
                                                // Decode JSON extras array
                                                $extras = json_decode($job->extras ?? '[]', true);
                                                if (is_array($extras) && !empty($extras)) {
                                                    // Icon mapping for extras
                                                    $extras_icons = [
                                                        'deep_cleaning' => 'broom',
                                                        'windows' => 'window-maximize',
                                                        'appliances' => 'microchip',
                                                        'carpet' => 'layer-group',
                                                        'oven' => 'fire',
                                                        'cabinet_interior' => 'toolbox',
                                                        'light_fixtures' => 'lightbulb',
                                                        'baseboards' => 'minus',
                                                        'inside_fridge' => 'snowflake',
                                                        'bathroom_grout' => 'shower',
                                                        'doors_frames' => 'door-open',
                                                        'walls' => 'paint-brush',
                                                        'ceiling_fans' => 'fan',
                                                        'vents' => 'wind',
                                                        'mirrors' => 'glass-mirror',
                                                        'furniture_polish' => 'chair',
                                                        'blinds' => 'window-restore',
                                                        'trash_removal' => 'trash-alt',
                                                        'organization' => 'boxes',
                                                        'pet_hair' => 'paw',
                                                        'inside_cabinets' => 'archive',
                                                        'garage' => 'warehouse',
                                                        'patio_balcony' => 'home',
                                                        'exterior_windows' => 'window-frame',
                                                        'wash_linens' => 'tshirt',
                                                        'check_dishes' => 'utensils',
                                                        'clean_refrigerator' => 'snowflake',
                                                        'ensure_supplies' => 'shopping-bag',
                                                        'reset_beds' => 'bed',
                                                        'reset_kitchen' => 'utensils',
                                                        'check_amenities' => 'clipboard-check',
                                                        'leave_goodies' => 'candy-cane',
                                                        'reset_makeup' => 'eye',
                                                        'reset_shower' => 'shower',
                                                        'reset_coffee' => 'coffee',
                                                        'check_tv' => 'tv',
                                                        'check_wifi' => 'wifi',
                                                        'check_keys' => 'key',
                                                        'reset_pillows' => 'couch',
                                                        'reset_table' => 'utensils',
                                                        'photo_documentation' => 'camera',
                                                        'check_hvac' => 'thermometer-half',
                                                        'ensure_quiet' => 'volume-mute',
                                                        'check_smoke_detector' => 'smoke'
                                                    ];
                                                    
                                                    // Clean label mapping
                                                    $extras_labels = [
                                                        'deep_cleaning' => 'Deep Cleaning',
                                                        'windows' => 'Window Cleaning',
                                                        'appliances' => 'Appliance Cleaning',
                                                        'carpet' => 'Carpet Cleaning',
                                                        'oven' => 'Oven Cleaning',
                                                        'cabinet_interior' => 'Cabinet Interior',
                                                        'light_fixtures' => 'Light Fixtures',
                                                        'baseboards' => 'Baseboards',
                                                        'inside_fridge' => 'Inside Refrigerator',
                                                        'bathroom_grout' => 'Bathroom Grout Scrubbing',
                                                        'doors_frames' => 'Doors & Frames',
                                                        'walls' => 'Wall Washing',
                                                        'ceiling_fans' => 'Ceiling Fans',
                                                        'vents' => 'Vent Cleaning',
                                                        'mirrors' => 'Mirror Polish',
                                                        'furniture_polish' => 'Furniture Polish',
                                                        'blinds' => 'Blinds Cleaning',
                                                        'trash_removal' => 'Trash Removal',
                                                        'organization' => 'Light Organization',
                                                        'pet_hair' => 'Pet Hair Removal',
                                                        'inside_cabinets' => 'Inside Cabinets',
                                                        'garage' => 'Garage Sweep',
                                                        'patio_balcony' => 'Patio/Balcony',
                                                        'exterior_windows' => 'Exterior Windows',
                                                        'wash_linens' => 'Wash All Linens & Towels',
                                                        'check_dishes' => 'Check & Wash Dishes',
                                                        'clean_refrigerator' => 'Clean Refrigerator',
                                                        'ensure_supplies' => 'Ensure Consumables Stocked',
                                                        'reset_beds' => 'Make All Beds',
                                                        'reset_kitchen' => 'Reset Kitchen to Empty',
                                                        'check_amenities' => 'Check All Amenities',
                                                        'leave_goodies' => 'Leave Welcome Goodies',
                                                        'reset_makeup' => 'Reset Makeup Area',
                                                        'reset_shower' => 'Reset Shower Supplies',
                                                        'reset_coffee' => 'Reset Coffee Station',
                                                        'check_tv' => 'Test TV & Electronics',
                                                        'check_wifi' => 'Verify WiFi Password',
                                                        'check_keys' => 'Check All Keys Available',
                                                        'reset_pillows' => 'Arrange Pillows',
                                                        'reset_table' => 'Set Dining Table',
                                                        'photo_documentation' => 'Photo Documentation',
                                                        'check_hvac' => 'Check HVAC',
                                                        'ensure_quiet' => 'Ensure Quiet Hours Notice',
                                                        'check_smoke_detector' => 'Test Smoke Detector'
                                                    ];
                                                    
                                                    echo '<div class="extras-display mt-2">';
                                                    foreach ($extras as $extra) {
                                                        $icon = isset($extras_icons[$extra]) ? $extras_icons[$extra] : 'check';
                                                        $label = isset($extras_labels[$extra]) ? $extras_labels[$extra] : str_replace('_', ' ', ucwords($extra, '_'));
                                                        echo '<span class="badge bg-light text-dark me-2 mb-2" style="font-size: 0.85rem; padding: 0.5rem 0.75rem;"><i class="fas fa-' . $icon . ' me-1"></i>' . htmlspecialchars($label) . '</span>';
                                                    }
                                                    echo '</div>';
                                                } else {
                                                    echo '<span class="text-muted">None selected</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($job->special_instructions)): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <strong>Special Instructions:</strong><br>
                                                <p class="text-muted"><?php echo htmlspecialchars($job->special_instructions); ?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($job->pets) && $job->pets): ?>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <span class="badge bg-info"><i class="fas fa-paw me-1"></i> Pets Present</span>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- OTP Information for Assigned/In Progress Jobs -->
                                        <?php if (in_array($job->status, ['assigned', 'in_progress']) && !empty($job->otp_code)): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="otp-info-card" style="background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%); border: 1px solid #bee5eb; border-radius: 15px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(23, 162, 184, 0.1);">
                                                    <h6 class="otp-card-title" style="color: #0c5460; margin-bottom: 1rem; font-weight: 600;">
                                                        <i class="fas fa-key me-2"></i>
                                                        Service Code (OTP) for Cleaner
                                                    </h6>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <p class="mb-2">Share this code with your assigned cleaner to start the service:</p>
                                                            <div class="otp-display">
                                                                <span class="otp-code" style="
                                                                    display: inline-block;
                                                                    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
                                                                    color: white;
                                                                    font-size: 1.5rem;
                                                                    font-weight: 700;
                                                                    padding: 0.75rem 1.5rem;
                                                                    border-radius: 10px;
                                                                    letter-spacing: 0.2em;
                                                                    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
                                                                    font-family: monospace;
                                                                "><?php echo $job->otp_code; ?></span>
                                                            </div>
                                                            <small class="text-muted mt-2 d-block" style="color: #6c757d !important;">
                                                                <i class="fas fa-info-circle me-1"></i>
                                                                The cleaner will need this code to start the job. Share it securely when they arrive.
                                                            </small>
                                                        </div>
                                                        <div class="ms-3">
                                                            <button class="btn btn-outline-info btn-sm" onclick="copyToClipboard('<?php echo $job->otp_code; ?>')">
                                                                <i class="fas fa-copy me-1"></i>
                                                                Copy Code
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Cleaner Information (Shown for all statuses after assignment) -->
                                        <?php if (!empty($job->assigned_cleaner_id)): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="cleaner-info-card" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%); border: 1px solid #c3e6cb; border-radius: 15px; padding: 1.5rem; box-shadow: 0 4px 12px rgba(40, 167, 69, 0.1);">
                                                    <h6 class="cleaner-card-title" style="color: #155724; margin-bottom: 1rem; font-weight: 600;">
                                                        <i class="fas fa-user-check me-2"></i>
                                                        <?php echo in_array($job->status, ['closed', 'completed', 'recall_settled', 'recalled']) ? 'Cleaner Information' : 'Assigned Cleaner Information'; ?>
                                                    </h6>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="cleaner-info">
                                                                <p class="mb-1"><strong>Name:</strong> <?php echo htmlspecialchars($job->cleaner_first_name . ' ' . $job->cleaner_last_name); ?></p>
                                                                <p class="mb-1"><strong>Username:</strong> @<?php echo htmlspecialchars($job->cleaner_username); ?></p>
                                                                <p class="mb-1"><strong>Email:</strong> <?php echo htmlspecialchars($job->cleaner_email); ?></p>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="cleaner-contact">
                                                                <?php if (!empty($job->cleaner_phone)): ?>
                                                                    <p class="mb-1"><strong>Phone:</strong> 
                                                                        <a href="tel:<?php echo htmlspecialchars($job->cleaner_phone); ?>" class="text-decoration-none" style="color: #155724;">
                                                                            <?php echo htmlspecialchars($job->cleaner_phone); ?>
                                                                        </a>
                                                                    </p>
                                                                <?php endif; ?>
                                                                <div class="contact-buttons mt-2">
                                                                    <a href="<?php echo base_url('cleaner/public-profile/' . $job->assigned_cleaner_id); ?>" class="btn btn-outline-info btn-sm me-2">
                                                                        <i class="fas fa-user-circle me-1"></i>
                                                                        View Profile
                                                                    </a>
                                                                    <a href="mailto:<?php echo htmlspecialchars($job->cleaner_email); ?>" class="btn btn-outline-primary btn-sm me-2">
                                                                        <i class="fas fa-envelope me-1"></i>
                                                                        Email
                                                                    </a>
                                                                    <?php if (!empty($job->cleaner_phone)): ?>
                                                                        <a href="tel:<?php echo htmlspecialchars($job->cleaner_phone); ?>" class="btn btn-outline-success btn-sm">
                                                                            <i class="fas fa-phone me-1"></i>
                                                                            Call
                                                                        </a>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <small class="text-muted mt-2 d-block" style="color: #6c757d !important;">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        This cleaner has been assigned to your job. You can contact them directly using the information above.
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <?php if (in_array($job->status, ['open', 'offers_received'])): ?>
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h5>Offers Received</h5>
                                        <p class="display-4 text-primary"><?php echo count($offers); ?></p>
                                        <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-modern btn-primary">
                                            View All Offers
                                        </a>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Adjustment Requests -->
            <?php if (!empty($price_adjustments)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-dollar-sign text-warning me-2"></i>
                                Price Adjustment Requests
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($price_adjustments as $adjustment): ?>
                                <div class="price-adjustment-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="mb-2">
                                                <i class="fas fa-calculator text-warning me-2"></i>
                                                Requested Amount: $<?php echo number_format($adjustment->requested_amount, 2); ?>
                                            </h6>
                                            <p class="mb-2">
                                                <strong>Reason:</strong> <?php echo htmlspecialchars($adjustment->price_reason); ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Requested: <?php echo date('M j, Y g:i A', strtotime($adjustment->created_at)); ?>
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge badge-warning badge-lg">
                                                <?php echo ucfirst($adjustment->status); ?>
                                            </span>
                                            <?php if ($adjustment->status === 'pending'): ?>
                                                <div class="mt-2">
                                                    <a href="<?php echo base_url('host/counter-offers'); ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-handshake me-1"></i>
                                                        Respond
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dispute Information -->
            <?php if (!empty($dispute_info)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                Dispute Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Dispute Details
                                    </h6>
                                    <div class="dispute-details">
                                        <p><strong>Disputed On:</strong> <?php echo date('M j, Y g:i A', strtotime($dispute_info['disputed_at'])); ?></p>
                                        <p><strong>Reason:</strong></p>
                                        <div class="dispute-reason-box p-3 bg-light border rounded">
                                            <small><?php echo nl2br(htmlspecialchars($dispute_info['dispute_reason'])); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($dispute_info['dispute_resolution']): ?>
                                        <h6 class="text-success mb-3">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Resolution
                                        </h6>
                                        <div class="resolution-details">
                                            <p><strong>Resolved On:</strong> <?php echo date('M j, Y g:i A', strtotime($dispute_info['dispute_resolved_at'])); ?></p>
                                            <p><strong>Status:</strong> <span class="badge badge-success">Resolved</span></p>
                                            <?php if ($dispute_info['dispute_resolution_notes']): ?>
                                                <p><strong>Resolution Notes:</strong></p>
                                                <div class="resolution-notes-box p-3 bg-success bg-opacity-10 border border-success rounded">
                                                    <small><?php echo nl2br(htmlspecialchars($dispute_info['dispute_resolution_notes'])); ?></small>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Financial Impact -->
                                            <?php 
                                            $original_amount = $job->final_price ?: $job->accepted_price;
                                            $cleaner_amount = $dispute_info['payment_amount'] ?: 0;
                                            $host_refund = $original_amount - $cleaner_amount;
                                            ?>
                                            <div class="financial-impact mt-3">
                                                <h6 class="text-info">Financial Impact</h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="text-center p-2 bg-success bg-opacity-10 border border-success rounded">
                                                            <small class="text-success">Your Refund</small><br>
                                                            <strong class="text-success">$<?php echo number_format($host_refund, 2); ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-center p-2 bg-info bg-opacity-10 border border-info rounded">
                                                            <small class="text-info">Cleaner Payment</small><br>
                                                            <strong class="text-info">$<?php echo number_format($cleaner_amount, 2); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <h6 class="text-warning mb-3">
                                            <i class="fas fa-clock me-2"></i>
                                            Under Review
                                        </h6>
                                        <div class="pending-resolution">
                                            <p><strong>Status:</strong> <span class="badge badge-warning">Under Review</span></p>
                                            <p class="text-muted">A moderator is reviewing your dispute and will provide a resolution soon.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

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

/* Price Adjustment Styling */
.price-adjustment-item {
    background: #fff8e1;
    border-left: 4px solid #ff9800 !important;
}

.price-adjustment-item h6 {
    color: #e65100;
}

/* Dispute Information Styling */
.dispute-reason-box {
    background: #ffebee;
    border-color: #f44336 !important;
    max-height: 150px;
    overflow-y: auto;
}

.resolution-notes-box {
    max-height: 150px;
    overflow-y: auto;
}

.financial-impact .col-6 {
    margin-bottom: 0.5rem;
}

.financial-impact .text-center {
    padding: 0.75rem !important;
}

/* Badge Styling */
.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

/* Card Styling */
.modern-card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.modern-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
    border: none;
}

.modern-card .card-header h5 {
    color: white;
    font-weight: 600;
}

/* Modern Card Styles */
.modern-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    border: none;
    width: 100%;
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

.modern-card .card-header .quick-actions .btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    transition: all 0.3s ease;
}

.modern-card .card-header .quick-actions .btn:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modern-card .card-header .quick-actions .btn.btn-light {
    background: white;
    color: #667eea;
    border-color: white;
}

.modern-card .card-header .quick-actions .btn.btn-light:hover {
    background: #f8f9fa;
    color: #667eea;
}

.modern-card .card-header .quick-actions .btn.btn-info {
    background: rgba(23, 162, 184, 0.3);
    border-color: rgba(23, 162, 184, 0.5);
}

.modern-card .card-header .quick-actions .btn.btn-info:hover {
    background: rgba(23, 162, 184, 0.5);
    border-color: rgba(23, 162, 184, 0.7);
}

.modern-card .card-header .quick-actions .btn.btn-secondary {
    background: rgba(108, 117, 125, 0.3);
    border-color: rgba(108, 117, 125, 0.5);
}

.modern-card .card-header .quick-actions .btn.btn-secondary:hover {
    background: rgba(108, 117, 125, 0.5);
    border-color: rgba(108, 117, 125, 0.7);
}

.modern-card .card-body {
    padding: 2rem;
}

/* Summary Box Styles */
.summary-box {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
}

.summary-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: white;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.summary-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.summary-item i {
    font-size: 2rem;
    opacity: 0.8;
}

.summary-item small {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.summary-item strong {
    font-size: 1.2rem;
    font-weight: 700;
}

.section-subtitle {
    color: #667eea;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.75rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid rgba(102, 126, 234, 0.2);
}

/* Pricing Breakdown Styles */
.pricing-breakdown-box {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 12px;
    padding: 1.5rem;
}

.pricing-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.pricing-row:last-child {
    border-bottom: none;
}

.pricing-deduction {
    color: #6c757d;
}

.pricing-label {
    font-weight: 500;
}

.pricing-value {
    font-weight: 600;
}

.cleaner-payout-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.payout-label {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.payout-amount {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.cleaner-payout-box small {
    color: rgba(255, 255, 255, 0.8);
}

.job-info {
    background: rgba(102, 126, 234, 0.05);
    padding: 1.5rem;
    border-radius: 15px;
    margin-top: 1rem;
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
</style>

<script>
function copyToClipboard(text) {
    // Create a temporary input element
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    
    // Select and copy the text
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
        button.classList.remove('btn-outline-info');
        button.classList.add('btn-success');
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-info');
        }, 2000);
        
    } catch (err) {
        console.error('Failed to copy text: ', err);
        alert('Failed to copy to clipboard. Please copy manually: ' + text);
    }
    
    // Remove the temporary input
    document.body.removeChild(tempInput);
}
</script>
