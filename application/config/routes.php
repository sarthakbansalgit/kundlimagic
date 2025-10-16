<?php
defined('BASEPATH') || exit('No direct script access allowed');

// Application Routes
$route['default_controller'] = 'home';

// Authentication routes - multiple patterns
$route['login'] = 'auth/login';
$route['auth/login'] = 'auth/login';
$route['register'] = 'auth/register';
$route['auth/register'] = 'auth/register';
$route['logout'] = 'auth/logout';
$route['auth/logout'] = 'auth/logout';
$route['dashboard'] = 'dashboard/index';
$route['dashboard/profile'] = 'dashboard/profile';
$route['dashboard/kundli/(:any)'] = 'dashboard/view_kundli/$1';
$route['dashboard/view_kundli/(:any)'] = 'dashboard/view_kundli/$1';
$route['dashboard/download_pdf/(:any)'] = 'dashboard/download_pdf/$1';

// Frontend routes
$route['contact-us'] = 'frontend/contact';
$route['term-condition'] = 'frontend/terms';
$route['privacy-policy'] = 'frontend/privacy';
$route['refund-policy'] = 'frontend/refund';
// Payment routes - explicit endpoints for redirects
$route['payment/initiate_payment'] = 'payment/initiate_payment';
$route['payment/generateKundli'] = 'payment/initiate_payment';
$route['payment/payment_confirmation'] = 'payment/payment_confirmation';
$route['payment/payment_cancel'] = 'payment/payment_cancel';
$route['payment/payment_failure'] = 'payment/payment_failure';
$route['payment/check_status/(:any)'] = 'payment/check_status/$1';

// PhonePe routes
$route['phonepe/token'] = 'phonepe/token';
$route['phonepe/create_payment'] = 'phonepe/create_payment';
$route['phonepe/order_status'] = 'phonepe/order_status';
$route['phonepe/diagnostic'] = 'phonepe/diagnostic';
$route['about-us'] = 'frontend/about';
$route['services'] = 'frontend/services';
$route['generate-kundli'] = 'frontend/appointment';
$route['downloadpdf'] = 'frontend/downloadpdf';

$route['404_override'] = 'Error404';
$route['translate_uri_dashes'] = false;
