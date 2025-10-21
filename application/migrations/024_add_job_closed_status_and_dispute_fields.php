<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_job_closed_status_and_dispute_fields extends CI_Migration {

    public function up()
    {
        // Add new fields to jobs table
        $fields = array(
            'dispute_window_ends_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the 24-hour dispute window ends'
            ),
            'disputed_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the job was disputed'
            ),
            'dispute_reason' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Reason for dispute'
            ),
            'dispute_resolved_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the dispute was resolved'
            ),
            'dispute_resolution' => array(
                'type' => 'ENUM',
                'constraint' => ['resolved_in_favor_host', 'resolved_in_favor_cleaner', 'compromise'],
                'null' => TRUE,
                'comment' => 'How the dispute was resolved'
            ),
            'payment_released_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When payment was released to cleaner'
            ),
            'payment_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Amount released to cleaner'
            )
        );
        
        $this->dbforge->add_column('jobs', $fields);
        
        // Update the status enum to include 'closed'
        $this->db->query("ALTER TABLE jobs MODIFY COLUMN status ENUM('open', 'assigned', 'in_progress', 'completed', 'disputed', 'closed') DEFAULT 'open'");
        
        // Add indexes for the new fields
        $this->db->query('ALTER TABLE jobs ADD INDEX idx_dispute_window_ends_at (dispute_window_ends_at)');
        $this->db->query('ALTER TABLE jobs ADD INDEX idx_payment_released_at (payment_released_at)');
    }

    public function down()
    {
        // Remove the new fields
        $this->dbforge->drop_column('jobs', 'dispute_window_ends_at');
        $this->dbforge->drop_column('jobs', 'disputed_at');
        $this->dbforge->drop_column('jobs', 'dispute_reason');
        $this->dbforge->drop_column('jobs', 'dispute_resolved_at');
        $this->dbforge->drop_column('jobs', 'dispute_resolution');
        $this->dbforge->drop_column('jobs', 'payment_released_at');
        $this->dbforge->drop_column('jobs', 'payment_amount');
        
        // Revert status enum
        $this->db->query("ALTER TABLE jobs MODIFY COLUMN status ENUM('open', 'assigned', 'in_progress', 'completed', 'disputed') DEFAULT 'open'");
    }
}
