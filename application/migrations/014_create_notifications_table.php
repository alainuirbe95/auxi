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
            'type' => array(
                'type' => 'ENUM',
                'constraint' => ['offer_received', 'offer_accepted', 'offer_declined', 'job_assigned', 'job_started', 'job_completed', 'job_cancelled', 'payment_received', 'rating_received', 'system_alert'],
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
                'null' => TRUE
            ),
            'is_read' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'read_at' => array(
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
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('type');
        $this->dbforge->add_key('is_read');
        $this->dbforge->add_key('created_at');
        
        $this->dbforge->create_table('notifications');
    }

    public function down()
    {
        $this->dbforge->drop_table('notifications');
    }
}