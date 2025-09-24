<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_counter_offer_fields extends CI_Migration {

    public function up()
    {
        // Add counter offer fields to offers table
        $fields = array(
            'counter_price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Counter offer price from cleaner'
            ),
            'original_price' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Original job price'
            ),
            'counter_message' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Message from cleaner with counter offer'
            ),
            'counter_offered_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When counter offer was made'
            ),
            'rejected_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'When offer was rejected'
            ),
            'rejection_reason' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'comment' => 'Reason for rejection'
            )
        );

        $this->dbforge->add_column('offers', $fields);
    }

    public function down()
    {
        // Remove the added columns
        $columns_to_drop = array(
            'counter_price',
            'original_price',
            'counter_message',
            'counter_offered_at',
            'rejected_at',
            'rejection_reason'
        );

        foreach ($columns_to_drop as $column) {
            $this->dbforge->drop_column('offers', $column);
        }
    }
}
