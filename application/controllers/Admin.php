<?php

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('Form_validation');
        $this->load->library('grocery_CRUD');

        $this->load->helper('form');
        $this->load->helper('Breadcrumb_helper');

        $this->load->model('M_users');

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
        
        $view["title"] = 'Dashboard'; 
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", array('pending_users_count' => count($pending_users)), TRUE);
        $view["body"] = $this->load->view("admin/dashboard", array(
            'total_users' => $stats->total_users,
            'active_users' => $stats->active_users,
            'banned_users' => $stats->banned_users,
            'pending_users_count' => count($pending_users)
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
        
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required|max_length[12]|is_unique[users.username]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('passwd', 'Password', 'required|min_length[8]|callback_password_strength_check');
        $this->form_validation->set_rules('passwd_confirm', 'Confirm Password', 'required|matches[passwd]');
        $this->form_validation->set_rules('first_name', 'First Name', 'max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'max_length[50]');
        $this->form_validation->set_rules('phone', 'Phone', 'max_length[20]');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'callback_validate_date_of_birth');
        $this->form_validation->set_rules('auth_level', 'User Level', 'required|in_list[3,6,9]');
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
        
        // Keep user banned and pending verification, but mark as rejected
        $update_data = array(
            'banned' => '1',
            'pending_verification' => '0' // Remove from pending list
        );
        
        // Add optional fields if they exist
        $columns = $this->db->list_fields('users');
        if (in_array('banned_reason', $columns)) {
            $update_data['banned_reason'] = 'Account rejected by admin';
        }
        if (in_array('banned_at', $columns)) {
            $update_data['banned_at'] = date('Y-m-d H:i:s');
        }
        if (in_array('banned_by', $columns)) {
            $update_data['banned_by'] = $this->session->userdata('user_id') ?: 'admin';
        }
        if (in_array('modified_by', $columns)) {
            $update_data['modified_by'] = $this->session->userdata('user_id') ?: 'admin';
        }
        
        $this->db->where('user_id', $user_id);
        if ($this->db->update('users', $update_data)) {
            $this->session->set_flashdata('text', 'User rejected successfully!');
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

}
