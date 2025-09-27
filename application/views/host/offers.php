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
/* Offers Management Styles */
.offers-container {
    padding: 2rem 0;
}

/* Make container wider */
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

.offers-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    color: white;
}

.header-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.header-actions .btn {
    border-radius: 50px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.header-actions .btn:hover {
    transform: translateY(-2px);
}

/* Filter Section */
.filter-section {
    margin-bottom: 2rem;
}

.filter-card {
    background: white;
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    overflow: hidden;
}

.filter-card .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: none;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.filter-card .card-body {
    padding: 2rem;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.input-group-text {
    background: #f8f9fa;
    border-color: #dee2e6;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.offers-header h2 {
    margin: 0 0 1rem 0;
    font-weight: 700;
}

.offers-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    backdrop-filter: blur(10px);
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.job-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    position: relative;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.job-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem;
    color: white;
    position: relative;
}

.job-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.job-price {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.job-date {
    font-size: 0.9rem;
    opacity: 0.9;
}

.offers-count {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: 600;
}

.job-body {
    padding: 1.5rem;
}

.offers-list {
    margin-top: 1rem;
    max-height: 400px;
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 10px;
    /* Custom scrollbar */
    scrollbar-width: thin;
    scrollbar-color: #667eea #f1f1f1;
}

.offers-list::-webkit-scrollbar {
    width: 6px;
}

.offers-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.offers-list::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 10px;
    transition: background 0.3s ease;
}

.offers-list::-webkit-scrollbar-thumb:hover {
    background: #5a6fd8;
}

.offer-item {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    border-left: 4px solid #e9ecef;
    transition: all 0.3s ease;
}

.offer-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.offer-item.counter-offer {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
}

.offer-item.accept-offer {
    border-left-color: #28a745;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
}

.offer-item.accepted {
    border-left-color: #007bff;
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 123, 255, 0.05) 100%);
}

.offer-item.declined {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
    opacity: 0.7;
}

.offer-header {
    display: flex;
    justify-content: between;
    align-items: center;
    margin-bottom: 1rem;
}

