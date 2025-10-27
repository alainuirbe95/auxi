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
/* Modern Offers Management Styles */
.offers-container {
    padding: 1rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Header Section */
.offers-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    color: white;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.header-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.header-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin-bottom: 2rem;
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: rgba(255, 255, 255, 0.15);
    border-radius: 15px;
    padding: 1.5rem;
    text-align: center;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
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

/* Action Buttons */
.header-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    justify-content: center;
}

.btn-header {
    padding: 0.75rem 1.5rem;
    border-radius: 50px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-header:hover {
    transform: translateY(-2px);
    text-decoration: none;
}

/* Filter Section */
.filter-section {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
}

.filter-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #dee2e6;
}

.filter-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #495057;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.filter-toggle {
    background: none;
    border: none;
    color: #667eea;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-toggle:hover {
    color: #764ba2;
    transform: scale(1.1);
}

.filter-content {
    padding: 2rem;
}

.filter-row {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr auto;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.filter-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.9rem;
}

.filter-input, .filter-select {
    padding: 0.75rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.filter-input:focus, .filter-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-filter {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

/* Jobs List */
.jobs-list {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.job-item {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: all 0.3s ease;
}

.job-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.job-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 1.5rem;
    color: white;
    position: relative;
}

.job-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.job-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    align-items: center;
    margin-bottom: 1rem;
}

.job-price {
    font-size: 1.5rem;
    font-weight: 800;
}

.job-date {
    font-size: 0.9rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.job-offers-count {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.job-description {
    padding: 1.5rem;
    color: #666;
    line-height: 1.6;
    border-bottom: 1px solid #f0f0f0;
}

/* Offers Section */
.offers-section {
    padding: 1.5rem;
}

.offers-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.offers-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    max-height: 600px;
    overflow-y: auto;
    padding-right: 0.5rem;
}

/* Custom scrollbar */
.offers-grid::-webkit-scrollbar {
    width: 8px;
}

.offers-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.offers-grid::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.offers-grid::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.offer-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    border-left: 4px solid #e9ecef;
    transition: all 0.3s ease;
    position: relative;
    display: flex;
    flex-direction: column;
}

.offer-card:hover {
    background: #e9ecef;
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.offer-card.counter-offer {
    border-left-color: #ffc107;
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(255, 193, 7, 0.05) 100%);
}

.offer-card.accept-offer {
    border-left-color: #28a745;
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(40, 167, 69, 0.05) 100%);
}

.offer-card.accepted {
    border-left-color: #007bff;
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 123, 255, 0.05) 100%);
}

.offer-card.declined {
    border-left-color: #dc3545;
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(220, 53, 69, 0.05) 100%);
    opacity: 0.7;
}

