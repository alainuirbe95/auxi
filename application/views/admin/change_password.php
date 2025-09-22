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
/* Modern Password Change Styles */
.password-change-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 2rem;
}

.modern-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.3);
    overflow: hidden;
    position: relative;
}

.modern-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: cardFloat 8s ease-in-out infinite;
}

@keyframes cardFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(180deg); }
}

.modern-card-header {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    padding: 2rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
    z-index: 2;
}

.modern-card-title {
    color: white;
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
}

.modern-card-title i {
    margin-right: 1rem;
    font-size: 1.5rem;
}

.modern-card-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1rem;
    margin: 0.5rem 0 0 0;
}

.first-login-notice {
    background: rgba(255, 193, 7, 0.2);
    border: 1px solid rgba(255, 193, 7, 0.4);
    color: #fff3cd;
    padding: 1rem;
    border-radius: 12px;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    font-size: 0.95rem;
    animation: pulse 2s infinite;
}

.first-login-notice i {
    margin-right: 0.75rem;
    font-size: 1.2rem;
    color: #ffc107;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.8; }
}

.modern-card-body {
    padding: 2rem;
    position: relative;
    z-index: 2;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    color: white;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-size: 1rem;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
}

.form-control::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

.form-control:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
}

.password-strength {
    margin-top: 0.5rem;
    font-size: 0.85rem;
}

.strength-bar {
    height: 4px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 2px;
    margin-top: 0.5rem;
    overflow: hidden;
}

