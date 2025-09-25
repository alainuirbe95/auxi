<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_job_inconsistencies_table extends CI_Migration {

    public function up()
    {
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
            'inconsistency_type' => array(
                'type' => 'ENUM',
                'constraint' => ['property_damage', 'missing_items', 'unexpected_conditions', 'access_issues', 'safety_concerns', 'other'],
                'default' => 'other'
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => FALSE
            ),
            'photos' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of photo URLs documenting the inconsistency'
            ),
            'severity' => array(
                'type' => 'ENUM',
                'constraint' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium'
            ),
            'reported_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            ),
            'host_notified' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether host has been notified of this inconsistency'
            ),
            'resolved' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether the inconsistency has been resolved'
            ),
            'resolution_notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
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
        $this->dbforge->add_key('inconsistency_type');
        $this->dbforge->add_key('severity');
        
        $this->dbforge->create_table('job_inconsistencies');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE job_inconsistencies ADD CONSTRAINT fk_job_inconsistencies_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE job_inconsistencies ADD CONSTRAINT fk_job_inconsistencies_cleaner_id FOREIGN KEY (cleaner_id) REFERENCES users(user_id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('job_inconsistencies');
    }
}
