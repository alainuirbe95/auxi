<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_pending_verification_field extends CI_Migration {

    public function up()
    {
        // Add pending_verification field to users table
        $this->dbforge->add_column('users', array(
            'pending_verification' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'comment' => 'Flag to indicate if user is pending verification (1 = pending, 0 = verified)'
            )
        ));
        
        // Add index for better performance
        $this->db->query('CREATE INDEX idx_pending_verification ON users(pending_verification)');
    }

    public function down()
    {
        // Remove the pending_verification field
        $this->dbforge->drop_column('users', 'pending_verification');
    }
}
