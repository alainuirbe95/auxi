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
        if ($this->db->table_exists('job_flags')) {
            $this->load->model('M_job_flags');
        }
        
        // Initialize session and check if user is logged in and is a host
        $this->init_session_auto(6); // Host level = 6
        
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
            $data['recent_jobs'] = $this->M_jobs->get_host_recent_jobs($user_id, 10);
            $data['pending_offers'] = $this->M_jobs->get_host_pending_offers($user_id, 10);
            $data['pending_completed'] = $this->M_jobs->get_host_pending_completed($user_id, 10);
            $data['marketplace_ready'] = true;
        } else {
            // Provide default stats when marketplace tables don't exist
            $data['stats'] = [
                'total_jobs' => 0,
                'active_jobs' => 0,
                'live_disputes' => 0,
                'completed_jobs' => 0,
                'closed_jobs' => 0,
                'pending_offers' => 0,
                'pending_completed' => 0
            ];
            $data['recent_jobs'] = [];
            $data['pending_offers'] = [];
            $data['pending_completed'] = [];
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
     * View Job Details (Alias for job method)
     * Show job details, offers, and management options
     */
    public function view_job($job_id)
    {
        return $this->job($job_id);
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
        
        // Get dispute and price adjustment information
        $dispute_info = null;
        $price_adjustments = [];
        
        if ($job->status === 'disputed' || ($job->status === 'closed' && $job->dispute_resolution)) {
            // Get dispute information
            $dispute_info = [
                'disputed_at' => $job->disputed_at,
                'dispute_reason' => $job->dispute_reason,
                'dispute_resolution' => $job->dispute_resolution,
                'dispute_resolution_notes' => $job->dispute_resolution_notes,
                'dispute_resolved_at' => $job->dispute_resolved_at,
                'payment_amount' => $job->payment_amount
            ];
        }
        
        if ($job->status === 'price_adjustment_requested') {
            // Get price adjustment requests
            $this->load->model('M_counter_offers');
            $price_adjustments = $this->M_counter_offers->get_counter_offers_for_job($job_id);
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
            'user_info' => $this->M_users->get_user_by_id($this->auth_user_id),
            'dispute_info' => $dispute_info,
            'price_adjustments' => $price_adjustments
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
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
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
        
        redirect('host/offers');
    }

    /**
     * Reject Offer
     * Reject a cleaner's offer
     */
    public function reject_offer($offer_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
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
        
        redirect('host/offers');
    }

    /**
     * View Expired Jobs
     * Show all expired jobs for the host
     */
    public function expired_jobs()
    {
        $user_id = $this->auth_user_id;
        
        // Get filter parameters
        $search_term = $this->input->get('search');
        $sort_by = $this->input->get('sort') ?: 'scheduled_date';
        $sort_order = $this->input->get('order') ?: 'DESC';
        
        // Get expired jobs
        $expired_jobs = [];
        if (isset($this->M_jobs)) {
            $expired_jobs = $this->M_jobs->get_host_expired_jobs($user_id);
            
            // Apply search filter
            if (!empty($search_term)) {
                $expired_jobs = array_filter($expired_jobs, function($job) use ($search_term) {
                    return stripos($job->title, $search_term) !== false || 
                           stripos($job->description, $search_term) !== false ||
                           stripos($job->address, $search_term) !== false;
                });
            }
            
            // Sort jobs
            usort($expired_jobs, function($a, $b) use ($sort_by, $sort_order) {
                switch ($sort_by) {
                    case 'scheduled_date':
                        $a_val = strtotime($a->scheduled_date ?? $a->created_at);
                        $b_val = strtotime($b->scheduled_date ?? $b->created_at);
                        break;
                    case 'title':
                        $a_val = $a->title;
                        $b_val = $b->title;
                        break;
                    case 'price':
                        $a_val = $a->suggested_price;
                        $b_val = $b->suggested_price;
                        break;
                    default:
                        $a_val = strtotime($a->created_at);
                        $b_val = strtotime($b->created_at);
                }
                
                if ($sort_order === 'DESC') {
                    return $b_val <=> $a_val;
                } else {
                    return $a_val <=> $b_val;
                }
            });
        }
        
        $data = [
            'title' => 'Expired Jobs',
            'page_icon' => 'fas fa-clock',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Expired Jobs', 'url' => '', 'active' => true]
            ],
            'expired_jobs' => $expired_jobs,
            'filters' => [
                'search' => $search_term,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order
            ]
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the expired jobs content as a string
        $data['body'] = $this->load->view('host/expired_jobs', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * View All Offers
     * Show all offers for host's jobs (today and future only)
     */
    public function offers()
    {
        $user_id = $this->auth_user_id;
        
        // Get filter parameters
        $status_filter = $this->input->get('status');
        $search_term = $this->input->get('search');
        $sort_by = $this->input->get('sort') ?: 'scheduled_date';
        $sort_order = $this->input->get('order') ?: 'ASC';
        
        // Get all jobs with offers for this host (today and future only)
        $jobs_with_offers = [];
        $total_offers = 0;
        $pending_offers = 0;
        $counter_offers = 0;
        $accepted_offers = 0;
        
        if ($this->db->table_exists('jobs') && $this->db->table_exists('offers')) {
            // Get jobs for this host (today and future only)
            $jobs = $this->M_jobs->get_host_active_jobs($user_id);
            
            foreach ($jobs as $job) {
                $offers = $this->M_offers->get_offers_by_job($job->id);
                
                if (!empty($offers)) {
                    $job->offers = $offers;
                    $jobs_with_offers[] = $job;
                    
                    foreach ($offers as $offer) {
                        $total_offers++;
                        
                        if ($offer->status === 'pending') {
                            $pending_offers++;
                        } elseif ($offer->status === 'accepted') {
                            $accepted_offers++;
                        }
                        
                        if ($offer->offer_type === 'counter') {
                            $counter_offers++;
                        }
                    }
                }
            }
            
            // Sort jobs by scheduled date
            usort($jobs_with_offers, function($a, $b) use ($sort_by, $sort_order) {
                switch ($sort_by) {
                    case 'scheduled_date':
                        $a_val = strtotime($a->scheduled_date ?? $a->created_at);
                        $b_val = strtotime($b->scheduled_date ?? $b->created_at);
                        break;
                    case 'title':
                        $a_val = $a->title;
                        $b_val = $b->title;
                        break;
                    case 'price':
                        $a_val = $a->suggested_price;
                        $b_val = $b->suggested_price;
                        break;
                    case 'offers_count':
                        $a_val = count($a->offers);
                        $b_val = count($b->offers);
                        break;
                    default:
                        $a_val = strtotime($a->created_at);
                        $b_val = strtotime($b->created_at);
                }
                
                if ($sort_order === 'DESC') {
                    return $b_val <=> $a_val;
                } else {
                    return $a_val <=> $b_val;
                }
            });
        }
        
        $data = [
            'title' => 'Job Offers Management',
            'page_icon' => 'fas fa-handshake',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Offers', 'url' => '', 'active' => true]
            ],
            'jobs_with_offers' => $jobs_with_offers,
            'total_offers' => $total_offers,
            'pending_offers' => $pending_offers,
            'counter_offers' => $counter_offers,
            'accepted_offers' => $accepted_offers,
            'filters' => [
                'status' => $status_filter,
                'search' => $search_term,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order
            ]
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the offers content as a string
        $data['body'] = $this->load->view('host/offers', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * My Jobs
     * List all jobs created by the host
     */
    public function jobs()
    {
        $host_id = $this->auth_user_id;
        
        // Get filter parameters
        $status_filter = $this->input->get('status');
        $search_term = $this->input->get('search');
        $sort_by = $this->input->get('sort') ?: 'created_at';
        $sort_order = $this->input->get('order') ?: 'DESC';
        $price_min = $this->input->get('price_min');
        $price_max = $this->input->get('price_max');
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        
        // Debug: Check if M_jobs model exists
        if (!isset($this->M_jobs)) {
            log_message('error', 'M_jobs model not loaded in jobs() method');
            $jobs = [];
            $total_jobs = 0;
        } else {
            // Get filtered and sorted jobs
            $jobs = $this->M_jobs->get_host_jobs_filtered($host_id, [
                'status' => $status_filter,
                'search' => $search_term,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order,
                'price_min' => $price_min,
                'price_max' => $price_max,
                'date_from' => $date_from,
                'date_to' => $date_to
            ]);
            
            // Get total count for pagination
            $total_jobs = $this->M_jobs->get_host_jobs_count_filtered($host_id, [
                'status' => $status_filter,
                'search' => $search_term,
                'price_min' => $price_min,
                'price_max' => $price_max,
                'date_from' => $date_from,
                'date_to' => $date_to
            ]);
            
            log_message('debug', 'Found ' . count($jobs) . ' filtered jobs for host ID: ' . $host_id);
        }
        
        // Get status counts for filter buttons
        $status_counts = [];
        if (isset($this->M_jobs)) {
            $status_counts = $this->M_jobs->get_host_job_status_counts($host_id);
        }
        
        $data = [
            'title' => 'My Jobs',
            'page_icon' => 'fas fa-clipboard-list',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'My Jobs', 'url' => '', 'active' => true]
            ],
            'jobs' => $jobs,
            'total_jobs' => $total_jobs,
            'status_counts' => $status_counts,
            'user_info' => $this->M_users->get_user_by_id($host_id),
            'filters' => [
                'status' => $status_filter,
                'search' => $search_term,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order,
                'price_min' => $price_min,
                'price_max' => $price_max,
                'date_from' => $date_from,
                'date_to' => $date_to
            ]
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
        // Set JSON header
        header('Content-Type: application/json');
        
        // Check if user is logged in and is a host
        if (!$this->require_min_level(6)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }
        
        $job_id = $this->input->post('job_id');
        
        // Debug: Log the request
        log_message('debug', 'Host delete_job - Job ID: ' . $job_id . ', Auth User ID: ' . $this->auth_user_id);
        
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
        
        // Debug: Log job details
        log_message('debug', 'Delete job - Job ID: ' . $job_id . ', Status: ' . $job->status . ', Host ID: ' . $job->host_id . ', Auth User ID: ' . $this->auth_user_id);
        
        // Verify the job belongs to this host
        if ($job->host_id != $this->auth_user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized to delete this job']);
            return;
        }
        
        // Only allow deletion of cancelled jobs (check both cases)
        if (strtolower($job->status) != 'cancelled') {
            echo json_encode(['success' => false, 'message' => 'Only cancelled jobs can be deleted. Current status: ' . $job->status]);
            return;
        }
        
        // Delete the job (hard delete)
        $result = $this->M_jobs->hard_delete_job($job_id);
        
        log_message('debug', 'Hard delete result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Job deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete job. Check logs for details.']);
        }
    }

    /**
     * Flag a job (AJAX) - Available to hosts
     */
    public function flag_job()
    {
        // Set JSON header
        header('Content-Type: application/json');

        // Check if user is logged in and is a host
        if (!$this->require_min_level(6)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        // Check if job_flags table exists
        if (!$this->db->table_exists('job_flags')) {
            echo json_encode(['success' => false, 'message' => 'Flagging system not available']);
            return;
        }

        $job_id = $this->input->post('job_id');
        $flag_reason = $this->input->post('flag_reason');
        $flag_details = $this->input->post('flag_details');

        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }

        $result = $this->M_job_flags->flag_job($job_id, $this->auth_user_id, 'host', $flag_reason, $flag_details);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Job flagged successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to flag job or you have already flagged this job']);
        }
    }

    /**
     * Repost a single expired job
     */
    public function repost_job()
    {
        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        
        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }
        
        // Get the expired job
        $expired_job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$expired_job || $expired_job->host_id != $user_id || $expired_job->status != 'expired') {
            echo json_encode(['success' => false, 'message' => 'Job not found or not expired']);
            return;
        }
        
        // Create new job data (copy from expired job)
        $new_job_data = [
            'host_id' => $expired_job->host_id,
            'title' => $expired_job->title,
            'description' => $expired_job->description,
            'address' => $expired_job->address,
            'city' => $expired_job->city,
            'state' => $expired_job->state,
            'zip_code' => $expired_job->zip_code,
            'latitude' => $expired_job->latitude,
            'longitude' => $expired_job->longitude,
            'scheduled_date' => date('Y-m-d'), // Set to today
            'scheduled_time' => date('H:i:s'), // Set to current time
            'estimated_duration' => $expired_job->estimated_duration,
            'rooms' => $expired_job->rooms,
            'extras' => $expired_job->extras,
            'pets' => $expired_job->pets,
            'pet_notes' => $expired_job->pet_notes,
            'special_instructions' => $expired_job->special_instructions,
            'suggested_price' => $expired_job->suggested_price,
            'status' => 'open',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $new_job_id = $this->M_jobs->create_job($new_job_data);
        
        if ($new_job_id) {
            echo json_encode(['success' => true, 'message' => 'Job reposted successfully', 'new_job_id' => $new_job_id]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to repost job']);
        }
    }

    /**
     * Bulk repost multiple expired jobs
     */
    public function bulk_repost_jobs()
    {
        $user_id = $this->auth_user_id;
        $job_ids = $this->input->post('job_ids');
        
        if (!$job_ids || !is_array($job_ids)) {
            echo json_encode(['success' => false, 'message' => 'No jobs selected']);
            return;
        }
        
        $success_count = 0;
        $errors = [];
        
        foreach ($job_ids as $job_id) {
            // Get the expired job
            $expired_job = $this->M_jobs->get_job_by_id($job_id);
            
            if (!$expired_job || $expired_job->host_id != $user_id || $expired_job->status != 'expired') {
                $errors[] = "Job #{$job_id}: Not found or not expired";
                continue;
            }
            
            // Create new job data
            $new_job_data = [
                'host_id' => $expired_job->host_id,
                'title' => $expired_job->title,
                'description' => $expired_job->description,
                'address' => $expired_job->address,
                'city' => $expired_job->city,
                'state' => $expired_job->state,
                'zip_code' => $expired_job->zip_code,
                'latitude' => $expired_job->latitude,
                'longitude' => $expired_job->longitude,
                'scheduled_date' => date('Y-m-d'),
                'scheduled_time' => date('H:i:s'),
                'estimated_duration' => $expired_job->estimated_duration,
                'rooms' => $expired_job->rooms,
                'extras' => $expired_job->extras,
                'pets' => $expired_job->pets,
                'pet_notes' => $expired_job->pet_notes,
                'special_instructions' => $expired_job->special_instructions,
                'suggested_price' => $expired_job->suggested_price,
                'status' => 'open',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $new_job_id = $this->M_jobs->create_job($new_job_data);
            
            if ($new_job_id) {
                $success_count++;
            } else {
                $errors[] = "Job #{$job_id}: Failed to repost";
            }
        }
        
        if ($success_count > 0) {
            echo json_encode([
                'success' => true, 
                'message' => "Successfully reposted {$success_count} jobs",
                'count' => $success_count,
                'errors' => $errors
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to repost any jobs', 'errors' => $errors]);
        }
    }

    /**
     * Delete an expired job permanently
     */
    public function delete_expired_job()
    {
        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        
        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }
        
        // Get the job
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->host_id != $user_id || $job->status != 'expired') {
            echo json_encode(['success' => false, 'message' => 'Job not found or not expired']);
            return;
        }
        
        // Delete the job
        $result = $this->M_jobs->hard_delete_job($job_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Job deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete job']);
        }
    }

    /**
     * Display change password form for host
     */
    public function change_password() {
        $this->load->model('M_users');
        
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
        
        $view["title"] = 'Change Password';
        $view["page_icon"] = 'key';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'host/dashboard'),
            array('title' => 'Change Password', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/host_sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("admin/change_password", array('user_info' => $user_info), TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Process password change for host
     */
    public function update_password() {
        $this->load->model('M_users');
        
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
