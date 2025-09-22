<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_user_strikes_table extends CI_Migration {

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
            'job_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'dispute_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'reason' => array(
                'type' => 'ENUM',
                'constraint' => ['no_show', 'late_start', 'poor_quality', 'damage', 'incomplete_work', 'dispute_resolution', 'other'],
                'default' => 'other'
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'admin_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'is_active' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => '0=Strike removed, 1=Active strike'
            ),
            'expires_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the strike expires (if applicable)'
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('dispute_id');
        $this->dbforge->add_key('is_active');
        
        $this->dbforge->create_table('user_strikes');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE user_strikes ADD CONSTRAINT fk_user_strikes_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE user_strikes ADD CONSTRAINT fk_user_strikes_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE SET NULL');
        $this->db->query('ALTER TABLE user_strikes ADD CONSTRAINT fk_user_strikes_dispute_id FOREIGN KEY (dispute_id) REFERENCES disputes(id) ON DELETE SET NULL');
        $this->db->query('ALTER TABLE user_strikes ADD CONSTRAINT fk_user_strikes_admin_id FOREIGN KEY (admin_id) REFERENCES users(user_id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('user_strikes');
    }
}
