<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_notifications_table extends CI_Migration {

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
            'type' => array(
                'type' => 'ENUM',
                'constraint' => ['offer_received', 'offer_accepted', 'offer_declined', 'job_assigned', 'job_reminder', 'late_start', 'job_completed', 'dispute_created', 'dispute_resolved', 'payout_processed', 'refund_processed'],
                'null' => FALSE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ),
            'message' => array(
                'type' => 'TEXT',
                'null' => FALSE
            ),
            'data' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Additional data for the notification'
            ),
            'channels' => array(
                'type' => 'JSON',
                'null' => FALSE,
                'comment' => 'Array of channels: email, sms, in_app'
            ),
            'email_sent' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'sms_sent' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'in_app_read' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'scheduled_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When to send the notification'
            ),
            'sent_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'read_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('type');
        $this->dbforge->add_key('in_app_read');
        $this->dbforge->add_key('scheduled_at');
        
        $this->dbforge->create_table('notifications');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE notifications ADD CONSTRAINT fk_notifications_user_id FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE notifications ADD CONSTRAINT fk_notifications_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('notifications');
    }
}
