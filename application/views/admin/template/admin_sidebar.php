<!--begin::Sidebar-->
<aside class="app-sidebar modern-sidebar" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?php echo base_url('admin/dashboard'); ?>" class="brand-link">
      <!--begin::Brand Image-->
      <div class="brand-image-container">
        <img
          src="<?php echo base_url('assets/img/logo.png'); ?>"
          alt="EasyClean Logo"
          class="brand-image"
        />
      </div>
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <div class="brand-text-container">
        <span class="brand-text">EasyClean</span>
        <span class="brand-subtitle">Admin Panel</span>
      </div>
      <!--end::Brand Text-->
    </a>
    <!--end::Brand Link-->
  </div>
  <!--end::Sidebar Brand-->
  
  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column modern-nav-menu"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false"
      >
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-tachometer-alt"></i>
            </div>
            <span class="nav-text">Dashboard</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">USER MANAGEMENT</li>

        <!-- All Users -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/users'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/users') !== false || strpos(uri_string(), 'admin/view_user') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-users"></i>
            </div>
            <span class="nav-text">All Users</span>
          </a>
        </li>

        <!-- Create User -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/create_user'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/create_user') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-user-plus"></i>
            </div>
            <span class="nav-text">Create User</span>
          </a>
        </li>

        <!-- Pending Reviews -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/pending_users'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/pending_users') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-user-clock"></i>
            </div>
            <span class="nav-text">Pending Reviews</span>
            <?php if (isset($pending_users_count) && $pending_users_count > 0): ?>
              <span class="badge bg-warning text-dark ms-auto"><?php echo $pending_users_count; ?></span>
            <?php endif; ?>
          </a>
        </li>

        <!-- Rejected Users -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/rejected_users'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/rejected_users') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-user-times"></i>
            </div>
            <span class="nav-text">Rejected Users</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">JOB MANAGEMENT</li>

        <!-- All Jobs -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/jobs'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'admin/jobs') ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-clipboard-list"></i>
            </div>
            <span class="nav-text">All Jobs</span>
          </a>
        </li>

        <!-- Flag Management -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/flags'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/flags') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-flag"></i>
            </div>
            <span class="nav-text">Flag Management</span>
          </a>
        </li>

        <!-- Recalled Jobs -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/recalled-jobs'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/recalled-jobs') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-exclamation-triangle"></i>
            </div>
            <span class="nav-text">Recalled Jobs</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">USER PANELS</li>

        <!-- Host Panel -->
        <li class="nav-item">
          <a href="<?php echo base_url('host'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'host') !== false && strpos(uri_string(), 'admin') === false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-home"></i>
            </div>
            <span class="nav-text">Host Panel</span>
          </a>
        </li>

        <!-- Cleaner Panel -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner') !== false && strpos(uri_string(), 'admin') === false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-broom"></i>
            </div>
            <span class="nav-text">Cleaner Panel</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">PROFILES</li>

        <!-- My Profile -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/profile'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/profile') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-user-circle"></i>
            </div>
            <span class="nav-text">My Profile</span>
          </a>
        </li>

        <!-- All Profiles -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/profiles'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'admin/profiles') ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-id-card"></i>
            </div>
            <span class="nav-text">All Profiles</span>
          </a>
        </li>

        <!-- Profile Statistics -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/profile-statistics'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/profile-statistics') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-chart-bar"></i>
            </div>
            <span class="nav-text">Profile Statistics</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">ACCOUNT</li>

        <!-- Change Password -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/change_password'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/change_password') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-key"></i>
            </div>
            <span class="nav-text">Change Password</span>
          </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
          <a href="<?php echo base_url('app/logout'); ?>" class="nav-link modern-nav-link logout-link" onclick="return confirm('Are you sure you want to logout?')">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-sign-out-alt"></i>
            </div>
            <span class="nav-text">Logout</span>
          </a>
        </li>

      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

<style>
/* Modern Sidebar Styles */
.modern-sidebar {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
  backdrop-filter: blur(10px);
}

.sidebar-brand {
  padding: 1.5rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  margin-bottom: 1rem;
}

.brand-link {
  display: flex;
  align-items: center;
  text-decoration: none;
  color: white;
  transition: all 0.3s ease;
}

.brand-link:hover {
  color: white;
  text-decoration: none;
  transform: scale(1.02);
}

.brand-image-container {
  width: 40px;
  height: 40px;
  margin-right: 1rem;
  border-radius: 50%;
  overflow: hidden;
  background: rgba(255, 255, 255, 0.1);
  display: flex;
  align-items: center;
  justify-content: center;
}

