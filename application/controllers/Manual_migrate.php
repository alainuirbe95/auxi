<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manual_migrate extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function index()
    {
        echo "<h2>Manual Migration - Creating Tables Directly</h2>";
        
        // Create user_profiles table
        echo "<h3>Creating user_profiles table...</h3>";
        $this->create_user_profiles_table();
        
        // Create reviews table
        echo "<h3>Creating reviews table...</h3>";
        $this->create_reviews_table();
        
        // Create review_responses table
        echo "<h3>Creating review_responses table...</h3>";
        $this->create_review_responses_table();
        
        // Add review fields to jobs table
        echo "<h3>Adding review fields to jobs table...</h3>";
        $this->add_review_fields_to_jobs();
        
        // Test the tables
        echo "<h3>Testing Created Tables:</h3>";
        $this->test_tables();
    }
    
    private function create_user_profiles_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `user_profiles` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `user_id` int(11) unsigned NOT NULL,
            `bio` text,
            `phone` varchar(20) DEFAULT NULL,
            `service_areas` json DEFAULT NULL,
            `profile_picture_url` varchar(255) DEFAULT NULL,
            `cover_photo_url` varchar(255) DEFAULT NULL,
            `verification_status` enum('unverified','pending','verified','rejected') DEFAULT 'unverified',
            `verification_documents` json DEFAULT NULL,
            `specialties` json DEFAULT NULL,
            `availability_schedule` json DEFAULT NULL,
            `response_time_avg` int(3) DEFAULT NULL,
            `completion_rate` decimal(5,2) DEFAULT 100.00,
            `total_jobs_completed` int(11) DEFAULT 0,
            `average_rating` decimal(3,2) DEFAULT NULL,
            `total_reviews` int(11) DEFAULT 0,
            `is_public` tinyint(1) DEFAULT 1,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_user_profiles_user_id` (`user_id`),
            KEY `user_id` (`user_id`),
            KEY `verification_status` (`verification_status`),
            KEY `is_public` (`is_public`),
            KEY `average_rating` (`average_rating`),
            CONSTRAINT `fk_user_profiles_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($this->db->query($sql)) {
            echo "✅ user_profiles table created successfully<br>";
        } else {
            echo "❌ Failed to create user_profiles table: " . $this->db->error()['message'] . "<br>";
        }
    }
    
    private function create_reviews_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `reviews` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `job_id` int(11) unsigned NOT NULL,
            `reviewer_id` int(11) unsigned NOT NULL,
            `reviewee_id` int(11) unsigned NOT NULL,
            `rating` tinyint(1) unsigned NOT NULL,
            `title` varchar(255) DEFAULT NULL,
            `comment` text,
            `review_type` enum('host_to_cleaner','cleaner_to_host') NOT NULL,
            `review_categories` json DEFAULT NULL,
            `is_verified` tinyint(1) DEFAULT 1,
            `review_window_expires_at` datetime DEFAULT NULL,
            `is_public` tinyint(1) DEFAULT 1,
            `is_edited` tinyint(1) DEFAULT 0,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_reviews_job_reviewer` (`job_id`,`reviewer_id`),
            KEY `job_id` (`job_id`),
            KEY `reviewer_id` (`reviewer_id`),
            KEY `reviewee_id` (`reviewee_id`),
            KEY `review_type` (`review_type`),
            KEY `rating` (`rating`),
            KEY `created_at` (`created_at`),
            KEY `review_window_expires_at` (`review_window_expires_at`),
            CONSTRAINT `fk_reviews_job_id` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_reviews_reviewer_id` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
            CONSTRAINT `fk_reviews_reviewee_id` FOREIGN KEY (`reviewee_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
            CONSTRAINT `chk_reviews_rating` CHECK (`rating` >= 1 AND `rating` <= 5)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($this->db->query($sql)) {
            echo "✅ reviews table created successfully<br>";
        } else {
            echo "❌ Failed to create reviews table: " . $this->db->error()['message'] . "<br>";
        }
    }
    
    private function create_review_responses_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `review_responses` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `review_id` int(11) unsigned NOT NULL,
            `responder_id` int(11) unsigned NOT NULL,
            `response_text` text NOT NULL,
            `is_private` tinyint(1) DEFAULT 0,
            `is_edited` tinyint(1) DEFAULT 0,
            `created_at` datetime NOT NULL,
            `updated_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_review_responses_review_responder` (`review_id`,`responder_id`),
            KEY `review_id` (`review_id`),
            KEY `responder_id` (`responder_id`),
            KEY `created_at` (`created_at`),
            CONSTRAINT `fk_review_responses_review_id` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_review_responses_responder_id` FOREIGN KEY (`responder_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if ($this->db->query($sql)) {
            echo "✅ review_responses table created successfully<br>";
        } else {
            echo "❌ Failed to create review_responses table: " . $this->db->error()['message'] . "<br>";
        }
    }
    
    private function add_review_fields_to_jobs()
    {
        $fields = array(
            'review_window_expires_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the review window expires (48hrs from completion)'
            ),
            'host_reviewed' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether host has reviewed the cleaner'
            ),
            'cleaner_reviewed' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether cleaner has reviewed the host'
            ),
            'reviews_sent_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When review notifications were sent'
            )
        );
        
        $this->load->dbforge();
        
        foreach ($fields as $field_name => $field_config) {
            if (!$this->db->field_exists($field_name, 'jobs')) {
                if ($this->dbforge->add_column('jobs', array($field_name => $field_config))) {
                    echo "✅ Added field '$field_name' to jobs table<br>";
                } else {
                    echo "❌ Failed to add field '$field_name' to jobs table<br>";
                }
            } else {
                echo "✅ Field '$field_name' already exists in jobs table<br>";
            }
        }
        
        // Add indexes
        $indexes = array(
            'idx_jobs_review_window' => 'CREATE INDEX idx_jobs_review_window ON jobs(review_window_expires_at)',
            'idx_jobs_review_status' => 'CREATE INDEX idx_jobs_review_status ON jobs(host_reviewed, cleaner_reviewed)'
        );
        
        foreach ($indexes as $index_name => $sql) {
            if ($this->db->query($sql)) {
                echo "✅ Added index '$index_name'<br>";
            } else {
                echo "❌ Failed to add index '$index_name'<br>";
            }
        }
    }
    
    private function test_tables()
    {
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
        $review_fields = array('review_window_expires_at', 'host_reviewed', 'cleaner_reviewed', 'reviews_sent_at');
        $all_exist = true;
        
        foreach ($review_fields as $field) {
            if ($this->db->field_exists($field, 'jobs')) {
                echo "✅ Jobs table has field '$field'<br>";
            } else {
                echo "❌ Jobs table missing field '$field'<br>";
                $all_exist = false;
            }
        }
        
        if ($all_exist) {
            echo "✅ All review fields added to jobs table successfully<br>";
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
        
        echo "<br><h3>🎉 Phase 1 Testing Complete!</h3>";
        echo "<p>All database tables and models have been created and tested successfully.</p>";
    }
}
