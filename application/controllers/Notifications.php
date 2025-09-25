<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Load required models
        $this->load->model('M_notifications');
        
        // Check authentication
        if (!$this->auth_user_id) {
            $this->session->set_flashdata('text', 'You must be logged in to view notifications.');
            $this->session->set_flashdata('type', 'error');
            redirect('app/login');
        }
    }

    /**
     * Get notifications for current user (AJAX)
     */
    public function get_notifications()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $unread_only = $this->input->get('unread_only') === 'true';
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;

        $notifications = $this->M_notifications->get_user_notifications($user_id, $limit, $unread_only);
        
        // Format notifications for JSON response
        $formatted_notifications = [];
        foreach ($notifications as $notification) {
            $data = $notification->data ? json_decode($notification->data, true) : null;
            
            $formatted_notifications[] = [
                'id' => $notification->id,
                'type' => $notification->type,
                'title' => $notification->title,
                'message' => $notification->message,
                'data' => $data,
                'read_at' => $notification->read_at,
                'created_at' => $notification->created_at,
                'time_ago' => $this->time_ago($notification->created_at)
            ];
        }

        $unread_count = $this->M_notifications->get_unread_count($user_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'notifications' => $formatted_notifications,
                'unread_count' => $unread_count
            ]));
    }

    /**
     * Mark notification as read
     */
    public function mark_read()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $notification_id = $this->input->post('notification_id');
        $user_id = $this->auth_user_id;

        if (!$notification_id) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'success' => false,
                    'message' => 'Notification ID is required'
                ]));
            return;
        }

        $result = $this->M_notifications->mark_as_read($notification_id, $user_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'Notification marked as read' : 'Failed to mark notification as read'
            ]));
    }

    /**
     * Mark all notifications as read
     */
    public function mark_all_read()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $result = $this->M_notifications->mark_all_as_read($user_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => $result,
                'message' => $result ? 'All notifications marked as read' : 'Failed to mark notifications as read'
            ]));
    }

    /**
     * Get unread count
     */
    public function get_unread_count()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $unread_count = $this->M_notifications->get_unread_count($user_id);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'success' => true,
                'unread_count' => $unread_count
            ]));
    }

    /**
     * Time ago helper function
     */
    private function time_ago($datetime)
    {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'just now';
        if ($time < 3600) return floor($time/60) . ' minutes ago';
        if ($time < 86400) return floor($time/3600) . ' hours ago';
        if ($time < 2592000) return floor($time/86400) . ' days ago';
        if ($time < 31536000) return floor($time/2592000) . ' months ago';
        return floor($time/31536000) . ' years ago';
    }
}