.brand-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.brand-text-container {
  display: flex;
  flex-direction: column;
}

.brand-text {
  font-size: 1.2rem;
  font-weight: 700;
  line-height: 1.2;
}

.brand-subtitle {
  font-size: 0.8rem;
  opacity: 0.8;
  font-weight: 500;
}

.modern-nav-header {
  color: rgba(255, 255, 255, 0.7);
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 1px;
  padding: 1rem 1.5rem 0.5rem;
  margin-bottom: 0.5rem;
}

.modern-nav-menu {
  padding: 0 1rem;
}

.modern-nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: rgba(255, 255, 255, 0.8);
  text-decoration: none;
  border-radius: 12px;
  margin-bottom: 0.25rem;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.modern-nav-link::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
  transition: left 0.5s ease;
}

.modern-nav-link:hover::before {
  left: 100%;
}

.modern-nav-link:hover {
  background: rgba(255, 255, 255, 0.1);
  color: white;
  text-decoration: none;
  transform: translateX(5px);
}

.modern-nav-link.active {
  background: rgba(255, 255, 255, 0.15);
  color: white;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.nav-icon-container {
  width: 20px;
  height: 20px;
  margin-right: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.nav-icon {
  font-size: 1rem;
}

.nav-text {
  font-weight: 500;
  font-size: 0.9rem;
}

.logout-link {
  margin-top: 1rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  padding-top: 1rem;
}

.logout-link:hover {
  background: rgba(220, 53, 69, 0.2);
  color: #ff6b7a;
}

/* Mobile responsive */
@media (max-width: 991.98px) {
  .app-sidebar.modern-sidebar {
    transform: translateX(-100%) !important;
    transition: transform 0.3s ease !important;
    z-index: 1050 !important;
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    height: 100vh !important;
    width: 280px !important;
    overflow-y: auto !important;
  }
  
  .app-sidebar.modern-sidebar.show {
    transform: translateX(0) !important;
  }
  
  /* Ensure sidebar is above other content */
  .app-sidebar {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    height: 100vh !important;
    width: 280px !important;
    z-index: 1050 !important;
    overflow-y: auto !important;
  }
  
  /* Make sure the modern sidebar gradient shows */
  .app-sidebar.modern-sidebar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }
}

/* Sidebar Wrapper - Enable Scrolling */
.sidebar-wrapper {
  height: calc(100vh - 120px) !important;
  height: calc(-webkit-fill-available - 120px) !important;
  overflow-y: auto !important;
  overflow-x: hidden !important;
  padding-right: 0.5rem;
}

/* Custom scrollbar */
.sidebar-wrapper::-webkit-scrollbar {
  width: 4px;
}

.sidebar-wrapper::-webkit-scrollbar-track {
  background: rgba(255, 255, 255, 0.1);
}

.sidebar-wrapper::-webkit-scrollbar-thumb {
  background: rgba(255, 255, 255, 0.3);
  border-radius: 2px;
}

.sidebar-wrapper::-webkit-scrollbar-thumb:hover {
  background: rgba(255, 255, 255, 0.5);
}
</style>

<script>
$(document).ready(function() {
    // Handle submenu functionality
    $('.nav-item.has-submenu .modern-nav-link').on('click', function(e) {
        e.preventDefault();
        
        var $submenu = $(this).next('.modern-submenu');
        var $arrow = $(this).find('.nav-arrow');
        
        // Close other submenus
        $('.modern-submenu').not($submenu).slideUp(200);
        $('.nav-arrow').not($arrow).removeClass('rotated');
        
        // Toggle current submenu
        $submenu.slideToggle(200);
        $arrow.toggleClass('rotated');
    });
    
    // Add ripple effect to nav links
    $('.modern-nav-link').on('click', function(e) {
        var $link = $(this);
        var $ripple = $('<span class="ripple"></span>');
        
        $link.append($ripple);
        
        setTimeout(function() {
            $ripple.remove();
        }, 600);
    });
    
    // Close sidebar on mobile when clicking a nav link
    $('.modern-nav-link').on('click', function() {
        if ($(window).width() <= 991.98) {
            $('.app-sidebar').removeClass('show');
            $('.sidebar-overlay').removeClass('show');
        }
    });
    
    // Mobile sidebar toggle, overlay click, and window resize are handled in layout_with_sidebar.php to avoid conflicts
});
</script>

