<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title . ' | ' : ''; ?>EasyClean Admin</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/adminlte.min.css'); ?>">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/admin/custom.css'); ?>">

    <!-- Layout CSS -->
    <style>
        /* Sidebar styles */
        .app-sidebar {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 250px !important;
            height: 100vh !important;
            z-index: 1000 !important;
            background-color: #f8f9fa !important;
            border-right: 1px solid #dee2e6 !important;
            transform: translateX(0) !important;
        }
        
        /* Content wrapper styles */
        .content-wrapper {
            margin-left: 250px !important;
            min-height: 100vh !important;
            position: relative !important;
            z-index: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }
        
        /* Main content centering */
        .content {
            flex: 1 !important;
            display: flex !important;
            flex-direction: column !important;
        }
        
        .container-fluid {
            max-width: 1200px !important;
            margin: 0 auto !important;
            padding: 0 20px !important;
        }
        
        /* Tablet responsive (768px - 991px) */
        @media (max-width: 991.98px) and (min-width: 768px) {
            .content-wrapper {
                margin-left: 0 !important;
            }
            
            .app-sidebar {
                transform: translateX(-100%) !important;
                width: 200px !important;
            }
            
            .app-sidebar.show {
                transform: translateX(0) !important;
            }
            
            /* Add mobile toggle button for tablet */
            .mobile-sidebar-toggle {
                display: block !important;
            }
            
            .container-fluid {
                max-width: 100% !important;
                padding: 0 15px !important;
            }
        }
        
        /* Mobile responsive (max-width: 767px) */
        @media (max-width: 767.98px) {
            .content-wrapper {
                margin-left: 0 !important;
                padding: 10px !important;
            }
            
            .app-sidebar {
                transform: translateX(-100%) !important;
                width: 280px !important;
                z-index: 1050 !important;
            }
            
            .app-sidebar.show {
                transform: translateX(0) !important;
            }
            
            /* Content adjustments for mobile */
            .content-header h1 {
                font-size: 1.5rem !important;
            }
            
            .breadcrumb {
                font-size: 0.875rem !important;
            }
            
            /* Add mobile toggle button */
            .mobile-sidebar-toggle {
                display: block !important;
            }
            
            .container-fluid {
                max-width: 100% !important;
                padding: 0 10px !important;
            }
            
            /* Overlay for mobile sidebar */
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 1040;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block !important;
            }
        }
        
        /* Small mobile (max-width: 575px) */
        @media (max-width: 575.98px) {
            .content-wrapper {
                padding: 5px !important;
            }
            
            .content-header {
                padding: 10px 0 !important;
            }
            
            .content-header h1 {
                font-size: 1.25rem !important;
            }
            
            .breadcrumb {
                display: none !important;
            }
            
            .app-sidebar {
                width: 100% !important;
            }
        }
        
        /* Ensure content area is properly styled */
        .content {
            position: relative;
            z-index: 1;
            background-color: #f8f9fa;
            padding: 15px;
        }
        
        /* Fix wrapper positioning */
        .wrapper {
            position: relative;
            min-height: 100vh;
        }
    </style>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/jquery-3.3.1.min.js'); ?>"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        
        <!-- Sidebar Overlay (Mobile) -->
        <div class="sidebar-overlay"></div>
        
        <!-- Sidebar -->
        <?php echo isset($sidebar) ? $sidebar : ''; ?>
        
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Mobile Sidebar Toggle Button -->
            <button class="mobile-sidebar-toggle" style="display: none; position: fixed; top: 10px; left: 10px; z-index: 1051; background: #007bff; color: white; border: none; border-radius: 4px; padding: 8px 12px; font-size: 14px;">
                <i class="fas fa-bars"></i>
            </button>
            
            <!-- Modern Header -->
            <?php 
            $this->load->view("admin/template/modern_header", array(
                'title' => isset($title) ? $title : 'Dashboard',
                'page_icon' => isset($page_icon) ? $page_icon : 'tachometer-alt',
                'breadcrumbs' => isset($breadcrumbs) ? $breadcrumbs : array()
            ));
            ?>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php echo isset($body) ? $body : ''; ?>
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#">EasyClean</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    <!-- ./wrapper -->

    <!-- Third Party Plugin(OverlayScrollbars) -->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!-- Required Plugin(popperjs for Bootstrap 5) -->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->

    <!-- Required Plugin(Bootstrap 5) -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)-->

    <!-- Required Plugin(AdminLTE) -->
    <script src="<?php echo base_url('assets/js/adminlte.js'); ?>"></script>
    <!--end::Required Plugin(AdminLTE)-->

    <!-- Toastr JS -->
    <script src="<?php echo base_url('assets/js/jquery.toaster.js'); ?>"></script>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata("text") != null): ?>
        <script>
            $(document).ready(function() {
                $.toaster({
                    priority: '<?php echo $this->session->flashdata("type"); ?>',
                    title: '<?php echo $this->session->flashdata("text"); ?>',
                    message: ''
                });
            });
        </script>
    <?php endif; ?>

    <!-- OverlayScrollbars Configure -->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>

    <!-- Custom JavaScript -->
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Initialize popovers
            $('[data-toggle="popover"]').popover();
            
            
            // Mobile sidebar toggle functionality
            $('.mobile-sidebar-toggle').click(function() {
                $('.app-sidebar').toggleClass('show');
                $('.sidebar-overlay').toggleClass('show');
            });
            
            // Close sidebar when clicking overlay
            $('.sidebar-overlay').click(function() {
                $('.app-sidebar').removeClass('show');
                $('.sidebar-overlay').removeClass('show');
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).click(function(e) {
                if ($(window).width() < 992) {
                    if (!$(e.target).closest('.app-sidebar, .mobile-sidebar-toggle').length) {
                        $('.app-sidebar').removeClass('show');
                        $('.sidebar-overlay').removeClass('show');
                    }
                }
            });
            
            // Handle window resize
            $(window).resize(function() {
                if ($(window).width() >= 992) {
                    // Desktop: remove mobile classes
                    $('.app-sidebar').removeClass('show');
                    $('.sidebar-overlay').removeClass('show');
                }
            });
            
            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
    <style>
        /* Logout button styling */
        .logout-btn {
            transition: all 0.3s ease;
        }
        
        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        /* User info styling */
        .user-info {
            font-size: 0.9rem;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .user-info {
                display: none;
            }
            
            .logout-btn {
                font-size: 0.8rem;
                padding: 0.25rem 0.5rem;
            }
        }
    </style>
    
    <!-- Fix for AdminLTE sidebar errors -->
    <script>
        $(document).ready(function() {
            // Prevent AdminLTE sidebar errors by ensuring elements exist
            if (typeof $.fn.pushMenu !== 'undefined') {
                try {
                    // Initialize push menu if elements exist
                    if ($('.sidebar-toggle').length > 0) {
                        $('.sidebar-toggle').pushMenu();
                    }
                } catch (e) {
                    console.log('AdminLTE sidebar initialization skipped:', e.message);
                }
            }
            
            // Simple mobile sidebar toggle functionality
            $('.mobile-sidebar-toggle').click(function() {
                $('.app-sidebar').toggleClass('show');
                $('.sidebar-overlay').toggleClass('show');
            });
            
            // Close sidebar when clicking overlay
            $('.sidebar-overlay').click(function() {
                $('.app-sidebar').removeClass('show');
                $('.sidebar-overlay').removeClass('show');
            });
        });
    </script>
    </body>
</html>
