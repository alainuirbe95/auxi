<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_db extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        // No authentication required for this test
    }
    
    public function index() {
        echo "<h2>Database Connection Test</h2>";
        
        try {
            // Test database connection
            $this->load->database();
            echo "✅ Connected to database: " . $this->db->database . "<br><br>";
            
            // Check if jobs table exists
            if ($this->db->table_exists('jobs')) {
                echo "✅ Jobs table exists<br>";
                
                // Show jobs table structure
                echo "<h3>Jobs Table Structure:</h3>";
                $fields = $this->db->field_data('jobs');
                echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                echo "<tr><th>Field</th><th>Type</th><th>Max Length</th><th>Primary Key</th><th>Null</th></tr>";
                
                foreach ($fields as $field) {
                    echo "<tr>";
                    echo "<td>" . $field->name . "</td>";
                    echo "<td>" . $field->type . "</td>";
                    echo "<td>" . $field->max_length . "</td>";
                    echo "<td>" . ($field->primary_key ? 'Yes' : 'No') . "</td>";
                    echo "<td>" . (isset($field->null) && $field->null ? 'Yes' : 'No') . "</td>";
                    echo "</tr>";
                }
                echo "</table><br>";
                
                // Check if there are any jobs
                $count = $this->db->count_all('jobs');
                echo "📊 Current jobs count: $count<br>";
                
                if ($count > 0) {
                    echo "<h3>Recent Jobs:</h3>";
                    $query = $this->db->select('id, title, host_id, status, created_at')
                                     ->order_by('created_at', 'DESC')
                                     ->limit(5)
                                     ->get('jobs');
                    
                    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                    echo "<tr><th>ID</th><th>Title</th><th>Host ID</th><th>Status</th><th>Created</th></tr>";
                    
                    foreach ($query->result() as $row) {
                        echo "<tr>";
                        echo "<td>" . $row->id . "</td>";
                        echo "<td>" . htmlspecialchars($row->title) . "</td>";
                        echo "<td>" . $row->host_id . "</td>";
                        echo "<td>" . $row->status . "</td>";
                        echo "<td>" . $row->created_at . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
                
            } else {
                echo "❌ Jobs table does NOT exist<br>";
                echo "<p>You need to run the database schema file to create the jobs table.</p>";
                echo "<p>Run the SQL file: <code>auxi_database_schema_fixed.sql</code></p>";
            }
            
            // Check users table too
            echo "<br><h3>Users Table Check:</h3>";
            if ($this->db->table_exists('users')) {
                echo "✅ Users table exists<br>";
                $user_count = $this->db->count_all('users');
                echo "📊 Users count: $user_count<br>";
                
                // Show sample users
                if ($user_count > 0) {
                    $query = $this->db->select('user_id, username, auth_level, banned')
                                     ->limit(3)
                                     ->get('users');
                    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
                    echo "<tr><th>User ID</th><th>Username</th><th>Auth Level</th><th>Banned</th></tr>";
                    foreach ($query->result() as $row) {
                        echo "<tr>";
                        echo "<td>" . $row->user_id . "</td>";
                        echo "<td>" . htmlspecialchars($row->username) . "</td>";
                        echo "<td>" . $row->auth_level . "</td>";
                        echo "<td>" . $row->banned . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                }
            } else {
                echo "❌ Users table does NOT exist<br>";
            }
            
        } catch (Exception $e) {
            echo "❌ Database connection failed: " . $e->getMessage();
        }
        
        echo "<br><hr>";
        echo "<h3>Test Form Submission</h3>";
        echo "<p>If the jobs table exists, try creating a job again.</p>";
        echo "<p>Check the CodeIgniter logs for detailed debugging information.</p>";
    }
}
?>
