<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Job Expiration Controller
 * 
 * This controller handles automatic expiration of jobs that have passed their scheduled date
 * and are still in "open" status (never got assigned to a cleaner).
 * 
 * Can be run manually or via cron job for automated cleanup.
 */
class JobExpiration extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        // Load required models
        $this->load->model('M_jobs');
        
        // Set timezone
        date_default_timezone_set('America/Phoenix'); // Arizona timezone
    }

    /**
     * Main method to expire old jobs
     * Can be accessed via: /job-expiration/expire-jobs
     */
    public function expire_jobs()
    {
        echo "<h2>Job Expiration Process</h2>";
        echo "<p>Starting job expiration process at: " . date('Y-m-d H:i:s') . "</p>";
        
        try {
            // Get all open jobs that have passed their scheduled date
            $expired_jobs = $this->get_jobs_to_expire();
            
            if (empty($expired_jobs)) {
                echo "<p style='color: green;'>✅ No jobs found that need to be expired.</p>";
                echo "<p>All open jobs are either current or future scheduled.</p>";
                return;
            }
            
            echo "<p>Found " . count($expired_jobs) . " job(s) that need to be expired:</p>";
            echo "<ul>";
            
            $success_count = 0;
            $error_count = 0;
            
            foreach ($expired_jobs as $job) {
                echo "<li>";
                echo "<strong>Job ID:</strong> {$job->id} | ";
                echo "<strong>Title:</strong> " . htmlspecialchars($job->title) . " | ";
                echo "<strong>Host ID:</strong> {$job->host_id} | ";
                echo "<strong>Scheduled:</strong> {$job->scheduled_date} {$job->scheduled_time} | ";
                echo "<strong>Created:</strong> {$job->created_at}";
                
                // Attempt to expire the job
                if ($this->expire_single_job($job->id)) {
                    echo " <span style='color: green;'>✅ EXPIRED</span>";
                    $success_count++;
                } else {
                    echo " <span style='color: red;'>❌ FAILED</span>";
                    $error_count++;
                }
                echo "</li>";
            }
            
            echo "</ul>";
            
            // Summary
            echo "<hr>";
            echo "<h3>Summary</h3>";
            echo "<p><strong>Total jobs processed:</strong> " . count($expired_jobs) . "</p>";
            echo "<p><strong>Successfully expired:</strong> <span style='color: green;'>{$success_count}</span></p>";
            echo "<p><strong>Failed to expire:</strong> <span style='color: red;'>{$error_count}</span></p>";
            
            if ($success_count > 0) {
                echo "<p style='color: green;'>✅ Job expiration process completed successfully!</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error during job expiration process: " . $e->getMessage() . "</p>";
            log_message('error', 'Job expiration error: ' . $e->getMessage());
        }
        
        echo "<hr>";
        echo "<p>Process completed at: " . date('Y-m-d H:i:s') . "</p>";
    }

    /**
     * Get all jobs that should be expired
     * Jobs that are:
     * - Status = 'open'
     * - Scheduled date is in the past
     * - Not already expired
     */
    private function get_jobs_to_expire()
    {
        // Check if jobs table exists
        if (!$this->db->table_exists('jobs')) {
            log_message('error', 'Jobs table does not exist');
            return [];
        }

        $this->db->select('*');
        $this->db->from('jobs');
        $this->db->where('status', 'open');
        $this->db->where('scheduled_date <', date('Y-m-d'));
        $this->db->order_by('scheduled_date', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Expire a single job
     * Changes status from 'open' to 'expired'
     */
    private function expire_single_job($job_id)
    {
        try {
            // Check which columns exist in the jobs table
            $columns = $this->db->list_fields('jobs');
            
            $update_data = [
                'status' => 'expired'
            ];
            
            // Only add updated_at if the column exists
            if (in_array('updated_at', $columns)) {
                $update_data['updated_at'] = date('Y-m-d H:i:s');
            }
            
            // Only add expired_at if the column exists
            if (in_array('expired_at', $columns)) {
                $update_data['expired_at'] = date('Y-m-d H:i:s');
            }
            
            $this->db->where('id', $job_id);
            $this->db->where('status', 'open'); // Double-check status hasn't changed
            
            $result = $this->db->update('jobs', $update_data);
            
            if ($result && $this->db->affected_rows() > 0) {
                log_message('info', "Job ID {$job_id} successfully expired");
                return true;
            } else {
                log_message('warning', "Failed to expire job ID {$job_id} - may have been updated by another process");
                return false;
            }
            
        } catch (Exception $e) {
            log_message('error', "Error expiring job ID {$job_id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get statistics about job expiration
     * Shows counts of different job statuses
     */
    public function stats()
    {
        echo "<h2>Job Status Statistics</h2>";
        echo "<p>Current statistics at: " . date('Y-m-d H:i:s') . "</p>";
        
        if (!$this->db->table_exists('jobs')) {
            echo "<p style='color: red;'>❌ Jobs table does not exist</p>";
            return;
        }

        // Get job counts by status
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('jobs');
        $this->db->group_by('status');
        $query = $this->db->get();
        $status_counts = $query->result();

        echo "<h3>Jobs by Status</h3>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Status</th><th>Count</th></tr>";
        
        $total_jobs = 0;
        foreach ($status_counts as $status) {
            echo "<tr><td>" . ucfirst($status->status) . "</td><td>{$status->count}</td></tr>";
            $total_jobs += $status->count;
        }
        echo "<tr><th>Total</th><th>{$total_jobs}</th></tr>";
        echo "</table>";

        // Get jobs that should be expired
        $expired_jobs = $this->get_jobs_to_expire();
        echo "<h3>Jobs Ready for Expiration</h3>";
        echo "<p>Open jobs with past scheduled dates: <strong>" . count($expired_jobs) . "</strong></p>";
        
        if (!empty($expired_jobs)) {
            echo "<p style='color: orange;'>⚠️ These jobs can be expired by running: <a href='" . base_url('job-expiration/expire-jobs') . "'>/job-expiration/expire-jobs</a></p>";
        } else {
            echo "<p style='color: green;'>✅ No jobs need to be expired at this time.</p>";
        }
    }

    /**
     * Dry run - shows what would be expired without actually doing it
     */
    public function dry_run()
    {
        echo "<h2>Job Expiration Dry Run</h2>";
        echo "<p>This shows what jobs would be expired without actually changing them.</p>";
        echo "<p>Run at: " . date('Y-m-d H:i:s') . "</p>";
        
        $expired_jobs = $this->get_jobs_to_expire();
        
        if (empty($expired_jobs)) {
            echo "<p style='color: green;'>✅ No jobs would be expired.</p>";
            return;
        }
        
        echo "<p>Would expire " . count($expired_jobs) . " job(s):</p>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Job ID</th><th>Title</th><th>Host ID</th><th>Scheduled Date</th><th>Scheduled Time</th><th>Created</th><th>Days Past</th></tr>";
        
        foreach ($expired_jobs as $job) {
            $scheduled_datetime = $job->scheduled_date . ' ' . $job->scheduled_time;
            $days_past = floor((time() - strtotime($scheduled_datetime)) / (24 * 60 * 60));
            
            echo "<tr>";
            echo "<td>{$job->id}</td>";
            echo "<td>" . htmlspecialchars($job->title) . "</td>";
            echo "<td>{$job->host_id}</td>";
            echo "<td>{$job->scheduled_date}</td>";
            echo "<td>{$job->scheduled_time}</td>";
            echo "<td>{$job->created_at}</td>";
            echo "<td>{$days_past} days</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "<hr>";
        echo "<p>To actually expire these jobs, run: <a href='" . base_url('job-expiration/expire-jobs') . "'>/job-expiration/expire-jobs</a></p>";
    }

    /**
     * Manual expiration of a specific job by ID
     * Usage: /job-expiration/expire-job/123
     */
    public function expire_job($job_id = null)
    {
        if (!$job_id) {
            echo "<p style='color: red;'>❌ Job ID is required. Usage: /job-expiration/expire-job/123</p>";
            return;
        }

        echo "<h2>Manual Job Expiration</h2>";
        echo "<p>Attempting to expire Job ID: {$job_id}</p>";
        
        // Check if job exists and is in open status
        $this->db->select('*');
        $this->db->from('jobs');
        $this->db->where('id', $job_id);
        $query = $this->db->get();
        $job = $query->row();
        
        if (!$job) {
            echo "<p style='color: red;'>❌ Job ID {$job_id} not found.</p>";
            return;
        }
        
        echo "<p><strong>Job Details:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Title:</strong> " . htmlspecialchars($job->title) . "</li>";
        echo "<li><strong>Status:</strong> {$job->status}</li>";
        echo "<li><strong>Scheduled:</strong> {$job->scheduled_date} {$job->scheduled_time}</li>";
        echo "<li><strong>Host ID:</strong> {$job->host_id}</li>";
        echo "</ul>";
        
        if ($job->status !== 'open') {
            echo "<p style='color: orange;'>⚠️ Job is not in 'open' status (current: {$job->status}). Cannot expire.</p>";
            return;
        }
        
        if ($this->expire_single_job($job_id)) {
            echo "<p style='color: green;'>✅ Job ID {$job_id} successfully expired!</p>";
        } else {
            echo "<p style='color: red;'>❌ Failed to expire Job ID {$job_id}.</p>";
        }
    }
}
