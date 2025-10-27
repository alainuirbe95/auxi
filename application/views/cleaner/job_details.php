<?php
// Helper function for time formatting
if (!function_exists('time_ago')) {
    function time_ago($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

// Calculate cleaner payout
$base_charge = isset($pricing_params['base_charge']) ? $pricing_params['base_charge'] : 25.00;
$tax_percent = isset($pricing_params['tax_percent']) ? $pricing_params['tax_percent'] : 10;
$app_percent = isset($pricing_params['app_percent']) ? $pricing_params['app_percent'] : 15;

$suggested_price = floatval($job->suggested_price);
$tax_amount = $suggested_price * ($tax_percent / 100);
$app_amount = $suggested_price * ($app_percent / 100);
$cleaner_payout = $suggested_price - $base_charge - $tax_amount - $app_amount;
?>

<style>
/* Container styling for wider desktop view */
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

/* Modern Job Details Styles */
.job-details-container {
    padding: 2rem 0;
}

.job-header-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.job-header-section::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.job-title-large {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.job-price-large {
    font-size: 3rem;
    font-weight: 900;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
}

.job-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 2rem;
}

.job-meta-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.1rem;
    position: relative;
    z-index: 1;
}

.job-meta-item i {
    font-size: 1.5rem;
    opacity: 0.9;
}

.job-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.job-main {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.job-sidebar {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.75rem;
    margin-top: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.section-title:first-child {
    margin-top: 0;
}

.section-title i {
    color: #667eea;
}

.job-description-full {
    color: #666;
    line-height: 1.8;
    font-size: 1.1rem;
    margin-bottom: 2rem;
}

.job-requirements {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.requirements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 0.75rem;
}

.requirement-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: white;
    border-radius: 8px;
    border-left: 3px solid #667eea;
}

.requirement-item i {
    color: #667eea;
    width: 20px;
}

.host-card {
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.host-avatar-large {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 800;
    font-size: 2rem;
    margin: 0 auto 1rem;
}

.host-name {
    font-size: 1.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 0.5rem;
}

.host-username {
    color: #666;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.host-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-top: 1.5rem;
}

.host-stat {
    text-align: center;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.host-stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #667eea;
    margin-bottom: 0.25rem;
}

.host-stat-label {
    font-size: 0.9rem;
    color: #666;
}

.offer-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2.5rem 2rem;
    box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4);
    position: relative;
    overflow: hidden;
    border: 3px solid rgba(255, 255, 255, 0.3);
    animation: pulse-offer 3s ease-in-out infinite;
}

@keyframes pulse-offer {
    0%, 100% {
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4), 0 0 30px rgba(102, 126, 234, 0.3);
    }
    50% {
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.6), 0 0 40px rgba(102, 126, 234, 0.5);
    }
}

.offer-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    animation: rotate-gradient 10s linear infinite;
}

@keyframes rotate-gradient {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.offer-header {
    position: relative;
    z-index: 2;
    text-align: center;
    margin-bottom: 2rem;
}

.offer-header-icon {
    font-size: 3rem;
    color: white;
    margin-bottom: 1rem;
    animation: bounce-icon 2s ease-in-out infinite;
}

@keyframes bounce-icon {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.offer-header-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: white;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.offer-header-subtitle {
    font-size: 1rem;
    color: rgba(255, 255, 255, 0.95);
    font-weight: 500;
}

.offer-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
    position: relative;
    z-index: 2;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-weight: 700;
    color: white;
    font-size: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-input {
    padding: 1rem 1.25rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    font-size: 1.05rem;
    transition: all 0.3s ease;
    background: rgba(255, 255, 255, 0.95);
    font-weight: 600;
}

.form-input:focus {
    border-color: white;
    outline: none;
    box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3);
    background: white;
}

.form-select {
    padding: 1rem 1.25rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 12px;
    font-size: 1.05rem;
    background: rgba(255, 255, 255, 0.95);
    transition: all 0.3s ease;
    font-weight: 600;
}

.form-select:focus {
    border-color: white;
    outline: none;
    box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.3);
    background: white;
}

.form-helper-text {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.9rem;
    font-weight: 500;
    margin-top: 0.5rem;
}

