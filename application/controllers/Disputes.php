<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Disputes Controller
 * 
 * Handles job dispute functionality
 */
class Disputes extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('M_jobs');
        $this->load->model('M_notifications');
        $this->load->library('form_validation');
    }

    /**
     * Host view - List jobs ready for dispute
     */
    public function index()
    {
        // Check if user is logged in
        if (!$this->is_logged_in()) {
            redirect('app/login');
        }

        $user_id = $this->auth_user_id;
        $jobs = $this->M_jobs->get_jobs_ready_for_dispute($user_id);
        
        $data = [
            'jobs' => $jobs,
            'user_id' => $user_id
        ];
        
        $view["title"] = 'Completed Jobs';
        $view["page_icon"] = 'check-circle';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'host/dashboard'),
            array('title' => 'Completed Jobs', 'url' => 'host/completed-jobs', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/host_sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("host/disputes", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }

    /**
     * Host action - Dispute a job
     */
    public function dispute_job()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $user_id = $this->auth_user_id;
        $job_id = $this->input->post('job_id');
        $issues_json = $this->input->post('issues');
        $notes = $this->input->post('notes');

        // Decode the issues JSON string
        $issues = json_decode($issues_json, true);

        // Set validation rules
        $this->form_validation->set_rules('job_id', 'Job ID', 'required|integer');
        $this->form_validation->set_rules('notes', 'Notes', 'max_length[1000]');

        // Custom validation for issues
        if (empty($issues) || !is_array($issues)) {
            echo json_encode([
                'success' => false,
                'message' => 'Please select at least one issue to report.'
            ]);
            return;
        }

        if ($this->form_validation->run()) {
            // Format the dispute reason with issues and notes
            $dispute_reason = $this->formatDisputeReason($issues, $notes);
            
            // Add debug logging
            log_message('debug', "Dispute Controller - Attempting to dispute job $job_id for host $user_id");
            
            // Get job details for debugging
            $job = $this->M_jobs->get_job_by_id($job_id);
            $current_time = time();
            $dispute_end_time = $job && $job->dispute_window_ends_at ? strtotime($job->dispute_window_ends_at) : 0;
            
            log_message('debug', "Dispute Controller - Job details: ID=$job_id, Host=$user_id, Status=" . ($job ? $job->status : 'NOT_FOUND'));
            log_message('debug', "Dispute Controller - Time check: Current=$current_time, End=$dispute_end_time, Window=" . ($job ? $job->dispute_window_ends_at : 'NULL'));
            
            $result = $this->M_jobs->dispute_job($job_id, $user_id, $dispute_reason);

            if ($result) {
                log_message('debug', "Dispute Controller - Dispute successful for job $job_id");
                echo json_encode([
                    'success' => true,
                    'message' => 'Issue report submitted successfully. A moderator will review your report.'
                ]);
            } else {
                log_message('debug', "Dispute Controller - Dispute failed for job $job_id");
                
                // Add debug info to response
                $debug_info = [
                    'job_id' => $job_id,
                    'host_id' => $user_id,
                    'job_status' => $job ? $job->status : 'NOT_FOUND',
                    'job_host_id' => $job ? $job->host_id : 'N/A',
                    'current_time' => $current_time,
                    'dispute_end_time' => $dispute_end_time,
                    'dispute_window_ends_at' => $job ? $job->dispute_window_ends_at : 'NULL',
                    'time_remaining' => $dispute_end_time > $current_time ? $dispute_end_time - $current_time : 0
                ];
                
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to submit report. The review window may have expired.',
                    'debug' => $debug_info
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed: ' . validation_errors()
            ]);
        }
    }

    /**
     * Admin view - Manage all disputes
     */
    public function admin_index()
    {
        // Check if user is logged in and is admin
        if (!$this->is_logged_in()) {
            redirect('app/login');
        }

        if ($this->auth_level < 9) {
            redirect('app/login');
        }

        $disputes = $this->M_jobs->get_disputed_jobs_for_admin();
        
        $view = [
            'title' => 'Manage Disputes',
            'page_icon' => 'gavel',
            'disputes' => $disputes,
            'breadcrumbs' => [
                ['title' => 'Admin', 'url' => base_url('admin')],
                ['title' => 'Manage Disputes', 'url' => '']
            ]
        ];

        $this->load->view('admin/template/layout_with_sidebar', [
            'body' => $this->load->view('admin/disputes/manage', $view, true),
            'sidebar' => $this->load->view('admin/template/sidebar', [], true),
            'page_title' => $view['title'],
            'page_icon' => $view['page_icon'],
            'breadcrumbs' => $view['breadcrumbs']
        ]);
    }

    /**
     * Get dispute details for admin
     */
    public function get_dispute_details($dispute_id)
    {
        if (!$this->input->is_ajax_request() || !$this->require_min_level(9)) {
            show_404();
        }

        $dispute = $this->M_jobs->get_dispute_details($dispute_id);
        
        if ($dispute) {
            echo json_encode([
                'success' => true,
                'dispute' => $dispute
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Dispute not found'
            ]);
        }
    }

    /**
     * Resolve dispute with percentage-based payout
     */
    public function resolve_dispute()
    {
        if (!$this->input->is_ajax_request() || !$this->require_min_level(9)) {
            show_404();
        }

        $dispute_id = $this->input->post('dispute_id');
        $cleaner_percentage = $this->input->post('cleaner_percentage');
        $resolution_notes = $this->input->post('resolution_notes');

        // Set validation rules
        $this->form_validation->set_rules('dispute_id', 'Dispute ID', 'required|integer');
        $this->form_validation->set_rules('cleaner_percentage', 'Cleaner Percentage', 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]');
        $this->form_validation->set_rules('resolution_notes', 'Resolution Notes', 'max_length[1000]');

        if ($this->form_validation->run()) {
            $result = $this->M_jobs->resolve_dispute_with_percentage($dispute_id, $this->auth_user_id, $cleaner_percentage, $resolution_notes);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Dispute resolved successfully. Both parties have been notified.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to resolve dispute. Please try again.'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed: ' . validation_errors()
            ]);
        }
    }

    /**
     * Host view - Show disputed jobs
     */
    public function host_disputes()
    {
        // Check if user is logged in
        if (!$this->is_logged_in()) {
            redirect('app/login');
        }

        $user_id = $this->auth_user_id;
        $disputed_jobs = $this->M_jobs->get_host_disputed_jobs($user_id);
        
        // Debug: Let's see what we're getting
        echo "<!-- DEBUG: User ID: " . $user_id . " -->";
        echo "<!-- DEBUG: Disputed Jobs Count: " . count($disputed_jobs) . " -->";
        
        $view = [
            'title' => 'My Disputed Jobs',
            'page_icon' => 'exclamation-triangle',
            'disputed_jobs' => $disputed_jobs,
            'breadcrumbs' => [
                ['title' => 'Host Dashboard', 'url' => base_url('host')],
                ['title' => 'My Disputed Jobs', 'url' => '']
            ]
        ];

        $this->load->view('admin/template/layout_with_sidebar', [
            'body' => $this->load->view('host/disputed_jobs', $view, true),
            'sidebar' => $this->load->view('admin/template/host_sidebar', [], true),
            'page_title' => $view['title'],
            'page_icon' => $view['page_icon'],
            'breadcrumbs' => $view['breadcrumbs']
        ]);
    }

    /**
     * Cleaner view - Show disputed jobs
     */
    public function cleaner_disputes()
    {
        // Check if user is logged in
        if (!$this->is_logged_in()) {
            redirect('app/login');
        }

        $user_id = $this->auth_user_id;
        $disputed_jobs = $this->M_jobs->get_cleaner_disputed_jobs($user_id);
        
        $view = [
            'title' => 'My Disputed Jobs',
            'page_icon' => 'exclamation-triangle',
            'disputed_jobs' => $disputed_jobs,
            'breadcrumbs' => [
                ['title' => 'Cleaner Dashboard', 'url' => base_url('cleaner')],
                ['title' => 'My Disputed Jobs', 'url' => '']
            ]
        ];

        $this->load->view('admin/template/layout_with_sidebar', [
            'body' => $this->load->view('cleaner/disputed_jobs', $view, true),
            'sidebar' => $this->load->view('admin/template/cleaner_sidebar', [], true),
            'page_title' => $view['title'],
            'page_icon' => $view['page_icon'],
            'breadcrumbs' => $view['breadcrumbs']
        ]);
    }

    /**
     * Format dispute reason from issues and notes
     */
    private function formatDisputeReason($issues, $notes = '')
    {
        $issueLabels = [
            'quality' => 'Poor Quality Work',
            'incomplete' => 'Incomplete Work',
            'damage' => 'Property Damage',
            'equipment' => 'Equipment Issues',
            'behavior' => 'Unprofessional Behavior',
            'time' => 'Time Issues',
            'supplies' => 'Supply Issues',
            'other' => 'Other Issues'
        ];

        $formattedIssues = [];
        if (is_array($issues)) {
            foreach ($issues as $issue) {
                if (isset($issueLabels[$issue])) {
                    $formattedIssues[] = $issueLabels[$issue];
                }
            }
        }

        $reason = "Issues Reported:\n" . implode("\n", $formattedIssues);
        
        if (!empty($notes)) {
            $reason .= "\n\nAdditional Notes:\n" . $notes;
        }

        return $reason;
    }

    /**
     * Moderator view - List disputed jobs
     */
    public function moderator_index()
    {
        // Check if user is admin/moderator
        if (!$this->is_logged_in() || $this->auth_level < 8) {
            redirect('app/login');
        }

        $jobs = $this->M_jobs->get_disputed_jobs();
        
        $data = [
            'jobs' => $jobs
        ];
        
        $view["title"] = 'Disputed Jobs';
        $view["page_icon"] = 'gavel';
        $view["breadcrumbs"] = array(
            array('title' => 'Dashboard', 'url' => 'admin/dashboard'),
            array('title' => 'Disputed Jobs', 'url' => '', 'active' => true)
        );
        $view["sidebar"] = $this->load->view("admin/template/sidebar", NULL, TRUE);
        $view["body"] = $this->load->view("admin/disputes", $data, TRUE);
        
        $this->load->view("admin/template/layout_with_sidebar", $view);
    }


    /**
     * Host manually closes a job (releases payment)
     */
    public function close_job()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        if (!$this->is_logged_in()) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
            return;
        }

        $job_id = $this->input->post('job_id');
        $host_id = $this->auth_user_id;

        // Verify the job belongs to this host and is completed
        $job = $this->M_jobs->get_job_by_id($job_id);
        if (!$job || $job->host_id != $host_id || $job->status != 'completed') {
            echo json_encode(['success' => false, 'message' => 'Job not found or not in a closable state.']);
            return;
        }

        // Check if dispute window is still open
        if ($job->dispute_window_ends_at && strtotime($job->dispute_window_ends_at) < time()) {
            echo json_encode(['success' => false, 'message' => 'Dispute window has expired.']);
            return;
        }

        // Close the job and release payment
        $this->db->trans_start();

        // Update job status to closed
        $this->db->where('id', $job_id);
        $this->db->update('jobs', [
            'status' => 'closed',
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Release payment
        $payment_amount = $job->final_price ?: $job->accepted_price;
        $this->M_jobs->release_payment($job_id, $payment_amount);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            // Notify cleaner about payment release
            $this->load->model('M_notifications');
            $this->M_notifications->notify_payment_released(
                $job->assigned_cleaner_id,
                $job->title,
                $payment_amount,
                ['job_id' => $job_id]
            );

            echo json_encode(['success' => true, 'message' => 'Job closed successfully. Payment released to cleaner.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to close job. Please try again.']);
        }
    }

    /**
     * Auto-close jobs (cron job endpoint)
     */
    public function auto_close_jobs()
    {
        // This should be called by a cron job
        $closed_count = $this->M_jobs->auto_close_completed_jobs();
        
        echo json_encode([
            'success' => true,
            'message' => "Auto-closed $closed_count jobs and released payments."
        ]);
    }

    /**
     * Get dispute details (AJAX)
     */
    public function get_details($job_id)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $job = $this->M_jobs->get_job_by_id($job_id);
        
        if (!$job) {
            echo json_encode([
                'success' => false,
                'message' => 'Job not found.'
            ]);
            return;
        }

        // Get counter-offer details if exists
        $this->load->model('M_counter_offers');
        $counter_offer = $this->M_counter_offers->get_counter_offer_for_job($job_id);

        echo json_encode([
            'success' => true,
            'job' => $job,
            'counter_offer' => $counter_offer
        ]);
    }
}
