<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Debug_migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
        $this->load->database();
    }

    public function index()
    {
        echo "<h2>Migration Debug Information</h2>";
        
        // Check if migrations table exists
        if ($this->db->table_exists('migrations')) {
            echo "✅ Migrations table exists<br>";
            
            // Get current version from database
            $result = $this->db->get('migrations')->row();
            if ($result) {
                echo "Current version in database: " . $result->version . "<br>";
            } else {
                echo "No version found in migrations table<br>";
            }
        } else {
            echo "❌ Migrations table does not exist<br>";
        }
        
        echo "<br>";
        
        // Check migration config
        echo "<h3>Migration Configuration:</h3>";
        echo "Migration enabled: " . ($this->config->item('migration_enabled') ? 'Yes' : 'No') . "<br>";
        echo "Migration type: " . $this->config->item('migration_type') . "<br>";
        echo "Migration version: " . $this->config->item('migration_version') . "<br>";
        echo "Migration path: " . $this->config->item('migration_path') . "<br>";
        
        echo "<br>";
        
        // List migration files with their versions
        echo "<h3>Migration Files Analysis:</h3>";
        $migration_files = glob(APPPATH . 'migrations/*.php');
        $migration_versions = array();
        
        foreach ($migration_files as $file) {
            $filename = basename($file);
            if (preg_match('/^(\d+)_/', $filename, $matches)) {
                $version = (int)$matches[1];
                $migration_versions[$version] = $filename;
            }
        }
        
        ksort($migration_versions);
        
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Version</th><th>Filename</th><th>Status</th></tr>";
        
        foreach ($migration_versions as $version => $filename) {
            $status = "Not Run";
            if ($this->db->table_exists('migrations')) {
                $result = $this->db->where('version', $version)->get('migrations')->row();
                if ($result) {
                    $status = "✅ Run";
                }
            }
            echo "<tr><td>$version</td><td>$filename</td><td>$status</td></tr>";
        }
        echo "</table>";
        
        echo "<br>";
        
        // Try to run specific migrations
        echo "<h3>Manual Migration Test:</h3>";
        
        // Test our specific migration files
        $test_migrations = array(18, 19, 20, 21);
        
        foreach ($test_migrations as $version) {
            echo "Testing migration $version...<br>";
            
            try {
                $result = $this->migration->version($version);
                if ($result === FALSE) {
                    echo "❌ Migration $version failed: " . $this->migration->error_string() . "<br>";
                } else {
                    echo "✅ Migration $version completed successfully<br>";
                }
            } catch (Exception $e) {
                echo "❌ Migration $version error: " . $e->getMessage() . "<br>";
            }
        }
        
        echo "<br>";
        
        // Test table creation
        $this->test_tables();
    }
    
    private function test_tables()
    {
        echo "<h3>Final Table Test:</h3>";
        
        $tables_to_test = array(
            'user_profiles' => 'User Profiles',
            'reviews' => 'Reviews',
            'review_responses' => 'Review Responses'
        );
        
        foreach ($tables_to_test as $table => $name) {
            if ($this->db->table_exists($table)) {
                echo "✅ $name table exists<br>";
                
                // Show table structure
                $fields = $this->db->field_data($table);
                echo "&nbsp;&nbsp;&nbsp;Fields: " . count($fields) . "<br>";
            } else {
                echo "❌ $name table does not exist<br>";
            }
        }
        
        // Test jobs table extensions
        if ($this->db->field_exists('review_window_expires_at', 'jobs')) {
            echo "✅ Jobs table has review fields<br>";
        } else {
            echo "❌ Jobs table missing review fields<br>";
        }
    }
}
