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

        <!-- Users Management -->
        <li class="nav-item has-submenu">
          <a href="#" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'admin/users') !== false || strpos(uri_string(), 'admin/view_user') !== false || strpos(uri_string(), 'admin/create_user') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-users"></i>
            </div>
            <span class="nav-text">Users Management</span>
            <div class="nav-arrow-container">
              <i class="nav-arrow fas fa-chevron-right"></i>
            </div>
          </a>
          <ul class="nav nav-treeview modern-submenu">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/users'); ?>" class="nav-link modern-submenu-link <?php echo (strpos(uri_string(), 'admin/users') !== false || strpos(uri_string(), 'admin/view_user') !== false) ? 'active' : ''; ?>">
                <div class="submenu-icon">
                  <i class="fas fa-list"></i>
                </div>
                <span>View All Users</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/create_user'); ?>" class="nav-link modern-submenu-link <?php echo (strpos(uri_string(), 'admin/create_user') !== false) ? 'active' : ''; ?>">
                <div class="submenu-icon">
                  <i class="fas fa-user-plus"></i>
                </div>
                <span>Create New User</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/pending_users'); ?>" class="nav-link modern-submenu-link <?php echo (strpos(uri_string(), 'admin/pending_users') !== false) ? 'active' : ''; ?>">
                <div class="submenu-icon">
                  <i class="fas fa-user-clock"></i>
                </div>
                <span>Pending Reviews</span>
                <?php if (isset($pending_users_count) && $pending_users_count > 0): ?>
                  <span class="modern-badge"><?php echo $pending_users_count; ?></span>
                <?php endif; ?>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/rejected_users'); ?>" class="nav-link modern-submenu-link <?php echo (strpos(uri_string(), 'admin/rejected_users') !== false) ? 'active' : ''; ?>">
                <div class="submenu-icon">
                  <i class="fas fa-user-times"></i>
                </div>
                <span>Rejected Users</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Content Management -->
        <li class="nav-item">
          <a href="#" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-clipboard-list"></i>
            </div>
            <span class="nav-text">Content Management</span>
          </a>
        </li>

        <!-- Reports & Analytics -->
        <li class="nav-item">
          <a href="#" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-chart-line"></i>
            </div>
            <span class="nav-text">Reports & Analytics</span>
          </a>
        </li>

        <!-- System Settings -->
        <li class="nav-item">
          <a href="#" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-cogs"></i>
            </div>
            <span class="nav-text">System Settings</span>
          </a>
        </li>

        <!-- Media Management -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/media'); ?>" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-images"></i>
            </div>
            <span class="nav-text">Media Management</span>
          </a>
        </li>

        <!-- Backup & Maintenance -->
        <li class="nav-item">
          <a href="#" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-shield-check"></i>
            </div>
            <span class="nav-text">Backup & Maintenance</span>
          </a>
        </li>

          <!-- Divider -->
          <li class="nav-header modern-nav-header">JOB MANAGEMENT</li>

          <!-- Job Management -->
          <li class="nav-item">
            <a href="<?php echo base_url('admin/jobs'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'admin/jobs') ? 'active' : ''; ?>">
              <div class="nav-icon-container">
                <i class="nav-icon fas fa-clipboard-list"></i>
              </div>
              <span class="nav-text">All Jobs</span>
            </a>
          </li>

          <!-- Divider -->
          <li class="nav-header modern-nav-header">MARKETPLACE</li>

        <!-- Host Management -->
        <li class="nav-item has-submenu">
          <a href="#" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'host') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-home"></i>
            </div>
            <span class="nav-text">Host Panel</span>
            <i class="nav-arrow fas fa-chevron-right"></i>
          </a>
          <ul class="modern-submenu">
            <li class="nav-item">
              <a href="<?php echo base_url('host'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'host') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                </div>
                <span class="nav-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('host/create_job'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'host/create_job') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-plus-circle"></i>
                </div>
                <span class="nav-text">Create Job</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('host/jobs'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'host/jobs') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-clipboard-list"></i>
                </div>
                <span class="nav-text">My Jobs</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('host/offers'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'host/offers') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-handshake"></i>
                </div>
                <span class="nav-text">Review Offers</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Cleaner Management -->
        <li class="nav-item has-submenu">
          <a href="#" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-broom"></i>
            </div>
            <span class="nav-text">Cleaner Panel</span>
            <i class="nav-arrow fas fa-chevron-right"></i>
          </a>
          <ul class="modern-submenu">
            <li class="nav-item">
              <a href="<?php echo base_url('cleaner'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'cleaner') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-tachometer-alt"></i>
                </div>
                <span class="nav-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('cleaner/jobs'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'cleaner/jobs') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-search"></i>
                </div>
                <span class="nav-text">Browse Jobs</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('cleaner/offers'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'cleaner/offers') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-handshake"></i>
                </div>
                <span class="nav-text">My Offers</span>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('cleaner/earnings'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'cleaner/earnings') ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-dollar-sign"></i>
                </div>
                <span class="nav-text">Earnings</span>
              </a>
            </li>
          </ul>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">ADMINISTRATION</li>

        <!-- User Profile -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/profile'); ?>" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-user-circle"></i>
            </div>
            <span class="nav-text">My Profile</span>
          </a>
        </li>

        <!-- Change Password -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/change_password'); ?>" class="nav-link modern-nav-link">
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

        <!-- Divider -->
        <li class="nav-header modern-nav-header">QUICK ACTIONS</li>

        <!-- Add New Post -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/posts/add'); ?>" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-plus-circle"></i>
            </div>
            <span class="nav-text">Add New Post</span>
          </a>
        </li>

        <!-- System Status -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/system-status'); ?>" class="nav-link modern-nav-link">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-heartbeat"></i>
            </div>
            <span class="nav-text">System Status</span>
            <span class="modern-status-badge">Online</span>
          </a>
        </li>

      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->

