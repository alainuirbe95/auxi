<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body text-center py-4">
                            <h2 class="mb-3">
                                <i class="fas fa-broom text-success me-2"></i>
                                Welcome, <?php echo htmlspecialchars($user_info->username); ?>!
                            </h2>
                            <p class="text-muted mb-0">Cleaner dashboard coming soon...</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coming Soon -->
            <div class="row">
                <div class="col-12">
                    <div class="modern-card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-tools fa-4x text-muted mb-4"></i>
                            <h3>Cleaner Features Coming Soon!</h3>
                            <p class="text-muted">We're working hard to bring you the best cleaning job marketplace experience.</p>
                            <div class="mt-4">
                                <span class="badge bg-primary me-2">Job Browsing</span>
                                <span class="badge bg-success me-2">Bidding System</span>
                                <span class="badge bg-info me-2">Job Management</span>
                                <span class="badge bg-warning me-2">Earnings Tracking</span>
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

.modern-card .card-body {
    padding: 2rem;
}

.badge {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
}
</style>
