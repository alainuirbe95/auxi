<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <div class="header-content">
                    <h2 class="page-title">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Recall Job for Admin Review
                    </h2>
                    <p class="page-subtitle">Report issues with this job for admin review and potential legal action</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card job-info-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>
                        Job Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>Job Title:</label>
                                <p><?php echo htmlspecialchars($job->title); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Description:</label>
                                <p><?php echo htmlspecialchars($job->description); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Address:</label>
                                <p><?php echo htmlspecialchars($job->address . ', ' . $job->city . ', ' . $job->state); ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <label>Scheduled Date:</label>
                                <p><?php echo date('M j, Y g:i A', strtotime($job->scheduled_date . ' ' . $job->scheduled_time)); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Cleaner:</label>
                                <p><?php echo htmlspecialchars($cleaner_name); ?></p>
                            </div>
                            <div class="info-item">
                                <label>Job Price:</label>
                                <p class="price-highlight">$<?php echo number_format($job->final_price ?: $job->accepted_price ?: $job->suggested_price, 2); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recall Form -->
    <div class="row">
        <div class="col-12">
            <div class="card recall-form-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>
                        Recall Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Important:</strong> 
                        <?php if ($job->status === 'completed'): ?>
                            Recalling this job will still release payment to the cleaner, but will notify the admin for review and potential legal action.
                        <?php else: ?>
                            Recalling this past job will notify the admin for review and potential legal action. This action cannot be undone.
                        <?php endif; ?>
                    </div>

                    <form id="recallJobForm" method="POST" action="<?php echo base_url('host/process_recall_job'); ?>">
                        <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
                        <input type="hidden" name="recall_type" value="<?php echo $job->status === 'completed' ? 'completed' : 'past'; ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="recall_reason" class="form-label">Reason for Recall <span class="text-danger">*</span></label>
                                    <select class="form-select" id="recall_reason" name="recall_reason" required>
                                        <option value="">Select a reason...</option>
                                        <option value="poor_quality">Poor Quality Work</option>
                                        <option value="incomplete_service">Incomplete Service</option>
                                        <option value="damage_caused">Damage to Property</option>
                                        <option value="unprofessional_behavior">Unprofessional Behavior</option>
                                        <option value="safety_concerns">Safety Concerns</option>
                                        <option value="contract_violation">Contract Violation</option>
                                        <option value="other">Other (specify in details)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="severity" class="form-label">Severity Level <span class="text-danger">*</span></label>
                                    <select class="form-select" id="severity" name="severity" required>
                                        <option value="">Select severity...</option>
                                        <option value="low">Low - Minor issues</option>
                                        <option value="medium">Medium - Significant issues</option>
                                        <option value="high">High - Serious issues</option>
                                        <option value="critical">Critical - Major problems</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="recall_details" class="form-label">Detailed Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="recall_details" name="recall_details" rows="6" 
                                      placeholder="Please provide a detailed description of the issues encountered. Include specific examples, photos if available, and any relevant information that will help the admin review this case." 
                                      required></textarea>
                            <div class="form-text">
                                <span id="detailsCount">0</span>/1000 characters
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="evidence_notes" class="form-label">Evidence & Supporting Information</label>
                            <textarea class="form-control" id="evidence_notes" name="evidence_notes" rows="4" 
                                      placeholder="Describe any evidence you have (photos, videos, witnesses, etc.) that supports your recall. This information will be crucial for the admin review."></textarea>
                            <div class="form-text">
                                <span id="evidenceCount">0</span>/500 characters
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="desired_resolution" class="form-label">Desired Resolution</label>
                            <select class="form-select" id="desired_resolution" name="desired_resolution">
                                <option value="">Select desired resolution...</option>
                                <option value="refund">Full or partial refund</option>
                                <option value="rework">Job to be redone</option>
                                <option value="compensation">Compensation for damages</option>
                                <option value="cleaner_review">Cleaner account review</option>
                                <option value="other">Other (specify in details)</option>
                            </select>
                        </div>

                        <!-- Review Section -->
                        <div class="card mt-4">
                            <?php if (!$existing_review): ?>
                                <!-- New Review Form -->
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-star"></i>
                                        Review the Cleaner (Required)
                                    </h5>
                                    <small>Your honest feedback helps maintain quality standards</small>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        <strong>Review Required:</strong> You must review the cleaner before submitting this recall. Reviews cannot be edited after submission.
                                        <br><br>
                                        <i class="fas fa-shield-alt text-info"></i> <strong>Fair Review Process:</strong> The cleaner has also submitted a review, but you won't be able to see it until after you submit yours. This ensures both reviews are honest and unbiased.
                                    </div>
                                
                                <!-- Public Review Section -->
                                <div class="review-section public-review">
                                    <h6 class="section-title">
                                        <i class="fas fa-globe mr-2"></i>
                                        Public Review (Visible on Cleaner's Profile)
                                    </h6>
                                    
                                    <!-- Overall Rating -->
                                    <div class="form-group">
                                        <label class="required-label">
                                            Overall Rating
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="star-rating-input" id="overall-stars">
                                            <i class="far fa-star" data-rating="1"></i>
                                            <i class="far fa-star" data-rating="2"></i>
                                            <i class="far fa-star" data-rating="3"></i>
                                            <i class="far fa-star" data-rating="4"></i>
                                            <i class="far fa-star" data-rating="5"></i>
                                        </div>
                                        <input type="hidden" name="overall_rating" id="overall_rating" required>
                                        <small class="form-text text-muted">Click on a star to rate (1-5 stars)</small>
                                    </div>
                                    
                                    <!-- Public Comment -->
                                    <div class="form-group">
                                        <label class="required-label">
                                            Public Comment
                                            <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" 
                                                  id="public_comment" 
                                                  name="public_comment" 
                                                  rows="3" 
                                                  minlength="30"
                                                  maxlength="100"
                                                  placeholder="Share your experience with this cleaner (30-100 characters, will be public)"
                                                  required></textarea>
                                        <small class="form-text">
                                            <span id="publicCommentCount" class="text-muted">0/100 characters</span>
                                            <span id="publicCommentWarning" class="text-danger ml-2" style="display:none;">Minimum 30 characters required</span>
                                        </small>
                                    </div>
                                </div>
                                
                                <!-- Private Review Section -->
                                <div class="review-section private-review mt-4">
                                    <h6 class="section-title">
                                        <i class="fas fa-lock mr-2"></i>
                                        Private Feedback (Only Visible to You & Cleaner)
                                    </h6>
                                    <p class="text-muted small mb-3">This detailed feedback is private and will only be shared between you and the cleaner.</p>
                                    
                                    <!-- Professionalism -->
                                    <div class="category-review">
                                        <label class="required-label">
                                            Professionalism
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="star-rating-input" id="professionalism-stars">
                                            <i class="far fa-star" data-rating="1"></i>
                                            <i class="far fa-star" data-rating="2"></i>
                                            <i class="far fa-star" data-rating="3"></i>
                                            <i class="far fa-star" data-rating="4"></i>
                                            <i class="far fa-star" data-rating="5"></i>
                                        </div>
                                        <input type="hidden" name="professionalism_rating" id="professionalism_rating" required>
                                        <textarea class="form-control mt-2" 
                                                  name="professionalism_comment" 
                                                  rows="2" 
                                                  maxlength="100"
                                                  placeholder="Optional: Add specific feedback about professionalism (max 100 chars)"></textarea>
                                        <small class="form-text text-muted char-counter">0/100 characters</small>
                                    </div>
                                    
                                    <!-- Quality -->
                                    <div class="category-review">
                                        <label class="required-label">
                                            Quality of Work
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="star-rating-input" id="quality-stars">
                                            <i class="far fa-star" data-rating="1"></i>
                                            <i class="far fa-star" data-rating="2"></i>
                                            <i class="far fa-star" data-rating="3"></i>
                                            <i class="far fa-star" data-rating="4"></i>
                                            <i class="far fa-star" data-rating="5"></i>
                                        </div>
                                        <input type="hidden" name="quality_rating" id="quality_rating" required>
                                        <textarea class="form-control mt-2" 
                                                  name="quality_comment" 
                                                  rows="2" 
                                                  maxlength="100"
                                                  placeholder="Optional: Add specific feedback about work quality (max 100 chars)"></textarea>
                                        <small class="form-text text-muted char-counter">0/100 characters</small>
                                    </div>
                                    
                                    <!-- Communication -->
                                    <div class="category-review">
                                        <label class="required-label">
                                            Communication
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="star-rating-input" id="communication-stars">
                                            <i class="far fa-star" data-rating="1"></i>
                                            <i class="far fa-star" data-rating="2"></i>
                                            <i class="far fa-star" data-rating="3"></i>
                                            <i class="far fa-star" data-rating="4"></i>
                                            <i class="far fa-star" data-rating="5"></i>
                                        </div>
                                        <input type="hidden" name="communication_rating" id="communication_rating" required>
                                        <textarea class="form-control mt-2" 
                                                  name="communication_comment" 
                                                  rows="2" 
                                                  maxlength="100"
                                                  placeholder="Optional: Add specific feedback about communication (max 100 chars)"></textarea>
                                        <small class="form-text text-muted char-counter">0/100 characters</small>
                                    </div>
                                    
                                    <!-- Punctuality -->
                                    <div class="category-review">
                                        <label class="required-label">
                                            Punctuality & Reliability
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="star-rating-input" id="punctuality-stars">
                                            <i class="far fa-star" data-rating="1"></i>
                                            <i class="far fa-star" data-rating="2"></i>
                                            <i class="far fa-star" data-rating="3"></i>
                                            <i class="far fa-star" data-rating="4"></i>
                                            <i class="far fa-star" data-rating="5"></i>
                                        </div>
                                        <input type="hidden" name="punctuality_rating" id="punctuality_rating" required>
                                        <textarea class="form-control mt-2" 
                                                  name="punctuality_comment" 
                                                  rows="2" 
                                                  maxlength="100"
                                                  placeholder="Optional: Add specific feedback about punctuality (max 100 chars)"></textarea>
                                        <small class="form-text text-muted char-counter">0/100 characters</small>
                                    </div>
                                    
                                    <!-- Private Notes -->
                                    <div class="form-group">
                                        <label>Additional Private Notes (Optional)</label>
                                        <textarea class="form-control" 
                                                  name="private_notes" 
                                                  rows="2" 
                                                  maxlength="100"
                                                  placeholder="Any additional private feedback (max 100 chars)"></textarea>
                                        <small class="form-text text-muted char-counter">0/100 characters</small>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Note:</strong> Your review will be visible on the cleaner's profile immediately after submission. You cannot edit your review once submitted.
                                </div>
                            </div>
                            <?php else: ?>
                                <!-- Review Already Submitted -->
                                <div class="card-header bg-success text-white" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;">
                                    <h5 class="mb-0" style="color: #ffffff !important;">
                                        <i class="fas fa-check-circle" style="color: #ffffff !important;"></i>
                                        Review Already Submitted
                                    </h5>
                                    <small style="color: #ffffff !important; opacity: 0.95;">You have already reviewed this cleaner for this job</small>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-success" style="background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%) !important; border: 2px solid #28a745 !important;">
                                        <i class="fas fa-check-circle mr-2" style="color: #155724 !important;"></i>
                                        <strong style="color: #155724 !important;">Review Complete:</strong> 
                                        <span style="color: #155724 !important;">You have already submitted a review for this cleaner. You can proceed with the recall without submitting another review.</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="alert alert-danger" style="background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%) !important; border: 3px solid #dc3545 !important; box-shadow: none !important;">
                            <i class="fas fa-exclamation-triangle me-2" style="color: #721c24 !important;"></i>
                            <strong style="color: #721c24 !important;">Warning:</strong> 
                            <span style="color: #721c24 !important;">False or malicious recalls may result in account suspension. Please ensure your recall is legitimate and well-documented. All recalls are reviewed by admin and may result in legal action.</span>
                        </div>

                        <div class="form-actions">
                            <a href="<?php echo $back_url; ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>
                                Back
                            </a>
                            <button type="submit" class="btn btn-danger" id="submitRecall">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Submit Recall
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
}

