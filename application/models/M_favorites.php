<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Job Favorites Model
 * 
 * Handles job favorites functionality for cleaners
 */
class M_favorites extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add a job to favorites
     */
    public function add_favorite($cleaner_id, $job_id)
    {
        // Check if already favorited
        if ($this->is_job_favorited($cleaner_id, $job_id)) {
            return false;
        }

        $data = [
            'cleaner_id' => $cleaner_id,
            'job_id' => $job_id,
            'favorited_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('job_favorites', $data);
    }

    /**
     * Remove a job from favorites
     */
    public function remove_favorite($cleaner_id, $job_id)
    {
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('job_id', $job_id);
        
        return $this->db->delete('job_favorites');
    }

    /**
     * Check if a job is favorited by a cleaner
     */
    public function is_job_favorited($cleaner_id, $job_id)
    {
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('job_id', $job_id);
        $query = $this->db->get('job_favorites');
        
        return $query->num_rows() > 0;
    }

    /**
     * Get favorite jobs for a cleaner
     */
    public function get_favorite_jobs($cleaner_id, $limit = 20, $offset = 0)
    {
        $this->db->select('jf.*, j.*, u.first_name as host_first_name, u.last_name as host_last_name, u.username as host_username');
        $this->db->from('job_favorites jf');
        $this->db->join('jobs j', 'jf.job_id = j.id');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('jf.cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'open');
        $this->db->order_by('jf.favorited_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    /**
     * Count favorite jobs for a cleaner
     */
    public function count_favorite_jobs($cleaner_id)
    {
        $this->db->from('job_favorites jf');
        $this->db->join('jobs j', 'jf.job_id = j.id');
        $this->db->where('jf.cleaner_id', $cleaner_id);
        $this->db->where('j.status', 'open');
        
        return $this->db->count_all_results();
    }

    /**
     * Get jobs with favorites status for a cleaner
     */
    public function get_jobs_with_favorites($cleaner_id, $filters = [], $limit = 20, $offset = 0)
    {
        // Get ignored job IDs
        $ignored_job_ids = [];
        if ($this->db->table_exists('ignored_jobs')) {
            $this->db->select('job_id');
            $this->db->from('ignored_jobs');
            $this->db->where('cleaner_id', $cleaner_id);
            $ignored_jobs = $this->db->get()->result_array();
            $ignored_job_ids = array_column($ignored_jobs, 'job_id');
        }

        // Get available jobs excluding ignored ones
        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
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
        
        if (!empty($ignored_job_ids)) {
            $this->db->where_not_in('j.id', $ignored_job_ids);
        }

        // Apply filters
        if (!empty($filters['search'])) {
            $this->db->group_start();
            $this->db->like('j.title', $filters['search']);
            $this->db->or_like('j.description', $filters['search']);
            $this->db->or_like('j.city', $filters['search']);
            $this->db->group_end();
        }

        if (!empty($filters['max_price'])) {
            $this->db->where('j.suggested_price <=', $filters['max_price']);
        }

        if (!empty($filters['min_price'])) {
            $this->db->where('j.suggested_price >=', $filters['min_price']);
        }

        if (!empty($filters['date_from'])) {
            $this->db->where('j.scheduled_date >=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $this->db->where('j.scheduled_date <=', $filters['date_to']);
        }

        // Left join with favorites to get favorite status
        $this->db->select('jf.favorited_at as is_favorited');
        if ($cleaner_id !== null && $cleaner_id !== '') {
            $this->db->join('job_favorites jf', 'j.id = jf.job_id AND jf.cleaner_id = ' . $this->db->escape($cleaner_id), 'left');
        } else {
            $this->db->join('job_favorites jf', 'j.id = jf.job_id', 'left');
        }
        
        // Order by favorites first, then by creation date
        $this->db->order_by('jf.favorited_at', 'DESC', FALSE);
        $this->db->order_by('j.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
}
