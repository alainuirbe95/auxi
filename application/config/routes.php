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
$route['cleaner/earnings'] = 'cleaner/earnings';

// Test route
$route['test_db'] = 'test_db';

// Redirect root URL based on authentication
$route['^$'] = 'app/redirect_user';
