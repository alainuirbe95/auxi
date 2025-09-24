<?php
// time_ago() helper function
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

<div class="container-fluid">
  <!-- Modern Header Section -->
  <div class="modern-header-section">
    <div class="header-content">
      <h1 class="page-title">
        <i class="fas fa-flag mr-3"></i>
        Flag Management
      </h1>
      <p class="page-subtitle">
        Review and manage job flags reported by users
      </p>
    </div>
  </div>

  <!-- Modern Statistics Cards -->
  <div class="row mb-5">
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-primary">
        <div class="stats-icon">
          <i class="fas fa-flag"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['total_flags'] ?? 0); ?></h3>
          <p class="stats-label">Total Flags</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-warning">
        <div class="stats-icon">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['active_flags'] ?? 0); ?></h3>
          <p class="stats-label">Active Flags</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-success">
        <div class="stats-icon">
          <i class="fas fa-check-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['resolved_flags'] ?? 0); ?></h3>
          <p class="stats-label">Resolved</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="stats-card stats-card-secondary">
        <div class="stats-icon">
          <i class="fas fa-times-circle"></i>
        </div>
        <div class="stats-content">
          <h3 class="stats-number"><?php echo number_format($stats['dismissed_flags'] ?? 0); ?></h3>
          <p class="stats-label">Dismissed</p>
        </div>
        <div class="stats-decoration"></div>
      </div>
    </div>
  </div>

  <!-- Main Flags Table Card -->
  <div class="modern-card">
    <div class="modern-card-header">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <h3 class="card-title">
            <i class="fas fa-table mr-2"></i>
            Active Flags
          </h3>
        </div>
      </div>
    </div>

    <!-- Flags Table -->
    <div class="modern-table-container">
      <?php if (empty($flags)): ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-flag"></i>
          </div>
          <h4>No Active Flags</h4>
          <p>There are currently no active flags to review.</p>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="modern-table">
            <thead>
              <tr>
                <th>Flagged Job</th>
                <th>Flagged By</th>
                <th>Reason</th>
                <th>Flagged At</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($flags as $flag): ?>
                <tr class="flag-row">
                  <td>
                    <div class="job-info">
                      <h5 class="job-title"><?php echo htmlspecialchars($flag->job_title); ?></h5>
                      <p class="job-description"><?php echo htmlspecialchars(substr($flag->job_description, 0, 100)) . (strlen($flag->job_description) > 100 ? '...' : ''); ?></p>
                      <div class="job-meta">
                        <span class="job-host">
                          <i class="fas fa-user mr-1"></i>
                          Host: <?php echo htmlspecialchars($flag->host_first_name . ' ' . $flag->host_last_name); ?>
                        </span>
                        <span class="job-status">
                          <i class="fas fa-circle mr-1"></i>
                          <?php echo ucfirst($flag->job_status); ?>
                        </span>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="user-info">
                      <h6 class="user-name"><?php echo htmlspecialchars($flag->first_name . ' ' . $flag->last_name); ?></h6>
                      <p class="user-username">@<?php echo htmlspecialchars($flag->username); ?></p>
                      <span class="user-type-badge user-type-<?php echo $flag->flagged_by_user_type; ?>">
                        <?php echo ucfirst($flag->flagged_by_user_type); ?>
                      </span>
                    </div>
                  </td>
                  <td>
                    <div class="flag-info">
                      <?php if ($flag->flag_reason): ?>
                        <p class="flag-reason"><?php echo htmlspecialchars($flag->flag_reason); ?></p>
                      <?php endif; ?>
                      <?php if ($flag->flag_details): ?>
                        <p class="flag-details"><?php echo htmlspecialchars(substr($flag->flag_details, 0, 150)) . (strlen($flag->flag_details) > 150 ? '...' : ''); ?></p>
                      <?php endif; ?>
                    </div>
                  </td>
                  <td>
                    <div class="created-info">
                      <p class="created-date"><?php echo date('M j, Y', strtotime($flag->created_at)); ?></p>
                      <p class="created-duration"><?php echo time_ago($flag->created_at); ?></p>
                    </div>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="<?php echo base_url('admin/view_job/' . $flag->job_id); ?>" 
                         class="btn btn-sm btn-outline-primary" 
                         title="View Job">
                        <i class="fas fa-eye"></i>
                      </a>
                      <button type="button" 
                              class="btn btn-sm btn-outline-success resolve-flag-btn" 
                              data-flag-id="<?php echo $flag->id; ?>"
                              data-job-title="<?php echo htmlspecialchars($flag->job_title); ?>"
                              title="Resolve Flag">
                        <i class="fas fa-check"></i>
                      </button>
                      <button type="button" 
                              class="btn btn-sm btn-outline-secondary dismiss-flag-btn" 
                              data-flag-id="<?php echo $flag->id; ?>"
                              data-job-title="<?php echo htmlspecialchars($flag->job_title); ?>"
                              title="Dismiss Flag">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
      <div class="modern-pagination">
        <div class="pagination-info">
          <span>Showing <?php echo $pagination['offset'] + 1; ?> to <?php echo min($pagination['offset'] + $pagination['per_page'], $pagination['total_items']); ?> of <?php echo $pagination['total_items']; ?> flags</span>
        </div>
        
        <nav aria-label="Flags pagination">
          <ul class="pagination">
            <?php if ($pagination['current_page'] > 1): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/flags?page=' . $pagination['prev_page']); ?>">
                  <i class="fas fa-chevron-left"></i>
                </a>
              </li>
            <?php endif; ?>
            
            <?php
            $start_page = max(1, $pagination['current_page'] - 2);
            $end_page = min($pagination['total_pages'], $pagination['current_page'] + 2);
            
            for ($i = $start_page; $i <= $end_page; $i++):
            ?>
              <li class="page-item <?php echo ($i == $pagination['current_page']) ? 'active' : ''; ?>">
                <a class="page-link" href="<?php echo base_url('admin/flags?page=' . $i); ?>">
                  <?php echo $i; ?>
                </a>
              </li>
            <?php endfor; ?>
            
            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
              <li class="page-item">
                <a class="page-link" href="<?php echo base_url('admin/flags?page=' . $pagination['next_page']); ?>">
                  <i class="fas fa-chevron-right"></i>
                </a>
              </li>
            <?php endif; ?>
          </ul>
        </nav>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Resolve Flag Modal -->
