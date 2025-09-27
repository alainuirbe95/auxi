<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User Profile Controller
 * 
 * Handles common functionality accessible to all user levels (3 and above)
 * including password changes, profile management, etc.
 */
class UserProfile extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load required helpers and libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Load required models
        $this->load->model('M_users');
        
        // Initialize session and check if user is logged in (level 3 and above)
        $this->init_session_auto(3); // Minimum level 3 (cleaner, host, admin)
        
        // Clear flash messages
        $this->session->unset_userdata('text');
        $this->session->unset_userdata('type');
    }

    /**
     * Display change password form for any user level
     */
    public function change_password()
    {
        // Get current user info - try different session field names (exact copy from admin)
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
        
        // Determine user level and set appropriate sidebar and breadcrumbs
        $auth_level = $this->session->userdata('auth_level');
        $sidebar_view = 'admin/template/sidebar'; // Default to admin
        $dashboard_url = 'admin/dashboard';
        $user_type = 'Admin';
        
        if ($auth_level == 3) {
            $sidebar_view = 'admin/template/cleaner_sidebar';
            $dashboard_url = 'cleaner';
            $user_type = 'Cleaner';
        } elseif ($auth_level == 6) {
            $sidebar_view = 'admin/template/host_sidebar';
            $dashboard_url = 'host/dashboard';
            $user_type = 'Host';
        }
        
        $data = [
            'title' => 'Change Password',
            'page_icon' => 'key',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => $dashboard_url],
                ['title' => 'Change Password', 'url' => '', 'active' => true]
            ],
            'user_info' => $user_info,
            'user_type' => $user_type
        ];
        
        // Load appropriate sidebar
        $data['sidebar'] = $this->load->view($sidebar_view, NULL, TRUE);
        
        // Load the main content (reuse admin's change password view)
        $data['body'] = $this->load->view("admin/change_password", array('user_info' => $user_info), TRUE);
        
        // Load the layout
        $this->load->view("admin/template/layout_with_sidebar", $data);
    }

    /**
     * Process password change for any user level
     */
    public function update_password()
    {
        // Get current user info - try different session field names (exact copy from admin)
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
}
