<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Review Responses Model
 * 
 * Handles all database operations related to review responses
 */
class M_review_responses extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Create a review response
     */
    public function create_response($response_data)
    {
        if (!$this->db->table_exists('review_responses')) {
            return false;
        }
        
        $data = array(
            'review_id' => $response_data['review_id'],
            'responder_id' => $response_data['responder_id'],
            'response_text' => $response_data['response_text'],
            'is_private' => $response_data['is_private'] ?? 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $result = $this->db->insert('review_responses', $data);
        
        if ($result) {
            return $this->db->insert_id();
        }
        
        return false;
    }
    
    /**
     * Get responses for a review
     */
    public function get_responses_for_review($review_id, $include_private = false)
    {
        if (!$this->db->table_exists('review_responses')) {
            return array();
        }
        
        $this->db->select('rr.*, u.username as responder_username, u.first_name as responder_first_name, u.last_name as responder_last_name');
        $this->db->from('review_responses rr');
        $this->db->join('users u', 'rr.responder_id = u.user_id');
        $this->db->where('rr.review_id', $review_id);
        
        if (!$include_private) {
            $this->db->where('rr.is_private', 0);
        }
        
        $this->db->order_by('rr.created_at', 'ASC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Get response by ID
     */
    public function get_response_by_id($response_id)
    {
        if (!$this->db->table_exists('review_responses')) {
            return false;
        }
        
        $this->db->select('rr.*, u.username as responder_username, u.first_name as responder_first_name, u.last_name as responder_last_name');
        $this->db->from('review_responses rr');
        $this->db->join('users u', 'rr.responder_id = u.user_id');
        $this->db->where('rr.id', $response_id);
        
        return $this->db->get()->row();
    }
    
    /**
     * Check if user can respond to a review
     */
    public function can_respond_to_review($review_id, $user_id)
    {
        if (!$this->db->table_exists('review_responses') || !$this->db->table_exists('reviews')) {
            return false;
        }
        
        // Get review details
        $review = $this->db->select('id, reviewee_id, reviewer_id')
                           ->from('reviews')
                           ->where('id', $review_id)
                           ->get()
                           ->row();
        
        if (!$review) {
            return false;
        }
        
        // Check if user is the reviewee (can respond to reviews about them)
        if ($user_id != $review->reviewee_id) {
            return false;
        }
        
        // Check if user has already responded
        $existing_response = $this->db->select('id')
                                     ->from('review_responses')
                                     ->where('review_id', $review_id)
                                     ->where('responder_id', $user_id)
                                     ->get()
                                     ->row();
        
        if ($existing_response) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Update response
     */
    public function update_response($response_id, $response_data)
    {
        if (!$this->db->table_exists('review_responses')) {
            return false;
        }
        
        $data = array(
            'response_text' => $response_data['response_text'],
            'is_private' => $response_data['is_private'] ?? 0,
            'is_edited' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $response_id);
        return $this->db->update('review_responses', $data);
    }
    
    /**
     * Delete response
     */
    public function delete_response($response_id)
    {
        if (!$this->db->table_exists('review_responses')) {
            return false;
        }
        
        $this->db->where('id', $response_id);
        return $this->db->delete('review_responses');
    }
}
