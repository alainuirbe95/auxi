<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid">
  <!-- Modern Header Section -->
  <div class="modern-header-section">
    <div class="header-content">
      <h1 class="page-title">
        <i class="fas fa-chart-bar mr-3"></i>
        Profile Statistics
      </h1>
      <p class="page-subtitle">
        Comprehensive overview of user profiles and review statistics
      </p>
    </div>
  </div>

  <!-- Profile Statistics Cards -->
  <div class="row mb-4">
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-primary">
        <div class="stats-icon">
          <i class="fas fa-users"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($total_profiles); ?></h3>
          <p class="stats-label">Total Profiles</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-success">
        <div class="stats-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($verification_stats['verified']); ?></h3>
          <p class="stats-label">Verified Profiles</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-clock"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($verification_stats['pending']); ?></h3>
          <p class="stats-label">Pending Verification</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-orange">
        <div class="stats-icon">
          <i class="fas fa-times-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($verification_stats['rejected']); ?></h3>
          <p class="stats-label">Rejected Profiles</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Review Statistics Cards -->
  <div class="row mb-4">
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-info">
        <div class="stats-icon">
          <i class="fas fa-star"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo isset($review_stats['total_reviews']) ? number_format($review_stats['total_reviews']) : '0'; ?></h3>
          <p class="stats-label">Total Reviews</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-teal">
        <div class="stats-icon">
          <i class="fas fa-chart-line"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo isset($review_stats['average_rating']) ? $review_stats['average_rating'] : '0.0'; ?></h3>
          <p class="stats-label">Average Rating</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-cyan">
        <div class="stats-icon">
          <i class="fas fa-calendar-check"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo isset($review_stats['reviews_last_30_days']) ? number_format($review_stats['reviews_last_30_days']) : '0'; ?></h3>
          <p class="stats-label">Recent Reviews (30d)</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>

    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="stats-card stats-card-gray">
        <div class="stats-icon">
          <i class="fas fa-eye-slash"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo isset($review_stats['hidden_reviews']) ? number_format($review_stats['hidden_reviews']) : '0'; ?></h3>
          <p class="stats-label">Hidden Reviews</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Charts Row -->
  <div class="row mb-4">
    <!-- Verification Status Chart -->
    <div class="col-lg-6 mb-4">
      <div class="modern-card">
        <div class="modern-card-header">
          <h3 class="modern-card-title">
            <i class="fas fa-shield-alt mr-2"></i>
            Verification Status Breakdown
          </h3>
        </div>
        <div class="modern-card-body">
          <canvas id="verificationChart" style="height: 280px;"></canvas>
        </div>
      </div>
    </div>

    <!-- Visibility Chart -->
    <div class="col-lg-6 mb-4">
      <div class="modern-card">
        <div class="modern-card-header">
          <h3 class="modern-card-title">
            <i class="fas fa-eye mr-2"></i>
            Profile Visibility
          </h3>
        </div>
        <div class="modern-card-body">
          <canvas id="visibilityChart" style="height: 280px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Review Charts Row -->
  <div class="row mb-4">
    <!-- Rating Distribution Chart -->
    <div class="col-lg-6 mb-4">
      <div class="modern-card">
        <div class="modern-card-header">
          <h3 class="modern-card-title">
            <i class="fas fa-star-half-alt mr-2"></i>
            Rating Distribution
          </h3>
        </div>
        <div class="modern-card-body">
          <canvas id="ratingChart" style="height: 280px;"></canvas>
        </div>
      </div>
    </div>

    <!-- Review Type Chart -->
    <div class="col-lg-6 mb-4">
      <div class="modern-card">
        <div class="modern-card-header">
          <h3 class="modern-card-title">
            <i class="fas fa-exchange-alt mr-2"></i>
            Reviews by Type
          </h3>
        </div>
        <div class="modern-card-body">
          <canvas id="reviewTypeChart" style="height: 280px;"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Top Rated Profiles -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="modern-card">
        <div class="modern-card-header">
          <h3 class="modern-card-title">
            <i class="fas fa-trophy mr-2"></i>
            Top Rated Profiles
          </h3>
        </div>
        <div class="modern-card-body">
          <?php if (empty($top_profiles)): ?>
            <div class="empty-state">
              <i class="fas fa-star fa-3x mb-3 text-muted"></i>
              <p class="text-muted">No rated profiles yet. Profiles need at least 3 reviews to appear here.</p>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="modern-table">
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
                          <span class="badge badge-gold">
                            <i class="fas fa-trophy"></i> #<?php echo $rank; ?>
                          </span>
                        <?php elseif ($rank <= 3): ?>
                          <span class="badge badge-silver">
                            <i class="fas fa-medal"></i> #<?php echo $rank; ?>
                          </span>
                        <?php else: ?>
                          <span class="badge badge-light">#<?php echo $rank; ?></span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <div class="d-flex align-items-center">
                          <?php if (!empty($profile->profile_picture_url)): ?>
                            <img src="<?php echo $profile->profile_picture_url; ?>" class="profile-avatar mr-2" alt="Profile">
                          <?php else: ?>
                            <div class="profile-avatar-placeholder mr-2">
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
                        <span class="badge badge-warning">
                          <i class="fas fa-star"></i> <?php echo number_format($profile->average_rating, 2); ?>
                        </span>
                      </td>
                      <td><?php echo $profile->total_reviews; ?></td>
                      <td><?php echo $profile->total_jobs_completed; ?></td>
                      <td>
                        <a href="<?php echo base_url('admin/profile/' . $profile->user_id); ?>" class="btn btn-sm btn-modern btn-primary">
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

    // Rating Distribution Chart
    var ratingCtx = document.getElementById('ratingChart').getContext('2d');
    var ratingChart = new Chart(ratingCtx, {
        type: 'bar',
        data: {
            labels: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
            datasets: [{
                label: 'Number of Reviews',
                data: [
                    <?php echo isset($review_stats['by_rating'][5]) ? $review_stats['by_rating'][5] : 0; ?>,
                    <?php echo isset($review_stats['by_rating'][4]) ? $review_stats['by_rating'][4] : 0; ?>,
                    <?php echo isset($review_stats['by_rating'][3]) ? $review_stats['by_rating'][3] : 0; ?>,
                    <?php echo isset($review_stats['by_rating'][2]) ? $review_stats['by_rating'][2] : 0; ?>,
                    <?php echo isset($review_stats['by_rating'][1]) ? $review_stats['by_rating'][1] : 0; ?>
                ],
                backgroundColor: [
                    '#28a745',
                    '#5cb85c',
                    '#ffc107',
                    '#fd7e14',
                    '#dc3545'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Review Type Chart
    var reviewTypeCtx = document.getElementById('reviewTypeChart').getContext('2d');
    var reviewTypeChart = new Chart(reviewTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['Host to Cleaner', 'Cleaner to Host'],
            datasets: [{
                data: [
                    <?php echo isset($review_stats['host_to_cleaner']) ? $review_stats['host_to_cleaner'] : 0; ?>,
                    <?php echo isset($review_stats['cleaner_to_host']) ? $review_stats['cleaner_to_host'] : 0; ?>
                ],
                backgroundColor: [
                    '#007bff',
                    '#ffc107'
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
/* Modern Header Section */
.modern-header-section {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 20px;
  padding: 2.5rem 2rem;
  margin-bottom: 2rem;
  color: white;
  position: relative;
  overflow: hidden;
}

.modern-header-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
  opacity: 0.3;
}

.header-content {
  position: relative;
  z-index: 1;
}

.page-title {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.page-subtitle {
  font-size: 1rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.95;
}

/* Modern Statistics Cards */
.stats-card {
  background: white;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  position: relative;
  overflow: hidden;
  transition: all 0.3s ease;
  border: none;
  height: 100%;
}

.stats-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}

.stats-card-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.stats-card-success {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
}

.stats-card-warning {
  background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
  color: white;
}

.stats-card-orange {
  background: linear-gradient(135deg, #ff9a56 0%, #ff6a00 100%);
  color: white;
}

.stats-card-info {
  background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
  color: white;
}

.stats-card-teal {
  background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
  color: white;
}

.stats-card-cyan {
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  color: white;
}

.stats-card-gray {
  background: linear-gradient(135deg, #607d8b 0%, #455a64 100%);
  color: white;
}

.stats-icon {
  position: absolute;
  top: 1rem;
  right: 1rem;
  font-size: 2rem;
  opacity: 0.3;
}

.stats-content {
  position: relative;
  z-index: 1;
}

.stats-number {
  font-size: 2rem;
  font-weight: 700;
  margin: 0;
  line-height: 1;
}

.stats-label {
  font-size: 0.9rem;
  margin: 0.5rem 0 0 0;
  opacity: 0.95;
  font-weight: 500;
}

.stats-decoration {
  position: absolute;
  bottom: -15px;
  right: -15px;
  width: 80px;
  height: 80px;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 50%;
}

/* Modern Card */
.modern-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
  overflow: hidden;
  height: 100%;
}

.modern-card-header {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  padding: 1.25rem 1.5rem;
  border-bottom: 2px solid #e9ecef;
}

.modern-card-title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0;
  color: #495057;
}

.modern-card-body {
  padding: 1.5rem;
}

/* Modern Table */
.modern-table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}

.modern-table thead {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.modern-table thead th {
  padding: 1rem;
  font-weight: 600;
  color: #495057;
  border-bottom: 2px solid #dee2e6;
  text-align: left;
}

.modern-table tbody td {
  padding: 1rem;
  border-bottom: 1px solid #e9ecef;
}

.modern-table tbody tr:hover {
  background-color: #f8f9fa;
}

/* Profile Avatar */
.profile-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  object-fit: cover;
}

.profile-avatar-placeholder {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: #e9ecef;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6c757d;
}

/* Badges */
.badge-gold {
  background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
  color: #333;
  padding: 0.4rem 0.8rem;
  border-radius: 8px;
  font-weight: 600;
}

.badge-silver {
  background: linear-gradient(135deg, #c0c0c0 0%, #d3d3d3 100%);
  color: #333;
  padding: 0.4rem 0.8rem;
  border-radius: 8px;
  font-weight: 600;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 3rem 1rem;
}

/* Responsive */
@media (max-width: 768px) {
  .modern-header-section {
    padding: 2rem 1.5rem;
  }
  
  .page-title {
    font-size: 1.75rem;
  }
  
  .stats-card {
    padding: 1.25rem;
  }
  
  .stats-number {
    font-size: 1.75rem;
  }
  
  .modern-table tbody td {
    padding: 0.8rem 0.5rem;
  }
}
</style>
