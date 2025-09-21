<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card card-success">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-key mr-2"></i>Password Reset Successful
          </h3>
        </div>
        <div class="card-body">
          <div class="alert alert-success">
            <h5><i class="fas fa-check-circle mr-2"></i>Password Reset Complete</h5>
            <p class="mb-0">The user's password has been successfully reset with a temporary password.</p>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="info-box">
                <span class="info-box-icon bg-info">
                  <i class="fas fa-user"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">User</span>
                  <span class="info-box-number"><?php echo htmlspecialchars($user->username); ?></span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="info-box">
                <span class="info-box-icon bg-warning">
                  <i class="fas fa-envelope"></i>
                </span>
                <div class="info-box-content">
                  <span class="info-box-text">Email</span>
                  <span class="info-box-number"><?php echo htmlspecialchars($user->email); ?></span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="card card-warning">
            <div class="card-header">
              <h5 class="card-title">
                <i class="fas fa-exclamation-triangle mr-2"></i>Temporary Password
              </h5>
            </div>
            <div class="card-body">
              <div class="input-group">
                <input type="text" class="form-control form-control-lg font-weight-bold text-center" 
                       value="<?php echo htmlspecialchars($temp_password); ?>" 
                       id="tempPassword" readonly>
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary" type="button" onclick="copyPassword()">
                    <i class="fas fa-copy"></i> Copy
                  </button>
                </div>
              </div>
              <small class="text-muted mt-2 d-block">
                <i class="fas fa-info-circle mr-1"></i>
                Share this password securely with the user. They will be forced to change it on their next login.
              </small>
            </div>
          </div>
          
          <div class="alert alert-info">
            <h6><i class="fas fa-shield-alt mr-2"></i>Security Information</h6>
            <ul class="mb-0">
              <li>The user <strong>must change this password</strong> on their next login</li>
              <li>This temporary password will expire and cannot be used after the first login</li>
              <li>Share this password through a secure channel (not via email or unencrypted messaging)</li>
              <li>Consider using a secure communication method or in-person delivery</li>
            </ul>
          </div>
          
          <div class="text-center mt-4">
            <a href="<?php echo base_url('admin/view_user/' . $user->user_id); ?>" class="btn btn-primary mr-2">
              <i class="fas fa-user mr-1"></i>View User Profile
            </a>
            <a href="<?php echo base_url('admin/users'); ?>" class="btn btn-secondary">
              <i class="fas fa-list mr-1"></i>Back to Users List
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function copyPassword() {
  const tempPasswordInput = document.getElementById('tempPassword');
  tempPasswordInput.select();
  tempPasswordInput.setSelectionRange(0, 99999); // For mobile devices
  
  try {
    document.execCommand('copy');
    
    // Show success feedback
    const button = event.target.closest('button');
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
    button.classList.remove('btn-outline-secondary');
    button.classList.add('btn-success');
    
    setTimeout(() => {
      button.innerHTML = originalText;
      button.classList.remove('btn-success');
      button.classList.add('btn-outline-secondary');
    }, 2000);
    
  } catch (err) {
    alert('Unable to copy password. Please select and copy manually.');
  }
}

// Auto-select password on page load
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('tempPassword').select();
});
</script>

<style>
.info-box {
  display: flex;
  align-items: center;
  padding: 1rem;
  margin-bottom: 1rem;
  background: #fff;
  border: 1px solid #dee2e6;
  border-radius: 0.375rem;
}

.info-box-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
  border-radius: 0.375rem;
  margin-right: 1rem;
}

.info-box-content {
  flex: 1;
}

.info-box-text {
  display: block;
  font-size: 0.875rem;
  color: #6c757d;
  margin-bottom: 0.25rem;
}

.info-box-number {
  display: block;
  font-size: 1.25rem;
  font-weight: 600;
  color: #495057;
}
</style>
