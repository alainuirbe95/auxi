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
        
        // Check if user is logged in and is a cleaner
        if (!$this->require_min_level(3)) { // Cleaner level = 3
            redirect('app/login');
        }
        
        // Load required helpers and libraries
        $this->load->helper('form');
        $this->load->library('form_validation');
        
        // Load required models
        $this->load->model('M_users');
        $this->load->model('M_jobs');
        $this->load->model('M_offers');
        $this->load->model('M_ignored_jobs');
        $this->load->model('M_favorites');
        
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
        
        // Get dashboard data
        $data = [
            'title' => 'Cleaner Dashboard',
            'page_icon' => 'fas fa-broom',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id)
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
            'per_page' => $per_page
        ];
        
        // Load jobs if tables exist
        if ($this->db->table_exists('jobs')) {
            // Get jobs with favorites status, excluding ignored ones
            if ($this->db->table_exists('job_favorites')) {
                $data['jobs'] = $this->M_favorites->get_jobs_with_favorites($user_id, $filters, $per_page, $offset);
                $data['total_jobs'] = $this->M_jobs->count_active_jobs($filters);
            } else {
                // Fallback to regular job loading
                if ($this->db->table_exists('ignored_jobs')) {
                    $data['jobs'] = $this->M_ignored_jobs->get_available_jobs_excluding_ignored($user_id, $filters, $per_page, $offset);
                } else {
                    $data['jobs'] = $this->M_jobs->get_active_jobs($filters, $per_page, $offset);
                }
                $data['total_jobs'] = $this->M_jobs->count_active_jobs($filters);
            }
            $data['total_pages'] = ceil($data['total_jobs'] / $per_page);
            
            // Check which jobs the cleaner has already applied to and add favorite status
            if ($this->db->table_exists('offers')) {
                foreach ($data['jobs'] as $job) {
                    $job->has_applied = $this->M_offers->cleaner_has_offered($user_id, $job->id);
                    $job->has_been_declined = $this->M_offers->cleaner_has_been_declined($user_id, $job->id);
                    $job->is_favorited = $this->db->table_exists('job_favorites') ? $this->M_favorites->is_job_favorited($user_id, $job->id) : false;
                }
            } else {
                foreach ($data['jobs'] as $job) {
                    $job->has_applied = false;
                    $job->has_been_declined = false;
                    $job->is_favorited = $this->db->table_exists('job_favorites') ? $this->M_favorites->is_job_favorited($user_id, $job->id) : false;
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
            'user_info' => $this->M_users->get_user_by_id($user_id)
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
        
        // Prepare offer data based on offer type
        $amount = $this->input->post('amount');
        
        // For accept offers, use the suggested price if amount is not provided
        if ($offer_type === 'accept' && empty($amount)) {
            $amount = number_format($job->suggested_price, 2, '.', '');
        } else if (!empty($amount)) {
            // Format amount to 2 decimal places if provided
            $amount = number_format((float)$amount, 2, '.', '');
        }
        
        $offer_data = [
            'job_id' => $job_id,
            'cleaner_id' => $user_id,
            'offer_type' => $offer_type,
            'amount' => $amount,
            'original_price' => $job->suggested_price,
            'status' => 'pending'
        ];
        
        // Set expiration and counter price for counter offers
        if ($offer_type === 'counter') {
            $offer_data['expires_at'] = date('Y-m-d H:i:s', strtotime('+6 hours'));
            $offer_data['counter_price'] = $amount;
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
        
        $data = [
            'title' => 'My Earnings',
            'page_icon' => 'fas fa-dollar-sign',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Earnings', 'url' => '', 'active' => true]
            ],
            'user_info' => $this->M_users->get_user_by_id($user_id)
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
            $this->session->set_flashdata('text', 'Please correct the errors: ' . implode(', ', $errors));
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/assigned_jobs');
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
            log_message('debug', "Failed to start job $job_id for user $user_id");
            $this->session->set_flashdata('text', 'Invalid service code or job not found. Please check the code provided by the host.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/assigned_jobs');
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

        $data = [
            'title' => 'Start Job - ' . $job->title,
            'page_icon' => 'fas fa-play-circle',
            'breadcrumbs' => [
                ['title' => 'Dashboard', 'url' => 'cleaner'],
                ['title' => 'Assigned Jobs', 'url' => 'cleaner/assigned_jobs'],
                ['title' => 'Start Job', 'url' => '', 'active' => true]
            ],
            'job' => $job,
            'user_info' => $this->M_users->get_user_by_id($user_id)
        ];
        
        // Load the sidebar content as a string
        $data['sidebar'] = $this->load->view('admin/template/cleaner_sidebar', array(), TRUE);
        
        // Load the start job page content as a string
        $data['body'] = $this->load->view('cleaner/start_job_page', $data, TRUE);
        
        // Load the layout with the content
        $this->load->view('admin/template/layout_with_sidebar', $data);
    }
}