.offer-header {
    display: flex;
    justify-content: space-between;
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

.offer-time {
    font-size: 0.85rem;
    color: #666;
}

.offer-amount {
    font-size: 1.5rem;
    font-weight: 800;
    color: #333;
    margin-bottom: 1rem;
}

.offer-details {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.cleaner-rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    margin: 0.5rem 0;
}

.cleaner-rating .stars {
    color: #ffc107;
    font-size: 1rem;
}

.cleaner-rating .rating-value {
    font-weight: 600;
    font-size: 1.1rem;
    color: #333;
}

.cleaner-rating .review-count {
    color: #666;
    font-size: 0.85rem;
}

.no-rating {
    color: #999;
    font-style: italic;
    font-size: 0.9rem;
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
    flex-wrap: wrap;
}

.btn-offer {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    min-width: 120px;
    justify-content: center;
}

.btn-profile {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-profile:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-accept {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-accept:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
}

.btn-reject {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-reject:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.btn-status {
    background: #6c757d;
    color: white;
    cursor: default;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.empty-state i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1.5rem;
}

.empty-state h3 {
    color: #999;
    margin-bottom: 1rem;
    font-size: 1.5rem;
}

.empty-state p {
    font-size: 1.1rem;
    line-height: 1.6;
    max-width: 500px;
    margin: 0 auto;
}

.empty-state a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
}

.empty-state a:hover {
    text-decoration: underline;
}

/* No Offers Message */
.no-offers-message {
    text-align: center;
    padding: 3rem 2rem;
    background: #f8f9fa;
    border-radius: 15px;
    border: 2px dashed #dee2e6;
}

.no-offers-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: #6c757d;
    font-size: 2rem;
}

.no-offers-message h5 {
    color: #495057;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

.no-offers-message p {
    color: #6c757d;
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
}

.no-offers-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.no-offers-actions .btn {
    border-radius: 25px;
    padding: 0.5rem 1.5rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.no-offers-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive Design */
@media (max-width: 768px) {
    .offers-container {
        padding: 0.5rem;
    }
    
    .offers-header {
        padding: 1.5rem;
    }
    
    .header-title {
        font-size: 1.5rem;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
    
    .header-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn-header {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .job-header {
        padding: 1rem;
    }
    
    .job-title {
        font-size: 1.2rem;
    }
    
    .job-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .job-offers-count {
        position: static;
        margin-top: 1rem;
        align-self: flex-start;
    }
    
    .job-description {
        padding: 1rem;
    }
    
    .offers-section {
        padding: 1rem;
    }
    
    .offer-card {
        padding: 1rem;
    }
    
    .offers-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .offer-details {
        gap: 0.75rem;
    }
    
    .offer-actions {
        flex-direction: column;
    }
    
    .btn-offer {
        width: 100%;
        min-width: auto;
    }
}

@media (max-width: 480px) {
    .offers-header {
        padding: 1rem;
    }
    
    .header-title {
        font-size: 1.3rem;
        flex-direction: column;
        text-align: center;
        gap: 0.5rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .offers-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-content {
        padding: 1rem;
    }
    
    .offer-amount {
        font-size: 1.3rem;
    }
}
</style>

<div class="offers-container">
    <!-- Header Section -->
    <div class="offers-header">
        <h1 class="header-title">
            <i class="fas fa-handshake"></i>
            Job Offers Management
        </h1>
        <p class="header-subtitle">Review and manage all offers for your active cleaning jobs</p>
        
        <div class="stats-grid">
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
        
        <div class="header-actions">
            <a href="<?php echo base_url('host/expired-jobs'); ?>" class="btn-header" style="background: rgba(255, 255, 255, 0.2); color: white; border: 1px solid rgba(255, 255, 255, 0.3);">
                <i class="fas fa-clock"></i>
                View Expired Jobs
            </a>
            <a href="<?php echo base_url('host/create_job'); ?>" class="btn-header" style="background: rgba(255, 255, 255, 0.9); color: #667eea;">
                <i class="fas fa-plus"></i>
                Create New Job
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <h3 class="filter-title">
                <i class="fas fa-filter"></i>
                Filters & Search
            </h3>
            <button class="filter-toggle" id="toggleFilters">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="filter-content" id="filterContent">
            <form method="GET" action="<?php echo base_url('host/offers'); ?>" id="filterForm">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="filter-label">Search Jobs</label>
                        <input type="text" 
                               class="filter-input" 
                               name="search" 
                               value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                               placeholder="Search by job title or description">
                    </div>
                    
                    <div class="filter-group">
                        <label class="filter-label">Sort By</label>
                        <select class="filter-select" name="sort">
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
                    
                    <div class="filter-group">
                        <label class="filter-label">Order</label>
                        <select class="filter-select" name="order">
                            <option value="ASC" <?php echo ($filters['sort_order'] ?? '') === 'ASC' ? 'selected' : ''; ?>>
                                Ascending
                            </option>
                            <option value="DESC" <?php echo ($filters['sort_order'] ?? '') === 'DESC' ? 'selected' : ''; ?>>
                                Descending
                            </option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <button type="submit" class="btn-filter">
                            <i class="fas fa-search"></i>
                            Search
                        </button>
                    </div>
                </div>
                
                <div style="margin-top: 1rem;">
                    <a href="<?php echo base_url('host/offers'); ?>" style="color: #667eea; text-decoration: none; font-weight: 600;">
                        <i class="fas fa-times"></i> Clear Filters
                    </a>
                    <span style="color: #666; margin-left: 1rem;">
                        Showing <?php echo count($jobs_with_offers); ?> jobs (<?php echo $total_offers; ?> total offers)
                    </span>
                </div>
            </form>
        </div>
    </div>

    <!-- Jobs List -->
    <?php if (!empty($jobs_with_offers)): ?>
        <div class="jobs-list">
            <?php foreach ($jobs_with_offers as $job): ?>
                <div class="job-item">
                    <div class="job-header">
                        <h3 class="job-title"><?php echo htmlspecialchars($job->title); ?></h3>
                        <div class="job-meta">
                            <?php 
                            // Calculate cleaner payout from host's suggested price
                            $tax_amount = ($job->suggested_price * $pricing_params['tax_percent']) / 100;
                            $app_fee = ($job->suggested_price * $pricing_params['app_percent']) / 100;
                            $cleaner_payout = $job->suggested_price - $pricing_params['base_charge'] - $tax_amount - $app_fee;
                            ?>
                            <div class="job-price">
                                <span style="font-size: 1.2rem; opacity: 0.9;">Your Price:</span> 
                                <strong>$<?php echo number_format($job->suggested_price, 2); ?></strong>
                                <span style="font-size: 0.9rem; opacity: 0.85; display: block; margin-top: 0.25rem;">
                                    (Cleaner earns: $<?php echo number_format($cleaner_payout, 2); ?>)
                                </span>
                            </div>
                            <div class="job-date">
                                <i class="fas fa-clock"></i>
                                <?php 
                                if (isset($job->scheduled_date) && isset($job->scheduled_time)) {
                                    $datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
                                    echo date('M j, Y g:i A', strtotime($datetime));
                                } else {
                                    echo 'Flexible';
                                }
                                ?>
                            </div>
                        </div>
                        <div class="job-offers-count">
                            <i class="fas fa-users"></i>
                            <?php echo count($job->offers); ?> offers
                        </div>
                    </div>
                    
                    <div class="job-description">
                        <?php echo htmlspecialchars($job->description); ?>
                    </div>
                    
                    <div class="offers-section">
                        <h4 class="offers-title">
                            <i class="fas fa-handshake"></i>
                            Offers (<?php echo count($job->offers); ?>)
                        </h4>
                        
                        <div class="offers-grid">
                            <?php if (empty($job->offers)): ?>
                                <div class="no-offers-message">
                                    <div class="no-offers-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h5>No Offers Received</h5>
                                    <p>This job hasn't received any offers yet. Cleaners may still be reviewing your job posting.</p>
                                    <div class="no-offers-actions">
                                        <a href="<?php echo base_url('host/job/' . $job->id); ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i>
                                            View Job Details
                                        </a>
                                        <a href="<?php echo base_url('host/edit_job/' . $job->id); ?>" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-edit me-1"></i>
                                            Edit Job
                                        </a>
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($job->offers as $offer): ?>
                                <div class="offer-card <?php echo $offer->offer_type; ?>-offer <?php echo $offer->status; ?>">
                                    <div class="offer-header">
                                        <div class="offer-type <?php echo $offer->offer_type; ?>">
                                            <i class="fas fa-<?php echo $offer->offer_type === 'counter' ? 'handshake' : 'check-circle'; ?>"></i>
                                            <?php echo ucfirst($offer->offer_type); ?> Offer
                                        </div>
                                        <div class="offer-time">
                                            <?php echo time_ago($offer->created_at); ?>
                                        </div>
                                    </div>
                                    
                                    <div class="offer-amount">
                                        <?php if ($offer->offer_type === 'counter'): ?>
                                            <!-- Counter Offer: Show host price and difference -->
                                            <div style="margin-bottom: 0.5rem;">
                                                <div style="font-size: 1.2rem; color: #666;">You'll Pay:</div>
                                                <div style="font-size: 1.8rem; font-weight: 800;">$<?php echo number_format($offer->amount, 2); ?></div>
                                            </div>
                                            <?php 
                                            $price_difference = $offer->amount - $offer->original_price;
                                            $diff_percent = ($price_difference / $offer->original_price) * 100;
                                            ?>
                                            <div style="padding: 0.75rem; background: <?php echo $price_difference > 0 ? 'rgba(255, 193, 7, 0.2)' : 'rgba(40, 167, 69, 0.2)'; ?>; border-radius: 8px; margin-top: 0.5rem;">
                                                <div style="font-size: 0.85rem; color: #666; margin-bottom: 0.25rem;">
                                                    Original Price: $<?php echo number_format($offer->original_price, 2); ?>
                                                </div>
                                                <div style="font-size: 1rem; font-weight: 700; color: <?php echo $price_difference > 0 ? '#f57c00' : '#28a745'; ?>;">
                                                    <?php echo $price_difference > 0 ? '+' : ''; ?><?php echo $price_difference > 0 ? '$' : '-$'; ?><?php echo number_format(abs($price_difference), 2); ?> 
                                                    (<?php echo $price_difference > 0 ? '+' : ''; ?><?php echo number_format(abs($diff_percent), 1); ?>%)
                                                </div>
                                                <div style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">
                                                    Cleaner's payout: $<?php echo number_format($offer->cleaner_payout_calculated ?? 0, 2); ?>
                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <!-- Accept Offer: Show host price and cleaner payout -->
                                            <div style="margin-bottom: 0.5rem;">
                                                <div style="font-size: 1.2rem; color: #666;">You'll Pay:</div>
                                                <div style="font-size: 1.8rem; font-weight: 800;">$<?php echo number_format($offer->amount, 2); ?></div>
                                            </div>
                                            <div style="font-size: 0.9rem; color: #666; margin-top: 0.5rem;">
                                                Cleaner's payout: $<?php echo number_format($offer->cleaner_payout_calculated ?? 0, 2); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="offer-details">
                                        <div class="offer-detail">
                                            <i class="fas fa-user"></i>
                                            <span><?php echo htmlspecialchars($offer->cleaner_username); ?></span>
                                        </div>
                                        
                                        <!-- Cleaner Rating -->
                                        <div class="cleaner-rating">
                                            <?php if ($offer->cleaner_rating > 0): ?>
                                                <div class="stars">
                                                    <?php
                                                    $rating = $offer->cleaner_rating;
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= floor($rating)) {
                                                            echo '<i class="fas fa-star"></i>';
                                                        } elseif ($i - 0.5 <= $rating) {
                                                            echo '<i class="fas fa-star-half-alt"></i>';
                                                        } else {
                                                            echo '<i class="far fa-star"></i>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <span class="rating-value"><?php echo number_format($offer->cleaner_rating, 1); ?></span>
                                                <span class="review-count">(<?php echo $offer->cleaner_review_count; ?> <?php echo $offer->cleaner_review_count == 1 ? 'review' : 'reviews'; ?>)</span>
                                            <?php else: ?>
                                                <span class="no-rating">No reviews yet</span>
                                            <?php endif; ?>
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
                                            <i class="fas fa-quote-left"></i>
                                            "<?php echo htmlspecialchars($offer->message); ?>"
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="offer-actions">
                                        <!-- View Cleaner Profile Button -->
                                        <a href="<?php echo base_url('cleaner/public-profile/' . $offer->cleaner_id); ?>" 
                                           class="btn-offer btn-profile">
                                            <i class="fas fa-user"></i>
                                            View Profile
                                        </a>
                                        
                                        <?php if ($offer->status === 'pending'): ?>
                                            <form method="POST" action="<?php echo base_url('host/accept_offer/' . $offer->id); ?>" style="display: inline;">
                                                <button type="submit" 
                                                        class="btn-offer btn-accept"
                                                        onclick="return confirm('Are you sure you want to accept this offer? This will reject all other pending offers for this job.');">
                                                    <i class="fas fa-check"></i>
                                                    Accept
                                                </button>
                                            </form>
                                            <form method="POST" action="<?php echo base_url('host/reject_offer/' . $offer->id); ?>" style="display: inline;">
                                                <button type="submit" 
                                                        class="btn-offer btn-reject"
                                                        onclick="return confirm('Are you sure you want to reject this offer?');">
                                                    <i class="fas fa-times"></i>
                                                    Reject
                                                </button>
                                            </form>
                                        <?php elseif ($offer->status === 'accepted'): ?>
                                            <span class="btn-offer btn-status">
                                                <i class="fas fa-check-circle"></i>
                                                Accepted
                                            </span>
                                        <?php elseif ($offer->status === 'declined'): ?>
                                            <span class="btn-offer btn-status">
                                                <i class="fas fa-times-circle"></i>
                                                Declined
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <div class="empty-state">
            <i class="fas fa-handshake"></i>
            <h3>No Offers Yet</h3>
            <p>
                You haven't received any offers for your jobs yet. 
                <a href="<?php echo base_url('host/jobs'); ?>">View your jobs</a> 
                to make sure they're properly posted and visible to cleaners.
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Toggle filters visibility
    $('#toggleFilters').click(function() {
        $('#filterContent').slideToggle(300);
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
        if (!$btn.hasClass('btn-status')) {
            $btn.html('<i class="fas fa-spinner fa-spin"></i>Processing...');
        }
    });
    
    // Smooth scroll for better UX
    $('html').css('scroll-behavior', 'smooth');
});
</script>