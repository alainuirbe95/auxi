<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
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

        </div>
    </section>
</div>

<style>
/* Content Layout */
.content {
    display: flex !important;
    flex-direction: column !important;
    align-items: flex-start !important;
}

.container-fluid {
    max-width: 95% !important;
    width: 100% !important;
    margin: 0 !important;
    padding: 0 20px !important;
}

/* Responsive Layout */
@media (max-width: 991.98px) {
    .container-fluid {
        max-width: 98% !important;
        padding: 0 15px !important;
    }
}

@media (max-width: 767.98px) {
    .container-fluid {
        max-width: 100% !important;
        padding: 0 10px !important;
    }
}

/* Modern Card Styles */
.modern-card {
    background: linear-gradient(135deg, rgba(255,255,255,0.9) 0%, rgba(255,255,255,0.8) 100%);
    border: none;
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
