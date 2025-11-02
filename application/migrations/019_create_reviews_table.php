<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_create_reviews_table extends CI_Migration {

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
            'reviewer_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'reviewee_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'rating' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => TRUE,
                'null' => FALSE,
                'comment' => 'Rating from 1 to 5 stars'
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'Review title'
            ),
            'comment' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Detailed review comment'
            ),
            'review_type' => array(
                'type' => 'ENUM',
                'constraint' => ['host_to_cleaner', 'cleaner_to_host'],
                'null' => FALSE,
                'comment' => 'Type of review'
            ),
            'review_categories' => array(
                'type' => 'JSON',
                'null' => TRUE,
                'comment' => 'Selected review categories (multiselect)'
            ),
            'is_verified' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether review is verified (job completed)'
            ),
            'review_window_expires_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the review window expires (48hrs from job completion)'
            ),
            'is_public' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Whether review is public'
            ),
            'is_edited' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether review has been edited (not allowed)'
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
        $this->dbforge->add_key('reviewer_id');
        $this->dbforge->add_key('reviewee_id');
        $this->dbforge->add_key('review_type');
        $this->dbforge->add_key('rating');
        $this->dbforge->add_key('created_at');
        $this->dbforge->add_key('review_window_expires_at');
        
        $this->dbforge->create_table('reviews');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE reviews ADD CONSTRAINT fk_reviews_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE reviews ADD CONSTRAINT fk_reviews_reviewer_id FOREIGN KEY (reviewer_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE reviews ADD CONSTRAINT fk_reviews_reviewee_id FOREIGN KEY (reviewee_id) REFERENCES users(user_id) ON DELETE CASCADE');
        
        // Add unique constraint to ensure one review per job per reviewer
        $this->db->query('ALTER TABLE reviews ADD CONSTRAINT uk_reviews_job_reviewer UNIQUE (job_id, reviewer_id)');
        
        // Add check constraint for rating range
        $this->db->query('ALTER TABLE reviews ADD CONSTRAINT chk_reviews_rating CHECK (rating >= 1 AND rating <= 5)');
    }

    public function down()
    {
        $this->dbforge->drop_table('reviews');
    }
}
