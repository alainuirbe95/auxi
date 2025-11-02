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
     * Get job statistics for admin dashboard
     */
    public function get_job_statistics() {
        $stats = array();
        
        if (!$this->db->table_exists('jobs')) {
            return $stats;
        }
        
        // Total jobs
        $stats['total_jobs'] = $this->db->count_all('jobs');
        
        // Jobs by status
        $this->db->reset_query();
        $columns = $this->db->list_fields('jobs');
        if (in_array('status', $columns)) {
            $this->db->select('status, COUNT(*) as count');
            $this->db->from('jobs');
            $this->db->group_by('status');
            $status_stats = $this->db->get()->result();
        } else {
            $status_stats = array();
        }
        
        $stats['by_status'] = array();
        foreach ($status_stats as $status_stat) {
            $stats['by_status'][$status_stat->status] = $status_stat->count;
        }
        
        // Recent jobs (last 30 days)
        $this->db->reset_query();
        if (in_array('created_at', $columns)) {
            $stats['recent_jobs'] = $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime('-30 days')))
                                             ->count_all_results('jobs', FALSE);
        } else {
            $stats['recent_jobs'] = 0;
        }
        
        // Active disputes - reset query first to avoid table alias conflict
        $this->db->reset_query();
        $stats['dispute_count'] = $this->get_dispute_count();
        
        return $stats;
    }
    
    /**
     * Get recent jobs
     */
    public function get_recent_jobs($limit = 5) {
        if (!$this->db->table_exists('jobs')) {
            return array();
        }
        
        $this->db->reset_query();
        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id', 'left');
        $this->db->order_by('j.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get count of active disputes
     */
    public function get_dispute_count() {
        if (!$this->db->table_exists('jobs')) {
            return 0;
        }
        
        // Check if dispute_status column exists
        $columns = $this->db->list_fields('jobs');
        if (!in_array('dispute_status', $columns)) {
            // If dispute_status column doesn't exist, check for other dispute-related fields
            if (in_array('status', $columns)) {
                $this->db->reset_query();
                $this->db->where('status', 'disputed');
                return $this->db->count_all_results('jobs');
            }
            return 0;
        }
        
        $this->db->reset_query();
        $this->db->where('dispute_status', 'active');
        return $this->db->count_all_results('jobs');
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
        
        // STR Filter: If cleaner doesn't offer STR services, exclude STR jobs
        if (isset($filters['cleaner_offers_str']) && !$filters['cleaner_offers_str']) {
            $this->db->group_start();
            $this->db->where('j.property_type !=', 'str');
            $this->db->or_where('j.property_type IS NULL');
            $this->db->or_where('j.property_type', 'residential');
            $this->db->group_end();
        }
        
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
                'live_disputes' => 0,
                'completed_jobs' => 0,
                'closed_jobs' => 0,
                'pending_offers' => 0,
                'pending_completed' => 0
            ];
        }
        
        $stats = [];
        
        // Total jobs
        $this->db->where('host_id', $host_id);
        $stats['total_jobs'] = $this->db->count_all_results('jobs');
        
        // Active jobs (open + assigned + in_progress + price_adjustment_requested)
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where_in('status', ['open', 'assigned', 'in_progress', 'price_adjustment_requested']);
        $stats['active_jobs'] = $this->db->count_all_results('jobs');
        
        // Live disputes
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'disputed');
        $stats['live_disputes'] = $this->db->count_all_results('jobs');
        
        // Completed jobs
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'completed');
        $stats['completed_jobs'] = $this->db->count_all_results('jobs');
        
        // Closed jobs
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'closed');
        $stats['closed_jobs'] = $this->db->count_all_results('jobs');
        
        // Pending offers (jobs with pending offers)
        $this->db->reset_query();
        if ($this->db->table_exists('offers')) {
            $this->db->select('COUNT(DISTINCT o.job_id) as pending_offers');
            $this->db->from('offers o');
            $this->db->join('jobs j', 'j.id = o.job_id');
            $this->db->where('j.host_id', $host_id);
            $this->db->where('j.status', 'open');
            $this->db->where('o.status', 'pending');
            $query = $this->db->get();
            $result = $query->row();
            $stats['pending_offers'] = $result->pending_offers ?: 0;
        } else {
            $stats['pending_offers'] = 0;
        }
        
        // Pending completed jobs (completed but not closed or disputed)
        $this->db->reset_query();
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'completed');
        $this->db->where('dispute_window_ends_at >', date('Y-m-d H:i:s')); // Still within dispute window
        $stats['pending_completed'] = $this->db->count_all_results('jobs');
        
        return $stats;
    }

    /**
     * Get total jobs count for host
     */
    public function get_total_jobs_for_host($host_id)
    {
        if (!$this->db->table_exists('jobs')) {
            return 0;
        }
        
        $this->db->where('host_id', $host_id);
        return $this->db->count_all_results('jobs');
    }

    /**
     * Get completed jobs count for host
     */
    public function get_completed_jobs_count_for_host($host_id)
    {
        if (!$this->db->table_exists('jobs')) {
            return 0;
        }
        
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'completed');
        return $this->db->count_all_results('jobs');
    }

    /**
     * Get pending offers for host dashboard
     */
    public function get_host_pending_offers($host_id, $limit = 10)
    {
        if (!$this->db->table_exists('offers')) {
            return [];
        }
        
        $this->db->select('o.*, j.title as job_title, j.suggested_price, u.username as cleaner_username, u.email as cleaner_email');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'j.id = o.job_id');
        $this->db->join('users u', 'u.user_id = o.cleaner_id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('j.status', 'open');
        $this->db->where('o.status', 'pending');
        $this->db->order_by('o.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Get pending completed jobs for host dashboard
     */
    public function get_host_pending_completed($host_id, $limit = 10)
    {
        $this->db->select('j.*, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name, u.username as cleaner_username');
        $this->db->from('jobs j');
        $this->db->join('users u', 'u.user_id = j.assigned_cleaner_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('j.status', 'completed');
        $this->db->where('j.dispute_window_ends_at >', date('Y-m-d H:i:s')); // Still within dispute window
        $this->db->order_by('j.completed_at', 'ASC'); // Oldest first (FIFO)
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }

    /**
     * Get host jobs with filtering, search, and sorting
     */
    public function get_host_jobs_filtered($host_id, $filters = [])
    {
        $this->db->select('j.*, 
                           COUNT(DISTINCT CASE WHEN o.status != "cancelled" THEN o.id END) as offer_count,
                           u.username as assigned_cleaner_name');
        $this->db->from('jobs j');
        $this->db->join('offers o', 'o.job_id = j.id', 'left');
        $this->db->join('users u', 'u.user_id = j.assigned_cleaner_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->group_by('j.id');
        
        // Apply filters
        if (!empty($filters['status'])) {
            $this->db->where('j.status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $search_term = $filters['search'];
            $this->db->group_start();
            $this->db->like('j.title', $search_term);
            $this->db->or_like('j.description', $search_term);
            $this->db->or_like('j.address', $search_term);
            $this->db->group_end();
        }
        
        if (!empty($filters['price_min'])) {
            $this->db->where('j.suggested_price >=', $filters['price_min']);
        }
        
        if (!empty($filters['price_max'])) {
            $this->db->where('j.suggested_price <=', $filters['price_max']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('j.scheduled_date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('j.scheduled_date <=', $filters['date_to']);
        }
        
        // Apply sorting
        $sort_by = $filters['sort_by'] ?? 'created_at';
        $sort_order = $filters['sort_order'] ?? 'DESC';
        
        // Map sort fields
        $sort_mapping = [
            'title' => 'j.title',
            'date' => 'j.scheduled_date',
            'price' => 'j.suggested_price',
            'status' => 'j.status',
            'offers' => 'offer_count',
            'created' => 'j.created_at',
            'updated' => 'j.updated_at'
        ];
        
        $sort_field = $sort_mapping[$sort_by] ?? 'j.created_at';
        $this->db->order_by($sort_field, $sort_order);
        
        return $this->db->get()->result();
    }

    /**
     * Get count of host jobs with filters
     */
    public function get_host_jobs_count_filtered($host_id, $filters = [])
    {
        $this->db->from('jobs j');
        $this->db->where('j.host_id', $host_id);
        
        // Apply filters
        if (!empty($filters['status'])) {
            $this->db->where('j.status', $filters['status']);
        }
        
        if (!empty($filters['search'])) {
            $search_term = $filters['search'];
            $this->db->group_start();
            $this->db->like('j.title', $search_term);
            $this->db->or_like('j.description', $search_term);
            $this->db->or_like('j.address', $search_term);
            $this->db->group_end();
        }
        
        if (!empty($filters['price_min'])) {
            $this->db->where('j.suggested_price >=', $filters['price_min']);
        }
        
        if (!empty($filters['price_max'])) {
            $this->db->where('j.suggested_price <=', $filters['price_max']);
        }
        
        if (!empty($filters['date_from'])) {
            $this->db->where('j.scheduled_date >=', $filters['date_from']);
        }
        
        if (!empty($filters['date_to'])) {
            $this->db->where('j.scheduled_date <=', $filters['date_to']);
        }
        
        return $this->db->count_all_results();
    }

    /**
     * Get host job status counts for filter buttons
     */
    public function get_host_job_status_counts($host_id)
    {
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('jobs');
        $this->db->where('host_id', $host_id);
        $this->db->group_by('status');
        
        $result = $this->db->get()->result();
        $counts = [];
        
        foreach ($result as $row) {
            $counts[$row->status] = $row->count;
        }
        
        return $counts;
    }

    /**
     * Get host's active jobs (today and future only)
     */
    public function get_host_active_jobs($host_id)
    {
        $this->db->select('*');
        $this->db->from('jobs');
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'open'); // Only open jobs
        $this->db->where('(scheduled_date >= CURDATE() OR scheduled_date IS NULL)'); // Today and future only
        $this->db->order_by('scheduled_date', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get host's expired jobs
     */
    public function get_host_expired_jobs($host_id)
    {
        $this->db->select('*');
        $this->db->from('jobs');
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'expired'); // Only expired jobs
        $this->db->order_by('scheduled_date', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Get completed jobs for host that need review/completion
     * Only shows 'completed' status - recalled jobs are shown separately
     */
    public function get_host_completed_jobs($host_id)
    {
        $this->db->select('j.*, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name, u.username as cleaner_username');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('j.status', 'completed'); // Only completed, not recalled
        $this->db->order_by('j.completed_at', 'DESC');
        
        return $this->db->get()->result();
    }

    /**
     * Auto-expire jobs that have passed their scheduled date without offers being accepted
     */
    public function auto_expire_jobs()
    {
        // Update jobs that are past their scheduled date and still open
        $this->db->where('status', 'open');
        $this->db->where('scheduled_date < CURDATE()');
        $this->db->where('scheduled_date IS NOT NULL');
        
        $result = $this->db->update('jobs', [
            'status' => 'expired',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        return $result;
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
                'open_jobs' => 0,
                'assigned_jobs' => 0,
                'in_progress_jobs' => 0,
                'completed_jobs' => 0,
                'recalled_jobs' => 0,
                'recall_settled_jobs' => 0,
                'closed_jobs' => 0,
                'cancelled_jobs' => 0,
                'expired_jobs' => 0,
                'active_jobs' => 0
            ];
        }
        
        $stats = [];
        
        // Total jobs
        $stats['total_jobs'] = $this->db->count_all('jobs');
        
        // Open jobs
        $this->db->reset_query();
        $this->db->where('status', 'open');
        $stats['open_jobs'] = $this->db->count_all_results('jobs');
        
        // Assigned jobs
        $this->db->reset_query();
        $this->db->where('status', 'assigned');
        $stats['assigned_jobs'] = $this->db->count_all_results('jobs');
        
        // In Progress jobs
        $this->db->reset_query();
        $this->db->where('status', 'in_progress');
        $stats['in_progress_jobs'] = $this->db->count_all_results('jobs');
        
        // Completed jobs
        $this->db->reset_query();
        $this->db->where('status', 'completed');
        $stats['completed_jobs'] = $this->db->count_all_results('jobs');
        
        // Recalled jobs
        $this->db->reset_query();
        $this->db->where('status', 'recalled');
        $stats['recalled_jobs'] = $this->db->count_all_results('jobs');
        
        // Recall Settled jobs
        $this->db->reset_query();
        $this->db->where('status', 'recall_settled');
        $stats['recall_settled_jobs'] = $this->db->count_all_results('jobs');
        
        // Closed jobs
        $this->db->reset_query();
        $this->db->where('status', 'closed');
        $stats['closed_jobs'] = $this->db->count_all_results('jobs');
        
        // Cancelled jobs
        $this->db->reset_query();
        $this->db->where('status', 'cancelled');
        $stats['cancelled_jobs'] = $this->db->count_all_results('jobs');
        
        // Expired jobs
        $this->db->reset_query();
        $this->db->where('status', 'expired');
        $stats['expired_jobs'] = $this->db->count_all_results('jobs');
        
        // Active jobs (open + assigned + in_progress)
        $stats['active_jobs'] = $stats['open_jobs'] + $stats['assigned_jobs'] + $stats['in_progress_jobs'];
        
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
     * @param int $cleaner_id - Optional cleaner ID to filter by service areas
     * @return int
     */
    public function count_active_jobs($filters = [], $cleaner_id = null)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return 0;
        }

        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.status', 'open');

        // Filter out past jobs - only show future jobs and current day jobs
        $today = date('Y-m-d');
        $this->db->group_start();
        $this->db->where('j.scheduled_date >', $today);
        $this->db->or_where('j.scheduled_date', $today);
        $this->db->or_where('j.scheduled_date IS NULL');
        $this->db->group_end();
        
        // STR Filter: If cleaner doesn't offer STR services, exclude STR jobs
        if (isset($filters['cleaner_offers_str']) && !$filters['cleaner_offers_str']) {
            $this->db->group_start();
            $this->db->where('j.property_type !=', 'str');
            $this->db->or_where('j.property_type IS NULL');
            $this->db->or_where('j.property_type', 'residential');
            $this->db->group_end();
        }

        // Apply filters
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('j.title', $filters['search']);
            $this->db->or_like('j.description', $filters['search']);
            $this->db->or_like('j.city', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['min_price'])) {
            $this->db->where('j.suggested_price >=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $this->db->where('j.suggested_price <=', $filters['max_price']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('j.scheduled_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('j.scheduled_date <=', $filters['date_to']);
        }

        // Filter by cleaner's service areas
        if ($cleaner_id && $this->db->table_exists('user_profiles')) {
            $this->load->model('M_user_profiles');
            $service_locations = $this->M_user_profiles->get_cleaner_service_locations($cleaner_id);
            
            if (!empty($service_locations) && !in_array('Other', $service_locations)) {
                // Build location conditions for the cleaner's service areas
                $location_conditions = [];
                foreach ($service_locations as $location) {
                    $location_parts = explode(', ', trim($location));
                    if (count($location_parts) == 2) {
                        $city = trim($location_parts[0]);
                        $state = trim($location_parts[1]);
                        $location_conditions[] = "(j.city = " . $this->db->escape($city) . " AND j.state = " . $this->db->escape($state) . ")";
                    }
                }
                
                if (!empty($location_conditions)) {
                    $this->db->group_start();
                    $this->db->where('(' . implode(' OR ', $location_conditions) . ')', null, false);
                    $this->db->group_end();
                }
            }
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

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name, u.email as host_email, u.phone as host_phone,
                          c.username as cleaner_username, c.first_name as cleaner_first_name, c.last_name as cleaner_last_name, c.email as cleaner_email, c.phone as cleaner_phone');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->join('users c', 'j.assigned_cleaner_id = c.user_id', 'left');
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
        $this->db->join('users u', 'j.host_id = u.user_id', 'left');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('j.status', ['assigned', 'in_progress', 'completed']);
        
        // Order by status priority (in_progress first, then completed, then assigned), then by date
        $this->db->order_by("FIELD(j.status, 'in_progress', 'completed', 'assigned')", '', FALSE);
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
            log_message('error', 'Jobs table does not exist in start_job_with_otp');
            return false;
        }

        // Log the attempt
        log_message('debug', "Starting job $job_id for cleaner $cleaner_id with OTP: $otp_code");

        // Get job details
        $this->db->select('j.*');
        $this->db->from('jobs j');
        $this->db->where('j.id', $job_id);
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'assigned');
        $job = $this->db->get()->row();

        if (!$job) {
            log_message('error', "Job $job_id not found or not assigned to cleaner $cleaner_id or not in 'assigned' status");
            log_message('debug', 'Query executed: ' . $this->db->last_query());
            return false;
        }

        log_message('debug', "Job found: ID=$job->id, Status=$job->status, AssignedCleaner=$job->assigned_cleaner_id, OTP=$job->otp_code");

        // Validate OTP
        if ($job->otp_code !== $otp_code) {
            log_message('error', "OTP mismatch for job $job_id. Expected: '$job->otp_code', Provided: '$otp_code'");
            return false;
        }

        // Check if OTP is still valid (not used before)
        if (!empty($job->otp_used_at)) {
            log_message('error', "OTP already used for job $job_id at: $job->otp_used_at");
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

        log_message('debug', "Transaction completed. Status: " . ($this->db->trans_status() ? 'SUCCESS' : 'FAILED'));

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

        $result = $this->db->trans_status();
        log_message('debug', "start_job_with_otp returning: " . ($result ? 'TRUE' : 'FALSE'));
        return $result;
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
     * Get job history for a cleaner (completed jobs only)
     */
    public function get_completed_jobs_for_cleaner($cleaner_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_name, u.email as host_email');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id', 'left');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        // Only show completed and recalled (pending recall) jobs
        // recall_settled jobs move to earnings only
        $this->db->where_in('j.status', ['completed', 'recalled']);
        $this->db->order_by('j.completed_at', 'DESC'); // Order by completion date
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get earnings data for cleaner within date range
     */
    public function get_cleaner_earnings($cleaner_id, $start_date = null, $end_date = null)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [
                'total_earnings' => 0,
                'total_jobs' => 0,
                'average_earnings' => 0,
                'jobs' => []
            ];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('j.status', ['completed', 'disputed', 'closed']);
        $this->db->where('j.payment_released_at IS NOT NULL'); // Only jobs with released payments

        // Apply date filters
        if ($start_date) {
            $this->db->where('j.payment_released_at >=', $start_date . ' 00:00:00');
        }
        if ($end_date) {
            $this->db->where('j.payment_released_at <=', $end_date . ' 23:59:59');
        }

        $this->db->order_by('j.payment_released_at', 'DESC');
        
        $query = $this->db->get();
        $jobs = $query->result();

        // Calculate totals
        $total_earnings = 0;
        $total_jobs = count($jobs);
        
        foreach ($jobs as $job) {
            $earnings = $job->payment_amount ?: ($job->final_price ?: $job->accepted_price);
            $total_earnings += (float)$earnings;
        }

        $average_earnings = $total_jobs > 0 ? $total_earnings / $total_jobs : 0;

        return [
            'total_earnings' => $total_earnings,
            'total_jobs' => $total_jobs,
            'average_earnings' => $average_earnings,
            'jobs' => $jobs
        ];
    }

    /**
     * Get earnings summary for cleaner (all time)
     */
    public function get_cleaner_earnings_summary($cleaner_id, $start_date = null, $end_date = null)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [
                'total_earnings' => 0,
                'total_jobs' => 0,
                'avg_earnings' => 0
            ];
        }

        // If date range is provided, calculate for that range
        // Otherwise, calculate all time totals
        $this->db->select('COUNT(*) as total_jobs, SUM(COALESCE(payment_amount, final_price, accepted_price, suggested_price)) as total_earnings');
        $this->db->from('jobs');
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('status', ['closed', 'recalled', 'recall_settled']); // Include recalled jobs
        
        // Date range filtering - use COALESCE for recalled jobs without payment_released_at
        if ($start_date) {
            $this->db->where('DATE(COALESCE(payment_released_at, updated_at)) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(COALESCE(payment_released_at, updated_at)) <=', $end_date);
        }
        
        $result = $this->db->get()->row();
        
        $total_earnings = (float)($result->total_earnings ?: 0);
        $total_jobs = (int)($result->total_jobs ?: 0);
        $avg_earnings = $total_jobs > 0 ? ($total_earnings / $total_jobs) : 0;

        return [
            'total_earnings' => $total_earnings,
            'total_jobs' => $total_jobs,
            'avg_earnings' => $avg_earnings
        ];
    }

    /**
     * Report job inconsistency before completion
     */
    public function report_job_inconsistency($job_id, $cleaner_id, $inconsistency_data)
    {
        // Check if job inconsistencies table exists
        if (!$this->db->table_exists('job_inconsistencies')) {
            log_message('error', 'Job inconsistencies table does not exist');
            return false;
        }

        // Validate job ownership and status
        $job = $this->get_job_by_id($job_id);
        if (!$job || $job->assigned_cleaner_id != $cleaner_id || $job->status != 'in_progress') {
            log_message('error', 'Invalid job for inconsistency reporting');
            return false;
        }

        $data = [
            'job_id' => $job_id,
            'cleaner_id' => $cleaner_id,
            'inconsistency_type' => $inconsistency_data['inconsistency_type'],
            'description' => $inconsistency_data['description'],
            'photos' => isset($inconsistency_data['photos']) ? json_encode($inconsistency_data['photos']) : null,
            'severity' => $inconsistency_data['severity'],
            'reported_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->db->insert('job_inconsistencies', $data);
        
        if ($result) {
            $inconsistency_id = $this->db->insert_id();
            log_message('debug', 'Job inconsistency reported with ID: ' . $inconsistency_id);
            
            // Notify host about the inconsistency
            $this->load->model('M_notifications');
            $this->M_notifications->notify_job_inconsistency(
                $job->host_id,
                $job->title,
                $inconsistency_data['description'],
                [
                    'job_id' => $job_id,
                    'inconsistency_id' => $inconsistency_id,
                    'severity' => $inconsistency_data['severity']
                ]
            );
            
            return $inconsistency_id;
        }

        return false;
    }

    /**
     * Get job inconsistencies for a specific job
     */
    public function get_job_inconsistencies($job_id)
    {
        // Check if job inconsistencies table exists
        if (!$this->db->table_exists('job_inconsistencies')) {
            return [];
        }

        $this->db->select('ji.*, u.first_name, u.last_name');
        $this->db->from('job_inconsistencies ji');
        $this->db->join('users u', 'ji.cleaner_id = u.user_id');
        $this->db->where('ji.job_id', $job_id);
        $this->db->order_by('ji.reported_at', 'DESC');
        
        $query = $this->db->get();
        $result = $query->result();
        
        // Decode photos JSON for each inconsistency
        foreach ($result as $inconsistency) {
            if ($inconsistency->photos) {
                $inconsistency->photos = json_decode($inconsistency->photos, true);
            }
        }
        
        return $result;
    }

    /**
     * Complete a job with optional inconsistencies
     */
    public function complete_job($job_id, $cleaner_id, $completion_data = [])
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            log_message('error', 'Jobs table does not exist');
            return false;
        }

        // Validate job ownership and status
        $job = $this->get_job_by_id($job_id);
        if (!$job || $job->assigned_cleaner_id != $cleaner_id || $job->status != 'in_progress') {
            log_message('error', 'Invalid job for completion');
            return false;
        }

        // Start transaction
        $this->db->trans_start();

        // Update job status to completed and set dispute window
        $update_data = [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'dispute_window_ends_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Add completion notes if provided
        if (isset($completion_data['completion_notes'])) {
            $update_data['completion_notes'] = $completion_data['completion_notes'];
        }

        // Format and handle final price - if different from accepted price, create counter-offer
        if (isset($completion_data['final_price']) && !empty($completion_data['final_price'])) {
            // Ensure final price is properly formatted as decimal
            $final_price = number_format((float)$completion_data['final_price'], 2, '.', '');
            
            if ($final_price != $job->accepted_price) {
                // Create counter-offer instead of directly updating final price
                $this->load->model('M_counter_offers');
                $counter_offer_id = $this->M_counter_offers->create_counter_offer(
                    $job_id,
                    $cleaner_id,
                    $job->host_id,
                    $job->accepted_price,
                    $final_price,
                    isset($completion_data['price_reason']) ? $completion_data['price_reason'] : null
                );
                
                if ($counter_offer_id) {
                    // Update job status to price_adjustment_requested
                    $update_data['status'] = 'price_adjustment_requested';
                    
                    // Send notification to host about counter-offer
                    $this->load->model('M_notifications');
                    $this->M_notifications->notify_counter_offer(
                        $job->host_id,
                        $job->title,
                        $job->accepted_price,
                        $final_price,
                        [
                            'job_id' => $job_id,
                            'counter_offer_id' => $counter_offer_id,
                            'cleaner_id' => $cleaner_id
                        ]
                    );
                    
                    // Update the job with the new status
                    $this->db->where('id', $job_id);
                    $this->db->update('jobs', $update_data);
                    
                    $this->db->trans_complete();
                    return 'counter_offer_created';
                }
            } else {
                // Use accepted price as final price (formatted)
                $update_data['final_price'] = $final_price;
            }
        } else {
            // Use accepted price as final price
            $update_data['final_price'] = $job->accepted_price;
        }

        $this->db->where('id', $job_id);
        $this->db->update('jobs', $update_data);

        // Complete transaction
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            // Load notifications model
            $this->load->model('M_notifications');
            
            // Get cleaner information for notification
            $cleaner_info = $this->get_cleaner_for_job($job_id);
            
            if ($cleaner_info) {
                // Notify host that job has been completed
                $this->M_notifications->notify_job_completed(
                    $job->host_id,
                    $job->title,
                    $cleaner_info->cleaner_name,
                    [
                        'job_id' => $job_id,
                        'completed_at' => date('Y-m-d H:i:s'),
                        'has_inconsistencies' => $this->has_job_inconsistencies($job_id)
                    ]
                );
            }

            log_message('debug', 'Job completed successfully: ' . $job_id);
            return true;
        }

        return false;
    }

    /**
     * Get cleaner information for job notifications
     */
    public function get_cleaner_for_job($job_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return false;
        }

        $this->db->select('j.id as job_id, j.title as job_title, j.assigned_cleaner_id, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name');
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
     * Check if job has reported inconsistencies
     */
    public function has_job_inconsistencies($job_id)
    {
        // Check if job inconsistencies table exists
        if (!$this->db->table_exists('job_inconsistencies')) {
            return false;
        }

        $this->db->where('job_id', $job_id);
        $count = $this->db->count_all_results('job_inconsistencies');
        
        return $count > 0;
    }

    /**
     * Get jobs ready for completion (in_progress status)
     */
    public function get_jobs_ready_for_completion($cleaner_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name, u.email as host_email, u.phone as host_phone');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'in_progress');
        $this->db->order_by('j.started_at', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Check if cleaner can complete job
     */
    public function can_complete_job($job_id, $cleaner_id)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return false;
        }

        $this->db->where('id', $job_id);
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where('status', 'in_progress');
        
        return $this->db->count_all_results('jobs') > 0;
    }

    /**
     * Dispute a completed job
     */
    public function dispute_job($job_id, $host_id, $dispute_reason)
    {
        // Add debug logging
        log_message('debug', "Dispute Job Debug - Job ID: $job_id, Host ID: $host_id");
        
        // Verify job ownership and status
        $job = $this->get_job_by_id($job_id);
        if (!$job || $job->host_id != $host_id || $job->status != 'completed') {
            log_message('debug', "Dispute Job Debug - Job validation failed: " . ($job ? "host_id mismatch or status not completed" : "job not found"));
            return false;
        }

        // Add debug logging for dispute window check
        $current_time = time();
        $dispute_end_time = $job->dispute_window_ends_at ? strtotime($job->dispute_window_ends_at) : 0;
        
        // Convert to PST for debugging
        $current_pst = date('Y-m-d H:i:s T', $current_time);
        $dispute_end_pst = $dispute_end_time ? date('Y-m-d H:i:s T', $dispute_end_time) : 'NULL';
        
        log_message('debug', "Dispute Job Debug - Current time: $current_time ($current_pst), Dispute end time: $dispute_end_time ($dispute_end_pst), Window ends at: {$job->dispute_window_ends_at}");
        
        // Check if dispute window is still open
        if ($job->dispute_window_ends_at && $dispute_end_time < $current_time) {
            log_message('debug', "Dispute Job Debug - Dispute window expired. Current: $current_time, End: $dispute_end_time");
            return false; // Dispute window has expired
        }

        $this->db->trans_start();

        // Update job status to disputed
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'status' => 'disputed',
            'disputed_at' => date('Y-m-d H:i:s'),
            'dispute_reason' => $dispute_reason,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            // Send notification to cleaner
            $this->load->model('M_notifications');
            $cleaner_info = $this->get_cleaner_for_job($job_id);
            
            if ($cleaner_info) {
                $this->M_notifications->notify_job_disputed(
                    $cleaner_info->assigned_cleaner_id,
                    $job->title,
                    $dispute_reason,
                    [
                        'job_id' => $job_id,
                        'disputed_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
        }

        return $this->db->trans_status();
    }

    /**
     * Resolve a disputed job
     */
    public function resolve_dispute($job_id, $moderator_id, $resolution, $final_amount = null)
    {
        // Verify job is disputed
        $job = $this->get_job_by_id($job_id);
        if (!$job || $job->status != 'disputed') {
            return false;
        }

        $this->db->trans_start();

        // Determine final amount based on resolution
        if (!$final_amount) {
            switch ($resolution) {
                case 'resolved_in_favor_cleaner':
                    $final_amount = $job->final_price ?: $job->accepted_price;
                    break;
                case 'resolved_in_favor_host':
                    $final_amount = 0; // No payment to cleaner
                    break;
                case 'compromise':
                    $final_amount = $final_amount ?: ($job->final_price ?: $job->accepted_price) * 0.5;
                    break;
            }
        }
        
        // Format final amount as decimal
        $final_amount = number_format((float)$final_amount, 2, '.', '');

        // Update job status based on resolution
        $new_status = ($final_amount > 0) ? 'completed' : 'closed';
        
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'status' => $new_status,
            'dispute_resolved_at' => date('Y-m-d H:i:s'),
            'dispute_resolution' => $resolution,
            'payment_amount' => $final_amount,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status() && $final_amount > 0) {
            // Release payment to cleaner
            $this->release_payment($job_id, $final_amount);
        }

        return $this->db->trans_status();
    }

    /**
     * Release payment to cleaner
     */
    public function release_payment($job_id, $amount)
    {
        $job = $this->get_job_by_id($job_id);
        if (!$job) {
            return false;
        }

        // Format amount as decimal
        $amount = number_format((float)$amount, 2, '.', '');

        $this->db->trans_start();

        // Update job with payment release
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'payment_released_at' => date('Y-m-d H:i:s'),
            'payment_amount' => $amount,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Here you would integrate with your payment system
        // For now, we'll just log it
        log_message('info', "Payment of $amount released for job $job_id to cleaner {$job->assigned_cleaner_id}");

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            // Send notification to cleaner
            $this->load->model('M_notifications');
            $cleaner_info = $this->get_cleaner_for_job($job_id);
            
            if ($cleaner_info) {
                $this->M_notifications->notify_payment_released(
                    $cleaner_info->assigned_cleaner_id,
                    $job->title,
                    $amount,
                    [
                        'job_id' => $job_id,
                        'released_at' => date('Y-m-d H:i:s')
                    ]
                );
            }
        }

        return $this->db->trans_status();
    }

    /**
     * Auto-close jobs after dispute window expires
     */
    public function auto_close_completed_jobs()
    {
        // Find jobs that are completed and dispute window has expired
        $this->db->where('status', 'completed');
        $this->db->where('dispute_window_ends_at <=', date('Y-m-d H:i:s'));
        $this->db->where('payment_released_at IS NULL');
        
        $jobs = $this->db->get('jobs')->result();

        $closed_count = 0;
        foreach ($jobs as $job) {
            // Format payment amount as decimal
            $payment_amount = number_format((float)($job->final_price ?: $job->accepted_price), 2, '.', '');
            
            // Release payment and close job
            $this->release_payment($job->id, $payment_amount);
            
            // Update status to closed
            $this->db->where('id', $job->id);
            $this->db->update('jobs', [
                'status' => 'closed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $closed_count++;
        }

        return $closed_count;
    }

    /**
     * Get jobs ready for dispute (completed within dispute window)
     */
    public function get_jobs_ready_for_dispute($host_id)
    {
        $this->db->select('j.*, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'u.user_id = j.assigned_cleaner_id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('j.status', 'completed');
        $this->db->where('j.dispute_window_ends_at >', date('Y-m-d H:i:s'));
        $this->db->order_by('j.completed_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get disputed jobs for moderators
     */
    public function get_disputed_jobs()
    {
        $this->db->select('j.*, 
                          u.first_name as cleaner_first_name, u.last_name as cleaner_last_name,
                          h.first_name as host_first_name, h.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'u.user_id = j.assigned_cleaner_id');
        $this->db->join('users h', 'h.user_id = j.host_id');
        $this->db->where('j.status', 'disputed');
        $this->db->order_by('j.disputed_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Check if job can be disputed
     */
    public function can_dispute_job($job_id, $host_id)
    {
        $job = $this->get_job_by_id($job_id);
        if (!$job || $job->host_id != $host_id || $job->status != 'completed') {
            return false;
        }
        
        // Check if dispute window is still open
        if ($job->dispute_window_ends_at && strtotime($job->dispute_window_ends_at) < time()) {
            return false;
        }
        
        return true;
    }

    /**
     * Get jobs with price adjustment requests for a host
     */
    public function get_price_adjustment_jobs($host_id)
    {
        $this->db->select('j.*, co.id as counter_offer_id, co.original_price, co.proposed_price, 
                           co.reason as price_reason, co.created_at as adjustment_requested_at, co.expires_at,
                           c.first_name as cleaner_first_name, c.last_name as cleaner_last_name');
        $this->db->from('jobs j');
        $this->db->join('counter_offers co', 'co.job_id = j.id AND co.status = "pending"');
        $this->db->join('users c', 'c.user_id = j.assigned_cleaner_id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('j.status', 'price_adjustment_requested');
        $this->db->order_by('co.created_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get escalated counter-offer jobs for moderators
     */
    public function get_escalated_price_adjustments()
    {
        $this->db->select('j.*, co.id as counter_offer_id, co.original_price, co.proposed_price, 
                           co.reason as price_reason, co.escalated_at, co.host_response,
                           h.first_name as host_first_name, h.last_name as host_last_name,
                           c.first_name as cleaner_first_name, c.last_name as cleaner_last_name');
        $this->db->from('jobs j');
        $this->db->join('counter_offers co', 'co.job_id = j.id AND co.status = "escalated"');
        $this->db->join('users h', 'h.user_id = j.host_id');
        $this->db->join('users c', 'c.user_id = j.assigned_cleaner_id');
        $this->db->where('j.status', 'price_adjustment_requested');
        $this->db->order_by('co.escalated_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get disputed jobs for admin management
     */
    public function get_disputed_jobs_for_admin()
    {
        $this->db->select('j.*, h.username as host_name, h.email as host_email, c.username as cleaner_name, c.email as cleaner_email');
        $this->db->from('jobs j');
        $this->db->join('users h', 'j.host_id = h.user_id', 'left');
        $this->db->join('users c', 'j.assigned_cleaner_id = c.user_id', 'left');
        $this->db->where('j.status', 'disputed');
        $this->db->order_by('j.disputed_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get dispute details for admin
     */
    public function get_dispute_details($job_id)
    {
        $this->db->select('j.*, h.username as host_name, h.email as host_email, h.phone as host_phone, c.username as cleaner_name, c.email as cleaner_email, c.phone as cleaner_phone');
        $this->db->from('jobs j');
        $this->db->join('users h', 'j.host_id = h.user_id', 'left');
        $this->db->join('users c', 'j.assigned_cleaner_id = c.user_id', 'left');
        $this->db->where('j.id', $job_id);
        $this->db->where('j.status', 'disputed');
        
        return $this->db->get()->row();
    }

    /**
     * Get host's disputed jobs
     */
    public function get_host_disputed_jobs($host_id)
    {
        $this->db->select('j.*, c.username as cleaner_name, c.email as cleaner_email');
        $this->db->from('jobs j');
        $this->db->join('users c', 'j.assigned_cleaner_id = c.user_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('j.status', 'disputed');
        $this->db->order_by('j.disputed_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get cleaner's disputed jobs
     */
    public function get_cleaner_disputed_jobs($cleaner_id)
    {
        $this->db->select('j.*, h.username as host_name, h.email as host_email');
        $this->db->from('jobs j');
        $this->db->join('users h', 'j.host_id = h.user_id', 'left');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'disputed');
        $this->db->order_by('j.disputed_at', 'ASC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Get total jobs for cleaner
     */
    public function get_total_jobs_for_cleaner($cleaner_id)
    {
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('status', ['assigned', 'in_progress', 'completed', 'disputed', 'closed']);
        return $this->db->count_all_results('jobs');
    }
    
    /**
     * Get active jobs for cleaner
     */
    public function get_active_jobs_for_cleaner($cleaner_id)
    {
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('status', ['assigned', 'in_progress']);
        return $this->db->count_all_results('jobs');
    }
    
    /**
     * Get completed jobs count for cleaner
     */
    public function get_completed_jobs_count_for_cleaner($cleaner_id)
    {
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where('status', 'completed');
        return $this->db->count_all_results('jobs');
    }
    
    /**
     * Get total completed jobs count for cleaner (includes both completed and closed)
     */
    public function get_total_completed_jobs_count_for_cleaner($cleaner_id)
    {
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('status', ['completed', 'closed']);
        return $this->db->count_all_results('jobs');
    }
    
    /**
     * Get disputed jobs count for cleaner
     */
    public function get_disputed_jobs_count_for_cleaner($cleaner_id)
    {
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where('status', 'disputed');
        return $this->db->count_all_results('jobs');
    }
    
    /**
     * Get closed jobs count for cleaner
     */
    public function get_closed_jobs_count_for_cleaner($cleaner_id)
    {
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where('status', 'closed');
        return $this->db->count_all_results('jobs');
    }
    
    /**
     * Get recent jobs for cleaner
     */
    public function get_recent_jobs_for_cleaner($cleaner_id, $limit = 5)
    {
        $this->db->select('j.*, h.username as host_name');
        $this->db->from('jobs j');
        $this->db->join('users h', 'h.user_id = j.host_id', 'left');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('j.status', ['assigned', 'in_progress', 'completed', 'disputed', 'closed']);
        $this->db->order_by('j.created_at', 'DESC');
        $this->db->limit($limit);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get pending price adjustments for cleaner
     */
    public function get_pending_price_adjustments_for_cleaner($cleaner_id)
    {
        $this->db->select('co.*, j.title as job_title, j.suggested_price');
        $this->db->from('counter_offers co');
        $this->db->join('jobs j', 'j.id = co.job_id');
        $this->db->where('co.cleaner_id', $cleaner_id);
        $this->db->where('co.status', 'pending');
        $this->db->order_by('co.created_at', 'DESC');
        
        return $this->db->get()->result();
    }
    
    /**
     * Get total earnings for cleaner
     */
    public function get_total_earnings_for_cleaner($cleaner_id)
    {
        $this->db->select('SUM(COALESCE(payment_amount, final_price, suggested_price)) as total_earnings');
        $this->db->from('jobs');
        $this->db->where('assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('status', ['completed', 'closed']);
        
        $result = $this->db->get()->row();
        return $result ? $result->total_earnings : 0;
    }
    
    /**
     * Get potential earnings for cleaner (sum of completed jobs only)
     */
    public function get_potential_earnings_for_cleaner($cleaner_id)
    {
        // Calculate potential earnings from assigned and in-progress jobs
        // Use accepted_price (counter offer) or final_price if exists, otherwise suggested_price
        $this->db->select('
            SUM(
                COALESCE(
                    j.final_price,
                    j.accepted_price,
                    j.suggested_price
                )
            ) as potential_earnings
        ', false);
        $this->db->from('jobs j');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('j.status', ['assigned', 'in_progress']);
        
        $result = $this->db->get()->row();
        return $result ? (float)$result->potential_earnings : 0;
    }
    
    /**
     * Get closed jobs for cleaner with date filtering
     */
    public function get_closed_jobs_for_cleaner($cleaner_id, $start_date = null, $end_date = null)
    {
        $this->db->select('j.*, h.username as host_name, h.email as host_email', false);
        $this->db->from('jobs j');
        $this->db->join('users h', 'h.user_id = j.host_id', 'left');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('j.status', ['closed', 'recalled', 'recall_settled']); // Include recalled jobs
        
        // Date range filtering - use COALESCE for recalled jobs without payment_released_at
        if ($start_date) {
            $this->db->where('DATE(COALESCE(j.payment_released_at, j.updated_at)) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(COALESCE(j.payment_released_at, j.updated_at)) <=', $end_date);
        }
        
        // Fix ORDER BY - can't use DESC inside COALESCE
        $this->db->order_by('IF(j.payment_released_at IS NOT NULL, j.payment_released_at, j.updated_at)', 'DESC', false);
        
        return $this->db->get()->result();
    }
    
    /**
     * Get completed jobs for cleaner with filtering and sorting
     */
    public function get_completed_jobs_for_cleaner_filtered($cleaner_id, $start_date = null, $end_date = null, $sort_by = 'completed_at', $sort_order = 'DESC', $search = null)
    {
        $this->db->select('j.*, h.username as host_name, h.email as host_email');
        $this->db->from('jobs j');
        $this->db->join('users h', 'h.user_id = j.host_id', 'left');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('j.status', ['completed', 'disputed', 'closed']);
        
        // Date range filtering
        if ($start_date) {
            $this->db->where('DATE(j.completed_at) >=', $start_date);
        }
        if ($end_date) {
            $this->db->where('DATE(j.completed_at) <=', $end_date);
        }
        
        // Search filtering
        if ($search) {
            $this->db->group_start();
            $this->db->like('j.title', $search);
            $this->db->or_like('j.description', $search);
            $this->db->or_like('h.username', $search);
            $this->db->group_end();
        }
        
        // Sorting
        $allowed_sort_fields = ['completed_at', 'title', 'suggested_price', 'status', 'created_at'];
        if (in_array($sort_by, $allowed_sort_fields)) {
            $this->db->order_by('j.' . $sort_by, $sort_order);
        } else {
            $this->db->order_by('j.completed_at', 'DESC');
        }
        
        return $this->db->get()->result();
    }

    /**
     * Resolve dispute with percentage-based payout
     */
    public function resolve_dispute_with_percentage($job_id, $moderator_id, $cleaner_percentage, $resolution_notes = null)
    {
        $job = $this->get_job_by_id($job_id);
        if (!$job || $job->status != 'disputed') {
            return false;
        }

        $this->db->trans_start();

        // Calculate amounts
        $original_amount = $job->final_price ?: $job->accepted_price;
        $cleaner_amount = number_format((float)($original_amount * $cleaner_percentage / 100), 2, '.', '');
        $host_refund = number_format((float)($original_amount - $cleaner_amount), 2, '.', '');

        // Determine resolution type based on percentage
        $dispute_resolution = 'compromise'; // Default
        if ($cleaner_percentage >= 75) {
            $dispute_resolution = 'resolved_in_favor_cleaner';
        } elseif ($cleaner_percentage <= 25) {
            $dispute_resolution = 'resolved_in_favor_host';
        }

        // Update job status
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'status' => 'closed',
            'dispute_resolved_at' => date('Y-m-d H:i:s'),
            'dispute_resolution' => $dispute_resolution,
            'dispute_resolution_notes' => $resolution_notes,
            'payment_amount' => $cleaner_amount,
            'payment_released_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            // Send notifications
            $this->load->model('M_notifications');
            
            // Notify host about resolution
            $this->M_notifications->notify_dispute_resolved(
                $job->host_id,
                $job->title,
                $dispute_resolution,
                $host_refund,
                [
                    'job_id' => $job_id,
                    'cleaner_percentage' => $cleaner_percentage,
                    'host_refund' => $host_refund,
                    'cleaner_amount' => $cleaner_amount,
                    'resolution_notes' => $resolution_notes
                ]
            );

            // Notify cleaner about resolution
            $this->M_notifications->notify_dispute_resolved(
                $job->assigned_cleaner_id,
                $job->title,
                $dispute_resolution,
                $cleaner_amount,
                [
                    'job_id' => $job_id,
                    'cleaner_percentage' => $cleaner_percentage,
                    'host_refund' => $host_refund,
                    'cleaner_amount' => $cleaner_amount,
                    'resolution_notes' => $resolution_notes
                ]
            );

            return true;
        }

        return false;
    }

    /**
     * Get price adjustment statistics for host dashboard
     */
    public function get_price_adjustment_stats($host_id)
    {
        // Check if counter_offers table exists
        if (!$this->db->table_exists('counter_offers')) {
            return (object)[
                'total_requests' => 0,
                'pending_requests' => 0,
                'approved_requests' => 0,
                'escalated_requests' => 0,
                'total_additional_amount' => 0,
                'average_adjustment' => 0,
                'disputed_requests' => 0
            ];
        }

        $stats = [];

        // Total requests
        $this->db->where('host_id', $host_id);
        $stats['total_requests'] = $this->db->count_all_results('counter_offers');

        // Pending requests
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'pending');
        $stats['pending_requests'] = $this->db->count_all_results('counter_offers');

        // Approved requests
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'approved');
        $stats['approved_requests'] = $this->db->count_all_results('counter_offers');

        // Escalated requests
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'escalated');
        $stats['escalated_requests'] = $this->db->count_all_results('counter_offers');

        // Disputed requests
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'disputed');
        $stats['disputed_requests'] = $this->db->count_all_results('counter_offers');

        // Total additional amount (approved requests)
        $this->db->select('SUM(proposed_price - original_price) as total_additional');
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'approved');
        $result = $this->db->get('counter_offers')->row();
        $stats['total_additional_amount'] = $result ? $result->total_additional : 0;

        // Average adjustment amount
        $this->db->select('AVG(proposed_price - original_price) as avg_adjustment');
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'approved');
        $result = $this->db->get('counter_offers')->row();
        $stats['average_adjustment'] = $result ? $result->avg_adjustment : 0;

        return (object)$stats;
    }

    /**
     * Get filtered price adjustment jobs for host
     */
    public function get_price_adjustment_jobs_filtered($host_id, $filters = [])
    {
        // Check if counter_offers table exists
        if (!$this->db->table_exists('counter_offers')) {
            return [];
        }

        $this->db->select('
            co.id as counter_offer_id,
            co.job_id,
            co.cleaner_id,
            co.host_id,
            co.original_price,
            co.proposed_price,
            co.reason,
            co.status,
            co.host_response,
            co.escalated_at,
            co.moderator_id,
            co.moderator_decision,
            co.moderator_notes,
            co.final_price,
            co.expires_at,
            co.created_at,
            co.updated_at,
            co.host_dispute_reason,
            co.host_dispute_details,
            co.disputed_by_host,
            j.title,
            j.description,
            j.address,
            j.scheduled_date,
            j.scheduled_time,
            j.final_price as job_final_price,
            u.username as cleaner_username,
            u.first_name as cleaner_first_name,
            u.last_name as cleaner_last_name,
            u.email as cleaner_email
        ');
        $this->db->from('counter_offers co');
        $this->db->join('jobs j', 'co.job_id = j.id');
        $this->db->join('users u', 'co.cleaner_id = u.user_id');
        $this->db->where('co.host_id', $host_id);

        // Apply filters
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('j.title', $search);
            $this->db->or_like('j.description', $search);
            $this->db->or_like('u.first_name', $search);
            $this->db->or_like('u.last_name', $search);
            $this->db->or_like('co.reason', $search);
            $this->db->group_end();
        }

        if (!empty($filters['status'])) {
            $this->db->where('co.status', $filters['status']);
        }

        if (!empty($filters['price_min'])) {
            $this->db->where('co.proposed_price >=', $filters['price_min']);
        }

        if (!empty($filters['price_max'])) {
            $this->db->where('co.proposed_price <=', $filters['price_max']);
        }

        // Apply sorting
        $sort_by = $filters['sort_by'] ?? 'created_at';
        $sort_order = $filters['sort_order'] ?? 'desc';
        
        $allowed_sort_fields = ['created_at', 'proposed_price', 'original_price', 'expires_at', 'status'];
        if (in_array($sort_by, $allowed_sort_fields)) {
            $this->db->order_by('co.' . $sort_by, strtoupper($sort_order));
        } else {
            $this->db->order_by('co.created_at', 'DESC');
        }

        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get host's closed jobs with payment information
     */
    public function get_host_past_jobs($host_id, $filters = [])
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        // Select all job fields including recall and settlement fields
        $this->db->select('j.*, u.username as cleaner_username, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->where_in('j.status', ['closed', 'recalled', 'recall_settled']);
        
        // Check which columns exist
        $columns = $this->db->list_fields('jobs');
        
        // Apply date filters
        // For closed jobs: use payment_released_at
        // For recalled jobs: use updated_at (since they might not have payment_released_at)
        if (!empty($filters['date_from'])) {
            if (in_array('payment_released_at', $columns)) {
                $this->db->where("(DATE(COALESCE(j.payment_released_at, j.updated_at)) >= '{$filters['date_from']}')", NULL, FALSE);
            } else {
                $this->db->where('DATE(j.updated_at) >=', $filters['date_from']);
            }
        }
        if (!empty($filters['date_to'])) {
            if (in_array('payment_released_at', $columns)) {
                $this->db->where("(DATE(COALESCE(j.payment_released_at, j.updated_at)) <= '{$filters['date_to']}')", NULL, FALSE);
            } else {
                $this->db->where('DATE(j.updated_at) <=', $filters['date_to']);
            }
        }
        
        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $this->db->group_start();
            $this->db->like('j.title', $search);
            $this->db->or_like('j.description', $search);
            $this->db->or_like('j.address', $search);
            $this->db->or_like('u.first_name', $search);
            $this->db->or_like('u.last_name', $search);
            $this->db->group_end();
        }
        
        // Apply status filter
        if (!empty($filters['status'])) {
            $this->db->where('j.status', $filters['status']);
        }
        
        // Order by payment_released_at if available, otherwise updated_at
        if (in_array('payment_released_at', $columns)) {
            $this->db->order_by('j.payment_released_at', 'DESC');
        } else {
            $this->db->order_by('j.updated_at', 'DESC');
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get host's recalled jobs with filtering
     */
    public function get_host_recalled_jobs($host_id, $filters = [])
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, u.username as cleaner_username, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->where_in('j.status', ['recalled', 'recall_settled']);
        
        // Check which columns exist
        $columns = $this->db->list_fields('jobs');
        
        // Apply filters - only if columns exist
        if (!empty($filters['reason']) && in_array('recall_reason', $columns)) {
            $this->db->where('j.recall_reason', $filters['reason']);
        }
        
        if (!empty($filters['severity']) && in_array('recall_severity', $columns)) {
            $this->db->where('j.recall_severity', $filters['severity']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            // Check which columns exist
            $columns = $this->db->list_fields('jobs');
            
            $this->db->group_start();
            $this->db->like('j.title', $search);
            $this->db->or_like('j.description', $search);
            
            // Only search in recall_details if column exists
            if (in_array('recall_details', $columns)) {
                $this->db->or_like('j.recall_details', $search);
            }
            
            $this->db->or_like('u.first_name', $search);
            $this->db->or_like('u.last_name', $search);
            $this->db->group_end();
        }
        
        // Apply sorting
        $sort_by = $filters['sort'] ?? 'recalled_at';
        $sort_order = 'DESC';
        
        // Check which columns exist in the jobs table
        $columns = $this->db->list_fields('jobs');
        
        switch ($sort_by) {
            case 'title':
                $this->db->order_by('j.title', 'ASC');
                break;
            case 'severity':
                // Only sort by severity if column exists
                if (in_array('recall_severity', $columns)) {
                    $this->db->order_by('j.recall_severity', 'DESC');
                } else {
                    $this->db->order_by('j.updated_at', 'DESC');
                }
                break;
            default:
                // Only sort by recalled_at if column exists, otherwise use updated_at
                if (in_array('recalled_at', $columns)) {
                    $this->db->order_by('j.recalled_at', 'DESC');
                } else {
                    $this->db->order_by('j.updated_at', 'DESC');
                }
                break;
        }
        
        $query = $this->db->get();
        $results = $query->result();
        
        // Add cleaner name, host name and recall status to each result
        foreach ($results as $job) {
            $cleaner_name = trim(($job->cleaner_first_name ?? '') . ' ' . ($job->cleaner_last_name ?? ''));
            if (empty($cleaner_name)) {
                $cleaner_name = $job->cleaner_username ?? 'Unknown Cleaner';
            }
            $job->cleaner_name = $cleaner_name;
            
            $host_name = trim(($job->host_first_name ?? '') . ' ' . ($job->host_last_name ?? ''));
            if (empty($host_name)) {
                $host_name = $job->host_username ?? 'Unknown Host';
            }
            $job->host_name = $host_name;
            
            // Set default recall status if not set
            $job->recall_status = $job->recall_status ?? 'pending';
        }
        
        return $results;
    }

    /**
     * Get recalled jobs for a specific cleaner
     */
    public function get_cleaner_recalled_jobs($cleaner_id, $filters = [])
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        // Select all job fields and host info
        $this->db->select('j.*, 
                          h.username as host_username,
                          h.first_name as host_first_name,
                          h.last_name as host_last_name,
                          h.phone as host_phone,
                          h.email as host_email', false);
        $this->db->from('jobs j');
        $this->db->join('users h', 'j.host_id = h.user_id', 'left');
        $this->db->where('j.assigned_cleaner_id', $cleaner_id);
        $this->db->where_in('j.status', ['recalled', 'recall_settled']);
        
        // Check which columns exist
        $columns = $this->db->list_fields('jobs');
        
        // Apply filters - only if columns exist
        if (!empty($filters['reason']) && in_array('recall_reason', $columns)) {
            $this->db->where('j.recall_reason', $filters['reason']);
        }
        
        if (!empty($filters['severity']) && in_array('recall_severity', $columns)) {
            $this->db->where('j.recall_severity', $filters['severity']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            
            $this->db->group_start();
            $this->db->like('j.title', $search);
            $this->db->or_like('j.description', $search);
            
            // Only search in recall_details if column exists
            if (in_array('recall_details', $columns)) {
                $this->db->or_like('j.recall_details', $search);
            }
            
            $this->db->or_like('h.first_name', $search);
            $this->db->or_like('h.last_name', $search);
            $this->db->group_end();
        }
        
        // Apply date range filtering using recalled_at or updated_at
        if (!empty($filters['date_from'])) {
            if (in_array('recalled_at', $columns)) {
                $this->db->where('DATE(COALESCE(j.recalled_at, j.updated_at)) >=', $filters['date_from']);
            } else {
                $this->db->where('DATE(j.updated_at) >=', $filters['date_from']);
            }
        }
        
        if (!empty($filters['date_to'])) {
            if (in_array('recalled_at', $columns)) {
                $this->db->where('DATE(COALESCE(j.recalled_at, j.updated_at)) <=', $filters['date_to']);
            } else {
                $this->db->where('DATE(j.updated_at) <=', $filters['date_to']);
            }
        }
        
        // Apply sorting
        $sort_by = $filters['sort'] ?? 'recalled_at';
        
        switch ($sort_by) {
            case 'title':
                $this->db->order_by('j.title', 'ASC');
                break;
            case 'severity':
                if (in_array('recall_severity', $columns)) {
                    $this->db->order_by('j.recall_severity', 'DESC');
                } else {
                    $this->db->order_by('j.updated_at', 'DESC');
                }
                break;
            default:
                if (in_array('recalled_at', $columns)) {
                    $this->db->order_by('j.recalled_at', 'DESC');
                } else {
                    $this->db->order_by('j.updated_at', 'DESC');
                }
                break;
        }
        
        $query = $this->db->get();
        $results = $query->result();
        
        // Add host name to each result
        foreach ($results as $job) {
            $host_name = trim(($job->host_first_name ?? '') . ' ' . ($job->host_last_name ?? ''));
            if (empty($host_name)) {
                $host_name = $job->host_username ?? 'Unknown Host';
            }
            $job->host_name = $host_name;
            
            // Set default recall status if not set
            $job->recall_status = $job->recall_status ?? 'pending';
        }
        
        return $results;
    }

    /**
     * Get all recalled jobs for admin (all hosts)
     */
    public function count_all_recalled_jobs($filters = [])
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return 0;
        }
        
        $this->db->from('jobs j');
        $this->db->where_in('j.status', ['recalled', 'recall_settled']);
        
        // Apply status filter if provided
        if (!empty($filters['status'])) {
            $this->db->where('j.status', $filters['status']);
        }
        
        return $this->db->count_all_results();
    }
    
    public function get_all_recalled_jobs($filters = [], $limit = null, $offset = null)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('j.*, 
                          u.username as cleaner_username, 
                          u.first_name as cleaner_first_name, 
                          u.last_name as cleaner_last_name,
                          u.phone as cleaner_phone,
                          u.email as cleaner_email,
                          h.username as host_username,
                          h.first_name as host_first_name,
                          h.last_name as host_last_name,
                          h.phone as host_phone,
                          h.email as host_email');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id', 'left');
        $this->db->join('users h', 'j.host_id = h.user_id', 'left');
        $this->db->where('j.status', 'recalled');
        
        // Check which columns exist
        $columns = $this->db->list_fields('jobs');
        
        // Apply filters - only if columns exist
        if (!empty($filters['reason']) && in_array('recall_reason', $columns)) {
            $this->db->where('j.recall_reason', $filters['reason']);
        }
        
        if (!empty($filters['severity']) && in_array('recall_severity', $columns)) {
            $this->db->where('j.recall_severity', $filters['severity']);
        }
        
        if (!empty($filters['recall_status']) && in_array('recall_status', $columns)) {
            $this->db->where('j.recall_status', $filters['recall_status']);
        }
        
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            
            $this->db->group_start();
            $this->db->like('j.title', $search);
            $this->db->or_like('j.description', $search);
            
            // Only search in recall_details if column exists
            if (in_array('recall_details', $columns)) {
                $this->db->or_like('j.recall_details', $search);
            }
            
            $this->db->or_like('u.first_name', $search);
            $this->db->or_like('u.last_name', $search);
            $this->db->or_like('h.first_name', $search);
            $this->db->or_like('h.last_name', $search);
            $this->db->group_end();
        }
        
        // Apply sorting
        $sort_by = $filters['sort'] ?? 'recalled_at';
        
        switch ($sort_by) {
            case 'title':
                $this->db->order_by('j.title', 'ASC');
                break;
            case 'severity':
                if (in_array('recall_severity', $columns)) {
                    $this->db->order_by('j.recall_severity', 'DESC');
                } else {
                    $this->db->order_by('j.updated_at', 'DESC');
                }
                break;
            case 'host':
                $this->db->order_by('h.last_name', 'ASC');
                break;
            default:
                if (in_array('recalled_at', $columns)) {
                    $this->db->order_by('j.recalled_at', 'DESC');
                } else {
                    $this->db->order_by('j.updated_at', 'DESC');
                }
                break;
        }
        
        // Apply limit and offset if provided
        if ($limit !== null) {
            $this->db->limit($limit, $offset ?? 0);
        }
        
        $query = $this->db->get();
        $results = $query->result();
        
        // Add cleaner and host names to each result
        foreach ($results as $job) {
            $cleaner_name = trim(($job->cleaner_first_name ?? '') . ' ' . ($job->cleaner_last_name ?? ''));
            if (empty($cleaner_name)) {
                $cleaner_name = $job->cleaner_username ?? 'Unknown Cleaner';
            }
            $job->cleaner_name = $cleaner_name;
            
            $host_name = trim(($job->host_first_name ?? '') . ' ' . ($job->host_last_name ?? ''));
            if (empty($host_name)) {
                $host_name = $job->host_username ?? 'Unknown Host';
            }
            $job->host_name = $host_name;
            
            // Set default recall status if not set
            $job->recall_status = $job->recall_status ?? 'pending';
        }
        
        return $results;
    }
}
