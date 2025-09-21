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

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Rejected Users Table -->
            <div class="card modern-card">
                <div class="card-header modern-card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">
                                <i class="fas fa-user-times mr-2"></i>
                                Rejected Users
                            </h3>
                            <p class="card-subtitle">Users who were rejected by administrators</p>
                        </div>
                        <div class="card-actions">
                            <button onclick="refreshTable()" class="btn btn-outline-primary">
                                <i class="fas fa-sync-alt mr-1"></i>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php if (empty($rejected_users)): ?>
                        <div class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-user-times empty-icon"></i>
                                <h4>No Rejected Users</h4>
                                <p>There are currently no rejected users in the system.</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table modern-table">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Contact</th>
                                        <th>Role</th>
                                        <th>Rejected</th>
                                        <th>Rejected By</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rejected_users as $user): ?>
                                        <tr class="user-row">
                                            <td>
                                                <div class="user-info">
                                                    <div class="user-avatar">
                                                        <?php if (!empty($user->avatar)): ?>
                                                            <img src="<?php echo base_url('uploads/avatars/' . $user->avatar); ?>" alt="Avatar">
                                                        <?php else: ?>
                                                            <div class="avatar-placeholder">
                                                                <i class="fas fa-user"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="user-details">
                                                        <h5 class="user-name"><?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?></h5>
                                                        <p class="user-username">@<?php echo htmlspecialchars($user->username); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="contact-info">
                                                    <p class="contact-email">
                                                        <i class="fas fa-envelope mr-1"></i>
                                                        <?php echo htmlspecialchars($user->email); ?>
                                                    </p>
                                                    <?php if (!empty($user->phone)): ?>
                                                        <p class="contact-phone">
                                                            <i class="fas fa-phone mr-1"></i>
                                                            <?php echo htmlspecialchars($user->phone); ?>
                                                        </p>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                $role_class = '';
                                                $role_text = '';
                                                switch($user->auth_level) {
                                                    case 3:
                                                        $role_class = 'badge-cleaner';
                                                        $role_text = 'Cleaner';
                                                        break;
                                                    case 6:
                                                        $role_class = 'badge-host';
                                                        $role_text = 'Host';
                                                        break;
                                                    case 9:
                                                        $role_class = 'badge-admin';
                                                        $role_text = 'Administrator';
                                                        break;
                                                    default:
                                                        $role_class = 'badge-secondary';
                                                        $role_text = 'Unknown';
                                                }
                                                ?>
                                                <span class="badge <?php echo $role_class; ?>"><?php echo $role_text; ?></span>
                                            </td>
                                            <td>
                                                <div class="rejection-info">
                                                    <p class="rejection-date">
                                                        <i class="fas fa-calendar-times mr-1"></i>
                                                        <?php echo date('M j, Y', strtotime($user->rejected_at)); ?>
                                                    </p>
                                                    <p class="rejection-time">
                                                        <small class="text-muted"><?php echo time_ago($user->rejected_at); ?></small>
                                                    </p>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="rejected-by">
                                                    <i class="fas fa-user-shield mr-1"></i>
                                                    <?php echo htmlspecialchars($user->rejected_by); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="<?php echo base_url('admin/view_user/' . $user->user_id); ?>" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button onclick="restoreUser(<?php echo $user->user_id; ?>, '<?php echo htmlspecialchars($user->first_name . ' ' . $user->last_name); ?>')" 
                                                            class="btn btn-sm btn-outline-success" 
                                                            title="Restore User">
                                                        <i class="fas fa-undo"></i>
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
            </div>
        </div>
    </div>
</div>

<style>
.modern-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
}

.modern-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    padding: 1.5rem;
    border: none;
}

.modern-card-header .card-title {
    margin: 0;
    font-weight: 600;
    font-size: 1.5rem;
}

.modern-card-header .card-subtitle {
    margin: 0.5rem 0 0 0;
    opacity: 0.9;
    font-size: 0.95rem;
}

.modern-table {
    margin: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.modern-table thead th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #495057;
    padding: 1rem;
    border-bottom: 2px solid #dee2e6;
}

.modern-table tbody tr {
    border-bottom: 1px solid #f1f3f4;
    transition: all 0.3s ease;
}

.modern-table tbody tr:hover {
    background: #f8f9fa;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.modern-table tbody td {
    padding: 1rem;
    border: none;
    vertical-align: middle;
}

.user-info {
    display: flex;
    align-items: center;
}

.user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin-right: 12px;
    overflow: hidden;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    color: #6c757d;
    font-size: 18px;
}

.user-details h5 {
    margin: 0;
    font-weight: 600;
    color: #2c3e50;
    font-size: 1rem;
}

.user-details p {
    margin: 0;
    color: #6c757d;
    font-size: 0.85rem;
}

.contact-info p {
    margin: 0.25rem 0;
    font-size: 0.9rem;
    color: #495057;
}

.badge-cleaner {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-host {
    background: linear-gradient(135deg, #007bff, #6610f2);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-admin {
    background: linear-gradient(135deg, #dc3545, #e83e8c);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.rejection-info p {
    margin: 0.25rem 0;
    font-size: 0.9rem;
}

.rejection-date {
    color: #dc3545;
    font-weight: 600;
}

.rejected-by {
    color: #6c757d;
    font-size: 0.9rem;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.action-buttons .btn {
    border-radius: 6px;
    padding: 0.4rem 0.6rem;
    font-size: 0.8rem;
    transition: all 0.3s ease;
}

.action-buttons .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.empty-state {
    padding: 3rem 1rem;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-state h4 {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #adb5bd;
    margin: 0;
}

.card-actions .btn {
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.card-actions .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}
</style>

<script>
$(document).ready(function() {
    // Refresh table function
    window.refreshTable = function() {
        location.reload();
    };

    // Restore user function
    window.restoreUser = function(userId, userName) {
        if (confirm('Are you sure you want to restore user "' + userName + '"?\n\nThis will unban their account and allow them to access the platform again.')) {
            window.location.href = '<?php echo base_url('admin/restore_user/'); ?>' + userId;
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
