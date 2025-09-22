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
/* Modern Header Styles */
.modern-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 0.75rem 0;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
    z-index: 1000;
}

.modern-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: headerFloat 8s ease-in-out infinite;
}

@keyframes headerFloat {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-10px) rotate(180deg); }
}

.header-content {
    position: relative;
    z-index: 2;
}

.header-left {
    display: flex;
    align-items: center;
}

.mobile-menu-btn {
    background: rgba(255, 255, 255, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    border-radius: 8px;
    padding: 0.5rem;
    margin-right: 1rem;
    transition: all 0.3s ease;
    display: none;
    cursor: pointer;
    z-index: 1001;
    position: relative;
}

.mobile-menu-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    color: white;
    transform: scale(1.05);
}

.page-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
}

.page-title i {
    margin-right: 0.5rem;
    font-size: 1.2rem;
}

.header-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-info {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.15);
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.user-info:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-1px);
}

.user-info i {
    margin-right: 0.4rem;
    font-size: 0.9rem;
}

.user-name {
    font-weight: 500;
    font-size: 0.85rem;
}

.logout-btn {
    background: rgba(220, 53, 69, 0.9);
    border: 1px solid rgba(220, 53, 69, 0.3);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.8rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    backdrop-filter: blur(10px);
}

.logout-btn:hover {
    background: rgba(220, 53, 69, 1);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.logout-btn i {
    margin-right: 0.4rem;
}

.breadcrumb-container {
    background: rgba(255, 255, 255, 0.1);
    padding: 0.4rem 0.8rem;
    border-radius: 18px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.breadcrumb {
    background: none;
    padding: 0;
    margin: 0;
    display: flex;
    align-items: center;
    list-style: none;
}

.breadcrumb-item {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.8rem;
}

.breadcrumb-item:not(:last-child)::after {
    content: '/';
    margin: 0 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

.breadcrumb-item a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: none;
    transition: all 0.3s ease;
}

.breadcrumb-item a:hover {
    color: white;
    text-decoration: none;
}

.breadcrumb-item.active {
    color: white;
    font-weight: 500;
}

/* Responsive Design */
@media (max-width: 991.98px) {
    .mobile-menu-btn {
        display: block !important;
    }
    
    .page-title {
        font-size: 1.2rem;
    }
    
    .header-right {
        gap: 0.75rem;
    }
    
    .user-info {
        padding: 0.3rem 0.6rem;
    }
    
    .logout-btn {
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
    }
    
    .breadcrumb-container {
        padding: 0.3rem 0.6rem;
    }
}

@media (max-width: 768px) {
    .modern-header {
        padding: 0.5rem 0;
    }
    
    .page-title {
        font-size: 1.1rem;
    }
    
    .page-title i {
        font-size: 1rem;
        margin-right: 0.4rem;
    }
    
    .header-right {
        flex-direction: column;
        gap: 0.5rem;
        align-items: flex-end;
    }
    
    .user-info {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    
    .logout-btn {
        font-size: 0.75rem;
        padding: 0.25rem 0.6rem;
    }
    
    .breadcrumb-container {
        padding: 0.3rem 0.6rem;
    }
    
    .breadcrumb-item {
        font-size: 0.75rem;
    }
}

@media (max-width: 576px) {
    .header-left {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.4rem;
    }
    
    .mobile-menu-btn {
        margin-right: 0;
        margin-bottom: 0.4rem;
    }
    
    .page-title {
        font-size: 1rem;
    }
    
    .header-right {
        width: 100%;
        justify-content: space-between;
        flex-direction: row;
        gap: 0.4rem;
    }
    
    .user-info {
        font-size: 0.75rem;
        padding: 0.2rem 0.4rem;
    }
    
    .logout-btn {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }
}
</style>

<!-- Modern Header -->
<div class="modern-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="header-left">
                    <button type="button" class="mobile-menu-btn d-lg-none" id="mobileSidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">
                        <i class="fas fa-<?php echo isset($page_icon) ? $page_icon : 'tachometer-alt'; ?>"></i>
                        <?php echo isset($title) ? $title : 'Dashboard'; ?>
                    </h1>
                </div>
            </div>
            <div class="col-md-6">
                <div class="header-right">
                    <!-- User Info -->
                    <div class="user-info">
                        <i class="fas fa-user-circle"></i>
                        <span class="user-name">Welcome, <?php echo $this->session->userdata('username') ?: 'Admin'; ?></span>
                    </div>
                    
                    <!-- Logout Button -->
                    <a href="<?php echo base_url('app/logout'); ?>" 
                       class="logout-btn" 
                       onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt"></i>Logout
                    </a>
                    
                    <!-- Breadcrumbs -->
                    <div class="breadcrumb-container">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="<?php echo base_url('admin/dashboard'); ?>">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                                <?php foreach ($breadcrumbs as $breadcrumb): ?>
                                    <li class="breadcrumb-item <?php echo isset($breadcrumb['active']) && $breadcrumb['active'] ? 'active' : ''; ?>">
                                        <?php if (isset($breadcrumb['url']) && !isset($breadcrumb['active'])): ?>
                                            <a href="<?php echo $breadcrumb['url']; ?>"><?php echo $breadcrumb['title']; ?></a>
                                        <?php else: ?>
                                            <?php echo $breadcrumb['title']; ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    console.log('Header script loaded');
    console.log('Button element:', $('#mobileSidebarToggle').length);
    console.log('Sidebar element:', $('.app-sidebar').length);
    console.log('Overlay element:', $('.sidebar-overlay').length);
    
    // Mobile sidebar toggle
    $(document).on('click', '#mobileSidebarToggle', function(e) {
        e.preventDefault();
        console.log('Mobile toggle clicked');
        
        var $sidebar = $('.app-sidebar');
        var $overlay = $('.sidebar-overlay');
        
        console.log('Before toggle - Sidebar classes:', $sidebar.attr('class'));
        console.log('Before toggle - Overlay classes:', $overlay.attr('class'));
        console.log('Before toggle - Sidebar has show class:', $sidebar.hasClass('show'));
        console.log('Before toggle - Overlay has show class:', $overlay.hasClass('show'));
        
        $sidebar.toggleClass('show');
        $overlay.toggleClass('show');
        
        console.log('After toggle - Sidebar classes:', $sidebar.attr('class'));
        console.log('After toggle - Overlay classes:', $overlay.attr('class'));
        console.log('After toggle - Sidebar has show class:', $sidebar.hasClass('show'));
        console.log('After toggle - Overlay has show class:', $overlay.hasClass('show'));
        
        // Force visibility for testing
        if ($sidebar.hasClass('show')) {
            $sidebar.css({
                'transform': 'translateX(0)',
                'z-index': '1050',
                'position': 'fixed'
            });
            $overlay.css({
                'display': 'block',
                'opacity': '1'
            });
        } else {
            $sidebar.css({
                'transform': 'translateX(-100%)',
                'z-index': '1000'
            });
            $overlay.css({
                'display': 'none',
                'opacity': '0'
            });
        }
    });
    
    // Close sidebar when clicking overlay
    $('.sidebar-overlay').on('click', function() {
        var $sidebar = $('.app-sidebar');
        var $overlay = $('.sidebar-overlay');
        
        $sidebar.removeClass('show');
        $overlay.removeClass('show');
        
        // Force CSS properties
        $sidebar.css({
            'transform': 'translateX(-100%)',
            'z-index': '1000'
        });
        $overlay.css({
            'display': 'none',
            'opacity': '0'
        });
    });
    
    // Close sidebar when clicking outside on mobile
    $(document).on('click', function(e) {
        if ($(window).width() <= 991) {
            if (!$(e.target).closest('.app-sidebar, #mobileSidebarToggle').length) {
                var $sidebar = $('.app-sidebar');
                var $overlay = $('.sidebar-overlay');
                
                $sidebar.removeClass('show');
                $overlay.removeClass('show');
                
                // Force CSS properties
                $sidebar.css({
                    'transform': 'translateX(-100%)',
                    'z-index': '1000'
                });
                $overlay.css({
                    'display': 'none',
                    'opacity': '0'
                });
            }
        }
    });
    
    // Add animation to header elements
    $('.user-info, .logout-btn, .breadcrumb-container').each(function(index) {
        $(this).css('opacity', '0').delay(index * 100).animate({
            opacity: 1
        }, 500);
    });
});
</script>
