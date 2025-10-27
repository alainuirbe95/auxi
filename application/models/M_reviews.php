<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Reviews Model - Two-Tier Review System
 * 
 * Manages public and private reviews for jobs
 * Public: Overall rating + public comment (visible on profiles)
 * Private: Category ratings + comments (only visible to job participants)
 */
class M_reviews extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Create a new review with public and private data
     * 
     * @param array $review_data Review data including all ratings and comments
     * @return int|false Review ID on success, false on failure
     */
    public function create_review($review_data)
    {
        if (!$this->db->table_exists('reviews')) {
            return false;
        }
        
        // Validate required fields
        $required_fields = [
            'job_id', 'reviewer_id', 'reviewee_id', 'review_type',
            'overall_rating', 'public_comment',
            'professionalism_rating', 'quality_rating', 
            'communication_rating', 'punctuality_rating'
        ];
        
        foreach ($required_fields as $field) {
            if (!isset($review_data[$field])) {
                log_message('error', "Missing required field: {$field}");
                return false;
            }
        }
        
        // Prepare data for insertion
        $data = array(
            'job_id' => $review_data['job_id'],
            'reviewer_id' => $review_data['reviewer_id'],
            'reviewee_id' => $review_data['reviewee_id'],
            'review_type' => $review_data['review_type'],
            
            // Public data
            'overall_rating' => $review_data['overall_rating'],
            'public_comment' => $review_data['public_comment'],
            
            // Private category ratings (required)
            'professionalism_rating' => $review_data['professionalism_rating'],
            'professionalism_comment' => $review_data['professionalism_comment'] ?? null,
            'quality_rating' => $review_data['quality_rating'],
            'quality_comment' => $review_data['quality_comment'] ?? null,
            'communication_rating' => $review_data['communication_rating'],
            'communication_comment' => $review_data['communication_comment'] ?? null,
            'punctuality_rating' => $review_data['punctuality_rating'],
            'punctuality_comment' => $review_data['punctuality_comment'] ?? null,
            'private_notes' => $review_data['private_notes'] ?? null,
            
            // Admin moderation
            'is_hidden' => 0,
            'hidden_by' => null,
            'hidden_reason' => null,
            'hidden_at' => null,
            
            // Timestamps
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        
        $result = $this->db->insert('reviews', $data);
        
        if ($result) {
            $review_id = $this->db->insert_id();
            
            // Update reviewee's average rating
            $this->update_user_rating_stats($review_data['reviewee_id']);
            
            return $review_id;
        }
        
        return false;
    }
    
    /**
     * Check if a user has already reviewed for a specific job
     * 
     * @param int $job_id Job ID
     * @param int $reviewer_id Reviewer user ID
     * @return object|null Review object or null
     */
    public function get_review_by_job_and_reviewer($job_id, $reviewer_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return null;
        }
        
        $this->db->where('job_id', $job_id);
        $this->db->where('reviewer_id', $reviewer_id);
        $query = $this->db->get('reviews');
        
        return $query->row();
    }
    
    /**
     * Get public reviews for a user (to display on their profile)
     * 
     * @param int $user_id User ID
     * @param int $limit Number of reviews to fetch
     * @param int $offset Offset for pagination
     * @return array Array of review objects
     */
    public function get_public_reviews_for_user($user_id, $limit = 10, $offset = 0, $requesting_user_id = null)
    {
        if (!$this->db->table_exists('reviews')) {
            return [];
        }
        
        $this->db->select('
            r.*,
            reviewer.username as reviewer_username,
            reviewer.first_name as reviewer_first_name,
            reviewer.last_name as reviewer_last_name,
            j.title as job_title,
            j.host_id,
            CASE 
                WHEN TRIM(CONCAT(COALESCE(reviewer.first_name, ""), " ", COALESCE(reviewer.last_name, ""))) != "" 
                THEN TRIM(CONCAT(COALESCE(reviewer.first_name, ""), " ", COALESCE(reviewer.last_name, "")))
                ELSE reviewer.username
            END as reviewer_name
        ', FALSE);
        $this->db->from('reviews r');
        $this->db->join('users reviewer', 'r.reviewer_id = reviewer.user_id', 'left');
        $this->db->join('jobs j', 'r.job_id = j.id', 'left');
        $this->db->where('r.reviewee_id', $user_id);
        $this->db->where('r.is_hidden', 0);
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        $reviews = $query->result();
        
        // Filter reviews based on visibility rules
        // If requesting user is a host viewing their own profile/dashboard, 
        // hide cleaner reviews where the host hasn't reviewed yet
        if ($requesting_user_id && $reviews) {
            $filtered_reviews = [];
            foreach ($reviews as $review) {
                $should_show = true;
                
                // If this is a cleaner-to-host review
                if ($review->review_type === 'cleaner_to_host') {
                    // And the requesting user is the host being reviewed
                    if ($requesting_user_id == $review->host_id) {
                        // Check if host has submitted their review for this job
                        $host_has_reviewed = $this->has_host_reviewed_job($review->job_id, $requesting_user_id);
                        if (!$host_has_reviewed) {
                            $should_show = false;
                        }
                    }
                }
                
                if ($should_show) {
                    $filtered_reviews[] = $review;
                }
            }
            return $filtered_reviews;
        }
        
        return $reviews;
    }
    
    /**
     * Count public reviews for a user
     * 
     * @param int $user_id User ID
     * @return int Count of reviews
     */
    public function count_public_reviews_for_user($user_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return 0;
        }
        
        $this->db->where('reviewee_id', $user_id);
        $this->db->where('is_hidden', 0);
        return $this->db->count_all_results('reviews');
    }
    
    /**
     * Get full review details (public + private) for job participants
     * Only accessible by the two users involved in the job
     * 
     * @param int $review_id Review ID
     * @param int $requesting_user_id User requesting the data
     * @return object|null Review object with all details or null
     */
    public function get_review_details($review_id, $requesting_user_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return null;
        }
        
        $this->db->select('
            r.*,
            reviewer.username as reviewer_username,
            reviewer.first_name as reviewer_first_name,
            reviewer.last_name as reviewer_last_name,
            reviewee.username as reviewee_username,
            reviewee.first_name as reviewee_first_name,
            reviewee.last_name as reviewee_last_name,
            j.title as job_title,
            j.host_id,
            j.assigned_cleaner_id
        ');
        $this->db->from('reviews r');
        $this->db->join('users reviewer', 'r.reviewer_id = reviewer.user_id', 'left');
        $this->db->join('users reviewee', 'r.reviewee_id = reviewee.user_id', 'left');
        $this->db->join('jobs j', 'r.job_id = j.id', 'left');
        $this->db->where('r.id', $review_id);
        
        $query = $this->db->get();
        $review = $query->row();
        
        // Verify requesting user is part of the job (host or cleaner)
        if ($review && ($review->host_id == $requesting_user_id || $review->assigned_cleaner_id == $requesting_user_id)) {
            return $review;
        }
        
        return null;
    }
    
    /**
     * Get both reviews for a job (host's review of cleaner + cleaner's review of host)
     * 
     * @param int $job_id Job ID
     * @return array Array with 'host_review' and 'cleaner_review' keys
     */
    public function get_reviews_for_job($job_id, $requesting_user_id = null, $user_role = null)
    {
        if (!$this->db->table_exists('reviews')) {
            return ['host_review' => null, 'cleaner_review' => null, 'can_see_cleaner_review' => false];
        }
        
        $this->db->where('job_id', $job_id);
        $query = $this->db->get('reviews');
        $reviews = $query->result();
        
        $result = [
            'host_review' => null,
            'cleaner_review' => null,
            'can_see_cleaner_review' => false
        ];
        
        foreach ($reviews as $review) {
            if ($review->review_type === 'host_to_cleaner') {
                $result['host_review'] = $review;
            } else if ($review->review_type === 'cleaner_to_host') {
                $result['cleaner_review'] = $review;
            }
        }
        
        // Visibility logic: Host can only see cleaner's review after submitting their own
        // This prevents bias
        if ($user_role === 'host' && $requesting_user_id) {
            // Host can see cleaner's review only if they've already reviewed the cleaner
            $result['can_see_cleaner_review'] = !empty($result['host_review']);
            
            // Hide cleaner review if host hasn't reviewed yet
            if (!$result['can_see_cleaner_review']) {
                $result['cleaner_review'] = null;
            }
        } else {
            // Cleaners, admins, or no role specified can see all reviews
            $result['can_see_cleaner_review'] = true;
        }
        
        return $result;
    }
    
    /**
     * Check if host has already reviewed a job (submitted their review)
     * Used to determine if host can see cleaner's review
     * 
     * @param int $job_id Job ID
     * @param int $host_id Host user ID
     * @return bool True if host has reviewed, false otherwise
     */
    public function has_host_reviewed_job($job_id, $host_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return false;
        }
        
        $this->db->where('job_id', $job_id);
        $this->db->where('reviewer_id', $host_id);
        $this->db->where('review_type', 'host_to_cleaner');
        $count = $this->db->count_all_results('reviews');
        
        return $count > 0;
    }
    
    /**
     * Calculate average ratings for a user
     * 
     * @param int $user_id User ID
     * @param int $requesting_user_id ID of user viewing ratings (for visibility filtering)
     * @return array Average ratings for all categories
     */
    public function calculate_user_average_ratings($user_id, $requesting_user_id = null)
    {
        if (!$this->db->table_exists('reviews')) {
            return [
                'overall_average' => 0,
                'category_averages' => [
                    'professionalism' => 0,
                    'quality' => 0,
                    'communication' => 0,
                    'punctuality' => 0
                ],
                'total_reviews' => 0
            ];
        }
        
        // Standard calculation for everyone else
        // Get all reviews to calculate overall average from category ratings
        $this->db->select('
            professionalism_rating,
            quality_rating,
            communication_rating,
            punctuality_rating
        ');
        $this->db->where('reviewee_id', $user_id);
        $this->db->where('is_hidden', 0);
        $query = $this->db->get('reviews');
        $reviews = $query->result();
        
        if (empty($reviews)) {
            return [
                'overall_average' => 0,
                'category_averages' => [
                    'professionalism' => 0,
                    'quality' => 0,
                    'communication' => 0,
                    'punctuality' => 0
                ],
                'total_reviews' => 0
            ];
        }
        
        // Calculate averages
        $sum_prof = $sum_quality = $sum_comm = $sum_punct = 0;
        $total_overall = 0;
        
        foreach ($reviews as $review) {
            $sum_prof += $review->professionalism_rating ?? 0;
            $sum_quality += $review->quality_rating ?? 0;
            $sum_comm += $review->communication_rating ?? 0;
            $sum_punct += $review->punctuality_rating ?? 0;
            
            // Calculate this review's overall rating as average of its categories
            $review_categories = 0;
            $review_sum = 0;
            
            if ($review->professionalism_rating > 0) { $review_sum += $review->professionalism_rating; $review_categories++; }
            if ($review->quality_rating > 0) { $review_sum += $review->quality_rating; $review_categories++; }
            if ($review->communication_rating > 0) { $review_sum += $review->communication_rating; $review_categories++; }
            if ($review->punctuality_rating > 0) { $review_sum += $review->punctuality_rating; $review_categories++; }
            
            if ($review_categories > 0) {
                $total_overall += ($review_sum / $review_categories);
            }
        }
        
        $count = count($reviews);
        
        return [
            'overall_average' => round($total_overall / $count, 1),
            'category_averages' => [
                'professionalism' => round($sum_prof / $count, 1),
                'quality' => round($sum_quality / $count, 1),
                'communication' => round($sum_comm / $count, 1),
                'punctuality' => round($sum_punct / $count, 1)
            ],
            'total_reviews' => $count
        ];
    }
    
    /**
     * Update user rating statistics (called after new review)
     * This could update a cached rating in users table for performance
     * 
     * @param int $user_id User ID
     * @return bool Success
     */
    public function update_user_rating_stats($user_id)
    {
        $ratings = $this->calculate_user_average_ratings($user_id);
        
        // Check if users table has rating columns
        $columns = $this->db->list_fields('users');
        
        if (in_array('average_rating', $columns) && in_array('total_reviews', $columns)) {
            $update_data = [
                'average_rating' => $ratings['overall'],
                'total_reviews' => $ratings['total_reviews']
            ];
            
            $this->db->where('user_id', $user_id);
            return $this->db->update('users', $update_data);
        }
        
        return true; // If columns don't exist, it's okay
    }
    
    /**
     * Check if a user can leave a review for a job
     * 
     * @param int $job_id Job ID
     * @param int $user_id User ID
     * @return array ['can_review' => bool, 'reason' => string]
     */
    public function can_user_review($job_id, $user_id)
    {
        // Load job model
        $this->load->model('M_jobs');
        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            return ['can_review' => false, 'reason' => 'Job not found'];
        }
        
        // Check if user is part of this job
        $is_host = ($job->host_id == $user_id);
        $is_cleaner = ($job->assigned_cleaner_id == $user_id);
        
        if (!$is_host && !$is_cleaner) {
            return ['can_review' => false, 'reason' => 'You are not part of this job'];
        }
        
        // Check if already reviewed
        $existing_review = $this->get_review_by_job_and_reviewer($job_id, $user_id);
        if ($existing_review) {
            return ['can_review' => false, 'reason' => 'You have already reviewed this job'];
        }
        
        // Cleaner can review when job is completed
        if ($is_cleaner && $job->status === 'completed') {
            return ['can_review' => true, 'reason' => 'Ready to review'];
        }
        
        // Host can review when job is completed (before closing or recalling)
        if ($is_host && $job->status === 'completed') {
            return ['can_review' => true, 'reason' => 'Ready to review'];
        }
        
        return ['can_review' => false, 'reason' => 'Job is not ready for review'];
    }
    
    /**
     * Get review statistics for admin dashboard
     * 
     * @return array Review statistics
     */
    public function get_review_statistics()
    {
        if (!$this->db->table_exists('reviews')) {
            return [
                'total_reviews' => 0,
                'avg_rating' => 0,
                'reviews_last_30_days' => 0,
                'hidden_reviews' => 0
            ];
        }
        
        $stats = [];
        
        // Total reviews
        $stats['total_reviews'] = $this->db->count_all('reviews');
        
        // Average overall rating
        $this->db->select('AVG(overall_rating) as avg');
        $this->db->where('is_hidden', 0);
        $result = $this->db->get('reviews')->row();
        $stats['average_rating'] = round($result->avg ?? 0, 1);
        
        // Reviews in last 30 days
        $this->db->where('created_at >=', date('Y-m-d', strtotime('-30 days')));
        $stats['reviews_last_30_days'] = $this->db->count_all_results('reviews');
        
        // Hidden reviews
        $this->db->where('is_hidden', 1);
        $stats['hidden_reviews'] = $this->db->count_all_results('reviews');
        
        // Flagged reviews (placeholder - implement flagging system later)
        $stats['flagged_reviews'] = 0;
        
        return $stats;
    }
    
    /**
     * Hide a review (admin moderation)
     * 
     * @param int $review_id Review ID
     * @param int $admin_id Admin user ID
     * @param string $reason Reason for hiding
     * @return bool Success
     */
    public function hide_review($review_id, $admin_id, $reason)
    {
        if (!$this->db->table_exists('reviews')) {
            return false;
        }
        
        $data = [
            'is_hidden' => 1,
            'hidden_by' => $admin_id,
            'hidden_reason' => $reason,
            'hidden_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $review_id);
        $result = $this->db->update('reviews', $data);
        
        // Recalculate reviewee's rating stats
        if ($result) {
            $review = $this->get_review_by_id($review_id);
            if ($review) {
                $this->update_user_rating_stats($review->reviewee_id);
            }
        }
        
        return $result;
    }
    
    /**
     * Unhide a review (admin action)
     * 
     * @param int $review_id Review ID
     * @return bool Success
     */
    public function unhide_review($review_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return false;
        }
        
        $data = [
            'is_hidden' => 0,
            'hidden_by' => null,
            'hidden_reason' => null,
            'hidden_at' => null,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $review_id);
        $result = $this->db->update('reviews', $data);
        
        // Recalculate reviewee's rating stats
        if ($result) {
            $review = $this->get_review_by_id($review_id);
            if ($review) {
                $this->update_user_rating_stats($review->reviewee_id);
            }
        }
        
        return $result;
    }
    
    /**
     * Delete a review (admin only)
     * 
     * @param int $review_id Review ID
     * @return bool Success
     */
    public function delete_review($review_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return false;
        }
        
        // Get review before deleting to update stats
        $review = $this->get_review_by_id($review_id);
        
        $this->db->where('id', $review_id);
        $result = $this->db->delete('reviews');
        
        // Recalculate reviewee's rating stats
        if ($result && $review) {
            $this->update_user_rating_stats($review->reviewee_id);
        }
        
        return $result;
    }
    
    /**
     * Get review by ID
     * 
     * @param int $review_id Review ID
     * @return object|null Review object
     */
    public function get_review_by_id($review_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return null;
        }
        
        $this->db->select('
            r.*,
            r.id as review_id,
            reviewer.username as reviewer_username,
            reviewer.first_name as reviewer_first_name,
            reviewer.last_name as reviewer_last_name,
            reviewee.username as reviewee_username,
            reviewee.first_name as reviewee_first_name,
            reviewee.last_name as reviewee_last_name,
            j.title as job_title,
            CONCAT(COALESCE(reviewer.first_name, ""), " ", COALESCE(reviewer.last_name, "")) as reviewer_name,
            CONCAT(COALESCE(reviewee.first_name, ""), " ", COALESCE(reviewee.last_name, "")) as reviewee_name
        ', FALSE);
        $this->db->from('reviews r');
        $this->db->join('users reviewer', 'r.reviewer_id = reviewer.user_id', 'left');
        $this->db->join('users reviewee', 'r.reviewee_id = reviewee.user_id', 'left');
        $this->db->join('jobs j', 'r.job_id = j.id', 'left');
        $this->db->where('r.id', $review_id);
        
        $query = $this->db->get();
        return $query->row();
    }
    
    /**
     * Get all reviews (admin view)
     * 
     * @param array $filters Filters (rating, user, hidden status, etc.)
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Review objects
     */
    public function get_all_reviews_admin($filters = [], $limit = 20, $offset = 0)
    {
        if (!$this->db->table_exists('reviews')) {
            return [];
        }
        
        $this->db->select('
            r.id as review_id,
            r.job_id,
            r.reviewer_id,
            r.reviewee_id,
            r.overall_rating,
            r.public_comment,
            r.professionalism_rating,
            r.professionalism_comment,
            r.quality_rating,
            r.quality_comment,
            r.communication_rating,
            r.communication_comment,
            r.punctuality_rating,
            r.punctuality_comment,
            r.private_notes,
            r.is_hidden,
            r.hidden_by,
            r.hidden_reason,
            r.hidden_at,
            r.review_type,
            r.created_at,
            r.updated_at,
            reviewer.username as reviewer_username,
            reviewer.first_name as reviewer_first_name,
            reviewer.last_name as reviewer_last_name,
            reviewee.username as reviewee_username,
            reviewee.first_name as reviewee_first_name,
            reviewee.last_name as reviewee_last_name,
            j.title as job_title,
            CONCAT(COALESCE(reviewer.first_name, ""), " ", COALESCE(reviewer.last_name, "")) as reviewer_name,
            CONCAT(COALESCE(reviewee.first_name, ""), " ", COALESCE(reviewee.last_name, "")) as reviewee_name
        ', FALSE);
        $this->db->from('reviews r');
        $this->db->join('users reviewer', 'r.reviewer_id = reviewer.user_id', 'left');
        $this->db->join('users reviewee', 'r.reviewee_id = reviewee.user_id', 'left');
        $this->db->join('jobs j', 'r.job_id = j.id', 'left');
        
        // Apply filters
        if (!empty($filters['rating'])) {
            $this->db->where('r.overall_rating', $filters['rating']);
        }
        
        if (!empty($filters['review_type'])) {
            $this->db->where('r.review_type', $filters['review_type']);
        }
        
        if (isset($filters['is_hidden'])) {
            $this->db->where('r.is_hidden', $filters['is_hidden']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('r.public_comment', $filters['search']);
            $this->db->or_like('reviewer.username', $filters['search']);
            $this->db->or_like('reviewee.username', $filters['search']);
            $this->db->group_end();
        }
        
        $this->db->order_by('r.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result();
    }
    
    /**
     * Count all reviews (admin)
     * 
     * @param array $filters Filters
     * @return int Count
     */
    public function count_all_reviews_admin($filters = [])
    {
        if (!$this->db->table_exists('reviews')) {
            return 0;
        }
        
        $this->db->from('reviews r');
        $this->db->join('users reviewer', 'r.reviewer_id = reviewer.user_id', 'left');
        $this->db->join('users reviewee', 'r.reviewee_id = reviewee.user_id', 'left');
        
        // Apply same filters as get_all_reviews_admin
        if (!empty($filters['rating'])) {
            $this->db->where('r.overall_rating', $filters['rating']);
        }
        
        if (!empty($filters['review_type'])) {
            $this->db->where('r.review_type', $filters['review_type']);
        }
        
        if (isset($filters['is_hidden'])) {
            $this->db->where('r.is_hidden', $filters['is_hidden']);
        }
        
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('r.public_comment', $filters['search']);
            $this->db->or_like('reviewer.username', $filters['search']);
            $this->db->or_like('reviewee.username', $filters['search']);
            $this->db->group_end();
        }
        
        return $this->db->count_all_results();
    }
    
    /**
     * Validate review data before submission
     * 
     * @param array $review_data Review data to validate
     * @return array ['valid' => bool, 'errors' => array]
     */
    public function validate_review_data($review_data)
    {
        $errors = [];
        
        // Validate overall rating
        if (empty($review_data['overall_rating']) || $review_data['overall_rating'] < 1 || $review_data['overall_rating'] > 5) {
            $errors[] = 'Overall rating must be between 1 and 5 stars';
        }
        
        // Validate public comment
        if (empty($review_data['public_comment'])) {
            $errors[] = 'Public comment is required';
        } else {
            $comment_length = strlen(trim($review_data['public_comment']));
            if ($comment_length < 30) {
                $errors[] = 'Public comment must be at least 30 characters';
            }
            if ($comment_length > 100) {
                $errors[] = 'Public comment must not exceed 100 characters';
            }
        }
        
        // Validate category ratings (all required)
        $categories = ['professionalism', 'quality', 'communication', 'punctuality'];
        foreach ($categories as $category) {
            $field = $category . '_rating';
            if (empty($review_data[$field]) || $review_data[$field] < 1 || $review_data[$field] > 5) {
                $errors[] = ucfirst($category) . ' rating must be between 1 and 5 stars';
            }
        }
        
        // Validate optional category comments (max 100 chars)
        foreach ($categories as $category) {
            $field = $category . '_comment';
            if (!empty($review_data[$field]) && strlen($review_data[$field]) > 100) {
                $errors[] = ucfirst($category) . ' comment must not exceed 100 characters';
            }
        }
        
        // Validate private notes (max 100 chars)
        if (!empty($review_data['private_notes']) && strlen($review_data['private_notes']) > 100) {
            $errors[] = 'Private notes must not exceed 100 characters';
        }
        
        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }
    
    /**
     * Get rating distribution for a user (for displaying rating breakdown)
     * 
     * @param int $user_id User ID
     * @return array Rating distribution [5 => count, 4 => count, etc.]
     */
    public function get_rating_distribution($user_id)
    {
        if (!$this->db->table_exists('reviews')) {
            return [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        }
        
        $this->db->select('overall_rating, COUNT(*) as count');
        $this->db->where('reviewee_id', $user_id);
        $this->db->where('is_hidden', 0);
        $this->db->group_by('overall_rating');
        $query = $this->db->get('reviews');
        
        $distribution = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        foreach ($query->result() as $row) {
            $distribution[$row->overall_rating] = $row->count;
        }
        
        return $distribution;
    }
}
