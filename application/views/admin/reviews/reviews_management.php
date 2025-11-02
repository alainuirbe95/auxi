<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
  <!-- Modern Header Section -->
  <div class="modern-header-section">
    <div class="header-content">
      <h1 class="page-title">
        <i class="fas fa-star mr-3"></i>
        Review Management
      </h1>
      <p class="page-subtitle">
        Monitor and manage all reviews across the platform
      </p>
    </div>
  </div>

  <!-- Statistics Cards -->
  <div class="row mb-4">
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-primary">
        <div class="stats-icon">
          <i class="fas fa-comments"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['total_reviews'] ?? 0); ?></h3>
          <p class="stats-label">Total Reviews</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-success">
        <div class="stats-icon">
          <i class="fas fa-star"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['average_rating'] ?? 0, 1); ?></h3>
          <p class="stats-label">Average Rating</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-info">
        <div class="stats-icon">
          <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['reviews_this_week'] ?? 0); ?></h3>
          <p class="stats-label">This Week</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-cyan">
        <div class="stats-icon">
          <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['reviews_this_month'] ?? 0); ?></h3>
          <p class="stats-label">This Month</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Additional Statistics -->
  <div class="row mb-4">
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-eye-slash"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['hidden_reviews'] ?? 0); ?></h3>
          <p class="stats-label">Hidden Reviews</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-teal">
        <div class="stats-icon">
          <i class="fas fa-user-tie"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['host_to_cleaner'] ?? 0); ?></h3>
          <p class="stats-label">Host Reviews</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-orange">
        <div class="stats-icon">
          <i class="fas fa-user"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['cleaner_to_host'] ?? 0); ?></h3>
          <p class="stats-label">Cleaner Reviews</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-gray">
        <div class="stats-icon">
          <i class="fas fa-flag"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['flagged_reviews'] ?? 0); ?></h3>
          <p class="stats-label">Flagged Reviews</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Category Ratings Summary -->
  <?php if (isset($stats['category_averages'])): ?>
  <div class="row mb-4">
    <div class="col-12">
      <div class="modern-card">
        <div class="modern-card-header">
          <h3 class="modern-card-title">
            <i class="fas fa-chart-bar mr-2"></i>
            Average Category Ratings
          </h3>
        </div>
        <div class="modern-card-body">
          <div class="row">
            <div class="col-md-3 mb-3">
              <div class="category-stat">
                <div class="category-icon"><i class="fas fa-user-tie"></i></div>
                <div class="category-info">
                  <h4><?php echo number_format($stats['category_averages']->avg_professionalism ?? 0, 1); ?></h4>
                  <p>Professionalism</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="category-stat">
                <div class="category-icon"><i class="fas fa-star"></i></div>
                <div class="category-info">
                  <h4><?php echo number_format($stats['category_averages']->avg_quality ?? 0, 1); ?></h4>
                  <p>Quality</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="category-stat">
                <div class="category-icon"><i class="fas fa-comments"></i></div>
                <div class="category-info">
                  <h4><?php echo number_format($stats['category_averages']->avg_communication ?? 0, 1); ?></h4>
                  <p>Communication</p>
                </div>
              </div>
            </div>
            <div class="col-md-3 mb-3">
              <div class="category-stat">
                <div class="category-icon"><i class="fas fa-clock"></i></div>
                <div class="category-info">
                  <h4><?php echo number_format($stats['category_averages']->avg_punctuality ?? 0, 1); ?></h4>
                  <p>Punctuality</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <!-- Filters Card -->
  <div class="modern-card mb-4">
    <div class="modern-card-header">
      <h3 class="modern-card-title">
        <i class="fas fa-filter mr-2"></i>
        Filters
      </h3>
    </div>
    <div class="modern-card-body">
      <form method="GET" action="<?php echo base_url('admin/reviews'); ?>" id="filterForm">
        <div class="row">
          <div class="col-md-3 mb-3">
            <label class="form-label">Rating</label>
            <select name="rating" class="form-control form-control-modern">
              <option value="">All Ratings</option>
              <option value="5" <?php echo ($filters['rating'] ?? '') == '5' ? 'selected' : ''; ?>>5 Stars</option>
              <option value="4" <?php echo ($filters['rating'] ?? '') == '4' ? 'selected' : ''; ?>>4 Stars</option>
              <option value="3" <?php echo ($filters['rating'] ?? '') == '3' ? 'selected' : ''; ?>>3 Stars</option>
              <option value="2" <?php echo ($filters['rating'] ?? '') == '2' ? 'selected' : ''; ?>>2 Stars</option>
              <option value="1" <?php echo ($filters['rating'] ?? '') == '1' ? 'selected' : ''; ?>>1 Star</option>
            </select>
          </div>
          
          <div class="col-md-3 mb-3">
            <label class="form-label">Review Type</label>
            <select name="review_type" class="form-control form-control-modern">
              <option value="">All Types</option>
              <option value="host_to_cleaner" <?php echo ($filters['review_type'] ?? '') == 'host_to_cleaner' ? 'selected' : ''; ?>>Host → Cleaner</option>
              <option value="cleaner_to_host" <?php echo ($filters['review_type'] ?? '') == 'cleaner_to_host' ? 'selected' : ''; ?>>Cleaner → Host</option>
            </select>
          </div>
          
          <div class="col-md-3 mb-3">
            <label class="form-label">Status</label>
            <select name="is_hidden" class="form-control form-control-modern">
              <option value="">All Reviews</option>
              <option value="0" <?php echo ($filters['is_hidden'] ?? '') === '0' ? 'selected' : ''; ?>>Visible</option>
              <option value="1" <?php echo ($filters['is_hidden'] ?? '') === '1' ? 'selected' : ''; ?>>Hidden</option>
            </select>
          </div>
          
          <div class="col-md-3 mb-3">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control form-control-modern" placeholder="Search reviews..." value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <button type="submit" class="btn btn-modern btn-primary">
              <i class="fas fa-filter"></i> Apply Filters
            </button>
            <a href="<?php echo base_url('admin/reviews'); ?>" class="btn btn-modern btn-secondary">
              <i class="fas fa-redo"></i> Clear Filters
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Reviews List -->
  <div class="modern-card">
    <div class="modern-card-header">
      <h3 class="modern-card-title">
        <i class="fas fa-comments mr-2"></i>
        Reviews (<?php echo number_format($pagination['total']); ?>)
      </h3>
      <div class="modern-card-tools">
        <select id="perPageSelect" class="form-control form-control-modern form-control-sm" style="width: auto; display: inline-block;">
          <option value="20" <?php echo $pagination['per_page'] == 20 ? 'selected' : ''; ?>>20 per page</option>
          <option value="50" <?php echo $pagination['per_page'] == 50 ? 'selected' : ''; ?>>50 per page</option>
          <option value="100" <?php echo $pagination['per_page'] == 100 ? 'selected' : ''; ?>>100 per page</option>
        </select>
      </div>
    </div>
    <div class="modern-card-body p-0">
      <?php if (!empty($reviews)): ?>
      <div class="table-responsive">
        <table class="modern-table">
          <thead>
            <tr>
              <th width="8%">ID</th>
              <th width="15%">Reviewer</th>
              <th width="15%">Reviewee</th>
              <th width="10%">Rating</th>
              <th width="25%">Comment</th>
              <th width="12%">Job</th>
              <th width="10%">Date</th>
              <th width="5%">Status</th>
              <th width="10%">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reviews as $review): ?>
            <tr class="review-row <?php echo $review->is_hidden ? 'review-hidden' : ''; ?> clickable-row" data-review-id="<?php echo $review->review_id; ?>">
              <td>
                <strong>#<?php echo $review->review_id; ?></strong>
              </td>
              <td>
                <div>
                  <strong><?php echo htmlspecialchars($review->reviewer_name ?? 'Unknown'); ?></strong>
                  <br>
                  <small class="text-muted">
                    <span class="badge badge-<?php echo $review->review_type == 'host_to_cleaner' ? 'info' : 'success'; ?>">
                      <?php echo $review->review_type == 'host_to_cleaner' ? 'Host' : 'Cleaner'; ?>
                    </span>
                  </small>
                </div>
              </td>
              <td>
                <div>
                  <strong><?php echo htmlspecialchars($review->reviewee_name ?? 'Unknown'); ?></strong>
                  <br>
                  <small class="text-muted">
                    <span class="badge badge-<?php echo $review->review_type == 'host_to_cleaner' ? 'success' : 'info'; ?>">
                      <?php echo $review->review_type == 'host_to_cleaner' ? 'Cleaner' : 'Host'; ?>
                    </span>
                  </small>
                </div>
              </td>
              <td>
                <div class="review-rating">
                  <?php for($i = 1; $i <= 5; $i++): ?>
                    <i class="fas fa-star <?php echo $i <= $review->overall_rating ? 'text-warning' : 'text-muted'; ?>"></i>
                  <?php endfor; ?>
                  <br>
                  <strong><?php echo number_format($review->overall_rating, 1); ?></strong>
                </div>
              </td>
              <td>
                <div class="review-comment-preview">
                  <?php echo htmlspecialchars(substr($review->public_comment, 0, 80)); ?>
                  <?php if (strlen($review->public_comment) > 80): ?>...<?php endif; ?>
                </div>
              </td>
              <td>
                <?php if (!empty($review->job_title)): ?>
                  <small>
                    <i class="fas fa-briefcase"></i>
                    <?php echo htmlspecialchars(substr($review->job_title, 0, 30)); ?>
                    <?php if (strlen($review->job_title) > 30): ?>...<?php endif; ?>
                  </small>
                <?php else: ?>
                  <small class="text-muted">N/A</small>
                <?php endif; ?>
              </td>
              <td>
                <small class="text-muted">
                  <?php 
                  $review_date = new DateTime($review->created_at);
                  echo $review_date->format('M j, Y');
                  ?>
                </small>
              </td>
              <td>
                <?php if ($review->is_hidden): ?>
                  <span class="badge badge-danger">
                    <i class="fas fa-eye-slash"></i> Hidden
                  </span>
                <?php else: ?>
                  <span class="badge badge-success">
                    <i class="fas fa-eye"></i> Visible
                  </span>
                <?php endif; ?>
              </td>
              <td>
                <div class="btn-group btn-group-sm" onclick="event.stopPropagation();">
                  <?php if ($review->is_hidden): ?>
                  <button type="button" class="btn btn-success btn-sm unhide-review-btn" 
                          data-review-id="<?php echo $review->review_id; ?>"
                          title="Restore Review">
                    <i class="fas fa-eye"></i>
                  </button>
                  <?php else: ?>
                  <button type="button" class="btn btn-warning btn-sm hide-review-btn" 
                          data-review-id="<?php echo $review->review_id; ?>"
                          title="Hide Review">
                    <i class="fas fa-eye-slash"></i>
                  </button>
                  <?php endif; ?>
                  
                  <button type="button" class="btn btn-danger btn-sm delete-review-btn" 
                          data-review-id="<?php echo $review->review_id; ?>"
                          title="Delete Review">
                    <i class="fas fa-trash"></i>
                  </button>
                </div>
              </td>
            </tr>
            <!-- Review Details Dropdown Row -->
            <tr class="review-details-row" id="details-<?php echo $review->review_id; ?>" style="display: none;">
              <td colspan="9">
                <div class="review-details-container">
                  <div class="row">
                    <!-- Overall Review -->
                    <div class="col-md-12 mb-3">
                      <div class="card bg-light">
                        <div class="card-body">
                          <h5 class="mb-2">
                            <i class="fas fa-star text-warning"></i> 
                            Overall Rating: <?php echo number_format($review->overall_rating, 1); ?>/5
                          </h5>
                          <p class="mb-0"><strong>Public Comment:</strong></p>
                          <p><?php echo nl2br(htmlspecialchars($review->public_comment)); ?></p>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Category Ratings -->
                    <div class="col-md-6 mb-3">
                      <div class="card">
                        <div class="card-header">
                          <strong><i class="fas fa-chart-bar"></i> Category Ratings</strong>
                        </div>
                        <div class="card-body">
                          <div class="category-rating-detail">
                            <strong>Professionalism:</strong>
                            <div class="rating-stars-inline">
                              <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review->professionalism_rating ? 'text-warning' : 'text-muted'; ?>"></i>
                              <?php endfor; ?>
                              <span class="ml-2"><?php echo number_format($review->professionalism_rating, 1); ?>/5</span>
                            </div>
                            <?php if (!empty($review->professionalism_comment)): ?>
                              <p class="mb-2 text-muted"><small><?php echo htmlspecialchars($review->professionalism_comment); ?></small></p>
                            <?php endif; ?>
                          </div>
                          
                          <div class="category-rating-detail">
                            <strong>Quality:</strong>
                            <div class="rating-stars-inline">
                              <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review->quality_rating ? 'text-warning' : 'text-muted'; ?>"></i>
                              <?php endfor; ?>
                              <span class="ml-2"><?php echo number_format($review->quality_rating, 1); ?>/5</span>
                            </div>
                            <?php if (!empty($review->quality_comment)): ?>
                              <p class="mb-2 text-muted"><small><?php echo htmlspecialchars($review->quality_comment); ?></small></p>
                            <?php endif; ?>
                          </div>
                          
                          <div class="category-rating-detail">
                            <strong>Communication:</strong>
                            <div class="rating-stars-inline">
                              <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review->communication_rating ? 'text-warning' : 'text-muted'; ?>"></i>
                              <?php endfor; ?>
                              <span class="ml-2"><?php echo number_format($review->communication_rating, 1); ?>/5</span>
                            </div>
                            <?php if (!empty($review->communication_comment)): ?>
                              <p class="mb-2 text-muted"><small><?php echo htmlspecialchars($review->communication_comment); ?></small></p>
                            <?php endif; ?>
                          </div>
                          
                          <div class="category-rating-detail">
                            <strong>Punctuality:</strong>
                            <div class="rating-stars-inline">
                              <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $review->punctuality_rating ? 'text-warning' : 'text-muted'; ?>"></i>
                              <?php endfor; ?>
                              <span class="ml-2"><?php echo number_format($review->punctuality_rating, 1); ?>/5</span>
                            </div>
                            <?php if (!empty($review->punctuality_comment)): ?>
                              <p class="mb-2 text-muted"><small><?php echo htmlspecialchars($review->punctuality_comment); ?></small></p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- Job & Metadata -->
                    <div class="col-md-6 mb-3">
                      <div class="card">
                        <div class="card-header">
                          <strong><i class="fas fa-info-circle"></i> Review Information</strong>
                        </div>
                        <div class="card-body">
                          <p><strong>Job:</strong> <?php echo htmlspecialchars($review->job_title ?? 'N/A'); ?></p>
                          <p><strong>Review Type:</strong> 
                            <span class="badge badge-<?php echo $review->review_type == 'host_to_cleaner' ? 'info' : 'success'; ?>">
                              <?php echo $review->review_type == 'host_to_cleaner' ? 'Host → Cleaner' : 'Cleaner → Host'; ?>
                            </span>
                          </p>
                          <p><strong>Created:</strong> <?php echo date('M j, Y g:i A', strtotime($review->created_at)); ?></p>
                          
                          <?php if (!empty($review->private_notes)): ?>
                          <div class="alert alert-warning mt-3">
                            <strong><i class="fas fa-lock"></i> Private Notes:</strong>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($review->private_notes)); ?></p>
                          </div>
                          <?php endif; ?>
                          
                          <?php if ($review->is_hidden): ?>
                          <div class="alert alert-danger mt-3">
                            <strong><i class="fas fa-eye-slash"></i> Hidden Review</strong>
                            <p class="mb-1"><strong>Reason:</strong> <?php echo htmlspecialchars($review->hidden_reason ?? 'N/A'); ?></p>
                            <p class="mb-0"><strong>Hidden at:</strong> <?php echo $review->hidden_at ? date('M j, Y g:i A', strtotime($review->hidden_at)) : 'N/A'; ?></p>
                          </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <?php if ($pagination['total_pages'] > 1): ?>
      <div class="modern-pagination">
        <div class="pagination-info">
          Showing <?php echo count($reviews); ?> of <?php echo number_format($pagination['total']); ?> reviews
        </div>
        
        <nav aria-label="Reviews pagination">
          <ul class="pagination">
            <?php if ($pagination['current_page'] > 1): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&per_page=<?php echo $pagination['per_page']; ?><?php echo !empty($filters['rating']) ? '&rating=' . $filters['rating'] : ''; ?><?php echo !empty($filters['review_type']) ? '&review_type=' . $filters['review_type'] : ''; ?><?php echo !empty($filters['is_hidden']) ? '&is_hidden=' . $filters['is_hidden'] : ''; ?><?php echo !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : ''; ?>">«</a>
            </li>
            <?php endif; ?>
            
            <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
            <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $i; ?>&per_page=<?php echo $pagination['per_page']; ?><?php echo !empty($filters['rating']) ? '&rating=' . $filters['rating'] : ''; ?><?php echo !empty($filters['review_type']) ? '&review_type=' . $filters['review_type'] : ''; ?><?php echo !empty($filters['is_hidden']) ? '&is_hidden=' . $filters['is_hidden'] : ''; ?><?php echo !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : ''; ?>"><?php echo $i; ?></a>
            </li>
            <?php endfor; ?>
            
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&per_page=<?php echo $pagination['per_page']; ?><?php echo !empty($filters['rating']) ? '&rating=' . $filters['rating'] : ''; ?><?php echo !empty($filters['review_type']) ? '&review_type=' . $filters['review_type'] : ''; ?><?php echo !empty($filters['is_hidden']) ? '&is_hidden=' . $filters['is_hidden'] : ''; ?><?php echo !empty($filters['search']) ? '&search=' . urlencode($filters['search']) : ''; ?>">»</a>
            </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
      <?php endif; ?>
      
      <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
        <p class="text-muted">No reviews found</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<style>
