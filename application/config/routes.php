<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'app/home';

// Public pages
$route['app/for-cleaners'] = 'app/for_cleaners';
$route['app/for-hosts'] = 'app/for_hosts';
$route['app/faq'] = 'app/faq';

// $route['comment/(:any)'] = 'comment/$1';
// $route['spa/favorite_save/(:num)'] = 'spa/favorite_save/$1';
// $route['spa/favorite_delete/(:num)'] = 'spa/favorite_delete/$1';
// $route['spa/category/(:any)'] = 'spa/category/$1';
// $route['spa/(:num)'] = 'spa/index/$1';
// $route['spa/(:any)/(:any)'] = 'spa/post_view/$1/$2';
// $route['blog/favorite_save/(:num)'] = 'blog/favorite_save/$1';
// $route['blog/favorite_delete/(:num)'] = 'blog/favorite_delete/$1';
// $route['blog/category/(:any)'] = 'blog/category/$1';
// $route['blog/(:num)'] = 'blog/index/$1';
// $route['blog/(:any)/(:any)'] = 'blog/post_view/$1/$2';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route[LOGIN_PAGE] = 'app/login';
$route['register'] = 'app/register';

// Host routes
$route['host'] = 'host/index';
$route['host/create_job'] = 'host/create_job';
$route['host/process_create_job'] = 'host/process_create_job';
$route['host/jobs'] = 'host/jobs';
$route['host/job/(:num)'] = 'host/view_job/$1';
$route['host/edit_job/(:num)'] = 'host/edit_job/$1';
$route['host/process_edit_job/(:num)'] = 'host/process_edit_job/$1';
$route['host/cancel_job'] = 'host/cancel_job';
$route['host/delete_job'] = 'host/delete_job';
$route['host/offers'] = 'host/offers';
$route['host/expired-jobs'] = 'host/expired_jobs';
$route['host/repost-job'] = 'host/repost_job';
$route['host/bulk-repost-jobs'] = 'host/bulk_repost_jobs';
$route['host/delete-expired-job'] = 'host/delete_expired_job';
$route['host/accept_offer/(:num)'] = 'host/accept_offer/$1';
$route['host/reject_offer/(:num)'] = 'host/reject_offer/$1';

// Host Profile routes (Phase 2)
$route['host/my-profile'] = 'host/my_profile';
$route['host/edit-profile'] = 'host/edit_my_profile';
$route['host/update-profile'] = 'host/update_my_profile';
$route['host/test-redirect'] = 'host/test_redirect';
$route['host/payment-history'] = 'host/payment_history';
$route['host/cleaner/(:num)/offer/(:num)'] = 'host/view_cleaner_profile/$1/$2';

// Public Profile routes
$route['cleaner/public-profile/(:num)'] = 'cleaner/public_profile/$1';
$route['host/public-profile/(:num)'] = 'host/public_profile/$1';

// Cleaner routes
$route['cleaner'] = 'cleaner/index';
$route['cleaner/jobs'] = 'cleaner/jobs';
$route['cleaner/job/(:num)'] = 'cleaner/job/$1';
$route['cleaner/make_offer/(:num)'] = 'cleaner/make_offer/$1';
$route['cleaner/offers'] = 'cleaner/offers';
$route['cleaner/rejected_offers'] = 'cleaner/rejected_offers';
$route['cleaner/assigned_jobs'] = 'cleaner/assigned_jobs';
$route['cleaner/start_job'] = 'cleaner/start_job';
$route['cleaner/start_job_page/(:num)'] = 'cleaner/start_job_page/$1';

// Cleaner Profile routes
$route['cleaner/my-profile'] = 'cleaner/my_profile';
$route['cleaner/edit-profile'] = 'cleaner/edit_my_profile';
$route['cleaner/update-profile'] = 'cleaner/update_my_profile';
$route['cleaner/profile-setup-required'] = 'cleaner/profile_setup_required';

