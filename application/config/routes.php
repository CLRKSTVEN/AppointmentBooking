<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller'] = 'staffdirectory';

$route['login']          = 'login';
$route['login/auth']     = 'login/auth';
$route['logout']         = 'login/logout';
$route['dashboard']      = 'login/dashboard';

$route['register']       = 'login/register';        // show registration form
$route['register/save']  = 'login/register_save';  // handle registration POST

$route['directory/profile/(:num)'] = 'staffdirectory/profile/$1';

$route['404_override']        = '';
$route['translate_uri_dashes'] = FALSE;
