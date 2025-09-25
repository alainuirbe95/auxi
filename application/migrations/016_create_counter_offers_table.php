<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_counter_offers_table extends CI_Migration {

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
            'host_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'original_price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE
            ),
            'proposed_price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE
            ),
            'reason' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Reason for price adjustment'
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected', 'escalated'],
                'default' => 'pending'
            ),
            'host_response' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Host response to counter-offer'
            ),
            'escalated_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When escalated to moderator'
            ),
            'moderator_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE,
                'comment' => 'Admin/moderator who resolved the counter-offer'
            ),
            'moderator_decision' => array(
                'type' => 'ENUM',
                'constraint' => ['approved', 'rejected', 'compromise'],
                'null' => TRUE
            ),
            'moderator_notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'final_price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Final agreed price after moderation'
            ),
            'expires_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
                'comment' => 'When counter-offer expires (24 hours)'
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
        $this->dbforge->add_key('host_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('expires_at');
        $this->dbforge->create_table('counter_offers');

        // Add foreign key constraints
        $this->db->query('ALTER TABLE counter_offers ADD CONSTRAINT fk_counter_offers_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE counter_offers ADD CONSTRAINT fk_counter_offers_cleaner_id FOREIGN KEY (cleaner_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE counter_offers ADD CONSTRAINT fk_counter_offers_host_id FOREIGN KEY (host_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE counter_offers ADD CONSTRAINT fk_counter_offers_moderator_id FOREIGN KEY (moderator_id) REFERENCES users(user_id) ON DELETE SET NULL');
    }

    public function down()
    {
        $this->dbforge->drop_table('counter_offers');
    }
}
