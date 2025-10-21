<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-star"></i> Review Details
                        </h4>
                        <div class="review-meta">
                            <span class="badge badge-info">
                                <?php echo ($review->review_type == 'host_to_cleaner') ? 'Host → Cleaner' : 'Cleaner → Host'; ?>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Review Header -->
                    <div class="review-header mb-4">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-2">
                                    <?php if ($review->title): ?>
                                        <?php echo htmlspecialchars($review->title); ?>
                                    <?php else: ?>
                                        Review for <?php echo htmlspecialchars($review->reviewee_first_name . ' ' . $review->reviewee_last_name); ?>
                                    <?php endif; ?>
                                </h5>
                                <div class="review-rating mb-2">
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?php echo ($i <= $review->rating) ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                        <span class="rating-text"><?php echo $review->rating; ?>/5</span>
                                    </div>
                                </div>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-user"></i> 
                                    By <?php echo htmlspecialchars($review->reviewer_first_name . ' ' . $review->reviewer_last_name); ?>
                                    (@<?php echo htmlspecialchars($review->reviewer_username); ?>)
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <div class="review-date">
                                    <i class="fas fa-calendar"></i>
                                    <?php echo date('M j, Y', strtotime($review->created_at)); ?>
                                </div>
                                <div class="review-job">
                                    <i class="fas fa-briefcase"></i>
                                    <a href="<?php echo base_url('jobs/view/' . $review->job_id); ?>">
                                        <?php echo htmlspecialchars($review->job_title); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Review Categories -->
                    <?php if ($review->review_categories): ?>
                    <div class="review-categories mb-4">
                        <h6><i class="fas fa-tags"></i> Categories</h6>
                        <div class="categories-list">
                            <?php 
                            $categories = json_decode($review->review_categories, true);
                            $all_categories = $this->M_reviews->get_review_categories($review->review_type);
                            if ($categories):
                                foreach ($categories as $category):
                                    if (isset($all_categories[$category])):
                            ?>
                                <span class="badge badge-secondary mr-2 mb-2">
                                    <?php echo htmlspecialchars($all_categories[$category]); ?>
                                </span>
                            <?php 
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Review Comment -->
                    <?php if ($review->comment): ?>
                    <div class="review-comment mb-4">
                        <h6><i class="fas fa-comment"></i> Review</h6>
                        <div class="comment-text">
                            <?php echo nl2br(htmlspecialchars($review->comment)); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Review Responses -->
                    <?php if (!empty($responses)): ?>
                    <div class="review-responses mb-4">
                        <h6><i class="fas fa-reply"></i> Responses</h6>
                        <?php foreach ($responses as $response): ?>
                        <div class="response-item">
                            <div class="response-header">
                                <strong>
                                    <?php echo htmlspecialchars($response->responder_first_name . ' ' . $response->responder_last_name); ?>
                                    (@<?php echo htmlspecialchars($response->responder_username); ?>)
                                </strong>
                                <span class="text-muted small">
                                    <?php echo date('M j, Y g:i A', strtotime($response->created_at)); ?>
                                </span>
                                <?php if ($response->is_private): ?>
                                    <span class="badge badge-warning ml-2">Private Response</span>
                                <?php endif; ?>
                            </div>
                            <div class="response-text">
                                <?php echo nl2br(htmlspecialchars($response->response_text)); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Response Form (if user can respond) -->
                    <?php if ($this->session->userdata('user_id') == $review->reviewee_id): ?>
                    <div class="response-form">
                        <h6><i class="fas fa-reply"></i> Respond to Review</h6>
                        <form id="responseForm" method="post">
                            <input type="hidden" name="review_id" value="<?php echo $review->id; ?>">
                            
                            <div class="form-group">
                                <textarea class="form-control" name="response_text" rows="3" placeholder="Write your response to this review..." required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_private" value="1" id="is_private">
                                    <label class="form-check-label" for="is_private">
                                        Make this response private (only visible to the reviewer)
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Submit Response
                                </button>
                            </div>
                        </form>
                    </div>
                    <?php endif; ?>

                    <!-- Review Actions -->
                    <div class="review-actions mt-4 pt-3 border-top">
                        <a href="<?php echo base_url('reviews/user_reviews/' . $review->reviewee_id); ?>" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i> View All Reviews for <?php echo htmlspecialchars($review->reviewee_first_name); ?>
                        </a>
                        <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.review-header {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #007bff;
}

.stars {
    display: inline-block;
    font-size: 1.5rem;
    margin-right: 10px;
}

.star {
    color: #ddd;
}

.star.filled {
    color: #ffc107;
}

.rating-text {
    font-weight: 500;
    color: #666;
}

.review-categories .categories-list {
    margin-top: 10px;
}

.review-comment .comment-text {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    border-left: 3px solid #28a745;
    line-height: 1.6;
}

.response-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 15px;
    border-left: 3px solid #17a2b8;
}

.response-header {
    margin-bottom: 10px;
    font-size: 0.9rem;
}

.response-text {
    line-height: 1.6;
}

.response-form {
    background: #e7f3ff;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #b3d7ff;
}

.review-actions {
    text-align: center;
}

.review-actions .btn {
    margin: 0 5px;
}

.badge {
    font-size: 0.8rem;
}

.text-muted {
    color: #6c757d !important;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
</style>

<script>
$(document).ready(function() {
    // Response form submission
    $('#responseForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Submitting...').prop('disabled', true);
        
        $.ajax({
            url: '<?php echo base_url("reviews/respond"); ?>',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
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
