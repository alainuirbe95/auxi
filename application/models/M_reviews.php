<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reviews Model
 * 
 * Handles all database operations related to reviews
 */
class M_reviews extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Create a new review
     */
    public function create_review($review_data)
    {
        if (!$this->db->table_exists('reviews')) {
            return false;
        }
        
        $data = array(
            'job_id' => $review_data['job_id'],
            'reviewer_id' => $review_data['reviewer_id'],
            'reviewee_id' => $review_data['reviewee_id'],
            'rating' => $review_data['rating'],
            'title' => $review_data['title'] ?? null,
            'comment' => $review_data['comment'] ?? null,
            'review_type' => $review_data['review_type'],
            'review_categories' => isset($review_data['review_categories']) ? json_encode($review_data['review_categories']) : null,
            'is_verified' => 1,
            'review_window_expires_at' => $review_data['review_window_expires_at'] ?? null,
            'is_public' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $result = $this->db->insert('reviews', $data);
        
        if ($result) {
            $review_id = $this->db->insert_id();
            
            // Update user profile statistics
            $this->update_user_rating_stats($review_data['reviewee_id']);
            
            return $review_id;
        }
        
        return false;
    }
    
    /**
     * Get reviews for a user
     */
    public function get_reviews_for_user($user_id, $review_type = null, $limit = 20, $offset = 0)
    {
        if (!$this->db->table_exists('reviews')) {
            return array();
        }
        
        $this->db->select('r.*, u.username as reviewer_username, u.first_name as reviewer_first_name, u.last_name as reviewer_last_name, j.title as job_title');
        $this->db->from('reviews r');
        $this->db->join('users u', 'r.reviewer_id = u.user_id');
        $this->db->join('jobs j', 'r.job_id = j.id');
        $this->db->where('r.reviewee_id', $user_id);
        $this->db->where('r.is_public', 1);
        
        if ($review_type) {
            $this->db->where('r.review_type', $review_type);
        }
        
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get reviews by a user
     */
    public function get_reviews_by_user($user_id, $limit = 20, $offset = 0)
    {
        if (!$this->db->table_exists('reviews')) {
            return array();
        }
        
        $this->db->select('r.*, u.username as reviewee_username, u.first_name as reviewee_first_name, u.last_name as reviewee_last_name, j.title as job_title');
        $this->db->from('reviews r');
        $this->db->join('users u', 'r.reviewee_id = u.user_id');
        $this->db->join('jobs j', 'r.job_id = j.id');
        $this->db->where('r.reviewer_id', $user_id);
        $this->db->where('r.is_public', 1);
        
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get review by ID
     */
    public function get_review_by_id($review_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return false;
        }
        
        $this->db->select('r.*, u.username as reviewer_username, u.first_name as reviewer_first_name, u.last_name as reviewer_last_name, v.username as reviewee_username, v.first_name as reviewee_first_name, v.last_name as reviewee_last_name, j.title as job_title');
        $this->db->from('reviews r');
        $this->db->join('users u', 'r.reviewer_id = u.user_id');
        $this->db->join('users v', 'r.reviewee_id = v.user_id');
        $this->db->join('jobs j', 'r.job_id = j.id');
        $this->db->where('r.id', $review_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Check if user can review a job
     */
    public function can_review_job($job_id, $user_id)
    {
        if (!$this->db->table_exists('reviews') || !$this->db->table_exists('jobs')) {
            return false;
        }
        
        // Get job details
        $job = $this->db->select('id, host_id, assigned_cleaner_id, status, completed_at, review_window_expires_at')
                        ->from('jobs')
                        ->where('id', $job_id)
                        ->get()
                        ->row();
        
        if (!$job || $job->status !== 'completed') {
            return false;
        }
        
        // Check if user is involved in the job
        if ($user_id != $job->host_id && $user_id != $job->assigned_cleaner_id) {
            return false;
        }
        
        // Check if review window has expired
        if ($job->review_window_expires_at && strtotime($job->review_window_expires_at) < time()) {
            return false;
        }
        
        // Check if user has already reviewed this job
        $existing_review = $this->db->select('id')
                                   ->from('reviews')
                                   ->where('job_id', $job_id)
                                   ->where('reviewer_id', $user_id)
                                   ->get()
                                   ->row();
        
        if ($existing_review) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get review statistics for a user
     */
    public function get_user_review_stats($user_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return array(
                'average_rating' => 0,
                'total_reviews' => 0,
                'rating_breakdown' => array()
            );
        }
        
        // Get average rating and total reviews
        $stats = $this->db->select('AVG(rating) as average_rating, COUNT(*) as total_reviews')
                          ->from('reviews')
                          ->where('reviewee_id', $user_id)
                          ->where('is_public', 1)
                          ->get()
                          ->row();
        
        // Get rating breakdown
        $breakdown = $this->db->select('rating, COUNT(*) as count')
                             ->from('reviews')
                             ->where('reviewee_id', $user_id)
                             ->where('is_public', 1)
                             ->group_by('rating')
                             ->order_by('rating', 'DESC')
                             ->get()
                             ->result();
        
        $rating_breakdown = array();
        for ($i = 5; $i >= 1; $i--) {
            $rating_breakdown[$i] = 0;
        }
        
        foreach ($breakdown as $item) {
            $rating_breakdown[$item->rating] = (int)$item->count;
        }
        
        return array(
            'average_rating' => (float)($stats->average_rating ?? 0),
            'total_reviews' => (int)($stats->total_reviews ?? 0),
            'rating_breakdown' => $rating_breakdown
        );
    }
    
    /**
     * Update user rating statistics
     */
    public function update_user_rating_stats($user_id)
    {
        if (!$this->db->table_exists('reviews') || !$this->db->table_exists('user_profiles')) {
            return false;
        }
        
        $stats = $this->get_user_review_stats($user_id);
        
        $this->db->where('user_id', $user_id);
        return $this->db->update('user_profiles', array(
            'average_rating' => $stats['average_rating'],
            'total_reviews' => $stats['total_reviews'],
            'updated_at' => date('Y-m-d H:i:s')
        ));
    }
    
    /**
     * Get recent reviews
     */
    public function get_recent_reviews($limit = 10)
    {
        if (!$this->db->table_exists('reviews')) {
            return array();
        }
        
        $this->db->select('r.*, u.username as reviewer_username, u.first_name as reviewer_first_name, u.last_name as reviewer_last_name, v.username as reviewee_username, v.first_name as reviewee_first_name, v.last_name as reviewee_last_name, j.title as job_title');
        $this->db->from('reviews r');
        $this->db->join('users u', 'r.reviewer_id = u.user_id');
        $this->db->join('users v', 'r.reviewee_id = v.user_id');
        $this->db->join('jobs j', 'r.job_id = j.id');
        $this->db->where('r.is_public', 1);
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get review categories
     */
    public function get_review_categories($review_type)
    {
        $categories = array(
            'host_to_cleaner' => array(
                'quality_work' => 'Quality of Work',
                'punctuality' => 'Punctuality',
                'communication' => 'Communication',
                'professionalism' => 'Professionalism',
                'cleanliness' => 'Cleanliness',
                'respect_property' => 'Respect for Property',
                'following_instructions' => 'Following Instructions',
                'overall_satisfaction' => 'Overall Satisfaction'
            ),
            'cleaner_to_host' => array(
                'clear_instructions' => 'Clear Instructions',
                'fair_payment' => 'Fair Payment',
                'respectful_treatment' => 'Respectful Treatment',
                'property_condition' => 'Property Condition',
                'communication' => 'Communication',
                'flexibility' => 'Flexibility',
                'overall_experience' => 'Overall Experience'
            )
        );
        
        return $categories[$review_type] ?? array();
    }


}
