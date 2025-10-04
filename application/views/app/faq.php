<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - AuxiApp</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --accent-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --success-gradient: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            --dark-bg: #0a0e27;
            --card-bg: #ffffff;
            --text-primary: #2d3748;
            --text-secondary: #718096;
            --text-light: #a0aec0;
            --border-color: #e2e8f0;
            --shadow-light: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-medium: 0 8px 30px rgba(0, 0, 0, 0.12);
            --shadow-heavy: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text-primary);
            overflow-x: hidden;
            background: #fafbfc;
        }
        
        /* Custom Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header Styles */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 0;
            min-height: 70px;
        }
        
        .logo {
            font-family: 'Poppins', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-link {
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: #667eea;
        }
        
        .nav-link.active {
            color: #667eea;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-gradient);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        
        .btn {
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            color: white;
            box-shadow: var(--shadow-light);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
            color: white;
        }
        
        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        
        .btn-outline:hover {
            background: var(--primary-gradient);
            color: white;
            transform: translateY(-2px);
        }
        
        /* Hero Section */
        .hero {
            background: var(--primary-gradient);
            color: white;
            text-align: center;
            padding: 120px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23a)"/><circle cx="800" cy="300" r="200" fill="url(%23a)"/><circle cx="600" cy="700" r="250" fill="url(%23a)"/></svg>');
            background-size: cover;
        }
        
        .hero-content {
            position: relative;
            z-index: 2;
        }
        
        .hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
        }
        
        .hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Section Styles */
        .section {
            padding: 80px 0;
        }
        
        /* FAQ Styles */
        .faq-container {
            max-width: 900px;
            margin: 0 auto;
        }
        
        .faq-item {
            background: var(--card-bg);
            border-radius: 15px;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-light);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            box-shadow: var(--shadow-medium);
        }
        
        .faq-question {
            padding: 1.5rem 2rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            color: var(--text-primary);
            transition: all 0.3s ease;
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            font-size: 1.1rem;
        }
        
        .faq-question:hover {
            background: #f8fafc;
        }
        
        .faq-question.active {
            background: var(--primary-gradient);
            color: white;
        }
        
        .faq-icon {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }
        
        .faq-question.active .faq-icon {
            transform: rotate(180deg);
        }
        
        .faq-answer {
            padding: 0 2rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            background: #f8fafc;
        }
        
        .faq-answer.active {
            padding: 1.5rem 2rem;
            max-height: 500px;
        }
        
        .faq-answer p {
            color: var(--text-secondary);
            line-height: 1.7;
            margin: 0;
        }
        
        /* Mobile Menu */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
            z-index: 1001;
        }
        
        .mobile-menu {
            display: none;
            position: fixed;
            top: 120px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-medium);
            z-index: 999;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }
        
        .mobile-menu.active {
            display: block;
        }
        
        .mobile-nav-links {
            list-style: none;
            padding: 1rem 0;
            margin: 0;
        }
        
        .mobile-nav-links li {
            margin: 0;
        }
        
        .mobile-nav-links .nav-link {
            display: block;
            padding: 1rem 2rem;
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border-bottom: 1px solid var(--border-color);
        }
        
        .mobile-nav-links .nav-link:hover {
            background: #f8fafc;
            color: #667eea;
        }
        
        .mobile-nav-links .btn {
            margin: 1rem 2rem;
            display: inline-block;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 15px;
            }
            
            .nav-links {
                display: none;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .faq-question {
                padding: 1.25rem 1.5rem;
                font-size: 1rem;
            }
            
            .faq-answer.active {
                padding: 1.25rem 1.5rem;
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <nav class="nav">
                <a href="<?php echo base_url(); ?>" class="logo">
                    <i class="fas fa-sparkles"></i>
                    AuxiApp
                </a>
                
                <ul class="nav-links">
                    <li><a href="<?php echo base_url(); ?>#how-it-works" class="nav-link">How It Works</a></li>
                    <li><a href="<?php echo base_url('app/for-cleaners'); ?>" class="nav-link">For Cleaners</a></li>
                    <li><a href="<?php echo base_url('app/for-hosts'); ?>" class="nav-link">For Hosts</a></li>
                    <li><a href="<?php echo base_url('app/faq'); ?>" class="nav-link active">FAQ</a></li>
                    <li><a href="<?php echo base_url('app/login'); ?>" class="btn btn-outline">Login</a></li>
                    <li><a href="<?php echo base_url('app/register'); ?>" class="btn btn-primary">Sign Up</a></li>
                </ul>
                
                <button class="mobile-menu-btn" id="mobile-menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>
            </nav>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobile-menu">
        <ul class="mobile-nav-links">
            <li><a href="<?php echo base_url(); ?>#how-it-works" class="nav-link">How It Works</a></li>
            <li><a href="<?php echo base_url('app/for-cleaners'); ?>" class="nav-link">For Cleaners</a></li>
            <li><a href="<?php echo base_url('app/for-hosts'); ?>" class="nav-link">For Hosts</a></li>
            <li><a href="<?php echo base_url('app/faq'); ?>" class="nav-link active">FAQ</a></li>
            <li>
                <a href="<?php echo base_url('app/login'); ?>" class="btn btn-outline">Login</a>
                <a href="<?php echo base_url('app/register'); ?>" class="btn btn-primary">Sign Up</a>
            </li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Frequently Asked Questions</h1>
                <p>Everything you need to know about AuxiApp's smart cleaning services</p>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section">
        <div class="container">
            <div class="faq-container">
                <?php $this->load->view('app/home_faq'); ?>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script>
        // FAQ Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const isActive = this.classList.contains('active');
                    
                    // Close all other FAQ items
                    faqQuestions.forEach(q => {
                        q.classList.remove('active');
                        q.nextElementSibling.classList.remove('active');
                    });
                    
                    // Toggle current FAQ item
                    if (!isActive) {
                        this.classList.add('active');
                        answer.classList.add('active');
                    }
                });
            });

            // Mobile Menu Toggle
            const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuIcon = mobileMenuToggle.querySelector('i');
            
            if (mobileMenuToggle && mobileMenu) {
                mobileMenuToggle.addEventListener('click', function() {
                    mobileMenu.classList.toggle('active');
                    
                    // Change icon
                    if (mobileMenu.classList.contains('active')) {
                        mobileMenuIcon.classList.remove('fa-bars');
                        mobileMenuIcon.classList.add('fa-times');
                    } else {
                        mobileMenuIcon.classList.remove('fa-times');
                        mobileMenuIcon.classList.add('fa-bars');
                    }
                });

                // Close mobile menu when clicking on a link
                const mobileNavLinks = mobileMenu.querySelectorAll('.nav-link');
                mobileNavLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.remove('active');
                        mobileMenuIcon.classList.remove('fa-times');
                        mobileMenuIcon.classList.add('fa-bars');
                    });
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuToggle.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.remove('active');
                        mobileMenuIcon.classList.remove('fa-times');
                        mobileMenuIcon.classList.add('fa-bars');
                    }
                });
            }
        });

        // Header background change on scroll
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.header');
            if (window.scrollY > 50) {
                header.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                header.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        });
    </script>
</body>
</html>