.offer-type {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.offer-type.counter {
    background: #ffc107;
    color: #000;
}

.offer-type.accept {
    background: #28a745;
    color: white;
}

.offer-type.accepted {
    background: #007bff;
    color: white;
}

.offer-type.declined {
    background: #dc3545;
    color: white;
}

.offer-amount {
    font-size: 1.5rem;
    font-weight: 800;
    color: #333;
    margin-bottom: 0.5rem;
}

.offer-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.offer-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.offer-detail i {
    color: #667eea;
    width: 16px;
}

.offer-message {
    background: white;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    font-style: italic;
    color: #666;
    border-left: 3px solid #e9ecef;
}

.offer-actions {
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.btn-offer {
    padding: 0.5rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
}

.btn-accept {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-accept:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-reject {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-view {
    background: #6c757d;
    color: white;
}

.btn-view:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}

.no-offers {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.no-offers i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-offers h3 {
    color: #999;
    margin-bottom: 1rem;
}

.no-offers p {
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .jobs-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .offers-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .offer-details {
        grid-template-columns: 1fr;
    }
    
    .offer-actions {
        flex-direction: column;
    }
    
    /* Adjust offers list for mobile */
    .offers-list {
        max-height: 300px;
        padding-right: 5px;
    }
    
    .offers-list::-webkit-scrollbar {
        width: 4px;
    }
}

@media (max-width: 576px) {
    .offers-list {
        max-height: 250px;
    }
    
    .job-card {
        margin: 0 10px;
    }
    
    .job-body {
        padding: 1rem;
    }
    
    .offer-item {
        padding: 1rem;
        margin-bottom: 0.75rem;
    }
}
</style>

<div class="offers-container">
    <!-- Header Section -->
    <div class="offers-header">
        <h2>
            <i class="fas fa-handshake me-2"></i>
            Job Offers Management
        </h2>
        <p class="mb-0">Review and manage all offers for your active cleaning jobs (today and future)</p>
        
        <div class="offers-stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_offers; ?></div>
                <div class="stat-label">Total Offers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $pending_offers; ?></div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $counter_offers; ?></div>
                <div class="stat-label">Counter Offers</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $accepted_offers; ?></div>
                <div class="stat-label">Accepted</div>
            </div>
        </div>
        
        <div class="header-actions mt-3">
            <a href="<?php echo base_url('host/expired-jobs'); ?>" class="btn btn-outline-light">
                <i class="fas fa-clock me-2"></i>
                View Expired Jobs
            </a>
            <a href="<?php echo base_url('host/create_job'); ?>" class="btn btn-light">
                <i class="fas fa-plus me-2"></i>
                Create New Job
            </a>
        </div>
    </div>

    <!-- Filter and Search Section -->
    <div class="filter-section">
        <div class="filter-card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-filter text-info me-2"></i>
                    Filters & Search
                </h5>
                <button class="btn btn-sm btn-outline-secondary" id="toggleFilters">
                    <i class="fas fa-chevron-down"></i>
                </button>
            </div>
            <div class="card-body" id="filterBody">
                <form method="GET" action="<?php echo base_url('host/offers'); ?>" id="filterForm">
                    <div class="row">
                        <!-- Search -->
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Search Jobs</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       name="search" 
                                       value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                                       placeholder="Search by job title or description">
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Sort By</label>
                            <select class="form-select" name="sort">
                                <option value="scheduled_date" <?php echo ($filters['sort_by'] ?? '') === 'scheduled_date' ? 'selected' : ''; ?>>
                                    Scheduled Date
                                </option>
                                <option value="title" <?php echo ($filters['sort_by'] ?? '') === 'title' ? 'selected' : ''; ?>>
                                    Job Title
                                </option>
                                <option value="price" <?php echo ($filters['sort_by'] ?? '') === 'price' ? 'selected' : ''; ?>>
                                    Price
                                </option>
                                <option value="offers_count" <?php echo ($filters['sort_by'] ?? '') === 'offers_count' ? 'selected' : ''; ?>>
                                    Number of Offers
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">Order</label>
                            <select class="form-select" name="order">
                                <option value="ASC" <?php echo ($filters['sort_order'] ?? '') === 'ASC' ? 'selected' : ''; ?>>
                                    Ascending
                                </option>
                                <option value="DESC" <?php echo ($filters['sort_order'] ?? '') === 'DESC' ? 'selected' : ''; ?>>
                                    Descending
                                </option>
                            </select>
                        </div>

                        <div class="col-md-1 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <div class="row">
                        <div class="col-12">
                            <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-times me-1"></i>
                                Clear Filters
                            </a>
                            <span class="text-muted ms-3">
                                Showing <?php echo count($jobs_with_offers); ?> jobs with offers
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Jobs with Offers -->
    <?php if (!empty($jobs_with_offers)): ?>
        <div class="jobs-grid">
            <?php foreach ($jobs_with_offers as $job): ?>
                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title"><?php echo htmlspecialchars($job->title); ?></h3>
                        <div class="job-price">$<?php echo number_format($job->suggested_price, 2); ?></div>
                        <div class="job-date">
                            <i class="fas fa-clock me-1"></i>
                            <?php 
                            if (isset($job->scheduled_date) && isset($job->scheduled_time)) {
                                $datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                                echo date('M j, Y g:i A', strtotime($datetime));
                            } else {
                                echo 'Flexible';
                            }
                            ?>
                        </div>
                        <div class="offers-count">
                            <i class="fas fa-users me-1"></i>
                            <?php echo count($job->offers); ?> offers
                        </div>
                    </div>
                    
                    <div class="job-body">
                        <p class="job-description">
                            <?php echo htmlspecialchars(substr($job->description, 0, 100)) . (strlen($job->description) > 100 ? '...' : ''); ?>
                        </p>
                        
                        <div class="offers-list">
                            <?php foreach ($job->offers as $offer): ?>
                                <div class="offer-item <?php echo $offer->offer_type; ?>-offer <?php echo $offer->status; ?>">
                                    <div class="offer-header">
                                        <div class="offer-type <?php echo $offer->offer_type; ?>">
                                            <i class="fas fa-<?php echo $offer->offer_type === 'counter' ? 'handshake' : 'check-circle'; ?>"></i>
                                            <?php echo ucfirst($offer->offer_type); ?> Offer
                                        </div>
                                        <small class="text-muted">
                                            <?php echo time_ago($offer->created_at); ?>
                                        </small>
                                    </div>
                                    
                                    <div class="offer-amount">
                                        $<?php echo number_format($offer->amount, 2); ?>
                                        <?php if ($offer->offer_type === 'counter'): ?>
                                            <small class="text-muted">
                                                (Original: $<?php echo number_format($offer->original_price, 2); ?>)
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="offer-details">
                                        <div class="offer-detail">
                                            <i class="fas fa-user"></i>
                                            <span><?php echo htmlspecialchars($offer->cleaner_username); ?></span>
                                        </div>
                                        <div class="offer-detail">
                                            <i class="fas fa-envelope"></i>
                                            <span><?php echo htmlspecialchars($offer->cleaner_email); ?></span>
                                        </div>
                                        <div class="offer-detail">
                                            <i class="fas fa-calendar"></i>
                                            <span><?php echo date('M j, Y', strtotime($offer->created_at)); ?></span>
                                        </div>
                                        <div class="offer-detail">
                                            <i class="fas fa-clock"></i>
                                            <span><?php echo date('g:i A', strtotime($offer->created_at)); ?></span>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($offer->message)): ?>
                                        <div class="offer-message">
                                            <i class="fas fa-quote-left me-1"></i>
                                            "<?php echo htmlspecialchars($offer->message); ?>"
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="offer-actions">
                                        <?php if ($offer->status === 'pending'): ?>
                                            <form method="POST" action="<?php echo base_url('host/accept_offer/' . $offer->id); ?>" style="display: inline;">
                                                <button type="submit" 
                                                        class="btn-offer btn-accept"
                                                        onclick="return confirm('Are you sure you want to accept this offer? This will reject all other pending offers for this job.');">
                                                    <i class="fas fa-check me-1"></i>
                                                    Accept
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo base_url('host/reject_offer/' . $offer->id); ?>" style="display: inline;">
                                                <button type="submit" 
                                                        class="btn-offer btn-reject"
                                                        onclick="return confirm('Are you sure you want to reject this offer?');">
                                                    <i class="fas fa-times me-1"></i>
                                                    Reject
                                                </button>
                                            </form>
                                        <?php elseif ($offer->status === 'accepted'): ?>
                                            <span class="btn-offer btn-view">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Accepted
                                            </span>
                                        <?php elseif ($offer->status === 'declined'): ?>
                                            <span class="btn-offer btn-view">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Declined
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <div class="no-offers">
            <i class="fas fa-handshake"></i>
            <h3>No Offers Yet</h3>
            <p>
                You haven't received any offers for your jobs yet. 
                <a href="<?php echo base_url('host/jobs'); ?>" style="color: #667eea; text-decoration: none;">View your jobs</a> 
                to make sure they're properly posted and visible to cleaners.
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Toggle filters visibility
    $('#toggleFilters').click(function() {
        $('#filterBody').slideToggle();
        const icon = $(this).find('i');
        icon.toggleClass('fa-chevron-down fa-chevron-up');
    });
    
    // Auto-submit form on filter change
    $('#filterForm select').change(function() {
        $('#filterForm').submit();
    });
    
    // Search with debounce
    let searchTimeout;
    $('#filterForm input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });
    
    // Add loading state to action buttons
    $('.btn-offer').on('click', function() {
        const $btn = $(this);
        if (!$btn.hasClass('btn-view')) {
            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
        }
    });
    
    // Enhance offers list scrolling
    $('.offers-list').each(function() {
        const $list = $(this);
        const $items = $list.find('.offer-item');
        
        // Add scroll indicator if there are many items
        if ($items.length > 3) {
            $list.css('position', 'relative');
            
            // Add scroll hint
            if ($list.scrollHeight > $list.height()) {
                $list.after('<div class="scroll-hint text-center text-muted mt-2" style="font-size: 0.8rem;"><i class="fas fa-chevron-up me-1"></i>Scroll to see more offers<i class="fas fa-chevron-down ms-1"></i></div>');
            }
        }
        
        // Smooth scroll behavior
        $list.css('scroll-behavior', 'smooth');
        
        // Add scroll event listener
        $list.on('scroll', function() {
            const $hint = $(this).next('.scroll-hint');
            if ($hint.length) {
                if ($(this).scrollTop() > 50) {
                    $hint.fadeOut();
                } else {
                    $hint.fadeIn();
                }
            }
        });
    });
    
    // Add keyboard navigation for offers
    $('.offers-list').on('keydown', function(e) {
        const $list = $(this);
        const scrollAmount = 100;
        
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            $list.scrollTop($list.scrollTop() + scrollAmount);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            $list.scrollTop($list.scrollTop() - scrollAmount);
        }
    });
});
</script>
