<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Offers Model
 * 
 * Handles all database operations related to job offers
 */
class M_offers extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * Get offers for a specific job
     */
    public function get_offers_by_job($job_id)
    {
        // Check if required tables exist
        if (!$this->db->table_exists('offers')) {
            return [];
        }
        
        $this->db->select('o.*, u.username as cleaner_username, u.email as cleaner_email');
        $this->db->from('offers o');
        $this->db->join('users u', 'o.cleaner_id = u.user_id');
        $this->db->where('o.job_id', $job_id);
        $this->db->order_by('o.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }


    /**
     * Get pending offers for host
     */
    public function get_pending_offers_for_host($host_id, $limit = null)
    {
        // Check if required tables exist
        if (!$this->db->table_exists('offers') || !$this->db->table_exists('jobs')) {
            return [];
        }
        
        // Check if the jobs table has the required columns
        $jobs_fields = $this->db->field_data('jobs');
        $has_date_time = false;
        foreach ($jobs_fields as $field) {
            if ($field->name === 'date_time') {
                $has_date_time = true;
                break;
            }
        }
        
        if ($has_date_time) {
            $this->db->select('o.*, j.title as job_title, j.date_time, u.username as cleaner_username');
        } else {
            $this->db->select('o.*, j.title as job_title, u.username as cleaner_username');
        }
        
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->join('users u', 'o.cleaner_id = u.user_id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('o.status', 'pending');
        $this->db->order_by('o.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Accept an offer
     */
    public function accept_offer($offer_id, $host_id)
    {
        // Start transaction
        $this->db->trans_start();
        
        // Update offer status
        $this->db->where('id', $offer_id);
        $this->db->update('offers', [
            'status' => 'accepted',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Reject all other offers for the same job
        $offer = $this->get_offer_by_id($offer_id);
        $this->db->where('job_id', $offer->job_id);
        $this->db->where('id !=', $offer_id);
        $this->db->update('offers', [
            'status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Create job assignment
        $this->db->insert('job_assignments', [
            'job_id' => $offer->job_id,
            'cleaner_id' => $offer->cleaner_id,
            'qr_code' => $this->generate_qr_code(),
            'passcode' => $this->generate_passcode(),
            'status' => 'assigned',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Update job status to assigned
        $this->db->where('id', $offer->job_id);
        $this->db->update('jobs', [
            'status' => 'assigned',
            'final_price' => $offer->amount,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Complete transaction
        $this->db->trans_complete();
        
        return $this->db->trans_status();
    }

    /**
     * Reject an offer
     */
    public function reject_offer($offer_id)
    {
        $this->db->where('id', $offer_id);
        return $this->db->update('offers', [
            'status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }


    /**
     * Get offer statistics for cleaner
     */
    public function get_cleaner_offer_stats($cleaner_id)
    {
        $stats = [];
        
        // Total offers
        $this->db->where('cleaner_id', $cleaner_id);
        $stats['total_offers'] = $this->db->count_all_results('offers');
        
        // Pending offers
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('status', 'pending');
        $stats['pending_offers'] = $this->db->count_all_results('offers');
        
        // Accepted offers
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('status', 'accepted');
        $stats['accepted_offers'] = $this->db->count_all_results('offers');
        
        // Rejected offers
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('status', 'rejected');
        $stats['rejected_offers'] = $this->db->count_all_results('offers');
        
        // Success rate
        if ($stats['total_offers'] > 0) {
            $stats['success_rate'] = round(($stats['accepted_offers'] / $stats['total_offers']) * 100, 1);
        } else {
            $stats['success_rate'] = 0;
        }
        
        return $stats;
    }

    /**
     * Generate QR code for job assignment
     */
    private function generate_qr_code()
    {
        return 'QR_' . uniqid() . '_' . time();
    }

    /**
     * Generate passcode for job access
     */
    private function generate_passcode()
    {
        return rand(1000, 9999);
    }


    /**
     * Delete offer (if still pending)
     */
    public function delete_offer($offer_id, $cleaner_id)
    {
        $this->db->where('id', $offer_id);
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('status', 'pending');
        
        return $this->db->delete('offers');
    }

    /**
     * Get offers expiring soon
     */
    public function get_expiring_offers($hours = 24)
    {
        $this->db->select('o.*, j.title as job_title, u.username as cleaner_username');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->join('users u', 'o.cleaner_id = u.user_id');
        $this->db->where('o.status', 'pending');
        $this->db->where('o.expires_at <=', date('Y-m-d H:i:s', strtotime("+{$hours} hours")));
        $this->db->order_by('o.expires_at', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Check if cleaner has already offered on a job
     * @param int $cleaner_id
     * @param int $job_id
     * @return bool
     */
    public function cleaner_has_offered($cleaner_id, $job_id)
    {
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            return false;
        }

        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('job_id', $job_id);
        $this->db->where_in('status', ['pending', 'accepted', 'rejected']);
        
        return $this->db->count_all_results('offers') > 0;
    }

    /**
     * Create a new offer
     * @param array $offer_data
     * @return int|false
     */
    public function create_offer($offer_data)
    {
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            return false;
        }

        $data = [
            'job_id' => $offer_data['job_id'],
            'cleaner_id' => $offer_data['cleaner_id'],
            'offer_type' => $offer_data['offer_type'],
            'amount' => $offer_data['amount'],
            'status' => $offer_data['status'],
            'expires_at' => $offer_data['expires_at'],
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('offers', $data);
        return $this->db->insert_id();
    }

    /**
     * Get offer by ID with job and user details
     */
    public function get_offer_by_id($offer_id)
    {
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            return false;
        }

        $this->db->select('o.*, j.title as job_title, j.address, j.suggested_price, u.username as cleaner_username, u.email as cleaner_email, h.username as host_username, h.email as host_email');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->join('users u', 'o.cleaner_id = u.user_id');
        $this->db->join('users h', 'j.host_id = h.user_id');
        $this->db->where('o.id', $offer_id);
        
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Update offer status and details
     */
    public function update_offer($offer_id, $data)
    {
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            return false;
        }

        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->where('id', $offer_id);
        return $this->db->update('offers', $data);
    }

    /**
     * Get offers by cleaner with job details
     */
    public function get_offers_by_cleaner($cleaner_id)
    {
        // Check if required tables exist
        if (!$this->db->table_exists('offers') || !$this->db->table_exists('jobs')) {
            return [];
        }

        $this->db->select('o.*, j.title as job_title, j.address, j.suggested_price, j.status as job_status, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('o.cleaner_id', $cleaner_id);
        $this->db->order_by('o.created_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }
}
