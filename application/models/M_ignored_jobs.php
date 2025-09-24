<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ignored Jobs Model
 * 
 * Handles ignored jobs functionality for cleaners
 */
class M_ignored_jobs extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ignore a job
     */
    public function ignore_job($cleaner_id, $job_id, $reason = null)
    {
        // Check if already ignored
        if ($this->is_job_ignored($cleaner_id, $job_id)) {
            return false;
        }

        $data = [
            'cleaner_id' => $cleaner_id,
            'job_id' => $job_id,
            'reason' => $reason,
            'ignored_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('ignored_jobs', $data);
    }

    /**
     * Check if a job is ignored by a cleaner
     */
    public function is_job_ignored($cleaner_id, $job_id)
    {
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('job_id', $job_id);
        $query = $this->db->get('ignored_jobs');
        
        return $query->num_rows() > 0;
    }

    /**
     * Get ignored jobs for a cleaner
     */
    public function get_ignored_jobs($cleaner_id, $limit = 20, $offset = 0)
    {
        $this->db->select('ij.*, j.*, u.first_name as host_first_name, u.last_name as host_last_name, u.username as host_username');
        $this->db->from('ignored_jobs ij');
        $this->db->join('jobs j', 'ij.job_id = j.id');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('ij.cleaner_id', $cleaner_id);
        $this->db->where('j.scheduled_date >=', date('Y-m-d')); // Only future jobs
        $this->db->order_by('ij.ignored_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }

    /**
     * Count ignored jobs for a cleaner
     */
    public function count_ignored_jobs($cleaner_id)
    {
        $this->db->from('ignored_jobs ij');
        $this->db->join('jobs j', 'ij.job_id = j.id');
        $this->db->where('ij.cleaner_id', $cleaner_id);
        $this->db->where('j.scheduled_date >=', date('Y-m-d')); // Only future jobs
        
        return $this->db->count_all_results();
    }

    /**
     * Remove a job from ignored list
     */
    public function unignore_job($cleaner_id, $job_id)
    {
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('job_id', $job_id);
        
        return $this->db->delete('ignored_jobs');
    }

    /**
     * Get jobs that are not ignored by a cleaner
     */
    public function get_available_jobs_excluding_ignored($cleaner_id, $filters = [], $limit = 20, $offset = 0)
    {
        // Get ignored job IDs
        $this->db->select('job_id');
        $this->db->from('ignored_jobs');
        $this->db->where('cleaner_id', $cleaner_id);
        $ignored_jobs = $this->db->get()->result_array();
        $ignored_job_ids = array_column($ignored_jobs, 'job_id');

        // Get available jobs excluding ignored ones
        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.status', 'open');
        
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

        $this->db->order_by('j.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        return $this->db->get()->result();
    }
}
