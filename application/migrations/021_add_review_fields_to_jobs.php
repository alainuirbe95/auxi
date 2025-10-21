<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_review_fields_to_jobs extends CI_Migration {

    public function up()
    {
        // Add review-related fields to jobs table
        $fields = array(
            'review_window_expires_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the review window expires (48hrs from completion)',
                'after' => 'completed_at'
            ),
            'host_reviewed' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether host has reviewed the cleaner',
                'after' => 'review_window_expires_at'
            ),
            'cleaner_reviewed' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether cleaner has reviewed the host',
                'after' => 'host_reviewed'
            ),
            'reviews_sent_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When review notifications were sent',
                'after' => 'cleaner_reviewed'
            )
        );

        $this->dbforge->add_column('jobs', $fields);
        
        // Add indexes for better performance
        $this->db->query('CREATE INDEX idx_jobs_review_window ON jobs(review_window_expires_at)');
        $this->db->query('CREATE INDEX idx_jobs_review_status ON jobs(host_reviewed, cleaner_reviewed)');
    }

    public function down()
    {
        // Drop indexes
        $this->db->query('DROP INDEX idx_jobs_review_window ON jobs');
        $this->db->query('DROP INDEX idx_jobs_review_status ON jobs');
        
        // Remove the added columns
        $columns_to_drop = array(
            'review_window_expires_at',
            'host_reviewed',
            'cleaner_reviewed',
            'reviews_sent_at'
        );

        foreach ($columns_to_drop as $column) {
            $this->dbforge->drop_column('jobs', $column);
        }
    }
}