<div class="modal fade" id="resolveFlagModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Resolve Flag</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to resolve this flag?</p>
        <div class="form-group">
          <label for="resolveNotes">Resolution Notes (Optional)</label>
          <textarea class="form-control" id="resolveNotes" rows="3" placeholder="Add notes about how this flag was resolved..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="confirmResolve">Resolve Flag</button>
      </div>
    </div>
  </div>
</div>

<!-- Dismiss Flag Modal -->
<div class="modal fade" id="dismissFlagModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Dismiss Flag</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to dismiss this flag?</p>
        <div class="form-group">
          <label for="dismissNotes">Dismissal Notes (Optional)</label>
          <textarea class="form-control" id="dismissNotes" rows="3" placeholder="Add notes about why this flag is being dismissed..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-secondary" id="confirmDismiss">Dismiss Flag</button>
      </div>
    </div>
  </div>
</div>

<style>
/* User Type Badges */
.user-type-badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    margin-top: 0.25rem;
}

.user-type-host {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.user-type-cleaner {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.user-type-admin {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}

/* Flag Info Styling */
.flag-info {
    max-width: 200px;
}

.flag-reason {
    font-weight: 600;
    color: #dc3545;
    margin-bottom: 0.25rem;
}

.flag-details {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0;
}

/* Job Meta Styling */
.job-meta {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
    margin-top: 0.5rem;
}

.job-host, .job-status {
    font-size: 0.8rem;
    color: #6c757d;
}

.job-status i {
    color: #28a745;
}

/* Modal Styling */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.modal-header .close {
    color: white;
    opacity: 0.8;
}

.modal-header .close:hover {
    opacity: 1;
}

/* Action Button Hover Effects */
.resolve-flag-btn:hover {
    background-color: #28a745;
    border-color: #28a745;
    color: white;
}

.dismiss-flag-btn:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}
</style>

<script>
$(document).ready(function() {
    let currentFlagId = null;

    // Resolve flag button click handler
    $(document).on('click', '.resolve-flag-btn', function(e) {
        e.preventDefault();
        currentFlagId = $(this).data('flag-id');
        $('#resolveFlagModal').modal('show');
    });

    // Dismiss flag button click handler
    $(document).on('click', '.dismiss-flag-btn', function(e) {
        e.preventDefault();
        currentFlagId = $(this).data('flag-id');
        $('#dismissFlagModal').modal('show');
    });

    // Confirm resolve flag
    $('#confirmResolve').on('click', function() {
        if (!currentFlagId) return;

        const resolutionNotes = $('#resolveNotes').val();

        // Show loading state
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Resolving...');

        // Make AJAX request
        $.ajax({
            url: '<?php echo base_url('admin/resolve_flag'); ?>',
            type: 'POST',
            data: {
                flag_id: currentFlagId,
                resolution_notes: resolutionNotes
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message and reload page
                    alert('Flag resolved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    // Reset button
                    $('#confirmResolve').prop('disabled', false).html('Resolve Flag');
                }
            },
            error: function() {
                alert('An error occurred while resolving the flag.');
                // Reset button
                $('#confirmResolve').prop('disabled', false).html('Resolve Flag');
            }
        });
    });

    // Confirm dismiss flag
    $('#confirmDismiss').on('click', function() {
        if (!currentFlagId) return;

        const dismissNotes = $('#dismissNotes').val();

        // Show loading state
        $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Dismissing...');

        // Make AJAX request
        $.ajax({
            url: '<?php echo base_url('admin/dismiss_flag'); ?>',
            type: 'POST',
            data: {
                flag_id: currentFlagId,
                resolution_notes: dismissNotes
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    // Show success message and reload page
                    alert('Flag dismissed successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                    // Reset button
                    $('#confirmDismiss').prop('disabled', false).html('Dismiss Flag');
                }
            },
            error: function() {
                alert('An error occurred while dismissing the flag.');
                // Reset button
                $('#confirmDismiss').prop('disabled', false).html('Dismiss Flag');
            }
        });
    });

    // Clear modals when closed
    $('#resolveFlagModal, #dismissFlagModal').on('hidden.bs.modal', function() {
        currentFlagId = null;
        $('#resolveNotes, #dismissNotes').val('');
        $('#confirmResolve, #confirmDismiss').prop('disabled', false).html(function() {
            return $(this).hasClass('resolve-flag-btn') ? 'Resolve Flag' : 'Dismiss Flag';
        });
    });

    // Add animation to table rows
    $('.modern-table tbody tr').each(function(index) {
        $(this).css('opacity', '0').delay(index * 50).animate({
            opacity: 1
        }, 500);
    });
});
</script>