/* Modern Header Section */
.modern-header-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 20px;
  padding: 2.5rem 2rem;
  margin-bottom: 2rem;
  color: white;
  position: relative;
  overflow: hidden;
}

.modern-header-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
  opacity: 0.3;
}

.header-content {
  position: relative;
  z-index: 1;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.page-subtitle {
  font-size: 1rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.95;
}

/* Modern Statistics Cards */
.stats-card {
  background: white;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  border: none;
  height: 100%;
}

.stats-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.stats-card-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.stats-card-success {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
}

.stats-card-info {
  background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
  color: white;
}

.stats-card-cyan {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  color: white;
}

.stats-card-warning {
  background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
  color: white;
}

.stats-card-teal {
  background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
  color: white;
}

.stats-card-orange {
  background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
  color: white;
}

.stats-card-gray {
  background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);
  color: white;
}

.stats-icon {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 2rem;
  opacity: 0.3;
}

.stats-content {
  position: relative;
  z-index: 1;
}

.stats-number {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  line-height: 1;
}

.stats-label {
  font-size: 0.9rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.95;
  font-weight: 500;
}

.stats-decoration {
  position: absolute;
  bottom: -15px;
  right: -15px;
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
}

/* Modern Card */
.modern-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  height: 100%;
}

.modern-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid #e9ecef;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modern-card-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
  color: #495057;
}

