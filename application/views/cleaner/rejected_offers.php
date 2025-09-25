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
/* Rejected Offers Styles */
.rejected-offers-container {
    padding: 2rem 0;
}

.rejected-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-radius: 20px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(220, 53, 69, 0.2);
    color: white;
}

.rejected-header h2 {
    margin: 0 0 1rem 0;
    font-weight: 700;
}

.rejected-header p {
    margin: 0;
    opacity: 0.9;
}

.offers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.offer-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    border-left: 4px solid #dc3545;
}

.offer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.offer-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    padding: 1.5rem;
    color: white;
    position: relative;
}

.offer-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    line-height: 1.3;
}

.offer-amount {
    font-size: 1.5rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.offer-date {
    font-size: 0.9rem;
    opacity: 0.9;
}

.rejected-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50px;
    padding: 0.5rem 1rem;
    font-size: 0.8rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.offer-body {
    padding: 1.5rem;
}

.offer-description {
    color: #666;
    line-height: 1.6;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.offer-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.offer-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    color: #666;
}

.offer-detail i {
    color: #dc3545;
    width: 16px;
}

.offer-host {
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
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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

.offer-message {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1rem;
    margin-bottom: 1rem;
    font-style: italic;
    color: #666;
    border-left: 3px solid #dc3545;
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

.btn-view {
    background: #6c757d;
    color: white;
}

.btn-view:hover {
    background: #5a6268;
    color: white;
    text-decoration: none;
}

.btn-apply-again {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-apply-again:hover {
    background: linear-gradient(135deg, #218838 0%, #1e7e34 100%);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
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
    .offers-grid {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .offer-details {
        grid-template-columns: 1fr;
    }
    
    .offer-actions {
        flex-direction: column;
    }
}
</style>

<div class="rejected-offers-container">
    <!-- Header Section -->
    <div class="rejected-header">
        <h2>
            <i class="fas fa-times-circle me-2"></i>
            Declined Offers
        </h2>
        <p>Jobs where your offers were not accepted by the host</p>
    </div>

    <!-- Rejected Offers Grid -->
    <?php if (!empty($rejected_offers)): ?>
        <div class="offers-grid">
            <?php foreach ($rejected_offers as $offer): ?>
                <div class="offer-card">
                    <div class="offer-header">
                        <h3 class="offer-title"><?php echo htmlspecialchars($offer->job_title); ?></h3>
                        <div class="offer-amount">$<?php echo number_format($offer->amount, 2); ?></div>
                        <div class="offer-date">
                            <i class="fas fa-clock me-1"></i>
                            Declined <?php echo time_ago($offer->updated_at); ?>
                        </div>
                        <div class="rejected-badge">
                            <i class="fas fa-times-circle"></i>
                            Declined
                        </div>
                    </div>
                    
                    <div class="offer-body">
                        <p class="offer-description">
                            <?php echo htmlspecialchars($offer->job_description); ?>
                        </p>
                        
                        <div class="offer-details">
                            <div class="offer-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?php echo htmlspecialchars($offer->city . ', ' . $offer->state); ?></span>
                            </div>
                            <div class="offer-detail">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo date('M j, Y', strtotime($offer->created_at)); ?></span>
                            </div>
                            <div class="offer-detail">
                                <i class="fas fa-clock"></i>
                                <span><?php echo date('g:i A', strtotime($offer->created_at)); ?></span>
                            </div>
                            <div class="offer-detail">
                                <i class="fas fa-tag"></i>
                                <span><?php echo ucfirst($offer->offer_type); ?> Offer</span>
                            </div>
                        </div>
                        
                        <div class="offer-host">
                            <div class="host-avatar">
                                <?php echo strtoupper(substr($offer->host_first_name, 0, 1) . substr($offer->host_last_name, 0, 1)); ?>
                            </div>
                            <div class="host-info">
                                <h6><?php echo htmlspecialchars($offer->host_first_name . ' ' . $offer->host_last_name); ?></h6>
                                <small>
                                    <i class="fas fa-user me-1"></i>
                                    @<?php echo htmlspecialchars($offer->host_username); ?>
                                </small>
                            </div>
                        </div>
                        
                        <?php if (!empty($offer->message)): ?>
                            <div class="offer-message">
                                <i class="fas fa-quote-left me-1"></i>
                                "<?php echo htmlspecialchars($offer->message); ?>"
                            </div>
                        <?php endif; ?>
                        
                        <div class="offer-actions">
                            <a href="<?php echo base_url('cleaner/job/' . $offer->job_id); ?>" class="btn-offer btn-view">
                                <i class="fas fa-eye me-1"></i>
                                View Job
                            </a>
                            <a href="<?php echo base_url('cleaner/jobs'); ?>" class="btn-offer btn-apply-again">
                                <i class="fas fa-search me-1"></i>
                                Find Similar Jobs
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <div class="no-offers">
            <i class="fas fa-check-circle"></i>
            <h3>No Declined Offers</h3>
            <p>
                Great! You don't have any declined offers yet. 
                <a href="<?php echo base_url('cleaner/jobs'); ?>" style="color: #dc3545; text-decoration: none;">Browse available jobs</a> 
                to find new opportunities.
            </p>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    // Add loading state to action buttons
    $('.btn-offer').on('click', function() {
        const $btn = $(this);
        $btn.html('<i class="fas fa-spinner fa-spin me-1"></i>Loading...');
    });
});
</script>
