<style>
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

.job-summary-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

.job-summary-card h5 {
    color: white;
    font-weight: 600;
}

.job-summary-card .badge {
    font-size: 0.9rem;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-check-circle"></i>
                        Confirm Job Completion & Review Cleaner
                    </h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Important:</strong> Before closing this job and releasing payment, you must review the cleaner's work. Your review helps maintain quality in our community.
                        <br><br>
                        <i class="fas fa-shield-alt text-info"></i> <strong>Fair Review Process:</strong> The cleaner has also submitted a review, but you won't be able to see it until after you submit yours. This ensures both reviews are honest and unbiased.
                    </div>
                </div>
            </div>

            <!-- Job Summary -->
            <div class="job-summary-card">
                <div class="row">
                    <div class="col-md-8">
                        <h5><i class="fas fa-broom mr-2"></i><?php echo htmlspecialchars($job->title); ?></h5>
                        <p class="mb-2">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <?php echo htmlspecialchars($job->city . ', ' . $job->state); ?>
                        </p>
                        <p class="mb-2">
                            <i class="fas fa-user mr-2"></i>
                            Cleaner: <strong><?php echo htmlspecialchars($cleaner_name); ?></strong>
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-calendar mr-2"></i>
                            Completed: <?php echo date('M j, Y g:i A', strtotime($job->updated_at)); ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-right">
                        <h3 class="mb-0">$<?php echo number_format($job->final_price ?? $job->accepted_price ?? $job->suggested_price, 2); ?></h3>
                        <p class="mb-0">Payment Amount</p>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <form id="confirmCompletionForm">
                <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
                
                <!-- Review Section - MANDATORY -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-star"></i>
                            Review the Cleaner (Required)
                        </h5>
                        <small>Your honest feedback helps maintain quality standards</small>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Review Required:</strong> You must review the cleaner before confirming job completion and releasing payment. Reviews cannot be edited after submission.
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
                </div>
                
                <!-- Action Buttons -->
                <div class="card mt-4">
                    <div class="card-body text-center">
                        <button type="submit" class="btn btn-success btn-lg btn-block" id="submitBtn">
                            <i class="fas fa-check-circle"></i> Confirm Completion, Submit Review & Release Payment
                        </button>
                        <a href="<?php echo base_url('host/completed-jobs'); ?>" class="btn btn-secondary btn-lg btn-block mt-2">
                            <i class="fas fa-arrow-left"></i> Back to Completed Jobs
                        </a>
                        <div class="mt-3">
                            <small class="text-muted">
                                Payment of <strong>$<?php echo number_format($job->final_price ?? $job->accepted_price ?? $job->suggested_price, 2); ?></strong> will be released to the cleaner immediately upon confirmation.
                            </small>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

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
    
    // Form submission with review validation
    $('#confirmCompletionForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate all star ratings are selected
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
        
        // Confirm action
        if (!confirm('Are you sure you want to confirm this job completion and release payment? This action cannot be undone.')) {
            return;
        }
        
        // Disable submit button to prevent double submission
        $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
        
        var formData = $(this).serialize();
        
        $.ajax({
            url: '<?php echo base_url("host/process_confirm_completion"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    window.location.href = response.redirect || '<?php echo base_url("host/past-jobs"); ?>';
                } else {
                    alert('Error: ' + response.message);
                    $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Confirm Completion, Submit Review & Release Payment');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-check-circle"></i> Confirm Completion, Submit Review & Release Payment');
            }
        });
    });
});
</script>

