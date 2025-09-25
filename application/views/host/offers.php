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

.offers-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    color: white;
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
}
</style>

<div class="offers-container">
    <!-- Header Section -->
    <div class="offers-header">
        <h2>
            <i class="fas fa-handshake me-2"></i>
            Job Offers Management
        </h2>
        <p class="mb-0">Review and manage all offers for your cleaning jobs</p>
        
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
    // Add loading state to action buttons
    $('.btn-offer').on('click', function() {
        const $btn = $(this);
        if (!$btn.hasClass('btn-view')) {
            $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');
        }
    });
});
</script>
