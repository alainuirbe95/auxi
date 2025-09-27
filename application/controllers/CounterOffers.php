<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Counter Offers Controller
 * 
 * Handles counter-offer functionality for price adjustments
 */
class CounterOffers extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Require host level authentication (level 6)
        if (!$this->require_min_level(6)) {
            redirect('app/login');
        }
        
        $this->load->model('M_counter_offers');
        $this->load->model('M_jobs');
        $this->load->model('M_notifications');
        $this->load->library('form_validation');
    }

    /**
     * Host view - List pending counter-offers
     */
    public function index()
    {
        // Check if user is logged in
        if (!$this->is_logged_in()) {
            redirect('app/login');
        }

        $user_id = $this->auth_user_id;
        
        // Get filter parameters
        $search = $this->input->get('search');
        $status_filter = $this->input->get('status');
        $sort_by = $this->input->get('sort') ?: 'created_at';
        $sort_order = $this->input->get('order') ?: 'desc';
        $price_min = $this->input->get('price_min');
        $price_max = $this->input->get('price_max');
        
        // Get dashboard statistics
        $stats = $this->M_jobs->get_price_adjustment_stats($user_id);
        
        // Get filtered counter offers
        $counter_offers = $this->M_jobs->get_price_adjustment_jobs_filtered($user_id, [
            'search' => $search,
            'status' => $status_filter,
            'sort_by' => $sort_by,
            'sort_order' => $sort_order,
            'price_min' => $price_min,
            'price_max' => $price_max
        ]);
        
        $data = [
            'counter_offers' => $counter_offers,
            'user_id' => $user_id,
            'stats' => $stats,
            'filters' => [
                'search' => $search,
                'status' => $status_filter,
                'sort_by' => $sort_by,
                'sort_order' => $sort_order,
                'price_min' => $price_min,
                'price_max' => $price_max
            ]
        ];
        
        $view["title"] = 'Price Adjustment Requests';
        $view["page_icon"] = 'dollar-sign';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'host/dashboard'),
            array('title' => 'Price Adjustments', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/host_sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("host/counter_offers", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Host action - Approve counter-offer
     */
    public function approve()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $counter_offer_id = $this->input->post('counter_offer_id');
        $response = $this->input->post('response');

        $result = $this->M_counter_offers->approve_counter_offer($counter_offer_id, $user_id, $response);

        if ($result) {
            // Get counter-offer details for notification
            $counter_offer = $this->M_counter_offers->get_counter_offer($counter_offer_id);
            $job = $this->M_jobs->get_job_by_id($counter_offer->job_id);
            
            // Notify cleaner
            $this->M_notifications->notify_counter_offer_resolved(
                $counter_offer->cleaner_id,
                $job->title,
                'approved',
                $counter_offer->proposed_price,
                [
                    'job_id' => $counter_offer->job_id,
                    'counter_offer_id' => $counter_offer_id
                ]
            );

            echo json_encode([
                'success' => true,
                'message' => 'Counter-offer approved successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to approve counter-offer.'
            ]);
        }
    }

    /**
     * Host action - Reject counter-offer
     */
    public function reject()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $counter_offer_id = $this->input->post('counter_offer_id');
        $response = $this->input->post('response');

        $result = $this->M_counter_offers->reject_counter_offer($counter_offer_id, $user_id, $response);

        if ($result) {
            // Get counter-offer details for notification
            $counter_offer = $this->M_counter_offers->get_counter_offer($counter_offer_id);
            $job = $this->M_jobs->get_job_by_id($counter_offer->job_id);
            
            // Notify cleaner
            $this->M_notifications->notify_counter_offer_resolved(
                $counter_offer->cleaner_id,
                $job->title,
                'rejected',
                $counter_offer->original_price,
                [
                    'job_id' => $counter_offer->job_id,
                    'counter_offer_id' => $counter_offer_id
                ]
            );

            echo json_encode([
                'success' => true,
                'message' => 'Counter-offer rejected.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to reject counter-offer.'
            ]);
        }
    }

    /**
     * Host action - Escalate counter-offer to moderator
     */
    public function escalate()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $counter_offer_id = $this->input->post('counter_offer_id');
        $response = $this->input->post('response');

        $result = $this->M_counter_offers->escalate_counter_offer($counter_offer_id, $user_id, $response);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Counter-offer escalated to moderator for review.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to escalate counter-offer.'
            ]);
        }
    }

    /**
     * Handle host response to price adjustment (accept or dispute)
     */
    public function respond()
    {
        // Set JSON header
        header('Content-Type: application/json');
        
        // Check if user is logged in and is a host
        if (!$this->is_logged_in() || $this->auth_level < 6) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $counter_offer_id = $this->input->post('counter_offer_id');
        $action_type = $this->input->post('action_type');
        
        if (!$counter_offer_id || !$action_type) {
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        // Get counter offer details
        $counter_offer = $this->M_counter_offers->get_counter_offer($counter_offer_id);
        if (!$counter_offer) {
            echo json_encode(['success' => false, 'message' => 'Counter offer not found']);
            return;
        }

        // Verify this counter offer belongs to the current host
        $job = $this->M_jobs->get_job_by_id($counter_offer->job_id);
        if (!$job || $job->host_id != $this->auth_user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            return;
        }

        try {
            if ($action_type === 'accept') {
                // Host accepts the price adjustment
                $accept_comments = $this->input->post('accept_comments');
                
                $result = $this->M_counter_offers->approve_counter_offer($counter_offer_id, $this->auth_user_id, $accept_comments);
                
                if ($result) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Price adjustment accepted successfully'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to accept price adjustment']);
                }
                
            } elseif ($action_type === 'dispute') {
                // Host disputes the price adjustment - set status to disputed
                $dispute_reason = $this->input->post('dispute_reason');
                $dispute_details = $this->input->post('dispute_details');
                
                if (!$dispute_reason || !$dispute_details) {
                    echo json_encode(['success' => false, 'message' => 'Dispute reason and details are required']);
                    return;
                }
                
                // Update counter offer to disputed status
                $result = $this->M_counter_offers->dispute_counter_offer($counter_offer_id, $this->auth_user_id, $dispute_reason, $dispute_details);
                
                if ($result) {
                    echo json_encode([
                        'success' => true, 
                        'message' => 'Price adjustment disputed successfully. The cleaner has been notified.'
                    ]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to submit dispute']);
                }
                
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid action type']);
            }
            
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Cleaner view - List disputed price adjustments
     */
    public function cleaner_disputes()
    {
        // Check if user is logged in and is a cleaner
        if (!$this->is_logged_in() || $this->auth_level < 6) {
            redirect('app/login');
        }

        $cleaner_id = $this->auth_user_id;
        
        // Get disputed counter offers for this cleaner
        $disputed_offers = $this->M_counter_offers->get_disputed_offers_for_cleaner($cleaner_id);
        
        $data = [
            'disputed_offers' => $disputed_offers,
            'cleaner_id' => $cleaner_id
        ];
        
        $view["title"] = 'Disputed Price Adjustments';
        $view["page_icon"] = 'exclamation-triangle';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'cleaner'),
            array('title' => 'Disputed Adjustments', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/cleaner_sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("cleaner/price_adjustment_disputes", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Moderator view - List escalated counter-offers
     */
    public function moderator_index()
    {
        // Check if user is admin/moderator
        if (!$this->is_logged_in() || $this->auth_level < 8) {
            redirect('app/login');
        }

        $counter_offers = $this->M_jobs->get_escalated_price_adjustments();
        
        $data = [
            'counter_offers' => $counter_offers
        ];
        
        $view["title"] = 'Escalated Price Adjustments';
        $view["page_icon"] = 'gavel';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'admin/dashboard'),
            array('title' => 'Price Adjustments', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("admin/counter_offers", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Moderator action - Resolve counter-offer
     */
    public function resolve()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Check if user is admin/moderator
        if (!$this->is_logged_in() || $this->auth_level < 8) {
            echo json_encode([
                'success' => false,
                'message' => 'Unauthorized access.'
            ]);
            return;
        }

        $moderator_id = $this->auth_user_id;
        $counter_offer_id = $this->input->post('counter_offer_id');
        $decision = $this->input->post('decision');
        $final_price = $this->input->post('final_price');
        $notes = $this->input->post('notes');

        $result = $this->M_counter_offers->resolve_counter_offer($counter_offer_id, $moderator_id, $decision, $final_price, $notes);

        if ($result) {
            // Get counter-offer details for notification
            $counter_offer = $this->M_counter_offers->get_counter_offer($counter_offer_id);
            $job = $this->M_jobs->get_job_by_id($counter_offer->job_id);
            
            // Notify both parties
            $this->M_notifications->notify_counter_offer_resolved(
                $counter_offer->cleaner_id,
                $job->title,
                $decision,
                $final_price,
                [
                    'job_id' => $counter_offer->job_id,
                    'counter_offer_id' => $counter_offer_id
                ]
            );

            $this->M_notifications->notify_counter_offer_resolved(
                $counter_offer->host_id,
                $job->title,
                $decision,
                $final_price,
                [
                    'job_id' => $counter_offer->job_id,
                    'counter_offer_id' => $counter_offer_id
                ]
            );

            echo json_encode([
                'success' => true,
                'message' => 'Counter-offer resolved successfully!'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to resolve counter-offer.'
            ]);
        }
    }

    /**
     * Get counter-offer details (AJAX)
     */
    public function get_details($counter_offer_id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $counter_offer = $this->M_counter_offers->get_counter_offer($counter_offer_id);
        
        if (!$counter_offer) {
            echo json_encode([
                'success' => false,
                'message' => 'Counter-offer not found.'
            ]);
            return;
        }

        // Get job details
        $job = $this->M_jobs->get_job_by_id($counter_offer->job_id);

        echo json_encode([
            'success' => true,
            'counter_offer' => $counter_offer,
            'job' => $job
        ]);
    }
}