.strength-fill {
    height: 100%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-weak { background: #ff4757; width: 25%; }
.strength-fair { background: #ffa502; width: 50%; }
.strength-good { background: #2ed573; width: 75%; }
.strength-strong { background: #1e90ff; width: 100%; }

.btn-modern {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    color: white;
    padding: 0.75rem 2rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    backdrop-filter: blur(10px);
    position: relative;
    overflow: hidden;
}

.btn-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s ease;
}

.btn-modern:hover::before {
    left: 100%;
}

.btn-modern:hover {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.5);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.btn-modern:active {
    transform: translateY(0);
}

.btn-modern:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.btn-modern:disabled:hover {
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
    transform: none;
    box-shadow: none;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    border: none;
    font-weight: 500;
    position: relative;
    backdrop-filter: blur(10px);
}

.alert-success {
    background: rgba(40, 167, 69, 0.2);
    color: #d4edda;
    border: 1px solid rgba(40, 167, 69, 0.3);
}

.alert-danger {
    background: rgba(220, 53, 69, 0.2);
    color: #f8d7da;
    border: 1px solid rgba(220, 53, 69, 0.3);
}

.alert-warning {
    background: rgba(255, 193, 7, 0.2);
    color: #fff3cd;
    border: 1px solid rgba(255, 193, 7, 0.3);
}

.user-info {
    background: rgba(255, 255, 255, 0.1);
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.user-info h4 {
    color: white;
    margin: 0 0 0.75rem 0;
    font-size: 1rem;
    font-weight: 600;
    text-align: center;
}

.user-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

.user-detail {
    color: rgba(255, 255, 255, 0.9);
    font-size: 0.8rem;
    display: flex;
    flex-direction: column;
    padding: 0.25rem 0;
}


.user-detail strong {
    color: white;
    font-weight: 600;
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.15rem;
}

.user-detail span {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.75rem;
}

.loading {
    display: none;
    text-align: center;
    padding: 1rem;
}

.spinner {
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin: 0 auto 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .password-change-container {
        padding: 1rem;
    }
    
    .modern-card-header,
    .modern-card-body {
        padding: 1.5rem;
    }
    
    .modern-card-title {
        font-size: 1.5rem;
    }
    
    .user-info {
        max-width: 100%;
        margin-left: 0;
        margin-right: 0;
    }
    
    .user-details {
        grid-template-columns: 1fr;
        gap: 0.4rem;
    }
    
    .user-detail {
        padding: 0.2rem 0;
    }
}

@media (max-width: 576px) {
    .modern-card-header,
    .modern-card-body {
        padding: 1rem;
    }
    
    .btn-modern {
        width: 100%;
        padding: 0.75rem 1rem;
    }
}
</style>

<div class="password-change-container">
    <div class="modern-card">
        <div class="modern-card-header">
            <h2 class="modern-card-title">
                <i class="fas fa-key"></i>
                Change Password
            </h2>
            <p class="modern-card-subtitle">Update your account password for enhanced security</p>
            <?php if (isset($user_info) && $user_info->login_count == 1 && empty($user_info->passwd_modified_at)): ?>
                <div class="first-login-notice">
                    <i class="fas fa-info-circle"></i>
                    <strong>Welcome!</strong> This appears to be your first login. Please set a secure password for your account.
                </div>
            <?php endif; ?>
        </div>
        
        <div class="modern-card-body">
            <!-- User Info -->
            <div class="user-info">
                <h4><i class="fas fa-user-circle mr-2"></i>Account Information</h4>
                <div class="user-details">
                    <div class="user-detail">
                        <strong>Username:</strong>
                        <span><?php echo htmlspecialchars($user_info->username); ?></span>
                    </div>
                    <div class="user-detail">
                        <strong>Email:</strong>
                        <span><?php echo htmlspecialchars($user_info->email); ?></span>
                    </div>
                    <div class="user-detail">
                        <strong>Role:</strong>
                        <span>
                        <?php 
                        switch($user_info->auth_level) {
                            case 3: echo 'Cleaner'; break;
                            case 6: echo 'Host'; break;
                            case 9: echo 'Administrator'; break;
                            default: echo 'User'; break;
                        }
                        ?>
                        </span>
                    </div>
                    <div class="user-detail">
                        <strong>Last Updated:</strong>
                        <span><?php echo time_ago($user_info->modified_at); ?></span>
                    </div>
                </div>
            </div>

            <!-- Alert Messages -->
            <div id="alert-container"></div>

            <!-- Password Change Form -->
            <form id="password-change-form">
                <div class="form-group">
                    <label for="current_password" class="form-label">
                        <i class="fas fa-lock mr-2"></i>Current Password
                    </label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="form-control" 
                           placeholder="Enter your current password"
                           required>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">
                        <i class="fas fa-key mr-2"></i>New Password
                    </label>
                    <input type="password" 
                           id="new_password" 
                           name="new_password" 
                           class="form-control" 
                           placeholder="Enter your new password"
                           required>
                    <div class="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strength-fill"></div>
                        </div>
                        <small id="strength-text" style="color: rgba(255, 255, 255, 0.7);">Password strength will appear here</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-check-circle mr-2"></i>Confirm New Password
                    </label>
                    <input type="password" 
                           id="confirm_password" 
                           name="confirm_password" 
                           class="form-control" 
                           placeholder="Confirm your new password"
                           required>
                </div>

                <div class="loading" id="loading">
                    <div class="spinner"></div>
                    <p style="color: white; margin: 0;">Updating password...</p>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn-modern" id="submit-btn">
                        <i class="fas fa-save mr-2"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Password strength checker
    $('#new_password').on('input', function() {
        var password = $(this).val();
        var strength = checkPasswordStrength(password);
        
        $('#strength-fill').removeClass('strength-weak strength-fair strength-good strength-strong');
        $('#strength-fill').addClass('strength-' + strength.level);
        $('#strength-text').text(strength.text).css('color', strength.color);
    });

    // Form submission
    $('#password-change-form').on('submit', function(e) {
        e.preventDefault();
        
        var currentPassword = $('#current_password').val();
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();
        
        // Basic validation
        if (!currentPassword || !newPassword || !confirmPassword) {
            showAlert('Please fill in all fields', 'danger');
            return;
        }
        
        if (newPassword !== confirmPassword) {
            showAlert('New passwords do not match', 'danger');
            return;
        }
        
        if (newPassword.length < 8) {
            showAlert('New password must be at least 8 characters long', 'danger');
            return;
        }
        
        // Show loading
        $('#loading').show();
        $('#submit-btn').prop('disabled', true);
        
        // Submit form
        $.ajax({
            url: '<?php echo base_url('admin/update_password'); ?>',
            type: 'POST',
            data: {
                current_password: currentPassword,
                new_password: newPassword,
                confirm_password: confirmPassword
            },
            dataType: 'json',
            success: function(response) {
                $('#loading').hide();
                $('#submit-btn').prop('disabled', false);
                
                if (response.success) {
                    showAlert(response.message, 'success');
                    $('#password-change-form')[0].reset();
                    $('#strength-fill').removeClass().addClass('strength-fill');
                    $('#strength-text').text('Password strength will appear here').css('color', 'rgba(255, 255, 255, 0.7)');
                    
                    // Check if this was a first-time user
                    var isFirstLogin = <?php echo (isset($user_info) && $user_info->login_count == 1 && empty($user_info->passwd_modified_at)) ? 'true' : 'false'; ?>;
                    if (isFirstLogin) {
                        // Redirect to dashboard after 3 seconds for first-time users
                        setTimeout(function() {
                            window.location.href = '<?php echo base_url('admin/dashboard'); ?>';
                        }, 3000);
                    }
                } else {
                    showAlert(response.message, 'danger');
                }
            },
            error: function() {
                $('#loading').hide();
                $('#submit-btn').prop('disabled', false);
                showAlert('An error occurred. Please try again.', 'danger');
            }
        });
    });
    
    function showAlert(message, type) {
        var alertHtml = '<div class="alert alert-' + type + '">' +
                       '<i class="fas fa-' + (type === 'success' ? 'check-circle' : 'exclamation-triangle') + ' mr-2"></i>' +
                       message +
                       '</div>';
        $('#alert-container').html(alertHtml);
        
        // Auto-hide success messages
        if (type === 'success') {
            setTimeout(function() {
                $('#alert-container').fadeOut();
            }, 5000);
        }
    }
    
    function checkPasswordStrength(password) {
        var score = 0;
        var feedback = [];
        
        if (password.length >= 8) score++;
        else feedback.push('at least 8 characters');
        
        if (/[a-z]/.test(password)) score++;
        else feedback.push('lowercase letters');
        
        if (/[A-Z]/.test(password)) score++;
        else feedback.push('uppercase letters');
        
        if (/[0-9]/.test(password)) score++;
        else feedback.push('numbers');
        
        if (/[^A-Za-z0-9]/.test(password)) score++;
        else feedback.push('special characters');
        
        if (password.length >= 12) score++;
        
        var levels = [
            { level: 'weak', text: 'Very weak password', color: 'rgba(255, 71, 87, 0.8)' },
            { level: 'weak', text: 'Weak password', color: 'rgba(255, 71, 87, 0.8)' },
            { level: 'fair', text: 'Fair password', color: 'rgba(255, 165, 2, 0.8)' },
            { level: 'good', text: 'Good password', color: 'rgba(46, 213, 115, 0.8)' },
            { level: 'strong', text: 'Strong password', color: 'rgba(30, 144, 255, 0.8)' },
            { level: 'strong', text: 'Very strong password', color: 'rgba(30, 144, 255, 0.8)' }
        ];
        
        var result = levels[Math.min(score, 5)];
        if (feedback.length > 0) {
            result.text += ' (add ' + feedback.join(', ') + ')';
        }
        
        return result;
    }
});
</script>
