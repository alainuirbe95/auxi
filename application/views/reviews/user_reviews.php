<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- User Profile Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fas fa-user"></i> 
                                Reviews for <?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>
                                (@<?php echo htmlspecialchars($user->username); ?>)
                            </h2>
                            <p class="text-muted mb-0">
                                <?php echo ($review_type == 'host_to_cleaner') ? 'Reviews from hosts' : (($review_type == 'cleaner_to_host') ? 'Reviews from cleaners' : 'All reviews'); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="rating-summary">
                                <div class="average-rating">
                                    <span class="rating-number"><?php echo number_format($stats['average_rating'], 1); ?></span>
                                    <div class="stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?php echo ($i <= floor($stats['average_rating'])) ? 'filled' : ''; ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="review-count">
                                    <?php echo $stats['total_reviews']; ?> review<?php echo ($stats['total_reviews'] != 1) ? 's' : ''; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Filter Reviews</h5>
                            <div class="btn-group" role="group">
                                <a href="<?php echo base_url('reviews/user_reviews/' . $user->user_id); ?>" 
                                   class="btn <?php echo !$review_type ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                    All Reviews
                                </a>
                                <a href="<?php echo base_url('reviews/user_reviews/' . $user->user_id . '/host_to_cleaner'); ?>" 
                                   class="btn <?php echo $review_type == 'host_to_cleaner' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                    From Hosts
                                </a>
                                <a href="<?php echo base_url('reviews/user_reviews/' . $user->user_id . '/cleaner_to_host'); ?>" 
                                   class="btn <?php echo $review_type == 'cleaner_to_host' ? 'btn-primary' : 'btn-outline-primary'; ?>">
                                    From Cleaners
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Rating Breakdown</h5>
                            <div class="rating-breakdown">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                <div class="rating-bar">
                                    <span class="rating-label"><?php echo $i; ?>★</span>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: <?php echo $stats['total_reviews'] > 0 ? ($stats['rating_breakdown'][$i] / $stats['total_reviews'] * 100) : 0; ?>%"></div>
                                    </div>
                                    <span class="rating-count"><?php echo $stats['rating_breakdown'][$i]; ?></span>
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> 
                        Reviews 
                        <?php if ($review_type): ?>
                            (<?php echo ($review_type == 'host_to_cleaner') ? 'From Hosts' : 'From Cleaners'; ?>)
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="mb-2">
                                            <?php if ($review->title): ?>
                                                <?php echo htmlspecialchars($review->title); ?>
                                            <?php else: ?>
                                                Review for Job: <?php echo htmlspecialchars($review->job_title); ?>
                                            <?php endif; ?>
                                        </h6>
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
                            <div class="review-categories">
                                <?php 
                                $categories = json_decode($review->review_categories, true);
                                $all_categories = $this->M_reviews->get_review_categories($review->review_type);
                                if ($categories):
                                    foreach ($categories as $category):
                                        if (isset($all_categories[$category])):
                                ?>
                                    <span class="badge badge-secondary mr-1 mb-1">
                                        <?php echo htmlspecialchars($all_categories[$category]); ?>
                                    </span>
                                <?php 
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </div>
                            <?php endif; ?>

                            <!-- Review Comment -->
                            <?php if ($review->comment): ?>
                            <div class="review-comment">
                                <p><?php echo nl2br(htmlspecialchars($review->comment)); ?></p>
                            </div>
                            <?php endif; ?>

                            <!-- Review Actions -->
                            <div class="review-actions">
                                <a href="<?php echo base_url('reviews/view/' . $review->id); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i> View Details
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No reviews found</h5>
                            <p class="text-muted">
                                <?php if ($review_type): ?>
                                    This user hasn't received any reviews from <?php echo ($review_type == 'host_to_cleaner') ? 'hosts' : 'cleaners'; ?> yet.
                                <?php else: ?>
                                    This user hasn't received any reviews yet.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-summary {
    text-align: center;
}

.average-rating {
    margin-bottom: 10px;
}

.rating-number {
    font-size: 2.5rem;
    font-weight: bold;
    color: #007bff;
    display: block;
}

.stars {
    display: inline-block;
    font-size: 1.2rem;
    margin: 5px 0;
}

.star {
    color: #ddd;
}

.star.filled {
    color: #ffc107;
}

.rating-breakdown {
    margin-top: 10px;
}

.rating-bar {
    display: flex;
    align-items: center;
    margin-bottom: 8px;
}

.rating-label {
    width: 30px;
    font-size: 0.9rem;
    font-weight: 500;
}

.progress {
    flex: 1;
    height: 8px;
    margin: 0 10px;
    background-color: #e9ecef;
    border-radius: 4px;
}

.progress-bar {
    background-color: #ffc107;
    border-radius: 4px;
}

.rating-count {
    width: 30px;
    text-align: right;
    font-size: 0.9rem;
    color: #666;
}

.review-item {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
    background: #fff;
    transition: box-shadow 0.2s;
}

.review-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.review-header {
    margin-bottom: 15px;
}

.review-categories {
    margin-bottom: 15px;
}

.review-comment {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 6px;
    border-left: 3px solid #28a745;
    margin-bottom: 15px;
}

.review-comment p {
    margin: 0;
    line-height: 1.6;
}

.review-actions {
    text-align: right;
    padding-top: 15px;
    border-top: 1px solid #e9ecef;
}

.text-muted {
    color: #6c757d !important;
}

.badge {
    font-size: 0.8rem;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-group .btn {
    border-radius: 6px;
    margin-right: 5px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>

<?php $this->load->view('template/footer'); ?>
