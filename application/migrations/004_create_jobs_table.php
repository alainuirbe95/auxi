<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_jobs_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'host_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => FALSE
            ),
            'description' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'address' => array(
                'type' => 'TEXT',
                'null' => FALSE
            ),
            'city' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ),
            'state' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => FALSE
            ),
            'zip_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE
            ),
            'latitude' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,8',
                'null' => TRUE
            ),
            'longitude' => array(
                'type' => 'DECIMAL',
                'constraint' => '11,8',
                'null' => TRUE
            ),
            'scheduled_date' => array(
                'type' => 'DATE',
                'null' => FALSE
            ),
            'scheduled_time' => array(
                'type' => 'TIME',
                'null' => FALSE
            ),
            'estimated_duration' => array(
                'type' => 'INT',
                'constraint' => 3,
                'null' => FALSE,
                'comment' => 'Duration in minutes'
            ),
            'rooms' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of room types and counts'
            ),
            'extras' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Array of extra services requested'
            ),
            'pets' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => '0=No pets, 1=Pets present'
            ),
            'pet_notes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'special_instructions' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'suggested_price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'comment' => 'System suggested price'
            ),
            'final_price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Final agreed price'
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => ['draft', 'open', 'offers_received', 'assigned', 'in_progress', 'completed', 'cancelled', 'disputed'],
                'default' => 'draft'
            ),
            'boost_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '8,2',
                'default' => 0,
                'comment' => 'Amount paid to boost listing visibility'
            ),
            'expires_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the job posting expires'
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            ),
            'updated_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            ),
            'modified_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => TRUE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('host_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('scheduled_date');
        $this->dbforge->add_key('city');
        $this->dbforge->add_key('created_at');
        
        $this->dbforge->create_table('jobs');
        
        // Add foreign key constraint
        $this->db->query('ALTER TABLE jobs ADD CONSTRAINT fk_jobs_host_id FOREIGN KEY (host_id) REFERENCES users(user_id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('jobs');
    }
}
