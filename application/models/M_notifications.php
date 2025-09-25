<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_notifications extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new notification
     */
    public function create_notification($user_id, $type, $title, $message, $data = null, $job_id = null)
    {
        // Check if notifications table exists
        if (!$this->db->table_exists('notifications')) {
            return false;
        }

        $notification_data = [
            'user_id' => $user_id,
            'job_id' => $job_id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data ? json_encode($data) : null,
            'channels' => json_encode(['in_app']), // Default to in-app notification
            'in_app_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        return $this->db->insert('notifications', $notification_data);
    }

    /**
     * Get notifications for a user
     */
    public function get_notifications($user_id, $limit = 20, $offset = 0, $unread_only = false)
    {
        // Check if notifications table exists
        if (!$this->db->table_exists('notifications')) {
            return [];
        }

        $this->db->select('*');
        $this->db->from('notifications');
        $this->db->where('user_id', $user_id);
        
        if ($unread_only) {
            $this->db->where('is_read', 0);
        }
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit, $offset);
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get unread notification count for a user
     */
    public function get_unread_count($user_id)
    {
        // Check if notifications table exists
        if (!$this->db->table_exists('notifications')) {
            return 0;
        }

        $this->db->select('COUNT(*) as count');
        $this->db->from('notifications');
        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->count : 0;
    }

    /**
     * Mark notification as read
     */
    public function mark_as_read($notification_id, $user_id)
    {
        // Check if notifications table exists
        if (!$this->db->table_exists('notifications')) {
            return false;
        }

        $this->db->where('id', $notification_id);
        $this->db->where('user_id', $user_id);
        
        return $this->db->update('notifications', [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function mark_all_as_read($user_id)
    {
        // Check if notifications table exists
        if (!$this->db->table_exists('notifications')) {
            return false;
        }

        $this->db->where('user_id', $user_id);
        $this->db->where('is_read', 0);
        
        return $this->db->update('notifications', [
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Delete old notifications (older than 30 days)
     */
    public function cleanup_old_notifications($days = 30)
    {
        // Check if notifications table exists
        if (!$this->db->table_exists('notifications')) {
            return false;
        }

        $cutoff_date = date('Y-m-d H:i:s', strtotime("-{$days} days"));
        
        $this->db->where('created_at <', $cutoff_date);
        $this->db->where('is_read', 1); // Only delete read notifications
        
        return $this->db->delete('notifications');
    }

    /**
     * Get notification by ID
     */
    public function get_notification_by_id($notification_id, $user_id)
    {
        // Check if notifications table exists
        if (!$this->db->table_exists('notifications')) {
            return false;
        }

        $this->db->select('*');
        $this->db->from('notifications');
        $this->db->where('id', $notification_id);
        $this->db->where('user_id', $user_id);
        
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Helper method to create common notification types
     */
    public function notify_offer_received($host_id, $job_id, $cleaner_name, $offer_amount)
    {
        return $this->create_notification(
            $host_id,
            'offer_received',
            'New Offer Received',
            "You received a new offer from {$cleaner_name} for $" . number_format($offer_amount, 2),
            ['job_id' => $job_id, 'cleaner_name' => $cleaner_name, 'offer_amount' => $offer_amount]
        );
    }

    public function notify_offer_accepted($cleaner_id, $job_id, $job_title, $data = null)
    {
        return $this->create_notification(
            $cleaner_id,
            'offer_accepted',
            'Offer Accepted!',
            "Your offer for '{$job_title}' has been accepted!",
            $data,
            $job_id
        );
    }

    public function notify_offer_declined($cleaner_id, $job_id, $job_title)
    {
        return $this->create_notification(
            $cleaner_id,
            'offer_declined',
            'Offer Declined',
            "Your offer for '{$job_title}' was not accepted.",
            ['job_id' => $job_id, 'job_title' => $job_title],
            $job_id
        );
    }

    public function notify_job_assigned($cleaner_id, $job_id, $job_title, $otp_code)
    {
        return $this->create_notification(
            $cleaner_id,
            'job_assigned',
            'Job Assigned!',
            "You've been assigned to '{$job_title}'. Your service code is: {$otp_code}",
            ['job_id' => $job_id, 'job_title' => $job_title, 'otp_code' => $otp_code],
            $job_id
        );
    }

    public function notify_job_started($host_id, $job_id, $job_title, $cleaner_name)
    {
        return $this->create_notification(
            $host_id,
            'job_started',
            'Service Started',
            "{$cleaner_name} has started working on '{$job_title}'",
            ['job_id' => $job_id, 'job_title' => $job_title, 'cleaner_name' => $cleaner_name]
        );
    }

    public function notify_job_completed($host_id, $job_title, $cleaner_name, $data = [])
    {
        return $this->create_notification(
            $host_id,
            'job_completed',
            'Service Completed',
            "{$cleaner_name} has completed '{$job_title}'",
            $data,
            $data['job_id'] ?? null
        );
    }

    public function notify_job_inconsistency($host_id, $job_title, $inconsistency_description, $data = [])
    {
        return $this->create_notification(
            $host_id,
            'job_inconsistency',
            'Service Issue Reported',
            "An issue has been reported for '{$job_title}': {$inconsistency_description}",
            $data,
            $data['job_id'] ?? null
        );
    }

    public function notify_counter_offer($host_id, $job_title, $original_price, $proposed_price, $data = [])
    {
        $price_difference = $proposed_price - $original_price;
        $difference_text = $price_difference > 0 ? "+$" . number_format($price_difference, 2) : "-$" . number_format(abs($price_difference), 2);
        
        return $this->create_notification(
            $host_id,
            'counter_offer',
            'Price Adjustment Request',
            "A price adjustment has been requested for '{$job_title}': {$difference_text} ($" . number_format($original_price, 2) . " → $" . number_format($proposed_price, 2) . ")",
            $data,
            $data['job_id'] ?? null
        );
    }

    public function notify_counter_offer_resolved($user_id, $job_title, $decision, $final_price, $data = [])
    {
        $decision_text = $decision === 'approved' ? 'approved' : 'rejected';
        
        return $this->create_notification(
            $user_id,
            'counter_offer_resolved',
            'Price Adjustment ' . ucfirst($decision_text),
            "Your price adjustment request for '{$job_title}' has been {$decision_text}. Final price: $" . number_format($final_price, 2),
            $data,
            $data['job_id'] ?? null
        );
    }

    public function notify_job_disputed($cleaner_id, $job_title, $dispute_reason, $data = [])
    {
        return $this->create_notification(
            $cleaner_id,
            'job_disputed',
            'Job Disputed',
            "The job '{$job_title}' has been disputed. Reason: {$dispute_reason}",
            $data,
            $data['job_id'] ?? null
        );
    }

    public function notify_payment_released($cleaner_id, $job_title, $amount, $data = [])
    {
        return $this->create_notification(
            $cleaner_id,
            'payment_released',
            'Payment Released',
            "Payment of $" . number_format($amount, 2) . " has been released for '{$job_title}'",
            $data,
            $data['job_id'] ?? null
        );
    }

    public function notify_dispute_resolved($user_id, $job_title, $resolution, $final_amount, $data = [])
    {
        $resolution_text = str_replace('_', ' ', $resolution);
        $resolution_text = ucwords($resolution_text);
        
        return $this->create_notification(
            $user_id,
            'dispute_resolved',
            'Dispute Resolved',
            "The dispute for '{$job_title}' has been resolved in favor of: {$resolution_text}. Amount: $" . number_format($final_amount, 2),
            $data,
            $data['job_id'] ?? null
        );
    }


    /**
     * Notify dispute resolution with percentage breakdown
     */
    public function notify_dispute_resolution($user_id, $job_title, $cleaner_percentage, $host_refund, $cleaner_amount, $resolution_notes = '', $data = [])
    {
        $user_type = $data['user_type'] ?? 'unknown';
        
        if ($user_type === 'host') {
            $message = "Dispute resolved for job '{$job_title}'. You will receive a refund of $" . number_format($host_refund, 2) . " (Cleaner receives {$cleaner_percentage}%)";
        } else {
            $message = "Dispute resolved for job '{$job_title}'. You will receive $" . number_format($cleaner_amount, 2) . " ({$cleaner_percentage}% of original amount)";
        }
        
        if (!empty($resolution_notes)) {
            $message .= ". Resolution notes: {$resolution_notes}";
        }
        
        return $this->create_notification(
            $user_id,
            'dispute_resolution',
            'Dispute Resolution',
            $message,
            $data,
            $data['job_id'] ?? null
        );
    }
}