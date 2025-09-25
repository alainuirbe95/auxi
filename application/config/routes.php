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
$route['default_controller'] = 'app/login';
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
$route['host/accept_offer/(:num)'] = 'host/accept_offer/$1';
$route['host/reject_offer/(:num)'] = 'host/reject_offer/$1';

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

// Notification routes
$route['notifications/get'] = 'notifications/get_notifications';
$route['notifications/mark_read'] = 'notifications/mark_read';
$route['notifications/mark_all_read'] = 'notifications/mark_all_read';
$route['notifications/unread_count'] = 'notifications/get_unread_count';
$route['cleaner/accept_counter_offer/(:num)'] = 'cleaner/accept_counter_offer/$1';
$route['cleaner/reject_counter_offer/(:num)'] = 'cleaner/reject_counter_offer/$1';
$route['cleaner/make_counter_offer/(:num)'] = 'cleaner/make_counter_offer/$1';
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

// Redirect root URL based on authentication
$route['^$'] = 'app/redirect_user';