<script>
$(document).ready(function() {
    console.log('Sidebar script loaded');
    
    // Handle sidebar menu dropdowns - more specific targeting
    $('.nav-item.has-submenu .modern-nav-link').on('click', function(e) {
        e.preventDefault();
        console.log('Submenu link clicked');
        
        var $this = $(this);
        var $parent = $this.parent();
        var $submenu = $parent.find('.modern-submenu');
        var $arrow = $this.find('.nav-arrow');
        
        console.log('Submenu element:', $submenu.length);
        
        if ($submenu.length > 0) {
            if ($submenu.is(':visible')) {
                console.log('Hiding submenu');
                $submenu.slideUp(300);
                $arrow.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            } else {
                console.log('Showing submenu');
                $submenu.slideDown(300);
                $arrow.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            }
        }
    });
    
    // Auto-expand active submenu on page load
    $('.nav-item').each(function() {
        var $this = $(this);
        var $submenu = $this.find('.modern-submenu');
        var $activeLink = $submenu.find('.modern-submenu-link.active');
        
        if ($activeLink.length > 0) {
            console.log('Auto-expanding submenu for active link');
            $submenu.show();
            $this.find('.nav-arrow').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        }
    });
    
    // Add hover effects for better UX
    $('.modern-nav-link').hover(
        function() {
            $(this).addClass('hover-effect');
        },
        function() {
            $(this).removeClass('hover-effect');
        }
    );
    
    // Add click ripple effect (only for non-submenu links)
    $('.modern-nav-link').on('click', function(e) {
        var $this = $(this);
        var $parent = $this.parent();
        var $submenu = $parent.find('.modern-submenu');
        
        // Only add ripple if it's not a submenu toggle
        if ($submenu.length === 0) {
            var $ripple = $('<span class="ripple"></span>');
            var rect = this.getBoundingClientRect();
            var size = Math.max(rect.width, rect.height);
            var x = e.clientX - rect.left - size / 2;
            var y = e.clientY - rect.top - size / 2;
            
            $ripple.css({
                width: size,
                height: size,
                left: x,
                top: y
            });
            
            $this.append($ripple);
            
            setTimeout(function() {
                $ripple.remove();
            }, 600);
        }
    });
    
    // Close sidebar on mobile when clicking a navigation link
    $('.modern-nav-link').on('click', function() {
        if ($(window).width() <= 991) {
            var $this = $(this);
            var $parent = $this.parent();
            var $submenu = $parent.find('.modern-submenu');
            
            // Only close if it's not a submenu toggle
            if ($submenu.length === 0) {
                setTimeout(function() {
                    $('.app-sidebar').removeClass('show');
                    $('.sidebar-overlay').removeClass('show');
                }, 300);
            }
        }
    });
});
</script>

