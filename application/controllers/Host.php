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
        
        // Add review summary
        $this->load->model('M_reviews');
        // Pass user_id as requesting_user_id to filter out unreciprocated cleaner reviews
        $data['review_stats'] = $this->M_reviews->calculate_user_average_ratings($user_id, $user_id);
        $data['recent_reviews'] = $this->M_reviews->get_public_reviews_for_user($user_id, 3, 0, $user_id);
        
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
        
        // Get pricing parameters from database or use defaults
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10,
            'app_percent' => 15
        ];
        
        // Fetch from settings table if it exists
        if ($this->db->table_exists('pricing_settings')) {
            $settings = $this->db->get('pricing_settings')->row();
            if ($settings) {
                $pricing_params = [
                    'base_charge' => floatval($settings->base_charge),
                    'tax_percent' => floatval($settings->tax_percent),
                    'app_percent' => floatval($settings->app_percent)
                ];
            }
        }
        
        $data = [
            'title' => 'Create New Job',
            'page_icon' => 'fas fa-plus-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Create Job', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($this->auth_user_id),
            'profile_completion' => $completion,
            'pricing_params' => $pricing_params
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
        $this->form_validation->set_rules('job_date', 'Date', 'required');
        $this->form_validation->set_rules('job_time', 'Time', 'required');
        $this->form_validation->set_rules('property_type', 'Property Type', 'required');
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
        
        // Get separate date and time fields
        $scheduled_date = $this->input->post('job_date');
        $scheduled_time = $this->input->post('job_time');
        
        // Add seconds to time if not present
        if ($scheduled_time && strlen($scheduled_time) == 5) {
            $scheduled_time .= ':00';
        }
        
        // Validate that we have the required date/time values
        if (empty($scheduled_date) || empty($scheduled_time)) {
            $this->session->set_flashdata('text', 'Please select a valid date and time.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/create_job');
        }
        
        // Get property type
        $property_type = $this->input->post('property_type');
        
        // Check STR requirements confirmation if STR is selected
        if ($property_type === 'str') {
            $str_confirmed = $this->input->post('str_requirements_confirmed');
            if (!$str_confirmed) {
                $this->session->set_flashdata('text', 'Please confirm that you have read and understand all STR requirements.');
                $this->session->set_flashdata('type', 'error');
                redirect('host/create_job');
            }
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
            'property_type' => $property_type,
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
        
        // Get pricing parameters
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10,
            'app_percent' => 15
        ];
        
        if ($this->db->table_exists('pricing_settings')) {
            $pricing_row = $this->db->get('pricing_settings')->row();
            if ($pricing_row) {
                $pricing_params = [
                    'base_charge' => $pricing_row->base_charge,
                    'tax_percent' => $pricing_row->tax_percent,
                    'app_percent' => $pricing_row->app_percent
                ];
            }
        }
        
        // Get accepted offer details if job is assigned
        $accepted_offer = null;
        if (in_array($job->status, ['assigned', 'in_progress', 'completed', 'closed'])) {
            $offers = $this->M_offers->get_offers_by_job($job_id);
            foreach ($offers as $offer) {
                if ($offer->status === 'accepted') {
                    $accepted_offer = $offer;
                    break;
                }
            }
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
            'price_adjustments' => $price_adjustments,
            'pricing_params' => $pricing_params,
            'accepted_offer' => $accepted_offer
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
        
        // Get pricing parameters for calculations
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10,
            'app_percent' => 15
        ];
        
        if ($this->db->table_exists('pricing_settings')) {
            $pricing_row = $this->db->get('pricing_settings')->row();
            if ($pricing_row) {
                $pricing_params = [
                    'base_charge' => $pricing_row->base_charge,
                    'tax_percent' => $pricing_row->tax_percent,
                    'app_percent' => $pricing_row->app_percent
                ];
            }
        }
        
        // Get all jobs with offers for this host (today and future only)
        $jobs_with_offers = [];
        $total_offers = 0;
        $pending_offers = 0;
        $counter_offers = 0;
        $accepted_offers = 0;
        
        if ($this->db->table_exists('jobs') && $this->db->table_exists('offers')) {
            // Get jobs for this host (today and future only)
            $jobs = $this->M_jobs->get_host_active_jobs($user_id);
            
            // Load reviews model to get cleaner ratings
            $this->load->model('M_reviews');
            
            foreach ($jobs as $job) {
                $offers = $this->M_offers->get_offers_by_job($job->id);
                
                // Add cleaner rating to each offer and calculate cleaner payout
                if (!empty($offers)) {
                    foreach ($offers as $offer) {
                        $cleaner_ratings = $this->M_reviews->calculate_user_average_ratings($offer->cleaner_id);
                        $offer->cleaner_rating = $cleaner_ratings['overall_average'] ?? 0;
                        $offer->cleaner_review_count = $cleaner_ratings['total_reviews'] ?? 0;
                        
                        // Calculate cleaner payout based on offer amount
                        if ($offer->offer_type === 'accept') {
                            // For accept offers, calculate from host's suggested price
                            $host_price = $job->suggested_price;
                            $tax_amount = ($host_price * $pricing_params['tax_percent']) / 100;
                            $app_fee = ($host_price * $pricing_params['app_percent']) / 100;
                            $offer->cleaner_payout_calculated = $host_price - $pricing_params['base_charge'] - $tax_amount - $app_fee;
                        } else {
                            // For counter offers, use the stored cleaner_payout or calculate from amount
                            $offer->cleaner_payout_calculated = !empty($offer->cleaner_payout) ? $offer->cleaner_payout : 
                                ($offer->cleaner_payout = ($offer->amount - $pricing_params['base_charge'] - (($offer->amount * $pricing_params['tax_percent']) / 100) - (($offer->amount * $pricing_params['app_percent']) / 100)));
                        }
                    }
                }
                
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
            'pricing_params' => $pricing_params,
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
        
        // Get pricing parameters for calculator
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10,
            'app_percent' => 15
        ];
        
        if ($this->db->table_exists('pricing_settings')) {
            $pricing_row = $this->db->get('pricing_settings')->row();
            if ($pricing_row) {
                $pricing_params = [
                    'base_charge' => $pricing_row->base_charge,
                    'tax_percent' => $pricing_row->tax_percent,
                    'app_percent' => $pricing_row->app_percent
                ];
            }
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
            'user_info' => $this->M_users->get_user_by_id($this->auth_user_id),
            'pricing_params' => $pricing_params
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
        $this->form_validation->set_rules('property_type', 'Property Type', 'required');
        $this->form_validation->set_rules('job_date', 'Job Date', 'required');
        $this->form_validation->set_rules('job_time', 'Job Time', 'required');
        $this->form_validation->set_rules('estimated_duration', 'Estimated Duration', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('rooms', 'Number of Rooms', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('suggested_price', 'Suggested Price', 'required|numeric|greater_than[0]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('text', 'Please correct the errors below.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/edit_job/' . $job_id);
        }
        
        // Get property type and check STR requirements
        $property_type = $this->input->post('property_type');
        
        // Check STR requirements confirmation if STR is selected
        if ($property_type === 'str') {
            $str_confirmed = $this->input->post('str_requirements_confirmed');
            if (!$str_confirmed) {
                $this->session->set_flashdata('text', 'Please confirm that you have read and understand all STR requirements.');
                $this->session->set_flashdata('type', 'error');
                redirect('host/edit_job/' . $job_id);
                return;
            }
        }
        
        // Process data (same as create)
        $extras = $this->input->post('extras');
        $extras_json = json_encode($extras && is_array($extras) ? $extras : []);
        
        $rooms = $this->input->post('rooms');
        $rooms_json = json_encode($rooms ? [$rooms] : []);
        
        // Get date and time from separate fields
        $scheduled_date = $this->input->post('job_date');
        $scheduled_time = $this->input->post('job_time');
        
        // Add seconds to time if not present
        if ($scheduled_time && strlen($scheduled_time) == 5) {
            $scheduled_time .= ':00';
        }
        
        if (empty($scheduled_date) || empty($scheduled_time)) {
            $this->session->set_flashdata('text', 'Please select a valid date and time.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/edit_job/' . $job_id);
            return;
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
            'property_type' => $property_type,
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
        
        // Load reviews model to get host's reviews
        $this->load->model('M_reviews');
        // Don't filter reviews - show the same public rating that others see
        $reviews = $this->M_reviews->get_public_reviews_for_user($user_id, 10);
        $review_stats = $this->M_reviews->calculate_user_average_ratings($user_id);
        $rating_distribution = $this->M_reviews->get_rating_distribution($user_id);
        
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
            'reviews' => $reviews,
            'review_stats' => $review_stats,
            'rating_distribution' => $rating_distribution,
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
        
        // Get service areas for city dropdown
        $service_areas = $this->M_user_profiles->get_service_areas();
        
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
            'service_areas' => $service_areas,
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
            
            // Parse city and state from "City, State" format
            $city_full = trim($this->input->post('city'));
            $city = '';
            $country = ''; // DB column is 'country' but stores state
            
            if (!empty($city_full) && strpos($city_full, ',') !== false) {
                $parts = explode(',', $city_full, 2);
                $city = trim($parts[0]);
                $country = trim($parts[1]);
            } else {
                $city = $city_full;
            }
            
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
        $reviews = $this->M_reviews->get_public_reviews_for_user($cleaner_id, 5);
        $review_stats = $this->M_reviews->calculate_user_average_ratings($cleaner_id);
        $rating_distribution = $this->M_reviews->get_rating_distribution($cleaner_id);
        
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
            'review_stats' => $review_stats,
            'rating_distribution' => $rating_distribution,
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
     * NOTE: This method should be accessible without host authentication
     */
    public function public_profile($host_id)
    {
        // Debug: Test if method is reachable
        log_message('debug', '=== PUBLIC_PROFILE METHOD CALLED ===');
        log_message('debug', 'Host ID parameter: ' . $host_id);
        log_message('debug', 'Current user ID: ' . $this->session->userdata('user_id'));
        log_message('debug', 'Current auth level: ' . $this->session->userdata('auth_level'));
        
        // Auth is handled in constructor - cleaners, hosts, and admins can access
        
        // Load profile model
        $this->load->model('M_user_profiles');
        
        // Get host profile with user data
        $profile = $this->M_user_profiles->get_profile_with_user_data($host_id);
        
        log_message('debug', 'Public profile request for host_id: ' . $host_id);
        log_message('debug', 'Profile found: ' . ($profile ? 'YES' : 'NO'));
        if ($profile) {
            log_message('debug', 'Profile auth_level: ' . $profile->auth_level);
        }
        
        if (!$profile || $profile->auth_level != 6) {
            log_message('error', 'Host public profile 404 - Profile not found or not a host');
            show_404();
            return;
        }
        
        // Get host statistics
        $job_stats = [
            'total_jobs' => $this->M_jobs->get_total_jobs_for_host($host_id),
            'active_jobs' => count($this->M_jobs->get_host_active_jobs($host_id)),
            'completed_jobs' => $this->M_jobs->get_completed_jobs_count_for_host($host_id)
        ];
        
        // Load reviews model to get host's reviews
        $this->load->model('M_reviews');
        $reviews = $this->M_reviews->get_public_reviews_for_user($host_id, 5);
        $review_stats = $this->M_reviews->calculate_user_average_ratings($host_id);
        $rating_distribution = $this->M_reviews->get_rating_distribution($host_id);
        
        $data = [
            'title' => $profile->username . ' - Host Profile',
            'page_icon' => 'fas fa-user-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Host Profile', 'url' => '', 'active' => true]
            ],
            'profile' => $profile,
            'job_stats' => $job_stats,
            'reviews' => $reviews,
            'review_stats' => $review_stats,
            'rating_distribution' => $rating_distribution
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
        
        // Load reviews model
        $this->load->model('M_reviews');
        
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
        
        // Fetch pricing parameters first (needed for calculations)
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10.00,
            'app_percent' => 15.00
        ];
        
        if ($this->db->table_exists('pricing_settings')) {
            $pricing_settings = $this->db->get('pricing_settings')->row();
            if ($pricing_settings) {
                $pricing_params = [
                    'base_charge' => $pricing_settings->base_charge,
                    'tax_percent' => $pricing_settings->tax_percent,
                    'app_percent' => $pricing_settings->app_percent
                ];
            }
        }
        
        // Get past jobs (closed and recalled) with payment information
        $past_jobs = [];
        $total_paid = 0;
        $total_jobs = 0;
        $average_payment = 0;
        $recalled_jobs = 0;
        
        if (isset($this->M_jobs)) {
            $past_jobs = $this->M_jobs->get_host_past_jobs($user_id, $filters);
            
            // Calculate totals and fetch offer details
            foreach ($past_jobs as $job) {
                // Get accepted offer details
                $accepted_offer = $this->db
                    ->where('job_id', $job->id)
                    ->where('status', 'accepted')
                    ->get('offers')
                    ->row();
                
                $job->accepted_offer = $accepted_offer;
                
                // Calculate cleaner payout
                if ($accepted_offer && !empty($accepted_offer->cleaner_payout)) {
                    $job->cleaner_payout = $accepted_offer->cleaner_payout;
                } elseif ($accepted_offer) {
                    // Calculate from offer amount
                    $offer_amount = $accepted_offer->amount;
                    $tax_amount = ($offer_amount * $pricing_params['tax_percent']) / 100;
                    $app_amount = ($offer_amount * $pricing_params['app_percent']) / 100;
                    $job->cleaner_payout = $offer_amount - $pricing_params['base_charge'] - $tax_amount - $app_amount;
                } else {
                    // Calculate from job price
                    $base_price = $job->final_price ?: ($job->accepted_price ?: $job->suggested_price);
                    $tax_amount = ($base_price * $pricing_params['tax_percent']) / 100;
                    $app_amount = ($base_price * $pricing_params['app_percent']) / 100;
                    $job->cleaner_payout = $base_price - $pricing_params['base_charge'] - $tax_amount - $app_amount;
                }
                
                // Fetch reviews for this job (both host's review and cleaner's review)
                $job->host_review = null;
                $job->cleaner_review = null;
                
                if (isset($this->M_reviews)) {
                    // Get host's review of the cleaner
                    $host_review = $this->M_reviews->get_review_by_job_and_reviewer($job->id, $user_id);
                    if ($host_review) {
                        $job->host_review = $host_review;
                    }
                    
                    // Get cleaner's review of the host
                    if (!empty($job->assigned_cleaner_id)) {
                        $cleaner_review = $this->M_reviews->get_review_by_job_and_reviewer($job->id, $job->assigned_cleaner_id);
                        if ($cleaner_review) {
                            $job->cleaner_review = $cleaner_review;
                        }
                    }
                }
                
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
            'pricing_params' => $pricing_params,
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
     * Upcoming Jobs (Assigned Jobs)
     * Show all jobs that are assigned and upcoming
     */
    public function upcoming_jobs()
    {
        $user_id = $this->auth_user_id;
        
        // Get assigned and in_progress jobs
        $this->db->select('j.*, u.username as cleaner_username, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name, u.email as cleaner_email, u.phone as cleaner_phone');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id', 'left');
        $this->db->where('j.host_id', $user_id);
        $this->db->where_in('j.status', ['assigned', 'in_progress']);
        $this->db->order_by('j.scheduled_date', 'ASC');
        $jobs = $this->db->get()->result();
        
        $data = [
            'title' => 'Upcoming Jobs',
            'page_icon' => 'fas fa-calendar-check',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Upcoming Jobs', 'url' => '', 'active' => true]
            ],
            'jobs' => $jobs,
            'user_info' => $this->M_users->get_user_by_id($user_id)
        ];
        
        // Load sidebar
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', NULL, TRUE);
        
        // Load view
        $data['body'] = $this->load->view('host/upcoming_jobs', $data, TRUE);
        
        // Load layout
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Show Confirm Completion Page with Review Form
     * Host must review cleaner before closing job
     */
    public function confirm_completion($job_id)
    {
        $user_id = $this->auth_user_id;
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->host_id != $user_id) {
            $this->session->set_flashdata('text', 'Job not found or unauthorized');
            $this->session->set_flashdata('type', 'error');
            redirect('host/completed-jobs');
        }
        
        if ($job->status !== 'completed') {
            $this->session->set_flashdata('text', 'Job is not in completed status');
            $this->session->set_flashdata('type', 'error');
            redirect('host/completed-jobs');
        }
        
        // Check if host has already reviewed
        $this->load->model('M_reviews');
        $existing_review = $this->M_reviews->get_review_by_job_and_reviewer($job_id, $user_id);
        if ($existing_review) {
            $this->session->set_flashdata('text', 'You have already reviewed this job');
            $this->session->set_flashdata('type', 'warning');
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
        
        $data = [
            'title' => 'Confirm Completion & Review',
            'page_icon' => 'fas fa-check-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Completed Jobs', 'url' => 'host/completed-jobs'],
                ['title' => 'Confirm & Review', 'url' => '', 'active' => true]
            ],
            'job' => $job,
            'cleaner_name' => $cleaner_name
        ];
        
        // Load sidebar
        $data['sidebar'] = $this->load->view('admin/template/host_sidebar', NULL, TRUE);
        
        // Load the confirmation page with review form
        $data['body'] = $this->load->view('host/confirm_completion', $data, TRUE);
        
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
    
    /**
     * Process Confirm Completion with Review
     * Handle review submission + job closure + payment release
     */
    public function process_confirm_completion()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        
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
        
        // Set validation rules for review (MANDATORY)
        $this->form_validation->set_rules('overall_rating', 'Overall Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('public_comment', 'Public Comment', 'required|min_length[30]|max_length[100]');
        $this->form_validation->set_rules('professionalism_rating', 'Professionalism Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('quality_rating', 'Quality Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('communication_rating', 'Communication Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('punctuality_rating', 'Punctuality Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('professionalism_comment', 'Professionalism Comment', 'max_length[100]');
        $this->form_validation->set_rules('quality_comment', 'Quality Comment', 'max_length[100]');
        $this->form_validation->set_rules('communication_comment', 'Communication Comment', 'max_length[100]');
        $this->form_validation->set_rules('punctuality_comment', 'Punctuality Comment', 'max_length[100]');
        $this->form_validation->set_rules('private_notes', 'Private Notes', 'max_length[100]');
        
        if (!$this->form_validation->run()) {
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed: ' . strip_tags(validation_errors())
            ]);
            return;
        }
        
        // Load reviews model
        $this->load->model('M_reviews');
        
        // Start database transaction
        $this->db->trans_start();
        
        // 1. Create the review
        $review_data = [
            'job_id' => $job_id,
            'reviewer_id' => $user_id,
            'reviewee_id' => $job->assigned_cleaner_id,
            'review_type' => 'host_to_cleaner',
            
            // Public data
            'overall_rating' => $this->input->post('overall_rating'),
            'public_comment' => $this->input->post('public_comment'),
            
            // Private category data
            'professionalism_rating' => $this->input->post('professionalism_rating'),
            'professionalism_comment' => $this->input->post('professionalism_comment'),
            'quality_rating' => $this->input->post('quality_rating'),
            'quality_comment' => $this->input->post('quality_comment'),
            'communication_rating' => $this->input->post('communication_rating'),
            'communication_comment' => $this->input->post('communication_comment'),
            'punctuality_rating' => $this->input->post('punctuality_rating'),
            'punctuality_comment' => $this->input->post('punctuality_comment'),
            'private_notes' => $this->input->post('private_notes')
        ];
        
        $review_id = $this->M_reviews->create_review($review_data);
        
        if (!$review_id) {
            $this->db->trans_rollback();
            echo json_encode([
                'success' => false,
                'message' => 'Failed to submit review. Please try again.'
            ]);
            return;
        }
        
        // 2. Close the job and release payment
        $update_data = [
            'status' => 'closed',
            'payment_released_at' => date('Y-m-d H:i:s'),
            'host_reviewed' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $job_id);
        $job_updated = $this->db->update('jobs', $update_data);
        
        if (!$job_updated) {
            $this->db->trans_rollback();
            echo json_encode([
                'success' => false,
                'message' => 'Failed to close job. Please try again.'
            ]);
            return;
        }
        
        // 3. Send notification to cleaner
        $this->load->model('M_notifications');
        $this->M_notifications->create_notification(
            $job->assigned_cleaner_id,
            'Payment Released & Review Received',
            'The host has confirmed completion of job "' . $job->title . '" and left you a review. Your payment has been released!',
            base_url('cleaner/earnings')
        );
        
        // Complete transaction
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to complete job. Please try again.'
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'message' => 'Job confirmed, review submitted, and payment released successfully!',
                'redirect' => base_url('host/past-jobs')
            ]);
        }
    }
    
    /**
     * DEPRECATED - Old direct completion method
     * Kept for backward compatibility, but should not be used
     * Use confirm_completion($job_id) instead
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
        
        // Redirect to new review-based confirmation flow
        echo json_encode([
            'success' => false,
            'message' => 'Please use the new confirmation page to review and complete the job.',
            'redirect' => base_url('host/confirm-completion/' . $job_id)
        ]);
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
        
        // Check if host has already reviewed this cleaner for this job
        $this->load->model('M_reviews');
        $existing_review = $this->M_reviews->get_review_by_job_and_reviewer($job_id, $user_id);
        
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
            'back_url' => $back_url,
            'existing_review' => $existing_review ? true : false
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
        
        // Validate recall fields
        if (!$job_id || !$recall_reason || !$recall_details || !$severity) {
            $this->session->set_flashdata('text', 'All required fields must be filled');
            $this->session->set_flashdata('type', 'error');
            redirect('host/recall_job/' . $job_id);
        }
        
        // Get job details first
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
        
        // Check if review already exists
        $this->load->model('M_reviews');
        $existing_review = $this->M_reviews->get_review_by_job_and_reviewer($job_id, $user_id);
        
        // Only validate review fields if review doesn't already exist
        if (!$existing_review) {
            // Set validation rules for review (MANDATORY for new reviews)
            $this->form_validation->set_rules('overall_rating', 'Overall Rating', 'required|integer|greater_than[0]|less_than[6]');
            $this->form_validation->set_rules('public_comment', 'Public Comment', 'required|min_length[30]|max_length[100]');
            $this->form_validation->set_rules('professionalism_rating', 'Professionalism Rating', 'required|integer|greater_than[0]|less_than[6]');
            $this->form_validation->set_rules('quality_rating', 'Quality Rating', 'required|integer|greater_than[0]|less_than[6]');
            $this->form_validation->set_rules('communication_rating', 'Communication Rating', 'required|integer|greater_than[0]|less_than[6]');
            $this->form_validation->set_rules('punctuality_rating', 'Punctuality Rating', 'required|integer|greater_than[0]|less_than[6]');
            $this->form_validation->set_rules('professionalism_comment', 'Professionalism Comment', 'max_length[100]');
            $this->form_validation->set_rules('quality_comment', 'Quality Comment', 'max_length[100]');
            $this->form_validation->set_rules('communication_comment', 'Communication Comment', 'max_length[100]');
            $this->form_validation->set_rules('punctuality_comment', 'Punctuality Comment', 'max_length[100]');
            $this->form_validation->set_rules('private_notes', 'Private Notes', 'max_length[100]');
            
            if (!$this->form_validation->run()) {
                $this->session->set_flashdata('text', 'Review validation failed: ' . strip_tags(validation_errors()));
                $this->session->set_flashdata('type', 'error');
                redirect('host/recall_job/' . $job_id);
            }
        }
        
        // Start database transaction
        $this->db->trans_start();
        
        // 1. Create the review (only if it doesn't exist)
        $review_id = null;
        if (!$existing_review) {
            $review_data = [
                'job_id' => $job_id,
                'reviewer_id' => $user_id,
                'reviewee_id' => $job->assigned_cleaner_id,
                'review_type' => 'host_to_cleaner',
                
                // Public data
                'overall_rating' => $this->input->post('overall_rating'),
                'public_comment' => $this->input->post('public_comment'),
                
                // Private category data
                'professionalism_rating' => $this->input->post('professionalism_rating'),
                'professionalism_comment' => $this->input->post('professionalism_comment') ?: '',
                'quality_rating' => $this->input->post('quality_rating'),
                'quality_comment' => $this->input->post('quality_comment') ?: '',
                'communication_rating' => $this->input->post('communication_rating'),
                'communication_comment' => $this->input->post('communication_comment') ?: '',
                'punctuality_rating' => $this->input->post('punctuality_rating'),
                'punctuality_comment' => $this->input->post('punctuality_comment') ?: '',
                'private_notes' => $this->input->post('private_notes') ?: ''
            ];
            
            try {
                $review_id = $this->M_reviews->create_review($review_data);
            } catch (Exception $e) {
                // Review creation failed, but we can continue with recall
                log_message('error', 'Review creation failed during recall: ' . $e->getMessage());
            }
        } else {
            // Review already exists, use existing review ID
            $review_id = $existing_review->id;
        }
        
        // Skip validation if review already exists (for closed jobs)
        if (!$existing_review && !$review_id) {
            $this->db->trans_rollback();
            log_message('error', 'Recall review creation failed');
            
            $this->session->set_flashdata('text', 'Failed to submit review. Please try again.');
            $this->session->set_flashdata('type', 'error');
            redirect('host/recall_job/' . $job_id);
        }
        
        // 2. Update job status to recalled
        $columns = $this->db->list_fields('jobs');
        
        // Build update data with only existing columns
        $update_data = [
            'status' => 'recalled',
            'host_reviewed' => 1
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
        
        // If it's a completed job, also release payment
        if ($job->status === 'completed' && in_array('payment_released_at', $columns)) {
            $update_data['payment_released_at'] = date('Y-m-d H:i:s');
        }
        
        try {
            $this->db->where('id', $job_id);
            $job_updated = $this->db->update('jobs', $update_data);
            
            if (!$job_updated) {
                throw new Exception('Job update failed');
            }
            
            // 3. Send notifications
            $this->load->model('M_notifications');
            
            // Send notification to admin
            try {
                $this->M_notifications->create_notification(
                    1, // Assuming admin user ID is 1, adjust as needed
                    'Job Recall - Admin Review Required',
                    'Host has recalled job "' . $job->title . '" for review. Reason: ' . $recall_reason . ' (Severity: ' . $severity . ')',
                    'admin/recalled-jobs'
                );
            } catch (Exception $e) {
                log_message('error', 'Failed to send admin notification: ' . $e->getMessage());
            }
            
            // Send notification to cleaner
            if ($job->assigned_cleaner_id) {
                try {
                    $this->M_notifications->create_notification(
                        $job->assigned_cleaner_id,
                        'Job Recalled & Review Received',
                        'The host has recalled job "' . $job->title . '" and left you a review. Admin will review the recall.',
                        'cleaner/recalled-jobs'
                    );
                } catch (Exception $e) {
                    log_message('error', 'Failed to send cleaner notification: ' . $e->getMessage());
                }
            }
            
            // Complete transaction
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                throw new Exception('Transaction failed');
            }
            
            // Check if this is an AJAX request
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Job recalled successfully! Review submitted and admin has been notified for review.',
                    'redirect' => base_url('host/recalled-jobs')
                ]);
                return;
            }
            
            $this->session->set_flashdata('text', 'Job recalled successfully! Review submitted and admin has been notified for review.');
            $this->session->set_flashdata('type', 'success');
            redirect('host/recalled-jobs');
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Recall process failed: ' . $e->getMessage());
            log_message('error', 'Job ID: ' . $job_id . ', Update data: ' . json_encode($update_data));
            
            // Check if this is an AJAX request
            if ($this->input->is_ajax_request()) {
                echo json_encode([
                    'success' => false,
                    'message' => 'An error occurred while processing the recall: ' . $e->getMessage()
                ]);
                return;
            }
            
            $this->session->set_flashdata('text', 'An error occurred while processing the recall: ' . $e->getMessage());
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
    
    /**
     * Download Job Details as PDF
     * Generate a comprehensive PDF with all job information for proof/records
     */
    public function download_job_pdf($job_id)
    {
        $user_id = $this->auth_user_id;
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        // Verify ownership
        if (!$job || $job->host_id != $user_id) {
            show_404();
            return;
        }
        
        // Verify job is in past jobs (closed, recalled, or recall_settled)
        if (!in_array($job->status, ['closed', 'recalled', 'recall_settled'])) {
            show_error('PDF can only be generated for completed jobs.');
            return;
        }
        
        // Load necessary models
        $this->load->model('M_reviews');
        
        // Fetch pricing parameters
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10.00,
            'app_percent' => 15.00
        ];
        
        if ($this->db->table_exists('pricing_settings')) {
            $pricing_settings = $this->db->get('pricing_settings')->row();
            if ($pricing_settings) {
                $pricing_params = [
                    'base_charge' => $pricing_settings->base_charge,
                    'tax_percent' => $pricing_settings->tax_percent,
                    'app_percent' => $pricing_settings->app_percent
                ];
            }
        }
        
        // Get accepted offer details
        $accepted_offer = $this->db
            ->where('job_id', $job->id)
            ->where('status', 'accepted')
            ->get('offers')
            ->row();
        
        // Calculate cleaner payout
        if ($accepted_offer && !empty($accepted_offer->cleaner_payout)) {
            $cleaner_payout = $accepted_offer->cleaner_payout;
        } elseif ($accepted_offer) {
            $offer_amount = $accepted_offer->amount;
            $tax_amount = ($offer_amount * $pricing_params['tax_percent']) / 100;
            $app_amount = ($offer_amount * $pricing_params['app_percent']) / 100;
            $cleaner_payout = $offer_amount - $pricing_params['base_charge'] - $tax_amount - $app_amount;
        } else {
            $base_price = $job->final_price ?: ($job->accepted_price ?: $job->suggested_price);
            $tax_amount = ($base_price * $pricing_params['tax_percent']) / 100;
            $app_amount = ($base_price * $pricing_params['app_percent']) / 100;
            $cleaner_payout = $base_price - $pricing_params['base_charge'] - $tax_amount - $app_amount;
        }
        
        // Get reviews
        $host_review = $this->M_reviews->get_review_by_job_and_reviewer($job->id, $user_id);
        $cleaner_review = null;
        if (!empty($job->assigned_cleaner_id)) {
            $cleaner_review = $this->M_reviews->get_review_by_job_and_reviewer($job->id, $job->assigned_cleaner_id);
        }
        
        // Load TCPDF library
        require_once(APPPATH . 'third_party/tcpdf/tcpdf.php');
        
        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator('EasyClean');
        $pdf->SetAuthor('EasyClean');
        $pdf->SetTitle('Job Details - ' . $job->title);
        $pdf->SetSubject('Job Completion Record');
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add a page
        $pdf->AddPage();
        
        // Set font
        $pdf->SetFont('helvetica', '', 10);
        
        // Build HTML content
        $html = $this->_build_job_pdf_html($job, $accepted_offer, $cleaner_payout, $pricing_params, $host_review, $cleaner_review);
        
        // Output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Close and output PDF document
        $filename = 'Job_' . $job->id . '_' . preg_replace('/[^A-Za-z0-9_]/', '_', $job->title) . '_' . date('Y-m-d') . '.pdf';
        $pdf->Output($filename, 'D'); // D = download
    }
    
    /**
     * Build HTML content for PDF
     */
    private function _build_job_pdf_html($job, $accepted_offer, $cleaner_payout, $pricing_params, $host_review, $cleaner_review)
    {
        $payment_amount = $job->payment_amount ?: ($job->final_price ?: $job->accepted_price ?: 0);
        $cleaner_name = trim(($job->cleaner_first_name ?? '') . ' ' . ($job->cleaner_last_name ?? ''));
        if (empty($cleaner_name)) {
            $cleaner_name = $job->cleaner_username ?? 'Unknown Cleaner';
        }
        
        $html = '
        <style>
            h1 { color: #667eea; font-size: 24px; margin-bottom: 10px; }
            h2 { color: #667eea; font-size: 18px; margin-top: 15px; margin-bottom: 10px; border-bottom: 2px solid #667eea; padding-bottom: 5px; }
            h3 { color: #495057; font-size: 14px; margin-top: 10px; margin-bottom: 5px; }
            .info-box { background-color: #f8f9fa; padding: 10px; margin: 10px 0; border-left: 4px solid #667eea; }
            .label { font-weight: bold; color: #495057; }
            .value { color: #333; }
            .payment-box { background-color: #d4edda; padding: 10px; margin: 10px 0; border-left: 4px solid #28a745; }
            .warning-box { background-color: #fff3cd; padding: 10px; margin: 10px 0; border-left: 4px solid #ffc107; }
            .success-box { background-color: #d1ecf1; padding: 10px; margin: 10px 0; border-left: 4px solid #17a2b8; }
            .review-box { background-color: #f8f9ff; padding: 10px; margin: 10px 0; border: 1px solid #667eea; }
            table { border-collapse: collapse; width: 100%; margin: 10px 0; }
            table td { padding: 5px; border-bottom: 1px solid #dee2e6; }
            .star { color: #ffc107; }
        </style>
        
        <h1>Job Completion Record</h1>
        <p style="color: #6c757d;">Generated on: ' . date('F j, Y g:i A') . '</p>
        
        <div class="info-box">
            <p><span class="label">Job ID:</span> <span class="value">' . $job->id . '</span></p>
            <p><span class="label">Status:</span> <span class="value">' . strtoupper($job->status) . '</span></p>
        </div>
        
        <h2>Job Details</h2>
        <table>
            <tr><td class="label" width="30%">Title:</td><td>' . htmlspecialchars($job->title) . '</td></tr>
            <tr><td class="label">Description:</td><td>' . nl2br(htmlspecialchars($job->description)) . '</td></tr>
            <tr><td class="label">Property Type:</td><td>' . (!empty($job->property_type) ? ($job->property_type === 'str' ? 'Short Term Rental' : 'Residential') : 'Not specified') . '</td></tr>
            <tr><td class="label">Address:</td><td>' . htmlspecialchars($job->address) . '</td></tr>
            <tr><td class="label">City, State:</td><td>' . htmlspecialchars(($job->city ?? 'N/A') . ', ' . ($job->state ?? 'N/A')) . '</td></tr>
            <tr><td class="label">Scheduled Date:</td><td>' . ($job->scheduled_date ? date('F j, Y', strtotime($job->scheduled_date . ' ' . ($job->scheduled_time ?? ''))) : 'Not scheduled') . '</td></tr>
            <tr><td class="label">Estimated Duration:</td><td>' . ($job->estimated_duration ? ($job->estimated_duration / 60) . ' hours' : 'Not specified') . '</td></tr>
        </table>
        ';
        
        // Additional Services
        if (!empty($job->extras)) {
            $extras = json_decode($job->extras, true);
            if (is_array($extras) && !empty($extras)) {
                $html .= '<h3>Additional Services</h3><p>' . implode(', ', array_map('ucwords', str_replace('_', ' ', $extras))) . '</p>';
            }
        }
        
        // Special Instructions
        if (!empty($job->special_instructions)) {
            $html .= '<h3>Special Instructions</h3><p>' . nl2br(htmlspecialchars($job->special_instructions)) . '</p>';
        }
        
        // Cleaner Information
        $html .= '
        <h2>Cleaner Information</h2>
        <div class="success-box">
            <p><span class="label">Name:</span> <span class="value">' . htmlspecialchars($cleaner_name) . '</span></p>
            <p><span class="label">Username:</span> <span class="value">@' . htmlspecialchars($job->cleaner_username) . '</span></p>
            <p><span class="label">Email:</span> <span class="value">' . htmlspecialchars($job->cleaner_email) . '</span></p>
            ' . (!empty($job->cleaner_phone) ? '<p><span class="label">Phone:</span> <span class="value">' . htmlspecialchars($job->cleaner_phone) . '</span></p>' : '') . '
        </div>
        ';
        
        // Payment Information
        $html .= '
        <h2>Payment Information</h2>
        <div class="payment-box">
            <table>
                <tr><td class="label" width="50%">Your Suggested Price:</td><td>$' . number_format($job->suggested_price, 2) . '</td></tr>';
        
        if ($accepted_offer && $accepted_offer->offer_type === 'counter') {
            $price_diff = $accepted_offer->amount - $job->suggested_price;
            $html .= '<tr><td class="label">Counter Offer Accepted:</td><td>$' . number_format($accepted_offer->amount, 2) . ' (' . ($price_diff > 0 ? '+' : '') . '$' . number_format($price_diff, 2) . ')</td></tr>';
        }
        
        $html .= '
                <tr><td class="label">Amount You Paid:</td><td style="font-weight: bold; color: #28a745;">$' . number_format($payment_amount, 2) . '</td></tr>
                <tr><td class="label">Cleaner\'s Payout:</td><td style="color: #667eea;">$' . number_format($cleaner_payout, 2) . '</td></tr>
            </table>
        </div>
        
        <h3>Payment Breakdown</h3>
        <table>';
        
        $host_payment = $accepted_offer ? $accepted_offer->amount : $payment_amount;
        $tax_amount = ($host_payment * $pricing_params['tax_percent']) / 100;
        $app_amount = ($host_payment * $pricing_params['app_percent']) / 100;
        
        $html .= '
            <tr><td width="50%">Base Amount:</td><td>$' . number_format($host_payment, 2) . '</td></tr>
            <tr><td>- Base Fee:</td><td style="color: #dc3545;">-$' . number_format($pricing_params['base_charge'], 2) . '</td></tr>
            <tr><td>- Tax (' . number_format($pricing_params['tax_percent'], 0) . '%):</td><td style="color: #dc3545;">-$' . number_format($tax_amount, 2) . '</td></tr>
            <tr><td>- App Fee (' . number_format($pricing_params['app_percent'], 0) . '%):</td><td style="color: #dc3545;">-$' . number_format($app_amount, 2) . '</td></tr>
            <tr style="font-weight: bold; background-color: #f8f9fa;"><td>Cleaner Receives:</td><td style="color: #667eea;">$' . number_format($cleaner_payout, 2) . '</td></tr>
        </table>';
        
        if ($job->payment_released_at) {
            $html .= '<p style="font-size: 9px; color: #6c757d;">Payment released on: ' . date('F j, Y g:i A', strtotime($job->payment_released_at)) . '</p>';
        }
        
        // Reviews Section
        if ($host_review || $cleaner_review) {
            $html .= '<h2>Reviews</h2>';
            
            // Host's Review
            if ($host_review) {
                $html .= '
                <div class="review-box">
                    <h3>Your Review of Cleaner</h3>
                    <p><span class="label">Overall Rating:</span> ' . $this->_get_stars_html($host_review->overall_rating) . ' (' . number_format($host_review->overall_rating, 1) . '/5)</p>';
                
                if (!empty($host_review->public_comment)) {
                    $html .= '<p><span class="label">Comment:</span><br/><em>"' . nl2br(htmlspecialchars($host_review->public_comment)) . '"</em></p>';
                }
                
                $html .= '
                    <table>
                        <tr><td width="50%">Professionalism:</td><td>' . $host_review->professionalism_rating . '/5</td></tr>
                        <tr><td>Quality:</td><td>' . $host_review->quality_rating . '/5</td></tr>
                        <tr><td>Communication:</td><td>' . $host_review->communication_rating . '/5</td></tr>
                        <tr><td>Punctuality:</td><td>' . $host_review->punctuality_rating . '/5</td></tr>
                    </table>
                    <p style="font-size: 9px; color: #6c757d;">Reviewed on: ' . date('F j, Y g:i A', strtotime($host_review->created_at)) . '</p>
                </div>';
            }
            
            // Cleaner's Review
            if ($cleaner_review) {
                $html .= '
                <div class="review-box">
                    <h3>Cleaner\'s Review of You</h3>
                    <p><span class="label">Overall Rating:</span> ' . $this->_get_stars_html($cleaner_review->overall_rating) . ' (' . number_format($cleaner_review->overall_rating, 1) . '/5)</p>';
                
                if (!empty($cleaner_review->public_comment)) {
                    $html .= '<p><span class="label">Comment:</span><br/><em>"' . nl2br(htmlspecialchars($cleaner_review->public_comment)) . '"</em></p>';
                }
                
                $html .= '
                    <table>
                        <tr><td width="50%">Professionalism:</td><td>' . $cleaner_review->professionalism_rating . '/5</td></tr>
                        <tr><td>Quality:</td><td>' . $cleaner_review->quality_rating . '/5</td></tr>
                        <tr><td>Communication:</td><td>' . $cleaner_review->communication_rating . '/5</td></tr>
                        <tr><td>Punctuality:</td><td>' . $cleaner_review->punctuality_rating . '/5</td></tr>
                    </table>
                    <p style="font-size: 9px; color: #6c757d;">Reviewed on: ' . date('F j, Y g:i A', strtotime($cleaner_review->created_at)) . '</p>
                </div>';
            }
        }
        
        // Recall Information (if applicable)
        if (in_array($job->status, ['recalled', 'recall_settled']) && !empty($job->recall_reason)) {
            $html .= '
            <h2>Recall Information</h2>
            <div class="warning-box">
                <table>
                    <tr><td class="label" width="30%">Reason:</td><td>' . ucfirst(str_replace('_', ' ', htmlspecialchars($job->recall_reason))) . '</td></tr>';
            
            if (!empty($job->recall_severity)) {
                $html .= '<tr><td class="label">Severity:</td><td>' . strtoupper($job->recall_severity) . '</td></tr>';
            }
            
            if (!empty($job->recalled_at)) {
                $html .= '<tr><td class="label">Recalled On:</td><td>' . date('F j, Y g:i A', strtotime($job->recalled_at)) . '</td></tr>';
            }
            
            if (!empty($job->recall_details)) {
                $html .= '<tr><td class="label">Details:</td><td>' . nl2br(htmlspecialchars($job->recall_details)) . '</td></tr>';
            }
            
            $html .= '</table></div>';
            
            // Settlement Information
            if ($job->status === 'recall_settled') {
                $html .= '
                <h3>Recall Settlement</h3>
                <div class="success-box">
                    <table>';
                
                if (!empty($job->admin_decision)) {
                    $html .= '<tr><td class="label" width="30%">Admin Decision:</td><td>' . ucfirst(str_replace('_', ' ', htmlspecialchars($job->admin_decision))) . '</td></tr>';
                }
                
                if (!empty($job->resolution_type)) {
                    $html .= '<tr><td class="label">Resolution Type:</td><td>' . ucfirst(str_replace('_', ' ', htmlspecialchars($job->resolution_type))) . '</td></tr>';
                }
                
                if (!empty($job->recall_settled_at)) {
                    $html .= '<tr><td class="label">Settled On:</td><td>' . date('F j, Y g:i A', strtotime($job->recall_settled_at)) . '</td></tr>';
                }
                
                if (!empty($job->admin_notes)) {
                    $html .= '<tr><td class="label">Admin Notes:</td><td>' . nl2br(htmlspecialchars($job->admin_notes)) . '</td></tr>';
                }
                
                $html .= '</table></div>';
            }
        }
        
        // Footer
        $html .= '
        <hr style="margin-top: 20px; border: 1px solid #dee2e6;"/>
        <p style="font-size: 9px; color: #6c757d; text-align: center;">
            This document serves as an official record of job completion.<br/>
            Generated by EasyClean on ' . date('F j, Y g:i A') . '<br/>
            Document ID: JOB-' . $job->id . '-' . date('Ymd-His') . '
        </p>';
        
        return $html;
    }
    
    /**
     * Generate star HTML for ratings
     */
    private function _get_stars_html($rating)
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $stars .= '<span class="star">★</span>';
            } elseif ($i - 0.5 <= $rating) {
                $stars .= '<span class="star">⯨</span>';
            } else {
                $stars .= '<span style="color: #dee2e6;">☆</span>';
            }
        }
        return $stars;
    }

}
