<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?php echo base_url('admin/dashboard'); ?>" class="brand-link">
      <!--begin::Brand Image-->
      <img
        src="<?php echo base_url('assets/img/logo.png'); ?>"
        alt="EasyClean Logo"
        class="brand-image opacity-75 shadow"
      />
      <!--end::Brand Image-->
      <!--begin::Brand Text-->
      <span class="brand-text fw-light">EasyClean Admin</span>
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
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false"
      >
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link <?php echo (uri_string() == 'admin/dashboard') ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Users Management -->
        <li class="nav-item">
          <a href="#" class="nav-link <?php echo (strpos(uri_string(), 'admin/users') !== false || strpos(uri_string(), 'admin/view_user') !== false || strpos(uri_string(), 'admin/create_user') !== false) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-users"></i>
            <p>
              Users Management
              <i class="nav-arrow fas fa-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?php echo base_url('admin/users'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'admin/users') !== false || strpos(uri_string(), 'admin/view_user') !== false) ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-list"></i>
                <p>View All Users</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?php echo base_url('admin/create_user'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'admin/create_user') !== false) ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-user-plus"></i>
                <p>Create New User</p>
              </a>
            </li>
        <li class="nav-item">
          <a href="<?php echo base_url('admin/pending_users'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'admin/pending_users') !== false) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user-clock"></i>
            <p>Pending Reviews</p>
            <?php if (isset($pending_users_count) && $pending_users_count > 0): ?>
              <span class="badge badge-warning right"><?php echo $pending_users_count; ?></span>
            <?php endif; ?>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?php echo base_url('admin/rejected_users'); ?>" class="nav-link <?php echo (strpos(uri_string(), 'admin/rejected_users') !== false) ? 'active' : ''; ?>">
            <i class="nav-icon fas fa-user-times"></i>
            <p>Rejected Users</p>
          </a>
        </li>
          </ul>
        </li>

        <!-- Content Management -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-clipboard-list"></i>
            <p>Content Management</p>
          </a>
        </li>

        <!-- Reports & Analytics -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-chart-line"></i>
            <p>Reports & Analytics</p>
          </a>
        </li>

        <!-- System Settings -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>System Settings</p>
          </a>
        </li>

        <!-- Media Management -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/media'); ?>" class="nav-link">
            <i class="nav-icon fas fa-images"></i>
            <p>Media Management</p>
          </a>
        </li>

        <!-- Backup & Maintenance -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shield-check"></i>
            <p>Backup & Maintenance</p>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header">ADMINISTRATION</li>

        <!-- User Profile -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/profile'); ?>" class="nav-link">
            <i class="nav-icon fas fa-user-circle"></i>
            <p>My Profile</p>
          </a>
        </li>

        <!-- Logout -->
        <li class="nav-item">
          <a href="<?php echo base_url('app/logout'); ?>" class="nav-link" onclick="return confirm('Are you sure you want to logout?')">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>Logout</p>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header">QUICK ACTIONS</li>

        <!-- Add New Post -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/posts/add'); ?>" class="nav-link">
            <i class="nav-icon fas fa-plus-circle"></i>
            <p>Add New Post</p>
          </a>
        </li>


        <!-- System Status -->
        <li class="nav-item">
          <a href="<?php echo base_url('admin/system-status'); ?>" class="nav-link">
            <i class="nav-icon fas fa-heartbeat"></i>
            <p>
              System Status
              <span class="nav-badge badge text-bg-success me-3">Online</span>
            </p>
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
    // Handle sidebar menu dropdowns
    $('.nav-link').on('click', function(e) {
        var $this = $(this);
        var $parent = $this.parent();
        var $submenu = $parent.find('.nav-treeview');
        
        // If this link has a submenu
        if ($submenu.length > 0) {
            e.preventDefault();
            
            // Toggle the submenu
            $submenu.slideToggle(300);
            
            // Toggle the arrow direction
            var $arrow = $this.find('.nav-arrow');
            if ($arrow.hasClass('fa-chevron-right')) {
                $arrow.removeClass('fa-chevron-right').addClass('fa-chevron-down');
            } else {
                $arrow.removeClass('fa-chevron-down').addClass('fa-chevron-right');
            }
        }
    });
    
    // Auto-expand active submenu on page load
    $('.nav-item').each(function() {
        var $this = $(this);
        var $submenu = $this.find('.nav-treeview');
        var $activeLink = $submenu.find('.nav-link.active');
        
        if ($activeLink.length > 0) {
            $submenu.show();
            $this.find('.nav-arrow').removeClass('fa-chevron-right').addClass('fa-chevron-down');
        }
    });
});
</script>

