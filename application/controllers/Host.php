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
     * ENFORCES: Host must have 50%+ profile completion
     */
    public function create_job()
    {
        $user_id = $this->auth_user_id;
        
        // Load profile model
        $this->load->model('M_user_profiles');
        
        // Check profile completion
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        // ENFORCE: Must have at least 50% profile completion to post jobs
        if ($completion['percentage'] < 50) {
            // Redirect to incomplete profile warning page
            $data = [
                'title' => 'Complete Your Profile',
                'page_icon' => 'fas fa-exclamation-triangle',
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => 'host'],
                    ['title' => 'Complete Profile', 'url' => '', 'active' => true]
                ],
                'completion' => $completion,
                'user_info' => $this->M_users->get_user_by_id($user_id)
            ];
            
            // Load the sidebar content as a string
            $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
            
            // Load the incomplete profile warning
            $data['body'] = $this->load->view('host/profile/incomplete_profile_warning', $data, TRUE);
            
            // Load the layout with the content
            $this->load->view('admin/template/layout_with_sidebar', $data);
            return;
        }
        
        $data = [
            'title' => 'Create New Job',
            'page_icon' => 'fas fa-plus-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Create Job', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($this->auth_user_id),
            'profile_completion' => $completion
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
     * ENFORCES: Host must have 50%+ profile completion
     */
    public function process_create_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        // Load profile model and check completion (backend validation)
        $this->load->model('M_user_profiles');
        $completion = $this->M_user_profiles->calculate_profile_completion($this->auth_user_id);
        
        if ($completion['percentage'] < 50) {
            $this->session->set_flashdata('text', 'You must complete at least 50% of your profile to post jobs.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/edit-profile');
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
                
                // Always add the job, regardless of whether it has offers
                $job->offers = $offers ?: []; // Set empty array if no offers
                $jobs_with_offers[] = $job;
                
                // Count offers if they exist
                if (!empty($offers)) {
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
        
        // Prevent editing assigned jobs
        if ($job->status === 'assigned') {
            $this->session->set_flashdata('text', 'Cannot edit job that has been assigned to a cleaner.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/jobs');
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
        
        // Prevent editing assigned jobs
        if ($job->status === 'assigned') {
            $this->session->set_flashdata('text', 'Cannot edit job that has been assigned to a cleaner.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/jobs');
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
        
        // Check if scheduled date/time has changed
        $date_changed = false;
        $original_datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
        $new_datetime = $scheduled_date . ' ' . $scheduled_time;
        
        if ($original_datetime !== $new_datetime) {
            $date_changed = true;
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
            $message = 'Job updated successfully!';
            
            // If date changed, clear all offers for this job
            if ($date_changed && $this->db->table_exists('offers')) {
                $this->load->model('M_offers');
                $offers_cleared = $this->M_offers->clear_offers_for_job($job_id);
                
                if ($offers_cleared > 0) {
                    $message .= " The job date was changed, so all existing offers have been cleared.";
                    
                    // TODO: Send notification emails to cleaners who had offers
                    // This will be implemented in a future phase
                }
            }
            
            $this->session->set_flashdata('text', $message);
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
     * View Host's Own Profile
     * Display current host's profile with completion status
     */
    public function my_profile()
    {
        $user_id = $this->auth_user_id;
        
        // Load profile model
        $this->load->model('M_user_profiles');
        
        // Get profile data
        $profile = $this->M_user_profiles->get_profile_with_user_data($user_id);
        
        if (!$profile) {
            // Create default profile if it doesn't exist
            $this->M_user_profiles->create_default_profile($user_id);
            $profile = $this->M_user_profiles->get_profile_with_user_data($user_id);
        }
        
        // Calculate profile completion
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        // Get job statistics
        $job_stats = [];
        if (isset($this->M_jobs)) {
            $job_stats = $this->M_jobs->get_host_stats($user_id);
        }
        
        $data = [
            'title' => 'My Profile',
            'page_icon' => 'fas fa-user-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'My Profile', 'url' => '', 'active' => true]
            ],
            'profile' => $profile,
            'completion' => $completion,
            'job_stats' => $job_stats,
            'user_info' => $this->M_users->get_user_by_id($user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the profile view content as a string
        $data['body'] = $this->load->view('host/profile/my_profile', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Edit Host's Own Profile
     * Show form to edit profile information
     */
    public function edit_my_profile()
    {
        $user_id = $this->auth_user_id;
        
        // Load profile model
        $this->load->model('M_user_profiles');
        
        // Get profile data
        $profile = $this->M_user_profiles->get_profile_with_user_data($user_id);
        
        if (!$profile) {
            // Create default profile if it doesn't exist
            $this->M_user_profiles->create_default_profile($user_id);
            $profile = $this->M_user_profiles->get_profile_with_user_data($user_id);
        }
        
        // Calculate profile completion
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        $data = [
            'title' => 'Edit My Profile',
            'page_icon' => 'fas fa-user-edit',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'My Profile', 'url' => 'host/my-profile'],
                ['title' => 'Edit', 'url' => '', 'active' => true]
            ],
            'profile' => $profile,
            'completion' => $completion,
            'user_info' => $this->M_users->get_user_by_id($user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the edit profile view content as a string
        $data['body'] = $this->load->view('host/profile/edit_profile', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Update Host's Own Profile (AJAX)
     * Process profile update form submission
     */
    public function update_my_profile()
    {
        // Set JSON header first
        header('Content-Type: application/json');
        
        try {
            // Log the request for debugging
            log_message('info', 'Profile update request received');
            
            if ($this->input->method() !== 'post') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid request method'
                ]);
                return;
            }
            
            $user_id = $this->auth_user_id;
            
            // Fallback to session if auth_user_id is not available
            if (!$user_id) {
                $user_id = $this->session->userdata('user_id');
                if (!$user_id) {
                    $user_id = $this->session->userdata('id'); // Try 'id' field
                }
            }
            
            log_message('info', 'Auth user ID: ' . ($this->auth_user_id ? $this->auth_user_id : 'NULL'));
            log_message('info', 'Session user ID: ' . ($user_id ? $user_id : 'NULL'));
            
            if (!$user_id) {
                echo json_encode([
                    'success' => false,
                    'message' => 'User not authenticated'
                ]);
                return;
            }
            
            // Load required models
            $this->load->model('M_user_profiles');
            $this->load->model('M_users');
            log_message('info', 'Models loaded');
            
            // Get form data
            $bio = trim($this->input->post('bio'));
            $phone = trim($this->input->post('phone'));
            $address = trim($this->input->post('address'));
            $city = trim($this->input->post('city'));
            $country = trim($this->input->post('state')); // Form field is 'state' but DB column is 'country'
            $is_public = $this->input->post('is_public') ? 1 : 0;
            
            log_message('info', 'Form data received - Bio length: ' . strlen($bio) . ', Phone: ' . $phone);
            
            // Prepare update data
            $update_data = [
                'bio' => $bio,
                'phone' => $phone,
                'is_public' => $is_public,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Update profile
            log_message('info', 'About to update profile for user: ' . $user_id);
            $result = $this->M_user_profiles->update_profile($user_id, $update_data);
            log_message('info', 'Profile update result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            
            if ($result) {
                // Also update address in users table if provided
                log_message('info', 'Address fields - Address: "' . $address . '", City: "' . $city . '", Country: "' . $country . '"');
                
                if (!empty($address) || !empty($city) || !empty($country)) {
                    $user_update = [];
                    if (!empty($address)) $user_update['address'] = $address;
                    if (!empty($city)) $user_update['city'] = $city;
                    if (!empty($country)) $user_update['country'] = $country;
                    
                    log_message('info', 'User update data: ' . json_encode($user_update));
                    
                    if (!empty($user_update)) {
                        $user_update_result = $this->M_users->update_user($user_id, $user_update);
                        log_message('info', 'User address update result: ' . ($user_update_result ? 'SUCCESS' : 'FAILED'));
                    }
                } else {
                    log_message('info', 'No address fields provided, skipping user table update');
                }
                
                // Get updated completion percentage
                $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Profile updated successfully!',
                    'completion_percentage' => $completion['percentage']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update profile. Please try again.'
                ]);
            }
        } catch (Exception $e) {
            log_message('error', 'Profile update error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Test redirect URL (temporary debugging method)
     */
    public function test_redirect()
    {
        echo "Redirect test successful! This URL works: " . base_url('host/my-profile');
        echo "<br><a href='" . base_url('host/my-profile') . "'>Click here to go to my profile</a>";
    }

    /**
     * View Cleaner Profile (Context-Based)
     * Host can only view cleaner profiles who made offers on their jobs
     */
    public function view_cleaner_profile($cleaner_id, $offer_id)
    {
        $host_id = $this->auth_user_id;
        
        // Load required models
        $this->load->model('M_user_profiles');
        
        // Verify the offer exists and belongs to a job owned by this host
        $offer = $this->M_offers->get_offer_by_id($offer_id);
        
        if (!$offer) {
            show_404();
        }
        
        $job = $this->M_jobs->get_job_by_id($offer->job_id);
        
        // Security check: Verify job belongs to this host and offer is from the cleaner
        if (!$job || $job->host_id != $host_id || $offer->cleaner_id != $cleaner_id) {
            show_404();
        }
        
        // Get cleaner's profile
        $profile = $this->M_user_profiles->get_profile_with_user_data($cleaner_id);
        
        if (!$profile) {
            show_404();
        }
        
        // Calculate profile completion
        $completion = $this->M_user_profiles->calculate_profile_completion($cleaner_id);
        
        // Get cleaner's job statistics
        $job_stats = [];
        if (isset($this->M_jobs)) {
            // Get cleaner stats from offers/assignments
            $this->db->select('
                COUNT(DISTINCT o.id) as total_offers_made,
                COUNT(DISTINCT CASE WHEN o.status = "accepted" THEN o.id END) as offers_accepted,
                COUNT(DISTINCT CASE WHEN j.status = "completed" AND j.cleaner_id = ' . $cleaner_id . ' THEN j.id END) as jobs_completed
            ');
            $this->db->from('offers o');
            $this->db->join('jobs j', 'j.id = o.job_id', 'left');
            $this->db->where('o.cleaner_id', $cleaner_id);
            $query = $this->db->get();
            $job_stats = $query->row_array();
        }
        
        // Load reviews model to get cleaner's reviews
        $this->load->model('M_reviews');
        $reviews = $this->M_reviews->get_reviews_by_user($cleaner_id, 'cleaner', 5);
        
        $data = [
            'title' => 'Cleaner Profile - ' . $profile->username,
            'page_icon' => 'fas fa-user',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Offers', 'url' => 'host/offers'],
                ['title' => 'Cleaner Profile', 'url' => '', 'active' => true]
            ],
            'profile' => $profile,
            'completion' => $completion,
            'job_stats' => $job_stats,
            'reviews' => $reviews,
            'offer' => $offer,
            'job' => $job,
            'user_info' => $this->M_users->get_user_by_id($host_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the cleaner profile view content as a string
        $data['body'] = $this->load->view('host/profile/cleaner_profile', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
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

    /**
     * Public Profile View
     * Display public profile for hosts (visible to cleaners)
     * Shows: name, reviews, general location (city/state)
     * Hides: contact information, full address, email, phone
     */
    public function public_profile($host_id)
    {
        // Load profile model
        $this->load->model('M_user_profiles');
        
        // Get host profile with user data
        $profile = $this->M_user_profiles->get_profile_with_user_data($host_id);
        
        if (!$profile || $profile->auth_level != 6) {
            show_404();
        }
        
        // Get host statistics
        $job_stats = [
            'total_jobs' => $this->M_jobs->get_total_jobs_for_host($host_id),
            'active_jobs' => count($this->M_jobs->get_host_active_jobs($host_id)),
            'completed_jobs' => $this->M_jobs->get_completed_jobs_count_for_host($host_id),
            'average_rating' => $profile->average_rating ?? 0,
            'total_reviews' => $profile->total_reviews ?? 0
        ];
        
        // TODO: Get actual reviews when review system is implemented
        $reviews = [];
        
        $data = [
            'title' => $profile->username . ' - Host Profile',
            'page_icon' => 'fas fa-user-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Host Profile', 'url' => '', 'active' => true]
            ],
            'profile' => $profile,
            'job_stats' => $job_stats,
            'reviews' => $reviews
        ];
        
        // Check if this is being viewed by cleaner or admin
        $viewer_auth_level = $this->session->userdata('auth_level');
        if ($viewer_auth_level == 3) {
            // Cleaner viewing
            $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        } elseif ($viewer_auth_level == 9) {
            // Admin viewing
            $data['sidebar'] = $this->load->view('admin/template/admin_sidebar', array(), TRUE);
        } else {
            // Fallback
            $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        }
        
        // Load the public profile view
        $data['body'] = $this->load->view('host/public_profile', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Host Payment History
     * Show closed jobs and payment information with filtering
     */
    public function past_jobs()
    {
        $user_id = $this->auth_user_id;
        
        // Get filter parameters
        $filters = [
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
            'search' => $this->input->get('search'),
            'status' => $this->input->get('status')
        ];
        
        // Set default date range if not provided (last 30 days)
        if (empty($filters['date_from'])) {
            $filters['date_from'] = date('Y-m-d', strtotime('-30 days'));
        }
        if (empty($filters['date_to'])) {
            $filters['date_to'] = date('Y-m-d');
        }
        
        // Get past jobs (closed and recalled) with payment information
        $past_jobs = [];
        $total_paid = 0;
        $total_jobs = 0;
        $average_payment = 0;
        $recalled_jobs = 0;
        
        if (isset($this->M_jobs)) {
            $past_jobs = $this->M_jobs->get_host_past_jobs($user_id, $filters);
            
            // Calculate totals
            foreach ($past_jobs as $job) {
                $payment_amount = $job->payment_amount ?: ($job->final_price ?: $job->accepted_price ?: 0);
                $total_paid += (float)$payment_amount;
                $total_jobs++;
                
                if ($job->status === 'recalled') {
                    $recalled_jobs++;
                }
            }
            
            $average_payment = $total_jobs > 0 ? $total_paid / $total_jobs : 0;
        }
        
        $data = [
            'title' => 'Past Jobs',
            'page_icon' => 'fas fa-history',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Past Jobs', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'filters' => $filters,
            'past_jobs' => $past_jobs,
            'summary' => [
                'total_paid' => $total_paid,
                'total_jobs' => $total_jobs,
                'average_payment' => $average_payment,
                'recalled_jobs' => $recalled_jobs
            ]
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the past jobs content as a string
        $data['body'] = $this->load->view('host/past_jobs', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Completed Jobs
     * Show jobs that need to be completed by host and review system
     */
    public function completed_jobs()
    {
        $user_id = $this->auth_user_id;
        
        // Get filter parameters
        $search_term = $this->input->get('search');
        $sort_by = $this->input->get('sort') ?: 'completed_at';
        $sort_order = $this->input->get('order') ?: 'DESC';
        
        // Get completed jobs that need host action
        $completed_jobs = [];
        $jobs_needing_review = [];
        $jobs_past_review_window = [];
        
        if (isset($this->M_jobs)) {
            // Get jobs that are completed but not yet closed
            $completed_jobs = $this->M_jobs->get_host_completed_jobs($user_id);
            
            // Separate jobs by review status
            foreach ($completed_jobs as $job) {
                $completed_time = strtotime($job->completed_at);
                $review_deadline = $completed_time + (24 * 60 * 60); // 24 hours
                $current_time = time();
                
                if ($current_time > $review_deadline) {
                    $jobs_past_review_window[] = $job;
                } else {
                    $jobs_needing_review[] = $job;
                }
            }
            
            // Apply search filter
            if (!empty($search_term)) {
                $jobs_needing_review = array_filter($jobs_needing_review, function($job) use ($search_term) {
                    return stripos($job->title, $search_term) !== false || 
                           stripos($job->description, $search_term) !== false ||
                           stripos($job->cleaner_first_name . ' ' . $job->cleaner_last_name, $search_term) !== false;
                });
                
                $jobs_past_review_window = array_filter($jobs_past_review_window, function($job) use ($search_term) {
                    return stripos($job->title, $search_term) !== false || 
                           stripos($job->description, $search_term) !== false ||
                           stripos($job->cleaner_first_name . ' ' . $job->cleaner_last_name, $search_term) !== false;
                });
            }
            
            // Sort jobs
            $sort_function = function($a, $b) use ($sort_by, $sort_order) {
                switch ($sort_by) {
                    case 'completed_at':
                        $a_val = strtotime($a->completed_at);
                        $b_val = strtotime($b->completed_at);
                        break;
                    case 'title':
                        $a_val = $a->title;
                        $b_val = $b->title;
                        break;
                    case 'final_price':
                        $a_val = $a->final_price ?: $a->accepted_price;
                        $b_val = $b->final_price ?: $b->accepted_price;
                        break;
                    default:
                        $a_val = strtotime($a->completed_at);
                        $b_val = strtotime($b->completed_at);
                }
                
                if ($sort_order === 'DESC') {
                    return $b_val <=> $a_val;
                } else {
                    return $a_val <=> $b_val;
                }
            };
            
            usort($jobs_needing_review, $sort_function);
            usort($jobs_past_review_window, $sort_function);
        }
        
        $data = [
            'title' => 'Completed Jobs',
            'page_icon' => 'fas fa-check-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Completed Jobs', 'url' => '', 'active' => true]
            ],
            'jobs_needing_review' => $jobs_needing_review,
            'jobs_past_review_window' => $jobs_past_review_window,
            'filters' => [
                'search' => $search_term,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order
            ]
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the completed jobs content as a string
        $data['body'] = $this->load->view('host/completed_jobs', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Complete Job (Host Action)
     * Mark a job as complete and release payment
     */
    public function complete_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $job_id = $this->input->post('job_id');
        $user_id = $this->auth_user_id;
        
        if (!$job_id) {
            echo json_encode(['success' => false, 'message' => 'Job ID is required']);
            return;
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->host_id != $user_id) {
            echo json_encode(['success' => false, 'message' => 'Job not found or unauthorized']);
            return;
        }
        
        if ($job->status !== 'completed') {
            echo json_encode(['success' => false, 'message' => 'Job is not in completed status']);
            return;
        }
        
        // Update job status to closed and release payment
        $update_data = [
            'status' => 'closed',
            'payment_released_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->M_jobs->update_job($job_id, $update_data)) {
            // Send notification to cleaner
            $this->load->model('M_notifications');
            $this->M_notifications->create_notification(
                $job->assigned_cleaner_id,
                'Payment Released',
                'Your payment for job "' . $job->title . '" has been released by the host.',
                'payment_released',
                $job_id
            );
            
            echo json_encode([
                'success' => true, 
                'message' => 'Job completed successfully! Payment has been released to the cleaner.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to complete job. Please try again.']);
        }
    }


    /**
     * Show Recall Job Form
     * Display the recall form for a specific job
     */
    public function recall_job($job_id)
    {
        $user_id = $this->auth_user_id;
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->host_id != $user_id) {
            $this->session->set_flashdata('text', 'Job not found or unauthorized');
            $this->session->set_flashdata('type', 'error');
            redirect('host/completed-jobs');
        }
        
        if (!in_array($job->status, ['completed', 'closed'])) {
            $this->session->set_flashdata('text', 'Job cannot be recalled in its current status');
            $this->session->set_flashdata('type', 'error');
            redirect('host/completed-jobs');
        }
        
        // Get cleaner information
        $cleaner_name = 'Unknown Cleaner';
        if ($job->assigned_cleaner_id) {
            $cleaner = $this->M_users->get_user_by_id($job->assigned_cleaner_id);
            if ($cleaner) {
                $cleaner_name = trim(($cleaner->first_name ?? '') . ' ' . ($cleaner->last_name ?? ''));
                if (empty($cleaner_name)) {
                    $cleaner_name = $cleaner->username ?? 'Unknown Cleaner';
                }
            }
        }
        
        // Determine back URL
        $back_url = $job->status === 'completed' ? base_url('host/completed-jobs') : base_url('host/past-jobs');
        
        $data = [
            'title' => 'Recall Job',
            'page_icon' => 'fas fa-exclamation-triangle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => $job->status === 'completed' ? 'Completed Jobs' : 'Past Jobs', 'url' => $back_url],
                ['title' => 'Recall Job', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'job' => $job,
            'cleaner_name' => $cleaner_name,
            'back_url' => $back_url
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the recall job content as a string
        $data['body'] = $this->load->view('host/recall_job', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Process Recall Job
     * Handle the recall form submission
     */
    public function process_recall_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $job_id = $this->input->post('job_id');
        $recall_type = $this->input->post('recall_type');
        $recall_reason = $this->input->post('recall_reason');
        $recall_details = $this->input->post('recall_details');
        $severity = $this->input->post('severity');
        $evidence_notes = $this->input->post('evidence_notes');
        $desired_resolution = $this->input->post('desired_resolution');
        $user_id = $this->auth_user_id;
        
        if (!$job_id || !$recall_reason || !$recall_details || !$severity) {
            $this->session->set_flashdata('text', 'All required fields must be filled');
            $this->session->set_flashdata('type', 'error');
            redirect('host/recall_job/' . $job_id);
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->host_id != $user_id) {
            $this->session->set_flashdata('text', 'Job not found or unauthorized');
            $this->session->set_flashdata('type', 'error');
            redirect('host/completed-jobs');
        }
        
        if (!in_array($job->status, ['completed', 'closed'])) {
            $this->session->set_flashdata('text', 'Job cannot be recalled in its current status');
            $this->session->set_flashdata('type', 'error');
            redirect('host/completed-jobs');
        }
        
        // Check which columns exist in the jobs table
        $columns = $this->db->list_fields('jobs');
        
        // Build update data with only existing columns
        $update_data = [
            'status' => 'recalled'
        ];
        
        // Add recall-related fields only if columns exist
        if (in_array('recall_reason', $columns)) {
            $update_data['recall_reason'] = $recall_reason;
        }
        
        if (in_array('recall_details', $columns)) {
            $update_data['recall_details'] = $recall_details;
        }
        
        if (in_array('recall_severity', $columns)) {
            $update_data['recall_severity'] = $severity;
        }
        
        if (in_array('evidence_notes', $columns)) {
            $update_data['evidence_notes'] = $evidence_notes;
        }
        
        if (in_array('desired_resolution', $columns)) {
            $update_data['desired_resolution'] = $desired_resolution;
        }
        
        if (in_array('recalled_at', $columns)) {
            $update_data['recalled_at'] = date('Y-m-d H:i:s');
        }
        
        // Note: updated_at will be automatically added by M_jobs::update_job() method
        // So we don't need to add it here
        
        // If it's a completed job, also release payment
        if ($job->status === 'completed' && in_array('payment_released_at', $columns)) {
            $update_data['payment_released_at'] = date('Y-m-d H:i:s');
        }
        
        if ($this->M_jobs->update_job($job_id, $update_data)) {
            // Send notification to admin
            $this->load->model('M_notifications');
            $this->M_notifications->create_notification(
                1, // Assuming admin user ID is 1, adjust as needed
                'Job Recall - Admin Review Required',
                'Host has recalled job "' . $job->title . '" for review. Reason: ' . $recall_reason . ' (Severity: ' . $severity . ')',
                'job_recall',
                $job_id
            );
            
            // Send notification to cleaner if it's a completed job
            if ($job->status === 'completed' && $job->assigned_cleaner_id) {
                $this->M_notifications->create_notification(
                    $job->assigned_cleaner_id,
                    'Payment Released - Job Recalled',
                    'Your payment for job "' . $job->title . '" has been released, but the host has recalled the job for admin review.',
                    'payment_released_recalled',
                    $job_id
                );
            }
            
            $this->session->set_flashdata('text', 'Job recalled successfully! Admin has been notified for review.');
            $this->session->set_flashdata('type', 'success');
            redirect('host/recalled-jobs');
        } else {
            $this->session->set_flashdata('text', 'Failed to recall job. Please try again.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/recall_job/' . $job_id);
        }
    }

    /**
     * Recalled Jobs
     * Show all jobs that have been recalled by the host
     */
    public function recalled_jobs()
    {
        $user_id = $this->auth_user_id;
        
        // Get filter parameters
        $filters = [
            'reason' => $this->input->get('reason'),
            'severity' => $this->input->get('severity'),
            'search' => $this->input->get('search'),
            'sort' => $this->input->get('sort')
        ];
        
        // Get recalled jobs
        $recalled_jobs = [];
        $pending_review = 0;
        $under_investigation = 0;
        $resolved = 0;
        
        if (isset($this->M_jobs)) {
            $recalled_jobs = $this->M_jobs->get_host_recalled_jobs($user_id, $filters);
            
            // Calculate summary statistics
            foreach ($recalled_jobs as $job) {
                $recall_status = $job->recall_status ?? 'pending';
                switch ($recall_status) {
                    case 'pending':
                        $pending_review++;
                        break;
                    case 'under_investigation':
                        $under_investigation++;
                        break;
                    case 'resolved':
                        $resolved++;
                        break;
                }
            }
        }
        
        $data = [
            'title' => 'Recalled Jobs',
            'page_icon' => 'fas fa-exclamation-triangle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Recalled Jobs', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'filters' => $filters,
            'recalled_jobs' => $recalled_jobs,
            'pending_review' => $pending_review,
            'under_investigation' => $under_investigation,
            'resolved' => $resolved
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        
        // Load the recalled jobs content as a string
        $data['body'] = $this->load->view('host/recalled_jobs', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

}
