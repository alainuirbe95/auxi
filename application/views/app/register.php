<!-- Modern Registration Container -->
<div class="modern-register-container">
    <!-- Background Elements -->
    <div class="register-background">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>
        <div class="bg-shape shape-3"></div>
    </div>

    <!-- Registration Card -->
    <div class="register-card">
        <!-- Header Section -->
        <div class="register-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h1 class="logo-text">Join <?php echo APP_NAME ?></h1>
            </div>
            <p class="register-subtitle">Create your account and start your journey with us</p>
        </div>

        <!-- Registration Form -->
        <div class="register-form-container">
            <!-- Error Message -->
            <?php if (isset($registration_error)): ?>
                <div class="alert alert-danger modern-alert">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Registration Failed!</h4>
                        <p><?php echo $registration_error; ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Validation Errors -->
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger modern-alert">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Please fix the following errors:</h4>
                        <p><?php echo validation_errors(); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <?php echo form_open('app/register', ['class' => 'modern-register-form']); ?>
            
            <!-- Name Fields -->
            <div class="form-row">
                <div class="form-group modern-form-group">
                    <div class="input-container">
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                        </div>
                                <input type="text" class="modern-input" placeholder="First Name" name="first_name" value="<?php echo isset($first_name) ? $first_name : ''; ?>" required>
                        <div class="input-border"></div>
                    </div>
                </div>
                
                <div class="form-group modern-form-group">
                    <div class="input-container">
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                        </div>
                                <input type="text" class="modern-input" placeholder="Last Name" name="last_name" value="<?php echo isset($last_name) ? $last_name : ''; ?>" required>
                        <div class="input-border"></div>
                    </div>
                </div>
            </div>

            <!-- Username Field -->
            <div class="form-group modern-form-group">
                <div class="input-container">
                    <div class="input-icon">
                        <i class="fas fa-at"></i>
                    </div>
                    <input type="text" class="modern-input" placeholder="Username" name="username" value="<?php echo isset($username) ? $username : ''; ?>" required>
                    <div class="input-border"></div>
                </div>
            </div>

            <!-- Email Field -->
            <div class="form-group modern-form-group">
                <div class="input-container">
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <input type="email" class="modern-input" placeholder="Email Address" name="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                    <div class="input-border"></div>
                </div>
            </div>

            <!-- Password Field -->
            <div class="form-group modern-form-group">
                <div class="input-container">
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <input type="password" class="modern-input" placeholder="Password" name="passwd" required>
                    <div class="input-border"></div>
                </div>
                <div class="password-requirements">
                    <small>Password must be at least 8 characters long</small>
                </div>
            </div>

            <!-- Role Selection -->
            <div class="form-group modern-form-group">
                <label class="role-label">I want to register as:</label>
                <div class="role-selection">
                    <div class="role-option">
                        <input type="radio" id="role_cleaner" name="user_role" value="3" required>
                        <label for="role_cleaner" class="role-card">
                            <div class="role-icon">
                                <i class="fas fa-broom"></i>
                            </div>
                            <div class="role-content">
                                <h4>Cleaner</h4>
                                <p>I want to provide cleaning services</p>
                            </div>
                        </label>
                    </div>
                    
                    <div class="role-option">
                        <input type="radio" id="role_host" name="user_role" value="6" required>
                        <label for="role_host" class="role-card">
                            <div class="role-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <div class="role-content">
                                <h4>Host</h4>
                                <p>I want to hire cleaning services</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            <div class="form-group modern-form-group">
                <div class="terms-container">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms" class="terms-label">
                        I agree to the <a href="#" class="terms-link">Terms of Service</a> and <a href="#" class="terms-link">Privacy Policy</a>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" class="modern-btn">
                    <span class="btn-text">Create Account</span>
                    <div class="btn-loader">
                        <div class="spinner"></div>
                    </div>
                </button>
            </div>

        </form>

            <!-- Links Section -->
            <div class="register-links">
                <a href="login" class="login-link">
                    <i class="fas fa-sign-in-alt"></i>
                    Already have an account? Sign In
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="register-footer">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME ?>. All rights reserved.</p>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
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

    // Role selection effects
    $('.role-card').on('click', function() {
        $('.role-card').removeClass('selected');
        $(this).addClass('selected');
    });

    // Form submission with enhanced UX
    $(document).on('submit', '.modern-register-form', function (e) {
        e.preventDefault();
        
        var $form = $(this);
        var $btn = $form.find('.modern-btn');
        var $inputs = $form.find('.modern-input');
        var $roleInputs = $form.find('input[name="user_role"]');
        var $termsInput = $form.find('input[name="terms"]');
        
        // Remove previous error states
        $inputs.removeClass('error');
        $('.error-message').remove();
        
        // Validate inputs
        var isValid = true;
        var errorMessages = [];
        
        // Check required fields
        $inputs.each(function() {
            if ($(this).val().trim() === '') {
                $(this).addClass('error');
                isValid = false;
            }
        });
        
        // Check role selection
        if (!$roleInputs.is(':checked')) {
            errorMessages.push('Please select a user role');
            isValid = false;
        }
        
        // Check terms acceptance
        if (!$termsInput.is(':checked')) {
            errorMessages.push('Please accept the terms and conditions');
            isValid = false;
        }
        
        // Check password strength
        var password = $form.find('input[name="passwd"]').val();
        if (password.length < 8) {
            errorMessages.push('Password must be at least 8 characters long');
            isValid = false;
        }
        
        if (!isValid) {
            // Show error messages
            if (errorMessages.length > 0) {
                showError(errorMessages.join('<br>'));
            }
            
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
        
        // Submit the form
        $form[0].submit();
    });
    
    // Function to show error messages
    function showError(message) {
        // Remove existing error messages
        $('.error-message').remove();
        
        // Create error message
        var errorHtml = '<div class="error-message" style="background: #fed7d7; color: #e53e3e; padding: 12px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #e53e3e; display: flex; align-items: center;"><i class="fas fa-exclamation-triangle" style="margin-right: 8px;"></i>' + message + '</div>';
        
        // Insert error message
        $('.register-form-container').prepend(errorHtml);
        
        // Auto-remove after 8 seconds
        setTimeout(function() {
            $('.error-message').fadeOut(300, function() {
                $(this).remove();
            });
        }, 8000);
    }
    
    // Password strength indicator
    $('input[name="passwd"]').on('input', function() {
        var password = $(this).val();
        var strength = 0;
        
        if (password.length >= 8) strength++;
        if (password.match(/[a-z]/)) strength++;
        if (password.match(/[A-Z]/)) strength++;
        if (password.match(/[0-9]/)) strength++;
        if (password.match(/[^a-zA-Z0-9]/)) strength++;
        
        var $requirements = $('.password-requirements');
        if (strength < 2) {
            $requirements.html('<small style="color: #e53e3e;">Password is too weak</small>');
        } else if (strength < 4) {
            $requirements.html('<small style="color: #f6ad55;">Password is moderate</small>');
        } else {
            $requirements.html('<small style="color: #48bb78;">Password is strong</small>');
        }
    });
});
</script>

<style>
/* Additional registration animations */
.shake {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

.role-card.selected {
    border-color: #667eea !important;
    background: rgba(102, 126, 234, 0.1) !important;
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2) !important;
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

/* Form validation styles */
.modern-input.error {
    border-color: #e53e3e !important;
    background: #fed7d7 !important;
}

.modern-input.error:focus {
    border-color: #e53e3e !important;
    box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1) !important;
}
</style>