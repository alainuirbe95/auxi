                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <strong>Copyright &copy; 2024 <a href="#">EasyClean</a>.</strong>
            All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
    
    <!-- Bootstrap JS -->
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
    
    <!-- AdminLTE JS -->
    <script src="<?php echo base_url('assets/js/adminlte.min.js'); ?>"></script>

    <!-- Custom Profile Scripts -->
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Initialize popovers
            $('[data-toggle="popover"]').popover();
            
            // Star rating functionality
            $('.star-rating').on('click', function() {
                const rating = $(this).data('rating');
                $(this).siblings('.star-rating').removeClass('active');
                $(this).addClass('active');
                $(this).prevAll('.star-rating').addClass('active');
                $(this).closest('form').find('input[name="rating"]').val(rating);
            });
            
            // Form validation
            $('form').on('submit', function(e) {
                const requiredFields = $(this).find('[required]');
                let isValid = true;
                
                requiredFields.each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('is-invalid');
                        isValid = false;
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });
            
            // AJAX form submissions
            $('.ajax-form').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const formData = form.serialize();
                const url = form.attr('action');
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            if (response.message) {
                                showAlert('success', response.message);
                            }
                            if (response.redirect) {
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 1000);
                            }
                        } else {
                            showAlert('error', response.message || 'An error occurred.');
                        }
                    },
                    error: function() {
                        showAlert('error', 'An error occurred while processing your request.');
                    }
                });
            });
            
            // Show alert function
            function showAlert(type, message) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alert = $(`
                    <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);
                
                $('.container-fluid').prepend(alert);
                
                setTimeout(function() {
                    alert.fadeOut();
                }, 5000);
            }
            
            // Image preview functionality
            $('input[type="file"]').on('change', function() {
                const file = this.files[0];
                const preview = $(this).siblings('.image-preview');
                
                if (file && preview.length) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).show();
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>

    <style>
        /* Additional profile-specific styles */
        .star-rating {
            cursor: pointer;
            color: #ddd;
            font-size: 1.5rem;
            transition: color 0.2s ease;
        }
        
        .star-rating:hover,
        .star-rating.active {
            color: #ffc107;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            border-radius: 10px;
            margin-top: 1rem;
            display: none;
        }
        
        .profile-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }
        
        .skill-tag {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            margin: 0.25rem;
        }
        
        .verification-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .pending-badge {
            background: #ffc107;
            color: #212529;
        }
        
        .rejected-badge {
            background: #dc3545;
            color: white;
        }
        
        @media (max-width: 768px) {
            .profile-stats {
                flex-direction: column;
            }
            
            .stat-item {
                margin-bottom: 1rem;
            }
            
            .profile-photo {
                width: 120px;
                height: 120px;
                font-size: 3rem;
            }
        }
    </style>
</body>
</html>
