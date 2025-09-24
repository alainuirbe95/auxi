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
     * Accept an offer (enhanced system)
     */
    public function accept_offer($offer_id, $host_id)
    {
        // Start transaction
        $this->db->trans_start();
        
        // Get offer details
        $offer = $this->get_offer_by_id($offer_id);
        if (!$offer) {
            $this->db->trans_rollback();
            return false;
        }
        
        // Verify host owns the job
        if ($offer->host_id != $host_id) {
            $this->db->trans_rollback();
            return false;
        }
        
        // Update offer status to accepted
        $this->db->where('id', $offer_id);
        $this->db->update('offers', [
            'status' => 'accepted',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Decline all other offers for the same job
        $this->db->where('job_id', $offer->job_id);
        $this->db->where('id !=', $offer_id);
        $this->db->update('offers', [
            'status' => 'declined',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Determine final price
        $final_price = ($offer->offer_type === 'accept') ? $offer->amount : $offer->counter_price;
        
        // Update job with assignment details
        $this->db->where('id', $offer->job_id);
        $this->db->update('jobs', [
            'status' => 'assigned',
            'assigned_cleaner_id' => $offer->cleaner_id,
            'accepted_price' => $final_price,
            'assignment_date' => date('Y-m-d H:i:s'),
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
     * Create a new offer (accept or counter)
     * @param array $offer_data
     * @return int|false
     */
    public function create_offer($offer_data)
    {
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            return false;
        }

        // Check if cleaner has already made a pending offer for this job
        $existing_offer = $this->db->where('job_id', $offer_data['job_id'])
                                  ->where('cleaner_id', $offer_data['cleaner_id'])
                                  ->where('status', 'pending')
                                  ->get('offers')
                                  ->row();

        if ($existing_offer) {
            log_message('info', 'Cleaner ' . $offer_data['cleaner_id'] . ' has already made a pending offer for job ' . $offer_data['job_id']);
            return false; // Cleaner has already made a pending offer
        }

        // Set expiration time for counter offers (6 hours from now)
        if (isset($offer_data['offer_type']) && $offer_data['offer_type'] === 'counter') {
            $offer_data['expires_at'] = date('Y-m-d H:i:s', strtotime('+6 hours'));
        }

        $data = [
            'job_id' => $offer_data['job_id'],
            'cleaner_id' => $offer_data['cleaner_id'],
            'offer_type' => $offer_data['offer_type'] ?? 'counter',
            'amount' => $offer_data['amount'],
            'counter_price' => isset($offer_data['counter_price']) ? $offer_data['counter_price'] : null,
            'original_price' => $offer_data['original_price'] ?? null,
            'status' => 'pending',
            'expires_at' => $offer_data['expires_at'] ?? null,
            'message' => $offer_data['message'] ?? null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $result = $this->db->insert('offers', $data);
        
        if ($result) {
            log_message('info', 'Offer created successfully for job ' . $offer_data['job_id'] . ' by cleaner ' . $offer_data['cleaner_id']);
            return $this->db->insert_id();
        }

        return false;
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

    /**
     * Get jobs available for cleaners in their city
     */
    public function get_available_jobs_for_cleaner($cleaner_id, $city, $limit = 20, $offset = 0)
    {
        // Check if required tables exist
        if (!$this->db->table_exists('jobs') || !$this->db->table_exists('offers')) {
            return [];
        }

        $this->db->select('j.*, u.username as host_username, u.first_name as host_first_name, u.last_name as host_last_name');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.host_id = u.user_id');
        $this->db->where('j.status', 'open');
        $this->db->where('j.city', $city);
        
        // Exclude jobs where cleaner has already made an offer
        $this->db->where("j.id NOT IN (SELECT job_id FROM offers WHERE cleaner_id = {$cleaner_id} AND status IN ('pending', 'accepted', 'declined'))");
        
        $this->db->order_by('j.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Update expired counter offers to declined status
     */
    public function expire_counter_offers()
    {
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            return false;
        }

        $this->db->where('offer_type', 'counter');
        $this->db->where('status', 'pending');
        $this->db->where('expires_at <=', date('Y-m-d H:i:s'));
        
        return $this->db->update('offers', [
            'status' => 'expired',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Allow cleaner to accept original price after counter offer expires
     */
    public function accept_original_price($job_id, $cleaner_id)
    {
        // Check if offers table exists
        if (!$this->db->table_exists('offers')) {
            return false;
        }

        // Check if cleaner has an expired counter offer for this job
        $expired_offer = $this->db->where('job_id', $job_id)
                                 ->where('cleaner_id', $cleaner_id)
                                 ->where('offer_type', 'counter')
                                 ->where('status', 'expired')
                                 ->get('offers')
                                 ->row();

        if (!$expired_offer) {
            return false; // No expired counter offer found
        }

        // Create new accept offer
        $offer_data = [
            'job_id' => $job_id,
            'cleaner_id' => $cleaner_id,
            'offer_type' => 'accept',
            'amount' => $expired_offer->original_price,
            'original_price' => $expired_offer->original_price,
            'message' => 'Accepting original price after counter offer expired'
        ];

        return $this->create_offer($offer_data);
    }

    /**
     * Get offer statistics for host
     */
    public function get_host_offer_stats($host_id)
    {
        // Check if required tables exist
        if (!$this->db->table_exists('offers') || !$this->db->table_exists('jobs')) {
            return [
                'total_offers' => 0,
                'pending_offers' => 0,
                'accepted_offers' => 0,
                'declined_offers' => 0,
                'expired_offers' => 0
            ];
        }

        $stats = [];

        // Total offers for host's jobs
        $this->db->select('COUNT(o.id) as total_offers');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->where('j.host_id', $host_id);
        $query = $this->db->get();
        $result = $query->row();
        $stats['total_offers'] = $result->total_offers;

        // Pending offers
        $this->db->select('COUNT(o.id) as pending_offers');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('o.status', 'pending');
        $query = $this->db->get();
        $result = $query->row();
        $stats['pending_offers'] = $result->pending_offers;

        // Accepted offers
        $this->db->select('COUNT(o.id) as accepted_offers');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('o.status', 'accepted');
        $query = $this->db->get();
        $result = $query->row();
        $stats['accepted_offers'] = $result->accepted_offers;

        // Declined offers
        $this->db->select('COUNT(o.id) as declined_offers');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('o.status', 'declined');
        $query = $this->db->get();
        $result = $query->row();
        $stats['declined_offers'] = $result->declined_offers;

        // Expired offers
        $this->db->select('COUNT(o.id) as expired_offers');
        $this->db->from('offers o');
        $this->db->join('jobs j', 'o.job_id = j.id');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('o.status', 'expired');
        $query = $this->db->get();
        $result = $query->row();
        $stats['expired_offers'] = $result->expired_offers;

        return $stats;
    }
}
