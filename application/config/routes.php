<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Send the homepage to the login screen (staffdirectory controller no longer exists).
$route['default_controller'] = 'login';

$route['login']          = 'login';
$route['login/auth']     = 'login/auth';
$route['logout']         = 'login/logout';
$route['dashboard']      = 'login/dashboard';
$route['dashboard/log']  = 'login/accomplishments';
$route['dashboard/log/save']   = 'login/save_accomplishment';
$route['dashboard/log/update'] = 'login/update_accomplishment';
$route['dashboard/log/delete/(:num)'] = 'login/delete_accomplishment/$1';

$route['register']       = 'login/register';        // show registration form
$route['register/save']  = 'login/register_save';  // handle registration POST

$route['directory/profile/(:num)'] = 'staffdirectory/profile/$1';
$route['login/registration'] = 'login/registration';

$route['404_override']        = '';
$route['translate_uri_dashes'] = FALSE;
