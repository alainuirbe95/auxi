<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-chart-bar"></i> Profile Statistics</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <?php foreach ($breadcrumbs as $crumb): ?>
                            <?php if (isset($crumb['active']) && $crumb['active']): ?>
                                <li class="breadcrumb-item active"><?php echo $crumb['title']; ?></li>
                            <?php else: ?>
                                <li class="breadcrumb-item"><a href="<?php echo base_url($crumb['url']); ?>"><?php echo $crumb['title']; ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Overview Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $total_profiles; ?></h3>
                            <p>Total Profiles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="<?php echo base_url('admin/profiles'); ?>" class="small-box-footer">
                            View All <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $verification_stats['verified']; ?></h3>
                            <p>Verified Profiles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="<?php echo base_url('admin/profiles?verification_status=verified'); ?>" class="small-box-footer">
                            View Verified <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $verification_stats['pending']; ?></h3>
                            <p>Pending Verification</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="<?php echo base_url('admin/profiles?verification_status=pending'); ?>" class="small-box-footer">
                            Review Pending <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $verification_stats['rejected']; ?></h3>
                            <p>Rejected Profiles</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <a href="<?php echo base_url('admin/profiles?verification_status=rejected'); ?>" class="small-box-footer">
                            View Rejected <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Verification Status Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-shield-alt"></i> Verification Status Breakdown</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="verificationChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Visibility Chart -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-eye"></i> Profile Visibility</h3>
                        </div>
                        <div class="card-body">
                            <canvas id="visibilityChart" style="height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Rated Profiles -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-star"></i> Top Rated Profiles</h3>
                        </div>
                        <div class="card-body">
                            <?php if (empty($top_profiles)): ?>
                                <p class="text-muted">No rated profiles yet. Profiles need at least 3 reviews to appear here.</p>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Rank</th>
                                                <th>Profile</th>
                                                <th>Role</th>
                                                <th>Rating</th>
                                                <th>Total Reviews</th>
                                                <th>Jobs Completed</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $rank = 1; foreach ($top_profiles as $profile): ?>
                                                <tr>
                                                    <td>
                                                        <?php if ($rank == 1): ?>
                                                            <span class="badge badge-warning" style="font-size: 1.2rem;">
                                                                <i class="fas fa-trophy"></i> #<?php echo $rank; ?>
                                                            </span>
                                                        <?php elseif ($rank <= 3): ?>
                                                            <span class="badge badge-secondary" style="font-size: 1.1rem;">
                                                                <i class="fas fa-medal"></i> #<?php echo $rank; ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="badge badge-light">#<?php echo $rank; ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php if (!empty($profile->profile_picture_url)): ?>
                                                                <img src="<?php echo $profile->profile_picture_url; ?>" class="img-circle mr-2" style="width: 40px; height: 40px; object-fit: cover;" alt="Profile">
                                                            <?php else: ?>
                                                                <div class="img-circle bg-secondary text-white mr-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                            <div>
                                                                <strong><?php echo htmlspecialchars($profile->first_name . ' ' . $profile->last_name); ?></strong><br>
                                                                <small class="text-muted">@<?php echo htmlspecialchars($profile->username); ?></small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-primary">Cleaner</span>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-warning" style="font-size: 1rem;">
                                                            <i class="fas fa-star"></i> <?php echo number_format($profile->average_rating, 2); ?>
                                                        </span>
                                                    </td>
                                                    <td><?php echo $profile->total_reviews; ?></td>
                                                    <td><?php echo $profile->total_jobs_completed; ?></td>
                                                    <td>
                                                        <a href="<?php echo base_url('admin/profile/' . $profile->user_id); ?>" class="btn btn-sm btn-info">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php $rank++; endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-info-circle"></i> Summary Statistics</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Profiles</span>
                                            <span class="info-box-number"><?php echo $total_profiles; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Public Profiles</span>
                                            <span class="info-box-number"><?php echo $visibility_stats['public']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-warning"><i class="fas fa-eye-slash"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Private Profiles</span>
                                            <span class="info-box-number"><?php echo $visibility_stats['private']; ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-secondary"><i class="fas fa-question-circle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Unverified</span>
                                            <span class="info-box-number"><?php echo $verification_stats['unverified']; ?></span>
                                        </div>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
$(document).ready(function() {
    // Verification Status Chart
    var verificationCtx = document.getElementById('verificationChart').getContext('2d');
    var verificationChart = new Chart(verificationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Verified', 'Pending', 'Unverified', 'Rejected'],
            datasets: [{
                data: [
                    <?php echo $verification_stats['verified']; ?>,
                    <?php echo $verification_stats['pending']; ?>,
                    <?php echo $verification_stats['unverified']; ?>,
                    <?php echo $verification_stats['rejected']; ?>
                ],
                backgroundColor: [
                    '#28a745',
                    '#ffc107',
                    '#6c757d',
                    '#dc3545'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Visibility Chart
    var visibilityCtx = document.getElementById('visibilityChart').getContext('2d');
    var visibilityChart = new Chart(visibilityCtx, {
        type: 'pie',
        data: {
            labels: ['Public', 'Private'],
            datasets: [{
                data: [
                    <?php echo $visibility_stats['public']; ?>,
                    <?php echo $visibility_stats['private']; ?>
                ],
                backgroundColor: [
                    '#17a2b8',
                    '#6c757d'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<style>
.small-box {
    border-radius: 0.25rem;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    position: relative;
    display: block;
    margin-bottom: 20px;
}
.small-box .inner {
    padding: 10px;
}
.small-box h3 {
    font-size: 2.2rem;
    font-weight: bold;
    margin: 0 0 10px 0;
    padding: 0;
    white-space: nowrap;
}
.small-box p {
    font-size: 1rem;
}
.small-box .icon {
    color: rgba(0,0,0,.15);
    z-index: 0;
}
.small-box .icon > i {
    font-size: 70px;
    position: absolute;
    right: 15px;
    top: 15px;
}
.small-box .small-box-footer {
    position: relative;
    text-align: center;
    padding: 3px 0;
    color: rgba(255,255,255,.8);
    display: block;
    z-index: 10;
    background: rgba(0,0,0,.1);
    text-decoration: none;
}
.small-box .small-box-footer:hover {
    color: #fff;
    background: rgba(0,0,0,.15);
}
.info-box {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    border-radius: 0.25rem;
    background-color: #fff;
    display: flex;
    margin-bottom: 1rem;
    min-height: 80px;
    padding: 0.5rem;
    position: relative;
}
.info-box .info-box-icon {
    border-radius: 0.25rem;
    align-items: center;
    display: flex;
    font-size: 1.875rem;
    justify-content: center;
    text-align: center;
    width: 70px;
}
.info-box .info-box-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    line-height: 1.8;
    flex: 1;
    padding: 0 10px;
}
.info-box .info-box-text,
.info-box .info-box-number {
    display: block;
}
.info-box .info-box-text {
    text-transform: uppercase;
}
.info-box .info-box-number {
    font-weight: 700;
    font-size: 1.5rem;
}
.img-circle {
    border-radius: 50%;
}
</style>


