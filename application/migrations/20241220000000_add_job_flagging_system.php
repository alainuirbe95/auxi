<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_job_flagging_system extends CI_Migration {

    public function up()
    {
        // Add flag_count column to jobs table
        $this->dbforge->add_column('jobs', [
            'flag_count' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'comment' => 'Number of times this job has been flagged'
            ]
        ]);

        // Create job_flags table to track individual flags
        $this->dbforge->add_field([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ],
            'job_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'comment' => 'ID of the flagged job'
            ],
            'flagged_by_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'comment' => 'ID of the user who flagged the job'
            ],
            'flagged_by_user_type' => [
                'type' => 'ENUM("host", "cleaner", "admin")',
                'comment' => 'Type of user who flagged the job'
            ],
            'flag_reason' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'Optional reason for flagging'
            ],
            'flag_details' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Additional details about the flag'
            ],
            'status' => [
                'type' => 'ENUM("active", "resolved", "dismissed")',
                'default' => 'active',
                'comment' => 'Status of the flag'
            ],
            'resolved_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'ID of admin who resolved the flag'
            ],
            'resolved_at' => [
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the flag was resolved'
            ],
            'resolution_notes' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Admin notes about flag resolution'
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => FALSE
            ]
        ]);

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('flagged_by_user_id');
        $this->dbforge->add_key('status');
        $this->dbforge->create_table('job_flags');

        // Add foreign key constraints
        $this->db->query('ALTER TABLE job_flags ADD CONSTRAINT fk_job_flags_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE job_flags ADD CONSTRAINT fk_job_flags_flagged_by FOREIGN KEY (flagged_by_user_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE job_flags ADD CONSTRAINT fk_job_flags_resolved_by FOREIGN KEY (resolved_by) REFERENCES users(user_id) ON DELETE SET NULL');
    }

    public function down()
    {
        // Drop the job_flags table
        $this->dbforge->drop_table('job_flags');
        
        // Remove flag_count column from jobs table
        $this->dbforge->drop_column('jobs', 'flag_count');
    }
}
