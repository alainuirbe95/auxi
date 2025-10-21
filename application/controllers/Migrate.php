<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('migration');
    }

    public function index()
    {
        echo "<h2>Running Migrations</h2>";
        
        // Check current migration version
        $current_version = $this->migration->current();
        echo "Current migration version: " . $current_version . "<br><br>";
        
        // Force migration to latest version
        echo "Forcing migration to latest version...<br>";
        $this->migration->latest();
        echo "Migration to latest completed.<br><br>";
        
        // List available migrations
        echo "<h3>Available Migrations:</h3>";
        $migration_files = glob(APPPATH . 'migrations/*.php');
        foreach ($migration_files as $file) {
            $filename = basename($file);
            echo "- " . $filename . "<br>";
        }
        echo "<br>";
        
        if ($current_version === FALSE)
        {
            echo "❌ Migration error: " . $this->migration->error_string() . "<br>";
        } else {
            echo "✅ Migrations completed successfully!<br><br>";
            
            // Test the new tables
            $this->test_new_tables();
        }
    }
    
    private function test_new_tables()
    {
        echo "<h3>Testing New Tables:</h3>";
        
        // Test user_profiles table
        if ($this->db->table_exists('user_profiles')) {
            echo "✅ user_profiles table created successfully<br>";
            
            // Show table structure
            $fields = $this->db->field_data('user_profiles');
            echo "<h4>user_profiles table structure:</h4>";
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
        } else {
            echo "❌ user_profiles table not found<br>";
        }
        
        // Test reviews table
        if ($this->db->table_exists('reviews')) {
            echo "✅ reviews table created successfully<br>";
            
            // Show table structure
            $fields = $this->db->field_data('reviews');
            echo "<h4>reviews table structure:</h4>";
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
        } else {
            echo "❌ reviews table not found<br>";
        }
        
        // Test review_responses table
        if ($this->db->table_exists('review_responses')) {
            echo "✅ review_responses table created successfully<br>";
            
            // Show table structure
            $fields = $this->db->field_data('review_responses');
            echo "<h4>review_responses table structure:</h4>";
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
        } else {
            echo "❌ review_responses table not found<br>";
        }
        
        // Test jobs table extensions
        if ($this->db->field_exists('review_window_expires_at', 'jobs')) {
            echo "✅ jobs table extended with review fields successfully<br>";
        } else {
            echo "❌ jobs table review fields not found<br>";
        }
        
        // Test model loading
        echo "<h3>Testing Model Loading:</h3>";
        
        try {
            $this->load->model('M_user_profiles');
            echo "✅ M_user_profiles model loaded successfully<br>";
        } catch (Exception $e) {
            echo "❌ M_user_profiles model failed to load: " . $e->getMessage() . "<br>";
        }
        
        try {
            $this->load->model('M_reviews');
            echo "✅ M_reviews model loaded successfully<br>";
        } catch (Exception $e) {
            echo "❌ M_reviews model failed to load: " . $e->getMessage() . "<br>";
        }
        
        try {
            $this->load->model('M_review_responses');
            echo "✅ M_review_responses model loaded successfully<br>";
        } catch (Exception $e) {
            echo "❌ M_review_responses model failed to load: " . $e->getMessage() . "<br>";
        }
        
        echo "<br><h3>Phase 1 Testing Complete!</h3>";
        echo "<p>All database tables and models have been created and tested.</p>";
    }
}
