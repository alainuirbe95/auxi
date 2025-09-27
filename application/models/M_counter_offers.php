<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Counter Offers Model
 * 
 * Handles counter-offer functionality for price adjustments
 */
class M_counter_offers extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new counter-offer
     */
    public function create_counter_offer($job_id, $cleaner_id, $host_id, $original_price, $proposed_price, $reason = null)
    {
        // Ensure prices are properly formatted as decimals
        $original_price = number_format((float)$original_price, 2, '.', '');
        $proposed_price = number_format((float)$proposed_price, 2, '.', '');
        
        $data = [
            'job_id' => $job_id,
            'cleaner_id' => $cleaner_id,
            'host_id' => $host_id,
            'original_price' => $original_price,
            'proposed_price' => $proposed_price,
            'reason' => $reason,
            'status' => 'pending',
            'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('counter_offers', $data);
        return $this->db->insert_id();
    }

    /**
     * Get counter-offer by ID
     */
    public function get_counter_offer($id)
    {
        return $this->db->get_where('counter_offers', ['id' => $id])->row();
    }

    /**
     * Get pending counter-offers for a host
     */
    public function get_pending_counter_offers_for_host($host_id)
    {
        $this->db->select('co.*, j.title as job_title, j.description as job_description, j.scheduled_date, j.scheduled_time, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name');
        $this->db->from('counter_offers co');
        $this->db->join('jobs j', 'j.id = co.job_id');
        $this->db->join('users u', 'u.user_id = co.cleaner_id');
        $this->db->where('co.host_id', $host_id);
        $this->db->where('co.status', 'pending');
        $this->db->where('co.expires_at >', date('Y-m-d H:i:s'));
        $this->db->order_by('co.created_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get counter-offers for a job
     */
    public function get_counter_offers_for_job($job_id)
    {
        $this->db->select('co.*, u.first_name as cleaner_first_name, u.last_name as cleaner_last_name');
        $this->db->from('counter_offers co');
        $this->db->join('users u', 'u.user_id = co.cleaner_id');
        $this->db->where('co.job_id', $job_id);
        $this->db->order_by('co.created_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Get escalated counter-offers for moderators
     */
    public function get_escalated_counter_offers()
    {
        $this->db->select('co.*, j.title as job_title, j.description as job_description, j.scheduled_date, j.scheduled_time, 
                          u.first_name as cleaner_first_name, u.last_name as cleaner_last_name,
                          h.first_name as host_first_name, h.last_name as host_last_name');
        $this->db->from('counter_offers co');
        $this->db->join('jobs j', 'j.id = co.job_id');
        $this->db->join('users u', 'u.user_id = co.cleaner_id');
        $this->db->join('users h', 'h.user_id = co.host_id');
        $this->db->where('co.status', 'escalated');
        $this->db->order_by('co.escalated_at', 'ASC');
        
        return $this->db->get()->result();
    }

    /**
     * Host approves counter-offer
     */
    public function approve_counter_offer($counter_offer_id, $host_id, $response = null)
    {
        // Verify ownership
        $counter_offer = $this->get_counter_offer($counter_offer_id);
        if (!$counter_offer || $counter_offer->host_id != $host_id || $counter_offer->status != 'pending') {
            return false;
        }

        // Format final price as decimal
        $final_price = number_format((float)$counter_offer->proposed_price, 2, '.', '');
        
        // Update counter-offer
        $this->db->where('id', $counter_offer_id);
        $result = $this->db->update('counter_offers', [
            'status' => 'approved',
            'host_response' => $response,
            'final_price' => $final_price,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            // Update job with final price and complete it
            $this->db->where('id', $counter_offer->job_id);
            $this->db->update('jobs', [
                'final_price' => $final_price,
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s'),
                'dispute_window_ends_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $result;
    }

    /**
     * Host rejects counter-offer
     */
    public function reject_counter_offer($counter_offer_id, $host_id, $response = null)
    {
        // Verify ownership
        $counter_offer = $this->get_counter_offer($counter_offer_id);
        if (!$counter_offer || $counter_offer->host_id != $host_id || $counter_offer->status != 'pending') {
            return false;
        }

        // Format original price as decimal
        $original_price = number_format((float)$counter_offer->original_price, 2, '.', '');

        $this->db->trans_start();

        // Update counter-offer
        $this->db->where('id', $counter_offer_id);
        $result = $this->db->update('counter_offers', [
            'status' => 'rejected',
            'host_response' => $response,
            'final_price' => $original_price,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            // Update job with original price and complete it
            $this->db->where('id', $counter_offer->job_id);
            $this->db->update('jobs', [
                'final_price' => $original_price,
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s'),
                'dispute_window_ends_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Host escalates counter-offer to moderator
     */
    public function escalate_counter_offer($counter_offer_id, $host_id, $dispute_data = null)
    {
        // Verify ownership
        $counter_offer = $this->get_counter_offer($counter_offer_id);
        if (!$counter_offer || $counter_offer->host_id != $host_id || $counter_offer->status != 'pending') {
            return false;
        }

        // Prepare update data
        $update_data = [
            'status' => 'escalated',
            'escalated_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle dispute data if provided
        if ($dispute_data && is_array($dispute_data)) {
            $update_data['host_dispute_reason'] = $dispute_data['dispute_reason'] ?? null;
            $update_data['host_dispute_details'] = $dispute_data['dispute_details'] ?? null;
            $update_data['disputed_by_host'] = $dispute_data['disputed_by_host'] ?? false;
        } elseif ($dispute_data && is_string($dispute_data)) {
            // Legacy support for simple response string
            $update_data['host_response'] = $dispute_data;
        }

        // Update counter-offer
        $this->db->where('id', $counter_offer_id);
        return $this->db->update('counter_offers', $update_data);
        // Note: Job remains in 'price_adjustment_requested' status until moderator resolves
    }

    /**
     * Host disputes counter-offer
     */
    public function dispute_counter_offer($counter_offer_id, $host_id, $dispute_reason, $dispute_details)
    {
        // Verify ownership and status
        $counter_offer = $this->get_counter_offer($counter_offer_id);
        if (!$counter_offer || $counter_offer->host_id != $host_id || $counter_offer->status != 'pending') {
            return false;
        }

        // Update counter-offer to disputed status
        $this->db->where('id', $counter_offer_id);
        $result = $this->db->update('counter_offers', [
            'status' => 'disputed',
            'host_dispute_reason' => $dispute_reason,
            'host_dispute_details' => $dispute_details,
            'disputed_by_host' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            // Send notification to cleaner
            $this->load->model('M_notifications');
            $this->M_notifications->notify_price_adjustment_disputed(
                $counter_offer->cleaner_id,
                $counter_offer->job_id,
                $dispute_reason,
                $dispute_details
            );
        }

        return $result;
    }

    /**
     * Get disputed counter offers for cleaner
     */
    public function get_disputed_offers_for_cleaner($cleaner_id)
    {
        $this->db->select('
            co.*,
            j.title,
            j.description,
            j.address,
            j.scheduled_date,
            j.scheduled_time,
            h.username as host_username,
            h.first_name as host_first_name,
            h.last_name as host_last_name,
            h.email as host_email
        ');
        $this->db->from('counter_offers co');
        $this->db->join('jobs j', 'co.job_id = j.id');
        $this->db->join('users h', 'co.host_id = h.user_id');
        $this->db->where('co.cleaner_id', $cleaner_id);
        $this->db->where('co.status', 'disputed');
        $this->db->order_by('co.updated_at', 'DESC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Moderator resolves counter-offer
     */
    public function resolve_counter_offer($counter_offer_id, $moderator_id, $decision, $final_price, $notes = null)
    {
        // Verify counter-offer exists and is escalated
        $counter_offer = $this->get_counter_offer($counter_offer_id);
        if (!$counter_offer || $counter_offer->status != 'escalated') {
            return false;
        }

        // Format final price as decimal
        $final_price = number_format((float)$final_price, 2, '.', '');
        
        // Update counter-offer
        $this->db->where('id', $counter_offer_id);
        $result = $this->db->update('counter_offers', [
            'status' => $decision,
            'moderator_id' => $moderator_id,
            'moderator_decision' => $decision,
            'moderator_notes' => $notes,
            'final_price' => $final_price,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        if ($result) {
            // Update job with final price and complete it
            $this->db->where('id', $counter_offer->job_id);
            $this->db->update('jobs', [
                'final_price' => $final_price,
                'status' => 'completed',
                'completed_at' => date('Y-m-d H:i:s'),
                'dispute_window_ends_at' => date('Y-m-d H:i:s', strtotime('+24 hours')),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        return $result;
    }

    /**
     * Check if job has pending counter-offer
     */
    public function has_pending_counter_offer($job_id)
    {
        $this->db->where('job_id', $job_id);
        $this->db->where('status', 'pending');
        $this->db->where('expires_at >', date('Y-m-d H:i:s'));
        
        return $this->db->count_all_results('counter_offers') > 0;
    }

    /**
     * Get counter-offer for a job
     */
    public function get_counter_offer_for_job($job_id)
    {
        $this->db->where('job_id', $job_id);
        $this->db->order_by('created_at', 'DESC');
        
        return $this->db->get('counter_offers')->row();
    }

    /**
     * Clean up expired counter-offers
     */
    public function cleanup_expired_counter_offers()
    {
        $this->db->where('status', 'pending');
        $this->db->where('expires_at <=', date('Y-m-d H:i:s'));
        
        return $this->db->update('counter_offers', [
            'status' => 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get counter-offer statistics
     */
    public function get_counter_offer_stats($user_id = null, $user_type = 'all')
    {
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('counter_offers');
        
        if ($user_id) {
            if ($user_type === 'host') {
                $this->db->where('host_id', $user_id);
            } elseif ($user_type === 'cleaner') {
                $this->db->where('cleaner_id', $user_id);
            }
        }
        
        $this->db->group_by('status');
        
        $result = $this->db->get()->result();
        
        $stats = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0,
            'escalated' => 0
        ];
        
        foreach ($result as $row) {
            $stats[$row->status] = (int)$row->count;
        }
        
        return $stats;
    }
}