<style>
/* Modern Sidebar Styles */
.modern-sidebar {
  position: fixed !important;
  top: 0 !important;
  left: 0 !important;
  height: 100vh !important;
  width: 250px !important;
  z-index: 1000 !important;
  overflow-y: auto !important;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  box-shadow: 4px 0 20px rgba(102, 126, 234, 0.3) !important;
  border-right: none !important;
  position: relative;
  overflow: hidden;
}

.modern-sidebar::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
  animation: sidebarFloat 10s ease-in-out infinite;
}

@keyframes sidebarFloat {
  0%, 100% { transform: translateY(0px) rotate(0deg); }
  50% { transform: translateY(-15px) rotate(180deg); }
}

/* Brand Section */
.sidebar-brand {
  position: relative;
  z-index: 2;
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
  margin-bottom: 1rem;
}

.brand-link {
  display: flex !important;
  align-items: center !important;
  padding: 1.5rem 1rem !important;
  text-decoration: none !important;
  color: white !important;
  transition: all 0.3s ease;
}

.brand-link:hover {
  color: white !important;
  text-decoration: none !important;
  transform: translateY(-2px);
}

.brand-image-container {
  position: relative;
  margin-right: 1rem;
}

.brand-image {
  width: 40px !important;
  height: 40px !important;
  border-radius: 50% !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2) !important;
  transition: all 0.3s ease;
}

.brand-image:hover {
  transform: scale(1.1);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
}

.brand-text-container {
  display: flex;
  flex-direction: column;
}

.brand-text {
  font-size: 1.3rem !important;
  font-weight: 700 !important;
  color: white !important;
  margin: 0 !important;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.brand-subtitle {
  font-size: 0.8rem !important;
  font-weight: 400 !important;
  color: rgba(255, 255, 255, 0.8) !important;
  margin: 0 !important;
  text-transform: uppercase;
  letter-spacing: 1px;
}

/* Sidebar Wrapper */
.sidebar-wrapper {
  position: relative;
  z-index: 2;
  padding: 0 !important;
  height: calc(100vh - 120px) !important;
  overflow-y: auto !important;
}

/* Navigation Menu */
.modern-nav-menu {
  padding: 0 0.5rem !important;
}

.nav-item {
  margin-bottom: 0.25rem !important;
}

/* Modern Nav Links */
.modern-nav-link {
  display: flex !important;
  align-items: center !important;
  padding: 0.75rem 1rem !important;
  text-decoration: none !important;
  color: rgba(255, 255, 255, 0.9) !important;
  border-radius: 12px !important;
  margin: 0.25rem 0 !important;
  transition: all 0.3s ease !important;
  position: relative !important;
  overflow: hidden !important;
}

.modern-nav-link::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
  transition: left 0.5s ease;
}

.modern-nav-link:hover::before {
  left: 100%;
}

.modern-nav-link:hover {
  background: rgba(255, 255, 255, 0.15) !important;
  color: white !important;
  text-decoration: none !important;
  transform: translateX(5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.modern-nav-link.active {
  background: rgba(255, 255, 255, 0.2) !important;
  color: white !important;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
  border-left: 4px solid rgba(255, 255, 255, 0.8);
}

/* Icon Container */
.nav-icon-container {
  width: 40px;
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 10px;
  margin-right: 0.75rem;
  transition: all 0.3s ease;
}

.modern-nav-link:hover .nav-icon-container {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.1);
}

.nav-icon {
  font-size: 1.1rem !important;
  color: rgba(255, 255, 255, 0.9) !important;
}

.nav-text {
  font-size: 0.95rem !important;
  font-weight: 500 !important;
  flex: 1;
}

/* Arrow Container */
.nav-arrow-container {
  margin-left: auto;
  transition: all 0.3s ease;
}

.nav-arrow {
  font-size: 0.8rem !important;
  color: rgba(255, 255, 255, 0.7) !important;
  transition: all 0.3s ease !important;
}

.nav-arrow.fa-chevron-down {
  transform: rotate(90deg) !important;
}

/* Submenu Styling */
.modern-submenu {
  display: none;
  padding-left: 0 !important;
  margin: 0.5rem 0 0 0 !important;
  background: rgba(0, 0, 0, 0.1);
  border-radius: 8px;
  overflow: hidden;
}

.modern-submenu.show {
  display: block !important;
}

.modern-submenu-link {
  display: flex !important;
  align-items: center !important;
  padding: 0.6rem 1rem 0.6rem 2.5rem !important;
  text-decoration: none !important;
  color: rgba(255, 255, 255, 0.8) !important;
  font-size: 0.9rem !important;
  transition: all 0.3s ease !important;
  position: relative !important;
}

.modern-submenu-link::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  width: 3px;
  height: 100%;
  background: transparent;
  transition: all 0.3s ease;
}

