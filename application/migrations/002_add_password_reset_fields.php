<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migration: Add password reset functionality fields
 * 
 * This migration adds fields to support admin-initiated password resets
 * and force password changes on next login.
 */
class Migration_add_password_reset_fields extends CI_Migration {

    public function up() {
        // Add password reset fields
        $fields = array(
            'passwd_force_change' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => TRUE,
                'default' => 0,
                'comment' => 'Flag to force password change on next login'
            ),
            'passwd_temp_generated' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
                'comment' => 'Timestamp when temporary password was generated'
            )
        );
        
        $this->dbforge->add_column('users', $fields);
        
        // Add indexes for performance
        $this->db->query('CREATE INDEX idx_passwd_force_change ON users (passwd_force_change)');
        $this->db->query('CREATE INDEX idx_passwd_temp_generated ON users (passwd_temp_generated)');
    }

    public function down() {
        // Drop indexes
        $this->db->query('DROP INDEX idx_passwd_force_change ON users');
        $this->db->query('DROP INDEX idx_passwd_temp_generated ON users');
        
        // Drop columns
        $this->dbforge->drop_column('users', 'passwd_force_change');
        $this->dbforge->drop_column('users', 'passwd_temp_generated');
    }
}
