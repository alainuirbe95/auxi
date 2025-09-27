<div class="container-fluid">
            
            <!-- Job Details -->
            <div class="row">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clipboard-list text-primary me-2"></i>
                                Job Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4><?php echo htmlspecialchars($job->title); ?></h4>
                                    <p class="text-muted"><?php echo htmlspecialchars($job->description); ?></p>
                                    
                                    <div class="job-info">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <strong>Address:</strong><br>
                                                <?php echo htmlspecialchars($job->address); ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong>Date & Time:</strong><br>
                                                <?php 
                                                // Use scheduled_date and scheduled_time from database
                                                $job_date = isset($job->scheduled_date) ? $job->scheduled_date : '';
                                                $job_time = isset($job->scheduled_time) ? $job->scheduled_time : '';
                                                
                                                if ($job_date && $job_time) {
                                                    $datetime = $job_date . ' ' . $job_time;
                                                    echo date('M j, Y g:i A', strtotime($datetime));
                                                } else {
                                                    echo 'Not scheduled';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-sm-6">
                                                <strong>Rooms:</strong> 
                                                <?php 
                                                // Decode JSON rooms array
                                                $rooms = json_decode($job->rooms, true);
                                                if (is_array($rooms) && !empty($rooms)) {
                                                    echo implode(', ', $rooms);
                                                } else {
                                                    echo 'Not specified';
                                                }
                                                ?><br>
                                                <strong>Price:</strong> $<?php echo number_format($job->suggested_price, 2); ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong>Status:</strong> 
                                                <span class="badge bg-<?php echo $job->status == 'active' ? 'success' : ($job->status == 'completed' ? 'primary' : 'warning'); ?>">
                                                    <?php echo ucfirst($job->status); ?>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- OTP Information for Assigned Jobs -->
                                        <?php if ($job->status === 'assigned' && !empty($job->otp_code)): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="alert alert-info" style="border-left: 4px solid #17a2b8;">
                                                    <h6 class="alert-heading">
                                                        <i class="fas fa-key me-2"></i>
                                                        Service Code (OTP) for Cleaner
                                                    </h6>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <p class="mb-2">Share this code with your assigned cleaner to start the service:</p>
                                                            <div class="otp-display">
                                                                <span class="otp-code" style="
                                                                    display: inline-block;
                                                                    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
                                                                    color: white;
                                                                    font-size: 1.5rem;
                                                                    font-weight: 700;
                                                                    padding: 0.75rem 1.5rem;
                                                                    border-radius: 10px;
                                                                    letter-spacing: 0.2em;
                                                                    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
                                                                    font-family: monospace;
                                                                "><?php echo $job->otp_code; ?></span>
                                                            </div>
                                                            <small class="text-muted mt-2 d-block">
                                                                <i class="fas fa-info-circle me-1"></i>
                                                                The cleaner will need this code to start the job. Share it securely when they arrive.
                                                            </small>
                                                        </div>
                                                        <div class="ms-3">
                                                            <button class="btn btn-outline-info btn-sm" onclick="copyToClipboard('<?php echo $job->otp_code; ?>')">
                                                                <i class="fas fa-copy me-1"></i>
                                                                Copy Code
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <!-- Additional Job Details -->
                                        <div class="row mt-3">
                                            <div class="col-sm-6">
                                                <strong>City:</strong> <?php echo htmlspecialchars($job->city ?? 'Not specified'); ?><br>
                                                <strong>State:</strong> <?php echo htmlspecialchars($job->state ?? 'Not specified'); ?><br>
                                                <strong>Duration:</strong> <?php echo isset($job->estimated_duration) ? $job->estimated_duration . ' minutes' : 'Not specified'; ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong>Additional Services:</strong><br>
                                                <?php 
                                                // Decode JSON extras array
                                                $extras = json_decode($job->extras ?? '[]', true);
                                                if (is_array($extras) && !empty($extras)) {
                                                    echo '<ul class="list-unstyled mb-0">';
                                                    foreach ($extras as $extra) {
                                                        echo '<li><i class="fas fa-check text-success me-2"></i>' . htmlspecialchars($extra) . '</li>';
                                                    }
                                                    echo '</ul>';
                                                } else {
                                                    echo '<span class="text-muted">None selected</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <?php if (!empty($job->special_instructions)): ?>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <strong>Special Instructions:</strong><br>
                                                <p class="text-muted"><?php echo htmlspecialchars($job->special_instructions); ?></p>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (isset($job->pets) && $job->pets): ?>
                                        <div class="row mt-2">
                                            <div class="col-12">
                                                <span class="badge bg-info"><i class="fas fa-paw me-1"></i> Pets Present</span>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="text-center">
                                        <h5>Offers Received</h5>
                                        <p class="display-4 text-primary"><?php echo count($offers); ?></p>
                                        <a href="<?php echo base_url('host/offers'); ?>" class="btn btn-modern btn-primary">
                                            View All Offers
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Adjustment Requests -->
            <?php if (!empty($price_adjustments)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-dollar-sign text-warning me-2"></i>
                                Price Adjustment Requests
                            </h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($price_adjustments as $adjustment): ?>
                                <div class="price-adjustment-item border rounded p-3 mb-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 class="mb-2">
                                                <i class="fas fa-calculator text-warning me-2"></i>
                                                Requested Amount: $<?php echo number_format($adjustment->requested_amount, 2); ?>
                                            </h6>
                                            <p class="mb-2">
                                                <strong>Reason:</strong> <?php echo htmlspecialchars($adjustment->price_reason); ?>
                                            </p>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                Requested: <?php echo date('M j, Y g:i A', strtotime($adjustment->created_at)); ?>
                                            </small>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <span class="badge badge-warning badge-lg">
                                                <?php echo ucfirst($adjustment->status); ?>
                                            </span>
                                            <?php if ($adjustment->status === 'pending'): ?>
                                                <div class="mt-2">
                                                    <a href="<?php echo base_url('host/counter-offers'); ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-handshake me-1"></i>
                                                        Respond
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Dispute Information -->
            <?php if (!empty($dispute_info)): ?>
            <div class="row mt-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                Dispute Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Dispute Details
                                    </h6>
                                    <div class="dispute-details">
                                        <p><strong>Disputed On:</strong> <?php echo date('M j, Y g:i A', strtotime($dispute_info['disputed_at'])); ?></p>
                                        <p><strong>Reason:</strong></p>
                                        <div class="dispute-reason-box p-3 bg-light border rounded">
                                            <small><?php echo nl2br(htmlspecialchars($dispute_info['dispute_reason'])); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if ($dispute_info['dispute_resolution']): ?>
                                        <h6 class="text-success mb-3">
                                            <i class="fas fa-check-circle me-2"></i>
                                            Resolution
                                        </h6>
                                        <div class="resolution-details">
                                            <p><strong>Resolved On:</strong> <?php echo date('M j, Y g:i A', strtotime($dispute_info['dispute_resolved_at'])); ?></p>
                                            <p><strong>Status:</strong> <span class="badge badge-success">Resolved</span></p>
                                            <?php if ($dispute_info['dispute_resolution_notes']): ?>
                                                <p><strong>Resolution Notes:</strong></p>
                                                <div class="resolution-notes-box p-3 bg-success bg-opacity-10 border border-success rounded">
                                                    <small><?php echo nl2br(htmlspecialchars($dispute_info['dispute_resolution_notes'])); ?></small>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Financial Impact -->
                                            <?php 
                                            $original_amount = $job->final_price ?: $job->accepted_price;
                                            $cleaner_amount = $dispute_info['payment_amount'] ?: 0;
                                            $host_refund = $original_amount - $cleaner_amount;
                                            ?>
                                            <div class="financial-impact mt-3">
                                                <h6 class="text-info">Financial Impact</h6>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="text-center p-2 bg-success bg-opacity-10 border border-success rounded">
                                                            <small class="text-success">Your Refund</small><br>
                                                            <strong class="text-success">$<?php echo number_format($host_refund, 2); ?></strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="text-center p-2 bg-info bg-opacity-10 border border-info rounded">
                                                            <small class="text-info">Cleaner Payment</small><br>
                                                            <strong class="text-info">$<?php echo number_format($cleaner_amount, 2); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <h6 class="text-warning mb-3">
                                            <i class="fas fa-clock me-2"></i>
                                            Under Review
                                        </h6>
                                        <div class="pending-resolution">
                                            <p><strong>Status:</strong> <span class="badge badge-warning">Under Review</span></p>
                                            <p class="text-muted">A moderator is reviewing your dispute and will provide a resolution soon.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

</div>

<style>
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

/* Price Adjustment Styling */
.price-adjustment-item {
    background: #fff8e1;
    border-left: 4px solid #ff9800 !important;
}

.price-adjustment-item h6 {
    color: #e65100;
}

/* Dispute Information Styling */
.dispute-reason-box {
    background: #ffebee;
    border-color: #f44336 !important;
    max-height: 150px;
    overflow-y: auto;
}

.resolution-notes-box {
    max-height: 150px;
    overflow-y: auto;
}

.financial-impact .col-6 {
    margin-bottom: 0.5rem;
}

.financial-impact .text-center {
    padding: 0.75rem !important;
}

/* Badge Styling */
.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

/* Card Styling */
.modern-card {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.modern-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px 10px 0 0;
    border: none;
}

.modern-card .card-header h5 {
    color: white;
    font-weight: 600;
}

/* Modern Card Styles */
.modern-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    border: none;
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    backdrop-filter: blur(10px);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.modern-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.modern-card .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.5rem;
}

.modern-card .card-body {
    padding: 2rem;
}

.job-info {
    background: rgba(102, 126, 234, 0.05);
    padding: 1.5rem;
    border-radius: 15px;
    margin-top: 1rem;
}

/* Button Styles */
.btn-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 50px;
    padding: 0.75rem 2rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
}
</style>

<script>
function copyToClipboard(text) {
    // Create a temporary input element
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    
    // Select and copy the text
    tempInput.select();
    tempInput.setSelectionRange(0, 99999); // For mobile devices
    
    try {
        document.execCommand('copy');
        
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
        button.classList.remove('btn-outline-info');
        button.classList.add('btn-success');
        
        // Reset button after 2 seconds
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('btn-success');
            button.classList.add('btn-outline-info');
        }, 2000);
        
    } catch (err) {
        console.error('Failed to copy text: ', err);
        alert('Failed to copy to clipboard. Please copy manually: ' + text);
    }
    
    // Remove the temporary input
    document.body.removeChild(tempInput);
}
</script>