.modern-submenu-link:hover {
  background: rgba(255, 255, 255, 0.1) !important;
  color: white !important;
  text-decoration: none !important;
  transform: translateX(5px);
}

.modern-submenu-link:hover::before {
  background: rgba(255, 255, 255, 0.8);
}

.modern-submenu-link.active {
  background: rgba(255, 255, 255, 0.15) !important;
  color: white !important;
}

.modern-submenu-link.active::before {
  background: white;
}

.submenu-icon {
  width: 30px;
  height: 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255, 255, 255, 0.1);
  border-radius: 6px;
  margin-right: 0.75rem;
  font-size: 0.9rem;
}

/* Headers */
.modern-nav-header {
  font-size: 0.75rem !important;
  font-weight: 700 !important;
  text-transform: uppercase !important;
  letter-spacing: 1px !important;
  margin: 1.5rem 0 0.75rem 0 !important;
  padding: 0.5rem 1rem !important;
  color: rgba(255, 255, 255, 0.6) !important;
  background: rgba(0, 0, 0, 0.1);
  border-radius: 6px;
  text-align: center;
}

/* Badges */
.modern-badge {
  background: rgba(255, 193, 7, 0.9) !important;
  color: #000 !important;
  font-size: 0.7rem !important;
  font-weight: 600 !important;
  padding: 0.25rem 0.5rem !important;
  border-radius: 12px !important;
  margin-left: auto !important;
  box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.modern-status-badge {
  background: rgba(40, 167, 69, 0.9) !important;
  color: white !important;
  font-size: 0.7rem !important;
  font-weight: 600 !important;
  padding: 0.25rem 0.5rem !important;
  border-radius: 12px !important;
  margin-left: auto !important;
  box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

/* Logout Link Special Styling */
.logout-link {
  background: rgba(220, 53, 69, 0.2) !important;
  border: 1px solid rgba(220, 53, 69, 0.3) !important;
}

.logout-link:hover {
  background: rgba(220, 53, 69, 0.3) !important;
  border-color: rgba(220, 53, 69, 0.5) !important;
}

/* Ripple Effect */
.ripple {
  position: absolute;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: scale(0);
  animation: ripple-animation 0.6s linear;
  pointer-events: none;
}

@keyframes ripple-animation {
  to {
    transform: scale(4);
    opacity: 0;
  }
}

/* Hover Effect */
.hover-effect {
  transform: translateX(8px) !important;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25) !important;
}

/* Sidebar Overlay */
.sidebar-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
  display: none;
  transition: opacity 0.3s ease;
  opacity: 0;
}

.sidebar-overlay.show {
  display: block !important;
  opacity: 1;
}

/* Responsive Design */
@media (max-width: 991.98px) {
  .modern-sidebar {
    transform: translateX(-100%) !important;
    transition: transform 0.3s ease !important;
  }
  
  .modern-sidebar.show {
    transform: translateX(0) !important;
  }
}

@media (max-width: 991.98px) and (min-width: 768px) {
  .modern-sidebar {
    width: 200px !important;
  }
  
  .modern-nav-link {
    padding: 0.6rem 0.75rem !important;
  }
  
  .brand-text {
    font-size: 1.1rem !important;
  }
  
  .brand-subtitle {
    font-size: 0.7rem !important;
  }
}

@media (max-width: 767.98px) {
  .modern-sidebar {
    width: 280px !important;
    z-index: 1050 !important;
  }
  
  .modern-nav-link {
    padding: 0.8rem 1rem !important;
  }
  
  .brand-text {
    font-size: 1.2rem !important;
  }
  
  .modern-nav-header {
    font-size: 0.8rem !important;
    margin: 1.5rem 0 1rem 0 !important;
  }
}

@media (max-width: 575.98px) {
  .modern-sidebar {
    width: 100% !important;
  }
  
  .sidebar-brand {
    padding: 1rem 0.75rem !important;
  }
  
  .modern-nav-link {
    padding: 1rem 0.75rem !important;
  }
  
  .modern-nav-header {
    padding: 0.5rem 0.75rem !important;
  }
}

/* Scrollbar Styling */
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
