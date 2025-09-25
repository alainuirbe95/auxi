<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_price_adjustment_status extends CI_Migration {

    public function up()
    {
        // Update the 'status' ENUM to include 'price_adjustment_requested'
        $this->db->query("ALTER TABLE `jobs` CHANGE `status` `status` ENUM('open','assigned','in_progress','completed','disputed','closed','price_adjustment_requested') DEFAULT 'open'");
    }

    public function down()
    {
        // Revert the 'status' ENUM (remove 'price_adjustment_requested')
        $this->db->query("ALTER TABLE `jobs` CHANGE `status` `status` ENUM('open','assigned','in_progress','completed','disputed','closed') DEFAULT 'open'");
    }
}