.btn-submit-offer {
    background: white;
    color: #667eea;
    border: none;
    padding: 1.25rem 2.5rem;
    border-radius: 14px;
    font-weight: 800;
    font-size: 1.2rem;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.btn-submit-offer:hover {
    background: linear-gradient(135deg, #38d9a9 0%, #20c997 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 12px 30px rgba(56, 217, 169, 0.4);
}

.btn-submit-offer i {
    font-size: 1.3rem;
}

.btn-submit-offer:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}


.back-button {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #e9ecef;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 2rem;
}

.back-button:hover {
    background: #e9ecef;
    color: #333;
    text-decoration: none;
    transform: translateY(-1px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .job-content {
        grid-template-columns: 1fr;
    }
    
    .job-meta {
        grid-template-columns: 1fr;
    }
    
    .requirements-grid {
        grid-template-columns: 1fr;
    }
    
    .host-stats {
        grid-template-columns: 1fr;
    }
    
    .job-title-large {
        font-size: 2rem;
    }
    
    .job-price-large {
        font-size: 2.5rem;
    }
    
    .job-details-grid {
        grid-template-columns: 1fr;
    }
    
    .extras-list {
    justify-content: center;
    }
}


/* Host Rating Box in Sidebar */
.host-rating-box {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border-radius: 12px;
    padding: 1rem;
    margin: 1rem 0;
    text-align: center;
}

.rating-number-medium {
    font-size: 2.5rem;
    font-weight: 700;
    color: #f57c00;
    line-height: 1;
}

.rating-stars-medium {
    font-size: 1.2rem;
    margin: 0.5rem 0;
}

.rating-stars-medium i.filled {
    color: #ffc107;
}

.rating-stars-medium i.empty {
    color: #dee2e6;
}

.rating-count-text {
    font-size: 0.9rem;
    color: #6c757d;
    font-weight: 600;
}

/* Scrollable Reviews Container */
.reviews-scroll-container {
    max-height: 400px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

.reviews-scroll-container::-webkit-scrollbar {
    width: 6px;
}

.reviews-scroll-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.reviews-scroll-container::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
}

.reviews-scroll-container::-webkit-scrollbar-thumb:hover {
    background: #764ba2;
}

.review-item-mini {
    background: #f8f9fa;
    padding: 0.85rem;
    border-radius: 8px;
    border-left: 3px solid #ffc107;
    margin-bottom: 0.75rem;
}

.review-item-mini:last-child {
    margin-bottom: 0;
}

/* STR Badge */
.str-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #000;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.9rem;
    box-shadow: 0 4px 10px rgba(255, 215, 0, 0.3);
    border: 2px solid #ffc107;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.str-badge i {
    font-size: 1.1rem;
}

/* Property Type Badge in Header */
.property-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.25rem;
    border-radius: 20px;
    font-weight: 700;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.property-type-str {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #000;
    border: 2px solid #ffc107;
}

.property-type-residential {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #1565c0;
    border: 2px solid #42a5f5;
}

/* Additional Job Details Section */
.job-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.job-detail-box {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.25rem;
    border-radius: 12px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
}

.job-detail-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.job-detail-label {
    font-size: 0.85rem;
    color: #666;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.job-detail-label i {
    color: #667eea;
    font-size: 0.9rem;
}

.job-detail-value {
        font-size: 1.1rem;
    color: #333;
    font-weight: 700;
}

.extras-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 0.75rem;
}

.extra-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
}

.extra-badge i {
    font-size: 0.8rem;
}

</style>

