<!-- Modern Login Container -->
<div class="modern-login-container">
    <!-- Background Elements -->
    <div class="login-background">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>
        <div class="bg-shape shape-3"></div>
    </div>
    
    <!-- Login Card -->
    <div class="login-card">
        <!-- Header Section -->
        <div class="login-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h1 class="logo-text"><?php echo APP_NAME ?></h1>
            </div>
            <p class="login-subtitle">Welcome back! Please sign in to your account</p>
        </div>

        <!-- Success Message -->
        <?php if ($this->input->get(AUTH_LOGOUT_PARAM) == '1'): ?>
            <div class="alert alert-success modern-alert">
                <div class="alert-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="alert-content">
                    <h4>Logout Successful!</h4>
                    <p>You have been successfully logged out.</p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Registration Success Message -->
        <?php if ($this->input->get('registered') == '1'): ?>
            <div class="alert alert-success modern-alert">
                <div class="alert-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="alert-content">
                    <h4>Registration Successful!</h4>
                    <p>Your account has been created and is pending admin review. You will receive access once approved.</p>
                </div>
        </div>
        <?php endif; ?>

        <!-- Login Form -->
        <div class="login-form-container">
            <?php echo form_open('app/ajax_attempt_login', ['class' => 'modern-login-form']); ?>
            
            <!-- Username/Email Field -->
            <div class="form-group modern-form-group">
                <div class="input-container">
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <input name="login_string" class="modern-input" placeholder="Username or Email" type="text" required>
                    <div class="input-border"></div>
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-group modern-form-group">
                <div class="input-container">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input name="login_pass" class="modern-input" placeholder="Password" type="password" required>
                    <div class="input-border"></div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="modern-btn">
                    <span class="btn-text">Sign In</span>
                    <div class="btn-loader">
                        <div class="spinner"></div>
                    </div>
                </button>
        </div>

            <!-- Hidden Fields -->
        <input type="hidden" id="max_allowed_attempts" value="<?php echo config_item('max_allowed_attempts'); ?>" />
        <input type="hidden" id="mins_on_hold" value="<?php echo ( config_item('seconds_on_hold') / 60 ); ?>" />

        </form>

            <!-- Links Section -->
            <div class="login-links">
                <a href="<?php echo base_url() ?>app/recover" class="forgot-password">
                    <i class="fas fa-key"></i>
                    Forgot Password?
                </a>
                <a href="register" class="register-link">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="login-footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME ?>. All rights reserved.</p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Add focus effects to inputs
        $('.modern-input').on('focus', function() {
            $(this).parent().addClass('focused');
        }).on('blur', function() {
            if ($(this).val() === '') {
                $(this).parent().removeClass('focused');
            }
        });

        // Check if inputs have values on page load
        $('.modern-input').each(function() {
            if ($(this).val() !== '') {
                $(this).parent().addClass('focused');
            }
        });

        // Form submission with enhanced UX
        $(document).on('submit', '.modern-login-form', function (e) {
            e.preventDefault();
            
            var $form = $(this);
            var $btn = $form.find('.modern-btn');
            var $inputs = $form.find('.modern-input');
            
            // Remove previous error states
            $inputs.removeClass('error');
            
            // Validate inputs
            var isValid = true;
            $inputs.each(function() {
                if ($(this).val().trim() === '') {
                    $(this).addClass('error');
                    isValid = false;
                }
            });
            
            if (!isValid) {
                // Shake animation for error
                $form.addClass('shake');
                setTimeout(function() {
                    $form.removeClass('shake');
                }, 500);
                return false;
            }
            
            // Show loading state
            $btn.addClass('loading');
            $btn.prop('disabled', true);
            
            $.ajax({
                type: 'post',
                cache: false,
                url: '<?php echo base_url() ?>app/ajax_attempt_login',
                data: {
                    'login_string': $('[name="login_string"]').val(),
                    'login_pass': $('[name="login_pass"]').val(),
                    'loginToken': $('[name="token"]').val()
                },
                dataType: 'json',
                success: function (response) {
                    $('[name="loginToken"]').val(response.token);
                    console.log(response);
                    
                    if (response.status == 1) {
                        // Success - add success animation
                        $btn.removeClass('loading').addClass('success');
                        $btn.find('.btn-text').text('Success!');
                        
                        // Check if this is first login
                        if (response.first_login) {
                            // First login - redirect to password change
                            setTimeout(function() {
                                window.location.href = '<?php echo base_url('admin/change_password'); ?>';
                            }, 1000);
                        } else {
                            // Regular login - redirect based on user role
                            setTimeout(function() {
                                // Redirect based on user level
                                if (response.level == 9) {
                                    // Admin
                                    window.location.href = '<?php echo base_url('admin/dashboard'); ?>';
                                } else if (response.level == 6) {
                                    // Host
                                    window.location.href = '<?php echo base_url('host'); ?>';
                                } else if (response.level == 3) {
                                    // Cleaner
                                    window.location.href = '<?php echo base_url('cleaner'); ?>';
                                } else {
                                    // Default fallback
                                    window.location.href = '<?php echo base_url('admin/dashboard'); ?>';
                                }
                            }, 1000);
                        }
                    } else if (response.status == 0 && response.on_hold) {
                        // Account on hold
                        $btn.removeClass('loading');
                        $btn.prop('disabled', false);
                        showError('Account temporarily locked due to excessive login attempts.');
                    } else {
                        // Login failed
                        $btn.removeClass('loading');
                        $btn.prop('disabled', false);
                        showError('Invalid username or password. Please try again.');
                        
                        // Add error state to inputs
                        $inputs.addClass('error');
                    }
                },
                error: function() {
                    // Network error
                    $btn.removeClass('loading');
                    $btn.prop('disabled', false);
                    showError('Connection error. Please check your internet connection.');
                }
            });
            
            return false;
        });
        
        // Function to show error messages
        function showError(message) {
            // Remove existing error messages
            $('.error-message').remove();
            
            // Create error message
            var errorHtml = '<div class="error-message" style="background: #fed7d7; color: #e53e3e; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #e53e3e; display: flex; align-items: center;"><i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>' + message + '</div>';
            
            // Insert error message
            $('.login-form-container').prepend(errorHtml);
            
            // Auto-remove after 5 seconds
            setTimeout(function() {
                $('.error-message').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    });
</script>

<style>
/* Additional animations */
.shake {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.modern-btn.success {
    background: linear-gradient(135deg, #48bb78, #38a169) !important;
    box-shadow: 0 8px 16px rgba(72, 187, 120, 0.3) !important;
}

.input-container.focused .input-icon {
    color: #667eea;
}

.error-message {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>