<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_job_assignments_table extends CI_Migration {

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
            'qr_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE,
                'unique' => TRUE
            ),
            'passcode' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => FALSE
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => ['assigned', 'started', 'completed', 'cancelled', 'no_show'],
                'default' => 'assigned'
            ),
            'assigned_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            ),
            'started_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'completed_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'completion_notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'completion_photos' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of photo URLs'
            ),
            'host_confirmed_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'auto_confirmed_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'Auto-confirmed after 24h if no host response'
            ),
            'late_start_notified' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'no_show_notified' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
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
        $this->dbforge->add_key('qr_code');
        $this->dbforge->add_key('status');
        
        $this->dbforge->create_table('job_assignments');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE job_assignments ADD CONSTRAINT fk_job_assignments_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE job_assignments ADD CONSTRAINT fk_job_assignments_cleaner_id FOREIGN KEY (cleaner_id) REFERENCES users(user_id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('job_assignments');
    }
}
