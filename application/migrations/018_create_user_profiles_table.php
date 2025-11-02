<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_user_profiles_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'bio' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'User bio/description'
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
                'comment' => 'Public phone number'
            ),
            'service_areas' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of service areas/cities'
            ),
            'profile_picture_url' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'URL to profile picture'
            ),
            'cover_photo_url' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'URL to cover photo'
            ),
            'verification_status' => array(
                'type' => 'ENUM',
                'constraint' => ['unverified', 'pending', 'verified', 'rejected'],
                'default' => 'unverified',
                'comment' => 'Account verification status'
            ),
            'verification_documents' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of verification document URLs'
            ),
            'specialties' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of cleaning specialties'
            ),
            'availability_schedule' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'User availability schedule'
            ),
            'response_time_avg' => array(
                'type' => 'INT',
                'constraint' => 3,
                'null' => TRUE,
                'comment' => 'Average response time in minutes'
            ),
            'completion_rate' => array(
                'type' => 'DECIMAL',
                'constraint' => '5,2',
                'null' => TRUE,
                'default' => 100.00,
                'comment' => 'Job completion rate percentage'
            ),
            'total_jobs_completed' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total number of completed jobs'
            ),
            'average_rating' => array(
                'type' => 'DECIMAL',
                'constraint' => '3,2',
                'null' => TRUE,
                'comment' => 'Average rating received'
            ),
            'total_reviews' => array(
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Total number of reviews received'
            ),
            'is_public' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether profile is public (1) or private (0)'
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
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('verification_status');
        $this->dbforge->add_key('is_public');
        $this->dbforge->add_key('average_rating');
        
        $this->dbforge->create_table('user_profiles');
        
        // Add foreign key constraint
        $this->db->query('ALTER TABLE user_profiles ADD CONSTRAINT fk_user_profiles_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE');
        
        // Add unique constraint to ensure one profile per user
        $this->db->query('ALTER TABLE user_profiles ADD CONSTRAINT uk_user_profiles_user_id UNIQUE (user_id)');
    }

    public function down()
    {
        $this->dbforge->drop_table('user_profiles');
    }
}
