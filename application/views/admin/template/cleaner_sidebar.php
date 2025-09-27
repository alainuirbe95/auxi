<!--begin::Sidebar-->
<aside class="app-sidebar modern-sidebar" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <!--begin::Brand Link-->
    <a href="<?php echo base_url('cleaner'); ?>" class="brand-link">
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
        <span class="brand-subtitle">Cleaner Panel</span>
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
          <a href="<?php echo base_url('cleaner'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'cleaner') ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-tachometer-alt"></i>
            </div>
            <span class="nav-text">Dashboard</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">FIND WORK</li>

        <!-- Browse Jobs -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/jobs'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/jobs') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-search"></i>
            </div>
            <span class="nav-text">Browse Jobs</span>
          </a>
        </li>

        <!-- My Offers -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/offers'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/offers') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-handshake"></i>
            </div>
            <span class="nav-text">My Offers</span>
          </a>
        </li>

        <!-- Declined Offers -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/rejected_offers'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/rejected_offers') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-times-circle"></i>
            </div>
            <span class="nav-text">Declined Offers</span>
          </a>
        </li>

        <!-- Ignored Jobs -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/ignored_jobs'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/ignored_jobs') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-eye-slash"></i>
            </div>
            <span class="nav-text">Ignored Jobs</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">MY WORK</li>

        <!-- Assigned Jobs -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/assigned_jobs'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/assigned_jobs') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-clipboard-check"></i>
            </div>
            <span class="nav-text">Assigned Jobs</span>
          </a>
        </li>

            <!-- Jobs in Progress -->
            <li class="nav-item">
              <a href="<?php echo base_url('cleaner/jobs-in-progress'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'jobs-in-progress') !== false) ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-tasks"></i>
                </div>
                <span class="nav-text">Jobs in Progress</span>
              </a>
            </li>

            <!-- My Disputed Jobs -->
            <li class="nav-item">
              <a href="<?php echo base_url('cleaner/my-disputed-jobs'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'my-disputed-jobs') !== false) ? 'active' : ''; ?>">
                <div class="nav-icon-container">
                  <i class="nav-icon fas fa-exclamation-triangle"></i>
                </div>
                <span class="nav-text">My Disputed Jobs</span>
              </a>
            </li>

        <!-- Completed Jobs -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/completed'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/completed') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-check-circle"></i>
            </div>
            <span class="nav-text">Completed Jobs</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">EARNINGS</li>

        <!-- Earnings -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/earnings'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/earnings') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-dollar-sign"></i>
            </div>
            <span class="nav-text">My Earnings</span>
          </a>
        </li>

        <!-- Payment History -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/payments'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/payments') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-credit-card"></i>
            </div>
            <span class="nav-text">Payment History</span>
          </a>
        </li>

        <!-- Divider -->
        <li class="nav-header modern-nav-header">ACCOUNT</li>

        <!-- Profile -->
        <li class="nav-item">
          <a href="<?php echo base_url('cleaner/profile'); ?>" class="nav-link modern-nav-link <?php echo (strpos(uri_string(), 'cleaner/profile') !== false) ? 'active' : ''; ?>">
            <div class="nav-icon-container">
              <i class="nav-icon fas fa-user-circle"></i>
            </div>
            <span class="nav-text">My Profile</span>
          </a>
        </li>

        <!-- Change Password -->
        <li class="nav-item">
          <a href="<?php echo base_url('user-profile/change_password'); ?>" class="nav-link modern-nav-link <?php echo (uri_string() == 'user-profile/change_password') ? 'active' : ''; ?>">
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
  .modern-sidebar {
    transform: translateX(-100%);
    transition: transform 0.3s ease;
  }
  
  .modern-sidebar.show {
    transform: translateX(0);
  }
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
            $('.modern-sidebar').removeClass('show');
            $('.sidebar-overlay').removeClass('show');
        }
    });
});
</script>
