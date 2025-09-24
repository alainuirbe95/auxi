<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Job Flags Model
 * 
 * Handles all database operations related to job flagging
 */
class M_job_flags extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Flag a job
     */
    public function flag_job($job_id, $flagged_by_user_id, $flagged_by_user_type, $flag_reason = null, $flag_details = null)
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            log_message('error', 'Jobs table does not exist in flag_job');
            return false;
        }

        // Check if job_flags table exists
        if (!$this->db->table_exists('job_flags')) {
            log_message('error', 'Job_flags table does not exist in flag_job');
            return false;
        }

        // Check if user has already flagged this job
        $existing_flag = $this->db->where('job_id', $job_id)
                                 ->where('flagged_by_user_id', $flagged_by_user_id)
                                 ->where('status', 'active')
                                 ->get('job_flags')
                                 ->row();

        if ($existing_flag) {
            log_message('info', 'User ' . $flagged_by_user_id . ' has already flagged job ' . $job_id);
            return false; // User has already flagged this job
        }

        // Create the flag record
        $flag_data = [
            'job_id' => $job_id,
            'flagged_by_user_id' => $flagged_by_user_id,
            'flagged_by_user_type' => $flagged_by_user_type,
            'flag_reason' => $flag_reason,
            'flag_details' => $flag_details,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->db->insert('job_flags', $flag_data);

        if ($result) {
            // Update the flag count on the jobs table
            $this->db->set('flag_count', 'flag_count + 1', FALSE)
                     ->where('id', $job_id)
                     ->update('jobs');

            log_message('info', 'Job ' . $job_id . ' flagged by user ' . $flagged_by_user_id);
            return $this->db->insert_id();
        }

        return false;
    }

    /**
     * Get flags for a specific job
     */
    public function get_job_flags($job_id, $status = 'active')
    {
        if (!$this->db->table_exists('job_flags')) {
            return [];
        }

        $this->db->select('jf.*, u.username, u.first_name, u.last_name, u.email');
        $this->db->from('job_flags jf');
        $this->db->join('users u', 'jf.flagged_by_user_id = u.user_id');
        $this->db->where('jf.job_id', $job_id);

        if ($status !== 'all') {
            $this->db->where('jf.status', $status);
        }

        $this->db->order_by('jf.created_at', 'DESC');

        return $this->db->get()->result();
    }

    /**
     * Get all active flags for admin review
     */
    public function get_active_flags($limit = 50, $offset = 0)
    {
        if (!$this->db->table_exists('job_flags')) {
            return [];
        }

        $this->db->select('jf.*, j.title as job_title, j.description as job_description, j.status as job_status, 
                          u.username, u.first_name, u.last_name, u.email as flagged_by_email,
                          j.host_id, host_user.username as host_username, host_user.first_name as host_first_name, host_user.last_name as host_last_name');
        $this->db->from('job_flags jf');
        $this->db->join('jobs j', 'jf.job_id = j.id');
        $this->db->join('users u', 'jf.flagged_by_user_id = u.user_id');
        $this->db->join('users host_user', 'j.host_id = host_user.user_id');
        $this->db->where('jf.status', 'active');
        $this->db->order_by('jf.created_at', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    /**
     * Count active flags
     */
    public function count_active_flags()
    {
        if (!$this->db->table_exists('job_flags')) {
            return 0;
        }

        return $this->db->where('status', 'active')->count_all_results('job_flags');
    }

    /**
     * Resolve a flag (admin action)
     */
    public function resolve_flag($flag_id, $resolved_by_user_id, $resolution_notes = null)
    {
        if (!$this->db->table_exists('job_flags')) {
            return false;
        }

        $update_data = [
            'status' => 'resolved',
            'resolved_by' => $resolved_by_user_id,
            'resolved_at' => date('Y-m-d H:i:s'),
            'resolution_notes' => $resolution_notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $flag_id);
        $result = $this->db->update('job_flags', $update_data);

        if ($result) {
            // Update the flag count on the jobs table
            $flag = $this->db->where('id', $flag_id)->get('job_flags')->row();
            if ($flag) {
                $this->db->set('flag_count', 'flag_count - 1', FALSE)
                         ->where('id', $flag->job_id)
                         ->update('jobs');
            }

            log_message('info', 'Flag ' . $flag_id . ' resolved by admin ' . $resolved_by_user_id);
            return true;
        }

        return false;
    }

    /**
     * Dismiss a flag (admin action)
     */
    public function dismiss_flag($flag_id, $resolved_by_user_id, $resolution_notes = null)
    {
        if (!$this->db->table_exists('job_flags')) {
            return false;
        }

        $update_data = [
            'status' => 'dismissed',
            'resolved_by' => $resolved_by_user_id,
            'resolved_at' => date('Y-m-d H:i:s'),
            'resolution_notes' => $resolution_notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id', $flag_id);
        $result = $this->db->update('job_flags', $update_data);

        if ($result) {
            // Update the flag count on the jobs table
            $flag = $this->db->where('id', $flag_id)->get('job_flags')->row();
            if ($flag) {
                $this->db->set('flag_count', 'flag_count - 1', FALSE)
                         ->where('id', $flag->job_id)
                         ->update('jobs');
            }

            log_message('info', 'Flag ' . $flag_id . ' dismissed by admin ' . $resolved_by_user_id);
            return true;
        }

        return false;
    }

    /**
     * Check if user has flagged a job
     */
    public function user_has_flagged_job($job_id, $user_id)
    {
        if (!$this->db->table_exists('job_flags')) {
            return false;
        }

        $count = $this->db->where('job_id', $job_id)
                         ->where('flagged_by_user_id', $user_id)
                         ->where('status', 'active')
                         ->count_all_results('job_flags');

        return $count > 0;
    }

    /**
     * Get flag statistics for admin dashboard
     */
    public function get_flag_stats()
    {
        if (!$this->db->table_exists('job_flags')) {
            return [
                'total_flags' => 0,
                'active_flags' => 0,
                'resolved_flags' => 0,
                'dismissed_flags' => 0
            ];
        }

        $stats = [];

        // Total flags
        $stats['total_flags'] = $this->db->count_all_results('job_flags');

        // Active flags
        $this->db->reset_query();
        $stats['active_flags'] = $this->db->where('status', 'active')->count_all_results('job_flags');

        // Resolved flags
        $this->db->reset_query();
        $stats['resolved_flags'] = $this->db->where('status', 'resolved')->count_all_results('job_flags');

        // Dismissed flags
        $this->db->reset_query();
        $stats['dismissed_flags'] = $this->db->where('status', 'dismissed')->count_all_results('job_flags');

        return $stats;
    }

    /**
     * Get jobs with highest flag counts
     */
    public function get_most_flagged_jobs($limit = 10)
    {
        if (!$this->db->table_exists('jobs') || !$this->db->table_exists('job_flags')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.flag_count >', 0);
        $this->db->order_by('j.flag_count', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result();
    }
}
