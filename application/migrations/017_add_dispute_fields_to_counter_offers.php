<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_dispute_fields_to_counter_offers extends CI_Migration {

    public function up()
    {
        // Add dispute-related fields to counter_offers table
        $this->dbforge->add_column('counter_offers', [
            'host_dispute_reason' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'Reason for host disputing the price adjustment'
            ],
            'host_dispute_details' => [
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Detailed explanation from host about the dispute'
            ],
            'disputed_by_host' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether the host disputed this price adjustment'
            ]
        ]);
    }

    public function down()
    {
        // Remove the added columns
        $this->dbforge->drop_column('counter_offers', 'host_dispute_reason');
        $this->dbforge->drop_column('counter_offers', 'host_dispute_details');
        $this->dbforge->drop_column('counter_offers', 'disputed_by_host');
    }
}