// Jobs in Progress routes (Job Completion workflow)
$route['cleaner/jobs-in-progress'] = 'JobCompletion/index';
$route['cleaner/jobs-in-progress/complete/(:num)'] = 'JobCompletion/complete_job/$1';
$route['cleaner/jobs-in-progress/process-completion'] = 'JobCompletion/process_completion';
$route['cleaner/jobs-in-progress/report-inconsistency'] = 'JobCompletion/report_inconsistency';
$route['cleaner/jobs-in-progress/inconsistency-form/(:num)'] = 'JobCompletion/show_inconsistency_form/$1';
$route['cleaner/jobs-in-progress/upload-photos'] = 'JobCompletion/upload_inconsistency_photos';
$route['cleaner/jobs-in-progress/inconsistencies/(:num)'] = 'JobCompletion/get_inconsistencies/$1';


// Counter Offers routes
$route['host/counter-offers'] = 'counteroffers/index';
$route['counter-offers/approve'] = 'counteroffers/approve';
$route['counter-offers/reject'] = 'counteroffers/reject';
$route['counter-offers/escalate'] = 'counteroffers/escalate';
$route['counter-offers/respond'] = 'counteroffers/respond';
$route['counter-offers/resolve'] = 'counteroffers/resolve';
$route['counter-offers/details/(:num)'] = 'counteroffers/get_details/$1';
$route['admin/counter-offers'] = 'counteroffers/moderator_index';
$route['cleaner/price-adjustment-disputes'] = 'counteroffers/cleaner_disputes';

// Disputes routes
$route['host/upcoming-jobs'] = 'host/upcoming_jobs';
$route['host/completed-jobs'] = 'host/completed_jobs';
$route['host/confirm-completion/(:num)'] = 'host/confirm_completion/$1';
$route['host/process_confirm_completion'] = 'host/process_confirm_completion';
$route['host/complete_job'] = 'host/complete_job';
$route['host/recall_job/(:num)'] = 'host/recall_job/$1';
$route['host/process_recall_job'] = 'host/process_recall_job';
$route['host/past-jobs'] = 'host/past_jobs';
$route['host/recalled-jobs'] = 'host/recalled_jobs';
$route['host/payment-history'] = 'host/past_jobs'; // Redirect old route to new one
$route['host/disputes'] = 'disputes/index'; // Keep old route for backward compatibility
$route['host/my-disputed-jobs'] = 'disputes/host_disputes';
$route['disputes/dispute-job'] = 'disputes/dispute_job';
$route['disputes/resolve'] = 'disputes/resolve_dispute';
$route['disputes/resolve-dispute'] = 'disputes/resolve_dispute';
$route['disputes/close-job'] = 'disputes/close_job';

// Job Expiration routes
$route['job-expiration/expire-jobs'] = 'jobexpiration/expire_jobs';
$route['job-expiration/stats'] = 'jobexpiration/stats';
$route['job-expiration/dry-run'] = 'jobexpiration/dry_run';
$route['job-expiration/expire-job/(:num)'] = 'jobexpiration/expire_job/$1';

// Admin Recalled Jobs routes
$route['admin/recalled-jobs'] = 'admin/recalled_jobs';
$route['admin/update_recall_status'] = 'admin/update_recall_status';
$route['admin/settle_recall'] = 'admin/settle_recall';
$route['admin/ban_user'] = 'admin/ban_user';

// Admin Review Management routes
$route['admin/reviews'] = 'admin/reviews';
$route['admin/hide_review'] = 'admin/hide_review';
$route['admin/unhide_review'] = 'admin/unhide_review';
$route['admin/delete_review'] = 'admin/delete_review';
$route['admin/get_review_details'] = 'admin/get_review_details';

// Debug routes (temporary)
$route['debug-recalls/check-host/(:any)'] = 'debug_recalls/check_host/$1';
$route['debug-recalls/fix-job/(:num)'] = 'debug_recalls/fix_job/$1';
$route['disputes/auto-close'] = 'disputes/auto_close_jobs';
$route['disputes/details/(:num)'] = 'disputes/get_details/$1';
$route['disputes/get-dispute-details/(:num)'] = 'disputes/get_dispute_details/$1';
$route['admin/disputes'] = 'disputes/admin_index';
$route['admin/manage-disputes'] = 'disputes/admin_index';
$route['cleaner/my-disputed-jobs'] = 'disputes/cleaner_disputes';

