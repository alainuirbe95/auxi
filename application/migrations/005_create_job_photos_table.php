<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_job_photos_table extends CI_Migration {

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
            'photo_url' => array(
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => FALSE
            ),
            'photo_type' => array(
                'type' => 'ENUM',
                'constraint' => ['before', 'after', 'reference', 'dispute'],
                'default' => 'reference'
            ),
            'uploaded_by' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'is_primary' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0
            ),
            'sort_order' => array(
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => FALSE
            )
        ));
        
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('photo_type');
        
        $this->dbforge->create_table('job_photos');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE job_photos ADD CONSTRAINT fk_job_photos_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE job_photos ADD CONSTRAINT fk_job_photos_uploaded_by FOREIGN KEY (uploaded_by) REFERENCES users(user_id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('job_photos');
    }
}
