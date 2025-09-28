<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo APP_NAME . ' | ' . APP_DESCRIPTION ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.7 -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap.min.css">
        <!-- Font Awesome 6 -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/admin/AdminLTE.min.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/admin/custom.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/admin/skins/_all-skins.min.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">

        <script src="<?php echo base_url() ?>assets/js/jquery-3.3.1.min.js"></script>
        
        <!-- Modern Login Styles -->
        <style>
        /* Modern Login Page Styles */
        .hold-transition.login-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', 'Poppins', sans-serif;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .modern-login-container {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .bg-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation: float 6s ease-in-out infinite;
        }

        .shape-1 {
            width: 300px;
            height: 300px;
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .shape-2 {
            width: 200px;
            height: 200px;
            bottom: -100px;
            left: -100px;
            animation-delay: 2s;
        }

        .shape-3 {
            width: 150px;
            height: 150px;
            top: 50%;
            left: 10%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .login-card {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 420px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .logo-icon i {
            font-size: 24px;
            color: white;
        }

        .logo-text {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
            font-family: 'Poppins', sans-serif;
        }

        .login-subtitle {
            color: #718096;
            font-size: 16px;
            margin: 0;
            font-weight: 400;
        }

        .modern-alert {
            border: none;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            color: white;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .modern-alert.alert-success {
            background: linear-gradient(135deg, #48bb78, #38a169);
            box-shadow: 0 8px 16px rgba(72, 187, 120, 0.3);
        }

        .modern-alert.alert-danger {
            background: linear-gradient(135deg, #e53e3e, #c53030);
            box-shadow: 0 8px 16px rgba(229, 62, 62, 0.3);
        }

        .alert-icon {
            margin-right: 12px;
            font-size: 20px;
        }

        .alert-content h4 {
            margin: 0 0 4px 0;
            font-size: 16px;
            font-weight: 600;
        }

        .alert-content p {
            margin: 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .modern-form-group {
            margin-bottom: 25px;
        }

        .input-container {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 16px;
            z-index: 2;
        }

        .modern-input {
            width: 100%;
            padding: 16px 16px 16px 50px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 400;
            background: #f7fafc;
            transition: all 0.3s ease;
            outline: none;
            font-family: 'Inter', sans-serif;
        }

        .modern-input:focus {
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modern-input:focus + .input-border {
            opacity: 1;
            transform: scaleX(1);
        }

        .input-border {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transform: scaleX(0);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .modern-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }

        .modern-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }

        .modern-btn:active {
            transform: translateY(0);
        }

        .btn-loader {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: none;
        }

        .spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .forgot-password,
        .register-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .forgot-password:hover,
        .register-link:hover {
            color: #764ba2;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .forgot-password i,
        .register-link i {
            margin-right: 6px;
            font-size: 12px;
        }

        .login-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .login-footer p {
            color: #a0aec0;
            font-size: 12px;
            margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .login-card {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .logo-text {
                font-size: 24px;
            }
            
            .login-subtitle {
                font-size: 14px;
            }
            
            .modern-input {
                padding: 14px 14px 14px 45px;
                font-size: 15px;
            }
            
            .modern-btn {
                padding: 14px;
                font-size: 15px;
            }
        }

        /* Loading State */
        .modern-btn.loading .btn-text {
            opacity: 0;
        }

        .modern-btn.loading .btn-loader {
            display: block;
        }

        /* Error State */
        .modern-input.error {
            border-color: #e53e3e;
            background: #fed7d7;
        }

        .modern-input.error:focus {
            border-color: #e53e3e;
            box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
        }

        /* Registration Page Styles */
        .modern-register-container {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 20px;
            padding-top: 40px;
            padding-bottom: 40px;
        }

        .register-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .register-card {
            position: relative;
            z-index: 2;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .register-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .register-subtitle {
            color: #718096;
            font-size: 16px;
            margin: 0;
            font-weight: 400;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .password-requirements {
            margin-top: 8px;
            color: #a0aec0;
            font-size: 12px;
        }

        .role-label {
            display: block;
            margin-bottom: 15px;
            font-weight: 600;
            color: #2d3748;
            font-size: 16px;
        }

        .role-selection {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f7fafc;
            text-align: center;
        }

        .role-card:hover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.1);
        }

        .role-option input[type="radio"]:checked + .role-card {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.2);
        }

        .role-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }

        .role-icon i {
            font-size: 20px;
            color: white;
        }

        .role-content h4 {
            margin: 0 0 8px 0;
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
        }

        .role-content p {
            margin: 0;
            font-size: 12px;
            color: #718096;
            line-height: 1.4;
        }

        .terms-container {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .terms-container input[type="checkbox"] {
            margin: 0;
            margin-top: 2px;
        }

        .terms-label {
            font-size: 14px;
            color: #4a5568;
            line-height: 1.5;
            cursor: pointer;
        }

        .terms-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-link:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .register-links {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .login-link {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .login-link:hover {
            color: #764ba2;
            text-decoration: none;
            transform: translateY(-1px);
        }

        .login-link i {
            margin-right: 8px;
            font-size: 12px;
        }

        .register-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
        }

        .register-footer p {
            color: #a0aec0;
            font-size: 12px;
            margin: 0;
        }

        /* Registration Responsive Design */
        @media (max-width: 600px) {
            .modern-register-container {
                padding: 10px;
                padding-top: 20px;
                padding-bottom: 20px;
                align-items: flex-start;
            }
            
            .register-card {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .form-row {
                grid-template-columns: 1fr;
                gap: 15px;
            }
            
            .role-selection {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            
            .role-card {
                padding: 15px;
            }
            
            .role-icon {
                width: 40px;
                height: 40px;
            }
            
            .role-icon i {
                font-size: 16px;
            }
        }
        </style>
    </head>
    <body class="hold-transition login-page">
        {body}
        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.toaster.js"></script>

        <?php if ($this->session->flashdata("text") != null): ?>
            <script>
                $.toaster({
                    priority: '<?php echo $this->session->flashdata("type") ?>',
                    title: '<?php echo $this->session->flashdata("text") ?>',
                    message: ''});
            </script>
        <?php endif; ?>
    </body>
</html>
