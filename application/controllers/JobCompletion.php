<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Job Completion Controller
 * 
 * Handles job completion workflow for cleaners including inconsistency reporting
 */
class JobCompletion extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Initialize authentication system first
        $this->init_session_auto(3); // Initialize for cleaner level
        
        // Now check authentication
        if (!$this->is_logged_in() || $this->auth_level != 3) {
            redirect('app/login');
        }
        
        $this->load->model('M_jobs');
        $this->load->model('M_notifications');
        $this->load->library('form_validation');
        $this->load->helper('url');
    }

    /**
     * Show jobs ready for completion
     */
    public function index()
    {
        $cleaner_id = $this->auth_user_id;
        $jobs = $this->M_jobs->get_jobs_ready_for_completion($cleaner_id);
        
        $data = [
            'jobs' => $jobs,
            'cleaner_id' => $cleaner_id
        ];
        
        $view["title"] = 'Jobs in Progress';
        $view["page_icon"] = 'tasks';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'cleaner'),
            array('title' => 'Jobs in Progress', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/cleaner_sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("cleaner/complete_jobs", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Show job completion form with inconsistency reporting
     */
    public function complete_job($job_id = null)
    {
        if (!$job_id) {
            show_404();
        }
        
        $cleaner_id = $this->auth_user_id;
        
        // Validate job ownership and status
        if (!$this->M_jobs->can_complete_job($job_id, $cleaner_id)) {
            $this->session->set_flashdata('error', 'You cannot complete this job.');
            redirect('cleaner/jobs-in-progress');
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job) {
            show_404();
        }
        
        // Get any existing inconsistencies for this job
        $inconsistencies = $this->M_jobs->get_job_inconsistencies($job_id);
        
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
        $this->load->model('M_offers');
        $accepted_offer = null;
        $offers = $this->M_offers->get_offers_by_job($job_id);
        foreach ($offers as $offer) {
            if ($offer->status === 'accepted' && $offer->cleaner_id == $cleaner_id) {
                $accepted_offer = $offer;
                break;
            }
        }
        
        $data = [
            'job' => $job,
            'inconsistencies' => $inconsistencies,
            'cleaner_id' => $cleaner_id,
            'pricing_params' => $pricing_params,
            'accepted_offer' => $accepted_offer
        ];
        
        $view["title"] = 'Complete Job - ' . $job->title;
        $view["page_icon"] = 'check-circle';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'cleaner'),
            array('title' => 'Jobs in Progress', 'url' => 'cleaner/jobs-in-progress'),
            array('title' => 'Complete Job', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/cleaner_sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("cleaner/complete_job_form", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Process job completion
     */
    public function process_completion()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $cleaner_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        
        // Validate job ownership and status
        if (!$this->M_jobs->can_complete_job($job_id, $cleaner_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'You cannot complete this job.'
            ]);
            return;
        }
        
        // Set validation rules for job completion
        $this->form_validation->set_rules('job_id', 'Job ID', 'required|integer');
        $this->form_validation->set_rules('completion_notes', 'Completion Notes', 'max_length[1000]');
        $this->form_validation->set_rules('final_price', 'Final Price', 'decimal');
        
        // Set validation rules for review (MANDATORY - ratings only, comments optional)
        $this->form_validation->set_rules('overall_rating', 'Overall Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('public_comment', 'Public Comment', 'max_length[500]'); // Optional, but max 500 chars
        $this->form_validation->set_rules('professionalism_rating', 'Professionalism Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('quality_rating', 'Quality Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('communication_rating', 'Communication Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('punctuality_rating', 'Punctuality Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('professionalism_comment', 'Professionalism Comment', 'max_length[500]');
        $this->form_validation->set_rules('quality_comment', 'Quality Comment', 'max_length[500]');
        $this->form_validation->set_rules('communication_comment', 'Communication Comment', 'max_length[500]');
        $this->form_validation->set_rules('punctuality_comment', 'Punctuality Comment', 'max_length[500]');
        $this->form_validation->set_rules('private_notes', 'Private Notes', 'max_length[500]');
        
        if ($this->form_validation->run()) {
            // Load reviews model
            $this->load->model('M_reviews');
            
            // Get job details to find host_id
            $job = $this->M_jobs->get_job_by_id($job_id);
            if (!$job) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Job not found.'
                ]);
                return;
            }
            
            // Start database transaction
            $this->db->trans_start();
            
            // 1. Create the review
            $review_data = [
                'job_id' => $job_id,
                'reviewer_id' => $cleaner_id,
                'reviewee_id' => $job->host_id,
                'review_type' => 'cleaner_to_host',
                
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
            
            // 2. Complete the job
            $final_price = $this->input->post('final_price');
            if (!empty($final_price)) {
                $final_price = number_format((float)$final_price, 2, '.', '');
            }
            
            $completion_data = [
                'completion_notes' => $this->input->post('completion_notes'),
                'final_price' => $final_price,
                'price_reason' => $this->input->post('price_reason')
            ];
            
            $result = $this->M_jobs->complete_job($job_id, $cleaner_id, $completion_data);
            
            if ($result === true || $result === 'counter_offer_created') {
                // 3. Mark cleaner as reviewed in jobs table
                $this->db->where('id', $job_id);
                $this->db->update('jobs', ['cleaner_reviewed' => 1]);
                
                // 4. Send notification to host about review
                $this->load->model('M_notifications');
                $this->M_notifications->create_notification(
                    $job->host_id,
                    'New Review from Cleaner',
                    'A cleaner has completed the job "' . $job->title . '" and left you a review.',
                    base_url('host/completed-jobs')
                );
                
                // Complete transaction
                $this->db->trans_complete();
                
                if ($this->db->trans_status() === FALSE) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to complete job. Please try again.'
                    ]);
                } else {
                    $message = $result === 'counter_offer_created' 
                        ? 'Job completed and review submitted! Price adjustment request sent to host.'
                        : 'Job completed and review submitted successfully!';
                    
                    echo json_encode([
                        'success' => true,
                        'message' => $message,
                        'redirect' => base_url('cleaner')
                    ]);
                }
            } else {
                $this->db->trans_rollback();
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to complete job. Please try again.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed: ' . strip_tags(validation_errors())
            ]);
        }
    }

    /**
     * Report job inconsistency
     */
    public function report_inconsistency()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $cleaner_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        
        // Validate job ownership and status
        if (!$this->M_jobs->can_complete_job($job_id, $cleaner_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'You cannot report inconsistencies for this job.'
            ]);
            return;
        }
        
        // Set validation rules
        $this->form_validation->set_rules('job_id', 'Job ID', 'required|integer');
        $this->form_validation->set_rules('inconsistency_type', 'Inconsistency Type', 'required|in_list[property_damage,missing_items,unexpected_conditions,access_issues,safety_concerns,other]');
        $this->form_validation->set_rules('description', 'Description', 'required|max_length[1000]');
        $this->form_validation->set_rules('severity', 'Severity', 'required|in_list[low,medium,high,critical]');
        
        if ($this->form_validation->run()) {
            $inconsistency_data = [
                'inconsistency_type' => $this->input->post('inconsistency_type'),
                'description' => $this->input->post('description'),
                'severity' => $this->input->post('severity'),
                'photos' => $this->input->post('photos') // Array of photo URLs
            ];
            
            // Report the inconsistency
            $result = $this->M_jobs->report_job_inconsistency($job_id, $cleaner_id, $inconsistency_data);
            
            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Inconsistency reported successfully!',
                    'inconsistency_id' => $result
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to report inconsistency. Please try again.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed: ' . validation_errors()
            ]);
        }
    }

    /**
     * Show inconsistency reporting modal
     */
    public function show_inconsistency_form($job_id = null)
    {
        if (!$job_id) {
            show_404();
        }
        
        $cleaner_id = $this->auth_user_id;
        
        // Validate job ownership and status
        if (!$this->M_jobs->can_complete_job($job_id, $cleaner_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'You cannot report inconsistencies for this job.'
            ]);
            return;
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job) {
            show_404();
        }
        
        $data = [
            'job' => $job,
            'cleaner_id' => $cleaner_id
        ];
        
        $form_html = $this->load->view("cleaner/inconsistency_form", $data, TRUE);
        
        echo json_encode([
            'success' => true,
            'form_html' => $form_html
        ]);
    }

    /**
     * Get job inconsistencies (AJAX)
     */
    public function get_inconsistencies($job_id = null)
    {
        if (!$job_id || !$this->input->is_ajax_request()) {
            show_404();
        }
        
        $cleaner_id = $this->auth_user_id;
        
        // Validate job ownership
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job || $job->assigned_cleaner_id != $cleaner_id) {
            echo json_encode([
                'success' => false,
                'message' => 'You cannot view inconsistencies for this job.'
            ]);
            return;
        }
        
        $inconsistencies = $this->M_jobs->get_job_inconsistencies($job_id);
        
        echo json_encode([
            'success' => true,
            'inconsistencies' => $inconsistencies
        ]);
    }

    /**
     * Upload inconsistency photos
     */
    public function upload_inconsistency_photos()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $cleaner_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        
        // Validate job ownership and status
        if (!$this->M_jobs->can_complete_job($job_id, $cleaner_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'You cannot upload photos for this job.'
            ]);
            return;
        }
        
        // Configure upload
        $config['upload_path'] = './uploads/job_inconsistencies/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 2048; // 2MB
        $config['encrypt_name'] = TRUE;
        
        // Create directory if it doesn't exist
        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }
        
        $this->load->library('upload', $config);
        
        $uploaded_files = [];
        $errors = [];
        
        if (!empty($_FILES['photos']['name'][0])) {
            $files = $_FILES;
            $cpt = count($_FILES['photos']['name']);
            
            for ($i = 0; $i < $cpt; $i++) {
                $_FILES['photos']['name'] = $files['photos']['name'][$i];
                $_FILES['photos']['type'] = $files['photos']['type'][$i];
                $_FILES['photos']['tmp_name'] = $files['photos']['tmp_name'][$i];
                $_FILES['photos']['error'] = $files['photos']['error'][$i];
                $_FILES['photos']['size'] = $files['photos']['size'][$i];
                
                if ($this->upload->do_upload('photos')) {
                    $upload_data = $this->upload->data();
                    $uploaded_files[] = [
                        'filename' => $upload_data['file_name'],
                        'url' => base_url('uploads/job_inconsistencies/' . $upload_data['file_name']),
                        'size' => $upload_data['file_size']
                    ];
                } else {
                    $errors[] = $this->upload->display_errors();
                }
            }
        }
        
        if (!empty($errors)) {
            echo json_encode([
                'success' => false,
                'message' => 'Upload failed: ' . implode(', ', $errors)
            ]);
        } else {
            echo json_encode([
                'success' => true,
                'files' => $uploaded_files
            ]);
        }
    }
}
