<?php
// Helper function for time formatting
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

<style>
.start-job-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

/* Modern Header */
.modern-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.modern-header h1 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.modern-header p {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Two Column Layout */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Modern Card */
.modern-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.modern-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    font-weight: 600;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modern-card-body {
    padding: 2rem;
}

/* Summary Stats */
.summary-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-box {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.stat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
}

.stat-box.payout-box {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border-left-color: #2ecc71;
}

.stat-box.payout-box * {
    color: white !important;
}

.stat-label {
    font-size: 0.85rem;
    opacity: 0.8;
    margin-bottom: 0.5rem;
    display: block;
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: #2d3748;
}

/* Detail Sections */
.detail-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.detail-section h5 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.detail-item {
    padding: 1rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.detail-item-label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.detail-item-value {
    font-weight: 600;
    color: #2d3748;
}

/* Extras Badges */
.extras-container {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.extra-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
}

/* STR Requirements */
.str-alert {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe5e5 100%);
    border: 3px solid #ffc107;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.str-alert-title {
    color: #d32f2f;
    font-weight: 700;
    font-size: 1.1rem;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.str-warning-box {
    background: rgba(211, 47, 47, 0.1);
    border-left: 4px solid #d32f2f;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    color: #b71c1c;
    font-weight: 600;
}

.str-content {
    background: white;
    padding: 1rem;
    border-radius: 10px;
    line-height: 1.8;
}

/* Host Info Card */
.host-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.host-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem;
    color: white;
}

.host-info {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.host-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #667eea;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.host-details h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.5rem;
}

.host-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.1rem;
}

.host-rating .stars {
    color: #ffd700;
}

.host-body {
    padding: 1.5rem;
}

