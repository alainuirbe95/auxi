<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Create_payments_table extends CI_Migration {

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
            'host_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'cleaner_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'null' => FALSE
            ),
            'payment_type' => array(
                'type' => 'ENUM',
                'constraint' => ['job_payment', 'refund', 'payout', 'tip'],
                'default' => 'job_payment'
            ),
            'amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'comment' => 'Total amount'
            ),
            'platform_fee' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => FALSE,
                'comment' => '15% + $30 MXN'
            ),
            'cleaner_payout' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Amount paid to cleaner'
            ),
            'refund_amount' => array(
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => TRUE,
                'comment' => 'Amount refunded to host'
            ),
            'refund_percentage' => array(
                'type' => 'INT',
                'constraint' => 3,
                'null' => TRUE,
                'comment' => 'Refund percentage (25, 50, 100)'
            ),
            'status' => array(
                'type' => 'ENUM',
                'constraint' => ['pending', 'processing', 'completed', 'failed', 'refunded', 'disputed'],
                'default' => 'pending'
            ),
            'stripe_payment_intent_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),
            'stripe_connect_account_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'Cleaner Stripe Connect account'
            ),
            'stripe_transfer_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE,
                'comment' => 'Transfer ID for cleaner payout'
            ),
            'stripe_refund_id' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => TRUE
            ),
            'processed_at' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'refunded_at' => array(
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
        $this->dbforge->add_key('job_id');
        $this->dbforge->add_key('host_id');
        $this->dbforge->add_key('cleaner_id');
        $this->dbforge->add_key('status');
        $this->dbforge->add_key('stripe_payment_intent_id');
        
        $this->dbforge->create_table('payments');
        
        // Add foreign key constraints
        $this->db->query('ALTER TABLE payments ADD CONSTRAINT fk_payments_job_id FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE payments ADD CONSTRAINT fk_payments_host_id FOREIGN KEY (host_id) REFERENCES users(user_id) ON DELETE CASCADE');
        $this->db->query('ALTER TABLE payments ADD CONSTRAINT fk_payments_cleaner_id FOREIGN KEY (cleaner_id) REFERENCES users(user_id) ON DELETE CASCADE');
    }

    public function down()
    {
        $this->dbforge->drop_table('payments');
    }
}
