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
/* Modern Job Browse Styles */
.jobs-browse-container {
    padding: 2rem 0;
}

.filter-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
}

.filter-title {
    color: white;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-align: center;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1rem;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-label {
    color: rgba(255, 255, 255, 0.9);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.filter-input {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    color: white;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.filter-input:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.4);
    outline: none;
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.filter-input::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.filter-actions {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-top: 1rem;
}

.btn-filter {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-filter:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    text-decoration: none;
    transform: translateY(-2px);
}

.btn-filter.primary {
    background: rgba(255, 255, 255, 0.9);
    color: #667eea;
    border-color: transparent;
}

.btn-filter.primary:hover {
    background: white;
    color: #667eea;
}

.jobs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
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
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
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

.job-body {
    padding: 1.5rem;
}

.job-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.job-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.job-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.job-detail i {
    color: #43e97b;
    width: 16px;
}

.job-host {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.host-avatar {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
}

.host-info h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
}

.host-info small {
    color: #666;
}

.job-actions {
    display: flex;
    gap: 0.75rem;
}

.btn-job {
    flex: 1;
    padding: 0.75rem 1rem;
    border-radius: 10px;
    font-weight: 600;
    text-align: center;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-view {
    background: #f8f9fa;
    color: #666;
    border: 1px solid #e9ecef;
}

.btn-view:hover {
    background: #e9ecef;
    color: #333;
    text-decoration: none;
}

.btn-apply {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
    border: none;
}

.btn-apply:hover {
    background: linear-gradient(135deg, #38d9a9 0%, #20c997 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
}

.btn-applied {
    background: #6c757d;
    color: white;
    border: none;
    cursor: not-allowed;
}

.pagination-section {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 3rem;
}

.pagination-info {
    color: #666;
    font-size: 0.9rem;
}

.pagination {
    display: flex;
    gap: 0.5rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.page-item {
    margin: 0;
}

.page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: white;
    color: #666;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.page-link:hover {
    background: #43e97b;
    color: white;
    text-decoration: none;
    border-color: #43e97b;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
    border-color: transparent;
}

.no-jobs {
    text-align: center;
    padding: 4rem 2rem;
    color: #666;
}

.no-jobs i {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 1rem;
}

.no-jobs h3 {
    color: #999;
    margin-bottom: 1rem;
}

.no-jobs p {
    font-size: 1.1rem;
    line-height: 1.6;
}

/* Responsive Design */
@media (max-width: 768px) {
    .jobs-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        flex-direction: column;
    }
    
    .job-details {
        grid-template-columns: 1fr;
    }
    
    .job-actions {
        flex-direction: column;
    }
}
</style>

<div class="jobs-browse-container">
    <!-- Filter Section -->
    <div class="filter-section">
        <h2 class="filter-title">
            <i class="fas fa-filter me-2"></i>
            Find Your Perfect Cleaning Job
        </h2>
        
        <form method="GET" action="<?php echo base_url('cleaner/jobs'); ?>" id="filterForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-search me-1"></i>
                        Search Jobs
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        class="filter-input" 
                        placeholder="Job title, description, location..."
                        value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>"
                    >
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-dollar-sign me-1"></i>
                        Min Price
                    </label>
                    <input 
                        type="number" 
                        name="min_price" 
                        class="filter-input" 
                        placeholder="Minimum amount"
                        value="<?php echo isset($filters['min_price']) ? $filters['min_price'] : ''; ?>"
                    >
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-dollar-sign me-1"></i>
                        Max Price
                    </label>
                    <input 
                        type="number" 
                        name="max_price" 
                        class="filter-input" 
                        placeholder="Maximum amount"
                        value="<?php echo isset($filters['max_price']) ? $filters['max_price'] : ''; ?>"
                    >
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-calendar me-1"></i>
                        From Date
                    </label>
                    <input 
                        type="date" 
                        name="date_from" 
                        class="filter-input" 
                        value="<?php echo isset($filters['date_from']) ? $filters['date_from'] : ''; ?>"
                    >
                </div>
                
                <div class="filter-group">
                    <label class="filter-label">
                        <i class="fas fa-calendar me-1"></i>
                        To Date
                    </label>
                    <input 
                        type="date" 
                        name="date_to" 
                        class="filter-input" 
                        value="<?php echo isset($filters['date_to']) ? $filters['date_to'] : ''; ?>"
                    >
                </div>
            </div>
            
            <div class="filter-actions">
                <button type="submit" class="btn-filter primary">
                    <i class="fas fa-search"></i>
                    Search Jobs
                </button>
                <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn-filter">
                    <i class="fas fa-refresh"></i>
                    Clear Filters
                </a>
            </div>
        </form>
    </div>

    <!-- Jobs Grid -->
    <?php if (!empty($jobs)): ?>
        <div class="jobs-grid">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-header">
                        <h3 class="job-title"><?php echo htmlspecialchars($job->title); ?></h3>
                        <div class="job-price">$<?php echo number_format($job->suggested_price, 2); ?></div>
                        <div class="job-date">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo isset($job->date_time) ? date('M j, Y g:i A', strtotime($job->date_time)) : 'Flexible'; ?>
                        </div>
                    </div>
                    
                    <div class="job-body">
                        <p class="job-description">
                            <?php echo htmlspecialchars($job->description); ?>
                        </p>
                        
                        <div class="job-details">
                            <div class="job-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($job->address); ?></span>
                            </div>
                            <div class="job-detail">
                                <i class="fas fa-home"></i>
                                <span><?php echo $job->rooms; ?> rooms</span>
                            </div>
                            <?php if (!empty($job->extras)): ?>
                                <div class="job-detail">
                                    <i class="fas fa-plus"></i>
                                    <span><?php echo htmlspecialchars($job->extras); ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if ($job->pets == '1'): ?>
                                <div class="job-detail">
                                    <i class="fas fa-paw"></i>
                                    <span>Pets present</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="job-host">
                            <div class="host-avatar">
                                <?php echo strtoupper(substr($job->host_first_name, 0, 1) . substr($job->host_last_name, 0, 1)); ?>
                            </div>
                            <div class="host-info">
                                <h6><?php echo htmlspecialchars($job->host_first_name . ' ' . $job->host_last_name); ?></h6>
                                <small>
                                    <i class="fas fa-clock me-1"></i>
                                    Posted <?php echo time_ago($job->created_at); ?>
                                </small>
                            </div>
                        </div>
                        
                        <div class="job-actions">
                            <a href="<?php echo base_url('cleaner/job/' . $job->id); ?>" class="btn-job btn-view">
                                <i class="fas fa-eye me-1"></i>
                                View Details
                            </a>
                            <?php if (!$job->has_applied): ?>
                                <a href="<?php echo base_url('cleaner/job/' . $job->id); ?>" class="btn-job btn-apply">
                                    <i class="fas fa-handshake me-1"></i>
                                    Make Offer
                                </a>
                            <?php else: ?>
                                <button class="btn-job btn-applied" disabled>
                                    <i class="fas fa-check me-1"></i>
                                    Applied
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination-section">
                <div class="pagination-info">
                    Showing <?php echo count($jobs); ?> of <?php echo $total_jobs; ?> jobs
                </div>
                
                <nav>
                    <ul class="pagination">
                        <?php if ($page > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo base_url('cleaner/jobs?' . http_build_query(array_merge($filters, ['page' => $page - 1]))); ?>">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo base_url('cleaner/jobs?' . http_build_query(array_merge($filters, ['page' => $i]))); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($page < $total_pages): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo base_url('cleaner/jobs?' . http_build_query(array_merge($filters, ['page' => $page + 1]))); ?>">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="no-jobs">
            <i class="fas fa-search"></i>
            <h3>No Jobs Found</h3>
            <p>
                <?php if (!empty(array_filter($filters))): ?>
                    No jobs match your current filters. Try adjusting your search criteria or 
                    <a href="<?php echo base_url('cleaner/jobs'); ?>" style="color: #43e97b; text-decoration: none;">clear all filters</a>.
                <?php else: ?>
                    There are currently no active cleaning jobs available. Check back later for new opportunities!
                <?php endif; ?>
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Auto-submit form on filter change (with debounce)
    let searchTimeout;
    $('.filter-input').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            $('#filterForm').submit();
        }, 500);
    });
    
    // Add loading state to buttons
    $('.btn-job').on('click', function() {
        const $btn = $(this);
        if (!$btn.hasClass('btn-applied')) {
            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');
        }
    });
});
</script>
