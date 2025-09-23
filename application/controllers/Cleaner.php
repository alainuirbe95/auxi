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
            $data['jobs'] = $this->M_jobs->get_active_jobs($filters, $per_page, $offset);
            $data['total_jobs'] = $this->M_jobs->count_active_jobs($filters);
            $data['total_pages'] = ceil($data['total_jobs'] / $per_page);
            
            // Check which jobs the cleaner has already applied to
            if ($this->db->table_exists('offers')) {
                foreach ($data['jobs'] as $job) {
                    $job->has_applied = $this->M_offers->cleaner_has_offered($user_id, $job->id);
                }
            } else {
                foreach ($data['jobs'] as $job) {
                    $job->has_applied = false;
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
        
        if (!$job || $job->status !== 'active') {
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
        
        // Check if required tables exist
        if (!$this->db->table_exists('jobs') || !$this->db->table_exists('offers')) {
            $this->session->set_flashdata('text', 'Marketplace functionality not available yet.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/job/' . $job_id);
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job || $job->status !== 'active') {
            show_404();
        }
        
        // Check if cleaner has already applied
        if ($this->M_offers->cleaner_has_offered($user_id, $job_id)) {
            $this->session->set_flashdata('text', 'You have already applied to this job.');
            $this->session->set_flashdata('type', 'warning');
            redirect('cleaner/job/' . $job_id);
        }
        
        // Set validation rules
        $this->form_validation->set_rules('offer_type', 'Offer Type', 'required|in_list[fixed,hourly]');
        $this->form_validation->set_rules('amount', 'Amount', 'required|decimal|greater_than[0]');
        
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('text', 'Please correct the errors below.');
            $this->session->set_flashdata('type', 'error');
            redirect('cleaner/job/' . $job_id);
        }
        
        // Prepare offer data
        $offer_data = [
            'job_id' => $job_id,
            'cleaner_id' => $user_id,
            'offer_type' => $this->input->post('offer_type'),
            'amount' => $this->input->post('amount'),
            'status' => 'pending',
            'expires_at' => date('Y-m-d H:i:s', strtotime('+7 days')) // Offers expire in 7 days
        ];
        
        // Create the offer
        $offer_id = $this->M_offers->create_offer($offer_data);
        
        if ($offer_id) {
            $this->session->set_flashdata('text', 'Your offer has been submitted successfully!');
            $this->session->set_flashdata('type', 'success');
        } else {
            $this->session->set_flashdata('text', 'Failed to submit offer. Please try again.');
            $this->session->set_flashdata('type', 'error');
        }
        
        redirect('cleaner/job/' . $job_id);
    }
}
