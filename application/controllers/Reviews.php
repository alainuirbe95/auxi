<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reviews Controller
 * 
 * Handles all review-related operations including creation, display, and management
 */
class Reviews extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('M_reviews');
        $this->load->model('M_review_responses');
        $this->load->model('M_jobs');
        $this->load->model('M_user_profiles');
        $this->load->library('form_validation');
        $this->load->helper('form');
        
        // Require authentication for all review operations
        $this->init_session_auto(1);
    }

    /**
     * Show review form for a specific job
     */
    public function create($job_id = null)
    {
        if (!$job_id) {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        
        // Check if user can review this job
        if (!$this->M_reviews->can_review_job($job_id, $user_id)) {
            $this->session->set_flashdata('error', 'You cannot review this job. The review window may have expired or you may have already reviewed this job.');
            redirect('dashboard');
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job) {
            show_404();
        }
        
        // Determine review type and reviewee
        $review_type = ($user_id == $job->host_id) ? 'host_to_cleaner' : 'cleaner_to_host';
        $reviewee_id = ($review_type == 'host_to_cleaner') ? $job->assigned_cleaner_id : $job->host_id;
        
        // Get reviewee information
        $this->load->model('M_users');
        $reviewee = $this->M_users->get_user_by_id($reviewee_id);
        
        // Get review categories
        $categories = $this->M_reviews->get_review_categories($review_type);
        
        $data = array(
            'job' => $job,
            'reviewee' => $reviewee,
            'review_type' => $review_type,
            'categories' => $categories,
            'page_title' => 'Write Review'
        );
        
        $this->load->view('reviews/create', $data);
    }

    /**
     * Process review submission
     */
    public function submit()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        
        // Validate job ID
        if (!$job_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid job ID.'
            ]);
            return;
        }
        
        // Check if user can review this job
        if (!$this->M_reviews->can_review_job($job_id, $user_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'You cannot review this job. The review window may have expired or you may have already reviewed this job.'
            ]);
            return;
        }
        
        // Get job details
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job) {
            echo json_encode([
                'success' => false,
                'message' => 'Job not found.'
            ]);
            return;
        }
        
        // Determine review type and reviewee
        $review_type = ($user_id == $job->host_id) ? 'host_to_cleaner' : 'cleaner_to_host';
        $reviewee_id = ($review_type == 'host_to_cleaner') ? $job->assigned_cleaner_id : $job->host_id;
        
        // Set validation rules
        $this->form_validation->set_rules('rating', 'Rating', 'required|integer|greater_than[0]|less_than[6]');
        $this->form_validation->set_rules('title', 'Title', 'max_length[255]');
        $this->form_validation->set_rules('comment', 'Comment', 'max_length[1000]');
        $this->form_validation->set_rules('categories', 'Categories', 'required');
        
        if ($this->form_validation->run()) {
            // Prepare review data
            $review_data = array(
                'job_id' => $job_id,
                'reviewer_id' => $user_id,
                'reviewee_id' => $reviewee_id,
                'rating' => $this->input->post('rating'),
                'title' => $this->input->post('title'),
                'comment' => $this->input->post('comment'),
                'review_type' => $review_type,
                'review_categories' => $this->input->post('categories'),
                'review_window_expires_at' => $job->review_window_expires_at
            );
            
            // Create the review
            $review_id = $this->M_reviews->create_review($review_data);
            
            if ($review_id) {
                // Update job review status
                $this->update_job_review_status($job_id, $review_type);
                
                // Send notification to reviewee
                $this->send_review_notification($reviewee_id, $job, $review_type);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Review submitted successfully!',
                    'redirect' => base_url('reviews/view/' . $review_id)
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to submit review. Please try again.'
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
     * View a specific review
     */
    public function view($review_id = null)
    {
        if (!$review_id) {
            show_404();
        }
        
        $review = $this->M_reviews->get_review_by_id($review_id);
        if (!$review) {
            show_404();
        }
        
        // Get review responses
        $responses = $this->M_review_responses->get_responses_for_review($review_id);
        
        $data = array(
            'review' => $review,
            'responses' => $responses,
            'page_title' => 'Review Details'
        );
        
        $this->load->view('reviews/view', $data);
    }

    /**
     * List reviews for a user
     */
    public function user_reviews($user_id = null, $review_type = null)
    {
        if (!$user_id) {
            $user_id = $this->auth_user_id;
        }
        
        // Get user information
        $this->load->model('M_users');
        $user = $this->M_users->get_user_by_id($user_id);
        if (!$user) {
            show_404();
        }
        
        // Get reviews
        $reviews = $this->M_reviews->get_reviews_for_user($user_id, $review_type, 20, 0);
        
        // Get review statistics
        $stats = $this->M_reviews->get_user_review_stats($user_id);
        
        $data = array(
            'user' => $user,
            'reviews' => $reviews,
            'stats' => $stats,
            'review_type' => $review_type,
            'page_title' => 'Reviews for ' . $user->username
        );
        
        $this->load->view('reviews/user_reviews', $data);
    }

    /**
     * List reviews by a user
     */
    public function my_reviews()
    {
        $user_id = $this->auth_user_id;
        
        // Get reviews by this user
        $reviews = $this->M_reviews->get_reviews_by_user($user_id, 20, 0);
        
        $data = array(
            'reviews' => $reviews,
            'page_title' => 'My Reviews'
        );
        
        $this->load->view('reviews/my_reviews', $data);
    }

    /**
     * Show pending reviews for current user
     */
    public function pending()
    {
        $user_id = $this->auth_user_id;
        
        // Get jobs that need reviews
        $pending_reviews = $this->get_pending_reviews($user_id);
        
        $data = array(
            'pending_reviews' => $pending_reviews,
            'page_title' => 'Pending Reviews'
        );
        
        $this->load->view('reviews/pending', $data);
    }

    /**
     * Create review response
     */
    public function respond()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $user_id = $this->auth_user_id;
        $review_id = $this->input->post('review_id');
        
        if (!$review_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid review ID.'
            ]);
            return;
        }
        
        // Check if user can respond to this review
        if (!$this->M_review_responses->can_respond_to_review($review_id, $user_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'You cannot respond to this review.'
            ]);
            return;
        }
        
        // Set validation rules
        $this->form_validation->set_rules('response_text', 'Response', 'required|max_length[1000]');
        $this->form_validation->set_rules('is_private', 'Privacy', 'in_list[0,1]');
        
        if ($this->form_validation->run()) {
            $response_data = array(
                'review_id' => $review_id,
                'responder_id' => $user_id,
                'response_text' => $this->input->post('response_text'),
                'is_private' => $this->input->post('is_private') ?: 0
            );
            
            $response_id = $this->M_review_responses->create_response($response_data);
            
            if ($response_id) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Response submitted successfully!'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to submit response. Please try again.'
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
     * Get pending reviews for a user
     */
    private function get_pending_reviews($user_id)
    {
        // Get completed jobs where user can review
        $this->db->select('j.*, u.username as other_party_username, u.first_name as other_party_first_name, u.last_name as other_party_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id OR j.assigned_cleaner_id = u.user_id');
        $this->db->where('j.status', 'completed');
        $this->db->where('j.review_window_expires_at >', date('Y-m-d H:i:s'));
        $this->db->group_start();
        $this->db->where('j.host_id', $user_id);
        $this->db->or_where('j.assigned_cleaner_id', $user_id);
        $this->db->group_end();
        
        // Check if user has already reviewed
        $this->db->where("NOT EXISTS (SELECT 1 FROM reviews r WHERE r.job_id = j.id AND r.reviewer_id = $user_id)");
        
        $this->db->order_by('j.completed_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Update job review status
     */
    private function update_job_review_status($job_id, $review_type)
    {
        $field = ($review_type == 'host_to_cleaner') ? 'host_reviewed' : 'cleaner_reviewed';
        
        $this->db->where('id', $job_id);
        $this->db->update('jobs', array($field => 1));
    }

    /**
     * Send review notification
     */
    private function send_review_notification($reviewee_id, $job, $review_type)
    {
        // Load notifications model
        $this->load->model('M_notifications');
        
        $reviewer_type = ($review_type == 'host_to_cleaner') ? 'host' : 'cleaner';
        $reviewee_type = ($review_type == 'host_to_cleaner') ? 'cleaner' : 'host';
        
        $this->M_notifications->notify_review_received(
            $reviewee_id,
            $job->title,
            $reviewer_type,
            array(
                'job_id' => $job->id,
                'review_type' => $review_type
            )
        );
    }
}
