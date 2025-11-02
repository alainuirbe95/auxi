<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-cog"></i> Profile Settings
                    </h4>
                    <p class="text-muted mb-0">Manage your profile preferences and privacy settings</p>
                </div>
                <div class="card-body">
                    
                    <!-- Privacy Settings -->
                    <div class="settings-section">
                        <h5><i class="fas fa-shield-alt"></i> Privacy Settings</h5>
                        
                        <form id="privacySettingsForm">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_public" name="is_public" 
                                           <?php echo ($profile->is_public ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_public">
                                        <strong>Make my profile public</strong>
                                        <p class="text-muted mb-0">Allow other users to find and view your profile in search results</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="show_phone" name="show_phone" 
                                           <?php echo ($profile->show_phone ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="show_phone">
                                        <strong>Show phone number on profile</strong>
                                        <p class="text-muted mb-0">Display your phone number on your public profile</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="show_email" name="show_email" 
                                           <?php echo ($profile->show_email ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="show_email">
                                        <strong>Show email address on profile</strong>
                                        <p class="text-muted mb-0">Display your email address on your public profile</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="allow_messages" name="allow_messages" 
                                           <?php echo ($profile->allow_messages ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="allow_messages">
                                        <strong>Allow direct messages</strong>
                                        <p class="text-muted mb-0">Allow other users to send you direct messages through the platform</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Privacy Settings
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Notification Settings -->
                    <div class="settings-section">
                        <h5><i class="fas fa-bell"></i> Notification Settings</h5>
                        
                        <form id="notificationSettingsForm">
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email_notifications" name="email_notifications" 
                                           <?php echo ($profile->email_notifications ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="email_notifications">
                                        <strong>Email notifications</strong>
                                        <p class="text-muted mb-0">Receive notifications via email about new jobs, messages, and reviews</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="sms_notifications" name="sms_notifications" 
                                           <?php echo ($profile->sms_notifications ?? 0) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="sms_notifications">
                                        <strong>SMS notifications</strong>
                                        <p class="text-muted mb-0">Receive notifications via SMS for urgent updates</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="job_alerts" name="job_alerts" 
                                           <?php echo ($profile->job_alerts ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="job_alerts">
                                        <strong>New job alerts</strong>
                                        <p class="text-muted mb-0">Get notified when new jobs matching your specialties are posted</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="review_notifications" name="review_notifications" 
                                           <?php echo ($profile->review_notifications ?? 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="review_notifications">
                                        <strong>Review notifications</strong>
                                        <p class="text-muted mb-0">Get notified when someone leaves you a review</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Notification Settings
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Profile Management -->
                    <div class="settings-section">
                        <h5><i class="fas fa-user-cog"></i> Profile Management</h5>
                        
                        <div class="profile-actions">
                            <div class="action-item">
                                <div class="action-info">
                                    <h6>Edit Profile Information</h6>
                                    <p class="text-muted mb-0">Update your bio, specialties, service areas, and contact information</p>
                                </div>
                                <div class="action-button">
                                    <a href="<?php echo base_url('userprofile/edit'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit Profile
                                    </a>
                                </div>
                            </div>
                            
                            <div class="action-item">
                                <div class="action-info">
                                    <h6>Manage Photos</h6>
                                    <p class="text-muted mb-0">Upload or update your profile picture and cover photo</p>
                                </div>
                                <div class="action-button">
                                    <a href="<?php echo base_url('userprofile/edit'); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-camera"></i> Manage Photos
                                    </a>
                                </div>
                            </div>
                            
                            <div class="action-item">
                                <div class="action-info">
                                    <h6>Account Verification</h6>
                                    <p class="text-muted mb-0">Get verified to build trust and attract more clients</p>
                                </div>
                                <div class="action-button">
                                    <a href="<?php echo base_url('userprofile/verification'); ?>" class="btn btn-outline-success">
                                        <i class="fas fa-shield-alt"></i> 
                                        <?php echo ($profile->verification_status ?? 'not-started') == 'verified' ? 'Verification Status' : 'Get Verified'; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Actions -->
                    <div class="settings-section">
                        <h5><i class="fas fa-exclamation-triangle"></i> Account Actions</h5>
                        
                        <div class="account-actions">
                            <div class="action-item danger">
                                <div class="action-info">
                                    <h6>Deactivate Account</h6>
                                    <p class="text-muted mb-0">Temporarily deactivate your account. You can reactivate it anytime.</p>
                                </div>
                                <div class="action-button">
                                    <button type="button" class="btn btn-outline-warning" data-action="deactivate">
                                        <i class="fas fa-pause"></i> Deactivate
                                    </button>
                                </div>
                            </div>
                            
                            <div class="action-item danger">
                                <div class="action-info">
                                    <h6>Delete Account</h6>
                                    <p class="text-muted mb-0">Permanently delete your account and all associated data. This action cannot be undone.</p>
                                </div>
                                <div class="action-button">
                                    <button type="button" class="btn btn-outline-danger" data-action="delete">
                                        <i class="fas fa-trash"></i> Delete Account
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Data Export -->
                    <div class="settings-section">
                        <h5><i class="fas fa-download"></i> Data Management</h5>
                        
                        <div class="data-actions">
                            <div class="action-item">
                                <div class="action-info">
                                    <h6>Export My Data</h6>
                                    <p class="text-muted mb-0">Download a copy of all your profile data, reviews, and job history</p>
                                </div>
                                <div class="action-button">
                                    <button type="button" class="btn btn-outline-info" data-action="export">
                                        <i class="fas fa-download"></i> Export Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Confirm Action</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Dynamic content -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmAction">Confirm</button>
            </div>
        </div>
    </div>
</div>

<style>
.settings-section {
    margin-bottom: 40px;
    padding-bottom: 30px;
    border-bottom: 1px solid #e9ecef;
}

.settings-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.settings-section h5 {
    color: #007bff;
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e9ecef;
}

.form-switch .form-check-input {
    width: 3rem;
    height: 1.5rem;
}

.form-switch .form-check-input:checked {
    background-color: #007bff;
    border-color: #007bff;
}

.form-check-label strong {
    color: #333;
    font-weight: 600;
}

.form-check-label p {
    margin-top: 5px;
    font-size: 0.9rem;
}

.form-actions {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.profile-actions,
.account-actions,
.data-actions {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.action-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.action-item:hover {
    background: #e9ecef;
    border-color: #dee2e6;
}

.action-item.danger {
    border-color: #f8d7da;
    background: #f8d7da;
}

.action-item.danger:hover {
    background: #f5c6cb;
    border-color: #f1b0b7;
}

.action-info h6 {
    margin-bottom: 5px;
    color: #333;
    font-weight: 600;
}

.action-info p {
    margin-bottom: 0;
    font-size: 0.9rem;
}

.action-button {
    flex-shrink: 0;
}

.card {
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn {
    font-weight: 500;
    padding: 10px 20px;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.modal-content {
    border-radius: 10px;
}

.modal-header {
    border-bottom: 1px solid #e9ecef;
}

.modal-footer {
    border-top: 1px solid #e9ecef;
}
</style>

<script>
$(document).ready(function() {
    // Privacy settings form submission
    $('#privacySettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?php echo base_url("userprofile/update_settings"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    // Notification settings form submission
    $('#notificationSettingsForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.ajax({
            url: '<?php echo base_url("userprofile/update_notifications"); ?>',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
            }
        });
    });
    
    // Account action buttons
    $('[data-action]').on('click', function() {
        const action = $(this).data('action');
        
        switch (action) {
            case 'deactivate':
                showConfirmationModal(
                    'Deactivate Account',
                    'Are you sure you want to deactivate your account? You can reactivate it anytime by logging in again.',
                    'Deactivate',
                    'btn-warning',
                    function() {
                        // Handle deactivation
                        console.log('Account deactivation requested');
                    }
                );
                break;
                
            case 'delete':
                showConfirmationModal(
                    'Delete Account',
                    'Are you absolutely sure you want to permanently delete your account? This action cannot be undone and all your data will be lost.',
                    'Delete Account',
                    'btn-danger',
                    function() {
                        // Handle deletion
                        console.log('Account deletion requested');
                    }
                );
                break;
                
            case 'export':
                // Handle data export
                window.location.href = '<?php echo base_url("userprofile/export_data"); ?>';
                break;
        }
    });
    
    function showConfirmationModal(title, body, confirmText, confirmClass, confirmCallback) {
        $('#modalTitle').text(title);
        $('#modalBody').text(body);
        $('#confirmAction').text(confirmText).removeClass('btn-primary btn-warning btn-danger').addClass(confirmClass);
        
        // Remove previous event handlers and add new one
        $('#confirmAction').off('click').on('click', function() {
            confirmCallback();
            $('#confirmationModal').modal('hide');
        });
        
        $('#confirmationModal').modal('show');
    }
});
</script>

<?php $this->load->view('template/footer'); ?>
