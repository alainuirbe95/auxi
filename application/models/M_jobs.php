<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Jobs Model
 * 
 * Handles all database operations related to cleaning jobs
 */
class M_jobs extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new job
     */
    public function create_job($data)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            log_message('error', 'Jobs table does not exist');
            return false;
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        log_message('debug', 'Inserting job data: ' . print_r($data, true));
        
        $result = $this->db->insert('jobs', $data);
        
        if (!$result) {
            $error = $this->db->error();
            log_message('error', 'Job insertion failed: ' . print_r($error, true));
            return false;
        }
        
        $job_id = $this->db->insert_id();
        log_message('debug', 'Job created successfully with ID: ' . $job_id);
        
        return $job_id;
    }

    /**
     * Update an existing job
     */
    public function update_job($job_id, $data)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            log_message('error', 'Jobs table does not exist in update_job');
            return false;
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        log_message('debug', 'Updating job ID ' . $job_id . ' with data: ' . print_r($data, true));
        
        $this->db->where('id', $job_id);
        $result = $this->db->update('jobs', $data);
        
        if (!$result) {
            $error = $this->db->error();
            log_message('error', 'Job update failed: ' . print_r($error, true));
            return false;
        }
        
        log_message('debug', 'Job updated successfully');
        return true;
    }

    /**
     * Get jobs by host ID
     */
    public function get_jobs_by_host($host_id, $limit = null, $offset = 0)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            log_message('error', 'Jobs table does not exist in get_jobs_by_host');
            return [];
        }
        
        $this->db->select('j.*');
        $this->db->from('jobs j');
        $this->db->where('j.host_id', $host_id);
        
        // Only join offers table if it exists
        if ($this->db->table_exists('offers')) {
            $this->db->select('COUNT(o.id) as offer_count');
            $this->db->join('offers o', 'j.id = o.job_id', 'left');
            $this->db->group_by('j.id');
        } else {
            $this->db->select('0 as offer_count');
        }
        
        $this->db->order_by('j.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        $result = $query->result();
        
        log_message('debug', 'get_jobs_by_host query: ' . $this->db->last_query());
        log_message('debug', 'get_jobs_by_host result count: ' . count($result));
        
        return $result;
    }

    /**
     * Get recent jobs for host dashboard
     */
    public function get_host_recent_jobs($host_id, $limit = 5)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }
        
        $this->db->select('j.*, COUNT(o.id) as offer_count');
        $this->db->from('jobs j');
        
        // Only join offers table if it exists
        if ($this->db->table_exists('offers')) {
            $this->db->join('offers o', 'j.id = o.job_id', 'left');
        }
        
        $this->db->where('j.host_id', $host_id);
        $this->db->group_by('j.id');
        $this->db->order_by('j.created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get active jobs for cleaners to browse
     */
    public function get_active_jobs($filters = [], $limit = 20, $offset = 0)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        
        // Only join offers table if it exists
        if ($this->db->table_exists('offers')) {
            $this->db->select('COUNT(o.id) as offer_count');
            $this->db->join('offers o', 'j.id = o.job_id', 'left');
            $this->db->group_by('j.id');
        }
        
        $this->db->where('j.status', 'open');
        
        // Filter out past jobs - only show future jobs and current day jobs
        $today = date('Y-m-d');
        $this->db->group_start();
        $this->db->where('j.scheduled_date >', $today);
        $this->db->or_where('j.scheduled_date', $today);
        $this->db->or_where('j.scheduled_date IS NULL');
        $this->db->group_end();
        
        // Apply filters
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('j.title', $filters['search']);
            $this->db->or_like('j.description', $filters['search']);
            $this->db->or_like('j.address', $filters['search']);
            $this->db->group_end();
        }
        
        if (!empty($filters['max_price'])) {
            $this->db->where('j.suggested_price <=', $filters['max_price']);
        }
        
        if (!empty($filters['min_price'])) {
            $this->db->where('j.suggested_price >=', $filters['min_price']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('j.date_time >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('j.date_time <=', $filters['date_to']);
        }
        
        // Order by creation date (newest first)
        $this->db->order_by('j.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result();
    }


    /**
     * Update job status
     */
    public function update_job_status($job_id, $status)
    {
        return $this->update_job($job_id, ['status' => $status]);
    }

    /**
     * Get host statistics for dashboard
     */
    public function get_host_stats($host_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [
                'total_jobs' => 0,
                'active_jobs' => 0,
                'completed_jobs' => 0,
                'cancelled_jobs' => 0,
                'total_spent' => 0
            ];
        }
        
        $stats = [];
        
        // Total jobs
        $this->db->where('host_id', $host_id);
        $stats['total_jobs'] = $this->db->count_all_results('jobs');
        
        // Active jobs (open + assigned)
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where_in('status', ['open', 'assigned']);
        $stats['active_jobs'] = $this->db->count_all_results('jobs');
        
        // Completed jobs
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'completed');
        $stats['completed_jobs'] = $this->db->count_all_results('jobs');
        
        // Cancelled jobs
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'cancelled');
        $stats['cancelled_jobs'] = $this->db->count_all_results('jobs');
        
        // Total spent
        $this->db->reset_query();
        $this->db->select('SUM(final_price) as total_spent');
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'completed');
        $query = $this->db->get('jobs');
        $result = $query->row();
        $stats['total_spent'] = $result->total_spent ?: 0;
        
        return $stats;
    }

    /**
     * Get admin job statistics
     */
    public function get_admin_stats()
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [
                'total_jobs' => 0,
                'active_jobs' => 0,
                'completed_jobs' => 0,
                'cancelled_jobs' => 0
            ];
        }
        
        $stats = [];
        
        // Total jobs
        $stats['total_jobs'] = $this->db->count_all('jobs');
        
        // Active jobs (open + assigned) - reset query builder
        $this->db->reset_query();
        $this->db->where_in('status', ['open', 'assigned']);
        $stats['active_jobs'] = $this->db->count_all_results('jobs');
        
        // Completed jobs - reset query builder
        $this->db->reset_query();
        $this->db->where('status', 'completed');
        $stats['completed_jobs'] = $this->db->count_all_results('jobs');
        
        // Cancelled jobs - reset query builder
        $this->db->reset_query();
        $this->db->where('status', 'cancelled');
        $stats['cancelled_jobs'] = $this->db->count_all_results('jobs');
        
        return $stats;
    }


    /**
     * Get jobs with offers count
     */
    public function get_jobs_with_offers($host_id)
    {
        $this->db->select('j.*, COUNT(o.id) as offer_count, COUNT(CASE WHEN o.status = "pending" THEN 1 END) as pending_offers');
        $this->db->from('jobs j');
        $this->db->join('offers o', 'j.id = o.job_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->group_by('j.id');
        $this->db->order_by('j.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Check if user can modify job
     */
    public function can_modify_job($job_id, $user_id)
    {
        $this->db->where('id', $job_id);
        $this->db->where('host_id', $user_id);
        $this->db->where('status', 'active'); // Can only modify active jobs
        
        return $this->db->count_all_results('jobs') > 0;
    }

    /**
     * Delete job (soft delete by setting status to cancelled)
     */
    public function delete_job($job_id, $user_id)
    {
        if (!$this->can_modify_job($job_id, $user_id)) {
            return false;
        }
        
        return $this->update_job_status($job_id, 'cancelled');
    }
    
    /**
     * Hard delete job (permanently remove from database)
     */
    public function hard_delete_job($job_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            log_message('error', 'Jobs table does not exist in hard_delete_job');
            return false;
        }
        
        // Check if job exists and is cancelled (case-insensitive)
        $this->db->where('id', $job_id);
        $this->db->where('LOWER(status)', 'cancelled');
        $query = $this->db->get('jobs');
        
        log_message('debug', 'Hard delete check - Job ID: ' . $job_id . ', Found rows: ' . $query->num_rows());
        
        if ($query->num_rows() == 0) {
            log_message('error', 'Job not found or not cancelled in hard_delete_job');
            return false;
        }
        
        // Delete the job
        $this->db->where('id', $job_id);
        $result = $this->db->delete('jobs');
        
        if (!$result) {
            $error = $this->db->error();
            log_message('error', 'Delete job failed: ' . print_r($error, true));
        } else {
            log_message('debug', 'Job deleted successfully from database');
        }
        
        return $result;
    }

    /**
     * Get all jobs for admin management
     */
    public function get_all_jobs_admin($filters = [], $limit = 20, $offset = 0, $sort_by = 'created_at', $sort_order = 'DESC')
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name, u.email as host_email');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        
        // Apply filters
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('j.title', $filters['search']);
            $this->db->or_like('j.description', $filters['search']);
            $this->db->or_like('j.address', $filters['search']);
            $this->db->or_like('u.username', $filters['search']);
            $this->db->or_like('u.first_name', $filters['search']);
            $this->db->or_like('u.last_name', $filters['search']);
            $this->db->group_end();
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('j.status', $filters['status']);
        }
        
        if (!empty($filters['host'])) {
            $this->db->where('j.host_id', $filters['host']);
        }
        
        // Apply sorting
        $this->db->order_by('j.' . $sort_by, $sort_order);
        
        // Apply limit and offset
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Count all jobs for admin management
     */
    public function count_all_jobs_admin($filters = [])
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return 0;
        }

        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        
        // Apply filters
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('j.title', $filters['search']);
            $this->db->or_like('j.description', $filters['search']);
            $this->db->or_like('j.address', $filters['search']);
            $this->db->or_like('u.username', $filters['search']);
            $this->db->or_like('u.first_name', $filters['search']);
            $this->db->or_like('u.last_name', $filters['search']);
            $this->db->group_end();
        }
        
        if (!empty($filters['status'])) {
            $this->db->where('j.status', $filters['status']);
        }
        
        if (!empty($filters['host'])) {
            $this->db->where('j.host_id', $filters['host']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Get job photos
     */
    public function get_job_photos($job_id)
    {
        $this->db->where('job_id', $job_id);
        $this->db->order_by('created_at', 'ASC');
        
        $query = $this->db->get('job_photos');
        return $query->result();
    }

    /**
     * Add photo to job
     */
    public function add_job_photo($job_id, $photo_data)
    {
        $data = [
            'job_id' => $job_id,
            'photo_url' => $photo_data['photo_url'],
            'description' => $photo_data['description'],
            'uploaded_by' => $photo_data['uploaded_by'],
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('job_photos', $data);
        return $this->db->insert_id();
    }


    /**
     * Count active jobs with filters
     * @param array $filters - Search and filter parameters
     * @return int
     */
    public function count_active_jobs($filters = [])
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return 0;
        }

        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.status', 'open');

        // Apply filters
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('j.title', $filters['search']);
            $this->db->or_like('j.description', $filters['search']);
            $this->db->or_like('j.address', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['min_price'])) {
            $this->db->where('j.suggested_price >=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $this->db->where('j.suggested_price <=', $filters['max_price']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('j.date_time >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('j.date_time <=', $filters['date_to']);
        }

        return $this->db->count_all_results();
    }

    /**
     * Get job by ID with host information
     * @param int $job_id
     * @return object|null
     */
    public function get_job_by_id($job_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return null;
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name, u.email as host_email');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.id', $job_id);
        
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get assigned jobs for a cleaner (only future jobs)
     */
    public function get_assigned_jobs_for_cleaner($cleaner_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name, u.email as host_email, u.phone as host_phone');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'assigned');
        
        // Only show future jobs or jobs scheduled for today
        $today = date('Y-m-d');
        $this->db->group_start();
        $this->db->where('j.scheduled_date >', $today);
        $this->db->or_where('j.scheduled_date', $today);
        $this->db->group_end();
        
        $this->db->order_by('j.scheduled_date', 'ASC');
        $this->db->order_by('j.scheduled_time', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Start a job with OTP validation
     */
    public function start_job_with_otp($job_id, $cleaner_id, $otp_code)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return false;
        }

        // Get job details
        $this->db->select('j.*');
        $this->db->from('jobs j');
        $this->db->where('j.id', $job_id);
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'assigned');
        $job = $this->db->get()->row();

        if (!$job) {
            return false;
        }

        // Validate OTP
        if ($job->otp_code !== $otp_code) {
            return false;
        }

        // Check if OTP is still valid (not used before)
        if (!empty($job->otp_used_at)) {
            return false;
        }

        // Start transaction
        $this->db->trans_start();

        // Update job status to active and mark OTP as used
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'status' => 'in_progress',
            'otp_used_at' => date('Y-m-d H:i:s'),
            'started_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Complete transaction
        $this->db->trans_complete();

        // Send notification if transaction was successful
        if ($this->db->trans_status()) {
            // Load notifications model
            $this->load->model('M_notifications');
            
            // Get host information for notification
            $host_info = $this->get_host_for_job($job_id);
            
            if ($host_info) {
                // Notify host that job has started
                $this->M_notifications->notify_job_started(
                    $host_info->host_id,
                    $host_info->job_title,
                    $host_info->cleaner_name,
                    [
                        'job_id' => $job_id,
                        'started_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
        }

        return $this->db->trans_status();
    }

    /**
     * Get host information for job notifications
     */
    public function get_host_for_job($job_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return false;
        }

        $this->db->select('j.id as job_id, j.title as job_title, j.host_id, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id');
        $this->db->where('j.id', $job_id);
        
        $query = $this->db->get();
        $result = $query->row();
        
        if ($result) {
            $result->cleaner_name = $result->cleaner_first_name . ' ' . $result->cleaner_last_name;
        }
        
        return $result;
    }

    /**
     * Get job history for a cleaner (completed jobs)
     */
    public function get_completed_jobs_for_cleaner($cleaner_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'completed');
        $this->db->order_by('j.updated_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }
}
