<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Host Controller
 * 
 * Handles all host-related functionality including:
 * - Job creation and management
 * - Offer review and selection
 * - Payment processing
 * - Dashboard and analytics
 */
class Host extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load required helpers and libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Load required models
        $this->load->model('M_users');
        
        // Load marketplace models only if tables exist
        if ($this->db->table_exists('jobs')) {
            $this->load->model('M_jobs');
        }
        if ($this->db->table_exists('offers')) {
            $this->load->model('M_offers');
        }
        
        // Check if user is logged in and is a host
        if (!$this->require_min_level(6)) { // Host level = 6
            redirect('app/login');
        }
        
        // Clear flash messages
        $this->session->unset_userdata('text');
        $this->session->unset_userdata('type');
    }

    /**
     * Host Dashboard
     * Main dashboard showing job statistics, recent activity, and quick actions
     */
    public function index()
    {
        $user_id = $this->auth_user_id;
        
        // Initialize dashboard data
        $data = [
            'title' => 'Host Dashboard',
            'page_icon' => 'fas fa-home',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'marketplace_ready' => false
        ];
        
        // Check if marketplace tables exist and load data accordingly
        if ($this->db->table_exists('jobs') && isset($this->M_jobs)) {
            $data['stats'] = $this->M_jobs->get_host_stats($user_id);
            $data['recent_jobs'] = $this->M_jobs->get_host_recent_jobs($user_id, 5);
            $data['marketplace_ready'] = true;
        } else {
            // Provide default stats when marketplace tables don't exist
            $data['stats'] = [
                'total_jobs' => 0,
                'active_jobs' => 0,
                'completed_jobs' => 0,
                'total_spent' => 0
            ];
            $data['recent_jobs'] = [];
        }
        
        if ($this->db->table_exists('offers') && isset($this->M_offers)) {
            $data['pending_offers'] = $this->M_offers->get_pending_offers_for_host($user_id, 5);
        } else {
            $data['pending_offers'] = [];
        }
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the dashboard content as a string
        $data['body'] = $this->load->view('host/dashboard', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Create New Job
     * Form to create a new cleaning job
     */
    public function create_job()
    {
        $data = [
            'title' => 'Create New Job',
            'page_icon' => 'fas fa-plus-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Create Job', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($this->auth_user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the job creation content as a string
        $data['body'] = $this->load->view('host/job_create', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Process Job Creation
     * Handle form submission for new job creation
     */
    public function process_create_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        // Debug: Log all POST data
        log_message('debug', 'POST data received: ' . print_r($this->input->post(), true));
        
        // Set validation rules
        $this->form_validation->set_rules('title', 'Job Title', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('description', 'Description', 'required|min_length[20]|max_length[1000]');
        $this->form_validation->set_rules('address', 'Address', 'required|min_length[10]|max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('date_time', 'Date & Time', 'required');
        $this->form_validation->set_rules('estimated_duration', 'Estimated Duration', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('rooms', 'Number of Rooms', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('suggested_price', 'Suggested Price', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() === FALSE) {
            // Validation failed, redirect back with errors
            $errors = validation_errors();
            log_message('error', 'Validation failed: ' . $errors);
            $this->session->set_flashdata('text', 'Validation failed: ' . $errors);
            $this->session->set_flashdata('type', 'error');
            redirect('host/create_job');
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
        
        // Validate that we have the required date/time values
        if (empty($scheduled_date) || empty($scheduled_time)) {
            $this->session->set_flashdata('text', 'Please select a valid date and time.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/create_job');
        }
        
        // Validate required fields are not empty
        $required_fields = [
            'title' => $this->input->post('title'),
            'description' => $this->input->post('description'),
            'address' => $this->input->post('address'),
            'city' => $this->input->post('city'),
            'state' => $this->input->post('state'),
            'estimated_duration' => $this->input->post('estimated_duration'),
            'suggested_price' => $this->input->post('suggested_price')
        ];
        
        foreach ($required_fields as $field => $value) {
            if (empty($value)) {
                $this->session->set_flashdata('text', "Please fill in the {$field} field.");
                $this->session->set_flashdata('type', 'error');
                redirect('host/create_job');
            }
        }
        
        // Prepare job data according to database schema
        $job_data = [
            'host_id' => $this->auth_user_id,
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
        
        // Debug: Log the job data being submitted
        log_message('debug', 'Job data being submitted: ' . print_r($job_data, true));
        
        // Check if M_jobs model exists
        if (!isset($this->M_jobs)) {
            $this->session->set_flashdata('text', 'Jobs functionality not available. Please check database setup.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/create_job');
        }
        
        // Create the job
        $job_id = $this->M_jobs->create_job($job_data);
        
        log_message('debug', 'Job creation result: ' . ($job_id ? $job_id : 'FAILED'));
        
        if ($job_id) {
            $this->session->set_flashdata('text', 'Job created successfully!');
            $this->session->set_flashdata('type', 'success');
            redirect('host/jobs');
        } else {
            // Get the last database error
            $db_error = $this->db->error();
            $error_message = 'Failed to create job. ';
            if (!empty($db_error['message'])) {
                $error_message .= 'Database error: ' . $db_error['message'];
            } else {
                $error_message .= 'Please try again.';
            }
            
            log_message('error', 'Job creation failed: ' . print_r($db_error, true));
            
            $this->session->set_flashdata('text', $error_message);
            $this->session->set_flashdata('type', 'error');
            redirect('host/create_job');
        }
    }

    /**
     * View Job Details
     * Show job details, offers, and management options
     */
    public function job($job_id)
    {
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->host_id != $this->auth_user_id) {
            show_404();
        }
        
        $data = [
            'title' => 'Job Details - ' . $job->title,
            'page_icon' => 'fas fa-clipboard-list',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Jobs', 'url' => 'host/jobs'],
                ['title' => $job->title, 'url' => '', 'active' => true]
            ],
            'job' => $job,
            'offers' => $this->M_offers->get_offers_by_job($job_id),
            'user_info' => $this->M_users->get_user_by_id($this->auth_user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the job details content as a string
        $data['body'] = $this->load->view('host/job_details', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Accept Offer
     * Accept a cleaner's offer for a job
     */
    public function accept_offer($offer_id)
    {
        $offer = $this->M_offers->get_offer_by_id($offer_id);
        
        if (!$offer) {
            show_404();
        }
        
        $job = $this->M_jobs->get_job_by_id($offer->job_id);
        
        if (!$job || $job->host_id != $this->auth_user_id) {
            show_404();
        }
        
        // Accept the offer and create job assignment
        if ($this->M_offers->accept_offer($offer_id, $this->auth_user_id)) {
            $this->session->set_flashdata('text', 'Offer accepted successfully!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to accept offer. Please try again.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('host/job/' . $offer->job_id);
    }

    /**
     * Reject Offer
     * Reject a cleaner's offer
     */
    public function reject_offer($offer_id)
    {
        $offer = $this->M_offers->get_offer_by_id($offer_id);
        
        if (!$offer) {
            show_404();
        }
        
        $job = $this->M_jobs->get_job_by_id($offer->job_id);
        
        if (!$job || $job->host_id != $this->auth_user_id) {
            show_404();
        }
        
        if ($this->M_offers->reject_offer($offer_id)) {
            $this->session->set_flashdata('text', 'Offer rejected.');
            $this->session->set_flashdata('type', 'info');
        } else {
            $this->session->set_flashdata('text', 'Failed to reject offer. Please try again.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('host/job/' . $offer->job_id);
    }

    /**
     * My Jobs
     * List all jobs created by the host
     */
    public function jobs()
    {
        $host_id = $this->auth_user_id;
        
        // Debug: Check if M_jobs model exists
        if (!isset($this->M_jobs)) {
            log_message('error', 'M_jobs model not loaded in jobs() method');
            $jobs = [];
        } else {
            $jobs = $this->M_jobs->get_jobs_by_host($host_id);
            log_message('debug', 'Found ' . count($jobs) . ' jobs for host ID: ' . $host_id);
        }
        
        $data = [
            'title' => 'My Jobs',
            'page_icon' => 'fas fa-clipboard-list',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'My Jobs', 'url' => '', 'active' => true]
            ],
            'jobs' => $jobs,
            'user_info' => $this->M_users->get_user_by_id($host_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the jobs list content as a string
        $data['body'] = $this->load->view('host/jobs_list', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Edit Job
     * Show form to edit an existing job
     */
    public function edit_job($job_id)
    {
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->host_id != $this->auth_user_id) {
            show_404();
        }
        
        $data = [
            'title' => 'Edit Job',
            'page_icon' => 'fas fa-edit',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'My Jobs', 'url' => 'host/jobs'],
                ['title' => 'Edit Job', 'url' => '', 'active' => true]
            ],
            'job' => $job,
            'user_info' => $this->M_users->get_user_by_id($this->auth_user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the edit job content as a string
        $data['body'] = $this->load->view('host/job_edit', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Process Job Edit
     * Handle form submission for job editing
     */
    public function process_edit_job($job_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        // Get job details and verify ownership
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job || $job->host_id != $this->auth_user_id) {
            show_404();
        }
        
        // Set validation rules (same as create)
        $this->form_validation->set_rules('title', 'Job Title', 'required|min_length[5]|max_length[100]');
        $this->form_validation->set_rules('description', 'Description', 'required|min_length[20]|max_length[1000]');
        $this->form_validation->set_rules('address', 'Address', 'required|min_length[10]|max_length[255]');
        $this->form_validation->set_rules('city', 'City', 'required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('state', 'State', 'required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('date_time', 'Date & Time', 'required');
        $this->form_validation->set_rules('estimated_duration', 'Estimated Duration', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('rooms', 'Number of Rooms', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('suggested_price', 'Suggested Price', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('text', 'Please correct the errors below.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/edit_job/' . $job_id);
        }
        
        // Process data (same as create)
        $extras = $this->input->post('extras');
        $extras_json = json_encode($extras && is_array($extras) ? $extras : []);
        
        $rooms = $this->input->post('rooms');
        $rooms_json = json_encode($rooms ? [$rooms] : []);
        
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
        
        if (empty($scheduled_date) || empty($scheduled_time)) {
            $this->session->set_flashdata('text', 'Please select a valid date and time.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/edit_job/' . $job_id);
        }
        
        // Prepare update data
        $update_data = [
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
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Update the job
        if ($this->M_jobs->update_job($job_id, $update_data)) {
            $this->session->set_flashdata('text', 'Job updated successfully!');
            $this->session->set_flashdata('type', 'success');
            redirect('host/jobs');
        } else {
            $this->session->set_flashdata('text', 'Failed to update job. Please try again.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/edit_job/' . $job_id);
        }
    }

    /**
     * Cancel Job
     * Cancel a job via AJAX
     */
    public function cancel_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $job_id = $this->input->post('job_id');
        
        // Get job details and verify ownership
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job || $job->host_id != $this->auth_user_id) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Job not found or access denied'
            ]));
            return;
        }
        
        // Update job status to cancelled
        $update_data = [
            'status' => 'cancelled',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->M_jobs->update_job($job_id, $update_data)) {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'success' => true,
                'message' => 'Job cancelled successfully'
            ]));
        } else {
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Failed to cancel job'
            ]));
        }
    }
    
    /**
     * Delete a job (AJAX endpoint)
     */
    public function delete_job()
    {
        // Check if user is logged in and is a host
        if (!$this->require_min_level(6)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $job_id = $this->input->post('job_id');
        
        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }
        
        // Check if M_jobs model exists
        if (!isset($this->M_jobs)) {
            echo json_encode(['success' => false, 'message' => 'Jobs functionality not available']);
            return;
        }
        
        // Get job details to verify ownership
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            echo json_encode(['success' => false, 'message' => 'Job not found']);
            return;
        }
        
        // Verify the job belongs to this host
        if ($job->host_id != $this->auth_user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized to delete this job']);
            return;
        }
        
        // Only allow deletion of cancelled jobs
        if ($job->status != 'cancelled') {
            echo json_encode(['success' => false, 'message' => 'Only cancelled jobs can be deleted']);
            return;
        }
        
        // Delete the job (hard delete)
        $result = $this->M_jobs->hard_delete_job($job_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Job deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete job']);
        }
    }
}
