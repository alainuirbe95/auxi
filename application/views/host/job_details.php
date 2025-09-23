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
                                                <?php echo date('M j, Y g:i A', strtotime($job->date_time)); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-sm-6">
                                                <strong>Rooms:</strong> <?php echo $job->rooms; ?><br>
                                                <strong>Price:</strong> $<?php echo number_format($job->suggested_price, 2); ?>
                                            </div>
                                            <div class="col-sm-6">
                                                <strong>Status:</strong> 
                                                <span class="badge bg-<?php echo $job->status == 'active' ? 'success' : ($job->status == 'completed' ? 'primary' : 'warning'); ?>">
                                                    <?php echo ucfirst($job->status); ?>
                                                </span>
                                            </div>
                                        </div>
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
