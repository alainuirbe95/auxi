<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_review_responses_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'review_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'responder_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'response_text' => array(
                'type' => 'TEXT',
                'null' => FALSE,
                'comment' => 'Response text to the review'
            ),
            'is_private' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether response is private (0=public, 1=private)'
            ),
            'is_edited' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether response has been edited'
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
        $this->dbforge->add_key('review_id');
        $this->dbforge->add_key('responder_id');
        $this->dbforge->add_key('created_at');
        
        $this->dbforge->create_table('review_responses');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE review_responses ADD CONSTRAINT fk_review_responses_review_id FOREIGN KEY (review_id) REFERENCES reviews(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE review_responses ADD CONSTRAINT fk_review_responses_responder_id FOREIGN KEY (responder_id) REFERENCES users(user_id) ON DELETE CASCADE');
        
        // Add unique constraint to ensure one response per review per responder
        $this->db->query('ALTER TABLE review_responses ADD CONSTRAINT uk_review_responses_review_responder UNIQUE (review_id, responder_id)');
    }

    public function down()
    {
        $this->dbforge->drop_table('review_responses');
    }
}
