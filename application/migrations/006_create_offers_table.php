<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_offers_table extends CI_Migration {

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
            'offer_type' => array(
                'type' => 'ENUM',
                'constraint' => ['accept', 'counter'],
                'default' => 'accept'
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => ['pending', 'accepted', 'declined', 'expired', 'cancelled'],
                'default' => 'pending'
            ),
            'expires_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
                'comment' => 'Offer expires after 3 hours'
            ),
            'responded_at' => array(
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
        $this->dbforge->add_key('cleaner_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('expires_at');
        
        $this->dbforge->create_table('offers');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE offers ADD CONSTRAINT fk_offers_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE offers ADD CONSTRAINT fk_offers_cleaner_id FOREIGN KEY (cleaner_id) REFERENCES users(user_id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('offers');
    }
}
