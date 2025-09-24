<?php

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('Form_validation');
        $this->load->library('grocery_CRUD');

        $this->load->helper('form');
        $this->load->helper('Breadcrumb_helper');

        $this->load->model('M_users');
        $this->load->model('M_job_flags');

        $this->init_session_auto(9);
        
        // Clear any flash data to prevent persistent messages on admin pages
        $this->session->unset_userdata('text');
        $this->session->unset_userdata('type');
    }

    public function index() {
        redirect('admin/dashboard');
    }

    public function dashboard() {
        // Load user statistics
        $this->load->model('M_users');
        
        $stats = $this->M_users->get_user_statistics();
        $pending_users = $this->M_users->get_pending_users();
        $recent_activity = $this->M_users->get_recent_activity(5);
        
        $view["title"] = 'Dashboard'; 
        $view["page_icon"] = 'tachometer-alt';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", array('pending_users_count' => count($pending_users)), TRUE);
        $view["body"] = $this->load->view("admin/dashboard", array(
            'total_users' => $stats->total_users,
            'active_users' => $stats->active_users,
            'banned_users' => $stats->banned_users,
            'pending_users_count' => count($pending_users),
            'recent_activity' => $recent_activity
        ), TRUE);

        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    public function users() {

        // Get filter parameters from URL/GET
        $search = $this->input->get('search') ?: '';
        $status = $this->input->get('status') ?: '';
        $role = $this->input->get('role') ?: '';
        $sort = $this->input->get('sort') ?: 'created_at_desc';
        
        // Map status filter values
        $status_filter = '';
        if ($status === 'active') {
            $status_filter = '0'; // Not banned
        } elseif ($status === 'banned') {
            $status_filter = '1'; // Banned
        } elseif ($status === 'pending') {
            // For pending users, we need to check pending_verification
            $status_filter = 'pending';
        }
        
        // Map role filter values
        $level_filter = '';
        if ($role === '3' || $role === '6' || $role === '9') {
            $level_filter = $role;
        }
        
        // Handle the 'sort' parameter
        $sort_by = 'created_at';
        $sort_order = 'DESC';
        if ($sort) {
            $sort_parts = explode('_', $sort);
            if (count($sort_parts) == 2) {
                $sort_by = $sort_parts[0];
                $sort_order = strtoupper($sort_parts[1]);
            }
        }
        
        $params = array(
            'page' => $this->input->get('page') ?: 1,
            'limit' => $this->input->get('limit') ?: 25,
            'search' => $search,
            'sort_by' => $sort_by,
            'sort_order' => $sort_order,
            'status' => $status_filter,
            'level' => $level_filter,
            'verified' => $this->input->get('verified') ?: ''
        );
        
        // Fetch user data with advanced filtering
        $result = $this->M_users->get_users_advanced($params);
        
        // Get user statistics
        $stats = $this->M_users->get_user_statistics();
        
        // Prepare data for the view
        $data['users'] = $result->users;
        $data['pagination'] = $result->pagination;
        $data['stats'] = $stats;
        
        // Prepare filters for the view (using original values from form)
        $view_filters = array(
            'search' => $search,
            'status' => $status,
            'role' => $role,
            'sort' => $sort
        );
        $data['filters'] = $view_filters;
        
        // Set page title and breadcrumbs
        $view["title"] = 'Users Management';
        $view["page_icon"] = 'users';
        $view["breadcrumbs"] = array(
            array('title' => 'Users Management', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
        
        // Load the view with user data
        $view["body"] = $this->load->view("admin/users/users_management", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Reset user password with temporary password (Admin-initiated)
     * 
     * @param int $user_id
     */
    public function reset_password($user_id) {
        // Security check - prevent self-reset
        if ($user_id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'You cannot reset your own password. Please use the profile settings.');
            redirect('admin/users');
            return;
        }

        
        // Generate temporary password
        $temp_password = $this->M_users->generate_temp_password($user_id);
        
        if ($temp_password) {
            // Get user details for display
            $user = $this->M_users->get_user_by_id($user_id);
            
            // Log the action
            log_message('info', 'Admin ' . $this->session->userdata('username') . ' reset password for user ' . $user->username . ' (ID: ' . $user_id . ')');
            
            // Prepare data for the success view
            $data['user'] = $user;
            $data['temp_password'] = $temp_password;
            
            // Set page title and breadcrumbs
            $view["title"] = 'Password Reset Success';
            $view["breadcrumbs"] = array(
                array('title' => 'Users Management', 'url' => 'admin/users'),
                array('title' => 'User Profile', 'url' => 'admin/view_user/' . $user_id),
                array('title' => 'Password Reset Success', 'url' => '', 'active' => true)
            );
            $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
            
            // Load the success view
            $view["body"] = $this->load->view("admin/users/password_reset_success", $data, TRUE);
            
            $this->load->view("admin/template/layout_with_sidebar", $view);
        } else {
            $this->session->set_flashdata('error', 'Failed to reset password. User may not exist or is banned.');
            redirect('admin/users');
        }
    }


    /**
     * AJAX endpoint for dynamic user loading
     */
    public function ajax_users() {
        // Set JSON header
        header('Content-Type: application/json');
        
        
        // Get filter parameters from POST
        $params = array(
            'page' => $this->input->post('page') ?: 1,
            'limit' => $this->input->post('limit') ?: 25,
            'search' => $this->input->post('search') ?: '',
            'sort_by' => $this->input->post('sort_by') ?: 'created_at',
            'sort_order' => $this->input->post('sort_order') ?: 'DESC',
            'status' => $this->input->post('status') ?: '',
            'level' => $this->input->post('level') ?: '',
            'verified' => $this->input->post('verified') ?: ''
        );
        
        // Fetch user data with advanced filtering
        $result = $this->M_users->get_users_advanced($params);
        
        // Return JSON response
        echo json_encode(array(
            'success' => true,
            'users' => $result->users,
            'pagination' => $result->pagination,
            'filters' => $params
        ));
        exit;
    }

    public function view_user($user_id = null) {
        // Check if user ID is provided
        if (!$user_id) {
            show_error('User ID is required', 400);
        }

        
        // Fetch user data using the enhanced model method
        $user = $this->M_users->get_user_with_stats($user_id);
        
        // Check if user exists
        if (!$user) {
            show_error('User not found', 404);
        }
        
        // Prepare data for the view
        $data['user'] = $user;
        
        // Set page title and breadcrumbs
        $view["title"] = 'View User - ' . ($user->username ?? 'Unknown');
        $view["breadcrumbs"] = array(
            array('title' => 'Users Management', 'url' => base_url('admin/users')),
            array('title' => 'View User', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
        
        // Load the view with user data
        $view["body"] = $this->load->view("admin/users/user_view", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    public function create_user() {
        // Load required libraries and helpers
        $this->load->library('form_validation');
        
        // Set validation rules - Only require essential fields for admin-created users
        $this->form_validation->set_rules('username', 'Username', 'required|max_length[50]|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('passwd', 'Password', 'required|min_length[8]|callback_password_strength_check');
        $this->form_validation->set_rules('passwd_confirm', 'Confirm Password', 'required|matches[passwd]');
        $this->form_validation->set_rules('auth_level', 'User Level', 'required|in_list[3,6,9]');
        
        // Optional fields - no validation required
        $this->form_validation->set_rules('first_name', 'First Name', 'max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'max_length[50]');
        $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'callback_validate_date_of_birth');
        $this->form_validation->set_rules('address', 'Address', 'max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'max_length[100]');
        $this->form_validation->set_rules('country', 'Country', 'max_length[100]');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[500]');
        
        if ($this->form_validation->run() == FALSE) {
            // Form validation failed, show the form again
            $view["title"] = 'Create New User';
            $view["breadcrumbs"] = array(
                array('title' => 'Users Management', 'url' => base_url('admin/users')),
                array('title' => 'Create User', 'url' => '', 'active' => true)
            );
            $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
            $view["body"] = $this->load->view("admin/users/user_create", NULL, TRUE);
            
            $this->load->view("admin/template/layout_with_sidebar", $view);
        } else {
            // Form validation passed, create the user
            $user_data = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'passwd' => $this->input->post('passwd'),
                'auth_level' => $this->input->post('auth_level'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'date_of_birth' => $this->input->post('date_of_birth'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'country' => $this->input->post('country'),
                'notes' => $this->input->post('notes'),
                'email_verified' => $this->input->post('email_verified') ? '1' : '1', // Admin-created users are auto-verified
                'banned' => $this->input->post('banned') ? '1' : '0',
                'pending_verification' => '0', // Admin-created users bypass review process
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user_id')
            );
            
            $user_id = $this->M_users->create_user($user_data);
            
            if ($user_id) {
                // User created successfully
                $this->session->set_flashdata('success', 'User created successfully!');
                
                // Send welcome email if requested
                if ($this->input->post('send_welcome_email')) {
                    $this->send_welcome_email($user_data, $user_id);
                }
                
                redirect('admin/view_user/' . $user_id);
            } else {
                // User creation failed
                $this->session->set_flashdata('error', 'Failed to create user. Please try again.');
                redirect('admin/create_user');
            }
        }
    }
    
    /**
     * Password strength validation callback
     */
    public function password_strength_check($password) {
        // Check for minimum length
        if (strlen($password) < 8) {
            $this->form_validation->set_message('password_strength_check', 'Password must be at least 8 characters long.');
            return FALSE;
        }
        
        // Check for uppercase letter
        if (!preg_match('/[A-Z]/', $password)) {
            $this->form_validation->set_message('password_strength_check', 'Password must contain at least one uppercase letter.');
            return FALSE;
        }
        
        // Check for lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            $this->form_validation->set_message('password_strength_check', 'Password must contain at least one lowercase letter.');
            return FALSE;
        }
        
        // Check for number
        if (!preg_match('/[0-9]/', $password)) {
            $this->form_validation->set_message('password_strength_check', 'Password must contain at least one number.');
            return FALSE;
        }
        
        // Check for special character
        if (!preg_match('/[^A-Za-z0-9]/', $password)) {
            $this->form_validation->set_message('password_strength_check', 'Password must contain at least one special character.');
            return FALSE;
        }
        
        return TRUE;
    }
    
    /**
     * Validate date of birth
     */
    public function validate_date_of_birth($date) {
        // If date is empty, it's valid (optional field)
        if (empty($date)) {
            return TRUE;
        }
        
        // Check if date is in correct format (YYYY-MM-DD)
        $d = DateTime::createFromFormat('Y-m-d', $date);
        if (!$d || $d->format('Y-m-d') !== $date) {
            $this->form_validation->set_message('validate_date_of_birth', 'Please enter a valid date in YYYY-MM-DD format.');
            return FALSE;
        }
        
        // Check if date is not in the future
        $today = new DateTime();
        $birth_date = new DateTime($date);
        
        if ($birth_date > $today) {
            $this->form_validation->set_message('validate_date_of_birth', 'Date of birth cannot be in the future.');
            return FALSE;
        }
        
        // Check if age is reasonable (not more than 120 years old)
        $age = $today->diff($birth_date)->y;
        if ($age > 120) {
            $this->form_validation->set_message('validate_date_of_birth', 'Please enter a valid date of birth (age cannot exceed 120 years).');
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Delete a user
     */
    public function delete_user($user_id = null) {
        // Check if user ID is provided
        if (!$user_id) {
            show_error('User ID is required', 400);
        }

        
        // Get user info before deletion
        $user = $this->M_users->get_user_by_id($user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
        }
        
        // Prevent deletion of the current logged-in user
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == $current_user_id) {
            $this->session->set_flashdata('error', 'You cannot delete your own account.');
            redirect('admin/users');
        }
        
        // Attempt to delete the user
        $deleted = $this->M_users->delete_user($user_id);
        
        if ($deleted) {
            $this->session->set_flashdata('success', 'User "' . $user->username . '" has been deleted successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to delete user. Please try again.');
        }
        
        redirect('admin/users');
    }

    /**
     * Ban a user
     */
    public function ban_user($user_id = null) {
        // Check if user ID is provided
        if (!$user_id) {
            show_error('User ID is required', 400);
        }

        
        // Get user info before banning
        $user = $this->M_users->get_user_by_id($user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
        }
        
        // Prevent banning of the current logged-in user
        $current_user_id = $this->session->userdata('user_id');
        if ($user_id == $current_user_id) {
            $this->session->set_flashdata('error', 'You cannot ban your own account.');
            redirect('admin/users');
        }
        
        // Attempt to ban the user
        $banned = $this->M_users->ban_user($user_id);
        
        if ($banned) {
            $this->session->set_flashdata('success', 'User "' . $user->username . '" has been banned successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to ban user. Please try again.');
        }
        
        redirect('admin/users');
    }

    /**
     * Unban a user
     */
    public function unban_user($user_id = null) {
        // Check if user ID is provided
        if (!$user_id) {
            show_error('User ID is required', 400);
        }

        
        // Get user info before unbanning
        $user = $this->M_users->get_user_by_id($user_id);
        
        if (!$user) {
            $this->session->set_flashdata('error', 'User not found.');
            redirect('admin/users');
        }
        
        // Attempt to unban the user
        $unbanned = $this->M_users->unban_user($user_id);
        
        if ($unbanned) {
            $this->session->set_flashdata('success', 'User "' . $user->username . '" has been unbanned successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to unban user. Please try again.');
        }
        
        redirect('admin/users');
    }

    /**
     * Edit user form
     */
    public function edit_user($user_id = null) {
        // Check if user ID is provided
        if (!$user_id) {
            show_error('User ID is required', 400);
        }

        
        // Fetch user data
        $user = $this->M_users->get_user_by_id($user_id);
        
        // Check if user exists
        if (!$user) {
            show_error('User not found', 404);
        }
        
        // Prepare data for the view
        $data['user'] = $user;
        
        // Set page title and breadcrumbs
        $view["title"] = 'Edit User - ' . ($user->username ?? 'Unknown');
        $view["breadcrumbs"] = array(
            array('title' => 'Users Management', 'url' => base_url('admin/users')),
            array('title' => 'View User', 'url' => base_url('admin/view_user/' . $user_id)),
            array('title' => 'Edit User', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
        
        // Load the view with user data
        $view["body"] = $this->load->view("admin/users/user_edit", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Update user data
     */
    public function update_user($user_id = null) {
        // Check if user ID is provided
        if (!$user_id) {
            show_error('User ID is required', 400);
        }

        // Load required libraries and helpers
        $this->load->library('form_validation');
        
        // Get user to check if exists
        $user = $this->M_users->get_user_by_id($user_id);
        if (!$user) {
            show_error('User not found', 404);
        }
        
        // Set validation rules (only for editable fields)
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_check_email_unique[' . $user_id . ']');
        $this->form_validation->set_rules('first_name', 'First Name', 'max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'max_length[50]');
        $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'callback_validate_date_of_birth');
        $this->form_validation->set_rules('auth_level', 'User Level', 'required|in_list[3,6,9]');
        $this->form_validation->set_rules('address', 'Address', 'max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'max_length[100]');
        $this->form_validation->set_rules('country', 'Country', 'max_length[100]');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[500]');
        
        // Debug: Log form validation status
        log_message('debug', 'Form validation run result: ' . ($this->form_validation->run() ? 'PASSED' : 'FAILED'));
        
        if ($this->form_validation->run() == FALSE) {
            // Form validation failed, show the form again
            log_message('debug', 'Form validation failed, showing form again with errors');
            $data['user'] = $user;
            
            $view["title"] = 'Edit User - ' . ($user->username ?? 'Unknown');
            $view["breadcrumbs"] = array(
                array('title' => 'Users Management', 'url' => base_url('admin/users')),
                array('title' => 'View User', 'url' => base_url('admin/view_user/' . $user_id)),
                array('title' => 'Edit User', 'url' => '', 'active' => true)
            );
            $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
            $view["body"] = $this->load->view("admin/users/user_edit", $data, TRUE);
            
            $this->load->view("admin/template/layout_with_sidebar", $view);
        } else {
            // Form validation passed, update the user
            $user_data = array(
                'email' => $this->input->post('email'),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'date_of_birth' => $this->input->post('date_of_birth'),
                'auth_level' => $this->input->post('auth_level'),
                'address' => $this->input->post('address'),
                'city' => $this->input->post('city'),
                'country' => $this->input->post('country'),
                'notes' => $this->input->post('notes'),
                'email_verified' => $this->input->post('email_verified') ? '1' : '0',
                'banned' => $this->input->post('banned') ? '1' : '0',
                'locked' => $this->input->post('locked') ? '1' : '0',
                'modified_at' => date('Y-m-d H:i:s'),
                'modified_by' => $this->session->userdata('user_id')
            );
            
            // Debug: Log the data being updated
            log_message('debug', 'Updating user ' . $user_id . ' with data: ' . print_r($user_data, true));
            
            $updated = $this->M_users->update_user($user_id, $user_data);
            
            // Debug: Log the update result
            log_message('debug', 'Update result for user ' . $user_id . ': ' . ($updated ? 'SUCCESS' : 'FAILED'));
            
            if ($updated) {
                // User updated successfully
                $this->session->set_flashdata('success', 'User "' . $user->username . '" has been updated successfully.');
                redirect('admin/view_user/' . $user_id);
            } else {
                // User update failed
                $this->session->set_flashdata('error', 'Failed to update user. Please try again.');
                redirect('admin/edit_user/' . $user_id);
            }
        }
    }

    /**
     * Check if email is unique (excluding current user)
     */
    public function check_email_unique($email, $user_id) {
        
        $existing_user = $this->M_users->get_user_by_email($email);
        
        if ($existing_user && $existing_user->user_id != $user_id) {
            $this->form_validation->set_message('check_email_unique', 'This email address is already in use by another user.');
            return FALSE;
        }
        
        return TRUE;
    }

    /**
     * Send welcome email to new user
     */
    private function send_welcome_email($user_data, $user_id) {
        // This would integrate with your email system
        // For now, we'll just log it
        log_message('info', 'Welcome email should be sent to: ' . $user_data['email'] . ' for user ID: ' . $user_id);
    }

    /**
     * Show pending user reviews
     */
    public function pending_users() {
        // Ensure model is loaded
        $this->load->model('M_users');
        
        try {
            // Get pending users (banned with reason "Account pending admin review")
            $pending_users = $this->M_users->get_pending_users();
            
        $view["title"] = 'Pending User Reviews';
        $view["page_icon"] = 'user-clock';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'admin/dashboard'),
            array('title' => 'Pending User Reviews', 'url' => '', 'active' => true)
        );
            $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
            $view["body"] = $this->load->view("admin/users/pending_users", array('pending_users' => $pending_users), TRUE);
            
            $this->load->view("admin/template/layout_with_sidebar", $view);
        } catch (Exception $e) {
            log_message('error', 'Error in pending_users: ' . $e->getMessage());
            show_error('An error occurred while loading pending users: ' . $e->getMessage());
        }
    }

    /**
     * Approve a pending user
     */
    public function approve_user($user_id) {
        $this->load->model('M_users');
        
        // Unban user and clear pending verification
        $update_data = array(
            'banned' => '0',
            'pending_verification' => '0'
        );
        
        // Add optional fields if they exist
        $columns = $this->db->list_fields('users');
        if (in_array('banned_reason', $columns)) {
            $update_data['banned_reason'] = NULL;
        }
        if (in_array('banned_at', $columns)) {
            $update_data['banned_at'] = NULL;
        }
        if (in_array('banned_by', $columns)) {
            $update_data['banned_by'] = NULL;
        }
        if (in_array('modified_by', $columns)) {
            $update_data['modified_by'] = $this->session->userdata('user_id') ?: 'admin';
        }
        
        $this->db->where('user_id', $user_id);
        if ($this->db->update('users', $update_data)) {
            $this->session->set_flashdata('text', 'User approved successfully!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to approve user.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('admin/pending_users');
    }

    /**
     * Reject a pending user
     */
    public function reject_user($user_id) {
        $this->load->model('M_users');
        
        // Use the model's reject_user method which properly sets rejection fields
        $result = $this->M_users->reject_user($user_id);
        
        if ($result) {
            $this->session->set_flashdata('text', 'User rejected successfully and moved to Rejected Users!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to reject user.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('admin/pending_users');
    }

    /**
     * Approve all pending users (AJAX endpoint)
     */
    public function approve_all_users() {
        $this->load->model('M_users');
        
        $user_ids = $this->input->post('user_ids');
        
        if (empty($user_ids) || !is_array($user_ids)) {
            echo json_encode(['success' => false, 'message' => 'No users provided']);
            return;
        }
        
        $approved_count = 0;
        $errors = [];
        
        foreach ($user_ids as $user_id) {
            if ($this->M_users->approve_user($user_id)) {
                $approved_count++;
            } else {
                $errors[] = 'Failed to approve user ID: ' . $user_id;
            }
        }
        
        if ($approved_count > 0) {
            $this->session->set_flashdata('text', "Successfully approved {$approved_count} user(s)!");
            $this->session->set_flashdata('type', 'success');
        }
        
        echo json_encode([
            'success' => $approved_count > 0,
            'message' => $approved_count > 0 ? "Approved {$approved_count} users" : 'No users were approved',
            'approved_count' => $approved_count,
            'errors' => $errors
        ]);
    }

    /**
     * Show rejected users
     */
    public function rejected_users() {
        $this->load->model('M_users');
        
        $rejected_users = $this->M_users->get_rejected_users();
        
        $view["title"] = 'Rejected Users';
        $view["page_icon"] = 'user-times';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'admin/dashboard'),
            array('title' => 'Rejected Users', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("admin/users/rejected_users", array('rejected_users' => $rejected_users), TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Restore a rejected user (unban and allow them to reapply)
     */
    public function restore_user($user_id) {
        $this->load->model('M_users');
        
        if ($this->M_users->restore_user($user_id)) {
            $this->session->set_flashdata('text', 'User has been restored successfully!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to restore user. Please try again.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('admin/rejected_users');
    }

    /**
     * Debug method to test rejection fields
     */
    public function debug_rejection() {
        $this->load->model('M_users');
        
        echo "<!DOCTYPE html><html><head><title>Debug Rejection Fields</title></head><body>";
        echo "<h2>Debug Rejection Fields</h2>";
        
        // Check columns
        $columns = $this->db->list_fields('users');
        echo "<h3>Available columns in users table:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Column Name</th><th>Status</th></tr>";
        
        $rejection_fields = ['rejected', 'rejected_at', 'rejected_by'];
        foreach ($columns as $column) {
            $highlight = in_array($column, $rejection_fields) ? ' style="background: yellow;"' : '';
            $status = in_array($column, $rejection_fields) ? 'EXISTS' : '';
            echo "<tr$highlight><td>$column</td><td>$status</td></tr>";
        }
        echo "</table>";
        
        // Get pending users
        $pending_users = $this->M_users->get_pending_users();
        echo "<h3>Pending Users (" . count($pending_users) . " found):</h3>";
        
        if (!empty($pending_users)) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Username</th><th>Name</th><th>Pending</th><th>Banned</th><th>Rejected</th><th>Test</th></tr>";
            
            foreach ($pending_users as $user) {
                echo "<tr>";
                echo "<td>" . $user->user_id . "</td>";
                echo "<td>" . $user->username . "</td>";
                echo "<td>" . $user->first_name . " " . $user->last_name . "</td>";
                echo "<td>" . $user->pending_verification . "</td>";
                echo "<td>" . $user->banned . "</td>";
                echo "<td>" . (isset($user->rejected) ? $user->rejected : 'NULL') . "</td>";
                echo "<td><a href='" . base_url('admin/test_reject/' . $user->user_id) . "'>Test Reject</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No pending users found.</p>";
        }
        
        // Check rejected users
        $rejected_users = $this->M_users->get_rejected_users();
        echo "<h3>Rejected Users (" . count($rejected_users) . " found):</h3>";
        
        if (!empty($rejected_users)) {
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>ID</th><th>Username</th><th>Name</th><th>Rejected</th><th>Rejected At</th><th>Rejected By</th></tr>";
            
            foreach ($rejected_users as $user) {
                echo "<tr>";
                echo "<td>" . $user->user_id . "</td>";
                echo "<td>" . $user->username . "</td>";
                echo "<td>" . $user->first_name . " " . $user->last_name . "</td>";
                echo "<td>" . (isset($user->rejected) ? $user->rejected : 'NULL') . "</td>";
                echo "<td>" . (isset($user->rejected_at) ? $user->rejected_at : 'NULL') . "</td>";
                echo "<td>" . (isset($user->rejected_by) ? $user->rejected_by : 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No rejected users found.</p>";
        }
        
        echo "</body></html>";
    }

    /**
     * Test rejecting a specific user
     */
    public function test_reject($user_id) {
        $this->load->model('M_users');
        
        echo "<!DOCTYPE html><html><head><title>Test Reject User</title></head><body>";
        echo "<h2>Test Reject User</h2>";
        echo "<p>Testing with user ID: $user_id</p>";
        
        // Get user before rejection
        $this->db->where('user_id', $user_id);
        $user_before = $this->db->get('users')->row();
        
        if (!$user_before) {
            echo "<p style='color: red;'>User not found</p>";
            echo "</body></html>";
            return;
        }
        
        echo "<h3>User Before Rejection:</h3>";
        echo "<ul>";
        echo "<li>Username: " . $user_before->username . "</li>";
        echo "<li>Pending: " . $user_before->pending_verification . "</li>";
        echo "<li>Banned: " . $user_before->banned . "</li>";
        echo "<li>Rejected: " . (isset($user_before->rejected) ? $user_before->rejected : 'NULL') . "</li>";
        echo "<li>Rejected At: " . (isset($user_before->rejected_at) ? $user_before->rejected_at : 'NULL') . "</li>";
        echo "<li>Rejected By: " . (isset($user_before->rejected_by) ? $user_before->rejected_by : 'NULL') . "</li>";
        echo "</ul>";
        
        // Test rejection
        echo "<h3>Testing rejection...</h3>";
        $result = $this->M_users->reject_user($user_id);
        echo "<p>Rejection result: " . ($result ? "SUCCESS" : "FAILED") . "</p>";
        
        // Get user after rejection
        $this->db->where('user_id', $user_id);
        $user_after = $this->db->get('users')->row();
        
        echo "<h3>User After Rejection:</h3>";
        echo "<ul>";
        echo "<li>Username: " . $user_after->username . "</li>";
        echo "<li>Pending: " . $user_after->pending_verification . "</li>";
        echo "<li>Banned: " . $user_after->banned . "</li>";
        echo "<li>Rejected: " . (isset($user_after->rejected) ? $user_after->rejected : 'NULL') . "</li>";
        echo "<li>Rejected At: " . (isset($user_after->rejected_at) ? $user_after->rejected_at : 'NULL') . "</li>";
        echo "<li>Rejected By: " . (isset($user_after->rejected_by) ? $user_after->rejected_by : 'NULL') . "</li>";
        echo "</ul>";
        
        // Check if user appears in rejected users query
        $rejected_users = $this->M_users->get_rejected_users();
        echo "<p>Total rejected users: " . count($rejected_users) . "</p>";
        
        echo "<p><a href='" . base_url('admin/debug_rejection') . "'>Back to Debug</a></p>";
        echo "</body></html>";
    }

    public function change_password() {
        $this->load->model('M_users');
        
        // Get current user info - try different session field names
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $user_id = $this->session->userdata('id'); // Try 'id' field
        }
        
        if (!$user_id) {
            show_error('User not authenticated. Please login again.', 401);
        }
        
        $user_info = $this->M_users->get_user_by_id($user_id);
        
        if (!$user_info) {
            show_error('User not found. Please contact administrator.', 404);
        }
        
        $view["title"] = 'Change Password';
        $view["page_icon"] = 'key';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'admin/dashboard'),
            array('title' => 'Change Password', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("admin/change_password", array('user_info' => $user_info), TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    public function update_password() {
        $this->load->model('M_users');
        
        // Get current user info - try different session field names
        $user_id = $this->session->userdata('user_id');
        if (!$user_id) {
            $user_id = $this->session->userdata('id'); // Try 'id' field
        }
        
        if (!$user_id) {
            $this->output->set_status_header(401);
            echo json_encode(array('success' => false, 'message' => 'User not authenticated'));
            return;
        }
        
        // Get form data
        $current_password = $this->input->post('current_password');
        $new_password = $this->input->post('new_password');
        $confirm_password = $this->input->post('confirm_password');
        
        // Validate input
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            echo json_encode(array('success' => false, 'message' => 'All fields are required'));
            return;
        }
        
        if ($new_password !== $confirm_password) {
            echo json_encode(array('success' => false, 'message' => 'New passwords do not match'));
            return;
        }
        
        if (strlen($new_password) < 8) {
            echo json_encode(array('success' => false, 'message' => 'New password must be at least 8 characters long'));
            return;
        }
        
        // Verify current password
        $user = $this->M_users->get_user_by_id($user_id);
        if (!$user || !password_verify($current_password, $user->passwd)) {
            echo json_encode(array('success' => false, 'message' => 'Current password is incorrect'));
            return;
        }
        
        // Update password
        $result = $this->M_users->update_user_password($user_id, $new_password);
        
        if ($result) {
            echo json_encode(array('success' => true, 'message' => 'Password updated successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to update password'));
        }
    }

    /**
     * Admin Job Management - List all jobs
     */
    public function jobs()
    {
        // Load required models
        $this->load->model('M_jobs');
        
        // Get filter parameters
        $search = $this->input->get('search');
        $status = $this->input->get('status');
        $host = $this->input->get('host');
        $sort = $this->input->get('sort');
        
        // Parse sort parameter
        $sort_by = 'created_at';
        $sort_order = 'DESC';
        if ($sort) {
            $sort_parts = explode('_', $sort);
            if (count($sort_parts) == 2) {
                $sort_by = $sort_parts[0];
                $sort_order = strtoupper($sort_parts[1]);
            }
        }
        
        // Get pagination parameters
        $page = $this->input->get('page') ?: 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        // Prepare filters for model
        $filters = [
            'search' => $search,
            'status' => $status,
            'host' => $host
        ];
        
        // Get jobs with pagination
        $jobs = $this->M_jobs->get_all_jobs_admin($filters, $per_page, $offset, $sort_by, $sort_order);
        $total_jobs = $this->M_jobs->count_all_jobs_admin($filters);
        
        // Get job statistics
        $stats = $this->M_jobs->get_admin_stats();
        
        // Get hosts for filter dropdown
        $this->load->model('M_users');
        $hosts = $this->M_users->get_users_by_level(6); // Host level = 6
        
        // Calculate pagination
        $total_pages = ceil($total_jobs / $per_page);
        $pagination = [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'per_page' => $per_page,
            'total_items' => $total_jobs,
            'has_prev' => $page > 1,
            'has_next' => $page < $total_pages,
            'prev_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $total_pages ? $page + 1 : null
        ];
        
        $data = [
            'title' => 'Job Management',
            'page_icon' => 'fas fa-clipboard-list',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'admin/dashboard'],
                ['title' => 'Job Management', 'url' => '', 'active' => true]
            ],
            'jobs' => $jobs,
            'stats' => $stats,
            'hosts' => $hosts,
            'pagination' => $pagination,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'host' => $host,
                'sort' => $sort
            ],
            'view_filters' => [
                'search' => $search,
                'status' => $status,
                'host' => $host,
                'sort' => $sort
            ]
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/sidebar', array(), TRUE);
        
        // Load the jobs management content as a string
        $data['body'] = $this->load->view('admin/jobs/jobs_management', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
    
    /**
     * Admin Job Creation - Show create job form
     */
    public function create_job()
    {
        // Load required models
        $this->load->model('M_jobs');
        
        $data = [
            'title' => 'Create New Job',
            'page_icon' => 'fas fa-plus-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'admin/dashboard'],
                ['title' => 'Job Management', 'url' => 'admin/jobs'],
                ['title' => 'Create Job', 'url' => '', 'active' => true]
            ]
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/sidebar', array(), TRUE);
        
        // Load the job creation content as a string
        $data['body'] = $this->load->view('admin/jobs/job_create', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
    
    /**
     * Admin Job Creation - Process create job form
     */
    public function process_create_job()
    {
        // Load required models
        $this->load->model('M_jobs');
        
        // Set validation rules
        $this->form_validation->set_rules('title', 'Job Title', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'required|trim');
        $this->form_validation->set_rules('address', 'Address', 'required|trim|max_length[500]');
        $this->form_validation->set_rules('city', 'City', 'required|trim|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'required|trim|max_length[50]');
        $this->form_validation->set_rules('suggested_price', 'Suggested Price', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('estimated_duration', 'Estimated Duration', 'required|integer|greater_than[0]');
        
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/create_job');
        }
        
        // Process extras (checkbox array to JSON)
        $extras = $this->input->post('extras');
        $extras_json = json_encode($extras && is_array($extras) ? $extras : []);
        
        // Process rooms (single value to JSON array)
        $rooms = $this->input->post('rooms');
        $rooms_json = json_encode($rooms ? [$rooms] : []);
        
        // Parse date_time into separate date and time
        $date_time = $this->input->post('date_time');
        $scheduled_date = '';
        $scheduled_time = '';
        if ($date_time) {
            $datetime_obj = DateTime::createFromFormat('Y-m-d\TH:i', $date_time);
            if ($datetime_obj) {
                $scheduled_date = $datetime_obj->format('Y-m-d');
                $scheduled_time = $datetime_obj->format('H:i:s');
            }
        }
        
        // Prepare job data
        $job_data = [
            'host_id' => $this->auth_user_id, // Admin creating the job
            'title' => trim($this->input->post('title')),
            'description' => trim($this->input->post('description')),
            'address' => trim($this->input->post('address')),
            'city' => trim($this->input->post('city')),
            'state' => trim($this->input->post('state')),
            'scheduled_date' => $scheduled_date,
            'scheduled_time' => $scheduled_time,
            'estimated_duration' => (int)$this->input->post('estimated_duration'),
            'rooms' => $rooms_json,
            'extras' => $extras_json,
            'pets' => $this->input->post('pets') ? 1 : 0,
            'special_instructions' => trim($this->input->post('notes')),
            'suggested_price' => (float)$this->input->post('suggested_price'),
            'status' => 'open'
        ];
        
        // Create the job
        $job_id = $this->M_jobs->create_job($job_data);
        
        if ($job_id) {
            $this->session->set_flashdata('success', 'Job created successfully!');
            redirect('admin/my_jobs');
        } else {
            $this->session->set_flashdata('error', 'Failed to create job. Please try again.');
            redirect('admin/create_job');
        }
    }
    
    /**
     * Admin My Jobs - Show admin's own jobs
     */
    public function my_jobs()
    {
        // Load required models
        $this->load->model('M_jobs');
        
        // Get filter parameters
        $search = $this->input->get('search');
        $status = $this->input->get('status');
        $sort = $this->input->get('sort');
        
        // Parse sort parameter
        $sort_by = 'created_at';
        $sort_order = 'DESC';
        if ($sort) {
            $sort_parts = explode('_', $sort);
            if (count($sort_parts) == 2) {
                $sort_by = $sort_parts[0];
                $sort_order = strtoupper($sort_parts[1]);
            }
        }
        
        // Get pagination parameters
        $page = $this->input->get('page') ?: 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;
        
        // Prepare filters for model
        $filters = [
            'search' => $search,
            'status' => $status,
            'host' => $this->auth_user_id // Only admin's own jobs
        ];
        
        // Get jobs with pagination
        $jobs = $this->M_jobs->get_all_jobs_admin($filters, $per_page, $offset, $sort_by, $sort_order);
        $total_jobs = $this->M_jobs->count_all_jobs_admin($filters);
        
        // Get job statistics for admin's jobs
        $stats = $this->M_jobs->get_host_stats($this->auth_user_id);
        
        // Calculate pagination
        $total_pages = ceil($total_jobs / $per_page);
        $pagination = [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'per_page' => $per_page,
            'total_items' => $total_jobs,
            'has_prev' => $page > 1,
            'has_next' => $page < $total_pages,
            'prev_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $total_pages ? $page + 1 : null
        ];
        
        $data = [
            'title' => 'My Jobs',
            'page_icon' => 'fas fa-clipboard-list',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'admin/dashboard'],
                ['title' => 'My Jobs', 'url' => '', 'active' => true]
            ],
            'jobs' => $jobs,
            'stats' => $stats,
            'pagination' => $pagination,
            'filters' => [
                'search' => $search,
                'status' => $status,
                'sort' => $sort
            ],
            'view_filters' => [
                'search' => $search,
                'status' => $status,
                'sort' => $sort
            ]
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/sidebar', array(), TRUE);
        
        // Load the my jobs content as a string
        $data['body'] = $this->load->view('admin/jobs/my_jobs', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Admin Job Management - View job details
     */
    public function view_job($job_id)
    {
        // Load required models
        $this->load->model('M_jobs');
        $this->load->model('M_offers');
        
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            show_404();
        }
        
        $data = [
            'title' => 'Job Details - ' . $job->title,
            'page_icon' => 'fas fa-clipboard-list',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'admin/dashboard'],
                ['title' => 'Job Management', 'url' => 'admin/jobs'],
                ['title' => $job->title, 'url' => '', 'active' => true]
            ],
            'job' => $job,
            'offers' => $this->M_offers->get_offers_by_job($job_id),
            'is_admin_view' => true
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/sidebar', array(), TRUE);
        
        // Load the job details content as a string
        $data['body'] = $this->load->view('admin/jobs/job_details', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
    
    /**
     * Admin Job Management - Edit job
     */
    public function edit_job($job_id)
    {
        // Load required models
        $this->load->model('M_jobs');
        
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            show_404();
        }
        
        $data = [
            'title' => 'Edit Job - ' . $job->title,
            'page_icon' => 'fas fa-edit',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'admin/dashboard'],
                ['title' => 'Job Management', 'url' => 'admin/jobs'],
                ['title' => 'Edit Job', 'url' => '', 'active' => true]
            ],
            'job' => $job
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/sidebar', array(), TRUE);
        
        // Load the job edit content as a string
        $data['body'] = $this->load->view('admin/jobs/job_edit', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
    
    /**
     * Admin Job Management - Process job edit
     */
    public function process_edit_job($job_id)
    {
        // Load required models
        $this->load->model('M_jobs');
        
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            show_404();
        }
        
        // Set validation rules
        $this->form_validation->set_rules('title', 'Job Title', 'required|trim|max_length[255]');
        $this->form_validation->set_rules('description', 'Description', 'required|trim');
        $this->form_validation->set_rules('address', 'Address', 'required|trim');
        $this->form_validation->set_rules('city', 'City', 'required|trim');
        $this->form_validation->set_rules('state', 'State', 'required|trim');
        $this->form_validation->set_rules('scheduled_date', 'Scheduled Date', 'required|trim');
        $this->form_validation->set_rules('scheduled_time', 'Scheduled Time', 'required|trim');
        $this->form_validation->set_rules('estimated_duration', 'Estimated Duration', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('suggested_price', 'Suggested Price', 'required|numeric|greater_than[0]');
        $this->form_validation->set_rules('status', 'Status', 'required|in_list[open,active,assigned,completed,cancelled]');
        
        if ($this->form_validation->run() == FALSE) {
            // Validation failed, redirect back to edit form
            $this->session->set_flashdata('text', validation_errors());
            $this->session->set_flashdata('type', 'error');
            redirect('admin/edit_job/' . $job_id);
        } else {
            // Process extras (checkbox array to JSON)
            $extras = $this->input->post('extras');
            $extras_json = json_encode($extras && is_array($extras) ? $extras : []);
            
            // Process rooms (single value to JSON array)
            $rooms = $this->input->post('rooms');
            $rooms_json = json_encode($rooms ? [$rooms] : []);
            
            // Prepare job data
            $job_data = [
                'title' => trim($this->input->post('title')),
                'description' => trim($this->input->post('description')),
                'address' => trim($this->input->post('address')),
                'city' => trim($this->input->post('city')),
                'state' => trim($this->input->post('state')),
                'scheduled_date' => trim($this->input->post('scheduled_date')),
                'scheduled_time' => trim($this->input->post('scheduled_time')),
                'estimated_duration' => (int)$this->input->post('estimated_duration'),
                'rooms' => $rooms_json,
                'extras' => $extras_json,
                'pets' => $this->input->post('pets') ? 1 : 0,
                'special_instructions' => trim($this->input->post('special_instructions')),
                'suggested_price' => (float)$this->input->post('suggested_price'),
                'status' => trim($this->input->post('status'))
            ];
            
            // Update the job
            $updated = $this->M_jobs->update_job($job_id, $job_data);
            
            if ($updated) {
                $this->session->set_flashdata('text', 'Job updated successfully!');
                $this->session->set_flashdata('type', 'success');
                redirect('admin/jobs');
            } else {
                $this->session->set_flashdata('text', 'Failed to update job. Please try again.');
                $this->session->set_flashdata('type', 'error');
                redirect('admin/edit_job/' . $job_id);
            }
        }
    }
    
    /**
     * Admin Job Management - Cancel job (AJAX)
     */
    public function cancel_job()
    {
        // Check if user is logged in and is an admin
        if (!$this->require_min_level(9)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $job_id = $this->input->post('job_id');
        
        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }
        
        // Load required models
        $this->load->model('M_jobs');
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            echo json_encode(['success' => false, 'message' => 'Job not found']);
            return;
        }
        
        // Cancel the job
        $result = $this->M_jobs->update_job_status($job_id, 'cancelled');
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Job cancelled successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to cancel job']);
        }
    }
    
    /**
     * Admin Job Management - Delete job (AJAX)
     */
    public function delete_job()
    {
        // Check if user is logged in and is an admin
        if (!$this->require_min_level(9)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $job_id = $this->input->post('job_id');
        
        // Debug: Log the job ID
        log_message('debug', 'Admin delete_job - Job ID: ' . $job_id);
        
        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }
        
        // Load required models
        $this->load->model('M_jobs');
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        // Debug: Log job details
        log_message('debug', 'Admin delete_job - Job found: ' . ($job ? 'YES' : 'NO'));
        if ($job) {
            log_message('debug', 'Admin delete_job - Job status: ' . $job->status);
        }
        
        if (!$job) {
            echo json_encode(['success' => false, 'message' => 'Job not found']);
            return;
        }
        
        // Only allow deletion of cancelled jobs
        if ($job->status != 'cancelled') {
            echo json_encode(['success' => false, 'message' => 'Only cancelled jobs can be deleted. Current status: ' . $job->status]);
            return;
        }
        
        // Delete the job (hard delete)
        $result = $this->M_jobs->hard_delete_job($job_id);
        
        // Debug: Log the result
        log_message('debug', 'Admin delete_job - Hard delete result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Job deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete job. Check logs for details.']);
        }
    }

    /**
     * Flag Management - List all active flags
     */
    public function flags()
    {
        // Get filter parameters
        $page = $this->input->get('page') ?: 1;
        $per_page = 20;
        $offset = ($page - 1) * $per_page;

        // Get active flags
        $flags = $this->M_job_flags->get_active_flags($per_page, $offset);
        $total_flags = $this->M_job_flags->count_active_flags();

        // Get flag statistics
        $stats = $this->M_job_flags->get_flag_stats();

        // Calculate pagination
        $total_pages = ceil($total_flags / $per_page);
        $pagination = [
            'current_page' => $page,
            'total_pages' => $total_pages,
            'per_page' => $per_page,
            'total_items' => $total_flags,
            'has_prev' => $page > 1,
            'has_next' => $page < $total_pages,
            'prev_page' => $page > 1 ? $page - 1 : null,
            'next_page' => $page < $total_pages ? $page + 1 : null
        ];

        $data = [
            'title' => 'Flag Management',
            'page_icon' => 'fas fa-flag',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'admin/dashboard'],
                ['title' => 'Flag Management', 'url' => '', 'active' => true]
            ],
            'flags' => $flags,
            'stats' => $stats,
            'pagination' => $pagination
        ];

        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/sidebar', array(), TRUE);

        // Load the flags management content as a string
        $data['body'] = $this->load->view('admin/flags/flags_management', $data, TRUE);

        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Resolve a flag (AJAX)
     */
    public function resolve_flag()
    {
        // Set JSON header
        header('Content-Type: application/json');

        // Check if user is logged in and is an admin
        if (!$this->require_min_level(9)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $flag_id = $this->input->post('flag_id');
        $resolution_notes = $this->input->post('resolution_notes');

        if (!$flag_id) {
            echo json_encode(['success' => false, 'message' => 'Flag ID is required']);
            return;
        }

        $result = $this->M_job_flags->resolve_flag($flag_id, $this->auth_user_id, $resolution_notes);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Flag resolved successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to resolve flag']);
        }
    }

    /**
     * Dismiss a flag (AJAX)
     */
    public function dismiss_flag()
    {
        // Set JSON header
        header('Content-Type: application/json');

        // Check if user is logged in and is an admin
        if (!$this->require_min_level(9)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $flag_id = $this->input->post('flag_id');
        $resolution_notes = $this->input->post('resolution_notes');

        if (!$flag_id) {
            echo json_encode(['success' => false, 'message' => 'Flag ID is required']);
            return;
        }

        $result = $this->M_job_flags->dismiss_flag($flag_id, $this->auth_user_id, $resolution_notes);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Flag dismissed successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to dismiss flag']);
        }
    }

    /**
     * Flag a job (AJAX) - Available to hosts, cleaners, and admins
     */
    public function flag_job()
    {
        // Set JSON header
        header('Content-Type: application/json');

        // Check if user is logged in
        if (!$this->auth_user_id) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $job_id = $this->input->post('job_id');
        $flag_reason = $this->input->post('flag_reason');
        $flag_details = $this->input->post('flag_details');

        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }

        // Determine user type based on auth level
        $user_type = 'host'; // default
        $auth_level = $this->session->userdata('auth_level');
        if ($auth_level == 3) {
            $user_type = 'cleaner';
        } elseif ($auth_level == 9) {
            $user_type = 'admin';
        }

        $result = $this->M_job_flags->flag_job($job_id, $this->auth_user_id, $user_type, $flag_reason, $flag_details);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Job flagged successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to flag job or you have already flagged this job']);
        }
    }

}
