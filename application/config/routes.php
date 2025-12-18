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
$route['dashboard/log/status'] = 'login/update_accomplishment_status';

$route['messages'] = 'messages/index';
$route['messages/send'] = 'messages/send';
$route['messages/thread'] = 'messages/thread';
$route['messages/send_ajax'] = 'messages/send_ajax';
$route['messages/unread_count'] = 'messages/unread_count';
$route['portal/appointments'] = 'portal/appointments';
$route['portal/manage-appointments'] = 'portal/manage_appointments';
$route['portal/reschedule/(:num)'] = 'portal/reschedule/$1';
$route['portal/cancel/(:num)'] = 'portal/cancel/$1';
$route['portal/patient-list'] = 'portal/patient_list';
$route['portal/manage-patients'] = 'portal/manage_patients';
$route['portal/manage-staff'] = 'portal/manage_staff';
$route['portal/doctors'] = 'portal/doctors';
$route['portal/services'] = 'portal/services';
$route['portal/walkins'] = 'portal/walkins';
$route['portal/prescriptions'] = 'portal/prescriptions';
$route['portal/lab-requests'] = 'portal/lab_requests';
$route['portal/medical-records'] = 'portal/medical_records';
$route['portal/consultation-notes'] = 'portal/consultation_notes';
$route['portal/hospital-info'] = 'portal/hospital_info';
$route['portal/reports/appointments'] = 'portal/appointment_reports';
$route['portal/reports/patients'] = 'portal/patient_statistics';
$route['portal/booking-rules'] = 'portal/booking_rules';
$route['portal/roles'] = 'portal/roles';
$route['portal/permissions'] = 'portal/permissions';
$route['portal/departments'] = 'portal/departments';
$route['portal/schedules'] = 'portal/schedules';
$route['portal/invoices'] = 'portal/invoices';
$route['portal/payments'] = 'portal/payments';
$route['portal/my-medical-records'] = 'portal/client_medical_records';
$route['portal/my-prescriptions'] = 'portal/client_prescriptions';
$route['portal/audit-logs'] = 'portal/audit_logs';
$route['portal/billing'] = 'portal/billing';
$route['portal/contact'] = 'portal/contact';
$route['portal/page/(:any)'] = 'portal/page/$1';

$route['register']       = 'login/register';        // show registration form
$route['register/save']  = 'login/register_save';  // handle registration POST

$route['directory/profile/(:num)'] = 'staffdirectory/profile/$1';
$route['login/registration'] = 'login/registration';

$route['404_override']        = '';
$route['translate_uri_dashes'] = FALSE;
