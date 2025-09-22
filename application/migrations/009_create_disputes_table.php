<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_disputes_table extends CI_Migration {

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
            'host_id' => array(
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
            'dispute_type' => array(
                'type' => 'ENUM',
                'constraint' => ['quality', 'no_show', 'damage', 'incomplete', 'other'],
                'default' => 'quality'
            ),
            'reason' => array(
                'type' => 'TEXT',
                'null' => FALSE
            ),
            'host_evidence' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of photo URLs and descriptions'
            ),
            'cleaner_response' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'cleaner_evidence' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of photo URLs and descriptions'
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => ['open', 'under_review', 'resolved', 'closed'],
                'default' => 'open'
            ),
            'admin_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'admin_notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'resolution' => array(
                'type' => 'ENUM',
                'constraint' => ['host_favor', 'cleaner_favor', 'partial_refund', 'full_refund', 'no_action'],
                'null' => TRUE
            ),
            'refund_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE
            ),
            'cleaner_strike' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether cleaner receives a strike'
            ),
            'host_strike' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether host receives a strike'
            ),
            'resolved_at' => array(
                'type' => 'DATETIME',
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
        $this->dbforge->add_key('host_id');
        $this->dbforge->add_key('cleaner_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('admin_id');
        
        $this->dbforge->create_table('disputes');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE disputes ADD CONSTRAINT fk_disputes_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE disputes ADD CONSTRAINT fk_disputes_host_id FOREIGN KEY (host_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE disputes ADD CONSTRAINT fk_disputes_cleaner_id FOREIGN KEY (cleaner_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE disputes ADD CONSTRAINT fk_disputes_admin_id FOREIGN KEY (admin_id) REFERENCES users(user_id) ON DELETE SET NULL');
    }

    public function down()
    {
        $this->dbforge->drop_table('disputes');
    }
}
