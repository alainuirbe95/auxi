<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payments Model
 * 
 * Handles all database operations related to payments
 */
class M_payments extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new payment record
     */
    public function create_payment($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        $this->db->insert('payments', $data);
        return $this->db->insert_id();
    }

    /**
     * Get payment by ID
     */
    public function get_payment_by_id($payment_id)
    {
        $this->db->select('p.*, j.title as job_title, h.username as host_username, c.username as cleaner_username');
        $this->db->from('payments p');
        $this->db->join('jobs j', 'p.job_id = j.id');
        $this->db->join('users h', 'p.host_id = h.user_id');
        $this->db->join('users c', 'p.cleaner_id = c.user_id');
        $this->db->where('p.id', $payment_id);
        
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get payments by host
     */
    public function get_payments_by_host($host_id, $limit = null, $offset = 0)
    {
        $this->db->select('p.*, j.title as job_title, c.username as cleaner_username');
        $this->db->from('payments p');
        $this->db->join('jobs j', 'p.job_id = j.id');
        $this->db->join('users c', 'p.cleaner_id = c.user_id');
        $this->db->where('p.host_id', $host_id);
        $this->db->order_by('p.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get payments by cleaner
     */
    public function get_payments_by_cleaner($cleaner_id, $limit = null, $offset = 0)
    {
        $this->db->select('p.*, j.title as job_title, h.username as host_username');
        $this->db->from('payments p');
        $this->db->join('jobs j', 'p.job_id = j.id');
        $this->db->join('users h', 'p.host_id = h.user_id');
        $this->db->where('p.cleaner_id', $cleaner_id);
        $this->db->order_by('p.created_at', 'DESC');
        
        if ($limit) {
            $this->db->limit($limit, $offset);
        }
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Update payment status
     */
    public function update_payment_status($payment_id, $status, $stripe_payment_intent = null)
    {
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($stripe_payment_intent) {
            $data['stripe_payment_intent'] = $stripe_payment_intent;
        }
        
        $this->db->where('id', $payment_id);
        return $this->db->update('payments', $data);
    }

    /**
     * Get payment statistics for host
     */
    public function get_host_payment_stats($host_id)
    {
        $stats = [];
        
        // Total spent
        $this->db->select('SUM(amount) as total_spent');
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'completed');
        $query = $this->db->get('payments');
        $result = $query->row();
        $stats['total_spent'] = $result->total_spent ?: 0;
        
        // Total payments count
        $this->db->where('host_id', $host_id);
        $stats['total_payments'] = $this->db->count_all_results('payments');
        
        // Completed payments
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'completed');
        $stats['completed_payments'] = $this->db->count_all_results('payments');
        
        // Pending payments
        $this->db->where('host_id', $host_id);
        $this->db->where('status', 'pending');
        $stats['pending_payments'] = $this->db->count_all_results('payments');
        
        return $stats;
    }

    /**
     * Get payment statistics for cleaner
     */
    public function get_cleaner_payment_stats($cleaner_id)
    {
        $stats = [];
        
        // Total earned
        $this->db->select('SUM(cleaner_payout) as total_earned');
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('status', 'completed');
        $query = $this->db->get('payments');
        $result = $query->row();
        $stats['total_earned'] = $result->total_earned ?: 0;
        
        // Total payments count
        $this->db->where('cleaner_id', $cleaner_id);
        $stats['total_payments'] = $this->db->count_all_results('payments');
        
        // Completed payments
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('status', 'completed');
        $stats['completed_payments'] = $this->db->count_all_results('payments');
        
        // Pending payments
        $this->db->where('cleaner_id', $cleaner_id);
        $this->db->where('status', 'pending');
        $stats['pending_payments'] = $this->db->count_all_results('payments');
        
        return $stats;
    }

    /**
     * Create payment for job completion
     */
    public function create_job_payment($job_id, $host_id, $cleaner_id, $amount, $platform_fee_percentage = 10)
    {
        $platform_fee = $amount * ($platform_fee_percentage / 100);
        $cleaner_payout = $amount - $platform_fee;
        
        $payment_data = [
            'job_id' => $job_id,
            'host_id' => $host_id,
            'cleaner_id' => $cleaner_id,
            'amount' => $amount,
            'platform_fee' => $platform_fee,
            'cleaner_payout' => $cleaner_payout,
            'status' => 'pending'
        ];
        
        return $this->create_payment($payment_data);
    }

    /**
     * Get recent payments for dashboard
     */
    public function get_recent_payments($user_id, $user_type = 'host', $limit = 5)
    {
        if ($user_type === 'host') {
            $this->db->where('host_id', $user_id);
        } else {
            $this->db->where('cleaner_id', $user_id);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get('payments');
        return $query->result();
    }

    /**
     * Calculate platform earnings
     */
    public function get_platform_earnings($date_from = null, $date_to = null)
    {
        $this->db->select('SUM(platform_fee) as total_platform_fee');
        $this->db->where('status', 'completed');
        
        if ($date_from) {
            $this->db->where('created_at >=', $date_from);
        }
        
        if ($date_to) {
            $this->db->where('created_at <=', $date_to);
        }
        
        $query = $this->db->get('payments');
        $result = $query->row();
        
        return $result->total_platform_fee ?: 0;
    }
}
