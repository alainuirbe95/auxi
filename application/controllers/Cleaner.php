<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cleaner Controller
 * 
 * Handles all cleaner-related functionality including:
 * - Job browsing and bidding
 * - Offer management
 * - Job completion
 * - Dashboard and analytics
 */
class Cleaner extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load required helpers and libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Load required models
        $this->load->model('M_users');
        $this->load->model('M_jobs');
        $this->load->model('M_offers');
        $this->load->model('M_ignored_jobs');
        $this->load->model('M_favorites');
        
        // Initialize session and check if user is logged in and is a cleaner
        $this->init_session_auto(3); // Cleaner level = 3
        
        // Clear flash messages
        $this->session->unset_userdata('text');
        $this->session->unset_userdata('type');
    }

    /**
     * Cleaner Dashboard
     * Main dashboard showing available jobs, statistics, and recent activity
     */
    public function index()
    {
        $user_id = $this->auth_user_id;
        
        // Check profile completion - cleaners need 50%+ completion to access jobs
        $this->load->model('M_user_profiles');
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        if ($completion['percentage'] < 50) {
            $this->session->set_flashdata('text', 'Please complete your profile setup to access job listings. You need at least 50% profile completion.');
            $this->session->set_flashdata('type', 'warning');
            redirect('cleaner/profile-setup-required');
        }
        
        // Get cleaner statistics
        $stats = [
            'total_jobs' => $this->M_jobs->get_total_jobs_for_cleaner($user_id),
            'active_jobs' => $this->M_jobs->get_active_jobs_for_cleaner($user_id),
            'completed_jobs' => $this->M_jobs->get_completed_jobs_count_for_cleaner($user_id),
            'disputed_jobs' => $this->M_jobs->get_disputed_jobs_count_for_cleaner($user_id),
            'closed_jobs' => $this->M_jobs->get_closed_jobs_count_for_cleaner($user_id),
            'pending_offers' => $this->M_offers->get_pending_offers_count_for_cleaner($user_id),
            'total_earnings' => $this->M_jobs->get_total_earnings_for_cleaner($user_id),
            'potential_earnings' => $this->M_jobs->get_potential_earnings_for_cleaner($user_id)
        ];
        
        // Get recent jobs data
        $recent_jobs = $this->M_jobs->get_recent_jobs_for_cleaner($user_id, 5);
        $assigned_jobs = $this->M_jobs->get_assigned_jobs_for_cleaner($user_id);
        $pending_disputes = $this->M_jobs->get_cleaner_disputed_jobs($user_id);
        $pending_price_adjustments = $this->M_jobs->get_pending_price_adjustments_for_cleaner($user_id);
        
        // Add review summary
        $this->load->model('M_reviews');
        $review_stats = $this->M_reviews->calculate_user_average_ratings($user_id);
        $recent_reviews = $this->M_reviews->get_public_reviews_for_user($user_id, 3);
        
        // Get dashboard data
        $data = [
            'title' => 'Cleaner Dashboard',
            'page_icon' => 'fas fa-broom',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'stats' => $stats,
            'recent_jobs' => $recent_jobs,
            'assigned_jobs' => $assigned_jobs,
            'pending_disputes' => $pending_disputes,
            'pending_price_adjustments' => $pending_price_adjustments,
            'review_stats' => $review_stats,
            'recent_reviews' => $recent_reviews
        ];
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the dashboard content as a string
        $data['body'] = $this->load->view('cleaner/dashboard', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Browse Available Jobs
     * Show all active jobs that cleaners can apply to
     */
    public function jobs()
    {
        $user_id = $this->auth_user_id;
        
        // Check profile completion - cleaners need 50%+ completion to access jobs
        $this->load->model('M_user_profiles');
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        if ($completion['percentage'] < 50) {
            $this->session->set_flashdata('text', 'Please complete your profile setup to access job listings. You need at least 50% profile completion.');
            $this->session->set_flashdata('type', 'warning');
            redirect('cleaner/profile-setup-required');
        }
        
        // Get filter parameters
        $filters = [
            'search' => $this->input->get('search'),
            'max_price' => $this->input->get('max_price'),
            'min_price' => $this->input->get('min_price'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        ];
        
        // Pagination
        $page = $this->input->get('page') ?: 1;
        $per_page = 12;
        $offset = ($page - 1) * $per_page;
        
        // Get cleaner's service areas and STR status
        $service_areas = [];
        $cleaner_offers_str = false;
        if ($this->db->table_exists('user_profiles')) {
            $this->load->model('M_user_profiles');
            $service_areas = $this->M_user_profiles->get_cleaner_service_locations($user_id);
            
            // Check if cleaner offers STR services
            $cleaner_profile = $this->M_user_profiles->get_profile_by_user_id($user_id);
            $cleaner_offers_str = !empty($cleaner_profile->services_str);
        }
        
        // Add STR filter based on cleaner's capability
        $filters['cleaner_offers_str'] = $cleaner_offers_str;
        $filters['cleaner_id'] = $user_id;

        // Get jobs data
        $data = [
            'title' => 'Browse Jobs',
            'page_icon' => 'fas fa-search',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Browse Jobs', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'filters' => $filters,
            'page' => $page,
            'per_page' => $per_page,
            'service_areas' => $service_areas,
            'cleaner_offers_str' => $cleaner_offers_str
        ];
        
        // Load jobs if tables exist
        if ($this->db->table_exists('jobs')) {
            // Get jobs with favorites status, excluding ignored ones
            if ($this->db->table_exists('job_favorites')) {
                $data['jobs'] = $this->M_favorites->get_jobs_with_favorites($user_id, $filters, $per_page, $offset);
                $data['total_jobs'] = $this->M_jobs->count_active_jobs($filters, $user_id);
            } else {
                // Fallback to regular job loading
                if ($this->db->table_exists('ignored_jobs')) {
                    $data['jobs'] = $this->M_ignored_jobs->get_available_jobs_excluding_ignored($user_id, $filters, $per_page, $offset);
                } else {
                    $data['jobs'] = $this->M_jobs->get_active_jobs($filters, $per_page, $offset);
                }
                $data['total_jobs'] = $this->M_jobs->count_active_jobs($filters, $user_id);
            }
            $data['total_pages'] = ceil($data['total_jobs'] / $per_page);
            
            // Check which jobs the cleaner has already applied to and add favorite status
            // Also fetch host ratings for each job
            $this->load->model('M_reviews');
            if ($this->db->table_exists('offers')) {
                foreach ($data['jobs'] as $job) {
                    $job->has_applied = $this->M_offers->cleaner_has_offered($user_id, $job->id);
                    $job->has_been_declined = $this->M_offers->cleaner_has_been_declined($user_id, $job->id);
                    $job->is_favorited = $this->db->table_exists('job_favorites') ? $this->M_favorites->is_job_favorited($user_id, $job->id) : false;
                    
                    // Add host rating
                    if (!empty($job->host_id)) {
                        $host_ratings = $this->M_reviews->calculate_user_average_ratings($job->host_id);
                        $job->host_rating = $host_ratings['overall_average'] ?? 0;
                        $job->host_review_count = $host_ratings['total_reviews'] ?? 0;
                    } else {
                        $job->host_rating = 0;
                        $job->host_review_count = 0;
                    }
                }
            } else {
                foreach ($data['jobs'] as $job) {
                    $job->has_applied = false;
                    $job->has_been_declined = false;
                    $job->is_favorited = $this->db->table_exists('job_favorites') ? $this->M_favorites->is_job_favorited($user_id, $job->id) : false;
                    
                    // Add host rating
                    if (!empty($job->host_id)) {
                        $host_ratings = $this->M_reviews->calculate_user_average_ratings($job->host_id);
                        $job->host_rating = $host_ratings['overall_average'] ?? 0;
                        $job->host_review_count = $host_ratings['total_reviews'] ?? 0;
                    } else {
                        $job->host_rating = 0;
                        $job->host_review_count = 0;
                    }
                }
            }
        } else {
            $data['jobs'] = [];
            $data['total_jobs'] = 0;
            $data['total_pages'] = 0;
        }
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the jobs browse content as a string
        $data['body'] = $this->load->view('cleaner/jobs_browse', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * View Rejected Offers
     * Show all rejected offers for the cleaner
     */
    public function rejected_offers()
    {
        $user_id = $this->auth_user_id;
        
        if (!$user_id) {
            $this->session->set_flashdata('text', 'You must be logged in to view rejected offers.');
            $this->session->set_flashdata('type', 'error');
            redirect('app/login');
        }
        
        $data = [
            'title' => 'Rejected Offers',
            'page_icon' => 'fas fa-times-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Rejected Offers', 'url' => '', 'active' => true]
            ],
            'rejected_offers' => $this->M_offers->get_rejected_offers_for_cleaner($user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the rejected offers content as a string
        $data['body'] = $this->load->view('cleaner/rejected_offers', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * View Job Details
     * Show detailed information about a specific job
     */
    public function job($job_id)
    {
        $user_id = $this->auth_user_id;
        
        // Check profile completion - cleaners need 50%+ completion to view job details
        $this->load->model('M_user_profiles');
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        if ($completion['percentage'] < 50) {
            $this->session->set_flashdata('text', 'Please complete your profile setup to view job details. You need at least 50% profile completion.');
            $this->session->set_flashdata('type', 'warning');
            redirect('cleaner/profile-setup-required');
        }
        
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            show_404();
        }
        
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            show_404();
        }
        
        // Check if user has access to this job
        $has_access = false;
        
        // Cleaner can view if:
        // 1. Job is open (for making offers)
        // 2. Job is assigned to them (for viewing assigned job details)
        if ($job->status === 'open') {
            $has_access = true;
        } elseif ($job->status === 'assigned' && $job->assigned_cleaner_id == $user_id) {
            $has_access = true;
        } elseif ($job->status === 'in_progress' && $job->assigned_cleaner_id == $user_id) {
            $has_access = true;
        }
        
        if (!$has_access) {
            show_404();
        }
        
        // Check if cleaner has already applied
        $has_applied = false;
        if ($this->db->table_exists('offers')) {
            $has_applied = $this->M_offers->cleaner_has_offered($user_id, $job_id);
        }
        
        // Check if this cleaner is assigned to this job
        $is_assigned = ($job->assigned_cleaner_id == $user_id);
        
        // Get pricing parameters for payout calculation
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
        
        // Load host profile information
        $this->load->model('M_user_profiles');
        $host_profile = $this->M_user_profiles->get_profile_with_user_data($job->host_id);
        
        // Get host reviews and ratings
        $this->load->model('M_reviews');
        $host_reviews = $this->M_reviews->get_public_reviews_for_user($job->host_id, 10);
        $host_review_stats = $this->M_reviews->calculate_user_average_ratings($job->host_id);
        $host_rating_distribution = $this->M_reviews->get_rating_distribution($job->host_id);
        
        // Get host statistics
        $host_stats = [
            'total_jobs' => $this->M_jobs->get_total_jobs_for_host($job->host_id),
            'active_jobs' => count($this->M_jobs->get_host_active_jobs($job->host_id)),
            'completed_jobs' => $this->M_jobs->get_completed_jobs_count_for_host($job->host_id),
            'average_rating' => $host_review_stats['overall_average'] ?? 0,
            'total_reviews' => $host_review_stats['total_reviews'] ?? 0
        ];
        
        $data = [
            'title' => 'Job Details - ' . $job->title,
            'page_icon' => 'fas fa-clipboard-list',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Browse Jobs', 'url' => 'cleaner/jobs'],
                ['title' => $job->title, 'url' => '', 'active' => true]
            ],
            'job' => $job,
            'has_applied' => $has_applied,
            'is_assigned' => $is_assigned,
            'pricing_params' => $pricing_params,
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'host_profile' => $host_profile,
            'host_stats' => $host_stats,
            'host_reviews' => $host_reviews,
            'host_review_stats' => $host_review_stats,
            'host_rating_distribution' => $host_rating_distribution
        ];
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the job details content as a string
        $data['body'] = $this->load->view('cleaner/job_details', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Make an Offer
     * Allow cleaner to submit an offer for a job
     */
    public function make_offer($job_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        
        // Check profile completion - cleaners need 50%+ completion to make offers
        $this->load->model('M_user_profiles');
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        if ($completion['percentage'] < 50) {
            $this->output->set_status_header(400);
            $this->output->set_content_type('application/json');
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Please complete your profile setup to make offers. You need at least 50% profile completion.'
            ]));
            return;
        }
        
        if (!$user_id) {
            $this->session->set_flashdata('text', 'You must be logged in to make an offer.');
            $this->session->set_flashdata('type', 'error');
            redirect('app/login');
        }
        
        // Check if required tables exist
        if (!$this->db->table_exists('jobs') || !$this->db->table_exists('offers')) {
            $this->session->set_flashdata('text', 'Marketplace functionality not available yet.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/job/' . $job_id);
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->status !== 'open') {
            show_404();
        }
        
        // Check if cleaner has already applied
        if ($this->M_offers->cleaner_has_offered($user_id, $job_id)) {
            $this->session->set_flashdata('text', 'You have already applied to this job.');
            $this->session->set_flashdata('type', 'warning');
            redirect('cleaner/job/' . $job_id);
        }
        
        // Set validation rules
        $this->form_validation->set_rules('offer_type', 'Offer Type', 'required|in_list[accept,counter]');
        
        // Validate amount based on offer type
        $offer_type = $this->input->post('offer_type');
        if ($offer_type === 'counter') {
            $this->form_validation->set_rules('amount', 'Counter Offer Amount', 'required|decimal|greater_than[0]');
        } else if ($offer_type === 'accept') {
            // For accept offers, amount is optional (will use suggested price if not provided)
            $this->form_validation->set_rules('amount', 'Amount', 'decimal|greater_than[0]');
        }
        
        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->session->set_flashdata('text', 'Please correct the errors below: ' . implode(', ', $errors));
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/job/' . $job_id);
        }
        
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
        
        // Prepare offer data based on offer type
        $cleaner_payout = 0;
        $host_price = 0;
        
        if ($offer_type === 'accept') {
            // Accept: Cleaner accepts the calculated payout from host's suggested price
            $host_price = $job->suggested_price;
            
            // Calculate cleaner's payout
            $tax_amount = ($host_price * $pricing_params['tax_percent']) / 100;
            $app_fee = ($host_price * $pricing_params['app_percent']) / 100;
            $cleaner_payout = $host_price - $pricing_params['base_charge'] - $tax_amount - $app_fee;
            
        } else if ($offer_type === 'counter') {
            // Counter: Cleaner specifies desired payout, calculate adjusted host price
            $desired_payout = (float)$this->input->post('amount');
            
            // Reverse calculation: host_price = (desired_payout + base_charge) / (1 - (tax% + app%)/100)
            $fee_percentage = ($pricing_params['tax_percent'] + $pricing_params['app_percent']) / 100;
            $host_price = ($desired_payout + $pricing_params['base_charge']) / (1 - $fee_percentage);
            $cleaner_payout = $desired_payout;
        }
        
        // Round to 2 decimal places
        $host_price = number_format($host_price, 2, '.', '');
        $cleaner_payout = number_format($cleaner_payout, 2, '.', '');
        
        $offer_data = [
            'job_id' => $job_id,
            'cleaner_id' => $user_id,
            'offer_type' => $offer_type,
            'amount' => $host_price,  // What the host will pay
            'cleaner_payout' => $cleaner_payout,  // What the cleaner will receive
            'original_price' => $job->suggested_price,
            'status' => 'pending'
        ];
        
        // Set expiration and counter price for counter offers
        if ($offer_type === 'counter') {
            $offer_data['expires_at'] = date('Y-m-d H:i:s', strtotime('+6 hours'));
            $offer_data['counter_price'] = $host_price;  // Host's adjusted price
            $offer_data['counter_offered_at'] = date('Y-m-d H:i:s');
        } else {
            // Accept offers don't expire
            $offer_data['expires_at'] = null;
        }
        
        // Create the offer
        $offer_id = $this->M_offers->create_offer($offer_data);
        
        if ($offer_id) {
            $this->session->set_flashdata('text', 'Your offer has been submitted successfully!');
            $this->session->set_flashdata('type', 'success');
        } else {
            // Get database error for debugging
            $db_error = $this->db->error();
            $error_message = 'Failed to submit offer. ';
            if (!empty($db_error['message'])) {
                $error_message .= 'Database error: ' . $db_error['message'];
            }
            $this->session->set_flashdata('text', $error_message);
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('cleaner/job/' . $job_id);
    }

    /**
     * View My Offers
     * Show all offers made by the cleaner
     */
    public function offers()
    {
        $user_id = $this->auth_user_id;
        
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            $data = [
                'title' => 'My Offers',
                'page_icon' => 'fas fa-handshake',
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => 'cleaner'],
                    ['title' => 'My Offers', 'url' => '', 'active' => true]
                ],
                'offers' => [],
                'user_info' => $this->M_users->get_user_by_id($user_id)
            ];
        } else {
            // Get offers data
            $offers = $this->M_offers->get_offers_by_cleaner($user_id);
            
            $data = [
                'title' => 'My Offers',
                'page_icon' => 'fas fa-handshake',
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => 'cleaner'],
                    ['title' => 'My Offers', 'url' => '', 'active' => true]
                ],
                'offers' => $offers,
                'user_info' => $this->M_users->get_user_by_id($user_id)
            ];
        }
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the offers content as a string
        $data['body'] = $this->load->view('cleaner/offers', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
    
    /**
     * Show all job applications (offers) made by the cleaner
     * Replaces the old "My Offers" page with better naming
     */
    public function applications()
    {
        $user_id = $this->auth_user_id;
        
        // Get filters
        $date_from = $this->input->get('date_from');
        $date_to = $this->input->get('date_to');
        $status_filter = $this->input->get('status');
        
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            $data = [
                'title' => 'My Job Applications',
                'page_icon' => 'fas fa-file-invoice',
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => 'cleaner'],
                    ['title' => 'Job Applications', 'url' => '', 'active' => true]
                ],
                'offers' => [],
                'pending_offers' => [],
                'accepted_offers' => [],
                'declined_offers' => [],
                'user_info' => $this->M_users->get_user_by_id($user_id)
            ];
        } else {
            // Get offers data (all applications)
            $offers = $this->M_offers->get_offers_by_cleaner($user_id);
            
            // Apply filters
            $filtered_offers = [];
            foreach ($offers as $offer) {
                // Date filter
                if ($date_from && strtotime($offer->created_at) < strtotime($date_from)) {
                    continue;
                }
                if ($date_to && strtotime($offer->created_at) > strtotime($date_to . ' 23:59:59')) {
                    continue;
                }
                
                // Status filter
                if ($status_filter) {
                    if ($status_filter === 'pending' && !($offer->status === 'pending' && $offer->job_status === 'open')) {
                        continue;
                    }
                    if ($status_filter === 'accepted' && !($offer->status === 'accepted' || ($offer->status === 'pending' && in_array($offer->job_status, ['assigned', 'in_progress'])))) {
                        continue;
                    }
                    if ($status_filter === 'declined' && !($offer->status === 'declined' || ($offer->status === 'pending' && !in_array($offer->job_status, ['open', 'assigned', 'in_progress'])))) {
                        continue;
                    }
                }
                
                $filtered_offers[] = $offer;
            }
            
            // Separate offers by status for better organization
            $pending_offers = [];
            $accepted_offers = [];
            $declined_offers = [];
            
            foreach ($filtered_offers as $offer) {
                if ($offer->status === 'pending' && $offer->job_status === 'open') {
                    $pending_offers[] = $offer;
                } elseif ($offer->status === 'accepted' || ($offer->status === 'pending' && in_array($offer->job_status, ['assigned', 'in_progress']))) {
                    $accepted_offers[] = $offer;
                } else {
                    $declined_offers[] = $offer;
                }
            }
            
            $data = [
                'title' => 'My Job Applications',
                'page_icon' => 'fas fa-file-invoice',
                'breadcrumbs' => [
                    ['title' => 'Dashboard', 'url' => 'cleaner'],
                    ['title' => 'Job Applications', 'url' => '', 'active' => true]
                ],
                'offers' => $filtered_offers,
                'pending_offers' => $pending_offers,
                'accepted_offers' => $accepted_offers,
                'declined_offers' => $declined_offers,
                'user_info' => $this->M_users->get_user_by_id($user_id)
            ];
        }
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the applications view
        $data['body'] = $this->load->view('cleaner/applications', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Accept Host's Counter Offer
     * Allow cleaner to accept a counter offer from the host
     */
    public function accept_counter_offer($offer_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            $this->session->set_flashdata('text', 'Marketplace functionality not available yet.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/offers');
        }
        
        // Get offer details
        $offer = $this->M_offers->get_offer_by_id($offer_id);
        
        if (!$offer || $offer->cleaner_id != $user_id) {
            show_404();
        }
        
        // Check if offer has a counter offer
        if (!$offer->counter_amount || $offer->counter_amount <= 0) {
            $this->session->set_flashdata('text', 'No counter offer available to accept.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/offers');
        }
        
        // Update offer to accepted
        $update_data = [
            'status' => 'accepted',
            'accepted_at' => date('Y-m-d H:i:s'),
            'final_amount' => $offer->counter_amount
        ];
        
        if ($this->M_offers->update_offer($offer_id, $update_data)) {
            // Update job status to assigned
            $this->M_jobs->update_job_status($offer->job_id, 'assigned');
            
            $this->session->set_flashdata('text', 'Counter offer accepted successfully!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to accept counter offer. Please try again.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('cleaner/offers');
    }

    /**
     * Reject Host's Counter Offer
     * Allow cleaner to reject a counter offer from the host
     */
    public function reject_counter_offer($offer_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            $this->session->set_flashdata('text', 'Marketplace functionality not available yet.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/offers');
        }
        
        // Get offer details
        $offer = $this->M_offers->get_offer_by_id($offer_id);
        
        if (!$offer || $offer->cleaner_id != $user_id) {
            show_404();
        }
        
        // Check if offer has a counter offer
        if (!$offer->counter_amount || $offer->counter_amount <= 0) {
            $this->session->set_flashdata('text', 'No counter offer available to reject.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/offers');
        }
        
        // Update offer to rejected
        $update_data = [
            'status' => 'rejected',
            'rejected_at' => date('Y-m-d H:i:s'),
            'rejection_reason' => 'Cleaner rejected counter offer'
        ];
        
        if ($this->M_offers->update_offer($offer_id, $update_data)) {
            $this->session->set_flashdata('text', 'Counter offer rejected successfully.');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to reject counter offer. Please try again.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('cleaner/offers');
    }

    /**
     * Make Counter Offer
     * Allow cleaner to make a counter offer to the host
     */
    public function make_counter_offer($offer_id)
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            $this->session->set_flashdata('text', 'Marketplace functionality not available yet.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/offers');
        }
        
        // Get offer details
        $offer = $this->M_offers->get_offer_by_id($offer_id);
        
        if (!$offer || $offer->cleaner_id != $user_id) {
            show_404();
        }
        
        // Set validation rules
        $this->form_validation->set_rules('counter_amount', 'Counter Offer Amount', 'required|decimal|greater_than[0]');
        $this->form_validation->set_rules('counter_message', 'Message', 'trim|max_length[500]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('text', 'Please correct the errors below.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/offers');
        }
        
        // Update offer with counter offer
        $update_data = [
            'counter_price' => $this->input->post('counter_amount'),
            'counter_message' => $this->input->post('counter_message'),
            'counter_offered_at' => date('Y-m-d H:i:s'),
            'status' => 'pending'  // Keep as pending since it's waiting for host response
        ];
        
        if ($this->M_offers->update_offer($offer_id, $update_data)) {
            $this->session->set_flashdata('text', 'Counter offer submitted successfully!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to submit counter offer. Please try again.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('cleaner/offers');
    }

    /**
     * View Earnings
     * Show cleaner's earnings and payment history
     */
    public function earnings()
    {
        $user_id = $this->auth_user_id;
        
        if (!$user_id) {
            show_error('User not authenticated. Please login again.', 401);
        }
        
        // Get date range filters from GET parameters
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        
        // Set default date range if not provided (current month)
        if (!$start_date) {
            $start_date = date('Y-m-01'); // First day of current month
        }
        if (!$end_date) {
            $end_date = date('Y-m-d'); // Today
        }
        
        // Get closed jobs only with dispute information
        $closed_jobs = $this->M_jobs->get_closed_jobs_for_cleaner($user_id, $start_date, $end_date);
        
        // Fetch pricing parameters
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10.00,
            'app_percent' => 15.00
        ];
        
        $pricing_settings = $this->db->get('pricing_settings')->row();
        if ($pricing_settings) {
            $pricing_params = [
                'base_charge' => $pricing_settings->base_charge,
                'tax_percent' => $pricing_settings->tax_percent,
                'app_percent' => $pricing_settings->app_percent
            ];
        }
        
        // Get dispute and price adjustment information for each job
        $jobs_with_details = [];
        foreach ($closed_jobs as $job) {
            $job_details = $job;
            $job_details->dispute_info = null;
            $job_details->price_adjustments = [];
            $job_details->accepted_offer = null;
            $job_details->cleaner_payout = null;
            
            // Get accepted offer details (for counter offers)
            $accepted_offer = $this->db
                ->where('job_id', $job->id)
                ->where('status', 'accepted')
                ->get('offers')
                ->row();
            
            if ($accepted_offer) {
                $job_details->accepted_offer = $accepted_offer;
                
                // Calculate actual cleaner payout
                if (!empty($accepted_offer->cleaner_payout)) {
                    $job_details->cleaner_payout = $accepted_offer->cleaner_payout;
                } else {
                    // Calculate from offer amount
                    $offer_amount = $accepted_offer->amount;
                    $tax_amount = ($offer_amount * $pricing_params['tax_percent']) / 100;
                    $app_amount = ($offer_amount * $pricing_params['app_percent']) / 100;
                    $job_details->cleaner_payout = $offer_amount - $pricing_params['base_charge'] - $tax_amount - $app_amount;
                }
            } else {
                // Calculate from suggested price or final price
                $base_price = $job->final_price ?: ($job->accepted_price ?: $job->suggested_price);
                $tax_amount = ($base_price * $pricing_params['tax_percent']) / 100;
                $app_amount = ($base_price * $pricing_params['app_percent']) / 100;
                $job_details->cleaner_payout = $base_price - $pricing_params['base_charge'] - $tax_amount - $app_amount;
            }
            
            // Get dispute information if applicable
            if ($job->dispute_resolution) {
                $job_details->dispute_info = [
                    'disputed_at' => $job->disputed_at,
                    'dispute_reason' => $job->dispute_reason,
                    'dispute_resolution' => $job->dispute_resolution,
                    'dispute_resolution_notes' => $job->dispute_resolution_notes,
                    'dispute_resolved_at' => $job->dispute_resolved_at,
                    'payment_amount' => $job->payment_amount
                ];
            }
            
            // Get price adjustment requests if applicable
            if ($job->status === 'price_adjustment_requested') {
                $this->load->model('M_counter_offers');
                $job_details->price_adjustments = $this->M_counter_offers->get_counter_offers_for_job($job->id);
            }
            
            $jobs_with_details[] = $job_details;
        }
        
        // Get earnings summary with date filtering
        $earnings_summary = $this->M_jobs->get_cleaner_earnings_summary($user_id, $start_date, $end_date);
        
        // Calculate total cleaner payout (not host's payment amount)
        $total_cleaner_payout = 0;
        foreach ($jobs_with_details as $job) {
            $total_cleaner_payout += $job->cleaner_payout;
        }
        
        // Override the total_earnings with cleaner's actual payout
        $earnings_summary['total_earnings'] = $total_cleaner_payout;
        $earnings_summary['avg_earnings'] = count($jobs_with_details) > 0 ? ($total_cleaner_payout / count($jobs_with_details)) : 0;
        
        $data = [
            'title' => 'My Earnings',
            'page_icon' => 'fas fa-dollar-sign',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Earnings', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'closed_jobs' => $jobs_with_details,
            'earnings_summary' => $earnings_summary,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'user_id' => $user_id
        ];
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the earnings content as a string
        $data['body'] = $this->load->view('cleaner/earnings', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Ignore a job
     */
    public function ignore_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        $reason = $this->input->post('reason');

        if (!$job_id) {
            $this->session->set_flashdata('text', 'Invalid job ID.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/jobs');
        }

        // Check if job exists and is open
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job || $job->status !== 'open') {
            $this->session->set_flashdata('text', 'Job not found or not available.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/jobs');
        }

        // Ignore the job
        if ($this->M_ignored_jobs->ignore_job($user_id, $job_id, $reason)) {
            $this->session->set_flashdata('text', 'Job ignored successfully.');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to ignore job. It may already be ignored.');
            $this->session->set_flashdata('type', 'error');
        }

        redirect('cleaner/jobs');
    }

    /**
     * View ignored jobs
     */
    public function ignored_jobs()
    {
        $user_id = $this->auth_user_id;
        
        // Pagination
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $per_page = 12;
        $offset = ($page - 1) * $per_page;
        
        $data = [
            'title' => 'Ignored Jobs',
            'page_icon' => 'fas fa-eye-slash',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Ignored Jobs', 'url' => '', 'active' => true]
            ],
            'page' => $page,
            'per_page' => $per_page
        ];
        
        // Load ignored jobs if table exists
        if ($this->db->table_exists('ignored_jobs')) {
            $data['jobs'] = $this->M_ignored_jobs->get_ignored_jobs($user_id, $per_page, $offset);
            $data['total_jobs'] = $this->M_ignored_jobs->count_ignored_jobs($user_id);
            $data['total_pages'] = ceil($data['total_jobs'] / $per_page);
        } else {
            $data['jobs'] = [];
            $data['total_jobs'] = 0;
            $data['total_pages'] = 0;
        }
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the ignored jobs content as a string
        $data['body'] = $this->load->view('cleaner/ignored_jobs', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Unignore a job
     */
    public function unignore_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');

        if (!$job_id) {
            $this->session->set_flashdata('text', 'Invalid job ID.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/ignored_jobs');
        }

        // Unignore the job
        if ($this->M_ignored_jobs->unignore_job($user_id, $job_id)) {
            $this->session->set_flashdata('text', 'Job removed from ignored list.');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to remove job from ignored list.');
            $this->session->set_flashdata('type', 'error');
        }

        redirect('cleaner/ignored_jobs');
    }

    /**
     * Toggle job favorite status
     */
    public function toggle_favorite()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');

        if (!$job_id) {
            $this->session->set_flashdata('text', 'Invalid job ID.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/jobs');
        }

        // Check if job exists and is open
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job || $job->status !== 'open') {
            $this->session->set_flashdata('text', 'Job not found or not available.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/jobs');
        }

        // Toggle favorite status
        if ($this->M_favorites->is_job_favorited($user_id, $job_id)) {
            // Remove from favorites
            if ($this->M_favorites->remove_favorite($user_id, $job_id)) {
                $this->session->set_flashdata('text', 'Job removed from favorites.');
                $this->session->set_flashdata('type', 'success');
            } else {
                $this->session->set_flashdata('text', 'Failed to remove job from favorites.');
                $this->session->set_flashdata('type', 'error');
            }
        } else {
            // Add to favorites
            if ($this->M_favorites->add_favorite($user_id, $job_id)) {
                $this->session->set_flashdata('text', 'Job added to favorites.');
                $this->session->set_flashdata('type', 'success');
            } else {
                $this->session->set_flashdata('text', 'Failed to add job to favorites.');
                $this->session->set_flashdata('type', 'error');
            }
        }

        redirect('cleaner/jobs');
    }

    /**
     * View Assigned Jobs
     * Show jobs that have been assigned to the cleaner
     */
    public function assigned_jobs()
    {
        $user_id = $this->auth_user_id;
        
        if (!$user_id) {
            $this->session->set_flashdata('text', 'You must be logged in to view assigned jobs.');
            $this->session->set_flashdata('type', 'error');
            redirect('app/login');
        }
        
        $data = [
            'title' => 'Assigned Jobs',
            'page_icon' => 'fas fa-clipboard-check',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Assigned Jobs', 'url' => '', 'active' => true]
            ],
            'assigned_jobs' => $this->M_jobs->get_assigned_jobs_for_cleaner($user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the assigned jobs content as a string
        $data['body'] = $this->load->view('cleaner/assigned_jobs', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Start a job with OTP validation
     */
    public function start_job()
    {
        if ($this->input->method() !== 'post') {
            show_404();
        }

        $user_id = $this->auth_user_id;
        
        if (!$user_id) {
            $this->session->set_flashdata('text', 'You must be logged in to start a job.');
            $this->session->set_flashdata('type', 'error');
            redirect('app/login');
        }

        // Validate input
        $this->form_validation->set_rules('job_id', 'Job ID', 'required|integer');
        $this->form_validation->set_rules('otp_code', 'Service Code', 'required|exact_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $errors = $this->form_validation->error_array();
            $this->session->set_flashdata('text', '⚠️ Invalid Input: The service code must be exactly 6 digits. Please try again.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/start_job_page/' . $this->input->post('job_id'));
        }

        $job_id = $this->input->post('job_id');
        $otp_code = $this->input->post('otp_code');

        // Log the attempt for debugging
        log_message('debug', "Attempting to start job $job_id for user $user_id with OTP: $otp_code");

        // Check if required database fields exist
        if (!$this->db->field_exists('assigned_cleaner_id', 'jobs')) {
            log_message('error', 'assigned_cleaner_id field does not exist in jobs table');
            $this->session->set_flashdata('text', 'Database configuration error. Please contact support.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/assigned_jobs');
        }

        // Attempt to start the job
        $result = $this->M_jobs->start_job_with_otp($job_id, $user_id, $otp_code);
        
        if ($result) {
            log_message('debug', "Job $job_id started successfully for user $user_id");
            $this->session->set_flashdata('text', 'Job started successfully! You can now begin the cleaning service.');
            $this->session->set_flashdata('type', 'success');
            redirect('cleaner/jobs-in-progress'); // Redirect to jobs in progress page
        } else {
            log_message('debug', "Failed to start job $job_id for user $user_id - Invalid OTP: $otp_code");
            $this->session->set_flashdata('text', '❌ Incorrect Service Code! Please verify the 6-digit code provided by the host and try again.');
            $this->session->set_flashdata('type', 'error');
            // Also pass error via URL parameter as backup
            redirect('cleaner/start_job_page/' . $job_id . '?error=invalid_otp'); // Redirect back to start job page
        }
    }

    /**
     * Show start job page with OTP form
     */
    public function start_job_page($job_id)
    {
        $user_id = $this->auth_user_id;
        
        if (!$user_id) {
            $this->session->set_flashdata('text', 'You must be logged in to start a job.');
            $this->session->set_flashdata('type', 'error');
            redirect('app/login');
        }

        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->assigned_cleaner_id != $user_id || $job->status !== 'assigned') {
            $this->session->set_flashdata('text', 'Job not found or not assigned to you.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/assigned_jobs');
        }

        // Check if job can be started (30 minutes before scheduled time)
        $can_start = false;
        $start_message = '';
        
        if (!empty($job->scheduled_date) && !empty($job->scheduled_time)) {
            $scheduled_datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
            $job_start_time = strtotime($scheduled_datetime);
            $thirty_min_before = $job_start_time - (30 * 60); // 30 minutes before
            $current_time = time();
            
            if ($current_time >= $thirty_min_before) {
                $can_start = true;
            } else {
                $time_diff = $thirty_min_before - $current_time;
                $hours = floor($time_diff / 3600);
                $minutes = floor(($time_diff % 3600) / 60);
                
                if ($hours > 0) {
                    $start_message = "Can start in {$hours}h {$minutes}m";
                } else {
                    $start_message = "Can start in {$minutes} minutes";
                }
            }
        }

        if (!$can_start) {
            $this->session->set_flashdata('text', "You cannot start this job yet. {$start_message}.");
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/assigned_jobs');
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
        
        // Get accepted offer details
        $accepted_offer = null;
        $offers = $this->M_offers->get_offers_by_job($job_id);
        foreach ($offers as $offer) {
            if ($offer->status === 'accepted' && $offer->cleaner_id == $user_id) {
                $accepted_offer = $offer;
                break;
            }
        }
        
        // Load host profile and reviews
        $this->load->model('M_user_profiles');
        $this->load->model('M_reviews');
        
        $host_profile = $this->M_user_profiles->get_profile_with_user_data($job->host_id);
        $host_reviews = $this->M_reviews->get_public_reviews_for_user($job->host_id, 10);
        $host_review_stats = $this->M_reviews->calculate_user_average_ratings($job->host_id);
        $host_rating_distribution = $this->M_reviews->get_rating_distribution($job->host_id);

        $data = [
            'title' => 'Start Job - ' . $job->title,
            'page_icon' => 'fas fa-play-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Assigned Jobs', 'url' => 'cleaner/assigned_jobs'],
                ['title' => 'Start Job', 'url' => '', 'active' => true]
            ],
            'job' => $job,
            'user_info' => $this->M_users->get_user_by_id($user_id),
            'pricing_params' => $pricing_params,
            'accepted_offer' => $accepted_offer,
            'host_profile' => $host_profile,
            'host_reviews' => $host_reviews,
            'host_review_stats' => $host_review_stats,
            'host_rating_distribution' => $host_rating_distribution
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the start job page content as a string
        $data['body'] = $this->load->view('cleaner/start_job_page', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Display change password form for cleaner
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
            array('title' => 'Dashboard', 'url' => 'cleaner'),
            array('title' => 'Change Password', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/cleaner_sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("admin/change_password", array('user_info' => $user_info), TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Process password change for cleaner
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
     * Display completed jobs for cleaner
     */
    public function completed()
    {
        $user_id = $this->auth_user_id;
        
        if (!$user_id) {
            show_error('User not authenticated. Please login again.', 401);
        }
        
        // Get completed jobs for this cleaner (only completed status)
        $completed_jobs = $this->M_jobs->get_completed_jobs_for_cleaner($user_id);
        
        // Load reviews model and pricing settings
        $this->load->model('M_reviews');
        
        // Fetch pricing parameters
        $pricing_params = [
            'base_charge' => 25.00,
            'tax_percent' => 10.00,
            'app_percent' => 15.00
        ];
        
        $pricing_settings = $this->db->get('pricing_settings')->row();
        if ($pricing_settings) {
            $pricing_params = [
                'base_charge' => $pricing_settings->base_charge,
                'tax_percent' => $pricing_settings->tax_percent,
                'app_percent' => $pricing_settings->app_percent
            ];
        }
        
        // Get dispute and price adjustment information for each job
        $jobs_with_details = [];
        foreach ($completed_jobs as $job) {
            $job_details = $job;
            $job_details->dispute_info = null;
            $job_details->price_adjustments = [];
            $job_details->accepted_offer = null;
            $job_details->cleaner_payout = null;
            
            // Get cleaner's review of the host for this job
            $job_details->my_review = $this->M_reviews->get_review_by_job_and_reviewer($job->id, $user_id);
            
            // Get accepted offer details (for counter offers)
            $accepted_offer = $this->db
                ->where('job_id', $job->id)
                ->where('status', 'accepted')
                ->get('offers')
                ->row();
            
            if ($accepted_offer) {
                $job_details->accepted_offer = $accepted_offer;
                
                // Calculate actual cleaner payout
                if (!empty($accepted_offer->cleaner_payout)) {
                    $job_details->cleaner_payout = $accepted_offer->cleaner_payout;
                } else {
                    // Calculate from offer amount
                    $offer_amount = $accepted_offer->amount;
                    $tax_amount = ($offer_amount * $pricing_params['tax_percent']) / 100;
                    $app_amount = ($offer_amount * $pricing_params['app_percent']) / 100;
                    $job_details->cleaner_payout = $offer_amount - $pricing_params['base_charge'] - $tax_amount - $app_amount;
                }
            } else {
                // Calculate from suggested price
                $tax_amount = ($job->suggested_price * $pricing_params['tax_percent']) / 100;
                $app_amount = ($job->suggested_price * $pricing_params['app_percent']) / 100;
                $job_details->cleaner_payout = $job->suggested_price - $pricing_params['base_charge'] - $tax_amount - $app_amount;
            }
            
            // Get dispute information if applicable
            if ($job->status === 'disputed' || ($job->status === 'closed' && $job->dispute_resolution)) {
                $job_details->dispute_info = [
                    'disputed_at' => $job->disputed_at,
                    'dispute_reason' => $job->dispute_reason,
                    'dispute_resolution' => $job->dispute_resolution,
                    'dispute_resolution_notes' => $job->dispute_resolution_notes,
                    'dispute_resolved_at' => $job->dispute_resolved_at,
                    'payment_amount' => $job->payment_amount
                ];
            }
            
            // Get price adjustment requests if applicable
            if ($job->status === 'price_adjustment_requested') {
                $this->load->model('M_counter_offers');
                $job_details->price_adjustments = $this->M_counter_offers->get_counter_offers_for_job($job->id);
            }
            
            $jobs_with_details[] = $job_details;
        }
        
        // Get summary statistics
        $total_jobs = $this->M_jobs->get_completed_jobs_count_for_cleaner($user_id);
        $disputed_count = $this->M_jobs->get_disputed_jobs_count_for_cleaner($user_id);
        
        // Calculate potential earnings from cleaner payouts
        $potential_earnings = 0;
        foreach ($jobs_with_details as $job) {
            if ($job->cleaner_payout) {
                $potential_earnings += $job->cleaner_payout;
            }
        }
        
        // Prepare view data
        $data = [
            'title' => 'Completed Jobs',
            'page_icon' => 'fas fa-check-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Completed Jobs', 'url' => '', 'active' => true]
            ],
            'completed_jobs' => $jobs_with_details,
            'user_id' => $user_id,
            'total_jobs' => $total_jobs,
            'potential_earnings' => $potential_earnings,
            'disputed_count' => $disputed_count
        ];
        
        // Load sidebar
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', NULL, TRUE);
        
        // Load the main content
        $data['body'] = $this->load->view('cleaner/completed_jobs', $data, TRUE);
        
        // Load the layout
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Recalled Jobs - View jobs that were recalled by the host
     */
    public function recalled_jobs()
    {
        $user_id = $this->auth_user_id;
        
        if (!$user_id) {
            show_error('User not authenticated. Please login again.', 401);
        }
        
        // Get filter parameters
        $filters = [
            'reason' => $this->input->get('reason'),
            'severity' => $this->input->get('severity'),
            'search' => $this->input->get('search'),
            'sort' => $this->input->get('sort') ?? 'recalled_at',
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to')
        ];
        
        // Get recalled jobs for this cleaner
        $recalled_jobs = $this->M_jobs->get_cleaner_recalled_jobs($user_id, $filters);
        
        // Calculate summary statistics
        $total_recalled = count($recalled_jobs);
        $settled_count = 0;
        $pending_count = 0;
        
        foreach ($recalled_jobs as $job) {
            if ($job->status === 'recall_settled') {
                $settled_count++;
            } else {
                $pending_count++;
            }
        }
        
        // Prepare view data
        $data = [
            'title' => 'Recalled Jobs',
            'page_icon' => 'fas fa-exclamation-triangle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Recalled Jobs', 'url' => '', 'active' => true]
            ],
            'recalled_jobs' => $recalled_jobs,
            'total_recalled' => $total_recalled,
            'settled_count' => $settled_count,
            'pending_count' => $pending_count,
            'filters' => $filters
        ];
        
        // Load sidebar
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', NULL, TRUE);
        
        // Load the main content
        $data['body'] = $this->load->view('cleaner/recalled_jobs', $data, TRUE);
        
        // Load the layout
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Cleaner Profile Management
     * View cleaner's own profile
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
        
        // Load reviews model to get cleaner's reviews
        $this->load->model('M_reviews');
        $reviews = $this->M_reviews->get_public_reviews_for_user($user_id, 10);
        $review_stats = $this->M_reviews->calculate_user_average_ratings($user_id);
        $rating_distribution = $this->M_reviews->get_rating_distribution($user_id);
        
        // Get job statistics
        $job_stats = [];
        if (isset($this->M_jobs)) {
            $job_stats = [
                'completed_jobs' => $this->M_jobs->get_completed_jobs_count_for_cleaner($user_id),
                'active_jobs' => $this->M_jobs->get_active_jobs_for_cleaner($user_id),
                'total_earnings' => $this->M_jobs->get_total_earnings_for_cleaner($user_id),
                'average_rating' => (isset($review_stats['total_reviews']) && $review_stats['total_reviews'] > 0) ? $review_stats['overall_average'] : 0
            ];
        }
        
        $data = [
            'title' => 'My Profile',
            'page_icon' => 'fas fa-user-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
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
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the profile view content as a string
        $data['body'] = $this->load->view('cleaner/profile/my_profile', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Edit Cleaner's Own Profile
     * Display profile edit form
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
        
        // Get service areas and specialties for cleaners
        $service_areas = $this->M_user_profiles->get_service_areas();
        $specialties = $this->M_user_profiles->get_specialties();
        
        $data = [
            'title' => 'Edit My Profile',
            'page_icon' => 'fas fa-user-edit',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'My Profile', 'url' => 'cleaner/my-profile'],
                ['title' => 'Edit', 'url' => '', 'active' => true]
            ],
            'profile' => $profile,
            'completion' => $completion,
            'service_areas' => $service_areas,
            'specialties' => $specialties,
            'user_info' => $this->M_users->get_user_by_id($user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the profile edit content as a string
        $data['body'] = $this->load->view('cleaner/profile/edit_profile', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Update Cleaner's Own Profile (AJAX)
     * Process profile update form submission
     */
    public function update_my_profile()
    {
        // Set JSON header first
        header('Content-Type: application/json');
        
        try {
            // Log the request for debugging
            log_message('info', 'Cleaner profile update request received');
            
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
            
            // Get cleaner-specific fields
            $service_areas = $this->input->post('service_areas');
            $specialties = $this->input->post('specialties');
            $services_str = $this->input->post('services_str') ? 1 : 0;
            
            log_message('info', 'Form data received - Bio length: ' . strlen($bio) . ', Phone: ' . $phone);
            log_message('info', 'STR services checkbox value: ' . ($this->input->post('services_str') ?? 'NULL') . ' | Converted to: ' . $services_str);
            
            // Prepare update data for user_profiles table
            $update_data = [
                'bio' => $bio,
                'phone' => $phone,
                'is_public' => $is_public,
                'services_str' => $services_str,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Add service areas and specialties for cleaners
            if ($service_areas) {
                $update_data['service_areas'] = json_encode($service_areas);
            }
            if ($specialties) {
                $update_data['specialties'] = json_encode($specialties);
            }
            
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
            log_message('error', 'Cleaner profile update error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Public Profile View
     * Display public profile for cleaners (visible to hosts)
     * Shows: name, reviews, service areas, specialties
     * Hides: contact information, address, email, phone
     */
    public function public_profile($cleaner_id)
    {
        // Load profile model
        $this->load->model('M_user_profiles');
        $this->load->model('M_reviews');
        
        // Get cleaner profile with user data
        $profile = $this->M_user_profiles->get_profile_with_user_data($cleaner_id);
        
        if (!$profile || $profile->auth_level != 3) {
            show_404();
        }
        
        // Get review statistics
        $review_stats = $this->M_reviews->calculate_user_average_ratings($cleaner_id);
        
        // Get cleaner statistics
        $job_stats = [
            'completed_jobs' => $this->M_jobs->get_completed_jobs_count_for_cleaner($cleaner_id),
            'active_jobs' => $this->M_jobs->get_active_jobs_for_cleaner($cleaner_id),
            'average_rating' => $review_stats['overall_average'] ?? 0,
            'total_reviews' => $review_stats['total_reviews'] ?? 0
        ];
        
        // Get public reviews for this cleaner (limit to 10 most recent)
        $reviews = $this->M_reviews->get_public_reviews_for_user($cleaner_id, 10, 0);
        
        // Get rating distribution
        $rating_distribution = $this->M_reviews->get_rating_distribution($cleaner_id);
        
        $data = [
            'title' => $profile->username . ' - Cleaner Profile',
            'page_icon' => 'fas fa-user-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'host'],
                ['title' => 'Cleaner Profile', 'url' => '', 'active' => true]
            ],
            'profile' => $profile,
            'job_stats' => $job_stats,
            'reviews' => $reviews,
            'review_stats' => $review_stats,
            'rating_distribution' => $rating_distribution
        ];
        
        // Check if this is being viewed by host or admin
        $viewer_auth_level = $this->session->userdata('auth_level');
        if ($viewer_auth_level == 6) {
            // Host viewing
            $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        } elseif ($viewer_auth_level == 9) {
            // Admin viewing
            $data['sidebar'] = $this->load->view('admin/template/admin_sidebar', array(), TRUE);
        } else {
            // Fallback
            $data['sidebar'] = $this->load->view('admin/template/host_sidebar', array(), TRUE);
        }
        
        // Load the public profile view
        $data['body'] = $this->load->view('cleaner/public_profile', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }

    /**
     * Profile Setup Required
     * Show message when cleaner needs to complete profile before accessing jobs
     */
    public function profile_setup_required()
    {
        $user_id = $this->auth_user_id;
        
        // Get profile completion details
        $this->load->model('M_user_profiles');
        $completion = $this->M_user_profiles->calculate_profile_completion($user_id);
        
        $data = [
            'title' => 'Profile Setup Required',
            'page_icon' => 'fas fa-user-cog',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Profile Setup Required', 'url' => '', 'active' => true]
            ],
            'completion' => $completion,
            'user_info' => $this->M_users->get_user_by_id($user_id)
        ];
        
        // Load the cleaner sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the profile setup required content as a string
        $data['body'] = $this->load->view('cleaner/profile_setup_required', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
}
