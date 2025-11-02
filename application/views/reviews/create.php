<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-star"></i> Write Review
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Job Information -->
                    <div class="alert alert-info">
                        <h5><i class="fas fa-briefcase"></i> Job: <?php echo htmlspecialchars($job->title); ?></h5>
                        <p class="mb-1">
                            <strong>Reviewing:</strong> 
                            <?php echo htmlspecialchars($reviewee->first_name . ' ' . $reviewee->last_name); ?>
                            (@<?php echo htmlspecialchars($reviewee->username); ?>)
                        </p>
                        <p class="mb-0">
                            <strong>Review Type:</strong> 
                            <?php echo ($review_type == 'host_to_cleaner') ? 'Host reviewing Cleaner' : 'Cleaner reviewing Host'; ?>
                        </p>
                    </div>

                    <!-- Review Form -->
                    <form id="reviewForm" method="post">
                        <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">
                        
                        <!-- Rating Section -->
                        <div class="form-group">
                            <label class="form-label required">Overall Rating</label>
                            <div class="rating-input">
                                <div class="stars">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5" class="star">★</label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" class="star">★</label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" class="star">★</label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" class="star">★</label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" class="star">★</label>
                                </div>
                                <div class="rating-text">
                                    <span id="ratingText">Select a rating</span>
                                </div>
                            </div>
                        </div>

                        <!-- Review Categories -->
                        <div class="form-group">
                            <label class="form-label required">What was your experience like?</label>
                            <p class="text-muted small">Select all that apply (at least one required)</p>
                            <div class="categories-grid">
                                <?php foreach ($categories as $key => $label): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="<?php echo $key; ?>" id="cat_<?php echo $key; ?>">
                                    <label class="form-check-label" for="cat_<?php echo $key; ?>">
                                        <?php echo htmlspecialchars($label); ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Review Title -->
                        <div class="form-group">
                            <label for="title" class="form-label">Review Title (Optional)</label>
                            <input type="text" class="form-control" id="title" name="title" maxlength="255" placeholder="Summarize your experience in a few words">
                        </div>

                        <!-- Review Comment -->
                        <div class="form-group">
                            <label for="comment" class="form-label">Detailed Review</label>
                            <textarea class="form-control" id="comment" name="comment" rows="5" maxlength="1000" placeholder="Share your detailed experience with this <?php echo ($review_type == 'host_to_cleaner') ? 'cleaner' : 'host'; ?>..."></textarea>
                            <div class="form-text">
                                <span id="charCount">0</span>/1000 characters
                            </div>
                        </div>

                        <!-- Review Guidelines -->
                        <div class="alert alert-warning">
                            <h6><i class="fas fa-info-circle"></i> Review Guidelines</h6>
                            <ul class="mb-0 small">
                                <li>Be honest and constructive in your feedback</li>
                                <li>Focus on the work quality and professionalism</li>
                                <li>Reviews are public and cannot be edited after submission</li>
                                <li>You have 48 hours from job completion to submit your review</li>
                            </ul>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-paper-plane"></i> Submit Review
                            </button>
                            <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-secondary btn-lg ml-2">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-input {
    text-align: center;
    margin: 20px 0;
}

.stars {
    display: inline-block;
    position: relative;
    font-size: 2rem;
    margin-bottom: 10px;
}

.stars input[type="radio"] {
    display: none;
}

.stars label {
    color: #ddd;
    cursor: pointer;
    transition: color 0.2s;
    margin: 0 2px;
}

.stars label:hover,
.stars label:hover ~ label,
.stars input[type="radio"]:checked ~ label {
    color: #ffc107;
}

.rating-text {
    font-size: 1.1rem;
    font-weight: 500;
    color: #666;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 10px;
    margin-top: 10px;
}

.form-check {
    padding: 8px 12px;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    transition: all 0.2s;
}

.form-check:hover {
    background-color: #f8f9fa;
    border-color: #007bff;
}

.form-check-input:checked + .form-check-label {
    color: #007bff;
    font-weight: 500;
}

.form-check-input:checked ~ .form-check {
    background-color: #e7f3ff;
    border-color: #007bff;
}

.required::after {
    content: " *";
    color: #dc3545;
}

#charCount {
    font-weight: 500;
}

.alert {
    border-radius: 8px;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-lg {
    padding: 12px 30px;
    font-size: 1.1rem;
}
</style>

<script>
$(document).ready(function() {
    // Star rating interaction
    $('.stars input[type="radio"]').change(function() {
        const rating = $(this).val();
        const texts = {
            '1': 'Poor',
            '2': 'Fair', 
            '3': 'Good',
            '4': 'Very Good',
            '5': 'Excellent'
        };
        $('#ratingText').text(texts[rating]);
    });

    // Character count for comment
    $('#comment').on('input', function() {
        const count = $(this).val().length;
        $('#charCount').text(count);
        
        if (count > 900) {
            $('#charCount').css('color', '#dc3545');
        } else if (count > 700) {
            $('#charCount').css('color', '#ffc107');
        } else {
            $('#charCount').css('color', '#6c757d');
        }
    });

    // Form submission
    $('#reviewForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        if (!$('input[name="rating"]:checked').length) {
            alert('Please select a rating.');
            return;
        }
        
        if (!$('input[name="categories[]"]:checked').length) {
            alert('Please select at least one category.');
            return;
        }
        
        // Show loading state
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Submitting...').prop('disabled', true);
        
        // Submit form
        $.ajax({
            url: '<?php echo base_url("reviews/submit"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message
                    alert(response.message);
                    // Redirect
                    window.location.href = response.redirect;
                } else {
                    alert(response.message);
                    submitBtn.html(originalText).prop('disabled', false);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                submitBtn.html(originalText).prop('disabled', false);
            }
        });
    });
});
</script>

<?php $this->load->view('template/footer'); ?>
