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
    background: white;
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.offer-form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: #333;
    font-size: 0.95rem;
}

.form-input {
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-input:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(67, 233, 123, 0.1);
}

.form-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    background: white;
    transition: all 0.3s ease;
}

.form-select:focus {
    border-color: #667eea;
    outline: none;
    box-shadow: 0 0 0 3px rgba(67, 233, 123, 0.1);
}

.btn-submit-offer {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 10px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-submit-offer:hover {
    background: linear-gradient(135deg, #38d9a9 0%, #20c997 100%);
    transform: translateY(-2px);
    box-shadow: 0 10px 20px rgba(67, 233, 123, 0.3);
}

.btn-submit-offer:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.applied-badge {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    padding: 1rem 2rem;
    border-radius: 10px;
    text-align: center;
    font-weight: 600;
    font-size: 1.1rem;
}

.applied-badge i {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    display: block;
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
        <div class="job-price-large">$<?php echo number_format($job->suggested_price, 2); ?></div>
        
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
                <i class="fas fa-tasks"></i>
                Job Requirements
            </h2>
            <div class="job-requirements">
                <div class="requirements-grid">
                    <div class="requirement-item">
                        <i class="fas fa-home"></i>
                        <span>
                            <?php 
                            $rooms = is_string($job->rooms) ? json_decode($job->rooms, true) : $job->rooms;
                            if (is_array($rooms)) {
                                echo htmlspecialchars(implode(', ', $rooms)) . ' Rooms to Clean';
                            } else {
                                echo htmlspecialchars($job->rooms) . ' Rooms to Clean';
                            }
                            ?>
                        </span>
                    </div>
                    <?php if (!empty($job->extras)): ?>
                        <div class="requirement-item">
                            <i class="fas fa-plus"></i>
                            <span>
                                <?php 
                                $extras = is_string($job->extras) ? json_decode($job->extras, true) : $job->extras;
                                if (is_array($extras)) {
                                    echo htmlspecialchars(implode(', ', $extras));
                                } else {
                                    echo htmlspecialchars($job->extras);
                                }
                                ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php if ($job->pets == 1 || $job->pets == '1'): ?>
                        <div class="requirement-item">
                            <i class="fas fa-paw"></i>
                            <span>Pets Present</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($job->notes)): ?>
                        <div class="requirement-item">
                            <i class="fas fa-sticky-note"></i>
                            <span>Special Notes: <?php echo htmlspecialchars($job->notes); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
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
                
                <div class="host-stats">
                    <div class="host-stat">
                        <div class="host-stat-number">5</div>
                        <div class="host-stat-label">Jobs Posted</div>
                    </div>
                    <div class="host-stat">
                        <div class="host-stat-number">4.8</div>
                        <div class="host-stat-label">Rating</div>
                    </div>
                </div>
            </div>

            <!-- Make Offer Section -->
            <?php if (!$has_applied): ?>
                <div class="offer-card">
                    <h3 class="section-title">
                        <i class="fas fa-handshake"></i>
                        Make Your Offer
                    </h3>
                    
                    <form method="POST" action="<?php echo base_url('cleaner/make_offer/' . $job->id); ?>" class="offer-form" id="offerForm">
                        <div class="form-group">
                            <label class="form-label">Offer Type</label>
                            <select name="offer_type" id="offerType" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="accept">Accept Suggested Price ($<?php echo number_format($job->suggested_price, 2); ?>)</option>
                                <option value="counter">Counter Offer</option>
                            </select>
                        </div>
                        
                        <div class="form-group" id="priceGroup" style="display: none;">
                            <label class="form-label">Your Counter Offer ($)</label>
                            <input 
                                type="number" 
                                name="amount" 
                                id="offerAmount"
                                class="form-input" 
                                step="0.01" 
                                min="0" 
                                placeholder="Enter your counter offer"
                            >
                            <small style="color: #666; font-size: 0.85rem;">
                                Suggested: $<?php echo number_format($job->suggested_price, 2); ?>
                                <?php if (isset($job->estimated_duration) && $job->estimated_duration > 0): ?>
                                    | Estimated: <?php 
                                        $hours = $job->estimated_duration / 60;
                                        $hourly_rate = $job->suggested_price / $hours;
                                        echo '$' . number_format($hourly_rate, 2) . '/hour';
                                        // Debug: uncomment to see values
                                        // echo ' (Duration: ' . $job->estimated_duration . ' min, Hours: ' . $hours . ')';
                                    ?>
                                <?php else: ?>
                                    | No duration estimate available
                                <?php endif; ?>
                            </small>
                        </div>
                        
                        <button type="submit" class="btn-submit-offer">
                            <i class="fas fa-paper-plane me-2"></i>
                            Submit Offer
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <div class="offer-card">
                    <div class="applied-badge">
                        <i class="fas fa-check-circle"></i>
                        You've Already Applied
                        <div style="font-size: 0.9rem; margin-top: 0.5rem; opacity: 0.9;">
                            Your offer is being reviewed by the host.
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
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
    
    // Auto-fill suggested price
    $('[name="amount"]').on('focus', function() {
        if (!$(this).val()) {
            const suggestedPrice = <?php echo $job->suggested_price; ?>;
            $(this).val(suggestedPrice.toFixed(2));
        }
    });
});
</script>
    </div>
</div>
