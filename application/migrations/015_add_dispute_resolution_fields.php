<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_dispute_resolution_fields extends CI_Migration {

    public function up()
    {
        // Add dispute resolution fields to jobs table
        $fields = array(
            'dispute_resolved_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When the dispute was resolved'
            ),
            'dispute_resolution' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'comment' => 'How the dispute was resolved (resolved_by_admin, etc.)'
            ),
            'dispute_resolution_notes' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Admin notes about the resolution'
            ),
            'payment_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Final payment amount to cleaner'
            ),
            'payment_released_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When payment was released'
            )
        );

        $this->dbforge->add_column('jobs', $fields);
    }

    public function down()
    {
        // Remove the added columns
        $this->dbforge->drop_column('jobs', 'dispute_resolved_at');
        $this->dbforge->drop_column('jobs', 'dispute_resolution');
        $this->dbforge->drop_column('jobs', 'dispute_resolution_notes');
        $this->dbforge->drop_column('jobs', 'payment_amount');
        $this->dbforge->drop_column('jobs', 'payment_released_at');
    }
}
