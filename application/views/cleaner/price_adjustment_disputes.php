<style>
/* Container styling for wider desktop view */
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

/* Disputed Price Adjustments Styling */
.disputes-container {
    padding: 2rem 1rem;
}

.disputes-header {
    text-align: center;
    margin-bottom: 3rem;
}

.disputes-header h1 {
    color: #2c3e50;
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.disputes-header p {
    color: #6c757d;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

.dispute-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    margin-bottom: 2rem;
    overflow: hidden;
    border-left: 5px solid #e91e63;
    transition: all 0.3s ease;
}

.dispute-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
}

.dispute-card-header {
    background: linear-gradient(135deg, #e91e63 0%, #f06292 100%);
    color: white;
    padding: 1.5rem;
    position: relative;
}

.dispute-card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
    opacity: 0.3;
}

.dispute-card-header-content {
    position: relative;
    z-index: 1;
}

.dispute-status {
    display: inline-flex;
    align-items: center;
    background: rgba(255,255,255,0.2);
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
    margin-bottom: 1rem;
    backdrop-filter: blur(10px);
}

.dispute-status i {
    margin-right: 0.5rem;
    font-size: 1rem;
}

.dispute-job-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.dispute-job-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    font-size: 0.9rem;
    opacity: 0.9;
}

.dispute-job-meta span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dispute-card-body {
    padding: 2rem;
}

.dispute-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.dispute-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #667eea;
}

.dispute-section h4 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dispute-section h4 i {
    color: #667eea;
}

.price-comparison {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    padding: 1rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.original-price {
    text-align: center;
}

.original-price-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.original-price-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #6c757d;
    text-decoration: line-through;
}

.price-arrow {
    font-size: 1.5rem;
    color: #667eea;
    margin: 0 1rem;
}

.proposed-price {
    text-align: center;
}

