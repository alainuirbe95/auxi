<?php
// Helper function for time ago
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
/* Enhanced Pending Users Styles */
.pending-users-container {
    background: #f8f9fa;
    min-height: 100vh;
    padding: 2rem 0;
}

.pending-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.pending-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.pending-content {
    position: relative;
    z-index: 2;
}

.pending-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.pending-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 1.5rem;
}

.pending-stats {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.stat-item {
    display: flex;
    align-items: center;
    background: rgba(255,255,255,0.2);
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    backdrop-filter: blur(10px);
}

.stat-item i {
    margin-right: 0.5rem;
    font-size: 1.2rem;
}

.modern-table-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    margin-bottom: 2rem;
}

.table-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem 2rem;
    border-bottom: 1px solid #dee2e6;
}

.table-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin: 0;
    display: flex;
    align-items: center;
}

.table-title i {
    margin-right: 0.75rem;
    color: #667eea;
}

.table-actions {
    display: flex;
    gap: 0.5rem;
    margin-top: 1rem;
}

.btn-modern {
    border-radius: 8px;
    font-weight: 500;
    padding: 0.5rem 1rem;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    cursor: pointer;
}

.btn-modern i {
    margin-right: 0.5rem;
}

.btn-view {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
    color: white;
    text-decoration: none;
}

.btn-approve {
    background: linear-gradient(135deg, #a8edea, #fed6e3);
    color: #2d3748;
}

.btn-approve:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(168, 237, 234, 0.4);
    color: #2d3748;
    text-decoration: none;
}

.btn-reject {
    background: linear-gradient(135deg, #fa709a, #fee140);
    color: white;
}

.btn-reject:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(250, 112, 154, 0.4);
    color: white;
    text-decoration: none;
}

