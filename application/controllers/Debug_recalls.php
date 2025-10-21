<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Debug Recalls Controller
 * Temporary controller to debug recall issues
 */
class Debug_recalls extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_jobs');
        $this->load->model('M_users');
    }

    public function check_host($username = null)
    {
        if (!$username) {
            echo "<h2>Usage: /debug_recalls/check_host/host3</h2>";
            return;
        }

        echo "<h2>Debug Recalls for: {$username}</h2>";
        echo "<hr>";

        // Get user info
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('username', $username);
        $user = $this->db->get()->row();

        if (!$user) {
            echo "<p style='color: red;'>❌ User '{$username}' not found!</p>";
            return;
        }

        echo "<h3>User Information:</h3>";
        echo "<ul>";
        echo "<li><strong>User ID:</strong> {$user->user_id}</li>";
        echo "<li><strong>Username:</strong> {$user->username}</li>";
        echo "<li><strong>Auth Level:</strong> {$user->auth_level}</li>";
        echo "</ul>";

        $host_id = $user->user_id;

        echo "<hr>";
        echo "<h3>All Jobs for this Host:</h3>";

        // Get all jobs for this host
        $this->db->select('id, title, status, created_at, updated_at, scheduled_date');
        $this->db->from('jobs');
        $this->db->where('host_id', $host_id);
        $this->db->order_by('updated_at', 'DESC');
        $jobs = $this->db->get()->result();

        if (empty($jobs)) {
            echo "<p>No jobs found for this host.</p>";
        } else {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Scheduled</th><th>Created</th><th>Updated</th></tr>";
            foreach ($jobs as $job) {
                $status_color = $job->status === 'recalled' ? 'background-color: #ffc107;' : '';
                echo "<tr style='{$status_color}'>";
                echo "<td>{$job->id}</td>";
                echo "<td>" . htmlspecialchars($job->title) . "</td>";
                echo "<td><strong>{$job->status}</strong></td>";
                echo "<td>{$job->scheduled_date}</td>";
                echo "<td>{$job->created_at}</td>";
                echo "<td>{$job->updated_at}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }

        echo "<hr>";
        echo "<h3>Recalled Jobs Query Test:</h3>";

        // Test the recalled jobs query
        $this->db->select('j.*, u.username as cleaner_username');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->where('j.status', 'recalled');
        $recalled_jobs = $this->db->get()->result();

        echo "<p><strong>Query:</strong> " . $this->db->last_query() . "</p>";
        echo "<p><strong>Count:</strong> " . count($recalled_jobs) . "</p>";

        if (!empty($recalled_jobs)) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Cleaner</th></tr>";
            foreach ($recalled_jobs as $job) {
                echo "<tr>";
                echo "<td>{$job->id}</td>";
                echo "<td>" . htmlspecialchars($job->title) . "</td>";
                echo "<td>{$job->status}</td>";
                echo "<td>{$job->cleaner_username}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ No recalled jobs found with direct query.</p>";
        }

        echo "<hr>";
        echo "<h3>Past Jobs Query Test (closed + recalled):</h3>";

        // Test the past jobs query
        $this->db->select('j.*, u.username as cleaner_username');
        $this->db->from('jobs j');
        $this->db->join('users u', 'j.assigned_cleaner_id = u.user_id', 'left');
        $this->db->where('j.host_id', $host_id);
        $this->db->where_in('j.status', ['closed', 'recalled']);
        $past_jobs = $this->db->get()->result();

        echo "<p><strong>Query:</strong> " . $this->db->last_query() . "</p>";
        echo "<p><strong>Count:</strong> " . count($past_jobs) . "</p>";

        if (!empty($past_jobs)) {
            echo "<table border='1' cellpadding='5' cellspacing='0'>";
            echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Cleaner</th></tr>";
            foreach ($past_jobs as $job) {
                echo "<tr>";
                echo "<td>{$job->id}</td>";
                echo "<td>" . htmlspecialchars($job->title) . "</td>";
                echo "<td>{$job->status}</td>";
                echo "<td>{$job->cleaner_username}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p style='color: orange;'>⚠️ No past jobs found with direct query.</p>";
        }

        echo "<hr>";
        echo "<h3>Check Database Columns:</h3>";
        $columns = $this->db->list_fields('jobs');
        echo "<p><strong>Jobs table columns:</strong></p>";
        echo "<ul>";
        $recall_columns = ['recall_reason', 'recall_details', 'recall_severity', 'recalled_at', 'payment_released_at'];
        foreach ($recall_columns as $col) {
            $exists = in_array($col, $columns) ? '✅' : '❌';
            echo "<li>{$exists} {$col}</li>";
        }
        echo "</ul>";

        echo "<hr>";
        echo "<h3>Model Method Test:</h3>";

        // Test using the actual model methods
        try {
            $recalled_jobs_model = $this->M_jobs->get_host_recalled_jobs($host_id, []);
            echo "<p><strong>get_host_recalled_jobs() count:</strong> " . count($recalled_jobs_model) . "</p>";
            
            $past_jobs_model = $this->M_jobs->get_host_past_jobs($host_id, []);
            echo "<p><strong>get_host_past_jobs() count:</strong> " . count($past_jobs_model) . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error calling model methods: " . $e->getMessage() . "</p>";
        }

        echo "<hr>";
        echo "<h3>Past Jobs Query with Default Filters:</h3>";
        
        // Test with default date filters
        $default_filters = [
            'date_from' => date('Y-m-d', strtotime('-30 days')),
            'date_to' => date('Y-m-d')
        ];
        
        echo "<p><strong>Date From:</strong> {$default_filters['date_from']}</p>";
        echo "<p><strong>Date To:</strong> {$default_filters['date_to']}</p>";
        
        try {
            $past_jobs_filtered = $this->M_jobs->get_host_past_jobs($host_id, $default_filters);
            echo "<p><strong>get_host_past_jobs() with filters count:</strong> " . count($past_jobs_filtered) . "</p>";
            
            if (!empty($past_jobs_filtered)) {
                echo "<table border='1' cellpadding='5' cellspacing='0'>";
                echo "<tr><th>ID</th><th>Title</th><th>Status</th><th>Updated</th><th>Payment Released</th></tr>";
                foreach ($past_jobs_filtered as $job) {
                    echo "<tr>";
                    echo "<td>{$job->id}</td>";
                    echo "<td>" . htmlspecialchars($job->title) . "</td>";
                    echo "<td>{$job->status}</td>";
                    echo "<td>{$job->updated_at}</td>";
                    echo "<td>" . ($job->payment_released_at ?? 'NULL') . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
            echo "<p><strong>Last Query:</strong> " . $this->db->last_query() . "</p>";
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }

    public function fix_job($job_id)
    {
        echo "<h2>Fix Job Status</h2>";
        echo "<p>Attempting to fix job ID: {$job_id}</p>";
        
        // Get current job status
        $this->db->select('*');
        $this->db->from('jobs');
        $this->db->where('id', $job_id);
        $job = $this->db->get()->row();
        
        if (!$job) {
            echo "<p style='color: red;'>❌ Job not found!</p>";
            return;
        }
        
        echo "<p><strong>Current Status:</strong> '" . htmlspecialchars($job->status) . "'</p>";
        
        // Update to recalled
        $this->db->where('id', $job_id);
        $result = $this->db->update('jobs', ['status' => 'recalled', 'updated_at' => date('Y-m-d H:i:s')]);
        
        if ($result) {
            echo "<p style='color: green;'>✅ Job status updated to 'recalled'!</p>";
            echo "<p><strong>Query:</strong> " . $this->db->last_query() . "</p>";
        } else {
            $error = $this->db->error();
            echo "<p style='color: red;'>❌ Failed to update: " . print_r($error, true) . "</p>";
        }
        
        echo "<hr>";
        
        // Verify the update
        $this->db->select('*');
        $this->db->from('jobs');
        $this->db->where('id', $job_id);
        $updated_job = $this->db->get()->row();
        
        echo "<h3>Verified Job Data:</h3>";
        echo "<ul>";
        echo "<li><strong>Status:</strong> '" . htmlspecialchars($updated_job->status) . "'</li>";
        echo "<li><strong>Updated At:</strong> {$updated_job->updated_at}</li>";
        echo "<li><strong>Payment Released At:</strong> " . ($updated_job->payment_released_at ?? 'NULL') . "</li>";
        echo "<li><strong>Host ID:</strong> {$updated_job->host_id}</li>";
        echo "</ul>";
        
        echo "<hr>";
        echo "<p><a href='" . base_url('host/recalled-jobs') . "'>View Recalled Jobs</a></p>";
        echo "<p><a href='" . base_url('host/past-jobs') . "'>View Past Jobs</a></p>";
    }
}

