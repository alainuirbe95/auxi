<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Database Setup Controller
 * 
 * Handles database setup and debugging operations
 */
class DatabaseSetup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->dbforge();
    }

    /**
     * Setup job fields
     */
    public function setup_job_fields()
    {
        echo "<h2>Setting up Job Fields...</h2>";
        
        try {
            // Check if fields already exist
            $stmt = $this->db->query("DESCRIBE jobs");
            $columns = $stmt->result_array();
            
            $existing_fields = array_column($columns, 'Field');
            
            // Fields to add
            $fields_to_add = [
                'assigned_cleaner_id' => "INT(11) UNSIGNED NULL AFTER `final_price`",
                'otp_code' => "VARCHAR(10) NULL AFTER `assigned_cleaner_id`",
                'otp_used_at' => "DATETIME NULL AFTER `otp_code`",
                'started_at' => "DATETIME NULL AFTER `otp_used_at`",
                'completion_notes' => "TEXT NULL AFTER `started_at`",
                'completed_at' => "DATETIME NULL AFTER `completion_notes`"
            ];
            
            $added_fields = [];
            
            foreach ($fields_to_add as $field => $definition) {
                if (!in_array($field, $existing_fields)) {
                    try {
                        $sql = "ALTER TABLE jobs ADD COLUMN `$field` $definition";
                        $this->db->query($sql);
                        echo "<span style='color: green;'>✓ Added field: $field</span><br>";
                        $added_fields[] = $field;
                    } catch (Exception $e) {
                        echo "<span style='color: red;'>✗ Failed to add field $field: " . $e->getMessage() . "</span><br>";
                    }
                } else {
                    echo "<span style='color: blue;'>→ Field $field already exists</span><br>";
                }
            }
            
            // Add indexes if fields were added
            if (!empty($added_fields)) {
                echo "<br><h3>Adding Indexes...</h3>";
                
                $indexes_to_add = [
                    'assigned_cleaner_id' => "idx_assigned_cleaner_id",
                    'completed_at' => "idx_completed_at",
                    'started_at' => "idx_started_at"
                ];
                
                foreach ($indexes_to_add as $field => $index_name) {
                    if (in_array($field, $added_fields)) {
                        try {
                            $sql = "ALTER TABLE jobs ADD INDEX `$index_name` (`$field`)";
                            $this->db->query($sql);
                            echo "<span style='color: green;'>✓ Added index: $index_name</span><br>";
                        } catch (Exception $e) {
                            echo "<span style='color: red;'>✗ Failed to add index $index_name: " . $e->getMessage() . "</span><br>";
                        }
                    }
                }
                
                // Add foreign key constraint for assigned_cleaner_id
                if (in_array('assigned_cleaner_id', $added_fields)) {
                    try {
                        $sql = "ALTER TABLE jobs ADD CONSTRAINT `fk_jobs_assigned_cleaner_id` FOREIGN KEY (`assigned_cleaner_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL";
                        $this->db->query($sql);
                        echo "<span style='color: green;'>✓ Added foreign key constraint for assigned_cleaner_id</span><br>";
                    } catch (Exception $e) {
                        echo "<span style='color: red;'>✗ Failed to add foreign key constraint: " . $e->getMessage() . "</span><br>";
                    }
                }
            }
            
            echo "<br><h3>Final Jobs Table Structure:</h3>";
            
            // Show updated table structure
            $stmt = $this->db->query("DESCRIBE jobs");
            $columns = $stmt->result_array();
            
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            
            foreach ($columns as $column) {
                $row_color = in_array($column['Field'], ['assigned_cleaner_id', 'otp_code', 'otp_used_at', 'started_at', 'completion_notes', 'completed_at']) ? 'background-color: lightgreen;' : '';
                echo "<tr style='$row_color'>";
                echo "<td>" . $column['Field'] . "</td>";
                echo "<td>" . $column['Type'] . "</td>";
                echo "<td>" . $column['Null'] . "</td>";
                echo "<td>" . $column['Key'] . "</td>";
                echo "<td>" . $column['Default'] . "</td>";
                echo "<td>" . $column['Extra'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<br><p style='color: green; font-weight: bold;'>Setup completed!</p>";
            
        } catch (Exception $e) {
            echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span>";
        }
    }

    /**
     * Debug job start issue
     */
    public function debug_job_start()
    {
        $job_id = $this->input->get('job_id') ?: 14;
        $user_id = $this->input->get('user_id') ?: 96662118;
        $otp_code = $this->input->get('otp_code') ?: '111049';
        
        echo "<h2>Debugging Job Start Issue</h2>";
        echo "<p><strong>Job ID:</strong> $job_id</p>";
        echo "<p><strong>User ID:</strong> $user_id</p>";
        echo "<p><strong>OTP Code:</strong> $otp_code</p>";
        
        try {
            // Check if job exists
            echo "<h3>1. Job Details:</h3>";
            $job_query = $this->db->get_where('jobs', ['id' => $job_id]);
            
            if ($job_query->num_rows() == 0) {
                echo "<p style='color: red;'>❌ Job not found!</p>";
                return;
            }
            
            $job = $job_query->row_array();
            
            echo "<table border='1' style='border-collapse: collapse; margin-bottom: 20px;'>";
            foreach ($job as $field => $value) {
                $row_color = in_array($field, ['assigned_cleaner_id', 'otp_code', 'status']) ? 'background-color: lightyellow;' : '';
                echo "<tr style='$row_color'>";
                echo "<td><strong>$field</strong></td>";
                echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Check validation steps
            echo "<h3>2. Validation Steps:</h3>";
            
            // Step 1: Check assigned_cleaner_id
            echo "<p><strong>Step 1:</strong> Check assigned_cleaner_id</p>";
            if ($job['assigned_cleaner_id'] == $user_id) {
                echo "<p style='color: green;'>✅ assigned_cleaner_id matches user_id ($user_id)</p>";
            } else {
                echo "<p style='color: red;'>❌ assigned_cleaner_id ({$job['assigned_cleaner_id']}) does not match user_id ($user_id)</p>";
            }
            
            // Step 2: Check status
            echo "<p><strong>Step 2:</strong> Check job status</p>";
            if ($job['status'] == 'assigned') {
                echo "<p style='color: green;'>✅ Job status is 'assigned'</p>";
            } else {
                echo "<p style='color: red;'>❌ Job status is '{$job['status']}', expected 'assigned'</p>";
            }
            
            // Step 3: Check OTP code
            echo "<p><strong>Step 3:</strong> Check OTP code</p>";
            if ($job['otp_code'] == $otp_code) {
                echo "<p style='color: green;'>✅ OTP code matches</p>";
            } else {
                echo "<p style='color: red;'>❌ OTP code mismatch. Expected: '$otp_code', Found: '{$job['otp_code']}'</p>";
            }
            
            // Step 4: Check if OTP already used
            echo "<p><strong>Step 4:</strong> Check if OTP already used</p>";
            if (empty($job['otp_used_at'])) {
                echo "<p style='color: green;'>✅ OTP not used yet (otp_used_at is NULL)</p>";
            } else {
                echo "<p style='color: red;'>❌ OTP already used at: {$job['otp_used_at']}</p>";
            }
            
            // Check if required fields exist
            echo "<h3>3. Required Fields Check:</h3>";
            $required_fields = ['assigned_cleaner_id', 'otp_code', 'otp_used_at', 'started_at'];
            $stmt = $this->db->query("DESCRIBE jobs");
            $columns = $stmt->result_array();
            $existing_fields = array_column($columns, 'Field');
            
            foreach ($required_fields as $field) {
                if (in_array($field, $existing_fields)) {
                    echo "<p style='color: green;'>✅ Field '$field' exists</p>";
                } else {
                    echo "<p style='color: red;'>❌ Field '$field' is missing</p>";
                }
            }
            
            // Simulate the start job process
            echo "<h3>4. Simulating Job Start Process:</h3>";
            
            $all_valid = true;
            $errors = [];
            
            if ($job['assigned_cleaner_id'] != $user_id) {
                $all_valid = false;
                $errors[] = "assigned_cleaner_id mismatch";
            }
            
            if ($job['status'] != 'assigned') {
                $all_valid = false;
                $errors[] = "job status is not 'assigned'";
            }
            
            if ($job['otp_code'] != $otp_code) {
                $all_valid = false;
                $errors[] = "OTP code mismatch";
            }
            
            if (!empty($job['otp_used_at'])) {
                $all_valid = false;
                $errors[] = "OTP already used";
            }
            
            if ($all_valid) {
                echo "<p style='color: green; font-weight: bold;'>✅ All validations passed! Job should start successfully.</p>";
            } else {
                echo "<p style='color: red; font-weight: bold;'>❌ Validation failed:</p>";
                echo "<ul>";
                foreach ($errors as $error) {
                    echo "<li style='color: red;'>$error</li>";
                }
                echo "</ul>";
            }
            
            // Check recent job assignments
            echo "<h3>5. Recent Job Assignments:</h3>";
            $recent_jobs_query = $this->db->where('assigned_cleaner_id', $user_id)
                                        ->order_by('updated_at', 'DESC')
                                        ->limit(5)
                                        ->get('jobs');
            
            if ($recent_jobs_query->num_rows() == 0) {
                echo "<p>No jobs assigned to this user.</p>";
            } else {
                $recent_jobs = $recent_jobs_query->result_array();
                echo "<table border='1' style='border-collapse: collapse;'>";
                echo "<tr><th>Job ID</th><th>Title</th><th>Status</th><th>Assigned Cleaner ID</th><th>OTP Code</th><th>Updated At</th></tr>";
                foreach ($recent_jobs as $recent_job) {
                    echo "<tr>";
                    echo "<td>{$recent_job['id']}</td>";
                    echo "<td>" . htmlspecialchars($recent_job['title']) . "</td>";
                    echo "<td>{$recent_job['status']}</td>";
                    echo "<td>{$recent_job['assigned_cleaner_id']}</td>";
                    echo "<td>{$recent_job['otp_code']}</td>";
                    echo "<td>{$recent_job['updated_at']}</td>";
                    echo "</tr>";
                }
                echo "</table>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
        }
    }

    /**
     * Setup dispute fields
     */
    public function setup_dispute_fields()
    {
        echo "<h2>Setting up Dispute Fields for Jobs Table</h2>";
        
        try {
            // Check if fields already exist
            $stmt = $this->db->query("DESCRIBE jobs");
            $columns = $stmt->result_array();
            
            $existing_fields = array_column($columns, 'Field');
            
            // Fields to add
            $fields_to_add = [
                'dispute_window_ends_at' => "DATETIME NULL COMMENT 'When the 24-hour dispute window ends'",
                'disputed_at' => "DATETIME NULL COMMENT 'When the job was disputed'",
                'dispute_reason' => "TEXT NULL COMMENT 'Reason for dispute'",
                'dispute_resolved_at' => "DATETIME NULL COMMENT 'When the dispute was resolved'",
                'dispute_resolution' => "ENUM('resolved_in_favor_host', 'resolved_in_favor_cleaner', 'compromise') NULL COMMENT 'How the dispute was resolved'",
                'payment_released_at' => "DATETIME NULL COMMENT 'When payment was released to cleaner'",
                'payment_amount' => "DECIMAL(10,2) NULL COMMENT 'Amount released to cleaner'"
            ];
            
            $added_fields = [];
            
            foreach ($fields_to_add as $field => $definition) {
                if (!in_array($field, $existing_fields)) {
                    try {
                        $sql = "ALTER TABLE jobs ADD COLUMN `$field` $definition";
                        $this->db->query($sql);
                        echo "<span style='color: green;'>✓ Added field: $field</span><br>";
                        $added_fields[] = $field;
                    } catch (Exception $e) {
                        echo "<span style='color: red;'>✗ Failed to add field $field: " . $e->getMessage() . "</span><br>";
                    }
                } else {
                    echo "<span style='color: blue;'>→ Field $field already exists</span><br>";
                }
            }
            
            // Update the status enum to include 'closed'
            try {
                $this->db->query("ALTER TABLE jobs MODIFY COLUMN status ENUM('open', 'assigned', 'in_progress', 'completed', 'disputed', 'closed') DEFAULT 'open'");
                echo "<span style='color: green;'>✓ Updated status enum to include 'closed'</span><br>";
            } catch (Exception $e) {
                echo "<span style='color: red;'>✗ Failed to update status enum: " . $e->getMessage() . "</span><br>";
            }
            
            // Add indexes if fields were added
            if (!empty($added_fields)) {
                echo "<br><h3>Adding Indexes...</h3>";
                
                $indexes_to_add = [
                    'dispute_window_ends_at' => "idx_dispute_window_ends_at",
                    'payment_released_at' => "idx_payment_released_at"
                ];
                
                foreach ($indexes_to_add as $field => $index_name) {
                    if (in_array($field, $added_fields)) {
                        try {
                            $sql = "ALTER TABLE jobs ADD INDEX `$index_name` (`$field`)";
                            $this->db->query($sql);
                            echo "<span style='color: green;'>✓ Added index: $index_name</span><br>";
                        } catch (Exception $e) {
                            echo "<span style='color: red;'>✗ Failed to add index $index_name: " . $e->getMessage() . "</span><br>";
                        }
                    }
                }
            }
            
            // Create counter_offers table if it doesn't exist
            echo "<br><h3>Creating Counter Offers Table...</h3>";
            
            if (!$this->db->table_exists('counter_offers')) {
                $this->dbforge->add_field(array(
                    'id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'auto_increment' => TRUE
                    ),
                    'job_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'null' => FALSE
                    ),
                    'cleaner_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'null' => FALSE
                    ),
                    'host_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'null' => FALSE
                    ),
                    'original_price' => array(
                        'type' => 'DECIMAL',
                        'constraint' => '10,2',
                        'null' => FALSE
                    ),
                    'proposed_price' => array(
                        'type' => 'DECIMAL',
                        'constraint' => '10,2',
                        'null' => FALSE
                    ),
                    'reason' => array(
                        'type' => 'TEXT',
                        'null' => TRUE,
                        'comment' => 'Reason for price adjustment'
                    ),
                    'status' => array(
                        'type' => 'ENUM',
                        'constraint' => ['pending', 'approved', 'rejected', 'escalated'],
                        'default' => 'pending'
                    ),
                    'host_response' => array(
                        'type' => 'TEXT',
                        'null' => TRUE,
                        'comment' => 'Host response to counter-offer'
                    ),
                    'escalated_at' => array(
                        'type' => 'DATETIME',
                        'null' => TRUE,
                        'comment' => 'When escalated to moderator'
                    ),
                    'moderator_id' => array(
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => TRUE,
                        'null' => TRUE,
                        'comment' => 'Admin/moderator who resolved the counter-offer'
                    ),
                    'moderator_decision' => array(
                        'type' => 'ENUM',
                        'constraint' => ['approved', 'rejected', 'compromise'],
                        'null' => TRUE
                    ),
                    'moderator_notes' => array(
                        'type' => 'TEXT',
                        'null' => TRUE
                    ),
                    'final_price' => array(
                        'type' => 'DECIMAL',
                        'constraint' => '10,2',
                        'null' => TRUE,
                        'comment' => 'Final agreed price after moderation'
                    ),
                    'expires_at' => array(
                        'type' => 'DATETIME',
                        'null' => FALSE,
                        'comment' => 'When counter-offer expires (24 hours)'
                    ),
                    'created_at' => array(
                        'type' => 'DATETIME',
                        'null' => FALSE
                    ),
                    'updated_at' => array(
                        'type' => 'DATETIME',
                        'null' => FALSE
                    )
                ));
                
                $this->dbforge->add_key('id', TRUE);
                $this->dbforge->add_key('job_id');
                $this->dbforge->add_key('cleaner_id');
                $this->dbforge->add_key('host_id');
                $this->dbforge->add_key('status');
                $this->dbforge->add_key('expires_at');
                $this->dbforge->create_table('counter_offers');

                echo "<span style='color: green;'>✓ Created counter_offers table</span><br>";
            } else {
                echo "<span style='color: blue;'>→ counter_offers table already exists</span><br>";
            }
            
            echo "<br><h3>Final Jobs Table Structure:</h3>";
            
            // Show updated table structure
            $stmt = $this->db->query("DESCRIBE jobs");
            $columns = $stmt->result_array();
            
            echo "<table border='1' style='border-collapse: collapse;'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            
            foreach ($columns as $column) {
                $row_color = in_array($column['Field'], array_keys($fields_to_add)) ? 'background-color: lightgreen;' : '';
                echo "<tr style='$row_color'>";
                echo "<td>" . $column['Field'] . "</td>";
                echo "<td>" . $column['Type'] . "</td>";
                echo "<td>" . $column['Null'] . "</td>";
                echo "<td>" . $column['Key'] . "</td>";
                echo "<td>" . $column['Default'] . "</td>";
                echo "<td>" . $column['Extra'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            echo "<br><p style='color: green; font-weight: bold;'>Database setup completed successfully!</p>";
            
        } catch (Exception $e) {
            echo "<span style='color: red;'>Error: " . $e->getMessage() . "</span>";
        }
    }

    /**
     * Check application logs
     */
    public function check_logs()
    {
        $log_path = APPPATH . 'logs/';
        
        echo "<h2>Application Logs Check</h2>";
        
        if (!is_dir($log_path)) {
            echo "<p style='color: red;'>Logs directory not found: $log_path</p>";
            return;
        }
        
        $log_files = glob($log_path . '*.php');
        if (empty($log_files)) {
            echo "<p>No log files found.</p>";
            return;
        }
        
        // Get the most recent log file
        $latest_log = max($log_files);
        
        echo "<p><strong>Latest log file:</strong> " . basename($latest_log) . "</p>";
        
        // Read the last 50 lines of the log file
        $lines = file($latest_log);
        $recent_lines = array_slice($lines, -50);
        
        echo "<h3>Recent Log Entries (Last 50 lines):</h3>";
        echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 400px; overflow-y: auto;'>";
        
        foreach ($recent_lines as $line) {
            // Highlight error and debug lines
            if (strpos($line, 'ERROR') !== false) {
                echo "<span style='color: red; font-weight: bold;'>" . htmlspecialchars($line) . "</span>";
            } elseif (strpos($line, 'DEBUG') !== false) {
                echo "<span style='color: blue;'>" . htmlspecialchars($line) . "</span>";
            } else {
                echo htmlspecialchars($line);
            }
        }
        
        echo "</pre>";
        
        // Also check for specific job start related entries
        echo "<h3>Job Start Related Entries:</h3>";
        $job_start_lines = array_filter($lines, function($line) {
            return strpos($line, 'start_job') !== false || 
                   strpos($line, 'Job') !== false || 
                   strpos($line, '96662118') !== false ||
                   strpos($line, '111049') !== false;
        });
        
        if (empty($job_start_lines)) {
            echo "<p>No job start related entries found in logs.</p>";
        } else {
            echo "<pre style='background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
            foreach ($job_start_lines as $line) {
                echo htmlspecialchars($line);
            }
            echo "</pre>";
        }
    }

    /**
     * Debug dispute window status
     */
    public function debug_dispute_window()
    {
        echo "<h2>=== DISPUTE WINDOW DEBUG ===</h2>";
        
        // Get current time
        $current_time = date('Y-m-d H:i:s');
        $current_timestamp = time();
        echo "<p><strong>Current Time:</strong> $current_time (timestamp: $current_timestamp)</p>";
        
        // Get all completed jobs
        $query = $this->db->query("
            SELECT id, title, status, completed_at, dispute_window_ends_at, 
                   UNIX_TIMESTAMP(dispute_window_ends_at) as dispute_timestamp,
                   (UNIX_TIMESTAMP(dispute_window_ends_at) - $current_timestamp) as time_remaining_seconds
            FROM jobs 
            WHERE status = 'completed' 
            ORDER BY completed_at DESC 
            LIMIT 10
        ");
        
        $jobs = $query->result();
        
        if (empty($jobs)) {
            echo "<p>No completed jobs found.</p>";
        } else {
            echo "<p>Found " . count($jobs) . " completed jobs:</p>";
            
            foreach ($jobs as $job) {
                echo "<div style='border: 1px solid #ccc; padding: 10px; margin: 10px 0;'>";
                echo "<h4>Job ID: {$job->id}</h4>";
                echo "<p><strong>Title:</strong> {$job->title}</p>";
                echo "<p><strong>Status:</strong> {$job->status}</p>";
                echo "<p><strong>Completed At:</strong> {$job->completed_at}</p>";
                echo "<p><strong>Dispute Window Ends:</strong> {$job->dispute_window_ends_at}</p>";
                
                if ($job->dispute_timestamp) {
                    echo "<p><strong>Dispute Timestamp:</strong> {$job->dispute_timestamp}</p>";
                    echo "<p><strong>Time Remaining:</strong> {$job->time_remaining_seconds} seconds</p>";
                    
                    if ($job->time_remaining_seconds > 0) {
                        $hours = floor($job->time_remaining_seconds / 3600);
                        $minutes = floor(($job->time_remaining_seconds % 3600) / 60);
                        echo "<p><strong>Time Remaining:</strong> {$hours}h {$minutes}m</p>";
                        echo "<p style='color: green;'><strong>Status: ✅ CAN DISPUTE</strong></p>";
                    } else {
                        echo "<p style='color: red;'><strong>Status: ❌ DISPUTE WINDOW EXPIRED</strong></p>";
                    }
                } else {
                    echo "<p style='color: red;'><strong>Status: ❌ NO DISPUTE WINDOW SET</strong></p>";
                }
                echo "</div>";
            }
        }
    }

    /**
     * Setup dispute resolution fields
     */
    public function setup_dispute_resolution_fields()
    {
        echo "<h2>Setting up Dispute Resolution Fields</h2>";
        
        try {
            // Check if columns already exist and add only missing ones
            $existing_columns = array();
            $result = $this->db->query("SHOW COLUMNS FROM jobs");
            foreach ($result->result() as $row) {
                $existing_columns[] = $row->Field;
            }
            
            if (in_array('dispute_resolved_at', $existing_columns)) {
                echo "<p style='color: orange;'>⚠️ Column 'dispute_resolved_at' already exists</p>";
            } else {
                $this->db->query("ALTER TABLE jobs ADD COLUMN dispute_resolved_at DATETIME NULL COMMENT 'When the dispute was resolved'");
                echo "<p style='color: green;'>✅ Added 'dispute_resolved_at' column</p>";
            }

            if (in_array('dispute_resolution', $existing_columns)) {
                echo "<p style='color: orange;'>⚠️ Column 'dispute_resolution' already exists</p>";
            } else {
                $this->db->query("ALTER TABLE jobs ADD COLUMN dispute_resolution VARCHAR(100) NULL COMMENT 'How the dispute was resolved'");
                echo "<p style='color: green;'>✅ Added 'dispute_resolution' column</p>";
            }

            if (in_array('dispute_resolution_notes', $existing_columns)) {
                echo "<p style='color: orange;'>⚠️ Column 'dispute_resolution_notes' already exists</p>";
            } else {
                $this->db->query("ALTER TABLE jobs ADD COLUMN dispute_resolution_notes TEXT NULL COMMENT 'Admin notes about the resolution'");
                echo "<p style='color: green;'>✅ Added 'dispute_resolution_notes' column</p>";
            }

            if (in_array('payment_amount', $existing_columns)) {
                echo "<p style='color: orange;'>⚠️ Column 'payment_amount' already exists</p>";
            } else {
                $this->db->query("ALTER TABLE jobs ADD COLUMN payment_amount DECIMAL(10,2) NULL COMMENT 'Final payment amount to cleaner'");
                echo "<p style='color: green;'>✅ Added 'payment_amount' column</p>";
            }

            if (in_array('payment_released_at', $existing_columns)) {
                echo "<p style='color: orange;'>⚠️ Column 'payment_released_at' already exists</p>";
            } else {
                $this->db->query("ALTER TABLE jobs ADD COLUMN payment_released_at DATETIME NULL COMMENT 'When payment was released'");
                echo "<p style='color: green;'>✅ Added 'payment_released_at' column</p>";
            }

            echo "<p style='color: green; font-weight: bold;'>🎉 Dispute resolution fields setup complete!</p>";
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
}
