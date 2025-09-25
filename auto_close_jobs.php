<?php
/**
 * Auto Close Jobs Cron Job
 * 
 * This script should be run every hour via cron to automatically close
 * completed jobs after their 24-hour dispute window expires.
 * 
 * Cron job example:
 * 0 * * * * /usr/bin/php /path/to/easyclean/auto_close_jobs.php
 */

// Set the path to CodeIgniter
$system_path = './system';
$application_folder = './application';

// Define constants
define('BASEPATH', $system_path.'/');
define('APPPATH', $application_folder.'/');

// Load CodeIgniter bootstrap
require_once BASEPATH.'core/CodeIgniter.php';

// Get CodeIgniter instance
$CI =& get_instance();

// Load the jobs model
$CI->load->model('M_jobs');

// Auto-close completed jobs
$closed_count = $CI->M_jobs->auto_close_completed_jobs();

// Log the results
log_message('info', "Auto-close jobs cron: Closed $closed_count jobs and released payments.");

// Output for cron logs
echo "Auto-close jobs completed: $closed_count jobs closed and payments released.\n";
?>
