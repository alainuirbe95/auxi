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
.complete-job-container {
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

/* CRITICAL VIDEO ALERT - Maximum Visibility */
.video-alert-critical {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    border: 4px solid #c92a2a;
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 40px rgba(201, 42, 42, 0.4);
    animation: pulse 2s infinite;
    position: relative;
    overflow: hidden;
}

@keyframes pulse {
    0%, 100% { box-shadow: 0 10px 40px rgba(201, 42, 42, 0.4); }
    50% { box-shadow: 0 10px 50px rgba(201, 42, 42, 0.6); }
}

.video-alert-critical::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    animation: shine 3s infinite;
}

@keyframes shine {
    0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
    100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
}

.video-alert-content {
    position: relative;
    z-index: 1;
    display: flex;
    gap: 2rem;
    align-items: center;
    color: white;
}

.video-alert-icon {
    width: 100px;
    height: 100px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    flex-shrink: 0;
    backdrop-filter: blur(10px);
    border: 3px solid white;
}

.video-alert-text h3 {
    color: white;
    font-weight: 800;
    font-size: 1.8rem;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.video-alert-text ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.video-alert-text ul li {
    margin-bottom: 0.75rem;
    padding-left: 2rem;
    position: relative;
    font-size: 1.05rem;
    font-weight: 600;
}

.video-alert-text ul li::before {
    content: '📹';
    position: absolute;
    left: 0;
    font-size: 1.3rem;
}

/* Two Column Layout */
.content-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
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

/* Payout Display */
.payout-section {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    color: white;
    margin-bottom: 2rem;
}

.payout-label {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.payout-amount {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.payout-note {
    font-size: 0.9rem;
    opacity: 0.85;
}

/* Info Sections */
.info-section {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    border: 1px solid rgba(102, 126, 234, 0.2);
    border-radius: 12px;
    padding: 1.25rem;
    margin-bottom: 1rem;
}

.info-section h6 {
    color: #667eea;
    font-weight: 600;
    margin-bottom: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-section p {
    margin-bottom: 0.5rem;
    color: #4a5568;
    line-height: 1.6;
}

.info-section p:last-child {
    margin-bottom: 0;
}

/* Review Section */
.review-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.review-header-section {
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
    padding: 1.5rem;
    color: white;
    text-align: center;
}

.review-header-section h3 {
    font-weight: 800;
    margin-bottom: 0.5rem;
    font-size: 1.6rem;
}

.review-header-section p {
    opacity: 0.95;
    margin-bottom: 0;
}

.review-body {
    padding: 2rem;
}

/* Star Rating */
.star-rating-group {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.08) 0%, rgba(118, 75, 162, 0.08) 100%);
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: all 0.3s ease;
}

.star-rating-group:hover {
    border-color: rgba(102, 126, 234, 0.4);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
}

.rating-label {
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.star-rating-input {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.star-rating-input i {
    color: #dee2e6;
    transition: all 0.2s ease;
    cursor: pointer;
}

.star-rating-input i:hover,
.star-rating-input i.active {
    color: #ffc107;
    transform: scale(1.2);
}

.star-rating-input i.fas {
    color: #ffc107;
}

.rating-comment {
    margin-top: 1rem;
}

.rating-comment textarea {
    border: 2px solid rgba(102, 126, 234, 0.2);
    border-radius: 10px;
    padding: 0.75rem;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

.rating-comment textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
    outline: none;
}

/* Public Review Section */
.public-review-section {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border: 3px solid #4caf50;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
}

.public-review-section h4 {
    color: #2e7d32;
    font-weight: 700;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.public-review-section .form-control {
    border: 2px solid #4caf50;
    border-radius: 10px;
}

.public-review-section .form-control:focus {
    border-color: #2e7d32;
    box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.15);
}

/* Character Counter */
.char-counter {
    font-size: 0.85rem;
    color: #6c757d;
    margin-top: 0.5rem;
    display: block;
}

.char-counter.warning {
    color: #ff9800;
    font-weight: 600;
}

/* Submit Section */
.submit-section {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    text-align: center;
}

.btn-submit-completion {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1rem 3rem;
    font-size: 1.2rem;
    font-weight: 700;
    border-radius: 50px;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    transition: all 0.3s ease;
    min-width: 300px;
}

.btn-submit-completion:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(102, 126, 234, 0.5);
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
}

.btn-submit-completion:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-back {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    padding: 0.75rem 2rem;
    font-weight: 600;
    border-radius: 50px;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-block;
    margin-top: 1rem;
}

.btn-back:hover {
    background: #667eea;
    color: white;
    text-decoration: none;
}

@media (max-width: 992px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="complete-job-container">
    <!-- Modern Header -->
    <div class="modern-header">
        <h1>
            <i class="fas fa-check-circle"></i>
            Complete Cleaning Job
        </h1>
        <p>Submit your work completion and review the host to receive payment</p>
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

    <!-- CRITICAL VIDEO ALERT -->
    <div class="video-alert-critical">
        <div class="video-alert-content">
            <div class="video-alert-icon">
                <i class="fas fa-video"></i>
            </div>
            <div class="video-alert-text">
                <h3>🎥 VIDEO DOCUMENTATION REQUIRED BEFORE SUBMISSION</h3>
                <ul>
                    <li>Record a video showing ALL completed cleaning work</li>
                    <li>Show every room, surface, and area that was cleaned</li>
                    <li>Send the video to the host via WhatsApp BEFORE completing</li>
                    <li>This is your proof of work and protects you from disputes</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Start Form Here - Wraps Everything -->
    <form id="completionForm">
        <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
        <input type="hidden" name="final_price" value="<?php echo $job->accepted_price ?? $job->suggested_price; ?>">

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Left Column: Job Details & Review -->
        <div>
            <!-- Your Payout -->
            <div class="payout-section">
                <div class="payout-label">Your Payout for This Job</div>
                <div class="payout-amount">$<?php echo number_format($your_payout, 2); ?></div>
                <?php if ($is_counter): ?>
                    <div class="payout-note">
                        <span class="badge bg-warning text-dark">Counter Offer Accepted</span>
                    </div>
                <?php endif; ?>
                <div class="payout-note">Payment released after host confirms completion</div>
                                        </div>
                                        
            <!-- Job Details Card -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <div class="modern-card-header">
                    <i class="fas fa-clipboard-list"></i>
                    <?php echo htmlspecialchars($job->title); ?>
                    <?php if ($is_str): ?>
                        <span class="badge bg-warning text-dark ms-2">STR</span>
                    <?php endif; ?>
                </div>
                <div class="modern-card-body">
                    <div class="info-section">
                        <h6><i class="fas fa-map-marker-alt"></i> Location</h6>
                        <p><strong><?php echo htmlspecialchars($job->address); ?></strong></p>
                        <p class="text-muted"><?php echo htmlspecialchars($job->city . ', ' . $job->state); ?></p>
                                        </div>
                                        
                    <div class="info-section">
                        <h6><i class="fas fa-clock"></i> Time Information</h6>
                        <p><strong>Started:</strong> <?php echo date('M j, Y g:i A', strtotime($job->started_at)); ?></p>
                        <p><strong>Duration:</strong> <?php echo ($job->estimated_duration / 60); ?> hours estimated</p>
                                        </div>
                                        
                    <?php if (!empty($job->description)): ?>
                        <div class="info-section">
                            <h6><i class="fas fa-align-left"></i> Job Description</h6>
                            <p><?php echo nl2br(htmlspecialchars($job->description)); ?></p>
                                        </div>
                    <?php endif; ?>
                                    </div>
                                </div>
                                
            <!-- Review Form -->
            <div class="review-card">
                <div class="review-header-section">
                    <h3>⭐ Review Required to Complete Job</h3>
                    <p>Your honest feedback is mandatory for payment release</p>
                                    </div>
                <div class="review-body">
                    <div style="background: rgba(255, 193, 7, 0.1); border-left: 4px solid #ffc107; padding: 1rem; border-radius: 8px; margin-bottom: 2rem;">
                        <i class="fas fa-shield-alt" style="color: #f57c00;"></i>
                        <strong style="color: #e65100;">Fair Review Process:</strong> 
                        <span style="color: #4a5568;">Your review is submitted now but hidden from the host until they review you. This ensures both reviews are honest and unbiased.</span>
                                        </div>
                                        
                    <!-- Public Review -->
                    <div class="public-review-section">
                        <h4><i class="fas fa-globe"></i> Public Review</h4>
                        <p style="color: #2e7d32; margin-bottom: 1.5rem;">This will appear on the host's public profile</p>
                        
                        <div class="star-rating-group" style="background: white; border-color: #4caf50;">
                            <div class="rating-label">
                                <i class="fas fa-star" style="color: #ffc107;"></i>
                                Overall Rating <span style="color: #dc3545;">*</span>
                            </div>
                                                <div class="star-rating-input" id="overall-stars">
                                                    <i class="far fa-star" data-rating="1"></i>
                                                    <i class="far fa-star" data-rating="2"></i>
                                                    <i class="far fa-star" data-rating="3"></i>
                                                    <i class="far fa-star" data-rating="4"></i>
                                                    <i class="far fa-star" data-rating="5"></i>
                                                </div>
                                                <input type="hidden" name="overall_rating" id="overall_rating" required>
                                            </div>
                                            
                                            <div class="form-group">
                            <label style="font-weight: 700; color: #2e7d32;">
                                Public Comment (Optional)
                                                </label>
                                                <textarea class="form-control" 
                                                          id="public_comment" 
                                                          name="public_comment" 
                                                          rows="3" 
                                      maxlength="500"
                                      placeholder="Share your experience with this host (optional, max 500 characters)"
                                      style="border: 2px solid #4caf50; border-radius: 10px;"></textarea>
                            <small class="char-counter" id="publicCommentCount">0/500 characters</small>
                                            </div>
                                        </div>
                                        
                    <!-- Private Category Ratings -->
                    <h5 style="color: #667eea; font-weight: 700; margin: 2rem 0 1.5rem 0;">
                        <i class="fas fa-lock me-2"></i>
                        Private Category Ratings
                    </h5>
                    <p style="color: #6c757d; margin-bottom: 1.5rem;">Rate each category - visible only to you and the host</p>
                    
                    <div class="star-rating-group">
                        <div class="rating-label">
                            <i class="fas fa-user-tie"></i>
                            Professionalism <span style="color: #dc3545;">*</span>
                        </div>
                                                <div class="star-rating-input" id="professionalism-stars">
                                                    <i class="far fa-star" data-rating="1"></i>
                                                    <i class="far fa-star" data-rating="2"></i>
                                                    <i class="far fa-star" data-rating="3"></i>
                                                    <i class="far fa-star" data-rating="4"></i>
                                                    <i class="far fa-star" data-rating="5"></i>
                                                </div>
                                                <input type="hidden" name="professionalism_rating" id="professionalism_rating" required>
                        <div class="rating-comment">
                            <textarea class="form-control" 
                                      name="professionalism_comment" 
                                      rows="2" 
                                      maxlength="500"
                                      placeholder="Optional: Add specific feedback about professionalism"></textarea>
                            <small class="char-counter">0/500</small>
                        </div>
                    </div>

                    <div class="star-rating-group">
                        <div class="rating-label">
                            <i class="fas fa-award"></i>
                            Quality of Experience <span style="color: #dc3545;">*</span>
                        </div>
                        <div class="star-rating-input" id="quality-stars">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="quality_rating" id="quality_rating" required>
                        <div class="rating-comment">
                            <textarea class="form-control" 
                                      name="quality_comment" 
                                      rows="2" 
                                      maxlength="500"
                                      placeholder="Optional: Add specific feedback about quality"></textarea>
                            <small class="char-counter">0/500</small>
                        </div>
                    </div>

                    <div class="star-rating-group">
                        <div class="rating-label">
                            <i class="fas fa-comments"></i>
                            Communication <span style="color: #dc3545;">*</span>
                        </div>
                        <div class="star-rating-input" id="communication-stars">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="communication_rating" id="communication_rating" required>
                        <div class="rating-comment">
                            <textarea class="form-control" 
                                      name="communication_comment" 
                                      rows="2" 
                                      maxlength="500"
                                      placeholder="Optional: Add specific feedback about communication"></textarea>
                            <small class="char-counter">0/500</small>
                        </div>
                    </div>

                    <div class="star-rating-group">
                        <div class="rating-label">
                            <i class="fas fa-clock"></i>
                            Punctuality & Responsiveness <span style="color: #dc3545;">*</span>
                        </div>
                        <div class="star-rating-input" id="punctuality-stars">
                            <i class="far fa-star" data-rating="1"></i>
                            <i class="far fa-star" data-rating="2"></i>
                            <i class="far fa-star" data-rating="3"></i>
                            <i class="far fa-star" data-rating="4"></i>
                            <i class="far fa-star" data-rating="5"></i>
                        </div>
                        <input type="hidden" name="punctuality_rating" id="punctuality_rating" required>
                        <div class="rating-comment">
                            <textarea class="form-control" 
                                      name="punctuality_comment" 
                                      rows="2" 
                                      maxlength="500"
                                      placeholder="Optional: Add specific feedback about punctuality"></textarea>
                            <small class="char-counter">0/500</small>
                        </div>
                    </div>

                    <!-- Private Notes -->
                    <div style="background: rgba(102, 126, 234, 0.05); border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem;">
                        <label style="font-weight: 600; color: #4a5568;">
                            <i class="fas fa-sticky-note me-2"></i>
                            Additional Private Notes (Optional)
                        </label>
                        <textarea class="form-control" 
                                  name="private_notes" 
                                  rows="3" 
                                  maxlength="500"
                                  placeholder="Any additional private feedback that only you and the host will see"
                                  style="border: 2px solid rgba(102, 126, 234, 0.2); border-radius: 10px; margin-top: 0.5rem;"></textarea>
                        <small class="char-counter">0/500</small>
                    </div>
                </div>
                                            </div>
                                        </div>
                                        
        <!-- Right Column: Summary & Actions -->
        <div>
            <!-- Job Summary -->
            <div class="modern-card" style="margin-bottom: 2rem;">
                <div class="modern-card-header">
                    <i class="fas fa-info-circle"></i>
                    Job Summary
                                        </div>
                <div class="modern-card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 0.25rem;">Host</div>
                        <div style="font-weight: 700; color: #2d3748;">
                            <?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?>
                                    </div>
                                </div>
                                
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 0.25rem;">Property Type</div>
                        <div>
                            <?php if ($is_str): ?>
                                <span class="badge bg-warning text-dark">🏠 Short Term Rental</span>
                            <?php else: ?>
                                <span class="badge bg-info">🏡 Residential</span>
                            <?php endif; ?>
                                        </div>
                                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 0.25rem;">Scheduled</div>
                        <div style="font-weight: 600;">
                            <?php echo date('M j, Y g:i A', strtotime($job->scheduled_date . ' ' . $job->scheduled_time)); ?>
                                </div>
                                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 0.25rem;">Started</div>
                        <div style="font-weight: 600;">
                            <?php echo date('M j, Y g:i A', strtotime($job->started_at)); ?>
                        </div>
                        <small class="text-muted"><?php echo time_ago($job->started_at); ?></small>
                                </div>
                    
                    <?php
                    // Calculate time worked
                    $started_timestamp = strtotime($job->started_at);
                    $current_timestamp = time();
                    $time_worked_seconds = $current_timestamp - $started_timestamp;
                    $hours_worked = floor($time_worked_seconds / 3600);
                    $minutes_worked = floor(($time_worked_seconds % 3600) / 60);
                    ?>
                    
                    <div style="background: linear-gradient(135deg, rgba(67, 233, 123, 0.1) 0%, rgba(56, 249, 215, 0.1) 100%); border: 2px solid rgba(67, 233, 123, 0.3); border-radius: 12px; padding: 1rem; text-align: center;">
                        <div style="font-size: 0.85rem; color: #6c757d; margin-bottom: 0.25rem;">Time Worked</div>
                        <div style="font-size: 1.8rem; font-weight: 800; color: #20c997;">
                            <?php 
                            if ($hours_worked > 0) {
                                echo $hours_worked . 'h ' . $minutes_worked . 'm';
                            } else {
                                echo $minutes_worked . ' minutes';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Reminder -->
            <div style="background: linear-gradient(135deg, #fff3cd 0%, #ffe8cc 100%); border: 3px solid #ffc107; border-radius: 15px; padding: 1.5rem; margin-bottom: 2rem;">
                <h6 style="color: #e65100; font-weight: 700; margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Before You Submit
                </h6>
                <ul style="margin: 0; padding-left: 1.5rem; color: #4a5568; line-height: 1.8;">
                    <li>✅ All cleaning tasks completed</li>
                    <li>✅ Video sent to host via WhatsApp</li>
                    <li>✅ Property locked/secured</li>
                    <li>✅ All supplies put away</li>
                    <?php if ($is_str): ?>
                        <li>✅ STR requirements met</li>
                        <li>✅ Guest-ready presentation achieved</li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Payment Info -->
            <div style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 2px solid #2196f3; border-radius: 15px; padding: 1.5rem;">
                <h6 style="color: #1565c0; font-weight: 700; margin-bottom: 1rem;">
                    <i class="fas fa-info-circle me-2"></i>
                    Payment Timeline
                </h6>
                <p style="color: #4a5568; margin: 0; line-height: 1.8;">
                    After submission, the host has <strong>24 hours</strong> to confirm completion. 
                    If they don't respond, payment is <strong>automatically released</strong> to you.
                </p>
            </div>
        </div>
    </div>

        <!-- Optional Notes -->
        <div class="modern-card" style="margin-bottom: 2rem;">
            <div class="modern-card-header">
                <i class="fas fa-clipboard"></i>
                Completion Notes (Optional)
            </div>
            <div class="modern-card-body">
                <textarea class="form-control" 
                          id="completion_notes" 
                          name="completion_notes" 
                          rows="3" 
                          maxlength="1000"
                          placeholder="Add any notes about the completed service (optional)"
                          style="border: 2px solid rgba(102, 126, 234, 0.2); border-radius: 10px; font-size: 1rem; padding: 1rem;"></textarea>
                <small class="char-counter" id="notesCount">0/1000 characters</small>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="submit-section">
            <div class="form-check" style="margin-bottom: 2rem; display: flex; align-items: center; justify-content: center; gap: 0.75rem;">
                <input type="checkbox" class="form-check-input" id="confirm_completion" required style="width: 24px; height: 24px; cursor: pointer;">
                <label class="form-check-label" for="confirm_completion" style="font-size: 1.1rem; font-weight: 600; color: #2d3748; cursor: pointer;">
                    I confirm the service is completed and video was sent to the host
                </label>
            </div>
            
            <button type="submit" class="btn-submit-completion" id="submitBtn">
                <i class="fas fa-check-circle me-2"></i>
                Complete Job & Submit Review
            </button>
            
            <a href="<?php echo base_url('cleaner/jobs-in-progress'); ?>" class="btn-back">
                <i class="fas fa-arrow-left me-1"></i>
                Back to Jobs
            </a>
        </div>
    </form>
</div>

<script>
$(document).ready(function() {
    console.log('Complete job form initialized');
    console.log('Form found:', $('#completionForm').length);
    console.log('Star rating containers found:', $('.star-rating-input').length);
    
    // Character counter for completion notes
    $('#completion_notes').on('input', function() {
        $('#notesCount').text($(this).val().length + '/1000 characters');
    });
    
    // Character counter for public comment
    $('#public_comment').on('input', function() {
        const length = $(this).val().length;
        $('#publicCommentCount').text(length + '/500 characters');
    });
    
    // Character counters for all category comment textareas
    $('textarea[name$="_comment"], textarea[name="private_notes"]').on('input', function() {
        const length = $(this).val().length;
        $(this).siblings('.char-counter').text(length + '/500');
    });
    
    // Star Rating Functionality
    $('.star-rating-input').each(function() {
        const $container = $(this);
        const containerId = $container.attr('id');
        const targetId = containerId.replace('-stars', '_rating');
        const $hiddenInput = $('#' + targetId);
        
        console.log('Initializing star rating for:', containerId, '→ Hidden input:', targetId, 'Found:', $hiddenInput.length > 0);
        
        $container.find('i').on('click', function() {
            const rating = $(this).data('rating');
            $hiddenInput.val(rating);
            console.log('Rating set for ' + targetId + ': ' + rating);
            
            // Update star display
            $container.find('i').each(function(index) {
                if (index < rating) {
                    $(this).removeClass('far').addClass('fas');
                } else {
                    $(this).removeClass('fas').addClass('far');
                }
            });
        });
        
        // Hover effect
        $container.find('i').on('mouseenter', function() {
            const rating = $(this).data('rating');
            $container.find('i').each(function(index) {
                if (index < rating) {
                    $(this).addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        });
        
        $container.on('mouseleave', function() {
            $container.find('i').removeClass('active');
        });
    });
    
    // Form submission with validation
    $('#completionForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate completion confirmation
        if (!$('#confirm_completion').is(':checked')) {
            alert('❌ Please confirm that the service has been completed and video was sent.');
            return;
        }
        
        // Validate all star ratings
        const requiredRatings = ['overall', 'professionalism', 'quality', 'communication', 'punctuality'];
        let missingRatings = [];
        
        console.log('Validating ratings...');
        requiredRatings.forEach(function(category) {
            const ratingValue = $('#' + category + '_rating').val();
            console.log(category + ' rating value:', ratingValue);
            if (!ratingValue || ratingValue < 1 || ratingValue > 5) {
                missingRatings.push(category.charAt(0).toUpperCase() + category.slice(1));
            }
        });
        
        if (missingRatings.length > 0) {
            console.log('Missing ratings:', missingRatings);
            alert('❌ Please provide star ratings for: ' + missingRatings.join(', '));
            return;
        }
        
        console.log('All ratings validated successfully');
        
        // Validate public comment (optional, but if provided, check max length)
        const publicComment = $('#public_comment').val().trim();
        if (publicComment.length > 500) {
            alert('❌ Public comment must not exceed 500 characters.');
            $('#public_comment').focus();
            return;
        }
        
        // Disable submit button
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Submitting...');
        
        const formData = $(this).serialize();
        console.log('Form data being sent:', formData);
        
        $.ajax({
            url: '<?php echo base_url("cleaner/jobs-in-progress/process-completion"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert('✅ ' + response.message);
                    window.location.href = response.redirect;
                } else {
                    alert('❌ Error: ' + response.message);
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle me-2"></i>Complete Job & Submit Review');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('❌ An error occurred. Please try again.');
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle me-2"></i>Complete Job & Submit Review');
            }
        });
    });
});
</script>