.modern-table {
    width: 100%;
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead th {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    font-weight: 600;
    padding: 1rem;
    text-align: left;
    border: none;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modern-table tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f4;
}

.modern-table tbody tr:hover {
    background: linear-gradient(135deg, #f8f9ff, #f0f2ff);
    transform: scale(1.01);
}

.modern-table tbody td {
    padding: 1rem;
    border: none;
    vertical-align: middle;
}

.user-avatar-small {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    margin-right: 0.75rem;
}

.user-info-cell {
    display: flex;
    align-items: center;
}

.user-details-cell {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: #2d3748;
    margin: 0 0 0.25rem 0;
}

.user-username {
    color: #718096;
    font-size: 0.9rem;
    margin: 0;
}

.user-email {
    color: #4a5568;
    font-size: 0.9rem;
    margin: 0;
}

.role-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.role-cleaner {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
}

.role-host {
    background: linear-gradient(135deg, #a8edea, #fed6e3);
    color: #2d3748;
}

.time-info {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.time-date {
    color: #4a5568;
    font-size: 0.9rem;
    font-weight: 500;
}

.time-ago {
    color: #718096;
    font-size: 0.8rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.no-pending {
    text-align: center;
    padding: 4rem 2rem;
    color: #718096;
}

.no-pending i {
    font-size: 4rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.no-pending h4 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: #4a5568;
}

.no-pending p {
    font-size: 1rem;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .pending-header {
        padding: 1.5rem;
    }
    
    .pending-title {
        font-size: 2rem;
    }
    
    .pending-subtitle {
        font-size: 1rem;
    }
    
    .pending-stats {
        flex-direction: column;
        gap: 1rem;
    }
    
    .modern-table-container {
        overflow-x: auto;
    }
    
    .modern-table {
        min-width: 600px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .btn-modern {
        justify-content: center;
    }
}
</style>

<!-- Main content -->
<div class="pending-users-container">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="pending-header">
            <div class="pending-content">
                <h1 class="pending-title">
                    <i class="fas fa-user-clock mr-3"></i>
                    Pending User Reviews
                </h1>
                <p class="pending-subtitle">
                    Review and approve new user registrations for your EasyClean platform.
                </p>
                <div class="pending-stats">
                    <div class="stat-item">
                        <i class="fas fa-users"></i>
                        <span><?php echo count($pending_users); ?> Pending Reviews</span>
                    </div>
                    <div class="stat-item">
                        <i class="fas fa-clock"></i>
                        <span>Last updated: <?php echo date('g:i A'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="modern-table-container">
            <div class="table-header">
                <h3 class="table-title">
                    <i class="fas fa-table"></i>
                    User Registration Details
                </h3>
                <div class="table-actions">
                    <button class="btn-modern btn-view" onclick="refreshTable()">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </button>
                    <button class="btn-modern btn-approve" onclick="approveAll()">
                        <i class="fas fa-check-double"></i>
                        Approve All
                    </button>
                </div>
            </div>
            
            <?php if (!empty($pending_users)): ?>
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Registration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pending_users as $user): ?>
                                <tr data-user-id="<?php echo $user->user_id; ?>">
                                    <td>
                                        <div class="user-info-cell">
                                            <div class="user-avatar-small">
                                                <?php echo strtoupper(substr($user->first_name, 0, 1)); ?>
                                            </div>
                                            <div class="user-details-cell">
                                                <div class="user-name"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></div>
                                                <div class="user-username">@<?php echo htmlspecialchars($user->username); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="role-badge role-<?php echo ($user->auth_level == 3) ? 'cleaner' : 'host'; ?>">
                                            <?php 
                                            switch($user->auth_level) {
                                                case 3: echo 'Cleaner'; break;
                                                case 6: echo 'Host'; break;
                                                default: echo 'Unknown'; break;
                                            }
                                            ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-email"><?php echo htmlspecialchars($user->email); ?></div>
                                    </td>
                                    <td>
                                        <div class="time-info">
                                            <div class="time-date"><?php echo date('M j, Y', strtotime($user->created_at)); ?></div>
                                            <div class="time-ago"><?php echo time_ago($user->created_at); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo base_url('admin/view_user/' . $user->user_id); ?>" 
                                               class="btn-modern btn-view" title="View Full Details">
                                                <i class="fas fa-eye"></i>
                                                View
                                            </a>
                                            <button type="button" class="btn-modern btn-approve approve-user-btn" 
                                                    data-user-id="<?php echo $user->user_id; ?>" title="Approve User">
                                                <i class="fas fa-check"></i>
                                                Approve
                                            </button>
                                            <button type="button" class="btn-modern btn-reject reject-user-btn" 
                                                    data-user-id="<?php echo $user->user_id; ?>" title="Reject User">
                                                <i class="fas fa-times"></i>
                                                Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-pending">
                    <i class="fas fa-user-check"></i>
                    <h4>No Pending Reviews</h4>
                    <p>All user registrations have been reviewed and processed.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    // Handle approve button click
    $('.approve-user-btn').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');
        var userName = $(this).closest('tr').find('.user-name').text();
        
        if (confirm('Are you sure you want to approve user "' + userName + '"?\n\nThis will activate their account and allow them to access the platform.')) {
            window.location.href = '<?php echo base_url('admin/approve_user/'); ?>' + userId;
        }
    });

    // Handle reject button click
    $('.reject-user-btn').on('click', function(e) {
        e.preventDefault();
        var userId = $(this).data('user-id');
        var userName = $(this).closest('tr').find('.user-name').text();
        
        if (confirm('Are you sure you want to reject user "' + userName + '"?\n\nThis will permanently ban their account and remove them from the pending list.')) {
            window.location.href = '<?php echo base_url('admin/reject_user/'); ?>' + userId;
        }
    });

    // Refresh table function
    window.refreshTable = function() {
        location.reload();
    };

    // Approve all function
    window.approveAll = function() {
        var pendingCount = $('.approve-user-btn').length;
        
        if (confirm('Are you sure you want to approve ALL ' + pendingCount + ' pending users?\n\nThis will activate all their accounts at once.')) {
            // Get all user IDs
            var userIds = [];
            $('.approve-user-btn').each(function() {
                userIds.push($(this).data('user-id'));
            });
            
            if (userIds.length > 0) {
                // Show loading message
                alert('Processing approval for ' + userIds.length + ' users...');
                
                // Send AJAX request to approve all
                $.ajax({
                    url: '<?php echo base_url('admin/approve_all_users'); ?>',
                    type: 'POST',
                    data: { user_ids: userIds },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Successfully approved ' + response.approved_count + ' users!');
                            location.reload();
                        } else {
                            alert('Error approving users: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('Error approving users. Please try again.');
                    }
                });
            }
        }
    };

    // Add animation to table rows on load
    $('.modern-table tbody tr').each(function(index) {
        $(this).css('opacity', '0').delay(index * 100).animate({
            opacity: 1
        }, 500);
    });
});
</script>