<div class="container-fluid">
    <div class="job-details-container">
    <a href="<?php echo base_url('cleaner/jobs'); ?>" class="back-button">
        <i class="fas fa-arrow-left"></i>
        Back to Jobs
    </a>

    <!-- Job Header -->
    <div class="job-header-section">
        <h1 class="job-title-large"><?php echo htmlspecialchars($job->title); ?></h1>
        
        <!-- Property Type Badge -->
        <?php 
        $is_str = (isset($job->property_type) && strtolower($job->property_type) === 'str');
        $property_type_display = $is_str ? 'Short Term Rental' : 'Residential';
        ?>
        <div style="margin-bottom: 1rem;">
            <span class="property-type-badge <?php echo $is_str ? 'property-type-str' : 'property-type-residential'; ?>">
                <i class="fas fa-<?php echo $is_str ? 'home' : 'house-user'; ?>"></i>
                <?php echo $property_type_display; ?>
            </span>
        </div>
        
        <!-- Cleaner Payout (Primary Display) -->
        <div style="margin-bottom: 1rem;">
            <div style="font-size: 1rem; font-weight: 600; opacity: 0.9; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 1px;">
                💰 Your Estimated Payout
            </div>
            <div class="job-price-large" style="color: #43e97b;">
                $<?php echo number_format($cleaner_payout, 2); ?>
            </div>
        </div>
        
        <div class="job-meta">
            <div class="job-meta-item">
                <i class="fas fa-calendar-alt"></i>
                <span>
                    <?php 
                    if (isset($job->scheduled_date) && isset($job->scheduled_time)) {
                        $datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                        echo date('M j, Y g:i A', strtotime($datetime));
                    } elseif (isset($job->date_time)) {
                        echo date('M j, Y g:i A', strtotime($job->date_time));
                    } else {
                        echo 'Flexible Date';
                    }
                    ?>
                </span>
            </div>
            <div class="job-meta-item">
                <i class="fas fa-map-marker-alt"></i>
                <span><?php echo htmlspecialchars($job->city . ', ' . $job->state); ?></span>
            </div>
            <div class="job-meta-item">
                <i class="fas fa-home"></i>
                <span>
                    <?php 
                    $rooms = is_string($job->rooms) ? json_decode($job->rooms, true) : $job->rooms;
                    if (is_array($rooms)) {
                        echo htmlspecialchars(implode(', ', $rooms)) . ' Rooms';
                    } else {
                        echo htmlspecialchars($job->rooms) . ' Rooms';
                    }
                    ?>
                </span>
            </div>
            <div class="job-meta-item">
                <i class="fas fa-clock"></i>
                <span>Posted <?php echo time_ago($job->created_at); ?></span>
            </div>
            <?php if (isset($job->estimated_duration) && $job->estimated_duration > 0): ?>
            <div class="job-meta-item">
                <i class="fas fa-hourglass-half"></i>
                <span>
                    <?php 
                    $hours = floor($job->estimated_duration / 60);
                    $minutes = $job->estimated_duration % 60;
                    if ($hours > 0 && $minutes > 0) {
                        echo $hours . 'h ' . $minutes . 'm estimated';
                    } elseif ($hours > 0) {
                        echo $hours . ' hour' . ($hours > 1 ? 's' : '') . ' estimated';
                    } else {
                        echo $minutes . ' minutes estimated';
                    }
                    ?>
                </span>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="job-content">
        <!-- Main Job Information -->
        <div class="job-main">
            <h2 class="section-title">
                <i class="fas fa-clipboard-list"></i>
                Job Description
            </h2>
            <p class="job-description-full">
                <?php echo nl2br(htmlspecialchars($job->description)); ?>
            </p>

            <h2 class="section-title">
                <i class="fas fa-info-circle"></i>
                Job Details
            </h2>
            <div class="job-details-grid">
                <!-- Full Address -->
                <div class="job-detail-box">
                    <div class="job-detail-label">
                        <i class="fas fa-map-marker-alt"></i>
                        Location
                    </div>
                    <?php if ($is_str && !$is_assigned): ?>
                        <!-- STR + Not assigned = Show only city/state -->
                        <div class="job-detail-value" style="display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-lock" style="color: #9c27b0; font-size: 1rem;"></i>
                            <?php echo htmlspecialchars($job->city . ', ' . $job->state); ?>
                        </div>
                        <div style="color: #9c27b0; font-size: 0.75rem; margin-top: 0.5rem; font-weight: 600;">
                            🔒 Full address visible after assignment
                        </div>
                    <?php else: ?>
                        <!-- Non-STR or Assigned = Show full address -->
                        <div class="job-detail-value">
                            <?php echo htmlspecialchars($job->address ?? 'Address not specified'); ?>
                        </div>
                        <div style="color: #666; font-size: 0.85rem; margin-top: 0.25rem;">
                            <?php echo htmlspecialchars($job->city . ', ' . $job->state); ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Property Type -->
                <div class="job-detail-box" style="<?php echo $is_str ? 'border-left-color: #ff9800; background: linear-gradient(135deg, #fff3e0 0%, #ffebee 100%);' : ''; ?>">
                    <div class="job-detail-label">
                        <i class="fas fa-building"></i>
                        Property Type
                    </div>
                    <div class="job-detail-value">
                        <?php echo $is_str ? '🏠 Short Term Rental' : '🏡 Residential'; ?>
                    </div>
                    <?php if ($is_str): ?>
                    <div style="margin-top: 0.5rem; font-size: 0.8rem; color: #e65100; font-weight: 600;">
                        <i class="fas fa-star"></i> Guest-Ready Standards Required
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Estimated Duration -->
                <?php if (isset($job->estimated_duration) && $job->estimated_duration > 0): ?>
                <div class="job-detail-box">
                    <div class="job-detail-label">
                        <i class="fas fa-clock"></i>
                        Estimated Duration
                    </div>
                    <div class="job-detail-value">
                        <?php 
                        $hours = floor($job->estimated_duration / 60);
                        $minutes = $job->estimated_duration % 60;
                        if ($hours > 0 && $minutes > 0) {
                            echo $hours . 'h ' . $minutes . 'm';
                        } elseif ($hours > 0) {
                            echo $hours . ' hour' . ($hours > 1 ? 's' : '');
                        } else {
                            echo $minutes . ' minutes';
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Pets -->
                <div class="job-detail-box">
                    <div class="job-detail-label">
                        <i class="fas fa-paw"></i>
                        Pets
                    </div>
                    <div class="job-detail-value">
                        <?php echo ($job->pets == 1 || $job->pets == '1') ? '🐾 Yes' : '❌ No'; ?>
                    </div>
                </div>
            </div>

            <!-- Rooms to Clean -->
            <h2 class="section-title">
                <i class="fas fa-door-open"></i>
                Rooms to Clean
            </h2>
            <div class="job-requirements">
                <div class="requirements-grid">
                            <?php 
                            $rooms = is_string($job->rooms) ? json_decode($job->rooms, true) : $job->rooms;
                    if (is_array($rooms)):
                        foreach ($rooms as $room):
                    ?>
                        <div class="requirement-item">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo htmlspecialchars($room); ?></span>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                        <div class="requirement-item">
                            <i class="fas fa-check-circle"></i>
                            <span><?php echo htmlspecialchars($job->rooms); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Additional Services -->
            <?php if (!empty($job->extras)): ?>
            <h2 class="section-title">
                <i class="fas fa-plus-circle"></i>
                Additional Services Required
            </h2>
            <div class="extras-list">
                                <?php 
                                $extras = is_string($job->extras) ? json_decode($job->extras, true) : $job->extras;
                if (is_array($extras)):
                    // Define icons for different services
                    $service_icons = [
                        'window' => 'fa-window-maximize',
                        'deep' => 'fa-broom',
                        'fridge' => 'fa-snowflake',
                        'refrigerator' => 'fa-snowflake',
                        'oven' => 'fa-fire',
                        'carpet' => 'fa-th',
                        'dish' => 'fa-utensils',
                        'linen' => 'fa-bed',
                        'towel' => 'fa-bath',
                        'bed' => 'fa-bed',
                        'goodies' => 'fa-gift',
                        'supplies' => 'fa-box',
                        'default' => 'fa-check'
                    ];
                    
                    foreach ($extras as $extra):
                        $extra_lower = strtolower($extra);
                        $icon = 'fa-check';
                        foreach ($service_icons as $keyword => $service_icon) {
                            if (strpos($extra_lower, $keyword) !== false) {
                                $icon = $service_icon;
                                break;
                            }
                        }
                ?>
                    <span class="extra-badge">
                        <i class="fas <?php echo $icon; ?>"></i>
                        <?php echo htmlspecialchars($extra); ?>
                            </span>
                <?php 
                    endforeach;
                endif;
                ?>
                        </div>
                    <?php endif; ?>

            <!-- STR Specific Requirements -->
            <?php if ($is_str): ?>
            <h2 class="section-title" style="color: #e65100;">
                <i class="fas fa-list-check"></i>
                STR Cleaning Standards & Expectations
            </h2>
            <div style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #ff9800; margin-bottom: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                    <div style="background: white; padding: 1rem; border-radius: 8px;">
                        <h4 style="margin: 0 0 0.75rem 0; color: #e65100; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-sparkles" style="color: #ffd700;"></i> Presentation Standards
                        </h4>
                        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem; line-height: 1.7; color: #555;">
                            <li>Hotel-quality cleanliness expected</li>
                            <li>All surfaces dust-free and spotless</li>
                            <li>Fresh, welcoming appearance</li>
                            <li>Photo-ready presentation</li>
                        </ul>
                    </div>
                    
                    <div style="background: white; padding: 1rem; border-radius: 8px;">
                        <h4 style="margin: 0 0 0.75rem 0; color: #e65100; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-clock" style="color: #ff9800;"></i> Time Sensitivity
                        </h4>
                        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem; line-height: 1.7; color: #555;">
                            <li>Quick turnaround between guests</li>
                            <li>Punctuality is critical</li>
                            <li>Check-in times must be met</li>
                            <li>Flexibility may be needed</li>
                        </ul>
                    </div>
                    
                    <div style="background: white; padding: 1rem; border-radius: 8px;">
                        <h4 style="margin: 0 0 0.75rem 0; color: #e65100; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-box" style="color: #ff6f00;"></i> Supply Management
                        </h4>
                        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem; line-height: 1.7; color: #555;">
                            <li>Host provides consumables</li>
                            <li>Verify sufficient supplies</li>
                            <li>Report any shortages immediately</li>
                            <li>Ensure amenities are stocked</li>
                        </ul>
                    </div>
                    
                    <div style="background: white; padding: 1rem; border-radius: 8px;">
                        <h4 style="margin: 0 0 0.75rem 0; color: #e65100; font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-check-double" style="color: #43a047;"></i> Quality Checklist
                        </h4>
                        <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem; line-height: 1.7; color: #555;">
                            <li>All linens fresh and pristine</li>
                            <li>Bathrooms sanitized thoroughly</li>
                            <li>Kitchen guest-ready</li>
                            <li>Floors vacuumed/mopped spotless</li>
                        </ul>
                    </div>
                </div>
                
                <div style="margin-top: 1rem; padding: 1rem; background: #fff9c4; border-radius: 8px; border: 2px dashed #ff9800; text-align: center;">
                    <p style="margin: 0; font-weight: 700; color: #e65100; font-size: 0.95rem;">
                        <i class="fas fa-exclamation-circle"></i> 
                        STR properties are subject to guest reviews. Your work directly impacts the host's ratings and business success.
                    </p>
                </div>
                        </div>
                    <?php endif; ?>

            <!-- Host Requirements/Notes -->
            <?php if ($is_str): ?>
                <!-- STR Requirements - Only visible if assigned -->
                <h2 class="section-title" style="color: #e65100;">
                    <i class="fas fa-<?php echo $is_assigned ? 'exclamation-triangle' : 'lock'; ?>"></i>
                    <?php echo $is_assigned ? 'STR Requirements from Host' : 'STR Requirements (Private)'; ?>
                </h2>
                
                <?php if ($is_assigned): ?>
                    <!-- Cleaner is assigned - show requirements -->
                    <?php if (!empty($job->special_instructions)): ?>
                    <div style="background: linear-gradient(135deg, #ffebee 0%, #fff3e0 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #d32f2f; border: 3px solid #d32f2f; box-shadow: 0 8px 20px rgba(211, 47, 47, 0.2);">
                        <div style="background: #ffcdd2; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; border-left: 4px solid #d32f2f;">
                            <p style="margin: 0; font-weight: 700; color: #b71c1c; font-size: 0.95rem;">
                                <i class="fas fa-star"></i> These are MANDATORY requirements for this STR property. Failure to follow may result in job rejection and negative review.
                            </p>
                        </div>
                        <div style="color: #333; line-height: 1.8; font-size: 1.05rem; font-weight: 500;">
                            <?php echo nl2br(htmlspecialchars($job->special_instructions)); ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #2196f3; text-align: center;">
                        <p style="margin: 0; color: #1565c0; font-size: 1rem; font-weight: 600;">
                            <i class="fas fa-info-circle"></i> The host has not added specific requirements. Follow the standard STR cleaning guidelines above.
                        </p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <!-- Cleaner is NOT assigned - show privacy notice -->
                    <div style="background: linear-gradient(135deg, #f3e5f5 0%, #e1bee7 100%); padding: 2rem; border-radius: 12px; border: 3px solid #9c27b0; text-align: center; box-shadow: 0 8px 20px rgba(156, 39, 176, 0.2);">
                        <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; box-shadow: 0 4px 15px rgba(156, 39, 176, 0.3);">
                            <i class="fas fa-lock" style="font-size: 2.5rem; color: white;"></i>
                </div>
                        <h4 style="color: #6a1b9a; font-weight: 800; margin-bottom: 1rem; font-size: 1.3rem;">
                            🔒 Requirements Protected
                        </h4>
                        <p style="color: #555; font-size: 1rem; line-height: 1.7; margin-bottom: 1rem;">
                            <strong>Privacy Notice:</strong> The host's specific STR requirements and special instructions will be revealed once your offer is accepted and you're assigned to this job.
                        </p>
                        <div style="background: rgba(156, 39, 176, 0.1); padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                            <p style="margin: 0; color: #6a1b9a; font-weight: 600; font-size: 0.95rem;">
                                <i class="fas fa-shield-alt"></i> This protects the host's privacy and property details
                            </p>
            </div>
                    </div>
                <?php endif; ?>
                
            <?php elseif (!empty($job->special_instructions)): ?>
                <!-- For non-STR jobs, always show special notes -->
                <h2 class="section-title">
                    <i class="fas fa-sticky-note"></i>
                    Special Notes from Host
                </h2>
                <div style="background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%); padding: 1.5rem; border-radius: 12px; border-left: 4px solid #ff9800;">
                    <div style="color: #333; line-height: 1.8; font-size: 1rem;">
                        <?php echo nl2br(htmlspecialchars($job->special_instructions)); ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="job-sidebar">
            <!-- Host Information -->
            <div class="host-card">
                <div class="host-avatar-large">
                    <?php echo strtoupper(substr($job->host_first_name, 0, 1) . substr($job->host_last_name, 0, 1)); ?>
                </div>
                <h3 class="host-name"><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></h3>
                <p class="host-username">@<?php echo htmlspecialchars($job->host_username); ?></p>
                
                <!-- Host Rating Display -->
                <?php if (isset($host_review_stats) && $host_review_stats['total_reviews'] > 0): ?>
                <div class="host-rating-box">
                    <div class="rating-number-medium"><?php echo number_format($host_review_stats['overall_average'], 1); ?></div>
                    <div class="rating-stars-medium">
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= round($host_review_stats['overall_average']) ? 'filled' : 'empty'; ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="rating-count-text"><?php echo $host_review_stats['total_reviews']; ?> reviews</div>
                </div>
                <?php else: ?>
                <div class="host-rating-box">
                    <p class="text-muted mb-1" style="font-size: 0.9rem;">No reviews yet</p>
                </div>
                <?php endif; ?>
                
                <div class="host-stats">
                    <div class="host-stat">
                        <div class="host-stat-number"><?php echo $host_stats['total_jobs'] ?? 0; ?></div>
                        <div class="host-stat-label">Jobs Posted</div>
                    </div>
                    <div class="host-stat">
                        <div class="host-stat-number"><?php echo $host_stats['completed_jobs'] ?? 0; ?></div>
                        <div class="host-stat-label">Completed</div>
                    </div>
                </div>
                
                <!-- Host Bio Summary -->
                <?php if (!empty($host_profile->bio)): ?>
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #f0f0f0;">
                    <h6 style="font-size: 0.95rem; font-weight: 700; color: #667eea; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-quote-left"></i> About
                    </h6>
                    <p style="color: #555; font-size: 0.95rem; line-height: 1.6; margin: 0;">
                        <?php 
                        $bio = htmlspecialchars($host_profile->bio);
                        echo strlen($bio) > 150 ? substr($bio, 0, 150) . '...' : $bio; 
                        ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <!-- Recent Reviews (Scrollable) -->
                <?php if (!empty($host_reviews) && count($host_reviews) > 0): ?>
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid #f0f0f0;">
                    <h6 style="font-size: 0.95rem; font-weight: 700; color: #667eea; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-comments"></i> Recent Reviews (<?php echo count($host_reviews); ?>)
                    </h6>
                    <div class="reviews-scroll-container">
                        <?php foreach ($host_reviews as $review): ?>
                        <div class="review-item-mini">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <span style="font-weight: 600; color: #333; font-size: 0.85rem;">
                                    <?php echo htmlspecialchars($review->reviewer_name ?? 'Anonymous'); ?>
                                </span>
                                <div style="color: #ffc107; font-size: 0.75rem;">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= $review->overall_rating ? '' : 'text-muted'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p style="color: #666; font-size: 0.8rem; line-height: 1.4; margin: 0;">
                                <?php echo nl2br(htmlspecialchars($review->public_comment)); ?>
                            </p>
                            <small style="color: #999; font-size: 0.75rem; margin-top: 0.25rem; display: block;">
                                <?php echo time_ago($review->created_at); ?>
                            </small>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>

            <!-- Make Offer Section -->
            <?php if (!$has_applied): ?>
                <div class="offer-card">
                    <div class="offer-header">
                        <div class="offer-header-icon">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                        <h3 class="offer-header-title">Submit Your Offer</h3>
                        <p class="offer-header-subtitle">Interested in this job? Send your offer now!</p>
                    </div>
                    
                    <form method="POST" action="<?php echo base_url('cleaner/make_offer/' . $job->id); ?>" class="offer-form" id="offerForm">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-clipboard-check"></i> Choose Offer Type
                            </label>
                            <select name="offer_type" id="offerType" class="form-select" required>
                                <option value="">-- Select How You Want to Apply --</option>
                                <option value="accept">✅ Accept Job Offer</option>
                                <option value="counter">💰 Make a Counter Offer</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="priceGroup" style="display: none;">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i> Your Desired Payout
                            </label>
                            <input 
                                type="number" 
                                name="amount" 
                                id="offerAmount"
                                class="form-input" 
                                step="0.01" 
                                min="0" 
                                placeholder="Enter your desired payout amount"
                            >
                            <div class="form-helper-text">
                                💡 Enter how much you want to earn from this job
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-submit-offer">
                            <i class="fas fa-paper-plane"></i>
                            Send Offer Now
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="offer-card" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                    <div class="offer-header">
                        <div class="offer-header-icon">
                        <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="offer-header-title">Offer Submitted!</h3>
                        <p class="offer-header-subtitle">Your offer is being reviewed by the host. You'll be notified once they respond.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Pricing parameters from PHP
    const pricingParams = {
        baseCharge: <?php echo $base_charge; ?>,
        taxPercent: <?php echo $tax_percent; ?>,
        appPercent: <?php echo $app_percent; ?>
    };
    
    // Function to calculate cleaner payout from host price
    function calculateCleanerPayout(hostPrice) {
        const baseCharge = pricingParams.baseCharge;
        const taxAmount = hostPrice * (pricingParams.taxPercent / 100);
        const appAmount = hostPrice * (pricingParams.appPercent / 100);
        const cleanerPayout = hostPrice - baseCharge - taxAmount - appAmount;
        return cleanerPayout;
    }
    
    // Function to calculate host price from desired cleaner payout (reverse calculation)
    function calculateHostPrice(desiredPayout) {
        const baseCharge = pricingParams.baseCharge;
        const feePercentage = (pricingParams.taxPercent + pricingParams.appPercent) / 100;
        const hostPrice = (desiredPayout + baseCharge) / (1 - feePercentage);
        return hostPrice;
    }
    
    // Handle offer type selection
    $('#offerType').on('change', function() {
        const offerType = $(this).val();
        const $priceGroup = $('#priceGroup');
        
        if (offerType === 'counter') {
            $priceGroup.show();
            $('#offerAmount').prop('required', true);
        } else {
            $priceGroup.hide();
            $('#offerAmount').prop('required', false);
        }
    });
    
        // Form validation and submission
        $('.offer-form').on('submit', function(e) {
            console.log('Form submission started!');
            
            const $form = $(this);
            const offerType = $form.find('[name="offer_type"]').val();
            const amount = $form.find('[name="amount"]').val();
            
            console.log('Offer Type:', offerType);
            console.log('Amount:', amount);
            
            if (!offerType) {
                alert('Please select an offer type.');
                e.preventDefault();
                return;
            }
            
            // For accept offers, set the amount to suggested price
            if (offerType === 'accept') {
                const suggestedPrice = <?php echo $job->suggested_price; ?>;
                $form.find('[name="amount"]').val(suggestedPrice.toFixed(2));
            } else if (offerType === 'counter') {
                // For counter offers, validate amount and format to 2 decimal places
                const amountValue = parseFloat(amount);
                if (!amountValue || amountValue <= 0) {
                    alert('Please enter a valid counter offer amount.');
                    e.preventDefault();
                    return;
                }
                // Format amount to 2 decimal places
                $form.find('[name="amount"]').val(amountValue.toFixed(2));
            }
            
            console.log('Form validation passed, submitting...');
            // Form will submit normally
        });
    
    // Auto-fill suggested price and calculate payout
    $('#offerAmount').on('focus', function() {
        if (!$(this).val()) {
            const suggestedPrice = <?php echo $job->suggested_price; ?>;
            $(this).val(suggestedPrice.toFixed(2));
            
            // Trigger calculation
            $(this).trigger('input');
        }
    });
});
</script>

            </div>
</div>