.modern-card-tools {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.modern-card-body {
  padding: 1.5rem;
}

/* Form Controls */
.form-control-modern {
  border: 2px solid #e9ecef;
  border-radius: 8px;
  padding: 0.5rem 0.75rem;
  transition: all 0.2s ease;
}

.form-control-modern:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.form-label {
  font-weight: 500;
  color: #495057;
  margin-bottom: 0.5rem;
}

/* Buttons */
.btn-modern {
  border-radius: 8px;
  padding: 0.5rem 1.25rem;
  font-weight: 500;
  transition: all 0.2s ease;
}

.btn-modern:hover {
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}

/* Modern Table */
.modern-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.modern-table thead {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.modern-table thead th {
  padding: 1rem;
  font-weight: 600;
  color: #495057;
  border-bottom: 2px solid #dee2e6;
  text-align: left;
}

.modern-table tbody td {
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
}

.modern-table tbody tr:hover {
  background-color: #f8f9fa;
}

/* Review Specific Styles */
.review-hidden {
  background-color: #fff3cd !important;
  opacity: 0.7;
}

.review-comment-preview {
  font-size: 0.9rem;
  color: #495057;
}

.review-rating i {
  font-size: 0.85rem;
}

.clickable-row {
  cursor: pointer;
  transition: background-color 0.2s ease;
}

.clickable-row:hover {
  background-color: #f8f9fa !important;
}

.review-details-row {
  background-color: #f8f9fa;
}

.review-details-container {
  padding: 1.5rem;
}

.category-rating-detail {
  margin-bottom: 1rem;
  padding-bottom: 0.75rem;
  border-bottom: 1px solid #e0e0e0;
}

.category-rating-detail:last-child {
  border-bottom: none;
}

.rating-stars-inline {
  margin-top: 0.25rem;
}

.rating-stars-inline i {
  font-size: 1rem;
}

/* Category Stats */
.category-stat {
  display: flex;
  align-items: center;
  padding: 1rem;
  background: #f8f9fa;
  border-radius: 10px;
}

.category-icon {
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border-radius: 10px;
  margin-right: 1rem;
  font-size: 1.5rem;
}

.category-info h4 {
  margin: 0;
  font-size: 1.75rem;
  font-weight: 700;
  color: #495057;
}

.category-info p {
  margin: 0;
  color: #6c757d;
  font-size: 0.9rem;
}

/* Pagination */
.modern-pagination {
  background: #f8f9fa;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
  padding: 1rem 1.5rem;
}

.pagination-info {
  color: #6c757d;
  font-size: 0.85rem;
  font-weight: 500;
}

.pagination {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
  gap: 0.4rem;
}

.page-item {
  margin: 0;
}

.page-link {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0.6rem 0.8rem;
  background: white;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  color: #495057;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.2s ease;
  min-width: 38px;
  height: 38px;
  font-size: 0.85rem;
}

.page-link:hover {
  background: #667eea;
  border-color: #667eea;
  color: white;
  transform: translateY(-1px);
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
  text-decoration: none;
}

.page-item.active .page-link {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-color: #667eea;
  color: white;
  box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
}

/* Responsive */
@media (max-width: 768px) {
  .modern-header-section {
    padding: 2rem 1.5rem;
  }
  
  .page-title {
    font-size: 1.75rem;
  }
  
  .stats-card {
    padding: 1.25rem;
  }
  
  .stats-number {
    font-size: 1.75rem;
  }
  
  .modern-table tbody td {
    padding: 0.8rem 0.5rem;
  }
  
  .modern-pagination {
    flex-direction: column;
    text-align: center;
  }
  
  .pagination {
    justify-content: center;
    flex-wrap: wrap;
  }
}
</style>

<script>
$(document).ready(function() {
    // Per page selector
    $('#perPageSelect').on('change', function() {
        const perPage = $(this).val();
        const url = new URL(window.location.href);
        url.searchParams.set('per_page', perPage);
        url.searchParams.set('page', '1');
        window.location.href = url.toString();
    });
    
    // Toggle review details on row click
    $('.clickable-row').on('click', function() {
        const reviewId = $(this).data('review-id');
        const $detailsRow = $('#details-' + reviewId);
        
        // Toggle this review's details
        $detailsRow.slideToggle(300);
        
        // Optionally close other open reviews
        $('.review-details-row').not($detailsRow).slideUp(300);
    });
    
    // Hide review with confirm dialog
    $('.hide-review-btn').on('click', function(e) {
        e.stopPropagation();
        
        const reviewId = $(this).data('review-id');
        
        // Prompt for reason
        const reason = prompt('Enter reason for hiding this review:\n\nOptions:\n- inappropriate_language\n- offensive_content\n- spam\n- false_information\n- harassment\n- other');
        
        if (!reason) {
            return; // User cancelled
        }
        
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '<?php echo base_url("admin/hide_review"); ?>',
            type: 'POST',
            data: { 
                review_id: reviewId,
                reason: reason.trim()
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    $btn.prop('disabled', false).html('<i class="fas fa-eye-slash"></i>');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.prop('disabled', false).html('<i class="fas fa-eye-slash"></i>');
            }
        });
    });
    
    // Unhide review
    $('.unhide-review-btn').on('click', function(e) {
        e.stopPropagation();
        
        if (!confirm('Are you sure you want to restore this review to public view?')) {
            return;
        }
        
        const reviewId = $(this).data('review-id');
        const $btn = $(this);
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '<?php echo base_url("admin/unhide_review"); ?>',
            type: 'POST',
            data: { review_id: reviewId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    $btn.prop('disabled', false).html('<i class="fas fa-eye"></i>');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.prop('disabled', false).html('<i class="fas fa-eye"></i>');
            }
        });
    });
    
    // Delete review
    $('.delete-review-btn').on('click', function(e) {
        e.stopPropagation();
        
        if (!confirm('Are you sure you want to PERMANENTLY delete this review? This action cannot be undone.')) {
            return;
        }
        
        const reviewId = $(this).data('review-id');
        const $btn = $(this);
        
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
        
        $.ajax({
            url: '<?php echo base_url("admin/delete_review"); ?>',
            type: 'POST',
            data: { review_id: reviewId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                $btn.prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    });
});
</script>
