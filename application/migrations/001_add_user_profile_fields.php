<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_user_profile_fields extends CI_Migration {

    public function up()
    {
        // Add new columns to the users table
        $fields = array(
            'first_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'after' => 'email'
            ),
            'last_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => TRUE,
                'after' => 'first_name'
            ),
            'phone' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => TRUE,
                'after' => 'last_name'
            ),
            'date_of_birth' => array(
                'type' => 'DATE',
                'null' => TRUE,
                'after' => 'phone'
            ),
            'address' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'date_of_birth'
            ),
            'city' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'after' => 'address'
            ),
            'country' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => TRUE,
                'after' => 'city'
            ),
            'email_verified' => array(
                'type' => "ENUM('0','1')",
                'default' => '0',
                'after' => 'country'
            ),
            'locked' => array(
                'type' => "ENUM('0','1')",
                'default' => '0',
                'after' => 'email_verified'
            ),
            'failed_logins' => array(
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'after' => 'locked'
            ),
            'login_count' => array(
                'type' => 'INT',
                'constraint' => 10,
                'default' => 0,
                'after' => 'failed_logins'
            ),
            'last_ip' => array(
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => TRUE,
                'after' => 'login_count'
            ),
            'notes' => array(
                'type' => 'TEXT',
                'null' => TRUE,
                'after' => 'last_ip'
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'notes'
            ),
            'modified_by' => array(
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => TRUE,
                'null' => TRUE,
                'after' => 'created_by'
            )
        );

        $this->dbforge->add_column('users', $fields);
    }

    public function down()
    {
        // Remove the added columns
        $columns_to_drop = array(
            'first_name',
            'last_name', 
            'phone',
            'date_of_birth',
            'address',
            'city',
            'country',
            'email_verified',
            'locked',
            'failed_logins',
            'login_count',
            'last_ip',
            'notes',
            'created_by',
            'modified_by'
        );

        foreach ($columns_to_drop as $column) {
            $this->dbforge->drop_column('users', $column);
        }
    }
}