.header-content {
    text-align: center;
}

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.page-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 0;
}

/* Cards */
.job-info-card, .recall-form-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

/* Review Section Styles */
.review-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.review-section.public-review {
    background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
    border: 2px solid #4caf50;
}

.review-section.private-review {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border: 2px solid #ff9800;
}

.section-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

.required-label {
    font-weight: 600;
    color: #495057;
}

/* Star Rating Input */
.star-rating-input {
    display: inline-flex;
    gap: 0.5rem;
    font-size: 2rem;
    cursor: pointer;
    margin: 0.5rem 0;
}

.star-rating-input i {
    color: #ddd;
    transition: all 0.2s ease;
}

.star-rating-input i:hover,
.star-rating-input i.active {
    color: #ffc107;
    transform: scale(1.1);
}

.star-rating-input i.fas {
    color: #ffc107;
}

.category-review {
    background: white;
    border-radius: 8px;
    padding: 1rem;
    border: 1px solid #e0e0e0;
    margin-bottom: 1rem;
}

.category-review .star-rating-input {
    font-size: 1.5rem;
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 2px solid #dee2e6;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.5rem;
}

.card-title {
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.card-body {
    padding: 2rem;
}

/* Info Items */
.info-item {
    margin-bottom: 1.5rem;
}

.info-item label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.info-item p {
    margin: 0;
    color: #6c757d;
    font-size: 1rem;
}

.price-highlight {
    color: #28a745 !important;
    font-weight: 600;
    font-size: 1.1rem;
}

/* Form Elements */
.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.75rem;
}