.host-contact {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.contact-item i {
    color: #667eea;
    width: 20px;
}

.contact-item a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.contact-item a:hover {
    text-decoration: underline;
}

/* Reviews Section */
.reviews-section {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.reviews-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem;
    color: white;
    font-weight: 600;
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.reviews-scroll {
    max-height: 500px;
    overflow-y: auto;
    padding: 1.5rem;
}

.reviews-scroll::-webkit-scrollbar {
    width: 8px;
}

.reviews-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.reviews-scroll::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.reviews-scroll::-webkit-scrollbar-thumb:hover {
    background: #764ba2;
}

.review-item {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1rem;
    border-left: 4px solid #667eea;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.review-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.review-rating .stars {
    color: #ffc107;
    font-size: 1rem;
}

.review-date {
    font-size: 0.85rem;
    color: #6c757d;
}

.review-text {
    color: #4a5568;
    line-height: 1.6;
    margin-bottom: 0.75rem;
}

.review-categories {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5rem;
    font-size: 0.85rem;
}

.category-rating {
    display: flex;
    justify-content: space-between;
    padding: 0.25rem 0;
    color: #6c757d;
}

.no-reviews {
    text-align: center;
    padding: 3rem 2rem;
    color: #6c757d;
}

.no-reviews i {
    font-size: 3rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

/* Video Reminder Alert */
.video-reminder-alert {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe8cc 100%);
    border: 3px solid #ff9800;
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 8px 25px rgba(255, 152, 0, 0.2);
    display: flex;
    gap: 1.5rem;
    align-items: flex-start;
}

.video-reminder-alert .alert-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: white;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
}

.video-reminder-alert .alert-content {
    flex: 1;
}

.video-reminder-alert h4 {
    color: #e65100;
    font-weight: 700;
    margin-bottom: 1rem;
    font-size: 1.3rem;
}

.video-reminder-alert p {
    color: #4a5568;
    margin-bottom: 0.75rem;
    font-weight: 500;
}

.video-reminder-alert ul {
    margin: 0 0 1rem 1.5rem;
    padding: 0;
    color: #4a5568;
    line-height: 1.8;
}

.video-reminder-alert ul li {
    margin-bottom: 0.5rem;
}

.video-reminder-alert .alert-footer {
    background: rgba(255, 152, 0, 0.15);
    border-left: 4px solid #ff9800;
    padding: 0.75rem 1rem;
    border-radius: 8px;
    color: #e65100;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-top: 1rem;
}

/* OTP Section */
.otp-card {
    background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
    border: 2px solid #667eea;
}

.otp-header {
    text-align: center;
    margin-bottom: 2rem;
}

.otp-header h3 {
    color: #667eea;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.otp-input-group {
    max-width: 400px;
    margin: 0 auto 2rem auto;
}

.otp-input {
    font-size: 2rem;
    text-align: center;
    letter-spacing: 0.5em;
    font-weight: 700;
    padding: 1rem;
    border: 3px solid #667eea;
    border-radius: 15px;
    background: white;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
}

.otp-input:focus {
    border-color: #764ba2;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

.otp-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn-start {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border: none;
    color: white;
    padding: 0.75rem 2rem;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 50px;
    box-shadow: 0 5px 15px rgba(67, 233, 123, 0.3);
    transition: all 0.3s ease;
}

.btn-start:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(67, 233, 123, 0.4);
    color: white;
}

.btn-back {
    background: white;
    border: 2px solid #667eea;
    color: #667eea;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    border-radius: 50px;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: #667eea;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .summary-stats {
        grid-template-columns: 1fr;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .video-reminder-alert {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .video-reminder-alert ul {
        text-align: left;
    }
}
</style>

<div class="start-job-container">
    <!-- Flash Messages Container -->
    <div id="flash-message-container"></div>
    
    <!-- Flash Messages from Session -->
    <?php if ($this->session->flashdata('text')): ?>
        <div class="alert alert-<?php echo $this->session->flashdata('type') === 'error' ? 'danger' : ($this->session->flashdata('type') === 'success' ? 'success' : 'info'); ?> alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 2rem; animation: slideDown 0.3s ease; font-size: 1.1rem; padding: 1.25rem;">
            <i class="fas fa-<?php echo $this->session->flashdata('type') === 'error' ? 'exclamation-circle' : ($this->session->flashdata('type') === 'success' ? 'check-circle' : 'info-circle'); ?> me-2"></i>
            <strong><?php echo $this->session->flashdata('text'); ?></strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
    <!-- Error Messages from URL Parameter (backup method) -->
    <?php 
    $error_param = $this->input->get('error');
    if ($error_param === 'invalid_otp'): 
    ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 15px; border: none; box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3); margin-bottom: 2rem; animation: slideDown 0.3s ease; font-size: 1.1rem; padding: 1.25rem;">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong>❌ Incorrect Service Code!</strong> Please verify the 6-digit code provided by the host and try again.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Modern Header -->
    <div class="modern-header">
        <h1>
            <i class="fas fa-play-circle"></i>
            Ready to Start Job
        </h1>
        <p>Review all job details and host information before entering your service code</p>
    </div>

    <?php
    // Calculate actual payout
    if (!empty($accepted_offer)) {
        $your_payout = !empty($accepted_offer->cleaner_payout) ? $accepted_offer->cleaner_payout :
            ($accepted_offer->amount - $pricing_params['base_charge'] - 
            (($accepted_offer->amount * $pricing_params['tax_percent']) / 100) - 
            (($accepted_offer->amount * $pricing_params['app_percent']) / 100));
        $host_price = $accepted_offer->amount;
        $is_counter = ($accepted_offer->offer_type === 'counter');
    } else {
        $host_price = $job->accepted_price ?? $job->suggested_price;
        $tax_amount = ($host_price * $pricing_params['tax_percent']) / 100;
        $app_amount = ($host_price * $pricing_params['app_percent']) / 100;
        $your_payout = $host_price - $pricing_params['base_charge'] - $tax_amount - $app_amount;
        $is_counter = false;
    }
    
    $is_str = (isset($job->property_type) && strtolower($job->property_type) === 'str');
    ?>

    <!-- Summary Stats -->
    <div class="summary-stats">
        <div class="stat-box payout-box">
            <span class="stat-label">Your Payout</span>
            <div class="stat-value">$<?php echo number_format($your_payout, 2); ?></div>
            <?php if ($is_counter): ?>
                <span class="badge bg-warning text-dark mt-2">Counter Offer</span>
            <?php endif; ?>
        </div>
        
        <div class="stat-box">
            <span class="stat-label">Scheduled</span>
            <div class="stat-value" style="font-size: 1.3rem;">
                <?php 
                if (!empty($job->scheduled_date) && !empty($job->scheduled_time)) {
                    echo date('M j, Y', strtotime($job->scheduled_date));
                } else {
                    echo 'Flexible';
                }
                ?>
            </div>
            <small class="text-muted">
                <?php 
                if (!empty($job->scheduled_time)) {
                    echo date('g:i A', strtotime($job->scheduled_time));
                }
                ?>
            </small>
        </div>
        
        <div class="stat-box">
            <span class="stat-label">Property Type</span>
            <div class="stat-value" style="font-size: 1.3rem;">
                <?php echo $is_str ? 'STR' : 'Residential'; ?>
            </div>
            <?php if ($is_str): ?>
                <span class="badge bg-warning text-dark mt-2">High Standards</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="content-grid">
        <!-- Left Column: Job Details -->
        <div>
            <!-- Job Description -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <div class="modern-card-header">
                    <i class="fas fa-clipboard-list"></i>
                    <?php echo htmlspecialchars($job->title); ?>
                </div>
                <div class="modern-card-body">
                    <?php if (!empty($job->description)): ?>
                        <p style="color: #4a5568; line-height: 1.8; margin-bottom: 2rem;">
                            <?php echo nl2br(htmlspecialchars($job->description)); ?>
                        </p>
                    <?php endif; ?>
                    
                    <!-- Location -->
                    <div class="detail-section">
                        <h5><i class="fas fa-map-marker-alt"></i> Location</h5>
                        <p class="mb-1" style="font-weight: 600;"><?php echo htmlspecialchars($job->address); ?></p>
                        <p class="mb-0 text-muted"><?php echo htmlspecialchars($job->city . ', ' . $job->state); ?></p>
                    </div>
                    
                    <!-- Property Details -->
                    <div class="detail-section">
                        <h5><i class="fas fa-info-circle"></i> Property Details</h5>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-item-label">Rooms</div>
                                <div class="detail-item-value">
                                    <?php 
                                    $rooms = json_decode($job->rooms ?? '[]', true);
                                    if (is_array($rooms) && !empty($rooms)) {
                                        echo implode(', ', $rooms);
                                    } else {
                                        echo $job->rooms ?? 'Not specified';
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-item-label">Duration</div>
                                <div class="detail-item-value"><?php echo ($job->estimated_duration / 60); ?> hours</div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-item-label">Pets</div>
                                <div class="detail-item-value"><?php echo ($job->pets == 1 || $job->pets == '1') ? '🐾 Yes' : '❌ No'; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional Services -->
                    <?php 
                    $extras = json_decode($job->extras ?? '[]', true);
                    
                    // Separate STR-specific services from general services
                    $str_services = ['wash_linens', 'check_dishes', 'clean_refrigerator', 'ensure_supplies', 
                                     'reset_beds', 'reset_kitchen', 'check_amenities', 'leave_goodies',
                                     'reset_makeup', 'reset_shower', 'reset_coffee', 'check_tv',
                                     'check_wifi', 'check_keys', 'reset_pillows', 'reset_table',
                                     'photo_documentation', 'check_hvac', 'ensure_quiet', 'check_smoke_detector'];
                    
                    $general_services = [];
                    $str_only_services = [];
                    
                    if (is_array($extras) && !empty($extras)) {
                        foreach ($extras as $extra) {
                            if (in_array($extra, $str_services)) {
                                $str_only_services[] = $extra;
                            } else {
                                $general_services[] = $extra;
                            }
                        }
                    }
                    
                    $all_extras_labels = [
                        // General Services
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
                        // STR Services
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
                    ?>
                    
                    <?php if (!empty($general_services)): ?>
                        <div class="detail-section">
                            <h5><i class="fas fa-list-check"></i> Additional Services Required</h5>
                            <div class="extras-container">
                                <?php 
                                foreach ($general_services as $extra) {
                                    $label = isset($all_extras_labels[$extra]) ? $all_extras_labels[$extra] : str_replace('_', ' ', ucwords($extra, '_'));
                                    echo '<span class="extra-badge"><i class="fas fa-check-circle"></i>' . htmlspecialchars($label) . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($str_only_services)): ?>
                        <div class="detail-section" style="background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 152, 0, 0.1) 100%); border-color: rgba(255, 193, 7, 0.3);">
                            <h5 style="color: #f57c00;"><i class="fas fa-home"></i> STR-Specific Services Required</h5>
                            <div class="extras-container">
                                <?php 
                                foreach ($str_only_services as $extra) {
                                    $label = isset($all_extras_labels[$extra]) ? $all_extras_labels[$extra] : str_replace('_', ' ', ucwords($extra, '_'));
                                    echo '<span class="extra-badge" style="background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);"><i class="fas fa-check-circle"></i>' . htmlspecialchars($label) . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- STR Requirements (Host's Text) -->
                    <?php if ($is_str && !empty($job->special_instructions)): ?>
                        <div class="str-alert">
                            <div class="str-alert-title">
                                <i class="fas fa-exclamation-triangle"></i>
                                Host's STR Requirements & Notes
                            </div>
                            <div class="str-warning-box">
                                <i class="fas fa-info-circle me-2"></i>
                                These are MANDATORY requirements from the host. Failure to follow may result in job rejection.
                            </div>
                            <div class="str-content">
                                <?php echo nl2br(htmlspecialchars($job->special_instructions)); ?>
                            </div>
                        </div>
                    <?php elseif (!empty($job->special_instructions)): ?>
                        <div class="detail-section">
                            <h5><i class="fas fa-clipboard-list"></i> Special Instructions from Host</h5>
                            <p style="margin: 0; line-height: 1.8;"><?php echo nl2br(htmlspecialchars($job->special_instructions)); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Host Info & Reviews -->
        <div>
            <!-- Host Info -->
            <div class="host-card">
                <div class="host-header">
                    <div class="host-info">
                        <div class="host-avatar">
                            <?php echo strtoupper(substr($job->host_first_name, 0, 1) . substr($job->host_last_name, 0, 1)); ?>
                        </div>
                        <div class="host-details">
                            <h3><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></h3>
                            <div class="host-rating">
                                <div class="stars">
                                    <?php
                                    // Use the overall rating from review stats (calculated from ALL reviews, not just displayed ones)
                                    $rating = $host_review_stats['overall_average'] ?? 0;
                                    $total_reviews = $host_review_stats['total_reviews'] ?? 0;
                                    
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= floor($rating)) {
                                            echo '<i class="fas fa-star"></i>';
                                        } elseif ($i - 0.5 <= $rating) {
                                            echo '<i class="fas fa-star-half-alt"></i>';
                                        } else {
                                            echo '<i class="far fa-star"></i>';
                                        }
                                    }
                                    ?>
                                </div>
                                <span><?php echo number_format($rating, 1); ?></span>
                                <span style="opacity: 0.8;">(<?php echo $total_reviews; ?> reviews)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="host-body">
                    <div class="host-contact">
                        <div class="contact-item">
                            <i class="fas fa-user"></i>
                            <span>@<?php echo htmlspecialchars($job->host_username); ?></span>
                        </div>
                        <?php if (!empty($job->host_email)): ?>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:<?php echo htmlspecialchars($job->host_email); ?>"><?php echo htmlspecialchars($job->host_email); ?></a>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($job->host_phone)): ?>
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:<?php echo htmlspecialchars($job->host_phone); ?>"><?php echo htmlspecialchars($job->host_phone); ?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Reviews -->
            <div class="reviews-section">
                <div class="reviews-header">
                    <i class="fas fa-star"></i>
                    Host Reviews (<?php echo $host_review_stats['total_reviews'] ?? 0; ?>)
                </div>
                <div class="reviews-scroll">
                    <?php if (!empty($host_reviews)): ?>
                        <?php foreach ($host_reviews as $review): ?>
                            <?php
                            // Calculate overall rating from category ratings
                            $prof = $review->professionalism_rating ?? 0;
                            $quality = $review->quality_rating ?? 0;
                            $comm = $review->communication_rating ?? 0;
                            $punct = $review->punctuality_rating ?? 0;
                            
                            $total_ratings = 0;
                            $sum_ratings = 0;
                            
                            if ($prof > 0) { $sum_ratings += $prof; $total_ratings++; }
                            if ($quality > 0) { $sum_ratings += $quality; $total_ratings++; }
                            if ($comm > 0) { $sum_ratings += $comm; $total_ratings++; }
                            if ($punct > 0) { $sum_ratings += $punct; $total_ratings++; }
                            
                            $overall_rating = $total_ratings > 0 ? ($sum_ratings / $total_ratings) : 0;
                            
                            // Get reviewer name
                            $reviewer_name = $review->reviewer_username ?? $review->reviewer_first_name ?? 'Anonymous';
                            ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div>
                                        <div class="review-rating">
                                            <div class="stars">
                                                <?php
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= floor($overall_rating)) {
                                                        echo '<i class="fas fa-star"></i>';
                                                    } elseif ($i - 0.5 <= $overall_rating) {
                                                        echo '<i class="fas fa-star-half-alt"></i>';
                                                    } else {
                                                        echo '<i class="far fa-star"></i>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <span style="font-weight: 600; margin-left: 0.5rem;"><?php echo number_format($overall_rating, 1); ?></span>
                                        </div>
                                        <div style="margin-top: 0.25rem; font-size: 0.85rem; color: #6c757d;">
                                            by <?php echo htmlspecialchars($reviewer_name); ?>
                                        </div>
                                    </div>
                                    <div class="review-date"><?php echo time_ago($review->created_at); ?></div>
                                </div>
                                
                                <?php if (!empty($review->public_comment)): ?>
                                    <div class="review-text">
                                        "<?php echo htmlspecialchars($review->public_comment); ?>"
                                    </div>
                                <?php elseif (!empty($review->review_text)): ?>
                                    <div class="review-text">
                                        "<?php echo htmlspecialchars($review->review_text); ?>"
                                    </div>
                                <?php endif; ?>
                                
                                <div class="review-categories">
                                    <?php if ($prof > 0): ?>
                                        <div class="category-rating">
                                            <span>Professionalism</span>
                                            <strong><?php echo number_format($prof, 1); ?>/5</strong>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($quality > 0): ?>
                                        <div class="category-rating">
                                            <span>Quality</span>
                                            <strong><?php echo number_format($quality, 1); ?>/5</strong>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($comm > 0): ?>
                                        <div class="category-rating">
                                            <span>Communication</span>
                                            <strong><?php echo number_format($comm, 1); ?>/5</strong>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($punct > 0): ?>
                                        <div class="category-rating">
                                            <span>Punctuality</span>
                                            <strong><?php echo number_format($punct, 1); ?>/5</strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php 
                        $total_reviews = $host_review_stats['total_reviews'] ?? 0;
                        $displayed_reviews = count($host_reviews);
                        if ($total_reviews > $displayed_reviews): 
                        ?>
                            <div style="text-align: center; padding: 1rem; color: #6c757d; font-size: 0.9rem; border-top: 1px solid #dee2e6;">
                                Showing last <?php echo $displayed_reviews; ?> of <?php echo $total_reviews; ?> total reviews
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="no-reviews">
                            <i class="fas fa-star-half-alt"></i>
                            <p>No reviews yet</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Documentation Reminder -->
    <div class="video-reminder-alert">
        <div class="alert-icon">
            <i class="fas fa-video"></i>
        </div>
        <div class="alert-content">
            <h4>📹 Important Reminder: Video Documentation Required</h4>
            <p>Before entering the OTP code and starting this job, make sure you're ready to:</p>
            <ul>
                <li><strong>Record a video</strong> of the property's current condition before you start cleaning</li>
                <li>Show all rooms, surfaces, and areas that will be cleaned</li>
                <li>Keep the video as evidence of the property's initial state</li>
                <li>This protects both you and the host in case of any disputes</li>
            </ul>
            <div class="alert-footer">
                <i class="fas fa-shield-alt"></i>
                Video documentation is your protection and professional standard
            </div>
        </div>
    </div>

    <!-- OTP Section -->
    <div class="otp-card">
        <div class="otp-header">
            <h3>
                <i class="fas fa-key me-2"></i>
                Enter Service Code to Start
            </h3>
            <p class="text-muted">
                Enter the 6-digit code provided by the host to begin this cleaning job
            </p>
        </div>
        
        <form method="POST" action="<?php echo base_url('cleaner/start_job'); ?>" class="otp-form">
            <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
            
            <div class="otp-input-group">
                <label for="otp_code" class="form-label" style="text-align: center; display: block; margin-bottom: 1rem; font-weight: 600; color: #667eea;">Service Code</label>
                <input type="text" 
                       class="form-control otp-input" 
                       id="otp_code" 
                       name="otp_code" 
                       maxlength="6" 
                       placeholder="000000"
                       pattern="[0-9]{6}"
                       required
                       autocomplete="off">
            </div>
            
            <div class="otp-actions">
                <a href="<?php echo base_url('cleaner/assigned_jobs'); ?>" class="btn btn-back">
                    <i class="fas fa-arrow-left me-1"></i>
                    Back
                </a>
                <button type="submit" class="btn btn-start">
                    <i class="fas fa-play-circle me-1"></i>
                    Start Job
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const otpInput = document.getElementById('otp_code');
    
    if (otpInput) {
        // Check if there's an error (either from flash message or URL parameter)
        const alertElement = document.querySelector('.alert-danger');
        const urlParams = new URLSearchParams(window.location.search);
        const hasError = alertElement || urlParams.get('error') === 'invalid_otp';
        
        if (hasError) {
            console.log('OTP Error detected - showing visual feedback');
            otpInput.classList.add('error-shake');
            otpInput.value = ''; // Clear the input
            
            // Add red border
            otpInput.style.borderColor = '#dc3545';
            otpInput.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
            
            // Remove shake animation after it completes
            setTimeout(function() {
                otpInput.classList.remove('error-shake');
            }, 600);
            
            // Focus and select after animation
            setTimeout(function() {
                otpInput.focus();
                otpInput.select();
            }, 700);
        } else {
            // Normal focus on input when page loads
            otpInput.focus();
        }
        
        // Only allow numbers
        otpInput.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
            
            // Remove error styling when user starts typing
            e.target.classList.remove('is-invalid');
            e.target.style.borderColor = '#667eea';
            e.target.style.boxShadow = '0 5px 15px rgba(102, 126, 234, 0.2)';
        });
        
        // Handle paste
        otpInput.addEventListener('paste', function(e) {
            setTimeout(function() {
                otpInput.value = otpInput.value.replace(/[^0-9]/g, '').substring(0, 6);
            }, 10);
        });
        
        // Auto-submit when 6 digits are entered
        otpInput.addEventListener('input', function(e) {
            if (e.target.value.length === 6) {
                setTimeout(function() {
                    if (confirm('⚠️ Ready to Start?\n\n✓ Have you recorded the pre-cleaning video?\n✓ Are you at the property location?\n\nThis will mark the job as in progress.')) {
                        console.log('Form submitting with OTP:', otpInput.value);
                        document.querySelector('.otp-form').submit();
                    } else {
                        // Clear the input if they cancel
                        console.log('User cancelled submission');
                        otpInput.value = '';
                        otpInput.focus();
                    }
                }, 500);
            }
        });
    }
    
    // Add manual submit handler for debugging
    const form = document.querySelector('.otp-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            console.log('Job ID:', document.querySelector('[name="job_id"]').value);
            console.log('OTP Code:', document.querySelector('[name="otp_code"]').value);
            console.log('Form action:', form.action);
            // Don't prevent default - let form submit normally
        });
    }
    
    // Scroll to alert if it exists
    const alertElement = document.querySelector('.alert');
    if (alertElement) {
        console.log('Alert found on page load');
        setTimeout(function() {
            alertElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    }
});
</script>

<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
    20%, 40%, 60%, 80% { transform: translateX(10px); }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.error-shake {
    animation: shake 0.6s;
}

.otp-input.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}
</style>