<style>
/* Custom sidebar styles */
.app-sidebar {
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  width: 250px;
  z-index: 1000;
  overflow-y: auto;
  background-color: #f8f9fa !important;
  border-right: 1px solid #dee2e6;
}

.sidebar-brand .brand-link {
  display: flex;
  align-items: center;
  padding: 1rem;
  text-decoration: none;
  color: #495057 !important;
  border-bottom: 1px solid #dee2e6;
}

.brand-image {
  width: 33px;
  height: 33px;
  margin-right: 0.75rem;
}

.brand-text {
  font-size: 1.1rem;
  font-weight: 300;
  color: #495057 !important;
}

.sidebar-wrapper {
  padding: 0;
  height: calc(100vh - 70px);
  overflow-y: auto;
}

.nav-link {
  color: #495057 !important;
}

.nav-link.active {
  background-color: rgba(0, 123, 255, 0.1);
  color: #007bff !important;
}

.nav-link:hover {
  background-color: rgba(0, 123, 255, 0.05);
  color: #007bff !important;
}

.nav-header {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin: 1rem 0 0.5rem;
  padding: 0 1rem;
  color: #6c757d !important;
}

.nav-badge {
  font-size: 0.65rem;
  padding: 0.25rem 0.5rem;
}

/* Submenu styling */
.nav-treeview {
  display: none;
  padding-left: 0;
  margin: 0;
}

.nav-treeview .nav-item {
  margin: 0;
}

.nav-treeview .nav-link {
  padding: 0.5rem 1rem 0.5rem 2.5rem;
  font-size: 0.9rem;
  border-left: 3px solid transparent;
}

.nav-treeview .nav-link:hover {
  border-left-color: #007bff;
  background-color: rgba(0, 123, 255, 0.05);
}

.nav-treeview .nav-link.active {
  border-left-color: #007bff;
  background-color: rgba(0, 123, 255, 0.1);
  color: #007bff !important;
}

.nav-arrow {
  transition: transform 0.3s ease;
}

.nav-arrow.fa-chevron-down {
  transform: rotate(90deg);
}

/* Main content adjustments */
.main-content {
  padding-left: 0;
  padding-right: 0;
}

/* Responsive sidebar adjustments */
@media (max-width: 991.98px) {
  .app-sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }
  
  .app-sidebar.show {
    transform: translateX(0);
  }
}

/* Tablet adjustments */
@media (max-width: 991.98px) and (min-width: 768px) {
  .app-sidebar {
    width: 200px !important;
  }
  
  .nav-link {
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
  }
  
  .brand-text {
    font-size: 1rem;
  }
}

/* Mobile adjustments */
@media (max-width: 767.98px) {
  .app-sidebar {
    width: 280px !important;
    z-index: 1050;
  }
  
  .nav-link {
    padding: 1rem;
    font-size: 1rem;
  }
  
  .brand-text {
    font-size: 1.1rem;
  }
  
  .nav-header {
    font-size: 0.8rem;
    margin: 1.5rem 0 0.75rem;
  }
}

/* Small mobile adjustments */
@media (max-width: 575.98px) {
  .app-sidebar {
    width: 100% !important;
  }
  
  .sidebar-brand {
    padding: 1rem 0.75rem;
  }
  
  .nav-link {
    padding: 1rem 0.75rem;
  }
  
  .nav-header {
    padding: 0 0.75rem;
  }
}
</style>