.form-select, .form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Alerts */
.alert {
    border: none;
    border-radius: 10px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    color: #856404;
    border-left: 4px solid #ffc107;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Form Actions */
.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    padding-top: 2rem;
    border-top: 2px solid #e9ecef;
    margin-top: 2rem;
}

.btn {
    border-radius: 8px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    border: none;
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    color: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
$(document).ready(function() {
    // Star Rating Functionality
    $('.star-rating-input').each(function() {
        const $container = $(this);
        const targetId = $container.attr('id').replace('-stars', '_rating');
        const $hiddenInput = $('#' + targetId);
        
        $container.find('i').on('click', function() {
            const rating = $(this).data('rating');
            $hiddenInput.val(rating);
            
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
    
    // Character counters for all textareas
    $('textarea[maxlength]').on('input', function() {
        const length = $(this).val().length;
        const maxLength = $(this).attr('maxlength');
        $(this).siblings('.form-text').find('.char-counter').text(length + '/' + maxLength + ' characters');
    });
    
    // Public comment character counter with validation
    $('#public_comment').on('input', function() {
        const length = $(this).val().length;
        $('#publicCommentCount').text(length + '/100 characters');
        
        if (length > 0 && length < 30) {
            $('#publicCommentWarning').show();
            $(this).addClass('is-invalid');
        } else {
            $('#publicCommentWarning').hide();
            $(this).removeClass('is-invalid');
        }
    });
    
    // Character counters
    $('#recall_details').on('input', function() {
        const count = $(this).val().length;
        $('#detailsCount').text(count);
        
        if (count > 1000) {
            $(this).val($(this).val().substring(0, 1000));
            $('#detailsCount').text('1000');
        }
    });

    $('#evidence_notes').on('input', function() {
        const count = $(this).val().length;
        $('#evidenceCount').text(count);
        
        if (count > 500) {
            $(this).val($(this).val().substring(0, 500));
            $('#evidenceCount').text('500');
        }
    });

    // Form submission
    $('#recallJobForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $submitBtn = $('#submitRecall');
        
        // Validate form
        if (!$('#recall_reason').val()) {
            alert('Please select a reason for the recall.');
            return;
        }
        
        if (!$('#severity').val()) {
            alert('Please select a severity level.');
            return;
        }
        
        if (!$('#recall_details').val().trim()) {
            alert('Please provide detailed description of the issues.');
            return;
        }
        
        if ($('#recall_details').val().length < 20) {
            alert('Please provide a more detailed description (at least 20 characters).');
            return;
        }
        
        // Validate review fields (ONLY if review form exists - i.e., no existing review)
        if ($('#overall_rating').length > 0) {
            const requiredRatings = ['overall', 'professionalism', 'quality', 'communication', 'punctuality'];
            let missingRatings = [];
            
            requiredRatings.forEach(function(category) {
                const ratingValue = $('#' + category + '_rating').val();
                if (!ratingValue || ratingValue < 1 || ratingValue > 5) {
                    missingRatings.push(category.charAt(0).toUpperCase() + category.slice(1));
                }
            });
            
            if (missingRatings.length > 0) {
                alert('Please provide star ratings for: ' + missingRatings.join(', '));
                return;
            }
            
            // Validate public comment
            const publicComment = $('#public_comment').val().trim();
            if (publicComment.length < 30) {
                alert('Public comment must be at least 30 characters.');
                $('#public_comment').focus();
                return;
            }
            if (publicComment.length > 100) {
                alert('Public comment must not exceed 100 characters.');
                $('#public_comment').focus();
                return;
            }
        }
        
        // Show loading state
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Submitting...');
        
        // Submit form
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect;
                } else {
                    alert('Error: ' + response.message);
                    $submitBtn.prop('disabled', false).html('<i class="fas fa-exclamation-triangle me-1"></i>Submit Recall');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $submitBtn.prop('disabled', false).html('<i class="fas fa-exclamation-triangle me-1"></i>Submit Recall');
            }
        });
    });
});
</script>