// Notification routes
$route['notifications/get'] = 'notifications/get_notifications';
$route['notifications/mark_read'] = 'notifications/mark_read';
$route['notifications/mark_all_read'] = 'notifications/mark_all_read';
$route['notifications/unread_count'] = 'notifications/get_unread_count';
$route['cleaner/accept_counter_offer/(:num)'] = 'cleaner/accept_counter_offer/$1';
$route['cleaner/reject_counter_offer/(:num)'] = 'cleaner/reject_counter_offer/$1';
$route['cleaner/make_counter_offer/(:num)'] = 'cleaner/make_counter_offer/$1';
$route['cleaner/applications'] = 'cleaner/applications';
$route['cleaner/earnings'] = 'cleaner/earnings';
$route['cleaner/ignore_job'] = 'cleaner/ignore_job';
$route['cleaner/ignored_jobs'] = 'cleaner/ignored_jobs';
$route['cleaner/unignore_job'] = 'cleaner/unignore_job';
$route['cleaner/toggle_favorite'] = 'cleaner/toggle_favorite';

// Test route
$route['test_db'] = 'test_db';

// Admin Job Management routes
$route['admin/jobs'] = 'admin/jobs';
$route['admin/my_jobs'] = 'admin/my_jobs';
$route['admin/create_job'] = 'admin/create_job';
$route['admin/process_create_job'] = 'admin/process_create_job';
$route['admin/view_job/(:num)'] = 'admin/view_job/$1';
$route['admin/edit_job/(:num)'] = 'admin/edit_job/$1';
$route['admin/process_edit_job/(:num)'] = 'admin/process_edit_job/$1';
$route['admin/cancel_job'] = 'admin/cancel_job';
$route['admin/delete_job'] = 'admin/delete_job';

// Flag Management Routes
$route['admin/flags'] = 'admin/flags';
$route['admin/resolve_flag'] = 'admin/resolve_flag';
$route['admin/dismiss_flag'] = 'admin/dismiss_flag';
$route['admin/flag_job'] = 'admin/flag_job';
$route['host/flag_job'] = 'host/flag_job';

// Database setup and debugging routes
$route['database-setup/setup-job-fields'] = 'databasesetup/setup_job_fields';
$route['database-setup/setup-dispute-fields'] = 'databasesetup/setup_dispute_fields';
$route['database-setup/debug-job-start'] = 'databasesetup/debug_job_start';
$route['database-setup/check-logs'] = 'databasesetup/check_logs';
$route['database-setup/debug-dispute-window'] = 'databasesetup/debug_dispute_window';
$route['database-setup/setup-dispute-resolution-fields'] = 'databasesetup/setup_dispute_resolution_fields';
$route['database-setup/setup-expired-status'] = 'databasesetup/setup_expired_status';
$route['database-setup/debug-expire-jobs'] = 'databasesetup/debug_expire_jobs';
$route['database-setup/debug-jobs-fields'] = 'databasesetup/debug_jobs_fields';
$route['database-setup/setup-counter-offer-dispute-fields'] = 'databasesetup/setup_counter_offer_dispute_fields';
$route['database-setup/add-disputed-status-to-counter-offers'] = 'databasesetup/add_disputed_status_to_counter_offers';

// Password change routes
$route['host/change_password'] = 'host/change_password';
$route['host/update_password'] = 'host/update_password';
$route['cleaner/change_password'] = 'cleaner/change_password';
$route['cleaner/update_password'] = 'cleaner/update_password';
$route['cleaner/completed'] = 'cleaner/completed';
$route['cleaner/recalled-jobs'] = 'cleaner/recalled_jobs';

// User Profile routes (accessible to all user levels 3+)
$route['user-profile/change_password'] = 'userprofile/change_password';
$route['user-profile/update_password'] = 'userprofile/update_password';

// Admin Profile Management routes
$route['admin/profiles'] = 'admin/profiles';
$route['admin/profile-statistics'] = 'admin/profile_statistics';
$route['admin/profile/edit/(:num)'] = 'admin/edit_profile/$1';
$route['admin/profile/(:num)'] = 'admin/view_profile/$1';
$route['admin/profile'] = 'admin/my_profile';  // Must come after specific routes
$route['admin/update_profile'] = 'admin/update_profile';
$route['admin/update_verification_status'] = 'admin/update_verification_status';

// Redirect root URL based on authentication
$route['^$'] = 'app/redirect_user';
