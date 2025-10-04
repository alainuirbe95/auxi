<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>For Hosts - AuxiApp</title>
    
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
            background: linear-gradient(135deg, #fafbfc 0%, #f1f5f9 50%, #e2e8f0 100%);
            min-height: 100vh;
        }
        
        /* Custom Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            position: relative;
            z-index: 2;
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
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding-top: 120px;
            z-index: 1;
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
            color: white;
            text-align: center;
            width: 100%;
        }
        
        .hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 4rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, #ffffff 0%, #e2e8f0 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2.5rem;
            opacity: 0.9;
            max-width: 600px;
            line-height: 1.7;
            margin-left: auto;
            margin-right: auto;
        }
        
        .hero-stats {
            display: flex;
            gap: 3rem;
            margin-top: 3rem;
            justify-content: center;
        }
        
        .stat {
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: white;
            display: block;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Section Styles */
        .section {
            padding: 100px 0;
            position: relative;
        }
        
        /* Gradient Sections */
        .how-it-works-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            position: relative;
        }
        
        .how-it-works-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(79,172,254,0.05)"/><stop offset="100%" stop-color="rgba(79,172,254,0)"/></radialGradient></defs><circle cx="200" cy="200" r="400" fill="url(%23a)"/><circle cx="800" cy="300" r="300" fill="url(%23a)"/></svg>');
            background-size: cover;
            pointer-events: none;
        }
        
        .use-cases-section {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 50%, #cbd5e1 100%);
            position: relative;
        }
        
        .use-cases-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="b" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(102,126,234,0.05)"/><stop offset="100%" stop-color="rgba(102,126,234,0)"/></radialGradient></defs><circle cx="300" cy="400" r="350" fill="url(%23b)"/><circle cx="700" cy="600" r="250" fill="url(%23b)"/></svg>');
            background-size: cover;
            pointer-events: none;
        }
        
        .benefits-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #cbd5e1 100%);
            position: relative;
        }
        
        .benefits-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="c" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(67,233,123,0.05)"/><stop offset="100%" stop-color="rgba(67,233,123,0)"/></radialGradient></defs><circle cx="400" cy="300" r="300" fill="url(%23c)"/><circle cx="600" cy="700" r="350" fill="url(%23c)"/></svg>');
            background-size: cover;
            pointer-events: none;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: #1a202c;
        }
        
        .section-subtitle {
            font-size: 1.2rem;
            color: #2d3748;
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Cards */
        .card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-heavy);
        }
        
        .card-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            background: var(--primary-gradient);
            color: white;
        }
        
        .card h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }
        
        .card p {
            color: var(--text-secondary);
            line-height: 1.7;
        }
        
        /* Grid System */
        .grid {
            display: grid;
            gap: 2rem;
        }
        
        .grid-3 {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }
        
        .grid-2 {
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        }
        
        /* Use Cases */
        .use-case {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
        }
        
        .use-case:hover {
            transform: translateX(10px);
            box-shadow: var(--shadow-medium);
        }
        
        /* Cost Savings */
        .cost-savings {
            background: var(--primary-gradient);
            color: white;
            border-radius: 20px;
            padding: 3rem;
            margin: 2rem 0;
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
            top: 70px;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-medium);
            z-index: 999;
            max-height: calc(100vh - 70px);
            overflow-y: auto;
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
        
        /* CTA Section */
        .cta {
            background: var(--primary-gradient);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="200" cy="200" r="300" fill="url(%23a)"/><circle cx="800" cy="300" r="200" fill="url(%23a)"/><circle cx="600" cy="700" r="250" fill="url(%23a)"/></svg>');
            background-size: cover;
        }
        
        .cta-content {
            position: relative;
            z-index: 2;
        }
        
        .cta h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .cta p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
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
            
            .hero-stats {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }
            
            .section-title {
                font-size: 2.2rem;
            }
            
            .grid-3,
            .grid-2 {
                grid-template-columns: 1fr;
            }
            
            .card {
                padding: 2rem;
            }
        }
        
        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
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
                    <li><a href="<?php echo base_url('app/for-hosts'); ?>" class="nav-link active">For Hosts</a></li>
                    <li><a href="<?php echo base_url('app/faq'); ?>" class="nav-link">FAQ</a></li>
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
            <li><a href="<?php echo base_url('app/for-hosts'); ?>" class="nav-link active">For Hosts</a></li>
            <li><a href="<?php echo base_url('app/faq'); ?>" class="nav-link">FAQ</a></li>
            <li>
                <a href="<?php echo base_url('app/login'); ?>" class="btn btn-outline">Login</a>
                <a href="<?php echo base_url('app/register'); ?>" class="btn btn-primary">Sign Up</a>
            </li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content fade-in-up">
                <h1>Professional Cleaning for Your Properties</h1>
                <p>Connect with verified cleaners for your STR properties, vacation homes, and rentals. Get quality cleaning services at competitive rates with our innovative counter-offer system.</p>
                
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center;">
                    <a href="<?php echo base_url('app/register'); ?>" class="btn btn-primary" style="padding: 16px 32px; font-size: 1.1rem;">
                        <i class="fas fa-home"></i>
                        Post Your First Job
                    </a>
                    <a href="#how-it-works" class="btn btn-outline" style="padding: 16px 32px; font-size: 1.1rem; background: rgba(255,255,255,0.1); color: white; border-color: rgba(255,255,255,0.3);">
                        <i class="fas fa-play"></i>
                        See How It Works
                    </a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat">
                        <span class="stat-number">$30-80</span>
                        <span class="stat-label">Per cleaning</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">24hrs</span>
                        <span class="stat-label">Average response</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">4.9★</span>
                        <span class="stat-label">Average rating</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section how-it-works-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <h2 class="section-title">How AuxiApp Works for Hosts</h2>
                <p class="section-subtitle">Our innovative platform connects you with professional cleaners through a transparent counter-offer system</p>
            </div>
            
            <div class="grid grid-3">
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <h3>1. Post Your Job</h3>
                    <p>Create a detailed cleaning job listing with your requirements, preferred schedule, and budget range.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>2. Review Offers</h3>
                    <p>Compare offers from multiple professional cleaners. Review their profiles, ratings, and proposed rates.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>3. Negotiate & Select</h3>
                    <p>Use our counter-offer system to negotiate the best price. Accept the offer that works for your budget.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3>4. Relax & Review</h3>
                    <p>Your cleaner handles everything. Review their work and release payment when satisfied.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Use Cases Section -->
    <section class="section use-cases-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <h2 class="section-title">Perfect for These Use Cases</h2>
                <p class="section-subtitle">Get professional cleaning services for your STR properties, vacation homes, and rentals</p>
            </div>
            
            <div class="grid grid-2">
                <div class="card fade-in-up">
                    <h3 style="color: #667eea; margin-bottom: 1.5rem;">
                        <i class="fas fa-chart-line me-2"></i>
                        Best Use Cases
                    </h3>
                    <div>
                        <div class="use-case">
                            <h5 style="margin-bottom: 0.5rem; color: #667eea;">
                                <i class="fas fa-bed"></i>
                                Short-Term Rentals (STR)
                            </h5>
                            <p style="margin: 0; color: var(--text-secondary);">Clean between guest stays quickly and professionally. Perfect for Airbnb, VRBO, and other rental platforms.</p>
                        </div>
                        
                        <div class="use-case">
                            <h5 style="margin-bottom: 0.5rem; color: #667eea;">
                                <i class="fas fa-umbrella-beach"></i>
                                Vacation Homes
                            </h5>
                            <p style="margin: 0; color: var(--text-secondary);">Keep your vacation property spotless for your family's visits. Regular cleaning to maintain property value.</p>
                        </div>
                        
                        <div class="use-case">
                            <h5 style="margin-bottom: 0.5rem; color: #667eea;">
                                <i class="fas fa-building"></i>
                                Rental Properties
                            </h5>
                            <p style="margin: 0; color: var(--text-secondary);">Professional cleaning for tenant turnover, move-in/move-out cleanings, and regular maintenance.</p>
                        </div>
                    </div>
                </div>
                
                <div class="card fade-in-up">
                    <div class="cost-savings text-center">
                        <h3 style="margin-bottom: 1.5rem;">Cost Savings</h3>
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 10px;">
                                <span>Traditional Services</span>
                                <span style="font-weight: 600; color: #ffd700;">$80-150</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 10px;">
                                <span>AuxiApp</span>
                                <span style="font-weight: 600; color: #90EE90;">$30-80</span>
                            </div>
                        </div>
                        <p style="margin-bottom: 1.5rem; opacity: 0.9;">Save 40-60% compared to traditional cleaning services</p>
                        <a href="<?php echo base_url('app/register'); ?>" class="btn" style="background: white; color: #667eea; width: 100%; justify-content: center;">
                            <i class="fas fa-plus"></i>
                            Post Your First Job
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="section benefits-section">
        <div class="container">
            <div class="section-header fade-in-up">
                <h2 class="section-title">Why Choose AuxiApp?</h2>
                <p class="section-subtitle">Smart and cost-effective cleaning solutions for property owners</p>
            </div>
            
            <div class="grid grid-3">
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <h3>Competitive Pricing</h3>
                    <p>Our counter-offer system ensures you get the best price. Cleaners compete for your business.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Verified Cleaners</h3>
                    <p>All cleaners are background-checked and verified. See their ratings and reviews before hiring.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>Secure Access</h3>
                    <p>Cleaners get secure OTP codes to access your property. No need to be present during cleaning.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h3>Quality Guarantee</h3>
                    <p>Dispute system ensures you're satisfied. Only pay when the job meets your standards.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3>Quick Turnaround</h3>
                    <p>Get cleaning services scheduled quickly. Perfect for last-minute guest turnovers.</p>
                </div>
                
                <div class="card fade-in-up">
                    <div class="card-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3>Easy Management</h3>
                    <p>Manage all your cleaning jobs from one platform. Track progress and communicate with cleaners.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta">
        <div class="container">
            <div class="cta-content fade-in-up">
                <h2>Ready to Get Started?</h2>
                <p>Join thousands of property owners using AuxiApp for reliable, affordable cleaning services. Post your first job today.</p>
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
                    <a href="<?php echo base_url('app/register'); ?>" class="btn" style="background: white; color: #667eea; padding: 16px 32px; font-size: 1.1rem;">
                        <i class="fas fa-rocket"></i>
                        Post Your First Job
                    </a>
                    <a href="<?php echo base_url('app/login'); ?>" class="btn" style="background: transparent; color: white; border: 2px solid white; padding: 16px 32px; font-size: 1.1rem;">
                        <i class="fas fa-sign-in-alt"></i>
                        Already a Member?
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
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

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
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

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe all fade-in-up elements
        document.querySelectorAll('.fade-in-up').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });
    </script>
</body>
</html>