<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_expired_status extends CI_Migration {

    public function up()
    {
        // Add 'expired' to the status enum
        $this->db->query("ALTER TABLE jobs MODIFY COLUMN status ENUM('open', 'assigned', 'in_progress', 'completed', 'disputed', 'closed', 'cancelled', 'price_adjustment_requested', 'expired') DEFAULT 'open'");
    }

    public function down()
    {
        // Remove 'expired' from the status enum
        $this->db->query("ALTER TABLE jobs MODIFY COLUMN status ENUM('open', 'assigned', 'in_progress', 'completed', 'disputed', 'closed', 'cancelled', 'price_adjustment_requested') DEFAULT 'open'");
    }
}