.proposed-price-label {
    font-size: 0.8rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.proposed-price-value {
    font-size: 1.5rem;
    font-weight: 600;
    color: #28a745;
}

.price-difference {
    text-align: center;
    margin-top: 0.5rem;
    font-size: 0.9rem;
    font-weight: 600;
    color: #28a745;
}

.host-dispute-reason {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.host-dispute-reason h5 {
    color: #856404;
    margin-bottom: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
}

.host-dispute-reason p {
    color: #856404;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

.dispute-details-text {
    background: #f8d7da;
    border: 1px solid #f5c6cb;
    border-radius: 8px;
    padding: 1rem;
    color: #721c24;
    font-size: 0.9rem;
    line-height: 1.5;
}

.moderator-note {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #90caf9;
    border-radius: 10px;
    padding: 1.5rem;
    margin-top: 1.5rem;
}

.moderator-note h5 {
    color: #1565c0;
    margin-bottom: 0.5rem;
    font-size: 1rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.moderator-note p {
    color: #1565c0;
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.5;
}

.cleaner-response-section {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #e9ecef;
}

.cleaner-response-section h4 {
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1.1rem;
    font-weight: 600;
}

.response-form {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    border-left: 4px solid #28a745;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 8px;
    padding: 0.75rem;
    width: 100%;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    outline: none;
}

.btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-state h3 {
    margin-bottom: 1rem;
    color: #495057;
}

/* Responsive Design */
@media (max-width: 768px) {
    .dispute-details-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .dispute-job-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .price-comparison {
        flex-direction: column;
        gap: 1rem;
    }
    
    .price-arrow {
        transform: rotate(90deg);
        margin: 0.5rem 0;
    }
}
</style>

<div class="container-fluid">
    <div class="disputes-container">
    <div class="disputes-header">
        <h1><i class="fas fa-exclamation-triangle"></i> Disputed Price Adjustments</h1>
        <p>These are price adjustment requests that have been disputed by hosts. A moderator will review both perspectives and make a fair resolution.</p>
    </div>

    <?php if (empty($disputed_offers)): ?>
        <div class="empty-state">
            <i class="fas fa-check-circle"></i>
            <h3>No Disputed Price Adjustments</h3>
            <p>You currently have no disputed price adjustments. All your price adjustment requests have been accepted or are still pending.</p>
        </div>
    <?php else: ?>
        <?php foreach ($disputed_offers as $offer): ?>
            <div class="dispute-card">
                <div class="dispute-card-header">
                    <div class="dispute-card-header-content">
                        <div class="dispute-status">
                            <i class="fas fa-exclamation-triangle"></i>
                            Disputed by Host
                        </div>
                        
                        <div class="dispute-job-title"><?= htmlspecialchars($offer->title) ?></div>
                        
                        <div class="dispute-job-meta">
                            <span><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($offer->address) ?></span>
                            <span><i class="fas fa-calendar"></i> <?= date('M j, Y', strtotime($offer->scheduled_date)) ?></span>
                            <span><i class="fas fa-user"></i> <?= htmlspecialchars($offer->host_first_name . ' ' . $offer->host_last_name) ?></span>
                            <span><i class="fas fa-clock"></i> Disputed <?= date('M j, Y g:i A', strtotime($offer->updated_at)) ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="dispute-card-body">
                    <div class="dispute-details-grid">
                        <!-- Price Comparison -->
                        <div class="dispute-section">
                            <h4><i class="fas fa-dollar-sign"></i> Price Comparison</h4>
                            
                            <div class="price-comparison">
                                <div class="original-price">
                                    <div class="original-price-label">Original Price</div>
                                    <div class="original-price-value">$<?= number_format($offer->original_price, 2) ?></div>
                                </div>
                                
                                <div class="price-arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                                
                                <div class="proposed-price">
                                    <div class="proposed-price-label">Your Request</div>
                                    <div class="proposed-price-value">$<?= number_format($offer->proposed_price, 2) ?></div>
                                </div>
                            </div>
                            
                            <?php 
                            $difference = $offer->proposed_price - $offer->original_price;
                            $difference_text = $difference > 0 ? "+$" . number_format($difference, 2) : "-$" . number_format(abs($difference), 2);
                            ?>
                            <div class="price-difference">
                                Difference: <?= $difference_text ?>
                            </div>
                            
                            <?php if ($offer->reason): ?>
                                <div style="margin-top: 1rem;">
                                    <strong>Your Reason:</strong>
                                    <p style="margin: 0.5rem 0 0 0; color: #495057; font-size: 0.9rem;">
                                        <?= htmlspecialchars($offer->reason) ?>
                                    </p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Host's Dispute -->
                        <div class="dispute-section">
                            <h4><i class="fas fa-user-shield"></i> Host's Dispute</h4>
                            
                            <?php 
                            $reason_mapping = [
                                'unexpected_work' => 'The cleaner is requesting payment for work that wasn\'t agreed upon',
                                'overpriced' => 'The additional work doesn\'t justify this price increase',
                                'misunderstanding' => 'There seems to be a misunderstanding about the scope of work',
                                'missing_details' => 'Important details about the job were not provided initially',
                                'other' => 'Other reason provided'
                            ];
                            $reason_text = $reason_mapping[$offer->host_dispute_reason] ?? $offer->host_dispute_reason;
                            ?>
                            
                            <div class="host-dispute-reason">
                                <h5><i class="fas fa-info-circle"></i> Reason for Dispute</h5>
                                <p><?= htmlspecialchars($reason_text) ?></p>
                            </div>
                            
                            <?php if ($offer->host_dispute_details): ?>
                                <div class="dispute-details-text">
                                    <strong>Additional Details:</strong><br>
                                    <?= htmlspecialchars($offer->host_dispute_details) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Moderator Note -->
                    <div class="moderator-note">
                        <h5><i class="fas fa-gavel"></i> What Happens Next?</h5>
                        <p>A moderator will review both your price adjustment request and the host's dispute. They will consider the scope of work, original agreement, and any additional factors to make a fair decision. You will be notified once a resolution is reached.</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>
</div>
